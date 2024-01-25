<x-tenant-layout title="Novo Cliente" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Customers') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Create') }}</a></li>
            </ol>
        </div>
        <div class="default-tab">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home"><i class="la la-home mr-2"></i> {{ __("Customer") }}</a>
                </li>
                <li class="nav-item" style="display:none;">
                    <a class="nav-link disabled" data-toggle="tab" href="#profile"><i class="la la-phone mr-2"></i> Contacts</a>
                </li>
                <li class="nav-item" style="display:none;">
                    <a class="nav-link disabled" data-toggle="tab" href="#contact"><i class="flaticon-381-notepad mr-2"></i> Services</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="home" role="tabpanel">
                    <div>
                        <x-tenant.customers.form :action="route('tenant.customers.store')" :update="false" buttonAction="{{__('Create Customer')}}" formTitle="{{ __('Create Customer') }}" :districts="$districts" :counties="$counties" :district="$district" :county="$county" :allAccountManagers="$allAccountManagers"/>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile">
                    <div>

                    </div>
                </div>
                <div class="tab-pane fade" id="contact">
                    <div>

                    </div>
                </div>
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
