<?php

namespace App\Http\Livewire\Tenant\Files;

use Livewire\Component;
use App\Events\ChatMessage;
use App\Models\Tenant\Files;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Tenant\Customers\CustomersInterface;


class ShowFiles extends Component
{
    use WithPagination;

    protected $listeners = ["FilesOfThisCustomer" => "FilesOfThisCustomer"];

    private object $customers;
    public int $perPage;
    public string $files = '';
    public int $customer = 0;


    protected object $customersRepository;

    public function boot(CustomersInterface $interfaceCustomer)
    {
        $this->customersRepository = $interfaceCustomer;
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

    public function FilesOfThisCustomer($id)
    {
        $this->customer = $id;
        $this->files = Files::where('customer_id',$this->customer)->get();
        $this->emit('FilesUpdatedFromMembers', $this->files,$this->customer);
        $this->emit("teste");
    }

    /**
     * List informations of customer location
     *
     * @return View
     */
    public function render(): View
    {
        
        $this->customers = $this->customersRepository->getCustomersOfMember(Auth::user()->id,$this->perPage); 
               
        return view('tenant.livewire.files.show', [
            'customers' => $this->customers,
            'file' => $this->files,
            'customer' => $this->customer
        ]);
    }
}
