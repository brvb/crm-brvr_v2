<x-tenant-layout title="{{ __('Dashboard') }}" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="container-fluid"> --}}
                <!-- Add Order -->
                <div class="modal fade" id="addOrderModalside">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Add Event</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form>
                          <div class="form-group">
                            <label class="text-black font-w500">Event Name</label>
                            <input type="text" class="form-control">
                          </div>
                          <div class="form-group">
                            <label class="text-black font-w500">Event Date</label>
                            <input type="date" class="form-control">
                          </div>
                          <div class="form-group">
                            <label class="text-black font-w500">Description</label>
                            <input type="text" class="form-control">
                          </div>
                          <div class="form-group">
                            <button type="button" class="btn btn-primary">Create</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- row -->
                
                @livewire('tenant.dashboard.show')

                {{-- <div class="modal fade" id="modalInfo" data-id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                </div> --}}
                
              {{-- </div> --}}
        </div>
    </div>
</x-tenant-layout>

{{-- @push('custom-scripts') --}}
<script>
  var mesAtual = (new Date).getMonth() + 1;
  var anoAtual = (new Date).getFullYear();
  var date = (new Date);

 
  document.addEventListener('livewire:load', function () {
      restartCalendar();
          
  });


  
  var timer = 0;
  var delay = 200;
  var prevent = false;


  jQuery("body").on("click","#pedidoLinha",function(){

    var idPedido = jQuery(this).attr('data-id');

    Livewire.emit("checkStatePedido",idPedido);

    
    // var referencia = jQuery(this).attr('data-referencia');
    // var cliente = jQuery(this).attr('data-cliente');

    // var clienteEspaco = cliente.replace('£',' ');

    // timer = setTimeout(function() {
    //   if (!prevent) {
    //     jQuery(".modal-body").empty();

    //     jQuery('#modalInfo').modal('show');
    //     jQuery(".modal-body").append("Referência: "+referencia+ "<br>Cliente: "+clienteEspaco); 

        
    //     jQuery("body").on("click","#abrirIntervencaoButton",function(){

    //       window.location.href="deleteTask/"+valueData;
    //     });

    //     jQuery("body").on("click","#consultarPedidoButton",function(){

    //       window.location.href="tasks/"+idPedido+"/edit";
    //     });
    //   }
    //   prevent = false;
    // }, delay);


  });

  
    
</script>   
{{-- @endpush --}}
