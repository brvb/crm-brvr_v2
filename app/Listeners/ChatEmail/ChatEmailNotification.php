<?php

namespace App\Listeners\ChatEmail;

use App\Models\User;
use App\Models\Tenant\Config;
use App\Models\Tenant\Customers;
use App\Events\Alerts\AlertEvent;
use App\Models\Tenant\TeamMember;
use App\Mail\ChatEmail\ChatEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use App\Models\Tenant\CustomerServices;
use App\Events\ChatEmail\ChatEmailEvent;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Tenant\CustomerNotifications;

class ChatEmailNotification
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

   
    public function handle(ChatEmailEvent $chatEvent)
    {
        $customer = $chatEvent->customer;

        //Quem enviou mensagem foi um cliente
      
            $getCustomer = Customers::where('id',$customer)->first();

            Mail::to($getCustomer->email)->queue(new ChatEmail(($customer)));

        
        

        //aqui tenho de ir buscar o email do que recebe com o id de cima
      
        //Mail::to($email->email)->queue(new AlertEmail(($alert)));

              
    }
}
