<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu {!! Auth::user()->type !!}"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            @if(Auth::user()->role_id == config('quickadmin.defaultRole'))
                <li @if(Request::path() == config('quickadmin.route') . '/menu') class="active" @endif>
                    <a href="{{ url(config('quickadmin.route') . '/menu') }}">
                        <i class="fa fa-list"></i>
                        <span class="title">Menu</span>
                    </a>
                </li>
            @endif
            @foreach($menus as $menu)
                @if($menu->menu_type != 2 && is_null($menu->parent_id))
                    @if(in_array(Auth::user()->role_id, explode(',',$menu->roles)))
                        <li @if(isset(explode('/', Request::path())[1]) && explode('/', Request::path())[1] == strtolower($menu->name)) class="active" @endif>
                            <a href="{{ route(config('quickadmin.route') . '.'.strtolower($menu->name).'.index') }}">
                                <i class="fa {{ $menu->icon }}"></i>
                                <span class="title">{{ $menu->title }}</span>
                            </a>
                        </li>
                    @endif
                @else
                    @if(in_array(Auth::user()->role_id, explode(',',$menu->roles)) && !is_null($menu->children()->first()) && is_null($menu->parent_id))
                        @if(!preg_match('/' . Auth::user()->type . '/', strtolower($menu->name)))
                        <li class="{!! strtolower($menu->name) !!}" @if('User management' == $menu->title) class="separator" @elseif(isset(explode('/', Request::path())[1]) && explode('/', Request::path())[1] == strtolower($menu->name)) class="active" @endif>
                            <a href="#">
                                <i class="fa {{ $menu->icon }}"></i>
                                <span class="title">{{ $menu->title }}</span>
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @foreach($menu['children'] as $child)
                                    @if(in_array(Auth::user()->role_id, explode(',',$child->roles)))
                                        @if($child->menu_type == 2 )
                                            @if(!preg_match('/' . Auth::user()->type . '/', strtolower($child->name)))
                                            <li @if(isset(explode('/', Request::path())[1]) && explode('/', Request::path())[1] == strtolower($child->name)) class="active active-sub {!! strtolower($child->name) !!}" @else class="{!! strtolower($child->name) !!}"@endif>
                                                <a href="#">
                                                    <i class="fa {{ $child->icon }}"></i>
                                                    <span class="title">{{ $child->title }}</span>
                                                    <span class="fa arrow"></span>
                                                </a>
                                                <ul class="sub-menu">
                                                    @foreach($child['children'] as $subchild)
                                                        @if(in_array(Auth::user()->role_id, explode(',',$subchild->roles)))
                                                            <li @if(isset(explode('/', Request::path())[1]) && explode('/', Request::path())[1] == strtolower($subchild->name)) class="active active-subsub" @endif>
                                                                <a href="{{ route(config('quickadmin.route') . '.' . strtolower($subchild->name) . '.index') }}">
                                                                    <i class="fa {{ $subchild->icon }}"></i>
                                                                    <span class="title"> {{ $subchild->title }}</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                            @endif
                                        @elseif('users' == strtolower($child->name))
                                            <li @if(isset(explode('/', Request::path())[0]) && explode('/', Request::path())[0] == 'users') class="active active-sub" @endif @if(Request::path() == 'users') class="active" @endif>
                                                <a href="{{ url('/' . strtolower($child->name)) }}">
                                                    <i class="fa {{ $child->icon }}"></i>
                                                    <span class="title">{{ $child->title }}</span>
                                                </a>
                                            </li>
                                        @elseif('actions' == strtolower($child->name))
                                            <li @if(isset(explode('/', Request::path())[1]) && explode('/', Request::path())[1] == 'actions') class="active active-sub" @endif @if(Request::path() == config('quickadmin.route') . '/actions') class="active" @endif>
                                                <a href="{{ url(config('quickadmin.route') . '/' . strtolower($child->name)) }}">
                                                    <i class="fa {{ $child->icon }}"></i>
                                                    <span class="title">{{ $child->title }}</span>
                                                </a>
                                            </li>
                                        @else
                                            @if(Auth::user()->type === 'admin' && preg_match('/scrapping/', strtolower($child->title)))
                                            @if(app()->isLocal())
                                            <li @if(isset(explode('/', Request::path())[1]) && explode('/', Request::path())[1] == strtolower($child->name)) class="active active-sub" @endif>
                                                <a href="{{ route(config('quickadmin.route') . '.' . strtolower($child->name) . '.index') }}">
                                                    <strong>
                                                        <i class="fa {{ $child->icon }}"></i>
                                                        /!\ <span class="title"> {{ $child->title }}</span> /!\
                                                    </strong>
                                                </a>
                                            </li>
                                            @endif
                                            @elseif(Auth::user()->type === 'admin' && preg_match('/gateway/', strtolower($child->title)))
                                            @if(app()->isLocal())
                                                <li class="text-danger">
                                                    <strong>
                                                        <i class="fa {{ $child->icon }}"></i>
                                                        /!\ <span class="title"> {{ $child->title }}</span> /!\
                                                    </strong>
                                                </li>
                                            @endif
                                            @else
                                            <li @if(isset(explode('/', Request::path())[1]) && explode('/', Request::path())[1] == strtolower($child->name)) class="active active-sub" @endif>
                                                <a href="{{ route(config('quickadmin.route') . '.' . strtolower($child->name) . '.index') }}">
                                                    <i class="fa {{ $child->icon }}"></i>
                                                    <span class="title"> {{ $child->title }}</span>
                                                </a>
                                            </li>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    @endif
                @endif
            @endforeach

            <li class="separator">
                <a href="{{ url('logout') }}">
                    <i class="fa fa-sign-out fa-fw"></i>
                    <span class="title">Logout</span>
                </a>
                <br>
                <strong class="well well-white btn-block active text-primary text-center">
                    <i class="fa fa-user fa-fw"></i>
                    {!! Auth::user()->username !!}
                </strong>
            </li>
        </ul>
    </div>
</div>
