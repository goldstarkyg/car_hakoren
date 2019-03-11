<!DOCTYPE html>
<html lang="JP"><!-- {{ config('app.locale') }} -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noydir, noodp, nosnippet, noimageindex">
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif
        ハコレン管理者
    </title>
    <meta name="description" content="">
    <meta name="author" content="Motocle.com">
    <link rel="shortcut icon" href="{{URL::to('/')}}/adminfavicon.ico">

    {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{-- Fonts --}}
    @yield('template_linked_fonts')

    {{-- Styles --}}

    @yield('template_linked_css')

    <style type="text/css">
        @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
                .user-avatar-nav {
            {{--background: url({{ Gravatar::get(Auth::user()->email) }}) 50% 50% no-repeat;--}}
            /*background-size: auto 100%;*/
        }
        @endif

    </style>

    {{-- Scripts --}}
    <script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
    </script>

    <link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />
    <link href="{{URL::to('/')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/style.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/mainadmin.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/toast/jquery.toast.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/jquery-ui/jquery-ui.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/admin.css" rel="stylesheet">

    <!-- Mainly scripts -->
    <script src="{{URL::to('/')}}/js/jquery-2.1.1.js"></script>
    <script src="{{URL::to('/')}}/js/bootstrap.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/toast/jquery.toast.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/jquery-ui/jquery-ui.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{URL::to('/')}}/js/inspinia.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/pace/pace.min.js"></script>

    @yield('head')

</head>
<body class="top-navigation">

<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="clearfix border-bottom white-bg adminnav">
            @include('partials.adminnav')
        </div>
        <div class="wrapper wrapper-content row" style="min-height:1366px;">
            @include('partials.form-status')
            <div class="container">
                <div class="col-md-3">
                    @include('partials.adminmenu')
                </div>
                <div class="col-md-9">
                    @yield('content')
                </div>
            </div>
        </div>
        <div class="footer" style="background: #aaa;text-align: center;">
            <div style="padding:0px;padding:3px 0px;">
                <span style="color:#fff">Copyright @ {{ date('Y') }} Hakoren All Rights Reserved.</span>
            </div>
        </div>
    </div>
</div>

@yield('footer_scripts')

</body>
</html>