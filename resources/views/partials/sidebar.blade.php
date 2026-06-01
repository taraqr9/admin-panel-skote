<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title">Menu</li>

                @forelse($menus as $menu)

                    @php
                        $visibleChildren = $menu->children->filter(function ($child) {
                            return !$child->permission || auth()->user()->can($child->permission);
                        });

                        $hasVisibleChildren = $visibleChildren->isNotEmpty();

                        $menuUrl = '#';

                        if ($menu->route && Route::has($menu->route)) {
                            $menuUrl = route($menu->route);
                        } elseif (!empty($menu->url)) {
                            $menuUrl = url($menu->url);
                        }

                        $isChildActive = $hasVisibleChildren && $visibleChildren->contains(function ($child) {
                            if (!$child->route || !Route::has($child->route)) {
                                return false;
                            }

                            if (request()->routeIs($child->route)) {
                                return true;
                            }

                            if (str($child->route)->endsWith('.index')) {
                                $childRouteBase = str($child->route)->beforeLast('.')->toString();

                                return request()->routeIs($childRouteBase . '.*');
                            }

                            return false;
                        });

                        $isParentRouteActive = false;

                        if ($menu->route && Route::has($menu->route)) {
                            $isParentRouteActive = request()->routeIs($menu->route);

                            if (str($menu->route)->endsWith('.index')) {
                                $menuRouteBase = str($menu->route)->beforeLast('.')->toString();

                                $isParentRouteActive = $isParentRouteActive || request()->routeIs($menuRouteBase . '.*');
                            }
                        }

                        $isActive = $isParentRouteActive || $isChildActive;

                        $canViewParent = !$menu->permission || auth()->user()->can($menu->permission);
                    @endphp

                    @if($canViewParent)

                        @if($menu->children->isNotEmpty())

                            @if($hasVisibleChildren)
                                <li class="{{ $isActive ? 'mm-active' : '' }}">
                                    <a href="javascript:void(0);"
                                       class="has-arrow waves-effect {{ $isActive ? 'mm-active' : '' }}">

                                        @if($menu->icon)
                                            <i class="{{ $menu->icon }}"></i>
                                        @endif

                                        <span>{{ $menu->title }}</span>
                                    </a>

                                    <ul class="sub-menu {{ $isActive ? 'mm-show' : '' }}"
                                        aria-expanded="{{ $isActive ? 'true' : 'false' }}">

                                        @foreach($visibleChildren as $child)

                                            @php
                                                $childUrl = '#';

                                                if ($child->route && Route::has($child->route)) {
                                                    $childUrl = route($child->route);
                                                } elseif (!empty($child->url)) {
                                                    $childUrl = url($child->url);
                                                }

                                                $childActive = false;

                                                if ($child->route && Route::has($child->route)) {
                                                    $childActive = request()->routeIs($child->route);

                                                    if (str($child->route)->endsWith('.index')) {
                                                        $childRouteBase = str($child->route)->beforeLast('.')->toString();

                                                        $childActive = $childActive || request()->routeIs($childRouteBase . '.*');
                                                    }
                                                }
                                            @endphp

                                            <li class="{{ $childActive ? 'mm-active' : '' }}">
                                                <a href="{{ $childUrl }}"
                                                   class="{{ $childActive ? 'active' : '' }}">

                                                    @if($child->icon)
                                                        <i class="{{ $child->icon }}"></i>
                                                    @endif

                                                    <span>{{ $child->title }}</span>
                                                </a>
                                            </li>

                                        @endforeach

                                    </ul>
                                </li>
                            @endif

                        @else

                            @if($menu->route || $menu->url)
                                <li class="{{ $isActive ? 'mm-active' : '' }}">
                                    <a href="{{ $menuUrl }}"
                                       class="waves-effect {{ $isActive ? 'active' : '' }}">

                                        @if($menu->icon)
                                            <i class="{{ $menu->icon }}"></i>
                                        @endif

                                        <span>{{ $menu->title }}</span>
                                    </a>
                                </li>
                            @endif

                        @endif

                    @endif

                @empty

                    <li class="text-muted px-3 py-2">
                        No menu found
                    </li>

                @endforelse

            </ul>
        </div>
    </div>
</div>
