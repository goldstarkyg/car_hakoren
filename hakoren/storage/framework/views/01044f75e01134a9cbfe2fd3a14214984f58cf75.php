<?php $__env->startSection('template_linked_css'); ?>
<link href="<?php echo e(URL::to('/')); ?>/css/page_quickstart_form.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('content'); ?>
    <script>
        // data layer
        <?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false): ?>
        <?php if(isset($transaction_id)): ?>
        dataLayer.push({
            'ecommerce': {
                'purchase': {
                    'actionField': {
                        'id': '<?php echo e($transaction_id); ?>',                         // Transaction ID. Required for purchases and refunds. Must be unique
                        'revenue': '<?php echo e($amount_paid); ?>',                     // Total transaction value (incl. tax and shipping)
                        'tax':'0',
                        'shipping': '0'
                    },
                    'products': [
                        {
                            'name': '<?php echo e($class_name); ?>',
                            'id': '<?php echo e($class_id); ?>',
                            'price': '<?php echo e($amount_paid); ?>',
                            // 'brand': 'エスティマ',
                            'quantity': 1
                        },
                    ]
                }
            }
        });
        <?php endif; ?>
        <?php endif; ?>
    </script>
<div class="page-container">
  <!-- BEGIN PAGE HEAD-->
  <div class="page-head hidden-xs">
    <div class="container clearfix">
      <!-- BEGIN PAGE TITLE -->
      <div class="page-title">
        <ul class="page-breadcrumb breadcrumb">
          <li> <a href="<?php echo e(URL::to('/')); ?>"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> </li>
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
  <div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->

    <!-- BEGIN CONTENT HEADER -->
    <div class="dynamic-page-header dynamic-page-header-default">
      <div class="container clearfix">
        <div class="col-md-12 bottom-border ">
          <div class="page-header-title">
            <h1> <?php echo app('translator')->getFromJson('qs3.qsform'); ?></h1>
          </div>
        </div>
      </div>
    </div>
    <!-- begin search -->
    <div class="page-content">
      <div class="container">
        <div class="row">
          <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
            <h3> <?php echo app('translator')->getFromJson('qs3.reservation'); ?> <?php echo e($userInfo->last_name); ?> <?php echo e($userInfo->first_name); ?> <?php echo app('translator')->getFromJson('qs3.mr'); ?></h3>

            <?php if(isset($transaction_id)): ?>
            <p><?php echo app('translator')->getFromJson('qs3.thank'); ?></p>
            <?php endif; ?>

            <!-- quick start 3 -->
            <div class="box-shadow relative red-border-top">
              <div class="formcard-block-cont">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formcard-right">
                    <h3 class="text-center relative"><?php echo app('translator')->getFromJson('qs3.completed'); ?></h3>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formcard-right">
                    <div class="panel panel-default">
                      <div class="bg-grad-gray panel-heading"> <a id="credit-sec"></a>
                        <?php if(isset($transaction_id)): ?>
                        <h4><?php echo app('translator')->getFromJson('qs3.config'); ?></h4>
                        <?php else: ?>
                        <h4><?php echo app('translator')->getFromJson('qs3.thank1'); ?>
                        </h4>
                        <?php endif; ?>
                      </div>
                    </div>

                    

                    <?php if(isset($transaction_id)): ?>
                    <form method="post" id="search-thankyou" action="">
                    <?php echo csrf_field(); ?>

                   	<input type="hidden" name="bag_choosed" id="bag_choosed" value="1" />
                    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $booking_id; ?>" />
                    <div class="choose-content">
                        <div class="choose-common">
                            <label >
                            <input type="radio" name="bag_choose" value="1" class="bag_choose hidden" bid="1">
                            <div class="choose-inner-block active" bid="1">
                                <div class="small-block">
                                    <h2><?php echo app('translator')->getFromJson('qs3.choose'); ?></h2>
                                </div>
                                <div class="choose-img-block">
                                    <?php $bag1 = $util->Tr('bag1'); ?>
                                    <img src="img/<?php echo e($bag1); ?>.jpg" alt="">
                                </div>
                                <div class="choose-text-block">
                                    <p><?php echo app('translator')->getFromJson('qs3.refresh'); ?></p>
                                </div>
                            </div>
                            </label>
                        </div>
                        <div class="choose-common">
                            <label>
                            <input type="radio" name="bag_choose" value="2" class="bag_choose hidden" bid="2">
                            <div class="choose-inner-block" bid="2">
                                <div class="small-block" style="display: none">
                                    <h2><?php echo app('translator')->getFromJson('qs3.choose'); ?></h2>
                                </div>
                                <div class="choose-img-block">
                                    <?php $bag2 = $util->Tr('bag2'); ?>
                                    <img src="img/<?php echo e($bag2); ?>.jpg" alt="">
                                </div>
                                <div class="choose-text-block">
                                    <p><?php echo app('translator')->getFromJson('qs3.adult'); ?></p>
                                </div>
                            </div>
                            </label>
                        </div>
                        <div class="choose-common">
                            <label>
                            <input type="radio" name="bag_choose" value="3" class="bag_choose hidden" bid="3">
                            <div class="choose-inner-block" bid="3">
                                <div class="small-block" style="display: none">
                                    <h2><?php echo app('translator')->getFromJson('qs3.choose'); ?></h2>
                                </div>
                                <div class="choose-img-block">
                                    <?php $bag3 = $util->Tr('bag3'); ?>
                                    <img src="img/<?php echo e($bag3); ?>.jpg" alt="">
                                </div>
                                <div class="choose-text-block">
                                    <p> <?php echo app('translator')->getFromJson('qs3.enjoy'); ?></p>
                                </div>
                            </div>
                            </label>
                        </div>
                    </div>

                    <div class="quick-button" id="show_message">
                        <?php if($util->lang() == 'ja'): ?>
                        <input type="button" name="submit" id="bagsubmit" onclick="javascript:choosebag();" class="submitBtn form-control h40" value="送信する">
                        <?php endif; ?>
                        <?php if($util->lang() == 'en'): ?>
                            <input type="button" name="submit" id="bagsubmit" onclick="javascript:choosebag();" class="submitBtn form-control h40" value="Send">
                        <?php endif; ?>
                    </div>
                    </form>
                    <?php endif; ?>

                  </div>
                </div>
              </div>
            </div>
            <!-- quick start 3 -->

          </div>
          <!-- END PAGE CONTENT INNER -->
          <div class="dynamic-sidebar col-lg-3 col-md-3 hidden-lg hidden-md hidden-sm hidden-xs">
            <div class="portlet portlet-fit light cont-box">
              <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10"> <a href="#"><img class="center-block img-responsive" src="" alt=""></a> </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 clearfix"> <a href="#" class="bg-carico totop-link"><?php echo app('translator')->getFromJson('qs3.toppage'); ?></a> </div>
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
    <script>
        $small_block = $('.small-block');
        $('.bag_choose').click( function () {
            var bag_id = $(this).attr('bid');
            $('.choose-inner-block').removeClass('active');
            $('.choose-inner-block div.small-block').fadeOut();

            $('.choose-inner-block[bid="' + bag_id + '"]').addClass('active');
            $('.choose-inner-block.active div.small-block').fadeIn();
            $('#bag_choosed').val(bag_id);
			console.log(bag_id);
        });


		function choosebag(){

            var bag_choosed = $('#bag_choosed').val();
			var booking_id  = $('#booking_id').val();
            $.ajax({
                url  : '<?php echo URL::to("/savebag_choose"); ?>',
                type : 'post',
                data : {'booking_id':booking_id, 'bag_choosed':bag_choosed, '_token':$('input[name="_token"]').val()},
                success : function(result,status,xhr) {
                    <?php if($util->lang() == 'ja'): ?>
                   	 $('#show_message').html('<br>ご利用いただきまして、ありがとうございます。<br><br>当日はスタッフ一同楽しみにおまちしております！<br>');
                   	<?php endif; ?>
                    <?php if($util->lang() == 'en'): ?>
                    $('#show_message').html('<br>Thank you for using Hako Rent-a-car. <br><br> We look forward to meeting you on the day!<br>');
                    <?php endif; ?>
                },
                error : function(xhr,status,error){
                    alert(error);
                }
            });
		}


    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>