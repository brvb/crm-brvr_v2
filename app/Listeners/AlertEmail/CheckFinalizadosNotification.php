<?php

namespace App\Listeners\AlertEmail;

use App\Models\Tenant\Tasks;
use App\Models\Tenant\Config;
use App\Events\Alerts\AlertEvent;
use App\Events\Alerts\CheckFinalizadosEvent;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use App\Mail\AlertEmail\AlertEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use App\Models\Tenant\CustomerServices;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use App\Events\Alerts\EmailConclusionEvent;
use App\Mail\AlertEmail\AlertCheckFinalizados;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Tenant\CustomerNotifications;
use App\Mail\AlertEmail\AlertEmailConclusionDay;
use App\Models\Tenant\Customers;
use App\Models\Tenant\Pedidos;
use App\Models\Tenant\Intervencoes;
use App\Models\Tenant\TeamMember as TenantTeamMember;
use App\Models\User;

class CheckFinalizadosNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

   
    public function handle(CheckFinalizadosEvent $checkFinalizadosEvent)
    {
 
        $eventIntervencoes = $checkFinalizadosEvent->intervencoes;

        Pedidos::where('id',$eventIntervencoes->id_pedido)->update([
           "estado" => 5
        ]);

        $user = User::where('id',$eventIntervencoes->user_id)->first();

        $pedido = Pedidos::where('id',$eventIntervencoes->id_pedido)->first();

        $intervencao = Intervencoes::where('id_pedido', $pedido->id)->Get();

        $customer = Customers::where('id',$pedido->customer_id)->first();

        Mail::to($user->email)->queue(new AlertCheckFinalizados($pedido, $intervencao));

        Mail::to($customer->email)->queue(new AlertCheckFinalizados($pedido, $intervencao));

    }
}
