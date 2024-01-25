<x-tenant-layout title="Listagem de Ficheiros" :themeAction="$themeAction" :status="$status" :message="$message">
    {{-- Content --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-9">
                <div class="page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Files') }}</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('List') }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            @livewire('tenant.files-customer.show-files')
        </div>
    </div>
</x-tenant-layout>
{{-- <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>
  
    var pusher = new Pusher('9fc590949fcf83c98e55', {
        cluster: 'eu',
    });
    
    var channel = pusher.subscribe('chat');
    channel.bind('App\\Events\\ChatMessage', function(data) {
        Livewire.emit("chatUpdate");
        Livewire.emit("AlertMessages");
    });
</script> --}}
