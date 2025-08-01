@extends('layouts.app-layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="flex justify-end mb-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('expenses.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Expense
        </a>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Personal Expenses Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-1">Personal Expenses</h3>
                                <p class="text-3xl font-bold text-blue-600">${{ number_format($personalExpenses, 2) }}</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Expenses Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-1">Team Expenses</h3>
                                @if(auth()->user()->team)
                                    <p class="text-3xl font-bold text-green-600">${{ number_format($teamExpenses, 2) }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->team->name }}</p>
                                @else
                                    <p class="text-gray-500">Not part of any team</p>
                                    <a href="{{ route('teams.index') }}" class="text-blue-500 hover:text-blue-700 text-sm mt-1 inline-block">
                                        Join a team
                                    </a>
                                @endif
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Expenses by Category -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Expenses by Category</h3>
                            <a href="{{ route('expenses.index') }}" class="text-sm text-blue-500 hover:text-blue-700">View All</a>
                        </div>
                        
                        @forelse($expensesByCategory as $category)
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $category->color }}"></div>
                                        <span class="text-sm font-medium">{{ $category->name }}</span>
                                    </div>
                                    <span class="text-sm font-semibold">${{ number_format($category->total, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    @php
                                        $percentage = ($category->total / max($personalExpenses + $teamExpenses, 1)) * 100;
                                    @endphp
                                    <div class="h-2 rounded-full" style="background-color: {{ $category->color }}; width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-gray-500">No expenses recorded yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Transactions</h3>
                        
                        @forelse($recentExpenses as $expense)
                            <div class="border-b border-gray-100 pb-3 mb-3 last:border-b-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $expense->title }}</h4>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="text-xs px-2 py-1 rounded-full {{ $expense->type === 'personal' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($expense->type) }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $expense->category->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $expense->expense_date->format('M d') }}</span>
                                        </div>
                                    </div>
                                    <span class="font-semibold {{ $expense->amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        ${{ number_format($expense->amount, 2) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2 text-gray-500">No recent transactions</p>
                            </div>
                        @endforelse

                        <div class="mt-4">
                            <a href="{{ route('expenses.index') }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium inline-flex items-center">
                                View all expenses
                                <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection