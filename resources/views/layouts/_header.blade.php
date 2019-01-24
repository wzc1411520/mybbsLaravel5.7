<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top" style="position: fixed;width: 100%;z-index: 999;top:0;">
    <div class="container">
        <!-- Branding Image -->
        <a class="navbar-brand " href="{{ url('/') }}">
            LaraBBS
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ active_class(if_route('topics.index')) }}"><a class="nav-link" href="{{ route('topics.index') }}">话题</a></li>
                @foreach($categories as $category)
                <li class="nav-item {{ category_nav_active($category->id) }}"><a class="nav-link" href="{{ route('categories.show', $category->id) }}">{{$category->name}}</a></li>
                @endforeach
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav navbar-right">
                @guest
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登录</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">注册</a></li>
                @else
                    <li class="nav-item">
                        <a class="nav-link mt-1 mr-3 font-weight-bold" href="{{ route('topics.create') }}">
                            <i class="fa fa-plus"></i>
                        </a>
                    </li>
                    <li class="nav-item notification-badge">
                        <a class="nav-link mr-3 badge badge-pill badge-{{ Auth::user()->notification_count > 0 ? 'hint' : 'secondary' }} text-white" href="{{ route('notifications.index') }}">
                            {{ Auth::user()->notification_count }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{Auth::user()->avatar?:'https://iocaffcdn.phphub.org/uploads/images/201709/20/1/PtDKbASVcz.png?imageView2/1/w/60/h/60'}}" class="img-responsive img-circle" width="30px" height="30px">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('users.show',Auth::id())}}">个人中心</a>
                            <a class="dropdown-item" href="{{route('users.edit',Auth::id())}}">编辑资料</a>
                            {{--<a class="dropdown-item" href="{{route('password.reset',csrf_token())}}">修改密码</a>--}}
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" id="logout" href="#">
                                <form action="{{ route('logout') }}" method="POST">
                                    {{ csrf_field() }}
                                    <button class="btn btn-block btn-danger" type="submit" name="button">退出</button>
                                </form>
                            </a>
                        </div>
                    </li>
                    @endguest
            </ul>
        </div>
    </div>
</nav>