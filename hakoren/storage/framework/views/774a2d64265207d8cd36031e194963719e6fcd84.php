<?php $__env->startSection('template_linked_css'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/page_mypage.css" rel="stylesheet" type="text/css">
    
    

<style>
.btn-circle_active {
 left:15px;
	 background:#13e313; 
	 width:10px; 
	 height:10px; 
	 position:absolute; 
	 margin-right:10px; 
	 border-radius: 5px;
}
.btn-circle_old {
 
	 background:#fff; 
	 width:10px; 
	 position:absolute; 
	 height:10px; 
	 margin-right:10px; 
	 border-radius: 5px;
}
p {
    margin: 10px 0;
}
</style>    
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('content'); ?>
    <script>
        <?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false ): ?>
        dataLayer.push({'userId':'<?php echo e($user->id); ?>'});
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
                            <span><?php echo app('translator')->getFromJson('mypage.mypage'); ?></span>
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
								<h1><?php echo app('translator')->getFromJson('mypage.memberpage'); ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">

						<div class="content-main col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
							<!-- mypage box -->
							<div class="mypage-box">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php
                                            $mapage_top = $util->Tr('mapage-top');
                                        ?>
										<img class="img-responsive center-block m_T20" src="<?php echo e(URL::to('/')); ?>/img/<?php echo e($mapage_top); ?>.png" alt="">
											
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

										<h2><b><?php echo e($user->last_name); ?>&nbsp;<?php echo e($user->first_name); ?>&nbsp;</b><?php echo app('translator')->getFromJson('mypage.reservation'); ?></h2>
                                        <?php if(session()->has('success')): ?>
                                            <div class="alert alert-success">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <?php echo e(session()->get('success')); ?>

                                            </div>
                                        <?php endif; ?>

										<table class="booking-list table">
                                            <?php
                                                $depart_shop = $util->Tr('depart_shop');
                                            ?>
											<?php if(count($books) !== 0): ?>
												<?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr>
												<th style="padding-bottom:20px; font-size:12px;">
                                                    <span class="yoyaku" ><?php echo app('translator')->getFromJson('mypage.reser'); ?> <?php echo e($key+1); ?></span>
                                                    <div class="<?php if($book->booking_active): ?> btn-circle_active <?php else: ?>  btn-circle_old <?php endif; ?>"></div>
												</th>
												
												<td style=" cursor:pointer; " onclick="bookingDetail(<?php echo e(json_encode($book)); ?>)">
                                                    <?php echo e($book->depart_date); ?>&nbsp;<?php echo e($book->depart_time); ?>&nbsp;～&nbsp;<?php echo e($book->return_date); ?> <br><?php echo e($book->return_time); ?>&nbsp;<?php echo e($book->$depart_shop); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($book->class_name); ?>&nbsp;<?php echo e($book->smoke); ?>&nbsp;&nbsp;
                                                </td>
												<td class="quick"> 
												<?php if($book->web_status < 3 and $book->booking_active): ?> <a class="btn btn-success" style="color:#fff;" href="<?php echo e(url('/mypage/movetoqs/'.$book->id)); ?>">Quick <?php echo app('translator')->getFromJson('mypage.embark'); ?></a> <?php endif; ?>
												</td>
												</tr>

												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php else: ?>
												<li style="padding-bottom:20px; font-size:14px;" >
													<?php echo app('translator')->getFromJson('mypage.currentreservation'); ?>
												</li>
												
											<?php endif; ?>

										</table>

                                        <?php if(count($posts)): ?>
                                        <h2><?php echo app('translator')->getFromJson('mypage.latestnotice'); ?></h2>
										<div class="clearfix">
											<table class="table newsTable" style="table-layout:fixed;">
											<?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr>
													<td style="width:25%;padding-left:2px;">
                                                    <?php if($util->lang() == 'ja'): ?>
                                                        <?php echo e(date("Y年n月j日",strtotime($post->created_at))); ?>

                                                    <?php endif; ?>
                                                    <?php if($util->lang() == 'en'): ?>
                                                        <?php echo e(date("Y/n/j",strtotime($post->created_at))); ?>

                                                    <?php endif; ?>
                                                    </td>
													<td class="postTags" style="width:30%;">
													<?php
                                                        $tag = DB::table('post_tags as t')
                                                            ->leftJoin('blog_posts as p', 't.id', '=', 'p.post_tag_id')
                                                            ->select('t.title as tagname','t.title_en as tagname_en')
                                                            ->where('t.id','=', $post->post_tag_id)->first();
                                                        $tagname = 'tagname';
													?>
														<span class="tag tag_<?php echo e($post->post_tag_id); ?>">
															<?php echo e($tag->$tagname); ?>

														</span>
                                                    </td>
                                                    <?php
                                                        $title = 'title';
                                                    ?>
													<td style="
     width:45%;
    "><a href="<?php echo e(url('/view-post/'.$post->slug)); ?>"><?php echo $post->$title; ?></a></td>
												</tr>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</table>
											
										</div>
										<?php endif; ?>

									</div>
								</div>
							</div>
                        </div>
                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 col-sm-3 col-xs-12">

							<div class="mp-menu">
								<h3><?php echo app('translator')->getFromJson('mypage.pagemenu'); ?></h3>
								<ul>
									<a href="<?php echo e(URL::to('/mypage/top')); ?>"><li><?php echo app('translator')->getFromJson('mypage.bookinglist'); ?></li></a>
									<a href="<?php echo e(URL::to('/mypage/log')); ?>"><li><?php echo app('translator')->getFromJson('mypage.reservationhistory'); ?></li></a>
									<a href="<?php echo e(URL::to('/mypage/profile')); ?>"><li><?php echo app('translator')->getFromJson('mypage.memberinformation'); ?></li></a>
									<a href="<?php echo e(URL::to('/mypage/faq')); ?>"><li><?php echo app('translator')->getFromJson('mypage.faq'); ?></li></a>
									<a href="<?php echo e(URL::to('/mypage/changepassword')); ?>"><li><?php echo app('translator')->getFromJson('mypage.changepass'); ?></li></a>
									<a href="<?php echo e(URL::to('/logout')); ?>"><li><?php echo app('translator')->getFromJson('mypage.logout'); ?></li></a>
								</ul>
							</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link"><?php echo app('translator')->getFromJson('mypage.toppage'); ?></a>
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
            <input type="hidden" name="phone" id="data_phone" >
        </form>
        

    <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo app('translator')->getFromJson('mypage.reservationhistory'); ?></h4>
                    </div>
                    <form action="<?php echo e(url('/mypage/bookingupdate')); ?>" method="post" id="mdl_form">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" id="mdl_book_id" name="book_id">
                    <div class="modal-body row">
                        <div class="col-xs-12">
                            <p><?php echo app('translator')->getFromJson('mypage.thanks'); ?></p>
                            <p>■ <?php echo app('translator')->getFromJson('mypage.reserveContent'); ?></p>
                            <p><?php echo app('translator')->getFromJson('mypage.reserveId'); ?>: <span id="mdl_booking_id"></span></p>
                            <p><?php echo app('translator')->getFromJson('mypage.store'); ?>：<span id="mdl_shop_name"></span></p>
                            <p><?php echo app('translator')->getFromJson('mypage.departure'); ?>：<span id="mdl_departing"></span></p>
                            <p><?php echo app('translator')->getFromJson('mypage.return'); ?>：<span id="mdl_returning"></span></p>

                            <p><?php echo app('translator')->getFromJson('mypage.carclass'); ?>：<span id="mdl_car_model_name"></span> <span id="mdl_car_shortname"></span> (<span id="mdl_car_capacity"></span>)/<span id="mdl_smoke"></span></p>
                            <p><?php echo app('translator')->getFromJson('mypage.compensation'); ?>：<span id="mdl_insurance_type"></span></p>
                            <p><?php echo app('translator')->getFromJson('mypage.option'); ?>： <span id="mdl_options"></span></p>

                            <p>■ <?php echo app('translator')->getFromJson('mypage.popPrice'); ?></p>
                            <p><?php echo app('translator')->getFromJson('mypage.basicPrice'); ?>：<?php echo app('translator')->getFromJson('mypage.yen_en'); ?> <span id="mdl_base_price" class="coin" ></span><?php echo app('translator')->getFromJson('mypage.yen'); ?></p>
                            <p><span id="mdl_insurance_part"></span></p>
                            <p><?php echo app('translator')->getFromJson('mypage.option'); ?> <?php echo app('translator')->getFromJson('mypage.yen_en'); ?> <span id="mdl_option_price" class="coin"></span><?php echo app('translator')->getFromJson('mypage.yen'); ?> (<span id="mdl_option_detail" class="coin"></span>)</p>

                            <p>==============================<br>
                            <?php echo app('translator')->getFromJson('mypage.totalPrice'); ?>： <?php echo app('translator')->getFromJson('mypage.yen_en'); ?><span id="mdl_total_price" class="coin"></span><?php echo app('translator')->getFromJson('mypage.yen'); ?><br>
                            ==============================</p>

                            <p><?php echo app('translator')->getFromJson('mypage.smartInput'); ?></p>
                        </div>

                        <div class="col-xs-12" style="margin-bottom: 7px">
                            <label class="control-label col-xs-3" style="margin-top: 5px"> <?php echo app('translator')->getFromJson('mypage.flightNum'); ?></label>
                            <div class="col-xs-4">
                                <input type="text" id="flight_inform" name="flight_inform" class="form-control" maxlength="128" placeholder="ANA 123">
                            </div>
                        </div>
                        <div class="col-xs-12" style="margin-bottom: 7px">
                            <label class="control-label col-xs-3" style="margin-top: 5px"><?php echo app('translator')->getFromJson('mypage.passenger'); ?></label>
                            <div class="col-xs-4">
                                <select name="passenger" id="passenger" class="form-control">
                                </select>
                            </div>
                            <label class="control-label col-xs-3" style="margin-top: 5px;padding-left: 0;"><?php echo app('translator')->getFromJson('mypage.man'); ?></label>
                        </div>
                        <div class="col-xs-12" style="margin-bottom: 7px">
                            <label class="control-label col-xs-3" style="margin-top: 5px"><?php echo app('translator')->getFromJson('mypage.comment'); ?></label>
                            <div class="col-sm-4 col-xs-9">
                                <textarea rows="5" id="comment" class="xsmw220" name="comment"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal" style="line-height: 2;font-size: 22px;" onclick="updateBooking()"><?php echo app('translator')->getFromJson('mypage.register'); ?></button>
                    </div>
                    </form>
                </div>

            </div>
        </div>

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

        function updateBooking() {
            $('#mdl_form').submit();
/*            var url = "",
                method = "post",
                token = $('input[name="_token"]').val();

            $.ajax({
                url : url,
                type : method,
                data : {
                    _token          : token,
                    book_id         : $('#mdl_book_id').val(),
                    flight_inform   : $('#flight_inform').val(),
                    passenger       : $('input[name="passenger"]').val(),
                    comment         : $('textarea[name="comment"]').val()
                },
                success : function(result, status) {
                    if(status == 'success') {
                        if(result.error == '') {
                            $('#alert-wrap .alert').fadeOut();
                            $('#alert-wrap').append('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>ありがとうございました。変更内容を受け付けました。</div>');
                        } else {
                            $('#alert-wrap .alert').fadeOut();
                            $('#alert-wrap').append('<div class="alert alert-warning alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>申し訳ありません。内容を変更することができません。</div>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    alert(error);
                }
            })
            */
        }

        function bookingDetail(book) {
            $('#mdl_booking_id').text(book.booking_id);
            $('#mdl_shop_name').text(book.depart_shop);
            $('#mdl_departing').text(book.depart_date);
            $('#mdl_returning').text(book.return_date);
            $('#mdl_car_model_name').text(book.class_name);
            //$('#mdl_car_shortname').text(book.shortname);
            $('#mdl_car_capacity').text(book.max_passenger);
            $('#mdl_smoke').text(book.smoke);
            $('#flight_inform').val(book.flight_inform);
            $('#passenger').val(book.passengers);
            $('#comment').text(book.client_message);

            var insurance, insurance_part, insurance_type;
            if(book.insurance1 == 0 && book.insurance2 == 0) insurance = '0';
            if(book.insurance2 == 0 && book.insurance1 > 0) insurance = '1';
            if(book.insurance2 > 0 && book.insurance1 > 0) insurance = '2';

            if(insurance == '0') {
                <?php if($util->lang() == 'ja'): ?>
                    insurance_part = '';
                    insurance_type = 'なし';
                <?php endif; ?>
                <?php if($util->lang() == 'en'): ?>
                    insurance_part = '';
                    insurance_type = 'No';
                <?php endif; ?>
            } else if(insurance == 1) {
                <?php if($util->lang() == 'ja'): ?>
                    insurance_part = '免責補償：<span class="coin">'+ book.insurance1 +' </span>円';
                    insurance_type = '免責補償';
                <?php endif; ?>
                <?php if($util->lang() == 'en'): ?>
                    insurance_part = 'Exemption of Liability Compensation：JPY <span class="coin">'+ book.insurance1+'</span>';
                    insurance_type = 'Exemption of Liability Compensation';
                <?php endif; ?>
            } else {
                <?php if($util->lang() == 'ja'): ?>
                    insurance_part = '免責補償：<span class="coin">'+book.insurance1+'</span>円<br>ワイド免責補償：<span class="coin">'+book.insurance2+'</span>円';
                    insurance_type = '免責補償/ワイド免責補償';
                <?php endif; ?>
                <?php if($util->lang() == 'en'): ?>
                    insurance_part = 'Exemption of Liability Compensation：JPY <span class="coin" >'+book.insurance1+'</span><br>Wide Protection Package ： JPY <span class="coin">'+book.insurance2+'</span>';
                    insurance_type = 'Exemption of Liability Compensation/Wide Protection Package ';
                <?php endif; ?>
            }
            $('#mdl_insurance_type').text(insurance_type);
            $('#mdl_options').text(book.option_names);
            $('#mdl_base_price').text(book.basic_price);
            $('#mdl_insurance_part').html(insurance_part);
            $('#mdl_option_price').text(book.option_price);
            $('#mdl_option_detail').text(book.option_prices);
            $('#mdl_total_price').text(book.payment);
            $(".coin").digits();
            $('#mdl_book_id').val(book.id);
            $("#passenger option").each(function() {
                $(this).remove();
            });
            var maxperson = book.maxperson;
            var str_max = ''
            for(var m=0; m < maxperson.length ; m++) {
                var select ='';
                if(maxperson[m] == book.passengers) select='selected';
                str_max +='<option value="'+maxperson[m]+'"  '+select+' >'+maxperson[m]+'</option>';
            }
            $("#passenger").append(str_max);
            $('#myModal').modal('show');
        }
        //change foramt
        $.fn.digits = function(){
            return this.each(function(){
                $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
            })
        }

        function check() {
            if($('#check-it').prop('checked') === false){
                <?php if($util->lang() == 'ja'): ?>
                    showModal('会員規約に同意する必要があります。');
                <?php endif; ?>
                <?php if($util->lang() == 'en'): ?>
                    showModal('Please agree to the terms of use ');
                <?php endif; ?>
                return false;
            }
            var email = $('input[name="email"]').val().trim();
            if(email === '') {
                showModal('Input email');
                return false;
            } else {
                $('#data_email').val(email);
            }
            var fname = $('input[name="first_name"]').val().trim();
            if(fname === '') {
                showModal('Input first name');
                return false;
            } else {
                $('#data_first_name').val(fname);
            }
            var lname = $('input[name="last_name"]').val().trim();
            if(lname === '') {
                showModal('Input last name');
                return false;
            } else {
                $('#data_last_name').val(lname);
            }
            var ffname = $('input[name="furi_first_name"]').val().trim();
            if(ffname === '') {
                showModal('Input furigana first name');
                return false;
            } else {
                $('#data_furi_first_name').val(ffname);
            }
            var flname = $('input[name="furi_last_name"]').val().trim();
            if(flname === '') {
                showModal('Input furigana last name');
                return false;
            } else {
                $('#data_furi_last_name').val(flname);
            }
            var phone = $('input[name="phone"]').val().trim();
            if(phone === '') {
                showModal('Input phone number');
                return false;
            } else {
                $('#data_phone').val(phone);
            }
            // var model = $('#model_id').val();
            // if(model === '') {
            //     showModal('Select car model');
            //     return false;
            // }

            $('#booking-submit').submit();
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
            "last_name"     : { required: true },
            "first_name"    : { required: true },
            "furi_last_name"    : { required: true, katakana: true },
            "furi_first_name"   : { required: true, katakana: true },
            "email"             : { required: true, email: true },
            "phone"             : { required: true, number: true, minlength: 9, maxlength: 11 },
            "zip11"             : { required: true, number: true },
            "address"           : { required: true},
            "person-number"     : { required: true, number: true },
            "driver-number"     : { required: true, number: true }
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
<script type="text/javascript">
//jQuery.noConflict();
	$(function() {
		$.fn.autoKana('#first_name', '#furi_first_name', {
			katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
		});
		$.fn.autoKana('#last_name', '#furi_last_name', {
			katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
		});
	});
</script>

	
            
            
            
            
        
	
		
		
			
			
		

		
		
			
				
			
			
				
			
			
				
			
			
				
			
			
				
				
			
			
				
				
			
		
		
			
		
	
	
    
    
        
    
        
    


<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>