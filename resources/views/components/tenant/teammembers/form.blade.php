<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $formTitle }}</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ $action }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if ($update)
                            @method('PUT')
                           
                        @endif
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <section class="col-xl-12 col-xs-12">
                                        
                                        <label  for="checkStatus">Estado</label>
                                        <div class="form-check custom-checkbox mb-3 checkbox-info">
                                    
                                            <input type="checkbox" class="form-check-input" name="checkstatus" id="checkstatus" @isset($checkstatus) @if($checkstatus == 1) checked @endif @endisset>
                                            <label class="form-check-label" for="customCheckBox2">Ativado</label>
                                        </div>
                                       
                                    </section>
                                    <section class="col-xl-7 col-xs-12">
                                       
                                        <input style="display:none" type="text" name="idTeamMember" id="idTeamMember" class="form-control"
                                            @isset($idTeamMember) value="{{ $idTeamMember }}" @endisset
                                            @if(null !== old('idTeamMember'))value="{{ old('idTeamMember') }}"@endisset
                                            placeholder="{{ __('idTeamMember') }}">
                                        <label>{{ __('Name') }}</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            @isset($name)value="{{ $name }}" @endisset
                                            @if(null !== old('name'))value="{{ old('name') }}"@endisset
                                            placeholder="{{ __('Name') }}">
                                    </section>
                                    <section class="col-xl-5 col-xs-12">
                                        <label>{{ __('Mobile Phone') }}</label>
                                        <input type="text" name="mobile_phone" id="mobile_phone" class="form-control"
                                            @isset($mphone)value="{{ $mphone }}" @endisset
                                            @if(null !== old('mobile_phone'))value="{{ old('mobile_phone') }}"@endisset
                                            placeholder="{{ __('Mobile Phone') }}">
                                    </section>
                                </div>
                                <div class="form-group row">
                                    <section class="col-xl-7 col-xs-12">
                                        <label>{{ __('E-mail address') }}</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            @isset($email)value="{{ $email }}" @endisset
                                            @if(null !== old('email'))value="{{ old('email') }}"@endisset
                                            placeholder="{{ __('E-mail address') }}">
                                    </section>
                                    <section class="col-xl-5 col-xs-12">
                                        <label>{{ __('Username') }}</label>
                                        <input type="text" name="username" id="username" class="form-control"
                                            @isset($username)value="{{ $username }}" @endisset
                                            @if(null !== old('username'))value="{{ old('username') }}"@endisset
                                            placeholder="{{ __('Username') }}">
                                    </section>
                                </div>
                                <div class="form-group row">
                                    <section class="col-xl-7 col-xs-12">
                                        <label>{{ __('Additional Information') }}</label>
<textarea name="additional_information" id="additional_information" class="form-control" placeholder="{{ __('Additional Information') }}" rows="10">
@isset($addinfo){{ $addinfo }} @elseif (null !== old('additional_information')) {{ old('additional_information') }} @endisset
</textarea>
                                    </section>
                                    <section class="col-xl-5 col-xs-12">
                                        <label>{{ __('Job') }}</label>
                                        <input type="text" name="job" id="job" class="form-control"
                                            @isset($job)value="{{ $job }}" @endisset
                                            @if(null !== old('job'))value="{{ old('job') }}"@endisset
                                            placeholder="{{ __('Job') }}">
                                    </section>
                                </div>
                                <div class="form-group row">
                                    <section class="col-xl-6 col-xs-12">
                                        <label>{{ __('Color of the team member') }}</label>
                                        <div class="asColorPicker-wrap">
                                            <input type="text" class="as_colorpicker form-control asColorPicker-input" name="color" id="color"
                                            @isset($color)value="{{ $color }}" @endisset
                                            @if(null !== old('color')) value="{{ old('color') }}"@endisset >
                                        </div>
                                    </section>
                                </div>
                                <div class="form-group row">
                                    <section class="col-xl-6 col-xs-12">
                                        <label>{{ __('Departmento') }}</label>
                                        @php
                                            $departamentos = \App\Models\Tenant\Departamentos::all();
                                        @endphp
                                         <select name="id_departamento" id="id_departamento" class="form-control">
                                            <option value="">{{ __('Selecione Departamento') }}</option>

                                            @forelse ($departamentos as $item)
                            
                                                <option value="{{ $item->id }}" @isset($iddepartamento) @if($iddepartamento != "") @if($item->id == $iddepartamento) selected @endif @endif @endisset >{{ $item->nome_departamento }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </section>
                                    <section class="col-xl-6 col-xs-12">
                                        <label>{{ __('Hierarquia') }}</label>
                                        @php
                                            $hierarquias = \App\Models\Tenant\Hierarquia::all();
                                        @endphp
                                         <select name="id_hierarquia" id="id_hierarquia" class="form-control">
                                            <option value="">{{ __('Selecione Hierarquia') }}</option>

                                            @forelse ($hierarquias as $item)
                                                <option value="{{ $item->id }}" @isset($iddepartamento) @if($idhierarquia != "") @if($item->id == $idhierarquia) selected @endif @endif @endisset >{{ $item->descricao }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </section>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-footer justify-content-between">
        <div class="row">
            <div class="col text-right">
                <a href="{{ route('tenant.team-member.index') }}" class="btn btn-secondary mr-2">{{
                    __('Back') }}
                    <span class="btn-icon-right"><i class="las la-angle-double-left"></i></span>
                </a>
                <button type="submit" style="border:none;background:none;">
                    <a type="submit" class="btn btn-primary"  role="button">
                        {{ $buttonAction }}
                        <span class="btn-icon-right"><i class="las la-check mr-2"></i></span>
                    </a>
                </button>
            </div>
        </div>
    </div>
</div>



</form>
@push('custom-scripts')
    <script>
         window.addEventListener('swal',function(e){
            swal(e.detail.title, e.detail.message, e.detail.status);
            restartObjects();
        });

        jQuery( document ).ready(function() {
            jQuery('body').on('change', '.asColorPicker-trigger span', function() {
            });
        });
       

    </script>
@endpush
