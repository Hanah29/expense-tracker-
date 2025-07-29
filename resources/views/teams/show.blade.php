<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">{{ $team->name }}</h2>
                <p class="text-lg mb-6">Total Spending: ${{ number_format($currentSpending, 2) }}</p>
                
                <h3 class="text-xl font-semibold mb-3">Members</h3>
                <ul class="space-y-2">
                    @foreach($members as $member)
                        <li class="flex justify-between">
                            <span>{{ $member->name }}</span>
                            <span>{{ $member->expenses_count }} expenses</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>