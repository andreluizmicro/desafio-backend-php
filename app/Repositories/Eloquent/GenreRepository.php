<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Genre as GenreModel;
use App\Presenters\PaginationPresenter;
use Core\Domain\Entity\Genre;
use Core\Domain\Exceptions\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;

class GenreRepository implements GenreRepositoryInterface
{
    public function __construct(private GenreModel $model)
    {
    }

    public function insert(Genre $genre): Genre
    {
        $genreCreated = $this->model->create([
            'id' => $genre->id(),
            'name' => $genre->name,
            'is_active' => $genre->isActive,
            'created_at' => $genre->createdAt,
        ]);

        if (count($genre->categoriesId) > 0) {
            $genreCreated->categories()->sync($genre->categoriesId);
        }

        return $this->toGenre($genreCreated);
    }

    public function findAll(string $filters = '', $order = 'DESC'): array
    {
        $genres = $this->model
            ->where(function ($query) use ($filters) {
                if ($filters) {
                    $query->where('name', 'LIKE', "%{$filters}%");
                }
            })
            ->orderBy('id', $order)
            ->get();

        return $genres->toArray();
    }

    /**
     * @throws NotFoundException
     */
    public function findById(string $id): Genre
    {
        if (! $genreFound = $this->model->find($id)) {
            throw new NotFoundException("Genre with {$id} not found");
        }

        return $this->toGenre($genreFound);
    }

    public function paginate(int $page = 1, int $totalPage = 15, string $filters = '', string $order = 'DESC'): PaginationInterface
    {
        $result = $this->model
            ->where(function ($query) use ($filters) {
                if ($filters) {
                    $query->where('name', 'LIKE', "%{$filters}%");
                }
            })
            ->orderBy('name', $order)
            ->paginate($totalPage);

        return new PaginationPresenter($result);
    }

    /**
     * @throws NotFoundException
     */
    public function update(Genre $genre): Genre
    {
        if (! $genreFound = $this->model->find($genre->id)) {
            throw new NotFoundException("Genre with {$genre->id()} not found");
        }

        $genreFound->update([
            'name' => $genre->name,
        ]);

        if (count($genre->categoriesId) > 0) {
            $genreFound->categories()->sync($genre->categoriesId);
        }

        $genreFound->refresh();

        return $this->toGenre($genreFound);
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $id): bool
    {
        if (! $genreFound = $this->model->find($id)) {
            throw new NotFoundException("Genre with {$id} not found");
        }

        return $genreFound->delete();
    }

    private function toGenre(GenreModel $object): Genre
    {
        return new Genre(
            name: $object->name,
            id: new Uuid($object->id),
            isActive: $object->is_active,
            createdAt: $object->createdAt,
        );
    }
}
