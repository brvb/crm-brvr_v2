<?php

namespace App\Http\Livewire\Tenant\Customerservices;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant\Services;
use App\Models\Tenant\Customers;
use Illuminate\Contracts\View\View;
use App\Models\Tenant\CustomerServices;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;

class ShowCustomerServices extends Component
{
    use WithPagination;

    private object $customerServices;
    public int $perPage;
    public string $searchString = '';
    public string $userAction = '';

    protected $listeners = [
        'updatedSearch'
     ];

    protected object $customerServicesRepository;

    public function boot(CustomerServicesInterface $customerService)
    {
        $this->customerServicesRepository = $customerService;
    }

    public function mount(): void
    {
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

    public function addCustomerService()
    {
        $this->userAction = 'add';
    }

    // public function updatedSearch($string)
    // {
    //     dd($string);
    // }

    /**
     * Listar serviços
     *
     * @return View
     */
    public function render(): View
    {
       
        if($this->userAction == 'add') {
            return view('tenant.livewire.customerservices.add', [
                'customers' => Customers::all(),
                'services' => Services::all()
            ]);
        }

        
    
        if(isset($this->searchString) && $this->searchString) {
            $this->customerServices = $this->customerServicesRepository->getSearchedCustomerService($this->searchString,$this->perPage);
        } else {
            $this->customerServices = $this->customerServicesRepository->getAllCustomerServices($this->perPage);
        }


        return view('tenant.livewire.customerservices.show', [
            'customerServices' => $this->customerServices
        ]);
    }
}
