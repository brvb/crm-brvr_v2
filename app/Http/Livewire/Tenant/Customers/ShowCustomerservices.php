<?php

namespace App\Http\Livewire\Tenant\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;
use App\Models\Tenant\CustomerServices;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;

class ShowCustomerservices extends Component
{
    use WithPagination;

    public int $perPage;
    public string $searchString = '';

    protected object $customerServices;
    public int $customer_id;

    protected object $customerServicesRepository;

    public function boot(CustomerServicesInterface $customerService)
    {
        $this->customerServicesRepository = $customerService;
    }

    public function mount($customer): void
    {
        if(!is_integer($customer)) {
            $this->customer_id = $customer->id;
        } else {
            $this->customer_id = $this->customer;
        }

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
        $this->customerServices = $this->customerServicesRepository->getSearchedCustomerServiceWithFilterCostumer($this->customer_id,$this->searchString,$this->perPage);
       // $this->customerServices = CustomerServices::where('customer_id', $this->customer_id)->with('service')->paginate($this->perPage);
        return view('tenant.livewire.customers.show-customerservices', [
            'customerServices' => $this->customerServices,
            'customer_id' => $this->customer_id,
        ]);
    }
}
