@extends('layouts.frontend')
@section('template_title')
よくある質問
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/faq.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
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
                            <span>@lang('faqusage.faq') </span> <!--{{trans('fs.current')}}-->
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
								<h1>@lang('faqusage.faq') </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row page-header-body">
                        <!-- main content -->
						<div class="col-md-8 col-sm-8 col-xs-12">
							<img src="{{ URL::to('/') }}/img/faq-top.png" class="img-responsive center-block">

							<div class="stepbox2">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 faq-main-block">
										<div class="faq-listing-main">
											<h3 class="faq-title"> @lang('faqusage.faq') </h3>
											<div class="faq_ctg">
												<ul>
													<li><a href="{{URL::to('/')}}/faq/price#top" class=""> @lang('faqusage.about') </a></li>
													<li><a href="{{URL::to('/')}}/faq/insurance#top" class=""> @lang('faqusage.insurance') </a></li>
													<li><a href="{{URL::to('/')}}/faq/option#top" class="">@lang('faqusage.vehicle') </a></li>
													<li><a href="{{URL::to('/')}}/faq/license#top" class="">@lang('faqusage.license')</a></li>
													<li><a href="{{URL::to('/')}}/faq/booking#top" class="">@lang('faqusage.reservation')</a></li>
													<li><a href="{{URL::to('/')}}/faq/passing#top" class="">@lang('faqusage.delivery')</a></li>
													<li><a href="{{URL::to('/')}}/faq/usage#top" class="active">@lang('faqusage.aboutus')</a></li>
													<li><a href="{{URL::to('/')}}/faq/member#top" class=""> @lang('faqusage.membership')</a></li>
													<li><a href="{{URL::to('/')}}/faq/other#top" class=""> @lang('faqusage.other')</a></li>
												</ul>
												<a id="top"></a>
											</div>
										</div>
										<!-------------faq-01 ------------>
										<div class="panel-group m_B20 m_T20">
											<h3>@lang('faqusage.aboutus')</h3>
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse1" class="collapsed" aria-expanded="false"><span class="icon-faq"> @lang('faqusage.breakdown')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse1" class="panel-collapse collapse in" aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqusage.breakdown-a')<br>
													<a href="{{URL::to('/')}}/shop/fukuoka-airport-rentacar-shop">@lang('faqusage.shop-fukuoka')</a><br>
													<a href="{{URL::to('/')}}/shop/naha-airport-rentacar-shop">@lang('faqusage.shop-okinawa')</a></p>
												</div>
											</div>
										</div>


										<!-------------faq-04 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse4" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqusage.accident')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse4" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqusage.accident-a')<br>
													<a href="{{URL::to('/')}}/shop/fukuoka-airport-rentacar-shop">@lang('faqusage.shop-fukuoka')</a><br>
													<a href="{{URL::to('/')}}/shop/naha-airport-rentacar-shop">@lang('faqusage.shop-okinawa')</a></p>
												</div>
											</div>
										</div>


										<!-------------faq-07 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse7" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqusage.extension')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse7" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqusage.extension-a')</p>
												</div>
											</div>
										</div>

										

										<!-------------faq-10 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse10" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqusage.ferry')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse10" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqusage.ferry-a')</p>
												</div>
											</div>
										</div>


										<!-- faq -->
									</div>
								</div>
							</div>



						</div>
						<!-- main content -->

                        @include('partials.faqsidebar')
                    </div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link">@lang('faqusage.toppage')</a>
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
