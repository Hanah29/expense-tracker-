@extends('layouts.app-layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Expense') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('expenses.update', $expense) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                            <input id="title" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   type="text" name="title" value="{{ $expense->title }}" required />
                        </div>
                        
                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block font-medium text-sm text-gray-700">Amount</label>
                            <input id="amount" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   type="number" step="0.01" name="amount" value="{{ $expense->amount }}" required />
                        </div>
                        
                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block font-medium text-sm text-gray-700">Category</label>
                            <select id="category_id" name="category_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Expense Date -->
                        <div>
                            <label for="expense_date" class="block font-medium text-sm text-gray-700">Date</label>
                            <input id="expense_date" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   type="date" name="expense_date" value="{{ $expense->expense_date->format('Y-m-d') }}" required />
                        </div>
                        
                        <!-- Type -->
                        <div>
                            <label for="type" class="block font-medium text-sm text-gray-700">Type</label>
                            <select id="type" name="type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="personal" {{ $expense->type == 'personal' ? 'selected' : '' }}>Personal</option>
                                <option value="team" {{ $expense->type == 'team' ? 'selected' : '' }}>Team</option>
                            </select>
                        </div>
                        
                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block font-medium text-sm text-gray-700">Description (Optional)</label>
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $expense->description }}</textarea>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('Update Expense') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection