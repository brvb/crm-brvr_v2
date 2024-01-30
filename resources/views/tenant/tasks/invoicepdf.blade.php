{{-- <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Invocando a folha de estilo usando asset() -->
    <style>
      body {
        font-family: Arial, sans-serif;
      }
      h2 {
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0.5rem 0;
      }
      h3{
        font-size: 1.35rem;
        margin: 0.5rem 0;
      }
      .logo {
        align-self: flex-start;
      }
      img.logogrande {
        position: relative;
      }
      hr{
        background: #b9b9b9; /* Cor branca */
    padding: 0.1px;
    width: 100%;
    height: auto;
    border: none;
      }

      .invoice {
        width: 100%;
        height: 100%;
        padding: 0.2rem;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }

      .invoice-header {
        display: flex;
        margin-bottom: 20px;
        flex-direction: column;
      }

      .invoice-header-left {
        display: flex;
        justify-content: space-between;
      }

      .invoice-left{
        padding: 0rem 0rem 1rem 0rem;
      }

      table.invoice-table {
        width: 100%;
        border-collapse: collapse;
        border-bottom:10px solid #ffffff00;
        margin-bottom: -10px;
      }

      table.invoice-table.last{
        border-bottom: 1px solid #000;
        margin-bottom: 3rem;
      }

      .invoice-table th{
        border: 1px solid #000;
        padding: 10px 10px;
        text-align: center;
      }

      .invoice-table td {
        border: 1px solid #000;
        padding: 25px 10px;
        text-align: center;
      }

      .invoice-table th {
        background-color: #326c91;
        color: #fff;
        font-weight: bold;
        text-align: center;
      }

      footer{
        padding: 1rem 0rem 0rem  0rem;
        display: flex;
        justify-content: space-between;
      }
      p.p-footer{
        color: #858585;
        font-size:0.5rem;
      }
    </style>

    <title>Sua Página PDF</title>
  </head>
  <body>
    <!-- Conteúdo da sua página PDF aqui -->
    <div class="invoice">
      <div class="invoice-header">
        <div class="logo">
          <img class="logogrande" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(global_tenancy_asset('/app/public/images/logo/' . $config->logotipo))) }}" alt="Company Logo" width="280">
        </div>
        <hr></hr>
        <div class="invoice-header-left">
          <div class="invoice-left">
            <h2>Referência :</h2>
            <p>{{$impressao->task->reference}}</p>
            <p>Data: {{date('Y-m-d')}}</p>
            <br>
            <h2>Cliente</h2>
            <p>{{$impressao->task->customer->name}}</p>
            <br>
            <h2>Problema</h2>
            <p>{{$impressao->task->descricao}}</p>
          </div>

          <div class="invoice-right">
            <h3>{{$config->company_name}} :</h3>
            <p>
              {{$config->address}}
            </p>
            <p>
              Email:
              {{$config->email}}
            </p>
            <p>
              Contacto:
              {{$config->contact}}
            </p>
          </div>
        </div>
      </div>

      <table class="invoice-table">
        <thead>
          <tr>
            <th>Resolução</th>
            <th>Horas Gastas</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{$impressao->descricaoRealizado}}</td>
            @if($impressao->horasAlterado != 0)
                <td>{{$impressao->horasAlterado}}</td>
            @else
                <td>{{$impressao->horasAtuais}}</td>
           @endif
          </tr>
        </tbody>
      </table>

      @if($impressao->signatureTecnico != null)
      <table class="invoice-table">
        <thead>
          <tr>
            <th>Assinatura Técnico</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(global_tenancy_asset('/app/public/pedidos/assinaturas/'.$impressao->task->reference.'/'.$impressao->signatureTecnico))) }}" alt="Company Logo" width="150">
            </td>
          </tr>
        </tbody>
      </table>
       @endif

      @if($impressao->signatureClient != null)
      <table class="invoice-table 
      last">
        <thead>
          <tr>
            <th>Assinatura Cliente</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(global_tenancy_asset('/app/public/pedidos/assinaturas/'.$impressao->task->reference.'/'.$impressao->signatureClient))) }}" alt="Company Logo" width="150">
            </td>
          </tr>
        </tbody>
      </table>
      @endif

      <hr></hr>
      <footer class="footer">
        <div class="logo-pequena">
          <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(global_tenancy_asset('/app/public/images/logo/' . $config->logotipo))) }}" alt="Company Logo" width="130">
          </div>
          <p class="p-footer">
           {{$config->address}}
          </p>
          <p  class="p-footer">
            Email:
            {{$config->email}}
          </p>
          <p  class="p-footer">
            Contacto:
            {{$config->contact}}
          </p>
      </footer>
    </div>
  </body>
</html> --}}



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Invocando a folha de estilo usando asset() -->
    <style>
      body {
        font-family: Arial, sans-serif;
      }
      h2 {
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0.5rem 0;
      }
      h3{
        font-size: 1.35rem;
        margin: 0.5rem 0;
      }
      .logo {
        align-self: flex-start;
      }
      img.logogrande {
        position: relative;
      }
      hr{
        background:#4e4e4e; /* Cor branca */
    padding: 0.1px;
    width: 100%;
    height: auto;
    border: none;
      }

      .invoice {
        width: 100vw;
        height: 100vh;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }

     
      .header {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
        padding-top: 1rem;
      }

      table.table{
        width: 100%;
        border-collapse: collapse;
        border-bottom:10px solid #00000000;
      }
      .table th{
        font-size: 1.2rem;
        text-align: left;
      }
      .table th.right{
        align-self: flex-end;
        text-align: right;
      }

      .table td.right{
        align-self: flex-end;
        text-align: right;
      }

      .table td{
        text-align: left;
      }

      .table-flex{
        display: flex;
        gap: 1.5rem;
      }


      table.invoice-table {
        width: 100%;
        border-collapse: collapse;
      }

      table.invoice-table.last{
        width: 100%;
        border-bottom: 1px solid #000;
        border-collapse: collapse;
        margin-bottom: 3rem;
        margin-top: 2rem;
      }


      .invoice-table th{
        border: 1px solid #000;
        padding: 10px 10px;
        text-align: center;
        background-color: #326c91;
        color: #fff;
        font-weight: bold;
      }

      .invoice-table td {
        border: 1px solid #000;
        padding: 20px 10px;
        text-align: center;
      }

     
      footer{
        padding: 1rem 0rem 0rem  0rem;
        display: flex;
        justify-content: space-between;
        margin-top: auto;
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 50px; /* Ajuste conforme necessário */
      }
      p.p-footer{
        color: #4e4e4e;
        font-size: 0.8rem;
      }
    </style>

    <title>PDF</title>
  </head>
  <body style="position: relative;min-height: 100vh;">
    <!-- Conteúdo da sua página PDF aqui -->
    <div class="invoice">
      <div class="invoice-header">
        <div class="logo">
          <img
            class="logogrande"
            src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(global_tenancy_asset('/app/public/images/logo/' . $config->logotipo))) }}"
            alt="Company Logo"
            width="280"
          />
        </div>
        <hr></hr>
        <div class="header">
            <table class="table">
                <thead class="main-table">
                    <tr>
                      <th>Referência :</th>
                      <th class="right">{{$config->company_name}} :</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td >
                        <p style="color:#326c91;font-weight:bold;">{{$impressao->task->reference}}</p>
                        <p>Data: {{date('Y-m-d')}}</p>
                        <p>Cliente: {{$impressao->task->customer->name}}</p>
                      </td>
                    
                      <td class="right">
                        <p>
                          {{$config->address}}
                        </p>
                        <p>
                          Email:
                          {{$config->email}}
                        </p>
                        <p>
                          Contacto:
                          {{$config->contact}}
                        </p>
                      </td>
                      
                    </tr>
                  </tbody>
                </table>

           
                <table class="table">
                    <thead class="main-table">
                        <tr>
                          <th>Problema :</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><p>{{$impressao->task->descricao}}</p></td>
                        </tr>
                      </tbody>
                    </table>
                
        </div>
      </div>

      <table class="invoice-table" style="border-bottom:none;">
        <thead>
          <tr>
            <th>Resolução</th>
            <th style="background: white;border:none;"></th>
            <th>Horas Gastas</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{$impressao->descricaoRealizado}}</td>
            <td style="background: white;border-color:white;border:none;border-bottom:none;">
            @if($impressao->horasAlterado != 0)
            <td>{{$impressao->horasAlterado}}</td>
            @else
                      <td>{{$impressao->horasAtuais}}</td>
                  @endif
          </tr>
        </tbody>
      </table>
      <hr class="barra-final" style="margin-top:2rem"></hr>

      <p style="font-size: 1.2rem;font-weight:bold;">Assinaturas :</p>
      <div class="table-flex">
       @if($impressao->signatureTecnico != null)
       <table class="invoice-table last" style="border-bottom:none;">
        <thead>
          <tr>
            <th>Assinatura Técnico</th>
            <th style="background: white;border:none;"></th>
            <th>Assinatura Cliente</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(global_tenancy_asset('/app/public/pedidos/assinaturas/'.$impressao->task->reference.'/'.$impressao->signatureTecnico))) }}"
                alt="Company Logo" width="150" />
            </td>
            <td style="background: white;border-color:white;border:none;border-bottom:none;">
            </td>
            <td>
              <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(global_tenancy_asset('/app/public/pedidos/assinaturas/'.$impressao->task->reference.'/'.$impressao->signatureClient))) }}" alt="Company Logo" width="150" />
            </td>
          </tr>
        </tbody>
      </table>
      @endif
      </div>
     
      
     
    </div>
    
    <footer class="footer">
      <hr class="barra-final"></hr>
      <div class="logo-pequena">
          <img
             src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(global_tenancy_asset('/app/public/images/logo/' . $config->logotipo))) }}"
            alt="Company Logo"
            width="130"
          />
        </div>
        <p class="p-footer">
         {{$config->address}} &nbsp; {{$config->email}} &nbsp; {{$config->contact}}
        </p>
        </footer>
    
  </body>
 
</html>