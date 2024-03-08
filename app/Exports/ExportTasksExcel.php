<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\Intervencoes;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Interfaces\Tenant\Customers\CustomersInterface;

class ExportTasksExcel implements FromCollection, WithHeadings, WithEvents,ShouldAutoSize, WithStyles
{

    protected $analysis;
    protected object $customersRepository;


    public function __construct($analysis,$customersRepository) {
        $this->analysis = $analysis;

        $this->customersRepository = $customersRepository;
    }

    public function styles(Worksheet $sheet)
    {
        $numOfRows = count($this->analysis) + 1;
        $totalRow = $numOfRows + 2;

        $sum_minutes = 0;

        $resultado_soma = "";
        $somaDiferencasSegundos = 0;

        foreach($this->analysis as $ana)
        {
            $intervencoes = Intervencoes::where('id_pedido',$ana["id"])->where('data_inicio','!=',null)->get();
                                
            $arrHours[$ana["id"]] = [];
           
          
            $minutosSomados = 0;
            foreach($intervencoes as $hora)
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
            
                if($minutosSomados == "")
                {
                    $minutosSomados = 0;
                }
                $arrHours[$ana["id"]] = $minutosSomados;
                /*********************** */           

            }
           

        }

        //FAZER AQUI A CONTINUAÇÃO DO CODIGO
        $sum = 0;
       
        foreach($arrHours as $hour)
        {
           if(!empty($hour))
           {
                $sum += $hour;
           }
            
        }
        
        
         //Converter segundos e horas e minutos
        
        $sheet->setCellValue("I{$totalRow}","SOMA DE MINUTOS");
        $sheet->setCellValue("J{$totalRow}", "$sum min");

        $sheet->getStyle("I{$totalRow}:J{$totalRow}")->applyFromArray(
            array(
                'fill' => array(
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '326c91']
                ),
                'font' => array(
                    'color' => ['argb' => 'FFFFFF']
                )
            )

        )->getFont()->setBold(true);

       

        $sheet->getStyle("A1:J1")->applyFromArray(
            array(
               'fill' => array(
                  'fillType' => Fill::FILL_SOLID,
                  'startColor' => ['argb' => '326c91']
               ),
               'font' => array(
                  'color' => ['argb' => 'FFFFFF']
               ),
               'alignment' => array(
                  'horizontal' => "center", 
               )
            )
        )->getFont()->setBold(true);
        
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $newCollection = collect($this->analysis);


        $collectionMapped = $newCollection->map(function($analysis){

            $intervencoes = Intervencoes::where('id_pedido',$analysis["id"])->where('data_inicio','!=',null)->get();

            $arrHours[$analysis["id"]] = [];
                                    
            $somaDiferencasSegundos = 0;


            $minutosSomados = 0;

            foreach($intervencoes as $hora)
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
            
            if($minutosSomados == "" || empty($minutosSomados))
            {
                $minutosSomados = 0;
            }
                
            

            $horasAtuais = $minutosSomados;

            $teamMember = TeamMember::where('id',$analysis["tech_id"])->first();

            $cst = $this->customersRepository->getSpecificCustomerInfo($analysis["customer_id"]);
           
            return [
                'reference' => $analysis["reference"],
                'stateOfTask' => $analysis["tipo_estado"]["nome_estado"],
                'tech' => $teamMember->name,
                'dateBegin' => $analysis["data_agendamento"],
                'hourBegin' => $analysis["hora_agendamento"],
                'shortName' => $cst->customers->name,
                'serviceName' => $analysis["services_to_do"]["name"],
                'descricao' => $analysis["descricao"],
                'totalHours' => $horasAtuais
            ];
        });

        return $collectionMapped;
    }

    public function headings(): array
    {
        return ["Referência", "Estado do Pedido","Técnico", "Data de Agendamento", "Hora de Agendamento", "Cliente", "Serviço", "Descrição", "Tempo Gasto"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:J1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('326c91');
            }

        ];
    }
}
