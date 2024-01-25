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
                                        <div class="form-group row">
                                            <section class="col" wire:ignore>
                                                <label>{{ __('Customer Name') }}</label>
                                                <select name="selectedCustomer" id="selectedCustomer" @isset($taskToUpdate->id) disabled @endisset>
                                                    <option value="">{{ __('Select Customer') }}</option>
                                                    @isset($taskToUpdate->id)
                                                        <option value="{{ $taskToUpdate->customer->id }}" selected>{{ $taskToUpdate->customer->name }}</option>
                                                    @else
                                                        @forelse ($customerList as $item)
                                                        <option value="{{ $item->id }}" @if($item->id == $selectedCustomer)
                                                            selected @endif>{{ $item->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    @endisset
                                                </select>
                                            </section>
                                        </div>
                                        @if($taskToUpdate->id)
                                        <div class="form-group row">
                                            <section class="col-xl-3 col-xs-12">
                                                <label>{{ __('VAT') }}</label>
                                                <input type="text" name="vat" id="vat" class="form-control"
                                                    value="{{ $taskToUpdate->customer->vat }}" readonly>
                                            </section>
                                            <section class="col-xl-3 col-xs-12">
                                                <label>{{ __('Phone number') }}</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    value="{{ $taskToUpdate->customer->contact }}" readonly>
                                            </section>
                                            <section class="col-xl-6 col-xs-12">
                                                <label>{{ __('Primary e-mail address') }}</label>
                                                <input type="text" name="email" id="email" class="form-control"
                                                    value="{{ $taskToUpdate->customer->email }}" readonly>
                                            </section>
                                        </div>
                                        <div class="form-group row">
                                            <section class="col-12">
                                                <label>{{ __('Customer Address') }}</label>
                                                <input type="text" name="address" id="address" class="form-control"
                                                    value="{{ $taskToUpdate->customer->address }}" readonly>
                                            </section>
                                        </div>
                                        <div class="form-group row">
                                            <section class="col-xl-2 col-xs-12">
                                                <label>{{ __('Zip Code') }}</label>
                                                <input type="text" name="zipcode" id="zipcode" class="form-control"
                                                    value="{{ $taskToUpdate->customer->zipcode }}" readonly>
                                            </section>
                                        </div>
                                        <div class="form-group row">
                                            <section class="col" wire:ignore>
                                                <label>{{ __('Location') }}</label>
                                                <select name="selectedLocation" id="selectedLocation">
                                                    <option value="">{{ __('Select Location') }}</option>
                                                    @isset($customerLocations)
                                                        @forelse ($customerLocations as $item)
                                                        <option value="{{ $item->id }}" @if($item->id == $selectedLocation)
                                                            selected @endif>{{ $item->description }} | {{ $item->zip_code }}
                                                        </option>
                                                        @empty
                                                        @endforelse
                                                    @else
                                                        @isset($taskToUpdate->location)
                                                            @forelse ($taskToUpdate->location as $item)
                                                            <option value="{{ $item->id }}" @if($item->id == $taskToUpdate->location_id->id)
                                                                selected @endif>{{ $item->description }} | {{ $item->zip_code }}
                                                            </option>
                                                            @empty
                                                            @endforelse
                                                        @else

                                                        @endisset
                                                    @endisset
                                                </select>
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
        <div class="tab-pane fade {{ $servicesPanel }}" id="servicesPanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            <div class="basic-form">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <div class="col">
                                                <button class="btn btn-secondary" wire:click="addServiceForm">{{ __('Add Service') }}</button>
                                            </div>
                                        </div>
                                            {{-- <livewire:user-profile :user="$user" :wire:key="$user->id"> --}}
                                        {{-- @livewire('tenant.tasks.service-list', ['task' => $task, 'customerList' => $customerList, 'serviceList' => $serviceList, 'teamMembers' => $teamMembers], $numberOfForms) --}}

                                        {{-- @for($i=0; $i < $numberOfForms; $i++)
                                        <div class="form-group row" wire:click>
                                            <section class="col">
                                                <label>{{ __('Service Name') }}</label>
                                                <select name="selectedService" id="selectedService" class="selectedService" data-rel="{{ $i }}">
                                                    <option value="">{{ __('Select Service') }}</option>
                                                    @if(isset($serviceList))
                                                    @forelse ($serviceList as $item)
                                                    <option value="{{ $item->service->id }}" @if($item->service->id ==
                                                        $selectedService)
                                                        selected @endif>{{ $item->service->name }}</option>
                                                    @empty
                                                    @endforelse
                                                    @endif
                                                </select>
                                            </section>
                                        </div>
                                        @if($selectedService)
                                            <div class="form-group row">
                                                <section class="col">
                                                    <label>{{ __('Description') }}</label>
                                                    <textarea name="description[]" id="description" class="form-control"
                                                        rows="4" wire:key="{{ $i }}" wire:model="description">
    @if($task->additional_description){{ $task->additional_description }}@endif
                                                    </textarea>
                                                </section>
                                            </div>
                                            @endisset
                                        @endfor --}}
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
                                        @isset($taskToUpdate->ide)
                                        <div class="form-group row">
                                            <section class="col">
                                                <label>{{ __('Service Technician') }}</label>
                                                <select name="selectedTechnician" id="selectedTechnician">
                                                    <option value="">{{ __('Select Technician') }}</option>
                                                    @if(isset($teamMembers))
                                                    @forelse ($teamMembers as $item)
                                                    <option value="{{ $item->id }}" @if($item->id ==
                                                        $selectedTeamMember)
                                                        selected @endif>{{ $item->name }}</option>
                                                    @empty
                                                    @endforelse
                                                    @endif
                                                </select>
                                            </section>
                                        </div>
                                        <div class="form-group row" wire:click>
                                            <section class="col-6">
                                                <label>{{ __('Preview date') }}</label>
                                                <input type="text" name="preview_date" id="preview_date"
                                                    class="form-control" class="datepicker-default"
                                                    @if($task->appointment_date) value="{{ $task->appointment_date
                                                }}"@endif>
                                            </section>
                                            <section class="col-6">
                                                <label>{{ __('Scheduled date') }}</label>
                                                <input type="text" name="scheduled_date" id="scheduled_date"
                                                    class="form-control" @if($task->appointment_date) value="{{
                                                $task->appointment_date }}"@endif readonly>
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
    </form>
    <div class="card">
        <div class="card-footer justify-content-between">
            <div class="row">
                <div class="col-6">
                    <a href="{{ route('tenant.tasks.index') }}" class="btn btn-secondary">
                        <i class="las la-angle-double-left mr-2"></i>{{ __('Back') }}
                    </a>
                </div>
                <div class="col-6 text-right">
                    <a wire:click="cancel" class="btn btn-secondary mr-2">
                        <i class="las la-times mr-2"></i>{{ $cancelButton }}
                    </a>
                    <a wire:click="save" class="btn btn-primary">
                        <i class="las la-check mr-2"></i>{{ $actionButton }}
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
            jQuery('#selectedCustomer').select2();
            jQuery("#selectedCustomer").on("select2:select", function (e) { @this.selectedCustomer = jQuery('#selectedCustomer').find(':selected').val(); });

            jQuery('.selectedService').select2();
            jQuery(".selectedService").on("select2:select", function (e) {
                if(jQuery('.selectedService').attr('data-rel') == 0) {
                    @this.selectedServiceId[0] = jQuery('.selectedService').find(':selected').val();
                } else if(jQuery('.selectedService').attr('data-rel') == 1) {
                    @this.selectedServiceId[1] = jQuery('.selectedService').find(':selected').val();
                }

            });
        });

        window.addEventListener('contentChanged', event => {
            restartObjects();
        });

        window.addEventListener('swal',function(e){
            swal(e.detail.title, e.detail.message, e.detail.status);
            restartObjects();
        });

        function restartObjects()
        {
            jQuery('.selectedService').select2();
            jQuery('#selectedLocation').select2();
            jQuery("#selectedLocation").on("select2:select", function (e) {
                @this.selectedLocation = jQuery('#selectedLocation').find(':selected').val();
            });
            jQuery('#selectedTechnician').select2();
            jQuery("#selectedTechnician").on("select2:select", function (e) {
                @this.set('selectedTechnician', jQuery('#selectedTechnician').find(':selected').val(), true)
            });

            jQuery('#start_date').pickadate({
                monthsFull:["{!! __('January') !!}","{!! __('February') !!}","{!! __('March') !!}","{!! __('April') !!}","{!! __('May') !!}","{!! __('June') !!}","{!! __('July') !!}","{!! __('August') !!}","{!! __('September') !!}","{!! __('October') !!}","{!! __('November') !!}","{!! __('December') !!}"],
                weekdaysShort: ["{!!__('Sun') !!}","{!!__('Mon') !!}","{!!__('Tue') !!}","{!!__('Wed') !!}","{!!__('Thu') !!}","{!!__('Fri') !!}","{!!__('Sat') !!}"],
                today: "{!! __('today') !!}",
                clear: "{!! __('clear') !!}",
                close: "{!! __('close') !!}",
                onSet: function(thingSet) {
                    @this.set('start_date', formatDate(thingSet.select), true)
                }
            });
        }

        function formatDate(unixDate)
        {
            var date = new Date(unixDate);
            var year = date.getFullYear();
            var month = "0" + (date.getMonth()+1);
            var day = "0" + date.getDate();
            var formattedTime = year + '/' + month.substr(-2) + '/' + day.substr(-2);
            return formattedTime;
        }

    </script>
	@endpush
</div>

