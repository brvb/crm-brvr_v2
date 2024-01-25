<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Customers;
use App\Models\Tenant\TaskServices;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Database\Eloquent\Builder;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TasksTimes extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $fillable = ['service_id','task_id', 'tech_id', 'date_begin', 'hour_begin','date_end','hour_end', 'total_hours','descontos','descricao'];

   
    public function service()
    {
        return $this->hasOne(Services::class, 'id', 'service_id');
    }

    public function tasksReports()
    {
        return $this->hasOne(TasksReports::class,'task_id','task_id')->with('tech')->with('taskCustomer');
    }

    public function task()
    {
        return $this->hasOne(Tasks::class,'id','task_id');
    }

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('task_id');
        });
    }
}
