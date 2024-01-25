<x-tenant-layout title="{{ __('Config') }}" :themeAction="$themeAction" :status="$status" :message="$message">
    {{-- Content --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-9">
                <div class="page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Setup') }}</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('App') }}</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Config') }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Config') }}</h4>
                    </div>
                    <div class="card-body">
                        @livewire('tenant.setup.config.edit-config')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-layout>
