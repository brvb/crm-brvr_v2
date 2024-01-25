<?php

namespace App\Repositories\Tenant\Tasks;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Pedidos;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\SerieNumbers;
use App\Models\Tenant\TaskServices;
use App\Models\Tenant\TasksReports;
use App\Models\Tenant\Departamentos;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\GenerateTaskReference;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tenant\Tasks\TasksFormRequest;
use App\Models\Tenant\Intervencoes;

class TasksRepository implements TasksInterface
{
    use GenerateTaskReference;

    public function add(TasksFormRequest $request): Tasks
    {
        return DB::transaction(function () use ($request) {
            $tasks = Tasks::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_phone' => $request->mobile_phone,
                'job' => $request->job,
                'additional_info' => $request->additional_info,
            ]);
            return $tasks;
        });
    }

    public function createPedido(object $values): Pedidos
    {
        

        return DB::transaction(function () use ($values) {

            if(!empty($values->arrayFirstUploaded)){
                $imagesPedido = [];
                foreach($values->arrayFirstUploaded as $img)
                {
                    array_push($imagesPedido,$img[0]->getClientOriginalName());
                }
            }
            else {
                $imagesPedido = [];
            }

            if(!empty($values->arrayEquipamentoUploaded)){
                $imagesEquipamento = [];
                foreach($values->arrayEquipamentoUploaded as $img)
                {
                    array_push($imagesEquipamento,$img[0]->getClientOriginalName());
                }
            }
            else {
                $imagesEquipamento = [];
            }

            
            $pedido = Pedidos::create([
                'reference' => $values->taskReference,
                'number' => $values->number,
                'customer_id' => $values->selectedCustomer,
                'contacto_adicional' => $values->contactoAdicional,
                'tipo_pedido' => $values->selectedPedido,
                'tipo_servico' => $values->selectedServico,
                'descricao' => $values->serviceDescription,
                'anexos' => json_encode($imagesPedido),
                'location_id' => $values->selectedLocation,
                'nr_serie' => $values->serieNumber,
                'marca' => $values->marcaEquipment,
                'modelo' => $values->modelEquipment,
                'nome_equipamento' => $values->nameEquipment,
                'descricao_equipamento' => $values->descriptionEquipment,
                'riscado' => $values->riscado,
                'partido' => $values->partido,
                'bom_estado' => $values->bomestado,
                'estado_normal' => $values->normalestado,
                'transformador' => $values->transformador,
                'mala' => $values->mala,
                'tinteiro' => $values->tinteiro,
                'ac' => $values->ac,
                'descricao_extra' => $values->descriptionExtra,
                'anexos_equipamentos' => json_encode($imagesEquipamento),
                'prioridade' => $values->selectPrioridade,
                'tech_id' => $values->selectedTechnician,
                'origem_pedido' => $values->origem_pedido,
                'tipo_agendamento' => $values->tipo_pedido,
                'quem_pediu' => $values->quem_pediu,
                'data_agendamento' => $values->previewDate,
                'hora_agendamento' => $values->previewHour,
                'observacoes_agendamento' => $values->observacoesAgendar,
                'estado' => 1
            ]);


            return Pedidos::where('id', $pedido->id)->first();
        });
    }

    public function updateTask(Tasks $task, object $values): bool
    {
        DB::beginTransaction();
        if ($task->location_id != $values->selectedLocation) {
            if(TaskServices::where('task_id', $task->id)->delete() == 0) {
                DB::rollBack();
                return false;
            }
        }


        if($values->imagem != "")
        {
            $imagem = $values->imagem;
        }
        else {
            $imagem = "";
        }

        $update = Tasks::where('id', $task->id)
            ->update([
                'location_id' => $values->selectedLocation,
                'resume' => $values->resume,
                'additional_description' => $values->taskAdditionalDescription,
                'preview_date' => $values->previewDate,
                'preview_hour' => $values->previewHour,
                'scheduled_date' => $values->scheduledDate,
                'scheduled_hour' => $values->scheduledHour,
                'tech_id' => $values->selectedTechnician,
                'origem_pedido' => $values->origem_pedido,
                'quem_pediu' => $values->quem_pediu,
                'tipo_pedido' => $values->tipo_pedido,
                'alert_email' => $values->alert_email,
                'nr_serie' => $values->serieNumber,
                'marca' => $values->marcaEquipment,
                'modelo' => $values->modelEquipment,
                'nome_equipamento' => $values->nameEquipment,
                'descricao_equipamento' => $values->descriptionEquipment,
                'riscado' => $values->riscado,
                'partido' => $values->partido,
                'bom_estado' => $values->bomestado,
                'estado_normal' => $values->normalestado,
                'transformador' => $values->transformador,
                'mala' => $values->mala,
                'tinteiro' => $values->tinteiro,
                'ac' => $values->ac,
                'descricao_extra' => $values->descriptionExtra,
                'imagem' => $imagem,
                'prioridade' => $values->selectPrioridade
                ]);
        
        $taskReportUpdate = TasksReports::where('task_id', $task->id)
                             ->update([
                                'preview_date' => $values->previewDate,
                                'preview_hour' => $values->previewHour,
                                'scheduled_date' => $values->scheduledDate,
                                'scheduled_hour' => $values->scheduledHour,
                                'tech_id' => $values->selectedTechnician
                             ]);
        
        if($update == 0) {
            DB::rollBack();
            return false;
        }

     
        $update = TaskServices::where('task_id', $task->id)
            ->whereNotIn('service_id', $values->selectedServiceId)
            ->delete();
      
        // if($update == 0) {
        //     DB::rollBack();
        //     return false;
        // }

        foreach ($values->selectedServiceId as $key => $service) {
            $temp = TaskServices::where(['task_id' => $task->id, 'task_service_id' => $key])->first();
            if ($temp && $temp->count() > 0) {
                $update = TaskServices::where([
                        'task_id' => $task->id,
                        'task_service_id' => $key])
                    ->update([
                        'service_id' => $service,
                        'additional_description' => $values->serviceDescription[$key],
                    ]);
                if($update == 0) {
                    DB::rollBack();
                    return false;
                }
            } else {
                $update = TaskServices::create([
                    'task_id' => $task->id,
                    'task_service_id' => $key,
                    'service_id' => $service,
                    'additional_description' => $values->serviceDescription[$key],
                ]);
               
               if($update->count() == 0)
               {
                    $update = 0;
                    if($update == 0) {
                        DB::rollBack();
                        return false;
                    }
               }
               else {
                   DB::commit();
                   return true;
               } 
               
                // if($update == 0) {
                //     DB::rollBack();
                //     return false;
                // }
            }
        }

       
        // $update = TasksReports::create([
        //     'reference' => $task->reference,
        //     'customer_id' => $task->customer_id,
        //     'location_id' => $task->location_id,
        //     'task_id' => $task->id,
        //     'additional_description' => $task->additional_description,
        //     'applicant_name' => $task->applicant_name,
        //     'applicant_contact' => $task->applicant_contact,
        //     'preview_date' => $task->preview_date,
        //     'preview_hour' => $task->preview_hour,
        //     'scheduled_date' => $values->scheduledDate,
        //     'scheduled_hour' => $values->scheduledHour,
        //     'tech_id' => $task->tech_id
        // ]);
        
         
        // if($update == null) {
        //     DB::rollBack();
        //     return false;
        // }
        DB::commit();
        return true;
    }

    public function dispatchTask(Tasks $tasks): TasksReports
    {
        return DB::transaction(function () use ($tasks) {
            Tasks::where('id', $tasks->id)
                ->update([
                    'status' => $tasks->status,
                    'scheduled_date' => $tasks->scheduled_date,
                    'scheduled_hour' => $tasks->scheduled_hour,
                ]);

            return TasksReports::create([
                'reference' => $tasks->reference,
                'customer_id' => $tasks->customer_id,
                'location_id' => $tasks->location_id,
                'task_id' => $tasks->id,
                'additional_description' => $tasks->additional_description,
                'applicant_name' => $tasks->applicant_name,
                'applicant_contact' => $tasks->applicant_contact,
                'preview_date' => $tasks->preview_date,
                'preview_hour' => $tasks->preview_hour,
                'scheduled_date' => $tasks->scheduled_date,
                'scheduled_hour' => $tasks->scheduled_hour,
                'tech_id' => $tasks->tech_id,
             ]);

        });

    }

    public function getTasks($perPage)
    {
        if(Auth::user()->type_user == 2)
        {
            $customer = Customers::where('user_id',Auth::user()->id)->first();

            return Pedidos::where('customer_id',$customer->id)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
                ->orderBy('created_at','desc')
                ->paginate($perPage);
        }
        else if(Auth::user()->type_user == 1)
        {
            $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
            return Pedidos::where('tech_id',$teammember->id)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
                ->orderBy('created_at','desc')
                ->paginate($perPage);
        }
        else 
        {
            $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
            return Pedidos::where('tech_id',$teammember->id)
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('created_at','desc')
            ->paginate($perPage);
        }
    }


    public function getTaskSearch($searchString,$perPage): LengthAwarePaginator
    {
        if(Auth::user()->type_user == 2)
        {

            $customer = Customers::where('user_id',Auth::user()->id)->first();
            return Pedidos::where('customer_id',$customer->id)          
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
              ->orderBy('created_at','desc')
              ->paginate($perPage);
        }
        else if(Auth::user()->type_user == 1)
        {
          $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
           return Pedidos::where('tech_id',$teammember->id)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
              ->paginate($perPage);
        }
        else {
            $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
            return Pedidos::where('tech_id',$teammember->id)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
              ->paginate($perPage);
        }
    }

    public function getTask($task): Pedidos
    {
        return Pedidos::where('id', $task->id)
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->first();
    }

    public function getTaskById($taskId): Pedidos
    {
        return Pedidos::where('id', $taskId)
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->first();
    }

    public function deleteTask($task) {
     
        DB::beginTransaction();
        if(Tasks::where('id', $task->id)->delete() == 0) {
            DB::rollBack();
            return false;
        }
        if(TaskServices::where('task_id', $task->id)->delete() == 0) {
            DB::rollBack();
            return false;
        }

        $taskReports = TasksReports::where('reference',$task->reference)->first();
        if($taskReports != null)
        {
            if(TasksReports::where('reference', $task->reference)->delete() == 0){
                DB::rollBack();
                return false;
            }
        }
        
        DB::commit();
        return true;
    }

      

    /**FILTRO */

    public function getTasksFilter($searchString,$tech,$client,$typeReport,$work,$ordenation,$dateBegin,$dateEnd,$perPage): LengthAwarePaginator
    {          
        if($client != 0)
        {
            $tasks = Pedidos::whereHas('tech', function ($query) use ($tech)
            {
               if($tech != 0)
               {
                   $query->where('id',$tech);
               }
            })
            ->whereHas('servicesToDo', function ($query) use ($work, $searchString)
            {
               
                    if($work != 0)
                    {
                         $query->where('id',$work);
                    }

                    if($searchString != "")
                    {
                        $query->where('name', 'like', '%' . $searchString . '%');
                    }

              
              
            })
            ->whereHas('customer', function ($query) use ($searchString)
            {
                if($searchString != "")
                {
                    $query->where('short_name', 'like', '%' . $searchString . '%');
                }
            });
            
         
                $tasks = $tasks
                ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                    $query->where('created_at','>=',$dateBegin)->where('created_at','<=',$dateEnd);
                })
                ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                    $query->where('created_at','>=',$dateBegin);
                })
                ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                    $query->where('created_at','<=',$dateEnd);
                });
    
                if(Auth::user()->type_user == 2)
                {
                   $customer = Customers::where('user_id',Auth::user()->id)->first();
                   $tasks = $tasks->where('customer_id',$customer->id);
                }


                $tasks = $tasks->where('customer_id',$client);

                $tasks = $tasks->whereHas('tipoEstado', function ($query) use ($typeReport)
                {
                
                        if($typeReport != 0)
                        {
                            $query->where('id',$typeReport);
                        }
                
                
                });
    
                if($ordenation == "asc"){
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'asc')->paginate($perPage);
                 }
                 else {
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'desc')->paginate($perPage);
                 }

        
                     
        }
        else 
        {
            $tasks = Pedidos::whereHas('tech', function ($query) use ($tech)
            {
               if($tech != 0)
               {
                   $query->where('id',$tech);
               }
            })
            ->whereHas('customer', function ($query) use ($searchString)
            {
                if($searchString != "")
                {
                    $query->where('short_name', 'like', '%' . $searchString . '%');
                }
            })
            ->whereHas('servicesToDo', function ($query) use ($work, $searchString)
            {
               
                    if($work != 0)
                    {
                         $query->where('id',$work);
                    }
                    if($searchString != "")
                    {
                        $query->orwhere('name', 'like', '%' . $searchString . '%');
                    }

              
            });

          
                $tasks = $tasks
                ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                    $query->where('created_at','>=',$dateBegin)->where('created_at','<=',$dateEnd);
                })
                ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                    $query->where('created_at','>=',$dateBegin);
                })
                ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                    $query->where('created_at','<=',$dateEnd);
                });
    
                if(Auth::user()->type_user == 2)
                {
                   $customer = Customers::where('user_id',Auth::user()->id)->first();
                   $tasks = $tasks->where('customer_id',$customer->id);
                }

                $tasks = $tasks->whereHas('tipoEstado', function ($query) use ($typeReport)
                {
                
                        if($typeReport != 0)
                        {
                            $query->where('id',$typeReport);
                        }
                
                
                });
    
                if($ordenation == "asc"){
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'asc')->paginate($perPage);
                 }
                 else {
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'desc')->paginate($perPage);
                 }

         
        
        }
       
        
        return $tasks;
    }


    /**FIM FILTRO */

    public function searchSerialNumber($serialNumber): LengthAwarePaginator
    {
        //Fazer sempre uma pesquisa com where e vou retornar os 2 valores
        $collection = SerieNumbers::where('nr_serie','like', '%'.$serialNumber.'%')->paginate(1);

        return $collection;

    }


    public function getIntervencoes($perPage)
    {
        if(Auth::user()->type_user == 2)
        {
            $customer = Customers::where('user_id',Auth::user()->id)->first();

            return Pedidos::where('customer_id',$customer->id)
                ->where('estado','!=',2)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
                ->orderBy('created_at','desc')
                ->paginate($perPage);
        }
        else if(Auth::user()->type_user == 1)
        {
            $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
            return Pedidos::where('tech_id',$teammember->id)
                ->where('estado','!=',2)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
                ->orderBy('created_at','desc')
                ->paginate($perPage);
        }
        else 
        {
            $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
            return Pedidos::where('tech_id',$teammember->id)
            ->where('estado','!=',2)
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->orderBy('created_at','desc')
            ->paginate($perPage);
        }
    }


    public function getIntervencaoSearch($searchString,$perPage): LengthAwarePaginator
    {
        if(Auth::user()->type_user == 2)
        {

            $customer = Customers::where('user_id',Auth::user()->id)->first();
            return Pedidos::where('customer_id',$customer->id)
            ->where('estado','!=',2)          
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
              ->orderBy('created_at','desc')
              ->paginate($perPage);
        }
        else if(Auth::user()->type_user == 1)
        {
          $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
           return Pedidos::where('tech_id',$teammember->id)
                ->where('estado','!=',2)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
              ->paginate($perPage);
        }
        else {
            $teammember = TeamMember::where('user_id',Auth::user()->id)->first();
            return Pedidos::where('tech_id',$teammember->id)
                ->where('estado','!=',2)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
              ->paginate($perPage);
        }
    }

    public function getIntervencao($task): Tasks
    {
        return Pedidos::where('id', $task->id)
            ->where('estado','!=',2)
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->first();
    }

    public function getIntervencaoById($taskId): Tasks
    {
        return Pedidos::where('id', $taskId)
            ->where('estado','!=',2)
            ->with('customer')
            ->with('tipoEstado')
            ->with('tech')
            ->with('servicesToDo')
            ->with('location')
            ->first();
    }

    public function getIntervencaoFilter($searchString,$tech,$client,$typeReport,$work,$ordenation,$dateBegin,$dateEnd,$perPage): LengthAwarePaginator
    {          
        if($client != 0)
        {
            $tasks = Pedidos::where('estado','!=',2)->whereHas('tech', function ($query) use ($tech)
            {
               if($tech != 0)
               {
                   $query->where('id',$tech);
               }
            })
            ->whereHas('servicesToDo', function ($query) use ($work, $searchString)
            {
               
                    if($work != 0)
                    {
                         $query->where('id',$work);
                    }

                    if($searchString != "")
                    {
                        $query->where('name', 'like', '%' . $searchString . '%');
                    }

              
              
            })
            ->whereHas('customer', function ($query) use ($searchString)
            {
                if($searchString != "")
                {
                    $query->where('short_name', 'like', '%' . $searchString . '%');
                }
            });
            
         
                $tasks = $tasks
                ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                    $query->where('created_at','>=',$dateBegin)->where('created_at','<=',$dateEnd);
                })
                ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                    $query->where('created_at','>=',$dateBegin);
                })
                ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                    $query->where('created_at','<=',$dateEnd);
                });
    
                if(Auth::user()->type_user == 2)
                {
                   $customer = Customers::where('user_id',Auth::user()->id)->first();
                   $tasks = $tasks->where('customer_id',$customer->id);
                }


                $tasks = $tasks->where('customer_id',$client);

                $tasks = $tasks->whereHas('tipoEstado', function ($query) use ($typeReport)
                {
                
                        if($typeReport != 0)
                        {
                            $query->where('id',$typeReport);
                        }
                
                
                });
    
                if($ordenation == "asc"){
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'asc')->paginate($perPage);
                 }
                 else {
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'desc')->paginate($perPage);
                 }

        
                     
        }
        else 
        {
            $tasks = Pedidos::where('estado','!=',2)->whereHas('tech', function ($query) use ($tech)
            {
               if($tech != 0)
               {
                   $query->where('id',$tech);
               }
            })
            ->whereHas('customer', function ($query) use ($searchString)
            {
                if($searchString != "")
                {
                    $query->where('short_name', 'like', '%' . $searchString . '%');
                }
            })
            ->whereHas('servicesToDo', function ($query) use ($work, $searchString)
            {
               
                    if($work != 0)
                    {
                         $query->where('id',$work);
                    }
                    if($searchString != "")
                    {
                        $query->orwhere('name', 'like', '%' . $searchString . '%');
                    }

              
            });

          
                $tasks = $tasks
                ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                    $query->where('created_at','>=',$dateBegin)->where('created_at','<=',$dateEnd);
                })
                ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                    $query->where('created_at','>=',$dateBegin);
                })
                ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                    $query->where('created_at','<=',$dateEnd);
                });
    
                if(Auth::user()->type_user == 2)
                {
                   $customer = Customers::where('user_id',Auth::user()->id)->first();
                   $tasks = $tasks->where('customer_id',$customer->id);
                }

                $tasks = $tasks->whereHas('tipoEstado', function ($query) use ($typeReport)
                {
                
                        if($typeReport != 0)
                        {
                            $query->where('id',$typeReport);
                        }
                
                
                });
    
                if($ordenation == "asc"){
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'asc')->paginate($perPage);
                 }
                 else {
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'desc')->paginate($perPage);
                 }

         
        
        }
       
        
        return $tasks;
    }

    public function addIntervencao($object): Intervencoes
    {
        return DB::transaction(function () use ($object) {

            if(!empty($object->arrayFirstUploaded)){
                $imagesPedido = [];
                foreach($object->arrayFirstUploaded as $img)
                {
                    array_push($imagesPedido,$img[0]->getClientOriginalName());
                }
            }
            else {
                $imagesPedido = [];
            }


            Intervencoes::create([
                "id_pedido" => $object->task->id,
                "material_ref_intervencao" => $object->referencia_intervencao,
                "material_descricao_intervencao" => $object->descricao_intervencao,
                "material_quantidade_intervencao" => $object->quantidade_intervencao,
                "estado_pedido" => $object->selectedEstado,
                "descricao_realizado" => $object->descricaoRealizado,
                "anexos" => json_encode($imagesPedido),
                "assinatura_tecnico" => $object->signatureTecnico,
                "assinatura_cliente" => $object->signatureCliente
            ]);


            //ALTERAR PARA CONCLUÍDO CASO ESTEJA CONCLUÍDA
            if($object->selectedEstado == "2")
            {
                Pedidos::where('id',$object->task->id)->update([
                    "estado" => "2"
                ]);
            }

        });
    }


}
