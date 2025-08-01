<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Updated to use pagination with 10 items per page
        $teams = Team::with(['users', 'expenses'])->paginate(10);
        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $team = Team::create($request->all());
        
        // Add creator to team
        Auth::user()->update(['team_id' => $team->id]);

        return redirect()->route('teams.index')
            ->with('success', 'Team created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {   
        $this->authorize('view', $team);
        $currentSpending = $team->expenses()->sum('amount');
        $members = $team->users()
            ->withCount(['expenses' => function($query) {
                $query->where('type', 'team');
            }])
            ->paginate(5); // Added pagination for members
        
        return view('teams.show', compact('team', 'currentSpending', 'members'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        $this->authorize('update', $team);
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $team->update($request->all());

        return redirect()->route('teams.index')
            ->with('success', 'Team updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        // Remove team association from users
        $this->authorize('delete', $team);
        $team->users()->update(['team_id' => null]);
        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully!');
    }

    public function join(Request $request, Team $team)
    {
        Auth::user()->update(['team_id' => $team->id]);
        
        return redirect()->route('teams.show', $team)
            ->with('success', 'Successfully joined the team!');
    }

    public function leave(Team $team)
    {
        Auth::user()->update(['team_id' => null]);
        
        return redirect()->route('teams.index')
            ->with('success', 'Successfully left the team!');
    }
}