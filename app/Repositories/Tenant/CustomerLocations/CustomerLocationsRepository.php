<?php

namespace App\Repositories\Tenant\CustomerLocations;

use Illuminate\Support\Facades\DB;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;
use App\Http\Requests\Tenant\CustomerLocations\CustomerLocationsFormRequest;
use App\Models\Tenant\Customers;
use App\Models\Tenant\CustomerServices;

class CustomerLocationsRepository implements CustomerLocationsInterface
{
    public function getAllCostumerLocations($perPage): LengthAwarePaginator
    {
        $customers = CustomerLocations::paginate($perPage);
        return $customers;
    }

    public function getSearchedCostumerLocations($searchString, $perPage): LengthAwarePaginator
    {
        $customers = CustomerLocations::where('description', 'like', '%' . $searchString . '%')->paginate($perPage);
        return $customers;
    }

      
    public function add(CustomerLocationsFormRequest $request): CustomerLocations
    {
        return DB::transaction(function () use ($request) {
            $customerLocation = CustomerLocations::create([
                'description' => $request->description,
                'customer_id' => $request->customer_id,
                'main' => 0,
                'address' => $request->address,
                'zipcode' => $request->zipcode,
                'district_id' => $request->district,
                'county_id' => $request->county,
                'contact' => $request->contact,
                'manager_name' => $request->manager_name,
                'manager_contact' => $request->manager_contact,
            ]);


            return $customerLocation;
        });
    }

    public function update(CustomerLocations $customerLocation,CustomerLocationsFormRequest $request): CustomerLocations
    {   
       
        return DB::transaction(function () use ($customerLocation,$request) {
            $obj_merged = '';
            if($request->main == "1")
            {
                $obj_merged = (object)array_merge((array)$request,(array)['main' => '1']);
            }
            else if(!isset($request->main))
            {
                $obj_merged = (object)array_merge((array)$request,(array)['main' => '0']);
            }

            $customerLocationRequest[]= $request->all();

            $arrayCustomerLocation = [];

            $newCompete = [];
                    
            foreach($customerLocationRequest as $req)
            {
                if($req["selectedCustomer"] != "")
                {
                    $newCompete["customer_id"] = $req["selectedCustomer"];
                    array_push($arrayCustomerLocation, $newCompete);
                    
                }
                if($req["description"] != "")
                {
                    $newCompete["description"] = $req["description"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
                if($req["contact"] != "")
                {
                    $newCompete["contact"] = $req["contact"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
                if($req["manager_name"] != "")
                {
                    $newCompete["manager_name"] = $req["manager_name"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
                if($req["manager_contact"] != "")
                {
                    $newCompete["manager_contact"] = $req["manager_contact"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
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
                if($req["district"] != "")
                {
                    $newCompete["district_id"] = $req["district"];
                    array_push($arrayCustomerLocation, $newCompete);
                }
                if(isset($req["county"]))
                {
                    if($req["county"] != "" )
                    {
                        $newCompete["county_id"] = $req["county"];
                        array_push($arrayCustomerLocation, $newCompete);
                    }
                }
                if(isset($obj_merged->main))
                {
                    $newCompete["main"] = $obj_merged->main;
                    array_push($arrayCustomerLocation,$newCompete);
                }
               
            }

            $arrayCheckMain = array_pop($arrayCustomerLocation);
    
            CustomerLocations::where('id',$customerLocation->id)->update(
                array_pop($arrayCustomerLocation)
            );

            if($arrayCheckMain["main"] == 1)
            {
                Customers::where("id",$arrayCheckMain["customer_id"])->update(
                [
                "contact" =>$arrayCheckMain["contact"],
                "address" =>$arrayCheckMain["address"],
                "zipcode" =>$arrayCheckMain["zipcode"],
                "district" =>$arrayCheckMain["district_id"],
                "county" =>$arrayCheckMain["county_id"]
                ]);
            }
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
