<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Navigation extends Component
{
    public function render()
    {
        $user = Auth::user();
        $orderQuery = Order::query();
        if ($user->hasRole('merchant')) {
            $user->whereHas('merchant', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        $orders_count = $orderQuery->count() ?? 0;
        $menus = [
            [
                'name' => 'Dashboard',
                'routeName' => 'dashboard',
                'prefixName' => 'dashboard',
                'icon' => 'fas fa-chart-line',
                'info' => null,
                'children' => []
            ],
            [
                'name' => 'Orders',
                'routeName' => 'orders.index',
                'prefixName' => 'orders',
                'icon' => 'fas fa-shopping-cart',
                'info' => $orders_count ?? 0,
                'children' => []
            ],
            [
                'name' => 'Merchants',
                'routeName' => 'merchants.index',
                'prefixName' => 'merchants',
                'icon' => 'far fa-user',
                'info' => null,
                'children' => []
            ],
            [
                'name' => 'Role Permissions',
                'routeName' => '#',
                'prefixName' => 'role_permissions',
                'icon' => 'fas fa-user-shield',
                'info' => null,
                'children' => [
                    [
                        'name' => 'Roles',
                        'routeName' => 'roles.index',
                        'prefixName' => 'roles',
                        'icon' => '',
                        'info' => null,
                        'children' => []
                    ],
                    [
                        'name' => 'Permissions',
                        'routeName' => 'permissions.index',
                        'prefixName' => 'permissions',
                        'icon' => '',
                        'info' => null,
                        'children' => []
                    ],
                    [
                        'name' => 'Users',
                        'routeName' => 'users.index',
                        'prefixName' => 'users',
                        'icon' => '',
                        'info' => null,
                        'children' => []
                    ],
                ]
            ],
            [
                'name' => 'Master Data',
                'routeName' => '#',
                'prefixName' => 'master_data',
                'icon' => 'fas fa-th-large',
                'info' => null,
                'children' => [
                    [
                        'name' => 'Categories',
                        'routeName' => 'categories.index',
                        'prefixName' => 'categories',
                        'icon' => '',
                        'info' => null,
                        'children' => []
                    ],
                    [
                        'name' => 'Products',
                        'routeName' => 'products.index',
                        'prefixName' => 'products',
                        'icon' => '',
                        'info' => null,
                        'children' => []
                    ],
                ]
            ],
        ];

        $routeNow = explode('.', Request::route()->getName());
        $prefixRouteNow = ($routeNow[0] == 'admin') ? $routeNow[1] : $routeNow[0];
        return view('livewire.navigation', compact('orders_count', 'prefixRouteNow', 'menus'));
    }
}
