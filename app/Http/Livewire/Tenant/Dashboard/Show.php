<?php

namespace App\Http\Livewire\Tenant\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\TasksReports;
use App\Models\Tenant\Departamentos;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;
use App\Interfaces\Tenant\CustomerNotification\CustomerNotificationInterface;

class Show extends Component
{
    
    protected $listeners = ["CalendarNextChanges" => 'CalendarNextChanges', "CalendarPreviousChanges" => 'CalendarPreviousChanges', "checkReport" => 'checkReport'];
    public string $month = '';

    public string $nextMonth = "";
    public string $nextYear = "";

    public int $avancoMes = 0;

    private ?object $teamMembersResponse = NULL;
    private ?object $tasks = NULL;

    private ?array $openTimes = [];

    private ?array $servicesNotifications = [];

    //Parte da segunda tabela

    private ?object $secondTable = NULL;

    //Parte do filtro

    private ?array $infoTeamMember = NULL;

    public $checkboxUser = [];


    protected TeamMemberInterface $TeamMember;
    protected CustomerNotificationInterface $customerNotification;
    protected TasksInterface $taskInterface;

    public function boot(TeamMemberInterface $members, CustomerNotificationInterface $customerNotification, TasksInterface $taskInterface)
    {
        $this->TeamMember = $members;
        $this->customerNotification = $customerNotification;
        $this->taskInterface = $taskInterface;
    }
    public function mount()
    {
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes();

        //PARTE EM TEMPO REAL
       
        $users = User::all();

        $arrayTimes = [];

        $taskTimes = TasksTimes::with('service')->with('task')->orderBy('id','asc')->where('date_end',null)->get();

        foreach($users as $i => $user)
        {
            foreach($taskTimes as $count => $time)
            {
                if($user->id == $time->tech_id)
                {
                    $serviceName = $time->service->name;

                    $taskReference = $time->task->reference;

                    $customer_id = $time->task->customer_id;

                    $customer_name = Customers::where('id',$customer_id)->first();

                    $timeReport = TasksReports::where('task_id',$time->task_id)->first();

                   
                }
            }
        }

        $this->openTimes = $arrayTimes;

        //PARTE SEGUNDA TABELA


        $tM = TeamMember::where('user_id',Auth::user()->id)->first();

        if($tM->id_hierarquia == "1")
        {
            $this->secondTable = Tasks::with('taskReports')
            ->with('tasksTimes')
            ->with('prioridades')
            ->with('servicesToDo')
            ->with('taskCustomer')
            ->with('tech')
            ->orderBy('prioridade','ASC')
            ->orderBy('tech_id','ASC')
            ->orderBy('preview_date','ASC')
            ->get();
        }
        else
        {
            $this->secondTable = Tasks::with('taskReports')
            ->with('tasksTimes')
            ->with('prioridades')
            ->with('servicesToDo')
            ->with('taskCustomer')
            ->with('tech')
            ->where('tech_id',$tM->id)
            ->orderBy('prioridade','ASC')
            ->orderBy('tech_id','ASC')
            ->orderBy('preview_date','ASC')
            ->get();
        }

       


        //PARTE DOS FILTROS

        $teamMember_this = TeamMember::where('user_id',Auth::user()->id)->first();

        $filtros_departamentos = [];

        $departamentos = Departamentos::all();

        $array = [];

        foreach($departamentos as $dept)
        {
            array_push($filtros_departamentos,$dept);
        }



        if($teamMember_this->id_hierarquia == "1"){
                
            $all = TeamMember::where('user_id','!=',Auth::user()->id)->get();

            foreach($filtros_departamentos as $fil)
            {
                $array[$fil->nome_departamento] = [];
                foreach($all as $person)
                {
                    if($person->id_departamento == $fil->id)
                    {
                        array_push($array[$fil->nome_departamento],$person);
                    }
                }
                
            }
        }
        else if($teamMember_this->id_hierarquia == "2"){

            $all = TeamMember::where('id_departamento',$teamMember_this->id_departamento)->where('user_id','!=',Auth::user()->id)->get();

            foreach($filtros_departamentos as $fil)
            {
                if($fil->id == $teamMember_this->id_departamento)
                {
                    $array[$fil->nome_departamento] = [];
                    foreach($all as $person)
                    {
                        if($person->id_departamento == $fil->id)
                        {
                            array_push($array[$fil->nome_departamento],$person);
                        }
                    }
                }
                
            }
        }

        $this->infoTeamMember = $array;


    }

   
    public function searchPeople()
    {
           //PARTE SEGUNDA TABELA
           
           $user_login = TeamMember::where('user_id',Auth::user()->id)->first();
                    
           $tbl = Tasks::with('taskReports')
           ->with('tasksTimes')
           ->with('prioridades')
           ->with('servicesToDo')
           ->with('taskCustomer')
           ->with('tech');
           
          
           if($this->checkboxUser != null)
           {

                if($user_login->id_hierarquia != "1")
                {
                    $tbl->where('tech_id',$user_login->id);
                }
                
                foreach($this->checkboxUser as $user)
                {
                    if($user != false)
                    {
                        $tbl->orWhere('tech_id',$user);
                    }
                    
                }
           }
           else {
                if($user_login->id_hierarquia != "1")
                {
                    $tbl->where('tech_id',$user_login->id);
                }
           }

           $tbl->orderBy('prioridade','ASC');
           $tbl->orderBy('tech_id','ASC');
           $tbl->orderBy('preview_date','ASC');
           $tbl = $tbl->get();


           $this->secondTable = $tbl;


        $this->servicesNotifications = $this->customerNotification->getNotificationTimes();

        //PARTE EM TEMPO REAL
       
        $users = User::all();

        $arrayTimes = [];

        $taskTimes = TasksTimes::with('service')->with('task')->orderBy('id','asc')->where('date_end',null)->get();

        foreach($users as $i => $user)
        {
            foreach($taskTimes as $count => $time)
            {
                if($user->id == $time->tech_id)
                {
                    $serviceName = $time->service->name;

                    $taskReference = $time->task->reference;

                    $customer_id = $time->task->customer_id;

                    $customer_name = Customers::where('id',$customer_id)->first();

                    $timeReport = TasksReports::where('task_id',$time->task_id)->first();

                    $arrayTimes[$user->name] = [
                        "service" => $serviceName,
                        "reference" => $taskReference,
                        "customer" => $customer_name->name,
                        "date_begin" => $time->date_begin,
                        "hour_begin" => $time->hour_begin,
                        "task_id" => $timeReport->id,
                        "tech" => $time->tech_id
                    ];
                }
            }
        }

        $this->openTimes = $arrayTimes;

        //PARTE DOS FILTROS

        $teamMember_this = TeamMember::where('user_id',Auth::user()->id)->first();

        $filtros_departamentos = [];

        $departamentos = Departamentos::all();

        $array = [];

        foreach($departamentos as $dept)
        {
            array_push($filtros_departamentos,$dept);
        }



        if($teamMember_this->id_hierarquia == "1"){
                
            $all = TeamMember::where('user_id','!=',Auth::user()->id)->get();

            foreach($filtros_departamentos as $fil)
            {
                $array[$fil->nome_departamento] = [];
                foreach($all as $person)
                {
                    if($person->id_departamento == $fil->id)
                    {
                        array_push($array[$fil->nome_departamento],$person);
                    }
                }
                
            }
        }
        else if($teamMember_this->id_hierarquia == "2"){

            $all = TeamMember::where('id_departamento',$teamMember_this->id_departamento)->where('user_id','!=',Auth::user()->id)->get();

            foreach($filtros_departamentos as $fil)
            {
                if($fil->id == $teamMember_this->id_departamento)
                {
                    $array[$fil->nome_departamento] = [];
                    foreach($all as $person)
                    {
                        if($person->id_departamento == $fil->id)
                        {
                            array_push($array[$fil->nome_departamento],$person);
                        }
                    }
                }
                
            }
        }

        $this->infoTeamMember = $array;

          $this->render();


    }


  
    public function checkReport($task)
    {
        $report = TasksReports::where('task_id',$task)->first();

        if($report != null){
            $this->dispatchBrowserEvent("responseReport",["response" => "existe", "value" => $report->id]);
        }
        else {
            $this->dispatchBrowserEvent("responseReport",["response" => "naoexiste", "value" => $task]);
        }

        $this->skipRender();
    }

    public function treated($id)
    {
        $this->customerNotification->changeTreatedStatus($id);
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes();
        $this->teamMembersResponse = $this->TeamMember->getAllTeamMembers(0);

        $this->tasks = $this->taskInterface->taskCalendar();

       
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes();

        //PARTE EM TEMPO REAL
       
        $users = User::all();

        $arrayTimes = [];

        $taskTimes = TasksTimes::with('service')->with('task')->orderBy('id','asc')->where('date_end',null)->get();

        foreach($users as $i => $user)
        {
            foreach($taskTimes as $count => $time)
            {
                if($user->id == $time->tech_id)
                {
                    $serviceName = $time->service->name;

                    $taskReference = $time->task->reference;

                    $customer_id = $time->task->customer_id;

                    $customer_name = Customers::where('id',$customer_id)->first();

                    $timeReport = TasksReports::where('task_id',$time->task_id)->first();

                    $arrayTimes[$user->name] = [
                        "service" => $serviceName,
                        "reference" => $taskReference,
                        "customer" => $customer_name->name,
                        "date_begin" => $time->date_begin,
                        "hour_begin" => $time->hour_begin,
                        "task_id" => $timeReport->id,
                        "tech" => $time->tech_id
                    ];
                }
            }
        }

        $this->openTimes = $arrayTimes;

        //PARTE SEGUNDA TABELA


        $tM = TeamMember::where('user_id',Auth::user()->id)->first();

        if($tM->id_hierarquia == "1")
        {
            $this->secondTable = Tasks::with('taskReports')
            ->with('tasksTimes')
            ->with('prioridades')
            ->with('servicesToDo')
            ->with('taskCustomer')
            ->with('tech')
            ->orderBy('prioridade','ASC')
            ->orderBy('tech_id','ASC')
            ->orderBy('preview_date','ASC')
            ->get();
        }
        else
        {
            $this->secondTable = Tasks::with('taskReports')
            ->with('tasksTimes')
            ->with('prioridades')
            ->with('servicesToDo')
            ->with('taskCustomer')
            ->with('tech')
            ->where('tech_id',$tM->id)
            ->orderBy('prioridade','ASC')
            ->orderBy('tech_id','ASC')
            ->orderBy('preview_date','ASC')
            ->get();
        }

       


        //PARTE DOS FILTROS

        $teamMember_this = TeamMember::where('user_id',Auth::user()->id)->first();

        $filtros_departamentos = [];

        $departamentos = Departamentos::all();

        $array = [];

        foreach($departamentos as $dept)
        {
            array_push($filtros_departamentos,$dept);
        }



        if($teamMember_this->id_hierarquia == "1"){
                
            $all = TeamMember::where('user_id','!=',Auth::user()->id)->get();

            foreach($filtros_departamentos as $fil)
            {
                $array[$fil->nome_departamento] = [];
                foreach($all as $person)
                {
                    if($person->id_departamento == $fil->id)
                    {
                        array_push($array[$fil->nome_departamento],$person);
                    }
                }
                
            }
        }
        else if($teamMember_this->id_hierarquia == "2"){

            $all = TeamMember::where('id_departamento',$teamMember_this->id_departamento)->where('user_id','!=',Auth::user()->id)->get();

            foreach($filtros_departamentos as $fil)
            {
                if($fil->id == $teamMember_this->id_departamento)
                {
                    $array[$fil->nome_departamento] = [];
                    foreach($all as $person)
                    {
                        if($person->id_departamento == $fil->id)
                        {
                            array_push($array[$fil->nome_departamento],$person);
                        }
                    }
                }
                
            }
        }

        $this->infoTeamMember = $array;
    }

    public function render()
    {
        // dd($this->arrayUserChecked);
        $perPage = 0;
        $this->teamMembersResponse = $this->TeamMember->getAllTeamMembers($perPage);

        return view('tenant.livewire.dashboard.show',['teamMembers' => $this->teamMembersResponse,'tasks' => $this->tasks ,'servicesNotifications' => $this->servicesNotifications, 'openTimes' => $this->openTimes, 'secondTable' => $this->secondTable, 'infoTeamMember' => $this->infoTeamMember]);
    }
}
