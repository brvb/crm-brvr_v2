<?php

namespace App\Repositories\Tenant\Customers;

use stdClass;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Tenant\Files;
use App\Models\Tenant\Counties;
use App\Models\Tenant\Customers;
use App\Models\Tenant\Districts;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\StampsClientes;
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
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://172.19.20.4:24004/customers/customers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if($response_decoded != null)
        {
            $currentItems = array_slice($response_decoded->customers, $perPage * ($currentPage - 1), $perPage);

            $itemsPaginate = new LengthAwarePaginator($currentItems, count((array)$response_decoded->customers),$perPage);
        }
        else {

            $currentItems = [];

            $itemsPaginate = new LengthAwarePaginator($currentItems, count((array)$currentItems),$perPage);
        }


        return $itemsPaginate; 

        // $customers = Customers::with('customerDistrict')->with('teamMember')->paginate($perPage);
        // return $customers;
    }

    public function getAllCustomersCollection(): object
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://172.19.20.4:24004/customers/customers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        return $response_decoded; 
    }

    public function getSpecificCustomerInfo($idCustomer): object
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://172.19.20.4:24004/customers/customers?id='.$idCustomer,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $response_decoded = json_decode($response);
    
        return $response_decoded;
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

    public function getSearchedCustomer($nomecustomer,$nifcustomer,$contactocustomer,$perPage): LengthAwarePaginator
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://172.19.20.4:24004/customers/customers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $new_object = new stdClass();
        $new_object->customers = [];

        $countSearchStringNome = strlen($nomecustomer);
        $countSearchStringNif = strlen($nifcustomer);
        $countSearchStringContacto = strlen($contactocustomer);

        $arrayNOME=[];
        $arrayNIF=[];
        $arrayCONTACTO=[];

       
        //TENHO DE ALGUMA MANEIRA LIMPAR CASO ENTRE NO IF
        foreach($response_decoded->customers as $resp)
        {
            if($nomecustomer != "")
            {
                if(substr(strtolower($resp->name),0,$countSearchStringNome) == substr(strtolower($nomecustomer),0,$countSearchStringNome))
                {
                    // array_push($new_object->customers,$resp);
                    array_push($arrayNOME,$resp);
                }
            }
            
            if($nifcustomer != "")
            {
                if(substr($resp->nif,0,$countSearchStringNif) == substr($nifcustomer,0,$countSearchStringNif))
                {
                    // array_push($new_object->customers,$resp);
                    array_push($arrayNIF,$resp);
                }
            }
            
            if($contactocustomer != "")
            {
                if(substr(strtolower($resp->email),0,$countSearchStringContacto) == substr(strtolower($contactocustomer),0,$countSearchStringContacto))
                {
                    // array_push($new_object->customers,$resp);
                    array_push($arrayCONTACTO,$resp);
                }
            }
            
        }

        $arrayUnico = [];

        // Adiciona objetos do primeiro array ao array único
        foreach ($arrayNOME as $objeto) {
            $arrayUnico[$objeto->id] = $objeto;
        }

        // Adiciona objetos do segundo array ao array único
        foreach ($arrayNIF as $objeto) {
            $arrayUnico[$objeto->id] = $objeto;
        }

        foreach ($arrayCONTACTO as $objeto) {
            $arrayUnico[$objeto->id] = $objeto;
        }

        // Converte o array associativo para um array indexado (opcional)
        $arrayUnico = array_values($arrayUnico);

        foreach($arrayUnico as $uni)
        {
            array_push($new_object->customers,$uni);
        }
        
     
        $currentPage = LengthAwarePaginator::resolveCurrentPage();


        if($new_object != null)
        {
            $currentItems = array_slice($new_object->customers, $perPage * ($currentPage - 1), $perPage);

            $itemsPaginate = new LengthAwarePaginator($currentItems, count((array)$new_object->customers),$perPage);
        }
        else {

            $currentItems = [];

            $itemsPaginate = new LengthAwarePaginator($currentItems, count((array)$currentItems),$perPage);
        }
        
        
        return $itemsPaginate;
    }

    public function getSearchedCustomerCollection($searchString): object
    {
       
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://172.19.20.4:24004/customers/customers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $new_object = new stdClass();
        $new_object->customers = [];

        $countSearchString = strlen($searchString);

        foreach($response_decoded->customers as $resp)
        {
            if(substr($resp->name,0,$countSearchString) == substr($searchString,0,$countSearchString))
            {
                array_push($new_object->customers,$resp);
            }
        }
        
        return $new_object;

    }

    public function getSearchedCustomerByNo($no): object
    {
        // $customers = Customers::where('name', 'like', '%' . $searchString . '%')->paginate($perPage);
        // return $customers;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://172.19.20.4:24004/customers/customers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $new_object = new stdClass();
        $new_object->customers = [];

        foreach($response_decoded->customers as $resp)
        {
            if($resp->no == $no)
            {
                array_push($new_object->customers,$resp);
            }
        }

        
        
        return $new_object;
    }

    public function getLocationsFromCustomer($customer_id, $perPage): LengthAwarePaginator
    {
    //    $customer = CustomerLocations::where('customer_id', $customer_id)->where('description', 'like', '%' . $searchString . '%')->paginate($perPage);
    //    return $customer;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://172.19.20.4:24004/location/locations',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);

        $new_object = new stdClass();
        $new_object->locations = [];

        $countSearchString = strlen($customer_id);

        foreach($response_decoded->locations as $resp)
        {
            if($resp->no == $customer_id)
            {
                array_push($new_object->locations,$resp);
            }
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();


        if($new_object != null)
        {
            $currentItems = array_slice($new_object->locations, $perPage * ($currentPage - 1), $perPage);

            $itemsPaginate = new LengthAwarePaginator($currentItems, count((array)$new_object->locations),$perPage);
        }
        else {

            $currentItems = [];

            $itemsPaginate = new LengthAwarePaginator($currentItems, count((array)$currentItems),$perPage);
        }
        
        
        return $itemsPaginate;

    }

    public function getLocationsFromCustomerCollection($customer_id): object
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://172.19.20.4:24004/location/locations',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response_decoded = json_decode($response);


        $new_object = new stdClass();
        $new_object->locations = [];

        $countSearchString = strlen($customer_id);

        foreach($response_decoded->locations as $resp)
        {
            if($resp->no == $customer_id)
            {
                array_push($new_object->locations,$resp);
            }
        }

       
        return $new_object;

    }

    public function add(CustomersFormRequest $request): Customers
    {
        return DB::transaction(function () use ($request) {

     
            // $distrito = Districts::where('id',$request->district)->first();

            // $cidade = Counties::where('id',$request->county)->where('district_id',$request->district)->first();


           
         
            $arrayPHC = [
                "name" => $request->name,
                "nif" => $request->vat,
                "surname" => $request->short_name,
                "phone" => $request->contact,
                "email" => $request->email,
                "address" =>$request->address,
                "username" => $request->username,
                "zipcode" => $request->zipcode,
                "state" => $request->district,
                "city" => $request->county
            ];


            $encoded = json_encode($arrayPHC);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://172.19.20.4:24004/customers/customers',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $encoded,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $getClient = json_decode($response);

            
            StampsClientes::create([
                "stamp" => $getClient->id,
                "nome_cliente" => $request->name
            ]);
            

            /******* LOCALIZAÇÕES ****/

            $arrayPHCLocation = [
                "name" => "Sede",
                "no" => $getClient->no,
                "addressname" => $request->address,
                "managername" => "",
                "locationmainornot" => true,
                "phonemanager" => "",
                "address" =>$request->address,
                "zipcode" => $request->zipcode,
                "state" => $request->district,
                "longitude" => $request->longitude,
                "latitude" => $request->latitude,
                "city" => $request->county
            ];


            $encodedLocation = json_encode($arrayPHCLocation);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://172.19.20.4:24004/location/locations',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $encodedLocation,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            
            $getLocation = json_decode($response);

            CustomerServices::create([
                'customer_id' => $getClient->id,
                'service_id' => 4,
                'location_id' => $getLocation->id,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+1 year')),
                'type' => 'Anual',
                'alert' => '0',
                'selectedTypeContract' => 'anualmente',
                'time_repeat' => '1',
                'number_times' => '999999',
                'allMails' => '0',
                'new_date' => date('Y-m-d'),
                'member_associated' => 9
            ]);



        //     $Customer = Customers::create([
        //         'name' => $request->name,
        //         'slug' => $request->slug,
        //         'short_name' => $request->short_name,
        //         'username' => $request->username,
        //         'vat' => $request->vat,
        //         'contact' => $request->contact,
        //         'email' => $request->email,
        //         'address' => $request->address,
        //         'district' => $request->district,
        //         'county' => $request->county,
        //         'zipcode' => $request->zipcode,
        //         'zone' => '1',
        //         'account_manager' => $request->account_manager,
        //         'account_active' => '0'
        //     ]);

        //     $memberInfo = TeamMember::where('id',$request->account_manager)->first();
        //     $manager_name = $memberInfo->name;
        //     $manager_contact = $memberInfo->mobile_phone;

        //    $location_customer = CustomerLocations::create([
        //         'description' => __('Main address'),
        //         'customer_id' => $Customer->id,
        //         'main' => 1,
        //         'address' => $request->address,
        //         'zipcode' => $request->zipcode,
        //         'district_id' => $request->district,
        //         'county_id' => $request->county,
        //         'contact' => $request->contact,
        //         'manager_name' => $manager_name,
        //         'manager_contact' => $manager_contact,
        //     ]);

         

            $Customer = Customers::first();

            return $Customer;
        });
    }

    public function update($noClient,CustomersFormRequest $request): Customers
    {
        return DB::transaction(function () use ($noClient,$request) {

            $arrayPHC = [
                "id" => $request->idCustomer,
                "no" => $noClient,
                "name" => $request->name,
                "slug" => $request->slug,
                "nif" => $request->vat,
                "surname" => $request->short_name,
                "phone" => $request->contact,
                "email" => $request->email,
                "address" =>$request->address,
                "username" => $request->username,
                "zipcode" => $request->zipcode,
                "state" => $request->district,
                "city" => $request->county
            ];


            $encoded = json_encode($arrayPHC);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://172.19.20.4:24004/customers/customers',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => $encoded,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);


            curl_close($curl);

         

            $customer = Customers::first();
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

