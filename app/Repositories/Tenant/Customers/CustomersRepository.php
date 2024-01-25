<?php

namespace App\Repositories\Tenant\Customers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Tenant\Files;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\CustomerServices;
use App\Models\Tenant\ContactsCustomers;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Http\Requests\Tenant\Customers\CustomersFormRequest;
use App\Http\Requests\Tenant\CustomerContacts\CustomerContactsFormRequest;

class CustomersRepository implements CustomersInterface
{
    public function getAllCustomers($perPage): LengthAwarePaginator
    {
        $customers = Customers::with('customerDistrict')->with('teamMember')->paginate($perPage);
        return $customers;
    }

    public function getCustomersAnalysis(): Collection
    {
        if(Auth::user()->type_user == 2)
        {
           $customers = Customers::where('user_id',Auth::user()->id)->get();
        }
        else
        {
           $customers = Customers::all();
        }
       
        return $customers;
    }

    public function getSearchedCustomer($searchString, $perPage): LengthAwarePaginator
    {
        $customers = Customers::where('name', 'like', '%' . $searchString . '%')->paginate($perPage);
        return $customers;
    }

    public function getLocationsFromCustomer($customer_id,$searchString, $perPage): LengthAwarePaginator
    {
       $customer = CustomerLocations::where('customer_id', $customer_id)->where('description', 'like', '%' . $searchString . '%')->paginate($perPage);
       return $customer;
    }

    public function add(CustomersFormRequest $request): Customers
    {
        return DB::transaction(function () use ($request) {
            $Customer = Customers::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'short_name' => $request->short_name,
                'username' => $request->username,
                'vat' => $request->vat,
                'contact' => $request->contact,
                'email' => $request->email,
                'address' => $request->address,
                'district' => $request->district,
                'county' => $request->county,
                'zipcode' => $request->zipcode,
                'zone' => '1',
                'account_manager' => $request->account_manager,
                'account_active' => '0'
            ]);

            $memberInfo = TeamMember::where('id',$request->account_manager)->first();
            $manager_name = $memberInfo->name;
            $manager_contact = $memberInfo->mobile_phone;

           $location_customer = CustomerLocations::create([
                'description' => __('Main address'),
                'customer_id' => $Customer->id,
                'main' => 1,
                'address' => $request->address,
                'zipcode' => $request->zipcode,
                'district_id' => $request->district,
                'county_id' => $request->county,
                'contact' => $request->contact,
                'manager_name' => $manager_name,
                'manager_contact' => $manager_contact,
            ]);

            CustomerServices::create([
                'customer_id' => $Customer->id,
                'service_id' => 4,
                'location_id' => $location_customer->id,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+1 year')),
                'type' => 'Anual',
                'alert' => '0',
                'selectedTypeContract' => 'anualmente',
                'time_repeat' => '1',
                'number_times' => '999999',
                'allMails' => '0',
                'new_date' => date('Y-m-d'),
                'member_associated' => $request->memberAssociated
            ]);



            return $Customer;
        });
    }

    public function update(Customers $customer,CustomersFormRequest $request): Customers
    {
        return DB::transaction(function () use ($customer,$request) {

            $customer->fill($request->all());
            $customer->save();

            $customerLocationRequest[]= $request->all();

            $arrayCustomerLocation = [];

            $newCompete = [];
            foreach($customerLocationRequest as $req)
            {
                if($req["address"] != "")
                {
                    $newCompete["address"] = $req["address"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
                if($req["zipcode"] != "")
                {
                    $newCompete["zipcode"] = $req["zipcode"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
                if($req["contact"] != "")
                {
                    $newCompete["contact"] = $req["contact"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
                if($req["district"] != "")
                {
                    $newCompete["district_id"] = $req["district"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
                if($req["county"] != "")
                {
                    $newCompete["county_id"] = $req["county"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
            }

            CustomerLocations::where('customer_id',$customer->id)->where('main','1')->update(
                array_pop($arrayCustomerLocation)
            );

            if(Auth::user()->type_user == 2)
            {
                User::where('id',Auth::user()->id)->update([
                    "name" => $request->name,
                    "username" => $request->username,
                    "email" => $request->email
                ]);
            }
            return $customer;

        });

    }

    public function destroy(Customers $costumer): Customers
    {
        return DB::transaction(function () use ($costumer) {
            CustomerLocations::where('customer_id',$costumer->id)->delete();
            CustomerServices::where('customer_id',$costumer->id)->delete();
            $costumer->delete();
            return $costumer;
        });

    }

    public function createLogin($customer): User
    {
        return DB::transaction(function () use ($customer){
           $password = Str::random(8);
           $hashed_password = Hash::make($password);

           $customerSelected = Customers::where('id',$customer)->first();
           
           $userCreate = User::create([
                'name' => $customerSelected->name,
                'username' => $customerSelected->username,
                'email' => $customerSelected->email,
                'type_user' => '2',
                'password' => $hashed_password,
           ]);

           $updateTeamMember = Customers::where('id',$customerSelected->id)->update([
              'user_id' => $userCreate->id,
              'account_active' => '1'
           ]);

           $userCreate["user"] = ['password_without_hashed' => $password];

           return $userCreate;
        });
    }

    public function getCustomersOfMember($id,$perPage): LengthAwarePaginator
    {
        if(Auth::user()->type_user == 0)
        {
            $customers = Customers::paginate($perPage);
        }
        else 
        {
            $teamMember = TeamMember::where('user_id',$id)->first();
            $customers = Customers::where('account_manager',$teamMember->id)->paginate($perPage);
        }
       
        return $customers;
    }



}

