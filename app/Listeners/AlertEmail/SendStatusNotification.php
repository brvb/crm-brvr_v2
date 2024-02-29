<?php

namespace App\Listeners\AlertEmail;

use App\Models\User;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Config;
use App\Models\Tenant\Pedidos;
use App\Models\Tenant\Customers;
use App\Events\Alerts\AlertEvent;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use App\Mail\AlertEmail\AlertEmail;
use Illuminate\Support\Facades\Mail;
use App\Events\Alerts\SendStatusEvent;
use App\Mail\Tasks\TaskReportFinished;
use App\Models\Tenant\CustomerServices;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use App\Mail\AlertEmail\AlertStatusPedido;
use App\Events\Alerts\EmailConclusionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Alerts\CheckFinalizadosEvent;
use App\Models\Tenant\CustomerNotifications;
use App\Mail\AlertEmail\AlertCheckFinalizados;
use App\Mail\AlertEmail\AlertEmailConclusionDay;
use App\Models\Tenant\TeamMember as TenantTeamMember;
use App\Interfaces\Tenant\Customers\CustomersInterface;

class SendStatusNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected object $customerRepository;

    public function __construct(CustomersInterface $interfaceCustomers)
    {
        $this->customerRepository = $interfaceCustomers;
    }

   
    public function handle(SendStatusEvent $sendStatusEvent)
    {
 
        $eventPedido = $sendStatusEvent->pedido;
        $eventMensagem = $sendStatusEvent->mensagem;

        $customer = $this->customerRepository->getSpecificCustomerInfo($eventPedido->customer_id);
    

        if($customer->customers->email != "")
        {
            $array = explode(";",$customer->customers->email);
        
            foreach($array as $email)
            {
                Mail::to($email)->queue(new AlertStatusPedido($eventPedido,$customer,$eventMensagem));
            }
           
        }
        else
        {
            return "Nao tem email";
        }
        

           
              
    

              
    }
}
