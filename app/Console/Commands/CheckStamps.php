<?php

namespace App\Console\Commands;

use Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Pedidos;
use Illuminate\Console\Command;
use App\Events\Alerts\AlertEvent;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Intervencoes;
use Illuminate\Support\Facades\Config;
use App\Models\Tenant\CustomerServices;
use App\Events\Alerts\EmailConclusionEvent;
use App\Events\Alerts\CheckFinalizadosEvent;
use App\Models\Tenant\StampsClientes;
use Stancl\Tenancy\Controllers\TenantAssetsController;

class CheckStamps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:check_stamps';

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
          
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://phc.brvr.pt:443/customers/customers',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
    
            $response = curl_exec($curl);
    
            curl_close($curl);
    
            $response_decoded = json_decode($response);

            foreach($response_decoded->customers as $decoded)
            {
               $cliente = StampsClientes::where('stamp',$decoded->id)->first();

               if(empty($cliente))
               {
                    StampsClientes::create([
                        "stamp" => $decoded->id,
                        "nome_cliente" => $decoded->name
                    ]);
               }
            }
                              
        });
       
        
        
    }
}
