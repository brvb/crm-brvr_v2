<div class="row">
    <div class="col-12">
        <div class="card" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
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
                                    <section class="col-xl-8 col-xs-12">
                                        <input style="display:none" type="text" name="idCustomer" id="idCustomer" class="form-control"
                                        @isset($idCustomer) value="{{ $idCustomer }}" @endisset
                                        @if(null !== old('idCustomer'))value="{{ old('idCustomer') }}"@endisset
                                        placeholder="{{ __('idCustomer') }}">
                                        <label>{{ __('Customer Name') }}</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            @isset($name)value="{{ $name }}" @endisset
                                            @if(null !== old('name'))value="{{ old('name') }}"@endisset
                                            placeholder="{{ __('Customer Name') }}">
                                    </section>
                                    <section class="col-xl-2 col-xs-12">
                                        <label>{{ __('Slug') }}</label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            @isset($slug)value="{{ $slug }}" @endisset
                                            @if(null !== old('slug'))value="{{ old('slug') }}"@endisset
                                            placeholder="{{ __('slug') }}">
                                    </section>
                                    <section class="col-xl-2 col-xs-12">
                                        <label>{{ __('VAT') }}</label>
                                        <input type="text" name="vat" id="vat" class="form-control"
                                            @isset($vat)value="{{ $vat }}" @endisset
                                            @if(null !== old('vat'))value="{{ old('vat') }}"@endisset
                                            placeholder="{{ __('VAT') }}">
                                    </section>
                                </div>
                                <div class="form-group row">
                                    <section class="col-xl-3 col-xs-12">
                                        <label>{{ __('Short Name') }}</label>
                                        <input type="text" name="short_name" id="short_name" class="form-control"
                                            @isset($shortName)value="{{ $shortName }}" @endisset
                                            @if(null !== old('short_name'))value="{{ old('short_name') }}"@endisset
                                            placeholder="{{ __('Short Name') }}">
                                    </section>
                                    <section class="col-xl-3 col-xs-12">
                                        <label>{{ __('Phone number') }}</label>
                                        <input type="text" name="contact" id="contact" class="form-control"
                                            @isset($contact)value="{{ $contact }}" @endisset
                                            @if(null !== old('contact'))value="{{ old('contact') }}"@endisset
                                            placeholder="{{ __('Phone number') }}">
                                    </section>
                                    <section class="col-xl-6 col-xs-12">
                                        <label>{{ __('Primary e-mail address') }}</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            @isset($email)value="{{ $email }}" @endisset
                                            @if(null !== old('email'))value="{{ old('email') }}"@endisset
                                            placeholder="{{ __('Primary e-mail address') }}">
                                    </section>
                                </div>
                                <div class="form-group row">
                                    <section class="col-xl-6 col-xs-12">
                                        <label>{{ __('Customer Address') }}</label>
                                        <input type="text" name="address" id="address" class="form-control"
                                            @isset($address)value="{{ $address }}" @endisset
                                            @if(null !== old('address'))value="{{ old('address') }}"@endisset
                                            placeholder="{{ __('Customer Address') }}">
                                    </section>
                                    <section class="col-xl-6 col-xs-12">
                                        <label>{{ __('Username') }}</label>
                                        <input type="text" name="username" id="username" class="form-control"
                                            @isset($username)value="{{ $username }}" @endisset
                                            @if(null !== old('username'))value="{{ old('username') }}"@endisset
                                            placeholder="{{ __('Username') }}">
                                    </section>
                                </div>
                                <div class="form-group row">
                                    <section class="col-xl-2 col-xs-12">
                                        <label>{{ __('Zip Code') }}</label>
                                        <input type="text" name="zipcode" id="zipcode" class="form-control"
                                            @isset($zipcode)value="{{ $zipcode }}" @endisset
                                            @if(null !== old('zipcode'))value="{{ old('zipcode') }}"@endisset
                                            placeholder="{{ __('Zip Code') }}">
                                    </section>

                                    @livewire('tenant.common.location',  ['districts' => $districts, 'counties' => $counties, 'district' => $district, 'county' => $county])
                                </div>
                                <div class="form-group row">
                                    <section class="col">
                                        <label>{{ __('Account manager') }}</label>
                                        @if(Auth::user()->type_user == "2")  
                                            <input type='hidden' name="account_manager" id="account_manager" value="{{$accountmanager}}">
                                        @endif
                                        <select name="account_manager" id="account_manager" class="form-control"  @if(Auth::user()->type_user == "2") disabled @endif>
                                            <option value="">{{ __('Select account manager') }}</option>

                                            {{-- @if (isset($accountmanager) && $accountmanager != '') --}}
                                                @forelse ($allAccountManagers as $item)
                                                    <option value="{{ $item->id }}" @if(isset($accountmanager) && $item->id == $accountmanager) selected @endif>{{ $item->name }}</option>
                                                @empty
                                                @endforelse
                                            {{-- @endif --}}
                                        </select>
                                    </section>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <div class="col-12 text-right">
                                <a href="{{ route('tenant.customers.index') }}" class="btn btn-secondary mr-2">{{
                                    __('Cancel') }}</a>
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
                @if(Auth::user()->type_user != "2")
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
