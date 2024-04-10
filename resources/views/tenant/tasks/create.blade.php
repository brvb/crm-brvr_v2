<style>
    @media (max-width: 767.98px) {
      .container-title h2 {
        position: static;
        margin-bottom: 0.5rem;
      }
    }
</style>
<x-tenant-layout title="Adicionar Pedido" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Pedidos') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Criar') }}</a></li>
            </ol>
        </div>
        <div class="default-tab">
            @livewire('tenant.tasks.add-tasks')
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
