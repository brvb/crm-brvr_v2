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
            <a class="nav-link {{ $homePanel }}" data-toggle="tab" href="#homePanel"><i class="la la-home mr-2"></i> {{
                __('Customer') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $servicesPanel }}" data-toggle="tab" href="#servicesPanel"><i
                    class="flaticon-381-notepad mr-2"></i> {{ __('Service') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $equipmentPanel }}" data-toggle="tab" href="#equipmentPanel" id="equipmentNav" style="display:none;">
             <i class="flaticon-381-battery-9 mr-2"></i> {{ __('Equipment')}}</a>
        </li>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $techPanel }}" data-toggle="tab" href="#techPanel"><i
                    class="flaticon-381-calendar mr-2"></i> {{ __('Schedule') }}</a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link {{ $profile }}" data-toggle="tab" href="#contact"><i class="la la-phone mr-2"></i>
                Contacts</a>
        </li> --}}
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade {{ $homePanel }}" id="homePanel" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            <div class="basic-form">
                                <div class="row">
                                    <div class="col">
                                        <div class="row form-group">
                                            <section class="col" wire:ignore>
                                                <label>{{ __('Customer Name') }}</label>
                                                <select name="selectedCustomer" id="selectedCustomer" disabled>
                                                    <option value="{{ $taskToUpdate->taskCustomer->id }}" selected>{{
                                                        $taskToUpdate->taskCustomer->name }}</option>
                                                </select>
                                            </section>
                                        </div>
                                        <div class="row form-group">
                                            <section class="col-xl-3 col-xs-12">
                                                <label>{{ __('VAT') }}</label>
                                                <input type="text" name="vat" id="vat" class="form-control"
                                                    value="{{ $taskToUpdate->taskCustomer->vat }}" readonly>
                                            </section>
                                            <section class="col-xl-3 col-xs-12">
                                                <label>{{ __('Phone number') }}</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    value="{{ $taskToUpdate->taskCustomer->contact }}" readonly>
                                            </section>
                                            <section class="col-xl-6 col-xs-12">
                                                <label>{{ __('Primary e-mail address') }}</label>
                                                <input type="text" name="email" id="email" class="form-control"
                                                    value="{{ $taskToUpdate->taskCustomer->email }}" readonly>
                                            </section>
                                        </div>
                                        <div class="row form-group">
                                            <section class="col-12">
                                                <label>{{ __('Customer Address') }}</label>
                                                <input type="text" name="address" id="address" class="form-control"
                                                    value="{{ $taskToUpdate->taskCustomer->address }}" readonly>
                                            </section>
                                        </div>
                                        <div class="row form-group">
                                            <section class="col-xl-2 col-xs-12">
                                                <label>{{ __('Zip Code') }}</label>
                                                <input type="text" name="zipcode" id="zipcode" class="form-control"
                                                    value="{{ $taskToUpdate->taskCustomer->zipcode }}" readonly>
                                            </section>
                                            <section class="col-xl-5 col-xs-12">
                                                <label>{{ __('District') }}</label>
                                                <input type="text" name="district" id="district" class="form-control"
                                                    value="{{ $taskToUpdate->taskCustomer->customerDistrict->name }}"
                                                    readonly>
                                            </section>
                                            <section class="col-xl-5 col-xs-12">
                                                <label>{{ __('County') }}</label>
                                                <input type="text" name="zipcode" id="zipcode" class="form-control"
                                                    value="{{ $taskToUpdate->taskCustomer->customerCounty->name }}"
                                                    readonly>
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
        <div class="tab-pane fade {{ $servicesPanel }}" id="servicesPanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            <div class="basic-form">
                                <div class="row form-group">
                                    <div class="col">
                                        <label>{{ __('Location') }}</label>
                                        <select name="selectedLocation" id="selectedLocation" disabled>
                                            <option value="{{ $taskToUpdate->location_id }}" selected>
                                                {{ $taskToUpdate->taskLocation->description }} | {{ $taskToUpdate->taskLocation->locationCounty->name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-check custom-checkbox checkbox-success">
                                        <input type="checkbox" name="equipamentoServico" class="form-check-input" id="equipamentoServico" @if($riscado != null || $partido != null || $bomestado != null || $normalestado != null || $transformador != null || $mala != null || $tinteiro != null || $ac != null) checked @endif>
                                        <label class="form-check-label" for="customCheckBox3" style="font-size:18px;margin-top:0px;!important">{{ __('Equipment?') }}</label>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col">
                                        <hr>
                                        <button class="btn btn-light" wire:click="addServiceForm">
                                            {{ __('Add Service') }}
                                            <span class="btn-icon-right"><i class="las la-plus-circle"></i></span>
                                        </button>
                                        <hr>
                                    </div>
                                </div>
                                @if(isset($numberOfSelectedServices) && $numberOfSelectedServices > 0)
                                    @for($i=0; $i < $numberOfSelectedServices; $i++)
                                        <div class="row form-group">
                                            <div class="col-9">
                                                <label>{{ __('Service Name') }}</label>
                                                <select name="selectedService_{{ $i }}" id="selectedService_{{ $i }}"
                                                    class="selectedService" data-rel="{{ $i }}">
                                                    <option value="">{{ __('Select Service') }}</option>
                                                    @forelse ($customerServicesList as $item)
                                                    <option value="{{ $item->service->id }}"
                                                        @if(isset($selectedServiceId[$i]) && $selectedServiceId[$i] == $item->service->id) selected
                                                        @endif>{{ $item->service->name }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="col-3 text-right">
                                                <button class="btn btn-light" wire:click="removeServiceForm({{ $i }})">
                                                    {{ __('Remove Service') }}
                                                    <span class="btn-icon-right"><i class="las la-minus-circle"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col">
                                                <label>{{ __('Service description or notes ') }}</label>
                                                <textarea name="serviceDescription_{{ $i }}"
                                                    class="form-control serviceDesription" id="serviceDescription_{{ $i }}"
                                                    wire:model.defer="serviceDescription.{{ $i }}" data-rel="{{ $i }}"
                                                    rows="4">
                                                @if(isset($serviceDescription[$i]) && $serviceDescription[$i] != '') {{ $serviceDescription[$i] }} @endif
                                                </textarea>
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

     {{-- Parte do equipamento --}}
     <div class="tab-pane fade {{ $equipmentPanel }}" id="equipmentPanel">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                    <div class="card-body">
                        <div class="basic-form">
                            <div class="row">
                                <div class="col">

                                    @if($imagem != '')
                                        <div class="form-group row" style="text-align:end;">
                                            <section class="col-12">
                                                <button type="button" id="badge-click" wire:click="downloadEtiqueta" class="btn-xs" style="border-radius:50px;border:1px;cursor:auto;pointer-events:none;">Etiqueta
                                                    &nbsp;&nbsp;<span class="badge badge-sm light badge-primary" style="cursor:pointer;pointer-events:auto;"><i class="fa-lg fa fa-download"></i></span>
                                                </button>&nbsp;
                                            </section>
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <section class="col-4">
                                            <label>{{ __('Serie Number') }}</label>
                                            <input type="text" name="serie_number" id="serie_number" class="form-control" value="{{ $serieNumber }}" wire:model.defer="serieNumber">
                                        </section>
                                        <section class="col-4">
                                            <label>{{ __('Marca') }}</label>
                                            <input type="text" name="marca_equipment" id="marca_equipment" class="form-control" value="{{ $marcaEquipment }}" wire:model.defer="marcaEquipment">
                                        </section>
                                        <section class="col-4">
                                            <label>{{ __('Model')}}</label>
                                            <input type="text" name="model_equipment" id="model_equipment" class="form-control" value="{{ $modelEquipment }}" wire:model.defer="modelEquipment">
                                        </section>
                                    </div>

                                    <div class="form-group row pr-2 pl-2" style="border-top: 1px solid;">
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

                                    <div class="form-group row pr-2 pl-2" style="border-top: 1px solid;">
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
                                    
                                </div>
                            </div>
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

                                    <div class="form-group row">
                                        <section class="col">
                                            <label>{{ __('Nível de prioridade') }}</label>
                                            <select name="prioridadeColors" id="prioridadeColors" wire:model.defer="selectPrioridade" class="form-control">
                                                @foreach ($coresObject as $cor)
                                                    <option style="background:{{$cor->cor}};" value="{{$cor->id}}">
                                                        <span class="badge badge-primary rounded-circle" style="background:{{$cor->cor}}; padding:10px 10px!important;">
                                                        </span>
                                                        <span>{{$cor->nivel}}</span>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </section>
                                    </div>

                                    @if(isset($numberOfSelectedServices) && $numberOfSelectedServices > 0)
                                    <div class="form-group row">
                                        <section class="col">
                                            <label>{{ __('Service Technician') }}</label>
                                            <select name="selectedTechnician" id="selectedTechnician">
                                                <option value="">{{ __('Select Technician') }}</option>
                                                
                                                @if(isset($teamMembers))
                                                @forelse ($teamMembers as $item)
                                                <option value="{{ $item->id }}" @if(isset($selectedTechnician) &&
                                                    $item->id == $selectedTechnician) selected @endif>{{ $item->name }}
                                                </option>
                                                @empty
                                                @endforelse
                                                @endif
                                            </select>
                                        </section>
                                    </div>
                                    <div class="form-group row" wire:ignore>
                                        <section class="col-3">
                                            <label>{{ __('Preview date') }}</label>
                                            <div class="input-group">
                                                <input type="text" name="preview_date" id="preview_date"
                                                    class="form-control" class="datepicker-default" @if($previewDate)
                                                    value="{{ $previewDate }}" @endif>
                                                <span class="input-group-append"><span class="input-group-text"><i
                                                            class="fa fa-calendar-o"></i></span></span>
                                            </div>
                                        </section>
                                        <section class="col-3">
                                            <label>{{ __('Preview hour') }}</label>
                                            <div class="input-group preview_hour">
                                                <input type="text" class="form-control" value="{{ $previewHour }}">
                                                <span class="input-group-append"><span class="input-group-text"><i
                                                            class="fa fa-clock-o"></i></span></span>
                                            </div>
                                        </section>
                                        <section class="col-3">
                                            <label>{{ __('Scheduled date') }}</label>
                                            <div class="input-group">
                                                <input type="text" name="scheduled_date" id="scheduled_date"
                                                    class="form-control" @if($taskToUpdate->scheduled_date) value="{{
                                                $taskToUpdate->scheduled_date }}"@endif>
                                                <span class="input-group-append"><span class="input-group-text"><i
                                                            class="fa fa-calendar-o"></i></span></span>
                                            </div>
                                        </section>
                                        <section class="col-3">
                                            <label>{{ __('Scheduled hour') }}</label>
                                            <div class="input-group scheduled_hour">
                                                <input type="text" class="form-control" value="{{ $scheduledHour }}">
                                                <span class="input-group-append"><span class="input-group-text"><i
                                                            class="fa fa-clock-o"></i></span></span>
                                            </div>
                                        </section>
                                    </div>

                                    <div class="form-group row">
                                        <section class="col-4">
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

                                        <section class="col-4">
                                            <label>{{ __('Type of Request') }}</label>
                                            <select name="tipo_pedido" id="tipo_pedido">
                                                <option value="">{{ __('Selecione Tipo de pedido') }}</option>
                                                
                                                <option value="Comercial"
                                                    @if($tipo_pedido == "Comercial")
                                                        selected @endif>
                                                        Comercial
                                                </option>
                                                <option value="Externo"
                                                    @if($tipo_pedido == "Externo")
                                                        selected @endif>
                                                        Externo
                                                </option>
                                                <option value="Faturado"
                                                    @if($tipo_pedido == "Faturado")
                                                        selected @endif>
                                                        Faturado
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

                                        <section class="col-4">
                                            <label>{{ __('Who asked') }}</label>
                                            <input name="quem_pediu" class="form-control"
                                                id="quem_pediu" wire:model.defer="quem_pediu" value="{{ $quem_pediu }}">
                                                   
                                        </section>


                                    </div>

                                    <div class="form-group row">
                                        <section class="col">
                                            <label>{{ __('Resume') }}</label>
                                            <input name="resume" class="form-control"
                                                id="resume" wire:model.defer="resume" value="{{ $resume }}">
                                        </section>
                                    </div>

                                    <div class="form-group row">
                                        <section class="col">
                                            <label>{{ __('Additional notes') }}</label>
                                            <textarea name="taskAdditionalDescription" class="form-control"
                                                id="taskAdditionalDescription" wire:model.defer="taskAdditionalDescription"
                                                rows="4">
@if($taskToUpdate->additional_description != '') {{ $taskToUpdate->additional_description }} @endif
</textarea>
                                        </section>
                                    </div>

                                    <div class="form-group row">
                                        <section class="col">
                                            <div class="form-check custom-checkbox checkbox-success">
                                                <input type="checkbox" name="email_alert" class="form-check-input" id="customCheckBox3" wire:model.defer="alert_email" @if($taskToUpdate->alert_email != null) @if($taskToUpdate->alert_email == "1") checked @endif @endif>
                                                <label class="form-check-label" for="customCheckBox3">Enviar email de alerta</label>
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
    
    @php
        $user = \App\Models\Tenant\TeamMember::where('id',$taskToUpdate->tech_id)->first();
    @endphp
        <div class="card">
            <div class="card-footer justify-content-between">
                <div class="row">
                    <div class="col text-right">
                        <a wire:click="cancel" class="btn btn-secondary mr-2">
                            {!! $cancelButton !!}
                        </a>
                        @if(Auth::user()->type_user == 0 || Auth::user()->id == $user->user_id)
                            <a wire:click="saveTask" class="btn btn-primary">
                                {{ $actionButton }}
                                <span class="btn-icon-right"><i class="las la-check mr-2"></i></span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    @push('custom-scripts')
    <script>
        var services = [];
        document.addEventListener('livewire:load', function () {
            restartObjects();
            jQuery('#selectedCustomer').select2();
        });

        window.addEventListener('swal',function(e){
            swal.fire({
                title: e.detail.title,
                html: e.detail.message,
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                type: "error",

            }).then((result) => {  
                if(result.value){

                    window.location.replace("http://"+window.location.hostname+"/services/create");

                    restartObjects();
                    if(e.detail.function == 'client')
                    {
                        location.reload();
                    }
                }                
            })
           
        });

        jQuery("body").on('click','#equipamentoServico', function(){
            //checkar se esta selecionado
            if(jQuery(this).is(":checked")){

                jQuery("#equipmentNav").css("display","block");

            }
            else {

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



                jQuery("#equipmentNav").css("display","none");

            }

        })

        function restartObjects()
        {

            if(jQuery("#equipamentoServico").is(":checked")){

            jQuery("#equipmentNav").css("display","block");
            }
            else {
            jQuery("#equipmentNav").css("display","none");
            }

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

                jQuery('#prioridadeColors').select2({
                templateResult: formatState,
                templateSelection: formatState
            });

            jQuery("#prioridadeColors").on("select2:select", function (e) {
                @this.set('selectPrioridade', jQuery('#prioridadeColors').find(':selected').val(), true)
            });


            jQuery('#selectedLocation').select2();
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
            jQuery('#scheduled_date').pickadate({
                monthsFull:["{!! __('January') !!}","{!! __('February') !!}","{!! __('March') !!}","{!! __('April') !!}","{!! __('May') !!}","{!! __('June') !!}","{!! __('July') !!}","{!! __('August') !!}","{!! __('September') !!}","{!! __('October') !!}","{!! __('November') !!}","{!! __('December') !!}"],
                weekdaysShort: ["{!! __('Sun') !!}","{!! __('Mon') !!}","{!! __('Tue') !!}","{!! __('Wed') !!}","{!! __('Thu') !!}","{!! __('Fri') !!}","{!! __('Sat') !!}"],
                today: "{!! __('today') !!}",
                clear: "{!! __('clear') !!}",
                close: "{!! __('close') !!}",
                onSet: function(thingSet) {
                    @this.set('scheduledDate', formatDate(thingSet.select), true);
                    jQuery('#scheduled_date').val(formatDate(thingSet.select));
               }
            });
            $('.preview_hour').clockpicker({
                donetext: '<i class="fa fa-check" aria-hidden="true"></i>',
            }).find('input').change(function () {
                @this.set('previewHour', this.value, true);
            });
            $('.scheduled_hour').clockpicker({
                donetext: '<i class="fa fa-check" aria-hidden="true"></i>',
            }).find('input').change(function () {
                @this.set('scheduledHour', this.value, true);
            });
        }

        window.addEventListener('loading', function(e) {
            @this.loading();
        })

        window.addEventListener('newService', function(e) {
            jQuery('.selectedService').each(function() {
                var selectId = '#' + jQuery(this).attr('id');
                jQuery(selectId).off('select2:select');
                jQuery(selectId).select2();
                jQuery(selectId).on('select2:select', function (e) {
                    @this.set('selectedServiceId.' + jQuery(selectId).attr('data-rel'), jQuery(selectId).find(':selected').val());
                });
            });
        });

        window.addEventListener('contentChanged', event => {
            restartObjects();
        });

        function formatDate(unixDate)
        {
            var date = new Date(unixDate);
            var year = date.getFullYear();
            var month = "0" + (date.getMonth()+1);
            var day = "0" + date.getDate();
            var formattedTime = year + '-' + month.substr(-2) + '-' + day.substr(-2);
            return formattedTime;
        }
    </script>
    @endpush
</div>
