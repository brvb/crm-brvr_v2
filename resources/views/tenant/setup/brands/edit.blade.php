<x-tenant-layout title="{{ __('Edit Brand') }} '{!! $brand->name !!}'" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Setup') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Device Brands') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Update') }}</a></li>
            </ol>
        </div>
        <x-tenant.setup.brands.form :action="route('tenant.setup.brands.update', $brand->id)" :name="$brand->name" :image="$brand->image" :update="true" buttonAction="{{ __('Update Brand') }}" formTitle="{{ __('Update Brand') }}"/>
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
