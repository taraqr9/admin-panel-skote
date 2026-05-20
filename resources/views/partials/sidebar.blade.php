<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title">Menu</li>

                @forelse($menus as $menu)

                    @php
                        $hasChildren = $menu->children?->isNotEmpty();

                        $menuUrl = '#';

                        if ($menu->route && Route::has($menu->route)) {
                            $menuUrl = route($menu->route);
                        } elseif (!empty($menu->url)) {
                            $menuUrl = url($menu->url);
                        }

                        $isChildActive = $hasChildren && $menu->children->contains(function ($child) {
                            return $child->route && Route::has($child->route) && request()->routeIs($child->route);
                        });

                        $isActive = (
                            $menu->route &&
                            Route::has($menu->route) &&
                            request()->routeIs($menu->route)
                        ) || $isChildActive;
                    @endphp

                    @if(!$menu->permission || auth()->user()->can($menu->permission))

                        @if($hasChildren)

                            <li class="{{ $isActive ? 'mm-active' : '' }}">
                                <a href="javascript:void(0);" class="has-arrow waves-effect">

                                    @if($menu->icon)
                                        <i class="{{ $menu->icon }}"></i>
                                    @endif

                                    <span>{{ $menu->title }}</span>
                                </a>

                                <ul class="sub-menu" aria-expanded="{{ $isActive ? 'true' : 'false' }}">

                                    @foreach($menu->children as $child)

                                        @if(!$child->permission || auth()->user()->can($child->permission))

                                            @php
                                                $childUrl = '#';

                                                if ($child->route && Route::has($child->route)) {
                                                    $childUrl = route($child->route);
                                                } elseif (!empty($child->url)) {
                                                    $childUrl = url($child->url);
                                                }

                                                $childActive = $child->route &&
                                                    Route::has($child->route) &&
                                                    request()->routeIs($child->route);
                                            @endphp

                                            <li class="{{ $childActive ? 'mm-active' : '' }}">
                                                <a href="{{ $childUrl }}">

                                                    @if($child->icon)
                                                        <i class="{{ $child->icon }}"></i>
                                                    @endif

                                                    <span>{{ $child->title }}</span>
                                                </a>
                                            </li>

                                        @endif

                                    @endforeach

                                </ul>
                            </li>

                        @else

                            @if($menu->route || $menu->url)
                                <li class="{{ $isActive ? 'mm-active' : '' }}">
                                    <a href="{{ $menuUrl }}" class="waves-effect">

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
