<?php

namespace App\Http\Controllers\Tenant\AnalysisDashboard;

use App\Models\Tenant\Tasks;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;

class AnalysisDashboardController extends Controller
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
        
        $themeAction = "form_element";
        return view('tenant.analysisdashboard.index', ['themeAction' => $themeAction]);
    }

}
