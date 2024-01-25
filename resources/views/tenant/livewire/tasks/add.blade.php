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
            <a class="nav-link {{ $homePanel }}" data-toggle="tab" href="#homePanel"><i class="la la-home mr-2"></i> Identificação</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $techPanel }}" data-toggle="tab" href="#techPanel"><i
                    class="flaticon-381-calendar mr-2"></i> Agendamento</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade {{ $homePanel }}" id="homePanel" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            <div class="basic-form">

                                <div class="container-fluid" style="position: relative;border:1px solid #49748fec;padding: 10px;border-radius:6px;">
                                    <div class="inner-text" style="position: absolute;top: 0%;left: 50%;transform: translate(-50%, -50%);background:white;">
                                        <h4>Identificação</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
    
                                            <div class="row form-group">
                                                <section class="col-12" style="text-align:end;">
                                                    <a class="btn btn-primary" id="addClientForm">
                                                        <i class="fa fa-user"></i> Criar Cliente
                                                    </a>
                                                </section>
                                                <section class="col" style="margin-top:20px;" wire:ignore>
                                                    <label>{{ __('Customer Name') }}</label>
                                                    <select name="selectedCustomer" id="selectedCustomer">
                                                        <option value="">{{ __('Select customer') }}</option>
                                                        @forelse ($customerList as $item)
                                                            <option value="{{ $item->id }}">{{ $item->vat }} | {{ $item->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </section>
                                            </div>
                                            @if(isset($selectedCustomer) && $selectedCustomer <> '')
                                            <div class="row form-group">
                                                <section class="col-xl-4 col-xs-12">
                                                
                                                    <label>{{ __('VAT') }}:</label>
                                                    
                                                    <input type="text" name="vat" id="vat" class="form-control"
                                                                value="{{ $customer->vat }}" readonly>
                                                                                                    
                                                    
                                                </section>
                                                <section class="col-xl-4 col-xs-12">
                                                
                                                    <label>{{ __('Phone number') }}</label>
                            
                                                    <input type="text" name="phone" id="phone" class="form-control"
                                                    value="{{ $customer->contact }}" readonly>
                                                    
                                                
                                                </section>
                                                <section class="col-xl-4 col-xs-12">
                                                
                                                    <label>{{ __('Primary e-mail address') }}</label>
                                                
                                                    <input type="text" name="email" id="email" class="form-control"
                                                    value="{{ $customer->email }}" readonly>
                                                    
                                                                                                
                                                </section>
                                            </div>
                                            <div class="row form-group">
                                                <section class="col-xl-12 col-xs-12">
                                                    <label>Contacto adicional</label>
                                                    
                                                    <input type="text" name="contacto_adicional" id="contacto_adicional" wire:model.defer="contactoAdicional" class="form-control">
                                                </section>
                                            </div>                                
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="container-fluid" style="position: relative;border:1px solid #49748fec;padding: 10px;margin-top:20px;border-radius:6px;">
                                    <div class="inner-text" style="position: absolute;top: 0%;left: 50%;transform: translate(-50%, -50%);background:white;">
                                        <h4>Serviço</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
    
                                            <div class="row form-group">
                                                <section class="col" style="margin-top:20px;" wire:ignore>
                                                    <label>Tipo Pedido</label>
                                                    <select name="selectedPedido" id="selectedPedido">
                                                        <option value="">Selecione Pedido</option>
                                                        @forelse ($pedidosList as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </section>
                                            </div>
                                            
                                        </div>
                                        <div class="col-6">
                                            <div class="row form-group">
                                                <section class="col" style="margin-top:20px;" wire:ignore>
                                                    <label>Tipo Serviço</label>
                                                    <select name="selectedServico" id="selectedServico">
                                                        <option value="">Selecione Serviço</option>
                                                        @forelse ($servicosList as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Descrição do Pedido</label>
                                            <textarea name="serviceDescription"
                                            class="form-control serviceDesription" id="serviceDescription"
                                            wire:model.defer="serviceDescription"
                                            rows="4"></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="container-fluid" style="position: relative;border:1px solid #49748fec;padding: 10px; margin-top:20px;border-radius:6px;">
                                    <div class="inner-text" style="position: absolute;top: 0%;left: 50%;transform: translate(-50%, -50%);background:white;">
                                        <h4>Anexos</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
    
                                            <div class="row form-group">
                                                <section class="col" style="margin-top:20px;" wire:ignore>
                                                    <label>Anexos</label>
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
                                            
                                        </div>
                                       
                                    </div>
                                  
                                </div>

                                <div class="container-fluid" style="position: relative;border:1px solid #49748fec;padding: 10px; margin-top:20px;border-radius:6px;">
                                    <div class="inner-text" style="position: absolute;top: 0%;left: 50%;transform: translate(-50%, -50%);background:white;">
                                        <h4>Equipamentos</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
    
                                            <div class="row form-group">
                                                <section class="col" style="margin-top:20px;" wire:ignore>
                                                    <div class="form-check custom-checkbox checkbox-success">
                                                        <input type="checkbox" name="equipamentoServico" class="form-check-input" id="equipamentoServico">
                                                        <label class="form-check-label" for="customCheckBox3" style="font-size:18px;margin-top:0px;!important">{{ __('Equipment?') }}</label>
                                                    </div>
                                                </section>
                                            </div>

                                            <div class="row form-group" id="equipamentosSection" style="display:{{$stateEquipment}};">
                                                <section class="col" style="margin-top:20px;" wire:ignore>
                                                <div class="form-group row">
                                                    <section class="col-xl-4 col-xs-12">
                                                        <div class="row">
                                                            <div class="col-9">
                                                                <label>{{ __('Serie Number') }}</label>
                                                                <input type="text" name="serie_number" id="serie_number" class="form-control" value="{{ $serieNumber }}" wire:model.defer="serieNumber">
                                                            </div>
                                                            <div class="col-3" style="padding-left:0px;">
                                                                <label style="visibility:hidden;">Acesso</label>
                                                                <a class="btn btn-primary" wire:click="searchSerieNumber">
                                                                    <i class="fa fa-search"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        
                                                    </section>
                                                    <section class="col-xl-4 col-xs-12">
                                                        <label>{{ __('Marca') }}</label>
                                                        <input type="text" name="marca_equipment" id="marca_equipment" class="form-control" value="{{ $marcaEquipment }}" wire:model.defer="marcaEquipment">
                                                    </section>
                                                    <section class="col-xl-4 col-xs-12">
                                                        <label>{{ __('Model')}}</label>
                                                        <input type="text" name="model_equipment" id="model_equipment" class="form-control" value="{{ $modelEquipment }}" wire:model.defer="modelEquipment">
                                                    </section>
                                                </div>
        
                                                <div class="form-group row pr-2 pl-2">
                                                    <section class="col-12 mt-2">
                                                        <label>{{ __('Name Equipment') }}</label>
                                                        <input type="text" name="name_equipment" id="name_equipment" class="form-control" value="{{ $nameEquipment }}" wire:model.defer="nameEquipment">
                                                    </section>
                                                    <section class="col-12">
                                                        <label>{{ __('Description') }}</label>
                                                        <textarea name="descriptionEquipment" class="form-control"
                                                        id="descriptionEquipment" wire:model.defer="descriptionEquipment"
                                                        rows="4"></textarea>
                                                    </section>
                                                </div>
        
                                                <div class="form-group row pr-2 pl-2">
                                                   <div class="col-6">
                                                    <section class="col-12 mt-2">
                                                        <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="riscado" class="form-check-input" id="riscado" wire:model.defer="riscado">
                                                            <label class="form-check-label" for="customCheckBox3">{{ __('Scratched') }}</label>
                                                        </div>
        
                                                        <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="partido" class="form-check-input" id="partido" wire:model.defer="partido">
                                                            <label class="form-check-label" for="customCheckBox3">{{ __('Broken') }}</label>
                                                        </div>
        
                                                        <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="bomestado" class="form-check-input" id="bomestado" wire:model.defer="bomestado">
                                                            <label class="form-check-label" for="customCheckBox3">{{ __('Good State') }}</label>
                                                        </div>
        
                                                        <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="normalestado" class="form-check-input" id="normalestado" wire:model.defer="normalestado">
                                                            <label class="form-check-label" for="customCheckBox3">{{ __('Normal State') }}</label>
                                                        </div>
                                                    </section>
                                                   </div>
                                                   <div class="col-6">
                                                    <section class="col-12 mt-2">
                                                        <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="transformador" class="form-check-input" id="transformador" wire:model.defer="transformador">
                                                            <label class="form-check-label" for="customCheckBox3">{{ __('Transformer') }}</label>
                                                        </div>
        
                                                        <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="mala" class="form-check-input" id="mala" wire:model.defer="mala">
                                                            <label class="form-check-label" for="customCheckBox3">{{ __('Bag') }}</label>
                                                        </div>
        
                                                        <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="tinteiro" class="form-check-input" id="tinteiro" wire:model.defer="tinteiro">
                                                            <label class="form-check-label" for="customCheckBox3">{{ __('Toners') }}</label>
                                                        </div>
        
                                                        <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="ac" class="form-check-input" id="ac" wire:model.defer="ac">
                                                            <label class="form-check-label" for="customCheckBox3">{{ __('Mouse/Pen') }}</label>
                                                        </div>
                                                    </section>
                                                   </div>
                                                   <div class="col-12 mt-4">
                                                    <label>{{ __('Description Extra') }}</label>
                                                    <textarea name="descriptionExtra" class="form-control"
                                                    id="descriptionExtra" wire:model.defer="descriptionExtra"
                                                    rows="4"></textarea>
                                                   </div>
                                                   
                                                </div>

                                                <div class="form-group row pr-2 pl-2">
                                                  
                                                    <div class="col-12">
                
                                                        <div class="row form-group">
                                                            <section class="col" style="margin-top:20px;" wire:ignore>
                                                                <label>Anexos do Equipamento</label>
                                                                <div class="custom-file">
                                                                    <input type="file" name="uploadFile" id="uploadFileEquipamento" class="custom-file-input">
                                                                    <label class="custom-file-label">{{__("Choose file")}}</label>
                                                                </div>
                                                            </section>
                                                        </div>
                                                        <div class="row form-group">
                                                            <section class="col" style="margin-top:20px;" wire:ignore>
                                                                <div id="receiveImagesEquipamento"></div>
                                                            </section>
                                                        </div>
                                                        
                                                    </div>
                                            
                                                  
                                                </div>

                                                </section>
                                            </div>
                                            
                                        </div>
                                       
                                    </div>
                                  
                                </div>

                                @if($this->selectedCustomer != "")
                                    <div class="container-fluid" style="position: relative;border:1px solid #49748fec;padding: 10px; margin-top:20px;border-radius:6px;">
                                        <div class="inner-text" style="position: absolute;top: 0%;left: 50%;transform: translate(-50%, -50%);background:white;">
                                            <h4>Local</h4>
                                        </div>
                                        <div wire:ignore>
                                            <label>{{ __('Location') }}</label>
                                            <select name="selectedLocation" id="selectedLocation">
                                                <option value="">{{ __('Please select location') }}</option>
                                                @forelse ($customerLocations as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->description }} | {{ $item->locationCounty->name }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                @endif






                                {{-- <div class="row" style="margin-top:20px;">
                                    <div class="col text-right">
                                        <a wire:click="cancel" class="btn btn-secondary mr-2">
                                            Atrás
                                        </a>
                                        <a wire:click="saveIdentificacao" class="btn btn-primary">
                                            <i class="las la-check mr-2"></i>Criar Identificação
                                        </a>
                                    </div>
                                </div> --}}






                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
       
    <div class="tab-pane fade {{ $techPanel }}" id="techPanel">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                    <div class="card-body">
                        <div class="basic-form">
                            <div class="row">
                                <div class="col">
                                    <div class="container-fluid" style="position: relative;border:1px solid #49748fec;padding: 10px;border-radius:6px;">
                                        <div class="inner-text" style="position: absolute;top: 0%;left: 50%;transform: translate(-50%, -50%);background:white;">
                                            <h4>Prioridade</h4>
                                        </div>
                                       
                                            <div class="form-group row">
                                                <section class="col-xl-4 col-xs-12">
                                                    <label>Data</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" class="datepicker-default" value="{{ date('Y-m-d') }}"  readonly>
                                                        <span class="input-group-append"><span class="input-group-text">
                                                            <i class="fa fa-calendar-o"></i>
                                                        </span></span>
                                                    </div>
                                                </section>
                                                <section class="col-xl-4 col-xs-12">
                                                    <label>Hora</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" value="{{ date('H:i') }}"  readonly>
                                                        <span class="input-group-append"><span class="input-group-text"><i
                                                                    class="fa fa-clock-o"></i></span></span>
                                                    </div>
                                                </section>
                                                <section class="col-xl-4 col-xs-12" wire:ignore>
                                                    <label>{{ __('Nível de prioridade') }}</label>
                                                    <select name="prioridadeColors" id="prioridadeColors" wire:model.defer="selectPrioridade" class="form-control">
                                                        @foreach ($coresObject as $cor)
                                                            <option style="background:{{$cor->cor}};" value="{{$cor->id}}">
                                                                
                                                                {{ $cor->nivel}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </section>
                                            </div>

                                    </div>

                                    <div class="container-fluid" style="position: relative;border:1px solid #49748fec;padding: 10px;border-radius:6px;margin-top:20px;">
                                        <div class="inner-text" style="position: absolute;top: 0%;left: 50%;transform: translate(-50%, -50%);background:white;">
                                            <h4>Atribuição de Pedido</h4>
                                        </div>

                                        
                                        <div class="form-group row">
                                            <section class="col-xl-3 col-xs-12" wire:ignore>
                                                <label>{{ __('Service Technician') }}</label>
                                                <select name="selectedTechnician" id="selectedTechnician">
                                                    <option value="">{{ __('Select Technician') }}</option>
                                                    @if(isset($membersList))
                                                        @forelse ($membersList as $item)
                                                        <option value="{{ $item->id }}"
                                                            @if(isset($customer->account_manager) && !isset($selectedTechnician))
                                                                selected
                                                            @elseif(isset($selectedTechnician) && $item->id == $selectedTechnician) selected @endif>
                                                                {{ $item->name }}
                                                        </option>
                                                        @empty
                                                        @endforelse
                                                    @endif
                                                </select>
                                            </section>
                                            <section class="col-xl-3 col-xs-12" wire:ignore>
                                                <label>{{ __('Origin request') }}</label>
                                                <select name="origem_pedido" id="origem_pedido">
                                                    <option value="">{{ __('Selecione Origem do Pedido') }}</option>
                                                    
                                                    <option value="Pessoalmente"
                                                        @if($origem_pedido == "Pessoalmente")
                                                            selected @endif>
                                                            Pessoalmente
                                                    </option>
                                                    <option value="Telefone"
                                                        @if($origem_pedido == "Telefone")
                                                            selected @endif>
                                                            Telefone
                                                    </option>
                                                    <option value="E-mail"
                                                        @if($origem_pedido == "E-mail")
                                                            selected @endif>
                                                            E-mail
                                                    </option>
                                                    <option value="WhatsApp"
                                                        @if($origem_pedido == "WhatsApp")
                                                            selected @endif>
                                                            WhatsApp
                                                    </option>
                                            
                                                </select>
                                            </section>

                                            <section class="col-xl-3 col-xs-12" wire:ignore>
                                                <label>Tipo de Agendamento</label>
                                                <select name="tipo_pedido" id="tipo_pedido">
                                                    <option value="">{{ __('Selecione Tipo de agenda') }}</option>
                                                    
                                               
                                                    <option value="Externo"
                                                        @if($tipo_pedido == "Externo")
                                                            selected @endif>
                                                            Externo
                                                    </option>
    
                                                    <option value="Interno"
                                                        @if($tipo_pedido == "Interno")
                                                            selected @endif>
                                                            Interno
                                                    </option>
                                                    <option value="Projecto"
                                                        @if($tipo_pedido == "Projecto")
                                                            selected @endif>
                                                            Projeto
                                                    </option>
    
                                                    <option value="Remoto"
                                                        @if($tipo_pedido == "Remoto")
                                                        selected @endif>
                                                        Remoto
                                                    </option>
                                              
                                                  
                                                </select>
                                            </section>
                                            <section class="col-xl-3 col-xs-12">
                                                <label>{{ __('Who asked') }}</label>
                                                <input name="quem_pediu" class="form-control"
                                                    id="quem_pediu" wire:model.defer="quem_pediu" value="{{ $quem_pediu }}">
                                                       
                                            </section>
    
                                            
                                        </div>

                                    </div>

                                    <div class="container-fluid" style="position: relative;border:1px solid #49748fec;padding: 10px;border-radius:6px;margin-top:20px;">
                                        <div class="inner-text" style="position: absolute;top: 0%;left: 50%;transform: translate(-50%, -50%);background:white;">
                                            <h4>Agendar</h4>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
        
                                                <div class="row form-group">
                                                    <section class="col" style="margin-top:20px;" wire:ignore>
                                                        <div class="form-check custom-checkbox checkbox-success">
                                                            <input type="checkbox" name="agendarCheck" class="form-check-input" id="agendarCheck">
                                                            <label class="form-check-label" for="customCheckBox3" style="font-size:18px;margin-top:0px;!important">Agendar?</label>
                                                        </div>
                                                    </section>
                                                </div>

                                                <div class="row form-group" id="agendarSection" style="display:{{$stateAgenda}};">
                                                   
                                                        <section class="col-xl-6 col-xs-12" wire:ignore>
                                                            <label>Data de Agendamento</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" class="datepicker-default" id="preview_date">
                                                                <span class="input-group-append"><span class="input-group-text">
                                                                    <i class="fa fa-calendar-o"></i>
                                                                </span></span>
                                                            </div>
                                                        </section>
                                                        <section class="col-xl-6 col-xs-12">
                                                            <label>Hora de Agendamento</label>
                                                            <div class="input-group preview_hour">
                                                                <input type="text" class="form-control">
                                                                <span class="input-group-append"><span class="input-group-text"><i
                                                                            class="fa fa-clock-o"></i></span></span>
                                                            </div>
                                                        </section>
                                                   
                                                        <section class="col-xl-12 col-xs-12">
                                                            <label>Observações</label>
                                                            <textarea name="observacoesAgendar" class="form-control"
                                                            id="observacoesAgendar" wire:model.defer="observacoesAgendar"
                                                            rows="4"></textarea>
                                                        </section>
                                                  
                                                   
                                                </div>


                                            </div>
                                        </div>


                                    </div>
                                 
                                    

                                    <div class="form-group row" style="margin-top:20px;padding-left:10px;">
                                        <section class="col">
                                            <div class="form-check custom-checkbox checkbox-success">
                                                <input type="checkbox" name="email_alert" class="form-check-input" id="customCheckBox3" wire:model.defer="alert_email">
                                                <label class="form-check-label" for="customCheckBox3">Não enviar email de alerta</label>
                                            </div>
                                        </section>
                                    </div>

                                  
                                </div>
                            </div>

    


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="card" style="display: table-cell;width:100vw;">
        <div class="card-footer justify-content-between">
            <div class="row">
                <div class="col text-right">
                    <a wire:click="cancel" class="btn btn-secondary mr-2">
                        Atrás
                    </a>
                    <a wire:click="savePedido" class="btn btn-primary">
                        <i class="las la-check mr-2"></i>Criar Pedido
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    
        
   

   
  
    
    @push('custom-scripts')
    <script>
        var services = [];
        document.addEventListener('livewire:load', function () {

            restartObjects();

        });

        jQuery("body").on('click',"#addClientForm", function(){

            Livewire.emit("FormAddClient");
        });
       
        

        jQuery("body").on('click','#equipamentoServico', function(){
            //checkar se esta selecionado
            if(jQuery(this).is(":checked")){

                jQuery("#equipamentosSection").css("display","block");
                @this.set('stateEquipment', "block");
                @this.set('homePanel', "show active");
                @this.set('techPanel', "");

            }
            else {

                @this.set('homePanel', "show active");
                @this.set('techPanel', "");

                jQuery("#serie_number").val("");
                jQuery("#marca_equipment").val("");
                jQuery("#model_equipment").val("");

                jQuery("#name_equipment").val("");
                jQuery("#descriptionEquipment").val("");

                jQuery("#riscado").prop('checked', false);
                jQuery("#partido").prop('checked', false);
                jQuery("#bomestado").prop('checked', false);
                jQuery("#normalestado").prop('checked', false);

                jQuery("#transformador").prop('checked', false);
                jQuery("#mala").prop('checked', false);
                jQuery("#tinteiro").prop('checked', false);
                jQuery("#ac").prop('checked', false);

                jQuery("#descriptionExtra").val("");



                jQuery("#equipamentosSection").css("display","none");
                @this.set('stateEquipment', "none");

            }

        })

        jQuery("body").on('click','#agendarCheck', function(){
            if(jQuery(this).is(":checked")){

                jQuery("#agendarSection").css("display","flex");
                @this.set('stateAgenda', "flex");
                @this.set('techPanel', "show active");
                @this.set('homePanel', "");

            } else {
                jQuery("#agendarSection").css("display","none");
                @this.set('stateAgenda', "none");
                @this.set('techPanel', "show active");
                @this.set('homePanel', "");
            }
        });



        //Criação email Tasks

        window.addEventListener('createCustomer', function (e) {

        swal.fire({
            title: e.detail.title,
            html: e.detail.message,
            type: e.detail.status,
            showCancelButton: false,
            showconfirmButton: false,

        });


        jQuery(".swal2-confirm").css("display","none");

          jQuery(".swalBox .row").on("click", "#buttonresponseCustomer",function(){

                if(jQuery(this).attr("data-anwser") == "ok")
                {
                    var customer_name = jQuery("#customer_name").val();
                    var nif = jQuery("#nif").val();
                    var contact = jQuery("#contact").val();
                    var email = jQuery("#email").val();

                    window.livewire.emit("createCustomerFormResponse",customer_name,nif,contact,email);

                    jQuery(this).remove();
                    jQuery(".swalBox").remove();
                    jQuery(".swal2-container").remove();

                    Swal.close();
                }
                else {
                    Swal.close();
                }

           });

        });



        //Fim email Tasks





        window.addEventListener('SendEmailTech', function (e) {

            swal.fire({
                title: e.detail.title,
                html: e.detail.message,
                type: e.detail.status,
                showCancelButton: false,
                showconfirmButton: false,
            
            });
       

            jQuery(".swal2-confirm").css("display","none");

            jQuery(".swalBox .row").on("click", "#buttonresponse",function(){
            
                if(jQuery(this).attr("data-anwser") == "ok")
                {
                    var email = jQuery("#emailToReceive").val();

                    var response = jQuery(this).attr("data-anwser");

                    window.livewire.emit("responseEmailCustomer",email,response,e.detail.parameter_function);
                    jQuery(this).remove();
                    jQuery(".swalBox").remove();
                    jQuery(".swal2-container").remove();

                    Swal.close();
                }
                else {
                    window.livewire.emit("responseEmailCustomer",email,response,e.detail.parameter_function);
                    Swal.close();
                }

            });

    });

        window.addEventListener('swal',function(e){

            var flag;
            if(e.detail.whatfunction == "add" || e.detail.whatfunction == "servicesMissing")
            {
                swal.fire({
                title: e.detail.title,
                html: e.detail.message,
                type: "error",

                }).then((result) => {  
                    if(result.value){

                        restartObjects();
                        if(e.detail.function == 'client')
                        {
                            location.reload();
                        }
                    }                
                })
            }
           else if(e.detail.whatfunction == "finishInsert")
            {
                swal.fire({
                title: e.detail.title,
                html: e.detail.message,
                type: "success",

                }).then((result) => {  
                    if(result.value){

                        restartObjects();
                        if(e.detail.function == 'client')
                        {
                            location.reload();
                        }
                    }                
                })
            }
            
            else {

                if(typeof e.detail.function === "undefined"){
                    flag=true;
                } else {
                    flag = false;
                }

                swal.fire({
                title: e.detail.title,
                html: e.detail.message,
                showCancelButton: flag,
                cancelButtonText: "Cancelar",
                type: "error",

            }).then((result) => {  

                if(typeof e.detail.function === "undefined")
                {
                    if(result.value){

                        window.location.replace("http://"+window.location.hostname+"/services/create");

                        restartObjects();
                        if(e.detail.function == 'client')
                        {
                            location.reload();
                        }
                    }   
                }
                              
            })
          }
         
           
        });

        function restartObjects()
        {

            if(jQuery("#equipamentoServico").is(":checked")){

                jQuery("#equipamentosSection").css("display","block");
                @this.set('stateEquipment', "block");
            }
            else {
                jQuery("#equipamentosSection").css("display","none");
                @this.set('stateEquipment', "none");
            }

            if(jQuery("#agendarCheck").is(":checked")){

                jQuery("#agendarSection").css("display","flex");
                @this.set('stateAgenda', "flex");
            }
            else {
                jQuery("#agendarSection").css("display","none");
                @this.set('stateAgenda', "none");
            }

            /* valido */
            jQuery('#selectedCustomer').select2();
            jQuery("#selectedCustomer").on("select2:select", function (e) {
                @this.set('selectedCustomer', jQuery('#selectedCustomer').find(':selected').val());
            });

            jQuery('#selectedPedido').select2();
            jQuery("#selectedPedido").on("select2:select", function (e) {
                @this.set('selectedPedido', jQuery('#selectedPedido').find(':selected').val());
            });

            jQuery('#selectedServico').select2();
            jQuery("#selectedServico").on("select2:select", function (e) {
                @this.set('selectedServico', jQuery('#selectedServico').find(':selected').val());
            });

            jQuery('#selectedLocation').select2();
            jQuery("#selectedLocation").on("select2:select", function (e) {
                @this.set('selectedLocation', jQuery('#selectedLocation').find(':selected').val());
            });



            //***AGENDAMENTOS*//
            jQuery('#selectedTechnician').select2();
            jQuery("#selectedTechnician").on("select2:select", function (e) {
                @this.set('selectedTechnician', jQuery('#selectedTechnician').find(':selected').val(),true);
            });

            jQuery('#tipo_pedido').select2();
            jQuery("#tipo_pedido").on("select2:select", function (e) {
                @this.set('tipo_pedido', jQuery('#tipo_pedido').find(':selected').val(),true);
            });

            jQuery('#origem_pedido').select2();
            jQuery("#origem_pedido").on("select2:select", function (e) {
                @this.set('origem_pedido', jQuery('#origem_pedido').find(':selected').val(),true);
            });
          







          

           
            function formatState (state) {

                var base_url = "https://suporte.brvr.pt/cl/7f3a1b73-d8ae-464f-b91e-2a3f8163bdfb/app/public/tasks_colors";
    
                if (!state.id) {
                    return state.text;
                }
            
                var $state = $(
                    '<span><img src="' + base_url + '/' + state.id + '.png" class="img-flag" style="width:30px;" /> ' + state.text + '</span>'
                );
                return $state;
            };



            @this.set('selectPrioridade',1,true);

            jQuery('#prioridadeColors').select2({
                templateResult: formatState,
                templateSelection: formatState
            });


            jQuery("#prioridadeColors").on("select2:select", function (e) {
                @this.set('selectPrioridade', jQuery('#prioridadeColors').find(':selected').val(), true)
            });
      
            jQuery('#selectedTechnician').select2();
            jQuery("#selectedTechnician").on("select2:select", function (e) {
                @this.set('selectedTechnician', jQuery('#selectedTechnician').find(':selected').val(), true)
            });

            jQuery('#tipo_pedido').select2();
            jQuery("#tipo_pedido").on("select2:select", function (e) {
                @this.set('tipo_pedido', jQuery('#tipo_pedido').find(':selected').val(),true);
            });

            jQuery('#origem_pedido').select2();
            jQuery("#origem_pedido").on("select2:select", function (e) {
                @this.set('origem_pedido', jQuery('#origem_pedido').find(':selected').val(),true);
            });


            jQuery('.selectedService').each(function() {
                var selectId = '#' + jQuery(this).attr('id');
                jQuery(selectId).off('select2:select');
                jQuery(selectId).select2();
                jQuery(selectId).on('select2:select', function (e) {
                    @this.set('selectedServiceId.' + jQuery(selectId).attr('data-rel'), jQuery(selectId).find(':selected').val());
                });
            });


            jQuery('#preview_date').pickadate({
                monthsFull:["{!! __('January') !!}","{!! __('February') !!}","{!! __('March') !!}","{!! __('April') !!}","{!! __('May') !!}","{!! __('June') !!}","{!! __('July') !!}","{!! __('August') !!}","{!! __('September') !!}","{!! __('October') !!}","{!! __('November') !!}","{!! __('December') !!}"],
                weekdaysShort: ["{!! __('Sun') !!}","{!! __('Mon') !!}","{!! __('Tue') !!}","{!! __('Wed') !!}","{!! __('Thu') !!}","{!! __('Fri') !!}","{!! __('Sat') !!}"],
                today: "{!! __('today') !!}",
                clear: "{!! __('clear') !!}",
                close: "{!! __('close') !!}",
                onSet: function(thingSet) {
                    @this.set('previewDate', formatDate(thingSet.select), true);
                    jQuery('#preview_date').val(formatDate(thingSet.select));
                }
            });
            


            $('.preview_hour').clockpicker({
                donetext: '<i class="fa fa-check" aria-hidden="true"></i>',
            }).find('input').change(function () {
                @this.set('previewHour', this.value, true);
            });
            
        }

        window.addEventListener('loading', function(e) {
            @this.loading();
        })

      

        window.addEventListener('contentChanged', function(e) {
            restartObjects();
        })

        window.addEventListener('refreshPage', function(e) {
            window.location.reload();
        })
        
  

              
       
        function formatDate(unixDate)
        {
            var date = new Date(unixDate);
            var year = date.getFullYear();
            var month = "0" + (date.getMonth()+1);
            var day = "0" + date.getDate();
            var formattedTime = year + '-' + month.substr(-2) + '-' + day.substr(-2);
            return formattedTime;
        }



        //PARTE DA IMAGEM

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

        jQuery("#uploadFileEquipamento").change(function (){
                
                var fileName = jQuery(this).val().replace(/C:\\fakepath\\/i, '');

                if(fileName != "")
                {
                                            
                    let file = document.querySelector('#uploadFileEquipamento').files[0];

                    @this.upload('uploadFileEquipamento', file, (uploadedFilename) => {
                        
                        //se isto ja for maior que 25 nao deixa colocar
                    });
                    
                    message = "<button type='button' id='badge-click' class='btn-xs' style='border-radius:50px;border:1px;cursor:auto;pointer-events:none;'>";
                    message += fileName;
                    message += "</button>";    
                    

                    jQuery("section #receiveImagesEquipamento").append(message);

                    jQuery("#equipamentosSection").css("display","block");
                    
                }

            
            });




    </script>
    @endpush
</div>


