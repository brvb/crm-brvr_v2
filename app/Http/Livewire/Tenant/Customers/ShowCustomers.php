<?php

namespace App\Http\Livewire\Tenant\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant\Customers;
use Illuminate\Contracts\View\View;
use App\Interfaces\Tenant\Customers\CustomersInterface;

class ShowCustomers extends Component
{
    use WithPagination;

    private ?object $customers = NULL;
    public int $perPage = 0;
    public string $searchString = '';

    public ?string $nomecustomer = '';
    public ?string $nifcustomer = '';
    public ?string $contactocustomer = '';

    public int $changedFilter = 0;

    protected object $customersRepository;

    public function boot(CustomersInterface $interfaceCustomers)
    {
        $this->customersRepository = $interfaceCustomers;
    }

    public function mount(): void
    {
        if (isset($this->perPage)) {
            $this->perPage = 10;
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

    // public function updatedSearchString(): void
    // {
    //     $this->resetPage();
    // }

    public function updatedNomeCustomer(): void
    {
        $this->changedFilter = 1;
    }

    public function updatedNifCustomer(): void
    {
        $this->changedFilter = 1;
    }

    public function updatedContactoCustomer(): void
    {
        $this->changedFilter = 1;
    }

    public function clearFilter()
    {
        $this->changedFilter = 0;
        $this->nomecustomer = ""; 
        $this->nifcustomer = ""; 
        $this->contactocustomer = "";      
    }

    // public function searchFilter()
    // {
    //     $this->changedFilter = 1;
    //     $this->customers = $this->customersRepository->getSearchedCustomer($this->nomecustomer,$this->nifcustomer,$this->contactocustomer,$this->perPage);
    //     $this->nomecustomer = ""; 
    //     $this->nifcustomer = ""; 
    //     $this->contactocustomer = ""; 
    // }
    
    

    public function paginationView()
    {
        return 'tenant.livewire.setup.pagination';
    }

    public function render(): View
    {
        if($this->changedFilter == 0)
        {
            $this->customers = $this->customersRepository->getAllCustomers($this->perPage);
        }


        if($this->changedFilter == 1)
        {
            $this->customers = $this->customersRepository->getSearchedCustomer($this->nomecustomer,$this->nifcustomer,$this->contactocustomer,$this->perPage);
            
            if($this->nomecustomer == "" && $this->nifcustomer == "" && $this->contactocustomer == "")
            {
                $this->customers = $this->customersRepository->getAllCustomers($this->perPage);
            }
        }
       
        return view('tenant.livewire.customers.show-customers', [
            'customers' => $this->customers
        ]);
    }
}
