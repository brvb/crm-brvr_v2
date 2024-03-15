
<div>
    <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
        style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
        <div class="sk-three-bounce" style="background:none;">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $reportPanel }}" data-toggle="tab" href="#reportPanel">
                <i class="la la-home mr-2"></i> {{ __('Report') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $timesPanel }}" data-toggle="tab" href="#timesPanel">
                <i class="la la-hourglass-end mr-2"></i> {{ __('Times') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">        
    
        <div class="tab-pane fade {{ $reportPanel }}" id="reportPanel" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            <div class="basic-form">
                                <div class="row">
                                    <div class="col">
                                        <div class="row form-group" style="display:flex;justify-content:end;">
                                            <section class="col-xl-2 col-xs-2 form-group text-right">
                                                <h4 style="color:#326c91;">Tempo Gasto</h4>
                                                <h4>
                                                   

                                                    @if($horasAtuais == "[]") 
                                                    0 
                                                    @else 
                                                    {{$horasAtuais}} min
                                                    @endif
                                                </h4>
                                                
                                            </section>
                                        </div>
                                        <div class="row form-group">
                                            <section class="col-xl-12 col-xs-12 form-group">
                                                <label>Descrição do Pedido</label>
                                                <textarea class="form-control" rows="4" cols="50" name="notes" id="notes"  disabled>@if($task->descricao != null){{$task->descricao}}@endif</textarea>
                                            </section>

                                            <section class="col-xl-12 col-xs-12 mb-2">
                                                <label>Produtos?</label>
                                                <input type="checkbox" id="verificaProdutos" wire:model="verificaProdutos">
                                            </section>
                                           
                                           
                                                <section class="col-xl-12 col-xs-12 form-group">
                                                
                                                    @if(!empty($arrayProdutos))
                                                
                                                        <div class="form-group row">
                                                            <section class="col-xl-12 col-xs-12 form-group">
                                                                <table class="table">
                                                                    <thead style="background:lightgray;">
                                                                        <tr>
                                                                            <th>Referência</th>
                                                                            <th>Designação</th>
                                                                            <th>Quantidade</th>
                                                                            <th>Ação</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        
                                                                        @foreach ($arrayProdutos as $i => $prod )
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $prod["referencia"] }}
                                                            
                                                                                </td>
                                                                                <td>
                                                                                
                                                                                    @if( $prod["referencia"] == "")
                                                                                    
                                                                                    <input class="form-control" type="text" name="designacao_intervencao"  wire:model.defer="designacao_intervencao.{{$i}}.description">
                                                                                    @else
                                                                                    {{$arrayDesignacoes[$i]["description"]}}
                                                                                    {{-- {{ $arrayDesignacoes[$i] }} --}}
                                                                                                    
                                                                                    @endif
                                                                                    
                                                                                    {{-- <textarea class="form-control" rows="4" cols="50" name="referencia_intervencao"  wire:model.defer="referencia_intervencao.{{$i}}"></textarea> --}}
                                                                                </td>
                                                                                <td>
                                                                                    <input class="form-control" type="number"  wire:model.defer="quantidade_intervencao.{{$i}}">
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-primary" wire:click="removeProduto({{$i}})">Remover</button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </section>
                                                        </div>
                                                    @endif

                                                
                                                </section>

                                                <section class="col-xl-12 col-xs-12 form-group" id="produtosDIV">
                                                
                                                    <div class="form-group row" style="display:flex;margin-right:0px;justify-content:end;">
                                                        <button type="button" class="btn btn-secondary" wire:click="adicionaProduto">Adiciona Produto</button>
                                                    </div>
                                                    
                                                    <div class="form-group row">
                                                        <section class="col-xl-12 col-xs-12 form-group">  
                                                            <label>Produtos:</label>                                 
                                                            <select name="selectedProdutos" id="selectedProdutos">
                                                                <option value="">Selecione Produto</option>
                                                                 @if(isset($produtos))
                                                                    @forelse ($produtos as $item)
                                                                        <option value="{{ $item->reference }}">{{ $item->reference }} | {{ $item->description }}</option>
                                                                    @empty
                                                                    @endforelse
                                                                 @endif
                                                            </select>
                                                        </section>
                                                    </div>
                                                    <div class="form-group row">
                                                        <section class="col-xl-12 col-xs-12 form-group">
                                                            <label>Descrição</label>
                                                            <textarea class="form-control" rows="4" cols="50" name="descricao_intervencao" id="descricao_intervencao" wire:model.defer="descricao_intervencao"></textarea>
                                                        </section>
                                                        
                                                    </div>
                                                </section>
                                          

            
                                            <section class="col-12 form-group" wire:ignore>
                                                <label>Estado do Pedido</label>
                                                <select name="selectedEstado" id="selectedEstado">
                                                    <option value="">Selecione estado do pedido</option>
                                                    @if(isset($statesPedido))
                                                        @forelse ($statesPedido as $item)
                                                            @foreach ( $item->estadoPedido as $est)
                                                           
                                                                @if($est->id != 5 && $est->id != 6 && $est->id != 1 )
                                                                <option value="{{ $est->id }}">
                                                                        {{ $est->nome_estado }}
                                                                </option>
                                                                @endif
                                                            @endforeach
                                                          
                                                        @empty
                                                        @endforelse
                                                    @endif
                                                </select>
                                            </section>

                                            @if($selectedEstado != "7")

                                            <div class="container-fluid" style="display:{{$descricaoPanel}};position: relative;border:1px solid #49748fec;padding: 10px;border-radius:6px;margin-top:20px;">
                                                
                                                <section class="col-xl-12 col-xs-12" wire:ignore>
                                                    <label>{{ __('Nível de prioridade') }}</label>
                                                    <select name="prioridadeColors" id="prioridadeColors" wire:model.defer="selectPrioridade" class="form-control">
                                                            <option value="0">Não mudar</option>
                                                        @foreach ($coresObject as $cor)
                                                            <option style="background:{{$cor->cor}};" value="{{$cor->id}}">
                                                                
                                                                {{ $cor->nivel}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </section>

                                                <div class="inner-text" style="position: absolute;top: 0%;left: 50%;transform: translate(-50%, -50%);background:white;">
                                                    <h4>Descrição da Intervenção</h4>
                                                </div>

                                                <section class="col-xl-12 col-xs-12 form-group mt-2">
                                                    <label>Descrição</label>
                                                    <textarea class="form-control" rows="4" cols="50" name="descricaoRealizado" id="descricaoRealizado" wire:model.defer="descricaoRealizado"></textarea>
                                                </section>

                                                @if($selectedEstado == "2")
                                                    <section class="col-xl-12 col-xs-12 form-group">
                                                        <label>Selecione Sinal</label>
                                                        <select name="selectSignal" id="selectSignal" wire:model.defer="selectSignal" class="form-control">
                                                            <option value="+" selected>+</option>
                                                            <option value="-">-</option>
                                                        </select>
                                                    </section>

                                                    <section class="col-xl-12 col-xs-12 form-group">
                                                        <label>Tempo</label>
                                                        <input type='number' id='horaFinal' wire:model.defer="horaFinal" class='form-control'>
                                                    </section>
                                                @endif

                                                <section class="col-xl-12 col-xs-12 form-group">
                                                    <div class="row form-group">
                                                        <section class="col" style="margin-top:20px;" wire:ignore>
                                                            <label>Anexos do Equipamento</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="uploadFile" id="uploadFile" class="custom-file-input">
                                                                <label class="custom-file-label">{{__("Choose file")}}</label>
                                                            </div>
                                                        </section>
                                                    </div>
                                                    <div class="row form-group">
                                                        <section class="col" style="margin-top:20px;" wire:ignore>
                                                            <div id="receiveImages"></div>
                                                        </section>
                                                    </div>
                                                </section>

                                                <section class="col-xl-12 col-xs-12 form-group" style="display:{{$signaturePad}}">
                                                    <div class="container text-center" style="margin-top: 0">
                                                        <label>Técnico</label>
                                                        <div class="row">
                                                            <div id="signature-container" class="col-12 col-lg-6 offset-lg-3 align-self-center signature-container">
                                                                <canvas id="signature-pad" class="w-100 border border-dark rounded signature-pad" wire:ignore></canvas>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-12 text-center">
                                                                <button onclick="clearSignature('signature-pad', 'Técnico');" class="btn btn-primary">Limpar Assinatura Técnico</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>

                                                <section class="col-xl-12 col-xs-12 form-group" style="display:{{$signaturePad}}">
                                                    <div class="container text-center" style="margin-top: 0">
                                                        <label>Cliente</label>
                                                        <div class="row">
                                                            <div id="signature-container-cliente" class="col-12 col-lg-6 offset-lg-3 align-self-center signature-container" >
                                                                <canvas id="signature-pad-cliente" class="w-100 border border-dark rounded signature-pad" wire:ignore></canvas>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-12 text-center">
                                                                <button onclick="clearSignature('signature-pad-cliente', 'Cliente');" class="btn btn-primary">Limpar Assinatura Cliente</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>

                                                
                                                
                                                <section class="col-xl-12 col-xs-12 form-group" style="display:{{$signaturePad}}">
                                                    <div class="container text-center" style="margin-top: 0">
                                                        {{-- <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="email_pdf" class="form-check-input" id="customCheckBox3" wire:model.defer="email_pdf">
                                                            <label class="form-check-label" for="customCheckBox3">Enviar PDF ao cliente</label>
                                                        </div> --}}
                                                    </div>
                                                </section>

                                            </div>
                                            @endif               


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade {{ $timesPanel }}" id="timesPanel" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            @livewire('tenant.tasks-times.show-times',['task' => $task])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $user = \App\Models\Tenant\TeamMember::where('id',$task->tech_id)->first();
    @endphp
    <div class="card">
        <div class="card-footer justify-content-between">
            <div class="row">
                <div class="col text-right">
                    <a wire:click="cancel" class="btn btn-secondary mr-2">
                        Atrás
                    </a>
                   
                        <a class="btn btn-primary" id="addInter">
                            Adicionar intervenção
                            <span class="btn-icon-right"><i class="las la-check mr-2"></i></span>
                        </a>
                  
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
  
    <script>
        var services = [];
        document.addEventListener('livewire:load', function () {
            //restartObjects();
            checkConclusion();
            jQuery('#selectedCustomer').select2();
            jQuery('#selectedProdutos').select2();
        });

        document.addEventListener('reloadProdutos', function () {
            
            jQuery('#selectedProdutos').select2();
            // jQuery("#selectedProdutos").on("select2:select", function (e) {
            //     @this.set('selectedProdutos', jQuery('#selectedProdutos').find(':selected').val());
            // });
        });

        function restartObjects()
        {
            jQuery('#tipo_pedido').select2();
            jQuery("#tipo_pedido").on("select2:select", function (e) {
                @this.set('tipo_pedido', jQuery('#tipo_pedido').find(':selected').val(),true);
            });

            jQuery('#selectedProdutos').select2();
            jQuery("#selectedProdutos").on("select2:select", function (e) {
                @this.set('selectedProdutos', jQuery('#selectedProdutos').find(':selected').val());
            });
        }

        window.addEventListener('loading', function(e) {
            @this.loading();
           
        });


        jQuery('body').on('click', '#addInter', function() {
            
            var cliente = "";
            var tecnico = "";
         
            if (Array.isArray(arraySignatures['signature-pad-cliente'])) {
                cliente = arraySignatures['signature-pad-cliente'][0];
            } else {
                
                cliente = "";
            }


            if (Array.isArray(arraySignatures['signature-pad'])) {
                tecnico = arraySignatures['signature-pad'][0];
            } else {
                
                tecnico = "";
            }
            
                    
            jQuery('#selectedProdutos').select2();


            Livewire.emit("teste",cliente,tecnico);
        });

        window.addEventListener('swal', function(e) {
            swal.fire({
                title: e.detail.title,
                html: e.detail.message,
                type: "error",
            });
           
        });


        window.addEventListener('contentChanged', event => {
            restartObjects();
        });

       
        jQuery( document ).ready(function() {

            jQuery('#selectedProdutos').select2();
            jQuery("#selectedProdutos").on("select2:select", function (e) {
                @this.set('selectedProdutos', jQuery('#selectedProdutos').find(':selected').val());
            });

            jQuery('#selectedEstado').select2();
            jQuery("#selectedEstado").on("select2:select", function (e) {
                @this.set('selectedEstado', jQuery('#selectedEstado').find(':selected').val(),true);

                if(jQuery('#selectedEstado').find(':selected').val() == "")
                {
                    @this.set('descricaoPanel','none');
                }
                else if(jQuery('#selectedEstado').find(':selected').val() == "2")
                {
                    @this.set('signaturePad','block'); 
                    @this.set('descricaoPanel','block');
                } 
                else if(jQuery('#selectedEstado').find(':selected').val() == "1"){
                    @this.set('descricaoPanel','none');
                    @this.set('signaturePad','none'); 
                }
                else {
                    @this.set('descricaoPanel','block');
                    @this.set('signaturePad','none'); 
                }
                
            });

            function formatState (state) {

                var base_url = "https://suporte.brvr.pt/cl/brv2-7f3a1b73-d8ae-464f-b91e-2a3f8163bdfb/app/public/tasks_colors";

                if (!state.id) {
                    return state.text;
                }

                var $state = $(
                    '<span><img src="' + base_url + '/' + state.id + '.png" class="img-flag" style="width:30px;" /> ' + state.text + '</span>'
                );


                return $state;
            };



            @this.set('selectPrioridade',0,true);

            jQuery('#prioridadeColors').select2({
                templateResult: formatState,
                templateSelection: formatState
            });


            jQuery("#prioridadeColors").on("select2:select", function (e) {
                @this.set('selectPrioridade', jQuery('#prioridadeColors').find(':selected').val(), true)
            });
        });

        jQuery("#uploadFile").change(function (){
                      
            var fileName = jQuery(this).val().replace(/C:\\fakepath\\/i, '');

            if(fileName != "")
            {
                                        
                let file = document.querySelector('#uploadFile').files[0];

                @this.upload('uploadFile', file, (uploadedFilename) => {
                    
                    //se isto ja for maior que 25 nao deixa colocar
                });
                
                message = "<button type='button' id='badge-click' class='btn-xs' style='border-radius:50px;border:1px;cursor:auto;pointer-events:none;'>";
                message += fileName;
                message += "</button>";    
                

                jQuery("section #receiveImages").append(message);

            }
          
        });



    </script>
    @endpush
</div>
