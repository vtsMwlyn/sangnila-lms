<?php

namespace App\Providers;

use App\Models\Progress;
use App\Models\CourseStudent;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Gate::define('can_swap_role', function ($user) {
			return $user && $user->can_swap_role == 1;
		});

        Paginator::defaultView('vendor.pagination.tailwind');
    }
}
