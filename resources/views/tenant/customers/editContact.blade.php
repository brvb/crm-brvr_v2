<x-tenant-layout title="{{ __('Edit Contact') }} '{!! $customerContact->name !!}'" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Customer') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ $customer->name }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Contact') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ $customerContact->name }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Update') }}</a></li>
            </ol>
        </div>
        <x-tenant.customercontacts.form :action="route('customer-contacts.update', $customerId)" :customerContact="$customerContact" :customerLocation="$customerLocation" :customerId="$customerId" :update="true" buttonAction="{{ __('Update Contact') }}" formTitle="{{ __('Update Contact') }}"/>
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
                        $message .= $message . '<p>' . $error . '</p>';
                    @endphp
                @endforeach
                message = '{!! $message !!}';
            </script>
        @endif
    </div>
</x-tenant-layout>
