<?php

namespace App\Http\Livewire\Tenant\Customers;

use App\Models\Counties;
use Livewire\Component;
use App\Models\Districts;
use App\Models\Tenant\TeamMember;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;

class EditCustomer extends Component
{
    use WithPagination;

    private object $customer;
    private object $districts;
    private object $counties;
   // private object $account_manager;
    private string $account_manager;
    private object $allAccountManager;

    public function mount($customer, $account_manager): void
    {
        $this->customer = $customer;
        $this->account_manager = $account_manager;
        // $this->districts = tenancy()->central(function () {
        //     return Districts::all();
        // });
        // $this->counties = tenancy()->central(function () {
        //     return Counties::where('district_id', $this->customer->district)->get();
        // });
        $this->allAccountManager = TeamMember::all();
        $this->districts = Districts::all();
        $this->counties = Counties::all();
        
    
    }

    public function render(): View
    {
        return view('tenant.livewire.customers.edit-customer', [
            'customer' => $this->customer,
            'districts' => $this->districts,
            'counties' => $this->counties,
            'district' => $this->customer->district,
            'county' => $this->customer->county,
            'account_manager' => $this->account_manager,
            'allAccountManagers' => $this->allAccountManager
        ]);
    }

}
