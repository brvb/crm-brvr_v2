<?php

namespace App\Listeners\Tasks;

use Exception;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Config;
use App\Models\Tenant\Pedidos;
use App\Models\Tenant\Customers;
use App\Events\Tasks\TaskCreated;
use App\Models\Tenant\TeamMember;
use App\Events\Tasks\TaskCustomer;
use App\Mail\Tasks\TaskDispatched;
use App\Mail\Tasks\TaskCreateEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskDispatchedTech;
use App\Mail\Tasks\TaskReceiveEmailUser;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Tasks\DispatchTasksToUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Notifications\Tasks\TasksDispatchedNotification;

class TaskCustomerNotification
{
    use Notifiable;

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

    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskCreated  $event
     * @return void
     */
    public function handle(TaskCustomer $task)
    {
        $pedido = Pedidos::where('reference',$task->taskCustomer["reference"])->with("tech")->with("customer")->with("tipoPedido")->first();
       
        $customerEmail = Customers::where('id',$task->taskCustomer["customer_id"])->first();
        

        //Envia para o cliente

        $customer = $this->customerRepository->getSpecificCustomerInfo($pedido->customer_id);



        try {
            if($customer->customers->email != "")
            {
                $array = explode(";",$customer->customers->email);
        
                foreach($array as $email)
                {
                    //CLIENTE
                    Mail::to($email)->queue(new TaskReceiveEmailUser($pedido,$customer));
                }
            }
            
        }
        catch (Exception $e) {
            
        }
        

        //TECNICO
        Mail::to($pedido->tech->email)->queue(new TaskReceiveEmailUser($pedido,$customer));


        //Envia para o email principal
        //criação da tarefa
        $emailConfig = Config::first();
        Mail::to($emailConfig->email)->queue(new TaskReceiveEmailUser($pedido,$customer));
    }
}
