<div class="col-sm-4 blog-archive-right">
	<div class="blog-listing-block box-shadow">
		<h2>お勧め情報はこちら</h2>
		<div class="blog-listing-four">
			<ul>
				<li>
					<a href="<?php echo e(URL::to('/')); ?>/fukuoka-rentacar-trip-information">
						<img src="<?php echo e(URL::asset('img/pages/posts/fukuoka-rentacar-trip-information-banner.jpg')); ?>" alt="">
					</a>
				</li>
				<!--
				<li>
					<a href="<?php echo e(URL::to('/')); ?>/okinawa-rentacar-trip-information">
						<img src="<?php echo e(URL::asset('img/pages/posts/okinawa-rentacar-trip-information-banner.jpg')); ?>" alt="">
					</a>
				</li>
				-->
				<li>
					<a href="<?php echo e(URL::to('/')); ?>/view-post/fukuoka-rentacar-onsen-oita">
						<img src="<?php echo e(URL::asset('img/pages/posts/fukuoka-rentacar-onsen-oita-beppu-yufuin.jpg')); ?>" alt="">
					</a>
				</li>				
				<li>
					<a href="<?php echo e(URL::to('/')); ?>/search-car">
						<img src="<?php echo e(URL::asset('img/pages/posts/rent-a-car-search.jpg')); ?>" alt="">
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="archive-blog blog-listing-block sidebar-listing box-shadow recent_wrap">
		<h2>人気の記事TOP3</h2>		
		<?php $__currentLoopData = $recent_post; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
		<div class="sidebar-listing-block">
			<!-- <div class="number-block"></div> -->
			<div class="sidebar-listing-left">
				<span class="numbering">
					<?php echo $loop->iteration; ?>

				</span>
				<div class="sidebar-listing-img">
					<a href="<?php echo e(url('/view-post/'.$post->slug)); ?>">
						<img src="<?php echo URL::to('/').$post->featured_image; ?>" alt="<?php echo $post->title; ?>">
					</a>
				</div>
			</div>
			<div class="sidebar-listing-right">
				<div class="sidebar-listing-content">
					<p><a href="<?php echo e(url('/view-post/'.$post->slug)); ?>"><?php echo substr(strip_tags($post->post_content),0,80); ?></a></p>
				</div>
			</div>
		</div>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>		 
		<!--
		<hr/>
		<h2>人気タグ</h2>
		<ul>    
			<?php $__currentLoopData = $all_tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
			<li>
				<span class="hashtag01">
					<i class="fa fa-hashtag"></i>
				</span>
				<a rel="Posts in tag" title="View all posts in <?php echo $tag->title; ?>" href="<?php echo e(url('/blog-tags/'.$tag->slug)); ?>">
					<?php echo $tag->title; ?>

				</a>
			</li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
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


