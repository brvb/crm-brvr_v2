<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContacts extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'location_id', 'name', 'job_description', 'mobile_phone', 'landline', 'email'];

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('name');
        });
    }

    public function location()
    {
        return $this->belongsTo(CustomerLocations::class, 'location_id', 'id');
    }

}
