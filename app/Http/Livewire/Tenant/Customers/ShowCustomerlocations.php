<?php

namespace App\Http\Livewire\Tenant\Customers;

use Livewire\Component;
use App\Models\Districts;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;
use App\Models\Tenant\CustomerLocations;
use App\Interfaces\Tenant\Customers\CustomersInterface;

class ShowCustomerlocations extends Component
{
    use WithPagination;

    public int $perPage;
    public string $searchString = '';

    private object $districts;
    private object $counties;
    private object $customer;
    public string $customer_id;

    protected object $customersRepository;

    public function boot(CustomersInterface $interfaceCustomers)
    {
        $this->customersRepository = $interfaceCustomers;
    }

    public function mount($customer): void
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
        $this->getDistricts();
        $this->resetPage();
        session()->put('perPage', $this->perPage);
    }

    public function updatedSearchString(): void
    {
        $this->getDistricts();
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'tenant.livewire.setup.pagination';
    }

    public function render(): View
    {
        $customerLocations = $this->customersRepository->getLocationsFromCustomer($this->customer_id,$this->perPage);


        return view('tenant.livewire.customers.show-customerlocations', [
            'customerLocations' => $customerLocations,
        ]);
    }

    private function getDistricts(): Void
     {
        // $this->districts = tenancy()->central(function () {
        //     return Districts::all();
        // });
        $this->districts = Districts::all();
    }

}
