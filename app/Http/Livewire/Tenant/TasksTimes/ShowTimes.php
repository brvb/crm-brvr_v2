<?php

namespace App\Http\Livewire\Tenant\TasksTimes;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Events\ChatMessage;
use Livewire\WithPagination;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TasksReports;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Tenant\TasksTimes\TasksTimesInterface;
use App\Models\Tenant\Intervencoes;

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

   

    protected $listeners = ['mudarHora' => 'mudarHora'];


      /**
     * Livewire construct function
     *
     * @param TasksInterface $tasksInterface
     * @return Void
     */
    

    public function mount($task = NULL)
    {

        $this->task = $task->id;

        $this->tenant =  tenant("id");

        $this->reference = $task->reference;

        $this->taskTimes =  Intervencoes::with('pedido')->where('estado_pedido',"!=","1")->where('id_pedido',$task->id)->paginate($this->perPage);

        $horas = Intervencoes::with('pedido')->where('estado_pedido',"!=","1")->where('id_pedido',$this->task)->get();

        $somaDiferencasSegundos = 0;

        $arrHours[$this->task] = [];

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
        //$horaFormatada = Carbon::createFromTime($horas, $minutos, 0)->format('H:i');

        $this->horasAtuais = $horas;
        $this->minutosAtuais = $minutos;

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

        $this->dispatchBrowserEvent("editarTempo",["id" => $id,"hora_final" => $intervencao->hora_final]);
    }

    public function removeTempo($id)
    {
        Intervencoes::where('id',$id)->delete();

        $this->dispatchBrowserEvent("atualizarPagina");
    }
     
    public function mudarHora($id,$mudarHora)
    {
       
        $intervencaoAtual = Intervencoes::where('id',$id)->first();
       
        Intervencoes::where('id',$id)->update([
                "hora_final" => date("H:i:s",strtotime($mudarHora)),
                "data_final" => date('Y-m-d')
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
            "taskInfo" => $this->taskInfo
        ]);
    }
}
