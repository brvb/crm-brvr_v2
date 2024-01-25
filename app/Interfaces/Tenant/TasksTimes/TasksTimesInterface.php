<?php

namespace App\Interfaces\Tenant\TasksTimes;

use Livewire\Request;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Services;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TasksReports;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tenant\Tasks\TasksFormRequest;
use Illuminate\Database\Eloquent\Collection;

interface TasksTimesInterface
{
    public function totalHours($task_id): Collection;
   
    public function getTasksTimes($task_id,$perPage): LengthAwarePaginator;

    public function getTaskTime($task_id,$searchString,$perPage): LengthAwarePaginator;

    public function getTotalHoursForTask($task_id): float;

    public function getAllServices(): Collection;

    public function addTime($arrayOfTimes): TasksTimes;

    public function deleteTime($taskId): int;

}
