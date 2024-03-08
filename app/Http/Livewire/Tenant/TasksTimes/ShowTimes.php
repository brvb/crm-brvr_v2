<?php

namespace App\Http\Livewire\Tenant\TasksTimes;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Events\ChatMessage;
use Livewire\WithPagination;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\Intervencoes;
use App\Models\Tenant\TasksReports;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\TasksTimes\TasksTimesInterface;

class ShowTimes extends Component
{
    use WithPagination;
    
    private TasksTimesInterface $tasksTimesInterface;
    private ?object $taskTimes =  NULL;
    public string $searchString = '';
    public int $perPage = 10;
    public $task ;
    public ?object $taskInfo = NULL;
    public float $task_hours = 0;
    public string $horasAtuais = "";
    public string $minutosAtuais = "";

    public string $serviceSelected = '';
    public string $date_inicial = '';
    public string $hora_inicial = '';
    public $hora_final;
    public string $desconto_hora = '';
    public string $descricao = '';

    private ?object $total_hours = NULL;

    public string $tenant = '';
    public string $reference = '';

    protected object $customersRepository;
   

    protected $listeners = ['mudarHora' => 'mudarHora'];


      /**
     * Livewire construct function
     *
     * @param TasksInterface $tasksInterface
     * @return Void
     */

     public function boot(CustomersInterface $interfaceCustomers): Void
     {
            
         $this->customersRepository = $interfaceCustomers;
      
 
     }
    

    public function mount($task = NULL)
    {

        $this->task = $task->id;

        $this->tenant =  tenant("id");

        $this->reference = $task->reference;

        $this->taskTimes =  Intervencoes::with('pedido')->where('estado_pedido',"!=","1")->where('id_pedido',$task->id)->paginate($this->perPage);

        $horas = Intervencoes::with('pedido')->where('estado_pedido',"!=","1")->where('id_pedido',$this->task)->get();

        $somaDiferencasSegundos = 0;

        $arrHours[$this->task] = [];

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

            $arrHours[$this->task] = $minutosSomados;
          
            /*********************** */           
        }
        $sum = 0;
        foreach($arrHours as $h)
        {
            if(!empty($h))
            {
                $sum += $h;
            }
        }



        $this->minutosAtuais = $sum;

    }

     /**
     * Change number of records to display
     *
     * @return void
     */
    public function updatedPerPage(): void
    {
        session()->put('perPage', $this->perPage);
        $this->taskTimes =  Intervencoes::with('pedido')->where('estado_pedido',"!=","1")->where('id_pedido',$this->task)->paginate($this->perPage);
    }



    /**
     * Create custom pagination html string
     *
     * @return string
     */
    public function paginationView(): String
    {
        return 'tenant.livewire.setup.pagination';
    }

      /**
     * Prepare properties
     *
     * @return void
     */
    private function initProperties(): void
    {
        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }
    }


    public function ver($anexos)
    {
        $this->dispatchBrowserEvent("Intervencao",["anexos" => $anexos]);
    }

    public function editarTempo($id)
    {
        $intervencao = Intervencoes::where('id',$id)->first();

        $this->dispatchBrowserEvent("editarTempo",["id" => $id]);
    }

    public function removeTempo($id)
    {
        Intervencoes::where('id',$id)->delete();

        $this->dispatchBrowserEvent("atualizarPagina");
    }
     
    public function mudarHora($id,$mudarHora,$selectedSinal,$desconto_descricao)
    {
        $intervencaoAtual = Intervencoes::where('id',$id)->first();

        if($selectedSinal == "mais"){
            $selectedSinal = "+";
        } else {
            $selectedSinal = "-";
        }
       
        Intervencoes::where('id',$id)->update([
               "descontos" => $selectedSinal.$mudarHora,
               "descricao_desconto" => $desconto_descricao
        ]);
        
        $this->dispatchBrowserEvent("atualizarPagina");
    }
    

    public function render()
    {
        $this->taskTimes = Intervencoes::with('pedido')->where('id_pedido',$this->task)->paginate($this->perPage);


        return view('tenant.livewire.taskstimes.show-times',[
            'tasksTimes' => $this->taskTimes,
            'taskHours' => $this->task_hours,
            'totalHours' => $this->horasAtuais,
            'totalMinutos' => $this->minutosAtuais,
            "taskInfo" => $this->taskInfo,
            "customersRepository" => $this->customersRepository
        ]);
    }
}
