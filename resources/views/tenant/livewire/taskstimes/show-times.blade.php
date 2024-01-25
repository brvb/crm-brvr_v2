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
            <h4 class="card-title">{{ __('Tasks Times') }}</h4>
           
            <div class="col-xl-3 col-xs-3 text-right pr-0">
                <div class="row">
                @if(Auth::user()->type_user == 2)
                    <div class="col-xl-12 col-xs-12">
                        <label class="font-weight-bold">{{ __('Hours in Task')}}:</label>&nbsp;{{ global_hours_sum($totalHours) }}
                    </div>
                @else
                    <div class="col-xl-4 col-xs-4">
                        <label class="font-weight-bold">{{ __('Hours in Task')}}:</label><br>{{ global_hours_sum($totalHours) }}
                    </div>
                    @php
                        $user = \App\Models\Tenant\TeamMember::where('id',$taskInfo->tech_id)->first();
                    @endphp

                    {{-- @if(Auth::user()->type_user == 0 || Auth::user()->id == $user->user_id) --}}
                        <div class="col-xl-8 col-xs-8">
                            <a href="javascript:void(0)" id="taskAddTime" class="btn btn-primary" wire:click="addTime">{{ __('Add Time') }}</a>
                        </div>
                    {{-- @endif --}}
                @endif
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
                    <div id="dataTables_search_filter" class="dataTables_filter">
                        <label>{{ __('Search') }}:
                            <input type="search" name="searchString" wire:model="searchString"></label>
                    </div>
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
                            <th>{{ __('Service') }}</th>
                            <th>{{ __('Date Begin') }}</th>
                            <th>{{ __('Date End') }}</th>
                            <th>{{ __('Total Hours')}}</th>
                            @if(Auth::user()->type_user != 2)
                                <th>{{ __('Action') }}</th>
                            @endif
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
                                   {{$item->service->name}}
                                </td>
                                <td>
                                    <i class="fa fa-calendar" aria-hidden="true"></i> {{ $item->date_begin }}<br>
                                    <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $item->hour_begin }}<br>
                                </td>
                                <td>
                                    <i class="fa fa-calendar" aria-hidden="true"></i> {{ $item->date_end }}<br>
                                    <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $item->hour_end }}<br>
                                </td>
                                <td>
                                   
                                    @if($item->total_hours != null)
                                    
                                        {{ global_hours_format($item->total_hours) }}
                                    @endif
                                  
    
                                </td>

                                @if(Auth::user()->type_user != 2)
                                    <td>
                                        <div class="dropdown ml-auto text-right">
                                            <div class="btn-link" data-toggle="dropdown">
                                                <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                        <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                        <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a wire:click="editTimeTask({{$item->id}})" class="dropdown-item">
                                                    {{__("Edit Time")}}
                                                </a>

                                                <button class="dropdown-item btn-sweet-alert" data-type="form"
                                                data-route="{{ route('tenant.tasks-reports.destroytimetask', $item->id) }}"
                                                data-style="warning" data-csrf="csrf"
                                                data-text="{{ __('Do you want to delete this time?') }}"
                                                data-title="{{ __('Are you sure?') }}"
                                                data-btn-cancel="{{ __('No, do not delete!!') }}"
                                                data-btn-ok="{{ __('Yes, delete time!!') }}" data-method="DELETE">
                                                {{ __('Delete task Time') }}
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $tasksTimes->links() }}
            </div>
        </div>
    </div>
    @push('custom-scripts')
    <script>
        var countAdd = 0;  
        var countEdit = 0;  
        window.addEventListener('contentChanged', event => {
            restartObjects();
        });
                  

        window.addEventListener('swal',function(e){
            
             Swal.fire({
                title: e.detail.title,
                text: e.detail.message,
                type: e.detail.status,
                showCancelButton: e.detail.showCancelButton,
                showConfirmButton: false,
                confirmButtonColor: e.detail.confirmButtonColor,
                confirmButtonText: e.detail.confirmButtonText,
                cancelButtonText: e.detail.cancelButtonText,
                
                onOpen: function() {
                    countAdd = 0;
                    countEdit = 0;
                    if(e.detail.function == "timesInsert")
                    {
                        jQuery(".swal2-confirm").css("display","none");
                    }
                    else if(e.detail.function == "EditTimes")
                    {
                        jQuery(".swal2-confirm").css("display","none");
                    }
                    jQuery("body").on('change', '#swal2-content #selectedService', function() {
                        @this.set('serviceSelected', jQuery(this).val(), true);
                    });

                   

                                       
                    jQuery('#swal2-content .input-group #date_inicial').pickadate({
                        monthsFull:["{!! __('January') !!}","{!! __('February') !!}","{!! __('March') !!}","{!! __('April') !!}","{!! __('May') !!}","{!! __('June') !!}","{!! __('July') !!}","{!! __('August') !!}","{!! __('September') !!}","{!! __('October') !!}","{!! __('November') !!}","{!! __('December') !!}"],
                        weekdaysShort: ["{!! __('Sun') !!}","{!! __('Mon') !!}","{!! __('Tue') !!}","{!! __('Wed') !!}","{!! __('Thu') !!}","{!! __('Fri') !!}","{!! __('Sat') !!}"],
                        today: "{!! __('today') !!}",
                        clear: "{!! __('clear') !!}",
                        close: "{!! __('close') !!}",
                            onSet: function(thingSet) {
                            @this.set('date_inicial', formatDate(thingSet.select), true);
                            jQuery('#date_inicial').val(formatDate(thingSet.select));
                        },
                        onOpen: function() {
                            //jQuery('.swal2-actions .swal2-cancel').attr('style', 'display:none;');
                            //jQuery('.swal2-actions .swal2-confirm').attr('style', 'display:none;');
                        },
                        onClose: function() {
                            //jQuery('.swal2-actions .swal2-cancel').attr('style', 'display:block;');
                            //jQuery('.swal2-actions .swal2-confirm').attr('style', 'display:block;');
                        }
                    });

                    jQuery('#swal2-content .input-group #hora_inicial').clockpicker({
                        donetext: '<i class="fa fa-check" aria-hidden="true"></i>',
                        placement: 'top',
                        afterDone: function() {
                            @this.set('hora_inicial', jQuery("#swal2-content .input-group #hora_inicial").val(), true);
                        },
                     });

                    jQuery('#swal2-content .input-group #desconto_hora').clockpicker({
                        donetext: '<i class="fa fa-check" aria-hidden="true"></i>',
                        placement: 'top',
                        afterDone: function() {
                            @this.set('desconto_hora', jQuery("#swal2-content .input-group #desconto_hora").val(), true);
                        },
                     });

                    jQuery('#swal2-content .input-group #hora_final').clockpicker({
                        donetext: '<i class="fa fa-check" aria-hidden="true"></i>',
                        placement: 'top',
                        afterDone: function() {
                            @this.set('hora_final', jQuery("#swal2-content .input-group #hora_final").val(), true);
                        },
                     });


                     jQuery("body").on('click', '#actionsDiv #btnAddTime', function() {

                        @this.set('descricao', jQuery("#descricao").val(), true);
    
                        if(jQuery("#swal2-content #numServices").val() == "1")
                        {
                            @this.set('serviceSelected', jQuery("#selectedService").val(), true);
                        }

                        var finalHour = new Date("November 13, 2013 " + jQuery('#swal2-content .input-group #hora_final').val());
                        finalHour = finalHour.getTime();
                        var desconto_hora = new Date("November 13, 2013 " + jQuery('#swal2-content .input-group #desconto_hora').val());
                        desconto_hora = desconto_hora.getTime();
                        var startHour = new Date("November 13, 2013 " + jQuery('#swal2-content .input-group #hora_inicial').val());
                        startHour = startHour.getTime();
                        if(jQuery('#swal2-content #selectedService').val() != ""  && jQuery('#swal2-content .input-group #hora_inicial').val() != "" && jQuery('#swal2-content .input-group #date_inicial').val() != "")
                        {
                            countAdd++;
                            if(countAdd == 1)
                            {
                                Livewire.emit(e.detail.function);
                                Swal.close();
                            }                            
                        }
                        else {
                            return false;
                        }
                     });

                     jQuery("body").on('click', '#actionsDiv #btnEditTime', function() {
                        console.log("teste");
                        
                        @this.set('descricao', jQuery("#descricao").val(), true);

                        @this.set('desconto_hora', jQuery("#desconto_hora").val(), true);

                        var service = jQuery('#swal2-content #selectedService').val();

                        var initialDate = jQuery('#swal2-content .input-group #date_inicial').val();

                        var finalHour = jQuery('#swal2-content .input-group #hora_final').val();

                        var desconto_hora = jQuery('#swal2-content .input-group #desconto_hora').val();
                        
                        var startHour = jQuery('#swal2-content .input-group #hora_inicial').val();
    
                        var description = jQuery("#descricao").val();

                        var values = [];

                        values.push(service,initialDate,startHour,finalHour,description);

                        if(e.detail.function != "timesInsert" && jQuery('#swal2-content #selectedService').val() != "" && jQuery('#swal2-content .input-group #hora_inicial').val() != "" && jQuery('#swal2-content .input-group #date_inicial').val() != "")
                        {
                            console.log("teste");
                            Livewire.emit(e.detail.function,e.detail.parameter,values);
                            Swal.close();
                                                       
                        }
                        else {
                            return false;
                        }
                     });

                     jQuery("body").on('click', '#actionsDiv #btnremoveTime', function() {
                        Swal.close();
                        countAdd = 0;
                     });

                    }
                }).then((result) => { 
                    console.log("gfd");
                    if(result.value) {
                        var finalHour = new Date("November 13, 2013 " + jQuery('#swal2-content .input-group #hora_final').val());
                        finalHour = finalHour.getTime();
                        var desconto_hora = new Date("November 13, 2013 " + jQuery('#swal2-content .input-group #desconto_hora').val());
                        desconto_hora = desconto_hora.getTime();
                        var startHour = new Date("November 13, 2013 " + jQuery('#swal2-content .input-group #hora_inicial').val());
                        startHour = startHour.getTime();
                        if(jQuery('#swal2-content .input-group #hora_final').val() != "" && jQuery('#swal2-content .input-group #hora_inicial').val() != "" && jQuery('#swal2-content .input-group #date_inicial').val() != "" && finalHour > startHour)
                        {
                            
                        }
                        else {
                            return false;
                        }
                    }
                });
        });
     
    
        function restartObjects()
        {
            jQuery('#selectedService').select2();
            jQuery('#selectedLocation').select2();
            jQuery("#selectedLocation").on("select2:select", function (e) {
                @this.selectedLocation = jQuery('#selectedLocation').find(':selected').val();
            });
            jQuery('#selectedTechnician').select2();
            jQuery("#selectedTechnician").on("select2:select", function (e) {
                @this.set('selectedTechnician', jQuery('#selectedTechnician').find(':selected').val(), true)
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
    </div>
    @endpush
    
</div>
