<x-tenant-layout title="{{ __('Create Contact') }}" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Customer') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ $customer->name }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Contacts') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Create') }}</a></li>
            </ol>
        </div>
        <!-- row -->
        <x-tenant.customercontacts.form :action="route('customer-contacts.store')" :update="false" :customerId="$customer->id" :customerName="$customer->name" :customerLocation="$customerLocation" buttonAction="{{__('Create Contact')}} " formTitle="{{ __('Create Contact') }}"/>
    </div>
    <div class="erros">
        @if ($errors->any())
            <script>
                let status = '';
                let message = '';

                status = 'error';
                @php
                    $message = '';
                @endphp
                @foreach ($errors->all() as $error)
                    @php
                        $message .= '<p>' . $error . '</p>';
                    @endphp
                @endforeach
                message = '{!! $message !!}';
            </script>
        @endif
    </div>
</x-tenant-layout>
