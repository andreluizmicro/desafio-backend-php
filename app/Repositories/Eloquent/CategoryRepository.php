<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Category as CategoryModel;
use App\Presenters\PaginationPresenter;
use Core\Domain\Entity\Category;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private CategoryModel $model)
    {
    }

    public function insert(Category $category): Category
    {
        $category = $this->model->create([
            'id' => $category->id(),
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
            'created_at' => $category->createdAt(),
        ]);

        return $this->toCategory($category);

    }

    public function findAll(string $filters = '', $order = 'DESC'): array
    {
        $categories = $this->model
            ->where(function ($query) use ($filters) {
                if ($filters) {
                    $query->where('name', 'LIKE', "%{$filters}%");
                }
            })
            ->orderBy('id', $order)
            ->get();

        return $categories->toArray();
    }

    /**
     * @throws NotFoundException
     */
    public function findById(string $id): Category
    {
        if (! $category = $this->model->find($id)) {
            throw new NotFoundException('Category not found');
        }

        return $this->toCategory($category);
    }

    public function getIdsByListIds(array $categoriesId = []): array
    {
        return $this->model
            ->whereIn('id', $categoriesId)
            ->pluck('id')
            ->toArray();
    }

    public function paginate(int $page = 1, int $totalPage = 15, string $filters = '', string $order = 'DESC'): PaginationInterface
    {
        $query = $this->model;
        if ($filters) {
            $query->where('name', 'LIKE', "%{$filters}%");
        }
        $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginationPresenter($paginator);
    }

    /**
     * @throws NotFoundException
     */
    public function update(Category $category): Category
    {
        if (! $categoryFound = $this->model->find($category->id())) {
            throw new NotFoundException(
                sprintf('Category %s not found', $category->id())
            );
        }

        $categoryFound->update($category->toArray());
        $categoryFound->refresh();

        return $this->toCategory($categoryFound);
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $id): bool
    {
        if (! $categoryFound = $this->model->find($id)) {
            throw new NotFoundException(
                sprintf('Category with id %s not found', $id)
            );
        }

        return $categoryFound->delete();
    }

    private function toCategory(CategoryModel $data): Category
    {
        $category = new Category(
            name: $data->name,
            id: new Uuid($data->id),
            description: $data->description,
        );

        (($data->is_active)) ? $category->activate() : $category->deactivate();

        return $category;
    }
}
