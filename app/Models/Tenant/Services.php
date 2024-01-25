<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'type','file', 'payment', 'periodicity', 'alert'];

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('name');
        });
    }

    public function serviceType()
    {
        return $this->belongsTo(CustomTypes::class, 'type', 'id');
    }

    public function paymentType()
    {
        return $this->belongsTo(CustomTypes::class, 'payment', 'id');
    }
}
