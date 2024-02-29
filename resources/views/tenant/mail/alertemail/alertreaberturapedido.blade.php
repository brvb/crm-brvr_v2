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
                                        <td class="content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                            <p style="text-align:center;padding-bottom:6px;">
                                                <img src="{{ global_tenancy_asset('/app/public/images/logo/' . $logotipo) }}" alt="{{ $company_name }}">
                                            </p>
                                           <hr>
                                            
                                            <div class="table" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                <p style="font-family:Arial, Helvetica, sans-serif; color: #74787e;">
                                                    <b>O pedido #{{$task->reference}} <span style="text-decoration: underline;color:red;">sofreu uma RECLAMAÇÃO</span> {{date('Y-m-d H:i:s')}}</b>
                                                </p>
                                        
                                                <p>
                                                    <span>Cliente: {{$cliente->customers->name}}</span><br>
                                                    <span>Telefone: {{$cliente->customers->phone}}</span><br>
                                                    <span>Email: {{$cliente->customers->email}}</span><br>
                                                    <span>Técnico designado: {{$task->tech->name}}</span><br>
                                                    <span>Assunto: {{$task->descricao}}</span><br>
                                                </p>
                                                <p>A sua reclamação será tratada com a maior brevidade possível.</p>
                                            </div>
                                            <hr>
                                                <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787e; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                                    {{__("Compliments")}},<br>
                                                    <strong>{{ $company_name }}</strong>
                                                </p>
                                                <p>
                                                    <small>
                                                        <label style="font-size: 1.5em;font-weight: bold;text-decoration: underline;">Não responda a este email. </label><br>
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
