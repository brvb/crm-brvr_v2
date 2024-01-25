<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $formTitle }}</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form wire:submit.prevent="save" class="tab-content">
                    {{-- <form action="{{ $action }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if ($update)
                            @method('PUT')
                        @endif --}}
                        <div class="row">
                            <div class="col-xl-8 col-xs-12 mb-3">
                                <div class="form-group row">
                                    <label>{{ __('Zone Name') }}</label>
                                    <input type="text" name="name" id="name" wire:model.defer="name" class="form-control"
                                        @isset($name)value="{{ $name }}"@endisset
                                        placeholder="{{ __('Zone Name') }}">

                                        <label>{{ __('Local') }}</label>
                                        <input type="text" name="locals" id="locals"  wire:model.defer="locals" class="form-control"
                                            @isset($locals)value="{{ $locals }}"@endisset
                                            placeholder="{{ __('Local') }}">

                                        <label>{{ __('Comercial') }}</label>
                                        <input type="text" name="comercial" id="comercial" wire:model.defer="comercial" class="form-control"
                                            @isset($comercial)value="{{ $comercial }}"@endisset
                                            placeholder="{{ __('Comercial') }}">

                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-footer justify-content-between">
        <div class="row">
            <div class="col text-right">
                <a href="{{ route('tenant.setup.zones.index') }}" class="btn btn-secondary mr-2">{{
                    __('Back') }}
                    <span class="btn-icon-right"><i class="las la-angle-double-left"></i></span>
                </a>
                <button type="submit" style="border:none;background:none;">
                    <a type="submit" class="btn btn-primary"  role="button">
                        {{ $buttonAction }}
                        <span class="btn-icon-right"><i class="las la-check mr-2"></i></span>
                    </a>
                </button>
            </div>
        </div>
    </div>
</div>
</form>
    @push('custom-scripts')
    <script>
          window.addEventListener('swal',function(e){
            swal(e.detail.title, e.detail.message, e.detail.status);
            // restartObjects();
        });
    </script>
    @endpush

