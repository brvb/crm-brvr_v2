<?php

namespace App\Http\Livewire\Tenant\Customerlocations;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\View;
use App\Models\Tenant\CustomerLocations;
use App\Models\Counties;
use App\Models\Districts;
use Illuminate\Validation\Validator as ValidationValidator;

class EditCustomerlocations extends Component
{
    public object $customerList;
    public object $customerLocation;
    public int $selectedCustomer;

    public object $districts;
    public object $counties;

    public string $description = '';
    public string $contact = '';
    public string $manager_name = '';
    public string $manager_contact = '';
    public string $address = '';
    public string $zipcode = '';
    public string $main = '';
    public bool $changed;
    private bool $doRender = true;
    private string $function = '';

    protected $listeners = ['resetChanges' => 'resetChanges'];

    public function mount($customerList, $customerLocation): void
    {
        $this->changed = false;
        $this->initProperties($customerList, $customerLocation);
    }

    /**
     * store informations of the customer Locations
     *
     * @param CustomerLocations $customerLocations
     * @return void
     */
    // public function save(CustomerLocations $customerLocations): void
    // {
    //     $errorMessage = $this->validateInput();

    //     if ($errorMessage !== False) {
    //         $this->dispatchBrowserEvent('swal', ['title' => __('Services'), 'message' => $errorMessage, 'status'=>'error']);
    //     } else {
    //         $customerLocations->where('id', $this->customerLocation->id)
    //             ->update([
    //                 'description' => $this->description,
    //                 'address' => $this->address,
    //                 'zipcode' => $this->zipcode,
    //                 'district_id' => $this->district,
    //                 'county_id' => $this->county,
    //                 'manager_name' => $this->manager_name,
    //                 'manager_contact' => $this->manager_contact,
    //                 'contact' => $this->contact,
    //             ]);
    //         $this->dispatchBrowserEvent('swal', ['title' => __('Customer Location'), 'message' => __('Customer location updated with success!'), 'status'=>'info']);
    //     }
    // }

    /**
     * Don t save the informations and returns the same information
     *
     * @return void
     */
    public function cancel() : void
    {
        $this->skipRender();
        $this->changed = true;
        if($this->changed === true) {
            $this->dispatchBrowserEvent('swal', [
                'title' => __('Customer Location'),
                'message' => __('You will loose all of the changes:'),
                'status' => 'warning',
                'confirm' => 'true',
                'page' => "edit",
                'customer_id' => $this->customerLocation->customer_id,
                'confirmButtonText' => __('Yes, loose changes!'),
                'cancellButtonText' => __('No, keep changes!'),
            ]);
        }
    }

    public function resetChanges()
    {
        $this->initProperties($this->customerList, $this->customerLocation);
        $this->changed = false;
    }

    public function like()
    {
        dd($this);
    }

    public function saveas()
    {
        if($this->changed === true) {
            $this->initProperties($this->customerList, $this->customerLocation);
        }
    }

    public function updatedDescription()
    {
        $this->changed = true;
    }

    public function render(): View
    {
        $id_customerLocation = $this->customerLocation;
        return view('tenant.livewire.customerlocations.edit',compact('id_customerLocation'));
    }

    private function initProperties($customerList, $customerLocation): void
    {
        
        $this->customerList = $customerList;
        $this->customerLocation = $customerLocation;
        $this->selectedCustomer = $customerLocation->customer_id;



        $this->description = $customerLocation->description;
        $this->contact = $customerLocation->contact;
        $this->main = $customerLocation->main;
        $this->manager_name = $customerLocation->manager_name;
        $this->manager_contact = $customerLocation->manager_contact;
        $this->address = $customerLocation->address;
        $this->zipcode = $customerLocation->zipcode;
        $this->district = $customerLocation->district_id;
        $this->county = $customerLocation->county_id;

        // $this->districts = tenancy()->central(function () {
        //     return Districts::all();
        // });
        $this->districts = Districts::all();

        $this->counties = tenancy()->central(function () use($customerLocation) {
            return Counties::where('district_id', $customerLocation->district_id)->get();
        });

        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }
    }

    private function validateInput(): Bool | String
    {
        $validator = Validator::make(
            [
                'selectedCustomer'  => $this->selectedCustomer,
                'description' => $this->description,
                'contact' => $this->contact,
                'manager_name' => $this->manager_name,
                'address' => $this->address,
                'zipcode' => $this->zipcode,
                'district' => $this->district,
                'county' => $this->county,
            ],
            [
                'selectedCustomer'  => 'required|min:1',
                'description' => 'required|min:3',
                'contact' => 'required|min:9',
                'manager_name' => 'required|min:3',
                'address' => 'required|min:3',
                'zipcode' => 'required|min:8',
                'district' => 'required|min:1',
                'county' => 'required|min:1',
            ],
            [
                'selectedCustomer'  => __('You must select the customer!'),
                'description' => __('You must enter the location description!'),
                'contact' => __('You must enter the phone contact of the location!'),
                'manager_name' => __('You must enter the manager name of the location!'),
                'address' => __('You must enter the address of the location!'),
                'zipcode' => __('You must enter the zip code of the location!'),
                'district' => __('You must enter the district of the location!'),
                'county' => __('You must enter the county of the location!'),
            ]
        );

        $errorMessage = False;
        if ($validator->fails()) {
            $errorMessage = '<p>';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= $message . '</br>';
            }
            $errorMessage .= '</p>';
        }
        return $errorMessage;

    }

}
