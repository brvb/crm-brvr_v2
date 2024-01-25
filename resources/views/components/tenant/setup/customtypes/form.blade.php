<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $formTitle }}</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ $action }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if ($update)
                            @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label>{{ __('Description') }}</label>
                                    <input type="text" name="description" id="description" class="form-control"
                                        @isset($description)value="{{ $description }}"@endisset
                                        placeholder="{{ __('Description') }}">
                                </div>
                              </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label>{{ __('Controller') }}</label>
                                    <input type="text" name="controller" id="controller" class="form-control"
                                        @isset($controller)value="{{ $controller }}"@endisset
                                        placeholder="{{ __('Controller') }}">
                                </div>
                              </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label>{{ __('Field Name') }}</label>
                                    <input type="text" name="field_name" id="field_name" class="form-control"
                                        @isset($fieldname)value="{{ $fieldname }}"@endisset
                                        placeholder="{{ __('Field Name') }}">
                                </div>
                              </div>
                        </div>
                        {{-- <div class="form-group row">
                            <div class="col-12 text-right">
                                <a href="{{ route('tenant.setup.custom-types.index') }}"
                                    class="btn btn-secondary mr-2">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary">{{ $buttonAction }}</button>
                            </div>
                        </div> --}}
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-footer justify-content-between">
        <div class="row">
            <div class="col text-right">
                <a href="{{ route('tenant.setup.custom-types.index') }}" class="btn btn-secondary mr-2">{{
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