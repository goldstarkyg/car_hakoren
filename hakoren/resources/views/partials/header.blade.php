@inject('service_header', 'App\Http\Controllers\Front\SearchController')
@inject('util', 'App\Http\DataUtil\ServerPath')
<?php $classlist = $service_header->classlist(); ?>
<header class="site-header">
	<div class="container">
		<div class="top-block">
			<div class="logo-block">
				<a href="{{ url('/') }}"><img src="{{ URL::asset('img/common/'.$util->Tr('logo-lp').'.png') }}" alt="@lang('head.logalt')"></a>
			</div>
			<div class="lang-sp-block">
				<a href="{{URL::to('/')}}/lang/ja"><img src="{{ URL::asset('img/common/jp.png') }}" alt="jp"></a>
				<a href="{{URL::to('/')}}/lang/en"><img src="{{ URL::asset('img/common/us.png') }}" alt="en"></a>
			</div>
            <div class="hamburger-toggle"><span></span> <span></span> <span></span> </div>
            <div class="mobile-menu">
                <ul>
                    <li>
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i><h2> @lang('head.home') </h2></a>
                    </li>
					@if($util->lang() == 'ja')
					<li>
                        <a href="{{URL::to('/')}}/carclasslist/fukuoka-airport-rentacar-shop"><i class="fa fa-car"></i><h2>@lang('head.fukurentprice')</h2></a>
                    </li>
					<li>
                        <a href="{{URL::to('/')}}/carclasslist/naha-airport-rentacar-shop"><i class="fa fa-car"></i><h2>@lang('head.okinarentprice')</h2></a>
                    </li>
					@endif
                    <?php
                        $shops = DB::table('car_shop')->orderBy('id')->get();
                        $name = $util->Tr('name');
                        ?>
                    @foreach($shops as $shop)
                        <li class="mobile-has-submenu">
                            <a href="{{ url('/shop') }}/{{ $shop->slug }}">
                                <i class="fa fa-building"></i><h2>{{$shop->$name}}</h2>
                            </a>
							{{--
							<ul class="mobile-sub-menu">
								<li><a href="{{ url('/') }}/shop/{{$shop->slug}}">福岡空港店</a></li>
								<li><a href="{{ url('/') }}/shop/{{$shop->slug}}">那覇空港店</a></li>
							</ul>
							--}}
                        </li>
                    @endforeach
					@if($util->lang() == 'ja')
                    <li>
                        <a href="{{URL::to('/')}}/campaign/Fukuoka/1"><span class="glyphicon glyphicon-piggy-bank"></span><h2>@lang('head.fukuokasales')</h2></a>
                    </li>
                    <li>
                        <a href="{{URL::to('/')}}/campaign/Okinawa/1"><span class="glyphicon glyphicon-piggy-bank"></span><h2>@lang('head.okinawasales')</h2></a>
                    </li>
                    <li>
                        <a href="{{ url('/') }}/first"><i class="fa fa-question-circle-o"></i><h2>@lang('head.firsttimeuser')</h2></a>
                    </li>
					@endif
                    <li>
                        <a href="{!! url('/search-car') !!}"><i class="fa fa-search"></i><h2> @lang('head.findyourcar')</h2></a>
                    </li>
					@if($util->lang() == 'ja')
                    <li>
                        <a href="{!! url('/info') !!}"><i class="fa fa-suitcase"></i><h2>@lang('head.travelinformation')</h2></a>
                    </li>
					@endif
                    <li><a href="{{URL::to('/')}}/faq/price"><i class="fa fa-caret-right"></i><h2> @lang('head.faq')</h2></a></li>
                    <li><a href="{{URL::to('/')}}/contact"><i class="fa fa-caret-right"></i><h2> @lang('head.contactus')</h2></a></li>
                    <li><a href="{{URL::to('/')}}/login"><i class="fa fa-sign-in"></i><h2> @lang('head.memberlogin')</h2></a></li>
                </ul>
            </div>
            <div class="query-block">
				<div class="clearfix">
					<select id="lang" name="lang" onchange="sendlang()" style="float: left">
						<option value="ja" selected >Language</option>
						<option value="ja" >日本語</option>
						<option value="en" >English</option>
					</select>
					<h2 style="float: right;margin-left: 5px;">
						@if(Auth::check())
							<a href="{!! url('/mypage/top') !!}">@lang('head.myaccount')</a>
						@else
							<a href="{!! url('/login') !!}">@lang('head.myaccount')</a>
						@endif
						
					</h2>
				</div>
                <ul class="clearfix">
                    @if($util->lang()=='ja')<li class="pull-left"><a href="{{URL::to('/')}}/business_contact"><i class="fa fa-caret-right"></i><span> @lang('head.business')</span></a></li>@endif
                    <li class="pull-left"><a href="{{URL::to('/')}}/contact"><i class="fa fa-caret-right"></i><span> @lang('head.contactus')</span></a></li>
                </ul>
            </div>
		</div>
        <div class="nav-block">
            <nav>
                <ul>
                    <li>
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i><h2>@lang('head.home')</h2></a>
                    </li>
                    @if($util->lang() == 'ja')
                    <li class="dropdown">

                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropdownMenuCar" style="background: transparent">
                            <i class="fa fa-car"></i><h2>@lang('head.typeprice')</h2>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuCar" style="top: 62px; padding: 5px;">
                            <?php
                            $car_class_names = \DB::table('car_shop')->orderBy('id')->get();
                            $name = $util->Tr('name');
                            ?>
                            @foreach($car_class_names as $class_name)
                                <li style="display: block; margin: 5px 0;">
                                    <a href="{{ url('/') }}/carclasslist/{{$class_name->slug}}">{{$class_name->$name}}</a>
                                </li>
                            @endforeach
                                <li style="display: block; margin: 5px 0;">
                                    <a href="{{URL::to('/')}}/campaign/Fukuoka/1">@lang('head.fukuokasales')</a>
                                </li>
                                <li style="display: block; margin: 5px 0;">
                                    <a href="{{URL::to('/')}}/campaign/Okinawa/1">@lang('head.okinawasales')</a>
                                </li>
                        </ul>
                    </li>
                    @endif
                     <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="dropdownMenu1" style="background: transparent">
                            <i class="fa fa-building"></i><h2>@lang('head.shoppickup')</h2>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="top: 62px; padding: 5px;">
                            <?php
                                $shops = DB::table('car_shop')->orderBy('id')->get();
                                $name = $util->Tr('name');
                            ?>
                            @foreach($shops as $shop)
                                <li style="display: block; margin: 5px 0;">
                                    <a href="{{ url('/') }}/shop/{{$shop->slug}}">{{$shop->$name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="dropdownMenu2" style="background: transparent"><i class="fa fa-question-circle-o"></i><h2>@lang('head.faq')</h2></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2" style="top: 62px; padding: 5px;">
                                <li style="display: block; margin: 5px 0;">
                                    <a href="{{ url('/') }}/faq/price">@lang('head.faq')</a>
                                </li>
                                @if($util->lang() == 'ja')
                                <li style="display: block; margin: 5px 0;">
                                    <a href="{{ url('/') }}/first">@lang('head.beginner')</a>
                                </li>
                                @endif
                        </ul>
                    </li>
                    <li>
                        <a href="{!! url('/search-car') !!}"><i class="fa fa-search"></i><h2>@lang('head.carsearch')</h2></a>
                    </li>
                    @if($util->lang() == 'ja')
                    <li>
                        <a href="{!! url('/info') !!}"><i class="fa fa-suitcase"></i><h2>@lang('head.travelinformation')</h2></a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
	</div>
</header>
<style>
    .dropdown-menu:before, .dropdown-menu:after {
        content:none !important;
    }
    .mega-img:hover {
        opacity: 0.5;
        filter: alpha(opacity=50); /* For IE8 and earlier */
    }
</style>
<script>
    function goDetail(url,class_id){
        window.location.href = url+"/carclass-detail/"+class_id;
    }
    function sendlang(){
        var lang=$('#lang').val();
        window.location.href = '{{URL::to('/')}}/lang/'+lang;
    }
</script>