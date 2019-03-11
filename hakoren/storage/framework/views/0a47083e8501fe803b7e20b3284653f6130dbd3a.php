<?php $__env->startSection('template_title'); ?>
店舗・送迎
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('template_linked_css'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/page_shop_detail.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
    <style>
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<script>
		<?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false): ?>
        dataLayer.push({
            'ecommerce': {
                'currencyCode': 'JPY',
                'impressions': [
					<?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {
                        'name': '<?php echo e($cls->name); ?>',
                        'id': '<?php echo e($cls->class_id); ?>',
                        'price': '<?php echo e($cls->price); ?>',
                        
                        'list': 'Shop <?php echo e($shop->region_code); ?>',
                        'position': <?php echo e($key + 1); ?>

                    },
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            }
        });
        <?php endif; ?>
	</script>

	<div class="page-container">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head hidden-xs">
            <div class="container clearfix">
                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo e(URL::to('/')); ?>"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="hidden">
                            <a href="#"><?php echo e(trans('fs.parent')); ?></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
							<?php
								$name = $util->Tr('name');
							?>
                            <span><?php echo $shop->$name; ?> <?php echo app('translator')->getFromJson('shop.information'); ?></span>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE TITLE -->
            </div>
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
          
            <!-- BEGIN CONTENT HEADER -->
            <div class="dynamic-page-header dynamic-page-header-default">
                <div class="container clearfix">
                    <div class="col-md-12 bottom-border ">
                        <div class="page-header-title">
							<h1><?php echo e($shop->$name); ?> <?php echo app('translator')->getFromJson('shop.information'); ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container shopdetail">
                    <div class="row">
                        <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- box 1 -->
							<div class="box-shadow shopmain_wrap">
								<!-- section 1 -->
								<div class="row shopbox01">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<?php
                                        	$thumb_path = $util->Tr('thumb_path');
                                        	$name = $util->Tr('name');
										?>
										<img src="<?php echo e(url('/').$shop->$thumb_path); ?>" class="img-responsive center-block" style="margin-bottom: 10px;">
									</div>
								</div>
								<div class="row shopbox02">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<a href="tel:<?php echo e($shop->phone); ?>">
											<div style="font-size:20px;text-align:center;padding:10px 30px; background:#e20001;color:#fff; line-height:1.2em;">
												<p style="margin-top:0;">
													<?php echo e($shop->$name); ?> <?php echo app('translator')->getFromJson('shop.shopguide'); ?>
												</p>
												<?php echo e($shop->phone); ?>

											</div>
										</a>
									</div>
								</div>
								<div class="row shopbox01">
									<div class="col-md-10 col-md-offset-1">
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												<?php
													$hakoren_shop_info = $util->Tr('hakoren-shop-info');
                                                	$hakoren_shop_pickup = $util->Tr('hakoren-shop-pickup');
                                                	$hakoren_shop_rentacar = $util->Tr('hakoren-shop-rentacar');
													?>
												
													<img src="<?php echo e(URL::to('/')); ?>/img/pages/shop/<?php echo e($hakoren_shop_info); ?>.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer" onclick="scrollToSection('#info')">
												
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												
													<img src="<?php echo e(URL::to('/')); ?>/img/pages/shop/<?php echo e($hakoren_shop_pickup); ?>.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer" onclick="scrollToSection('#pickup')">
												
											</div>
											<!--
											<div class="col-lg-3 col-md-3">
												
													<img src="<?php echo e(URL::to('/')); ?>/img/pages/shop/hakoren-shop-news.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer" onclick="scrollToSection('#news')">
												
											</div>
											-->
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												
													<?php if($util->lang()== 'ja'): ?>
														<img src="<?php echo e(URL::to('/')); ?>/img/pages/shop/<?php echo e($hakoren_shop_rentacar); ?>.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer" onclick="scrollToSection('#rentacar')">
													<?php elseif($util->lang()== 'en'): ?>
														<a href="<?php echo e(URL::to('/')); ?>/search-car">
														<img src="<?php echo e(URL::to('/')); ?>/img/pages/shop/<?php echo e($hakoren_shop_rentacar); ?>.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer">
														</a>
													<?php endif; ?>
												
											</div>
										</div>
									</div>
								</div>
								<h2 id="info"> <?php echo app('translator')->getFromJson('shop.shopinform'); ?></h2>
								<div class="row sougei-block">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<table class="table shop-detail-tbl">
											<?php
                                            	$postal 		= $util->Tr('postal');
                                            	$prefecture 	= $util->Tr('prefecture');
                                            	$city			= $util->Tr('city');
												$address1		= $util->Tr('address1');
                                            	$address2		= $util->Tr('address2');
                                            	$comment		= $util->Tr('comment');
                                            	$content1		= $util->Tr('content1');
											?>
											<tr>
												<th> <?php echo app('translator')->getFromJson('shop.address'); ?></th>
												<td>
													<small>〒<?php echo e($shop->$postal); ?></small>
													<?php echo e($shop->$prefecture); ?><?php echo e($shop->$city); ?><?php echo e($shop->$address1); ?><?php echo e($shop->$address2); ?>

												</td>
											</tr>
											<tr>
												<th> <?php echo app('translator')->getFromJson('shop.businesstime'); ?> </th>
												<td class="b-time">
													<span class="dblock" style="margin-right: 10px; margin-bottom:5px;">9:00 ~ 19:30</span><span style="padding:5px 10px; background:#e20001; color:#fff; font-weight:500;-webkit-border-radius: 3px !important;-moz-border-radius: 3px !important;border-radius: 3px !important;"> <?php echo app('translator')->getFromJson('shop.dayweek'); ?> </span>
												</td>
											</tr>
											<tr>
												<th>TEL</th>
												<td><a href="tel:<?php echo e($shop->phone); ?>" style="color: inherit"><?php echo e($shop->phone); ?></a></td>
											</tr>
											<tr>
												<th> <?php echo app('translator')->getFromJson('shop.contact'); ?> </th>
												<td>
													<a href="<?php echo e(URL::to('/')); ?>/contact" style="color: inherit"> <?php echo app('translator')->getFromJson('shop.inquiry'); ?> </a>
												</td>
											</tr>

											<tr>
												<th>Facebook URL</th>
												<td><a href="https://www.facebook.com/hakorentcar/" target="_blank" style="color: inherit"> <?php echo app('translator')->getFromJson('shop.facebook'); ?> </a></td>
											</tr>
											<?php if(!empty($shop->comment)): ?>
											<tr>
												<th> <?php echo app('translator')->getFromJson('shop.comment'); ?> </th>
												<td style="text-align: justify;">
													<?php echo e($shop->$comment); ?>

												</td>
											</tr>
											<?php endif; ?>

										</table>
									</div>　
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="gmap" id="map_canvas">
											<iframe width="500" height="700" frameborder="0" style="border:0"
													src="https://www.google.com/maps/embed/v1/place?
q=〒<?php echo e($shop->postal); ?><?php echo e($shop->prefecture); ?><?php echo e($shop->city); ?><?php echo e($shop->address1); ?><?php echo e($shop->address2); ?>&key=AIzaSyALiOyzY1rWm_pOnfh4impcuME0VRFaE3I"></iframe>
										</div>
									</div>
								</div>
								<!-- section 1 -->
								<div id="pickup"></div>
								<!-- sougei-block 1 -->
								<div class="sougei-block">
									<div class="clearfix bg-white">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<!--
											<div class="col-xs-12 text-left" style="padding: 15px;">
												<img src="<?php echo e($pickup->thumb_path); ?>">
											</div>
											-->
											<div class="shop_content">
												<?php echo $pickup->$content1; ?>

											</div>
										</div>
									</div>
								</div>
								<!--
								<h2 id="news">最新情報とお得情報</h2>
								<div class="sougei-block">
									<div class="clearfix bg-white">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<p class="ellipsis">
												<?php echo e(date('Y年n月j日', strtotime($post->publish_date))); ?>&emsp;
												<span class="<?php echo e(($post->post_tag_id == 1)? 'news':'promotion'); ?>">
													<?php echo e(($post->post_tag_id == 1)? '最新情報':'お得情報'); ?>

												</span>
												&emsp;
                                                
                                                <a href="<?php echo e(URL::to('/')); ?>/view-post/<?php echo e($post->slug); ?>" style="color:#555;"><?php echo e($post->title); ?></a>
											</p>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</div>
									</div>
								</div>
								-->
							</div>
							<div id="rentacar"></div>
						</div>

						<?php if($util->lang()=='ja'): ?>
						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 ribbon_main">
							<?php
                              $models = $util->Tr('models');
                              $name   = $util->Tr('name');
							?>
							<?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($class->carclass_status == 1 && $class->cs_id == $shop->id ): ?>
									<div class="box-shadow relative">
										<div class="ribbon-block bg-grad-red">
											<h3><?php echo e($class->name); ?></h3><!--<?php if($class->smoke == '0'): ?> 禁煙車 <?php endif; ?>-->
										</div>
										<!-- ROW -->
										<div class="row margin-bottom-40 carclass-margin">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<img src="<?php echo e(URL::to('/')); ?>/<?php echo e($class->thumb); ?>" class="img-responsive center-block m_Txs60">
												<p class="sml-txt"> <?php echo app('translator')->getFromJson('shop.imgdiagram'); ?> </p>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<h3 class="jnum"> <?php echo app('translator')->getFromJson('shop.passenger'); ?>： <span><?php echo e($class->tag); ?></span> <?php echo app('translator')->getFromJson('shop.name'); ?></h3>

												<table class="table carclass-tbl">
													<tr>
														<th width="30%"><span class="bg-grad-gray"> <?php echo app('translator')->getFromJson('shop.model'); ?></span></th>
														<td>
															<?php echo e(implode('、', $class->$models)); ?>

														</td>
													</tr>
													<tr>
														<th><span class="bg-grad-gray"> <?php echo app('translator')->getFromJson('shop.option'); ?></span></th>
														<td>
															<?php $op = 0;
															$ops = [];
															foreach($class->options as $option) {
																if($option->google_column_number < 200) {
																	$ops[] = $option->$name;
																}
															}
															echo implode('、', $ops);
															?>
														</td>
													</tr>
													<tr>
														<th>
															<p class="bg-grad-gray" style="margin: 0; border:1px solid #ccc;padding: 2px 5px;">
																<?php echo app('translator')->getFromJson('shop.dayprice'); ?> <br>( <?php echo app('translator')->getFromJson('shop.1n2d'); ?>)
															</p>
														</th>
														<td>
															<?php echo e(number_format($class->price)); ?> <?php echo app('translator')->getFromJson('shop.yen'); ?> /1 <?php echo app('translator')->getFromJson('shop.day'); ?>( <?php echo app('translator')->getFromJson('shop.taxcharge'); ?>)<br>
															(<?php echo e(number_format($class->price * 2)); ?> <?php echo app('translator')->getFromJson('shop.yen'); ?> <?php echo app('translator')->getFromJson('shop.1n2d'); ?>)
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn">
													<a class="bg-grad-red" onclick="gotoDetail('<?php echo e($class->name); ?>', <?php echo e($class->class_id); ?>, <?php echo e($class->price); ?>,<?php echo e($key + 1); ?>)"> <?php echo app('translator')->getFromJson('shop.viewdetail'); ?> </a>
												</div>
											</div>
										</div>
										<!-- ROW -->
									</div>
									<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								<?php if($shop->id == 4): ?>


								<div class="box-shadow relative">
									<div class="ribbon-block bg-grad-red">
										<h3>MB</h3> 
									</div>
									<!-- ROW -->
									<div class="row margin-bottom-40 carclass-margin">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<img src="<?php echo e(URL::to('/')); ?>/img/mb.png" class="img-responsive center-block m_Txs60">
											<p class="sml-txt"> <?php echo app('translator')->getFromJson('shop.imgdiagram'); ?> </p>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<h3 class="jnum"> <?php echo app('translator')->getFromJson('shop.passenger'); ?>： <span>26～29</span> <?php echo app('translator')->getFromJson('shop.name'); ?> </h3>

											<table class="table carclass-tbl">
												<tr>
													<th width="30%"><span class="bg-grad-gray"> <?php echo app('translator')->getFromJson('shop.model'); ?> </span></th>
													<td>
														<?php echo app('translator')->getFromJson('shop.coaster'); ?> 、 <?php echo app('translator')->getFromJson('shop.riese'); ?>
													</td>
												</tr>
												<tr>
													<th><span class="bg-grad-gray"> <?php echo app('translator')->getFromJson('shop.option'); ?></span></th>
													<td>
														<?php echo app('translator')->getFromJson('shop.babyseat'); ?> 、 <?php echo app('translator')->getFromJson('shop.juniorseat'); ?> 、 <?php echo app('translator')->getFromJson('shop.childseat'); ?> 、 <?php echo app('translator')->getFromJson('shop.airpotpickup'); ?> 、 <?php echo app('translator')->getFromJson('shop.etccard'); ?>
													</td>
												</tr>
												<tr>
													<th>
														<p class="bg-grad-gray" style="margin: 0; border:1px solid #ccc;padding: 2px 5px;">
															<?php echo app('translator')->getFromJson('shop.price'); ?>
														</p>
													</th>
													<td>
														21,900 <?php echo app('translator')->getFromJson('shop.yen'); ?>/1 <?php echo app('translator')->getFromJson('shop.day'); ?>( <?php echo app('translator')->getFromJson('shop.taxcharge'); ?>)
(43,800 <?php echo app('translator')->getFromJson('shop.yen'); ?> <?php echo app('translator')->getFromJson('shop.1n2d'); ?>) <br/><br/> <?php echo app('translator')->getFromJson('shop.rantal'); ?> <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> <?php echo app('translator')->getFromJson('shop.inquire'); ?>
													</td>
												</tr>
											</table>
											<div class="clearfix bg-white calc-btn">
												<a class=" bg-grad-red" href="<?php echo e(URL::to('/')); ?>/contact"> <?php echo app('translator')->getFromJson('shop.inquires'); ?> </a>
											</div>
										</div>
									</div>
									<!-- ROW -->
								</div>

								<div class="box-shadow relative">
									<div class="ribbon-block bg-grad-red">
										<h3> <?php echo app('translator')->getFromJson('shop.lexus460'); ?> </h3> 
									</div>
									<!-- ROW -->
									<div class="row margin-bottom-40 carclass-margin">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<img src="<?php echo e(URL::to('/')); ?>/img/ssp2.jpg" class="img-responsive center-block m_Txs60">
											<p class="sml-txt"> <?php echo app('translator')->getFromJson('shop.imgdiagram'); ?> </p>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<h3 class="jnum"> <?php echo app('translator')->getFromJson('shop.passenger'); ?>： <span>5</span> <?php echo app('translator')->getFromJson('shop.name'); ?></h3>

											<table class="table carclass-tbl">
												<tr>
													<th width="30%"><span class="bg-grad-gray"> <?php echo app('translator')->getFromJson('shop.model'); ?> </span></th>
													<td>
														<?php echo app('translator')->getFromJson('shop.lexus460'); ?>
													</td>
												</tr>
												<tr>
													<th><span class="bg-grad-gray"> <?php echo app('translator')->getFromJson('shop.option'); ?> </span></th>
													<td>

													</td>
												</tr>
												<tr>
													<th>
														<p class="bg-grad-gray" style="margin: 0; border:1px solid #ccc;padding: 2px 5px;">
															<?php echo app('translator')->getFromJson('shop.price'); ?>
														</p>
													</th>
													<td>
														<?php echo app('translator')->getFromJson('shop.lexus460desc'); ?> <br/> <?php echo app('translator')->getFromJson('shop.rental'); ?> <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> <?php echo app('translator')->getFromJson('shop.inquire'); ?>
													</td>
												</tr>
											</table>
											<div class="clearfix bg-white calc-btn">
												<a class=" bg-grad-red" href="<?php echo e(URL::to('/')); ?>/contact"> <?php echo app('translator')->getFromJson('shop.inquires'); ?> </a>
											</div>
										</div>
									</div>
									<!-- ROW -->
								</div>

								<div class="box-shadow relative">
									<div class="ribbon-block bg-grad-red">
										<h3> <?php echo app('translator')->getFromJson('shop.lexus460_'); ?> </h3>
									</div>
									<!-- ROW -->
									<div class="row margin-bottom-40 carclass-margin">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<img src="<?php echo e(URL::to('/')); ?>/img/ssp3.jpg" class="img-responsive center-block m_Txs60">
											<p class="sml-txt"> <?php echo app('translator')->getFromJson('shop.imgdiagram'); ?> </p>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<h3 class="jnum"> <?php echo app('translator')->getFromJson('shop.passenger'); ?>： <span>5</span> <?php echo app('translator')->getFromJson('shop.name'); ?> </h3>

											<table class="table carclass-tbl">
												<tr>
													<th width="30%"><span class="bg-grad-gray"> <?php echo app('translator')->getFromJson('shop.model'); ?> </span></th>
													<td>
														<?php echo app('translator')->getFromJson('shop.lexus460_'); ?>
													</td>
												</tr>
												<tr>
													<th><span class="bg-grad-gray"> <?php echo app('translator')->getFromJson('shop.option'); ?></span></th>
													<td>

													</td>
												</tr>
												<tr>
													<th>
														<p class="bg-grad-gray" style="margin: 0; border:1px solid #ccc;padding: 2px 5px;">
															<?php echo app('translator')->getFromJson('shop.price'); ?>
														</p>
													</th>
													<td>
														<?php echo app('translator')->getFromJson('shop.lexus460desc'); ?> <br/> <?php echo app('translator')->getFromJson('shop.rental'); ?> <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> <?php echo app('translator')->getFromJson('shop.inquire'); ?>
													</td>
												</tr>
											</table>
											<div class="clearfix bg-white calc-btn">
												<a class=" bg-grad-red" href="<?php echo e(URL::to('/')); ?>/contact"> <?php echo app('translator')->getFromJson('shop.inquires'); ?></a>
											</div>
										</div>
									</div>
									<!-- ROW -->
								</div>
								<?php endif; ?>
						</div>
						<?php endif; ?>
                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 hidden-lg hidden-md hidden-sm hidden-xs">
                            <div class="portlet portlet-fit light cont-box">
                                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10">
                                    <a href="#"><img class="center-block img-responsive" src="" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden-xs">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link"> <?php echo app('translator')->getFromJson('shop.toppage'); ?> </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
    </div>

    <?php echo $__env->make('modals.modal-membership', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('modals.modal-error', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
  <style>
      .box-shadow {
          -webkit-box-shadow: 2px 1px 3px 1px rgba(0,0,0,0.1);
          -moz-box-shadow: 2px 1px 3px 1px rgba(0,0,0,0.1);
          box-shadow: 2px 1px 3px 1px rgba(0,0,0,0.1);
          padding: 10px;
          margin-bottom: 30px;
      }
	  .ribbon-block {
		  position: absolute;
		  top: 10px;
		  left: -10px;
		  width: 50%;
		  height: auto;
		  color: #fff;
		  padding: 8px;
	  }
  </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
	<script>
		function scrollToSection(target) {
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 1000);
		}

		function gotoDetail(class_name, class_id, class_price, position){
			<?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false): ?>

            dataLayer.push({
                'event': 'productClick',
                'ecommerce': {
                    'click': {
                        'actionField': {'list': 'Shop <?php echo e($shop->region_code); ?>'},
                        'products': [{
                            'name': class_name,                      // Dynamic value
                            'id': class_id,					// Dynamic value
                            'price': class_price,
                            // 'brand': productObj.brand,
                            'position': position
                        }]
                    }
                }
            });
			<?php endif; ?>

            location.href = "<?php echo e(URL::to('/')); ?>/carclass-detail/" + class_id;
        }

	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>