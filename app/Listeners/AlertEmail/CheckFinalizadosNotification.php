<?php

namespace App\Listeners\AlertEmail;

use Exception;
use App\Models\User;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Config;
use App\Models\Tenant\Pedidos;
use App\Models\Tenant\Customers;
use App\Events\Alerts\AlertEvent;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use App\Mail\AlertEmail\AlertEmail;
use App\Models\Tenant\Intervencoes;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use App\Models\Tenant\CustomerServices;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use App\Events\Alerts\EmailConclusionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Alerts\CheckFinalizadosEvent;
use App\Models\Tenant\CustomerNotifications;
use App\Mail\AlertEmail\AlertCheckFinalizados;
use App\Mail\AlertEmail\AlertEmailConclusionDay;
use App\Models\Tenant\TeamMember as TenantTeamMember;
use App\Interfaces\Tenant\Customers\CustomersInterface;

class CheckFinalizadosNotification
{
    protected object $customerRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CustomersInterface $interfaceCustomers)
    {
        $this->customerRepository = $interfaceCustomers;
    }

   
    public function handle(CheckFinalizadosEvent $checkFinalizadosEvent)
    {
 
        $eventIntervencoes = $checkFinalizadosEvent->intervencoes;


        Pedidos::where('id',$eventIntervencoes->id_pedido)->update([
           "estado" => 5
        ]);

        $user = User::where('id',$eventIntervencoes->user_id)->first();

        $pedido = Pedidos::where('id',$eventIntervencoes->id_pedido)->with('tipoPedido')->first();

        $intervencao = Intervencoes::where('id_pedido', $pedido->id)->get();

        $cst = $this->customerRepository->getSpecificCustomerInfo($pedido->customer_id);

        
        //PASSAR AQUI PARA O PHC ARRAY DO PEDIDO FINALIZADO
      




        Mail::to($user->email)->queue(new AlertCheckFinalizados($pedido, $intervencao,$cst));


        try {
            if($cst->customers->email != "")
            {
                $array = explode(";",$cst->customers->email);
        
                foreach($array as $email)
                {
                    //CLIENTE
                    Mail::to($email)->queue(new AlertCheckFinalizados($pedido, $intervencao,$cst));
                }
            }
            
        }
        catch (Exception $e) {
            echo $e;
        }
        

    }
}
