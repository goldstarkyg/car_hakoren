<link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/css/home.css">
<script src="<?php echo e(URL::to('/')); ?>/js/plugins/typedjs/typed.min.js"></script>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<style type="text/css">
    .typed-cursor { display: none !important;}
   .btn-fixed{
    position: fixed;
    left: 0;
    right: 0;
     bottom: 0px;
}
.btn-set{
    float: left;
    margin-left: 20px;
    border-radius: 100% !important;
}
.Footer_model .bubble-wrap1 {
    /*width: 210px;*/
    margin: 20px auto;
    padding: 7px 15px 7px 7px;
}
.Footer_model .bubble {
    position: relative;
    padding: 10px;
    background: #FFFFFF;
    border-radius: 4px;
    border: #7F7F7F solid 1px;
    font-size: 1.5em;
    line-height: 1.25em;
    height: 145px;
    margin-top: -15px;
}
.Footer_model .bubble.left:before {
    content: '';
    position: absolute;
    border-style: solid;
    border-width: 16px 16px 16px 0;
    border-color: transparent #7F7F7F;
    display: block;
    width: 0;
    z-index: 0;
    left: -16px;
    top: 50%;
    margin-top: -16px;
}
.Footer_model .bubble.left:after {
    content: '';
    position: absolute;
    border-style: solid;
    border-width: 16px 16px 16px 0;
    border-color: transparent #FFFFFF;
    display: block;
    width: 0;
    z-index: 1;
    left: -15px;
    top: 50%;
    margin-top: -16px;
}
.Footer_model .m-top-30 {
    margin-top: 25px !important;
}
.Footer_model .m-top-30 img{
    display: inline-block;
}
.Footer_model .bubble_write {
    font-weight: 800;
    font-size: 19px;
}
.Footer_model .m-top-20 {
    margin-top: 20px !important;
}

.Footer_model h1 {
  margin-top: 100px;
}

.Footer_model .typewriter {
  border-right: .05em solid;
  animation: caret 1s steps(1) infinite;
}

@keyframes  caret {
  50% {
    border-color: transparent;
  }
}

.go_to_home{
    bottom: 25px;
    cursor: pointer;
    height: 40px;
    position: fixed;
    display: none;
    right: 25px;
    width: 40px;
    z-index: 999999;
    text-align: center;
    transition: all 0.4s ease 0s;
    border-radius: 100% !important;
    background-color: #e20001;
    color: #fff;
    font-size: 13px;
}
.go_to_home i{
  line-height: 35px !important;
}

@media  screen and (max-width: 600px){
.go_to_home{
  display: block;
}
}

</style>

<script type="text/javascript">
$(document).ready(function() {
  //go to home
    $('.go_to_home').click(function(){
    $('body,html').animate({
      scrollTop: 0
       }, 800);
       return false;
    });
    $(window).scroll(function () {
      if ($(this).scrollTop() > 200) {
        $(".go_to_home").show();
      } else {
        $(".go_to_home").hide();
      }
    });
});
</script>

<?php 
    $time1 = strtotime('09:00:00');
    $time2 = strtotime('19:30:00');
    date_default_timezone_set("Asia/tokyo");
    $time = strtotime(date("H:i:s"));
?>


<div class="row" style="margin:0px;">
    <div class="col-lg-12" style="padding:0px 0px;">
        <!-- BEGIN PRE-FOOTER -->
        <div class="page-prefooter footer_wrap footer-2" style="margin-top:0;" >
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4 ">
                        <img src="<?php echo e(URL::to('/')); ?>/img/common/<?php echo e($util->Tr('logo-lp')); ?>.png" width="300">
                    </div>
                </div>
            </div>
        </div>
<!-- Start Model Code add by bhavin -->
<!-- Model-1 -->
<div id="Footer1_Mobile_show_model" class="Footer_model modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
         <div id="callmodal_first" style="display: block;">
          <div class="col-xs-5 m-top-30">
           <img class="img-responsive" src="<?php echo e(URL::to('/')); ?>/img/stuff_02.png" alt="">
          </div>
          <div class="bubble-wrap1 col-xs-7" style="box-shadow: none !important;">
           <div class="bubble left" style="display: block !important;">
            <div class="headline"></div>
           </div>
          </div>
         </div>
        </div>
        <div class="row" id="callmodal_second" style="display: block;">
         <div class="col-sm-6">
		 <span class="btn-blu"><a  href="tel:092-260-9506">福岡空港店に電話</a></span>
         </div>
         <div class="col-sm-6">
		 <span class="btn-red"><a href="tel:098-851-4291">那覇空港店に電話</a></span>
         </div>
        </div>
        <div class="m-top-20" id="callmodal_third" style="display: block;">
         <img class="img-responsive" src="<?php echo e(URL::to('/')); ?>/img/banner_bottom1.png" alt="">
        </div>
      </div>
    </div>

  </div>
</div>
<!-- Model-2 -->
<div id="Footer2_Mobile_show_model" class="Footer_model modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
         <div id="callmodal_first" style="display: block;">
          <div class="col-xs-4 m-top-30">
           <img class="img-responsive" src="<?php echo e(URL::to('/')); ?>/img/banner_top.png" alt="">
          </div>
          <div class="bubble-wrap1 col-xs-8" style="padding:7px;">
           <div class="bubble left">
            <div class="headline"></div>
          </div>
          </div>
         </div>
        </div>
        <div class="row" id="callmodal_second" style="display: block;">
         <div class="col-sm-6">
		 <span class="btn-blu"><a  href="tel:092-260-9506">福岡空港店に電話</a></span>
         </div>
         <div class="col-sm-6">
		 <span class="btn-red"><a href="tel:098-851-4291">那覇空港店に電話</a></span>
         </div>
        </div>
        <div class="m-top-20" id="callmodal_third" style="display: block;">
         <img class="img-responsive" src="<?php echo e(URL::to('/')); ?>/img/banner_bottom2.png" alt="">
        </div>
      </div>
    </div>

  </div>
</div>
<!-- end Model Code add by bhavin -->

<?php if(!isset($no_sp_tel_icon)): ?>
    <?php if(!Session::has('confirm-submit') || Session::get('confirm-submit') !== true): ?>
    <?php if($time > $time1 && $time < $time2) { ?>
        <div class="text-center btn-fixed visible-xs-block">
          <a href="javascript:void(0);" id="call-button" data-toggle="modal" data-target="#Footer1_Mobile_show_model" style="width:50%;float:left;">
				<?php if($util->lang() == 'ja'): ?>
					<img src="<?php echo URL::asset('img/call-button.png'); ?>"  alt="Call Us" />
				<?php elseif($util->lang() == 'en'): ?>
					<img src="<?php echo URL::asset('img/call-button_en.png'); ?>"  alt="Call Us" />
				<?php endif; ?>
          </a>
          <a href="<?php echo e(url('/search-car')); ?>" id="search-button" style="width:50%;float:left;">
				<?php if($util->lang() == 'ja'): ?>
					<img src="<?php echo URL::asset('img/search-button.png'); ?>"  alt="Search Car" />
				<?php elseif($util->lang() == 'en'): ?>
					<img src="<?php echo URL::asset('img/search-button_en.png'); ?>"  alt="Search Car" />
				<?php endif; ?>
          </a>
        </div>
    <?php } ?>
    <?php endif; ?>
<?php endif; ?>
                <!-- END PRE-FOOTER -->
                <!-- BEGIN PRE-FOOTER -->
                <div class="page-prefooter last_footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class = "foo" style="text-align:center;">
                                    <!-- <li><a href="<?php echo e(URL::to('/')); ?>/rules">ご利用規約</a></li> -->
							<li><a href="<?php echo e(URL::to('/')); ?>/transactions"> <?php echo app('translator')->getFromJson('footer.law'); ?> </a></li>
							<li><a href="<?php echo e(URL::to('/')); ?>/agreement"> <?php echo app('translator')->getFromJson('footer.carrental'); ?> </a></li>
							<li><a href="<?php echo e(URL::to('/')); ?>/contact"> <?php echo app('translator')->getFromJson('footer.contactus'); ?> </a></li>
							<li><a href="<?php echo e(URL::to('/')); ?>/policy"> <?php echo app('translator')->getFromJson('footer.policy'); ?> </a></li>
						</ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PRE-FOOTER -->
        <!-- BEGIN INNER FOOTER -->
        <div class="page-footer">
            <div class="container">
                <p class="margin-top-0 margin-bottom-0 text-center">Copyright©　ハコレンタカー All Rights Reserved.</p>
            </div>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
        
        <!-- top btn -->
          <div class="go_to_home">
            <i class="fa fa-chevron-up"></i>
          </div>
        <!-- top btn -->
    </div>
</div>

    <script type="text/javascript">
        var type_bubble = null;
        $("#Footer1_Mobile_show_model").on('shown.bs.modal', function () {
            type_bubble = new Typed('.headline', {
                strings: [
                    "ハコレンタカーをご利用いただき誠にありがとうございます。",
                    "電話1本でお手間を取らせず空車確認・ご予約ができます。",
                    " どちらの店舗でご利用でしょうか？"
                ],
                typeSpeed: 60,
                backSpeed: 0,
                fadeOut: true,
                loop: true,
                backDelay: 1500
            });
        });
        $("#Footer1_Mobile_show_model").on('hide.bs.modal', function () {
            type_bubble.destroy();
            type_bubble = null;
        });
    </script>


<?php $__env->startSection('footer_scripts'); ?>

        <!--[if lt IE 9] -->
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/respond.min.js"></script>
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
	<script src="<?php echo e(URL::to('/')); ?>/js/plugins/kana/jquery.autoKana.js" type="text/javascript"></script>
	<script type="text/javascript">
	//jQuery.noConflict();
        $('select[name="change_locale"]').change(function(){
        var value = $(this).val();
        var url = '<?php echo e(URL::to('/')); ?>/lang/'+value;
        $(location).attr('href', url);
        });

        $(function() {
			$.fn.autoKana('#last_name', '#furi_last_name', {
				katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
			});
		});
	</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.1.0/css/drawer.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.3/iscroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.1.0/js/drawer.min.js"></script>
<script>
    $(document).ready(function() {
        $('.drawer').drawer();
        $('.drawer-nav').show();
    });

</script>

<script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<?php $__env->stopSection(); ?>