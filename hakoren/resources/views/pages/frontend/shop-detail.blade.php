@extends('layouts.frontend')

@section('template_title')
店舗・送迎
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_shop_detail.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
    <style>
    </style>
@endsection
@section('content')
	<script>
		@if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)
        dataLayer.push({
            'ecommerce': {
                'currencyCode': 'JPY',
                'impressions': [
					@foreach($classes as $key=>$cls)
                    {
                        'name': '{{ $cls->name }}',
                        'id': '{{ $cls->class_id }}',
                        'price': '{{ $cls->price }}',
                        {{--'brand': '{{ $cls->abbriviation }}',--}}
                        'list': 'Shop {{ $shop->region_code }}',
                        'position': {{ $key + 1 }}
                    },
					@endforeach
                ]
            }
        });
        @endif
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
							<?php
								$name = $util->Tr('name');
							?>
                            <span>{!! $shop->$name !!} @lang('shop.information')</span>
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
							<h1>{{ $shop->$name }} @lang('shop.information')</h1>
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
										<?php
                                        	$thumb_path = $util->Tr('thumb_path');
                                        	$name = $util->Tr('name');
										?>
										<img src="{{url('/').$shop->$thumb_path}}" class="img-responsive center-block" style="margin-bottom: 10px;">
									</div>
								</div>
								<div class="row shopbox02">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<a href="tel:{{ $shop->phone }}">
											<div style="font-size:20px;text-align:center;padding:10px 30px; background:#e20001;color:#fff; line-height:1.2em;">
												<p style="margin-top:0;">
													{{ $shop->$name }} @lang('shop.shopguide')
												</p>
												{{ $shop->phone }}
											</div>
										</a>
									</div>
								</div>
								<div class="row shopbox01">
									<div class="col-md-10 col-md-offset-1">
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												<?php
													$hakoren_shop_info = $util->Tr('hakoren-shop-info');
                                                	$hakoren_shop_pickup = $util->Tr('hakoren-shop-pickup');
                                                	$hakoren_shop_rentacar = $util->Tr('hakoren-shop-rentacar');
													?>
												{{--<a href="#info">--}}
													<img src="{{URL::to('/')}}/img/pages/shop/{{$hakoren_shop_info}}.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer" onclick="scrollToSection('#info')">
												{{--</a>--}}
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												{{--<a href="#pickup">--}}
													<img src="{{URL::to('/')}}/img/pages/shop/{{$hakoren_shop_pickup}}.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer" onclick="scrollToSection('#pickup')">
												{{--</a>--}}
											</div>
											<!--
											<div class="col-lg-3 col-md-3">
												{{--<a href="#news">--}}
													<img src="{{URL::to('/')}}/img/pages/shop/hakoren-shop-news.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer" onclick="scrollToSection('#news')">
												{{--</a>--}}
											</div>
											-->
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												{{--<a href="#rentacar">--}}
													@if($util->lang()== 'ja')
														<img src="{{URL::to('/')}}/img/pages/shop/{{$hakoren_shop_rentacar}}.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer" onclick="scrollToSection('#rentacar')">
													@elseif($util->lang()== 'en')
														<a href="{{URL::to('/')}}/search-car">
														<img src="{{URL::to('/')}}/img/pages/shop/{{$hakoren_shop_rentacar}}.png" class="img-responsive center-block" style="margin-bottom: 10px;cursor: pointer">
														</a>
													@endif
												{{--</a>--}}
											</div>
										</div>
									</div>
								</div>
								<h2 id="info"> @lang('shop.shopinform')</h2>
								<div class="row sougei-block">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<table class="table shop-detail-tbl">
											<?php
                                            	$postal 		= $util->Tr('postal');
                                            	$prefecture 	= $util->Tr('prefecture');
                                            	$city			= $util->Tr('city');
												$address1		= $util->Tr('address1');
                                            	$address2		= $util->Tr('address2');
                                            	$comment		= $util->Tr('comment');
                                            	$content1		= $util->Tr('content1');
											?>
											<tr>
												<th> @lang('shop.address')</th>
												<td>
													<small>〒{{ $shop->$postal }}</small>
													{{ $shop->$prefecture }}{{ $shop->$city }}{{ $shop->$address1 }}{{ $shop->$address2 }}
												</td>
											</tr>
											<tr>
												<th> @lang('shop.businesstime') </th>
												<td class="b-time">
													<span class="dblock" style="margin-right: 10px; margin-bottom:5px;">9:00 ~ 19:30</span><span style="padding:5px 10px; background:#e20001; color:#fff; font-weight:500;-webkit-border-radius: 3px !important;-moz-border-radius: 3px !important;border-radius: 3px !important;"> @lang('shop.dayweek') </span>
												</td>
											</tr>
											<tr>
												<th>TEL</th>
												<td><a href="tel:{{ $shop->phone }}" style="color: inherit">{{ $shop->phone }}</a></td>
											</tr>
											<tr>
												<th> @lang('shop.contact') </th>
												<td>
													<a href="{{URL::to('/')}}/contact" style="color: inherit"> @lang('shop.inquiry') </a>
												</td>
											</tr>

											<tr>
												<th>Facebook URL</th>
												<td><a href="https://www.facebook.com/hakorentcar/" target="_blank" style="color: inherit"> @lang('shop.facebook') </a></td>
											</tr>
											@if(!empty($shop->comment))
											<tr>
												<th> @lang('shop.comment') </th>
												<td style="text-align: justify;">
													{{ $shop->$comment }}
												</td>
											</tr>
											@endif

										</table>
									</div>　
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="gmap" id="map_canvas">
											<iframe width="500" height="700" frameborder="0" style="border:0"
													src="https://www.google.com/maps/embed/v1/place?
q=〒{{$shop->postal}}{{ $shop->prefecture }}{{ $shop->city }}{{ $shop->address1 }}{{ $shop->address2 }}&key=AIzaSyALiOyzY1rWm_pOnfh4impcuME0VRFaE3I"></iframe>
										</div>
									</div>
								</div>
								<!-- section 1 -->
								<div id="pickup"></div>
								<!-- sougei-block 1 -->
								<div class="sougei-block">
									<div class="clearfix bg-white">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<!--
											<div class="col-xs-12 text-left" style="padding: 15px;">
												<img src="{{$pickup->thumb_path}}">
											</div>
											-->
											<div class="shop_content">
												{!! $pickup->$content1 !!}
											</div>
										</div>
									</div>
								</div>
								<!--
								<h2 id="news">最新情報とお得情報</h2>
								<div class="sougei-block">
									<div class="clearfix bg-white">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											@foreach($posts as $post)
											<p class="ellipsis">
												{{ date('Y年n月j日', strtotime($post->publish_date)) }}&emsp;
												<span class="{{ ($post->post_tag_id == 1)? 'news':'promotion'}}">
													{{ ($post->post_tag_id == 1)? '最新情報':'お得情報'}}
												</span>
												&emsp;
                                                {{--https://www.motocle8.com/view-post/news-its-a-test-from-bai--}}
                                                <a href="{{URL::to('/')}}/view-post/{{$post->slug}}" style="color:#555;">{{ $post->title }}</a>
											</p>
											@endforeach
										</div>
									</div>
								</div>
								-->
							</div>
							<div id="rentacar"></div>
						</div>

						@if($util->lang()=='ja')
						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 ribbon_main">
							<?php
                              $models = $util->Tr('models');
                              $name   = $util->Tr('name');
							?>
							@foreach($classes as $key => $class)
								@if($class->carclass_status == 1 && $class->cs_id == $shop->id )
									<div class="box-shadow relative">
										<div class="ribbon-block bg-grad-red">
											<h3>{{$class->name}}</h3><!--@if($class->smoke == '0') 禁煙車 @endif-->
										</div>
										<!-- ROW -->
										<div class="row margin-bottom-40 carclass-margin">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<img src="{{URL::to('/')}}/{{$class->thumb}}" class="img-responsive center-block m_Txs60">
												<p class="sml-txt"> @lang('shop.imgdiagram') </p>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<h3 class="jnum"> @lang('shop.passenger')： <span>{{$class->tag}}</span> @lang('shop.name')</h3>

												<table class="table carclass-tbl">
													<tr>
														<th width="30%"><span class="bg-grad-gray"> @lang('shop.model')</span></th>
														<td>
															{{ implode('、', $class->$models) }}
														</td>
													</tr>
													<tr>
														<th><span class="bg-grad-gray"> @lang('shop.option')</span></th>
														<td>
															<?php $op = 0;
															$ops = [];
															foreach($class->options as $option) {
																if($option->google_column_number < 200) {
																	$ops[] = $option->$name;
																}
															}
															echo implode('、', $ops);
															?>
														</td>
													</tr>
													<tr>
														<th>
															<p class="bg-grad-gray" style="margin: 0; border:1px solid #ccc;padding: 2px 5px;">
																@lang('shop.dayprice') <br>( @lang('shop.1n2d'))
															</p>
														</th>
														<td>
															{{number_format($class->price)}} @lang('shop.yen') /1 @lang('shop.day')( @lang('shop.taxcharge'))<br>
															({{number_format($class->price * 2)}} @lang('shop.yen') @lang('shop.1n2d'))
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn">
													<a class="bg-grad-red" onclick="gotoDetail('{{$class->name}}', {{$class->class_id}}, {{$class->price}},{{$key + 1}})"> @lang('shop.viewdetail') </a>
												</div>
											</div>
										</div>
										<!-- ROW -->
									</div>
									@endif
								@endforeach

								@if($shop->id == 4)


								<div class="box-shadow relative">
									<div class="ribbon-block bg-grad-red">
										<h3>MB</h3> {{--@if($class->smoke == '0') 禁煙車--}}
									</div>
									<!-- ROW -->
									<div class="row margin-bottom-40 carclass-margin">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<img src="{{URL::to('/')}}/img/mb.png" class="img-responsive center-block m_Txs60">
											<p class="sml-txt"> @lang('shop.imgdiagram') </p>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<h3 class="jnum"> @lang('shop.passenger')： <span>26～29</span> @lang('shop.name') </h3>

											<table class="table carclass-tbl">
												<tr>
													<th width="30%"><span class="bg-grad-gray"> @lang('shop.model') </span></th>
													<td>
														@lang('shop.coaster') 、 @lang('shop.riese')
													</td>
												</tr>
												<tr>
													<th><span class="bg-grad-gray"> @lang('shop.option')</span></th>
													<td>
														@lang('shop.babyseat') 、 @lang('shop.juniorseat') 、 @lang('shop.childseat') 、 @lang('shop.airpotpickup') 、 @lang('shop.etccard')
													</td>
												</tr>
												<tr>
													<th>
														<p class="bg-grad-gray" style="margin: 0; border:1px solid #ccc;padding: 2px 5px;">
															@lang('shop.price')
														</p>
													</th>
													<td>
														21,900 @lang('shop.yen')/1 @lang('shop.day')( @lang('shop.taxcharge'))
(43,800 @lang('shop.yen') @lang('shop.1n2d')) <br/><br/> @lang('shop.rantal') <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> @lang('shop.inquire')
													</td>
												</tr>
											</table>
											<div class="clearfix bg-white calc-btn">
												<a class=" bg-grad-red" href="{{URL::to('/')}}/contact"> @lang('shop.inquires') </a>
											</div>
										</div>
									</div>
									<!-- ROW -->
								</div>

								<div class="box-shadow relative">
									<div class="ribbon-block bg-grad-red">
										<h3> @lang('shop.lexus460') </h3> {{--@if($class->smoke == '0') 禁煙車--}}
									</div>
									<!-- ROW -->
									<div class="row margin-bottom-40 carclass-margin">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<img src="{{URL::to('/')}}/img/ssp2.jpg" class="img-responsive center-block m_Txs60">
											<p class="sml-txt"> @lang('shop.imgdiagram') </p>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<h3 class="jnum"> @lang('shop.passenger')： <span>5</span> @lang('shop.name')</h3>

											<table class="table carclass-tbl">
												<tr>
													<th width="30%"><span class="bg-grad-gray"> @lang('shop.model') </span></th>
													<td>
														@lang('shop.lexus460')
													</td>
												</tr>
												<tr>
													<th><span class="bg-grad-gray"> @lang('shop.option') </span></th>
													<td>

													</td>
												</tr>
												<tr>
													<th>
														<p class="bg-grad-gray" style="margin: 0; border:1px solid #ccc;padding: 2px 5px;">
															@lang('shop.price')
														</p>
													</th>
													<td>
														@lang('shop.lexus460desc') <br/> @lang('shop.rental') <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> @lang('shop.inquire')
													</td>
												</tr>
											</table>
											<div class="clearfix bg-white calc-btn">
												<a class=" bg-grad-red" href="{{URL::to('/')}}/contact"> @lang('shop.inquires') </a>
											</div>
										</div>
									</div>
									<!-- ROW -->
								</div>

								<div class="box-shadow relative">
									<div class="ribbon-block bg-grad-red">
										<h3> @lang('shop.lexus460_') </h3>{{-- @if($class->smoke == '0') 禁煙車 @endif --}}
									</div>
									<!-- ROW -->
									<div class="row margin-bottom-40 carclass-margin">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<img src="{{URL::to('/')}}/img/ssp3.jpg" class="img-responsive center-block m_Txs60">
											<p class="sml-txt"> @lang('shop.imgdiagram') </p>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<h3 class="jnum"> @lang('shop.passenger')： <span>5</span> @lang('shop.name') </h3>

											<table class="table carclass-tbl">
												<tr>
													<th width="30%"><span class="bg-grad-gray"> @lang('shop.model') </span></th>
													<td>
														@lang('shop.lexus460_')
													</td>
												</tr>
												<tr>
													<th><span class="bg-grad-gray"> @lang('shop.option')</span></th>
													<td>

													</td>
												</tr>
												<tr>
													<th>
														<p class="bg-grad-gray" style="margin: 0; border:1px solid #ccc;padding: 2px 5px;">
															@lang('shop.price')
														</p>
													</th>
													<td>
														@lang('shop.lexus460desc') <br/> @lang('shop.rental') <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> @lang('shop.inquire')
													</td>
												</tr>
											</table>
											<div class="clearfix bg-white calc-btn">
												<a class=" bg-grad-red" href="{{URL::to('/')}}/contact"> @lang('shop.inquires')</a>
											</div>
										</div>
									</div>
									<!-- ROW -->
								</div>
								@endif
						</div>
						@endif
                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 hidden-lg hidden-md hidden-sm hidden-xs">
                            <div class="portlet portlet-fit light cont-box">
                                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10">
                                    <a href="#"><img class="center-block img-responsive" src="" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden-xs">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link"> @lang('shop.toppage') </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
    </div>

    @include('modals.modal-membership')
    @include('modals.modal-error')
@endsection

@section('style')
  <style>
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
  </style>

@endsection

@section('footer_scripts')
	<script>
		function scrollToSection(target) {
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 1000);
		}

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
