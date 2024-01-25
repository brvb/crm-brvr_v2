<?php

namespace App\Repositories\Tenant\CustomerContacts;

use App\Models\Tenant\CustomerContacts;
use App\Http\Requests\Tenant\CustomerContacts\CustomerContactsFormRequest;
use App\Interfaces\Tenant\CustomerContacts\CustomerContactsInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CustomersContactsRepository implements CustomerContactsInterface
{
    public function getAllCostumerContacts($customerId, $perPage): LengthAwarePaginator {
        $customersContacts = CustomerContacts::where('customer_id', $customerId)
            ->with('location')
            ->paginate($perPage);
        return $customersContacts;
    }

    public function getSearchedCostumerContacts($customerId, $searchString, $perPage): LengthAwarePaginator {
        $customersContacts = CustomerContacts::where('customer_id', $customerId)
            ->WhereHas('location', function ($q) use ($searchString) {
                $q->Where('description', 'like', '%' . $searchString . '%');
            })
            ->orWhere('name', 'like', '%' . $searchString . '%')
            ->orWhere('job_description', 'like', '%' . $searchString . '%')
            ->paginate($perPage);

        return $customersContacts;
    }

    public function addCustomerContact(CustomerContactsFormRequest $request): CustomerContacts {
        return DB::transaction(function () use ($request) {
            $CustomerContact = CustomerContacts::create([
                'customer_id' => $request->customer_id,
                'location_id' => $request->location_id,
                'name' => $request->name,
                'job_description' => $request->job_description,
                'mobile_phone' => $request->mobile_phone,
                'landline' => $request->landline,
                'email' => $request->email,
            ]);

            return $CustomerContact;
        });
    }

    public function updateCustomerContact(int $contact, CustomerContactsFormRequest $request): int {
        return DB::transaction(function () use ($contact, $request) {
            $update = [
                'location_id' => $request->get('location_id'),
                'name' => $request->get('name'),
                'job_description' => $request->get('job_description'),
                'mobile_phone' => $request->get('mobile_phone'),
                'landline' => $request->get('landline'),
                'email' => $request->get('email'),
                'all_mails' => 0,
            ];
            $update['all_mails'] = 0;
            if ($request->get('allMails') == 'on') {
                $update['all_mails'] = 1;
            }

            CustomerContacts::where('id', $contact)->update($update);
            return $contact;
        });
    }

    public function deleteCustomerContact(int $contactCustomer): int
    {
        return DB::transaction(function () use ($contactCustomer) {
            $customerContact = CustomerContacts::where(
                'id',
                $contactCustomer
            )->delete();

            return $customerContact;
        });
    }
}
