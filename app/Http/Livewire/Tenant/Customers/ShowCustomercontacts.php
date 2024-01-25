<?php

namespace App\Http\Livewire\Tenant\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Interfaces\Tenant\CustomerContacts\CustomerContactsInterface;
use Illuminate\Contracts\View\View;

class ShowCustomercontacts extends Component
{
    use WithPagination;

    public int $perPage;
    public string $searchString = '';
    protected ?object $customerContacts = NULL;
    public ?int $customer_id = NULL;
    protected object $customerContactsRepository;

    /**
     * Boot function to create database interface repository
     *
     * @param CustomerContactsInterface $customerContacts
     * @return void
     */
    public function boot(CustomerContactsInterface $customerContacts): void
    {
        $this->customerContactsRepository = $customerContacts;
    }

    /**
     * Mount livewire the first time
     *
     * @param [type] $customer
     * @return void
     */
    public function mount($customer): void
    {
        if (!is_integer($customer)) {
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

    /**
     * Change the number of items displayed by page
     *
     * @return void
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
        session()->put('perPage', $this->perPage);
    }

     /**
     * Change the number of items displayed by page
     *
     * @return void
     */
    public function updatedSearchString(): void
    {
        $this->resetPage();
    }

    /**
     * Return custom pagination view
     *
     * @return string
     */
    public function paginationView(): string
    {
        return 'tenant.livewire.setup.pagination';
    }

    /**
     * Render livewire view
     *
     * @return View
     */
    public function render(): View
    {
        if ($this->searchString == '') {
            $this->customerContacts = $this->customerContactsRepository->getAllCostumerContacts(
                $this->customer_id,
                $this->perPage
            );
        } else {
            $this->customerContacts = $this->customerContactsRepository->getSearchedCostumerContacts(
                $this->customer_id,
                $this->searchString,
                $this->perPage
            );
        }

        return view('tenant.livewire.customers.show-customercontacts', [
            'customerContacts' => $this->customerContacts,
            'customer_id' => $this->customer_id,
        ]);
    }
}
