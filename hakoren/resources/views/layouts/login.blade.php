<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex,nofollow" />
        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
        <meta name="description" content="">
        <link rel="shortcut icon" href="/favicon.ico">
		<!-- front css -->
		<link href="{{URL::to('/')}}/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="{{URL::to('/')}}/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<!-- BEGIN THEME GLOBAL STYLES -->
		<link href="{{URL::to('/')}}/admin_assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
		<link href="{{URL::to('/')}}/admin_assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
		<!-- END THEME GLOBAL STYLES -->
		<!-- BEGIN THEME LAYOUT STYLES -->
		<link href="{{URL::to('/')}}/admin_assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
		<link href="{{URL::to('/')}}/admin_assets/layouts/layout/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
		<link href="{{URL::to('/')}}/admin_assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
		<!-- END THEME LAYOUT STYLES -->
		<!-- BEGIN THEME GLOBAL CUSTOM STYLES -->
		<link href="{{URL::to('/')}}/css/common_custom.css" rel="stylesheet" id="style_components" type="text/css" />

		<!-- BEGIN PAGE LEVEL STYLES -->
		<link href="{{URL::to('/')}}/admin_assets/pages/css/about.min.css" rel="stylesheet" type="text/css" />
		<link href="{{URL::to('/')}}/css/megamenu.css" rel="stylesheet"/>
		<!-- END PAGE LEVEL STYLES -->
		<!--BEGIN CUSTOM ADD-->
		<script src="{{URL::to('/')}}/js/jquery-2.1.1.js" ></script>
		<script src="{{URL::to('/')}}/js/bootstrap.min.js" ></script>
		<!--END CUSTOM-->
        {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        {{-- Fonts --}}
        @yield('template_linked_fonts')
        {{-- Styles --}}
        <link href="{{URL::to('/')}}/css/login.css" rel="stylesheet">



        @yield('template_linked_css')

        <style type="text/css">
            @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile->avatar_status == 0))
                .user-avatar-nav {
                    background: url({{ Gravatar::get(Auth::user()->email) }}) 50% 50% no-repeat;
                    background-size: auto 100%;
                }
            @endif

        </style>

        {{-- Scripts --}}
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>

        {{--@if (Auth::User() && $theme->link != null && $theme->link != 'null')--}}
            {{--<link rel="stylesheet" type="text/css" href="{{ $theme->link }}">--}}
        {{--@endif--}}

        @yield('head')

    </head>

	<body class="page-container-bg-solid page-boxed page-header-menu-fixed  drawer drawer--right">
	
   @include('partials.header')
        <div id="app" class="login-page page-container">


            <div class="container">

                @include('partials.form-status')

            </div>

            @yield('content')

        </div>

		<div class="footer" style="padding:0px;">
			@include('partials.footer')
		</div>
        {{-- Scripts --}}
        <script src="{{URL::to('/')}}{{ mix('/js/app.js') }}"></script>
        {!! HTML::script('//maps.googleapis.com/maps/api/js?key='.env("GOOGLEMAPS_API_KEY").'&libraries=places&dummy=.js', array('type' => 'text/javascript')) !!}

        @yield('footer_scripts')

    </body>
</html>