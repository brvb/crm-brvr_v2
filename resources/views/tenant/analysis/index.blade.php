<x-tenant-layout title="{{ __('Analysis') }}" :themeAction="$themeAction" :status="$status" :message="$message">
    {{-- Content --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-9">
                <div class="page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Analysis') }}</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('List') }}</a></li>
                    </ol>
                </div>
            </div>
            {{-- <div class="col-3 text-right">
                <a href="{{ route('tenant.customers.create') }}" class="btn btn-primary">{{ __('Create Customer') }}</a>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card" >
                    <div class="card-header">
                        <h4 class="card-title">Todos os pedidos</h4>
                    </div>
                    <div class="card-body">
                        @livewire('tenant.analysis.show-tasks-reports')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-layout>

