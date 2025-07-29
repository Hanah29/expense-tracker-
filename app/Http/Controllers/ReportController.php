<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
            $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
            $type = $request->input('type', 'all');
            
            $query = $user->expenses()
                ->with(['category', 'team'])
                ->whereBetween('expense_date', [$startDate, $endDate]);
                
            if ($type !== 'all') {
                $query->where('type', $type);
            }
            
            $totalExpenses = $query->sum('amount');
            $expenseCount = $query->count();
            $avgExpense = $expenseCount > 0 ? $totalExpenses / $expenseCount : 0;
            
            $expensesByCategory = $query->clone()
                ->selectRaw('categories.name, categories.color, SUM(expenses.amount) as total, COUNT(expenses.id) as count')
                ->join('categories', 'expenses.category_id', '=', 'categories.id')
                ->groupBy('categories.id', 'categories.name', 'categories.color')
                ->orderByDesc('total')
                ->get();
                
            $monthlyTrend = collect();
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthTotal = $user->expenses()
                    ->whereYear('expense_date', $month->year)
                    ->whereMonth('expense_date', $month->month)
                    ->when($type !== 'all', fn($q) => $q->where('type', $type))
                    ->sum('amount');
                    
                $monthlyTrend->push([
                    'month' => $month->format('M Y'),
                    'total' => $monthTotal
                ]);
            }
            
            $topExpenses = $query->clone()
                ->orderByDesc('amount')
                ->limit(10)
                ->get();
                
            return view('reports.index', compact(
                'totalExpenses', 'expenseCount', 'avgExpense',
                'expensesByCategory', 'monthlyTrend', 'topExpenses',
                'startDate', 'endDate', 'type'
            ));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Report generation failed: '.$e->getMessage());
        }
    }
    
    public function teamReport(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user->team_id) {
                return redirect()->route('dashboard')->with('error', 'You must join a team first');
            }
            
            $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
            $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
            
            $query = $user->team->expenses()
                ->whereBetween('expense_date', [$startDate, $endDate]);
            
            $totalTeamExpenses = $query->sum('amount');
            $teamExpenseCount = $query->count();
            
            $expensesByMember = $user->team->users()
                ->withSum(['expenses' => fn($q) => $q->whereBetween('expense_date', [$startDate, $endDate])], 'amount')
                ->withCount(['expenses' => fn($q) => $q->whereBetween('expense_date', [$startDate, $endDate])])
                ->get();
                
            $teamExpensesByCategory = $query->clone()
                ->selectRaw('categories.name, categories.color, SUM(expenses.amount) as total')
                ->join('categories', 'expenses.category_id', '=', 'categories.id')
                ->groupBy('categories.id', 'categories.name', 'categories.color')
                ->orderByDesc('total')
                ->get();
                
            return view('reports.team', compact(
                'totalTeamExpenses', 'teamExpenseCount',
                'expensesByMember', 'teamExpensesByCategory',
                'startDate', 'endDate'
            ));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Team report failed: '.$e->getMessage());
        }
    }
}