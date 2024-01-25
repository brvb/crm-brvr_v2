<?php

namespace App\Http\Controllers\Tenant\Profile;

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

class ProfileController extends Controller
{
    public ProfileInterface $profileRepository;

    public function __construct(ProfileInterface $interfaceProfile)
    {
     $this->profileRepository = $interfaceProfile;
    }

    /**
     * Display the profile list.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        if (Auth::user()->type_user != '2')
        {
            $teamMember = $this->profileRepository->getMemberInformation(Auth::user()->id);
        }
        else{
            $teamMember = [];
        }
        
        return view('tenant.profile.index', [
            'themeAction' => 'form_pickers',
            'teamMember' => $teamMember,
            'status' => session('status'),
            'message' => session('message'),
        ]);
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
    
            if(!Storage::exists(tenant('id') . '/app/public/profile'))
            {
                File::makeDirectory(storage_path('app/public/profile'), 0755, true, true);
            }

            $patch = $request->uploadFile->storeAs(tenant('id') . '/app/public/profile', $request->uploadFile->getClientOriginalName());
            //$patch
            $request->merge(["photo" => $request->uploadFile->getClientOriginalName()]);
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
        if(isset($request->uploadFile))
        {
            Storage::disk('local')->delete($request->uploadFile);
    
            if(!Storage::exists(tenant('id') . '/app/public/profile'))
            {
                File::makeDirectory(storage_path('app/public/profile'), 0755, true, true);
            }

            $patch = $request->uploadFile->storeAs(tenant('id') . '/app/public/profile', $request->uploadFile->getClientOriginalName());
            //$patch
            $request->merge(["photo" => $request->uploadFile->getClientOriginalName()]);
        }
        
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
