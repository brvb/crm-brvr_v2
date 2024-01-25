<?php

namespace App\Http\Controllers\Tenant\Setup;

use Illuminate\Http\Request;
use App\Models\Tenant\Services;
use App\Models\Tenant\CustomTypes;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Tenant\Setup\Services\ServicesInterface;
use App\Repositories\Tenant\Setup\Services\ServicesRepository;
use App\Http\Requests\Tenant\Setup\Services\ServicesFormRequest;

class ServicesController extends Controller
{
    private ServicesInterface $servicesRepository;

    public function __construct(ServicesInterface $serviceRepository)
    {
        $this->servicesRepository = $serviceRepository;
    }

      /**
     * Display the services list.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('tenant.setup.services.index', [
            'themeAction' => 'table_datatable_basic',
            'status' => session('status'),
            'message' => session('message'),
            ]);
    }

     /**
      * Display create page for services
      *
      * @param Request $request
      * @return View
      */
    public function create(Request $request): View
    {
      
        $typeList = $this->servicesRepository->getServicesTypes();
       
        $paymentList = $this->servicesRepository->getServicesPayments();

        $themeAction = 'form_element';
        return view('tenant.setup.services.create', compact('themeAction', 'typeList', 'paymentList'));
    }

    /**
     * Display edit page for services
     *
     * @param Services $service
     * @return View
     */
    public function edit(Services $service): View
    {
        $typeList = $this->servicesRepository->getServicesTypes();

        $paymentList = $this->servicesRepository->getServicesPayments();

        $themeAction = 'form_element';
        return view('tenant.setup.services.edit', compact('service', 'themeAction', 'typeList', 'paymentList'));
    }

    /**
     * Store information in database
     *
     * @param ServicesFormRequest $request
     * @return RedirectResponse
     */
    public function store(ServicesFormRequest $request): RedirectResponse
    {
        $requestAll = $request->all();

        
        $patch = '';
        $jsonFile =[];
        $count = 0;

                
        if(isset($requestAll["fileName"]))
        {
            foreach($requestAll["fileName"] as $fn => $fileName)
            {
              $jsonFile[$fn] = ["fileFolder" => $requestAll["fileFolder"][$fn],"ficheiro" => $fileName,"size" => $requestAll["fileSize"][$fn]];  
            }
            $request->merge(["patch" => json_encode($jsonFile)]);
        }
        else
        {
            $request->merge(["patch" => Null]);
        }

        
       
        
        $this->servicesRepository->add($request);


        return to_route('tenant.setup.services.index')
            ->with('message', __('Service created with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Update information from a service
     *
     * @param Services $service
     * @param ServicesFormRequest $request
     * @return RedirectResponse
     */
    public function update(Services $service, ServicesFormRequest $request): RedirectResponse
    {
        $requestAll = $request->all();

        
        $patch = '';
        $jsonFile =[];
        $count = 0;

       
                
        if(isset($requestAll["fileName"]))
        {
            foreach($requestAll["fileName"] as $fn => $fileName)
            {
              $jsonFile[$fn] = ["fileFolder" => $requestAll["fileFolder"][$fn],"ficheiro" => $fileName,"size" => $requestAll["fileSize"][$fn]];  
            }
            $request->merge(["patch" => json_encode($jsonFile)]);
        }
        else
        {
            $request->merge(["patch" => ""]);
        }


              

        $this->servicesRepository->update($service,$request);

        return to_route('tenant.setup.services.index')
            ->with('message', __('Service updated with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Delete a service
     *
     * @param Services $service
     * @return RedirectResponse
     */

    public function destroy(Services $service): RedirectResponse
    {
        //$service->delete();
        $this->servicesRepository->destroy($service);
        return to_route('tenant.setup.services.index')
            ->with('message', __('Service deleted with success!'))
            ->with('status', 'sucess');
    }

}
