{{-- resources/views/layouts/navigation.blade.php --}}
<!-- Add these links to your navigation menu -->
<x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    {{ __('Dashboard') }}
</x-nav-link>
<x-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
    {{ __('Expenses') }}
</x-nav-link>
<x-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')">
    {{ __('Teams') }}
</x-nav-link>
<x-nav-link :href="route('category-limits.index')" :active="request()->routeIs('category-limits.*')">
    {{ __('Limits') }}
</x-nav-link>
<x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
    {{ __('Reports') }}
</x-nav-link>