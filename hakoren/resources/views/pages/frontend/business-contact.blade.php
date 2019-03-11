@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_business_contact.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
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
                            <span>法人のお客様限定プラン</span>
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
								<h1>法人のお客様限定プラン</h1>
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
									<div class="col-md-12 col-sm-12 col-xs-12 contact-box ">
										<!-- business plan box -->
										<img class="center-block img-responsive hidden-xs" style="padding-top:15px;" src="img/pages/contact/houjin-top-01.png" alt="">
										<img class="center-block img-responsive visible-xs" style="padding-top:15px;" src="img/pages/contact/houjin-top-sp-01.png" alt="">
										<div class="bizcon">
										<h3>法人のお客様限定プラン</h3>
										</div>
										<div class="row">
											<div class=" col-lg-4 col-md-4 col-sm-4 col-xs-12 bizcon">
												<h4 class="promis-ttl">地域1番の低価格</h4>
												<p>車種ラインナップをコンパクトワゴン以上と限定する事で地域1番の低価格を実現しました。<br>さらに一般料金よりお得な法人様専用の料金を設定しています！</p>
											 </div>
											<div class=" col-lg-4 col-md-4 col-sm-4 col-xs-12 bizcon">
												<h4 class="promis-ttl">安心の充実補償</h4>
												<p>対人・対物無制限の保険に全車加入済み！<br>ご契約時にご加入いただくことができる、ハコレンタカーの免責補償、ワイド免責補償制度でさらに安心！</p>
											 </div>
											<div class=" col-lg-4 col-md-4 col-sm-4 col-xs-12 bizcon">
												<h4 class="promis-ttl">点検/消耗品の負担0円</h4>
												<p>長期のご利用でも、定期点検・消耗品交換・自動車税などの維持費はかかりません！<br>車両費をのご負担を減らし経費削減を実現いただけます！</p>
											 </div>
										</div>
										<div class="row" style="margin:20px 0 40px;">
											<div class=" col-lg-4 col-md-4 col-sm-4 col-xs-12">
												<img class="center-block img-responsive" src="img/pages/contact/houjin-banner-01.png" alt="">
											 </div>
											<div class=" col-lg-4 col-md-4 col-sm-4 col-xs-12 ">
												<img class="center-block img-responsive" src="img/pages/contact/houjin-banner-02.png" alt="">
											 </div>
											<div class=" col-lg-4 col-md-4 col-sm-4 col-xs-12 ">
												<img class="center-block img-responsive" src="img/pages/contact/houjin-banner-03.png" alt="">
											 </div>
										</div>
										<!-- business plan box -->
									
										<h2>法人のお客様限定プラン　お問い合わせ</h2>
										<p>企業・団体様からのお問い合わせ・お見積もりなど承っております。<br>お電話または下記のフォームよりお問い合わせ下さい。</p>

										<h3>お電話でのお問い合わせ</h3>
										<div class="row contact-times">
											<div class="col-md-5 col-sm-5 col-xs-12 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">
												<h4>福岡空港店</h4>
												<p>Tel. 092-260-9506</p>
												<p class="fs12"><営業時間> 9：00～19：30 <span style="font-size:1.2rem; padding:3px 10px; background:#e20001; color:#fff;margin-left: 10px; font-weight:500;-webkit-border-radius: 3px !important;-moz-border-radius: 3px !important;border-radius: 3px !important;">年中無休</span></p>
											</div>
											<div class="col-md-5 col-sm-5 col-xs-12">
												<h4>那覇空港店</h4>
												<p>Tel. 098-851-4291</p>
												<p class="fs12"><営業時間> 9：00～19：30 <span style="font-size:1.2rem; padding:3px 10px; background:#e20001; color:#fff;margin-left: 10px; font-weight:500;-webkit-border-radius: 3px !important;-moz-border-radius: 3px !important;border-radius: 3px !important;">年中無休</span></p>
											</div>
										</div>
										<h3>お問い合わせフォーム</h3>

										<form id="business-contact-form" enctype="multipart/form-data" method="post" action="contactFormBusiness.php">
											<table class="table-res-form">
												<tbody>
													<tr>
														<th>
															企業・団体名 <span class="req01">必須</span>
														</th>
														<td>
															<input type="text" name="company" id="company" size="40" placeholder="企業・団体名をご入力ください。" value="" class="form-control h40" >
															<span class="error-class errorcompany"></span>
														</td>
													</tr>
													<tr>
														<th>
															部署名
														</th>
														<td>
															<input type="text" name="branch" id="branch" size="40" placeholder="部署名をご入力ください。" value="" class="form-control h40" >
															<span class="error-class errorbranch"></span>
														</td>
													</tr>
													<tr>
														<th>
															お名前 <span class="req01">必須</span>
														</th>
														<td>
															<input type="text" name="name" id="name" size="40" placeholder=" 例） 箱 錬太郎" value="" class="form-control h40" >
															<span class="error-class errorname"></span>
														</td>
													</tr>
													<tr>
														<th>
															ふりがな <span class="req01">必須</span>
														</th>
														<td>
															<input type="text" name="furi_name" id="furi_name" size="40" placeholder="全角ひらがなでご入力ください。" value="" class="form-control h40" >
															<span class="error-class errorfuri_name"></span>
														</td>
													</tr>

													<tr>
														<th>
															メールアドレス <span class="req01">必須</span>
														</th>
														<td>
																	<input type="email" name="email" value="" class="form-control h40" size="40" placeholder=" 例） hakorentarou@exsample.com">
															<span class="error-class erroremail"></span>
														</td>
													</tr>

													<tr>
														<th>
															電話番号 <span class="req01">必須</span>
														</th>
														<td>
																	<input type="tel" name="tel" value="" class="form-control h40" size="40" placeholder=" 電話にてご回答する場合もございます。">
																	<span class="error-class errortel"></span>
														</td>
													</tr>

													<tr>
														<th>
															ご利用（予定）店舗 <span class="req01">必須</span>
														</th>
														<td>
															<input type="radio" value="福岡空港店" name="radio_shop" class="" id="radio-fukuoka" checked>
															<label for="radio-fukuoka">福岡空港店</label>
															<input type="radio" name="radio_shop" class="" value="那覇空港店" id="radio-naha">
															<label for="radio-naha">那覇空港店</label>
														</td>
													</tr>

													<tr>
														<th>
															ご検討中の車両クラス　<span class="req01">必須</span>
														</th>
														<td>
															<select name="fukuoka-class" class="form-control" id="fukuoka-classes">
																<option value="">福岡空港店の車両クラスをご選択ください。</option>
																{{--
																@foreach($fukuoka_classes as $fclass)																
																<option value="{{ $fclass->name }}">{{ $fclass->name }}</option>
																@endforeach
																--}}
																<option value="CW2">CW2</option>
																<option value="K1-NBOX">K1-NBOX</option>
																<option value="HG200">HG200</option>
																<option value="W1">W1</option>
																<option value="W2">W2</option>
																<option value="WE">WE</option>
																<option value="WD">WD</option>
																<option value="CW3">CW3</option>
																<option value="W3">W3</option>
																<option value="CW3H">CW3H</option>
																<option value="HG100">HG100</option>
																<option value="HW">HW</option>
																<option value="V4">V4</option>
																<option value="V1">V1</option>
																<option value="V3">V3</option>
																<option value="C2">C2</option>
																<option value="K1">K1</option>
																<option value="HSL">HSL</option>	
																<option value="C1">C1</option>	
																<option value="CP">CP</option>	
																<option value="F-W">F-W</option>	
																<option value="MB">MB</option>	
																<option value="レクサス LS460 前期">レクサス LS460 前期</option>	
																<option value="レクサス LS460 後期">レクサス LS460 後期</option>
															</select>
															
															<select name="naha-class" class="form-control" id="naha-classes" style="display:none;">
																<option value="">那覇空港店の車両クラスをご選択ください。</option>
																<option value="SWO">SWO</option>
																<option value="HGO200">HGO200</option>
																<option value="WSPO">WSPO</option>
																<option value="WEO">WEO</option>
																<option value="WO1">WO1</option>
																<option value="WO3">WO3</option>
																<option value="SWO80">SWO80</option>
																<option value="CPO">CPO</option>
																<option value="HGO100">HGO100</option>
																<option value="HWO">HWO</option>
																<option value="HVO">HVO</option>
																<option value="SSPO">SSPO</option>
															</select>
														</td>
													</tr>
													<tr>
														<th>
															ご希望開始日 <span class="req01">必須</span>
														</th>
														<td>
															<input type="text" name="start_date" id="start_date" size="40" placeholder="ご希望の開始日をご入力ください。" value="" class="form-control h40" >
															<span class="error-class errorstart_date"></span>
														</td>
													</tr>
													<tr>
														<th>
															ご利用期間　<span class="req01">必須</span>
														</th>
														<td>
															<select name="period" class="form-control">
																<option value="">ご利用期間をご選択ください。</option>
																<option value="1週間～4週間未満">1週間～4週間未満</option>
																<option value="1ヶ月～2ヶ月未満">1ヶ月～2ヶ月未満</option>
																<option value="2ヶ月～3ヶ月未満">2ヶ月～3ヶ月未満</option>
																<option value="3ヶ月～4ヶ月未満">3ヶ月～4ヶ月未満</option>
																<option value="4ヶ月～5ヶ月未満">4ヶ月～5ヶ月未満</option>
																<option value="5ヶ月～6ヶ月未満">5ヶ月～6ヶ月未満</option>
																<option value="6ヶ月～12ヶ月未満">6ヶ月～12ヶ月未満</option>
																<option value="12ヶ月以上">12ヶ月以上</option>
															</select>
															<span class="error-class errorperiod"></span>
															
														</td>
													</tr>
													
													<tr>
														<th>
															ご利用台数　<span class="req01">必須</span>
														</th>
														<td>
															<select name="number" class="form-control">
																<option value="">ご利用台数をご選択ください。</option>
																<option value="1台">1台</option>
																<option value="2台">2台</option>
																<option value="3台">3台</option>
																<option value="4台">4台</option>
																<option value="5台">5台</option>
																<option value="6台">6台</option>
																<option value="7台">7台</option>
																<option value="8台">8台</option>
																<option value="9台">9台</option>
																<option value="10台">10台</option>
																<option value="11台">11台</option>
																<option value="12台">12台</option>
																<option value="13台">13台</option>
																<option value="14台">14台</option>
																<option value="15台">15台</option>
																<option value="16台">16台</option>
																<option value="17台">17台</option>
																<option value="18台">18台</option>
																<option value="19台">19台</option>
																<option value="20台">20台</option>
																<option value="21台">21台</option>
																<option value="22台">22台</option>
																<option value="23台">23台</option>
																<option value="24台">24台</option>
																<option value="25台">25台</option>
																<option value="26台">26台</option>
																<option value="27台">27台</option>
																<option value="28台">28台</option>
																<option value="29台">29台</option>
																<option value="30台">30台</option>
																<option value="30台以上">30台以上</option>					
															</select>
															<span class="error-class errornumber"></span>
														</td>
													</tr>
													<tr>
														<th>
															お問い合わせ内容 <span class="req01">必須</span>
														</th>
														<td>
																<textarea name="message" cols="50" class="form-control h40" rows="5"></textarea>
																<span class="error-class errormessage"></span>
														</td>
													</tr>

												</tbody>
											</table>
											<input type="submit" name="submit" class="submitBtn" value="入力内容を送信する">
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
                            <a href="#" class="bg-carico totop-link">ページトップへ</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
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
<script>
$(function(){
	$( 'input[name="radio_shop"]:radio' ).change( function() {
		var radioval = $(this).val();
		if(radioval == '福岡空港店'){
		$('#naha-classes').hide();
		$('#naha-classes').val('');
		$('#fukuoka-classes').show();
		}else if(radioval == '那覇空港店'){
		$('#fukuoka-classes').hide();
		$('#fukuoka-classes').val('');
		$('#naha-classes').show();
		}
	}); 
});
</script>
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
            company: { required: true},
            name: { required: true },
            furi_name: { required: true, katakana: true },
            email: { required: true, email: true },
            tel: { required: true, number: true, minlength: 9, maxlength: 11 },
            radio_shop: { required: true},
            start_date: { required: true },
            period: { required: true },
            number: { required: true},
            message: { required: true}
        };
	$('#business-contact-form').validate({
		errorElement : 'span',
		errorPlacement: function(error, element) {
			var eP = $(".error"+element.attr("name"));
			error.appendTo(eP);
		},

		rules: validate_rule,
		messages: {
			name: {
				required: jQuery.validator.format("名前を入力してください"),
			},
			furi_name: {
				required: jQuery.validator.format("名前を入力してください"),
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
	jQuery.validator.addMethod("hirag", function(value, element) {
	 return this.optional(element) || /^([ァ-ヶー]+)$/.test(value);
	 }, "全角カタカナを入力下さい"
	);
</script>
<script src="{{URL::to('/')}}/js/plugins/kana/jquery.autoKana.js" type="text/javascript"></script>
@endsection
