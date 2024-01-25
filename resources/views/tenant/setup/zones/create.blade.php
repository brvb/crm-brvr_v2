<x-tenant-layout title="Nova Zona" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Zones') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Create') }}</a></li>
            </ol>
        </div>
        <div class="default-tab">
            @livewire('tenant.setup.zones.add-zones', ['zonesList' => $zonesList])
        </div>
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
