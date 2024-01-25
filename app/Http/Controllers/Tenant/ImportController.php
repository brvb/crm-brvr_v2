<?php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use App\Models\Tenant\TeamMember;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Events\TeamMember\TeamMemberEvent;
use App\Http\Requests\Tenant\User\UserFormRequest;
use App\Interfaces\Tenant\Profile\ProfileInterface;
use App\Http\Requests\Tenant\TeamMember\TeamMemberFormRequest;
use App\Models\Tenant\CustomerLocations;
use App\Models\Tenant\Customers;

class ImportController extends Controller
{

    /**
     * Display the profile list.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $customers = Customers::get();
        foreach($customers as $item) {
            $loc = new CustomerLocations();
            $loc->description = 'Sede';
            $loc->customer_id = $item->id;
            $loc->main = 1;
            $loc->address = $item->address;
            $loc->zipcode = $item->zipcode;
            $loc->contact = "-";
            $loc->district_id = $item->district;
            $loc->county_id = "0";
            $loc->manager_name = "-";
            $loc->manager_contact = "-";

            $loc->save();
        }
dd($customers);

    }

    /**
     * Change the Team Member from the profile page
     *
     * @param TeamMemberFormRequest $request
     * @return RedirectResponse
     */
    public function store(TeamMemberFormRequest $request): RedirectResponse
    {
        if(isset($request->uploadFile))
        {
            Storage::disk('local')->delete($request->uploadFile);

            if(!Storage::exists(tenant('id') . '/app/profile'))
            {
                File::makeDirectory(storage_path('app/profile'), 0755, true, true);
            }

            $patch = $request->uploadFile->storeAs(tenant('id') . '/app/profile', $request->uploadFile->getClientOriginalName());

            $request->merge(["photo" => $patch]);
        }

        $this->profileRepository->updateMemberInformation($request);

        return to_route('tenant.profile.index')
            ->with('message', __('Profile updated with success!'))
            ->with('status', 'sucess');
    }


   /**
    * Change email and password from the profile page
    *
    * @param UserFormRequest $request
    * @return RedirectResponse
    */
    public function UserInfo(UserFormRequest $request): RedirectResponse
    {

        if($request->password == $request->repeatPassword){
            $this->profileRepository->updateUserInformation($request);
        }
        else{
            return to_route('tenant.profile.index')
                    ->with('message', __('The password and the repeated password dont combine!'))
                    ->with('status', 'error');
        }

        return to_route('tenant.profile.index')
            ->with('message', __('Profile updated with success!'))
            ->with('status', 'sucess');
    }


    /**
     * Create login for the Team Member
     *
     * @param TeamMember $teamMember
     * @return RedirectResponse
     */
    public function show(TeamMember $teamMember): RedirectResponse
    {

        $resultOfLogin = $this->teamMemberRepository->createLogin($teamMember);

        if ($resultOfLogin != null) {
            event(new TeamMemberEvent($resultOfLogin));
        }
        return to_route('tenant.team-member.index')
            ->with('message', __('Team member login created with success!'))
            ->with('status', 'sucess');
    }

}
