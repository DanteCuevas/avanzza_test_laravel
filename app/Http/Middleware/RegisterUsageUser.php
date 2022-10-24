<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Usage;

class RegisterUsageUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Usage::create([
            'url'       => $request->url(),
            'method'    => $request->route()->methods()[0],
            'action'    => $request->route()->getActionName(),
            'user_id'   => auth()->user()->id            
        ]);

        return $next($request);
    }
}
