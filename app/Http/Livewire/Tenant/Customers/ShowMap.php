<?php

namespace App\Http\Livewire\Tenant\Customers;

use Livewire\Component;
use App\Models\Districts;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;
use App\Models\Tenant\CustomerLocations;
use App\Interfaces\Tenant\Customers\CustomersInterface;

class ShowMap extends Component
{
    private $customer_id;
    protected object $customersRepository;

    public function boot(CustomersInterface $interfaceCustomers)
    {
        $this->customersRepository = $interfaceCustomers;
    }

    public function mount($customer): void
    {
        $this->customer_id = $customer->no;

    }

    public function render(): View
    {
        $customerLocations = $this->customersRepository->getLocationsFromCustomerCollection($this->customer_id);

      
        return view('tenant.livewire.customers.show-map', [
            'customerLocations' => json_encode($customerLocations),
        ]);
    }


}
