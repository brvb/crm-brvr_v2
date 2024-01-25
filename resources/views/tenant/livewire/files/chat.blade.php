<div>
<style>
    
	.chat-online {
		color: #34ce57
	}

	.chat-offline {
		color: #e4606d
	}

	.chat-messages {
		display: flex;
		flex-direction: column;
		max-height: 800px;
		overflow-y: scroll
	}

	.chat-message-left,
	.chat-message-right {
		display: flex;
		flex-shrink: 0
	}

	.chat-message-left {
		margin-right: auto
	}

	.chat-message-right {
		flex-direction: row-reverse;
		margin-left: auto
	}
	.py-3 {
		padding-top: 1rem!important;
		padding-bottom: 1rem!important;
	}
	.px-4 {
		padding-right: 1.5rem!important;
		padding-left: 1.5rem!important;
	}
	.flex-grow-0 {
		flex-grow: 0!important;
	}
	.border-top {
		border-top: 1px solid #dee2e6!important;
	}

	.attach_btn{
		border-radius: 15px 0 0 15px !important;
		background-color: rgba(0,0,0,0.3) !important;
				border:0 !important;
				color: white !important;
				cursor: pointer;
	}
	.send_btn{
		border-radius: 0 15px 15px 0 !important;
		background-color: rgba(0,0,0,0.3) !important;
				border:0 !important;
				color: white !important;
				cursor: pointer;
	}

	.img_conta{
			position: relative;
			height: 70px;
			width: 70px;
	}
	.users_img{
			height: 70px;
			width: 70px;
			border:1.5px solid #f5f6fa;
		
	}
	.users_info{
		margin-top: auto;
		margin-bottom: auto;
		margin-left: 15px;
	}

	.users_info span{
		font-size: 20px;
		color: white;
	}
	.users_info p{
	font-size: 10px;
	color: rgba(255,255,255,0.6);
	}

	.msg_head{
		position: relative;
	}

	.online_icon{
		position: absolute;
		height: 15px;
		width:15px;
		background-color: #4cd137;
		border-radius: 50%;
		bottom: 0.2em;
		right: 0.4em;
		border:1.5px solid white;
	}
	.offline{
		background-color: #c23616 !important;
	}

</style>

    <div id="wrapper">
		 <div class="card">
			<div class="card-header msg_head" style="background-color:#326c91;">
				<div class="d-flex bd-highlight">
					<div class="img_conta">
						@if(Auth::user()->type_user != 2)
							@php
								$customerr = \App\Models\Tenant\Customers::where('id',$customer)->first();
								$user = \App\Models\User::where('id',$customerr->user_id)->first();
							@endphp
							@if(isset($user->photo))
								<img src="{!! global_tenancy_asset('/app/public/profile/'.$user->photo.'') !!}" class="rounded-circle users_img">
							@else
							{{-- https://crm.dajasdourovalley.com/assets/resources/images/avatar/1.png --}}
								<img src="{!! "https://".$_SERVER['SERVER_NAME']."/assets/resources/images/avatar/1.png" !!}" class="rounded-circle users_img">
							@endif
						@else
							@php
								$customerr = \App\Models\Tenant\Customers::where('id',$customer)->first();
								$user_id = \App\Models\Tenant\TeamMember::where('id',$customerr->account_manager)->first();
								$user = \App\Models\User::where('id',$user_id->user_id)->first();
							@endphp
							@if(isset($user->photo))
								<img src="{!! global_tenancy_asset('/app/public/profile/'.$user->photo.'') !!}" class="rounded-circle users_img">
							@else
								<img src="{!! "https://".$_SERVER['SERVER_NAME']."/assets/resources/images/avatar/1.png" !!}" class="rounded-circle users_img">
							@endif
						@endif
						
					</div>
					<div class="users_info">
						@if(Auth::user()->type_user != 2)
							@php
								$customerr = \App\Models\Tenant\Customers::where('id',$customer)->first();
								$user_id = \App\Models\User::where('id',$customerr->user_id)->first();
							@endphp
							<span>{{$user_id->name}}</span>
						@else
							@php
								$customerr = \App\Models\Tenant\Customers::where('id',$customer)->first();
								$user_id = \App\Models\Tenant\TeamMember::where('id',$customerr->account_manager)->first();
								$user = \App\Models\User::where('id',$user_id->user_id)->first();
							@endphp

							@if($user != null)
								<span>{{$user->name}}</span>
							@else
								<span>{{$user_id->name}}</span>
							@endif
						@endif
						
					</div>
				</div>
			</div>
			<div class="chat-messages p-4" id="chatbox" style=" overflow:scroll;max-height:400px;overflow-x:auto;">

				@if ($chat->isEmpty())
					<div class="container">
						<h4 class="text-center">Escreva uma mensagem para começar conversação</h4>
					</div>
				@endif

				@foreach ($chat as $ch)
				@php
					$name = \App\Models\User::where('id',$ch->user_id)->first();
				@endphp

					@if($ch->user_id == Auth::user()->id)
						<div class="chat-message-right mb-4">
							<div>
								@if(Auth::user()->photo != null)
									<img src="{!! global_tenancy_asset('/app/public/profile/'.Auth::user()->photo.'') !!}" class="rounded-circle mr-1" width="40" height="40">
								@else
									<img src="{!! global_asset('assets/resources/images/avatar/1.png') !!}" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
								@endif
								
							</div>
							<div class="flex-shrink-1 rounded py-2 px-3 mr-3" style="background-color:#326c91;color:white;">
								<div class="font-weight-bold mb-1">Você</div>
								{{$ch->message}}
								<div class="text-muted small text-nowrap mt-2" style="color:white!important;">{{get_day_name($ch->created_at->toDateTimeString())}}</div>
							</div>
						</div>
					@else
						<div class="chat-message-left pb-4">
							<div>
								@php
                        			$photo = \App\Models\User::where('id',$ch->user_id)->first();
                    			@endphp
								@if($photo->photo != null)
									<div style="position:relative;">
										<img src="{!! global_tenancy_asset('/app/public/profile/'.$photo->photo.'') !!}" style="position:relative;" class="rounded-circle mr-1" width="40" height="40">
									</div>
								@else
									<img src="{!! global_asset('assets/resources/images/avatar/1.png') !!}" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
								@endif
								
							</div>
							<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
								<div class="font-weight-bold mb-1">{{$name->name}}</div>
								{{$ch->message}}
								<div class="text-muted small text-nowrap mt-2">{{get_day_name($ch->created_at->toDateTimeString())}}</div>
							</div>
						</div>
					@endif
				@endforeach
			</div>
		 </div>

		<div class="flex-grow-0 py-3 border-top">
			<div class="input-group">
				<div class="input-group-append d-none">
					<span class="input-group-text attach_btn" style="background-color:#326c91!important;" id="spanClick"></span>
				</div>
				<input class="form-control type_msg" name="usermsg" type="text" id="usermsg" wire:model="usermsg" placeholder="Escreva sua mensagem..."/>
				<div class="input-group-append">
					<span class="input-group-text send_btn" name="submitmsg" id="submitmsg" wire:click="SendMessage" style="background-color:#326c91!important;"><i class="fa fa-paper-plane"></i></span>
				</div>
			</div>
		</div>

           

    </div>


@if (Auth::user()->type_user == 2)
@push('custom-scripts')
@endif
      
   
<script>

jQuery(document).ready(function(){ 
  var input = document.getElementById("usermsg");
  //Livewire.emit("submitmsg")
  input.addEventListener("keypress", function(event) {
  // If the user presses the "Enter" key on the keyboard
  if (event.key === "Enter") {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    document.getElementById("submitmsg").click();
  }
});


    jQuery('#chatbox').on('scroll',function(){
        clearInterval(time);
        time = setInterval(updateScroll, 10000000000000000);
    });

    updateScroll();

    function updateScroll() {
      var element = document.getElementById("chatbox");
      element.scrollTop = element.scrollHeight;
    }

    var time = setInterval(updateScroll, 100);
    
    window.addEventListener('refreshChatPosition',function(e){
      clearInterval(time);
      time = setInterval(updateScroll, 100);
    });

});



</script>
@if (Auth::user()->type_user == 2)
@endpush
@endif
</div>