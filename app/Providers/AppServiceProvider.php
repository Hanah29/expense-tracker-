<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use App\Models\Expense;
use App\Policies\ExpensePolicy;
use App\Models\CategoryLimit;
use App\Models\Team;
use App\Policies\CategoryLimitPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    protected $policies = [
        Expense::class => ExpensePolicy::class,
        Team::class => TeamPolicy::class,
        CategoryLimit::class => CategoryLimitPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         $this->registerPolicies();
    }
}
