<?php $__env->startSection('template_linked_css'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/about.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::to('/')); ?>/css/page_search_thankyou.css" rel="stylesheet" id="style_components" type="text/css" />
    <style>
    </style>
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('content'); ?>

    <?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false): ?>
    <script>
        dataLayer.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField': {'step': 2, 'option': '<?php echo e($booking_options); ?>' }, // Please change step number dynamically according to the checkout step.
                    'products': [{
                        'name'  : '<?php echo e($booking_class_name); ?>',
                        'id'    : '<?php echo e($booking_class_id); ?>',
                        'price' : '<?php echo e($booking_price); ?>',
                        // 'brand': 'エスティマ',
                        'quantity': 1
                    }]
                }
            }
        });

        dataLayer.push({
            'event': 'Thank you',
            'Ordervalue': '<?php echo e($booking_price); ?>'
        });
    </script>

    <span id="a8sales"></span>

    <script src="//statics.a8.net/a8sales/a8sales.js"></script>

    <script>
        a8sales({
            "pid": "s00000018752001",
            "order_number": "<?php echo e($order_number); ?>",
            "currency": "JPY",
            "items": [
                {
                    "code": "a8",
                    "price": 1,
                    "quantity": 1
                },
            ],
            "total_price": '<?php echo e($booking_price); ?>',
        });
    </script>
    <?php endif; ?>
    <div class="container" style=" padding: 0;margin-bottom: 140px !important;">
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
                            <span> <?php echo app('translator')->getFromJson('thankyou.confirm'); ?></span>
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
                            <h1> <?php echo app('translator')->getFromJson('thankyou.confirm'); ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">

                    <div class="row">
                        <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <!-- result -->
                            <div class="reserve-block box-shadow">
                                <div class="reserve-block-cont">
                                    <div class="row m_T20">
                                        <div class="col-sm-12">
                                            <div class="reservation-main">
                                            	<form method="post" id="search-thankyou" action="<?php echo url('/savebag_choose'); ?>">
                                                 <?php echo e(csrf_field()); ?>

                                                <input type="hidden" name="bag_choosed" id="bag_choosed" value="0" />
                                                <div class="reservation-block">
                                                    <h2> <?php echo app('translator')->getFromJson('thankyou.thank'); ?></h2>
                                                    <h2> <?php echo app('translator')->getFromJson('thankyou.confirmcar'); ?> </h2>
                                                </div>
                                                <div class="store-block">
                                                    <?php echo app('translator')->getFromJson('thankyou.short'); ?>
                                                </div>
                                                <div class="quick-block">
                                                    <?php echo app('translator')->getFromJson('thankyou.nostart'); ?>
                                                </div>
                                                <div class="txt-block">
												<p> <?php echo app('translator')->getFromJson('thankyou.gift1'); ?></p>
                                                </div>
                                                <?php
                                                    $license = $util->Tr('license');
                                                    $bag1    = $util->Tr('bag1');
                                                    $bag2    = $util->Tr('bag2');
                                                    $bag3    = $util->Tr('bag3');
                                                ?>
                                                <div class="driver-license-block row">
													<div class="col-sm-2 col-sm-offset-2">
														<img src="img/<?php echo e($license); ?>.jpg" alt="">
													</div>
													<div class="col-sm-8">
														<ol class="license-list">
															<li> <?php echo app('translator')->getFromJson('thankyou.driverlicense'); ?></li>
															<li> <?php echo app('translator')->getFromJson('thankyou.contract'); ?></li>
															<li> <?php echo app('translator')->getFromJson('thankyou.creditcard'); ?></li>
														</ol>
													</div>
                                                </div>
                                                <div class="favorite-block clearfix">
                                                    <img class="pull-right" src="img/car.jpg" alt="">
                                                </div>
                                                <div class="choose-block">

                                                    <div class="quick-button">
                                                     <a href="<?php echo url('quickstart-01'); ?>"><?php echo app('translator')->getFromJson('thankyou.extension'); ?></a>
                                                    </div>
                                                </div>
                                                <div class="procedure-block m_T50">
													<div class="choose-content">
                                                        <div class="choose-common">
                                                            <label style="width:100%;">
                                                            <input type="radio" name="bag_choose" value="1" class="bag_choose hidden" bid="1">
                                                            <div class="choose-inner-block" bid="1">
                                                                <div class="small-block" style="display: none">
                                                                    <h2>選択中</h2>
                                                                </div>
                                                                <div class="choose-img-block">
                                                                    <img src="img/<?php echo e($bag1); ?>.jpg" alt="">
                                                                </div>
                                                                <div class="choose-text-block">
                                                                    <p><?php echo app('translator')->getFromJson('thankyou.refresh'); ?> <br> <?php echo app('translator')->getFromJson('thankyou.anytime'); ?></p>
                                                                </div>
                                                            </div>
                                                            </label>
                                                        </div>
                                                        <div class="choose-common">
                                                            <label>
                                                            <input type="radio" name="bag_choose" value="2" class="bag_choose hidden" bid="2">
                                                            <div class="choose-inner-block" bid="2">
                                                                <div class="small-block" style="display: none">
                                                                    <h2>選択中</h2>
                                                                </div>
                                                                <div class="choose-img-block">
                                                                    <img src="img/<?php echo e($bag2); ?>.jpg" alt="">
                                                                </div>
                                                                <div class="choose-text-block">
                                                                    <p> <?php echo app('translator')->getFromJson('thankyou.children'); ?><br> <?php echo app('translator')->getFromJson('thankyou.driving'); ?></p>
                                                                </div>
                                                            </div>
                                                            </label>
                                                        </div>
                                                        <div class="choose-common">
                                                            <label>
                                                            <input type="radio" name="bag_choose" value="3" class="bag_choose hidden" bid="3">
                                                            <div class="choose-inner-block" bid="3">
                                                                <div class="small-block" style="display: none">
                                                                    <h2>選択中</h2>
                                                                </div>
                                                                <div class="choose-img-block">
                                                                    <img src="img/<?php echo e($bag3); ?>.jpg" alt="">
                                                                </div>
                                                                <div class="choose-text-block">
                                                                    <p><?php echo app('translator')->getFromJson('thankyou.gift2'); ?></p>
                                                                </div>
                                                            </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                </form>
												<p class="ast" style="font-size:11px;"> <?php echo app('translator')->getFromJson('thankyou.confirmation'); ?>
												<br> <?php echo app('translator')->getFromJson('thankyou.customer'); ?>
												</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- result -->
                        </div>

                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 hidden-lg hidden-md hidden-sm hidden-xs">
                            <div class="portlet portlet-fit light cont-box">
                                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10">
                                    <a href="#"><img class="center-block img-responsive" src="#" alt=""></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link"> <?php echo app('translator')->getFromJson('thankyou.toppage'); ?></a>
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

  </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <script>
        /*$small_block = $('.small-block');
        $('.bag_choose').click( function () {
            var bag_id = $(this).attr('bid');
            $('.choose-inner-block').removeClass('active');
            $('.choose-inner-block div.small-block').fadeOut();

            $('.choose-inner-block[bid="' + bag_id + '"]').addClass('active');
            $('.choose-inner-block.active div.small-block').fadeIn();
            $('#bag_choosed').val(bag_id);
			console.log(bag_id);
        })*/
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>