@extends('layouts.app-layout')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success message -->
            @if(session('success'))
                <div class="p-4 mb-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add New Limit Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Set Category Limit</h3>
                    <form action="{{ route('category-limits.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" id="category_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                        required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="limit_amount" class="block text-sm font-medium text-gray-700">Limit Amount ($)</label>
                                <input type="number" name="limit_amount" id="limit_amount" step="0.01" min="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                       value="{{ old('limit_amount') }}"
                                       oninput="this.value = this.value < 0.01 ? 0.01 : this.value"
                                       required>
                                @error('limit_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="period" class="block text-sm font-medium text-gray-700">Period</label>
                                <select name="period" id="period" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                        required>
                                    <option value="monthly" {{ old('period', 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ old('period') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                                @error('period')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Set Limit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Limits -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Current Category Limits</h3>
                    @if($limits->count() > 0)
                        <div class="space-y-4">
                            @foreach($limits as $limit)
                                @php
                                    $currentSpending = auth()->user()->team->expenses()
                                        ->where('category_id', $limit->category_id)
                                        ->where('expense_date', '>=', $limit->period === 'monthly' ? now()->startOfMonth() : now()->startOfYear())
                                        ->sum('amount');
                                    $percentage = ($currentSpending / $limit->limit_amount) * 100;
                                    $isOverLimit = $currentSpending > $limit->limit_amount;
                                @endphp
                                
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $limit->category->color }}"></div>
                                            <h4 class="font-medium">{{ $limit->category->name }}</h4>
                                            <span class="ml-2 text-sm text-gray-500">({{ ucfirst($limit->period) }})</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm {{ $isOverLimit ? 'text-red-600' : 'text-gray-600' }}">
                                                ${{ number_format($currentSpending, 2) }} / ${{ number_format($limit->limit_amount, 2) }}
                                            </span>
                                            <form action="{{ route('category-limits.destroy', $limit) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm"
                                                        onclick="return confirm('Are you sure?')">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $isOverLimit ? 'bg-red-500' : ($percentage > 80 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                             style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                    
                                    <div class="mt-1 text-sm {{ $isOverLimit ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ number_format($percentage, 1) }}% of limit used
                                        @if($isOverLimit)
                                            (Over limit by ${{ number_format($currentSpending - $limit->limit_amount, 2) }})
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No category limits are set. Add one above to start tracking spending limits.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection