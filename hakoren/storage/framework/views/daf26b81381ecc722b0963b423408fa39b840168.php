<?php $service_header = app('App\Http\Controllers\Front\SearchController'); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $classlist = $service_header->classlist(); ?>
<header class="site-header">
	<div class="container">
		<div class="top-block">
			<div class="logo-block">
				<a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(URL::asset('img/common/'.$util->Tr('logo-lp').'.png')); ?>" alt="<?php echo app('translator')->getFromJson('head.logalt'); ?>"></a>
			</div>
			<div class="lang-sp-block">
				<a href="<?php echo e(URL::to('/')); ?>/lang/ja"><img src="<?php echo e(URL::asset('img/common/jp.png')); ?>" alt="jp"></a>
				<a href="<?php echo e(URL::to('/')); ?>/lang/en"><img src="<?php echo e(URL::asset('img/common/us.png')); ?>" alt="en"></a>
			</div>
            <div class="hamburger-toggle"><span></span> <span></span> <span></span> </div>
            <div class="mobile-menu">
                <ul>
                    <li>
                        <a href="<?php echo e(url('/')); ?>"><i class="fa fa-home"></i><h2> <?php echo app('translator')->getFromJson('head.home'); ?> </h2></a>
                    </li>
					<?php if($util->lang() == 'ja'): ?>
					<li>
                        <a href="<?php echo e(URL::to('/')); ?>/carclasslist/fukuoka-airport-rentacar-shop"><i class="fa fa-car"></i><h2><?php echo app('translator')->getFromJson('head.fukurentprice'); ?></h2></a>
                    </li>
					<li>
                        <a href="<?php echo e(URL::to('/')); ?>/carclasslist/naha-airport-rentacar-shop"><i class="fa fa-car"></i><h2><?php echo app('translator')->getFromJson('head.okinarentprice'); ?></h2></a>
                    </li>
					<?php endif; ?>
                    <?php
                        $shops = DB::table('car_shop')->orderBy('id')->get();
                        $name = $util->Tr('name');
                        ?>
                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="mobile-has-submenu">
                            <a href="<?php echo e(url('/shop')); ?>/<?php echo e($shop->slug); ?>">
                                <i class="fa fa-building"></i><h2><?php echo e($shop->$name); ?></h2>
                            </a>
							
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php if($util->lang() == 'ja'): ?>
                    <li>
                        <a href="<?php echo e(URL::to('/')); ?>/campaign/Fukuoka/1"><span class="glyphicon glyphicon-piggy-bank"></span><h2><?php echo app('translator')->getFromJson('head.fukuokasales'); ?></h2></a>
                    </li>
                    <li>
                        <a href="<?php echo e(URL::to('/')); ?>/campaign/Okinawa/1"><span class="glyphicon glyphicon-piggy-bank"></span><h2><?php echo app('translator')->getFromJson('head.okinawasales'); ?></h2></a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('/')); ?>/first"><i class="fa fa-question-circle-o"></i><h2><?php echo app('translator')->getFromJson('head.firsttimeuser'); ?></h2></a>
                    </li>
					<?php endif; ?>
                    <li>
                        <a href="<?php echo url('/search-car'); ?>"><i class="fa fa-search"></i><h2> <?php echo app('translator')->getFromJson('head.findyourcar'); ?></h2></a>
                    </li>
					<?php if($util->lang() == 'ja'): ?>
                    <li>
                        <a href="<?php echo url('/info'); ?>"><i class="fa fa-suitcase"></i><h2><?php echo app('translator')->getFromJson('head.travelinformation'); ?></h2></a>
                    </li>
					<?php endif; ?>
                    <li><a href="<?php echo e(URL::to('/')); ?>/faq/price"><i class="fa fa-caret-right"></i><h2> <?php echo app('translator')->getFromJson('head.faq'); ?></h2></a></li>
                    <li><a href="<?php echo e(URL::to('/')); ?>/contact"><i class="fa fa-caret-right"></i><h2> <?php echo app('translator')->getFromJson('head.contactus'); ?></h2></a></li>
                    <li><a href="<?php echo e(URL::to('/')); ?>/login"><i class="fa fa-sign-in"></i><h2> <?php echo app('translator')->getFromJson('head.memberlogin'); ?></h2></a></li>
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
						<?php if(Auth::check()): ?>
							<a href="<?php echo url('/mypage/top'); ?>"><?php echo app('translator')->getFromJson('head.myaccount'); ?></a>
						<?php else: ?>
							<a href="<?php echo url('/login'); ?>"><?php echo app('translator')->getFromJson('head.myaccount'); ?></a>
						<?php endif; ?>
						
					</h2>
				</div>
                <ul class="clearfix">
                    <?php if($util->lang()=='ja'): ?><li class="pull-left"><a href="<?php echo e(URL::to('/')); ?>/business_contact"><i class="fa fa-caret-right"></i><span> <?php echo app('translator')->getFromJson('head.business'); ?></span></a></li><?php endif; ?>
                    <li class="pull-left"><a href="<?php echo e(URL::to('/')); ?>/contact"><i class="fa fa-caret-right"></i><span> <?php echo app('translator')->getFromJson('head.contactus'); ?></span></a></li>
                </ul>
            </div>
		</div>
        <div class="nav-block">
            <nav>
                <ul>
                    <li>
                        <a href="<?php echo e(url('/')); ?>"><i class="fa fa-home"></i><h2><?php echo app('translator')->getFromJson('head.home'); ?></h2></a>
                    </li>
                    <?php if($util->lang() == 'ja'): ?>
                    <li class="dropdown">

                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropdownMenuCar" style="background: transparent">
                            <i class="fa fa-car"></i><h2><?php echo app('translator')->getFromJson('head.typeprice'); ?></h2>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuCar" style="top: 62px; padding: 5px;">
                            <?php
                            $car_class_names = \DB::table('car_shop')->orderBy('id')->get();
                            $name = $util->Tr('name');
                            ?>
                            <?php $__currentLoopData = $car_class_names; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li style="display: block; margin: 5px 0;">
                                    <a href="<?php echo e(url('/')); ?>/carclasslist/<?php echo e($class_name->slug); ?>"><?php echo e($class_name->$name); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <li style="display: block; margin: 5px 0;">
                                    <a href="<?php echo e(URL::to('/')); ?>/campaign/Fukuoka/1"><?php echo app('translator')->getFromJson('head.fukuokasales'); ?></a>
                                </li>
                                <li style="display: block; margin: 5px 0;">
                                    <a href="<?php echo e(URL::to('/')); ?>/campaign/Okinawa/1"><?php echo app('translator')->getFromJson('head.okinawasales'); ?></a>
                                </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                     <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="dropdownMenu1" style="background: transparent">
                            <i class="fa fa-building"></i><h2><?php echo app('translator')->getFromJson('head.shoppickup'); ?></h2>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="top: 62px; padding: 5px;">
                            <?php
                                $shops = DB::table('car_shop')->orderBy('id')->get();
                                $name = $util->Tr('name');
                            ?>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li style="display: block; margin: 5px 0;">
                                    <a href="<?php echo e(url('/')); ?>/shop/<?php echo e($shop->slug); ?>"><?php echo e($shop->$name); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="dropdownMenu2" style="background: transparent"><i class="fa fa-question-circle-o"></i><h2><?php echo app('translator')->getFromJson('head.faq'); ?></h2></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2" style="top: 62px; padding: 5px;">
                                <li style="display: block; margin: 5px 0;">
                                    <a href="<?php echo e(url('/')); ?>/faq/price"><?php echo app('translator')->getFromJson('head.faq'); ?></a>
                                </li>
                                <?php if($util->lang() == 'ja'): ?>
                                <li style="display: block; margin: 5px 0;">
                                    <a href="<?php echo e(url('/')); ?>/first"><?php echo app('translator')->getFromJson('head.beginner'); ?></a>
                                </li>
                                <?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo url('/search-car'); ?>"><i class="fa fa-search"></i><h2><?php echo app('translator')->getFromJson('head.carsearch'); ?></h2></a>
                    </li>
                    <?php if($util->lang() == 'ja'): ?>
                    <li>
                        <a href="<?php echo url('/info'); ?>"><i class="fa fa-suitcase"></i><h2><?php echo app('translator')->getFromJson('head.travelinformation'); ?></h2></a>
                    </li>
                    <?php endif; ?>
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
        window.location.href = '<?php echo e(URL::to('/')); ?>/lang/'+lang;
    }
</script>