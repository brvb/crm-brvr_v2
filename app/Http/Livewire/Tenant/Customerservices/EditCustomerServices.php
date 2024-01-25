<?php

namespace App\Http\Livewire\Tenant\Customerservices;

use Livewire\Component;
use App\Models\Tenant\Services;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TeamMember;

use Illuminate\Contracts\View\View;
use SebastianBergmann\Type\VoidType;
use App\Models\Tenant\CustomerServices;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Support\Facades\Validator;

class EditCustomerServices extends Component
{
    public object $service;
    public object $customer;
    public string $start_date = '';
    public string $end_date = '';
    public string $new_date = '';
    public string $type = '';
    public int $alert = 0;
    public object $customerList;
    public $selectedCustomer = NULL;
    public object $serviceList;
    public string $selectedService = '';
    public $customerLocations;
    public string $selectedLocation = '';
    private bool $updatedFields = false;

    public int $memberAssociated;
    public object $memberList;

    public string $selectedTypeContract = '';
    public int $number_times = 0;
    public int $allMails = 0;

    protected $listeners = ['resetChanges' => 'resetChanges', 'keepChanges' => 'keepChanges'];

    public function mount($service): void
    {
        $this->initProperties($service);
    }

    public function updatedSelectedService()
    {
        $this->updatedFields = true;
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function updatedStartDate()
    {
        $this->updatedFields = true;
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function updatedEndDate()
    {
        $this->updatedFields = true;
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function keepChanges()
    {
        $this->updatedFields = true;
    }

    public function updatedType()
    {
        $this->updatedFields = true;
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function resetChanges()
    {
        $this->initProperties($this->service);
    }

    public function cancel(): Void
    {
        $this->skipRender();

        // if($this->updatedFields === true) {
            $this->dispatchBrowserEvent('swal', [
                'title' => __('Customer Location'),
                'message' => __('You will loose all of the changes:'),
                'status' => 'warning',
                'page' => 'edit',
                'confirm' => 'true',
                'confirmButtonText' => __('Yes, loose changes!'),
                'cancellButtonText' => __('No, keep changes!'),
            ]);
        // }
    }

    public function save(CustomerServices $customerServices): Void
    {
        $validator = Validator::make(
            [
                'selectedCustomer'  => $this->selectedCustomer,
                'selectedLocation' => $this->selectedLocation,
                'selectedService' => $this->selectedService,
            ],
            [
                'selectedCustomer'  => 'required|min:1',
                'selectedLocation' => 'required|min:1',
                'selectedService' => 'required|min:1',
            ],
            [
                'selectedCustomer'  => __('You must select the customer!'),
                'selectedLocation' => __('You must select the customer location!'),
                'selectedService' => __('You must select the service!'),
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= '<p>' . $message . '</p>';
            }
            $this->dispatchBrowserEvent('swal', ['title' => __('Services'), 'message' => $errorMessage, 'status'=>'error']);
        } else {
            $start_date = '1970/01/01';
            $end_date = '1970/01/01';
            if($this->start_date) {
                $start_date = $this->start_date;
            }
            if($this->end_date) {
                $end_date = $this->end_date;
            }
            $customerServices->where('id', $this->service->id)
                ->update([
                    'customer_id' => $this->selectedCustomer,
                    'service_id' => $this->selectedService,
                    'location_id' => $this->selectedLocation,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'type' => $this->type,
                ]);
            $this->dispatchBrowserEvent('swal', ['title' => __('Customer Service'), 'message' => __('Customer service updated with success!'), 'status'=>'info']);
        }
    }

    public function render(): View
    {
        return view('tenant.livewire.customerservices.edit');
    }

    private function initProperties($service): void
    {
        $this->service = $service;
        if($service)
        $this->customer = Customers::where('id', $service->customer_id)->with('customerCounty')->with('customerDistrict')->first();
        $this->serviceList = Services::get();
        $this->customerList = Customers::get();
        $this->customerLocations = CustomerLocations::where('customer_id', $service->customer_id)->get();
        $this->selectedCustomer = $service->customer_id;
        if($service->service_id) {
            $this->selectedService = $service->service_id;
        }
        if($service->location_id) {
            $this->selectedLocation = $service->location_id;
        }
        if($service->type !== NULL) {
            $this->type = $service->type;
        } else {
            $this->type = '';
        }
        if($service->start_date != '1970-01-01') {
            $this->start_date = $service->start_date;
        }
        if($service->end_date != '1970-01-01') {
            $this->end_date = $service->end_date;
        }
        if($service->new_date != '1970-01-01') {
            $this->new_date = $service->new_date;
        }

        if($this->selectedTypeContract != null){
            $this->selectedTypeContract = $service->selectedTypeContract;
        }

        if($this->number_times != null){
            $this->number_times = $service->number_times;
        }

        if($this->allMails != null) {
            $this->allMails = $service->allMails;
        }

        if($service->memberAssociated != null) {
            $this->memberAssociated = $service->member_associated;
        }

        $this->memberList = TeamMember::get();

    }

}
