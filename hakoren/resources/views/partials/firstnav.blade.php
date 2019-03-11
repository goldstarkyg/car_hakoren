<nav class="navbar navbar-default navbar-static-top" style="background:transparent;">
    <div class="container">
        <div class="navbar-header" style="margin-left:25px;">

            {{-- Collapsed Hamburger --}}
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">{!! trans('titles.toggleNav') !!}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            {{-- Branding Image --}}
            <a class="navbar-brand" href="{{ url('/') }}">
                <img class="footermainimage" src="{{URL::to('/')}}/images/hakoren-logo.png" class="img-responsive"  style="height:40px;padding-right:10px;"/>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            {{-- Left Side Of Navbar --}}

            {{-- Right Side Of Navbar --}}
            <ul class="nav navbar-nav pull-left">
                {{-- Authentication Links --}}
                @if (Auth::guest())

                    {{--<li class="m-devider"></li>--}}
                    {{--<li {{ Request::is('firstuser') ? 'class=active' : null }}><a id="top_menu_21" class="top_menu_10" href="/firstuser">--}}
                            {{--{!! trans('head.about') !!}--}}
                        {{--</a></li>--}}

                    {{--<li class="m-devider"></li>--}}
                    {{--<li {{ Request::is('agreement') ? 'class=active' : null }}><a style="margin-left:10px;margin-right:10px;" id="top_menu_21" class="top_menu_10" href="/agreement">--}}
                            {{--{!! trans('head.createuser') !!}</a></li>--}}

                    {{--<li class="m-devider"></li>--}}

                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">

                            @if ((Auth::User()->profile) && Auth::user()->profile->avatar_status == 1)
                                <img src="{{ Auth::user()->profile->avatar }}" alt="{{ Auth::user()->name }}" class="user-avatar-nav">
                            @else
                                <div class="user-avatar-nav"></div>
                            @endif

                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li {{ Request::is('userprofile/'.Auth::user()->name, 'userprofile/'.Auth::user()->name . '/edit') ? 'class=active' : null }}>
                                {!! HTML::link(url('/userprofile/'.Auth::user()->name), trans('titles.profile')) !!}
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    {!! trans('titles.logout') !!}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
            {{--<ul class="nav navbar-nav pull-right" style="    margin-right: 25px;padding-top:10px;">--}}
                {{--<li {{ Request::is('login') ? 'class=active' : null }}><a id="top_menu_22" class="top_menu_3 top_menu_11" href="/login" style="border-radius:2px;border:3px;border-color: #ffdd24;background: #fff;padding: 3px 10px;font-weight:300;font-size:15px;">--}}
                        {{--{!! trans('head.memberlogin') !!}&nbsp;></a></li>--}}
            {{--</ul>--}}
        </div>
    </div>
</nav>
<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right">
    <h3 style="text-align: right;padding-right:20px;cursor: pointer"><i class="fa fa-angle-right" style="font-size:50px;font-weight: 200"></i></h3>
    <a href="{{URL::to('/')}}/">Home</a>
    <a href="{{URL::to('/')}}/firstuser">{!! trans('head.about') !!}</a>
    <a href="{{URL::to('/')}}/agreement">agreement</a>
    <a  href="{{URL::to('/')}}/login">login</a>
</nav>