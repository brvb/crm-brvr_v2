<?php

namespace App\Http\Livewire\Tenant\Dashboard;

use DB;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Events\ChatMessage;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Pedidos;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\Intervencoes;
use App\Models\Tenant\TasksReports;
use App\Models\Tenant\Departamentos;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;
use App\Interfaces\Tenant\CustomerNotification\CustomerNotificationInterface;
use App\Models\Tenant\StampsClientes;

class Show extends Component
{
    
    protected $listeners = ["checkStatePedido" => 'checkStatePedido','intervencaoCheck' => 'intervencaoCheck'];
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

    protected object $customersRepository;


    protected TeamMemberInterface $TeamMember;
    protected CustomerNotificationInterface $customerNotification;
    protected TasksInterface $taskInterface;
    protected CustomerLocationsInterface $customerLocationInterface;

    public function boot(TeamMemberInterface $members, CustomerNotificationInterface $customerNotification, TasksInterface $taskInterface,CustomersInterface $interfaceCustomers,CustomerLocationsInterface $customerLocationInterface)
    {
        $this->TeamMember = $members;
        $this->customerNotification = $customerNotification;
        $this->taskInterface = $taskInterface;
        $this->customersRepository = $interfaceCustomers;
        $this->customerLocationInterface = $customerLocationInterface;
    }
    public function mount()
    {

        $this->servicesNotifications = $this->customerNotification->getNotificationTimes($this->customersRepository,$this->customerLocationInterface);

        //PARTE EM TEMPO REAL
      
        $users = User::where('type_user','!=','2')->get();
        $arrayUser = [];

        foreach($users as $us)
        {
            $arrayUser[$us->name] = [];

            $intervencao = Intervencoes::where('user_id',$us->id)->where('hora_final',null)->get();
       
            
            if(!empty($intervencao)) {

                foreach($intervencao as $int)
                {
                    $pedidoInfo = Pedidos::where('id',$int->id_pedido)->with('customer')->first();
                   
                     $customer = $this->customersRepository->getSpecificCustomerInfo($pedidoInfo->customer_id);
                    
                        $arrayUser[$us->name][$int->id_pedido] = [];
                    
                        $arrayUser[$us->name][$int->id_pedido] = ["cliente" =>$customer->customers->name,"idpedido" => $pedidoInfo->id,"reference" => $pedidoInfo->reference,"tecnico" => $us->id, "data" => $int->created_at];
                                    
                    
                   
                }
             
            }

         
        }

             

        $this->openTimes = $arrayUser;
        
        
        //TABELA DE PEDIDOS      

        $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
        
        if(Auth::user()->type_user == "0")
        {
            $this->secondTable = Pedidos::where('estado','!=','5')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
        }
        else {
            $this->secondTable = Pedidos::where('tech_id',$teammember->id)
            ->where('estado','!=','5')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
    
        }
      
    }

    public function initializeArrays()
    {
        
        $users = User::where('type_user','!=','2')->get();
        $arrayUser = [];

        foreach($users as $us)
        {
            $arrayUser[$us->name] = [];

            $intervencao = Intervencoes::where('user_id',$us->id)->where('hora_final',null)->get();
       
            
            if(!empty($intervencao)) {

                foreach($intervencao as $int)
                {
                    $pedidoInfo = Pedidos::where('id',$int->id_pedido)->with('customer')->first();
                    $customer = $this->customersRepository->getSpecificCustomerInfo($pedidoInfo->customer_id);
                    
                        $arrayUser[$us->name][$int->id_pedido] = [];
                    
                        $arrayUser[$us->name][$int->id_pedido] = ["cliente" =>$customer->customers->name,"idpedido" => $pedidoInfo->id,"reference" => $pedidoInfo->reference,"tecnico" => $us->id, "data" => $int->created_at];
                                    
                    
                   
                }
             
            }

         
        }
        

        $this->openTimes = $arrayUser;
        
        
        //TABELA DE PEDIDOS      

        $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
        
        if(Auth::user()->type_user == "0")
        {
            $this->secondTable = Pedidos::where('estado','!=','5')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
        }
        else {
            $this->secondTable = Pedidos::where('tech_id',$teammember->id)
            ->where('estado','!=','5')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
    
        }
    }

    public function intervencaoCheck($idPedido)
    {
        $pedido = Pedidos::where('id',$idPedido)->first();

        $checkIntervencaoUser = Intervencoes::where('id_pedido',$idPedido)->where('user_id',Auth::user()->id)->where("data_final",'!=',"")->latest()->first();


        if($pedido->estado != "7")
        {
         
            // if($checkIntervencaoUser != null)
            // {
            //     return redirect()->route('tenant.tasks-reports.edit',["tasks_report" => $idPedido]);
            // }

            $data_agendamento = $pedido->data_agendamento;
            $hora_agendamento = $pedido->hora_agendamento;

            if($pedido->data_agendamento == null)
            {
                $data_agendamento = date('Y-m-d');
            }

            if($pedido->hora_agendamento == null)
            {
               $hora_agendamento = date('H:i:s');
            }

            Pedidos::where('id',$idPedido)->update([
                "data_agendamento" => $data_agendamento,
                "hora_agendamento" => $hora_agendamento
            ]);
            
            
           Intervencoes::create([
                "id_pedido" => $idPedido,
                "estado_pedido" => 7,
                "anexos" => "[]",
                "user_id" => Auth::user()->id,
                "data_inicio" => date('Y-m-d'),
                "hora_inicio" => date('H:i:s')
            ]);

            $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
            $pedido = Pedidos::where('tech_id',$teammember->id)->where('id',$idPedido)->first();

            if(isset($pedido->tech_id))
            {
                if($teammember->id == $pedido->tech_id)
                {
                    Pedidos::where('id',$idPedido)->update([
                        "estado" => 7
                    ]);
                }
            }
           


        } 
        else
        {
           
            //verifico se ja tem alguma intervencao
            $checkIntervencoes = Intervencoes::where('id_pedido',$idPedido)->where('user_id',Auth::user()->id)->latest()->first();


            if($checkIntervencoes != null)
            {
                return redirect()->route('tenant.tasks-reports.edit',["tasks_report" => $idPedido]);
            }
            else
            {
    
                $data_agendamento = $pedido->data_agendamento;
                $hora_agendamento = $pedido->hora_agendamento;
    
                if($pedido->data_agendamento == null)
                {
                    $data_agendamento = date('Y-m-d');
                }
    
                if($pedido->hora_agendamento == null)
                {
                   $hora_agendamento = date('H:i:s');
                }
    
                Pedidos::where('id',$idPedido)->update([
                    "data_agendamento" => $data_agendamento,
                    "hora_agendamento" => $hora_agendamento
                ]);
                
                
               Intervencoes::create([
                    "id_pedido" => $idPedido,
                    "estado_pedido" => 7,
                    "anexos" => "[]",
                    "user_id" => Auth::user()->id,
                    "data_inicio" => date('Y-m-d'),
                    "hora_inicio" => date('H:i:s')
                ]);

                $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
                $pedido = Pedidos::where('tech_id',$teammember->id)->where('id',$idPedido)->first();
    
                if($teammember->id == $pedido->tech_id)
                {
                    Pedidos::where('id',$idPedido)->update([
                        "estado" => 7
                    ]);
                }
    
            }
          

        }

        $usr = User::where('id',Auth::user()->id)->first();
        $pedido = Pedidos::where('id',$idPedido)->first();

        $usrRecebido = User::where('id',$pedido->user_id)->first();

       
        $message = "adicionou uma intervenção";
    

        event(new ChatMessage(Auth::user()->name, $message));

        return redirect()->route('tenant.dashboard');
        //$this->initializeArrays();
    }


    public function checkStatePedido($id)
    {
        $idPedido = $id;

        $pedido = Pedidos::where('id',$idPedido)->with('customer')->first();

        $intervencoes = Intervencoes::where('id_pedido',$pedido->id)->where('user_id',Auth::user()->id)->latest()->first();

        $resposta = "";


        if(empty($intervencoes))
        {
            $resposta = "abrir";
        } else {

            if($intervencoes->hora_final == "") {
                if($pedido->estado == 2)
                {
                    $resposta = "concluida";
                }
                else {
                    $resposta = "fechar";
                }
                
            } else {
                if($pedido->estado == 2)
                {
                    $resposta = "concluida";
                }
                else {
                    $resposta = "abrir";
                }
               
            }
        }

      
        $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
        
        if(Auth::user()->type_user == "0")
        {
            $this->secondTable = Pedidos::where('estado','!=','5')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
        }
        else {
            $this->secondTable = Pedidos::where('tech_id',$teammember->id)
            ->where('estado','!=','5')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
    
        }
      

        
        $users = User::where('type_user','!=','2')->get();
        $arrayUser = [];

        foreach($users as $us)
        {
            $arrayUser[$us->name] = [];

            $intervencao = Intervencoes::where('user_id',$us->id)->where('hora_final',null)->get();
       
            
            if($intervencao->count() > 0) {

                foreach($intervencao as $int)
                {
                    $pedidoInfo = Pedidos::where('id',$int->id_pedido)->with('customer')->first();
                                       
                    $customer = $this->customersRepository->getSpecificCustomerInfo($pedidoInfo->customer_id);
                    
                    $arrayUser[$us->name][$int->id_pedido] = [];
                
                    $arrayUser[$us->name][$int->id_pedido] = ["cliente" =>$customer->customers->name,"idpedido" => $pedidoInfo->id,"reference" => $pedidoInfo->reference,"tecnico" => $us->id, "data" => $int->created_at];
                    
                   
                }
            } else {
                $customer = $this->customersRepository->getSpecificCustomerInfo($pedido->customer_id);
            }

         
        }
        

        $this->openTimes = $arrayUser;


        $this->dispatchBrowserEvent('interventionCheck',["parameter" => $resposta,"idPedido" => $idPedido, "reference" => $pedido->reference, "cliente" => $customer->customers->name]);


    }

   

    public function treated($id)
    {
        $this->customerNotification->changeTreatedStatus($id);
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes($this->customersRepository,$this->customerLocationInterface);
        $this->teamMembersResponse = $this->TeamMember->getAllTeamMembers(0);

       
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes($this->customersRepository,$this->customerLocationInterface);

        //PARTE EM TEMPO REAL
       
        $users = User::where('type_user','!=','2')->get();
        $arrayUser = [];

        foreach($users as $us)
        {
            $arrayUser[$us->name] = [];

            $intervencao = Intervencoes::where('user_id',$us->id)->where('hora_final',null)->get();
       
            
            if(!empty($intervencao)) {

                foreach($intervencao as $int)
                {
                    $pedidoInfo = Pedidos::where('id',$int->id_pedido)->with('customer')->first();
                   
                   
                    
                    $customer = $this->customersRepository->getSpecificCustomerInfo($pedidoInfo->customer_id);
                    
                    $arrayUser[$us->name][$int->id_pedido] = [];
                
                    $arrayUser[$us->name][$int->id_pedido] = ["cliente" =>$customer->customers->name,"idpedido" => $pedidoInfo->id,"reference" => $pedidoInfo->reference,"tecnico" => $us->id, "data" => $int->created_at];
                                    
                    
                   
                }
             
            }

         
        }
        

        $this->openTimes = $arrayUser;

        //PARTE SEGUNDA TABELA


        $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
        
        if(Auth::user()->type_user == "0")
        {
            $this->secondTable = Pedidos::where('estado','!=','5')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
        }
        else {
            $this->secondTable = Pedidos::where('tech_id',$teammember->id)
            ->where('estado','!=','5')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
    
        }
      
       
    }

    public function render()
    {

        return view('tenant.livewire.dashboard.show',['servicesNotifications' => $this->servicesNotifications, 'openTimes' => $this->openTimes, 'pedidos' => $this->secondTable, 'customersRepository' => $this->customersRepository, 'customerLocationInterface' => $this->customerLocationInterface]);
    }
}
