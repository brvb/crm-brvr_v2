<?php

namespace App\Console\Commands;

use Log;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Tenant\Tasks;
use Illuminate\Console\Command;
use App\Events\Alerts\AlertEvent;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Tenant\CustomerServices;
use App\Events\Alerts\EmailConclusionEvent;
use Stancl\Tenancy\Controllers\TenantAssetsController;

class AlertsEmailConclusion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:mail_conclusion_day';

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
          
             //poder colocar da maneira como tenho representado nos quadros
             //dividir os arrays por quadros e enviar tudo para o evento do alerta

             $users = TeamMember::all();

             $arrayUsers = [];

             foreach($users as $us)
             {
                $arrayUsers[$us->email] = [
                    "hierarquia" => $us->id_hierarquia, 
                    "departamento" => $us->id_departamento,
                    "teamMember_id" => $us->id,
                ];
             }

             

             event(new EmailConclusionEvent($arrayUsers));
            // \Log::info($arrayUsers);
                  
        });
       
        
        
    }
}
