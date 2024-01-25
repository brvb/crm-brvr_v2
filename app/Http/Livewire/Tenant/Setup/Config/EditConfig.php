<?php

namespace App\Http\Livewire\Tenant\Setup\Config;

use Livewire\Component;
use App\Models\Tenant\Config;
use Livewire\WithFileUploads;
use App\Events\Alerts\AlertEvent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL;
use App\Models\Tenant\CustomerServices;
use Illuminate\Support\Facades\Validator;

class EditConfig extends Component
{
    use WithFileUploads;

    public string $homePanel = 'show active';
    public string $emailPanel = '';
    public string $cancelButton = '';
    public string $actionButton = '';

    public ?object $config = NULL;

    public string $company_name = '';
    public string $vat = '';
    public string $contact = '';
    public string $email = '';
    public string $address = '';
    public string $logotipo = '';
    public string $sender_email = '';
    public string $sender_name = '';
    public string $sender_cc_email = '';
    public string $sender_cc_name = '';
    public string $sender_bcc_email = '';
    public string $sender_bcc_name = '';
    public string $signature = '';
    public string $alert_email = '';
    public $uploadFile;
    public string $baseUrl = '';

    public $emailsRequest;

    public int $count = 0;

    public ?array $emailsStored = [];

    /**
     * Initialize livewire component
     *
     * @param [type] $taskToUpdate
     * @return Void
     */
    public function mount(): Void
    {
        $this->baseUrl = URL::to('/');
        $this->cancelButton = __('Back') . '<span class="btn-icon-right"><i class="las la-angle-double-left"></i></span>';
        $this->actionButton = __('Yes, update configuration');
        $this->config = Config::first();


        if($this->company_name == '') {
            $this->company_name = $this->config->company_name;
            $this->vat = $this->config->vat;
            $this->contact = $this->config->contact;
            $this->email = $this->config->email;
            $this->address = $this->config->address;
            $this->logotipo = $this->config->logotipo;
            $this->sender_email = $this->config->sender_email;
            $this->sender_name = $this->config->sender_name;
            $this->sender_cc_email = $this->config->sender_cc_email;
            $this->sender_cc_name = $this->config->sender_cc_name;
            $this->sender_bcc_email = $this->config->sender_bcc_email;
            $this->sender_bcc_name = $this->config->sender_bcc_name;
            $this->signature = $this->config->signature;
           // $this->alert_email = $this->config->alert_email;
        }

        if(json_decode($this->config->alert_email) != null)
        {
            foreach(json_decode($this->config->alert_email) as $count => $fl)
            {
                $this->emailsStored[$count] =["email" => $fl->email];
            }
        }
        else 
        {
            $this->emailsStored = [];
        }

    }

    public function updatedCompany_name()
    {
        $this->homePanel = 'show active';
        $this->emailPanel = '';
    }

    public function updatedVat()
    {
        $this->homePanel = 'show active';
        $this->emailPanel = '';
    }

    public function updatedContact()
    {
        $this->homePanel = 'show active';
        $this->emailPanel = '';
    }

    public function updatedEmail()
    {
        $this->homePanel = 'show active';
        $this->emailPanel = '';
    }

    public function updatedAddress()
    {
        $this->homePanel = 'show active';
        $this->emailPanel = '';
    }

    public function updatedSenderEmail()
    {
        $this->homePanel = '';
        $this->emailPanel = 'show active';
    }

    public function updatedSenderName()
    {
        $this->homePanel = '';
        $this->emailPanel = 'show active';
    }

    public function updatedSender_cc_email()
    {
        $this->homePanel = '';
        $this->emailPanel = 'show active';
    }

    public function updatedSender_cc_name()
    {
        $this->homePanel = '';
        $this->emailPanel = 'show active';
    }

    public function updatedSender_bcc_email()
    {
        $this->homePanel = '';
        $this->emailPanel = 'show active';
    }

    public function updatedSender_bcc_name()
    {
        $this->homePanel = '';
        $this->emailPanel = 'show active';
    }

    public function updatedUploadFile()
    {
        $this->validate([
            'uploadFile' => 'image|max:1024',
        ]);
    }

    public function saveTask()
    {
        $fileName = $this->config->logotipo;
        if($this->uploadFile) {
            $fileName = $this->uploadFile->getClientOriginalName();
            $this->uploadFile->storeAs(tenant('id') . '/app/public/images/logo', $this->uploadFile->getClientOriginalName());
        }
     

        $validator = Validator::make(
            [
                'company_name'  => $this->company_name,
                'vat' => $this->vat,
                'contact' => $this->contact,
                'email' => $this->email,
                'address' => $this->address,
                'sender_email' => $this->sender_email,
                'sender_name' => $this->sender_name,
            ],
            [
                'company_name'  => 'required',
                'vat'  => 'required',
                'contact'  => 'required',
                'email'  => 'required|email',
                'address'  => 'required',
                'sender_email'  => 'required|email',
                'sender_name'  => 'required',
            ],
            [
                'company_name'  => __('You must enter the company name!'),
                'vat' => __('You must enter the VAT!'),
                'contact' => __('You must enter the phone contact!'),
                'email' => __('You must enter the company e-mail!'),
                'address' => __('You must enter the address!'),
                'sender_email' => __('You must enter the sender e-mail!'),
                'sender_name' => __('You must enter the sender name!'),
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= $message . '<br>';
            }
            $this->dispatchBrowserEvent('swal', ['title' => __('Config'), 'message' => $errorMessage, 'status'=>'error']);
            return;
        }

        $c['company_name'] =  $this->company_name;
        $c['vat'] =  $this->vat;
        $c['contact'] =  $this->contact;
        $c['email'] =  $this->email;
        $c['address'] =  $this->address;
        $c['logotipo'] =  $fileName;
        $c['sender_email'] =  $this->sender_email;
        $c['sender_name'] =  $this->sender_name;
        $c['sender_cc_email'] =  $this->sender_cc_email;
        $c['sender_cc_name'] =  $this->sender_cc_name;
        $c['sender_bcc_email'] =  $this->sender_bcc_email;
        $c['sender_bcc_name'] =  $this->sender_bcc_name;
        $c['signature'] =  $this->signature;
        //$c['alert_email'] = $this->alert_email;
        $c['alert_email'] = json_encode($this->emailsStored);
        if($this->config === NULL) {
            Config::create($c);
        } else {
            Config::where('id',1)->update(
                $c
            );
        }

        $this->changed = false;
        $this->dispatchBrowserEvent('swal', ['title' => __('Config'), 'message' => __('The configuration was updated with success!'), 'status'=>'info']);
        $this->dispatchBrowserEvent('contentChanged');

    }

    public function addEmail()
    {
        $countEmails = 0;
        if($this->alert_email != ""){   
            foreach($this->emailsStored as $emails){
                if($emails["email"] == $this->alert_email){
                    $countEmails++;
                }
            }  

          
            $this->homePanel = '';
            $this->emailPanel = 'show active';

            if($countEmails == 0){
                array_push($this->emailsStored,["email" => $this->alert_email]);
                $this->alert_email = "";
            }
            else {
                $this->dispatchBrowserEvent('swal', ['title' => __('Config'), 'message' => __('That email already exists!'), 'status' => 'error']);
            }
        }
        
    }

    public function removeEmail($id)
    {
        unset($this->emailsStored[$id]);
        $this->homePanel = '';
        $this->emailPanel = 'show active';
    }

    /**
     * Returns the view of the task edit
     *
     * @return View
     */
    public function render(): View
    {
        return view('tenant.livewire.setup.config.edit',["emailsStored" => $this->emailsStored]);
    }

}
