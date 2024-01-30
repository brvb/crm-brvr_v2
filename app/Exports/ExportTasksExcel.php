<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant\Intervencoes;
use App\Models\Tenant\TeamMember;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTasksExcel implements FromCollection, WithHeadings, WithEvents,ShouldAutoSize, WithStyles
{

    protected $analysis;

    public function __construct($analysis) {
        $this->analysis = $analysis;
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
                                    
            
            foreach($intervencoes as $hora)
            {
                $data1 = Carbon::parse($hora->data_inicio);
                $data2 = Carbon::parse($hora->created_at);
                $result = $data1->diff($data2);
            
                $data = Carbon::createFromTime($result->h, $result->i, $result->s);

                $somaDiferencasSegundos += $data->diffInSeconds(Carbon::createFromTime(0, 0, 0));
            }


        }

         //Converter segundos e horas e minutos
         $horas = floor($somaDiferencasSegundos / 3600);
         $minutos = floor(($somaDiferencasSegundos % 3600) / 60);
         $horaFormatada = Carbon::createFromTime($horas, $minutos, 0)->format('H:i');

         $resultado_soma = $horaFormatada;
       

        $sheet->setCellValue("I{$totalRow}","SOMA DAS HORAS");
        $sheet->setCellValue("J{$totalRow}", "$resultado_soma");

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
                                    
            $somaDiferencasSegundos = 0;


            foreach($intervencoes as $hora)
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

            $horasAtuais = $horaFormatada;

            $teamMember = TeamMember::where('id',$analysis["tech_id"])->first();

           
            return [
                'reference' => $analysis["reference"],
                'stateOfTask' => $analysis["tipo_estado"]["nome_estado"],
                'tech' => $teamMember->name,
                'dateBegin' => $analysis["data_agendamento"],
                'hourBegin' => $analysis["hora_agendamento"],
                'shortName' => $analysis["customer"]["short_name"],
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
