<div>
    <div>
        <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
            style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
            <div class="sk-three-bounce" style="background:none;">
                <div class="sk-child sk-bounce1"></div>
                <div class="sk-child sk-bounce2"></div>
                <div class="sk-child sk-bounce3"></div>
            </div>
        </div>
        <div class="card-header" style="padding:1!important;" wire:key="tenanttasksshow">
            <h4 class="card-title">Intervenções</h4>
           
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTables_wrapper" class="dataTables_wrapper">
                    <div class="dataTables_length" id="dataTables_length">
                        <label>{{ __('Show') }}
                            <select name="perPage" wire:model="perPage">
                                <option value="10"
                                    @if ($perPage == 10) selected @endif>10</option>
                                <option value="25"
                                    @if ($perPage == 25) selected @endif>25</option>
                                <option value="50"
                                    @if ($perPage == 50) selected @endif>50</option>
                                <option value="100"
                                    @if ($perPage == 100) selected @endif>100</option>
                            </select>
                            {{ __('entries') }}</label>
                    </div>
                    {{-- <div id="dataTables_search_filter" class="dataTables_filter">
                        <label>{{ __('Search') }}:
                            <input type="search" name="searchString" wire:model="searchString"></label>
                    </div> --}}
                </div>
                <table id="dataTables-data" class="display dataTable no-footer">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkAll" required="">
                                    <label class="custom-control-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th>Cliente</th>
                            <th>Realizado</th>
                            <th>Tecnico</th>
                            <th>Data da Intervenção</th>
                            <th>Anexos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasksTimes as $item)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheckBox{{ $item->id }}"
                                            required="">
                                        <label class="custom-control-label" for="customCheckBox{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $cliente = \App\Models\Tenant\Customers::where('id',$item->pedido->customer_id)->first();
                                
                                    @endphp
                                   {{$cliente->name}}
                                </td>
                                <td>
                                    {{$item->descricao_realizado}}
                                </td>
                                <td>
                                    @php
                                    $user = \App\Models\User::where('id',$item->user_id)->first();
                            
                                     @endphp
                                    {{$user->name}}
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>
                               <td>
                                 @if($item->anexos != "[]")
                                  <button type="button" wire:click="ver({{$item->anexos}})" class="btn btn-success" style="font-size: 12px;">Ver</button>
                               
                                 @endif
                               </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $tasksTimes->links() }}
            </div>
        </div>
    </div>
 
</div>
@push('custom-scripts')
<script>

    jQuery( document ).ready(function() {

        window.addEventListener('Intervencao',function(e){

           
            var tenant = @this.tenant;
            var protocolo = window.location.protocol;
            var nomeDoServidor = window.location.hostname;
            var taskReference = @this.reference;
            var task = @this.task;

            var anexos = e.detail.anexos;

            for(var img in anexos)
            {
                var urlImagem = protocolo+"//"+nomeDoServidor+"/cl/"+tenant+"/app/public/pedidos/intervencoes_anexos/"+task+"/"+anexos[img];  
                window.open(urlImagem, '_blank');
            }

        });
    });

</script>
@endpush