<div class="col-sm-4 blog-archive-right">
	<div class="blog-listing-block box-shadow">
		<h2>お勧め情報はこちら</h2>
		<div class="blog-listing-four">
			<ul>
				<li>
					<a href="{{URL::to('/')}}/fukuoka-rentacar-trip-information">
						<img src="{{ URL::asset('img/pages/posts/fukuoka-rentacar-trip-information-banner.jpg') }}" alt="">
					</a>
				</li>
				<!--
				<li>
					<a href="{{URL::to('/')}}/okinawa-rentacar-trip-information">
						<img src="{{ URL::asset('img/pages/posts/okinawa-rentacar-trip-information-banner.jpg') }}" alt="">
					</a>
				</li>
				-->
				<li>
					<a href="{{URL::to('/')}}/view-post/fukuoka-rentacar-onsen-oita">
						<img src="{{ URL::asset('img/pages/posts/fukuoka-rentacar-onsen-oita-beppu-yufuin.jpg') }}" alt="">
					</a>
				</li>				
				<li>
					<a href="{{URL::to('/')}}/search-car">
						<img src="{{ URL::asset('img/pages/posts/rent-a-car-search.jpg') }}" alt="">
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="archive-blog blog-listing-block sidebar-listing box-shadow recent_wrap">
		<h2>人気の記事TOP3</h2>		
		@foreach($recent_post as $post) 
		<div class="sidebar-listing-block">
			<!-- <div class="number-block"></div> -->
			<div class="sidebar-listing-left">
				<span class="numbering">
					{!! $loop->iteration !!}
				</span>
				<div class="sidebar-listing-img">
					<a href="{{ url('/view-post/'.$post->slug) }}">
						<img src="{!! URL::to('/').$post->featured_image !!}" alt="{!! $post->title !!}">
					</a>
				</div>
			</div>
			<div class="sidebar-listing-right">
				<div class="sidebar-listing-content">
					<p><a href="{{ url('/view-post/'.$post->slug) }}">{!! substr(strip_tags($post->post_content),0,80) !!}</a></p>
				</div>
			</div>
		</div>
		@endforeach		 
		<!--
		<hr/>
		<h2>人気タグ</h2>
		<ul>    
			@foreach($all_tags as $tag) 
			<li>
				<span class="hashtag01">
					<i class="fa fa-hashtag"></i>
				</span>
				<a rel="Posts in tag" title="View all posts in {!! $tag->title !!}" href="{{ url('/blog-tags/'.$tag->slug) }}">
					{!! $tag->title !!}
				</a>
			</li>
			@endforeach        
		</ul>
		<div class="archive-view-all">
			<a href="#">タグ一覧を見る<i class="fa fa-angle-right"></i></a>
		</div>
		-->
	</div>
	<div class="hakorenblog-block box-shadow fb_wrap">
		<h2>Facebookで情報更新中♪</h2>
		<p>様々な情報を公開していますので是非お立ち寄りください！</p>
		<div class="facebook-like-block">		
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = 'https://connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.12&appId=228877800512144&autoLogAppEvents=1';
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
			</script>						   
			<div class="fb-page" data-href="https://www.facebook.com/hakorentcar/" data-tabs="timeline" data-width="308" data-height="280" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
				<blockquote cite="https://www.facebook.com/hakorentcar/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/hakorentcar/">ハコレンタカー</a></blockquote>
			</div>			
		</div>
		<!-- Twitter&Instagram section
		<div class="twitter-block">
			<a href="#"><i class="fa fa-twitter"></i>Twitter</a>
		</div>
		<div class="inta-block">
			<a href="#"><i class="fa fa-instagram"></i>Instagram</a>
		</div>
		-->
	</div>
</div>


