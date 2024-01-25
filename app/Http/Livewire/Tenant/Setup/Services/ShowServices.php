<?php

namespace App\Http\Livewire\Tenant\Setup\Services;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant\Services;
use App\Interfaces\Tenant\Setup\Services\ServicesInterface;

class ShowServices extends Component
{
    use WithPagination;

    private object $services;
    public int $perPage;
    public string $searchString = '';

    protected object $servicesRepository;

    public function boot(ServicesInterface $service)
    {
        $this->servicesRepository = $service;
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

    public function render()
    {
        
        if(isset($this->searchString) && $this->searchString != '') {

            // $this->services = Services::where('name', 'like', '%' . $this->searchString . '%')
            //     ->orWhere('description', 'like', '%' . $this->searchString . '%')
            //     ->orWhere('description', 'like', '%' . $this->searchString . '%')
            //     ->paginate($this->perPage);
            $this->services = $this->servicesRepository->getSearchedService($this->searchString,$this->perPage);
        } else {
            //$this->services = Services::with('serviceType')->with('paymentType')->paginate($this->perPage);
            $this->services = $this->servicesRepository->getAllServices($this->perPage);
           
        }
        return view('tenant.livewire.setup.services.show-services', [
            'services' => $this->services
        ]);
    }
}
