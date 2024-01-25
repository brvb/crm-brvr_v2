<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>

    <body
        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; color: #74787e; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
        <style>
            @media only screen and (max-width: 600px) {
                .inner-body {
                    width: 100% !important;
                }

                .footer {
                    width: 100% !important;
                }
            }

            @media only screen and (max-width: 500px) {
                .button {
                    width: 100% !important;
                }
            }
        </style>
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
            style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
            <tr>
                <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                    <table class="content" width="100%" cellpadding="0" cellspacing="0"
                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                        <tr>
                            <td class="header"
                                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center;">
                            </td>
                        </tr>
                        <!-- Email Body -->
                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0"
                                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #ffffff; border-bottom: 1px solid #edeff2; border-top: 1px solid #edeff2; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                <table class="inner-body" align="center" width="900" cellpadding="0" cellspacing="0"
                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #ffffff; margin: 0 auto; padding: 0; width: 900px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 900px;">
                                    <!-- Body content -->
                                    <tr>
                                        <td class="content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                            <p style="text-align:center;padding-bottom:6px;">
                                                <img src="{{ global_tenancy_asset('/app/public/images/logo/' . $logotipo) }}" alt="{{ $company_name }}">
                                            </p>
                                            <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: center;">
                                                {{ $subject }}
                                            </h1>
                                            <hr>
                                            <h4 style="text-align:center;">{{$infoSendEmail["nome"]}}</h4>
                                            <p style="text-align:center;">
                                                TAREFAS URGENTES
                                            </p>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Nome Tarefa</th>
                                                        <th>Nome Cliente</th>
                                                        <th>Resumo</th>
                                                        <th>Data Agendamento</th>
                                                        <th>Data Primeiro Tempo</th>
                                                        <th>Data ultima intervenção</th>
                                                        <th>Tempo total gasto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $lastTime = [];
                                                        $firstTime = [];
                                                        $count = 0;
                                                        $countForeach = 0;
                                                        foreach ($infoSendEmail["primeiro_quadro"] as $info )
                                                        {
                                                           
                                                            foreach($info->tasksTimes as $tsk)
                                                            {
                                                                $count++;
                                                                if($count == 1)
                                                                {
                                                                    $firstTime[$countForeach] = $tsk->date_begin ."/". $tsk->hour_begin;
                                                                }
                                                                $lastTime[$countForeach] = $tsk->date_begin ."/". $tsk->hour_begin;
                                                            }
                                                            $countForeach++;
                                                            $count = 0;
                                                        }
                                                       
                                                    @endphp

                                                    @php
                                                        $countLOOP = 0;
                                                    @endphp

                                                    @foreach ($infoSendEmail["primeiro_quadro"] as $info )
                                                   
                                                    <tr style="text-align:center;">                                                 
                                                        <td>{{$info->reference}}</td>
                                                        <td>{{$info->taskCustomer->name}}</td>
                                                        <td>{{$info->resume}}</td>
                                                        <td>{{$info->preview_date}} / {{$info->preview_hour}}</td>
                                                        @if(isset($info->tasksTimes))
                                                         <td>@if(isset($firstTime[$countLOOP])) {{$firstTime[$countLOOP]}} @endif</td>
                                                         <td>@if(isset($lastTime[$countLOOP])) {{$lastTime[$countLOOP]}} @endif</td>

                                                         @php

                                                          $countLOOP++;
                                                            $arrayTimes = [];
                                                            foreach($info->tasksTimes as $tsk)
                                                            {
                                                                if($tsk->date_end != "")
                                                                {
                                                                    array_push($arrayTimes,$tsk->total_hours);
                                                                }
                                                            }

                                                           
                                                            $minutes = 0; 

                                                            foreach ($arrayTimes as $time) {
                                                                list($hour, $minute) = explode(':', $time);
                                                                $minutes += $hour * 60;
                                                                $minutes += $minute;
                                                            }
                                                        
                                                            $hours = floor($minutes / 60);
                                                            $minutes -= $hours * 60;
                                                        
                                                            
                                                            $totalHoursSpent = sprintf('%02d:%02d', $hours, $minutes);
                                                            
                                                         @endphp
                                                        
                                                         <td>{{$totalHoursSpent}}</td>

                                                        @else
                                                         <td>Não iniciou ainda</td>
                                                         <td>Não iniciou ainda</td>
                                                         <td>Não iniciou ainda</td>
                                                        @endif
                                                       
                                                       

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                              </table>

                                            <hr>

                                            <p style="text-align:center;">
                                                OUTRAS TAREFAS
                                            </p>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Nome Tarefa</th>
                                                        <th>Nome Cliente</th>
                                                        <th>Resumo</th>
                                                        <th>Data Agendamento</th>
                                                        <th>Data Primeiro Tempo</th>
                                                        <th>Data ultima intervenção</th>
                                                        <th>Tempo total gasto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $lastTimeSegundo = [];
                                                        $firstTimeSegundo = [];
                                                        $count = 0;
                                                        $countForeachSegundo = 0;
                                                    
                                                        foreach ($infoSendEmail["segundo_quadro"] as $info )
                                                        {
                
                                                            foreach($info->tasksTimes as $tsk)
                                                            {
                                                                $count++;
                                                                if($count == 1)
                                                                {
                                                                    $firstTimeSegundo[$countForeachSegundo] = $tsk->date_begin ."/". $tsk->hour_begin;
                                                                }
                                                                $lastTimeSegundo[$countForeachSegundo] = $tsk->date_begin ."/". $tsk->hour_begin;
                                                            }
                                                            $countForeachSegundo++;
                                                            $count = 0;
                                                        }
                                                   
                                                    @endphp
                                                     @php
                                                        $countLOOPSegundo = 0;
                                                    @endphp
                                                    @foreach ($infoSendEmail["segundo_quadro"] as $info )
                                                   
                                                    <tr style="text-align:center;">
                                                        <td>{{$info->reference}}</td>
                                                        <td>{{$info->taskCustomer->name}}</td>
                                                        <td>{{$info->resume}}</td>
                                                        <td>{{$info->preview_date}} / {{$info->preview_hour}}</td>
                                                        @if(isset($info->tasksTimes))
                                                         @if(isset($firstTimeSegundo[$countLOOPSegundo]))
                                                            <td>{{$firstTimeSegundo[$countLOOPSegundo]}}</td>
                                                         @else
                                                           <td></td>
                                                         @endif
                                                         @if(isset($lastTimeSegundo[$countLOOPSegundo]))
                                                            <td>{{$lastTimeSegundo[$countLOOPSegundo]}}</td>
                                                         @else
                                                            <td></td>
                                                         @endif

                                                         @php
                                                          $countLOOPSegundo++;
                                                            $arrayTimesSecond = [];
                                                            foreach($info->tasksTimes as $tsk)
                                                            {
                                                                if($tsk->date_end != "")
                                                                {
                                                                    array_push($arrayTimesSecond,$tsk->total_hours);
                                                                }
                                                            }

                                                           
                                                            $minutes = 0; 

                                                            foreach ($arrayTimesSecond as $time) {
                                                                list($hour, $minute) = explode(':', $time);
                                                                $minutes += $hour * 60;
                                                                $minutes += $minute;
                                                            }
                                                        
                                                            $hours = floor($minutes / 60);
                                                            $minutes -= $hours * 60;
                                                        
                                                            
                                                            $totalHoursSpentSecond = sprintf('%02d:%02d', $hours, $minutes);
                                                            
                                                         @endphp
                                                        
                                                         <td>{{$totalHoursSpentSecond}}</td>

                                                        @else
                                                         <td>Não iniciou ainda</td>
                                                         <td>Não iniciou ainda</td>
                                                         <td>Não iniciou ainda</td>
                                                        @endif
                                                       
                                                       

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                              </table>

                                              <hr>

                                              <p style="text-align:center;">
                                                TAREFAS FECHADAS HOJE
                                            </p>
                                            {{-- <div class="row"> --}}

                                            
                                            <table>
                                                <thead>
                                                    <tr style="text-align:center;">
                                                        <th>Nome Tarefa</th>
                                                        <th>Nome Cliente</th>
                                                        <th>Soma tempos gastos na tarefa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($infoSendEmail["terceiro_quadro"] as $info )
                                                    <tr style="text-align:center;">
                                                        <td>{{$info->reference}}</td>
                                                        <td>{{$info->taskCustomer->name}}</td>

                                                        @php
                                                        $arrayTimesThird = [];
                                                        foreach($info->tasksTimes as $tsk)
                                                        {
                                                            if($tsk->date_end != "")
                                                            {
                                                                array_push($arrayTimesThird,$tsk->total_hours);
                                                            }
                                                        }

                                                       
                                                        $minutes = 0; 

                                                        foreach ($arrayTimesThird as $time) {
                                                            list($hour, $minute) = explode(':', $time);
                                                            $minutes += $hour * 60;
                                                            $minutes += $minute;
                                                        }
                                                    
                                                        $hours = floor($minutes / 60);
                                                        $minutes -= $hours * 60;
                                                    
                                                        
                                                        $totalHoursSpentThird = sprintf('%02d:%02d', $hours, $minutes);
                                                        
                                                     @endphp
                                                    
                                                     <td>{{$totalHoursSpentThird}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                              </table>
                                            {{-- </div> --}}

                                            <hr>

                                            <p style="text-align:center;">
                                                TEMPOS FECHADOS HOJE
                                            </p>
                                            {{-- <div class="row"> --}}

                                            
                                            <table>
                                                <thead>
                                                    <tr style="text-align:center;">
                                                        <th>Referência</th>
                                                        <th>Estado da Tarefa</th>
                                                        <th>Técnico</th>
                                                        <th>Data</th>
                                                        <th>Hora</th>
                                                        <th>Cliente</th>
                                                        <th>Descrição do Tempo</th>
                                                        <th>Horas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($infoSendEmail["quarto_quadro"] as $item )
                                                    <tr style="text-align:center;">
                                                        <td>{{ $item->tasksReports->reference }}</td>
                                                        <td>
                                                            @if($item->tasksReports->reportStatus == 0)
                                                            {{__("Agendada")}}
                                                            @elseif($item->tasksReports->reportStatus == 1)
                                                            {{__("Em Curso")}}
                                                            @else
                                                                {{__("Finalizada")}}
                                                            @endif
                                                        </td>                                                      
                                                    
                                                        @php
                                                            $user = \App\Models\User::where('id',$item->tech_id)->first();
                                                        @endphp
                                                        <td>{{ $user->name}}</td>
                                                        <td>{{ $item->date_begin }}</td>
                                                        <td>{{ $item->hour_begin }} / {{ $item->hour_end }}</td>
                                                        <td>{{ $item->tasksReports->taskCustomer->short_name }}</td>
                                                        <td>{{ $item->descricao }}</td>
                                                        <td>{{ global_hours_format($item->total_hours) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                              </table>
                                           
                                            <hr>
                                                <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787e; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                                    {{__("Compliments")}},<br>
                                                    <strong>{{ $company_name }}</strong>
                                                </p>
                                        </td>
                                    </tr>
                                </table>
                            
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0"
                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                    <tr>
                                        <td class="content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                            <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.3em; margin-top: 0; color: #aeaeae; font-size: 11px; text-align: center;">
                                                {{ $company_name }}<br>
                                                {{ $address }}<br>
                                                NIF: {{ $vat }} | Tel: {{ $contact }} | Tel: {{ $email }}<br>
                                            </p>
                                            <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #aeaeae; font-size: 12px; text-align: center;">
                                                <br><small>Chamada para a rede móvel nacional</small>
                                            </p>
                                            <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #aeaeae; font-size: 12px; text-align: center;">
                                                {{ date('Y') }} © Todos os direitos reservados.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>

</html>
