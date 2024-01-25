<div class="default-tab">
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#servicePanel"><i class="la la-home mr-2"></i> {{ __('Service') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#filesPanel"><i class="flaticon-381-notepad mr-2"></i> {{ __('Files') }}</a>
    </li>
</ul>
<form action="{{ $action }}" method="post" enctype="multipart/form-data">
<div class="tab-content">
    <div class="tab-pane fade show active" id="servicePanel" role="tabpanel">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $formTitle }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            
                                @csrf
                                @if ($update)
                                    @method('PUT')
                                @endif
                                <div class="form-group row">
                                    <div class="col">
                                        <label>{{ __('Service Name') }}</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            @isset($name)value="{{ $name }}"@endisset
                                            @if(null !== old('name'))value="{{ old('name') }}"@endisset
                                            placeholder="{{ __('Service Name') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>{{ __('Description') }}</label>
                                        <input type="text" name="description" id="description" class="form-control"
                                            @isset($description)value="{{ $description }}"@endisset
                                            @if(null !== old('description'))value="{{ old('description') }}"@endisset
                                            placeholder="{{ __('Description') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>{{ __('Type') }}</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">{{ __('Select type') }}</option>
                                            @forelse ($typeList as $item)
                                                <option value="{{ $item->id }}" @if(isset($type) && $type == $item->id) selected @endif>{{ $item->description }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>{{ __('Payment') }}</label>
                                        <select name="payment" id="payment" class="form-control">
                                            <option value="">{{ __('Select payment type') }}</option>
                                            @forelse ($paymentList as $item)
                                                <option value="{{ $item->id }}" @if(isset($payment) && $payment == $item->id) selected @endif>{{ $item->description }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label>{{ __('Periodicity (in months)') }}</label>
                                        <input type="number" name="periodicity" id="periodicity" class="form-control"
                                        @isset($periodicity)value="{{ $periodicity }}"@endisset
                                        @if(null !== old('periodicity'))value="{{ old('periodicity') }}"@endisset
                                        placeholder="{{ __('Periodicity') }}">
                                    </div>
                                    <div class="col-6">
                                        <label>{{ __('Alert before (in months)') }}</label>
                                        <input type="number" name="alert" id="alert" class="form-control"
                                        @isset($alert)value="{{ $alert }}"@endisset
                                        @if(null !== old('alert'))value="{{ old('alert') }}"@endisset
                                        placeholder="{{ __('Alert before') }}">
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade show" id="filesPanel" role="tabpanel">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $formTitle }}</h4>
                    </div>
                    <div class="card-body">
                        @if(isset($file) && $file != null)
                            @livewire('tenant.setup.services.update-table-service',['file' => $file,'update' => $update])
                        @else
                            @php
                                $file = '';
                            @endphp
                            @livewire('tenant.setup.services.update-table-service',['file' => $file,'update' => $update])
                        @endif
                            <input type="hidden" name="ReceiveValuesLivewire[]" id="ReceiveValuesLivewire">
                            {{-- @if ($update)
                              @livewire('tenant.setup.services.update-table-service',['files' => $file,'idService' => $idservice])
                            @endif --}}
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
                <a href="{{ route('tenant.setup.services.index') }}" class="btn btn-secondary mr-2">{{
                    __('Back') }}
                    <span class="btn-icon-right"><i class="las la-angle-double-left"></i></span>
                </a>
                
                <button type="submit" class="addServiceButton" style="border:none;background:none;">
                    <a type="submit" class="btn btn-primary"  role="button">
                        {{ $buttonAction }}
                        <span class="btn-icon-right"><i class="las la-check mr-2"></i></span>
                    </a>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="variaveis">

</div>
</form>
</div>



@push('custom-scripts')
<script>
var count = 0;
jQuery( document ).ready(function() {
    //Livewire.emit("refresh");
});

 window.addEventListener('sendValues',function(e){
      
        jQuery("#valuesTables").append("<tr data-id='"+count+"'><td>"+e.detail.Name+"</td><td><input type='text' name='fileName[]'' value="+e.detail.Name+" data-id="+count+"></td><td>"+e.detail.Size+"&nbsp;<button type='button' id='buttonRemover' class='btn-xs btn-danger' data-id="+count+">x</button></td></tr>");
        jQuery(".variaveis").append('<input type="text" name="fileName[]" value="'+e.detail.Name+'" data-id="'+count+'">');
        jQuery(".variaveis").append('<input type="text" name="fileSize[]" value="'+e.detail.Size+'" data-id="'+count+'">');

        count++;
  
 });

 jQuery("body").on("click",'#buttonRemover',function(){
    Livewire.emit("removeNew",jQuery(this).attr("data-id"));
 });

 window.addEventListener('receive',function(e){
        
        jQuery(".variaveis").append('<input type="text" name="fileName[]" value="'+e.detail.Name+'" data-id="'+count+'">');
        jQuery(".variaveis").append('<input type="text" name="fileSize[]" value="'+e.detail.Size+'" data-id="'+count+'">');
        count++;
  
 });

 window.addEventListener('removeFromTable',function(e){
        
      jQuery("#valuesTables tr[data-id="+e.detail.id+"]").remove();
      jQuery(".variaveis input[data-id="+e.detail.id+"]").remove();
  
 });

 


</script>   
@endpush