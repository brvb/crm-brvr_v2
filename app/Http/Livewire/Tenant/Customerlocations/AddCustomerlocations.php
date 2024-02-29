<?php

namespace App\Http\Livewire\Tenant\Customerlocations;

use Livewire\Component;
use App\Models\Counties;
use App\Models\Districts;
use Livewire\WithPagination;
use App\Models\Tenant\Services;
use App\Models\Tenant\Customers;
use Illuminate\Contracts\View\View;

use App\Models\Tenant\CustomerServices;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;

class AddCustomerlocations extends Component
{
    use WithPagination;

    private object $customerServices;
    public int $perPage;
    public string $searchString = '';

    private object $customerList;
    public object $serviceList;
    public string $selectedCustomer = '';
    public string $selectedService = '';
    public $customer = '';
    public $service = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $type = '';

    public string $homePanel = 'show active';
    public string $locationPanel = '';
    public string $profile = '';

    public object $districts;
    public string $selectCust = '';
    public string $district = '';
    public string $description = '';
    public string $contact = '';
    public string $manager_name = '';
    public string $manager_contact = '';
    public string $address = '';
    public string $zipcode = '';
    public string $customerLocation = '';
    public object $counties;
    public string $county = '';
    public string $customer_id = '';
    public bool $changed = false;


    protected $listeners = ['resetChanges' => 'resetChanges'];

    protected array $rules = [
        'selectedCustomer' => 'required|min:1',
        'selectedService' => 'required|min:1',
    ];

    protected object $customersLocationRepository;

    public function boot(CustomerLocationsInterface $interfaceCustomersLocation)
    {
        $this->customersLocationRepository = $interfaceCustomersLocation;
    }

    public function mount($customerList): void
    {
        //$this->customerList = $customerList;
       
        $this->customerList = $this->customersLocationRepository->getAllCostumerLocationsCollection();

          
        if(old('description')) {
            $this->description = old('description');
        }
        if(old('contact')) {
            $this->contact = old('contact');
        }
        if (old('manager_name')) {
            $this->manager_name = old('manager_name');
        }
        if(old('manager_contact')) {
            $this->manager_contact = old('manager_contact');
        }
        if(old('address')) {
            $this->address = old('address');
        }
        if(old('zipcode')) {
            $this->zipcode = old('zipcode');
        }
        

       

        $this->districts = Districts::all();
        $this->counties = Counties::all();

        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }

    }

    // public function updatedPerPage(): void
    // {
    //     $this->resetPage();
    //     session()->put('perPage', $this->perPage);
    // }

    // public function updatedSearchString(): void
    // {
    //     $this->resetPage();
    // }

    // public function paginationView()
    // {
    //     return 'tenant.livewire.setup.pagination';
    // }

    public function updatedSelectedCustomer()
    {
        $this->customer = Customers::where('id', $this->selectedCustomer)->first();
        $this->homePanel = 'show active';
        $this->locationPanel = '';
        $this->profile = '';
        //$this->dispatchBrowserEvent('contentChanged');
    }

    public function updatedSelectedService()
    {
        $this->service = Services::where('id', $this->selectedService)->first();
        $this->homePanel = '';
        $this->locationPanel = 'show active';
        $this->profile = '';
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function resetChanges()
    {
        $this->selectCust = '';
        $this->district = '';
        $this->description = '';
        $this->contact = '';
        $this->manager_name = '';
        $this->manager_contact = '';
        $this->address = '';
        $this->zipcode = '';
        $this->customerLocation = '';
        $this->county = '';
        $this->customer_id = '';
    }

    public function updatedStartDate()
    {
        //$this->dispatchBrowserEvent('contentChanged2');
    }

    public function updatedEndDate()
    {
        //$this->dispatchBrowserEvent('contentChanged2');
    }

    
    // public function save(CustomerServices $CustomerServices)
    // {
    //     $validator = Validator::make(
    //         [
    //             'selectedCustomer'  => $this->selectedCustomer,
    //             'selectedService' => $this->selectedService,
    //         ],
    //         [
    //             'selectedCustomer'  => 'required|min:1',
    //             'selectedService' => 'required|min:1',
    //         ],
    //         [
    //             'selectedCustomer'  => __('You must select the customer!'),
    //             'selectedService' => __('You must select the service!'),
    //         ]
    //     );

    //     if ($validator->fails()) {
    //         $errorMessage = '';
    //         foreach($validator->errors()->all() as $message) {
    //             $errorMessage .= '<p>' . $message . '</p>';
    //         }
    //         $this->dispatchBrowserEvent('swal', ['title' => __('Services'), 'message' => $errorMessage, 'status'=>'error']);
    //     } else {
    //         $start_date = '1970/01/01';
    //         $end_date = '1970/01/01';
    //         if($this->start_date) {
    //             $start_date = $this->start_date;
    //         }
    //         if($this->end_date) {
    //             $end_date = $this->end_date;
    //         }
    //         $CustomerServices->fill([
    //             'customer_id' => $this->selectedCustomer,
    //             'service_id' => $this->selectedService,
    //             'location_id' => 0,
    //             'start_date' => $start_date,
    //             'end_date' => $end_date,
    //             'type' => $this->type,
    //             'last_date' => '1970/01/01'
    //         ]);
    //         $CustomerServices->save();
    //         return redirect()->route('tenant.services.index')
    //             ->with('message', __('Service created with success!'))
    //             ->with('status', 'sucess');
    //     }

    // }

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
                'confirmButtonText' => __('Yes, loose changes!'),
                'cancellButtonText' => __('No, keep changes!'),
            ]);
        }

    }

    public function render(): View
    {
        $this->changed = false;
        if(isset($this->searchString) && $this->searchString) {
            $this->customerServices = CustomerServices::where('name', 'like', '%' . $this->searchString . '%')
                ->with('customer')
                ->with('service')
                ->paginate($this->perPage);
        } else {
            $this->customerServices = CustomerServices::with('customer')
                ->with('service')
                ->paginate($this->perPage);
        }

         
        return view('tenant.livewire.customerlocations.add', [
            'customerServices' => $this->customerServices,
            'customerList' => $this->customerList,
            'customer' => $this->customer,
            'service' => $this->service,
            'selectedCustomer' => $this->selectedCustomer,
            'selectedService' => $this->selectedService,
            'homePanel' => $this->homePanel,
            'locationPanel' => $this->locationPanel,
            'profile' => $this->profile,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'type' => $this->type,
            'districts' => $this->districts,
            'district' => $this->district,
            'counties' => $this->counties,
            'county' => $this->county,
            'customerLocation' => $this->customerLocation
        ]);
    }

}
