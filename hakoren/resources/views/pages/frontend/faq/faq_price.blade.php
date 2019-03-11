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
                            <span> @lang('faq.faq') </span> <!--{{trans('fs.current')}}-->
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
								<h1> @lang('faq.faq') </h1>
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
										
										<h3 class="faq-title @if($util->lang() == 'en') fssmall @endif"> @lang('faq.faq') </h3>
										<div class="faq_ctg">
											<ul>
												<li><a href="{{URL::to('/')}}/faq/price#top" class="active"> @lang('faq.about') </a></li>
												<li><a href="{{URL::to('/')}}/faq/insurance#top" class=""> @lang('faq.insurance') </a></li>
												<li><a href="{{URL::to('/')}}/faq/option#top" class=""> @lang('faq.vehicle') </a></li>
												<li><a href="{{URL::to('/')}}/faq/license#top" class=""> @lang('faq.license')</a></li>
												<li><a href="{{URL::to('/')}}/faq/booking#top" class=""> @lang('faq.reservation') </a></li>
												<li><a href="{{URL::to('/')}}/faq/passing#top" class=""> @lang('faq.delivery') </a></li>
												<li><a href="{{URL::to('/')}}/faq/usage#top" class=""> @lang('faq.aboutus') </a></li>
												<li><a href="{{URL::to('/')}}/faq/member#top" class=""> @lang('faq.membership') </a></li>
												<li><a href="{{URL::to('/')}}/faq/other#top" class=""> @lang('faq.other') </a></li>
											</ul>
											<a id="top"></a>
										</div>
										</div>
										<!-------------faq-01 ------------>
										<div class="panel-group m_B20 m_T20">
											<h3> @lang('faq.about') </h3>
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse1" class="collapsed" aria-expanded="false"><span class="icon-faq"> @lang('faq.rental') </span></a>
													</h4>
												</div>
											</div>

											<div id="collapse1" class="panel-collapse collapse in" aria-expanded="false">
												<div class="panel-body faq01">
													<p> @lang('faq.faqdesc') </p>
												</div>
											</div>
										</div>

										<!-------------faq-03 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse3" class="collapsed" aria-expanded="false"><span class="icon-faq"> @lang('faq.charge') </span></a>
													</h4>
												</div>
											</div>

											<div id="collapse3" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p> @lang('faq.mileage') </p>
												</div>
											</div>
										</div>

										<!-------------faq-04 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse4" class="collapsed" aria-expanded="false"><span class="icon-faq"> @lang('faq.fee')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse4" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p> @lang('faq.creditcard') <br>
                            							@lang('faq.quick') <br><br>
                            							@lang('faq.cash') <br>
                            							（1）@lang('faq.health') <br>
                            							（2）@lang('faq.student') <br>
														（3）@lang('faq.passport') <br>
														（4）@lang('faq.address') <br>
														（5）@lang('faq.pension') <br>
														（6）@lang('faq.certificate') <br>
														（7）@lang('faq.card') </p>
												</div>
											</div>
										</div>

										<!-------------faq-05 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse5" class="collapsed" aria-expanded="false"><span class="icon-faq"> @lang('faq.money') </span></a>
													</h4>
												</div>
											</div>

											<div id="collapse5" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p> @lang('faq.sorry') </p>
												</div>
											</div>
										</div>

										<!-------------faq-06 ------------>
										<div class="panel-group m_B20" id="kisetsu">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse6" class="collapsed" aria-expanded="false"><span class="icon-faq"> @lang('faq.season') </span></a>
													</h4>
												</div>
											</div>

											<div id="collapse6" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p> @lang('faq.time') <br><br>
                            							@lang('faq.search')
													</p>
												</div>
											</div>
										</div>

										<!-------------faq-07 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse7" class="collapsed" aria-expanded="false"><span class="icon-faq"> @lang('faq.cancel') </span></a>
													</h4>
												</div>
											</div>

											<div id="collapse7" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p> @lang('faq.conven') <br><br>
														@lang('faq.charge1')100%<br>
														@lang('faq.charge2')80%<br>
														@lang('faq.charge3')50%<br>
														@lang('faq.charge4')30%<br>
														@lang('faq.charge5')20%<br>
														@lang('faq.nospec')
             										</p>
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
                            <a href="#" class="bg-carico totop-link"> @lang('faq.toppage')</a>
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
