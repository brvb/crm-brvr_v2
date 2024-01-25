<div>
    <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
        style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
        <div class="sk-three-bounce" style="background:none;">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#locationPanel"><i
                    class="flaticon-381-location-2 mr-2"></i> {{ __('Location') }}</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="locationPanel">
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
                                <div class="tab-content">
                                    <section class="form-group row">
                                        <div class="col">
                                            <label>{{ __('Customer Name') }}</label>
                                            @php
                                            if(isset($customerLocation) && $customerLocation->main == 1) {
                                            echo '<input type="hidden" name="customer_id" id="customer_id">';
                                            echo '<select name="selectedCustomer" class="form-control" id="selectedCustomer" 
                                                style="pointer-events: none">';
                                                } else {
                                                echo '<input type="hidden" name="customer_id" id="customer_id">';
                                                echo '<select name="selectedCustomer" class="form-control" id="selectedCustomer" >';
                                                     }
                                                    @endphp
                                                    <option value="">{{ __('Select Customer') }}</option>
                                                    @if (Auth::user()->type_user == '2')
                                                      <option value="{{ $customerList->id }}" @isset($customerLocation->customer_id) @if($customerList->id ==
                                                        $customerLocation->customer_id)
                                                        selected @endif @endisset>{{ $customerList->name }}</option>
                                                    @else
                                                     

                                                    @forelse ($customerList as $item)
                                                    <option value="{{ $item->id }}" @isset($customerLocation->customer_id) @if($item->id ==
                                                        $customerLocation->customer_id)
                                                        selected @endif @endisset>{{ $item->name }}</option>
                                                    @empty
                                                    @endforelse
                                                    @endif
                                                </select>
                                        </div>
                                    </section>
                                    <section class="form-group row">
                                        <div class="col-xl-10 col-xs-12">
                                            <label>{{ __('Location Name') }}</label>
                                            <input type="text" name="description" id="description" class="form-control"
                                                @if(null !==old('description'))value="{{ old('description') }}"
                                                @endisset placeholder="{{ __('Location Name') }}"
                                                wire:model.defer="description">
                                        </div>
                                        <div class="col-xl-2 col-xs-12">
                                            <label>{{ __('Main Location') }}</label>
                                            <input type="checkbox" name="main" id="main" style="pointer-events: none;" @isset($customerLocation->main) @if($customerLocation->main ==
                                            1) checked readonly value="1" @else value="0" @endif @endisset>
                                        </div>
                                    </section>
                                    <section class="form-group row">
                                        <div class="col-xl-3 col-xs-12">
                                            <label>{{ __('Location Phone number') }}</label>
                                            <input type="text" name="contact" id="contact" class="form-control" @if(null
                                                !==old('contact'))value="{{ old('contact') }}" @endisset
                                                placeholder="{{ __('Location Phone number') }}"
                                                wire:model.defer="contact">
                                        </div>
                                        <div class="col-xl-6 col-xs-12">
                                            <label>{{ __('Manager name') }}</label>
                                            <input type="text" name="manager_name" id="manager_name"
                                                class="form-control" @if(null
                                                !==old('manager_name'))value="{{ old('manager_name') }}" @endisset
                                                placeholder="{{ __('Manager name') }}" wire:model.defer="manager_name">
                                        </div>
                                        <div class="col-xl-3 col-xs-12">
                                            <label>{{ __('Manager Phone number') }}</label>
                                            <input type="text" name="manager_contact" id="manager_contact"
                                                class="form-control" @if(null
                                                !==old('manager_contact'))value="{{ old('manager_contact') }}" @endisset
                                                placeholder="{{ __('Manager Phone number') }}"
                                                wire:model.defer="manager_contact">
                                        </div>
                                    </section>
                                    <section class="form-group row">
                                        <div class="col-12" wire:ignore>
                                            <label>{{ __('Location Address') }}</label>
                                            <input type="text" name="address" id="address" class="form-control" @if(null
                                                !==old('address'))value="{{ old('address') }}" @endisset
                                                placeholder="{{ __('Location Address') }}" wire:model.defer="address">
                                        </div>
                                    </section>
                                    <section class="form-group row">
                                        <div class="col-xl-2 col-xs-12">
                                            <label>{{ __('Location Zip Code') }}</label>
                                            <input type="text" name="zipcode" id="zipcode" class="form-control" @if(null
                                                !==old('zipcode'))value="{{ old('zipcode') }}" @endisset
                                                placeholder="{{ __('Zip Code') }}" wire:model.defer="zipcode">
                                        </div>
                                        @if(isset($customerLocation) && $customerLocation != '')
                                        @livewire('tenant.common.location', ['districts' => $districts,
                                        'counties' => $counties, 'district' => $customerLocation->district_id,
                                        'county' => $customerLocation->county_id])
                                        @else
                                        @livewire('tenant.common.location', ['districts' => $districts,
                                        'counties' => $counties, 'district' => '', 'county' => ''])
                                        @endif
                                    </section>
                                {{-- </form> --}}
                                {{-- <section class="form-group row">
                                    <div class="col-6">
                                        <a href="{{ route('tenant.customer-locations.index') }}"
                                            class="btn btn-secondary"><i class="las la-angle-double-left mr-2"></i>{{
                                            __('Back') }}</a>
                                    </div>
                                    <div class="col-6 text-right">
                                        <a wire:click="cancel" class="btn btn-secondary mr-2">
                                            <i class="las la-times mr-2"></i>{{ $cancelButton }}
                                        </a>
                                        <button type="submit" class="btn btn-primary">{{ $buttonAction }}</button>
                                    </div>
                                </section> --}}
                            {{-- </form> --}}
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
                            <a href="{{ route('tenant.customer-locations.index') }}" class="btn btn-secondary mr-2">{{
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
        @push('custom-scripts')
        <script>
             document.addEventListener('livewire:load', function () {
                jQuery("#selectedCustomer").on("change",function(e)
                {
                    jQuery("#customer_id").val(jQuery(this).val());
                });
            //     if(jQuery('#selectedCustomervisual').length > 0) {
            //         jQuery('#selectedCustomervisual').select2();
            //     } else {
            //         jQuery('#selectedCustomer').select2();
                     //jQuery("#selectedCustomer").on("select2:select", function (e) { @this.selectedCustomer = jQuery('#selectedCustomer').find(':selected').val(); });
            //     }
            //     jQuery('#district').select2();
            //     jQuery("#district").on("select2:select", function (e) { @this.district = jQuery('#district').find(':selected').val(); });
            //     jQuery('#county').select2();
            //     jQuery("#county").on("select2:select", function (e) { @this.county = jQuery('#county').find(':selected').val(); });
             });

             jQuery("#main").on("change",function(e)
             {
                if(jQuery(this).is(":checked"))
                {
                    jQuery(this).val("1");
                }
                else {
                    jQuery(this).val("0");
                }
             });

            window.addEventListener('swal',function(e){
                if(e.detail.confirm) {
                    var page = e.detail.page;
                    var customer_id = e.detail.customer_id;
                    swal.fire({
                        title: e.detail.title,
                        html: e.detail.message,
                        type: e.detail.status,
                        page: e.detail.page,
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
            });
        </script>
        @endpush
    </div>
