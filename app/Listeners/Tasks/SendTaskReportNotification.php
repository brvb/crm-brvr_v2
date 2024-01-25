<?php

namespace App\Listeners\Tasks;

use App\Models\Tenant\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use App\Events\Tasks\DispatchTaskReport;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskReportNotification
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

    /**
     * Handle the event.
     *
     * @param  \App\Events\DispatchTaskReport  $event
     * @return void
     */
    public function handle(DispatchTaskReport $tasksReports)
    {
        $tasksReports = $tasksReports->tasksReports;

    
        foreach($tasksReports->servicesToDo as $i => $services)
        {
            $servicesArray[$i] = [
                "descricao" => $services->additional_description,
                "nome_servico" => $services->service->name
            ];
        }

        foreach($tasksReports->getHoursTask as $i => $times)
        {
            $servicesTimes[$i] = [
                "data_inicio" => $times->date_begin,
                "hora_inicio" => $times->hour_begin,
                "hora_final" => $times->hour_end,
                "horas_total" => $times->total_hours,
                "data_tempo_criado" => $times->created_at,
                "descricao" => $times->descricao
            ];
        }


        $json = [
            "reference" => $tasksReports->reference, 
            "customer_name" => $tasksReports->taskCustomer->name, 
            "customer_nif" => $tasksReports->taskCustomer->vat,
            "descricao" => $tasksReports->additional_description,
            "data_prevista" => $tasksReports->scheduled_date,
            "hora_prevista" => $tasksReports->scheduled_hour,
            "data_criacao" => $tasksReports->created_at,
            "nome_tecnico" => $tasksReports->tech->name,
            "email_tecnico" => $tasksReports->tech->email,
            "origem_pedido" => $tasksReports->tasks->origem_pedido,
            "quem_pediu" => $tasksReports->tasks->quem_pediu,
            "tipo_pedido" => $tasksReports->tasks->tipo_pedido,
            "services" => $servicesArray,
            "tempos" => $servicesTimes
        ];

         $result_encoded = json_encode($json);


         $curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://phc.brvr.pt:443/api/times',
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

        Mail::to($tasksReports->tech->email)->queue(new TaskReportFinished($tasksReports));
        if($tasksReports->taskCustomer->email == null || !isset($tasksReports->taskCustomer->email))
        {
            Mail::to(env('MAIL_USERNAME'))->queue(new TaskReportFinished($tasksReports));
        }
        else {
            Mail::to($tasksReports->taskCustomer->email)->queue(new TaskReportFinished($tasksReports));
        }
        
        //finalizar tarefas
        $emailAdmin = Config::first();
        Mail::to($emailAdmin->email)->queue(new TaskReportFinished($tasksReports));
       

    }
}
