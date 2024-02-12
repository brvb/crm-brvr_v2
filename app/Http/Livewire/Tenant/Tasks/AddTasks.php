<?php

namespace App\Http\Livewire\Tenant\Tasks;

use App\Models\User;
use Livewire\Component;
use Livewire\Redirector;

use App\Events\ChatMessage;

use App\Models\Tenant\Tasks;
use Livewire\WithFileUploads;
use App\Models\Tenant\Services;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Tenant\Customers;
use App\Events\Tasks\TaskCreated;
use App\Models\Tenant\TeamMember;
use App\Events\Tasks\TaskCustomer;
use App\Models\Tenant\Prioridades;
use App\Models\Tenant\SerieNumbers;
use App\Models\Tenant\TiposPedidos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use SebastianBergmann\Type\VoidType;
use App\Models\Tenant\CustomerServices;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\GenerateTaskReference;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;
use App\Models\Tenant\Pedidos;

class AddTasks extends Component
{
    use GenerateTaskReference;
    use WithFileUploads;

    public string $homePanel = 'show active';
    public string $techPanel = '';
    public string $cancelButton = '';
    public string $actionButton = '';
    public string $stateEquipment = 'none';
    public string $stateAgenda = 'none';

    public string $selectedCustomer = '';
    public ?object $customerList = NULL;
    public string $contactoAdicional = '';
    public string $selectedPedido = '';
    public ?object $pedidosList = NULL;
    public string $selectedServico = '';
    public ?object $servicosList = NULL;
    public string $serviceDescription = '';

    public $iteration = 0;

    //Parte das Imagens

    public $uploadFile;
    public int $countFirstUpload = 0;
    public array $arrayFirstUploaded = [];
    public $uploadFileEquipamento;
    public int $countEquipamentoUploaded = 0;
    public array $arrayEquipamentoUploaded = [];

    /****/

    //PARTE DO EQUIPAMENTO

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


    /********* */

    /**ID DO PEDIDO ADICIONADO */

    public $pedido_id;

    /********* */

    /** PARTE DO AGENDAMENTO */
    public ?object $membersList = NULL;
    public string $selectedTechnician = '';
    public ?string $origem_pedido = NULL;
    public ?string $quem_pediu = NULL;
    public ?string $tipo_pedido = NULL;
    public ?string $previewDate = NULL;
    public ?string $previewHour = NULL;
    public ?string $observacoesAgendar = NULL;
    

    /******* */

    public ?object $customer = NULL;
    public string $selectedLocation = '';
    public ?object $customerServicesList = NULL;
    public ?object $customerLocations = NULL;

   
   
   
    public ?object $teamMembers = NULL;
    public ?string $resume = '';
    public ?string $taskAdditionalDescription = '';
    public ?string $taskReference = NULL;
    public int $number = 0;

    public int $alert_email;


   

    //PARTE DE IR BUSCAR AS CORES

    public ?object $coresObject = NULL;

    public ?int $selectPrioridade;

    /**********/

    private CustomerServicesInterface $customerServicesInterface;
    private TasksInterface $tasksInterface;

    protected $listeners = ['resetChanges' => 'resetChanges', 'responseEmailCustomer' => 'responseEmailCustomer', 'FormAddClient' => 'FormAddClient', 'createCustomerFormResponse' => 'createCustomerFormResponse'];

    /**
     * Livewire construct function
     *
     * @param TasksInterface $tasksInterface
     * @return Void
     */
    public function boot(CustomerServicesInterface $customerServicesInterface, TasksInterface $tasksInterface): Void
    {
        $this->customerServicesInterface = $customerServicesInterface;
        $this->tasksInterface = $tasksInterface;
    }

    public function mount($customerList): void
    {
        $this->customerList = $customerList;
        $this->pedidosList = TiposPedidos::all();
        $this->servicosList = Services::all();

        $this->membersList = TeamMember::all();


        $this->cancelButton = '<i class="las la-angle-double-left mr-2"></i>' . __('Back');
        $this->actionButton = __('Create Task');

        $this->coresObject = Prioridades::all();

        $this->alert_email = 0;
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

        $this->customer = Customers::where('id', $this->selectedCustomer)->with('customerCounty')->with('customerDistrict')->first();
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
        

        if($this->customer->customerCounty == null)
        {
             $this->dispatchBrowserEvent('swal', ['title' => __('Services'), 'message' => __('You need to select a county for this customer'), 'status'=>'error','function' => 'client']);
             $this->skipRender();
        }
 
    }

      

    public function FormAddClient()
    {
        $message = "";

        $message = "<div class='swalBox'>";
            $message .= "<div class='row mt-4' style='justify-content:center;'>";
                $message .= "<section class='col-xl-12'>";
                  $message .= "<label>Nome Cliente</label>";
                  $message .= "<input type='text' name='customer_name' id='customer_name' class='form-control'>";
                $message .= "</section>";
                $message .= "<section class='col-xl-12'>";
                    $message .= "<label>NIF</label>";
                    $message .= "<input type='text' name='nif' id='nif' class='form-control'>";
                $message .= "</section>";
                $message .= "<section class='col-xl-12'>";
                    $message .= "<label>Contacto</label>";
                    $message .= "<input type='text' name='contact' id='contact' class='form-control'>";
                $message .= "</section>";
                $message .= "<section class='col-xl-12' style='margin-bottom:20px;'>";
                    $message .= "<label>Email</label>";
                    $message .= "<input type='text' name='email' id='email' class='form-control'>";
                $message .= "</section>";
                $message .= "<button type='button' id='buttonresponseCustomer' data-anwser='ok' class='btn btn-primary'>Enviar</button>";
                $message .= "&nbsp;<button type='button' class='btn btn-secondary' id='buttonresponseCustomer' data-anwser='close'>Fechar</button>";
            $message .= "</div>";
        $message .= "</div>";

        $this->dispatchBrowserEvent('createCustomer', ['title' => __('Formulário Cliente'), 'message' => $message, 'status' => 'info']);
    }

    public function createCustomerFormResponse($name,$nif,$contact,$email)
    {
       $allLower = strtolower($name);
       
       $slug = str_replace(" ","-",$allLower);

       $validator = Validator::make(
            [
                'name' => $name,
                'nif'  => $nif,
                'contact' => $contact,
                'email' => $email
            ],
            [
                'name'  => 'required',
                'nif'  => 'required|min:9|max:9',
                'contact'  => 'required',
                'email' => 'required'
            ],
            [
                'name'  => "Tem de inserir um nome!",
                'nif' => "Tem de inserir um nif com 9 digitos!",
                'contact' => "Tem de inserir um contacto!",
                'email' => "Tem de inserir um email!"
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= '<p>' . $message . '</p>';
            }
            $this->dispatchBrowserEvent('swal', ['title' => __('Inserir Cliente'), 'message' => $errorMessage, 'status'=>'error', 'whatfunction'=>"add"]);
            return;
        }

        $checkBefore = Customers::where('vat', $nif)->first();

        if($checkBefore != null)
        {
            $this->dispatchBrowserEvent('swal', ['title' => __('Inserir Cliente'), 'message' => "Esse cliente já se encontra registado", 'status'=>'error', 'whatfunction'=>"add"]);
            return;
        }

        $response = Customers::Create([
            "name" => $name,
            "slug" => $slug,
            "short_name" => $slug,
            "username" => $email,
            "vat" => $nif,
            "contact" => $contact,
            "email" => $email,
            "address" => "Rua de Regufe, 33",
            "zipcode" => "4480-246",
            "district" => '13',
            "county" => '16',
            "account_manager" => '9'
        ]);

        $location = CustomerLocations::Create([
            "description" => "Sede",
            "customer_id" => $response->id,
            "main" => "1",
            "address" => "Rua de Regufe, 33",
            "zipcode" => "4480-246",
            "contact" => $contact,
            "district_id" => '13',
            "county_id" => '16',
            "manager_name" => "Vitor Oliveira",
            "manager_contact" => $contact
        ]);

        CustomerServices::Create([
            "customer_id" => $response->id,
            "service_id" => "4",
            "location_id" => $location->id,
            "start_date" => date('Y-m-d')
        ]);



        $this->dispatchBrowserEvent('swal', ['title' => "Cliente", 'message' => 'Cliente criado com sucesso!', 'status'=>'sucess', 'whatfunction'=>"finishInsert"]);

    }

    public function searchSerieNumber()
    {
        $response = $this->tasksInterface->searchSerialNumber($this->serieNumber);

        if(!isset($response[0]->marca)){
            $this->marcaEquipment = '';
        }
        else {
            $this->marcaEquipment = $response[0]->marca;
        }

        if(!isset($response[0]->modelo)){
            $this->modelEquipment = '';
        }
        else {
            $this->modelEquipment = $response[0]->modelo;
        }

        if($this->serieNumber == "")
        {
            $this->marcaEquipment = '';
            $this->modelEquipment = '';
        }

        
    }

    public function savePedido()
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
            'selectedCustomer' => 'required|int',
            'selectedPedido' => 'required|int',
            'selectedServico' => 'required|int',
            'serviceDescription' => 'required|string',
            'selectedLocation' => 'required|int',
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

        $latest = Pedidos::latest()->first();

        if($latest == null)
        {
            $this->number = 1;
        }
        else if (strpos($latest->created_at, date('Y-m')) === false) {
            $this->number = 1;
        } else {
            // $this->number = $latest('number') + 1;
            $this->number = $latest->number + 1;
        }

        $this->taskReference = $this->taskReference($this->number);


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
        if($this->riscado != 0 || $this->partido != 0 || $this->bomestado != 0 || $this->normalestado != 0 || $this->transformador != 0 || $this->mala != 0 || $this->tinteiro != 0 || $this->ac != 0)
        {
            $qrcode = base64_encode(QrCode::size(150)->generate('https://hihello.me/pt/p/adc8b89e-a3de-4033-beeb-43384aafa1c3?f=email'));
       
            $customPaper = array(0, 0, 400.00, 216.00);
            $pdf = PDF::loadView('tenant.livewire.tasks.impressaopdf',["impressao" => $this, "qrcode" => $qrcode])->setPaper($customPaper);
        
            $content = $pdf->download()->getOriginalContent();

            $this->imagem = 'impressao'.$this->taskReference.'.pdf';

    
            Storage::put(tenant('id') . '/app/public/pedidos/etiquetas/'.$this->taskReference.'/etiqueta'.$this->taskReference.'.pdf',$content);
        }



       
        //FAZ ADICIONAR Á BASE DE DADOS
        $this->pedido_id = $this->tasksInterface->createPedido($this);

        //GRAVA AS IMAGENS
        
        if(!empty($this->arrayFirstUploaded)){
            foreach($this->arrayFirstUploaded as $img)
            {
                $img[0]->storeAs(tenant('id') . '/app/public/pedidos/imagens_pedidos/'.$this->taskReference.'/', $img[0]->getClientOriginalName());
            }
        }

        if(!empty($this->arrayEquipamentoUploaded)){
            foreach($this->arrayEquipamentoUploaded as $img)
            {
                $img[0]->storeAs(tenant('id') . '/app/public/pedidos/equipamentos_pedidos/'.$this->taskReference.'/', $img[0]->getClientOriginalName());
            }
        }


       
        
        //Checka o Nao enviar email de alerta se devo enviar email ou nao
        if($this->alert_email == 0)
        {
            event(new TaskCustomer($this->pedido_id));
        }


        //TENTAR VER ESTA SITUAÇÃO PARA ENVIAR PARA O DASHBOARD
        $usr = User::where('id',Auth::user()->id)->first();
        $pedido = Pedidos::where('id',$this->pedido_id->id)->first();
        $teamM = TeamMember::where('id',$pedido->tech_id)->first();


        if(Auth::user()->id == $teamM->user_id){
            $message = "adicionou um pedido novo";
        } else {
            $message = "adicionou um pedido novo para ".$teamM->name."";
        }

        
        event(new ChatMessage($usr->name, $message));

       

        return redirect()->route('tenant.tasks.index')
            ->with('message', "Pedido criado com sucesso!")
            ->with('status', 'info');
     

    }

    

    public function responseEmailCustomer($email,$response,$responseEmailCustomer)
    {

        if($response == "ok")
        {
            event(new TaskCustomer($responseEmailCustomer));
        }

          return redirect()->route('tenant.tasks.index')
             ->with('message', __('Task created with success!'))
             ->with('status', 'info');
    }

    public function cancel()
    {
        return $this->askUserLooseChanges();
    }

    public function askUserLooseChanges(): Void
    {
        $this->dispatchBrowserEvent('swal', [
            'title' => __('Task Services'),
            'message' => __('Are you sure? You will loose all the unsaved changes!'),
            'status' => 'question',
            'confirm' => 'true',
            'page' => "add",
            'customer_id' => 1,
            'confirmButtonText' => __('Yes, loose changes!'),
            'cancellButtonText' => __('No, keep changes!'),
        ]);
    }

    public function resetChanges(): Redirector
    {
        //$this->dispatchBrowserEvent('loading');
        session()->put('message', 'Post successfully updated.');
        session()->put('status', 'info');

        return redirect()->route('tenant.tasks.index')
            ->with('message', __('Task updated canceled, all changes where lost!'))
            ->with('status', 'info');
    }

    public function render(): View
    {
        return view('tenant.livewire.tasks.add');
    }

}