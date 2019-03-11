<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<meta name="robots" content="noindex, nofollow, noydir, noodp, nosnippet, noimageindex">-->
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif ワクチン・ラボ </title>

    <meta name="author" content="Motocle.com">


    <meta name="description" content="ワクチンラボは医療従事者向けワクチン情報サイトです。国内外の最新ワクチン情報やホットピックス、また、現場の「何だっけ??」に答える便利なワクチンに関するよくある質問を掲載しています。ご利用は医師、看護師、薬剤師、その他医療従事者が対象。毎月更新中です！">
    <meta name="keywords" content="ワクチンラボ,ワクラボ,ワクチン情報サイト">
    <meta property="og:title" content="現場で役立つワクチン情報｜ワクチンラボ">
    <meta property="og:type" content="website">
    <meta property="og:description" content="ワクチンラボは医療従事者向けワクチン情報サイトです。国内外の最新ワクチン情報やホットピックス、また、現場の「何だっけ??」に答える便利なワクチンに関するよくある質問を掲載しています！">
    <meta property="og:url" content="https://www.vaccine-lab.jp/">
    <meta property="og:image" content="https://www.vaccine-lab.jp/images/ogp_image.jpg">
    <meta property="og:site_name" content="ワクチンラボ">
    <link rel="shortcut icon" href="/favicon.ico">

    {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{-- Fonts --}}
    @yield('template_linked_fonts')
    <link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />
    {{-- Styles --}}
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    @yield('template_linked_css')

    <style type="text/css">
        @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
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


    @yield('head')

</head>
<body>
<div id="app">

    <div class="container">

    </div>

    @yield('content')

</div>

{{-- Scripts --}}
<script src="{{ mix('/js/app.js') }}"></script>

@yield('footer_scripts')

</body>
</html>