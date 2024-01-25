<x-tenant-layout title="Novo Membro de Equipa" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Team Members') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Create') }}</a></li>
            </ol>
        </div>
        <div class="default-tab">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home"><i class="la la-home mr-2"></i> {{__("Team Members")}}</a>
                </li>
                <li class="nav-item" style="display:none">
                    <a class="nav-link disabled" data-toggle="tab" href="#profile"><i class="la la-phone mr-2"></i> Contacts</a>
                </li>
                <li class="nav-item" style="display:none">
                    <a class="nav-link disabled" data-toggle="tab" href="#contact"><i class="flaticon-381-notepad mr-2"></i> Services</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="home" role="tabpanel">
                    <div class="pt-0">
                        <x-tenant.teammembers.form :action="route('tenant.team-member.store')" :update="false" buttonAction="{{ __('Create Team Member') }}" formTitle="{{ __('Create Team Member') }}"/>
                    </div>
                </div>
            </div>
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
