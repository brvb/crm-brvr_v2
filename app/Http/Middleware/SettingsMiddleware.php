<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SettingsMiddleware
{
   protected $cmsSettings = NULL;

   public function handle($request, Closure $next) {
      $obj = \App\Models\Tenant\Config::first()->toArray();
      $request->session()->put($obj);
      return $next($request);
   }

    //  public function terminate($request, $response) {
    //     echo "<br>Executing statements of terminate method of TerminateMiddleware.";
    //  }
}
