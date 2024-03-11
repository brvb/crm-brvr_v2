<?php

namespace App\Http\Controllers\Tenant\Tasks;

use Illuminate\View\View;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Pedidos;

use App\Models\Tenant\Customers;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\Intervencoes;
use App\Models\Tenant\TasksReports;
use App\Http\Controllers\Controller;
use App\Models\Tenant\CustomerServices;
use Illuminate\Support\Facades\Redirect;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\TasksReports\TasksReportsInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;
use App\Models\Tenant\StampsClientes;

class TasksController extends Controller
{
    private TasksInterface $tasksInterface;
    private CustomersInterface $customersRepository;
    private CustomerServicesInterface $customerServicesRepository;

    public function __construct(TasksInterface $tasksInterface, TasksReportsInterface $tasksReportsInterface,CustomersInterface $customersInterface,CustomerServicesInterface $customersServicesRepository)
    {
        $this->tasksInterface = $tasksInterface;
        $this->tasksReportsInterface = $tasksReportsInterface;
        $this->customersRepository = $customersInterface;
        $this->customerServicesRepository = $customersServicesRepository;
    }

    /**
     * Display the customers list in livewire.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('tenant.tasks.index', [
            //'themeAction' => 'table_datatable_basic',
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
        // $customerList = $this->customerServicesRepository->getAllCustomers();

        return view('tenant.tasks.create', [
            'themeAction' => 'form_element_data_table',
        ]);
    }

    /**
     * Edit customer task in livewire
     *
     * @param Tasks $task
     * @return Redirect|View
     */
    public function edit(Pedidos $task): Redirect|View
    {
        // $taskReport = $this->tasksReportsInterface->getReportByTaskId($task->id);
        // if(isset($taskReport) && $taskReport->reportStatus > 0 ) {
        //     return redirect()->route('tenant.tasks.index')
        //         ->with('message', __('You cannot update a task with an ongoing report!'))
        //         ->with('status', 'error');
        // }

        return view('tenant.tasks.edit', [
            'themeAction' => 'form_element_data_table',
            'taskToUpdate' => $this->tasksInterface->getTask($task),
            'teamMembers' => TeamMember::get(),
        ]);
    }

    /**
     * store function maybe to redirect and display error message or just do noothing
     *
     * @return Redirect
     */
    public function store(): Redirect
    {
        return redirect()->route('tenant.tasks.index')
            ->with('message', __('Operation not permited!'))
            ->with('status', 'error');
    }

    /**
     * store function maybe to redirect and display error message or just do noothing
     *
     * @return Redirect
     */
    public function update(): Redirect
    {
        return redirect()->route('tenant.tasks.index')
            ->with('message', __('Operation not permited!'))
            ->with('status', 'error');
    }

    /**
     * Delete task
     *
     * @param Tasks $task
     * @return void
     */
    public function destroy(Pedidos $task)
    {
      
        Pedidos::where('id',$task->id)->delete();

        Intervencoes::where('id_pedido',$task->id)->delete();
    
        return to_route('tenant.tasks.index')
            ->with('message', "Pedido eliminado com sucesso!")
            ->with('status', 'sucess');
    }

    public function destroyTask(string $task)
    {
        $taskNumber = (int) $task;

        Tasks::where('id',$taskNumber)->delete();

        TasksReports::where('task_id',$taskNumber)->delete();

        TasksTimes::where('task_id',$taskNumber)->delete();

        return to_route('login')
            ->with('message', "Pedido eliminado com sucesso!")
            ->with('status', 'sucess');
    }

}
