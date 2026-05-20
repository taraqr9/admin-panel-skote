<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */
        Menu::firstOrCreate(
            [
                'route' => 'roles.index',
            ],
            [
                'title' => 'Role',
                'icon' => 'bx bx-shield-quarter',
                'permission' => 'role-view',
                'serial' => 1,
                'parent_id' => null,
                'is_active' => 1,
            ]
        );

        Menu::firstOrCreate(
            [
                'route' => 'menus.index',
            ],
            [
                'title' => 'Menus',
                'icon' => 'bx bx-menu',
                'permission' => 'menu-view',
                'serial' => 4,
                'parent_id' => null,
                'is_active' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Menu::firstOrCreate(
            [
                'route' => 'dashboard',
            ],
            [
                'title' => 'Dashboard',
                'icon' => 'bx bx-home-circle',
                'permission' => 'dashboard-view',
                'serial' => 2,
                'parent_id' => null,
                'is_active' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */
        Menu::firstOrCreate(
            [
                'route' => 'users.index',
            ],
            [
                'title' => 'Users',
                'icon' => 'bx bx-user-check',
                'permission' => 'user-view',
                'serial' => 3,
                'parent_id' => null,
                'is_active' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | LOGS (PARENT)
        |--------------------------------------------------------------------------
        */
        $logs = Menu::firstOrCreate(
            [
                'title' => 'Logs',
                'parent_id' => null,
            ],
            [
                'icon' => 'bx bx-history',
                'permission' => null,
                'serial' => 4,
                'route' => null,
                'is_active' => 1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CHILD: ACTIVITY LOGS
        |--------------------------------------------------------------------------
        */
        Menu::firstOrCreate(
            [
                'route' => 'logs.activity',
            ],
            [
                'title' => 'Activity Logs',
                'icon' => 'bx bx-list-ul me-1',
                'parent_id' => $logs->id,
                'permission' => 'activity_log-view',
                'serial' => 1,
                'is_active' => 1,
            ]
        );

        Menu::firstOrCreate(
            [
                'route' => 'logs.error',
            ],
            [
                'title' => 'Error Logs',
                'icon' => 'bx bx-error-circle me-1',
                'parent_id' => $logs->id,
                'permission' => 'error_log-view',
                'serial' => 2,
                'is_active' => 1,
            ]
        );
    }
}
