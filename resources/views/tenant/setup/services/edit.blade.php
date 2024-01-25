<x-tenant-layout title="{{ __('Edit Service') }} '{!! $service->name !!}'" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Setup') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Services') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Update') }}</a></li>
            </ol>
        </div>
     
        <x-tenant.setup.services.form :action="route('tenant.setup.services.update', $service->id)" :typeList="$typeList" :paymentList="$paymentList" :idservice="$service->id" :name="$service->name" :description="$service->description" :type="$service->type" :file="$service->file" :payment="$service->payment" :periodicity="$service->periodicity" :alert="$service->alert" :update="true" buttonAction="{{ __('Update service') }}" formTitle="{{ __('Update Service') }}"/>
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
