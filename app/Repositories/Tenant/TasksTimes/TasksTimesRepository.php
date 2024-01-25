<?php

namespace App\Repositories\Tenant\TasksTimes;

use App\Models\Tenant\Services;
use App\Models\Tenant\TasksTimes;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\TaskServices;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\TasksTimes\TasksTimesInterface;
use Illuminate\Database\Eloquent\Collection;

class TasksTimesRepository implements TasksTimesInterface
{

    public function totalHours($task_id): Collection
    {
        $tasksTimes = TasksTimes::where('task_id',$task_id)->with('service')->get();

        return $tasksTimes;
    }

    public function getTasksTimes($task_id,$perPage): LengthAwarePaginator
    {
        $tasksTimes = TasksTimes::where('task_id',$task_id)->with('service')->paginate($perPage);
        //$tasksTimes = TaskServices::where('task_id',$task_id)->with('servicesTasks')->paginate($perPage);
        return $tasksTimes;
    }

    public function getTaskTime($task_id,$searchString,$perPage): LengthAwarePaginator
    {
        $taskTime = TasksTimes::where("task_id",$task_id)->whereHas('service', function ($query) use($searchString) {
                $query->where('name', 'like', '%' . $searchString . '%');
        })
        ->paginate($perPage);

        return $taskTime;
    }

    public function getTotalHoursForTask($task_id): float
    {
        $taskTime = TasksTimes::where('task_id',$task_id)->sum('total_hours');
        return $taskTime;
    }

    public function getAllServices(): Collection
    {
        $services = Services::all();
        return $services;
    }

    public function addTime($arrayOfTimes): TasksTimes
    {
        $taskTime = TasksTimes::create($arrayOfTimes);
        return $taskTime;
    }

    public function deleteTime($taskId): int
    {
        $taskTime = TasksTimes::where('id',$taskId)->delete();

        return $taskTime;
    }

}
