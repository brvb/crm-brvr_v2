
<div class="container-fluid">
<div class="row">
    <div class="col-xl-3">
      <div class="card">
        <div class="card-body">
          <h4 class="card-intro-title">{{__("Calendar")}}</h4>
          
          <div class="">
            <div>
              <p>{{ __("Team Members") }}</p>
              @foreach ( $teamMembers as $member )
                  <div class="col-xs-12 mb-2" style="padding-left:10px;">
                      <div class="row">
                      <div class="col-xs-3">
                          <span class="badge badge-primary rounded-circle" style="background:{{$member->color}}; padding:10px 10px!important;">
                              <label style="display:none;"></label>
                          </span>
                      </div>
                      <div class="col-xs-9">
                          <label>&nbsp;{{$member->name}}</label>
                      </div>
                  </div>
                  </div>
              @endforeach
            </div>
      
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-9">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">

              {{-- @php
                $teamMemberCheck = \App\Models\Tenant\TeamMember::where('user_id',Auth::user()->id)->first();
              @endphp

              @if($teamMemberCheck->id_hierarquia != "3")
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
                                      
                                      <div class="col-12">

      
                                          <div class="form-group">
                                            @foreach ($infoTeamMember as $dept => $info)
                                            @if($info != null)
                                              <div class="row" style="border:1px solid;justify-content:center;">
                                                
                                                <label>{{ $dept }}</label>
                                                                                
                                                <div class="col-12" style="padding-left:25px;border-top: 1px solid;">
                                                @foreach($info as $inf)
                                              
                                                 
                                                    <div class="input-group">
                                                     
                                                        <label>{{ $inf->name }}</label>
                                                        <input type="checkBox" id="check{{$inf->id}}" wire:model.defer="checkboxUser.{{$inf->id}}" class="form-check-input"  value="{{ $inf->id }}">
                                                     
                                                    </div>
                                                  
                                           
                                                 
                                          
                                                @endforeach
                                              </div>
                                              </div>
                                              @endif
                                            @endforeach
                                             
                                          </div>
                                      </div>
                                    
                                  </div> 
                      
                                  <div class="row">
                    
                                      <div class="col-md-12 text-right">
                                          <button type="button" id="search" wire:click="searchPeople" class="btn-sm btn btn-primary">Pesquise</button>
                    
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>   
              @endif --}}

              <div id="calendarr" class="app-fullcalendarr fc fc-unthemed fc-ltr" wire:ignore></div>

            </div>
          </div>
        </div>

        @if(Auth::user()->type_user != 2)

        <div class="col-xl-12" style="margin-top:20px;">
          
        @php
          $teamMemberCheck = \App\Models\Tenant\TeamMember::where('user_id',Auth::user()->id)->first();
        @endphp

            @if($teamMemberCheck->id_hierarquia != "3")
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
                                    
                                    <div class="col-12">


                                        <div class="form-group">
                                          @foreach ($infoTeamMember as $dept => $info)
                                          @if($info != null)
                                            <div class="row" style="border:1px solid;justify-content:center;">
                                              
                                              <label>{{ $dept }}</label>
                                                                              
                                              <div class="col-12" style="padding-left:25px;border-top: 1px solid;">
                                              @foreach($info as $inf)
                                            
                                              
                                                  <div class="input-group">
                                                  
                                                      <label>{{ $inf->name }}</label>
                                                      <input type="checkBox" id="check{{$inf->id}}" wire:model.defer="checkboxUser.{{$inf->id}}" class="form-check-input"  value="{{ $inf->id }}">
                                                  
                                                  </div>
                                                
                                        
                                              
                                        
                                              @endforeach
                                            </div>
                                            </div>
                                            @endif
                                          @endforeach
                                          
                                        </div>
                                    </div>
                                  
                                </div> 
                    
                                <div class="row">
                  
                                    <div class="col-md-12 text-right">
                                        <button type="button" id="search" wire:click="searchPeople" class="btn-sm btn btn-primary">Pesquise</button>
                  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>   
            @endif
        </div>

        <div class="col-xl-12" style="height:50%;">
          {{-- <div class="row"> --}}
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">{{ __("Tarefas abertas")}}</h4>
              </div>
              <div class="card-body" style="display:flex;overflow:auto;">

            <div class="table-responsive" style="position: relative;">
              {{-- class="display dataTable no-footer" --}}
              <table id="dataTables-data" class="table table-responsive-lg mb-0 table-striped">
                  <thead>
                      <tr>
                          <th>Nome Cliente</th>
                          <th>Serviço</th>
                          <th>Resumo</th>
                          <th>Técnico</th>
                          <th>Data</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($secondTable as $ct => $item)
                        @if(isset($item->taskReports))
                          @if($item->taskReports->reportStatus != 2)

                            <tr style="background:{{$item->prioridades->cor}};">
                              <td>{{ $item->taskCustomer->name }}</td>
                              <td>
                                  @foreach($item->servicesToDo as $ser)
                                      {{$ser->service->name}}<br>
                                  @endforeach
                              </td>
                              @if($item->resume == null || !isset($item->resume) || $item->resume == "")
                                <td>Sem resumo</td>
                              @else
                                <td>{{ $item->resume }}</td>
                              @endif
                              <td>{{ $item->tech->name }}</td>                                
                              <td>{{ $item->preview_date}}</td>
                          </tr>
                          
                          @endif
                        @else
                          
                            <tr style="background:{{$item->prioridades->cor}};">
                                <td>{{ $item->taskCustomer->name }}</td>
                                <td>
                                    @foreach($item->servicesToDo as $ser)
                                        {{$ser->service->name}}<br>
                                    @endforeach
                                </td>
                                @if($item->resume == null || !isset($item->resume) || $item->resume == "")
                                  <td>Sem resumo</td>
                                @else
                                  <td>{{ $item->resume }}</td>
                                @endif
                                <td>{{ $item->tech->name }}</td>                                
                                <td>{{ $item->preview_date}}</td>
                            </tr>
                        
                        @endif
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
                  <h4 class="card-title">{{ __("Open Times")}}</h4>
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
                          <tr>
                            <td>
                            <h4>
                                <a href="javascript:void(0)" class="text-black">{{ $name }}</a>
                            </h4>
                            </td>
                            <td>{{$time["customer"]}}</td>
                            <td>
                              <i class="fa fa-tasks" aria-hidden="true"></i>
                              {{$time["reference"]}} <br>
                              <i class="fa fa-wrench" aria-hidden="true"></i>
                              {{$time["service"]}}
                            </td>
                            <td>
                              <i class="fa fa-calendar" aria-hidden="true"></i>
                              {{ $time["date_begin"] }} <br>
                              <i class="fa fa-clock-o" aria-hidden="true"></i>
                              {{ $time["hour_begin"] }}
                            </td>
                            <td>
                              <div class="d-flex">
                               @if (Auth::user()->type_user == 0 || (Auth::user()->id == $time["tech"]))
                                 <a href="{{ route('tenant.tasks-reports.edit', $time["task_id"])}}" class="btn btn-primary shadow  sharp mr-1">
                                   <i class="fa fa-pencil"></i>
                                 </a>
                               @endif
                              </div>
                           </td>
                          </tr>
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
                    {{-- <th>{{ __('Date Final of service') }}</th> --}}
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
                        {{-- <td>{{$notification["date_final_service"]}}</td> --}}
                        <td>{{$notification["team_member"]}}</td>
                        <td>{{$notification["customer"]}}</td>
                        <td>{{$notification["customer_county"]}}</td>
                        <td>{{$notification["notification"]}}</td>
                        <td>
                          <div class="d-flex">
                            <a href="javascript:void(0)" wire:click="treated({{$notification["customerServicesId"]}})" class="btn btn-primary btn-sm light px-4">{{__("Treated")}}</a>
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

        @endif
      
    </div>
    </div>
  </div>
</div>

