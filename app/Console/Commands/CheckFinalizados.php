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
use Stancl\Tenancy\Controllers\TenantAssetsController;

class CheckFinalizados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:check_finalizados';

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
          
            //tenho checkar dos concludis se ha algum que esteja nas 48 horas ou após

            //tenho de ir aos pedidos e ver se esta concluido
            //se tiver vou as intervenções e vejo o ultimo registo que esta concluido por ordem crescente
            //se passar 48 horas executo o evento abaixo

            $getPedidos = Pedidos::where('estado','2')->get();

            foreach($getPedidos as $pedido)
            {
                $teamMember = TeamMember::where('id',$pedido->tech_id)->first();
                $getIntervencao = Intervencoes::where('user_id',$teamMember->user_id)->where('id_pedido',$pedido->id)->orderBy('id','desc')->first();

                $dataHora = Carbon::parse("$getIntervencao->data_inicio $getIntervencao->hora_final");

                $dataHoraMais48Horas = $dataHora->addHours(48);

                $now = Carbon::now();

                if ($now->greaterThanOrEqualTo($dataHoraMais48Horas)) {
                    event(new CheckFinalizadosEvent($getIntervencao));
                }
            }

    
                  
        });
       
        
        
    }
}
