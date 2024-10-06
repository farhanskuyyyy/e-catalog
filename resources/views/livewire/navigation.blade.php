<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">{{ config('app.name', 'Laravel') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}"></a>
        </div>
        <ul class="sidebar-menu">
            @forelse ($menus as $menu)
                @if (count($menu['children']) > 0)
                    @php
                        $canChild = [];
                        $prefixNameChild = [];
                        foreach ($menu['children'] as $key => $child) {
                            $canChild[] = "view {$child['prefixName']}";
                            $prefixNameChild[] = $child['prefixName'];
                        }
                    @endphp
                    @canany($canChild)
                        <li class="nav-item dropdown {{ in_array($prefixRouteNow, $prefixNameChild) ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown"><i
                                    class="{{ $menu['icon'] }}"></i><span>{{ $menu['name'] }}</span></a>
                            <ul class="dropdown-menu">
                                @foreach ($menu['children'] as $children)
                                    @canany("view {$children['prefixName']}")
                                        <li class="{{ $prefixRouteNow == $children['prefixName'] ? 'active' : '' }}">
                                            <a class="nav-link"
                                                href="{{ route($children['routeName']) }}">{{ $children['name'] }}</a>
                                        </li>
                                    @endcanany
                                @endforeach
                            </ul>
                        </li>
                    @endcanany
                @else
                    @canany("view {$menu['prefixName']}")
                        <li class="{{ $prefixRouteNow == $menu['prefixName'] ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route($menu['routeName']) }}"><i class="{{ $menu['icon'] }}"></i>
                                <span>{{ $menu['name'] }}</span></a>
                        </li>
                    @endcanany
                @endif
            @empty
            @endforelse
        </ul>
    </aside>
</div>
