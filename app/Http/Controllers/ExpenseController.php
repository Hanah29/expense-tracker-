<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpenseController extends Controller
{   
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $expenses = Auth::user()->expenses()
            ->with(['category', 'team'])
            ->orderBy('expense_date', 'desc')
            ->paginate(10);
            
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      {
        $categories = \App\Models\Category::all(); 
        return view('expenses.create', compact('categories'));
    }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
   $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'expense_date' => 'required|date',
            'type' => 'required|in:personal,team'
        ]);

        $user = Auth::user();

           // Only set team_id if expense type is 'team'
    $teamId = $request->type === 'team' ? $user->team_id : null;
        
        Expense::create([
            'user_id' => $user->id,
           'team_id' => $teamId,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'expense_date' => $request->expense_date,
            'type' => $request->type,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
     $this->authorize('view', $expense);
    return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        $categories = Category::all();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
    $this->authorize('update', $expense);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'expense_date' => 'required|date',
            'type' => 'required|in:personal,team'
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
    $this->authorize('delete', $expense);
    $expense->delete();

    return redirect()->route('expenses.index')
      ->with('success', 'Expense deleted successfully!');
    }
}
