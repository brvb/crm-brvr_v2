<?php

namespace App\Http\Controllers\Tenant\FilesCustomer;

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

class FilesCustomerController extends Controller
{
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
        return view('tenant.filescustomer.index', [
            'themeAction' => 'table_datatable_basic',
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }


}
