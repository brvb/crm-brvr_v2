<div>
<style>
 .container-image {
    position: relative;  
    width:100%;
    max-width:100px;
}
.container-image:before {
  content:"";
  position:absolute;
  width:100%;
  height:100%;
  top:0;left:0;right:0;
  background-color:rgba(0,0,0,0);
}
.container-image:hover::before {
  background-color:rgba(0,0,0,0.5);
  z-index:9999999;
}
.container-image img {
  display:block;
}
.container-image .custom-file{
  position: absolute;
  top: 60%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  opacity:0;
} 
.container-image:hover .custom-file {   
  opacity: 1;
}

</style>

<div class="group-image" style="border-radius:50%;">
    @if($updatedImage == 1)
    <div class="container-image" style="border-radius:50px;overflow:hidden;width:70px;min-width:100%;">
        <img src="{{ $uploadFile->temporaryUrl() }}" class="img-fluid rounded-circle" alt style="position:relative;width:100%;display: block;height: auto;">

        <div class="custom-file" style="z-index: 1111111111111;">
            <input type="file" name="uploadFile" id="uploadFile" class="custom-file-input" wire:model="uploadFile">
            <label class="custom-file-label" style="display:none;"></label>
            <label style="position: absolute;left: 40%;color: white;bottom: 10%;width: fit-content;"><i class="fa fa-upload"></i></label>
        </div> 
    </div>
    @else
    
    <div class="container-image" style="border-radius:50px;overflow:hidden;width:70px;min-width:100%;">
        @if($uploadFile == null)
            <img src="{!! global_asset('assets/resources/images/avatar/1.png') !!}" class="img-fluid rounded-circle" alt style="position:relative;width:100%; display:block; height:auto;">
        @else
            <img src="{!! global_asset('cl/'.$uploadFile.'') !!}" class="img-fluid rounded-circle" alt style="position:relative;width:100%; display: block;height: auto;">
        @endif
        <div class="custom-file" style="z-index: 1111111111111;">
            <input type="file" name="uploadFile" id="uploadFile" class="custom-file-input" wire:model="uploadFile">
            <label class="custom-file-label" style="display:none;"></label>
            <label style="position: absolute;left: 40%;color: white;bottom: 10%;width: fit-content;"><i class="fa fa-upload"></i></label>
        </div> 
    </div>
         
    @endif
    <div class="stuff">
        <button type="button" class="btn-xs btn-primary mt-1" wire:click="storeImage">{{__("Store")}}</button>
    </div>
</div>
</div>
