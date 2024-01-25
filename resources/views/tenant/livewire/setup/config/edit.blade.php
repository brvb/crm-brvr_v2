<div>
    <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
        style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
        <div class="sk-three-bounce" style="background:none;">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $homePanel }}" data-toggle="tab" href="#homePanel">
                <i class="fa fa-building-o" aria-hidden="true"></i> {{ __('Company') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $emailPanel }}" data-toggle="tab" href="#emailPanel">
                <i class="fa fa-envelope-o" aria-hidden="true"></i> {{ __('E-mail') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade {{ $homePanel }}" id="homePanel" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            <div class="basic-form">
                                <div class="row">
                                    <div class="col">
                                        <div class="row form-group">
                                            <section class="col">
                                                <label for="company_name">{{ __('Company Name') }}</label>
                                                <input type="text" name="company_name" id="company_name" class="form-control"
                                                value="@if(isset($config->company_name)) $config->company_name @endif" wire:model.lazy="company_name">
                                            </section>
                                        </div>
                                        <div class="row form-group">
                                            <section class="col-12 col-md-6 col-lg-3">
                                                <label for="vat">{{ __('VAT') }}</label>
                                                <input type="text" name="vat" id="vat" class="form-control"
                                                value="@if(isset($config->vat)) $config->vat @endif" wire:model.lazy="vat">
                                            </section>
                                            <section class="col-12 col-md-6 col-lg-3">
                                                <label for="contact">{{ __('Phone number') }}</label>
                                                <input type="text" name="contact" id="contact" class="form-control"
                                                value="@if(isset($config->contact)) $config->contact @endif" wire:model.lazy="contact">
                                            </section>
                                            <section class="col-12 col-md-6">
                                                <label for="email">{{ __('Primary e-mail address') }}</label>
                                                <input type="text" name="email" id="email" class="form-control"
                                                value="@if(isset($config->email)) $config->email @endif" wire:model.lazy="email">
                                            </section>
                                        </div>
                                        <div class="row form-group">
                                            <section class="col-12">
                                                <label for="address">{{ __('Address') }}</label>
                                                <textarea rows="4" type="text" name="address" id="address" class="form-control"  wire:model.lazy="address">@if(isset($config->address)) $config->address @endif</textarea>
                                            </section>
                                        </div>
                                        <div class="row ">
                                            <section class="col-12 col-lg-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-primary btn-sm" type="button">{{__("Image")}}</button>
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" name="uploadFile" id="uploadFile" class="custom-file-input" wire:model="uploadFile">
                                                        <label class="custom-file-label"  style="height:35px;paddin;white-space: nowrap;">@if(isset($image)) {{$image}} @else {{__('Choose file')}} @endif</label>
                                                    </div>
                                                </div>
                                            </section>
                                            <section class="col-12 col-lg-6">
                                                @if ($uploadFile)
                                                    <img src="{{ $uploadFile->temporaryUrl() }}" style="max-width:300px;padding-right: 1px; padding-left: 2px;">
                                                @elseif (isset($logotipo) && $logotipo)
                                                    <img src="{{ global_tenancy_asset('/app/public/images/logo/' . $logotipo) }}" style="max-width:300px;">
                                                @endif
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade {{ $emailPanel }}" id="emailPanel" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            <div class="basic-form">
                                <div class="row">
                                    <div class="col">
                                        <div class="row form-group">
                                            <section class="col-12 col-sm-6">
                                                <label for="senderEmail">{{ __('Sender e-mail') }}</label>
                                                <input type="text" name="senderEmail" id="senderEmail" class="form-control"
                                                value="@if(isset($config->sender_email)) $config->sender_email @endif" wire:model.lazy="sender_email">
                                            </section>
                                            <section class="col-12 col-sm-6">
                                                <label for="senderName">{{ __('Sender name') }}</label>
                                                <input type="text" name="senderName" id="sender_name" class="form-control"
                                                value="@if(isset($config->sender_name)) $config->sender_name @endif" wire:model.lazy="sender_name">
                                            </section>
                                        </div>
                                        <div class="row form-group">
                                            <section class="col-12 col-sm-6">
                                                <label for="sender_cc_email">{{ __('CC e-mail') }}</label>
                                                <input type="text" name="sender_cc_email" id="sender_cc_email" class="form-control"
                                                value="@if(isset($config->sender_cc_email)) $config->sender_cc_email @endif" wire:model.lazy="sender_cc_email">
                                            </section>
                                            <section class="col-12 col-sm-6">
                                                <label for="sender_cc_name">{{ __('CC name') }}</label>
                                                <input type="text" name="sender_cc_name" id="sender_cc_name" class="form-control"
                                                value="@if(isset($config->sender_cc_name)) $config->sender_cc_name @endif" wire:model.lazy="sender_cc_name">
                                            </section>
                                        </div>
                                        <div class="row form-group">
                                            <section class="col-12 col-sm-6">
                                                <label for="sender_bcc_email">{{ __('BCC e-mail') }}</label>
                                                <input type="text" name="sender_bcc_email" id="sender_bcc_email" class="form-control"
                                                value="@if(isset($config->sender_bcc_email)) $config->sender_bcc_email @endif" wire:model.lazy="sender_bcc_email">
                                            </section>
                                            <section class="col-12 col-sm-6">
                                                <label for="sender_bcc_name">{{ __('BCC name') }}</label>
                                                <input type="text" name="sender_bcc_name" id="sender_bcc_name" class="form-control"
                                                value="@if(isset($config->sender_bcc_name)) $config->sender_bcc_name @endif" wire:model.lazy="sender_bcc_name">
                                            </section>
                                        </div>
                                        <div class="row form-group">
                                            <section class="col-12 col-lg-6" wire:ignore>
                                                <label for="signature">{{ __('Signature') }}</label>
                                                <div class="summernote"></div>
                                            </section>
                                            <section class="col-12 col-lg-6">
                                                <label for="alert_email">{{ __("Email Alert")}}</label>
                                                <input type="text" name="alert_email" id="alert_email" class="form-control" wire:model.defer="alert_email">
                                               
                                                <button type="button" class="btn btn-primary mt-2 mb-2" wire:click="addEmail">{{__("Add Email")}}</button>
                                                
                                                <table id="dataTables-data" class="table table-responsive mb-0 table-striped" style="display:block;">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Email Alert')}}</th>
                                                            <th>{{ __("Action")}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       @foreach ($emailsStored as $countEmail => $emails )
                                                        <tr>
                                                            <td>{{$emails["email"]}}</td>
                                                            <td>
                                                                <div class="dropdown ml-auto text-right">
                                                                    <button class="btn btn-primary tp-btn-light sharp" type="button" data-toggle="dropdown">
                                                                     <span class="fs--1">
                                                                         <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                                             <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                 <rect x="0" y="0" width="24" height="24"></rect>
                                                                                 <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                                                 <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                                                 <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                                             </g>
                                                                         </svg>
                                                                     </span>
                                                                    </button>
                                                                     <div class="dropdown-menu dropdown-menu-left" style="width:fit-content;">                                   
                                                                        <button class="dropdown-item" wire:click="removeEmail({{$countEmail}})">
                                                                            {{ __('Delete Email') }}
                                                                        </button>
                                                                     </div>
                                                                 </div>
                                                            </td>
                                                            <td colspan="3">
                                                                <input type="hidden" name="emailsRequest[]" wire:model.defer="emailsRequest" value="{{$emails["email"]}}" data-id="{{$countEmail}}">
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                   
                                                    </tbody>
                                                </table>
                                            </section>

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
    <div class="card">
        <div class="card-footer justify-content-between">
            <div class="row">
                <div class="col text-right"  style="padding:0;">
                    <a wire:click="saveTask" class="btn btn-primary" style="padding-left: 11px;padding-right: 7px;max-width: 250px;">
                        {{ $actionButton }}
                        <span class="btn-icon-right"><i class="las la-check mr-2"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            restartObjects();
            jQuery("#alert_email").val("");
        });

        function restartObjects() {
            jQuery(document).ready(function() {
                var HelloButton = function (context) {
                    var ui = $.summernote.ui;

                    // create button
                    var button = ui.button({
                        contents: '<i class="fa fa-compress"/> Small',
                        tooltip: 'hello',
                        click: function () {
                            context.invoke('editor.pasteHTML', '<small>...</small>');
                        }
                    });

                    return button.render();   // return button as jquery object
                }

                jQuery(".summernote").summernote({
                    height: 190,
                    minHeight: null,
                    maxHeight: null,
                    focus: !1,
                    codeviewFilter: false,
                    codeviewIframeFilter: true,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['mybutton', ['hello']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                    ],
                    buttons: {
                        hello: HelloButton
                    },
                    fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana'],
                    addDefaultFonts: false,
                    callbacks: {
                        onChange: function(contents) {
                            @this.set('signature', contents, true);
                            console.log(contents)
                        }
                    },
                }), $(".inline-editor").summernote({
                    airMode: !0
                });


            });
        }
    </script>
    @endpush
</div>
