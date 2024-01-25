<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskServices extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'task_service_id', 'service_id', 'additional_description'];

    public function service(){
        return $this->hasOne(Services::class, 'id', 'service_id');
    }

    public function servicesTasks() {
        return $this->hasOne(TasksTimes::class, 'service_id','service_id')->with('service');
    }


    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('task_service_id');
        });
    }
}
