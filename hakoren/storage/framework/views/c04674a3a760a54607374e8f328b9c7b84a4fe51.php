<?php $__env->startSection('template_linked_css'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/page_contact.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
    
    
    <style>
    </style>
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('content'); ?>
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
                            <span><?php echo app('translator')->getFromJson('contact.contactus'); ?></span>
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
								<h1><?php echo app('translator')->getFromJson('contact.contactus'); ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">

						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 ">

							<!-- contact box -->
							<div class="box-shadow relative red-border-top">
								<div class="clearfix">
									<div class="col-md-12 col-sm-12 col-xs-12 contact-box">
										<h2><?php echo app('translator')->getFromJson('contact.variousinquiry'); ?></h2>
										<p><?php echo app('translator')->getFromJson('contact.preparing'); ?><a href="<?php echo e(URL::to('/')); ?>/faq/price">＜<?php echo app('translator')->getFromJson('contact.faq'); ?>＞</a></p>

										<h3><?php echo app('translator')->getFromJson('contact.inquiry'); ?></h3>
										<div class="row contact-times">
											<div class="col-md-5 col-sm-5 col-xs-12 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">
												<h4><?php echo app('translator')->getFromJson('contact.fuku'); ?></h4>
												<p>Tel. 092-260-9506</p>
												<p class="fs12"><<?php echo app('translator')->getFromJson('contact.businesstime'); ?>> 9：00～19：30 <span style="font-size:1.2rem; padding:3px 10px; background:#e20001; color:#fff;margin-left: 10px; font-weight:500;-webkit-border-radius: 3px !important;-moz-border-radius: 3px !important;border-radius: 3px !important;"><?php echo app('translator')->getFromJson('contact.week'); ?></span></p>
											</div>
											<div class="col-md-5 col-sm-5 col-xs-12">
												<h4><?php echo app('translator')->getFromJson('contact.okina'); ?></h4>
												<p>Tel. 098-851-4291</p>
												<p class="fs12"><<?php echo app('translator')->getFromJson('contact.businesstime'); ?>> 9：00～19：30 <span style="font-size:1.2rem; padding:3px 10px; background:#e20001; color:#fff;margin-left: 10px; font-weight:500;-webkit-border-radius: 3px !important;-moz-border-radius: 3px !important;border-radius: 3px !important;"><?php echo app('translator')->getFromJson('contact.week'); ?></span></p>
											</div>
										</div>
										<h3><?php echo app('translator')->getFromJson('contact.inquiryform'); ?></h3>

										<form id="contact-form" enctype="multipart/form-data" method="post" action="" onSubmit="return validateForm()">
											<input type="hidden" name="lang" id="lang" value="<?php echo e($util->lang()); ?>" />
											<table class="table-res-form">
												<tbody>
													<tr>
														<th>
															<?php echo app('translator')->getFromJson('contact.name'); ?>
														</th>
														<td>
															<input type="text" name="name" id="name" size="40" placeholder=" <?php echo app('translator')->getFromJson('contact.hakoname'); ?>" value="" class="form-control h40" >
															<span class="error-class errorlast_name"></span>
														</td>
													</tr>

													<tr>
														<th>
															<?php echo app('translator')->getFromJson('contact.mail'); ?>
														</th>
														<td>
																	<input type="email" name="email" value="" class="form-control h40" size="40" placeholder=" <?php echo app('translator')->getFromJson('contact.ex'); ?> hakorentarou@exsample.com">
														</td>
													</tr>

													<tr>
														<th>
															<?php echo app('translator')->getFromJson('contact.use'); ?>
														</th>
														<td>
															<input type="radio" value="<?php echo app('translator')->getFromJson('contact.fuku'); ?>" name="radio01" class="radio01-input" id="radio01-01" checked>
															<label for="radio01-01"><?php echo app('translator')->getFromJson('contact.fuku'); ?></label>
															<input type="radio" name="radio01" class="radio02-input" value="<?php echo app('translator')->getFromJson('contact.okina'); ?>" id="radio01-02">
															<label for="radio01-02"><?php echo app('translator')->getFromJson('contact.okina'); ?></label>
														</td>
													</tr>

													<tr>
														<th>
															<?php echo app('translator')->getFromJson('contact.contentinquiry'); ?>
														</th>
														<td>
																<textarea name="message" cols="50" class="form-control h40" rows="5"></textarea>
														</td>
													</tr>

													<tr>
														<th>
															<?php echo app('translator')->getFromJson('contact.confirm'); ?>
														</th>
														<td>
														<input type="checkbox" style="display:inline-block; margin-right:3px;" name="confirm" class="" value="<?php echo app('translator')->getFromJson('contact.confirmtext'); ?>"><?php echo app('translator')->getFromJson('contact.confirmtext'); ?>
														</td>
													</tr>

												</tbody>
											</table>
											<input type="submit" name="submit" class="submitBtn" value="<?php echo app('translator')->getFromJson('contact.send'); ?>" onclick="check()">
										</form>
										<!-- contact form -->
									</div>
								</div>
							</div>
							<!-- contact box -->

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
                            <a href="#" class="bg-carico totop-link"><?php echo app('translator')->getFromJson('contact.toppage'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
        
        <form action="<?php echo e(URL::to('/')); ?>/search-save" method="POST" name="booking-submit" id="booking-submit">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="email" id="data_email" >
            <input type="hidden" name="first_name" id="data_first_name" >
            <input type="hidden" name="last_name" id="data_last_name" >
            <input type="hidden" name="furi_first_name" id="data_furi_first_name" >
            <input type="hidden" name="furi_last_name" id="data_furi_last_name" >
        </form>
        
    </div>

    <script>
        function showModal(text) {
            $('#modalError p.error-text').html(text);
            $('#modalError').modal();
        }

        function selectModel(model_id) {
            $('input[name="model_id"]').val(model_id);
            // change style of selected tag
        }

        function check() {
            if($('#check-it').prop('checked') === false){
                showModal('<?php echo app('translator')->getFromJson("contact.mebership"); ?>');
                return false;
            }
            var email = $('input[name="email"]').val().trim();
            if(email === '') {
                showModal('Input email');
                return false;
            } else {
                $('#data_email').val(email);
            }
            var name = $('input[name="name"]').val().trim();
            if(name === '') {
                showModal('<?php echo app('translator')->getFromJson("contact.entername"); ?>');
                return false;
            } else {
                $('#data_name').val(name);
            }

            // var model = $('#model_id').val();
            // if(model === '') {
            //     showModal('Select car model');
            //     return false;
            // }

            //$('#booking-submit').submit();
        }

        $('.model-photo').click(function () {
            // var mid = $(this).attr('model_id');
            // $('.model-photo').removeClass('active');
            // $(this).addClass('active');
            // selectModel(mid);
        });

        $('.breadcrumb-item').click(function () {
            // var mid = $(this).attr('model_id');
            // $('.breadcrumb-item').removeClass('active');
            // $(this).addClass('active');
            // selectModel(mid);
        });
    </script>

    <?php echo $__env->make('modals.modal-membership', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('modals.modal-error', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
  <style>

  </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
<script type="text/javascript">
$(function(){
    var setFileInput = $('.imgInput'),
    setFileImg = $('.imgView');

    setFileInput.each(function(){
        var selfFile = $(this),
        selfInput = $(this).find('input[type=file]'),
        prevElm = selfFile.find(setFileImg),
        orgPass = prevElm.attr('src');

        selfInput.change(function(){
            var file = $(this).prop('files')[0],
            fileRdr = new FileReader();

            if (!this.files.length){
                prevElm.attr('src', orgPass);
                return;
            } else {
                if (!file.type.match('image.*')){
                    prevElm.attr('src', orgPass);
                    return;
                } else {
                    fileRdr.onload = function() {
                        prevElm.attr('src', fileRdr.result);
                    }
                    fileRdr.readAsDataURL(file);
                }
            }
			alert('sda');
        });
    });
});
</script>
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/jquery-validation/js/localization/messages_ja.js" type="text/javascript"></script>
<script type="text/javascript">
	var validate_rule = {
            last_name: { required: true },
            first_name: { required: true },
            furi_last_name: { required: true, katakana: true },
            furi_first_name: { required: true, katakana: true },
            email: { required: true, email: true },
            phone: { required: true, number: true, minlength: 9, maxlength: 11 },
            zip11: { required: true, number: true },
            address: { required: true},
            person-number: { required: true, number: true },
            driver-number: { required: true, number: true }
        };
	$('#quickstart-form').validate({
		errorElement : 'span',
		errorPlacement: function(error, element) {
			var eP = $(".error"+element.attr("name"));
			error.appendTo(eP);
		},

		rules: validate_rule,
		messages: {
			last_name: {
				required: jQuery.validator.format("姓を入力してください"),
			},
			first_name: {
				required: jQuery.validator.format("名前を入力してください"),
			},
			furi_first_name: {
				required: jQuery.validator.format("名前を入力してください"),
			},
			furi_last_name: {
				required: jQuery.validator.format("姓を入力してください"),
			},
			'checkboxes1[]': {
				required: 'Please check some options',
				minlength: jQuery.validator.format("At least {0} items must be selected"),
			},
			'checkboxes2[]': {
				required: 'Please check some options',
				minlength: jQuery.validator.format("At least {0} items must be selected"),
			}
		},
		submitHandler: function(form) {
			check();
		}
	});

	//全角ひらがなのみ
	jQuery.validator.addMethod("katakana", function(value, element) {
	 return this.optional(element) || /^([ァ-ヶー]+)$/.test(value);
	 }, "全角カタカナを入力下さい"
	);
</script>
<script src="<?php echo e(URL::to('/')); ?>/js/plugins/kana/jquery.autoKana.js" type="text/javascript"></script>
<script type="text/javascript"><!--
//jQuery.noConflict();
	$(function() {
		$.fn.autoKana('#first_name', '#furi_first_name', {
			katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
		});
		$.fn.autoKana('#last_name', '#furi_last_name', {
			katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
		});
	});
	-->
</script>
<script type="text/javascript">
	var validate_rule_payment = {
            card_num: { required: true, number: true, minlength: 15, maxlength: 16 },
            card_expired_y: { required: true, number: true},
            card_expired_y: { required: true, number: true},
            secure_num: { required: true, number: true , minlength: 3, maxlength: 3}
        };
	$('#formcard-form').validate({
		errorElement : 'span',
		errorPlacement: function(error, element) {
			var eP = $(".error"+element.attr("name"));
			error.appendTo(eP);
		},

		rules: validate_rule_payment,
		messages: {
			last_name: {
				required: jQuery.validator.format("姓を入力してください"),
			},
			first_name: {
				required: jQuery.validator.format("名前を入力してください"),
			},
			furi_first_name: {
				required: jQuery.validator.format("名前を入力してください"),
			},
			furi_last_name: {
				required: jQuery.validator.format("姓を入力してください"),
			},
			'checkboxes1[]': {
				required: 'Please check some options',
				minlength: jQuery.validator.format("At least {0} items must be selected"),
			},
			'checkboxes2[]': {
				required: 'Please check some options',
				minlength: jQuery.validator.format("At least {0} items must be selected"),
			}
		},
		submitHandler: function(form) {
			check();
		}
	});
	$('input,textarea').on('keyup blur', function() {
    var $submitBtn = $('.submitBtn');
    if ($("#formcard-form").valid()) {
        $submitBtn.prop('disabled', false);
    } else {
        $submitBtn.prop('disabled', 'disabled');
    }
});


	function validateForm(){
// フォームの送信先を正規のURLに変えます。
$('#contact-form').attr('action','contactForm.php');
$('#contact-form').attr('method','POST');
return true;
}
</script>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>