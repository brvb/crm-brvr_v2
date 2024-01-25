<?php

namespace App\Http\Controllers\Tenant\TeamMember;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Tenant\TeamMember;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Events\TeamMember\TeamMemberEvent;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Http\Requests\Tenant\TeamMember\TeamMemberFormRequest;

class TeamMemberController extends Controller
{
    public TeamMemberInterface $teamMemberRepository;

    public function __construct(TeamMemberInterface $interfaceTeamMember)
    {
        $this->teamMemberRepository = $interfaceTeamMember;
    }


    /**
     * Display the customers list.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('tenant.teammember.index', [
            'themeAction' => 'table_datatable_basic',
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

    /**
     * Open page for creating a Team Member.
     *
     * @return View
     */
    public function create(): View
    {
        $themeAction = 'form_pickers';
        return view('tenant.teammember.create', compact('themeAction'));
    }

    /**
     * Open page for editing an existing Team Member
     *
     * @return \Illuminate\View\View
     */
    public function edit(TeamMember $teamMember): View
    {
        $themeAction = 'form_pickers';
        return view('tenant.teammember.edit', compact('teamMember', 'themeAction'));
    }

    /**
     * Insert a new Team Member
     *
     * @param TeamMemberFormRequest $request
     * @return RedirectResponse
     */
    public function store(TeamMemberFormRequest $request): RedirectResponse
    {
       $this->teamMemberRepository->add($request);

        return to_route('tenant.team-member.index')
            ->with('message', __('Team member created with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Update an existing Team Member
     *
     * @param TeamMember $teamMember
     * @param TeamMemberFormRequest $request
     * @return RedirectResponse
     */
    public function update(TeamMember $teamMember, TeamMemberFormRequest $request): RedirectResponse
    {
        $this->teamMemberRepository->update($teamMember,$request);
       
        return to_route('tenant.team-member.index')
            ->with('message', __('Team member updated with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Delete a Team Member
     *
     * @param TeamMember $teamMember
     * @return RedirectResponse
     */
    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        $this->teamMemberRepository->destroy($teamMember);

        if($teamMember->user_id == Auth::user()->id)
        {
            Auth::guard('web')->logout();    
            return redirect('/login');
        }
     
        return to_route('tenant.team-member.index')
            ->with('message', __('Team member deleted with success!'))
            ->with('status', 'sucess');
    }


    /**
     * Create login for the Team Member
     *
     * @param TeamMember $teamMember
     * @return RedirectResponse
     */
    public function createlogin(string $teamMember): RedirectResponse
    {
        $resultOfLogin = $this->teamMemberRepository->createLogin($teamMember);
        
        event(new TeamMemberEvent($resultOfLogin));
        
        return to_route('tenant.team-member.index')
               ->with('message', __('Team member login was created with success! password: ', ['attribute' => $resultOfLogin->user["password_without_hashed"] ]))
               ->with('status','sucess');
        
    }

}
