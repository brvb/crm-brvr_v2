<?php

namespace App\Http\Livewire\Tenant\TasksReports;

use App\Models\User;
use Livewire\Component;
use Livewire\Redirector;
use App\Events\ChatMessage;
use Livewire\WithFileUploads;
use App\Models\Tenant\EstadoPedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\TasksTimes\TasksTimesInterface;
use App\Interfaces\Tenant\TasksReports\TasksReportsInterface;

class EditTasksReports extends Component
{

    use WithFileUploads;

    private TasksInterface $tasksInterface;


    public string $searchString = '';
    public string $reportPanel = 'active show';
    public string $timesPanel = '';

    public ?object $task = NULL;
    public ?object $statesPedido = NULL;

    public string $descricaoPanel = 'none';
    public string $signaturePad = 'none';
    public string $selectedEstado = '';
    public int $horasAlterado;
    public string $referencia_intervencao = '';
    public string $descricao_intervencao = '';
    public string $quantidade_intervencao ='';
    public string $descricaoRealizado = '';
    public $uploadFile;
    public int $countFirstUpload = 0;
    public array $arrayFirstUploaded = [];
    public $signatureClient;
    public $signatureTecnico;
    public $email_pdf;
    
    public ?object $taskTimes =  NULL;

    public int $reportInfo  = 1;

    public ?object $taskReportCollection = NULL;
    public array $arrayReport = [];
    public bool $changed = false;

    protected $listeners = ['resetChanges' => 'resetChanges', 'signaturePads' => 'signaturePads','signaturePadsClear' => 'signaturePadsClear'];

     /**
     * Livewire construct function
     *
     * @param TasksInterface $tasksInterface
     * @return Void
     */
    public function boot(TasksInterface $tasksInterface): Void
    {
        $this->tasksInterface = $tasksInterface;
    }

    /**
     * Livewire mount properties
     *
     * @return void
     */
    public function mount($task): Void
    {
        $this->task == $task;

        $this->statesPedido = EstadoPedido::all();
     
    }

    public function updatedSelectedEstado()
    {
        $this->descricaoPanel="block";
    }

    public function updatedUploadFile()
    {
        $this->countFirstUpload++;
        $this->arrayFirstUploaded[$this->countFirstUpload] = [$this->uploadFile];
    }
   
   public function signaturePads($images,$pessoa)
   {
        if($pessoa == "signature-pad-cliente"){
            $this->signatureClient = $images;
        }
        else {
            $this->signatureTecnico = $images;
        }

   }

   public function signaturePadsClear($images,$pessoa)
   {
    if($pessoa == "signature-pad-cliente"){
        $this->signatureClient = "";
    }
    else {
        $this->signatureTecnico = "";
    }
   }

    /**
     * Saves the task report
     *
     * @return Void
     */
    public function addIntervention()
    {
        $validatedData = Validator::make(
            [
                'selectedEstado'  => $this->selectedEstado,
                'descricaoRealizado' => $this->descricaoRealizado,
            ],
            [
                'selectedEstado'  => 'required',
                'descricaoRealizado'  => 'required',
            ],
            [
                'selectedEstado'  => "Tem de selecionar um estado!",
                'descricaoRealizado' => "Tem de selecionar uma descrição da intervenção!",
            ]
        );

        if ($validatedData->fails()) {
            $errorMessage = '';
            foreach($validatedData->errors()->all() as $message) {
                $errorMessage .= '<p>' . $message . '</p>';
            }
            $this->dispatchBrowserEvent('swal', ['title' => "Intervenção", 'message' => $errorMessage, 'status'=>'error']);
            return;
        }


        if(!empty($this->arrayFirstUploaded)){
            foreach($this->arrayFirstUploaded as $img)
            {
                $img[0]->storeAs(tenant('id') . '/app/public/pedidos/intervencoes_anexos', $img[0]->getClientOriginalName());
            }
        }


        //PODER VERIFICAR AQUI AS SIGNATUREPADS
        //chekar se esta vazio ou nao

        //DD($this->signatureClient,$this->signatureTecnico);
        if($this->signatureClient != "")
        {
            $pos = strpos($this->signatureClient, ',') + 1;

            $base64Data = substr($this->signatureClient, $pos);

            $decodedClient = base64_decode($base64Data);

          
            $nomeArquivo = 'cliente_assinatura.png';
    
            $path = public_path().'/cl/'.tenant('id') . '/app/public/pedidos/assinaturas/'.$this->task->reference.'/'.$nomeArquivo;

            $pathCheck = public_path().'/cl/'.tenant('id') . '/app/public/pedidos/assinaturas/'.$this->task->reference.'/';

            if (!file_exists($pathCheck)) {
                mkdir($pathCheck, 0775, true);
            }
                        

            file_put_contents($path, $decodedClient);


            $this->signatureClient = $nomeArquivo;
        }

        if($this->signatureTecnico != "")
        {
            $posTecnico = strpos($this->signatureTecnico, ',') + 1;

            $base64DataTecnico = substr($this->signatureTecnico, $posTecnico);

            $decodedTecnico = base64_decode($base64DataTecnico);

          
            $nomeArquivo = 'tecnico_assinatura.png';
    
            $path = public_path().'/cl/'.tenant('id') . '/app/public/pedidos/assinaturas/'.$this->task->reference.'/'.$nomeArquivo;

            $pathCheck = public_path().'/cl/'.tenant('id') . '/app/public/pedidos/assinaturas/'.$this->task->reference.'/';

            if (!file_exists($pathCheck)) {
                mkdir($pathCheck, 0775, true);
            }
                        

            file_put_contents($path, $decodedTecnico);


            $this->signatureTecnico = $nomeArquivo;
        }
        
        
         $this->tasksInterface->addIntervencao($this);


         //fazer PDF
         if($this->email_pdf == true)
         {
            //Cria PDF e envia
         }
              
            
        return redirect()->route('tenant.tasks-reports.index')
            ->with('message', "Intervenção adicionada com sucesso!")
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
            $this->askUserLooseChanges();
            return NULL;
        }
        //$this->dispatchBrowserEvent('loading');
        return redirect()->route('tenant.tasks-reports.index');
    }

    /**
     * Ask user if he wants to loose the changes made
     *
     * @return Void
     */
    public function askUserLooseChanges(): Void
    {
        $this->dispatchBrowserEvent('swal', [
            'title' => __('Task Report'),
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
        return redirect()->route('tenant.tasks-reports.index')
            ->with('message', __('Task report updat canceled, all changes where lost!'))
            ->with('status', 'info');
    }

    public function render()
    {

        return view('tenant.livewire.tasksreports.edit');


    }

}
