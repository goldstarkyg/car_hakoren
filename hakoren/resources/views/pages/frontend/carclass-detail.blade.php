@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_carclass_detail.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
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
		.search-result-panel {
			border: 1px solid rgba(0, 0, 0, 0.2);
			border-top: 0.5em solid #b93332;
		}
		.bubble {
			position: relative;
			padding: 10px;
			background: #feaead;
			border-radius: 4px;
			border: #e99f9e solid 1px;
			font-size: 1em;
			line-height: 1.25em;
			text-align: center;
			/* height: 50px; */
			vertical-align: middle;
		}
		.bubble.many {
			position: relative;
			padding: 10px;
			background: #ccffb8;
			border-radius: 4px;
			border: #9de481 solid 1px;
			font-size: 1em;
			line-height: 1.25em;
			text-align: center;
			/* height: 50px; */
			vertical-align: middle;
		}
		.left {
			display: none;
		}
		.left.active {
			display: block;
		}
		.bubble.few {
			position: relative;
			padding: 10px;
			background: #fee2ad;
			border-radius: 4px;
		}
		.bubble.many {
			position: relative;
			padding: 10px;
			background: #ccffb8;
			border-radius: 4px;
			border: #9de481 solid 1px;
			font-size: 1em;
			line-height: 1.25em;
			text-align: center;
			/* height: 50px; */
			vertical-align: middle;
		}
		.result_shop {
			background-color: #fee8ea;
			padding: 0.6em;
			font-size: 12px;
			font-weight: 300;
			border-left: 7px solid #b69697;
			box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 3px 3px 0 rgba(0, 0, 0, 0.19);
		}
		.car_title {
			text-align: left;
			font-size: 2em;
			font-weight: bold;
		}
		.m_B20 {
			margin-bottom: 20px !important;
		}
		.m_T10 {
			margin-top: 10px !important;
		}
		.row-bordered-result {
			border-bottom: 1px dashed #e3e3e3;
			margin-top: 3px;
			margin-left: 0px !important;
			margin-right: 0px !important;
			margin-bottom: 3px;
			padding-bottom: 3px;
		}
		.bg-grad-red {
			background: -moz-linear-gradient(top, #ff0000 1%, #dd0000 50%, #dd0000 50%, #880000 100%);
			background: -webkit-linear-gradient(top, #ff0000 1%,#dd0000 50%,#dd0000 50%,#880000 100%);
			background: linear-gradient(to bottom, #ff0000 1%,#dd0000 50%,#dd0000 50%,#880000 100%);
			color: #ffffff;
		}
		.bubble.left:before {
			content: '';
			position: absolute;
			border-style: solid;
			border-width: 9px 9px 9px 0;
			border-color: transparent #e99f9e;
			display: block;
			width: 0;
			z-index: 0;
			left: -9px;
			top: 50%;
			margin-top: -10px;
		}
		.bubble_write {
			font-size: 23px;
			font-weight: 400;
			color: #b51b1b;
		}
		.block-booking { display: none; }
		.req-red {
			padding: .2em .5em .3em;
			margin-left: 8px;
			display: inline-block;
			border-radius: 5px!important;
			-webkit-border-radius: 5px!important;
			-moz-border-radius: 5px!important;
			background: #ff5912;
			color: #fff;
			font-size: 9px;
		}
		#validateBtn {
			text-align: center;
			padding: 8px;
			font-weight: 500;
			width: 260px;
			background:#8EC11E; color:#fff;
			-webkit-box-shadow: 0px 2px 3px 0px rgba(0,0,0,.4);
			-moz-box-shadow: 0px 2px 3px 0px rgba(0,0,0,.4);
			box-shadow: 0px 2px 3px 0px rgba(0,0,0,.4);
			border: none;
		}
		.error-class span.error:before {
			content: "\f06a";
			font-family: FontAwesome;
			padding-right: 5px;
		}
		.error-class {
			display: block;
			text-align: right;
			width: 100%;
			align-items: center;
			font-size: 12px;
			color: #ff5912;
		}
		#log-booking { width: 50%; }
		@media screen and (max-width: 425px) {
			.listing_wrap .label_manage {
				font-size: 10px;
				/*line-height: 10px;*/
			}
			.listing_wrap {
				margin-top: 10px;
			}
			#log-booking { width: 100%; }
		}
		@media (max-width: 767px){
			.page-container { }
			#xxx { font-size: 14px !important; }
		}
	</style>
@endsection
@inject('ClassPrice', 'App\Http\DataUtil\ServerPath')
@section('content')
<div class="page-container">
	@if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)
	<script>
        // Measure a view of product details.
        dataLayer.push({
            'ecommerce': {
                'detail': {
                    'products': [{
                        'name': '{{$class->name}}',         // Name or ID is required.
                        'id': '{{$class->id}}',
                        'price': '{{$class->basic_price}}',
                        // 'brand': 'エスティマ'
                    }]
                }
            }
        });

        @if($search_cond == '1' && !empty($suggest))
        dataLayer.push({
            'ecommerce': {
                'currencyCode': 'JPY',
                'impressions': [
                    @foreach($suggest as $key => $su)
                    {
                        'name'		: '{{ $su->class_name }}',  // This has to be generated dynamically
                        'id'		: '{{ $su->class_id }}', 		// This has to be generated dynamically
                        'price'		: '{{ $su->all_price }}',		// This has to be generated dynamically
                        'list'		: 'Suggested',	// Please name the “list’ as suggested
                        'position'	: '{{ $key + 1 }}'		// This has to be generated dynamically
                    },
					@endforeach
                ]
            }
        });
		@endif
	</script>
	@endif

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
					<span>レンタカークラス {{$class->name}}の詳細</span><!--{{trans('fs.current')}}-->
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
						<h1>レンタカークラス：{{$class->name}}の詳細</h1>
				</div>
			</div>
		</div>
	</div>
	<!-- begin search -->
	<div class="page-content carclassdetail slider_overflow">
		<div class="container">
			<div class="row">
				<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<!-- box 1 -->
					<div class="box-shadow paddingbox">
						<!-- section 1 -->
						<div class="row">
							<div class="col-md-12">
								<?php
								$min_psg = $class->passenger->min_passenger;
								$max_psg = $class->passenger->max_passenger;
								?>
								<?php
									$selected_shop = '';
									foreach($shops as $shop) {
										if($pickup_id == $shop->id) $selected_shop = $shop->name;
									}
									?>
								<h2 class="classtitle" style="margin-bottom: 20px;font-weight:600;">
								{{ $selected_shop }}のレンタカークラス&nbsp;{{$class->name}}&nbsp;<br/>対応車種:
									<?php $m = 0; ?>
									@foreach($class->models as $model)
										{{$model->name}} @if(count($class->models) > $m+1), @endif
										<?php $m++; ?>
									@endforeach
								<?php
								$capacity = ($min_psg != $max_psg)? $min_psg.'～'.$max_psg : $min_psg;
								if($class->name == 'W3' || $class->name == 'CW3H') $capacity = 7;
									?>
								/乗車人数:{{ $capacity }}名
								</h2>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-20 pxs0">
								<div class="row">
									<div class="col-md-12">
										<section class="model_list slider" >
											@if(empty($class->thumb_path))
												<img src="{{URL::to('/')}}/images/search_default.png" class="img-responsive center-block">
											@else
											<img src="{{URL::to('/')}}/{{$class->thumb_path}}" class="img-responsive center-block">
											@endif
											@foreach($class->thumbnails as $thumb)
												@if(!empty($thumb->thumb_path))
													<div>
														<img src="{{URL::to('/')}}/{{$thumb->thumb_path}}" class="img-responsive center-block" >
													</div>
												@endif
											@endforeach
										</section>
									</div>
								</div>
								<div class="clearfix" style="margin:5px 0 0 5px;">
									<div class="col-md-12">
										@if(empty($class->thumb_path))
											<img class="cardetail-thumbnail img-responsive center-block" src="{{URL::to('/')}}/images/search_default.png" width="40" onclick="javascript:$('.model_list').slick('slickGoTo',0)" style="cursor:pointer;">
										@else
											<img class="cardetail-thumbnail img-responsive center-block" src="{{URL::to('/')}}/{{$class->thumb_path}}" width="40" onclick="javascript:$('.model_list').slick('slickGoTo',0)" style="cursor:pointer;">
										@endif
										@foreach($class->thumbnails as $thumb)
											@if(!empty($thumb->thumb_path))
												<div>
													<img class="cardetail-thumbnail img-responsive center-block" src="{{URL::to('/')}}/{{$thumb->thumb_path}}"   width="40" onclick="javascript:$('.model_list').slick('slickGoTo',{!! $loop->iteration !!})" style="cursor:pointer;">
												</div>
											@endif
										@endforeach
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-top: 20px">

								<!-- 料金テーブル -->
								<table id="pricetable">
									<tr>
										<th><h3 class="classdetail-ttl" style="margin:0 !important;">基本料金</h3></th>
										<th class="headbackground">1日当たり</th>
										<th class="headbackground">合計</th>
									</tr>
									<tr>
										<td>当日返却</td>
										<td class="unitprice">{{number_format($ClassPrice->getPriceDayNight($class->id,'1','1'))}}円</td>
										<td>{{number_format($ClassPrice->getPriceDayNight($class->id,'1','1'))}}円</td>
									</tr>
									<tr>
										<td>1泊2日</td>
										<td class="unitprice">{{number_format(round($ClassPrice->getPriceDayNight($class->id,'1','2'))/2)}}円</td>
										<td>{{number_format($ClassPrice->getPriceDayNight($class->id,'1','2'))}}円</td>
									</tr>
									<tr>
										<td>1日追加</td>
										<td>--</td>
										<td>{{number_format($ClassPrice->getPriceDayNight($class->id,'0','0'))}}円</td>
									</tr>
								</table>
								<ul class="remarks">
									<li>基本料金に含まれる内容はレンタカー代と消費税です。オプションや免責補償は含まれておりませんがWeb予約時に追加することができます。</li>
									<li>料金は<b>{{date('Y年n月j日')}}</b>の料金レートで表示しています。ハイシーズンには料金レートが変動することがありますので、空車検索より期間を設定してご確認ください。</li>
								</ul>
							</div>
						</div>
						<!-- section 1 -->
						<!-- section 2 -->
						<div class="row margin-bottom-40 yellow_wrap">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 class-detail">
								<h3 class="classdetail-ttl">車両クラスの特徴</h3>
								<ul class="classdetail-li">
									{!! $class->description !!}
								</ul>
								<h3 class="classdetail-ttl">車両クラスの装備</h3>
								<ul class="clearfix classdetail-eqlist" >
									@foreach($equipments as $eq)
										@if(!empty($eq->thumbnail))
											<li><img src="{{URL::to('/')}}/{{$eq->thumbnail}}" class="img-responsive"></li>
										@else
											<li><img src="{{URL::to('/images/carequipment/no-icon.png')}}" class="img-responsive"></li>
										@endif
									@endforeach
								</ul>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 class-detail">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<div style="">
											<img src="{{URL::to('/')}}/img/pages/carbasic/staff-comment.png" class="img-responsive pop-stuff-img">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="popover left show">
											<div class="popover-content">
												<p>{!! $class->staff_comment !!}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- section 2 -->
					</div>
				</div>

				<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<!-- box 2 -->
					<a id="speed"></a>
					<div class="box-shadow paddingbox" style="position:relative;">
						<div class="ribbon-block bg-grad-red">
							<h3 class="speed">見積もりとスピード予約</h3>
						</div>
						<div class="clearfix margin-bottom-40">
							<div class="col-lg-12  col-md-12 pxs0">
								<!-- search -->
								<div class="p-block search-block bg-darkred carclass_search">
									<?php
									$selected_shop = '';
									foreach($shops as $shop) {
										if($pickup_id == $shop->id) $selected_shop = $shop->name;
									}
									?>
									<h3>{{$class->name}}クラス({{ $selected_shop }})の見積条件を設定して下さい。</h3>
									<div class="search-block-cont">
										<form method="POST" name="search" id="search" action="{{URL::to('/')}}/carclass-detail/{{$class->id}}" accept-charset="UTF-8" role="form" class="form-horizontal" enctype="multipart/form-data">
											{!! csrf_field() !!}
											<input type="hidden" id="class_id" name="class_id" value="{{$class_id}}">
											<input type="hidden" name="search_cond" id="search_cond" value="{{$search_cond}}">
											<table class="table search-tbl">
												<tr>
													<th><span class="cond-ttl bg-darkred">出発条件</span></th>
													<td>
														<div class="row">
															<div class="col-md-4">
																<div id="depart-datepicker" class="input-group date">
																	<input type="text" id="depart_date" name="depart_date" readonly value="{{$depart_date}}" class="form-control datetime-select" required>
																	<div class="input-group-addon">
																		<span class="glyphicon glyphicon-th"></span>
																	</div>
																</div>
															</div>
															<div class="col-md-4" >
																<select class="chosen-select form-control selectpicker datetime-select" name="depart_time" id="depart_time" required>
																	@foreach($hour as $h)
																		<option value="{{$h}}"
																			@if($depart_time == $h) selected @endif
																			@if(date('H:i') > date($h)) disabled @endif
																		>{{$h}} </option>
																	@endforeach
																</select>
															</div>
															<div class="col-md-4 hidden">
																<select class="form-control slct-3" name="pickup_id" id="pickup_id">
																	@foreach($shops as $shop)
																		<option value="{{$shop->id}}" @if($pickup_id == $shop->id) selected @endif  >{{$shop->name}}</option>
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
																	<input type="text" id="return_date" name="return_date" readonly class="form-control datetime-select" value="{{$return_date}}" required>
																	<div class="input-group-addon">
																		<span class="glyphicon glyphicon-th"></span>
																	</div>
																</div>
															</div>
															<div class="col-md-4" >
																<select class="chosen-select form-control selectpicker datetime-select" name="return_time" id="return_time" required>
																	@foreach($hour as $h)
																		<option value="{{$h}}" @if($return_time == $h) selected @endif>{{$h}} </option>
																	@endforeach
																</select>
															</div>
															<div class="col-md-4 hidden">
																<select class="form-control slct-3" name="dropoff_id" id="dropoff_id">
																	@foreach($shops as $shop)
																		<option value="{{$shop->id}}" @if($dropoff_id == $shop->id) selected @endif >{{$shop->name}}</option>
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
															@if($smoke_count > 0 && $non_smoke_count > 0)
															<option value="both" @if($smoking == 'both') selected @endif>どちらでもいい</option>
															@endif
															@if($smoke_count > 0)
															<option value="1" @if($smoking == '1') selected @endif>喫煙</option>
															@endif
															@if($non_smoke_count > 0)
															<option value="0" @if($smoking == '0') selected @endif>禁煙</option>
															@endif
														</select>
														<select name="insurance" class="form-control slct-3 hidden">
															<option value="0" selected>免責不要</option>
															<option value="1">免責補償</option>
															<option value="2">ワイド補償</option>
														</select>
													</td>
												</tr>
												@php
													$count_mps = count($max_passengers);
                                                    $noset_maxpassenger = $count_mps == 0;
                                                    $count_notempty = 0; $mps = [];
                                                    foreach($max_passengers as $key=>$mp){
                                                        if($mp->count > 0) {
                                                            $count_notempty++;
                                                            $mps[] = $mp;
                                                        }
                                                    }
                                                    $noset_maxpassenger = $count_notempty == 0;
												@endphp

												<tr>
													<th><span class="cond-ttl bg-darkred">最大乗車人数</span></th>
													<td id="passenger_block" style="vertical-align: middle;">
														@if($count_mps == 1)
															<b>{{$max_passengers[0]->max_passenger}}</b>人乗り車両
															<input type="hidden" name="max_passenger" class="car_passenger" value="{{$max_passengers[0]->max_passenger}}">
														@elseif($count_mps > 1)
															@if($count_notempty > 1)
																<select name="max_passenger" class="form-control slct-3" style="margin-right: 5px;">
																@foreach($mps as $mp)
																<option value="{{$mp->max_passenger}}" @if($max_passenger == $mp->max_passenger) selected @endif>{{$mp->max_passenger}}人乗り</option>
																@endforeach
																</select>
															@elseif($count_notempty == 1)
																<span >
																*ご選択日の在庫は<b>{{$mps[0]->max_passenger}}</b>名乗りのみとなります。</span>
																<input type="hidden" name="max_passenger" class="car_passenger" value="{{$mps[0]->max_passenger}}">
															@else
																<span class="alert-msg">現在の車両の在庫はありません。</span>
															@endif
														@else
															<span class="alert-msg">車両定員数が設定されていません。</span>
														@endif
													</td>
												</tr>
												<tr>
													<th><span class="cond-ttl bg-darkred">オプション</span></th>
													<td>
														<div class="row option_ui" style="margin-left: 5px;">
															@foreach($paid_options as $op)
																<div class="col-md-12 check_wrap">
																	<?php
																	$checked = '';
																	if(!empty($paid_option_ids)) {
																		if(in_array($op->id, $paid_option_ids)) $checked = 'checked';
																	}
																	$op_id = ''; $lbl_id = ''; $op_hidden = '';
																	if($op->google_column_number == 38) {
																		$op_id = 'id="paid_pickup_check"';
																		$lbl_id = 'id="paid_pickup_label"';
//																		$op_hidden = 'hidden';
																	}
																	$index = array_search($op->id, $paid_option_ids);
																	$pon = ($index === false)? 0 : $paid_option_nums[$index];
																	?>
																	<label class="checkbox col-md-4 col-sm-10 col-xs-9 search_option {{$op_hidden}}" style="display: inline;" {!! $lbl_id !!}>
																		<input type="checkbox" name="paid_options[]" class="opt" {!! $op_id !!} value="{{$op->id}}" {{$checked}}>
																		<span>{{$op->name}} </span>
																	</label>
																	<label>
																		@if($op->google_column_number == 25)
																			*ETC機器は全ての車に標準装備しています。
																		@endif
																	</label>
																	<select name="paid_option_numbers[]" oid="{{$op->id}}" class="opt_num {{($op->max_number == 1)? 'hidden':''}}">
																		@for( $k = 0; $k <= $op->max_number; $k++ )
																			<option value="{{ $k }}" @if($k == $pon) selected @endif>{{ ($k == 0)? '': $k }}</option>
																		@endfor
																	</select>
																</div>
															@endforeach
															@foreach($free_options as $key => $op)
																<?php
																$checked = '';
																if(!empty($free_option_ids)) {
																	if(in_array($op->id, $free_option_ids)) $checked = 'checked';
																}
																$op_id = ''; $lbl_id = ''; $op_class = '';
																if($op->google_column_number == 101 || $op->google_column_number == 102) {
																	$op_id = 'id="free_pickup_check_'.$key.'"';
																	$lbl_id = 'id="free_pickup_label_'.$key.'"';
																	$op_class = 'free_pickup';
																}
																?>
																<div class="col-md-12 check_wrap">
																	<label class="checkbox col-xs-6 search_option" style="display: inline;" {!! $lbl_id !!}>
																		<input type="checkbox" name="free_options[]" class="opt {{$op_class}}" {!! $op_id !!} value="{{$op->id}}" {{$checked}}>
																		<span>{{$op->name}}(無料)</span>
																	</label>
																	<select name="free_option_numbers[]" oid="{{$op->id}}" class="opt_num {{($op->max_number == 1)? 'hidden':''}}">
																		@for( $k = 0; $k <= $op->max_number; $k++ )
																			<option value="{{ $k }}">{{ ($k == 0)? '': $k }}</option>
																		@endfor
																	</select>
																</div>
															@endforeach
														</div>
													</td>
												</tr>
											</table>
										</form>
									</div>
									<div class="clearfix bg-white calc-btn">
										{{--<a id="btn-search" class="btn bg-grad-red" onclick="search_submit()" @if($noset_maxpassenger || $count_notempty == 0) disabled @endif>見積もりを見る</a>--}}
										<a id="btn-search" class="btn bg-grad-red" onclick="search_submit()">見積もりを見る</a>
									</div>
								</div>
								<!-- search -->

								<div id="showinform"> </div>
								@if(empty($searching) && $search_cond != '0')
								{{--<div class="p-block result-block bg-darkred search_cond">--}}
									{{--<h3>お見積もり内容</h3>--}}
									{{--<div class="result-block-cont">--}}
										{{--<div class="row m_T20 text-center">--}}
											{{--[ ご指定の条件で空車が見つかりませんでした ]--}}
										{{--</div>--}}
									{{--</div>--}}

								{{--</div>--}}
									<div class="p-block result-block bg-darkred">
										<h3 style="font-size:23px; font-weight:500;">{{$class->name}}クラス({{ $selected_shop }})の検索結果</h3>
										<div class="result-block-cont">
											<div class="row">
												<div class="col-sm-3 hidden-xs text-center" style="padding-right: 0">
													<img src="{{URL::to('/')}}/images/No-Result.png" class="img-responsive xs-center-block " style="width:75%;float:right;">
												</div>
												<div id="no-result" class="col-sm-9 col-xs-12">
													<h3 style="font-size:23px; font-weight:500;text-align: left">お探しのクラスで条件に合う車両は見つかりませんでした。</h3>
													<h3 style="font-size:23px; font-weight:500;text-align: left">キャンセルが出てる場合がございますので店舗までお問い合わせ下さい。</h3>
												</div>
												<div class="col-xs-12">
													<h3 style="font-size:25px; font-weight:500;;text-align: center;">
														<a id="Callnoresult" href="tel:0922609506" style="font-size: 1.5em;text-decoration: underline;font-style: italic;">092-260-9506</a>
													</h3>
													<h3 style="font-size:23px; font-weight:500;color:deeppink;">▼同じ日付でお取りできるお車はこちらです▼</h3>
												</div>
											</div>
										</div>
									</div>

									{{--suggested 3 classes of shop--}}
								<!--loop for search-->
									@foreach($top_three as $tcls)
                                        <?php $cid = $tcls->id; ?>
										<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 listing_wrap" style="padding: 0;">
											<div class="box-shadow relative search-result-panel ">
												<div class="row" style="margin: 0;">
													<div class="col-xs-12">
														<div class="car_title m_T10 m_B20" class_id="{{ $cid }}"
															 style="margin-left: 10px">
															{{$tcls->class_name}}
														</div>
													</div>
													<div class="col-xs-12" style="padding: 0;">
														<div class="col-sm-6 col-xs-12" style="padding: 0 10px;">
															<img src="{{URL::to('/').$tcls->thum_path}}" class="img-responsive center-block m_Txs60">
															<input type="hidden" class="car_photo" class_id="{{ $cid }}" value="{{ $tcls->thum_path }}">
															<p class="sml-txt">
																<label class="result_shop">
																	@if($tcls->shop_name =="" )
																		店舗未選択
																	@else
																		{{$tcls->shop_name}}
																	@endif
																</label>
																<label class="result_shop car_category" class_id="{{ $cid }}" style="margin-left: 20px;">
																	@if($tcls->category_name =="" )
																		車両カテゴリ未選択
																	@else
																		{{$tcls->category_name}}
																	@endif
																</label>
															</p>
														</div>
														<div class="col-sm-6 col-xs-12 padding-right-0" style="padding: 0;">
															<div class="panel panel-default" style="margin-bottom: 5px;">
																<div class="panel-heading bg-grad-gray">
																	お見積もり料金
																</div>
																<div class="panel-body" style="padding-bottom: 0px;">
																	<div class="form-group row-bordered-result row">
																		<label class="col-md-7 col-sm-7 col-xs-6 label_manage"
																			   style="padding-left: 0;padding-top: 3px;
    margin-bottom: 10px;">
                                                                            <?php
                                                                            $dt1 = date('Y年n月j日', strtotime($tcls->depart_date)).' ';
                                                                            $dt1 .= date('G時', strtotime($tcls->depart_time));
                                                                            $min = intval(date('i', strtotime($tcls->depart_time)));
                                                                            if ($min > 0) $dt1 .= $min . '分';
                                                                            $dt2 = date('Y年n月j日', strtotime($tcls->return_date)).' ';
                                                                            $dt2 .= date('G時', strtotime($tcls->return_time));
                                                                            $min = intval(date('i', strtotime($tcls->return_time)));
                                                                            if ($min > 0) $dt2 .= $min . '分';
                                                                            ?>
																			<div>
																				<label>出発 : </label>
																				<label>{{$dt1}}</label>
																			</div>
																			<div>
																				<label>返却 : </label>
																				<label>{{$dt2}}</label>
																			</div>
																		</label>
																		<label class="col-md-5 col-sm-5 col-xs-6" style="padding-right: 0">
																			<div class="bubble-wrap toltip_wrap" style="width: 100%">

                                                                                <?php
                                                                                $leftmany = ($tcls->car_count >= 10) ? 'active' : '';
                                                                                $leftfew = ($tcls->car_count <= 9 && $tcls->car_count >= 4) ? 'active' : '';
                                                                                $leftafew = ($tcls->car_count <= 3) ? 'active' : '';
                                                                                ?>
																				<div class="bubble left many {{$leftmany}}" class_id="{{ $cid }}" style="font-size: 16px">
																					予約できます
																				</div>
																				<div class="bubble left few {{$leftfew}}" class_id="{{ $cid }}" style="font-size: 16px">
																					残り<span class="bubble_write">僅か</span>です
																				</div>
																				<div class="bubble left afew {{$leftafew}}" class_id="{{ $cid }}" style="font-size: 16px">
																					残り在庫<span class="bubble_write car_count"
																							  class_id="{{ $cid }}">{{ $tcls->car_count }}</span>台
																				</div>

																			</div>
																		</label>
																	</div>
																	<div class="form-group row-bordered-result row">
																		<label class="pull-left span_nightday" class_id="{{ $cid }}">
																			基本料金 (<?php if ($tcls->night_day == "0泊1日") {
                                                                                echo "当日返却";
                                                                            } else {
                                                                                echo $tcls->night_day;
                                                                            } ?>)
																		</label>
																		<label class="pull-right basic_price" class_id="{{ $cid }}">
																			{{number_format($tcls->price)}}円
																		</label>
																		<input type="hidden" class="rent_days" class_id="{{ $cid }}"
																			   value="{{ $tcls->night_day }}">
																		<input type="hidden" class="price_rent"
																			   class_id="{{ $cid }}" value="{{ $tcls->price }}">
																	</div>
                                                                    <?php
                                                                    $option_ids = [];
                                                                    $option_names = [];
                                                                    $option_costs = [];
                                                                    $option_numbers = [];
                                                                    $option_prices = [];
                                                                    if (!empty($tcls->options)) {
                                                                        foreach ($tcls->options as $op) {
                                                                            $option_ids[] = $op->id;
                                                                            $option_names[] = $op->name;
                                                                            $option_costs[] = $op->price;
                                                                            $option_numbers[] = $op->number;
                                                                            $vp = 0;
                                                                            if ($op->charge_system == 'one') {
                                                                                $vp = $op->price * $op->number;
                                                                            } else {
                                                                                $vp = $op->price * $shop_search->rentdates*$op->number;
                                                                            }
                                                                            $option_prices[] = $vp;
                                                                        }
                                                                    }
                                                                    ?>
																	@if(!empty($tcls->options))
																		<div class="option-wrapper" class_id="{{ $cid }}">
																			<table style="width: 100%">
																				<tbody style="font-size: 13px">
																				@foreach($tcls->options as $op)
																					<tr class=" row-bordered-result">
																						<td style="text-align: left">{{$op->name}}
																							(オプション)
																						</td>
																						<td style="text-align: center">
																							<input type="hidden" name="opt_charge"
																								   class="opt_charge"
																								   class_id="{{ $cid }}"
																								   oid="{{$op->id}}"
																								   value="{{$op->charge_system}}">
																							<input type="hidden" name="opt_num"
																								   class_id="{{ $cid }}"
																								   oid="{{$op->id}}"
																								   value="{{ $op->number }}">
																							{{ $op->number }} 個
																						</td>
																						<td style="text-align: right">
                                                                                            <?php
                                                                                            if ($op->charge_system == 'one') {
                                                                                                $oprice = $op->price * $op->number;
                                                                                            } else {
                                                                                                $oprice = $op->price * $op->number * $shop_search->rentdates;
                                                                                            }
                                                                                            ?>
																							<span class="opt_cost" oid="{{$op->id}}"
																								  class_id="{{$cid}}">{{number_format($oprice)}}</span>円
																						</td>
																					</tr>
																				@endforeach
																				</tbody>
																			</table>
																		</div>
																		<input type="hidden" class="option_ids"
																			   class_id="{{ $cid }}"
																			   value="{{ implode(',', $option_ids) }}">
																		<input type="hidden" class="option_names"
																			   class_id="{{ $cid }}"
																			   value="{{ implode(',', $option_names) }}">
																		<input type="hidden" class="option_numbers"
																			   class_id="{{ $cid }}"
																			   value="{{ implode(',', $option_numbers) }}">
																		<input type="hidden" class="option_costs"
																			   class_id="{{ $cid }}"
																			   value="{{ implode(',', $option_costs) }}">
																		<input type="hidden" class="option_prices"
																			   class_id="{{ $cid }}"
																			   value="{{ implode(',', $option_prices) }}">
																	@endif

																	@if(!empty($class->insurance))
																		<div class="form-group row-bordered-result row hidden">
																			<div class="col-xs-6" style="padding: 0">
																				免責保障
																				<select name="insurance"
																						class="insurance pull-right"
																						class_id="{{ $cid }}">
																					<option value="0">不要</option>
																					<option value="{{ $tcls->insurance[1] }} selected">
																						免責補償
																					</option>
																					<option value="{{ $tcls->insurance[1]+$tcls->insurance[2] }}">
																						ワイド免責補償
																					</option>
																				</select>
																				<input type="hidden" class="insurance_price1"
																					   value="{{ $tcls->insurance_price1 }}"
																					   class_id="{{ $cid }}">
																				<input type="hidden" class="insurance_price2"
																					   value="{{ $tcls->insurance_price2 }}"
																					   class_id="{{ $cid }}">
																			</div>
																			<div class="col-xs-6" style="padding-right: 0;">
																				<label class="pull-right">
                                                                        <span class="insurance-price"
																			  class_id="{{ $cid }}">0</span>円
																				</label>
																			</div>
																		</div>
																	@endif

																	@php
																		$max_passengers = $tcls->max_passengers;
                                                                        $noset_maxpassenger = count($max_passengers) == 0;
                                                                        $count_notempty = 0; $mps = [];
                                                                        foreach($max_passengers as $key=>$mp){
                                                                            if($mp->count > 0) {
                                                                                $count_notempty++;
                                                                                $mps[] = $mp;
                                                                            }
                                                                        }
                                                                        $noset_maxpassenger = $count_notempty == 0;
																	@endphp

																	<div class="form-group row-bordered-result row">
																		<div class="col-xs-12" style="padding: 0">
																			禁煙/喫煙
																			<input type="hidden" name="car_smoking"
																					class="car_smoking"
																					class_id="{{ $cid }}"
																					value="{{ $tcls->smoke }}">
																			<label class="pull-right">
																				@if($tcls->smoke == '0') 禁煙 @endif
																				@if($tcls->smoke == '1') 喫煙 @endif
																				@if($tcls->smoke == 'both') どちらでも良い @endif
																			</label>
																		</div>
																	</div>

																	<div class="form-group row-bordered-result row">
																		@if(count($max_passengers) == 1)
																			<div class="col-xs-12" style="padding: 0">
																				<b>{{$max_passengers[0]->max_passenger}}</b>人乗り車両
																				<input type="hidden" name="car_passenger"
																					   class="car_passenger"
																					   value="{{$max_passengers[0]->max_passenger}}"
																					   class_id="{{ $cid }}">
																			</div>
																		@elseif(count($max_passengers) > 1)
																			<div class="col-xs-12" style="padding: 0">
																				@if($count_notempty > 1)
																					車両定員数
																					<select name="car_passenger"
																							class="car_passenger pull-right"
																							class_id="{{ $cid }}">
																						@foreach($mps as $pt)
																							<option value="{{$pt->max_passenger}}">{{$pt->max_passenger}}
																								人乗り
																							</option>
																						@endforeach
																					</select>
																				@elseif($count_notempty == 1)
																					<span>
                                                                        *現在この車両の在庫は<b>{{$mps[0]->max_passenger}}</b>名乗りのみとなります。</span>
																					<input type="hidden" name="car_passenger"
																						   class="car_passenger"
																						   value="{{$mps[0]->max_passenger}}"
																						   class_id="{{ $cid }}">
																				@else
																					<span class="alert-msg">現在の車両の在庫はありません。</span>
																				@endif
																			</div>
																		@else
																			<div class="col-xs-12" style="padding: 0">車両定員数
																				<span class="alert-msg">車両定員数が設定されていません。</span>
																			</div>
																		@endif
																	</div>
																	@if(count($free_options)>0)
																		<div class="form-group row-bordered-result row">
																			<div class="col-xs-12" style="padding: 0">
																				空港送迎
																				<select name="car_pickup" class="car_pickup pull-right" class_id="{{ $cid }}">
																					@foreach($free_options as $fr)
																						<option value="{{ $fr->id }}"
																								@if($fr->id == $shop_search->pickup) selected @endif>{{ $fr->name }}</option>
																					@endforeach
																					<option value="" @if($shop_search->pickup == '') selected @endif>
																						不要
																					</option>
																				</select>
																			</div>
																		</div>
																	@endif
																	<div class="form-group">
																		<label class="col-sm-5 padding-0">
																			<div>総計（税込）</div>
																		</label>
																		<label class="col-sm-7 padding-0" style="color: #b63432">
																			<div style="padding-top: 15px;">
																				<label style="font-weight:bold;font-size: 55px; color:#e60707;/*margin-top: -20px; margin-bottom: -20px;*/" class="total_price" class_id="{{ $cid }}">
																					{{number_format($tcls->all_price)}}
																				</label><label style="font-weight: 300">円</label>
																			</div>
																			<input type="hidden" class="price_all" class_id="{{ $cid }}" value="{{ $tcls->all_price }}">
																			<div>地域最安値挑戦中！</div>
																		</label>
																	</div>
																</div>
															</div>
															<div class="text-center">
																<label>
																	<button class="btn bg-grad-red btn_book{{$cid}}" style=" margin-top:10px;padding: 10px 50px 10px 50px"
																			onclick="gotoConfirm({{ $cid }}, '{{$tcls->class_name}}')"
																			@if($noset_maxpassenger)disabled @endif>
																		予約する
																	</button>
																</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endforeach
								<!--end loop-->
								@endif
								<!-- result -->

								@foreach($searching as $search)
								<div class="p-block result-block bg-darkred search_cond listing_content">
									<h3>お見積もり</h3>
									<div class="result-block-cont">
										<div class="row m_T20">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<table class="table result-tbl">
													<tr>
														<th><span>車両クラス</span></th>
														<td>
                                                            <?php
                                                            $capacity = ($min_psg != $max_psg)? $min_psg.'～'.$max_psg : $min_psg;
                                                            if($class->name == 'W3' || $class->name == 'CW3H') $capacity = 7;
                                                            ?>
															{{$class->name}}　　 {{ $capacity }}人乗り
														</td>
													</tr>
													<tr>
														<th><span>車種</span></th>
														<td>
															<?php $cnt = count($search->models); ?>
															@foreach($search->models as $key=>$model)
																{{$model->name}} @if($cnt > $key+1) / @endif
															@endforeach
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<ul class="clearfix result-carlist" >
																@foreach($search->models as $model)
																	@if(!empty($model->thumb_path))
																		<li>
																			<img src="{{URL::to('/')}}/{{$model->thumb_path}}" class="img-responsive">
																		</li>
																	@endif
																@endforeach
															</ul>
														</td>
													</tr>
													<tr><th><span>ご利用店舗</span></th><td>{{$pickup_name}}</td></tr>
													<tr>
														<th><span>出発</span></th>
														<td>{{date('Y年n月j日 G:i', strtotime($depart_date.' '.$depart_time))}}</td>
													</tr>
													<tr>
														<th><span>返却</span></th>
														<td>{{date('Y年n月j日 G:i', strtotime($return_date.' '.$return_time))}}</td>
													</tr>
													<tr>
														<th><span>利用期間</span></th>
														<td>@if($search->night == 0) 日帰り @else {{$search->night}}泊{{$search->day}}日 @endif</td>
													</tr>
												</table>
												<input type="hidden" name="car_count" id="car_count" value="{{$search->car_count}}">
												<p class="bg-carico r-txt">在庫残り<span class="font-red">
														{{$search->car_count}}台</span>です。ご予約はお早めに！</p>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<table class="table table-responsive result-detail-tbl">

													<tr>
														<th colspan="2" style="border-top:none!important;"><span>料金詳細</span></th>
													</tr>
													<tr>
													<!-- <th>基本料金（{{$search->night}}泊{{$search->day}}日）</th> -->
													<th>
														基本料金 ( <?php if($search->night == 0 && $search->day == 1) { echo "当日返却"; } else { echo $search->night.'泊'.$search->day.'日'; }?> )
													</th>
														<td>{{number_format($search->price)}}円</td>
													</tr>
													@if($insurance != '0')
													{{--<tr>--}}
														{{--<th>--}}
															{{--@if($insurance == '1') 免責補償 @endif--}}
															{{--@if($insurance == '2') 免責補償 / ワイド補償 @endif--}}
														{{--</th>--}}
														{{--<td>--}}
															{{--{{number_format($search->insurance)}}円--}}
														{{--</td>--}}
													{{--</tr>--}}
													@endif
													@foreach($search->options as $op)
														<tr>
															<th>{{$op->abbriviation}}
																@if($op->google_column_number != 25 && $op->google_column_number != 38)
																({{$op->number}}個)
																@endif
															</th>
															<td>{{number_format($op->price)}}円</td>
														</tr>
													@endforeach

													@if(!empty($free_option_ids))
													@foreach($free_options as $op)
														@if(in_array($op->id, $free_option_ids))
													<tr>
														<th>無料オプション</th>
														<td>{{$op->abbriviation}}</td>
													</tr>
														@endif
													@endforeach
													@endif
													<tr>
														<th>禁煙/喫煙</th>
														<td>
															@if($smoking == '0')
																禁煙車
															@else
																@if($smoking == '1')
																	喫煙車
																@else
																	どちらでもいい
																@endif
															@endif</td>
													</tr>
													<tr>
														<th>最大乗車人数</th>
														<td>
                                                            {{ $max_passenger }}人乗り
                                                        </td>
													</tr>
													<tr>
														<th>
															<span style="display:inline-block;">
															<a class="mens-modal" data-toggle="modal" href="#Modal_01" data-target="#Modal_01" style="color:#333; cursor: pointer;">免責補償 <i class="fa fa-question-circle" style="color:#4c4c4c;"></i></a>
															</span>
															<br >
															{{--<br class="visible-xs-inline">--}}
															<select name="insurance_type" id="insurance_type" style="/*max-width: 125px;*/">
																<option value="2" selected>【お勧め】ワイド免責補償を付ける</option>
																<option value="1">免責補償を付ける</option>
																<option value="0">免責は不要</option>
															</select>
														</th>
														<td class="vta_b">
															<input type="hidden" name="insurance_cost" value="{{$search->insurance}}">
															<span id="insurance_cost">
																{{ number_format($search->insurance) }}
															</span>円
														</td>
													</tr>
													<tr >
														<th colspan="2" style="width:100%;">
															<a id="xxx" data-toggle="modal" href="#Modal_01" class="added-txt" data-target="#Modal_01" style="background: #ffffcc;color: #333;">
																免責補償が追加されています。 <i class="fa fa-question-circle" style="color:#4c4c4c;"></i>
															</a>
														</th>
													</tr>

													<input type="hidden" name="all_price" value="{{$search->price + $search->option_price + $search->insurance}}">
													<input type="hidden" name="base_price" value="{{$search->price}}">
													<input type="hidden" name="option_price" value="{{$search->option_price}}">

													<tr>
														<td colspan="2" class="result">
															<div class="pull-left">
																<p style="font-size: 15px;color: #333;float: left;margin-top: 5px;text-align: left;">
																	総計（税込）<br>
																	<small style="font-weight:500;">表示料金は税込金額です。</small>
																</p>
															</div>
															<div class="pull-right">
																<span id="span_all_price">
																	{{number_format($search->price + $search->option_price + $search->insurance)}}
																</span>
																円<br>
																<small>地域最安値挑戦中！</small>
															</div>
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn hidden">
													<a class="bg-grad-red" onclick="goConfirm({{ json_encode($search)}})" >スピード予約に進む</a>
												</div>
											</div>
										</div>
										<div class="row m_T20">
											<div class="col-xs-12 text-center" style="margin-bottom: 20px;">
												<img src="{{ url('/images/book-now.jpg') }}" id="log-booking">
											</div>
											<div class="col-xs-12 text-center calc-btn">
												<a class="bg-grad-red" onclick="showBookingPanel(false)" style="float: none; display: inline-block;margin-bottom: 10px;">初めてのご予約</a>
												<span class="hidden-xs" style="width: 50px;display: inline-block;"></span>
												<a class="bg-grad-red" onclick="showBookingPanel(true)" style="float: none; display: inline-block;margin-bottom: 10px;">会員様ご予約</a>
											</div>
										</div>
									</div>
								</div>

								<div class="p-block result-block bg-darkred block-booking">
									<h3 class="text-center" style="font-size:30px; font-weight:500;">ご予約フォーム</h3>

									<div class="result-block-cont">
									<form id="reserve-right-form">
										<input type="checkbox" id="check-member" style="display: none;">
										<div class="row m_T10 only-nonmember">
											<div class="col-xs-12 col-sm-6 m_T10">
												<label class="col-xs-12" style="padding-left: 0">お名前<span class="req-red">必須</span></label>
												<div class="col-xs-12" style="padding: 0; margin-bottom:0px;">
													<div class="col-xs-6" style="padding: 0 5px 0 0">
														<input type="text" name="last_name" id="last_name" placeholder="姓" class="form-control h40" >
														<span class="error-class errorlast_name"></span> </div>
													<div class="col-xs-6" style="padding: 0 0 0 5px">
														<input type="text" name="first_name" id="first_name" placeholder="名" class="form-control h40" >
														<span class="error-class errorfirst_name"></span> </div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-6 m_T10">
												<label class="col-xs-12" style="padding-left: 0">フリガナ<span class="req-red">必須</span></label>
												<div class="col-xs-12" style="padding: 0">
													<div class="col-xs-6" style="padding: 0 5px 0 0">
														<input type="text" name="furi_last_name" id="furi_last_name" placeholder="セイ" class="form-control h40" >
														<span class="error-class errorfuri_last_name"></span>
													</div>
													<div class="col-xs-6" style="padding: 0 0 0 5px">
														<input type="text" name="furi_first_name" id="furi_first_name" placeholder="メイ" class="form-control h40" >
														<span class="error-class errorfuri_first_name"></span>
													</div>
												</div>
											</div>
										</div>
										<div class="row m_T10 ">
											<div class="col-xs-12 col-sm-6">
												<label>メールアドレス<span class="req-red">必須</span></label>
												<input type="text" name="email" id="email" placeholder="メールアドレス" class="form-control h40" style="margin-bottom:5px;">
												<div class="error-box col-md-12 col-sm-12 col-xs-12">
													<span class="error-class erroremail"></span>
												</div>
											</div>
											<div class="col-xs-12 col-sm-6 only-nonmember" id="div_phone">
												<label>電話番号<span class="req-red">必須</span></label>
												<input type="text" name="phone" id="phone" placeholder="ハイフン(-)なしでご入力ください" class="form-control h40">
												<div class="error-box col-md-12 col-sm-12 col-xs-12"> <span class="error-class errorphone"></span> </div>
											</div>
											<div class="col-xs-12 col-sm-6 only-member">
												<label>パスワード<span class="req-red">必須</span></label>
												<input type="password" name="password" id="password" placeholder="パスワードをご入力ください" class="form-control h40">
												<div class="error-box col-md-12 col-sm-12 col-xs-12"> <span class="error-class errorpassword"></span> </div>
											</div>
										</div>
										<div class="row m_T10 only-nonmember text-center" style="margin: 0">
											<label for="check-it" class="check-change">
												<input type="checkbox" name="accept-check" id="check-it" checked>
												<a data-toggle="modal" data-target="#myModal" href="#myModal" style="color: #337ab7;">会員規約</a>に同意する
											</label>
										</div>
										<div class="row m_T10 text-center" style="margin: 0">
											<p>公式サイトだから ! <span style="color:#f00f5e;">ベストプライス保証!!</span></p>
											<input type="submit" name="submit" id="validateBtn" class="bg-grad-red h40" value="車を予約する">
											<p class="only-nonmember m_B0" style="font-weight: 500">
												ご入力いただいたアドレスにメールをお送りします。
												<br class="visible-xs-block">
												<small>*ご予約と会員登録は同時に行われます。</small>
											</p>
										</div>
									</form>
									</div>
								</div>
								@endforeach
								<!-- result -->

								<!-- suggest -->
                                @if(!empty($suggest))
								<div class="suggest-block search_cond suggest_wrap">
									<div class="clearfix m_T20">
										<div class="col-lg-offset-1 col-lg-2 col-md-2 col-sm-2 col-xs-3 suggest_stuff">
											<img src="{{URL::to('/')}}/img/stuff_01.png" class="img-responsive pop-stuff-img">
										</div>
										<div class="col-lg-8 col-md-10 col-sm-10 col-xs-9 popover_wrap">
											<div class="popover right popover2 show">
												<div class="arrow-b arrow"></div>
												<div class="popover-content">
													<p>ワンランク上の車両クラスにできます！<br/>ストレス軽減で快適な乗り心地をお楽しみください！</p>
												</div>
											</div>
										</div>
									</div>
									<div class="suggest-block-cont suggest_item">
										@foreach($suggest as $key => $su)
										<hr>
										<div class="clearfix m_T20">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<img src="{{URL::to('/')}}/{{$su->thumb_path}}" class="img-responsive center-block">
											</div>
											<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
												<table class="table gradeup-tbl">
													<tr>
														<th colspan="2" style="margin-bottom: 5px; border-top:none!important;" ><span><b class="red-num">
															@if($su->all_price > $suggest_price) + @endif
															{{number_format($su->all_price-$suggest_price)}}円</b>でグレードアップ！</span>
														</th>
													</tr>
													<tr>
														<th><span>ワゴンクラス</span></th>
														<td>{{$su->class_name}}　
															@if($smoking == 'both')
																@if($su->smoking > 0) 喫煙車 @endif
																@if($su->nonsmoking > 0)  禁煙車 @endif
															@elseif($smoking == '1')
																@if($su->smoking > 0) 喫煙車 @endif
															@elseif($smoking == '0')
																@if($su->nonsmoking > 0)  禁煙車 @endif
															@endif
															{{$su->passenger->min_passenger}}~{{$su->passenger->max_passenger}}人乗り
														</td>
													</tr>
													<tr>
														<th><span>対応車種</span></th>
														<td>
															<?php $m = 0; ?>
															@foreach($su->models as $mo)
																{{$mo->name}} @if(count($su->models) > $m+1) / @endif
																<?php $m++; ?>
															@endforeach
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn">
													{{--<a class="bg-grad-red" href="{{URL::to('/')}}/carclass-detail/{{$su->class_id}}">グレードアップする</a>--}}
													<a class="bg-grad-red" onclick="gotoSuggestDetail('{{$su->class_name}}', '{{$su->class_id}}', '{{$su->all_price}}', '{{$key+1}}')">グレードアップする</a>
												</div>
											</div>
										</div>
										@endforeach
									</div>
									<!-- suggest -->
								</div>
                                @endif
							</div>
							<!-- ROW -->
						</div>
						<!-- caution 2 -->
						<div class="caution-block p-block bg-darkred car_content">
							<h3 class="bg-grad-gray">予約に関するご案内</h3>
							<div class="clearfix bg-white">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<table class="table caution2-tbl">
										<tr>
											<th class="cardetail-tableth">お支払方法</th>
											<td>ご利用当日、店舗にて現金またはクレジットカード決済が可能です。</td>
										</tr>
										<tr>
											<th class="cardetail-tableth">割引について</th>
											<td>本ページ内に記載等がない場合には割引の適用はございません。他社レンタカーと同クラスの車両を比較してもお安くなっておりますので、ご検討くださいませ。<br/>キャンペーンについてはお知らせで告知致します。</td>
										</tr>
										<tr>
											<th class="cardetail-tableth">複数の運転者がいる場合</th>
											<td>主に運転されるお客様以外にも運転する可能性がある同乗者がいる場合、受付時に全員分の免許証をお持ちください。</td>
										</tr>
										<tr>
											<th class="cardetail-tableth">免責補償について</th>
											<td>ハコレンタカーの基本料金には基本保険料が含まれておりますが、免責補償は含まれておりません。免責補償の加入は任意となっております。ご加入していただきますと万一事故を起こしても対物・車両補償の支払いが一定金額まで免除されます。Web予約際に免責補償＋ワイド免責補償にご加入していただきますと、店舗での車両外装チェックが一切不要となりますので、乗り出し時間の短縮化(10~15分程度)にも繋がります。予約ページへ進みますと自動でワイド免責補償が追加されますので、必要に応じて免責補償への切り替え等を行ってください。 免責補償およびワイド免責補償の詳細については下記のページをご確認ください。
											<div class="clearfix bg-white btn-redboreder">
												<a href="{{URL::to('/')}}/insurance">免責・補償について</a>
											</div>
											</td>
										</tr>
										<tr>
											<th class="cardetail-tableth">キャンセルについて</th>
											<td>出発日より16日以前であればキャンセル料金はかかりません。<br/>出発日を含めて15日前のお客様のご都合による予約取消はキャンセル料金が発生致しますので、ご了承ください。 キャンセル料金については『<a class="textlink" href="{{URL::to('/')}}/faq/price" >よくある質問</a>』をご確認ください。</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<!-- caution 2 -->
					</div>
					<!-- END PAGE CONTENT INNER -->

					<div class="container-full bg-white clearfix visible-xs" style="padding-bottom:30px;">
						<a href="{{URL::to('/')}}/search-car">
							<img src="{{URL::to('/')}}/img/pages/toppage/rentacar-sp-button2.jpg" class="img-responsive center-block m_TB20">
						</a>
						<a href="{{URL::to('/')}}/search-car">
							<p style="text-align:center; padding:5px 20px;">
								ご希望の日時でお車を検索してみませんか？<br/>>> お試し！レンタカー検索 <<
							</p>
						</a>
					</div>

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
		<!-- end search -->
	</div>
</div>
<!-- END CONTENT -->
		<!--search confirm-->
        <form action="{{URL::to('/')}}/search-confirm" method="POST" name="booking-submit" id="booking-submit">
            {!! csrf_field() !!}
            <input type="hidden" name="data_depart_date" id="data_depart_date" >
            <input type="hidden" name="data_depart_time" id="data_depart_time">
            <input type="hidden" name="data_return_date" id="data_return_date">
            <input type="hidden" name="data_return_time" id="data_return_time">
            <input type="hidden" name="data_depart_shop" id="data_depart_shop">
            <input type="hidden" name="data_depart_shop_name" id="data_depart_shop_name">
            <input type="hidden" name="data_return_shop" id="data_return_shop">
            <input type="hidden" name="data_return_shop_name" id="data_return_shop_name">
            <input type="hidden" name="data_car_category" id="data_car_category">
            <input type="hidden" name="data_passenger" id="data_passenger">
            <input type="hidden" name="data_insurance" id="data_insurance">
            <input type="hidden" name="data_insurance_price1" id="data_insurance_price1">
            <input type="hidden" name="data_insurance_price2" id="data_insurance_price2">
            <input type="hidden" name="data_smoke" id="data_smoke">
            <input type="hidden" name="data_option_list" id="data_option_list">
            <input type="hidden" name="data_class_id" id="data_class_id">
            <input type="hidden" name="data_class_name" id="data_class_name">
            <input type="hidden" name="data_class_category" id="data_class_category">
            <input type="hidden" name="data_car_photo" id="data_car_photo">
            <input type="hidden" name="data_rent_days" id="data_rent_days">
            {{--<input type="hidden" name="data_rendates" id="data_rent_dates" value="{{ $search->rentdates }}">--}}
            <input type="hidden" name="data_price_rent" id="data_price_rent">
            <input type="hidden" name="data_option_ids" id="data_option_ids">
            <input type="hidden" name="data_option_names" id="data_option_names">
            <input type="hidden" name="data_option_numbers" id="data_option_numbers">
            <input type="hidden" name="data_option_costs" id="data_option_costs">
            <input type="hidden" name="data_option_prices" id="data_option_prices">
            <input type="hidden" name="data_price_all" id="data_price_all">
            <input type="hidden" name="data_member" id="data_member">
            <input type="hidden" name="data_pickup" id="data_pickup" value="{{ empty($free_option_ids)? 0 : 1 }}">

        </form>
		<!--end searchconfirm-->

    </div>

	@include('modals.modal-membership')

    <div class="modal fade modal-warning modal-save" id="modalError" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="{{URL::to('/')}}/images/blank.jpg" style="width: 80%;">
                    <div class="error-text" style="font-size: 20px;">
						条件に合う車両は見つかりませんでした。<br>
						同じ日付で別のお車を検索させてください！
					</div>
                </div>
                <div class="modal-footer text-center">
                    <form action="" method="post" id="frm_to_search">
                        {!! csrf_field() !!}
                        <input type="hidden" name="depart_date" id="sr_depart_date">
                        <input type="hidden" name="depart_time" id="sr_depart_time">
                        <input type="hidden" name="return_date" id="sr_return_date">
                        <input type="hidden" name="return_time" id="sr_return_time">
                        <input type="hidden" name="depart_shop" id="sr_depart_shop">
                        <input type="hidden" name="return_shop" id="sr_return_shop">
                        <input type="hidden" name="car_category" id="sr_car_category">
                        <input type="hidden" name="passenger" id="sr_passenger">
                        <input type="hidden" name="options" id="sr_options">
                        <input type="hidden" name="smoke" id="sr_smoke">
                        <input type="checkbox" class="hidden" name="pickup" id="sr_pickup">
                        {{--<input type="hidden" name="">--}}

                        <a href="javascript:goToSearch()" class="btn btn-success" >はい、すぐ探して！</a>
                        <a href="javascript:location.reload()" class="btn btn-primary">いいえ</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-warning modal-save" id="modalAlert" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="alert-text" style="font-size: 20px;">
					</div>
                </div>
            </div>
        </div>
    </div>

	<div class="modal fade" id="Modal_01" tabindex="-1">
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

        $(function() {
            $.fn.autoKana('#first_name', '#furi_first_name', {
                katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
            });
            $.fn.autoKana('#last_name', '#furi_last_name', {
                katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
            });
        });

        function showModal(text) {
            $('#modalError div.error-text').html(text);
            $('#modalError').modal();
        }

        function showAlert(text) {
            $('#modalAlert div.alert-text').html(text);
            $('#modalAlert').modal();
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
                // do other things for a valid form
                // $("#contactForm").attr("action", "notification.php");
                // form.submit();
                check();
            }
        });

        //全角ひらがなのみ
        jQuery.validator.addMethod("katakana", function(value, element) {
                return this.optional(element) || /^([ァ-ヶー]+)$/.test(value);
            }, "全角カタカナを入力下さい"
        );

	@if(!empty($searching))

		$('#insurance_type').change(function () {
			var ins_type = $(this).val();
			var ins1 = '{{$search->insurances['ins1']}}',
				ins2 = '{{$search->insurances['ins2']}}',
				days = '{{$search->day}}';
			var ans = 0;
			if(ins_type === '2') ans = (ins1*1 + ins2*1) * days;
			if(ins_type === '1') ans = ins1 * days;

			$('#insurance_cost').text(ans.toLocaleString());
			$('input[name="insurance_cost"]').val(ans);
			var base_price = $('input[name="base_price"]').val();
			var option_price = $('input[name="option_price"]').val();
			var all = ans + base_price*1 + option_price * 1;
			$('input[name="all_price"]').val(all);
			$('#span_all_price').text(all.toLocaleString());
		});

        function check() {
					{{--form to go confirm page--}}
            var url = '{{url('/search-save')}}';
            var checked = $('#check-member').prop('checked') ? 1 : 0;
            var pickup = $('.free_pickup:checked').val();
            console.log(pickup);
            if(pickup == undefined) pickup = '';
            var ins1 = parseInt('{{ $search->insurances['ins1'] }}'),
                ins2 = parseInt('{{ $search->insurances['ins2'] }}'),
				days = parseInt('{{ $search->day }}');

			var ins_type = $('#insurance_type').val();

			if(ins_type === '0') { ins1 = 0; ins2 = 0; }
			if(ins_type === '1') { ins1 = ins1 * days; ins2 = 0; }
			if(ins_type === '2') { ins1 = ins1 * days; ins2 = ins2 * days; }

            var data = {
                "_token" :$('input[name="_token"]').val(),
                "email" : $('#email').val().trim(),
                "phone" : $('#phone').val().trim(),
                "password" : $('#password').val(),
                "depart_date" : "{{ $depart_date }}",
                "depart_time" : "{{ $depart_time }}",
                "return_date" : "{{ $return_date }}",
                "return_time" : "{{ $return_time }}",
                "depart_shop" : "{{ $pickup_id }}",
                "depart_shop_name" : "{{ $pickup_name }}",
                "return_shop" : "{{ $dropoff_id }}",
                "return_shop_name" : "{{ $dropoff_name }}",
                "car_category" : "",
                "passenger" : "{{ $max_passenger }}",
                "insurance" : ins_type,
                "insurance_price1" : ins1,
                "insurance_price2" : ins2,
                "smoke" : "{{ $smoking }}",
                "class_id" : "{{ $class_id }}",
                "class_name" : "{{ $class->name }}",
                "class_category" : "",
                "rent_days" : "{{ $search->day - 1 }}泊{{$search->day}}日",
                "price_rent" : "{{ $search->price }}",
                "option_ids" : "{{ $search->selected_option_ids }}",
                "option_indexs" : "{{ $search->selected_option_indexes }}",
                "option_numbers" : "{{ $search->selected_option_nums }}",
                "option_prices" : '{{ $search->selected_option_prices }}',
                "pickup" : pickup,
                "member_check" : checked,
                "rent_dates" : '{{$search->day}}'
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
                    if(status === 'success'){
                        if(result.success === true){
                            location.href = '{{URL::to('/')}}/thankyou';
                        } else {
                            console.log(result);
                            var errors = result.errors,
                                errorsHtml = '<ul>';
                            $.each( errors, function( key, value ) {
                                errorsHtml += '<li>' + value + '</li>'; //showing only the first error.
                            });
                            errorsHtml += '</ul>';
                            showAlert(errorsHtml);
                        }
                    } else {
                        showAlert(result.error);
                    }
                },
                error : function(xhr, status, error) {
                    showAlert(error);
                }
            })
        }
	@endif
	</script>
	<script type="text/javascript">
		var max_passenger = {{$max_passenger}},
			pickup_id = {{$pickup_id}};

		function  showBookingPanel(isMember) {
            $('#check-member').prop('checked', isMember);

		    if(isMember) {
		        $('.only-nonmember').addClass('hidden');
		        $('.only-member').removeClass('hidden');

                validate_rule = {
                    email: { required: true, email: true },
                    password: { required: true}
                };

            } else {
                $('.only-nonmember').removeClass('hidden');
                $('.only-member').addClass('hidden');

                validate_rule = {
                    last_name: { required: true },
                    first_name: { required: true },
                    furi_last_name: { required: true, katakana: true },
                    furi_first_name: { required: true, katakana: true },
                    email: { required: true, email: true },
                    phone: { required: true, number: true, minlength: 9, maxlength: 11 }
                };
            }
			$('.block-booking').fadeIn();
            $('html, body').animate({
                scrollTop: $(".block-booking").offset().top
            }, 1000);

        }

		$(document).on('ready', function() {
			$('.model_list').slick({
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 3333000,
			});
			var search_cond = $('#search_cond').val();
			if(search_cond == '1') $('.search_cond').show();
			else $('.search_cond').hide();
			@if($search_cond == '1')
			$('html, body').animate({
				scrollTop: $("#showinform").offset().top
			}, 2000);
			@endif
		});

		$('.datetime-select').change( function () {
			var depart_date = $('#depart_date').val(),
				depart_time = $('#depart_time').val(),
				return_date = $('#return_date').val(),
				return_time = $('#return_time').val();
			if(depart_date + depart_time >= return_date + return_time) {
			    alert('時間を正しく入力してください。');
			    return false;
			}
			$.ajax({
				url : '{{URL::to('/')}}/getpassengerlist',
				type: 'post',
				data: {
                    _token		: $('input[name="_token"]').val(),
                    class_id 	: $('#class_id').val(),
                    depart_date : depart_date,
                    return_date : return_date,
                    depart_shop : pickup_id,
                    smoke : $('#smoking').val()
				},
				success : function(data, status) {
				    var rows = JSON.parse(data).max_passengers;
                    var count_mps = rows.length;
                    var noset_maxpassenger = count_mps == 0;
                    var count_notempty = 0, mps = [], i;

                    for( i = 0; i < count_mps; i++){
                        var mp = rows[i];
                        if(mp.count > 0) {
                            count_notempty++;
                            mps.push( mp);
                        }
                    }
                    noset_maxpassenger = noset_maxpassenger && (count_notempty == 0);
                    var block = '';
					if(count_mps == 1){
					    var pp = rows[0];
                    	block = '<b>' + pp.max_passenger + '</b>人乗り車両' +
                        '<input type="hidden" name="max_passenger" class="car_passenger" value="'+pp.max_passenger + '">';
					} else if(count_mps > 1) {
						if(count_notempty > 1) {
                            block = '<select name="max_passenger" class="form-control slct-3" style="margin-right: 5px;">';
                            for (var j = 0; j < mps.length; j++) {
                                var qq = mps[j], maxp = qq.max_passenger;
                                block += '<option value="' + maxp + '"';
                                if ( max_passenger == maxp) block += ' selected';
                                block += '>' + maxp + '人乗り</option>';
                            }
                            block += '</select>';
                        } else if(count_notempty == 1){
						    block = '<span >*ご選択日の在庫は<b>'+ mps[0].max_passenger + '</b>名乗りのみとなります。</span>' +
                                '<input type="hidden" name="max_passenger" class="car_passenger" value="'+ mps[0].max_passenger + '">';
                        } else {
						    block = '<span class="alert-msg">現在の車両の在庫はありません。</span>';
                        }
                    } else {
					    block = '<span class="alert-msg">車両定員数が設定されていません。</span>';
					}
					$('#passenger_block').html(block);
					// if(noset_maxpassenger || count_notempty == 0) {
					//     $('.datetime-select').prop('disabled', true);
					//     $('#btn-search').attr('disabled', 'disabled');
					// } else {
                     //    $('.datetime-select').prop('disabled', false);
                     //    $('#btn-search').removeAttr('disabled');
					// }
                },
				error : function(xhr, status, error) {
				    alert('サーバーの応答がありません。網接続を確認してください。');
				}
			})
        });

		function goToSearch() {
            $('#sr_depart_date').val($('#depart_date').val());
            $('#sr_depart_time').val($('#depart_time').val());
            $('#sr_return_date').val($('#return_date').val());
            $('#sr_return_time').val($('#return_time').val());
            $('#sr_depart_shop').val($('#pickup_id').val());
            $('#sr_return_shop').val($('#dropoff_id').val());
            $('#sr_car_category').val('');
            $('#sr_passenger').val('all');
            $('#sr_insurance').val(1);
            $('#sr_smoke').val($('select[name="smoking"]').val());
            $('#sr_pickup').prop('checked', $('#free_pickup_check').prop('checked'));

            var $checked_options = $('input[name="paid_options[]"]:checked');
            var options = [];
            for(var k = 0; k < $checked_options.length; k++) {
                options.push($($checked_options[k]).val());
            }
            $('#sr_options').val(options.join(','));

		    $('#frm_to_search').submit();
        }

		/*search and select*/
		$(".chosen-select").chosen({
			max_selected_options: 5,
			no_results_text: "Oops, nothing found!",
		});
		//get today
		var today = '{{$depart_date}}';
		var tomday = '{{$return_date}}';
		var tod = new Date();
        tod = new Date(tod.getFullYear(), tod.getMonth(), tod.getDate(),0,0,0,0);
        var tom = new Date();
        tom.setDate(tod.getDate()+1);

		$('input[name="depart_date"]').val(today);
		$('input[name="return_date"]').val(tomday);

		var paid_pickup_label = $('#paid_pickup_label');
		var paid_pickup_check = $('#paid_pickup_check');
		var free_pickup_label = $('#free_pickup_label');
		var free_pickup_check = $('#free_pickup_check');

		function disable_paid_pickup() {
            var shop_name = $('#pickup_id option:selected').text();
            if( shop_name.includes('福岡')) {
                paid_pickup_check.prop('disabled', true);
                paid_pickup_label.removeClass('hidden').addClass('hidden');
            } else {
                paid_pickup_check.prop('disabled', false);
                paid_pickup_label.removeClass('hidden');
            }
        }

        paid_pickup_check.change( function () {
            if($(this).prop('checked')) {
                free_pickup_check.prop('disabled', true);
                free_pickup_label.removeClass('hidden').addClass('hidden');
            } else {
                free_pickup_check.prop('disabled', false);
                free_pickup_label.removeClass('hidden');
            }
        });

		$('#pickup_id').change( function ( e ) {
            $('#dropoff_id').val( $(this).val());
            disable_paid_pickup();
            e.preventDefault();
        });

		$('#dropoff_id').change( function ( e ) {
            $('#pickup_id').val($(this).val());
            disable_paid_pickup();
            e.preventDefault();
        });

		$('.opt').change( function () {
            var oid = $(this).val();
            $('.opt_num[oid=' + oid + ']').val($(this).prop('checked')? '1' : '0');
        })

		$('#depart-datepicker, #return-datepicker').datepicker({
{{--            @if(config('app.locale') == 'ja')--}}
                language: "ja",
            {{--@endif--}}
            format: 'yyyy-mm-dd',
            startDate: '{{ date('Y-n-j') }}',
            endDate: '{{ date('Y-m-d', strtotime(date("Y-m-d", time()) . " + 1 year")) }}',
            orientation: "bottom",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });
        var departPicker = $('#depart-datepicker');
        var returnPicker = $('#return-datepicker');

        // time selector initialize
        var dTimepicker = $('#depart_time'),
            rTimepicker = $('#return_time');

        function getAfterHours(hr) {
            // get time string after <hr> hours
            var crt = new Date(),
                crthour = crt.getHours(),
                crtmin = crt.getMinutes(),
                hrAfter = crthour * 1 + hr;
            if(hrAfter < 10) hrAfter = '0' + hrAfter;
            return hrAfter + ':' + crtmin;
        }

        function selectFirstOrLastTime(picker, cond) {
            // cond is 'first' or 'last'
            picker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
            picker.val(picker.find('option:'+ cond).val());
            picker.trigger('chosen:updated');
        }

        // initialize time pickers
        var all_hours_disable = updateTimepicker(dTimepicker, departPicker.datepicker('getDate'), tod, getAfterHours(3));
        if (all_hours_disable) {
            departPicker.datepicker('setStartDate', tom).datepicker('setDate', tom);
            selectFirstOrLastTime(dTimepicker, 'first');
        }
        all_hours_disable = updateTimepicker(rTimepicker, returnPicker.datepicker('getDate'), tod, getAfterHours(4));
        if(all_hours_disable) {
            returnPicker.datepicker('setStartDate', tom).datepicker('setDate', tom);
            selectFirstOrLastTime(rTimepicker, 'last');
        } else {
            if(compareDateWithToday(returnPicker.datepicker('getDate')) > 0)
                selectFirstOrLastTime(rTimepicker, 'last');
        }

        function compareDateWithToday(date) {
            var q = new Date(),
                today = new Date(q.getFullYear(),q.getMonth(),q.getDate(),0,0,0,0);
            if(date.getTime() > today.getTime())
                return 1;
            else if(date.getTime() == today.getTime())
                return 0;
            else
                return -1;
        }

        function updateTimepicker(picker, date, refdate, reftime) {
            var dYear = date.getFullYear(), dMonth = date.getMonth(), dDate = date.getDate();
            var refhm = reftime.split(':');

            var cTime = (new Date(refdate.getFullYear(), refdate.getMonth(), refdate.getDate(), refhm[0], refhm[1],0,0)).getTime();// + 10800 * 1000;
            // if(isdepart === true) cTime += 10800 * 1000;
            var hours = picker.find('option'), cnt = hours.length;
            var first = -1;
            var oldval = picker.val();

            for (var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]);
                var hr_min = hOption.val().split(':');
                var dTime = (new Date(dYear, dMonth, dDate, hr_min[0], hr_min[1], 0, 0)).getTime();
                if (dTime < cTime) {
                    hOption.prop('disabled', true);
                    hOption.css('display', 'none');
                }
                else {
                    hOption.prop('disabled', false);
                    hOption.css('display', 'block');
                    if (first < 0) first = k;
                }
            }
            if (picker.val() < oldval) picker.val(oldval);
            if (compareDateWithToday(date) === 0 && first >= 0) picker.val($(hours[first]).val());
            picker.trigger("chosen:updated");
            return first < 0;
        }

        departPicker.datepicker().on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
            if(compareDateWithToday(minDate) == 0){ // if today
                var disable1 = updateTimepicker(dTimepicker, minDate, tod, getAfterHours(3));
                var disable2 = updateTimepicker(rTimepicker, minDate, tod, getAfterHours(4));
                if(disable1 == true ) {
                    dTimepicker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
                    if(disable2 == true)
                        rTimepicker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
                    minDate.setDate(minDate.getDate() + 1);
                    departPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(dTimepicker, 'first');
                    returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(rTimepicker, 'last');
                }
                if(disable1 == false && disable2 == true) {
                    minDate.setDate(minDate.getDate() + 1);
                    returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(rTimepicker,'last');
                }
            } else if(compareDateWithToday(minDate) > 0) {
                selectFirstOrLastTime(dTimepicker,'first');
                selectFirstOrLastTime(rTimepicker,'last');
            }
        });

        returnPicker.datepicker().on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            var departDate = departPicker.datepicker('getDate');
            var departTime = dTimepicker.val().split(':');
            departTime[0] = departTime[0] * 1 + 1;
            if(departTime[0] < 10) departTime[0] = '0' + departTime[0];
            var all_hours_disable = updateTimepicker(rTimepicker, maxDate, departDate, departTime[0]+':'+departTime[1]);
            if (all_hours_disable) {
                maxDate.setDate(maxDate.getDate() + 1);
                returnPicker.datepicker('setStartDate', maxDate).datepicker('setDate', maxDate);
                selectFirstOrLastTime(rTimepicker,'first');
            }
        });

        dTimepicker.change( function () {
            var dDate = departPicker.datepicker('getDate'),
                dy = dDate.getFullYear(), dm = dDate.getMonth(), dd = dDate.getDate();
            var rDate = returnPicker.datepicker('getDate'),
                ry = rDate.getFullYear(), rm = rDate.getMonth(), rd = rDate.getDate();
            var hr_min = $(this).val().split(':');
            var dTime = (new Date(dy, dm, dd, hr_min[0], hr_min[1], 0, 0)).getTime() + 3600 * 1000;

            var hours = rTimepicker.find('option').removeAttr('disabled').css('display','block'),
                cnt = hours.length, oldval = rTimepicker.val();
            var index = -1;
            for (var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]), hm = hOption.val().split(':');
                var rTime = (new Date(ry, rm, rd, hm[0], hm[1], 0, 0)).getTime();
                if (rTime <= dTime)
                    hOption.attr('disabled', 'disabled').css('display', 'none');
                else {
                    // hOption.removeAttr('disabled').css('display','block');
                    if (index < 0 && hOption.val() == oldval) {
                        hOption.attr('selected', true);
                        index = k;
                    }
                }
            }
            if (index < 0) {
                dDate.setDate(dDate.getDate() + 1);
                returnPicker.datepicker('setStartDate', dDate).datepicker('setDate', dDate);
                selectFirstOrLastTime(rTimepicker,'first');
            }
            rTimepicker.trigger("chosen:updated");
        });

        rTimepicker.change( function () {
            var dDate = departPicker.datepicker('getDate'),
                rDate = returnPicker.datepicker('getDate');
            var hr_min = dTimepicker.val().split(':');
            var dTime = (new Date(dDate.getFullYear(), dDate.getMonth(), dDate.getDate(), hr_min[0], hr_min[1], 0, 0)).getTime() + 60 * 60 * 1000;
            hr_min = $(this).val().split(':');
            var rTime = (new Date(rDate.getFullYear(), rDate.getMonth(), rDate.getDate(), hr_min[0], hr_min[1], 0, 0)).getTime();

            if( rTime < dTime) {
                alert('return time is earlier than depart time');
            }
        });

		//search function
		function search_submit() {
			$('#search_cond').val('1');

			// check if depart date time < return date time
			var depart_date = $('#depart_date').val();
			var depart_time = $('#depart_time').val() + ':00';
            //var dtime = (new Date(depart_date + ' ' + depart_time)).getTime();
            var dtime = (new Date(depart_date + 'T' + depart_time + 'Z')).getTime();

            var return_date = $('#return_date').val();
            var return_time = $('#return_time').val()+ ':00';
           // var rtime = (new Date($('#return_date').val() + ' ' + $('#return_time').val())).getTime();
            var rtime = (new Date(return_date + 'T' + return_time + 'Z')).getTime();

            if(dtime + 5400*1000 < rtime) {
                $('#search').submit();
            } else {

            }
		}
		//option view
		function viewOption() {
			$('.option_ui').toggle( "slow", function() {
				// Animation complete.
			});
		}

		$('.free_pickup').change(function () {

            var checked = $(this).prop('checked');
            if(checked) {
                $('.free_pickup').prop('checked', false);
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }

        });

		//send confirm page
		function goConfirm(car){
		    var car_count = $('#car_count').val();
		    if(car_count === '0') {
                $('#modalError').modal('show');
		        return false;
			}

			@if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)
            dataLayer.push({
                'event': 'addToCart',
                'ecommerce': {
                    'currencyCode': 'JPY',
                    'add': {                       // 'add' actionFieldObject measures.
                        'products': [{              //  adding a product to a shopping cart.
                            'name': car.class_name,
                            'id': car.class_id,
                            'price': car.all_price,
                            // 'brand': 'エスティマ',
                            'quantity': 1
                        }]
                    }
                }
            });
			@endif

            $('#data_depart_date').val('{{$depart_date}}');
			$('#data_depart_time').val('{{$depart_time}}');
			$('#data_return_date').val('{{$return_date}}');
			$('#data_return_time').val('{{$return_time}}');
			$('#data_depart_shop').val('{{$pickup_id}}');
			$('#data_depart_shop_name').val('{{$pickup_name}}');
			$('#data_return_shop').val('{{$dropoff_id}}');
			$('#data_return_shop_name').val('{{$dropoff_name}}');
			$('#data_car_category').val('');
			$('#data_passenger').val(car.passenger.min_passenger);
			$('#data_insurance').val('{{$insurance}}');
			$('#data_smoke').val('{{$smoking}}');
			$('#data_class_id').val(car.class_id);
			$('#data_class_name').val(car.class_name);
			$('#data_insurance_price1').val({{ $class->insurance['ins1'] }}*car.day);
			$('#data_insurance_price2').val({{ $class->insurance['ins2'] }}*car.day);
			$('#data_class_category').val('');
			$('#data_car_photo').val(car.thumb_path);
			$('#data_rent_days').val(car.night+'泊'+car.day+'日');
			$('#data_price_rent').val(car.price);
			var options = car.options;
			var option_ids 		= [];
			var option_names 	= [];
			var option_numbers 	= [];
			var option_costs	= [];
			var option_prices	= [];
			for(var i =0 ; i < options.length;i++ ) {
				option_ids.push(options[i].id);
				option_names.push(options[i].abbriviation);
				option_numbers.push(options[i].number);
				option_costs.push(options[i].price/options[i].number);
				option_prices.push(options[i].price);
			}
			$('#data_option_ids').val(option_ids.toString());
			$('#data_option_names').val(option_names.toString());
			$('#data_option_numbers').val(option_numbers.toString());
			$('#data_option_costs').val(option_costs.toString());
			$('#data_option_prices').val(option_prices.toString());
			$('#data_price_all').val(car.all_price);
			// find if pickup was checked
			var pickup = $('.free_pickup:checked').val();
			console.log(pickup);
			if(pickup == undefined) pickup == '';
			$('#data_pickup').val(pickup);

			$('#booking-submit').submit();
		}

		function gotoSuggestDetail(class_name, class_id, price, position) {
			@if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)
            dataLayer.push({
                'event': 'productClick',
                'ecommerce': {
                    'click': {
                        'actionField': {'list': 'Suggested'},  // Please name the “list’ as suggested
                        'products': [{
                            'name'		: class_name,
                            'id'		: class_id,
                            'price'		: price,
                            'position'	: position
                        }]
                    }
                }
            });
            @endif

            location.href = "{{URL::to('/')}}/carclass-detail/" + class_id;
		}

        function gotoConfirm(class_id, class_name) {
            $('#data_depart_date').val('{{$depart_date}}');
            $('#data_depart_time').val('{{$depart_time}}');
            $('#data_return_date').val('{{$return_date}}');
            $('#data_return_time').val('{{$return_time}}');
            $('#data_depart_shop').val('{{$pickup_id}}');
            $('#data_depart_shop_name').val('{{$pickup_name}}');
            $('#data_return_shop').val('{{$dropoff_id}}');
            $('#data_return_shop_name').val('{{$dropoff_name}}');
            $('#data_car_category').val('');
            var search_passenger = $('.car_passenger[class_id="' + class_id + '"]').val();

            var rent_price = $('.price_rent[class_id="' + class_id + '"]').val(),
                all_price = $('.price_all[class_id="' + class_id + '"]').val();
            var cls_selector = 'class_id="' + class_id + '"';
            $('#data_passenger').val(search_passenger);
            $('#data_insurance').val($('#insurance').val());
            $('#data_class_id').val(class_id);
            $('#data_smoke').val($('.car_smoking['+ cls_selector +']').val());
            $('#data_class_name').val($('.car_title['+ cls_selector +']').html().trim());
            $('#data_insurance_price1').val($('.insurance_price1['+ cls_selector +']').val());
            $('#data_insurance_price2').val($('.insurance_price2['+ cls_selector +']').val());
            $('#data_class_category').val($('.car_category['+ cls_selector +']').html().trim());
            $('#data_car_photo').val($('.car_photo['+ cls_selector +']').val());
            $('#data_rent_days').val($('.rent_days['+ cls_selector +']').val());
            $('#data_price_rent').val(rent_price);
            $('#data_option_ids').val($('.option_ids['+ cls_selector +']').val());
            $('#data_option_names').val($('.option_names['+ cls_selector +']').val());
            $('#data_option_numbers').val($('.option_numbers['+ cls_selector +']').val());
            $('#data_option_costs').val($('.option_costs['+ cls_selector +']').val());
            $('#data_option_prices').val($('.option_prices['+ cls_selector +']').val());
            $('#data_price_all').val(all_price);
            $('#data_pickup').val($('.car_pickup['+ cls_selector +']').val());

            $('#booking-submit').submit();
        }

	</script>
@endsection
@section('meta_description', strip_tags($class->staff_comment))

@section('og_tags')
    <meta property="og:title" content="レンタカークラス：{{$class->name}}の詳細" />
    <meta property="og:url" content="{{ url('/carclass-detail/'.$class->id) }}" />
    <meta property="og:image" content="{!! URL::to('/').$class->thumb_path !!}" />
    <meta property="og:description" content="{{ strip_tags($class->staff_comment) }}" />
    <meta property="og:site_name" content="ハコレンタカー" />
@endsection
