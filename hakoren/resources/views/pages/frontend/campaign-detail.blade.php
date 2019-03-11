@extends('layouts.frontend')

@section('template_title')
	キャンペーン
@endsection

@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_campaign_detail.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
	<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/js/slick/slick.css">
	<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/js/slick/slick-theme.css">

    <style>
		.block-head {
			color: white;
			font-size: 24px;
			/* padding-left: 80px; */
			padding-top: 20px;
			text-align: center;
		}
		.block-head-time {
			position: absolute;
			background: white;
			padding: 5px 10px;
			border-radius: 15px !important;
			top: 10px;
		}
		#searchBtn {
			background-color: lightgreen;
			color: orangered;
			font-weight: bold;
			margin-left: 25px;
			font-size: 1em;
			border: 2px solid;		}
    </style>
@endsection
@section('content')
	<script>
		{{--@if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)--}}

        {{--dataLayer.push({--}}
            {{--'ecommerce': {--}}
                {{--'currencyCode': 'JPY',--}}
                {{--'impressions': [--}}
					{{--@foreach($classes as $key=>$cls)--}}
                    {{--{--}}
                        {{--'name': '{{ $cls->name }}',--}}
                        {{--'id': '{{ $cls->class_id }}',--}}
                        {{--'price': '{{ $cls->price }}',--}}
                        {{--'brand': '{{ $cls->abbriviation }}',--}}
                        {{--'list': 'Shop {{ $shop->region_code }}',--}}
                        {{--'position': {{ $key + 1 }}--}}
                    {{--},--}}
					{{--@endforeach--}}
                {{--]--}}
            {{--}--}}
        {{--});--}}
        {{--@endif--}}
	</script>

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
                            <span>キャンペーンの情報</span>
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
							<h1>{{ $shop->name }}のキャンペーン情報</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container shopdetail">
                    <div class="row">
                        <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- box 1 -->
							<div class="box-shadow shopmain_wrap">
								<!-- section 1 -->
								<div class="row shopbox01">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h2 class="xsfs24">{{date('n月j日', strtotime('tomorrow'))}}～{{date('n月j日', strtotime('today +14 days'))}}までのレンタル限定</h2>
										<img src="{{url('/img/campaign-top.png')}}" class="img-responsive center-block hidden-xs" style="margin-bottom: 10px;">
										<img src="{{url('/img/campaign-top-sp.png')}}" class="img-responsive center-block visible-xs-block" style="margin-bottom: 10px;">
										<img src="{{url('/img/campaign-top02.png')}}" class="img-responsive center-block" style="margin-bottom: 10px;">

										<h3 class="mdpl50"> {{$shop->name}} | スーパーセールに該当する商品は以下です
											@if($shop->region_code == 'Fukuoka')
												<a href="{{url('/campaign/Okinawa/1')}}" class="pull-right" style="font-size:15px;padding-top: 15px;">沖縄のスーパーセールはこちら</a>
											@else
												<a href="{{url('/campaign/Fukuoka/1')}}" class="pull-right" style="font-size:15px;padding-top: 15px;">福岡のスーパーセールはこちら</a>
											@endif
										</h3>
									</div>
								</div>
							</div>
						</div>

						<input type="hidden" name="shop" id="shop" value="{{$shop->id}}">

						<div class="col-xs-12" style="margin-bottom: 20px; font-size: 20px;">
							{{ date('n月', strtotime('tomorrow')) }}
							<select id="start_date" name="start_date">
								@for($i = 0; $i < 14; $i++)
									<?php $t = strtotime('tomorrow +'.$i.' days'); ?>
									<option value="{{date('Y-m-d', $t)}}" @if(date('Y-m-d', $t) == $start_date) selected @endif>
										{{ date('j', $t) }}</option>
								@endfor
							</select>
							から{{ date('n月', strtotime('tomorrow + 13 days')) }}
							<select id="end_date" name="end_date">
								@for($i = 0; $i < 14; $i++)
									<?php $t = strtotime('tomorrow +'.$i.' days'); ?>
									<option value="{{ date('Y-m-d', $t) }}" @if(date('Y-m-d', $t) == $end_date) selected @endif>
										{{ date('j', strtotime('tomorrow +'.$i.' days')) }}</option>
								@endfor
							</select>
							までのキャンペーン商品を表示する
							<a class="btn" id="searchBtn">検索</a>
						</div>

						<div class="content-main row ribbon_main">
							@foreach($campaigns as $key => $camp)
								{{--<form action="{{url('/search-confirm')}}" method="post">--}}
{{--									{{csrf_field()}}--}}
								<div class="col-xs-12 col-md-6 camp-wrapper" data-date="{{$camp->date}}" camp-id="{{ $key }}">
									<div class="col-xs-12 camp-block pad0">
										<div class="col-xs-12" style="background: #cc0228;">
											<p class="block-head" style="color: white;font-weight: 600;">
												<small>&yen;&nbsp;</small>{{number_format($camp->original_price)}}&nbsp;&nbsp;
												&#x25B6;&nbsp;&nbsp;&yen;&nbsp;
												<span style="font-size: 2em">{{number_format($camp->rent_price)}}</span>
												<input type="hidden" name="rent_price" class="rent_price" value="{{$camp->rent_price}}" camp-id="{{$key}}">
											</p>
											<span class="block-head-time">
											出発：{{date('Y年n月j日', strtotime($camp->date))}}
											</span>
										</div>
										<!-- ROW -->
										<div class="col-xs-12">
											<div class="col-xs-12 img_slide mb10 mt10" >
												@foreach($camp->imgs as $img)
													<img data-lazy="{{url($img)}}" class="img-responsive center-block m_Txs60">
												@endforeach
											</div>
											<h4 >
												&emsp;<b>出発: {{date('n/j', strtotime($camp->date))}}</b>
												&emsp;<b>返却: {{date('n/j', strtotime($camp->date.' +'.($camp->vacancy -1).'days'))}}
												(@if($camp->vacancy == 1) 日帰り @else 1泊2日 @endif)</b>
											</h4>
											<input type="hidden" name="inventory" class="inventory" value="{{$camp->car}}" camp-id="{{$key}}">
											<input type="hidden" name="rent_dates" class="rent_dates" value="{{$camp->vacancy}}" camp-id="{{$key}}">
											<input type="hidden" name="depart_date" class="depart_date" value="{{$camp->date}}" camp-id="{{$key}}">
											<input type="hidden" name="return_date" class="return_date" value="{{date('Y-m-d', strtotime($camp->date.' +'.($camp->vacancy -1).'days'))}}" camp-id="{{$key}}">
											<div class="col-xs-12 mb10">
												<div style="width:50%;float: left">
													<select name="depart_time" class="depart_time" camp-id="{{$key}}">
														@foreach($hours as $hour)
															<option value="{{$hour}}">{{$hour}}</option>
														@endforeach
													</select>
													時から
												</div>
												<div style="width:50%;float: left">
													<select name="return_time" class="return_time" camp-id="{{$key}}">
														@foreach($hours as $hour)
															<option value="{{$hour}}" @if($hour == '17:00') selected @endif>{{$hour}}</option>
														@endforeach
													</select>
													時まで
												</div>
											</div>
											<div class="col-xs-12 text-right mb10">
												*この車は<b>{{($camp->smoke==1)? '喫煙車':'禁煙車'}}、{{$camp->max_passenger}}人乗り</b>です
												<input type="hidden" class="smoke" camp-id="{{$key}}" value="{{$camp->smoke}}">
												<input type="hidden" class="max_passenger" camp-id="{{$key}}" value="{{$camp->max_passenger}}">
												<input type="hidden" class="model_id" camp-id="{{$key}}" value="{{$camp->model_id}}">
												<input type="hidden" class="class_id" camp-id="{{$key}}" value="{{$camp->class_id}}">
											</div>
											<div class="btn-block pull-left mb10">
												<span class="btn btn-block btn-default btn-option" camp-id="{{$key}}">オプションを追加する</span>
											</div>
											<div class="option-wrapper mb10" camp-id="{{$key}}" style="display: none">
												<div class="col-xs-12">
													<p class="mb10">
														<span style="padding: 5px;border-radius: 7px;border:1px solid;background:#fffce9;">オプション</span>
													</p>
													@foreach($camp->paid_options as $popt)
													<div class="col-xs-12">
														<label class="chk_label btn-block" style="" >
															<i class="fa fa-check-circle" style="display: none"></i>
															<input type="checkbox" name="paid_options[]" style="display:none;"
																   value="{{$popt->id}}" price="{{$popt->price}}"
																   charge="{{$popt->charge_system}}" index="{{$popt->google_column_number}}"
																   class="paid_option" camp-id="{{$key}}">
															&nbsp;&nbsp;{{$popt->name}}

														</label>
														<select class="pull-right paid_opt_num @if($popt->max_number==1) hidden @endif" name="paid_opt_nums[]" camp-id="{{$key}}" style="display: none">
															@for($k = 1; $k <= $popt->max_number; $k++)
																<option value="{{$k}}">{{$k}}</option>
															@endfor
														</select>
													</div>
													@endforeach
												</div>
												@if(count($camp->free_options)> 0)
													<div class="col-xs-12">
														<p><span style="padding: 5px;border-radius: 7px;border:1px solid;background:#fffce9;">無料オプション</span></p>
														@foreach($camp->free_options as $fopt)
														<div class="col-xs-12">
															<label style="" class="chk_label">
																<i class="fa fa-check-circle" style="display: none"></i>
																<input type="checkbox" name="free_options[]" class="pickup" style="display:none;" value="{{$fopt->id}}" camp-id="{{$key}}" >
																&nbsp;&nbsp;{{$fopt->name}}
															</label>
														</div>
														@endforeach
													</div>
												@endif
											</div>
											<div class="btn-block pull-left mb10">
												<span class="btn btn-blcok btn-default btn-insurance" camp-id="{{$key}}">免責保償を追加する</span>
											</div>
											<div class="insurance-wrapper mb10" camp-id="{{$key}}" style="display: none">
												<input type="hidden" name="insurance1" class="insurance1" value="{{$camp->insurances['ins1']}}" camp-id="{{$key}}">
												<input type="hidden" name="insurance2" class="insurance2" value="{{$camp->insurances['ins2']}}" camp-id="{{$key}}">
												<select name="insurance" class="insurance mb10" camp-id="{{$key}}">
													<option value="0" selected>免責は不要</option>
													<option value="1">免責補償を付ける</option>
													<option value="2">【お勧め】ワイド免責補償を付ける</option>
												</select>
												<br>
												<a data-toggle="modal" href="#insurance_info_modal" class="added-txt mb10" data-target="#insurance_info_modal" style="background: #ffffcc;color: #333;">
													安心 免責補償とは？
													{{--免責補償が追加されています。 <i class="fa fa-question-circle" style="color:#4c4c4c;"></i>--}}
												</a>
											</div>
											<h4 class="mb10 text-right">
												オプション料金：<span class="option_price" camp-id="{{$key}}">0</span>円
											</h4>
											<h4 class="mb10 text-right">
												合計金額: <span class="total_price" camp-id="{{$key}}">{{number_format($camp->rent_price)}}</span>円
											</h4>
											<div class="col-xs-12 text-center">
												<a class="btn-submit" onclick="openSubmitForm({{$key}})" style="display: inline-block;">この車を予約する</a>
											</div>
										</div>
										<!-- ROW -->
									</div>
								</div>
								{{--</form>--}}
							@endforeach
						</div>

						<div class="row text-center">
							<ul class="pagination">
								<li class="page-item @if($page==1) disabled @endif">
									<a class="page-link"
									   @if($page > 1)
									   href="{{url('/campaign/'.$shop->region_code.'/'.($page-1).'?start='.$start_date.'&end='.$end_date)}}"
									   @endif>前
									</a>
								</li>
								@php
								$pageCount = ceil($camp_count / 10);
								@endphp
								@for($k = 1; $k <= $pageCount; $k++)
								<li class="page-item @if($k == $page) active @endif">
									<a class="page-link"
									   href="{{url('/campaign/'.$shop->region_code.'/'.$k.'?start='.$start_date.'&end='.$end_date)}}">{{ $k }}</a>
								</li>
								@endfor
								<li class="page-item @if($page==$pageCount) disabled @endif">
									<a class="page-link"
										@if($page < $pageCount)
									   	href="{{url('/campaign/'.$shop->region_code.'/'.($page + 1).'?start='.$start_date.'&end='.$end_date)}}"
										@endif>次</a>
								</li>
							</ul>
						</div>

						<input type="hidden" name="current_camp" id="current_camp">

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
        </div>
    </div>

	<div class="modal fade in" id="insurance_info_modal" tabindex="-1" style="padding-right: 16px;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<i class="fa fa-close pull-right" style="cursor:pointer; padding:10px;" data-dismiss="modal"></i>
					<img class="center-block img-responsive" src="{{ URL::to('/img/pages/insurance/hosyou_hoken12.png') }}" alt="免責補償とは？">
					<p>ワンボックス車は運転しやすいですが、万が一擦り傷やヘコミがあった場合、塗装・修理代が発生致します。ワイド免責補償にご加入されますと、条件内ではご負担が0円となります。</p>
					<p>※免責補償、ワイド免責補償のご加入はご予約詳細のドロップダウンで選択・変更が可能です。</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade in" id="user-inform-modal" tabindex="-1" >
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="border-bottom: none;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body row">
					<div class="col-xs-12 reserve-right">
						<!-- <label for="check-member" style="position: absolute; right: 0; top: -15px; background: #ef8385; color: white; padding: 5px 10px;" id="lbl-member-check"> 会員様ご予約 </label> -->
						<input type="checkbox" id="check-member" style="display: none;">
						<div id="reserve-right-form-section" style="display: none">
							<p class="reserve-right-ttl m0">ご予約フォーム</p>
							<button style="border-style: none" id="return-select-member" class="btn btn-danger pull-right">戻る</button>
							<p class="reserve-right-ttl-small"><small>ご予約と会員登録は同時に行われます。</small></p>
							{!! csrf_field() !!}
							<form id="reserve-right-form">
								<div class="row">
									<div class="col-xs-12">
										<label>メールアドレス<span class="req-red">必須</span></label>
										<input type="text" name="email" id="email" placeholder="メールアドレス" class="form-control h40" style="margin-bottom:5px;">
										<div class="error-box col-md-12 col-sm-12 col-xs-12">
											<span class="error-class erroremail"></span>
										</div>
									</div>
								</div>
								<div class="col-xs-12 only-nonmember" style="padding: 0; margin-bottom:0px;">
									<label class="col-xs-12" style="padding: 0px 0 3px; margin-bottom:3px;">お名前<span class="req-red">必須</span></label>
									<div class="col-xs-12" style="padding: 0; margin-bottom:0px;">
										<div class="col-xs-6" style="padding: 0 5px 0 0">
											<input type="text" name="last_name" id="last_name" placeholder="姓" class="form-control h40" >
											<span class="error-class errorlast_name"></span>
										</div>
										<div class="col-xs-6" style="padding: 0 0 0 5px">
											<input type="text" name="first_name" id="first_name" placeholder="名" class="form-control h40" >
											<span class="error-class errorfirst_name"></span>
										</div>
									</div>
								</div>
								<div class="col-xs-12 only-nonmember" style="padding: 0; margin-bottom:10px;">
									<label class="col-xs-12" style="padding: 4px 0 3px; margin-bottom:3px;">フリガナ<span class="req-red">必須</span></label>
									<div class="col-xs-12" style="padding: 0">
										<div class="col-xs-6" style="padding: 0 5px 0 0">
											<input type="text" name="furi_last_name" id="furi_last_name" placeholder="セイ" class="form-control h40" >
											<span class="error-class errorfuri_last_name"></span> </div>
										<div class="col-xs-6" style="padding: 0 0 0 5px">
											<input type="text" name="furi_first_name" id="furi_first_name" placeholder="メイ" class="form-control h40" >
											<span class="error-class errorfuri_first_name"></span> </div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 only-nonmember">
										<label>電話番号<span class="req-red">必須</span></label>
										<input type="text" name="phone" id="phone" placeholder="ハイフン(-)なしでご入力ください" class="form-control h40">
										<div class="error-box col-md-12 col-sm-12 col-xs-12"> <span class="error-class errorphone"></span> </div>
									</div>
								</div>
								<div class="row password-already-member">
									<div class="col-xs-12">
										<label>パスワード<span class="req-red">必須</span></label>
										<input type="password" name="password" id="password" placeholder="パスワードをご入力ください" class="form-control h40">
										<div class="error-box col-md-12 col-sm-12 col-xs-12"> <span class="error-class errorpassword"></span> </div>
									</div>
								</div>

								<div id="forgot_password">
									<a href="{{URL::to('/')}}/password/reset" style="margin-top:7px;float:left;color: #337ab7;">パスワードを再発行する</a>
								</div>
								<label for="check-it" class="check-change only-nonmember">
									<input type="checkbox" name="accept-check" id="check-it" checked>
									<a data-toggle="modal" data-target="#myModal" href="#myModal" style="color: #337ab7;">会員規約</a>に同意する </label>
								<input type="submit" name="submit" class="submitBtn form-control h40 bg-grad-red" value="予約する" style="color: #fff;">
								<p class="only-nonmember m_B0">ご入力いただいたアドレスにメールをお送りします。</p>


							</form>
						</div>

						<div class="btn-main" id="btn-select-member">
							<div class="row text-center">
								<img src="{{url('img/pages/campaign/book-now02.jpg')}}" style="width: 70%;">
							</div>
							<div class="row text-center">
								<button id="btn-new-member" class="btn-wrap btn-warning">初めてのご予約</button>
							</div>
							<div class="row text-center">
								<button id="btn-existing-member" class="btn-wrap btn-warning" style="margin-bottom:25px;">会員様ご予約</button>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
    @include('modals.modal-membership')
    @include('modals.modal-error')
@endsection

@section('footer_scripts')
	<style>
		.paid_opt_num {position: absolute; right: 25px; top: 5px;}
		.reserve-right-ttl{ text-align:center; font-size:1.4em; font-weight:500; margin-top:0;}
		.req-red{
			padding: .2em .5em .3em;
			margin-left:8px;
			display:inline-block;
			border-radius: 5px!important;
			-webkit-border-radius: 5px!important;
			-moz-border-radius: 5px!important;
			background:#ff5912; color:#fff; font-size:9px;
		}
		.error-class span.error{ border:none!important;}
		.error-class span.error:before{
			content: "\f06a";
			font-family: FontAwesome; padding-right:5px;
		}
		#lbl-member-check { position: absolute; right: 0; top: -15px; background: #ef8385; color: white; padding: 5px 10px; }
		.car-type-td span:last-child{display:none;}
		.reserve-tbl td{text-align:right;}

		.btn-wrap{
			background: #ffd2c7;
			color: #422121;
			padding: 20px 10px;
			width: 60%;
			display: inline-block;
			text-align: center;
			border-radius: 10px !important;
			margin-top: 30px;
			font-size: 20px;
			font-weight: 600;
		}
		.chk_label {
			padding: 5px 10px;
			background: #ddd;
			border-radius: 10px !important;
			line-height: 1.7;
		}
		.camp-block {
			box-shadow: 2px 2px 3px #ddd;
			background: white;
			margin-bottom: 30px;
		}
		.pad0 { padding: 0;}
		.mb10 { margin-bottom: 10px; }
		.mt10 { margin-top: 10px; }
		.btn-submit {
			padding:10px;
			border-radius:20px !important;
			border:2px solid #363198;
			color:#363198;
			margin-bottom: 20px;
			background: white;
		}
		.slick-prev:before, .slick-next:before {
			color : black !important;
		}
		.btn-option, .btn-insurance {
			border: none;
			padding: 10px;
			border-radius: 5px !important;
			width: 100%;
			background-color: #e5e5e5;
		}
		.btn-option:after, .btn-insurance:after {
			content: "\f107";
			font: normal normal normal 14px/1 FontAwesome;
			float: right;
			line-height: 18px;
		}
		.box-shadow {
			-webkit-box-shadow: 2px 1px 3px 1px rgba(0,0,0,0.1);
			-moz-box-shadow: 2px 1px 3px 1px rgba(0,0,0,0.1);
			box-shadow: 2px 1px 3px 1px rgba(0,0,0,0.1);
			padding: 10px;
			margin-bottom: 30px;
		}
		.ribbon-block {
			position: absolute;
			top: 10px;
			left: -10px;
			width: 50%;
			height: auto;
			color: #fff;
			padding: 8px;
		}
		.chk_label i{
			color: #009a43;
			font-size: 18px;
			vertical-align: middle;
			display: none;
		}

	</style>

	<script src="{{URL::to('/')}}/js/slick/slick.min.js"></script>
	<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
	<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
	<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/localization/messages_ja.js" type="text/javascript"></script>
	<script src="{{URL::to('/')}}/js/plugins/kana/jquery.autoKana.js" type="text/javascript"></script>

	<script>
        var validate_rule = {
            last_name: { required: true },
            first_name: { required: true },
            furi_last_name: { required: true, katakana: true },
            furi_first_name: { required: true, katakana: true },
            email: { required: true, email: true },
            password: { required: true },
            phone: { required: true, number: true, minlength: 9, maxlength: 11 }
        };

        $('#start_date').change( function () {
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            if(start < end) return;

            var opts = $('#end_date').find('option');
			var first = false;
            for(var i = 0; i < opts.length; i++) {
                var opt = $(opts[i]);

                if(opt.val() < start) {
                    opt.prop('disabled', true).hide();
                } else {
                    opt.removeAttr('disabled').show();
                    if(first == false) {
                        opt.parent().val(opt.val());
                        first = true;
					}
				}
			}
        });

        $('#searchBtn').click( function () {
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            location.href = '{{ url('/campaign/'.$shop->region_code.'/1') }}' + '?start='+ start + '&end='+end;

        })

        $(document).ready(function(){

            $('.img_slide').slick({
                lazyLoad: 'progressive',
                // infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                // autoplay: true,
                // autoplaySpeed: 3333000,
            });

        })

        function showModal(text) {
            $('#modalError p.error-text').html(text);
            $('#modalError').modal();
        }

		function openSubmitForm(camp_id) {
		    $('#current_camp').val(camp_id);
		    $('#user-inform-modal').modal('show');
		}

        $('#reserve-right-form').validate({
            errorElement : 'span',
            errorPlacement: function(error, element) {
                var eP = $(".error"+element.attr("name"));
                error.appendTo(eP);
            },

            rules: validate_rule,
            messages: {
                email: {
                    required: jQuery.validator.format("メールアドレスは必須項目です"),
                },
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
                phone: {
                    required: jQuery.validator.format("電話番号は必須項目です"),
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
        $(function() {
            $.fn.autoKana('#first_name', '#furi_first_name', {
                katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
            });
            $.fn.autoKana('#last_name', '#furi_last_name', {
                katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
            });
        });

        function check() {
			var camp_id = $('#current_camp').val();
			if(camp_id == '') return false;
			var sel = '[camp-id="'+ camp_id +'"]';
            var url = '{{url('/book_campaign')}}';
            var checked = $('#check-member').prop('checked') ? 1 : 0;

            var paids = $('.paid_option'+sel),
				option_ids = [],
				option_nums = [],
				option_indexes = [],
				option_costs = [];
            for(var i = 0; i < paids.length; i++) {
            	var opt = $(paids[i]);
            	if(opt.prop('checked')) {
            	    option_ids.push(opt.val());
            	    option_nums.push(opt.closest('div').find('select').val());
            	    option_costs.push(opt.attr('price'));
            	    option_indexes.push(opt.attr('index'));
				}
			}
			var pickups = $('.pickup' + sel),
				pickup = '';
            for(i = 0; i < pickups.length; i++) {
                var pk = $(pickups[i]);
                if(pk.prop('checked')){
                    pickup = pk.val();
				}
			}

            var data = {
                "_token" :'{{ csrf_token() }}',
                "email" : $('#email').val().trim(),
                "phone" : $('#phone').val().trim(),
                "password" : $('#password').val(),
				"inventory_id" : $('.inventory'+ sel).val(),
				"model_id" : $('.model_id'+ sel).val(),
				"class_id" : $('.class_id'+ sel).val(),
				"passenger" : $('.max_passenger'+ sel).val(),
                "depart_date" : $('.depart_date'+ sel).val(),
                "depart_time" : $('.depart_time'+ sel).val(),
                "return_date" : $('.return_date'+ sel).val(),
                "return_time" : $('.return_time'+ sel).val(),
                "depart_shop" : $('#shop').val(),
                {{--"depart_shop_name" : "{{ $data['depart_shop_name'] }}",--}}
                "return_shop" : $('#shop').val(),
                {{--"return_shop_name" : "{{ $data['return_shop_name'] }}",--}}
                "insurance" : $('.insurance'+ sel).val(),
                "insurance_price1" : $('.insurance1'+ sel).val(),
                "insurance_price2" : $('.insurance2'+ sel).val(),
                "smoke" : $('.smoke'+ sel).val(),
                {{--"class_id" : "{{ $data['class_id'] }}",--}}
                {{--"class_name" : "{{ $data['class_name'] }}",--}}
                {{--"class_category" : "{{ $data['class_category'] }}",--}}
                {{--"car_photo" : "{{ $data['car_photo'] }}",--}}
{{--                "rent_days" : "{{ $data['rent_days'] }}",--}}
                "price_rent" : $('.rent_price'+ sel).val(),
                "option_ids" : option_ids,
                "option_indexs" : option_indexes,
                {{--"option_names" : "{{ $data['option_names'] }}",--}}
                "option_numbers" : option_nums,
                "option_costs" : option_costs,
{{--                "option_prices" : '{{ $data['option_prices'] }}',--}}
{{--                "price_all" : "{{ $data['price_all'] }}",--}}
                "pickup" : pickup,
                "member_check" : checked,
                "rent_dates" : $('.rent_dates'+ sel).val(),
				"camp_id" : camp_id
            };
            if(checked === 0 ) { // when not member
                data['first_name'] = $('#first_name').val().trim();
                data['last_name'] = $('#last_name').val().trim();
                data['name'] = data.last_name + data.first_name;
                data['furi_first_name'] = $('#furi_first_name').val().trim();
                data['furi_last_name'] = $('#furi_last_name').val().trim();
            }

            $.ajax({
                url : url,
                data : data,
                type : 'post',
                success : function(result, status) {
                    console.log(result);
                    if(status == 'success'){
                        if(result.success == true){
                            var camp_id = result.camp_id;
                            $('.camp-wrapper[camp-id="'+ camp_id +'"]').css('display', 'none');
                            location.href = '{{URL::to('/')}}/thankyou';
                        } else {
                            console.log(result.errors);
                            var errors = result.errors,
                                errorsHtml = '<ul>';

                            $.each( errors, function( key, value ) {
                                errorsHtml += '<li>' + value + '</li>'; //showing only the first error.
                            });
                            errorsHtml += '</ul>';
                            showModal(errorsHtml);
                        }
                    } else {
                        showModal(result.error);
                    }
                },
                error : function(xhr, status, error) {
                    showModal(error);
                }
            })
        }
        // For existing member
        $("#btn-existing-member").click(function() {
            $('#check-it').attr('checked', false); // Unchecks it
            $('#check-member').prop('checked', true); // Unchecks it

            $('#lbl-member-check').text('非会員の予約');
            $('.reserve-right-ttl').text('会員様ご予約');
            $('.reserve-right-ttl-small').html('<small>ハコレンタカーをご利用したことがある方はコチラ！');
            $('#email').attr('placeholder', '会員登録時のメールアドレスをご入力ください');
            $('#phone').attr('placeholder', '会員登録時の電話番号をご入力ください');
            $('.submitbtn').val('予約へ進む');
            $('.only-nonmember').fadeOut();
            $('#btn-select-member').hide();
            $('#reserve-right-form-section').show();
            $('.password-already-member').show();
            $('#forgot_password').show();
            $(".submitBtn").prop('disabled', false);

            validate_rule = {
                email: { required: true, email: true },
                password: { required: true}
            };
        });
        // For New member
        $("#btn-new-member").click(function() {
            $('#check-member').prop('checked', false); // Unchecks it
            $('#check-it').prop('checked', true); // Unchecks it
            $('#lbl-member-check').text('会員様ご予約');
            $('.reserve-right-ttl').text('ご予約フォーム');
            $('.reserve-right-ttl-small').html('<small>ご予約と会員登録は同時に行われます。</small>');
            $('#email').attr('placeholder', 'メールアドレス');
            $('#phone').attr('placeholder', 'ハイフン(-)無しでご入力下さい');
            $('.submitbtn').val('送信する');
            $('.only-nonmember').fadeIn();
            $('#btn-select-member').hide();
            $('#reserve-right-form-section').show();
            $('.password-already-member').hide();
            $('#forgot_password').hide();
            //check submit button prop disabled
            if($memberCheck.prop('checked') === true){
                $(".submitBtn").prop('disabled', false);
            }
            else{
                $(".submitBtn").prop('disabled', true);
            }
            validate_rule = {
                last_name: { required: true },
                first_name: { required: true },
                furi_last_name: { required: true, katakana: true },
                furi_first_name: { required: true, katakana: true },
                email: { required: true, email: true },
                phone: { required: true, number: true, minlength: 9, maxlength: 11 }
            };
        });

        $("#return-select-member").click(function() {
            $('#btn-select-member').show();
            $('#reserve-right-form-section').hide();
            $("#reserve-right-form")[0].reset();
        });

		$('input[name="paid_options[]"]').change(function() {
		    if($(this).prop('checked')){
                $(this).closest('label').find('i').show();
                $(this).closest('div').find('select').val(1).show();
			}
		    else {
                $(this).closest('label').find('i').hide();
                $(this).closest('div').find('select').val(1).hide();
			}
			// process price
			var camp = $(this).attr('camp-id'),
		    	paid_price = calcOptionPrice(camp),
		    	rent_price = $('.rent_price[camp-id="'+ camp +'"]').val() * 1,
		    	total_price = rent_price + paid_price;
            $('.option_price[camp-id="'+ camp +'"]').text(paid_price.toLocaleString());
            $('.total_price[camp-id="'+ camp +'"]').text(total_price.toLocaleString());
		});

		$('.paid_opt_num').change(function () {
            var camp = $(this).attr('camp-id'),
                paid_price = calcOptionPrice(camp),
                rent_price = $('.rent_price[camp-id="'+ camp +'"]').val() * 1,
                total_price = rent_price + paid_price;
            $('.option_price[camp-id="'+ camp +'"]').text(paid_price.toLocaleString());
            $('.total_price[camp-id="'+ camp +'"]').text(total_price.toLocaleString());
        });

		$('.insurance').change(function() {
            var camp = $(this).attr('camp-id'),
                paid_price = calcOptionPrice(camp),
                rent_price = $('.rent_price[camp-id="'+ camp +'"]').val() * 1,
                total_price = rent_price + paid_price;
            $('.option_price[camp-id="'+ camp +'"]').text(paid_price.toLocaleString());
            $('.total_price[camp-id="'+ camp +'"]').text(total_price.toLocaleString());
		})

		$('.pickup').change(function () {
            var camp = $(this).attr('camp-id');
            var checked = $(this).prop('checked');

            if(checked == true){
                $('.pickup[camp-id="'+ camp +'"]')
					.prop('checked', false)
					.closest('label')
					.find('i').hide();
                $(this).prop('checked', checked)
					.closest('label')
					.find('i').show();
            }
            else {
                $(this).closest('label').find('i').hide();
            }
        });

		function calcOptionPrice(camp) {
            var all_paids = $('.paid_option[camp-id="'+ camp +'"]');
            var insurance = $('.insurance[camp-id="'+ camp +'"]').val();
            var ins_cost1 = $('.insurance1[camp-id="'+ camp +'"]').val();
            var ins_cost2 = $('.insurance2[camp-id="'+ camp +'"]').val();
            var rent_dates = $('.rent_dates[camp-id="'+ camp +'"]').val();
            var paid_price = 0;
            for(var i = 0; i < all_paids.length; i++) {
                var popt = $(all_paids[i]);
                if(popt.prop('checked')) {
                    var opt_num = popt.closest('div').find('select').val();
                    var charge_system = popt.attr('charge');
                    if(charge_system == 'one')
                    	paid_price += popt.attr('price') * opt_num;
                    else
                        paid_price += popt.attr('price') * opt_num * rent_dates;
                }
            }
            if(insurance == 0) {
                paid_price += 0;
			} else if(insurance == 1){
                paid_price += ins_cost1 * rent_dates;
			} else {
                paid_price += ins_cost1 * rent_dates + ins_cost2 * rent_dates;
			}

            return paid_price;
		}

		function scrollToSection(target) {
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 1000);
		}

		$('.btn-insurance').click(function(){
		    var camp_id = $(this).attr('camp-id');
		    $('.insurance-wrapper[camp-id="'+camp_id+'"]').fadeToggle();
		});

		$('.btn-option').click(function(){
            var camp_id = $(this).attr('camp-id');
            $('.option-wrapper[camp-id="'+camp_id+'"]').fadeToggle();
		});

		function gotoDetail(class_name, class_id, class_price, position){
			@if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)

            dataLayer.push({
                'event': 'productClick',
                'ecommerce': {
                    'click': {
                        'actionField': {'list': 'Shop {{ $shop->region_code }}'},
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
			@endif

            location.href = "{{URL::to('/')}}/carclass-detail/" + class_id;
        }

	</script>
@endsection
