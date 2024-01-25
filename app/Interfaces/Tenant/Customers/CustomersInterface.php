<?php

namespace App\Interfaces\Tenant\Customers;

use App\Models\User;

use App\Models\Tenant\Files;
use App\Models\Tenant\Customers;
use App\Models\Tenant\ContactsCustomers;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tenant\Customers\CustomersFormRequest;
use App\Http\Requests\Tenant\CustomerContacts\CustomerContactsFormRequest;
use App\Http\Requests\Tenant\CustomerLocations\CustomerLocationsFormRequest;

interface CustomersInterface
{
    public function getAllCustomers($perPage): LengthAwarePaginator;

    public function getSearchedCustomer($searchString,$perPage): LengthAwarePaginator;

    public function getCustomersAnalysis(): Collection;

    public function getLocationsFromCustomer($customer_id,$searchString,$perPage): LengthAwarePaginator;

    public function add(CustomersFormRequest $request): Customers;

    public function update(Customers $customer,CustomersFormRequest $request): Customers;

    public function destroy(Customers $customer) : Customers;

    public function createLogin(string $customer): User;

    ///**** Ficheiros */

    public function getCustomersOfMember(int $id,$perPage): LengthAwarePaginator;


}
