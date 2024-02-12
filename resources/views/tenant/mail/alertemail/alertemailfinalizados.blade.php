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
                                <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #ffffff; margin: 0 auto; padding: 0; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                    <!-- Body content -->
                                    <tr>
                                        
                                        <td class="content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 30px 30px 30px 10px;">                                           
                                            <p style="text-align:center;padding-bottom:6px;">
                                                <img src="{{ global_tenancy_asset('/app/public/images/logo/' . $logotipo) }}" alt="{{ $company_name }}">
                                            </p>
                                            <hr>
                                            <div class="table" style="font-family:Arial, Helvetica, sans-serif; box-sizing: border-box; color: #74787e;">
                                                @php
                                                    $totalHorasMes = 0;
                                                @endphp

                                                @foreach ($intervencao as $intervencaoItem)
                                                    @php
                                                        $inicioTimestamp = strtotime($intervencaoItem->data_inicio . ' ' . $intervencaoItem->hora_inicio);
                                                        $finalTimestamp = strtotime($intervencaoItem->data_final . ' ' . $intervencaoItem->hora_final);
                                                        $diferencaEmSegundos = $finalTimestamp - $inicioTimestamp;
                                                        $totalHorasMes += $diferencaEmSegundos;
                                                    @endphp
                                                @endforeach

                                                @php
                                                    $totalHoras = floor($totalHorasMes / 3600);
                                                    $totalMinutos = floor(($totalHorasMes % 3600) / 60);
                                                    $tempoTotalFormatado = sprintf('%02d:%02d', $totalHoras, $totalMinutos);

                                                    $ultimaIntervencao = $intervencao->last();
                                                @endphp

                                                
                                                <p>
                                                    @php
                                                        $inicioTimestamp = strtotime($ultimaIntervencao->data_inicio . ' ' . $ultimaIntervencao->hora_inicio);
                                                        $finalTimestamp = strtotime($ultimaIntervencao->data_final . ' ' . $ultimaIntervencao->hora_final);
                                                        $diferencaEmSegundos = $finalTimestamp - $inicioTimestamp;

                                                        $horasUltimaIntervencao = floor($diferencaEmSegundos / 3600);
                                                        $minutosUltimaIntervencao = floor(($diferencaEmSegundos % 3600) / 60);
                                                        $tempoTotalFormatadoUltimaIntervencao = sprintf('%02d:%02d', $horasUltimaIntervencao, $minutosUltimaIntervencao);
                                                    @endphp
                                                </p>
                                                

                                                <p>O seu pedido #{{$task->reference}}foi <b>ENCERRADO</b> dia {{ $ultimaIntervencao->data_final }} às {{ $ultimaIntervencao->hora_final }}.</p>
                                                <p>O pedido durou {{ $tempoTotalFormatadoUltimaIntervencao }} horas</p><br>
                                                <p>Atualmente o seu saldo é de XXX horas</p><!-- No caso de cliente com bolsa de horas (falta adicionar no banco de dados e verificar aqui)-->
                                                <p>Neste mês já consumiu XXX horas</p><!-- No caso de cliente com avença mensal -->
                                            
                                            </div>
                                            <hr>
                                            <p style="font-family: Avenir, Helvetica, sans-serif;white-space: nowrap; box-sizing: border-box; color: #3d3d3d; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                                {{__("Compliments")}},<br>
                                                <strong>{{ $company_name }}</strong>
                                            </p>
                                            <p>
                                                <small>
                                                    Não responda a este email. <br>
                                                    Para qualquer esclarecimento use os contactos habituais:<br>
                                                    Telefone: 252646260 Email: suporte@brvr.pt <br>
                                                    Identifique sempre o número de pedido.
                                                </small>
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
