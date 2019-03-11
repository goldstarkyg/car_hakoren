<?php $__env->startSection('template_title'); ?>
車種・料金
<?php $__env->stopSection(); ?>
<?php $__env->startSection('template_linked_css'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/page_carlist.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>

<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('content'); ?>
    <?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false): ?>
    <script>
        dataLayer.push({
            'ecommerce': {
                'currencyCode': 'JPY',
                'impressions': [
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {
                        'name': '<?php echo e($cls->abbriviation); ?>',
                        'id': '<?php echo e($cls->id); ?>',
                        'price': '<?php echo e($cls->price1_0); ?>',

                        'list': 'Carclass <?php echo e($shop->region_code); ?>',
                        'position': '<?php echo e($key + 1); ?>'
                    },
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            }
        });
    </script>
    <?php endif; ?>

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
                            <a href="#">親ページ</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span> <?php echo app('translator')->getFromJson('classlist.classlist'); ?> </span>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE TITLE -->
            </div>
        </div>
        <!-- END PAGE HEAD-->

        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT HEADER -->
            <div class="dynamic-page-header dynamic-page-header-default">
                <div class="container clearfix">
                    <div class="col-md-12 bottom-border ">
                        <div class="page-header-title">
                            <h1><?php if(!empty($class->csname)): ?> <?php echo e($class->csname); ?> <?php endif; ?> <?php echo app('translator')->getFromJson('classlist.carfee'); ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BEGIN PAGE CONTENT BODY -->

            <div class="page-content">
                <div class="container">

                    <!-- ROW -->
                    <div class="row">
                        <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php
                                $car_class_names = \DB::table('car_shop')->select('id')->where('slug',Request::segment(2))->first();
                                if(!empty($car_class_names))
                                {
                                    $shop_id = $car_class_names->id;
                                }
                                else
                                {
                                    $shop_id = "";
                                }

                            ?>
                            <!-- CarClass 1 -->

                            <?php if(!empty($shop_id) || $shop_id != 0): ?>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($shop_id == $class->cs_id): ?>
                                            <div class="box-shadow relative">
                                                <div class="ribbon-block bg-grad-red">
                                                    <h3><?php echo e($class->name); ?>　</h3><!--<?php if($class->smoke == '0'): ?>禁煙車 <?php endif; ?>-->
                                                </div>
                                                <!-- ROW -->
                                                <div class="row margin-bottom-40 carclass-margin">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <?php
                                                        $class_thumb = (count($class->models) > 0)? $class->models[0]->thumb_path : 'images/blank.jpg';
                                                        ?>
                                                        <img src="<?php echo e(URL::to('/')); ?>/<?php echo e($class_thumb); ?>" class="img-responsive center-block">
                                                        <p class="sml-txt"> <?php echo app('translator')->getFromJson('classlist.modelimage'); ?> </p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <?php
                                                        $min_psg = $class->passenger->min_passenger;
                                                        $max_psg = $class->passenger->max_passenger;
                                                        $psg_num = ($min_psg != $max_psg)? $min_psg.'～'.$max_psg : $min_psg;
                                                        if($class->name == 'CW3H' || $class->name == 'W3' )
                                                            $psg_num = $min_psg;
                                                        ?>
                                                        <h3 class="jnum"> <?php echo app('translator')->getFromJson('classlist.passenger'); ?> ：
                                                            <span> <?php echo e($psg_num); ?></span> <?php echo app('translator')->getFromJson('classlist.name'); ?>
                                                        </h3>

                                                        <table class="table carclass-tbl">
                                                            <tr>
                                                                <th width="30%">
                                                                    <div class="titlebox">
                                                                     <?php echo app('translator')->getFromJson('classlist.modelname'); ?>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <?php
                                                                        $m = 0;
                                                                        $name = $util->Tr('name');
                                                                    ?>
                                                                    <?php $__currentLoopData = $class->models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php echo e($model->$name); ?> <?php if(count($class->models) > $m+1): ?>、<?php endif; ?>
                                                                       <?php $m++; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>
                                                                    <div class="titlebox">
                                                                        <?php echo app('translator')->getFromJson('classlist.option'); ?>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <?php $op = 0; ?>
                                                                    <?php $__currentLoopData = $class->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php echo e($option->$name); ?> <?php if(count($class->options) > $op+1): ?>、<?php endif; ?>
                                                                       <?php $op++; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>
                                                                    <div class="titlebox">
                                                                        <?php echo app('translator')->getFromJson('classlist.onedayprice'); ?> <br/><span style="font-size:12px;">（<?php echo app('translator')->getFromJson('classlist.1n2dtime'); ?>）</span>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <?php echo e(number_format($class->price1_0)); ?> <?php echo app('translator')->getFromJson('classlist.yen'); ?>/1 <?php echo app('translator')->getFromJson('classlist.day'); ?>(<?php echo app('translator')->getFromJson('classlist.tax'); ?><br>
                                                                    (<?php echo e(number_format($class->price2_1)); ?><?php echo app('translator')->getFromJson('classlist.yen'); ?> <?php echo app('translator')->getFromJson('classlist.1n2d'); ?>)
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <div class="clearfix bg-white calc-btn">
                                                            <a class="bg-grad-red" onclick="gotoDetail('<?php echo e($class->name); ?>', <?php echo e($class->id); ?>, <?php echo e($class->price1_0); ?>,<?php echo e($key + 1); ?>)"> <?php echo app('translator')->getFromJson('classlist.viewdetail'); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ROW -->
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   <?php if($shop_id == 4): ?>
									<div class="box-shadow relative">
										<div class="ribbon-block bg-grad-red">
											<h3>MB</h3> 
										</div>
										<!-- ROW -->
										<div class="row margin-bottom-40 carclass-margin">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<img src="<?php echo e(URL::to('/')); ?>/img/mb.png" class="img-responsive center-block m_Txs60">
												<p class="sml-txt"> <?php echo app('translator')->getFromJson('classlist.modelimage'); ?></p>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<h3 class="jnum"> <?php echo app('translator')->getFromJson('classlist.passenger'); ?>： <span>26～29</span> <?php echo app('translator')->getFromJson('classlist.name'); ?> </h3>

												<table class="table carclass-tbl">
													<tr>
														<th width="30%"><div class="titlebox"> <?php echo app('translator')->getFromJson('classlist.modelname'); ?></div></th>
														<td>
															<?php echo app('translator')->getFromJson('classlist.coaster'); ?>
														</td>
													</tr>
													<tr>
														<th><div class="titlebox"> <?php echo app('translator')->getFromJson('classlist.option'); ?></div></th>
														<td>
															<?php echo app('translator')->getFromJson('classlist.optionlist'); ?>
														</td>
													</tr>
													<tr>
														<th>
															<div class="titlebox">
																<?php echo app('translator')->getFromJson('classlist.price'); ?>
															</div>
														</th>
														<td>
															21,900 <?php echo app('translator')->getFromJson('classlist.yen'); ?>/1 <?php echo app('translator')->getFromJson('classlist.day'); ?>(<?php echo app('translator')->getFromJson('classlist.tax'); ?>
	(43,800 <?php echo app('translator')->getFromJson('classlist.yen'); ?> <?php echo app('translator')->getFromJson('classlist.1n2d'); ?><br/><br/> <?php echo app('translator')->getFromJson('classlist.rental'); ?> <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> <?php echo app('translator')->getFromJson('classlist.inquire'); ?>
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn">
													<a class=" bg-grad-red" href="<?php echo e(URL::to('/')); ?>/contact"> <?php echo app('translator')->getFromJson('classlist.inqu'); ?> </a>
												</div>
											</div>
										</div>
										<!-- ROW -->
									</div>

									<div class="box-shadow relative">
										<div class="ribbon-block bg-grad-red">
											<h3> <?php echo app('translator')->getFromJson('classlist.carlexus'); ?></h3><!--<?php if($class->smoke == '0'): ?> 禁煙車 <?php endif; ?>-->
										</div>
										<!-- ROW -->
										<div class="row margin-bottom-40 carclass-margin">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<img src="<?php echo e(URL::to('/')); ?>/img/ssp2.jpg" class="img-responsive center-block m_Txs60">
												<p class="sml-txt"> <?php echo app('translator')->getFromJson('classlist.modelimage'); ?> </p>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<h3 class="jnum"> <?php echo app('translator')->getFromJson('classlist.passenger'); ?>： <span>5</span> <?php echo app('translator')->getFromJson('classlist.name'); ?> </h3>

												<table class="table carclass-tbl">
													<tr>
														<th width="30%"><div class="titlebox"> <?php echo app('translator')->getFromJson('classlist.modelname'); ?> </div></th>
														<td>
															<?php echo app('translator')->getFromJson('classlist.carlexus'); ?>
														</td>
													</tr>
													<tr>
														<th>
                                                                    <div class="titlebox"> <?php echo app('translator')->getFromJson('classlist.option'); ?></div></th>
														<td>

														</td>
													</tr>
													<tr>
														<th>
															<div class="titlebox">
																<?php echo app('translator')->getFromJson('classlist.price'); ?>
															</div>
														</th>
														<td>
															<?php echo app('translator')->getFromJson('classlist.lexusdesc1'); ?> <br/> <?php echo app('translator')->getFromJson('classlist.rental'); ?> <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> <?php echo app('translator')->getFromJson('classlist.inquire'); ?>
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn">
													<a class=" bg-grad-red" href="<?php echo e(URL::to('/')); ?>/contact"> <?php echo app('translator')->getFromJson('classlist.inqu'); ?></a>
												</div>
											</div>
										</div>
										<!-- ROW -->
									</div>

									<div class="box-shadow relative">
										<div class="ribbon-block bg-grad-red">
											<h3> <?php echo app('translator')->getFromJson('classlist.carlexus1'); ?> </h3><!--<?php if($class->smoke == '0'): ?> 禁煙車 <?php endif; ?>-->
										</div>
										<!-- ROW -->
										<div class="row margin-bottom-40 carclass-margin">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<img src="<?php echo e(URL::to('/')); ?>/img/ssp3.jpg" class="img-responsive center-block m_Txs60">
												<p class="sml-txt"> <?php echo app('translator')->getFromJson('classlist.modelimage'); ?></p>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<h3 class="jnum"> <?php echo app('translator')->getFromJson('classlist.passenger'); ?>： <span>5</span> <?php echo app('translator')->getFromJson('classlist.name'); ?></h3>

												<table class="table carclass-tbl">
													<tr>
														<th width="30%"><div class="titlebox"> <?php echo app('translator')->getFromJson('classlist.modelname'); ?></div></th>
														<td>
															<?php echo app('translator')->getFromJson('classlist.carlexus1'); ?>
														</td>
													</tr>
													<tr>
														<th>
                                                                    <div class="titlebox"> <?php echo app('translator')->getFromJson('classlist.option'); ?> </div></th>
														<td>

														</td>
													</tr>
													<tr>
														<th>
															<div class="titlebox">
																<?php echo app('translator')->getFromJson('classlist.price'); ?>
															</div>
														</th>
														<td>
															<?php echo app('translator')->getFromJson('classlist.lexusdesc1'); ?> <br/> <?php echo app('translator')->getFromJson('classlist.rental'); ?> <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> <?php echo app('translator')->getFromJson('classlist.inquire'); ?>
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn">
													<a class=" bg-grad-red" href="<?php echo e(URL::to('/')); ?>/contact"> <?php echo app('translator')->getFromJson('classlist.inqu'); ?>    </a>
												</div>
											</div>
										</div>
										<!-- ROW -->
									</div>
								   <?php endif; ?>
                            <!-- CarClass 1-->

                        </div>
                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 hidden-lg hidden-md hidden-sm hidden-xs">
                            <div class="portlet portlet-fit light cont-box">
                                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10">
                                    <a href="#"><img class="center-block img-responsive" src="" alt=""></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- ROW -->
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link"> <?php echo app('translator')->getFromJson('classlist.toppage'); ?> </a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="box-shadow relative text-center">
                    <?php echo app('translator')->getFromJson('classlist.notfound'); ?>
                </div>
                <?php endif; ?>
            </div>
            <!-- END PAGE CONTENT BODY -->

            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->

    </div>
    <!-- END CONTAINER -->
 <?php $__env->stopSection(); ?>

 <?php $__env->startSection('footer_scripts'); ?>
<script>

    function gotoDetail(class_name, class_id, class_price, position){
        <?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false): ?>
            dataLayer.push({
                'event': 'productClick',
                'ecommerce': {
                    'click': {
                        'actionField': {'list': 'Carclass <?php echo e($shop->region_code); ?>'},
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