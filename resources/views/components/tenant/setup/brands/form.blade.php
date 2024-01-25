<div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $formTitle }}</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <span id="typeAction" style="display:none">{{$update}}</span>
                    <form action="{{ $action }}" method="post" id="formenv" enctype="multipart/form-data">
                        @csrf
                        @if ($update)
                            @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-xl-8 col-xs-12 mb-3">
                                <div class="form-group row">
                                    <label>{{ __('Brand Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        @isset($name)value="{{ $name }}"@endisset
                                        placeholder="{{ __('Brand Name') }}">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-primary btn-sm" type="button">{{__("Image")}}</button>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="file" id="file" class="custom-file-input">
                                        <label class="custom-file-label">@if(isset($image)) {{$name}} @else {{__('Choose file')}} @endif</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-xs-12">
                                <img src="" id="imagePreview" width="200">
                                @if(isset($image))
                                    <input type="hidden" name="image" id="image" value="{{ $image }}">
                                    <img src="{{ global_tenancy_asset('/app/public/brands/'.$image) }}" alt="{{ $name }}" id="imagePreviewEdit" width="200">
                                @endif
                            </div>
                        </div>
                        {{-- <img src="{{ tenant_asset('image.jpg') }}"> --}}
                        <p></p>
                        {{-- <div class="form-group row">
                            <div class="col-12 text-right">
                                <a href="{{ route('tenant.setup.brands.index') }}"
                                    class="btn btn-secondary mr-2">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary">{{ $buttonAction }}</button>
                            </div>
                        </div>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-footer justify-content-between">
        <div class="row">
            <div class="col text-right">
                <a href="{{ route('tenant.setup.brands.index') }}" class="btn btn-secondary mr-2">{{
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
        jQuery(document).ready(function (e){

            var method = $("#typeAction").text();
                        
            jQuery('#file').change(function(){

                let reader = new FileReader();


                reader.onload = (e) => { 
                  
                    if(method == "1")
                    {
                        jQuery("#imagePreviewEdit").attr('src', e.target.result);
                    }
                    else 
                    {
                        jQuery('#imagePreview').attr('src', e.target.result); 
                    }
                   
                }

                reader.readAsDataURL(this.files[0]); 
            });
        });
   </script>
@endpush
</div>
