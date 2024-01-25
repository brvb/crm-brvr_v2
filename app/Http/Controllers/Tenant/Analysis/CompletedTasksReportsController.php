<?php

namespace App\Http\Controllers\Tenant\Analysis;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CompletedTasksReportsController extends Controller
{
    /**
     * Display the completed tasks reports summary.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('tenant.analysis.completed', [
            'themeAction' => 'form_element_data_table',
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

}
