<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use GeneralTrait;

        public function handle($request, Closure $next, $guard = null)
        {
            Auth::shouldUse($guard);
            if (!Auth::guard($guard)->check()) {
                return $this->returnError('401','unauthorized');
            }
            return $next($request);
        }    }
