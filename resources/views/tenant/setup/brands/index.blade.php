<x-tenant-layout title="Listagem de Marcas" :themeAction="$themeAction" :status="$status" :message="$message">
    {{-- Content --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-9 col-xs-6">
                <div class="page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Setup') }}</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Device Brands') }}</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('List') }}</a></li>
                    </ol>
                </div>
            </div>
            <div class="col-xl-3 col-xs-6 text-right">
                <a href="{{ route('tenant.setup.brands.create') }}" class="btn btn-primary">{{ __('Create Brand') }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Brands') }}</h4>
                    </div>
                    <div class="card-body">
                        @livewire('tenant.setup.brands.show-brands')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-layout>
