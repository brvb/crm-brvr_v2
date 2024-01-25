<x-tenant-layout title="Nova Localização de Cliente" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Files') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Create') }}</a></li>
            </ol>
        </div>
        <div class="default-tab">
            @livewire('tenant.customerlocations.add-customerlocations', ['customerList' => $customerList])
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
