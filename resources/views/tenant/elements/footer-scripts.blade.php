@if(!empty(config('dz.public.global.js')))
	@foreach(config('dz.public.global.js') as $script)
        <script src="{{ '/assets/resources/' . $script }}"></script>
	@endforeach
@endif
@if(!empty(config('dz.public.pagelevel.js.'.$attributes['themeAction'])))
	@foreach(config('dz.public.pagelevel.js.'.$attributes['themeAction']) as $script)
        <script src="{{ '/assets/resources/' . $script }}"></script>
	@endforeach
@endif
@if(!empty(config('dz.public.sweet.js')))
	@foreach(config('dz.public.sweet.js') as $script)
        <script src="{{ '/assets/resources/' . $script }}"></script>
	@endforeach
@endif
{{-- <script src="{{ '/assets/resources/js/teste.js' }}" type="module"></script> --}}
<script>

    window.addEventListener('swal',function(e){
        if(e.detail.confirm) {
            var page = e.detail.page;
            var customer_id = e.detail.customer_id;
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
                    Livewire.emit('resetChanges');
                    if(page != "edit")
                    {
                        jQuery("#selectedCustomer").val("");
                    }
                    else {
                        jQuery("#selectedCustomer").val(jQuery("#selectedCustomer").attr('selected','selected'));
                    }
                }
            });
        } else {
            swal(e.detail.title, e.detail.message, e.detail.status);
            jQuery("#customer_id").val(jQuery("#selectedCustomer").val());
        }
        restartObjects();
    });
</script>
@livewireScripts
