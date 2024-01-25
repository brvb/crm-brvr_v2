<?php

namespace App\Http\Livewire\Tenant\Profile;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Tenant\Profile\ProfileInterface;

class Show extends Component
{
    use WithFileUploads;
    public $uploadFile;

    private int $updatedImage = 0;

    protected object $profileRepository;

    public function boot(ProfileInterface $interfaceProfile)
    {
        $this->profileRepository = $interfaceProfile;
    }

    public function mount()
    {
        $user = $this->profileRepository->getUser(Auth::user()->id);
        $this->uploadFile = $user->photo;

    }

    public function updatedUploadFile()
    {
        $this->updatedImage = 1;
    }
   
    public function storeImage()
    {
        if($this->uploadFile == null){
            $this->dispatchBrowserEvent('swal', ['title' => __('Image'), 'message' => __("You need to insert an image!"), 'status'=>'error']);
        }
        else {

            if(!Storage::exists(tenant('id') . '/app/profile'))
            {
                File::makeDirectory(storage_path('app/profile'), 0755, true, true);
            }

            $storedFile = $this->uploadFile->storeAs(tenant('id') . '/app/profile', $this->uploadFile->getClientOriginalName());

            $this->profileRepository->insertUser(Auth::user()->id, $storedFile);
            
            $this->dispatchBrowserEvent('swal', ['title' => __('Image'), 'message' => __("Image updated with success!"), 'status'=>'success']);

            $user = $this->profileRepository->getUser(Auth::user()->id);
            $this->uploadFile = $user->photo;
        }

    }

    public function render()
    {
        return view('tenant.livewire.profile.show',["uploadFile" => $this->uploadFile, "updatedImage" => $this->updatedImage]);
    }
}
