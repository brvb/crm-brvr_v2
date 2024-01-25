<?php

namespace App\Listeners\Tasks;

use App\Models\Tenant\Tasks;
use App\Models\Tenant\Config;
use App\Mail\Tasks\TaskDispatched;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskDispatchedTech;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Tasks\DispatchTasksToUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Tasks\TasksDispatchedNotification;

class SendDispatchTasksNotification
{
    use Notifiable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\DispatchTask  $event
     * @return void
     */
    public function handle(DispatchTasksToUser $task)
    {
       
        $json = [
            "reference" => $task->task->reference, 
            "customer_name" => $task->task->taskCustomer->name, 
            "customer_nif" => $task->task->taskCustomer->vat,
            "descricao" => $task->task->additional_description,
            "data_prevista" => $task->task->scheduled_date,
            "hora_prevista" => $task->task->scheduled_hour,
            "data_criacao" => $task->task->created_at,
            "nome_tecnico" => $task->task->tech->name,
            "email_tecnico" => $task->task->tech->email,
            "origem_pedido" => $task->task->origem_pedido,
            "quem_pediu" => $task->task->quem_pediu,
            "tipo_pedido" => $task->task->tipo_pedido
        ];

        $result_encoded = json_encode($json);

        $curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://phc.brvr.pt:443/api/task',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $result_encoded,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		  ),
		));
	
	    curl_exec($curl);

        
        Mail::to($task->task->tech->email)->queue(new TaskDispatchedTech($task));

        if($task->task->taskCustomer->email == null || !isset($task->task->taskCustomer->email))
        {
            Mail::to(env('MAIL_USERNAME'))->queue(new TaskDispatched($task));
        }
        else {
            Mail::to($task->task->taskCustomer->email)->queue(new TaskDispatched($task));
        }

        $emailAdmin = Config::first();

        Mail::to($emailAdmin->email)->queue(new TaskDispatched($task));

        //agendar tarefas
        
       

        
        
    }
}
