<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerServices extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $fillable = ['customer_id', 'service_id', 'location_id', 'start_date', 'end_date', 'type','alert','selectedTypeContract','number_times','allMails','new_date','time_repeat','member_associated'];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id', 'id');
    }

    public function customerLocation()
    {
        return $this->belongsTo(CustomerLocations::class,'location_id','id')->with('locationDistrict');
    }

}
