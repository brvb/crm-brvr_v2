<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosPHC extends Model
{
    use HasFactory;

    protected $table = 'produtos_phc';
    protected $fillable = ['reference','description', 'service','price','barcode', 'created_at', 'updated_at'];

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('reference');
        });
    }

}
