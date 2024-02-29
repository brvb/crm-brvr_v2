<?php

namespace App\Interfaces\Tenant\CustomerNotification;

use App\Models\Tenant\Customers;
use App\Models\Tenant\CustomerServices;
use App\Models\Tenant\CustomerLocations;
use App\Models\Tenant\CustomerNotification;
use Illuminate\Console\View\Components\Task;
use App\Http\Livewire\Tenant\Common\Location;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tenant\CustomersServices\CustomersServicesFormRequest;


interface CustomerNotificationInterface
{
    public function getNotificationTimes($customerRepository,$customerLocationRepository): Array;

    public function changeTreatedStatus(CustomerServices $idCustomerService): void;


}
