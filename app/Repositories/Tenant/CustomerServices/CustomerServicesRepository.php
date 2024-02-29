<?php

namespace App\Repositories\Tenant\CustomerServices;

use stdClass;
use App\Models\Tenant\Customers;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\CustomerServices;
use App\Http\Livewire\Tenant\Common\Location;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tenant\Customers\CustomersFormRequest;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;
use App\Http\Requests\Tenant\CustomersServices\CustomersServicesFormRequest;

class CustomerServicesRepository implements CustomerServicesInterface
{

    public function getAllCustomers(): object
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

    public function getAllCustomerServices($perPage): LengthAwarePaginator
    {
        $customerServices = CustomerServices::with('customer')
                            ->with('service')
                            ->with('customerLocation')
                            ->paginate($perPage);

        return $customerServices;
    }

    public function getSearchedCustomerService($searchString,$perPage): LengthAwarePaginator
    {
        $customerServices = CustomerServices::whereHas('customer', function ($query) use($searchString) {
            $query->where('name', 'like', '%' . $searchString . '%');
        })
        ->with('service')
        ->with('customerLocation')
        ->paginate($perPage);

        return $customerServices;
    }

    public function getSearchedCustomerServiceWithFilterCostumer($customerId,$searchString,$perPage): LengthAwarePaginator
    {
        $customerServices = CustomerServices::where('customer_id', $customerId)
        ->with('service')
        ->paginate($perPage);      


        return $customerServices;
    }

    public function add(CustomersServicesFormRequest $request): CustomerServices
    {
        // return DB::transaction(function () use ($request) {
        //     $customerService = CustomerServices::create([
        //         'customer_id' => $request->selectedCustomer,
        //         'service_id' => $request->selectedService,
        //         'location_id' => $request->selectedLocation,
        //         'start_date' => $request->start_date,
        //         'end_date' => $request->end_date,
        //         'type' => $request->type,
        //         'alert' => $request->alert
        //     ]);


        //     return $customerService;
        // });

        if($request->allMails == "on"){
            $allMails = 1;
        }
        else {
            $allMails = 0;
        }

        return DB::transaction(function () use ($request, $allMails){
            $customerService = CustomerServices::create([
                'customer_id' => $request->selectedCustomer,
                'service_id' => $request->selectedService,
                'location_id' => $request->selectedLocation,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'alert' => '0',
                'selectedTypeContract' => $request->selectedTypeContract,
                'time_repeat' => $request->time_repeat,
                'number_times' => $request->number_times,
                'allMails' => $allMails,
                'new_date' => $request->new_date,
                'member_associated' => $request->memberAssociated
            ]);

            return $customerService;

        });
    }

    public function update(int $customerService, CustomersServicesFormRequest $request): int
    {
        return DB::transaction(function () use ($customerService,$request) {
            $customerServiceRequest[]= $request->all();

            $arrayCustomerServices = [];

            $serviceRequest = [];
    
            foreach($customerServiceRequest as $req)
            {
                if($req["selectedService"] != "")
                {
                    $serviceRequest["service_id"] = $req["selectedService"];
                    array_push($arrayCustomerServices, $serviceRequest);
                }
                
                if($req["start_date"] != "")
                {
                    $serviceRequest["start_date"] = $req["start_date"];
                    array_push($arrayCustomerServices, $serviceRequest);
                }
                if($req["end_date"] != "")
                {
                    $serviceRequest["end_date"] = $req["end_date"];
                    array_push($arrayCustomerServices, $serviceRequest);
                }
                if($req["type"] != "")
                {
                    $serviceRequest["type"] = $req["type"];
                    array_push($arrayCustomerServices, $serviceRequest);
                }
                if($req["selectedLocation"] != "")
                {
                    $serviceRequest["location_id"] = $req["selectedLocation"];
                    array_push($arrayCustomerServices, $serviceRequest);
                }
                // if($req["alert"] != "")
                // {
                //     $serviceRequest["alert"] = $req["alert"];
                //     array_push($arrayCustomerServices,$serviceRequest);
                // }
                if($req["selectedTypeContract"] != "")
                {
                    $serviceRequest["selectedTypeContract"] = $req["selectedTypeContract"];
                    array_push($arrayCustomerServices,$serviceRequest);
                }
                if($req["time_repeat"] != "")
                {
                    $serviceRequest["time_repeat"] = $req["time_repeat"];
                    array_push($arrayCustomerServices,$serviceRequest);
                }
                if($req["number_times"] != "")
                {
                    $serviceRequest["number_times"] = $req["number_times"];
                    array_push($arrayCustomerServices,$serviceRequest);
                }
                if($req["new_date"] != "")
                {
                    $serviceRequest["new_date"] = $req["new_date"];
                    array_push($arrayCustomerServices, $serviceRequest);
                }
                if(isset($req["allMails"]))
                {
                    if($req["allMails"] != "" || $req["allMails"] == "")
                    {
                        if($req["allMails"] == "on"){
                            $req["allMails"] = 1;
                        }
                        else {
                            $req["allMails"] = 0;
                        }
                        $serviceRequest["allMails"] = $req["allMails"];
                        array_push($arrayCustomerServices,$serviceRequest);
                    }
                }
                if($req["memberAssociated"] != "")
                {
                    $serviceRequest["member_associated"] = $req["memberAssociated"];
                    array_push($arrayCustomerServices, $serviceRequest);
                }
                else if(!isset($req["allMails"]))
                {
                    $serviceRequest["allMails"] = 0;
                    array_push($arrayCustomerServices,$serviceRequest);
                }

                $serviceRequest["alert"] = 0;
                array_push($arrayCustomerServices,$serviceRequest);
            }


            $customerService = CustomerServices::where('id',$customerService)->update(array_pop($arrayCustomerServices));
            return $customerService;
        });


    }

    public function destroy($customerService): int
    {
        return DB::transaction(function () use ($customerService) {
            $customerService = CustomerServices::where('id',$customerService)->delete();
            return $customerService;
        });

    }

    public function getCustomerServices(Customers $customer, string $location)
    {
        return CustomerServices::where('customer_id', $customer->id)
            ->where('location_id', $location)
            ->with('service')
            ->with('customerLocation')
            ->get();
    }

    public function getNotificationTimes(): Array
    {
        $notificationInfo = [];
        $servicesNotifications = CustomerServices::where('end_date', '<=',date('Y-m-d'))->where('treated',1)->with('service')->with('customer')->with('customerLocation')->get();

        foreach($servicesNotifications as $count => $notification)
        {
            $date_of_notification = date('Y-m-d', strtotime('-'.$notification->alert.' day',strtotime($notification->end_date)));
            $notificationInfo[$count] = ["customerServicesId" => $notification->id, "service" => $notification->service->name, "date_final_service" => $notification->end_date, "customer" => $notification->customer->short_name, "customer_county" => $notification->customerLocation->locationDistrict->name, "notification" => $date_of_notification, "tratada" => $notification->treated];
        }

        return $notificationInfo;
    }

    public function changeTreatedStatus($idCustomerService): void
    {
        $customerService = CustomerServices::where('id',$idCustomerService)->update(["treated" => "2"]);

    }
    

}
