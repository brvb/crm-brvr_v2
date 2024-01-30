<?php

namespace App\Http\Livewire\Tenant\TasksReports;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\User;
use Livewire\Component;
use Livewire\Redirector;
use App\Events\ChatMessage;
use App\Events\Tasks\SendPDF;
use App\Models\Tenant\Config;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Tenant\EstadoPedido;
use App\Models\Tenant\Intervencoes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\TasksTimes\TasksTimesInterface;
use App\Interfaces\Tenant\TasksReports\TasksReportsInterface;
use App\Models\Tenant\Pedidos;

class EditTasksReports extends Component
{

    use WithFileUploads;

    private TasksInterface $tasksInterface;


    public string $searchString = '';
    public string $reportPanel = 'active show';
    public string $timesPanel = '';

    public ?object $task = NULL;
    public ?object $statesPedido = NULL;
    public string $horasAtuais = "";
    public string $descricaoPanel = 'none';
    public string $signaturePad = 'none';
    public string $selectedEstado = '';
    public int $horasAlterado = 0;
    public string $referencia_intervencao = '';
    public string $descricao_intervencao = '';
    public int $quantidade_intervencao = 0;
    public string $descricaoRealizado = '';
    public $uploadFile;
    public int $countFirstUpload = 0;
    public array $arrayFirstUploaded = [];
    public $signatureClient;
    public $signatureTecnico;
    public $email_pdf;

    public $loading;
    
    public ?object $taskTimes =  NULL;

    public int $reportInfo  = 1;

    public ?object $taskReportCollection = NULL;
    public array $arrayReport = [];
    public bool $changed = false;

    protected $listeners = ['resetChanges' => 'resetChanges', 'signaturePads' => 'signaturePads','signaturePadsClear' => 'signaturePadsClear','teste' => 'teste'];

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

        //CONTAR O TEMPO PARA COLOCAR NO CABEÇALHO
        $horas = Intervencoes::where('id_pedido',$this->task->id)->where('estado_pedido','!=',1)->get();

        $somaDiferencasSegundos = 0;


        foreach($horas as $hora)
        {
            $data1 = Carbon::parse($hora->data_inicio);
            $data2 = Carbon::parse($hora->created_at);
            $result = $data1->diff($data2);
          
            $data = Carbon::createFromTime($result->h, $result->i, $result->s);

            $somaDiferencasSegundos += $data->diffInSeconds(Carbon::createFromTime(0, 0, 0));
        }


        //Converter segundos e horas e minutos
        $horas = floor($somaDiferencasSegundos / 3600);
        $minutos = floor(($somaDiferencasSegundos % 3600) / 60);
        $horaFormatada = Carbon::createFromTime($horas, $minutos, 0)->format('H:i');

        $this->horasAtuais = $horaFormatada;

        $this->horasAlterado = 0;
     
    }

    public function updatedSelectedEstado()
    {
        if($this->selectedEstado != "1")
        {
            $this->descricaoPanel="block";
        }
        
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

   public function teste($cliente,$tecnico)
   {

      $this->signatureClient = $cliente;
      $this->signatureTecnico = $tecnico;

      $this->addIntervention();
   }

    /**
     * Saves the task report
     *
     * @return Void
     */
    public function addIntervention()
    {
        $config = Config::first();

        if($this->selectedEstado == "1")
        {
            $intervencao = Intervencoes::with('pedido')->where('id_pedido',$this->task->id)->where('user_id',Auth::user()->id)->latest()->first();
            if(isset($intervencao->estado_pedido))
            {
                if($intervencao->estado_pedido == "1" && isset($intervencao->estado_pedido))
                {
                    $this->dispatchBrowserEvent('swal', ['title' => "Intervenção", 'message' => "O seu utilizador já tem uma intervençao em aberto para este pedido!", 'status'=>'error']);
                    return false;
                }
            }
            
        }

        if($this->selectedEstado == "2")
        {
            $intervencaoCheckFinalizado = Intervencoes::with('pedido')->where('id_pedido',$this->task->id)->where('user_id',Auth::user()->id)->latest()->first();

            if(isset($intervencaoCheckFinalizado))
            {
                if($intervencaoCheckFinalizado->estado_pedido == "2")
                {
                    $this->dispatchBrowserEvent('swal', ['title' => "Intervenção", 'message' => "Este pedido já se encontra concluído", 'status'=>'error']);
                    return false;
                }  
                
                    
                //Checka se o campo de descrição final esta preenchido
    
                if($this->descricaoRealizado == "")
                {
                    $this->dispatchBrowserEvent('swal', ['title' => "Intervenção", 'message' => "Tem de selecionar uma descrição do realizado", 'status'=>'error']);
                    return false;
                }
            }
            else {
                $this->dispatchBrowserEvent('swal', ['title' => "Intervenção", 'message' => "Tem de abrir intervenção antes poder fechar", 'status'=>'error']);
                return false;
            }
           
        }


        if($this->selectedEstado == "4")
        {
            $intervencaoCheckSuspenso = Intervencoes::with('pedido')->where('id_pedido',$this->task->id)->where('user_id',Auth::user()->id)->latest()->first();

            if(!isset($intervencaoCheckSuspenso))
            {
                $this->dispatchBrowserEvent('swal', ['title' => "Intervenção", 'message' => "Tem de abrir uma intervenção para colocar em suspenso", 'status'=>'error']);
                return false;
    
            }
            else 
            {
                if($intervencaoCheckSuspenso->estado_pedido == "4")
                {
                    $this->dispatchBrowserEvent('swal', ['title' => "Intervenção", 'message' => "Este pedido já se encontra suspenso", 'status'=>'error']);
                    return false;
                }
            }
        }


        $validatedData = Validator::make(
            [
                'selectedEstado'  => $this->selectedEstado,
            ],
            [
                'selectedEstado'  => 'required',
            ],
            [
                'selectedEstado'  => "Tem de selecionar um estado!",
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
                $img[0]->storeAs(tenant('id') . '/app/public/pedidos/intervencoes_anexos/'.$this->task->id.'/', $img[0]->getClientOriginalName());
            }
        }


        //PODER VERIFICAR AQUI AS SIGNATUREPADS
     
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

        $horas = Intervencoes::where('id_pedido',$this->task->id)->where('estado_pedido','!=',1)->get();

        $somaDiferencasSegundos = 0;


        foreach($horas as $hora)
        {
            $data1 = Carbon::parse($hora->data_inicio);
            $data2 = Carbon::parse($hora->created_at);
            $result = $data1->diff($data2);
          
            $data = Carbon::createFromTime($result->h, $result->i, $result->s);

            $somaDiferencasSegundos += $data->diffInSeconds(Carbon::createFromTime(0, 0, 0));
        }


        //Converter segundos e horas e minutos
        $horas = floor($somaDiferencasSegundos / 3600);
        $minutos = floor(($somaDiferencasSegundos % 3600) / 60);
        $horaFormatada = Carbon::createFromTime($horas, $minutos, 0)->format('H:i');

        $this->horasAtuais = $horaFormatada;


         
        $pdf = PDF::loadView('tenant.tasks.invoicepdf',["impressao" => $this,'config' => $config])
        ->setPaper('a4')
        ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

        $content = $pdf->download()->getOriginalContent();

        Storage::put(tenant('id') . '/app/public/pedidos/pdfs_conclusao/'.$this->task->reference.'/'.$this->task->reference.'.pdf',$content);

        if($this->selectedEstado == "2")
        {

            if($this->email_pdf == true)
            {
                //ENVIA EMAIL
                $pedido = Pedidos::where('id',$this->task->id)->first();
                event(new SendPDF($pedido));
                
            } 
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

        $horas = Intervencoes::where('id_pedido',$this->task->id)->where('estado_pedido','!=',1)->get();

        $somaDiferencasSegundos = 0;


        foreach($horas as $hora)
        {
            $data1 = Carbon::parse($hora->data_inicio);
            $data2 = Carbon::parse($hora->created_at);
            $result = $data1->diff($data2);
          
            $data = Carbon::createFromTime($result->h, $result->i, $result->s);

            $somaDiferencasSegundos += $data->diffInSeconds(Carbon::createFromTime(0, 0, 0));
        }


        //Converter segundos e horas e minutos
        $horas = floor($somaDiferencasSegundos / 3600);
        $minutos = floor(($somaDiferencasSegundos % 3600) / 60);
        $horaFormatada = Carbon::createFromTime($horas, $minutos, 0)->format('H:i');

        $this->horasAtuais = $horaFormatada;

        return view('tenant.livewire.tasksreports.edit');


    }

}
