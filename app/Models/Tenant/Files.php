<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;

class Files extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $fillable = ['file', 'user_id','permission','customer_id'];

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('created_at');
        });
    }

}
