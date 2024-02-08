<?php

namespace App\Http\Controllers\Tenant\Tasks;

use Illuminate\View\View;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Customers;

use App\Models\Tenant\TasksTimes;
use App\Mail\Tasks\TaskDispatched;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Events\Tasks\DispatchTasksToUser;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Listeners\Tasks\SendDispatchTasksNotification;
use App\Interfaces\Tenant\TasksReports\TasksReportsInterface;
use App\Interfaces\Tenant\TasksTimes\TasksTimesInterface;

class TasksReportsController extends Controller
{
    private TasksReportsInterface $tasksReportsInterface;
    private TasksInterface $tasksInterface;
    private TasksTimesInterface $tasksTimesInterface;

    public function __construct(TasksReportsInterface $tasksReportsInterface, TasksInterface $tasksInterface, TasksTimesInterface $tasksTimesInterface )
    {
        $this->tasksReportsInterface = $tasksReportsInterface;
        $this->tasksInterface = $tasksInterface;
        $this->tasksTimesInterface = $tasksTimesInterface;
    }

    /**
     * Display the customers list in livewire.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {

        return view('tenant.tasksreports.index', [
            'themeAction' => 'form_element_data_table',
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

    /**
     * Create new customer task in livewire
     *
     * @return View
     */
    public function create(): View
    {
        return view('tenant.tasks.create', [
            'themeAction' => 'form_element_data_table',
            'customerList' => Customers::all(),
        ]);
    }

    /**
     * Edit customer task in livewire
     *
     * @param Tasks $task
     * @return View
     */
    public function edit(int $taskReport): View
    {
        $task = $this->tasksInterface->getTaskById($taskReport);
        return view('tenant.tasksreports.edit', [
            'themeAction' => 'form_element_data_table',
            'task' => $task,
        ]);
    }

    /**
     * store function maybe to redirect and display error message or just do noothing
     *
     * @return void
     */
    public function store(): void
    {

        // param: CustomersFormRequest $request
        // nothing to do here, everything is done thru livewire
        // $this->tasksInterface->add($request);
    }

    /**
     * store function maybe to redirect and display error message or just do noothing
     *
     * @return void
     */
    public function update()
    {
        // param: Customers $customers, CustomersFormRequest $request
        // nothing to do here, everything is done thru livewire
        // $this->tasksInterface->add($request);
    }

    public function destroy(Tasks $task)
    {
        $task->delete();
        return to_route('tenant.tasks.index')
            ->with('message', __('Task deleted with success!'))
            ->with('status', 'sucess');
    }

    public function destroyTimeTask(int $taskTime)
    {
        $this->tasksTimesInterface->deleteTime($taskTime);
        return to_route('tenant.tasks-reports.index')
            ->with('message', __('Task Time deleted with success!'))
            ->with('status', 'sucess');
    }

}
