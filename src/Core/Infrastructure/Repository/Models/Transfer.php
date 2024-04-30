<?php

declare(strict_types=1);

namespace Core\Infrastructure\Repository\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'payer_id',
        'payee_id',
        'value',
    ];
}
