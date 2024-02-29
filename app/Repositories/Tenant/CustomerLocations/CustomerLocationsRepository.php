<?php

namespace App\Repositories\Tenant\CustomerLocations;

use stdClass;
use App\Models\Tenant\Customers;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\CustomerServices;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;
use App\Http\Requests\Tenant\CustomerLocations\CustomerLocationsFormRequest;

class CustomerLocationsRepository implements CustomerLocationsInterface
{
    public function getAllCostumerLocations($perPage): LengthAwarePaginator
    {
        // $customers = CustomerLocations::paginate($perPage);
        // return $customers;

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


        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if($response_decoded != null)
        {
            $currentItems = array_slice($response_decoded->locations, $perPage * ($currentPage - 1), $perPage);

            $itemsPaginate = new LengthAwarePaginator($currentItems, count((array)$response_decoded->locations),$perPage);
        }
        else {

            $currentItems = [];

            $itemsPaginate = new LengthAwarePaginator($currentItems, count((array)$currentItems),$perPage);
        }


        return $itemsPaginate; 
    }

    public function getAllCostumerLocationsCollection(): object
    {
        // $customers = CustomerLocations::paginate($perPage);
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



        return $response_decoded; 
    }

    public function getSearchedCostumerLocations($searchString, $perPage): LengthAwarePaginator
    {
        // $customers = CustomerLocations::where('description', 'like', '%' . $searchString . '%')->paginate($perPage);
        // return $customers;
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

        $countSearchString = strlen($searchString);

        foreach($response_decoded->locations as $resp)
        {
            if(substr($resp->name,0,$countSearchString) == substr($searchString,0,$countSearchString))
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

   
    public function getSpecificLocationInfo($idLocation): object
    {
       
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://172.19.20.4:24004/location/locations?id='.$idLocation,
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

      
    public function add(CustomerLocationsFormRequest $request): CustomerLocations
    {
        return DB::transaction(function () use ($request) {
    
            // $customerLocation = CustomerLocations::create([
            //     'description' => $request->description,
            //     'customer_id' => $request->customer_id,
            //     'main' => 0,
            //     'address' => $request->address,
            //     'zipcode' => $request->zipcode,
            //     'district_id' => $request->district,
            //     'county_id' => $request->county,
            //     'contact' => $request->contact,
            //     'manager_name' => $request->manager_name,
            //     'manager_contact' => $request->manager_contact,
            // ]);

            $arrayPHCLocation = [
                "name" => $request->description,
                "no" => $request->selectedCustomer,
                "addressname" => $request->address,
                "phone" => $request->contact,
                "managername" => $request->manager_name,
                "locationmainornot" => false,
                "phonemanager" => $request->manager_contact,
                "address" =>$request->address,
                "zipcode" => $request->zipcode,
                "state" => $request->district,
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

            
            $customerLocation = CustomerLocations::first();
            return $customerLocation;
        });
    }

    public function update($customerLocation,CustomerLocationsFormRequest $request): CustomerLocations
    {   
       
        return DB::transaction(function () use ($customerLocation,$request) {

            // DD($request);
            // $obj_merged = '';
            // if($request->main == "1")
            // {
            //     $obj_merged = (object)array_merge((array)$request,(array)['main' => '1']);
            // }
            // else if(!isset($request->main))
            // {
            //     $obj_merged = (object)array_merge((array)$request,(array)['main' => '0']);
            // }

            // $customerLocationRequest[]= $request->all();

            // $arrayCustomerLocation = [];

            // $newCompete = [];
                    
            // foreach($customerLocationRequest as $req)
            // {
            //     if($req["selectedCustomer"] != "")
            //     {
            //         $newCompete["customer_id"] = $req["selectedCustomer"];
            //         array_push($arrayCustomerLocation, $newCompete);
                    
            //     }
            //     if($req["description"] != "")
            //     {
            //         $newCompete["description"] = $req["description"];
            //         array_push($arrayCustomerLocation, $newCompete);
            //     }
            //     if($req["contact"] != "")
            //     {
            //         $newCompete["contact"] = $req["contact"];
            //         array_push($arrayCustomerLocation, $newCompete);
            //     }
            //     if($req["manager_name"] != "")
            //     {
            //         $newCompete["manager_name"] = $req["manager_name"];
            //         array_push($arrayCustomerLocation, $newCompete);
            //     }
            //     if($req["manager_contact"] != "")
            //     {
            //         $newCompete["manager_contact"] = $req["manager_contact"];
            //         array_push($arrayCustomerLocation, $newCompete);
            //     }
            //     if($req["address"] != "")
            //     {
            //         $newCompete["address"] = $req["address"];
            //         array_push($arrayCustomerLocation, $newCompete);
            //     }
            //     if($req["zipcode"] != "")
            //     {
            //         $newCompete["zipcode"] = $req["zipcode"];
            //         array_push($arrayCustomerLocation, $newCompete);
            //     }
            //     if($req["district"] != "")
            //     {
            //         $newCompete["district_id"] = $req["district"];
            //         array_push($arrayCustomerLocation, $newCompete);
            //     }
            //     if(isset($req["county"]))
            //     {
            //         if($req["county"] != "" )
            //         {
            //             $newCompete["county_id"] = $req["county"];
            //             array_push($arrayCustomerLocation, $newCompete);
            //         }
            //     }
            //     if(isset($obj_merged->main))
            //     {
            //         $newCompete["main"] = $obj_merged->main;
            //         array_push($arrayCustomerLocation,$newCompete);
            //     }
               
            // }

            // $arrayCheckMain = array_pop($arrayCustomerLocation);
    
            // CustomerLocations::where('id',$customerLocation->id)->update(
            //     array_pop($arrayCustomerLocation)
            // );

            // if($arrayCheckMain["main"] == 1)
            // {
            //     Customers::where("id",$arrayCheckMain["customer_id"])->update(
            //     [
            //     "contact" =>$arrayCheckMain["contact"],
            //     "address" =>$arrayCheckMain["address"],
            //     "zipcode" =>$arrayCheckMain["zipcode"],
            //     "district" =>$arrayCheckMain["district_id"],
            //     "county" =>$arrayCheckMain["county_id"]
            //     ]);
            // }

            if(!isset($request->main))
            {
                $main = false;
            }
            else {
                if($request->main == 1)
                {
                    $main = true;
                } else {
                    $main = false;
                }
            }
           

            $arrayPHC = [
                "id" => $customerLocation,
                "no" => $request->selectedCustomer,
                "name" => $request->description,
                "addressname" => $request->address,
                "phone" => $request->contact,
                "locationmainornot" => $main,
                "managername" => $request->manager_name,
                "phonemanager" => $request->manager_contact,
                "address" =>$request->address,
                "zipcode" => $request->zipcode,
                "state" => $request->district,
                "city" => $request->county
            ];

            $encoded = json_encode($arrayPHC);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://172.19.20.4:24004/location/location',
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

            $customerLocation = CustomerLocations::first();

            return $customerLocation;
        });
    }

    public function destroy(CustomerLocations $customerLocation): CustomerLocations
    {
        return DB::transaction(function () use ($customerLocation) {
            if($customerLocation->main == 1)
            {
                Customers::where('id',$customerLocation->customer_id)->delete();
            }
            CustomerServices::where('location_id',$customerLocation->id)->delete();
            $customerLocation->delete();
            return $customerLocation;
        });
        
       
    }


}
