<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index()
{
    $user = Auth::user();
    
    // Personal expenses
    $personalExpenses = $user->expenses()
        ->where('type', 'personal')
        ->sum('amount');
    
    // Team expenses (only if user is part of a team)
    $teamExpenses = 0;
    if ($user->team) {
        $teamExpenses = $user->team->expenses()
            ->where('type', 'team')
            ->sum('amount');
    }
    
    // Combined expenses by category
    $expensesByCategory = $user->expenses()
        ->select('categories.name', 'categories.color', DB::raw('SUM(expenses.amount) as total'))
        ->join('categories', 'expenses.category_id', '=', 'categories.id')
        ->groupBy('categories.id', 'categories.name', 'categories.color')
        ->get();
    
    // Recent expenses (both personal and team)
    $recentExpenses = $user->expenses()
        ->with(['category', 'team'])
        ->latest()
        ->limit(5)
        ->get();
    
    return view('dashboard', compact(
        'personalExpenses',
        'teamExpenses',
        'expensesByCategory',
        'recentExpenses'
    ));
}
}
