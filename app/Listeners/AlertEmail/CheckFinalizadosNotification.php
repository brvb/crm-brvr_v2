<?php

namespace App\Listeners\AlertEmail;

use Exception;
use App\Models\User;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Config;
use App\Models\Tenant\Pedidos;
use App\Models\Tenant\Customers;
use App\Events\Alerts\AlertEvent;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use App\Mail\AlertEmail\AlertEmail;
use App\Models\Tenant\Intervencoes;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use App\Models\Tenant\CustomerServices;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use App\Events\Alerts\EmailConclusionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Alerts\CheckFinalizadosEvent;
use App\Models\Tenant\CustomerNotifications;
use App\Mail\AlertEmail\AlertCheckFinalizados;
use App\Mail\AlertEmail\AlertEmailConclusionDay;
use App\Models\Tenant\TeamMember as TenantTeamMember;
use App\Interfaces\Tenant\Customers\CustomersInterface;

class CheckFinalizadosNotification
{
    protected object $customerRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CustomersInterface $interfaceCustomers)
    {
        $this->customerRepository = $interfaceCustomers;
    }

   
    public function handle(CheckFinalizadosEvent $checkFinalizadosEvent)
    {
        
 
        $eventIntervencoes = $checkFinalizadosEvent->intervencoes;


        Pedidos::where('id',$eventIntervencoes->id_pedido)->update([
           "estado" => 5
        ]);

        $user = User::where('id',$eventIntervencoes->user_id)->first();

        $pedido = Pedidos::where('id',$eventIntervencoes->id_pedido)
                  ->with('tipoPedido')
                  ->with('tech')
                  ->with('tipoEstado')
                  ->with('customer')
                  ->with('prioridadeStat')
                  ->with('servicesToDo')
                  ->first();

            
        $intervencao = Intervencoes::where('id_pedido', $pedido->id)->get();


        $cst = $this->customerRepository->getSpecificCustomerInfo($pedido->customer_id);
        

        
        //PASSAR AQUI PARA O PHC ARRAY DO PEDIDO FINALIZADO
        $arrayIntervencao = [];

        $arrayProdutos = [];


        foreach($intervencao as $id => $int)
        {
            if(!isset($int->produtos_ref))
            {
                $arrayProdutos[$id] = [];
            }
            else {
                 if($int->produtos_ref == null){
                     $arrayProdutos[$id] = [];
                } else {
    
                    $arrayProdutos[$id] = [];
                    
    
                    $produtos_ref = json_decode($int->produtos_ref);
                    $produtos_desc = json_decode($int->produtos_desc);
                    $produtos_qtd = json_decode($int->produtos_qtd);
    
                    foreach($produtos_ref as $i => $prod)
                    {
                        $arrayProdutos[$id - 1][$i]["reference"] = trim($prod);
                        $arrayProdutos[$id - 1][$i]["designation"] = trim($produtos_desc[$i]);
                        $arrayProdutos[$id - 1][$i]["amount"] = trim($produtos_qtd[$i]);
                    }
    
    
                }
            }
           

        }


        foreach($intervencao as $i => $int)
        {
            if($int->descontos == "")
            {
                $desconto = "0";
            } else {

                if($int->descontos[0] == "+"){
                    $desconto = substr($int->descontos, 1);
                } else if($int->descontos[0] == "-"){
                    $desconto = substr($int->descontos, 1) * -1;
                }
            }

            $user = User::where('id',$int->user_id)->first();

            $arrayIntervencao[$i] = [
                "id_intervention" => $int->id,
                "technician" => $user->name,
                "date_start" => $int->data_inicio,
                "hour_start" => $int->hora_inicio,
                "hour_finish" => $int->hora_final,
                "discount" => $desconto,
                "description" => $int->descricao_realizado,
                "Product" => $arrayProdutos[$i]
            ];
        }



        //FALTA MUDAR AQUI ALGUNS VALORES CHECKAR A INFO DA API

        $arrayToSend = [];

        if($pedido->riscado == 1){
            $riscado = true;
        } else {
            $riscado =  false;
        }

        if($pedido->partido == 1){
            $partido = true;
        } else {
            $partido =  false;
        }

        if($pedido->bom_estado == 1){
            $bomestado = true;
        } else {
            $bomestado =  false;
        }

        if($pedido->estado_normal == 1){
            $estadonormal = true;
        } else {
            $estadonormal =  false;
        }

        if($pedido->transformador == 1){
            $transformador = true;
        } else {
            $transformador =  false;
        }

        if($pedido->mala == 1){
            $mala = true;
        } else {
            $mala =  false;
        }

        if($pedido->tinteiro == 1){
            $tinteiro = true;
        } else {
            $tinteiro =  false;
        }

        if($pedido->ac == 1){
            $ac = true;
        } else {
            $ac =  false;
        }


        // if($pedido->tipoPedido->id == 1)
        // {

            $arrayToSend = [
                "id" => $pedido->id,
                "customer_id" => $pedido->customer_id,
                "type_order" => $pedido->tipoPedido->name,
                "type_service" => $pedido->servicesToDo->name,
                "location_id" => $pedido->location_id,
                "priority" => $pedido->prioridadeStat->nivel,
                "technician" =>$pedido->tech->name,
                "origin_order" => $pedido->origem_pedido,
                "type_scheduling" => $pedido->tipo_agendamento,
                "who_asked" => $pedido->quem_pediu,
                "create_date" => date('Y-m-d H:i:s',strtotime($pedido->created_at)),
                "serialnumber" => $pedido->nr_serie,
                "brand" => $pedido->marca,
                "model" => $pedido->modelo,
                "equipment_name" => $pedido->nome_equipamento,
                "description" => $pedido->descricao,
                "riscado" => $riscado,
                "partido" => $partido,
                "bom_estado" => $bomestado,
                "estado_normal" => $estadonormal,
                "transformador" => $transformador,
                "mala"=> $mala,
                "tinteiro_toner" => $tinteiro,
                "ac" => $ac,
                "extradescription" => $pedido->descricao_extra,
                "intervention" => $arrayIntervencao
            ];
          
    
    
            $arrayEncoded =  json_encode($arrayToSend);
            
    
            //ENVIA PHC 
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://phc.brvr.pt:443/Requests/Requests',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $arrayEncoded,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
    
            $response = curl_exec($curl);
    
            curl_close($curl);
    
             $users_admins = User::where('type_user',0)->get();
    
            foreach($users_admins as $usrA)
            {
                if($pedido->tipoPedido->id == 1)
                {
                    Mail::to($usrA->email)->queue(new AlertCheckFinalizados($pedido, $intervencao,$cst));
                }
                
            }
    
            if($pedido->tipoPedido->id == 1)
            {
                Mail::to($user->email)->queue(new AlertCheckFinalizados($pedido, $intervencao,$cst));
            }
    
    
            try {
                if($cst->customers->email != "")
                {
                    $array = explode(";",$cst->customers->email);
            
                    foreach($array as $email)
                    {
                        if($pedido->tipoPedido->id == 1)
                        {
                            //CLIENTE
                            Mail::to($email)->queue(new AlertCheckFinalizados($pedido, $intervencao,$cst));
                        }
                    }
                }
                
            }
            catch (Exception $e) {
                echo $e;
            }

        //}


      
        

    }
}