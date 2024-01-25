<div class="table-responsive" wire:key="tenantteammembersshow">
    <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
        style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
        <div class="sk-three-bounce" style="background:none;">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <div class="container-fluid" style="padding-left:0px;padding-top:0px;">
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{__("Information")}}</h4>
                    </div>
                    <div class="card-body">
                            @if($files == null)
                                <h4>{{__("Select a Customer to check his info")}}</h4>
                            @else
                            <div class="row">
                                <!-- Começa aqui -->
                               <div class="container-fluid" style="padding-left:0px;padding-top:0px;">
                                <div class="default-tab">
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
                                                <a class="nav-link active" data-toggle="tab" href="#filesPanel"><i class="la la-file-text-o mr-2"></i> {{ __('Files') }}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#chatPanel"><i class="la la-comments-o mr-2"></i> {{ __('Chat') }}</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="filesPanel" role="tabpanel">
                                                <div class="container">
                                                    <!-- Starts Here files -->
                                                @if(isset($file) && $file != null)
                                                    @livewire('tenant.files-customer.update-table-files',['file' => $file,'update' => false, 'customer' => $customer])
                                                @else
                                                    @php
                                                        $file = '';
                                                    @endphp
                                                    @livewire('tenant.files-customer.update-table-files',['file' => $file,'update' => false, 'customer' => $customer])
                                                @endif
                                                
                                                    <input type="hidden" name="ReceiveValuesLivewire[]" id="ReceiveValuesLivewire">
                                        
                                                    <!-- Ends Here -->
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="chatPanel" role="tabpanel">
                                                <div class="container">
                                                    @livewire('tenant.files.chat',['customer' => $customer])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   </div>   
                                </div>
                                <!-- acaba aqui-->
                            </div>
                            @endif
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
      jQuery(document).ready(function() {
         jQuery("body").on("click","#customer",function(){
            Livewire.emit("FilesOfThisCustomer",jQuery(this).attr("data-id"));
         })
      });

      
   </script> 
@endpush
