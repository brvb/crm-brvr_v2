<?php

namespace App\Interfaces\Tenant\TasksReports;

use Livewire\Request;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\TasksReports;
use App\Http\Requests\Tenant\Tasks\TasksFormRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface TasksReportsInterface
{

    public function getTasksReports($perPage):LengthAwarePaginator;

    public function getTaskReport($searchString,$perPage): LengthAwarePaginator;

    public function getReport($taskReportId): TasksReports;

    public function updateReport($reportId,$taskReport): int;

    public function getReportByTaskId($taskId): NULL|TasksReports;

    public function destroyReportByTaskId($taskId): bool;

    public function updateTaskReport($task): int;


    /**Parte do filtro */
    public function getTasksReportsFilter(string $searchString,int $tech,int $client,int $typeReport, int $work, string $ordenation, string $dateBegin, string $dateEnd, $perPage): LengthAwarePaginator;

    /** */

}
