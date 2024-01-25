<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerNotifications extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $fillable = ['service_id', 'end_service_date', 'customer_id', 'location_id', 'notification_day', 'treated','customer_service_id'];

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
