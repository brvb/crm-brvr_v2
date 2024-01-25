<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Models\User;
use App\Events\ChatMessage;
use App\Events\LoginStatus;
use Illuminate\Http\Request;
use App\Models\Tenant\TeamMember;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $action = __FUNCTION__;

        return view('tenant.auth.login', compact('action'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $user = User::where('username',$request->username)->first();

        if($user != null)
        {
            $tm = TeamMember::where('user_id',$user->id)->first();

            if($tm->checkstatus == "0")
            {
                return redirect('/login');
            }
        }
    
        
        $request->authenticate();

        $request->session()->regenerate();
       
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
                                   
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    
        return redirect('/login');
        
        
    }
}
