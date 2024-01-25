<?php

namespace App\Exports;

use App\Models\User;
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

        foreach($this->analysis as $hour)
        {
            $explodedTime = array_map('intval',explode(':',$hour["total_hours"]));
            $sum_minutes += $explodedTime[0]*60+$explodedTime[1];
        }

        if(strlen(floor($sum_minutes/60)) == 1){
            $hoursCheck = '0'.floor($sum_minutes/60);
        }
        else {
            $hoursCheck = floor($sum_minutes/60);
        }

        if(strlen(floor($sum_minutes % 60)) == 1){
            $minutesCheck = '0'.floor($sum_minutes % 60);
        }
        else {
            $minutesCheck = floor($sum_minutes % 60);
        }

        $sumTime = $hoursCheck. ':' .$minutesCheck;

        $resultado_soma = global_hours_format($sumTime);

        $sheet->setCellValue("I{$totalRow}","SOMA DAS HORAS");
        $sheet->setCellValue("J{$totalRow}", "$resultado_soma min");

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

            if($analysis["tasks_reports"]["reportStatus"] == 0){
                $type = "Agendada";
            }
            else if($analysis["tasks_reports"]["reportStatus"] == 1){
                $type = "Em Curso";
            }
            else{
                $type="Finalizada";
            }

            $teamMember = User::where('id',$analysis["tech_id"])->first();
           
            return [
                'reference' => $analysis["tasks_reports"]["reference"],
                'stateOfTask' => $type,
                'tech' => $teamMember->name,
                'dateBegin' => $analysis["date_begin"],
                'hourBegin' => $analysis["hour_begin"],
                'hourEnd' => $analysis["hour_end"],
                'shortName' => $analysis["tasks_reports"]["task_customer"]["short_name"],
                'serviceName' => $analysis["service"]["name"],
                'descricao' => $analysis["descricao"],
                'totalHours' => $analysis["total_hours"]
            ];
        });

        return $collectionMapped;
    }

    public function headings(): array
    {
        return ["Referência", "Estado da Tarefa","Técnico", "Data", "Hora inicial", "Hora final", "Cliente", "Serviço", "Descrição", "Tempo Gasto"];
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
