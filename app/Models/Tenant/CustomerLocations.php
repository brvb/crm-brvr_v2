<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;

class CustomerLocations extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $fillable = ['description','customer_id','main','address','zipcode','contact','district_id','county_id','manager_name','manager_contact'];

    public function locationCounty()
    {
        return $this->hasOne(Counties::class, ['id', 'district_id'], ['county_id', 'district_id']);
    }

    public function locationDistrict()
    {
        return $this->hasOne(Districts::class, 'id', 'district_id');
    }
}
