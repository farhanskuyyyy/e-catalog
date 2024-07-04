<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">{{ config('app.name', 'Laravel') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}"></a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('dashboard') }}"><i class="far fa-square"></i> <span>Dashboard</span></a>
            </li>
            <li class="{{ Request::is('order') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('order') }}"><i class="fas fa-shopping-cart"></i> <span>Order</span></a>
            </li>
            <li class="{{ Request::is('user') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('user') }}"><i class="far fa-user"></i> <span>Users</span></a>
            </li>
            <li class="nav-item dropdown {{ $type_menu === 'master-data' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span>Master Data</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('category') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ url('category') }}">Categories</a>
                    </li>
                    <li class='{{ Request::is('product') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ url('product') }}">Products</a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
