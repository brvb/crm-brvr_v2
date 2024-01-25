<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\Tenant\Tasks;
use Illuminate\Console\Command;
use App\Events\Alerts\AlertEvent;
use App\Events\Alerts\EmailNotifyEvent;
use App\Models\Tenant\CustomerServices;
use Stancl\Tenancy\Controllers\TenantAssetsController;

class EmailNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       
        tenancy()->runForMultiple(null, function (Tenant $tenant) {

            // $customersServices = CustomerServices::with('service')->with('customer')->with('customerLocation')->get();
            // foreach ($customersServices as $customer)
            // {
                
            //     if($customer->number_times != null && $customer->number_times > 0 && $customer->allMails == 1)
            //     {
            //         if($customer->selectedTypeContract == "semanalmente"){
            //             $date_updated = date('Y-m-d', strtotime('+'.$customer->time_repeat.' week', strtotime($customer->new_date)));
            //         } else if($customer->selectedTypeContract == "mensalmente"){
            //             $date_updated = date('Y-m-d', strtotime('+'.$customer->time_repeat.' month', strtotime($customer->new_date)));
            //         } else {
            //             $date_updated = date('Y-m-d', strtotime('+'.$customer->time_repeat.' year', strtotime($customer->new_date)));
            //         }

            //         if(date('Y-m-d') == $date_updated)
            //         {
            //             $subtract_actual = $customer->number_times - 1;
            //             CustomerServices::where('id',$customer->id)->update(["number_times" => $subtract_actual, "new_date" => date('Y-m-d')]);
                    
            //             event(new AlertEvent($customer));
            //         }
            //     }
            // }

            $date = date('Y-m-d');

            $tasks = Tasks::where('scheduled_date',date('Y-m-d',strtotime($date. ' + 1 days')))->where('alert_email',1)->get();
            //colocar aqui mais um if para o da checkbox  
             

            foreach($tasks as $task)
            {
                //só se fizer aqui mais uma verificação por causa da data
                //a não ser que vá pelo created at e que a data marcada seja hoje?
                //Dizer o vitor que isto só funciona com as datas scheduled

                $date_created_at = date('Y-m-d',strtotime($task->created_at));
              
                if($date_created_at < date('Y-m-d'))
                {
                    event(new EmailNotifyEvent($task));
                }
               
            }


        });
        //\Log::info("Cron do Notify");
        
        //return Command::SUCCESS;
    }
}
