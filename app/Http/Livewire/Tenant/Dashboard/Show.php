<?php

namespace App\Http\Livewire\Tenant\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Pedidos;
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
use App\Models\Tenant\Intervencoes;

class Show extends Component
{
    
    protected $listeners = ["checkStatePedido" => 'checkStatePedido'];
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
       
      
        $users = User::where('type_user','!=','2')->get();
        $arrayUser = [];

        foreach($users as $us)
        {
            
            $intervencao = Intervencoes::where('user_id',$us->id)->latest()->orderBy('created_at','asc')->first();

            if(!empty($intervencao)) {
                $pedidoInfo = Pedidos::where('id',$intervencao->id_pedido)->with('customer')->first();
    
                if($pedidoInfo->estado == "1")
                {
                    $arrayUser[$us->name] = [];
                    $arrayUser[$us->name] = ["cliente" =>$pedidoInfo->customer->name,"reference" => $pedidoInfo->reference,"data" => $pedidoInfo->created_at];
                }
            }

         
        }
        

        $this->openTimes = $arrayUser;
        
        
        //TABELA DE PEDIDOS      

        $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
        
        $this->secondTable = Pedidos::where('tech_id',$teammember->id)
            ->where('estado','!=','2')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
    


    }

    public function checkStatePedido($id)
    {
        $idPedido = $id;

        $pedido = Pedidos::where('id',$idPedido)->with('customer')->first();

        $intervencoes = Intervencoes::where('id_pedido',$pedido->id)->where('user_id',Auth::user()->id)->latest()->orderBy('created_at','asc')->first();

        $resposta = "";


        if(empty($intervencoes))
        {
            $resposta = "abrir";
        } else {

            if($intervencoes->estado_pedido == "1") {
                $resposta = "fechar";
            } else {
                $resposta = "abrir";
            }
        }

      
        $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
        
        $this->secondTable = Pedidos::where('tech_id',$teammember->id)
            ->where('estado','!=','2')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();

        
        $users = User::where('type_user','!=','2')->get();
        $arrayUser = [];

        foreach($users as $us)
        {
            
            $intervencao = Intervencoes::where('user_id',$us->id)->latest()->orderBy('created_at','asc')->first();

            if(!empty($intervencao)) {
                $pedidoInfo = Pedidos::where('id',$intervencao->id_pedido)->with('customer')->first();
    
                if($pedidoInfo->estado == "1")
                {
                    $arrayUser[$us->name] = [];
                    $arrayUser[$us->name] = ["cliente" =>$pedidoInfo->customer->name,"reference" => $pedidoInfo->reference,"data" => $pedidoInfo->created_at];
                }
            }

            
        }
        

        $this->openTimes = $arrayUser;


        $this->dispatchBrowserEvent('interventionCheck',["parameter" => $resposta,"idPedido" => $idPedido, "reference" => $pedido->reference, "cliente" => $pedido->customer->name]);


    }

   

    public function treated($id)
    {
        $this->customerNotification->changeTreatedStatus($id);
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes();
        $this->teamMembersResponse = $this->TeamMember->getAllTeamMembers(0);

       
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes();

        //PARTE EM TEMPO REAL
       
        $users = User::where('type_user','!=','2')->get();
        $arrayUser = [];

        foreach($users as $us)
        {
            
            $intervencao = Intervencoes::where('user_id',$us->id)->latest()->orderBy('created_at','asc')->first();

            if(!empty($intervencao)) {
                $pedidoInfo = Pedidos::where('id',$intervencao->id_pedido)->with('customer')->first();
    
                if($pedidoInfo->estado == "1")
                {
                    $arrayUser[$us->name] = [];
                    $arrayUser[$us->name] = ["cliente" =>$pedidoInfo->customer->name,"reference" => $pedidoInfo->reference,"data" => $pedidoInfo->created_at];
                }
            }

         
        }
        

        $this->openTimes = $arrayUser;

        //PARTE SEGUNDA TABELA


        $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
        
        $this->secondTable = Pedidos::where('tech_id',$teammember->id)
            ->where('estado','!=','2')
            ->with('prioridadeStat')
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('prioridade','asc')
            ->get();
       
    }

    public function render()
    {

        return view('tenant.livewire.dashboard.show',['servicesNotifications' => $this->servicesNotifications, 'openTimes' => $this->openTimes, 'pedidos' => $this->secondTable]);
    }
}
