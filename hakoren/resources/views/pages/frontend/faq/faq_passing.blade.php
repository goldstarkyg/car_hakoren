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
                            <span> @lang('faqpassing.faq') </span>  <!--{{trans('fs.current')}}-->
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
								<h1> @lang('faqpassing.faq') </h1>
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
											<h3 class="faq-title"> @lang('faqpassing.faq') </h3>
											<div class="faq_ctg">
												<ul>
													<li><a href="{{URL::to('/')}}/faq/price#top" class=""> @lang('faqpassing.about') </a></li>
													<li><a href="{{URL::to('/')}}/faq/insurance#top" class=""> @lang('faqpassing.insurance') </a></li>
													<li><a href="{{URL::to('/')}}/faq/option#top" class=""> @lang('faqpassing.vehicle') </a></li>
													<li><a href="{{URL::to('/')}}/faq/license#top" class=""> @lang('faqpassing.license')</a></li>
													<li><a href="{{URL::to('/')}}/faq/booking#top" class=""> @lang('faqpassing.reservation')</a></li>
													<li><a href="{{URL::to('/')}}/faq/passing#top" class="active"> @lang('faqpassing.delivery')</a></li>
													<li><a href="{{URL::to('/')}}/faq/usage#top" class=""> @lang('faqpassing.aboutus')</a></li>
													<li><a href="{{URL::to('/')}}/faq/member#top" class=""> @lang('faqpassing.membership')</a></li>
													<li><a href="{{URL::to('/')}}/faq/other#top" class=""> @lang('faqpassing.other')</a></li>
												</ul>
												<a id="top"></a>
											</div>
										</div>
										

										<!-------------faq-02 ------------>
										<div class="panel-group m_B20">
											<h3>@lang('faqusage.delivery')</h3>
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse2" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.items')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse2" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.items-a')<br>
													<a href="{{URL::to('/')}}/shop/fukuoka-airport-rentacar-shop">@lang('faqpassing.shop-fukuoka')</a><br>
													<a href="{{URL::to('/')}}/shop/naha-airport-rentacar-shop">@lang('faqpassing.shop-okinawa')</a></p>
												</div>
											</div>
										</div>

										<!-------------faq-03 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse3" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.change')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse3" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.change-a')</p>
												</div>
											</div>
										</div>

										<!-------------faq-04 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse4" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.pickup')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse4" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.freepickup')</p>
												</div>
											</div>
										</div>

										<!-------------faq-05 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse5" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.dispatch')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse5" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.smart')</p>
												</div>
											</div>
										</div>

										<!-------------faq-06 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse6" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.keep')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse6" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.keep-a')</p>
												</div>
											</div>
										</div>

										<!-------------faq-07 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse7" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.gasoline')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse7" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.fill')</p>
												</div>
											</div>
										</div>

										<!-------------faq-08 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse8" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.extension')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse8" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.extension-a')</p>
												</div>
											</div>
										</div>

										<!-------------faq-09 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse9" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.repay')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse9" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.repay-a')</p>
												</div>
											</div>
										</div>

										<!-------------faq-10 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse10" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.quiet-hour')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse10" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.business-hours')<br>
													<a href="{{URL::to('/')}}/shop/fukuoka-airport-rentacar-shop">@lang('faqpassing.shop-fukuoka')</a><br>
													<a href="{{URL::to('/')}}/shop/naha-airport-rentacar-shop">@lang('faqpassing.shop-okinawa')</a></p>
												</div>
											</div>
										</div>

										<!-------------faq-11 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse11" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqpassing.note')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse11" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqpassing.note-a')</p>
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
                            <a href="#" class="bg-carico totop-link">@lang('faqpassing.toppage')</a>
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
