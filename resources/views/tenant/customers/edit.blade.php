
<x-tenant-layout title="Atualizar Cliente '{!! $customerInfo->customers->name !!}'" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Customer') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Update') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $customerInfo->customers->name }}</a></li>
            </ol>
        </div>
        <div class="default-tab">
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
                        <a class="nav-link active" data-toggle="tab" href="#homePanel"><i class="la la-home mr-2"></i> {{ __('Customer') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#locationPanel"><i class="flaticon-381-location-2 mr-2"></i> {{ __('Location') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#servicesPanel"><i class="flaticon-381-notepad mr-2"></i> {{ __('Services') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#mapPanel"><i class="flaticon-381-location-2 mr-2"></i> Mapa</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#contactsPanel"><i class="flaticon-381-smartphone-1 mr-2"></i> {{ __('Contacts') }}</a>
                    </li> --}}
    
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="homePanel" role="tabpanel">
                        @livewire('tenant.customers.edit-customer', ['customer' => $customerInfo->customers])
                    </div>
                     <div class="tab-pane fade" id="locationPanel" role="tabpanel">
                        @livewire('tenant.customers.show-customerlocations', ['customer' => $customerInfo->customers])
                    </div>
                    <div class="tab-pane fade" id="mapPanel" role="tabpanel">
                        @livewire('tenant.customers.show-map', ['customer' => $customerInfo->customers])
                    </div>
        
                    <div class="tab-pane fade" id="servicesPanel" role="tabpanel">
                        @livewire('tenant.customers.show-customerservices', ['customer' => $customerInfo->customers])
                    </div> 
                    {{-- <div class="tab-pane fade" id="contactsPanel" role="tabpanel">
                        @livewire('tenant.customers.show-customercontacts', ['customer' => $customer])
                    </div> --}}

                </div>
        </div>
    </div>
    <div class="erros">

        @if ($errors->any())
            <script>
                let status = '';
                let message = '';

                status = 'error';

                @php

                $allInfo = '';

                foreach ($errors->all() as $err )
                {
                   $allInfo .= $err."<br>";
                }

                $message = $allInfo;

                @endphp
                message = '{!! $message !!}';
            </script>
        @endif
    </div>
</x-tenant-layout>
