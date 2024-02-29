<?php

namespace App\Http\Livewire\Tenant\Tasks;

use App\Models\User;
use Livewire\Component;
use Livewire\Redirector;
use App\Events\ChatMessage;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;
use Livewire\WithFileUploads;
use App\Models\Tenant\Services;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\Prioridades;
use App\Models\Tenant\SerieNumbers;
use App\Models\Tenant\TiposPedidos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\TasksReports\TasksReportsInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;

class EditTasks extends Component
{
    use WithFileUploads;
    
    public string $homePanel = 'show active';
    public string $techPanel = '';
    public string $intervencoesPanel = '';
    public string $cancelButton = '';
    public string $actionButton = '';
    public string $stateEquipment = 'none';
    public string $stateAgenda = 'none';
    public string $tenant = "";


    public ?object $taskToUpdate = NULL;
    public ?string $taskReference = "";
    public int $selectedId;
    public string $selectedCustomer = '';
    protected ?object $customerList = NULL;
    public string $contactoAdicional = '';
    public string $selectedPedido = '';
    public ?object $pedidosList = NULL;
    public string $selectedServico = '';
    public ?object $servicosList = NULL;
    public string $serviceDescription = '';
    public string $descriptionReabertura = '';
    public ?object $membersList = NULL;
    protected ?object $customerLocations = NULL;
    public string $selectedLocation = '';
    public string $selectedEquipamentos = '';
    private ?object $equipamentosList = NULL;

    public $iteration = 0;
    public $iterationEquipment = 0;
    
    public string $dateCreate = '';
    public string $timeCreate = '';

    public string $selectedTechnician = '';
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

    public ?string $previewDate = NULL;
    public ?string $previewHour = NULL;
    public ?string $observacoesAgendar = NULL;

    //PARTE DE IR BUSCAR AS CORES

     public $uploadFile;
     public int $countFirstUpload = 0;
     public array $arrayFirstUploaded = [];
     public $uploadFileEquipamento;
     public int $countEquipamentoUploaded = 0;
     public array $arrayEquipamentoUploaded = [];

    public ?object $coresObject = NULL;

    public int $selectPrioridade;

    /**********/

    private TasksInterface $tasksInterface;
    private TasksReportsInterface $tasksReportsInterface;
    private CustomersInterface $customersInterface;
    private CustomerLocationsInterface $locationsInterface;

    protected $listeners = ['resetChanges' => 'resetChanges'];


    /**
     * Livewire construct function
     *
     * @param TasksInterface $tasksInterface
     * @return Void
     */
    public function boot(TasksInterface $tasksInterface, TasksReportsInterface $tasksReportsInterface,CustomersInterface $customersInterface,CustomerLocationsInterface $customerLocation): Void
    {
        $this->tasksInterface = $tasksInterface;
        $this->tasksReportsInterface = $tasksReportsInterface;
        $this->customersInterface = $customersInterface;
        $this->locationsInterface = $customerLocation;
    }

   
    /**
     * Initialize livewire component
     *
     * @param [type] $taskToUpdate
     * @return Void
     */
    public function mount($taskToUpdate): void
    {
        $this->tenant = tenant("id");
    
        $this->taskToUpdate = $taskToUpdate;
        $this->taskReference = $taskToUpdate->reference;
        $this->selectedId = $taskToUpdate->id;
       
        $this->selectedCustomer = $taskToUpdate->customer_id;
        $this->customerList = $this->customersInterface->getAllCustomersCollection();

        $this->selectedPedido = $taskToUpdate->tipo_pedido;
        $this->pedidosList = TiposPedidos::all();

        $this->selectedServico = $taskToUpdate->tipo_servico;
        $this->servicosList = Services::all();
        
        $this->membersList = TeamMember::all();

        $this->serviceDescription = $taskToUpdate->descricao;

        if($taskToUpdate->descricao_reabertura != null)
        {
            $this->descriptionReabertura = $taskToUpdate->descricao_reabertura;
        }
        

        //$this->customerLocations = CustomerLocations::where('customer_id',$taskToUpdate->customer_id)->get();
        $cust = $this->customersInterface->getSpecificCustomerInfo($taskToUpdate->customer_id);

        $this->customerLocations = $this->customersInterface->getLocationsFromCustomerCollection($cust->customers->no);
    
        $location = $this->locationsInterface->getSpecificLocationInfo($taskToUpdate->location_id);
        $this->selectedLocation = $location->locations->id;


        $this->selectedTechnician = $taskToUpdate->tech_id;
        $this->origem_pedido = $taskToUpdate->origem_pedido;
        $this->quem_pediu = $taskToUpdate->quem_pediu;
        $this->tipo_pedido = $taskToUpdate->tipo_agendamento;
        $this->selectPrioridade = $taskToUpdate->prioridade;

        $this->dateCreate = $taskToUpdate->created_at;
        $this->timeCreate = $taskToUpdate->created_at;

        if($taskToUpdate->alert_email != null)
        {
            $this->alert_email = $taskToUpdate->alert_email;
        }

        if($taskToUpdate->nr_serie != null)
        {
            $this->stateEquipment = "block";
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

        if($this->riscado != 0 || $this->partido != 0 || $this->bomestado != 0 || $this->normalestado != 0)
        {
            $this->stateEquipment = "block"; 
        }

        $this->transformador = $taskToUpdate->transformador;
        $this->mala = $taskToUpdate->mala;
        $this->tinteiro = $taskToUpdate->tinteiro;
        $this->ac = $taskToUpdate->ac;

        $this->descriptionExtra = $taskToUpdate->descricao_extra;

        $this->imagem = $taskToUpdate->imagem;

        $this->previewDate = $taskToUpdate->data_agendamento;
        $this->previewHour = $taskToUpdate->hora_agendamento;
        $this->observacoesAgendar = $taskToUpdate->observacoes_agendamento;


        $this->selectPrioridade = $taskToUpdate->prioridade;
        $this->coresObject = Prioridades::all();

        $getNumberImages = $taskToUpdate->anexos;
        foreach(json_decode($getNumberImages) as $img)
        {
            $this->countFirstUpload++;
            $this->arrayFirstUploaded[$this->countFirstUpload] = [$img];
        }

        $anexosEquipamentos = $taskToUpdate->anexos_equipamentos;
        foreach(json_decode($anexosEquipamentos) as $img)
        {
            $this->countEquipamentoUploaded++;
            $this->arrayEquipamentoUploaded[$this->countEquipamentoUploaded] = [$img];
        }
        
        $this->cancelButton = __('Back') . '<span class="btn-icon-right"><i class="las la-angle-double-left"></i></span>';
        $this->actionButton = __('Yes, update task');
    }


    public function downloadEtiqueta()
    {
        $this->dispatchBrowserEvent('contentChanged');
        return response()->download('cl/'.tenant('id') . '/app/impressoes/impressao'.$this->taskToUpdate->reference.'.pdf');
    }

    public function updatedUploadFile()
    {

        $this->countFirstUpload++;
        $this->arrayFirstUploaded[$this->countFirstUpload] = [$this->uploadFile];
    }

    public function updatedUploadFileEquipamento()
    {
        $this->countEquipamentoUploaded++;
        $this->arrayEquipamentoUploaded[$this->countEquipamentoUploaded] = [$this->uploadFileEquipamento];
    }

    public function updatedSelectedCustomer(): Void
    {
        // if(!empty($this->customer))
        // {
        //     $this->dispatchBrowserEvent('refreshPage');
        // }

        $customer = Customers::where('id', $this->selectedCustomer)->with('customerCounty')->with('customerDistrict')->first();
        $this->customerLocations = CustomerLocations::where('customer_id', $this->selectedCustomer)->with('locationCounty')->get();

        $conta = count($this->customerLocations);

        if($conta == 1)
        {
            $this->selectedLocation = $this->customerLocations[0]->id;
        }else {
            $this->selectedLocation = "";
        }
        
        $this->dispatchBrowserEvent('contentChanged');
        $this->iteration++;
        $this->iterationEquipment++;
        

        if(!isset($customer->customerCounty))
        {
             $this->dispatchBrowserEvent('swal', ['title' => __('Services'), 'message' => __('You need to select a county for this customer'), 'status'=>'error','function' => 'client']);
             $this->skipRender();
        }
 
    }

    

    

    public function refreshPedido()
    {

        //RECEBER OS VALORES TODOS
        $validatedData = Validator::make(
         [
            'selectedCustomer' => $this->selectedCustomer,
            'selectedPedido' => $this->selectedPedido,
            'selectedServico' => $this->selectedServico,
            'serviceDescription' => $this->serviceDescription,
            'selectedLocation' => $this->selectedLocation,
            'selectPrioridade' => $this->selectPrioridade,
            'selectedTechinician' => $this->selectedTechnician,
            'origem_pedido' => $this->origem_pedido,
            'tipo_pedido' => $this->tipo_pedido,
            'quem_pediu' => $this->quem_pediu,
         ],
         [
            'selectedCustomer' => 'required|string',
            'selectedPedido' => 'required|int',
            'selectedServico' => 'required|int',
            'serviceDescription' => 'required|string',
            'selectedLocation' => 'required|string',
            'selectPrioridade' => 'required|int',
            'selectedTechinician' => 'required|int',
            'origem_pedido' => 'required|string',
            'tipo_pedido' => 'required|string',
            'quem_pediu' => 'required|string',
        ],
        [
            'selectedCustomer'  => __('Tem de selecionar um cliente!'),
            'selectedPedido' => __("Tem de selecionar um tipo de pedido!"),
            'selectedServico' => __('Tem de selecionar um tipo de serviço!'),
            'serviceDescription' => __('Tem de selecionar uma descrição!'),
            'selectedLocation' => __('Tem de selecionar uma localização de cliente!'),
            'selectPrioridade' => __('Tem de selecionar uma prioridade!'),
            'selectedTechnician' => __('Tem de selecionar um técnico!'),
            'origem_pedido' => __('Tem de selecionar uma origem de pedido!'),
            'tipo_pedido' => __('Tem de selecionar o tipo de agendamento!'),
            'quem_pediu' => __('Tem de selecionar quem pediu!'),
        ]);

        //MANDA OS ERROS
        if ($validatedData->fails()) {
            $errorMessage = '';
            foreach($validatedData->errors()->all() as $message) {
                $errorMessage .= '<p>' . $message . '</p>';
            }
            $this->dispatchBrowserEvent('swal', ['title' => "Identificação", 'message' => $errorMessage, 'status'=>'error', 'whatfunction'=>"add"]);
            return;
        }

      
        //VERIFICA SE JA EXISTE ESSE NUMERO DE SERIE
        $serieNumberSearch = SerieNumbers::where('nr_serie',$this->serieNumber)->first();

        if($serieNumberSearch == null)
        {
            SerieNumbers::Create([
                "nr_serie" => $this->serieNumber,
                "marca" => $this->marcaEquipment,
                "modelo" => $this->modelEquipment
            ]);
        }
      
      
        //fazer a criacao do PDF da etiqueta
        if($this->serieNumber != null)
        {
            $customer = $this->customersInterface->getSpecificCustomerInfo($this->selectedCustomer);
            $qrcode = base64_encode(QrCode::size(150)->generate('https://hihello.me/pt/p/adc8b89e-a3de-4033-beeb-43384aafa1c3?f=email'));
       
            $customPaper = array(0, 0, 400.00, 216.00);
            $pdf = PDF::loadView('tenant.livewire.tasks.impressaopdf',["impressao" => $this,"customer" => $customer, "qrcode" => $qrcode])->setPaper($customPaper);
        
            $content = $pdf->download()->getOriginalContent();

            $this->imagem = 'impressao'.$this->taskReference.'.pdf';

    
            Storage::put(tenant('id') . '/app/public/pedidos/etiquetas/'.$this->taskReference.'/etiqueta'.$this->taskReference.'.pdf',$content);
        }



       
        //FAZ ADICIONAR Á BASE DE DADOS
        $pedido = $this->tasksInterface->updatePedido($this);

        //GRAVA AS IMAGENS
        
       
        if(!empty($this->arrayFirstUploaded)){
            foreach($this->arrayFirstUploaded as $img)
            {
                if (!is_string($img[0])) {
    
                    $img[0]->storeAs(tenant('id') . '/app/public/pedidos/imagens_pedidos/'.$this->taskReference.'/', $img[0]->getClientOriginalName());
                }
               
            }
        }

        if(!empty($this->arrayEquipamentoUploaded)){
            foreach($this->arrayEquipamentoUploaded as $img)
            {
                if (!is_string($img[0])) {
                
                    $img[0]->storeAs(tenant('id') . '/app/public/pedidos/equipamentos_pedidos/'.$this->taskReference.'/', $img[0]->getClientOriginalName());
                }
            }
        }


       
        
        //Checka o Nao enviar email de alerta se devo enviar email ou nao
        // if($this->alert_email == 0)
        // {
        //     event(new TaskCustomer($this->pedido_id));
        // }

       

        return redirect()->route('tenant.tasks.index')
            ->with('message', "Pedido editado com sucesso!")
            ->with('status', 'info');
     

    }

    /**
     * Checks if the task was changed and if so asks tbe user if he wants to loose changes or redirect to list of tasks
     *
     * @return null or redirect response
     */
    public function cancel(): NULL|Redirector
    {
        // if($this->changed == true )
        // {
        //     return $this->askUserLooseChanges();
        // }
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

    public function updatedSelectedEquipamentos()
    {
        $infoEquipamento = $this->tasksInterface->getEquipmentBySerial($this->selectedEquipamentos);

        $this->serieNumber = $infoEquipamento->equipments->serialnumber;
        $this->marcaEquipment = $infoEquipamento->equipments->brand;
        $this->modelEquipment = $infoEquipamento->equipments->model;

        $this->nameEquipment = $infoEquipamento->equipments->equipmentname;
        $this->descriptionEquipment = $infoEquipamento->equipments->description;

        $this->riscado = $infoEquipamento->equipments->riscado;
        $this->partido = $infoEquipamento->equipments->partido;
        $this->bomestado = $infoEquipamento->equipments->bom_estado;
        $this->normalestado = $infoEquipamento->equipments->estado_normal;
        $this->transformador = $infoEquipamento->equipments->transformador;
        $this->mala = $infoEquipamento->equipments->mala;
        $this->tinteiro = $infoEquipamento->equipments->tinteiro_toner;
        $this->ac = $infoEquipamento->equipments->rato_pen;
        $this->descriptionExtra = $infoEquipamento->equipments->extradescription;
    }

    /**
     * Returns the view of the task edit
     *
     * @return View
     */
    public function render(): View
    {
        $getClient = $this->customersInterface->getSpecificCustomerInfo($this->selectedCustomer);
        $this->equipamentosList = $this->tasksInterface->getEquipments($getClient->customers->no);

        return view('tenant.livewire.tasks.edit',["customersInterface" => $this->customersInterface, "locationsInterface" => $this->locationsInterface, "customerList" => $this->customerList, "customerLocations" => $this->customerLocations, "equipamentosList" => $this->equipamentosList]);
    }

}
