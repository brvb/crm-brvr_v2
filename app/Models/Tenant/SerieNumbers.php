<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerieNumbers extends Model
{
    use HasFactory;

    protected $table = 'serie_numbers';

    protected $fillable = ['nr_serie','marca','modelo'];

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('nr_serie');
        });
    }

}
