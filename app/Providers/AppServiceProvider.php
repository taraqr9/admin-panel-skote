<?php

namespace App\Providers;

use App\Models\Menu;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.sidebar', function ($view) {
            $menus = Menu::query()
                ->whereNull('parent_id')
                ->where('is_active', 1)
                ->orderBy('serial')
                ->with([
                    'children' => function ($q) {
                        $q->where('is_active', 1)
                            ->orderBy('serial');
                    },
                ])
                ->get();

            $view->with('menus', $menus);
        });
    }
}
