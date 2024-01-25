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
                            <div class="col">
                                <div class="form-group row">
                                    <input type="hidden" name="customer_id" @isset($customerId) value="{{ $customerId }}" @endisset>
                                    <label>{{ __('Location') }}</label>
                                    <select name="location_id" id="location_id" class="form-control">
                                        <option value="0">{{ __('Select location') }}</option>
                                            @forelse ($customerLocation as $item)
                                                <option value="{{ $item->id }}" @if(isset($customerContact->location_id) && $item->id == $customerContact->location_id) selected @endif>{{ $item->description }}</option>
                                            @empty
                                            @endforelse
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label>{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="@if(old('name')) {{ old('name') }} @elseif(isset($customerContact->name)){{ $customerContact->name }}@endif"
                                        placeholder="{{ __('Name') }}">
                                </div>
                                <div class="form-group row">
                                    <label>{{ __('Job Description') }}</label>
                                    <input type="text" name="job_description" id="job_description" class="form-control"
                                        value="@if(old('job_description')) {{ old('job_description') }} @elseif(isset($customerContact->job_description)){{ $customerContact->job_description }}@endif"
                                        placeholder="{{ __('Job Description') }}">
                                </div>
                                <div class="form-group row">
                                    <label>{{ __('Mobile Phone') }}</label>
                                    <input type="text" name="mobile_phone" id="mobile_phone" class="form-control"
                                        value="@if(old('mobile_phone')) {{ old('mobile_phone') }} @elseif(isset($customerContact->mobile_phone)){{ $customerContact->mobile_phone }}@endif"
                                        placeholder="{{ __('Mobile Phone') }}">
                                </div>
                                <div class="form-group row">
                                    <label>{{ __('LandLine') }}</label>
                                    <input type="text" name="landline" id="landline" class="form-control"
                                        value="@if(old('landline')) {{ old('landline') }} @elseif(isset($customerContact->landline)){{ $customerContact->landline }}@endif"
                                        placeholder="{{ __('LandLine') }}">
                                </div>
                                <div class="form-group row">
                                    <label>{{ __('Email') }}</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                        value="@if(old('email')) {{ old('email') }} @elseif(isset($customerContact->email)){{ $customerContact->email }}@endif"
                                        placeholder="{{ __('Email') }}">
                                </div>
                                <div class="form-group row">
                                    <div class="custom-control custom-checkbox mb-3 checkbox-success check-lg">
                                        <input type="checkbox" class="custom-control-input" @if(old('allMails')) checked @elseif(isset($customerContact->all_mails) && $customerContact->all_mails == 1) checked @endif id="customCheckBox8" name="allMails">
                                        <label class="custom-control-label pl-2" for="customCheckBox8">{{ __('Receive all email communications') }}</label>
                                    </div>
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
                @if(Auth::user()->type_user != 2)
                    <a href="{{ route('tenant.customers.index') }}" class="btn btn-secondary mr-2">{{
                        __('Back') }}
                        <span class="btn-icon-right"><i class="las la-angle-double-left"></i></span>
                    </a>
                @endif
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
</div>
