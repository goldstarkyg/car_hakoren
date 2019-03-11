@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_campaign.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/js/slick/slick.css">
	<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/js/slick/slick-theme.css">
	<style type="text/css">
		html, body {
			margin: 0;
			padding: 0;
		}

		* { box-sizing: border-box; }

		.slider { width: 100%; }
		.slick-slide { margin: 0 20px; }
		.slick-slide img { width: 100%; }
		.slick-prev, .slick-next{z-index:1;}
		.slick-prev:before, .slick-next:before { color: black; }
		.slick-slide {
			transition: all ease-in-out .3s;
			opacity: .2;
		}

		.slick-active { opacity: .5; }
		.s-link { color:#0a568c; }
		.s-link:hover { color:#00acee; }

		.slick-current { opacity: 1; }
		@media (max-width: 767px){
		.slick-prev:before,
		.slick-next:before {display:none;}
		.slider{margin:0;}
		}
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
				<li>
					<span>サマーキャンペーン2018</span>
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
	<div class="page-content carclassdetail slider_overflow">
		<div class="container-full" >
			<div class="container" >
				<div class="row">
					<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<!-- box 1 -->
						<div class="p0">
							<!-- section 1 -->
							<div class="row">
								<div class="col-md-12">
									<img class="center-block img-responsive" src="{{URL::to('/')}}/img/pages/campaign/summer_campaign_top.jpg" alt="">
								</div>
							</div>
							<!-- section 1 -->
						</div>
						<div class="box-shadow paddingbox ">
							<div class="clearfix margin-bottom-40">
								<div class="col-lg-12  col-md-12 pxs0">
									<p class="campaign-txt">各温泉地までには是非立ち寄りたい飲食店や土産物店がたくさん！<br>移動中に気になる場所を見つけたらふらっと寄り道し、目的地への移動間も存分に満喫するのが旅上級者の楽しみ方！</p>
									<p class="campaign-txt">限られた時間のなかで多くの観光スポットを訪れるなら、公共交通機関よりもだんぜんレンタカー！<br>
バスや電車だと時間のロスを避けることはできないし、観光ツアーだと自分のペースで行動できません。</p>
									<p class="campaign-txt">
レンタカーなら温泉地までの時間を約2倍も短縮できちゃいます！<br>
旅行中は普段話さないひととじっくり話したり、普段話せないようなことを話したりするのも旅の楽しみ。<br>
ハコレンで大きなプライベート空間をレンタルして旅の楽しみを最大限に引き出してみよう！</p>
									<!-- search -->
									<div class="p-block search-block bg-darkred carclass_search">
										<h3>見積もりとスピード予約</h3>
										<div class="search-block-cont">
											<form method="POST" name="search" id="search" action="{{URL::to('/')}}/carclass-detail/" accept-charset="UTF-8" role="form" class="form-horizontal" enctype="multipart/form-data">
												{!! csrf_field() !!}
												<input type="hidden" id="class_id" name="class_id" value="">
												<input type="hidden" name="search_cond" id="search_cond" value="">
												<table class="table search-tbl">
													<tr>
														<th><span class="cond-ttl bg-darkred">出発条件</span></th>
														<td>
															<div class="row">
																<div class="col-md-4">
																	<div id="depart-datepicker" class="input-group date">
																		<input type="text" id="depart_date" name="depart_date" readonly value="" class="form-control datetime-select" required>
																		<div class="input-group-addon">
																			<span class="glyphicon glyphicon-th"></span>
																		</div>
																	</div>
																</div>
																<div class="col-md-4" >
																	<select class="chosen-select form-control selectpicker datetime-select" name="depart_time" id="depart_time" required>
																		@foreach($hour as $h)
																			<option value="{{$h}}"
																				@if(date('H:i') > date($h)) disabled @endif
																			>{{$h}} </option>
																		@endforeach
																	</select>
																</div>
																<div class="col-md-4 hidden">
																	<select class="form-control slct-3" name="pickup_id" id="pickup_id">
																		@foreach($shops as $shop)
																			<option value="{{$shop->id}}"   >{{$shop->name}}</option>
																		@endforeach
																	</select>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<th><span class="cond-ttl bg-darkred">返却条件</span></th>
														<td>
															<div class="row">
																<div class="col-md-4">
																	<div id="return-datepicker" class="input-group date">
																		<input type="text" id="return_date" name="return_date" readonly class="form-control datetime-select" value="" required>
																		<div class="input-group-addon">
																			<span class="glyphicon glyphicon-th"></span>
																		</div>
																	</div>
																</div>
																<div class="col-md-4" >
																	<select class="chosen-select form-control selectpicker datetime-select" name="return_time" id="return_time" required>
																		@foreach($hour as $h)
																			<option value="{{$h}}" >{{$h}} </option>
																		@endforeach
																	</select>
																</div>
																<div class="col-md-4 hidden">
																	<select class="form-control slct-3" name="dropoff_id" id="dropoff_id">
																		@foreach($shops as $shop)
																			<option value="{{$shop->id}}" >{{$shop->name}}</option>
																		@endforeach
																	</select>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<th><span class="cond-ttl bg-darkred">禁煙/喫煙</span></th>
														<td>
															<select id="smoking" name="smoking" class="form-control slct-3" style="margin-right: 5px;">
																<option value="both" >どちらでもいい</option>
																<option value="1" >喫煙</option>
																<option value="0" >禁煙</option>
															</select>
															<select name="insurance" class="form-control slct-3 hidden">
																<option value="0" selected>免責不要</option>
																<option value="1">免責補償</option>
																<option value="2">ワイド補償</option>
															</select>
														</td>
													</tr>

													<tr>
														<th><span class="cond-ttl bg-darkred">最大乗車人数</span></th>
														<td id="passenger_block" style="vertical-align: middle;">
															<b>人乗り車両</b>
															<input type="hidden" name="car_passenger" class="car_passenger" value="">
															<select name="max_passenger" class="form-control slct-3" style="margin-right: 5px;">
																<option value="" >人乗り</option>
															</select>
														</td>
													</tr>
													<tr>
														<th><span class="cond-ttl bg-darkred">オプション</span></th>
														<td>
															<div class="row option_ui" style="margin-left: 5px;">
																
															</div>
														</td>
													</tr>
												</table>
											</form>
										</div>
										<div class="clearfix bg-white calc-btn">
											<a id="btn-search" class="btn bg-grad-red" onclick="search_submit()" >見積もりを見る</a>
										</div>
									</div>
									<!-- search -->

									<div id="showinform"> </div>
									{{--
									<div class="p-block result-block bg-darkred search_cond">
										<h3>お見積もり内容</h3>
										<div class="result-block-cont">
											<div class="row m_T20 text-center">
												[ ご指定の条件で空車が見つかりませんでした ]
											</div>
										</div>

									</div>
									--}}
									<!-- result -->

									
								</div>
								<!-- ROW -->
							</div>
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
						<div class="col-md-12 clearfix sign_wrap">
							<a href="#" class="bg-carico totop-link">ページトップへ</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end search -->
	</div>
</div>
<!-- END CONTENT -->
		
    </div>

   

@endsection


@section('footer_scripts')
	<style>
		.datepicker {
			background: #fff;
			border: 1px solid #555;
		}
		.chosen-container-multi .chosen-choices{
			border: 1px solid #dedede;
			background-image:none;
		}
		.chosen-results {
			height: 120px !important; ;
		}
		.chosen-container-single .chosen-single{
			height: 34px !important;
		}
	</style>

	<script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
	<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script src="{{URL::to('/')}}/js/slick/slick.js" type="text/javascript" charset="utf-8"></script>
	
@endsection

@section('og_tags')
@endsection
