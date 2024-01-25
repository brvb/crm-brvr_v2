<?php

namespace App\Http\Livewire\Tenant\Tasks;

use Livewire\Component;
use Illuminate\Contracts\View\View;

class ServiceList extends Component
{
    public object $customerServicesList;
    public int $numberOfSelectedServices = 0;

    public function mount($customerServicesList)
    {
        echo $customerServicesList;
        // $this->customerServicesList = $customerServicesList;
        // $this->numberOfSelectedServices = $numberOfSelectedServices;
    }

    public function render(): View
    {
        return view('tenant.livewire.tasks.servicelist', [
            // 'customerServicesList' => $this->customerServicesList,
            // 'numberOfSelectedServices' => $this->numberOfSelectedServices

        ]);
    }

 }
