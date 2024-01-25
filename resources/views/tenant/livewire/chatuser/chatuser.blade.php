
<div class="card chat dz-chat-history-box d-none" wire:ignore.self>
	<div class="card-header chat-list-header text-center">
		<a href="javascript:void(0)" class="dz-chat-history-back">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
				<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<polygon points="0 0 24 0 24 24 0 24"></polygon>
					<rect fill="#000000" opacity="0.3" transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000)" x="14" y="7" width="2" height="10" rx="1"></rect>
					<path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997)"></path>
				</g>
			</svg>
		</a>
		<div>
			@php
				$userChat = \App\Models\User::where('id',$tech)->first();
			@endphp
			@if($userChat != null)
				<h6 class="mb-1">Chat com {{$userChat->name}}</h6>
			@endif
		</div>
		<div class="dropdown"></div>
	</div>
	<div class="card-body msg_card_body" id="DZ_W_Contacts_Body3" style="overflow:scroll; overflow-x:auto;">

		@if($chat != null)
		  @if(count($chat) == 0)
		     <h4 class="text-center">Escreva uma mensagem para começar conversação</h4>
		  @else
				@foreach ($chat as $ch)
					@if($ch->user_id == Auth::user()->id)
						<div class="d-flex justify-content-start mb-4">
							<div class="img_cont_msg">
								@php
									$user = \App\Models\User::where('id',Auth::user()->id)->first();
								@endphp
								@if(isset($user->photo))
									<img src="{!! global_tenancy_asset('/app/public/profile/'.$user->photo.'') !!}" class="rounded-circle users_img">
								@else
									<img src="{!! "https://".$_SERVER['SERVER_NAME']."/assets/resources/images/avatar/1.png" !!}" class="rounded-circle users_img">
								@endif
							</div>
							<div class="msg_cotainer">
								{{$ch->message}}
								<span class="msg_time">{{get_day_name($ch->created_at->toDateTimeString())}}</span>
							</div>
						</div>
					@else
						<div class="d-flex justify-content-end mb-4">
							<div class="msg_cotainer_send">
								{{$ch->message}}
								<span class="msg_time_send">{{get_day_name($ch->created_at->toDateTimeString())}}</span>
							</div>
							<div class="img_cont_msg">
								@php
									$user = \App\Models\User::where('id',$tech)->first();
								@endphp
								@if(isset($user->photo))
									<img src="{!! global_tenancy_asset('/app/public/profile/'.$user->photo.'') !!}" class="rounded-circle users_img">
								@else
									<img src="{!! "https://".$_SERVER['SERVER_NAME']."/assets/resources/images/avatar/1.png" !!}" class="rounded-circle users_img">
								@endif
							</div>
						</div>
					@endif
				@endforeach
			@endif
			
		@else
			<h4 class="text-center">Escreva uma mensagem para começar conversação</h4>
		@endif
		
		<div class="ps__rail-x">
			<div class="ps__thumb-x" tabindex="0"></div>
		</div>
		<div class="ps__rail-y">
			<div class="ps__thumb-y" tabindex="0"></div>
		</div>



	</div>
	<div class="card-footer type_msg">
		<div class="input-group">
			<textarea class="form-control" id="usermsg" placeholder="Escreva a sua mensagem..." wire:model.defer="usermsg"></textarea>
			<div class="input-group-append">
				<button type="button" id="submitmsg" class="btn btn-primary" wire:click="SendMessage">
					<i class="fa fa-location-arrow"></i>
				</button>
			</div>
		</div>
	</div>
</div>


@push('custom-scripts')

     
   
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


    jQuery('#DZ_W_Contacts_Body3').on('scroll',function(){
        clearInterval(time);
        time = setInterval(updateScroll, 10000000000000000);
    });

    updateScroll();

    function updateScroll() {
      var element = document.getElementById("DZ_W_Contacts_Body3");
      element.scrollTop = element.scrollHeight;
    }

    var time = setInterval(updateScroll, 100);
    
	
	 window.addEventListener('refreshChatPosition',function(e){
       	clearInterval(time);
         time = setInterval(updateScroll, 100);
		 jQuery("#DZ_W_Contacts_Body3").scrollTop(jQuery("DZ_W_Contacts_Body3")[0].scrollHeight);
     });

	
	
   

});



</script>

@endpush
