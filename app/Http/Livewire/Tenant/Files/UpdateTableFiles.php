<?php

namespace App\Http\Livewire\Tenant\Files;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Events\ChatMessage;
use App\Models\Tenant\Files;
use Livewire\WithFileUploads;
use App\Models\Tenant\Customers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Events\ChatEmail\ChatEmailEvent;
use App\Interfaces\Tenant\Files\FilesInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\AlertMessage\AlertMessageInterface;

class UpdateTableFiles extends Component
{
    protected $listeners = ["refresh" => "refresh", "removeNew" => "removeNew", 'FilesUpdatedFromMembers', 'importance', 'FilesUpdateAfterEvent'];

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


    protected object $alertRepository;
    protected object $filesRepository;

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

        $this->dispatchBrowserEvent('swalModalQuestion', ['title' => __('Files'), 'message' => $message, 'status'=>'info', 'function' => 'importance']);  
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

    public function FilesUpdateAfterEvent()
    {
        $this->filesDatabase = Files::where('customer_id',$this->customer_id)->get();
    }

    public function importance($anwser)
    {
         $filesNew = [];

         $this->filePath = $this->fileUploaded->storeAs(tenant('id') . '/app/files',$this->fileUploaded->getClientOriginalName());

         array_push($filesNew,["fileFolder" => $this->filePath,"ficheiro" => $this->fileUploaded->getClientOriginalName(), "size" => $this->fileUploaded->getSize()]);

         $this->filesRepository->addToDatabase($filesNew,$this->customer_id,$anwser);
                 
         $this->filesDatabase = Files::where('customer_id',$this->customer_id)->get();

         //Manda notificação
         $this->alertRepository->SendNotification(Auth::user()->id,$this->customer_id,"file");
         event(new ChatMessage());

         if(Auth::user()->type_user != 2)
         {
             $userOfCustomer = Customers::where('id',$this->customer_id)->first();

             $userResult = User::where('id',$userOfCustomer->user_id)->first();

             $startDate = Carbon::parse($userResult->last_seen);
             $endDate = Carbon::parse(date('Y-m-d H:i:s'));

             $diff = $startDate->diff($endDate);

             $number_minutes = $diff->days * 24 * 60;
             $number_minutes += $diff->h * 60;
             $number_minutes += $diff->i;

            if($startDate == $endDate || $number_minutes <= 30){
            }
            else {
              event(new ChatEmailEvent($this->customer_id));
            }
        }

    }
     
    public function dehydrate()
    {
        $this->dispatchBrowserEvent('initSomething');
    }

    public function render()
    { 
        return view('tenant.livewire.files.update-table-files',['countInputs' => $this->countInputs, 'arrayFile' => $this->arrayFile, 'fileUpdate' => $this->fileUpdate, 'update'=> $this->update, 'filesStored' => $this->filesStored, 'filesDatabase' => $this->filesDatabase, 'customer' => $this->customer_id]);
    }
}
