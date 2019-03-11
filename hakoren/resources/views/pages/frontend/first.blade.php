@extends('layouts.frontend')

@section('template_title')
初めての方
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
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
                            <span>初めての方へ</span>
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
								<h1> @lang('faqfirst.firsttime') </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
					<div class="box-shadow">
                    <div class="row">
						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
							<?php
                            	$hajimete_top = $util->Tr('hajimete-top');
							?>
							<img class="center-block img-responsive" src="img/pages/first/{{$hajimete_top}}.png" alt="ハコレンタカー　初めてご利用の方へ">
						</div>
					</div>
					</div>
					<!--<div class="box-shadow">
					<div class="row m_TB30">
						<div class="content-main col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<a href="{{URL::to('/')}}/first#first-step"><img class="center-block img-responsive m_B10" src="img/pages/first/btn-fukuoka.png" alt="ハコレンタカー　福岡からご予約・出発・ご返却の流れ"></a>
						</div>
						<div class="content-main col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<a href="{{URL::to('/')}}/first#first-step"><img class="center-block img-responsive m_B10" src="img/pages/first/btn-okinawa.png" alt="ハコレンタカー　沖縄からご予約・出発・ご返却の流れ"></a>
						</div>
						<div class="content-main col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<a href="{{URL::to('/')}}/faq/price"><img class="center-block img-responsive m_B10" src="img/pages/first/btn-faq.png" alt="ハコレンタカー　よくあるご質問"></a>
						</div>
					</div>
					</div>-->

					<!-- BEGIN 4つのお約束 -->
					<div class="box-shadow">
						<div class="row first-step-sec" style="min-height:200px;">
							<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<!--<h2>ハコレンタカーについて</h2>-->
								 <h3 class="promis"> @lang('faqfirst.promise') </h3>
								<div class="row">
									<div class=" col-lg-3 col-md-3 col-sm-6 col-xs-12 first_content_ht">
										<h4 class="promis-ttl"> @lang('faqfirst.price') </h4>
										<p> @lang('faqfirst.contact') </p>
									 </div>
									<div class=" col-lg-3 col-md-3 col-sm-6 col-xs-12 first_content_ht">
										<h4 class="promis-ttl"> @lang('faqfirst.compensation') </h4>
										<p>@lang('faqfirst.insurance')</p>
									 </div>
									<div class=" col-lg-3 col-md-3 col-sm-6 col-xs-12 first_content_ht">
										<h4 class="promis-ttl"> @lang('faqfirst.clean') </h4>
										<p> @lang('faqfirst.confidence')</p>
									 </div>
									<div class=" col-lg-3 col-md-3 col-sm-6 col-xs-12 first_content_ht">
										<h4 class="promis-ttl"> @lang('faqfirst.dedicate') </h4>
										<p> @lang('faqfirst.customer') </p>
									 </div>
								</div>
								<p class="appeal01"> @lang('faqfirst.expect')</p>
							</div>
						</div>
					</div>

					<!-- begin 予約・出発・返却の流れ-->
					<div class="box-shadow">
					<div class="row first-step-sec">
						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 id="first-step"> @lang('faqfirst.return') </h2>
							<h3 class="promis"> @lang('faqfirst.step') </h3>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
							<?php
                             $first_step = $util->Tr('first-step');
                             $first_step_sp = $util->Tr('first-step-sp');
							?>
							<img class="center-block img-responsive hidden-xs" src="img/pages/first/{{$first_step}}.png" alt="ハコレンタカー　ご利用ステップ">
							<img class="center-block img-responsive visible-xs" src="img/pages/first/{{$first_step_sp}}.png" alt="ハコレンタカー　ご利用ステップ" style="width:50%;">
							<hr class="skyblue">
						</div>

						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">

							<!-- begin STEP 1-->
							<h3> <span class="step-title">STEP 1</span> @lang('faqfirst.reservation')</h3>
							<p class="appeal01"> @lang('faqfirst.date')</p>

							<div class="row m_T30 m_B50">
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
									<div class="step-rbox">
										<h3 class="promis"> @lang('faqfirst.quicllaunch') </h3>
										<p class="appeal01">@lang('faqfirst.start')</p>
										<p class="appeal01">@lang('faqfirst.inform')</p>

									   <div class="row">
											<div class="col-md-4 col-md-offset-4 col-xs-12 first_btn_grey p_TB30">
												<a href="{{URL::to('/')}}/search-car" class="btn btn-default btn-lg">@lang('faqfirst.make')</a>
											</div>
										</div>
									</div>

								</div>
							</div>

							<!-- begin STEP 2-->
							<h3 id="step2"><span class="step-title">STEP 2</span> @lang('faqfirst.departure')</h3>
							<div class="row m_T30 m_B50">
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
									<div class="step-rbox">
										<div class="quick-box">
											<h4><span class="btm-border">@lang('faqfirst.visit')</span></h4>
											<div class="type-blue">
												<ul>
													<li>@lang('faqfirst.license')</li>
													<li>@lang('faqfirst.webpayment')</li>
												</ul>
											</div>
										</div>
										<p class="appeal01">@lang('faqfirst.etc')</p>
										<div class="triangle"></div>
										<p class="appeal01"> @lang('faqfirst.staff')</p>
										<div class="triangle"></div>
										<p class="appeal01">@lang('faqfirst.key')</p>
										<div class="triangle"></div>
										<p class="appeal02"> @lang('faqfirst.departure1')</p>
										<p class="appeal01"> @lang('faqfirst.trip')</p>
									</div>
								</div>
							</div>

							<!-- begin STEP 3-->
							<h3><span class="step-title">STEP 3</span> @lang('faqfirst.during')</h3>
							<p class="appeal01"> @lang('faqfirst.follow')</p>
							<p class="text-center">
								@lang('faqfirst.confirm')
							<br></p>
							<div class="row m_T30 m_B50">
								<div class="col-lg-8 col-md-8 col-sm-10 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
									<div class="step-rbox2">
										<img style="width:80px;" class="center-block img-responsive" src="img/pages/first/mark.png" alt="ご利用中のご注意">
										<p class="appeal03 m_T20 fs16">@lang('faqfirst.accident')</p>
									</div>
								</div>
							</div>

							<!-- begin STEP 4-->
							<h3><span class="step-title">STEP 4</span>  @lang('faqfirst.time')</h3>
							<div class="row m_T30 m_B50">
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
									<div class="quick-box">
										<h4><span class="btm-border">@lang('faqfirst.returning')</span></h4>
										<div class="type-blue">
											<ul>
												<li>@lang('faqfirst.full')</li>
												<li>@lang('faqfirst.behind')</li>
												<li>@lang('faqfirst.store')</li>
												<li>@lang('faqfirst.charge')</li>
											</ul>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					</div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link">@lang('faqfirst.toppage')</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
    </div>
@endsection

@section('style')
  <style>

  </style>
@endsection

@section('footer_scripts')
@endsection
