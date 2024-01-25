<?php

namespace App\Repositories\Tenant\Setup\Services;

use App\Models\Tenant\Services;
use App\Models\Tenant\CustomTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\Setup\Services\ServicesInterface;
use App\Http\Requests\Tenant\Setup\Services\ServicesFormRequest;
use App\Models\Tenant\CustomerServices;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver;

class ServicesRepository implements ServicesInterface
{
    public function getAllServices($perPage): LengthAwarePaginator
    {
        $service = Services::with('serviceType')->with('paymentType')->paginate($perPage);
        return $service;
    }

    public function getSearchedService($searchString,$perPage): LengthAwarePaginator
    {
        $services = Services::where('name', 'like', '%' . $searchString . '%')
                    ->orWhere('description', 'like', '%' . $searchString . '%')
                    ->orWhere('description', 'like', '%' . $searchString . '%')
                    ->paginate($perPage);

        return $services;
    }

    public function getServicesAnalysis(): Collection
    {
        $services = Services::all();
        return $services;
    }

    public function getServicesTypes(): Collection
    {
        $services = CustomTypes::where('controller','setup.services')
            ->where('field_name', 'type')
            ->get();

        return $services;
    }

    public function getServicesPayments(): Collection
    {
        $services = CustomTypes::where('controller','setup.services')
            ->where('field_name', 'payment')
            ->get();

        return $services;
    }

    public function add(ServicesFormRequest $request): Services
    {      
        if(!isset($request->patch))
        {
            $request->patch = NULL;
        }

        $service = Services::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'file' => $request->patch,
            'payment' => $request->payment,
            'periodicity' => $request->periodicity,
            'alert' => $request->alert,
        ]);

        return $service;
    }

    public function update(Services $service,ServicesFormRequest $request): int
    {
        if(isset($request->patch))
        {
            $service = Services::where('id',$service->id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'file' => $request->patch,
                'payment' => $request->payment,
                'periodicity' => $request->periodicity,
                'alert' => $request->alert,
            ]);
        }
        else
        {
            $service = Services::where('id',$service->id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'payment' => $request->payment,
                'periodicity' => $request->periodicity,
                'alert' => $request->alert,
            ]);
        }
      

        return $service;
    }

    public function destroy(Services $service): Services
    {
        return DB::transaction(function () use ($service) {

        CustomerServices::where('service_id',$service->id)->delete();    

        $service->delete();
        
        return $service;
        });
    }

}
