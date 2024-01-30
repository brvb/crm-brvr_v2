<div>
<div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
  <div class="sk-three-bounce" style="background:none;">
      <div class="sk-child sk-bounce1"></div>
      <div class="sk-child sk-bounce2"></div>
      <div class="sk-child sk-bounce3"></div>
  </div>
</div>
<div class="modal fade" id="modalInfo" data-id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informação Pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
      </div>
      <div class="modal-footer">
        <!-- Fazer aqui   -->
        <button type="button" id="abrirIntervencaoButton" class="btn btn-success" style="font-size: 12px;">Abrir Intervenção</button>
        <button type="button" id="consultarPedidoButton" class="btn btn-danger" style="font-size: 12px;">Consultar Pedido</button> 
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size: 12px;">Fechar</button>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="row">
       
        <div class="col-xl-12" style="height:50%;">
          {{-- <div class="row"> --}}
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Pedidos Abertos</h4>
              </div>
              <div class="card-body" style="display:flex;overflow:auto;">

            <div class="table-responsive" style="position: relative;">
              {{-- class="display dataTable no-footer" --}}
              <table id="dataTables-data" class="table table-responsive-lg mb-0 table-striped">
                  <thead>
                      <tr>
                        <th>{{ __('Reference') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Descrição') }}</th>
                        <th>{{ __('Technical') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('County') }}</th>
                        <th>{{ __('Estado do Pedido') }}</th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach ($pedidos as $item)
                      <tr id="pedidoLinha" data-id="{{$item->id}}" data-cliente="{{str_replace(' ', '£', $item->customer->short_name)}}" data-referencia="{{$item->reference}}" style="background:{{ $item->prioridadeStat->cor }};">
              
                        <td>{{ $item->reference }}</td>
                        <td>{{ $item->customer->short_name }}</td>
                        <td>{{ $item->servicesToDo->name}}</td>
                        <td>{{ $item->tech->name }}</td>
                        <td>
                            @if($item->data_agendamento != "")
            
                            <i class="fa fa-calendar" aria-hidden="true"></i> {{ $item->data_agendamento }}<br>
                            <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $item->hora_agendamento }}
                            @else
                            <i class="fa fa-calendar" aria-hidden="true"></i> {{ date('Y-m-d',strtotime($item->created_at)) }}<br>
                            <i class="fa fa-clock-o" aria-hidden="true"></i> {{ date('H:i',strtotime($item->created_at)) }}
                            @endif
                        </td>
                        <td>{{ $item->location->locationCounty->name }}</td>

                        <td>{{ $item->tipoEstado->nome_estado }}</td>
                      </tr> 
                    @endforeach
                  </tbody>
              </table>
          </div>
        {{-- </div> --}}

          </div>
        </div>

          {{-- @if(Auth::user()->type_user == 0) --}}

            <div class="col-xl-12" style="margin-top:20px;padding-left:0px;padding-right:0px;">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Intervenções em aberto</h4>
                </div>
                <div class="card-body" style="display:flex;overflow:auto;">
                  <table class="table mb-4 dataTablesCard no-hover card-table fs-14 dataTable no-footer" id="data5" role="grid" aria-describedby="data5_info">
                      <thead>
                        <tr role="row" style="background:#326c91;">
                          <th class="sorting" style="color:white;" tabindex="0" aria-controls="data5" rowspan="1" colspan="1">{{ __('Technical') }}</th>
                          <th class="sorting" style="color:white;" tabindex="0" aria-controls="data5" rowspan="1" colspan="1">{{ __('Customer') }}</th>
                          <th class="d-lg-inline-block sorting" style="color:white;" tabindex="0" aria-controls="data5" rowspan="1" colspan="1">{{ __('Task') }}</th>
                          <th class="sorting" style="color:white;" tabindex="0" aria-controls="data5" rowspan="1" colspan="1">{{ __('Time used') }}</th>
                          <th></th>
                        </tr> 
                      </thead>
                      <tbody>
                        @foreach($openTimes as $name => $time)
                          @if(!empty($time))
                          <tr>
                            <td>
                            <h4>
                                <a href="javascript:void(0)" class="text-black">{{ $name }}</a>
                            </h4>
                            </td>
                            <td>{{ $time["cliente"] }}</td>
                            <td>
                              <i class="fa fa-tasks" aria-hidden="true"></i>
                              {{ $time["reference"] }} <br>
                            </td>
                            <td>
                              <i class="fa fa-calendar" aria-hidden="true"></i>
                              {{ date('Y-m-d',strtotime($time["data"])) }} <br>
                              <i class="fa fa-clock-o" aria-hidden="true"></i>
                              {{ date('H:i:s',strtotime($time["data"])) }}
                            </td>
                         
                          </tr>
                          @endif
                        @endforeach
                      </tbody>
                  </table>
                </div>
              </div>
            </div>
            
          {{-- @endif --}}

        <div class="col-xl-12" style="margin-top:20px;padding-right:0;padding-left:0;">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">{{ __("Notifications")}}</h4>
            </div>
            <div class="card-body" style="display:flex;overflow:auto;">
              <table id="dataTables-data" class="table table-responsive-lg mb-0 table-striped">
                <thead>
                  <tr>
                    <th>{{ __('Service') }}</th>
                    <th>{{ __('Technical') }}</th>
                    <th>{{ __('Customer') }}</th>
                    <th>{{ __('Customer Location') }}</th>                   
                    <th>{{ __('Notification day') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($servicesNotifications != null)
                    @foreach ($servicesNotifications as $notification)
                      <tr>
                        <td>{{$notification["service"]}}</td>
                        <td>{{$notification["team_member"]}}</td>
                        <td>{{$notification["customer"]}}</td>
                        <td>{{$notification["customer_county"]}}</td>
                        <td>{{$notification["notification"]}}</td>
                        <td>
                          <div class="d-flex">
                            <button href="javascript:void(0)" wire:click="treated({{$notification["customerServicesId"]}})" class="btn btn-primary btn-sm light px-4">{{__("Treated")}}</button>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      
    </div>
    </div>
  </div>
</div>

@push('custom-scripts')
<script>
  jQuery( document ).ready(function() {

    window.addEventListener('interventionCheck',function(e){

        var nome_text = e.detail.parameter;
        var referencia = e.detail.reference;
        var idPedido = e.detail.idPedido;
        var cliente = e.detail.cliente;

        if(nome_text == "fechar"){
          jQuery("#abrirIntervencaoButton").text("Fechar Intervenção");
        } else {
          jQuery("#abrirIntervencaoButton").text("Abrir Intervenção");
        }

          timer = setTimeout(function() {
              if (!prevent) {
                jQuery(".modal-body").empty();

                jQuery('#modalInfo').modal('show');
                jQuery(".modal-body").append("Referência: "+referencia+ "<br>Cliente: "+cliente); 

                
                jQuery("body").on("click","#abrirIntervencaoButton",function(){

                  window.location.href="tasks-reports/"+idPedido+"/edit";
                });

                jQuery("body").on("click","#consultarPedidoButton",function(){

                  window.location.href="tasks/"+idPedido+"/edit";
                });
              }
              prevent = false;
          }, delay);
        

    });



  });
</script>
@endpush

</div>