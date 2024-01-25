<?php

namespace App\Interfaces\Tenant\CustomerContacts;

use App\Models\Tenant\CustomerContacts;
use App\Http\Requests\Tenant\CustomerContacts\CustomerContactsFormRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerContactsInterface
{
    public function getAllCostumerContacts($customerId,$perPage): LengthAwarePaginator;

    public function getSearchedCostumerContacts($customerId,$searchString,$perPage): LengthAwarePaginator;

    public function addCustomerContact(CustomerContactsFormRequest $request): CustomerContacts;

    public function updateCustomerContact(int $contactCustomer,CustomerContactsFormRequest $request): int;

    public function deleteCustomerContact(int $contactCustomer): int;

}
