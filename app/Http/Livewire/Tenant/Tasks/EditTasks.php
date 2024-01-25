<?php

namespace App\Http\Livewire\Tenant\Tasks;

use App\Models\User;
use Livewire\Component;
use Livewire\Redirector;
use App\Events\ChatMessage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\Prioridades;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\TasksReports\TasksReportsInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;

class EditTasks extends Component
{
    public string $homePanel = 'show active';
    public string $servicesPanel = '';
    public string $equipmentPanel = '';
    public string $techPanel = '';
    public string $cancelButton = '';
    public string $actionButton = '';

    public ?object $customerList = NULL;
    public ?object $taskToUpdate = NULL;
    public ?object $customerServicesList = NULL;
    public ?object $teamMembers = NULL;
    public ?object $tempSelectedServices = NULL;
    public ?object $servicesToDo = NULL;
    public string $selectedTechnician = '';

    public string $selectedLocation = '';
    public int $numberOfSelectedServices = 0;
    public array $selectedServiceId = [];
    public array $serviceDescription = [];


    public ?string $resume = '';
    public ?string $taskAdditionalDescription = '';
    public bool $changed = false;

    public ?string $previewDate = NULL;
    public ?string $previewHour = NULL;
    public ?string $scheduledHour = NULL;
    public ?string $scheduledDate = NULL;

    public ?string $origem_pedido = NULL;
    public ?string $quem_pediu = NULL;
    public ?string $tipo_pedido = NULL;

    public ?int $alert_email = 0;

    //Vem do Equipamento

    public ?string $serieNumber = '';
    public ?string $marcaEquipment = '';
    public ?string $modelEquipment = '';

    public ?string $nameEquipment = '';
    public ?string $descriptionEquipment = '';

    public ?int $riscado = 0;
    public ?int $partido = 0;
    public ?int $bomestado = 0;
    public ?int $normalestado = 0;

    public ?int $transformador = 0;
    public ?int $mala = 0;
    public ?int $tinteiro = 0;
    public ?int $ac = 0;

    public ?string $descriptionExtra = '';
    public ?string $imagem = '';

    //PARTE DE IR BUSCAR AS CORES

    public ?object $coresObject = NULL;

    public int $selectPrioridade;

    /**********/

    private TasksInterface $tasksInterface;
    private TasksReportsInterface $tasksReportsInterface;

    protected $listeners = ['resetChanges' => 'resetChanges'];


    /**
     * Livewire construct function
     *
     * @param TasksInterface $tasksInterface
     * @return Void
     */
    public function boot(TasksInterface $tasksInterface, TasksReportsInterface $tasksReportsInterface): Void
    {
        $this->tasksInterface = $tasksInterface;
        $this->tasksReportsInterface = $tasksReportsInterface;
    }

    private function restartCustomerServiceList($tcl = NULL)
    {
        $this->numberOfSelectedServices = $this->taskToUpdate->servicesToDo->count();
        $this->customerServicesList = $this->taskToUpdate->customerServiceList;
        $this->servicesToDo = $this->taskToUpdate->servicesTodo;

        if($tcl != NULL && $tcl->count() == 0) {
            $this->selectedServiceId = [];
            $this->serviceDescription = [];
            $this->servicesToDo = NULL;
            $this->numberOfSelectedServices = 0;
        } else {
            foreach($this->servicesToDo as $item) {
                $this->selectedServiceId[$item->task_service_id] = $item->service->id;
                $this->serviceDescription[] = $item->additional_description;
            }
        }
    }
    /**
     * Initialize livewire component
     *
     * @param [type] $taskToUpdate
     * @return Void
     */
    public function mount($taskToUpdate): void
    {
        $this->taskToUpdate = $taskToUpdate;

        $this->restartCustomerServiceList();
        $this->selectedTechnician = $taskToUpdate->tech_id;
        $this->taskAdditionalDescription = $taskToUpdate->additional_description;
        $this->resume = $taskToUpdate->resume;
        $this->selectedLocation = $taskToUpdate->location_id;

        $this->previewDate = $taskToUpdate->preview_date;
        $this->previewHour = $taskToUpdate->preview_hour;
        $this->scheduledDate = $taskToUpdate->scheduled_date;
        $this->scheduledHour = $taskToUpdate->scheduled_hour;

        $this->origem_pedido = $taskToUpdate->origem_pedido;
        $this->quem_pediu = $taskToUpdate->quem_pediu;
        $this->tipo_pedido = $taskToUpdate->tipo_pedido;

        if($taskToUpdate->alert_email != null)
        {
            $this->alert_email = $taskToUpdate->alert_email;
        }

        $this->serieNumber = $taskToUpdate->nr_serie;
        $this->marcaEquipment = $taskToUpdate->marca;
        $this->modelEquipment = $taskToUpdate->modelo;

        $this->nameEquipment = $taskToUpdate->nome_equipamento;
        $this->descriptionEquipment = $taskToUpdate->descricao_equipamento;

        $this->riscado = $taskToUpdate->riscado;
        $this->partido = $taskToUpdate->partido;
        $this->bomestado = $taskToUpdate->bom_estado;
        $this->normalestado = $taskToUpdate->estado_normal;

        $this->transformador = $taskToUpdate->transformador;
        $this->mala = $taskToUpdate->mala;
        $this->tinteiro = $taskToUpdate->tinteiro;
        $this->ac = $taskToUpdate->ac;

        $this->descriptionExtra = $taskToUpdate->descricao_extra;

        $this->imagem = $taskToUpdate->imagem;


        $this->selectPrioridade = $taskToUpdate->prioridade;
        $this->coresObject = Prioridades::all();

        $this->teamMembers = TeamMember::where('checkstatus',1)->get();

        
        $this->cancelButton = __('Back') . '<span class="btn-icon-right"><i class="las la-angle-double-left"></i></span>';;
        $this->actionButton = __('Yes, update task');
    }


    public function downloadEtiqueta()
    {
        $this->dispatchBrowserEvent('contentChanged');
        return response()->download('cl/'.tenant('id') . '/app/impressoes/impressao'.$this->taskToUpdate->reference.'.pdf');
    }

    /**
     * When the service is select is checks if it is not duplicated and stores the property
     * Else it cleans the property and assigns a new created array without duplicate services
     *
     * @return Void
     */
    public function updatedSelectedServiceId(): Void
    {
        $this->homePanel = '';
        $this->servicesPanel = 'show active';
        $this->techPanel = '';
        $this->changed = true;

        $tempArray = [];
        foreach($this->selectedServiceId as $key => $item) {
            if(!in_array($item, $tempArray)) {
                $tempArray[$key] = $item;
            } elseif (in_array($item, $tempArray) && $item == '') {

            }else {
                $tempArray[$key] = '';
                $this->dispatchBrowserEvent('swal', ['title' => __('Task Services'), 'message' => __('Cannot add the same service!'), 'status'=>'error']);
            }
        }
        $this->selectedServiceId = $tempArray;
        $this->dispatchBrowserEvent('contentChanged');
    }

    /**
     * Changes the changed var to keep track to updated values
     *
     * @return void
     */
    public function updatedServiceDescription()
    {
        $this->changed = true;
    }

    /**
     * Changes the changed var to keep track to updated values
     *
     * @return void
     */
    public function updatedSelectedTechnician(): Void
    {
        $this->homePanel = '';
        $this->servicesPanel = '';
        $this->techPanel = 'show active';
        $this->changed = true;
    }

    /**
     * Changes the changed var to keep track to updated values
     *
     * @return void
     */
    public function updatedPreviewDate()
    {
        $this->homePanel = '';
        $this->servicesPanel = '';
        $this->techPanel = 'show active';
        $this->changed = true;
    }

    /**
     * Changes the changed var to keep track to updated values
     *
     * @return void
     */
    public function updatedPreviewHour()
    {
        $this->homePanel = '';
        $this->servicesPanel = '';
        $this->techPanel = 'show active';
        $this->changed = true;
    }

    /**
     * Changes the changed var to keep track to updated values
     *
     * @return void
     */
    public function updatedScheduledDate()
    {
        $this->homePanel = '';
        $this->servicesPanel = '';
        $this->techPanel = 'show active';
        $this->changed = true;
    }

    /**
     * Changes the changed var to keep track to updated values
     *
     * @return void
     */    public function updatedScheduledHour()
    {
        $this->homePanel = '';
        $this->servicesPanel = '';
        $this->techPanel = 'show active';
        $this->changed = true;
    }

    /**
     * Changes the changed var to keep track to updated values
     *
     * @return void
     */
    public function updatedTaskAdditionalDescription()
    {
        $this->homePanel = '';
        $this->servicesPanel = '';
        $this->techPanel = 'show active';
        $this->changed = true;
    }

    /**
     * Removes a service, removes fields from the task
     *
     * @return Void
     */
    public function removeServiceForm($id)
    {
        unset($this->selectedServiceId[$id]);
        unset($this->serviceDescription[$id]);
        $this->numberOfSelectedServices--;
        $this->changed = true;
        $this->dispatchBrowserEvent('contentChanged');
    }

    /**
     * Add a new service, adding news fields to the task
     *
     * @return Void
     */
    public function addServiceForm(): Void
    {
        if(!$this->customerServicesList) {
            $this->homePanel = '';
            $this->servicesPanel = 'show active';
            $this->techPanel = '';
            $this->dispatchBrowserEvent('swal', ['title' => __('Task Services'), 'message' => __('You must select the location!'), 'status'=>'error']);
            return;
        }

        if($this->customerServicesList->count() > $this->numberOfSelectedServices) {
            $this->numberOfSelectedServices++;
            $this->homePanel = '';
            $this->servicesPanel = 'show active';
            $this->techPanel = '';
            $this->dispatchBrowserEvent('newService');
        } else {
             $this->dispatchBrowserEvent('swal', ['title' => __('Task Services'), 'message' => __('You cannot add more services to this location!'), 'status'=>'error']);
        }
    }

    /**
     * Saves the task
     *
     * @return Void
     */
    public function saveTask()
    {

        $validator = Validator::make(
            [
                'selectedLocation'  => $this->selectedLocation,
                'selectedServiceId' => $this->selectedServiceId,
                'selectedTechnician' => $this->selectedTechnician,
                'previewDate' => $this->previewDate,
                'origem_pedido' => $this->origem_pedido,
                'quem_pediu' => $this->quem_pediu,
                'tipo_pedido' => $this->tipo_pedido
            ],
            [
                'selectedLocation'  => 'required|numeric|min:0|not_in:0',
                'selectedServiceId' => function ($attribute, $value, $fail)
                {
                    $found = false;
                    foreach($value as $item) {
                        if($item != '') {
                            $found = true;
                        }
                    }
                   if ($found == false) {
                       $fail('The :attribute must be uppercase.');
                   }
                },
                'selectedTechnician'  => 'required|numeric|min:0|not_in:0',
                'previewDate'  => 'required|string',

                'origem_pedido'  => 'required|string',
                'quem_pediu'  => 'required|string',
                'tipo_pedido'  => 'required|string',

            ],
            [
                'selectedCustomer'  => __('You must select the customer location!'),
                'numberOfSelectedServices' => __('You must select at least a service!'),
                'selectedServiceId' => __('You must select at least a service!'),
                'selectedTechnician' => __('You must select someone to perform this task!'),
                'previewDate' => __('You must select, at least, the preview date!'),

                'origem_pedido' => __('You must select, a request origin!'),
                'quem_pediu' => __('You must select, who asked!'),
                'tipo_pedido' => __('You must select, a type of request!'),
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= '<p>' . $message . '</p>';
            }
            $this->dispatchBrowserEvent('swal', ['title' => __('Services'), 'message' => $errorMessage, 'status'=>'error']);
            return NULL;
        }

        $taskReport = $this->tasksReportsInterface->getReportByTaskId($this->taskToUpdate->id);
        if($taskReport != null && $taskReport->count() > 0 ) {
            // if(!$this->tasksReportsInterface->destroyReportByTaskId($this->taskToUpdate->id)) {
            //     return redirect()->route('tenant.tasks.index')
            //         ->with('message', __('There was an error while trying to update the task!'))
            //         ->with('status', 'error');
            // }
        }

          //fazer a criacao do PDF
          if($this->riscado != 0 || $this->partido != 0 || $this->bomestado != 0 || $this->normalestado != 0 || $this->transformador != 0 || $this->mala != 0 || $this->tinteiro != 0 || $this->ac != 0)
          {
              $qrcode = base64_encode(QrCode::size(150)->generate('https://hihello.me/pt/p/adc8b89e-a3de-4033-beeb-43384aafa1c3?f=email'));
         
              $customPaper = array(0, 0, 400.00, 216.00);
              $pdf = PDF::loadView('tenant.livewire.tasks.impressaopdf',["impressao" => $this, "qrcode" => $qrcode])->setPaper($customPaper);
      
              if(!Storage::exists(tenant('id') . '/app/impressoes'))
              {
                  File::makeDirectory(storage_path('app/impressoes'), 0755, true, true);
              }
      
              $content = $pdf->download()->getOriginalContent();
  
              $this->imagem = 'impressao'.$this->taskToUpdate->reference.'.pdf';
  
      
              Storage::put(tenant('id') . '/app/impressoes/impressao'.$this->taskToUpdate->reference.'.pdf',$content);
          }
     
        if($this->tasksInterface->updateTask($this->taskToUpdate, $this) === false) {
            $this->dispatchBrowserEvent('swal', ['title' => __('Services'), 'message' => 'There was an error updating the task!', 'status'=>'error']);
            return NULL;
        }
        #$this->taskToUpdate = $this->tasksInterface->updateTask($this->taskToUpdate, $this);
        #$this->taskToUpdate = $this->taskToUpdate;
        $this->changed = false;
        //$this->dispatchBrowserEvent('loading');

        $message = "atualizou uma tarefa";
        $usr = User::where('id',Auth::user()->id)->first();

        event(new ChatMessage($usr->name, $message));

        return redirect()->route('tenant.tasks.index')
            ->with('message', __('Task updated with success!'))
            ->with('status', 'info');
    }

    /**
     * Checks if the task was changed and if so asks tbe user if he wants to loose changes or redirect to list of tasks
     *
     * @return null or redirect response
     */
    public function cancel(): NULL|Redirector
    {
        if($this->changed == true )
        {
            return $this->askUserLooseChanges();
        }
        //$this->dispatchBrowserEvent('loading');
        return redirect()->route('tenant.tasks.index');
    }

    /**
     * Ask user if he wants to loose the changes made
     *
     * @return Void
     */
    public function askUserLooseChanges(): Void
    {
        $this->dispatchBrowserEvent('swal', [
            'title' => __('Task Services'),
            'message' => __('Are you sure? You will loose all the unsaved changes!'),
            'status' => 'question',
            'confirm' => 'true',
            'page' => "edit",
            'customer_id' => 1,
            'confirmButtonText' => __('Yes, loose changes!'),
            'cancellButtonText' => __('No, keep changes!'),
        ]);
    }

    /**
     * Confirms the cancelation of the task report
     *
     * @return Redirector
     */
    public function resetChanges(): Redirector
    {
        //$this->dispatchBrowserEvent('loading');
        return redirect()->route('tenant.tasks.index')
            ->with('message', __('Task updated canceled, all changes where lost!'))
            ->with('status', 'info');
    }

    /**
     * Returns the view of the task edit
     *
     * @return View
     */
    public function render(): View
    {
        return view('tenant.livewire.tasks.edit');
    }

}
