<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            @if(Auth::user()->role_id == config('quickadmin.defaultRole'))
                <li @if(Request::path() == config('quickadmin.route').'/menu') class="active" @endif>
                    <a href="{{ url(config('quickadmin.route').'/menu') }}">
                        <i class="fa fa-list"></i>
                        <span class="title">Menu</span>
                    </a>
                </li>
            @endif
            @foreach($menus as $menu)
                @if($menu->menu_type != 2 && is_null($menu->parent_id))
                    @if(in_array(Auth::user()->role_id, explode(',',$menu->roles)))
                        <li @if(isset(explode('/',Request::path())[1]) && explode('/',Request::path())[1] == strtolower($menu->name)) class="active" @endif>
                            <a href="{{ route(config('quickadmin.route').'.'.strtolower($menu->name).'.index') }}">
                                <i class="fa {{ $menu->icon }}"></i>
                                <span class="title">{{ $menu->title }}</span>
                            </a>
                        </li>
                    @endif
                @else
                    @if(in_array(Auth::user()->role_id, explode(',',$menu->roles)) && !is_null($menu->children()->first()) && is_null($menu->parent_id))
                        <li>
                            <a href="#">
                                <i class="fa {{ $menu->icon }}"></i>
                                <span class="title">{{ $menu->title }}</span>
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @foreach($menu['children'] as $child)
                                    @if(in_array(Auth::user()->role_id, explode(',',$child->roles)))
                                        @if(strtolower($child->name) == 'users')
                                            <li @if(isset(explode('/',Request::path())[0]) && explode('/',Request::path())[0] == 'users') class="active active-sub" @endif @if(Request::path() == 'users') class="active" @endif>
                                                <a href="{{ url(strtolower($child->name)) }}">
                                                    <i class="fa {{ $child->icon }}"></i>
                                                    <span class="title">{{ $child->title }}</span>
                                                </a>
                                            </li>
                                        @elseif(strtolower($child->name) == 'actions')
                                            <li @if(isset(explode('/',Request::path())[1]) && explode('/',Request::path())[1] == 'actions') class="active active-sub" @endif @if(Request::path() == config('quickadmin.route').'/actions') class="active" @endif>
                                                <a href="{{ url(config('quickadmin.route') . '/' . strtolower($child->name)) }}">
                                                    <i class="fa {{ $child->icon }}"></i>
                                                    <span class="title">{{ $child->title }}</span>
                                                </a>
                                            </li>
                                        @else
                                            <li @if(isset(explode('/',Request::path())[1]) && explode('/',Request::path())[1] == strtolower($child->name)) class="active active-sub" @endif>
                                                <a href="{{ route(config('quickadmin.route') . '.' . strtolower($child->name) . '.index') }}">
                                                    <i class="fa {{ $child->icon }}"></i>
                                                    <span class="title"> {{ $child->title }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endif
            @endforeach

            <li>
                <a href="{{ url('logout') }}">
                    <i class="fa fa-sign-out fa-fw"></i>
                    <span class="title">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>