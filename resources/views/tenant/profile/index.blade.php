<x-tenant-layout title="{{ __('Profile') }}" :themeAction="$themeAction" :status="$status" :message="$message">
    {{-- Content --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-9">
                <div class="page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Profile') }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Profile') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="profile card card-body px-3 pt-3 pb-0">
                                <div class="profile-head">
                                    <div class="photo-content">
                                        <div class="cover-photo"></div>
                                    </div>
                                    <div class="profile-info">
                                        <div class="profile-photo">
                                            {{-- @livewire('tenant.profile.show') --}}
                                            <div class="group-image" style="border-radius:50%">
                                                <img src="{!! global_asset('cl/'.Auth::user()->photo.'') !!}" class="img-fluid rounded-circle" alt>
                                            </div>
                                        </div>
                                        <div class="profile-details">
                                            <div class="profile-name px-3 pt-2">
                                                <h4 class="text-primary mb-0">{{Auth::user()->name}}</h4>
                                                @if(Auth::user()->type_user != "2")
                                                    <p>{{$teamMember->job}}</p>
                                                @endif

                                            </div>
                                            <div class="profile-email px-2 pt-2">
                                                <h4 class="text-muted mb-0">{{Auth::user()->email}}</h4>
                                                <p>Email</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                         <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="profile-tab">
                                        <div class="custom-tab-1">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    @if (Auth::user()->type_user != '2')
                                                        <a href="#about-me" data-toggle="tab" class="nav-link active">{{__("About me")}}</a>
                                                    @endif
                                                    
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#profile-settings" data-toggle="tab" class="nav-link  @if (Auth::user()->type_user == '2') active @endif">{{__("Setting")}}</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                @if (Auth::user()->type_user != '2')
                                                <div id="about-me" class="tab-pane fade @if(Auth::user()->type_user != '2') active show @endif">
                                                    <div class="profile-personal-info pt-3">
                                                        <h4 class="text-primary mb-4">{{__("Personal Information")}}</h4>
                                                        <form action="{{ route('tenant.profile.store') }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                        
                                                            <input type="hidden" name="idTeamMember" @if($teamMember->id != null) value="{{$teamMember->id}}" @endisset>
                                                            <input type="hidden" name="username" value="{{$teamMember->username}}">
                                                            <input type="hidden" name="user_id" @if($teamMember->user_id != null) value="{{$teamMember->user_id}}" @endisset>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label>{{ __("Name")}}</label>
                                                                    <input type="text" name="name" placeholder="{{ __("Name")}}" class="form-control" @if($teamMember->name != null) value="{{$teamMember->name}}" @endisset>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label>{{ __("Email") }}</label>
                                                                    <input type="email" name="email" placeholder="{{ __("Email") }}" class="form-control"  @if($teamMember->email != null) value="{{$teamMember->email}}" @endisset>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label>{{ __("Mobile Phone") }}</label>
                                                                    <input type="text" name="mobile_phone" placeholder="{{ __("Mobile Phone") }}" class="form-control"  @if($teamMember->mobile_phone != null) value="{{$teamMember->mobile_phone}}" @endisset>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label>{{__("Job")}}</label>
                                                                    <input type="text" name="job" placeholder="{{__("Job")}}" class="form-control" @if($teamMember->job != null) value="{{$teamMember->job}}" @endisset>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label>{{ __("Additional Information")}}</label>
                                                                    <textarea name="additional_information" id="additional_information" class="form-control" placeholder="{{ __("Additional Information")}}" rows="10">@if($teamMember->additional_information != null){{$teamMember->additional_information}}@endisset</textarea>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-6">
                                                                    <label>{{ __('Color of the team member') }}</label><br>
                                                                    <div class="asColorPicker-wrap">
                                                                        <input type="text" class="as_colorpicker form-control asColorPicker-input" name="color" id="color"
                                                                        @if($teamMember->color != null) value="{{ $teamMember->color }}" @endisset >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                          
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label>{{ __("Profile Image")}}</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" name="uploadFile" id="uploadFile" class="custom-file-input">
                                                                        <label class="custom-file-label">{{__("Choose file")}}</label>
                                                                    </div>
                                                                    <small>De preferência imagens 300x300</small>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <img src="" id="imagePreview" width="200">
                                                                </div>
                                                            </div>
                                                            <button type="submit" style="border:none;background:none;">
                                                                <a type="submit" class="btn btn-primary"  role="button">
                                                                    {{ __("Edit Personal information")}}
                                                                    <span class="btn-icon-right"><i class="las la-check mr-2"></i></span>
                                                                </a>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                @endif
                                                <div id="profile-settings" class="tab-pane fade @if(Auth::user()->type_user == '2') active show @endif">
                                                    <div class="pt-3">
                                                        <div class="settings-form">
                                                            <h4 class="text-primary">{{__("Account Setting")}}</h4>
                                                            <form action="{{ route('tenant.user-info.userinfo') }}" method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="user_id" @if(Auth::user()->id != null) value="{{Auth::user()->id}}" @endisset>
                                                                
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label>{{ __("Username") }}</label>
                                                                        <input type="text" name="username" placeholder="utilizador" class="form-control" value="{{Auth::user()->username}}">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>{{ __("Password") }}</label>
                                                                        <input type="password" name="password" placeholder="Password" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6"></div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>{{ __("Repeat Password") }}</label>
                                                                        <input type="password" name="repeatPassword" placeholder="{{__("Repeat Password")}}" class="form-control">
                                                                    </div>
                                                                </div>
                                                                @if (Auth::user()->type_user == 2)
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6">
                                                                            <label>{{ __("Profile Image")}}</label>
                                                                            <div class="custom-file">
                                                                                <input type="file" name="uploadFile" id="uploadFile" class="custom-file-input">
                                                                                <label class="custom-file-label">{{__("Choose file")}}</label>
                                                                            </div>
                                                                            <small>De preferência imagens 300x300</small>
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <img src="" id="imagePreview" width="200">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <button type="submit" style="border:none;background:none;">
                                                                    <a type="submit" class="btn btn-primary"  role="button">
                                                                        {{ __("Update Informations")}}
                                                                        <span class="btn-icon-right"><i class="las la-check mr-2"></i></span>
                                                                    </a>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('custom-scripts')
    <script>
        jQuery(document).ready(function (e){
            jQuery('#uploadFile').change(function(){
                let reader = new FileReader();

                reader.onload = (e) => {
                    console.log(e);
                    jQuery("#imagePreview").attr('src',e.target.result);
                    jQuery("#imagePreview").focus();
                }

                reader.readAsDataURL(this.files[0]);
            });
        })
    </script>
@endpush
</x-tenant-layout>
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

