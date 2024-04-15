<?php

namespace App\Http\Livewire\Tenant\AlertMessages;

use App\Interfaces\Tenant\AlertMessage\AlertMessageInterface;
use Livewire\Component;

use App\Models\User;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\Intervencoes;
use App\Models\Tenant\Pedidos;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;

use App\Models\Tenant\Files;
use Livewire\WithPagination;
use App\Models\Tenant\Customers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\CustomerNotification\CustomerNotificationInterface;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;

class AlertMessages extends Component
{
    use WithPagination;

    protected $listeners = ["AlertMessages" => "AlertMessages","AfterPageRefresh","refreshserviceTable"=>'refreshserviceTable'];

    private object $notifications;
    private ?object $teamMembersResponse = NULL;
    protected object $customersRepository;
    protected TeamMemberInterface $TeamMember;

   
    protected CustomerNotificationInterface $customerNotification;
    protected object $alertMessageRepository;
    protected CustomerLocationsInterface $customerLocationInterface;
    public function boot( TeamMemberInterface $members,CustomerNotificationInterface $customerNotification,CustomersInterface $interfaceCustomers,AlertMessageInterface $interfaceAlert,CustomerLocationsInterface $customerLocationInterface)
    {
        $this->TeamMember = $members;
        $this->customerNotification = $customerNotification;
        $this->alertMessageRepository = $interfaceAlert;
        $this->customersRepository = $interfaceCustomers;
        $this->customerLocationInterface = $customerLocationInterface;
    }

    public function mount(): void
    {
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes($this->customersRepository,$this->customerLocationInterface);
        $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);  

        $read = 1;
        if($this->notifications->count() == 0)
        {
           //as notificações estão todas lidas
           $read = 0;
        }


        // print_r($read);
        $this->dispatchBrowserEvent("checkRead",["read" => $read]);
       
    }
    public function refreshserviceTable()
    {
        $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes($this->customersRepository,$this->customerLocationInterface);
        $read = 1;
        if($this->notifications->count() == 0)
        {
           //as notificações estão todas lidas
           $read = 0;
        }
        $this->dispatchBrowserEvent("checkRead",["read" => $read]);
    }
    //vem do livewire emit do evento do pusher
    public function AlertMessages()
    {
        $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);

        $read = 1;
        if($this->notifications->count() == 0)
        {
           //as notificações estão todas lidas
           $read = 0;
        }
        $this->dispatchBrowserEvent("checkRead",["read" => $read]);
    }

    public function AfterPageRefresh()
    {
        $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);
        $this->servicesNotifications = $this->customerNotification->getNotificationTimes($this->customersRepository,$this->customerLocationInterface);

        $read = 1;
        if($this->servicesNotifications){
            foreach ($this->servicesNotifications as $notification){
                if ($notification["team_member"] == Auth::user()->name || Auth::user()->type_user == 0){
                    if($this->notifications->count() == 0 && empty($this->servicesNotifications ))
                    {
                    $read = 0;
                    }
                }else{
                    $read = 0;
                }
            }
        }else{
            $read = 0;
        }
        

        $this->dispatchBrowserEvent("checkRead",["read" => $read]);
        // $this->dispatchBrowserEvent("refreshservice", ["read"=>$read]);
    }

    
    public function markRead()
    {
        //dar update e colocar como read a 1
        $this->alertMessageRepository->updateReadState(Auth::user()->id);

        $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);

        $read = 1;
        // $treated = 1;
        if($this->notifications->count() == 0)
        {
           //as notificações estão todas lidas
           $read = 0;
        }

        $this->mount();
        $this->dispatchBrowserEvent("refreshafter",["read" => $read]);

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
        $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);
        

        $read = 1;
        if($this->notifications->count() == 0)
        {
           //as notificações estão todas lidas
           $read = 0;
        }
        $this->dispatchBrowserEvent("refreshservice", ["read"=>$read]);
    }

    /**
     * List informations of customer location
     *
     * @return View
     */
    public function render(): View
    {
        return view('tenant.livewire.alertmessages.show', [
            'servicesNotifications' => $this->servicesNotifications,
            'notifications' => $this->notifications
        ]);
    }
}
