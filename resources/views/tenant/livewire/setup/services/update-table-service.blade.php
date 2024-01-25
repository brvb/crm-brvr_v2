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

{{-- 
    @if(isset($countInputs) && $countInputs >= 1 && $update == false)
        @foreach($arrayFile as $file)
            <input type="hidden" name="fileInput[]" id="fileInput" value={{$file["FolderPath"]}}>
        @endforeach
        <div class="table-responsive">
            <table class="table table-responsive-md">
                <thead>
                    <tr>
                        <th>Ficheiro</th>
                        <th>Tamanho</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arrayFile as $file)
                        <tr>
                        <td>{{$file["Name"]}}</td>
                        <td>{{$file["Size"]}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif --}}
    @if(isset($fileUpdate))
        <table id="dataTables-data" class="table table-responsive-lg mb-0 table-striped">
            <thead>
                <tr>
                    <th>{{__("File")}}</th>
                    <th>{{__("Size")}}</th>
                    <th>{{__("Action")}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($filesStored as $i => $file)
                    <tr data-id="{{$i}}">
                        <td>{{$file["ficheiro"]}}</td>
                        <td>{{$file["size"]}}&nbsp;KB</td>
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
                                 <button type="button" class="dropdown-item" wire:click="remove({{$i}})">{{__("Delete file")}}</button>
                                 <button type="button" class="dropdown-item" wire:click="download({{$i}})">{{__("Download file")}}</button>
                             </div>
                         </div>
                        </td>
                        <td colspan="3" style="display:none;">
                            <input type="hidden" name="fileFolder[]" value="{{$file["fileFolder"]}}" data-id="{{$i}}">
                            <input type="hidden" name="fileName[]" value="{{$file["ficheiro"]}}" data-id="{{$i}}">
                            <input type="hidden"  name="fileSize[]" value="{{$file["size"]}}" data-id="{{$i}}">
                        </td>
                        
                    </tr>
                    {{-- <tr style='display:none;'>
                        <td colspan="3" style="display:none;">
                            <input type="hidden" name="fileFolder[]" value="{{$file["fileFolder"]}}" data-id="{{$i}}">
                            <input type="hidden" name="fileName[]" value="{{$file["ficheiro"]}}" data-id="{{$i}}">
                            <input type="hidden"  name="fileSize[]" value="{{$file["size"]}}" data-id="{{$i}}">
                        </td>
                    </tr> --}}
                  
                @endforeach
            </tbody>
        </table>
    {{-- </div> --}}
    @endif
</div>
