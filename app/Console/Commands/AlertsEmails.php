<?php

namespace App\Console\Commands;

use Log;
use App\Models\Tenant;
use Illuminate\Console\Command;
use App\Events\Alerts\AlertEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Tenant\CustomerServices;
use Stancl\Tenancy\Controllers\TenantAssetsController;

class AlertsEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:cron';

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
            //$customersServices = CustomerServices::where('end_date','>=',date('Y-m-d'))->with('service')->with('customer')->with('customerLocation')->get();
            $customersServices = CustomerServices::with('service')->with('customer')->with('customerLocation')->get();
            foreach ($customersServices as $customer)
            {
                // $date_subtracted = date('Y-m-d', strtotime('-'.$customer->alert.' day', strtotime($customer->end_date)));
                // if(date('Y-m-d') == $date_subtracted)
                // {
                //     event(new AlertEvent($customer));
                // }
                if($customer->number_times != null && $customer->number_times > 0 && $customer->allMails == 1)
                {
                    if($customer->selectedTypeContract == "semanalmente"){
                        $date_updated = date('Y-m-d', strtotime('+'.$customer->time_repeat.' week', strtotime($customer->new_date)));
                    } else if($customer->selectedTypeContract == "mensalmente"){
                        $date_updated = date('Y-m-d', strtotime('+'.$customer->time_repeat.' month', strtotime($customer->new_date)));
                    } else {
                        $date_updated = date('Y-m-d', strtotime('+'.$customer->time_repeat.' year', strtotime($customer->new_date)));
                    }

                    if(date('Y-m-d') == $date_updated)
                    {
                        $subtract_actual = $customer->number_times - 1;
                        CustomerServices::where('id',$customer->id)->update(["number_times" => $subtract_actual, "new_date" => date('Y-m-d')]);
                    
                        event(new AlertEvent($customer));
                    }
                }
            }
        });
        \Log::info("Cron is working fine!");
        
        //return Command::SUCCESS;
    }
}
