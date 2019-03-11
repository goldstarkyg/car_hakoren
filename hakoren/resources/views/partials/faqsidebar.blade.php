<!-- END PAGE CONTENT INNER -->
@inject('util', 'App\Http\DataUtil\ServerPath')
<div class="dynamic-sidebar col-lg-4 col-md-4 col-sm-4 hidden-xs">
	<div class="blog-listing-block">
		<h2> @lang('faq.recommend') </h2>
		<div class="blog-listing-four">
			<?php
            $fukuoka_rentacar_trip_information_banner = $util->Tr('fukuoka-rentacar-trip-information-banner');
            $fukuoka_rentacar_onsen_oita_beppu_yufuin = $util->Tr('fukuoka-rentacar-onsen-oita-beppu-yufuin');
            $rent_a_car_search = $util->Tr('rent-a-car-search');
			?>
			<ul>
				@if($util->lang() == 'ja')
				<li>
					<a href="{{URL::to('/')}}/fukuoka-rentacar-trip-information">
						<img src="{{ URL::asset('img/pages/posts/'.$fukuoka_rentacar_trip_information_banner.'.jpg') }}" alt="">
					</a>
				</li>
				<!--
				<li>
					<a href="{{URL::to('/')}}/okinawa-rentacar-trip-information">
						<img src="{{ URL::asset('img/pages/posts/'.$fukuoka_rentacar_trip_information_banner.'.jpg') }}" alt="">
					</a>
				</li>
				-->
				<li>
					<a href="{{URL::to('/')}}/view-post/fukuoka-rentacar-onsen-oita">
						<img src="{{ URL::asset('img/pages/posts/'.$fukuoka_rentacar_onsen_oita_beppu_yufuin.'.jpg') }}" alt="">
					</a>
				</li>
				@endif
				<li>
					<a href="{{URL::to('/')}}/search-car">
						<img src="{{ URL::asset('img/pages/posts/'.$rent_a_car_search.'.jpg') }}" alt="">
					</a>
				</li>
			</ul>
		</div>
		
	</div>
	@if($util->lang()=='ja')
	<div class="guide-menu">
		<h3> @lang('faq.userguide')</h3>
		<ul>
			{{--<a href="http://motocle7.sakura.ne.jp/projects/rentcar_html/first.html"><li>ご予約からご返却まで</li></a>--}}
			{{--<a href="http://motocle7.sakura.ne.jp/projects/rentcar_html/yoyaku.html"><li>インターネット予約</li></a>--}}
			{{--<a href="http://motocle7.sakura.ne.jp/projects/rentcar_html/yoyaku.html"><li>お電話でご予約</li></a>--}}
			{{--<a href="http://motocle7.sakura.ne.jp/projects/rentcar_html/kashiwatashi.html"><li>貸渡について</li></a>--}}
			{{--<a href="http://motocle7.sakura.ne.jp/projects/rentcar_html/kashiwatashi.html"><li>ご返却について</li></a>--}}
			{{--<a href="#"><li>クイック乗り出し</li></a>--}}
			{{--<a href="menseki.html"><li>免責補償・ワイド免責補償</li></a>--}}

			<a href="{{ url('/first') }}"><li> @lang('faq.beginnerguide')</li></a>
			<a href="{{ url('/first#first-step') }}"><li> @lang('faq.departure')</li></a>
			<a href="{{ url('/search-car') }}"><li> @lang('faq.internet') </li></a>
			<a href="{{ url('/shop/fukuoka-airport-rentacar-shop') }}"><li> @lang('faq.phone') </li></a>
			<a href="{{ url('/shop/naha-airport-rentacar-shop') }}"><li> @lang('faq.phoneokina')</li></a>
			<a href="{{ url('/insurance') }}"><li> @lang('faq.comphensation') </li></a>
			<a href="{{ url('/faq/price') }}"><li> @lang('faq.faq')</li></a>
			<a href="{{ url('/contact') }}"><li> @lang('faq.contact')</li></a>
		</ul>
	</div>
	@endif
</div>
