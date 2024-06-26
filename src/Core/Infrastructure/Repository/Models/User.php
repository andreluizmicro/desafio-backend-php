<?php

declare(strict_types=1);

namespace Core\Infrastructure\Repository\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'email',
        'cpf',
        'cnpj',
        'password',
        'user_type_id',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}
