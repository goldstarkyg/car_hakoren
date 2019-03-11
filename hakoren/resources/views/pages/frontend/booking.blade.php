@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_booking.css" rel="stylesheet" type="text/css">
    {{--<link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">--}}
    {{--<link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">--}}
    <style>
    </style>
@endsection
@section('content')
    <div class="page-container">
        <!-- BEGIN PAGE HEAD-->

        <div class="page-head hidden-xs">
            <div class="container clearfix">
                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="{{URL::to('/')}}"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="hidden">
                            <a href="#">{{trans('fs.parent')}}</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span>{{trans('fs.current')}}</span>
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
							<h1>初めての方へ</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">

						<div class="content-main col-lg-8 col-md-8 col-sm-8 col-xs-12 pxs0">
							<img src="img/yoyaku-top.png" class="img-responsive center-block">
							<p>ご予約はインターネットと電話、2つの方法がございます。</p>
							<div class="stepbox2">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h3>インターネットで予約する</h3>
										<p>スマートフォンやタブレット・パソコンなら時間を気にせず24時間いつでもレンタカー予約」が可能です！</p>
										<div class="row  m_B10">
											<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
												<h4>① 「レンタカー予約」で検索</h4>

												<p>ご覧になっているこのページの右上「レンタカー予約」で、①地域を選択、②車両タイプを選択、③ご出発、ご返却の日時を入力し「検索」ボタンを押してください。</p>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6  ">
												<img src="img/kensaku-01.png" class="img-responsive center-block">

											</div>
										</div>
										<div class="row  m_B10 m_T50">
											<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
												<h4>② プランを選ぶ</h4>
												<p>お客様の条件に合ったおすすめのプランが表示されますので、ご希望のプラン内容をご確認の上「予約する」ボタンをクリックしてください。</p>
											</div>
											<div class="col-lg-7 col-md-7 col-sm-6 col-xs-6">
												<img src="img/kensaku-02.png" class="img-responsive center-block">
											</div>
										</div>
										<div class="row m_T50">
											<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
												<h4>③ 予約完了！</h4>
												<p>お客様のお名前・Eメールアドレス・電話番号をご記入の上「送信」してください。</p>
												<p>ご記入のEメールアドレスに予約完了メールが届きます。</p>
											</div>
											<div class="col-lg-7 col-md-7 col-sm-6 col-xs-6">
												<img src="img/kensaku-03.png" class="img-responsive center-block">
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="stepbox2">
								<div class="row ">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h3>お電話で予約する</h3>

										<div class="row ">
											<div class="col-lg-4 col-md-4 col-sm-3 col-xs-3  ">
												<img src="img/stuff_01.png" class="img-responsive center-block">

											</div>
											<div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
												<h4>ハコレンタカーまでお電話ください。</h4>
												<p><span style="font-size:2.4rem;">☎092-260-9506</span><br>受付時間： 平日9:00～19:30</p>

												<p>「レンタカー予約」とお伝えください。担当スタッフがご予約内容をお伺いいたします。</p>
											</div>

										</div>
									</div>
								</div>
							</div>



                        </div>
                        <!-- END PAGE CONTENT INNER -->

                        @include('partials.faqsidebar')
                    </div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link">ページトップへ</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
        {{--form to go confirm page--}}
        <form action="{{URL::to('/')}}/search-save" method="POST" name="booking-submit" id="booking-submit">
            {!! csrf_field() !!}
            <input type="hidden" name="email" id="data_email" >
            <input type="hidden" name="first_name" id="data_first_name" >
            <input type="hidden" name="last_name" id="data_last_name" >
            <input type="hidden" name="furi_first_name" id="data_furi_first_name" >
            <input type="hidden" name="furi_last_name" id="data_furi_last_name" >
            <input type="hidden" name="phone" id="data_phone" >
        </form>
        {{--end form--}}
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
                showModal('会員規約に同意する必要があります。');
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

    @include('modals.modal-membership')
    @include('modals.modal-error')
@endsection

@section('style')
  <style>

  </style>
@endsection

@section('footer_scripts')
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
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/localization/messages_ja.js" type="text/javascript"></script>
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
<script src="{{URL::to('/')}}/js/plugins/kana/jquery.autoKana.js" type="text/javascript"></script>
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
</script>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
@endsection
