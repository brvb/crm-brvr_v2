<?php

namespace App\Http\Livewire\Tenant\FilesCustomer;

use Livewire\Component;
use App\Events\ChatMessage;
use App\Models\Tenant\Files;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Tenant\Files\FilesInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\AlertMessage\AlertMessageInterface;

class UpdateTableFiles extends Component
{
    protected $listeners = ["refresh" => "refresh", "removeNew" => "removeNew", 'FilesUpdatedFromMembers', 'importance','FilesUpdateAfterEvent'];

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

    public ?int $customer_id = NULL;

    public ?object $filesDatabase = NULL;


   
    protected object $filesRepository;
    protected object $alertRepository;

    public function boot(FilesInterface $interfaceFiles, AlertMessageInterface $alertInterface)
    {
        $this->filesRepository = $interfaceFiles;
        $this->alertRepository = $alertInterface;
    }
    
    public function mount($file,$update,$customer)
    {
        $this->countInputs = 0;
        $this->filePath = '';
        $this->fileUpdate = $file;
        $this->update = $update;
        $this->customer_id = $customer;
      
        if($this->fileUpdate != '')
        {
            $this->filesDatabase = Files::where('customer_id',$this->customer_id)->get();
        }
        
        if($this->fileUpdate == "[]")
        {
            $this->filesStored = [];
        }

        
    }
    public function FilesUpdatedFromMembers($postId,$customerId)
    {
        $this->fileUpdate = $postId;
        $this->customer_id = $customerId;

        if($this->fileUpdate != '')
        {
            $this->filesDatabase = Files::where('customer_id',$this->customer_id)->get();
        }
        
        if($this->fileUpdate == "[]")
        {
            $this->filesStored = [];
        }

    }

        
    public function updatedFileUploaded()
    {
        $this->countInputs++;
        $filesNew = [];
        
        if (Storage::exists(tenant('id') . '/app/files',$this->fileUploaded->getClientOriginalName())) {
            Storage::delete($this->fileUploaded);
        }

        if(!Storage::exists(tenant('id') . '/app/files'))
        {
            File::makeDirectory(storage_path('app/files'), 0755, true, true);
        }

        $message = "Permite ao cliente remover este ficheiro?";
        $message .= "<br><div class='row mt-4' style='justify-content:center;'><button type='button' id='buttonresponse' data-anwser='ok' class='btn btn-primary'>Sim</button>";
        $message .= "&nbsp;<button type='button' id='buttonresponse' data-anwser='cancel' class='btn btn-secondary'>Não</button></div>";

         $this->filePath = $this->fileUploaded->storeAs(tenant('id') . '/app/files',$this->fileUploaded->getClientOriginalName());

         array_push($filesNew,["fileFolder" => $this->filePath,"ficheiro" => $this->fileUploaded->getClientOriginalName(), "size" => $this->fileUploaded->getSize()]);

         $this->filesRepository->addToDatabase($filesNew,$this->customer_id,"customer");
        
         $this->filesDatabase = Files::where('customer_id',$this->customer_id)->get();    
         
          //Manda notificação
          $this->alertRepository->SendNotification(Auth::user()->id,$this->customer_id,"file");
          event(new ChatMessage());
    }

    public function FilesUpdateAfterEvent()
    {
        $this->filesDatabase = Files::where('customer_id',$this->customer_id)->get();
    }

    public function remove($id)
    {
        $this->filesRepository->removeFileFromCustomer($id);
        $this->filesDatabase = Files::where('customer_id',$this->customer_id)->get();

        $this->alertRepository->SendNotification(Auth::user()->id,$this->customer_id,"file");
        event(new ChatMessage());
    }

    public function download($id)
    {
        
        $filename = Files::where('id',$id)->first();
        $filenameDecoded = json_decode($filename->file);
        return response()->download(storage_path().'/../'.$filenameDecoded[0]->fileFolder);        
    }
    

    public function render()
    {   
        return view('tenant.livewire.filescustomer.update-table-files',['countInputs' => $this->countInputs, 'arrayFile' => $this->arrayFile, 'fileUpdate' => $this->fileUpdate, 'update'=> $this->update, 'filesStored' => $this->filesStored, 'filesDatabase' => $this->filesDatabase]);
    }
}
