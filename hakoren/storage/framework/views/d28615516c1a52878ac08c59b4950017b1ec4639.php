<?php $__env->startSection('template_title'); ?>
<?php echo app('translator')->getFromJson('qs3.title'); ?>
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('template_linked_css'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/page_quickstart_form.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
    <link rel="stylesheet" href="<?php echo e(URL::asset('css/sqpaymentform.css')); ?>" type="text/css">
	<script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script>
    <script>
	// Set the application ID
	var applicationId = "<?php echo env('SQUARE_APPLICATION_ID'); ?>";
	// "sandbox-sq0idp-alUWCy0k-6Krcvh-eW4_Yw";

	// Set the location ID
	var locationId = "<?php echo env('SQUARE_LOCATION_ID'); ?>";
	//"CBASEOnXb3pp-u53CJxVFHVpQrQgAQ";
	</script>
    <script type="text/javascript" src="<?php echo e(URL::asset('js/sqpaymentform.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <script>
        <?php
        $options = [];
        if(!empty($paid_options)) {
            foreach ($paid_options as $op) {
                $options[] = $op->name;
            }
        }
        ?>
        <?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false): ?>

        dataLayer.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField': {'step': 5, 'option': '<?php echo e(implode(',', $options)); ?>' }, // Please note that on the site are 4 steps.
                    'products': [{
                        'name'  : '<?php echo e($carClassInfo->name); ?>',
                        'id'    : '<?php echo e($carClassInfo->id); ?>',
                        'price' : '<?php echo e($bookingInfo->payment); ?>',
                        // 'brand': 'エスティマ',
                        'quantity': 1
                    }]
                }
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
                        <li>
                            <span><?php echo app('translator')->getFromJson('qs3.cartitle'); ?></span>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE TITLE -->
            </div>
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper quick_main_wrapper">
            <!-- BEGIN CONTENT BODY -->
          
            <!-- BEGIN CONTENT HEADER -->
            <div class="dynamic-page-header dynamic-page-header-default">
                <div class="container clearfix">
                    <div class="col-md-12 bottom-border ">
                        <div class="page-header-title">
								<h1><?php echo app('translator')->getFromJson('qs3.cartitle'); ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">

						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
                            <div class="quick_user_three">
							     <h3><?php echo app('translator')->getFromJson('qs3.reservation'); ?> <?php echo app('translator')->getFromJson('qs3.mr_en'); ?> <?php echo e($userInfo->last_name); ?> <?php echo e($userInfo->first_name); ?> <?php echo app('translator')->getFromJson('qs3.mr'); ?></h3>
                            </div>

							<!-- quick start 3 -->
							<div class="box-shadow relative red-border-top">


                                <div class="formcard-block-cont">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formcard-right">
											<ol class="stepBar step3">
											  <li class="step">STEP1</li>
											  <li class="step">STEP2</li>
											  <li class="step current">STEP3</li>
											</ol>
											<h3 class="text-center relative">③<?php echo app('translator')->getFromJson('qs3.payment'); ?><span class="pay-local"><?php echo app('translator')->getFromJson('qs3.paying'); ?><a href="<?php echo url('/pay-locally'); ?>"><?php echo app('translator')->getFromJson('qs3.here'); ?></a></span></h3>
										</div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 formcard-right">
											<div class="panel panel-default">
												<div class="bg-grad-gray panel-heading">
														<a id="credit-sec"></a>
														<h4><?php echo app('translator')->getFromJson('qs3.creditcard'); ?></h4>
												</div>
											</div>

                                        <form id="nonce-form" novalidate action="<?php echo url('/savequickstart-03'); ?>" method="post">
                                          <?php echo csrf_field(); ?>

                                          <div class="formcard-wrapper clearfix">

                                            <div class="alert alert-danger" id="error_block" style="display:<?php if($errors->any()): ?> block <?php else: ?> none <?php endif; ?>;">
                                              <ul>
                                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo $error; ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                              </ul>
                                            </div>

                                            <div class="col-xs-12 only-nonmember　" style="padding: 0; margin-bottom:0px;">
                                              <label class="col-lg-5 col-md-5 col-sm-5 col-xs-12" style="padding: 0px 0 3px; margin-bottom:3px;"><?php echo app('translator')->getFromJson('qs3.cardnumber'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs3.required'); ?></span></label>
                                              <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12" style="">
                                                <div id="sq-card-number"></div>
                                                <span class="error-class errorcard_num"></span>
                                                <ul class="asterisk">
                                                  <li><?php echo app('translator')->getFromJson('qs3.nothyphen'); ?></li>
                                                </ul>
                                              </div>
                                            </div>
                                            <div class="col-xs-12 only-nonmember" style="padding: 0; margin-bottom:10px;">
                                              <label class="col-lg-5 col-md-5 col-sm-5 col-xs-12" style="padding: 4px 0 3px; margin-bottom:3px;"><?php echo app('translator')->getFromJson('qs3.cardexpire'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs3.required'); ?></span></label>
                                              <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                <div id="sq-expiration-date"></div>
                                                <ul class="asterisk">
                                                  <li><?php echo app('translator')->getFromJson('qs3.example'); ?> [01/19]</li>
                                                </ul>
                                                <span class="error-class errorcard_expired_m"></span> <span class="error-class errorcard_expired_y"></span> </div>
                                            </div>
                                            <div class="col-xs-12 only-nonmember" style="padding: 0; margin-bottom:10px;">
                                              <label class="col-lg-5 col-md-5 col-sm-5 col-xs-12" style="padding: 4px 0 3px; margin-bottom:3px;"><?php echo app('translator')->getFromJson('qs3.securitycode'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs3.required'); ?></span></label>
                                              <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                <div id="sq-cvv"></div>
                                                <span class="error-class errorsecure_num"></span> </div>
                                            </div>

                                          </div>
                                          <!-- wrapper -->

                                          <div align="center">
                                            <input type="button" name="button" id="sq-creditcard" class="submitBtn form-control h40" value="<?php echo app('translator')->getFromJson('qs3.completepayment'); ?>" onClick="requestCardNonce(event)">
                                            <!--<button id="sq-creditcard" class="button-credit-card" onClick="requestCardNonce(event)">お支払いを完了する </button>-->                              </div>

                                          <input type="hidden" id="card-nonce" name="nonce">
                                        </form>


                                        </div>

                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 formcard-left">
                                            <span class="pay-step-three">
                                                <?php echo app('translator')->getFromJson('qs3.payinglocal'); ?>
                                                <a href="<?php echo url('/pay-locally'); ?>"><?php echo app('translator')->getFromJson('qs3.here'); ?></a>
                                            </span>
											<div class="panel panel-default" style="margin-bottom: 5px;">
                                                <div class="panel-heading bg-grad-gray">
                                                    <?php echo app('translator')->getFromJson('qs3.paymentdetail'); ?>
                                                </div>
                                                <div class="panel-body" style="padding-bottom: 0px;">
                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left"><?php echo app('translator')->getFromJson('qs3.customername'); ?></label>
														<label class="pull-right"><?php echo app('translator')->getFromJson('qs3.mr_en'); ?> <?php echo $userInfo->last_name.' '.$userInfo->first_name; ?> <?php echo app('translator')->getFromJson('qs3.mr'); ?></label>
                                                    </div>
                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left"><?php echo app('translator')->getFromJson('qs3.carclass'); ?></label>
														<label class="pull-right">
                                                            <?php if(!empty($carClassInfo)): ?>
                                                                <?php echo $carClassInfo->name; ?>

                                                            <?php endif; ?>
                                                            <?php if(!empty($modelInfo)): ?>
                                                                <?php echo e($modelInfo->passengers); ?><?php echo app('translator')->getFromJson('qs3.riding'); ?>
                                                            <?php endif; ?>
                                                        </label>
                                                    </div>
                                                    <?php
                                                        $name = $util->Tr('name');
                                                    ?>
                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left"><?php echo app('translator')->getFromJson('qs3.model'); ?></label>
														<label class="pull-right">
                                                            <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php echo e($model->$name); ?> <?php if(!$loop->last): ?>&nbsp;/<?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </label>
                                                    </div>
                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left"><?php echo app('translator')->getFromJson('qs3.departure'); ?></label>
                                                        <?php if($util->lang() == 'ja'): ?>
														<label class="pull-right"><?php echo date('Y', strtotime($bookingInfo->departing)).'年'.date('m', strtotime($bookingInfo->departing)).'月'.date('d',strtotime($bookingInfo->departing)).'日'.date('G:i',strtotime($bookingInfo->departing)); ?> <?php echo $departShop->name; ?></label>
                                                        <?php endif; ?>
                                                        <?php if($util->lang() == 'en'): ?>
                                                            <label class="pull-right"><?php echo date('Y', strtotime($bookingInfo->departing)).'/'.date('m', strtotime($bookingInfo->departing)).'/'.date('d',strtotime($bookingInfo->departing)).'/'.date('G:i',strtotime($bookingInfo->departing)); ?> <?php echo $departShop->$name; ?></label>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left"><?php echo app('translator')->getFromJson('qs3.return'); ?></label>
                                                        <?php if($util->lang() == 'ja'): ?>
														<label class="pull-right"><?php echo date('Y', strtotime($bookingInfo->returning)).'年'.date('m', strtotime($bookingInfo->returning)).'月'.date('d',strtotime($bookingInfo->returning)).'日'.date('G:i',strtotime($bookingInfo->returning)); ?> <?php echo $returnShop->name; ?></label>
                                                        <?php endif; ?>
                                                        <?php if($util->lang() == 'en'): ?>
                                                            <label class="pull-right"><?php echo date('Y', strtotime($bookingInfo->returning)).'/'.date('m', strtotime($bookingInfo->returning)).'/'.date('d',strtotime($bookingInfo->returning)).'/'.date('G:i',strtotime($bookingInfo->returning)); ?> <?php echo $returnShop->$name; ?></label>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="form-group row-bordered-result row">
                                                        <label class="pull-left span_nightday" class_id="" >
                                                            <?php echo app('translator')->getFromJson('qs3.basiccharge'); ?>（<?php echo $util->changeDate($rent_days); ?>）
                                                        </label>
                                                        <label class="pull-right basic_price" class_id="" >

                                                            <?php echo app('translator')->getFromJson('qs3.yen_en'); ?><?php echo number_format($bookingInfo->basic_price); ?><?php echo app('translator')->getFromJson('qs3.yen'); ?>
                                                        </label>
                                                        <input type="hidden" class="rent_days" class_id="" value="">
                                                        <input type="hidden" class="price_rent" class_id="" value="">
                                                    </div>

													<div class="form-group row-bordered-result row">
                                                        <div class="col-xs-6" style="padding: 0">
                                                            <?php echo app('translator')->getFromJson('qs3.exemption'); ?>
                                                        </div>
                                                        <div class="col-xs-6" style="padding-right: 0;">
                                                            <label class="pull-right">
                                                                <?php echo app('translator')->getFromJson('qs3.yen_en'); ?><span class="insurance-price" class_id=""><?php echo number_format($bookingInfo->insurance1 + $bookingInfo->insurance2); ?></span><?php echo app('translator')->getFromJson('qs3.yen'); ?>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <?php if($bookingInfo->paid_options): ?>

                                                    <?php 
                                                        $options_price   = explode(',', $bookingInfo->paid_options_price);
                                                        $option_numbers  = explode(',', $bookingInfo->paid_option_numbers);
                                                        $loopindex		 = 0;
                                                     ?>

                                                    <?php $__currentLoopData = $paid_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="option-wrapper" class_id="">
														<div class="form-group row-bordered-result row">
															<label class="pull-left">
																<?php echo $option->$name; ?>

															</label>
															<label class="pull-right">
                                                                <?php echo app('translator')->getFromJson('qs3.yen_en'); ?><?php echo number_format($options_price[$loopindex] * $option_numbers[$loopindex]); ?><?php echo app('translator')->getFromJson('qs3.yen'); ?>
															</label>
														</div>
                                                    </div>
                                                    <?php  $loopindex++;  ?>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

													<?php endif; ?>

                                                    <div class="form-group row-bordered-result row">
                                                        <div class="col-xs-6" style="padding: 0">
                                                            <?php echo app('translator')->getFromJson('qs3.non_smoking'); ?>
                                                        </div>
                                                        <div class="col-xs-6" style="padding-right: 0;">
                                                            <label class="pull-right">
                                                                <span class="insurance-price" class_id=""><?php echo $smokeInfo; ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">

                                                        <div class="col-xs-6" style="padding: 0">

                                                            <div><?php echo app('translator')->getFromJson('qs3.total'); ?></div>
                                                        </div>
                                                        <div class="col-xs-6" style="padding-right: 0;">
                                                            <label style="font-weight:bold; font-size:2em;" class="pull-right total_price font-red"  class_id="">
																<?php echo app('translator')->getFromJson('qs3.yen_en'); ?><?php echo number_format($bookingInfo->payment); ?><span style="font-weight: 300"><?php echo app('translator')->getFromJson('qs3.yen'); ?></span>
															</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

											<div class="panel panel-default quick_over_tre" style="margin-bottom: 5px;">
                                                <div class="panel-heading bg-grad-gray">
                                                    <?php echo app('translator')->getFromJson('qs3.about'); ?>
                                                </div>
                                                <div class="panel-body" style="padding-bottom: 0px;">
                                                    <p><?php echo app('translator')->getFromJson('qs3.content'); ?></p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
							</div>
							<!-- quick start 3 -->


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
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link"><?php echo app('translator')->getFromJson('qs3.toppage'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
  <style>

  </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>

<script type="text/javascript">
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>