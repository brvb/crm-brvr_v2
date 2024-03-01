<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampsClientes extends Model
{
    use HasFactory;

    protected $table = 'stamp_cliente';
    protected $fillable = ['stamp','nome_cliente', 'created_at', 'updated_at'];

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('stamp');
        });
    }

}
