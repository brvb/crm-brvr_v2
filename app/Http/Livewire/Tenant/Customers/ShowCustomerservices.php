<?php

namespace App\Http\Livewire\Tenant\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;
use App\Models\Tenant\CustomerServices;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;

class ShowCustomerservices extends Component
{
    use WithPagination;

    public int $perPage;
    public string $searchString = '';

    protected object $customerServices;
    public int $customer_id;

    protected object $customerServicesRepository;
    protected object $customersRepository;
    protected object $customerLocationRepository;

    public function boot(CustomersInterface $customers,CustomerServicesInterface $customerService,CustomerLocationsInterface $customerLocationInterface)
    {
        $this->customerServicesRepository = $customerService;
        $this->customersRepository = $customers;
        $this->customerLocationRepository =  $customerLocationInterface;
    }

    public function mount($customer)
    {
        $this->customer_id = $customer->no;
       
        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
        session()->put('perPage', $this->perPage);
    }

    public function updatedSearchString(): void
    {
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'tenant.livewire.setup.pagination';
    }

    public function render(): View
    {
        $customer = $this->customersRepository->getSearchedCustomerByNo($this->customer_id);
        $this->customerServices = $this->customerServicesRepository->getSearchedCustomerServiceWithFilterCostumer($customer->customers[0]->id,$this->searchString,$this->perPage);

     
        return view('tenant.livewire.customers.show-customerservices', [
            'customerServices' => $this->customerServices,
            'customer_id' => $this->customer_id,
            'customersRepository' => $this->customersRepository,
            'customerLocationRepository' => $this->customerLocationRepository
        ]);
    }
}
