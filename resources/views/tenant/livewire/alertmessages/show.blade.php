<div>
    <li class="nav-item dropdown notification_dropdown">
        <a class="nav-link  ai-icon" href="javascript:void(0)" role="button"
            data-toggle="dropdown">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12.8333 5.91732V3.49998C12.8333 2.85598 13.356 2.33331 14 2.33331C14.6428 2.33331 15.1667 2.85598 15.1667 3.49998V5.91732C16.9003 6.16698 18.5208 6.97198 19.7738 8.22498C21.3057 9.75681 22.1667 11.8346 22.1667 14V18.3913L23.1105 20.279C23.562 21.1831 23.5142 22.2565 22.9822 23.1163C22.4513 23.9761 21.5122 24.5 20.5018 24.5H15.1667C15.1667 25.144 14.6428 25.6666 14 25.6666C13.356 25.6666 12.8333 25.144 12.8333 24.5H7.49817C6.48667 24.5 5.54752 23.9761 5.01669 23.1163C4.48469 22.2565 4.43684 21.1831 4.88951 20.279L5.83333 18.3913V14C5.83333 11.8346 6.69319 9.75681 8.22502 8.22498C9.47919 6.97198 11.0985 6.16698 12.8333 5.91732ZM14 8.16664C12.4518 8.16664 10.969 8.78148 9.87469 9.87581C8.78035 10.969 8.16666 12.453 8.16666 14V18.6666C8.16666 18.8475 8.12351 19.026 8.04301 19.1881C8.04301 19.1881 7.52384 20.2265 6.9755 21.322C6.88567 21.5028 6.89501 21.7186 7.00117 21.8901C7.10734 22.0616 7.29517 22.1666 7.49817 22.1666H20.5018C20.7037 22.1666 20.8915 22.0616 20.9977 21.8901C21.1038 21.7186 21.1132 21.5028 21.0234 21.322C20.475 20.2265 19.9558 19.1881 19.9558 19.1881C19.8753 19.026 19.8333 18.8475 19.8333 18.6666V14C19.8333 12.453 19.2185 10.969 18.1242 9.87581C17.0298 8.78148 15.547 8.16664 14 8.16664Z"
                    fill="#326c91 " />
            </svg>
            <div id="alerticon" class="pulse-css" style="display:none;"></div>
        </a>
        <div class="dropdown-menu rounded dropdown-menu-right">
            <div id="DZ_W_Notification1" class="widget-media dz-scroll p-3 height380 ps ps--active-y">
                <ul class="timeline">
                    @foreach ($notifications as $not)        
                        <li>
                            <div class="timeline-panel">
                                <div class="media mr-2">
                                    @if(Auth::user()->type_user == 0)
                                        @if($not->senderUser->photo == null)
                                            <img alt="image" width="50" src="{!! "https://".$_SERVER['SERVER_NAME']."/assets/resources/images/avatar/1.png" !!}">
                                        @else
                                        {{$userCustomer = null}}
                                            @php
                                            if($not->group_chat != null && $not->group_chat != "")
                                            {
                                                $userCustomer = \App\Models\Tenant\Customers::where('id',$not->group_chat)->first();
                                                $userTable = \App\Models\User::where('id',$userCustomer->user_id)->first();
                                            }
                                            else 
                                            {

                                                $userTable = \App\Models\User::where('id',$not->sender_user_id)->first();
                                            }
                                            @endphp

                                            <img alt="image" width="50"
                                            src="{!! global_tenancy_asset('/app/public/profile/'.$userTable->photo.'') !!}">

                                        @endif
                                    @else
                                        @if($not->senderUser->photo == null)
                                            <img alt="image" width="50" src="{!! "https://".$_SERVER['SERVER_NAME']."/assets/resources/images/avatar/1.png" !!}">
                                        @else
                                            <img alt="image" width="50"
                                            src="{!! global_tenancy_asset('/app/public/profile/'.$not->senderUser->photo.'') !!}">
                                        @endif
                                    @endif
                                    
                                </div>
                                <div class="media-body">
                                    @if(Auth::user()->type_user == 0)
                                        @php
                                           if($not->group_chat != null)
                                            {
                                                $userCustomer = \App\Models\Tenant\Customers::where('id',$not->group_chat)->first();
                                                $userTable = \App\Models\User::where('id',$userCustomer->user_id)->first();
                                            }
                                            else 
                                            {
                                                $userTable = \App\Models\User::where('id',$not->sender_user_id)->first();
                                            }
                                        @endphp
                                         @if($not->group_chat != null)
                                          <h6 class="mb-1">No grupo do cliente {{$userTable->name}} houve atividade</h6>
                                        @else
                                          <h6 class="mb-1">{{$userTable->name}} mandou lhe mensagem</h6>
                                        @endif
                                    @else
                                        @if($not->type == "message")
                                            <h6 class="mb-1">{{$not->senderUser->name}} enviou lhe uma mensagem</h6>
                                        @else
                                            <h6 class="mb-1">{{$not->senderUser->name}} fez alteração nos ficheiros</h6>
                                        @endif
                                    @endif
                                    <small class="d-block">{{$not->created_at}}</small>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    @if ($servicesNotifications != null)
                        @foreach ($servicesNotifications as $notification)
                            @if ($notification["team_member"] == Auth::user()->name || Auth::user()->type_user == 0)
                                <div class="timeline-panel">
                                    <div class="media mr-2">
                                        <img alt="image" width="50"src="{!! global_tenancy_asset('/app/public/profile/'.$notification["userphoto"]) !!}">
                                    </div>
                                    <div class="media-body">
                                        
                                        <h6 class="mb-1">{{$notification["team_member"]}}</h6>
                                        <small class="d-block">{{$notification["customer"]}}</small>
                                        <small class="d-flex justify-content-between align-items-end">
                                            {{$notification["notification"]}}
                                            <div class="d-flex">
                                                <button href="javascript:void(0)" wire:click="treated({{$notification["customerServicesId"]}})" class="btn btn-primary btn-sm light px-4"><i class="fa fa-check"></i></button>
                                            </div>
                                        </small>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
            <a class="all-notification" wire:click="markRead" href="javascript:void(0)">Marcar tudo como lida</a>
        </div>
    </li>
    
@push('custom-scripts')
    <script>
        jQuery(document).ready(function(){

            Livewire.emit("AfterPageRefresh");

            window.addEventListener('checkRead', function(e) {
                if(e.detail.read == 0){
                    jQuery("#alerticon").css("display","none");
                    jQuery(".timeline").append("<div class='media-body'><h6 class='mb-1'>Não tem alertas de mensagens</h6></div>")
                }
                else {
                    jQuery("#alerticon").css("display","block");
                }
                // Livewire.emit("tableservice");
            })

            window.addEventListener('refreshafter', function(e) {
            
                Livewire.emit("tableservice");
            })


            window.addEventListener('refreshservice', function(e) {
                if(e.detail.read == 0){
                    jQuery("#alerticon").css("display","none");
                    jQuery(".timeline").append("<div class='media-body'><h6 class='mb-1'>Não tem alertas de mensagens</h6></div>")
                }
                else {
                    jQuery("#alerticon").css("display","block");
                }
                Livewire.emit("tableservice");
            });
        })       
     
    </script>
@endpush
   
</div>

