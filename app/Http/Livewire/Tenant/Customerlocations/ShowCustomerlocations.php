<?php

namespace App\Http\Livewire\Tenant\Customerlocations;

use Livewire\Component;
use App\Models\Districts;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;
use App\Models\Tenant\CustomerLocations;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;

class ShowCustomerlocations extends Component
{
    use WithPagination;

    private object $customerLocations;
    public int $perPage;
    public string $searchString = '';

    protected object $customersLocationRepository;

    public function boot(CustomerLocationsInterface $interfaceCustomersLocation)
    {
        $this->customersLocationRepository = $interfaceCustomersLocation;
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

    public function updatedSearchString(): void
    {
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'tenant.livewire.setup.pagination';
    }

    /**
     * List informations of customer location
     *
     * @return View
     */
    public function render(): View
    {
        if(isset($this->searchString) && $this->searchString) {
            
            $this->customerLocations = $this->customersLocationRepository->getSearchedCostumerLocations($this->searchString,$this->perPage);
        } else {
            
            $this->customerLocations = $this->customersLocationRepository->getAllCostumerLocations($this->perPage);
        }

        // $districts  = tenancy()->central(function () {
        //     return Districts::all();
        // });

        $districts  = Districts::all();
       
        return view('tenant.livewire.customerlocations.show', [
            'customerLocations' => $this->customerLocations,
            'districts' => $districts
        ]);
    }
}
