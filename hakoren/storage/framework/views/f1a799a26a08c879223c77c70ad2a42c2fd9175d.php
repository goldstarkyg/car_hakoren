<?php $__env->startSection('template_title'); ?>
	<?php echo app('translator')->getFromJson('head.home'); ?>
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('template_linked_css'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/toppage.css" rel="stylesheet" type="text/css">
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/js/slick/slick-topslider.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/js/slick/slick-theme.css" media="screen" />
    <style>
        .search_block { display : none; }
        .search_block.current { display: block; }

		.stepBar .step {
			position: relative;
			float: left;
			display: inline-block;
			line-height: 30px;
			padding: 0px;
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
		}
		.stepBar .step:before, .stepBar .step:after {
			/* position: absolute; */
			/* left: -15px; */
			/* display: block; */
			/* content: ''; */
			/* background-color: #eee; */
			/* border-left: 4px solid #FFF; */
			/* width: 20px; */
			/* height: 15px; */
		}
		.stepBar .step:after {
			top: 0;
			-moz-transform: skew(30deg);
			-ms-transform: skew(30deg);
			-webkit-transform: skew(30deg);
			transform: skew(30deg);
		}
		.stepBar .step:before {
			/* bottom: 0; */
			/* -moz-transform: skew(-30deg); */
			/* -ms-transform: skew(-30deg); */
			/* -webkit-transform: skew(-30deg); */
			/* transform: skew(-30deg); */
		}
		.stepBar .step:first-child {
			-moz-border-radius-topleft: 4px;
			-webkit-border-top-left-radius: 4px;
			border-top-left-radius: 4px;
			-moz-border-radius-bottomleft: 4px;
			-webkit-border-bottom-left-radius: 4px;
			border-bottom-left-radius: 4px;
		}
		.stepBar .step:last-child {
			-moz-border-radius-topright: 4px;
			-webkit-border-top-right-radius: 4px;
			border-top-right-radius: 4px;
			-moz-border-radius-bottomright: 4px;
			-webkit-border-bottom-right-radius: 4px;
			border-bottom-right-radius: 4px;
		}
		.stepBar .step.current {
		}
		.stepBar.step2 .step { width: 50%; }
		.stepBar.step3 .step { width: 33.333%; }
		.stepBar.step4 .step { width: 25%; }
		.stepBar.step5 .step { width: 20%; }
		.bg-grad-blue {
			background: -moz-linear-gradient(top, #4588f7 1%, #437ee0 50%, #437ee0 50%, #0d48aa 100%); /* FF3.6-15 */
			background: -webkit-linear-gradient(top, #4588f7 1%, #437ee0 50%, #437ee0 50%, #0d48aa 100%); /* Chrome10-25,Safari5.1-6 */
			background: linear-gradient(to bottom, #4588f7 1%, #437ee0 50%, #437ee0 50%, #0d48aa 100%) ;
		}
		.search_li i {
			display: inline-block;
			color: #049341;
			margin-left: 20px;
		}
		.option-listing ul li { padding: 0 0 3px 5px; }
		.s-link{ cursor: pointer; }
		.datepicker.dropdown-menu{z-index: 998;}
		/**/
		#select-confirm-option .option-question{
			margin-top: 40px;
		}
		#select-confirm-option .option-question span,
		#select-shop .shop-question span,
		#select-date .period-question span,
		#select-option .option-question span{
			font-size: 17px;
			max-width: inherit !important;
		}
		#select-confirm-option .option-listing{
			margin-top: 50px;
		}
		#select-confirm-option .nt-sh-block,
		#select-date .nt-rt-block,
		#select-confirm-option .nt-sh-block,
		#select-option .nt-sh-block{
		    position: absolute;
		    left: 0;
		    right: 0;
		    bottom: 20px;
		    padding: 0px 15px;
		}
		.option_shop{
			min-height: 120px;
		    overflow-y: auto;
		    overflow-x: hidden;
		    width: 100%;
		    display: inline-block;
		    height: 1px;
		    margin-top: 30px;
		}
		.option_shop.option-listing ul li span { width: 155px;}
		.slider-fixed ::-webkit-scrollbar { width: 5px; }
		/* Track */
		.slider-fixed ::-webkit-scrollbar-track { background: #f1f1f1; }
		/* Handle */
		.slider-fixed ::-webkit-scrollbar-thumb { background: #06a6ff; }
		/* Handle on hover */
		.slider-fixed ::-webkit-scrollbar-thumb:hover { background: #06a6ff; }
		.thread_btn{ padding: 10px 18px; }
		.date_full input{ z-index: inherit !important; }
		.extra_add_btn{ padding: 10px 20px; }

		#camp-left-banner {
			width: 100%;
			margin-top: 20px;
		}
		#camp-right-ribbon {
			position: absolute;
			left: 5px;
			top: 5px;
		}
		#campaign-list li {
			/*margin-bottom: 10px;*/
			line-height: 1.5;
		}
		.shop-label {
			padding: 2px 8px;
			margin-right: 10px;
		}
		.top-campaign { min-height: 300px; margin-top:30px; }
		.shop-Fukuoka { background: #32b16c; color : white;}
		.shop-Okinawa { background: #00b8ee; color : white;}
		#camp-list {
			background: white;
			padding: 10px 20px;
			max-height: 300px;
			overflow-y: scroll;
		}
		@media  screen and (min-width:1024px) and (max-width:1280px){
			.content_over{
				display: inline-block;
				width: 100%;
				margin: 0;
			}
			.time_full select{
				margin-top: 8px;
				width: 100%;
			}
			.date_full,
			.time_full{
				width: 100%;
			}
			.content_over .time-text{
				margin-bottom: 15px;
			}
			#select-confirm-option .option-question span,
			#select-shop .shop-question span,
			#select-date .period-question span,
			#select-option .option-question span {
			    font-size: 13px;
			    max-width: inherit !important;
			    line-height: 18px;
			}
		}

		@media  screen and (min-width:981px) and (max-width:1280px){
			#select-confirm-option .option-question span,
			#select-shop .shop-question span,
			#select-date .period-question span,
			#select-option .option-question span {
			    font-size: 13px;
			    max-width: inherit !important;
			    line-height: 18px;
			}
		}
		
		
		@media  screen and (min-width:768px) and (max-width: 1490px){
			
			#select-date .period-question span{
			    font-size: 13px!important;
			    max-width: inherit !important;
			    line-height: 18px!important;
			}
		}

		@media  screen and (max-width: 767px){
			
			#select-confirm-option .nt-sh-block,
			#select-date .nt-rt-block,
			#select-confirm-option .nt-sh-block,
			#select-option .nt-sh-block{
			    position: inherit;
			}
			.thread_btn{
				padding:6px 13px !important;
			}
			.extra_add_btn{
				padding: 6px 13px !important;
			}
			#camp-list-wrap { padding:20px 15px !important;}
		}
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- BEGIN CONTAINER -->
	<div class="page-container overall_wrap">
		<!-- top slider -->
    <div class="top-slide-sec">
        <ul class="slider">
            <li class="slick-slide">
                <a href="#">
                    <img class="img-responsive center-block hidden-xs" src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('topslider-01')); ?>.png">
                    <img class="img-responsive center-block visible-xs" src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/topslider-01sp.png">
                </a>
            </li>
            <li class="slick-slide">
                <a href="#compare" class="anchor-link">
                    <img class="img-responsive center-block hidden-xs" src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('topslider-02')); ?>.jpg">
                    <img class="img-responsive center-block visible-xs" src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/topslider-01sp.png">
                </a>
            </li>
            <li class="slick-slide">
                <a href="#">
                    <img class="img-responsive center-block hidden-xs" src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('topslider-03')); ?>.png">
                    <img class="img-responsive center-block visible-xs" src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/topslider-01sp.png">
                </a>
            </li>
        </ul>
        <div class="container position-rel">
        <div class="h-left slider-fixed smart_fix">
            <!-- start select yes/no -->
            <div id="select-yes" class="shop-block" style="height: 100%;">
                <div style="text-align: center;margin: 0 20px;height: 100%;padding-bottom: 70px;">
                    <div class="col-xs-12 text-center" style="padding: 15px 0 0 0">
						<?php if($util->lang()== 'ja'): ?>
                        <img src="<?php echo e(URL::to('/')); ?>/img/<?php echo e($util->Tr('top-front-navi-girl')); ?>.jpg" style="width:100%;margin-bottom: 30px;">
						<?php endif; ?>
						<?php if($util->lang()== 'en'): ?>
							<img src="<?php echo e(URL::to('/')); ?>/img/<?php echo e($util->Tr('top-front-navi-girl')); ?>.jpg" style="width:100%;">
						<?php endif; ?>
                    </div>
                    <div class="col-xs-12 text-center" id="start-bubble">
                        <img src="<?php echo e(URL::to('/')); ?>/img/<?php echo e($util->Tr('startbubble')); ?>.png" style="width:100%">
                    </div>

                    
                    <span class="btn" style="background-color: #4589f8;color:white;border-radius:5px !important;box-shadow: 3px 4px 4px #ddd !important;font-size:24px;font-weight:500;width:70%;position: absolute;bottom: 20px;margin-left: 15%;left: 0;" onclick="showShopBlock();"><?php echo app('translator')->getFromJson('toppage.tryout'); ?></span>
                </div>
            </div>
            <!-- end select yes/no -->

            <div class="h-left-wrapper " style="display: none">
                <form method="POST" id="searchform" action="<?php echo e(URL::to('/search-car')); ?>" accept-charset="UTF-8" >
                    <?php echo csrf_field(); ?>

					<!--progress bar-->
					<input type="hidden" name="request_page" value="toppage" />
					<div class="row hidden">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<ol class="stepBar step3">
								<li id="progress-shop" class="step current active">店舗</li>
								<li id="progress-date" class="step">期間</li>
								<li id="progress-option" class="step">検索</li>
							</ol>
						</div>
					</div>
					<!--end progress bar-->
                	<div class="top-search-tbl m_B0">
						<!--start select shop-->
						<div id="select-shop" class="shop-block search_block current">
                            <div class="text-center">
                                <img src="/img/stuff_01.png" style="height: 120px;margin-top: 20px;">
                            </div>
							<div class="shop-question" style="margin-top: 30px;">
								<span id="type_question" style="font-size: 22px;"></span>
							</div>
							<input type="hidden" name="depart_shop" value="" >
							<div class="shop-option mobile_view_btn" style="display: none">
								<ul>
									<?php
										$name = $util->Tr('name');
									?>
									<?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<li class="shop_class shop_class_<?php echo e($shop->id); ?>" onclick="searchCondition('date','<?php echo e($shop->id); ?>','<?php echo e($shop->slug); ?>')" >
                                            <span style="color: #222;"><?php echo e($shop->$name); ?></span>
                                        </li>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</ul>
							</div>
						</div>
						<!--end select shop-->
						<!--start select date-->
						<div id="select-date" class="date-block search_block">
							<div class="period-question col-xs-12" style="padding:0 0 0 110px;height:75px;">
                                <img src="/img/stuff_01.png" style="height: 100px;top: -20px;position: absolute;left: 0;">
								<span id="date_question"></span>
							</div>
							<div class="time-block" style="display: none;">
								<div class="row content_over">
									<div class="col-sm-12 time-text">
									<span><?php echo app('translator')->getFromJson('toppage.departure'); ?></span>
									</div>
									<div class="col-sm-8 date_full">
										<div class="input-group date" id="depart-datepicker">
											<input type='text' class="form-control input-sm" name="depart_date" value="<?php echo e($depart_date); ?>" readonly/>
											<span class="input-group-addon input-sm">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</div>
									<div class="col-sm-4 time_full">
										<select name="depart_time" id="depart_time" style="height:30px">
											<?php $__currentLoopData = $hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option value="<?php echo e($hour); ?>" <?php if($hour == '09:00'): ?> selected <?php endif; ?>><?php echo e($hour); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>
									</div>
								</div>
							</div>
							<div class="time-block" style="display: none;">
								<div class="row content_over">
									<div class="col-sm-12 time-text">
										<span><?php echo app('translator')->getFromJson('toppage.return'); ?></span>
									</div>
									<div class="col-sm-8 date_full">
										<div class="input-group date" id="return-datepicker">
											<input type='text' class="form-control input-sm" name="return_date" value="<?php echo e($return_date); ?>" readonly/>
											<span class="input-group-addon input-sm">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</div>
									<div class="col-sm-4 time_full">
										<select name="return_time" id="return_time" style="height:30px">
											<?php $__currentLoopData = $hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option value="<?php echo e($hour); ?>" <?php if($hour == '19:30'): ?> selected <?php endif; ?>><?php echo e($hour); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>
									</div>
								</div>
							</div>
                            <div>
                                <span class="btn sup-btn" style="background-color: #4589f8;color:white;border-radius:5px !important;box-shadow: 3px 4px 4px #ddd !important;font-size:16px;font-weight:500;width:80%;position: absolute;bottom: 20px;margin-left: 10%;left: 0;" onclick="searchCondition('last')"><?php echo app('translator')->getFromJson('toppage.searchcarrental'); ?></span>
                            </div>
							<div class="nt-rt-block hidden">
								<div class="row">
								<div class="col-xs-6">
									<label class="button-grey" onclick="searchCondition('shop')">
										<?php echo app('translator')->getFromJson('toppage.back'); ?>
									</label>
								</div>
								<div class="col-xs-6">
									
										
									
                                    <label class="button-blue thread_btn" onclick="searchCondition('last')">
                                        <?php echo app('translator')->getFromJson('toppage.findcar'); ?>
                                    </label>
								</div>
								</div>
							</div>
						</div>
						<!--end select date-->

						<!--start confirm select options-->
						<div id="select-confirm-option" class="option-block search_block">
							<div class="option-question">
								<span><?php echo app('translator')->getFromJson('toppage.optionnecessary'); ?></span>
							</div>
							<div class="row">
								<div class="col-sm-12 option-listing">
									<ul>
										<li class="search_li search-confirm-option" style="margin-top: 7px;" id="select-confirm-yes">
											<input type="radio" name="search-confirm-radio-group"  class="hidden" value="yes">
											<span style="padding-left: 10px;"><?php echo app('translator')->getFromJson('toppage.yes'); ?></span>
										</li>
										<li class="search_li search-confirm-option" style="margin-top: 7px;" >
											<input type="radio" name="search-confirm-radio-group"  class="hidden" value="no">
											<span style="padding-left: 10px;"><?php echo app('translator')->getFromJson('toppage.noneedsearch'); ?></span>
										</li>
									</ul>
								</div>
							</div>
							<div class="nt-sh-block">
								<div class="row">
								<div class="col-xs-6">
									<label class="button-grey" onclick="searchCondition('date')">
										<?php echo app('translator')->getFromJson('toppage.back'); ?>
									</label>
								</div>
								<div class="col-xs-6">
									<label class="button-blue extra_add_btn" id="select-confirm-no">
										<?php echo app('translator')->getFromJson('toppage.findcar'); ?>
									</label>
								</div>
								</div>
							</div>
						</div>
						<!--end confirm select options-->

						<!--start select options-->
						<div id="select-option" class="option-block search_block">
							<div class="option-question">
								<span><?php echo app('translator')->getFromJson('toppage.selectoption'); ?></span>
							</div>
							<div class="row">
								<div class="col-sm-12 option_shop option-listing">
									<ul id="caroptions_vpn">

									</ul>
								</div>
							</div>

							<div class="nt-sh-block">
								<div class="row">
								<div class="col-xs-6">
									<label class="button-grey" onclick="searchCondition('confirm')">
										<?php echo app('translator')->getFromJson('toppage.back'); ?>
									</label>
								</div>
								<div class="col-xs-6">
									<label class="button-blue thread_btn" onclick="searchCondition('last')">
										<?php echo app('translator')->getFromJson('toppage.findcar'); ?>
									</label>
								</div>
								</div>
							</div>
						</div>
						<!--end select options-->
               		 </div>
                    <?php
                    foreach($categorys as $cate) {
                        if($cate->name == '乗用車') {
                            $car_category = $cate->id;
                        }
                    } ?>

                    <input type="hidden" name="return_shop" value="<?php if(count($shops)>0): ?> <?php echo e($shops[0]->id); ?> <?php endif; ?>">
                    <input type="hidden" name="car_category" value="<?php echo e($car_category); ?>">
                    <input type="hidden" name="passenger" value="all" >
                    <input type="hidden" name="smoke" value="both" >
                    <input type="hidden" name="insurance" value="1">
					
                </form>
            </div>
        </div>
        </div>
    </div>
		<!-- BEGIN CONTENT -->
		<div class="page-content-wrapper">
			<!-- BEGIN CONTENT BODY -->

			<!-- BEGIN PAGE CONTENT BODY -->
			<div class="page-content">
				<div class="container-full bg-white">
					<div class="container">
						<?php if($util->lang() == 'ja'): ?>
						<!-- campaign -->
						<div class="row top-campaign bg-grad-blue">
							<div class="col-xs-12 col-md-5">
                                <div id="camp-right-ribbon" class="hidden-xs">
                                    <img src="<?php echo e(url('/img/pages/campaign/right-ribbon.png')); ?>" style="width: 120px;">
                                </div>
								<img id="camp-left-banner" src="<?php echo e(url('/img/pages/campaign/left-banner.png')); ?>">
							</div>

							<div class="col-xs-12 col-md-7" id="camp-list-wrap" style="padding:20px 20px 20px 0;">
								<div id="camp-list">
									<h4 style="font-size: 16px; font-weight: bold;">
										ハコレンタカー公式WEBサイトでは、<?php echo e(date('n月j日', strtotime('tomorrow'))); ?>~<?php echo e(date('n月j日', strtotime('tomorrow +13days'))); ?>の期間限定でお得に貸出ししております。
									</h4>

									<ul style="list-style: none;" id="campaign-list">
										<?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $camp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<li class="xsmb3">
											<a href="<?php echo e(url('/campaign/'.$camp->region_code.'/1')); ?>">
												<span class="shop-label <?php echo e('shop-'.$camp->region_code); ?>"><?php echo e($camp->shop_name); ?></span>
												<?php echo e(date('n/j', strtotime($camp->date))); ?>日
												(<?php if($camp->vacancy == 1): ?> 日帰り <?php else: ?> 1泊2日 <?php endif; ?> )<br class="visible-xs">
												<?php echo e($camp->class_name); ?> <?php echo e($camp->max_passenger); ?>人乗り
												<?php echo e(number_format($camp->original_price)); ?>円 &rarr;
												<span style="color: red;">
													<span style="font-size: 1.5em;font-weight: bold"> <?php echo e(number_format($camp->rent_price)); ?></span>円
												</span>
											</a>
										</li>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</ul>
								</div>
							</div>
						</div>
						<!-- campaign end -->
						<?php endif; ?>
						<!-- lineup -->
						<div class="row top-lineup">
							<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h2 class="top-lineup-ttl"><small><?php echo app('translator')->getFromJson('toppage.popularcar'); ?></small><br>
									CAR LINEUP
								</h2>
								<!-- section 1 -->
								<div class="row m_T30">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 linup-wrapper carlinup_wrap">

										<div class="row margin-bottom-40">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_T30 carlinup_list">
												<div class="circle80 lineup-circle bg-darkred" <?php if($util->lang() == 'ja'): ?> style="padding-top:28px;" <?php else: ?> style="padding-top:22px;"  <?php endif; ?> ><?php echo app('translator')->getFromJson('toppage.familytrip'); ?></div>
												<div class="position-rel">
													<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/classimg-01.png" class="img-responsive center-block">
													<p class="price color-red"><span><?php echo app('translator')->getFromJson('toppage.1n2d'); ?><br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php echo e(number_format($lineups[49]->n1d2_day1)); ?><small> <?php echo app('translator')->getFromJson('toppage.yen'); ?>~</small></p>
												</div>
												<p class="cartype"> <?php echo app('translator')->getFromJson('toppage.70car'); ?></p>
												<p class="m_T0">（<?php echo app('translator')->getFromJson('toppage.7-8pepoleride'); ?>）</p>
												<p class="price-days color-red"><span></span></p>
												<p class="text-left"> <?php echo app('translator')->getFromJson('toppage.70cardesc'); ?></p>

												<div class="clearfix linup-btns">
													<?php if($util->lang()=='ja'): ?>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<span class="btn-blu"><a class="mens-modal" data-toggle="modal" href="#Modal_03" data-target="#Modal_03"> <?php echo app('translator')->getFromJson('toppage.viewdetail'); ?></a></span>
													</div>
													<?php endif; ?>
													<!-- Modal -->
													<div class="modal fade" id="Modal_03" tabindex="-1">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-body witch-store">
																	<img class="center-block img-responsive" src="<?php echo e(URL::to('/img/stuff_01.png')); ?>" alt="<?php echo app('translator')->getFromJson('toppage.whichstore'); ?>">
																	<p><?php echo app('translator')->getFromJson('toppage.whichstore'); ?></p>
																	<?php $csname= $util->Tr('csname'); ?>
																	<a href="<?php echo e(URL::to('/')); ?>/carclass-detail/3" class="store"><?php echo e($lineups[3]->$csname); ?> <span class="n1d2_day1"><span class="lsw"><?php echo app('translator')->getFromJson('toppage.1n2d'); ?></span><br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php echo e(number_format($lineups[3]->n1d2_day1)); ?> <?php echo app('translator')->getFromJson('toppage.yen'); ?></a>
																	<a href="<?php echo e(URL::to('/')); ?>/carclass-detail/49" class="store"><?php echo e($lineups[49]->$csname); ?> <span class="n1d2_day1"><span class="lsw"><?php echo app('translator')->getFromJson('toppage.1n2d'); ?></span><br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php echo e(number_format($lineups[49]->n1d2_day1)); ?><?php echo app('translator')->getFromJson('toppage.yen'); ?></a>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->getFromJson('toppage.closeup'); ?></button>
																</div>
															</div>
														</div>
													</div>
												</div>

											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_T30 carlinup_list">
												<div class="circle80 lineup-circle bg-darkred" <?php if($util->lang() == 'en'): ?> style="padding-top:25px;" <?php endif; ?> ><?php echo app('translator')->getFromJson('toppage.reception'); ?><br><?php echo app('translator')->getFromJson('toppage.golf'); ?></div>
												<div class="position-rel">
													<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/classimg-02.png" class="img-responsive center-block">
													<p class="price color-red"><span> <?php echo app('translator')->getFromJson('toppage.1n2d'); ?> <br> <?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php echo e(number_format($lineups[51]->n1d2_day1)); ?><small><?php echo app('translator')->getFromJson('toppage.yen'); ?>~</small></p>
												</div>
												<p class="cartype"> <?php echo app('translator')->getFromJson('toppage.30car'); ?></p>
												<p class="m_T0">（<?php echo app('translator')->getFromJson('toppage.7seat'); ?>）</p>
												<p class="price-days color-red"><span></span></p>
												<p class="text-left"> <?php echo app('translator')->getFromJson('toppage.30cardesc'); ?></p>

												<div class="clearfix linup-btns">
													<?php if($util->lang()=='ja'): ?>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<span class="btn-blu"><a class="mens-modal" data-toggle="modal" href="#Modal_02" data-target="#Modal_02"><?php echo app('translator')->getFromJson('toppage.viewdetail'); ?></a></span>
													</div>
													<?php endif; ?>
													<!-- Modal -->
													<div class="modal fade" id="Modal_02" tabindex="-1">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-body witch-store">
																	<img class="center-block img-responsive" src="<?php echo e(URL::to('/img/stuff_01.png')); ?>" alt="<?php echo app('translator')->getFromJson('toppage.whichstore'); ?>">
																	<p><?php echo app('translator')->getFromJson('toppage.whichstore'); ?></p>
																	<a href="<?php echo e(URL::to('/')); ?>/carclass-detail/6" class="store"><?php echo e($lineups[6]->$csname); ?> <span class="n1d2_day1"><span class="lsw"><?php echo app('translator')->getFromJson('toppage.1n2d'); ?></span><br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php echo e(number_format($lineups[6]->n1d2_day1)); ?><?php echo app('translator')->getFromJson('toppage.yen'); ?></a>
																	<a href="<?php echo e(URL::to('/')); ?>/carclass-detail/51" class="store"><?php echo e($lineups[51]->$csname); ?> <span class="n1d2_day1"><span class="lsw"><?php echo app('translator')->getFromJson('toppage.1n2d'); ?></span><br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php echo e(number_format($lineups[51]->n1d2_day1)); ?><?php echo app('translator')->getFromJson('toppage.yen'); ?></a>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->getFromJson('toppage.closeup'); ?></button>
																</div>
															</div>
														</div>
													</div>
												</div>

											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_T30 carlinup_list">
												<div class="circle80 lineup-circle bg-darkred" <?php if($util->lang() == 'ja'): ?> style="padding-top:28px;" <?php else: ?> style="padding-top:12px;font-size:14px;"  <?php endif; ?> ><?php echo app('translator')->getFromJson('toppage.manypepole'); ?></div>
												<div class="position-rel">
													<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/classimg-03.png" class="img-responsive center-block">
													<p class="price color-red"><span><?php echo app('translator')->getFromJson('toppage.1n2d'); ?><br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php echo e(number_format($lineups[54]->n1d2_day1)); ?><small><?php echo app('translator')->getFromJson('toppage.yen'); ?>~</small></p>
												</div>
												<p class="cartype"> <?php echo app('translator')->getFromJson('toppage.200car'); ?> </p>
												<p class="m_T0">（<?php echo app('translator')->getFromJson('toppage.10seat'); ?>）</p>
												<p class="price-days color-red"><span></span></p>
												<p class="text-left"> <?php echo app('translator')->getFromJson('toppage.200cardesc'); ?></p>

												<div class="clearfix linup-btns">
													<?php if($util->lang()=='ja'): ?>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<span class="btn-blu"><a class="mens-modal" data-toggle="modal" href="#Modal_01" data-target="#Modal_01"><?php echo app('translator')->getFromJson('toppage.viewdetail'); ?></a></span>
													</div>
													<?php endif; ?>
													<!-- Modal -->
													<div class="modal fade" id="Modal_01" tabindex="-1">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-body witch-store">
																	<img class="center-block img-responsive" src="<?php echo e(URL::to('/img/stuff_01.png')); ?>" alt="<?php echo app('translator')->getFromJson('toppage.whichstore'); ?>">
																	<p><?php echo app('translator')->getFromJson('toppage.whichstore'); ?></p>

																	<a href="<?php echo e(URL::to('/')); ?>/carclass-detail/10" class="store"><?php echo e($lineups[10]->$csname); ?> <span class="n1d2_day1"><span class="lsw"><?php echo app('translator')->getFromJson('toppage.1n2d'); ?></span><br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php echo e(number_format($lineups[10]->n1d2_day1)); ?><?php echo app('translator')->getFromJson('toppage.yen'); ?></a>
																	<a href="<?php echo e(URL::to('/')); ?>/carclass-detail/54" class="store"><?php echo e($lineups[54]->$csname); ?> <span class="n1d2_day1"><span class="lsw"><?php echo app('translator')->getFromJson('toppage.1n2d'); ?></span><br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php echo e(number_format($lineups[54]->n1d2_day1)); ?><?php echo app('translator')->getFromJson('toppage.yen'); ?></a>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->getFromJson('toppage.closeup'); ?></button>
																</div>
															</div>
														</div>
													</div>
												</div>

											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_T30 carlinup_list">
												<div class="circle80 lineup-circle bg-darkred"><?php echo app('translator')->getFromJson('toppage.circle'); ?><br><?php echo app('translator')->getFromJson('toppage.competition'); ?></div>
												<div class="position-rel">
													<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/classimg-04.png" class="img-responsive center-block">
													<p class="price color-red"><span><?php echo app('translator')->getFromJson('toppage.1n2d'); ?><br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span><?php if(!empty($lineups[32])): ?><?php echo e(number_format($lineups[32]->n1d2_day1)); ?><?php endif; ?><small><?php echo app('translator')->getFromJson('toppage.yen'); ?>~</small></p>
												</div>
												<p class="cartype"> <?php echo app('translator')->getFromJson('toppage.coastercar'); ?> </p>
												<p class="m_T0">（<?php echo app('translator')->getFromJson('toppage.26-29seat'); ?>）</p>
												<p class="price-days color-red"><span></span></p>
												<p class="text-left"> <?php echo app('translator')->getFromJson('toppage.coasterdesc'); ?></p>

												<div class="clearfix linup-btns">
													<?php if($util->lang()=='ja'): ?>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<span class="btn-blu"><a class="mens-modal" data-toggle="modal" href="#Modal_04" data-target="#Modal_04"><?php echo app('translator')->getFromJson('toppage.viewdetail'); ?></a></span>
													</div>
													<?php endif; ?>
													<!-- Modal -->
													<div class="modal fade" id="Modal_04" tabindex="-1">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-body witch-store">
																	<img class="center-block img-responsive" src="<?php echo e(URL::to('/img/stuff_01.png')); ?>" alt="<?php echo app('translator')->getFromJson('toppage.whichstore'); ?>">
																	<p> <?php echo app('translator')->getFromJson('toppage.whichstore'); ?></p>

																	<a href="tel:092-260-9506" class="store">
																		<?php if(!empty($lineups[32])): ?> <?php echo e($lineups[32]->$csname); ?> <?php endif; ?>
																			<span class="n1d2_day1"><span class="lsw"><?php echo app('translator')->getFromJson('toppage.1n2d'); ?></span>
																				<br><?php echo app('translator')->getFromJson('toppage.perday'); ?></span>
																			<?php if(!empty($lineups[32])): ?><?php echo e(number_format($lineups[32]->n1d2_day1)); ?> <?php endif; ?>
																			<?php echo app('translator')->getFromJson('toppage.yen'); ?>
																	</a>
																	<p class="ast"><?php echo app('translator')->getFromJson('toppage.requirerental'); ?></p>
																	<p class="ast"><?php echo app('translator')->getFromJson('toppage.mbclass'); ?></p>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->getFromJson('toppage.closeup'); ?></button>
																</div>
															</div>
														</div>
													</div>
												</div>



											</div>

										</div>
										<!-- section 1 -->
										<?php if($util->lang()=='ja'): ?>
											<div class="lineup-link-w">
											<a href="<?php echo e(URL::to('/')); ?>/carclasslist/fukuoka-airport-rentacar-shop" class="lineup-link left"><span><?php echo app('translator')->getFromJson('toppage.fukuairport'); ?></span><br><?php echo app('translator')->getFromJson('toppage.watchlinup'); ?></a>
											<a href="<?php echo e(URL::to('/')); ?>/carclasslist/naha-airport-rentacar-shop" class="lineup-link naha-color"><span><?php echo app('translator')->getFromJson('toppage.okinaairport'); ?></span><br><?php echo app('translator')->getFromJson('toppage.watchlinup'); ?></a>
											</div>
										<?php else: ?>
											<div class="lineup-link-w">
											<a href="<?php echo e(URL::to('/')); ?>/shop/fukuoka-airport-rentacar-shop" class="lineup-link left"><span><?php echo app('translator')->getFromJson('toppage.fukuairport'); ?></span><br><?php echo app('translator')->getFromJson('toppage.watchlinup'); ?></a>
											<a href="<?php echo e(URL::to('/')); ?>/shop/naha-airport-rentacar-shop" class="lineup-link naha-color"><span><?php echo app('translator')->getFromJson('toppage.okinaairport'); ?></span><br><?php echo app('translator')->getFromJson('toppage.watchlinup'); ?></a>
											</div>
										<?php endif; ?>
									</div>
								</div>

							</div>
						</div>
					</div><!-- container -->
				</div>

				<div class="container-full bg-white clearfix visible-xs" style="padding-bottom:30px;">
					<a href="<?php echo e(URL::to('/')); ?>/search-car">
						<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/rentacar-sp-button2.jpg" class="img-responsive center-block m_TB20">
					</a>
				</div>

				<div class="container-full bg-grad-gray content_manage">
					<div class="container">

						<!-- sec2 -->
						<div class="row ">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('sec2ttl')); ?>.png" class="img-responsive center-block m_T30" <?php if($util->lang()=='en'): ?> style="margin-top:-15px!important;" <?php endif; ?>>
												<p class="h-txt"> <?php echo app('translator')->getFromJson('toppage.hakoredesc1'); ?></p>
												<p class="h-txt"> <?php echo app('translator')->getFromJson('toppage.hakoredesc2'); ?></p>

									</div>
									<div class="content-main col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('sec2img')); ?>.png" class="img-responsive center-block m_TB20">
									</div>
								</div>
							</div>
						</div>
					</div><!-- container -->
				</div>

				<div class="container-full content_manage" style=" background:  #fff;">
					<h2 style="background-color:#e20001; color:#fff; text-align:center; padding:15px 0;"><?php echo app('translator')->getFromJson('toppage.hakopoint'); ?></h2>
					<div class="container">

						<!-- sec2-2 -->
						<div class="row ">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<img src="/img/pages/toppage/<?php echo e($util->Tr('hakoren_trip')); ?>.gif" class="img-responsive center-block m_T30">
								<p class="h-txt m_T40"><?php echo app('translator')->getFromJson('toppage.tripdesc'); ?></P>
								<img src="/img/pages/toppage/<?php echo e($util->Tr('insideroundimg')); ?>.gif" class="img-responsive center-block m_T30">
								<p class="h-txt m_T40"> <?php echo app('translator')->getFromJson('toppage.tripdesc1'); ?></p>

								<!-- fukidashi -->
								<?php if($util->lang() == 'ja'): ?>
								<div class="row">
									<div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">

										<div class="bubble right many pink" class_id="" style="font-size: 16px">

											<p class="h-txt"> <?php echo app('translator')->getFromJson('toppage.tripdesc2'); ?></p>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
										<img src="/img/pages/toppage/fukidashi-01.png" class="img-responsive center-block m_T30">
									</div>
								</div>
								<div class="row m_T30">
									<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
										<img src="/img/pages/toppage/fukidashi-02.png" class="img-responsive center-block m_T30">
									</div>
									<div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">
											<div class="bubble left many blue" class_id="" style="font-size: 16px">
												<p class="h-txt"> <?php echo app('translator')->getFromJson('toppage.tripdesc3'); ?></p>
											</div>


									</div>
								</div>
								<?php endif; ?>
						  </div>
						</div>
					</div>
					<!-- container -->
				</div>

				<a id="compare"></a>
				<div class="container-full bg-grad-gray content_manage">
					<div class="container">

						<div class="row ">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

								<div class="row">

									<div class="col-lg-6 col-sm-push-6 col-md-6 col-sm-6 col-xs-12 trip_h">
										<?php echo app('translator')->getFromJson('toppage.tripdesc4'); ?>
									</div>
									<div class="content-main col-lg-6 col-md-6 col-sm-6 col-sm-pull-6 col-xs-12">
										<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('pricecomparison')); ?>.gif" class="img-responsive center-block m_TB20">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="container-full bg-grad-gray content_manage">
					<div class="container">

						<!-- sec2 -->
						<div class="row ">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 trip_h">
										<?php echo app('translator')->getFromJson('toppage.tripdesc5'); ?>
									</div>
									<div class="content-main col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('top-hakoren-cleanup')); ?>.gif" class="img-responsive center-block m_TB20">
									</div>
								</div>
							</div>
						</div>
					</div><!-- container -->
				</div>

				<div class="container-full bg-white clearfix visible-xs" style="padding-bottom:20px;">
					<a href="<?php echo e(URL::to('/')); ?>/search-car">
						<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/rentacar-sp-button.jpg" class="img-responsive center-block m_TB20">
					</a>
				</div>

				<!-- BEGIN roadservice -->
				<div class="container-full p_TB30 road-service-wrapper">
					<div class="container">
						<div class="row road-service-sec">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h3><?php echo app('translator')->getFromJson('toppage.roadservice'); ?></h3>
								<p><?php echo app('translator')->getFromJson('toppage.24support'); ?></p>
								<div class="row p_TB30 support-3">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<div class="center-block"><i class="fa fa-check"></i></div>
										<h4><?php echo app('translator')->getFromJson('toppage.trouble'); ?></h4>
										<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/top-hakoren-punk.png" class="img-responsive center-block m_TB20">
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<div class="center-block"><i class="fa fa-check"></i></div>
										<h4><?php echo app('translator')->getFromJson('toppage.titer'); ?></h4>
										<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/top-hakoren-battery.png" class="img-responsive center-block m_TB20">
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<div class="center-block"><i class="fa fa-check"></i></div>
										<h4><?php echo app('translator')->getFromJson('toppage.key'); ?></h4>
										<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/top-hakoren-key.png" class="img-responsive center-block m_TB20">
									</div>
								</div>
								<p class="rs-txt"> <?php echo app('translator')->getFromJson('toppage.tripdesc6'); ?></p>
							</div>
						</div>
					</div><!-- container -->
				</div>
				<!-- END roadservice -->

				<!--<div class="container-full bg-deep-blu top-suggest">
					<div class="container">-->
						<!-- sec2 -->
                       <!-- <div class="rental-top">
                              <div class="rental-img">
								<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/hakoren-top-chat-01.png">
							</div>
						</div>
						<div class="rental-bottom">
                        <div class="row">
							<div class="col-sm-3 rental-lft">
                              <div class="user-block"> <img id="bubble_user_img" src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/stuff_01.png">
                                <p id="bubble_user_name">ハコレン　北村</p>
                              </div>
                            </div>
							<div class="col-sm-9 rental-rt">-->

                                <!--吹き出しはじまり-->
								<!--<div class="rnt-common">
                                  <input type="hidden" id="car_class_options" />
                                  <input type="hidden" id="car_insurance_selected" />
                                  <form method="POST" id="bubble_search_form" action="<?php echo e(URL::to('/search-confirm')); ?>" accept-charset="UTF-8">
                                    <?php echo csrf_field(); ?>

                                    <input type="hidden" name="data_depart_date" id="data_depart_date" value="" />
                                    <input type="hidden" name="data_depart_time" id="data_depart_time" value="" />
                                    <input type="hidden" name="data_return_date" id="data_return_date" value="" />
                                    <input type="hidden" name="data_return_time" id="data_return_time" value="19:30" />
                                    <input type="hidden" name="data_depart_shop" id="data_depart_shop" value="" />
                                    <input type="hidden" name="data_depart_shop_name" id="data_depart_shop_name" value="" />
                                    <input type="hidden" name="data_return_shop" id="data_return_shop" value="" />
                                    <input type="hidden" name="data_return_shop_name" id="data_return_shop_name" value="" />
                                    <input type="hidden" name="data_car_category" id="data_car_category" value="" />
                                    <input type="hidden" name="data_passenger" id="data_passenger" value="all" />
                                    <input type="hidden" name="data_insurance" id="data_insurance" value="" />
                                    <input type="hidden" name="data_insurance_price1" id="data_insurance_price1" value="" />
                                    <input type="hidden" name="data_insurance_price2" id="data_insurance_price2" value="" />
                                    <input type="hidden" name="data_smoke" id="data_smoke" value="" />
                                    <input type="hidden" name="data_option_list" id="data_option_list" value="" />
                                    <input type="hidden" name="data_class_id" id="data_class_id" value="" />
                                    <input type="hidden" name="data_class_name" id="data_class_name" value="" />
                                    <input type="hidden" name="data_class_category" id="data_class_category" value="" />
                                    <input type="hidden" name="data_car_photo" id="data_car_photo" value="" />
                                    <input type="hidden" name="data_rent_days" id="data_rent_days" value="" />
                                    <input type="hidden" name="data_rendates" id="data_rendates" value="" />
                                    <input type="hidden" name="data_price_rent" id="data_price_rent" value="" />
                                    <input type="hidden" name="data_option_ids" id="data_option_ids" value="" />
                                    <input type="hidden" name="data_option_names" id="data_option_names" value="" />
                                    <input type="hidden" name="data_option_numbers" id="data_option_numbers" value="" />
                                    <input type="hidden" name="data_option_costs" id="data_option_costs" value="" />
                                    <input type="hidden" name="data_price_all" id="data_price_all" value="" />
                                    <input type="hidden" name="data_member" id="data_member" value="" />
                                    <input type="hidden" name="data_pickup" id="data_pickup" value="" />
                                    <input type="hidden" name="data_quick_booking" id="data_quick_booking" value="" />
                                  </form>
                                  <div id="bubble_content">
                                      <div class="company-block">
                                          <h2>レンタカー会社なんてどこも同じ・・・ ではありません！ 私にご案内させてください！</h2>
                                          <div class="company-btn-block">
                                            <ul>
                                              <li class="green-btn"> <a href="javascript:void(0);" onclick="javascript:showbubblestep(1);">試しに頼んでみる</a> </li>
                                              <li class="gray-btn"> <a href="javascript:void(0);" onclick="javascript:showbubbletext();">嫌だ、頼まない</a> </li>
                                            </ul>
                                          </div>
                                       </div>
                                  </div>
								</div>-->
								<!--吹き出し終わり-->

							<!--</div>
                        </div>
						</div>
					</div>--><!-- container -->
				<!--</div>-->

				<div class="container-full p_TB30 top-movie-sec-wrapper">
					<div class="container">
						<!--  -->
						<div class="row top-movie-sec">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<p><span class="cir-item"><?php echo app('translator')->getFromJson('toppage.fuku'); ?></span><span class="cir-item"><?php echo app('translator')->getFromJson('toppage.okina'); ?></span> <?php echo app('translator')->getFromJson('toppage.in'); ?> <span> <?php echo app('translator')->getFromJson('toppage.of'); ?> </span></p>
								<h3> <?php echo app('translator')->getFromJson('toppage.onebox'); ?> </h3>
								<h4> <?php echo app('translator')->getFromJson('toppage.hako'); ?> </h4>
								<p> <?php echo app('translator')->getFromJson('toppage.cheap'); ?> </p>
								<div class="box">
									<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('hakoren_features')); ?>.gif" class="img-responsive center-block">

								</div>
								<iframe  src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fhakorentcar%2F%3Fref%3Daymt_homepage_panel&width=83&layout=button_count&action=like&size=small&show_faces=false&share=false&height=21&appId" width="100" height="21" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
								<p class="fs15"> <?php echo app('translator')->getFromJson('toppage.nicerent'); ?></p>
								<p> <?php echo app('translator')->getFromJson('toppage.feedback'); ?> </p>
							</div>
						</div>
					</div><!-- container -->
				</div>

				<div class="container-full p_TB30 bg-grad-gray sec4 fuku_wrap">
					<div class="container">
						<!--  -->
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_B20">
								<a href="<?php echo e(URL::to('/')); ?>/shop/fukuoka-airport-rentacar-shop">
									<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('shop-fukuoka-airport-rentacar')); ?>.jpg" class="img-responsive center-block">
								</a>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_B20">
								<a href="<?php echo e(URL::to('/')); ?>/shop/naha-airport-rentacar-shop">
									<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('shop-naha-airport-rentacar')); ?>.jpg" class="img-responsive center-block">
								</a>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 m_B20">
								
								
								<?php if($util->lang() == 'ja'): ?>
								<a href="<?php echo e(URL::to('/')); ?>/first">
								<?php elseif($util->lang() == 'en'): ?>
								<a href="<?php echo e(URL::to('/')); ?>/faq/price">
								<?php endif; ?>
									<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('hakoren-banner04')); ?>.jpg" class="img-responsive center-block">
								</a>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 m_B20">
								<a href="<?php echo e(URL::to('/')); ?>/insurance">
									<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('hakoren-banner05')); ?>.jpg" class="img-responsive center-block">
								</a>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 m_B20">
								<?php if($util->lang() == 'ja'): ?>
								<a href="<?php echo e(URL::to('/')); ?>/business_contact">
								<?php elseif($util->lang() == 'en'): ?>
								<a href="<?php echo e(URL::to('/')); ?>/search-car">
								<?php endif; ?>
									<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/<?php echo e($util->Tr('hakoren-banner03')); ?>.jpg" class="img-responsive center-block">
								</a>
							</div>
						</div>
					</div><!-- container -->
				</div>

				<div class="container-full bg-white clearfix visible-xs" style="padding-bottom:30px;">
					<a href="<?php echo e(URL::to('/')); ?>/search-car">
						<img src="<?php echo e(URL::to('/')); ?>/img/pages/toppage/rentacar-sp-button.jpg" class="img-responsive center-block m_TB20">
					</a>
				</div>

				<!-- BEGIN FAQ  ROW  -->
				<div class="container-full" style="padding-top: 30px; margin-top:30px;">
					<div class="container">
						<div class="row"  id="topdiv14">
							<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 ">
								<div class="news-box">
									<h4 style="background:#d83428; color:#fff; padding:5px;"> <?php echo app('translator')->getFromJson('toppage.news'); ?> </h4>
									<ul style="">
									 <?php if(count($blogposts)): ?>
										<?php $__currentLoopData = $blogposts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $singlepost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<li style=""><span style="min-width:78px!important; display:inline-block;"><?php echo date('Y/n/j', strtotime($singlepost->publish_date)); ?>　</span>
												<?php if(!empty($singlepost['posttag'])): ?>
													<?php $title= $util->Tr('title'); ?>
													<span class="tags" style="background:
													<?php if($singlepost['posttag']->id == 1): ?>
														#f1a8d0
													<?php elseif($singlepost['posttag']->id == 2): ?>
														#f4df7a
													<?php elseif($singlepost['posttag']->id == 3): ?>
														#84ccf6
													<?php elseif($singlepost['posttag']->id == 4): ?>
														#8bce56
													<?php else: ?>
														#16D808
													<?php endif; ?>
													; min-width:90px!important; display:inline-block; color:#fff;">
														<?php if(!empty($singlepost['shop'])): ?>
															<a href="<?php echo e(url('/info/'.$singlepost['shop']->slug.'/'.$singlepost['posttag']->slug)); ?>" style="color:#fff;">
																<?php echo $singlepost['posttag']->title; ?>

															</a>
														<?php endif; ?>
													</span>
												 <?php endif; ?>
												&nbsp;<a href="<?php echo url('/view-post/'.$singlepost->slug); ?>"><?php echo $singlepost->title; ?></a>
											</li>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									 <?php else: ?>
										<li> <?php echo app('translator')->getFromJson('toppage.notfound'); ?> </li>
									 <?php endif; ?>
									</ul>

									<p style=" text-align:right; font-size:1em;  margin:5px 30px 0 10px;">
									<?php if(count($blogposts)): ?>
										<a href="<?php echo url('/info'); ?>" style="color:#333;"><i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo app('translator')->getFromJson('toppage.viewlist'); ?> </a>
									<?php endif; ?>
									</p>

								</div>
							</div>

							<div class="col-lg-5 col-md-5 col-sm-5  col-xs-12" >
								<div class="faq-box">
									<h4 style="background:#d83428; color:#fff; padding:5px;"> <?php echo app('translator')->getFromJson('toppage.faq'); ?></h4>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
												<ul style="">
													<a href="/faq/booking#henkou">
														<li> <?php echo app('translator')->getFromJson('toppage.cancelbook'); ?> </li>
													</a>
													<a href="/faq/booking#toujitsu">
														<li> <?php echo app('translator')->getFromJson('toppage.book'); ?> </li>
													</a>						
													<a href="/faq/price#kisetsu">
														<li> <?php echo app('translator')->getFromJson('toppage.dependtime'); ?> </li>
													</a>
													<a href="/faq/insurance#menseki">
														<li> <?php echo app('translator')->getFromJson('toppage.fee'); ?></li>
													</a>
													<a href="/faq/insurance#menseki02">
														<li> <?php echo app('translator')->getFromJson('toppage.compansation'); ?> </li>
													</a>
												</ul>
											</div>
										</div>


									<p style=" text-align:right; font-size:1em; margin:5px 30px 0 10px;">
										<a href="/faq/price"><i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo app('translator')->getFromJson('toppage.viewlist'); ?> </a>
									</p>

								</div>
							</div>


						</div><!-- END ROW2 campaign -->

					</div>
				</div>

<!-- BEGIN NEWS  ROW  -->
<!-- BEGIN fbposts  -->

<!--<div class="container">--><!-- BEGIN *FULL WIDTH* -->
	<!--<div class="row" style="margin-top:50px;">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h4 class="text-center fb-ttl">Facebook 最近の投稿</h4>
		</div>
	</div>
</div>
<div class="container-full fb-posts" >--><!-- BEGIN *FULL WIDTH* -->
	<!--<div class="container">
		<div class="col-lg-12 col-md-12 col-sm-12 col-lg-offset-0 col-md-offset-01 col-sm-offset-0 col-xs-offset-0 col-xs-12" >
			<div id="fbcontentdiv" class="row">
				<div id="loadingdiv" style="background: rgba(255, 255, 255, 0.66);text-align: center;z-index:9999999">
					<img src="/img/loading.gif" style="width:50px;height:50px;">
				</div>

			</div>
		</div>
	</div>
</div>-->
		<!-- END CONTENT -->
	<!--</div>
	</div>
	</div>-->
	<!-- END CONTAINER -->
    <div class="modal fade modal-warning modal-save" id="modalError" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="error-text"><?php echo e(Lang::get('modals.error_modal_default_message')); ?> </p>
                </div>
                <div class="modal-footer text-center">
                    <?php echo Form::button('<i class="fa fa-fw '.Lang::get('modals.confirm_modal_button_cancel_icon').'" aria-hidden="true"></i> ' . Lang::get('modals.error_modal_button_cancel_text'), array('class' => 'btn btn-outline btn-flat', 'type' => 'button', 'data-dismiss' => 'modal' )); ?>

                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('modals.modal-membership', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
  <style>
  </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/jquery-validation/js/localization/messages_ja.js" type="text/javascript"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/slick/slick.min.js"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
	<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
	<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
	<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
	<script src="<?php echo e(URL::to('/')); ?>/js/velocity.min.js"></script>

	<script type="text/javascript">
        var today = new Date();
        today = new Date(today.getFullYear(), today.getMonth(), today.getDate(),0,0,0,0);
        var tomorrow = new Date();
        tomorrow.setDate(today.getDate()+1);

        function showShopBlock() {
            $('#select-yes').fadeOut();
            $('.h-left-wrapper').fadeIn();
            var typed = new Typed('#type_question', {
                strings: ['<?php echo app('translator')->getFromJson('toppage.whichshop'); ?>'],
                typeSpeed: 60,
                backSpeed: 0,
                fadeOut: true,
                loop: false,
                onComplete: function(self) {
                    setTimeout(function(){
                        $('.shop-option').fadeIn();
                    }, 1000);
                }
            });
        }

        $('#start-bubble').velocity(
            { translateY: "20px" },
            { loop: true }
        ).velocity("reverse");

		$(document).ready(function() {
			// $('#select-date').hide();
			// $('#select-option').hide();
			// $('#select-confirm-option').hide();
			var searchConfirmNo = Cookies.get("search-confirm-no");
			if(searchConfirmNo){
				$('#select-confirm-no').addClass('active');
			}
			//make search option active / checked
			$('.search-confirm-option').click(function(){
				$('.search-confirm-option').removeClass("active");
				$(this).addClass("active");
			});

			$('#select-confirm-yes').click(function(){
				Cookies.remove("search-confirm-no");
				$('#select-confirm-option').hide();
				$('#select-option').show();
				$('#progress-shop').addClass('current');
				$('#progress-date').addClass('current');
				$('#progress-option').addClass('current active');
			});

			$('#select-confirm-no').click(function(){
				searchCondition("last");
				Cookies.set("search-confirm-no", true);
			});
		});
		//get facebook post page
		$(function() {
            $('.slider').slick({
                // lazyLoad: 'ondemand',
                infinite: true,
                dots:false,
                slidesToShow: 1,
                centerMode: true, //要素を中央寄せ
                centerPadding:'20%', //両サイドの見えている部分のサイズ
                variableWidth: true,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: { slidesToShow: 1 }
                    },
                    {
                        breakpoint: 480,
                        settings: { slidesToShow: 1 }
                    }
                ]
            });

			var screenwidth = window.innerWidth;
			$.ajax({
				type: "get",
				url: '/getfbpost',
				success: function (result) {
					$('#fbcontentdiv').html(result);
				},
				error: function (result) {
					$('#fbcontentdiv').html("");
				}
			});
		});

        var depart_shop = $('input[name="depart_shop"]');
        var return_shop = $('input[name="return_shop"]');
        var depart_date = $('input[name="depart_date"]');
        var return_date = $('input[name="return_date"]');
        var depart_time = $('select[name="depart_time"]');
        var return_time = $('select[name="return_time"]');
        depart_shop.change( function () {
            return_shop.val($('input[type="radio"]:checked').val());
        });

        <?php
        $current = time();
        $shop_close_time = strtotime(date('Y-m-d 18:30:00'));
        if($current >= $shop_close_time) {
            $cal_start_date = date('Y-m-d', strtotime('tomorrow'));
        } else {
            $cal_start_date = date('Y-m-d');
        }
        ?>

        $('#depart-datepicker, #return-datepicker').datepicker({
			
            language: "ja",
			
            format: 'yyyy-mm-dd',
            startDate: '<?php echo e($cal_start_date); ?>',
            endDate: '<?php echo e(date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 1 year"))); ?>',
            orientation: "bottom",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });

        var departPicker = $('#depart-datepicker');
        var returnPicker = $('#return-datepicker');
        // time selector initialize
        var dTimepicker = $('#depart_time'),
            rTimepicker = $('#return_time');

        function getAfterHours(hr) {
            // get time string after <hr> hours
            var crt = new Date(),
                crthour = crt.getHours(),
                crtmin = crt.getMinutes(),
                hrAfter = crthour * 1 + hr;
            if(hrAfter < 10) hrAfter = '0' + hrAfter;
            return hrAfter + ':' + crtmin;
        }

        function selectFirstOrLastTime(picker, cond) {
            // cond is 'first' or 'last'
            picker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
            picker.val(picker.find('option:'+ cond).val());
            picker.trigger('chosen:updated');
        }

        // initialize time pickers
        var all_hours_disable = updateTimepicker(dTimepicker, departPicker.datepicker('getDate'), today, getAfterHours(3));
        if (all_hours_disable) {
            departPicker.datepicker('setStartDate', tomorrow).datepicker('setDate', tomorrow);
            selectFirstOrLastTime(dTimepicker, 'first');
        }
        all_hours_disable = updateTimepicker(rTimepicker, returnPicker.datepicker('getDate'), today, getAfterHours(4));
        if(all_hours_disable) {
            returnPicker.datepicker('setStartDate', tomorrow).datepicker('setDate', tomorrow);
            selectFirstOrLastTime(rTimepicker, 'last');
        } else {
            if(compareDateWithToday(returnPicker.datepicker('getDate')) > 0)
                selectFirstOrLastTime(rTimepicker, 'last');
        }

        function compareDateWithToday(date) {
            var q = new Date(),
                today = new Date(q.getFullYear(),q.getMonth(),q.getDate(),0,0,0,0);
            if(date.getTime() > today.getTime())
                return 1;
            else if(date.getTime() == today.getTime())
                return 0;
            else
                return -1;
        }

        function updateTimepicker(picker, date, refdate, reftime) {
            var dYear = date.getFullYear(), dMonth = date.getMonth(), dDate = date.getDate();
            var refhm = reftime.split(':');

            var cTime = (new Date(refdate.getFullYear(), refdate.getMonth(), refdate.getDate(), refhm[0], refhm[1],0,0)).getTime();// + 10800 * 1000;
            // if(isdepart === true) cTime += 10800 * 1000;
            var hours = picker.find('option'), cnt = hours.length;
            var first = -1;
            var oldval = picker.val();

            for (var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]);
                var hr_min = hOption.val().split(':');
                var dTime = (new Date(dYear, dMonth, dDate, hr_min[0], hr_min[1], 0, 0)).getTime();
                if (dTime < cTime) {
                    hOption.prop('disabled', true);
                    hOption.css('display', 'none');
                }
                else {
                    hOption.prop('disabled', false);
                    hOption.css('display', 'block');
                    if (first < 0) first = k;
                }
            }
            if (picker.val() < oldval) picker.val(oldval);
            if (compareDateWithToday(date) === 0 && first >= 0) picker.val($(hours[first]).val());
            picker.trigger("chosen:updated");
            return first < 0;
        }

        departPicker.datepicker().on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
            if(compareDateWithToday(minDate) == 0){ // if today
                var disable1 = updateTimepicker(dTimepicker, minDate, today, getAfterHours(3));
                var disable2 = updateTimepicker(rTimepicker, minDate, today, getAfterHours(4));
                if(disable1 == true ) {
                    dTimepicker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
                    if(disable2 == true)
                        rTimepicker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
                    minDate.setDate(minDate.getDate() + 1);
                    departPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(dTimepicker, 'first');
                    returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(rTimepicker, 'last');
                }
                if(disable1 == false && disable2 == true) {
                    minDate.setDate(minDate.getDate() + 1);
                    returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(rTimepicker,'last');
                }
            } else if(compareDateWithToday(minDate) > 0) {
                selectFirstOrLastTime(dTimepicker,'first');
                selectFirstOrLastTime(rTimepicker,'last');
            }
        });

        returnPicker.datepicker().on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            var departDate = departPicker.datepicker('getDate');
            var departTime = dTimepicker.val().split(':');
            departTime[0] = departTime[0] * 1 + 1;
            if(departTime[0] < 10) departTime[0] = '0' + departTime[0];
            var all_hours_disable = updateTimepicker(rTimepicker, maxDate, departDate, departTime[0]+':'+departTime[1]);
            if (all_hours_disable) {
                maxDate.setDate(maxDate.getDate() + 1);
                returnPicker.datepicker('setStartDate', maxDate).datepicker('setDate', maxDate);
                selectFirstOrLastTime(rTimepicker,'last');
            }
        });

        dTimepicker.change(function () {
            var dDate = departPicker.datepicker('getDate'),
                dy = dDate.getFullYear(), dm = dDate.getMonth(), dd = dDate.getDate();
            var rDate = returnPicker.datepicker('getDate'),
                ry = rDate.getFullYear(), rm = rDate.getMonth(), rd = rDate.getDate();
            var hr_min = $(this).val().split(':');
            var dTime = (new Date(dy, dm, dd, hr_min[0], hr_min[1], 0, 0)).getTime() + 3600 * 1000;

            var hours = rTimepicker.find('option').removeAttr('disabled').css('display','block'),
                cnt = hours.length,
				oldval = rTimepicker.val();
            var index = -1;
            for (var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]), hm = hOption.val().split(':');
                var rTime = (new Date(ry, rm, rd, hm[0], hm[1], 0, 0)).getTime();
                if (rTime <= dTime)
                    hOption.attr('disabled', 'disabled').css('display', 'none');
                else {
                    // hOption.removeAttr('disabled').css('display','block');
                    if (index < 0 && hOption.val() == oldval) {
                        hOption.attr('selected', true);
                        index = k;
                    }
                }
            }
            if (index < 0) {
                dDate.setDate(dDate.getDate() + 1);
                returnPicker.datepicker('setStartDate', dDate).datepicker('setDate', dDate);
                selectFirstOrLastTime(rTimepicker,'last');
            }
            rTimepicker.trigger("chosen:updated");
            // initDepartReturnTime();
        });

        function showError( txt ) {
            $('p.error-text').html(txt);
            $('#modalError').modal('show');
        }

        function check() {
            if( depart_date.val() > return_date.val() ) {
                showError('返却日は出発日より後である必要があります。');
                console.log('date error');
                return false;
            }
            if( depart_date.val() === return_date.val() && depart_time.val() >= return_time.val() ) {
                showError('復帰時間は出発時間より遅くなければなりません。');
                console.log('time error');
                return false;
            }
            return true;
        }


		function showbubblestep(step_num){

			var bubble_user_img  = '<?php echo URL::asset("img/pages/toppage/stuff_01.png"); ?>';
			var bubble_user_name = 'ハコレン　北村';
			$('#bubble_user_img').attr('src', bubble_user_img);
			$('#bubble_user_name').html(bubble_user_name);

			var data  = [];
			var token = '<?php echo csrf_token(); ?>';
			if(step_num == 0){
            	data.push(  {name: 'step_num', value: step_num},
							{name: '_token', value: token});
			}
			else if(step_num == 1){
            	data.push(  {name: 'step_num', value: step_num},
							{name: '_token', value: token});
			}else if(step_num == 2){
				var car_class = $('input[name="bubble_car_class"]:checked').val();
				if(!car_class){
					car_class = $('#data_class_id').val();
				}
            	data.push(  {name: 'step_num', value: step_num},
							{name: '_token', value: token},
							{name: 'car_class', value: car_class});
			}else if(step_num == 3){
				var car_options = [];
				$('input[name="bubble_car_option[]"]:checked').each(function(i){
				  car_options[i] = $(this).val();
				});
            	data.push(  {name: 'step_num', value: step_num},
							{name: '_token', value: token},
							{name: 'car_options', value: car_options});
				$('#car_class_options').val(car_options);

				$('#data_option_ids').val(car_options);

				if($('#car_ins_checked').is(':checked')){
					$('#car_insurance_selected').val('2');
					$('#data_insurance').val('2');
					$('#data_insurance_price1').val($('#ins_cost1').val());
					$('#data_insurance_price2').val($('#ins_cost2').val());
				}else{
					$('#car_insurance_selected').val('0');
					$('#data_insurance').val('0');
					$('#data_insurance_price1').val($('#ins_cost1').val());
					$('#data_insurance_price2').val($('#ins_cost2').val());
				}

			}else if(step_num == 4){
				var departing_shop = $('input[name="bubble_departing_shop"]:checked').val();
				if(!departing_shop){
					departing_shop = $('#data_depart_shop').val();
				}
				var departing_date = $('input[name="bubble_departing_date"]').val();
				var departing_time = $('#bubble_departing_time').val();
				var numbers_nights = $('#numbers_nights').val();

				$('#data_depart_date').val(departing_date);
				$('#data_depart_time').val(departing_time);
				$('#data_depart_shop').val(departing_shop);
            	data.push(  {name: 'step_num', value: step_num},
							{name: '_token', value: token},
							{name: 'departing_shop', value: departing_shop},
							{name: 'departing_date', value: departing_date},
							{name: 'departing_time', value: departing_time},

							{name: 'car_class', value: $('#data_class_id').val()},
							{name: 'car_options', value: $('#car_class_options').val()},
							{name: 'car_insurance', value: $('#car_insurance_selected').val()},

							{name: 'numbers_nights', value: numbers_nights});
			}else if(step_num == 5){
				 $('#data_smoke').val($('#bubble_car_smoking').val());
				 $('#bubble_search_form').submit();
			}

			$('#bubble_content').html('<img src="/img/loading.gif" style="width:100px;height:100px;">');
            $.ajax({
                url  : '<?php echo URL::to("/showbubblestep"); ?>?date='+new Date(),
                type : 'POST',
                data : data,
				dataType: "json",
                success : function(result,status,xhr) {
                	$('#bubble_content').html(result.html);
					if(result.others.hasOwnProperty('data_price_all')){
						$('#data_depart_date').val(result.others.data_depart_date);
						$('#data_depart_time').val(result.others.data_depart_time);
						$('#data_return_date').val(result.others.data_return_date);
						$('#data_return_time').val(result.others.data_return_time);
						$('#data_depart_shop').val(result.others.data_depart_shop);
						$('#data_depart_shop_name').val(result.others.data_depart_shop_name);
						$('#data_return_shop').val(result.others.data_return_shop);
						$('#data_return_shop_name').val(result.others.data_return_shop_name);
						$('#data_price_rent').val(result.others.data_price_rent);
						$('#data_option_ids').val(result.others.data_option_ids);
						$('#data_option_names').val(result.others.data_option_names);
						$('#data_option_numbers').val(result.others.data_option_numbers);
						$('#data_option_costs').val(result.others.data_option_costs);
						$('#data_price_all').val(result.others.data_price_all);

						$('#data_car_category').val(result.others.data_car_category);
						$('#data_class_name').val(result.others.data_class_name);
						$('#data_car_photo').val(result.others.data_car_photo);
						$('#data_rent_days').val(result.others.data_rent_days);
						$('#data_rendates').val(result.others.data_rendates);
						$('#data_class_category').val(result.others.data_class_category);

						$('#data_insurance').val(result.others.data_insurance);
						$('#data_insurance_price1').val(result.others.data_insurance_price1);
						$('#data_insurance_price2').val(result.others.data_insurance_price2);
						$('#data_quick_booking').val(1);

					}
                },
                error : function(xhr,status,error){
                    console.log(error);
                }
            });
		}


		function showbubbletext(){
			var bubble_user_img  = '<?php echo URL::asset("img/pages/toppage/stuff_02.png"); ?>';
			var bubble_user_name = 'User name';
            $('#bubble_content').html('<img src="/img/loading.gif" style="width:100px;height:100px;">');
			$.ajax({
                url  : '<?php echo URL::to("/showbubbletext"); ?>',
                type : 'post',
                data : {'_token':$('input[name="_token"]').val()},
				dataType: "json",
                success : function(result,status,xhr) {
					$('#bubble_user_img').attr('src', bubble_user_img);
					$('#bubble_user_name').html(bubble_user_name);
                	$('#bubble_content').html(result);
                },
                error : function(xhr,status,error){
                    console.log(error);
                }
            });
		}
		//search box condition event
		var options = [];
		function searchCondition(level, id ,slug){
			if(slug != "" && id !="" && level == 'date'){

				$.ajax({

					type : "post",
					data : {"shop_id" : id , '_token':$('input[name="_token"]').val()},
					url  : "/getOptionsByShopid",
					dataType : "json",
					success : function(response,status,xhr){

						if(response.result.length > 0){
							$("#caroptions_vpn").html('');
							$.each(response.result,function(index,value){

								var $class 		  = "search_"+index;
								var $option_index = ' option_'+value.google_column_number;
								var handleOptions = "('"+$class+"',"+value.id+")";

								var html =
								'<li class="search_li '+ $class + $option_index +' " onclick="handleOptions'+handleOptions+'" style="margin-top: 7px;" >'
							 		+'<input type="checkbox" name="options[]" id="options'+value.id+'" class="hidden" value="'+value.id+'">'
									+'<span style="padding-left: 10px;">'+ value.name +'</span>'
								+'</li>';
								$("#caroptions_vpn").append(html);
							});
						}
						else {

							$("#caroptions_vpn").html('No records found.');
						}
                    },
					error  : function(xhr,status,error){
						console.log('Error : ',error);
					}
				});
			}


			$('.step').removeClass('active');
			$('.step').removeClass('current');
			// $('#select-shop').hide();
			// $('#select-date').hide();
			// $('#select-option').hide();
			$('#select-confirm-option').hide();
			if(level == 'shop') {
				$('#progress-shop').addClass('current').addClass('active');
                $('.search_block.current').removeClass('current');
				$('#select-shop').addClass('current');
			}
			if(level == 'date') {
                $('.shop_class').removeClass('active');
                $('.shop_class_'+id).addClass('active');

                setTimeout(function(){
                    $('#progress-shop').addClass('current');
                    $('#progress-date').addClass('current').addClass('active');
                    $('.search_block.current').removeClass('current');
                    $('#select-date').addClass('current');
                    $('input[name="depart_shop"]').val(id);
                    if(slug != 'naha-airport') {
                        $('.option_38').hide();
                    }else {
                        $('.option_38').show();
                    }

                    setTimeout(function() {
                        var typed1 = new Typed('#date_question', {
                            strings: ['<?php echo app('translator')->getFromJson('toppage.choosedate'); ?><br><?php echo app('translator')->getFromJson('toppage.clickbluebutton'); ?>'],
                            typeSpeed: 60,
                            backSpeed: 0,
                            fadeOut: true,
                            loop: false,
                            onComplete: function(self) {
                                // setTimeout(function(){
                                    $('.time-block').fadeIn();
                                // }, 500);
                            }
                        });

                    }, 200);
                }, 500);
            }
			if(level == 'option') {
				$('#progress-shop').addClass('current');
				$('#progress-date').addClass('current');
                $('.search_block.current').removeClass('currrent');
				$('#select-confirm-option').addClass('current');
				$('#progress-option').addClass('current active');
			}
			if(level == 'confirm') {
                $('.search_block.current').removeClass('current');
				$('#select-confirm-option').addClass('current');
				$('#progress-shop').addClass('current');
				$('#progress-date').addClass('current');
				$('#progress-option').addClass('current active');
			}
			if(level == 'last') {
				// $('input[name="options[]"]').val(options);
				$('input[name="return_shop"]').val($('input[name="depart_shop"]').val());
				if(check()){
                    $('.slider-fixed').hide();
                    $('#searchform').submit();
                };
			}

        }

		//add options in search box
		function handleOptions(name, id){

			var index = options.indexOf(id);
			var cond = '';
			if(index != -1) cond = 'remove';
			else cond = 'add';
			if(cond == 'add') {
				options.push(id);
				$("."+name).addClass("active");
				$('#options' + id).prop('checked', true);
			}else {
				var index = options.indexOf(id);
				if (index >= 0) {
					options.splice( index, 1 );
				}
				$("."+name).removeClass("active");
                $('#options' + id).prop('checked', false);
			}
		}
		//select shop id
		function setShop(){
			<?php $count = 0 ; ?>
			<?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php if($count == '0'): ?>
					$('input[name="depart_shop"]').val('<?php echo e($shop->id); ?>}');
					$('.shop_class_'+'<?php echo e($shop->id); ?>').addClass('active');
				<?php endif; ?>
				<?php $count++; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		}

		// setShop();

    </script>

<script type="text/javascript">
$(function(){
  $('a[href^="#"].anchor-link').click(function(){
    var speed = 500;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>