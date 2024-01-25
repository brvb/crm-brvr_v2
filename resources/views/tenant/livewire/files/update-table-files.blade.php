<div>
    
    <div class="input-group">
        <div class="input-group-prepend">
            <button class="btn btn-primary btn-sm" type="button">{{__("File")}}</button>
        </div>
        <div class="custom-file">
            <input type="file" wire:model="fileUploaded" class="custom-file-input">
            <label class="custom-file-label">@if(isset($file)) {{$file}} @else {{__('Choose file')}} @endif</label>
        </div>
    </div>
    <input type="hidden" id="selectedCustomer" value={{$customer}}>
    @if(isset($fileUpdate))
    <div class="mt-4" style="max-height:400px; border:1px solid #326c91 ; overflow:scroll; overflow-x: hidden;">
        <table id="dataTables-data" class="table table-responsive-lg mb-0 table-striped" style="min-width:0px!important;">
            <thead>
                <tr>
                    <th>{{__("File")}}</th>
                    <th>{{__("Size")}}</th>
                    <th>{{__("Creation Date")}}</th>
                    <th>{{__("Send By")}}</th>
                    <th>{{__("Action")}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filesDatabase as $ct => $fileDat )
                    @foreach (json_decode($fileDat->file) as $i => $file)
                   
                    <tr data-id="{{$i}}">
                        <td>{{$file->ficheiro}}</td>
                        <td>{{$file->size}}&nbsp;KB</td>
                        <td>{{$fileDat->created_at}}</td>
                        <td>
                            @php
                                $user = \App\Models\User::where('id',$fileDat->user_id)->first();
                            @endphp
                            {{$user->name}}
                        </td>
                        <td>
                            <div class="dropdown ml-auto text-left">
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
                             <div class="dropdown-menu dropdown-menu-right">
                                 <button type="button" class="dropdown-item" wire:click="remove({{$fileDat->id}})">{{__("Delete file")}}</button>
                                 <button type="button" class="dropdown-item" wire:click="download({{$fileDat->id}})">{{__("Download file")}}</button>
                             </div>
                         </div>
                        </td>
                                              
                    </tr>
                                     
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- </div> --}}
    @endif

       
   <script>
     document.addEventListener('initSomething', function () {
    

        jQuery("#customer"+jQuery("#selectedCustomer").val()).addClass("active");
            

     });

    window.addEventListener('swalModalQuestion',function(e){
      
            if(e.detail.confirm) {
                swal.fire({
                    title: e.detail.title,
                    html: e.detail.message,
                    type: e.detail.status,
                    showCancelButton: true,
                    showconfirmButton: false,
                    confirmButtonColor: '#d33',
                    confirmButtonText: e.detail.confirmButtonText,
                    cancelButtonText: e.detail.cancellButtonText,
                   
                })
                // .then((result) => {
                //     console.log("pressionou OK")
                //     if(result.value) {
                    
                //     }
                // });
            } else {
                swal(e.detail.title, e.detail.message, e.detail.status);
            }
            jQuery(".swal2-confirm").css("display","none");

            jQuery("body").on("click","#buttonresponse",function(){
                var response = jQuery(this).attr("data-anwser");
                window.livewire.emit("importance",response);
                swal.closeModal();
            })
    });
    </script> 

</div>
