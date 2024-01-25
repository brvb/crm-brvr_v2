<style>
	/* width */
	div *::-webkit-scrollbar {
	  width: 5px;
	}
	
	/* Track */
	div *::-webkit-scrollbar-track {
	  background: #f1f1f1; 
	}
	 
	/* Handle */
	div *::-webkit-scrollbar-thumb {
	  background: #888; 
	}
	
	/* Handle on hover */
	div *::-webkit-scrollbar-thumb:hover {
	  background: #555; 
	}
</style>

<div>
    <li class="nav-item dropdown notification_dropdown pl-2">
        <a class="nav-link bell bell-link" href="javascript:void(0)" role="button"
            data-toggle="dropdown">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
               d="M19.75,15.67a6,6,0,1,0-7.51,0A11,11,0,0,0,5,26v1H27V26A11,11,0,0,0,19.75,15.67ZM12,11a4,4,0,1,1,4,4A4,4,0,0,1,12,11ZM7.06,25a9,9,0,0,1,17.89,0Z"
                    fill="#326c91 " />
            </svg>
                {{-- <div id="alerticon" class="pulse-css" style="display:none;"></div> --}}
        </a>
        
        <!-- começa aqui -->
        <div class="chatbox" wire:ignore.self>
            <div class="chatbox-close"></div>
            <div class="custom-tab-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toogle="tab" href="#chat">Utilizadores</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="chat" role="tabpanel">
                        <div class="card mb-sm-3 mb-md-0 contacts_card dz-chat-user-box">
                            <div class="card-body contacts_body p-0 dz-scroll ps ps--active-y" id="DZ_W_Contacts_Body">
                                <ul class="contacts">
                                    
                                    @foreach ($users as $key => $user )
    
                                        <li class="name-first-letter">{{$key}}</li>

                                        @foreach ($user as $us)
                                            @if($us["type_user"] == 2)
                                                <li class="active" data-id="{{$us["id"]}}">
                                            @else
                                                <li class="active dz-chat-user" data-id="{{$us["id"]}}">
                                            @endif
                                                <div class="d-flex bd-highlight">
                                                    <div class="img_cont">
                                                        @if($us["photo"] == null)
                                                            <img src="{!! "https://".$_SERVER['SERVER_NAME']."/assets/resources/images/avatar/1.png" !!}" class="rounded-circle user_img" alt>
                                                        @else
                                                            <img src="{!! global_tenancy_asset('/app/public/profile/'.$us["photo"].'') !!}" class="rounded-circle user_img" alt>
                                                        @endif
                                                        @if($us["status"] == "online")
                                                            <span class="online_icon"></span>
                                                        @else
                                                            <span class="online_icon offline"></span>
                                                        @endif
                                                    </div>
                                                    <div class="user_info">
                                                        <span id="nameUser">{{$us["name"]}}</span>
                                                        @if($us["status"] == "online")
                                                            <p>online</p>
                                                        @else
                                                            @if($us["last_seen"] != "")
                                                                @php
                                                                    $difference = \Carbon\Carbon::parse($us["last_seen"])->addMinute(30)->diffForHumans();
                                                                @endphp
                                                                <p>offline {{$difference}}</p>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endforeach

                                </ul>
                            </div>
                        </div>

                        <!--  Poderei fazer aqui a situação do chat  -->
                        <!-- Mas terei de fazer este codigo abaixo em livewire -->
                        @livewire('tenant.chat-users.chat-users')


                    </div>
                </div>
            </div>
        </div>


    </li>
    
@push('custom-scripts')
    <script>
         jQuery(document).ready(function(){
            

            //jQuery(".navbar-nav").on("click", ".bell-link", function(){
            jQuery(".bell-link").on('click',function() {
                Livewire.emit("RefreshUserLog");
            })

            jQuery("body").on('click', ".dz-chat-user", function(){ 
                Livewire.emit("messagesRightSide",jQuery(this).attr("data-id"));
            })

                  


          
         })  
         
           
     
    </script>
@endpush
   
</div>

