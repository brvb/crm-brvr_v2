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
use App\Models\Tenant\Pedidos;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Tenant\EstadoPedido;
use App\Models\Tenant\Intervencoes;
use App\Models\Tenant\PivotEstados;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\TasksTimes\TasksTimesInterface;
use App\Interfaces\Tenant\TasksReports\TasksReportsInterface;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\Prioridades;

class EditTasksReports extends Component
{

    use WithFileUploads;

    private TasksInterface $tasksInterface;
    private CustomersInterface $customersInterface;


    public string $searchString = '';
    public string $reportPanel = 'active show';
    public string $timesPanel = '';

    public ?object $task = NULL;
    public ?object $statesPedido = NULL;
    public string $horasAtuais = "";
    public string $minutosAtuais = "";
    public string $descricaoPanel = 'none';
    public string $signaturePad = 'none';
    public string $selectedEstado = '';
    public string $horasAlterado = '';
    public array $designacao_intervencao = [];
    public string $descricao_intervencao = '';
    public string $selectedProdutos = '';
    public array $quantidade_intervencao = [];
    public string $descricaoRealizado = '';
    public $uploadFile;
    public int $countFirstUpload = 0;
    public array $arrayFirstUploaded = [];
    public $signatureClient;
    public $signatureTecnico;
    public $email_pdf;

    public $loading;
    
    public ?object $taskTimes =  NULL;

    public ?object $coresObject = NULL;

    public int $reportInfo  = 1;

    public ?object $taskReportCollection = NULL;
    public array $arrayReport = [];
    public bool $changed = false;

    public array $array_produtos = [];

    public ?int $selectPrioridade;

    protected $listeners = ['resetChanges' => 'resetChanges', 'signaturePads' => 'signaturePads','signaturePadsClear' => 'signaturePadsClear','teste' => 'teste'];

     /**
     * Livewire construct function
     *
     * @param TasksInterface $tasksInterface
     * @return Void
     */
    public function boot(TasksInterface $tasksInterface,CustomersInterface $customersInterface): Void
    {
        $this->tasksInterface = $tasksInterface;
        $this->customersInterface = $customersInterface;
    }

    /**
     * Livewire mount properties
     *
     * @return void
     */
    public function mount($task): Void
    {
        $this->task == $task;


        $this->statesPedido  = PivotEstados::with('estadoPedido')->with('tipoPedido')->whereHas('tipoPedido', function ($query) {
            $query->where('id',$this->task->tipo_pedido);
        })->get();




        // $this->statesPedido = EstadoPedido::all();





        //CONTAR O TEMPO PARA COLOCAR NO CABEÇALHO
        $horas = Intervencoes::where('id_pedido',$this->task->id)->where('hora_final','!=',null)->get();

        $somaDiferencasSegundos = 0;

        $arrHours[$this->task->id] = [];

        $minutosSomados = 0;

        foreach($horas as $hora)
        {
            
            $dia_inicial = $hora->data_inicio.' '.$hora->hora_inicio;
            $dia_final = $hora->data_inicio.' '.$hora->hora_final;

            $data1 = Carbon::parse($dia_inicial);
            $data2 = Carbon::parse($dia_final);

            $result = $data1->diffInMinutes($data2);

           

            //*****PARTE A DESCONTAR********/

            
            if($hora->descontos == null)
            {
                $hora->descontos = "+0";
            }

          
            $minutosSomados += $result;

            if($hora["descontos"][0] == "+"){ 
                $minutosSomados += substr($hora->descontos, 1);
            } 
            else { 
                $minutosSomados -= substr($hora->descontos, 1);
            }
          
            /*********************** */           

        }




        $this->horasAtuais = $minutosSomados;



        $horaAlterado = Pedidos::where('id',$this->task->id)->first();
        if($horaAlterado->horas_alterado != null)
        {
            $this->horasAlterado = $horaAlterado->horas_alterado;
        }
        
     
    }

    public function removeProduto($i)
    {
        unset($this->array_produtos[$i]);
        unset($this->designacao_intervencao[$i]);
        unset($this->quantidade_intervencao[$i]);
        $this->dispatchBrowserEvent("reloadProdutos");
        $this->render();
    }

    public function updatedSelectedProdutos()
    {
        $infoProdutos = $this->tasksInterface->getProductByReference($this->selectedProdutos);

        $conta = count($this->array_produtos);

        if($conta == 0)
         {
            $cnt = 0;
         } else {
            $cnt = $conta + 1;
         }

        $this->array_produtos[$cnt] = [
            "referencia" => $infoProdutos->products->reference
        ];

        $this->designacao_intervencao[$cnt] = [
            "description" => $infoProdutos->products->description
        ];
        // array_push($this->array_produtos,$infoProdutos->products->description);

        $this->dispatchBrowserEvent("reloadProdutos");

    }

    public function adicionaProduto()
    {
        // array_push($this->array_produtos,"");

        $conta = count($this->array_produtos);

        if($conta == 0)
         {
            $cnt = 0;
         } else {
            $cnt = $conta + 1;
         }

        $this->array_produtos[$cnt] = [
            "referencia" => ""
        ];

        $this->designacao_intervencao[$cnt] = [
            "description" => ""
        ];

        $this->dispatchBrowserEvent("reloadProdutos");
    }

    public function updatedSelectedEstado()
    {
        if($this->selectedEstado != "7")
        {
            $this->descricaoPanel="block";

        } else {
            $this->descricaoPanel="none";
        }


        $this->dispatchBrowserEvent("reloadProdutos");
        
    }

    public function updatedUploadFile()
    {
        $this->countFirstUpload++;
        $this->arrayFirstUploaded[$this->countFirstUpload] = [$this->uploadFile];

        $this->dispatchBrowserEvent("reloadProdutos");
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

        if($this->horasAlterado != "")
        {
            Pedidos::where('id',$this->task->id)->update([
                "horas_alterado" => $this->horasAlterado
            ]);
        }

        if($this->selectPrioridade != 0)
        {
            Pedidos::where('id',$this->task->id)->update([
                "prioridade" => $this->selectPrioridade
            ]);
        }


        //VALIDAÇÕES VOU TER DE FAZER AQUI


        if($this->selectedEstado == "7")
        {
            $intervencao = Intervencoes::with('pedido')->where('id_pedido',$this->task->id)->where('user_id',Auth::user()->id)->latest()->first();

            if(isset($intervencao->estado_pedido))
            {
                if($intervencao->estado_pedido == "7" && $intervencao->hora_final == "")
                {
                    $this->dispatchBrowserEvent('swal', ['title' => "Intervenção", 'message' => "O seu utilizador já tem uma intervençao em curso!", 'status'=>'error']);
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

            $pedidoSpec = Pedidos::where('id',$this->task->id)->first();

            $memberSpec = TeamMember::where('id',$pedidoSpec->tech_id)->first();

            if($memberSpec->user_id != Auth::user()->id)
            {
                $this->dispatchBrowserEvent('swal', ['title' => "Intervenção", 'message' => "Não pode concluir um pedido que não lhe pertence", 'status'=>'error']);
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

        if(!Storage::exists(tenant('id') . '/app/public/pedidos/intervencoes_anexos/'.$this->task->id))
        {
            $caminhoImagesss = storage_path('/app/public/pedidos/intervencoes_anexos/'.$this->task->id.'/');
            mkdir($caminhoImagesss, 0755, true);
            // File::makeDirectory(tenant('id') . '/app/public/pedidos/intervencoes_anexos/'.$this->task->id, 0755, true, true);
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

        $horas = Intervencoes::where('id_pedido',$this->task->id)->where("data_final","!=",null)->get();

        $somaDiferencasSegundos = 0;

        $arrHours[$this->task->id] = [];

        $minutosSomados = 0;


        foreach($horas as $hora)
        {
            
            $dia_inicial = $hora->data_inicio.' '.$hora->hora_inicio;
            $dia_final = $hora->data_inicio.' '.$hora->hora_final;

            $data1 = Carbon::parse($dia_inicial);
            $data2 = Carbon::parse($dia_final);

            $result = $data1->diffInMinutes($data2);

           

            //*****PARTE A DESCONTAR********/

            
            if($hora->descontos == null)
            {
                $hora->descontos = "+0";
            }

          
            $minutosSomados += $result;

            if($hora["descontos"][0] == "+"){ 
                $minutosSomados += substr($hora->descontos, 1);
            } 
            else { 
                $minutosSomados -= substr($hora->descontos, 1);
            }
          
            /*********************** */           

        }


        $resultDivisao = $minutosSomados / 15;
        $resultBlocos = ceil($resultDivisao) * 15;
            



        $this->horasAtuais = $resultBlocos;

        


        if($this->selectedEstado == "2")
        {
       
         
            $pdf = PDF::loadView('tenant.tasks.invoicepdf',["horasGastasTotal" => $this->horasAtuais,"impressao" => $this,'customerRepository' => $this->customersInterface,'config' => $config])
            ->setPaper('a4')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

            $content = $pdf->download()->getOriginalContent();

            if(!Storage::exists(tenant('id') . '/app/public/pedidos/pdfs_conclusao/'.$this->task->reference))
            {
                $caminhoImagess = storage_path('/app/public/pedidos/pdfs_conclusao/'.$this->task->reference.'/');
                mkdir($caminhoImagess, 0755, true);
                // File::makeDirectory(tenant('id') . '/app/public/pedidos/pdfs_conclusao/'.$this->task->reference, 0755, true, true);
            }

            Storage::put(tenant('id') . '/app/public/pedidos/pdfs_conclusao/'.$this->task->reference.'/'.$this->task->reference.'.pdf',$content);

            $pedido = Pedidos::where('id',$this->task->id)->with('tech')->with('intervencoes')->with('customer')->first();
            event(new SendPDF($pedido, $this->email_pdf));
        }


          //TENTAR VER ESTA SITUAÇÃO PARA ENVIAR PARA O DASHBOARD
          $usr = User::where('id',Auth::user()->id)->first();
          $pedido = Pedidos::where('id',$this->task->id)->first();
  
          $usrRecebido = User::where('id',$pedido->user_id)->first();
  
         
          $message = "adicionou uma intervenção";
      
  
          event(new ChatMessage($usr->name, $message));


            
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
        $produtos = $this->tasksInterface->getProducts();

        $horas = Intervencoes::where('id_pedido',$this->task->id)->where('hora_final','!=',null)->get();

        $somaDiferencasSegundos = 0;

        $minutosSomados = 0;

        foreach($horas as $hora)
        {
            
            $dia_inicial = $hora->data_inicio.' '.$hora->hora_inicio;
            $dia_final = $hora->data_inicio.' '.$hora->hora_final;

            $data1 = Carbon::parse($dia_inicial);
            $data2 = Carbon::parse($dia_final);

            $result = $data1->diffInMinutes($data2);

           

            //*****PARTE A DESCONTAR********/

            
            if($hora->descontos == null)
            {
                $hora->descontos = "+0";
            }

          
            $minutosSomados += $result;

            if($hora["descontos"][0] == "+"){ 
                $minutosSomados += substr($hora->descontos, 1);
            } 
            else { 
                $minutosSomados -= substr($hora->descontos, 1);
            }
          
            /*********************** */           

        }


        $this->horasAtuais = $minutosSomados;


        $this->coresObject = Prioridades::all();

        return view('tenant.livewire.tasksreports.edit',["produtos" => $produtos, "arrayProdutos" => $this->array_produtos, "arrayDesignacoes" => $this->designacao_intervencao]);


    }

}
