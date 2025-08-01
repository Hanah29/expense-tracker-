<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTeamMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Check if user is authenticated and has a team
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->team_id) {
            return redirect()->route('teams.index')
                ->with('error', 'You need to join a team before accessing this feature.');
        }
        return $next($request);
    }
}
