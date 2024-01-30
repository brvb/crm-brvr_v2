<div>
    <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
        style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
        <div class="sk-three-bounce" style="background:none;">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <div class="card-body pt-0">
        <div id="accordion-one" class="accordion accordion-primary" wire:ignore>
            <div class="accordion__item">
                <div class="accordion__header rounded-lg collapsed" data-toggle="collapse" data-target="#default_collapseOne" aria-expanded="false">
                    <span class="accordion__header--text">{{__("Filters")}}</span>
                    <span class="accordion__header--indicator"></span>
                </div>
                <div id="default_collapseOne" class="accordion__body collapse" data-parent="#accordion-one">
                    <div class="accordion__body--text">
                        <div class="col-12" style="margin-bottom:25px;padding-left:0px;">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label>{{__("Select Technical")}}</label>
                                        <select name="selectTechnical" id="selectTechnical" class="form-control" wire:model="technical">
                                            <option value="0">{{__("All")}}</option>
                                            @foreach ($members as $member)
                                            <option value={{$member->id}}>{{$member->name}}</option> 
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label>{{__("Select Customer")}}</label>
                                        <select class="form-control" name="selectCustomer" id="selectCustomer" wire:model="client">
                                            <option value="0">{{__("All")}}</option>
                                                @foreach ($customers as $customer)
                                                    <option value={{$customer->id}}>{{$customer->short_name}}</option> 
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{__("Select Service")}}</label>
                
                                        <select class="form-control" name="workDescription" id="workDescription" wire:model="work">
                                            <option value="0">{{ __("All") }}</option>
                                            @foreach ($services as $service)
                                                <option value="{{$service->id}}">{{$service->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{__("Initial Date")}}</label>
                                        <div class="input-group" wire:ignore>
                                            <input id="dateBegin" class="form-control" type="text" wire:model="dateBegin" placeholder="{{ __("Date Begin") }}">
                                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar-o"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{__("Final Date")}}</label>
                                        <div class="input-group" wire:ignore>
                                            <input id="dateEnd" class="form-control picker__input" type="text" wire:model="dateEnd" placeholder="{{ __("Date End") }}">
                                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar-o"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" id="clearFilter" wire:click="clearFilter" class="btn-sm btn btn-primary">{{__("Clear Filter")}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>     

        <div class="row">
        <div class="table-responsive" style="position: relative;">
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
                <div class="col-12 text-right pr-0">
                    <a wire:click="exportExcel({{$analysisExcel}})" class="btn btn-primary"><i class="fa fa-file-text scale5 mr-3" aria-hidden='true'></i>{{ __('Export to Excel')}}</a>
                </div>
                <div id="dataTables_search_filter" class="dataTables_filter" style="display:none;">
                    <label>{{ __('Search') }}:
                        <input type="search" name="searchString" wire:model="searchString"></label>
                </div>
            </div>
            {{-- class="display dataTable no-footer" --}}
            <div class="table-responsive w-100">
                <table id="dataTables-data" class="table mb-0 table-striped">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkAll" required="">
                                    <label class="custom-control-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th>{{ __('Reference') }}</th>
                            <th>{{ __('State of Task') }}</th>
                            <th>{{ __('Technical') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Hour') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Service') }}</th>
                            <th>{{ __('Hours')}}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($analysis as $item)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheckBox{{ $item->id }}"
                                            required="">
                                        <label class="custom-control-label" for="customCheckBox{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $item->tasksReports->reference }}</td>
                                <td>
                                    @if($item->tasksReports->reportStatus == 0)
                                    {{__("Agendada")}}
                                    @elseif($item->tasksReports->reportStatus == 1)
                                    {{__("Em Curso")}}
                                    @else
                                        {{__("Finalizada")}}
                                    @endif
                                </td>
                                {{-- <td>{{ $item->tasksReports->tech->name }}</td> --}}
                                @php
                                    $user = \App\Models\User::where('id',$item->tech_id)->first();
                                @endphp
                                <td>{{ $user->name}}</td>
                                <td>{{ $item->date_begin }}</td>
                                <td>{{ $item->hour_begin }} / {{ $item->hour_end }}</td>
                                <td>{{ $item->tasksReports->taskCustomer->short_name }}</td>
                                <td>{{ $item->service->name }}</td>
                                <td>{{ global_hours_format($item->total_hours) }}</td>
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
                                            <a class="dropdown-item" href="{{ route('tenant.tasks-reports.edit', $item->tasksReports->id)}}">{{__('Visualize Report')}}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $analysis->links() }}
        </div>
      </div>
    </div>
</div>
@push('custom-scripts')
<script>

    window.addEventListener('livewire:load', event => {
       restartObjects();
    });


                
    window.addEventListener('contentChanged', event => {
        jQuery('.input-group #dateBegin').stop()
        restartObjects();
    });

    window.addEventListener('swalModalQuestion',function(e){
            if(e.detail.confirm) {
                swal.fire({
                    title: e.detail.title,
                    html: e.detail.message,
                    type: e.detail.status,
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: e.detail.confirmButtonText,
                    cancelButtonText: e.detail.cancellButtonText})
                .then((result) => {
                    if(result.value) {
                        Livewire.emit(e.detail.function);
                    }
                });
            } else {
                swal(e.detail.title, e.detail.message, e.detail.status);
            }
    });

    function restartObjects()
    {
        
        jQuery('.input-group #dateBegin').pickadate({
            monthsFull: ["{!!__('January') !!}", "{!!__('February') !!}","{!!__('March') !!}","{!!__('April') !!}","{!!__('May') !!}","{!!__('June') !!}","{!!__('July') !!}","{!!__('August') !!}","{!!__('September') !!}","{!!__('October') !!}","{!!__('November') !!}","{!!__('December') !!}"],
            weekdaysShort: ["{!!__('Sun') !!}","{!! __('Mon') !!}","{!! __('Tue') !!}", "{!! __('Wed') !!}","{!! __('Thu') !!}", "{!! __('Fri') !!}", "{!! __('Sat') !!}"],
            today: "{!! __('today') !!}",
            clear: "{!! __('clear') !!}",
            close: "{!! __('close') !!}",
            onSet: function(thingSet) {
                @this.set('dateBegin', formatDate(thingSet.select));
                //jQuery('#dateBegin').val(formatDate(thingSet.select));
                }
            });

        jQuery('.input-group #dateEnd').pickadate({
            monthsFull: ["{!! __('January') !!}","{!! __('February') !!}","{!! __('March') !!}","{!! __('April') !!}","{!! __('May') !!}","{!! __('June') !!}","{!! __('July') !!}","{!! __('August') !!}","{!! __('September') !!}","{!! __('October') !!}","{!! __('November') !!}","{!! __('December') !!}"],
            weekdaysShort: ["{!!__('Sun') !!}","{!!__('Mon') !!}","{!!__('Tue') !!}","{!!__('Wed') !!}","{!!__('Thu') !!}","{!!__('Fri') !!}","{!!__('Sat') !!}"],
            today: "{!! __('today') !!}",
            clear: "{!! __('clear') !!}",
            close: "{!! __('close') !!}",
            onSet: function(thingSet) {
                @this.set('dateEnd', formatDate(thingSet.select));
                //jQuery('#dateEnd').val(formatDate(thingSet.select));
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
</div>
@endpush
