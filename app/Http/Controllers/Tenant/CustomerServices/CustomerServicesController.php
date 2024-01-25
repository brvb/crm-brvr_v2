<?php

namespace App\Http\Controllers\Tenant\CustomerServices;

use App\Models\Counties;
use App\Models\Districts;
use Illuminate\Http\Request;
use App\Models\Tenant\Services;
use App\Models\Tenant\Customers;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\Tenant\CustomerServices;

use App\Models\Tenant\CustomerLocations;
use App\Http\Requests\Tenant\Customers\CustomersFormRequest;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;
use App\Http\Requests\Tenant\CustomersServices\CustomersServicesFormRequest;

class CustomerServicesController extends Controller
{

    private CustomerServicesInterface $customerServicesRepository;

    public function __construct(CustomerServicesInterface $customersServicesRepository)
    {
        $this->customerServicesRepository = $customersServicesRepository;
    }
    /**
     * Display the customers list.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('tenant.customerservices.index', [
            'themeAction' => 'table_datatable_basic',
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

    /**
     * Create customer.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('tenant.customerservices.create', [
            'themeAction' => 'form_element',
            'customerList' => Customers::all(),
            'serviceList' => Services::all(),
            'selectedCustomer' => '',
            'selectedService' => '',
            'customer' => '',
        ]);
    }

    /**
     * Edit a customer service
     *
     * @param CustomerServices $service
     * @return View
     */
    public function edit(CustomerServices $service): View
    {    
        $customerOfService = CustomerLocations::where('customer_id',$service->customer_id)->first();
        $clientForService = Customers::where('id',$customerOfService->customer_id)->first();

        return view('tenant.customerservices.edit',
            [
                'service' => $service,
                'customer' => $clientForService->short_name,
                'themeAction' => 'form_element_data_table'
            ]
        );
    }

    /**
     * Stores in database the customerService
     *
     * @param CustomersServicesFormRequest $request
     * @return RedirectResponse
     */
    public function store(CustomersServicesFormRequest $request): RedirectResponse
    { 
        $getCustomer = CustomerServices::where('customer_id',$request->selectedCustomer)
                            ->where('service_id',$request->selectedService)
                            ->where('location_id',$request->selectedLocation)
                            ->first();

        if($getCustomer == null)
        {
            $this->customerServicesRepository->add($request);      
            return to_route('tenant.services.index')
                ->with('message', __('Customer Service created with success!'))
                ->with('status', 'sucess');
        }
        else 
        {
            return to_route('tenant.services.index')
            ->with('message', __('That service is already associated with that customer location!'))
            ->with('status', 'error');
        }

        
    }

    /**
     * Updates a customerService
     *
     * @param int $customerService
     * @param CustomersServicesFormRequest $request
     * @return RedirectResponse
     */
    public function update(int $customerService, CustomersServicesFormRequest $request): RedirectResponse
    {
        $this->customerServicesRepository->update($customerService,$request);

        return to_route('tenant.services.index')
            ->with('message', __('Customer Service updated with success!'))
            ->with('status', 'sucess');
    }

   /**
    * Delete a service customer
    *
    * @param int $customerService
    * @return RedirectResponse
    */
    public function destroy(int $customerService): RedirectResponse
    {
        $this->customerServicesRepository->destroy($customerService);

        return to_route('tenant.services.index')
            ->with('message', __('Customer Service deleted with success!'))
            ->with('status', 'sucess');
    }

}
