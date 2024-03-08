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
        <div class="card-header" style="padding:1!important;display:block;" wire:key="tenanttasksshow">
          <div class="row">
            <div class="col-9">
                <h4 class="card-title">Intervenções</h4>
            </div>
            <div class="col-3" style="text-align:end;padding-right:0;">
                <span style="font-weight:bolder">Tempo Gasto: {{$totalMinutos}} min</span>
            </div>
          </div>
          
                        
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
                            {{-- <th>Cliente</th> --}}
                            <th>Realizado</th>
                            <th>Tecnico</th>
                            <th>Data de inicio intervenção</th>
                            <th>Hora fim</th>
                            <th>Minutos Gastos</th>
                            <th>Descontos</th>
                            <th>Razão do desconto</th>
                            <th>Minutos com descontado</th>
                            <th>Minutos a apresentar</th>
                            <th>Anexos</th>
                            <th>Ações</th>
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
                                    {{$item->descricao_realizado}}
                                </td>
                                <td>
                                    @php
                                    $user = \App\Models\User::where('id',$item->user_id)->first();
                            
                                     @endphp
                                    {{$user->name}}
                                </td>
                                <td>
                                    {{$item->data_inicio}} / {{$item->hora_inicio}}
                                </td>
                                <td>
                                    {{$item->data_final}} / {{$item->hora_final}}
                                </td>
                                <td>
                                    @php
                                        $minutosSomados = 0;
                                        $somaDiferencasSegundos = 0;
                                        $horaFormatada = "";

                                        $arrHours[$item->id] = [];

                                        $dia_inicial = $item->data_inicio.' '.$item->hora_inicio;
                                        $dia_final = $item->data_inicio.' '.$item->hora_final;

                                        $data1 = Carbon\Carbon::parse($dia_inicial);
                                        $data2 = Carbon\Carbon::parse($dia_final);

                           
                                        $result = $data1->diffInMinutes($data2);


                                        if($item->descontos == null)
                                        {
                                            $item->descontos = "+0";
                                        }

                                    
                                        $minutosSomados += $result;

                                        if($item->descontos[0] == "+"){ 
                                            $minutosSomados += substr($item->descontos, 1);
                                        } 
                                        else { 
                                            $minutosSomados -= substr($item->descontos, 1);
                                        }

                                        $resultDivisao = $minutosSomados / 15;
                                        $resultBlocos = ceil($resultDivisao) * 15;

                                       
                                       
                                        
                                    @endphp
                                    @if($item->hora_final == null)
                                       00:00
                                    @else
                                       {{-- {{global_hours_sum_individual($arrHours[$item->id])}} --}}
                                       {{$result}} min
                                    @endif
                                    
                                </td>
                                <td>
                                    @if($item->descontos != "")
                                        {{$item->descontos}} minutos
                                    @endif
                                </td>
                                <td>
                                    {{$item->descricao_desconto}}
                                </td>
                                <td>
                                    @if($item->hora_final != null)
                                        {{$minutosSomados}} min
                                    @endif

                                </td>
                                <td>
                                    {{$resultBlocos}} min
                                </td>
                               <td>
                                 @if($item->anexos != "[]")
                                  <button type="button" wire:click="ver({{$item->anexos}})" class="btn btn-success" style="font-size: 12px;">Ver</button>
                               
                                 @endif
                               </td>
                               <td>
                                   

                                        <div class="dropdown">
                                            <button class="btn btn-primary tp-btn-light sharp" type="button" data-toggle="dropdown">
                                                <span class="fs--1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                            <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                            <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">

                                                @php
                                                    $user = \App\Models\Tenant\TeamMember::where('id',$item->tech_id)->first();
                                                @endphp
                                                
                                                @if($item->hora_final != null && $horaFormatada != "00:00")
                                                    <a class="dropdown-item" wire:click="editarTempo({{$item->id}})">Editar tempo</a>
                                                @endif

                                                <a class="dropdown-item" wire:click="removeTempo({{$item->id}})">Remover tempo</a>
                                                
            

                                            </div>
                                        </div>
                                
                                
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


        window.addEventListener('atualizarPagina',function(e){
            
            location.reload();
            
        });
        


        window.addEventListener('editarTempo',function(e){

           

            message = "";

            message += "<div>";
                message += "<label>Sinal</label><br>";
                message += "<select name='selectedSinal' id='selectedSinal' class='form-control'>";
                    message += "<option value='mais'>+</option>";
                    message += "<option value='menos' selected>-</option>";
                message += "</select>";
            message += "</div>";

            message += "<div>";
                message += "<label>Tempo a descontar</label><br>";
                message += "<input type='number' id='horaFinal' class='form-control'>";
            message += "</div>";

            message += "<div>";
                message += "<label>Descrição do desconto</label><br>";
                message += "<textarea class='form-control' id='desconto_descricao' name='desconto_descricao' rows='4' cols='50'></textarea>";
            message += "</div>";

            swal.fire({
                title: "Editar Tempo",
                html: message,
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                type: "info",
                onOpen: function() {

                    // jQuery('#horaFinal').clockpicker({
                    //     donetext: '<i class="fa fa-check" aria-hidden="true"></i>',
                    //     }).find('input').change(function () {
                    //         @this.set('previewHour', this.value, true);
                    // });

                    // jQuery("#horaFinal").val(e.detail.hora_final);

                }

            }).then((result) => {  

                var horaFinal = jQuery("#horaFinal").val();
                var selectedSinal = jQuery("#selectedSinal").val();
                var desconto_descricao = jQuery("#desconto_descricao").val();

                if(horaFinal != "" && desconto_descricao != "")
                {
                    Livewire.emit("mudarHora",e.detail.id, horaFinal,selectedSinal,desconto_descricao);
                }

            
                              
            });




        });
    });

</script>
@endpush