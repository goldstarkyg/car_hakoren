@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_first.css" rel="stylesheet" type="text/css">
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
          
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
					<div class="box-shadow">
                    <div class="row">
						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
							<img class="center-block img-responsive" src="img/pages/posts/okinawa-rentacar-trip-information-top.jpg" alt="">
						</div>
						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
							<h1>旅行ページタイトル</h1>
						</div>
					</div>
					</div>
					<div class="box-shadow">
					<div class="row m_TB30">
						<div class="content-main col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<a href="#"><img class="center-block img-responsive m_B10" src="img/pages/first/btn-fukuoka.png" alt="ハコレンタカー　福岡からご予約・出発・ご返却の流れ"></a>
						</div>
						<div class="content-main col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<a href="#"><img class="center-block img-responsive m_B10" src="img/pages/first/btn-okinawa.png" alt="ハコレンタカー　沖縄からご予約・出発・ご返却の流れ"></a>
						</div>
						<div class="content-main col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<a href="#"><img class="center-block img-responsive m_B10" src="img/pages/first/btn-faq.png" alt="ハコレンタカー　よくあるご質問"></a>
						</div>
					</div>
					</div>

					<!-- begin ハコレンタカーについて-->
					<div class="box-shadow">
					<div class="row first-step-sec" style="min-height:200px;">
						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2>ハコレンタカーについて</h2>
						</div>
					</div>
					</div>

					<!-- begin 予約・出発・返却の流れ-->
					<div class="box-shadow">
					<div class="row first-step-sec">
						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2>予約・出発・返却の流れ</h2>
							<p><span class="color-red">ハコレンの安心・簡単</span>お手続きで初めての方でもレンタカーをすぐにご利用いただけます。</p>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
							<img class="center-block img-responsive hidden-xs" src="img/pages/first/first-step.png" alt="ハコレンタカー　ご利用ステップ">
							<img class="center-block img-responsive visible-xs" src="img/pages/first/first-step-sp.png" alt="ハコレンタカー　ご利用ステップ">
							<hr class="skyblue">
						</div>

						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">

							<!-- begin STEP 1-->
							<div class="circle80 step-circle bg-blue">福岡 </div>
							<h3> <span class="step-title">STEP 1</span>ご予約</h3>
							<p>ネット予約が簡単でお得！</p>
							<div class="row m_T30 m_B50">
								<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">
									<span class="btn-pink"><a href="" class="">会員の方はこちら　></a></span>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 ">
									<span class="btn-pink"><a href="" class="">予約をする　></a></span>
								</div>
							</div>

							<!-- begin STEP 2-->
							<h3><span class="step-title">STEP 2</span>  ご出発時のお手続き</h3>
							<p class="color-red">クイック乗り出しですぐにご出発</p>
							<h4 class="btm-border">ご来店時にご持参いただくもの</h4>
							<p>運転免許証（運転される方全員）<br>
クレジットカード（出発時にクレジットカードにてご予約金額を前払いいただきます。）<br>
※ Web決済をご利用の場合は、ご来店時にカードをご提示いただく必要はありません。<br>
現金でお支払の場合は、ご本人確認書類をご持参ください。<br>
※公共料金（電気・ガス・水道等）領収書、 健康保険証、住民票、年金手帳、住民基本台帳カード(氏名・生年月日・住所の記載があるもの)など</p>
							<div class="quick-box">
								<p class="fs20"><a href="#">クイック乗り出し未登録の方はこちら 〉</a></p>
								<p>※クイック乗り出しが未登録の場合、店舗でのお手続きが約20分ほどかかります。他のお客様とお時間が重なった場合は、お待たせする可能性がございますので、ご了承ください。
								</p>
							</div>

							<!-- begin STEP 3-->
							<h3><span class="step-title">STEP 3</span>  ご利用中</h3>
							<h4 class="btm-border">安心のロードサービス</h4>
							<div class="type-blue">
								<ul>
									<li>事故</li>
									<li>キー閉じ込めトラブル</li>
									<li>タイヤ・脱輪等のトラブル</li>
									<li>バッテリーのトラブルガス欠のトラブル</li>
								</ul>
							</div>

							<!-- begin STEP 4-->
							<h3><span class="step-title">STEP 4</span>  ご返却時のお手続き</h3>
							<h4 class="btm-border">ご返却の際に確認すること　</h4>
							<div class="type-blue">
								<ul>
									<li>燃料満タンの状態でお返しください。</li>
									<li>車内にお忘れ物のないようにご確認ください。</li>
									<li>店舗スタッフがキズの有無をチェックいたします。</li>
									<li>超過時間等の料金清算をいたします。</li>
								</ul>
							</div>

						</div>
					</div>
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
