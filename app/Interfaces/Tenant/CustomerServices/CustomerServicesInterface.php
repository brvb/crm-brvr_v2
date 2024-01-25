<?php

namespace App\Interfaces\Tenant\CustomerServices;

use App\Http\Livewire\Tenant\Common\Location;
use App\Models\Tenant\CustomerServices;
use App\Http\Requests\Tenant\CustomersServices\CustomersServicesFormRequest;
use App\Models\Tenant\CustomerLocations;
use App\Models\Tenant\Customers;
use Illuminate\Console\View\Components\Task;
use Illuminate\Pagination\LengthAwarePaginator;


interface CustomerServicesInterface
{
    public function getAllCustomerServices($perPage): LengthAwarePaginator;

    public function getSearchedCustomerService($searchString,$perPage): LengthAwarePaginator;

    public function getSearchedCustomerServiceWithFilterCostumer($customerId,$searchString,$perPage): LengthAwarePaginator;

    public function add(CustomersServicesFormRequest $request): CustomerServices;

    public function update(int $customerService, CustomersServicesFormRequest $request): int;

    public function destroy(int $customerService): int;

    public function getCustomerServices(Customers $customer, string $location);

    public function getNotificationTimes(): Array;

    public function changeTreatedStatus(CustomerServices $idCustomerService): void;


}
