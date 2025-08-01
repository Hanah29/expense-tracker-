<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\CategoryLimit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryLimitController extends Controller
{
    use AuthorizesRequests;
    // public function index()
    // {
    //     $user = Auth::user();
        
    //     if (!$user->team) {
    //         return redirect()->route('teams.index')
    //             ->with('error', 'You need to be part of a team to manage category limits.');
    //     }

    //     $limits = CategoryLimit::with('category')
    //         ->where('team_id', $user->team_id)
    //         ->get();
            
    //     $categories = Category::all();
        
    //     return view('category-limits.index', compact('limits', 'categories'));
    // }

// public function create()
// {
//     $categories = \App\Models\Category::all();
//     $limits = auth()->user()->team->categoryLimits ?? collect();
//     return view('category-limits.create', compact('categories', 'limits'));
// }

// public function index()
// {
//     return redirect()->route('category-limits.create');
// }
 public function index()
    {
    
    $user = Auth::user();
    
    if (!$user->team) {
        return redirect()->route('teams.index')
            ->with('error', 'You need to be part of a team to manage category limits.');
    }

    $limits = CategoryLimit::with('category')
        ->where('team_id', $user->team_id)
        ->get();
        
    $categories = Category::all();
    
    return view('category-limits.create', compact('limits', 'categories'));
    }

    public function create()
    {
      return $this->index();
    }

    public function store(Request $request)
    {   $this->authorize('manage', CategoryLimit::class);
        $user = Auth::user();
        
        if (!$user->team) {
            return redirect()->route('teams.index')
                ->with('error', 'You need to be part of a team to set category limits.');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'limit_amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:monthly,yearly'
        ]);

        CategoryLimit::updateOrCreate(
            [
                'team_id' => $user->team_id,
                'category_id' => $request->category_id
            ],
            [
                'limit_amount' => $request->limit_amount,
                'period' => $request->period
            ]
        );

        return redirect()->route('category-limits.index')
            ->with('success', 'Category limit set successfully!');
    }

    public function destroy(CategoryLimit $categoryLimit)
    {    
         $this->authorize('manage', $categoryLimit);
        $categoryLimit->delete();
        
        return redirect()->route('category-limits.index')
            ->with('success', 'Category limit removed successfully!');
    }
}