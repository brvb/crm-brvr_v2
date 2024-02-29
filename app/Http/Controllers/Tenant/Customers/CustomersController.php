<?php

namespace App\Http\Controllers\Tenant\Customers;

use App\Models\Counties;
use App\Models\Districts;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TeamMember;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Tenant\ContactsCustomers;
use App\Models\Tenant\CustomerLocations;
use App\Events\TeamMember\TeamMemberEvent;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Http\Requests\Tenant\Customers\CustomersFormRequest;
use App\Http\Requests\Tenant\CustomerContacts\CustomerContactsFormRequest;

class CustomersController extends Controller
{
    private $customerInfo;
    private CustomersInterface $customersRepository;

    public function __construct(CustomersInterface $customersRepository)
    {
        $this->customersRepository = $customersRepository;
    }

    /**
     * Display the customers list.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('tenant.customers.index', [
            'themeAction' => 'table_datatable_basic',
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

    /**
     * Create new customer form
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        
        $districts = Districts::all();
       
        $counties = Counties::all();
        $district = '';
        $county = '';
        $themeAction = 'form_element';
        $allAccountManagers = TeamMember::all();

        return view('tenant.customers.create', compact('themeAction', 'districts', 'counties', 'district', 'county', 'allAccountManagers'));
    }

    /**
     * Edit customer form
     *
     * @param Customers $customer
     * @return void
     */
    public function edit($customer) : View
    {
        //$customer = Customers::where('id',$customer->id)->with('customerCounty')->first();


        $customerInfo = $this->customersRepository->getSpecificCustomerInfo($customer);
   
           
        $themeAction = 'form_element_data_table';
       
        return view('tenant.customers.edit', compact('customerInfo', 'themeAction'));
    }

    /**
     * Insert a costumer
     *
     * @param CustomersFormRequest $request
     * @return RedirectResponse
     */
    public function store(CustomersFormRequest $request) : RedirectResponse
    {
        $this->customersRepository->add($request);

        if(Auth::user()->type_user == '2')
        {
            $customer = Customers::where('user_id',Auth::user()->id)->first();
            return to_route('tenant.customers.edit',$customer->slug)
            ->with('message', __('Customer updated with success!'))
            ->with('status', 'sucess');
        }
        return to_route('tenant.customers.index')
            ->with('message', __('Customer created with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Update a costumer
     *
     * @param Customers $customer
     * @param CustomersFormRequest $request
     * @return RedirectResponse
     */
    public function update($noClient,CustomersFormRequest $request) : RedirectResponse
    {

        $this->customersRepository->update($noClient,$request);
        
        if(Auth::user()->type_user == '2')
        {
            $customer = Customers::where('user_id',Auth::user()->id)->first();
            return to_route('tenant.customers.edit',$customer->slug)
            ->with('message', __('Customer updated with success!'))
            ->with('status', 'sucess');
        }
        return to_route('tenant.customers.index')
            ->with('message', __('Customer updated with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Delete a customer
     *
     * @param Customers $customer
     * @return RedirectResponse
     */
    public function destroy(Customers $customer) : RedirectResponse
    {
        $this->customersRepository->destroy($customer);

        if(Auth::user()->type_user == '2')
        {
            $customer = Customers::where('user_id',Auth::user()->id)->first();
            return to_route('tenant.customers.edit',$customer->id)
            ->with('message', __('Customer updated with success!'))
            ->with('status', 'sucess');
        }

        return to_route('tenant.customers.index')
            ->with('message', __('Customer deleted with success!'))
            ->with('status', 'sucess');
    }

     /**
     * Create login for the Team Member
     *
     * @param TeamMember $teamMember
     * @return RedirectResponse
     */
    public function createloginCustomer(string $customer): RedirectResponse
    {
        $resultOfLogin = $this->customersRepository->createLogin($customer);
        
        event(new TeamMemberEvent($resultOfLogin));

        if(Auth::user()->type_user == '2')
        {
            $customer = Customers::where('user_id',Auth::user()->id)->first();
            return to_route('tenant.customers.edit',$customer->id)
            ->with('message', __('Customer updated with success!'))
            ->with('status', 'sucess');
        }
        
        return to_route('tenant.customers.index')
               ->with('message', __('Team member login was created with success! password: ', ['attribute' => $resultOfLogin->user["password_without_hashed"] ]))
               ->with('status','sucess');
        
    }

}
