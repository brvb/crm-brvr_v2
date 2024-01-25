<?php

namespace App\Http\Livewire\Tenant\Setup\Services;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tenant\Services;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UpdateTableService extends Component
{
    protected $listeners = ["refresh" => "refresh", "removeNew" => "removeNew"];

    use WithFileUploads;
    public int $files = 0;
    public int $idService = 0;

    public $fileUploaded;
    public int $countInputs = 0;

    public string $filePath = '';

    public ?array $arrayFile = NULL;

    public string $fileUpdate = '';

    public bool $update = false;

    public ?array $filesStored = NULL;

   
    public function mount($file,$update)
    {
        $this->countInputs = 0;
        $this->filePath = '';
        $this->fileUpdate = $file;
        $this->update = $update;
      
        if($this->fileUpdate != '')
        {
            foreach(json_decode($this->fileUpdate) as $count => $fl)
            {
                $this->filesStored[$count] =["fileFolder" => $fl->fileFolder,"ficheiro" => $fl->ficheiro, "size" => $fl->size];
            }
        }
        else 
        {
            $this->filesStored = [];
        }
        

    }

    
    public function updatedFileUploaded()
    {
        $this->countInputs++;
        
        if (Storage::exists(tenant('id') . '/app/servicos',$this->fileUploaded->getClientOriginalName())) {
            Storage::delete($this->fileUploaded);
        }

        if(!Storage::exists(tenant('id') . '/app/servicos'))
        {
            File::makeDirectory(storage_path('app/servicos'), 0755, true, true);
        }

        $this->filePath = $this->fileUploaded->storeAs(tenant('id') . '/app/servicos',$this->fileUploaded->getClientOriginalName());

        array_push($this->filesStored,["fileFolder" => $this->filePath,"ficheiro" => $this->fileUploaded->getClientOriginalName(), "size" => $this->fileUploaded->getSize()]);
      
    }

    public function remove($id)
    {
        unset($this->filesStored[$id]);
    }

    public function download($id)
    {
        $filename = $this->filesStored[$id];
        return response()->download(storage_path().'/../'.$filename["fileFolder"]);        
    }
     

    public function render()
    {
        return view('tenant.livewire.setup.services.update-table-service',['countInputs' => $this->countInputs, 'arrayFile' => $this->arrayFile, 'fileUpdate' => $this->fileUpdate, 'update'=> $this->update, 'filesStored' => $this->filesStored]);
    }
}
