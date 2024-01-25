<div class="container-fluid">
<div class="row">
    <div class="col-xl-12">
      <div class="row">
       
        @if(Auth::user()->type_user != 2)

        <div class="col-xl-12 mt-4">
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
                  @foreach ($arrayTimes as $name => $time)

                  {{-- {!! "https://".$_SERVER['SERVER_NAME']."/assets/resources/images/avatar/1.png" !!} --}}

                  <tr>
                   <td>
                      <div class="media align-items-center">
                        @if($time["photo"] != null)
                          <img class="img-fluid rounded mr-3 d-xl-inline-block" width="70" src="{!! global_tenancy_asset('/app/public/profile/'.$time["photo"].'') !!}" alt="IMG">
                        @else
                          <img class="img-fluid rounded mr-3 d-xl-inline-block" width="70" src="{!! "https://".$_SERVER['SERVER_NAME']."/assets/resources/images/avatar/1.png" !!}" alt="img">
                        @endif
                        <div class="media-body">
                          <h4 class="font-w600 mb-1 wspace-no">
                            <a href="javascript:void(0)" class="text-black">{{ $name }}</a>
                          </h4>
                        </div>
                      </div>
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
                      {{$time["date_begin"]}} <br> 
                      <i class="fa fa-clock-o" aria-hidden="true"></i>
                      {{$time["hour_begin"]}}
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

        @endif
      
    </div>
    </div>
  </div>
</div>

