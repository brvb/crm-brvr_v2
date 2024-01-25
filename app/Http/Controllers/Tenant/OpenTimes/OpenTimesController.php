<?php

namespace App\Http\Controllers\Tenant\OpenTimes;
use App\Models\Tenant\Tasks;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;

class OpenTimesController extends Controller
{

    private TeamMemberInterface $teamMemberRepository;

    private CustomerServicesInterface $customerServiceRepository;

    public function __construct(TeamMemberInterface $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    /**
     * Display the dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
         //$teamMember = $this->teamMemberRepository->getAllTeamMembers($perPage);

        // $tasks = Tasks::with('tech')->with('taskCustomer')->get();
        
        $themeAction = "form_element";
        return view('tenant.opentimes.index', ['themeAction' => $themeAction]);
    }

}
