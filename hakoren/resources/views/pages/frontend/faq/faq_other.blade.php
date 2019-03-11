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
                            <span> @lang('faqother.faq') </span>　<!--{{trans('fs.current')}}-->
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
								<h1> @lang('faqother.faq') </h1>
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
											<h3 class="faq-title"> @lang('faqother.faq') </h3>
											<div class="faq_ctg">
												<ul>
													<li><a href="{{URL::to('/')}}/faq/price#top" class=""> @lang('faqother.about') </a></li>
													<li><a href="{{URL::to('/')}}/faq/insurance#top" class=""> @lang('faqother.insurance') </a></li>
													<li><a href="{{URL::to('/')}}/faq/option#top" class="">@lang('faqother.vehicle') </a></li>
													<li><a href="{{URL::to('/')}}/faq/license#top" class="">@lang('faqother.license')</a></li>
													<li><a href="{{URL::to('/')}}/faq/booking#top" class="">@lang('faqother.reservation')</a></li>
													<li><a href="{{URL::to('/')}}/faq/passing#top" class="">@lang('faqother.delivery')</a></li>
													<li><a href="{{URL::to('/')}}/faq/usage#top" class="">@lang('faqother.aboutus')</a></li>
													<li><a href="{{URL::to('/')}}/faq/member#top" class=""> @lang('faqother.membership')</a></li>
													<li><a href="{{URL::to('/')}}/faq/other#top" class="active"> @lang('faqother.other')</a></li>
												</ul>
												<a id="top"></a>
											</div>
										</div>
										<!-------------faq-01 ------------>
										<div class="panel-group m_B20 m_T20">
											<h3> @lang('faqother.other')</h3>
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse1" class="collapsed" aria-expanded="false"><span class="icon-faq"> @lang('faqother.hakorentcar')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse1" class="panel-collapse collapse in" aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqother.hakorentcar-a')<br>
                            <a href="{{URL::to('/')}}/transactions">@lang('faqother.transactions')</a><br><br>

                             @lang('faqother.guide')<br>
                             <a href="{{URL::to('/')}}/first"> @lang('faqother.first')</a></p>
												</div>
											</div>
										</div>

										<!-------------faq-02 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse2" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqother.inquiry')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse2" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqother.contacttext')<a href="{{URL::to('/')}}/contact">@lang('faqother.contactform')</a> @lang('faqother.shoptel')</p>
												</div>
											</div>
										</div>

										<!-------------faq-03 ------------>
										<div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse3" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('faqother.policy')</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse3" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>@lang('faqother.policy-a')<a href="{{URL::to('/')}}/policy">@lang('faqother.policy-page')</a>@lang('faqother.policy-b')</p>
												</div>
											</div>
										</div>

										<!-------------faq-04 ------------>
                    <!--

                    <div class="panel-group m_B20">
											<div class="panel panel-default fr-faq">
												<div class="panel-heading fr-faq">
													<h4 class="panel-title ">
														<a data-toggle="collapse" href="#collapse4" class="collapsed" aria-expanded="false"><span class="icon-faq">予約の変更や取消はどこに問い合わせたらいいですか？</span></a>
													</h4>
												</div>
											</div>

											<div id="collapse4" class="panel-collapse collapse " aria-expanded="false">
												<div class="panel-body faq01">
													<p>キャンセルの場合、ご出発日より7日前より所定のキャンセル料を申し受けます。お気お付けください<br><br>詳しくは、下記店舗にてご確認をお願いいたします。<br>
													<a href="{{URL::to('/')}}/shop/fukuoka">福岡空港店</a><br>
													<a href="{{URL::to('/')}}/shop/naha-airport">那覇空港店</a></p>
												</div>
											</div>
										</div>
                  -->


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
                            <a href="#" class="bg-carico totop-link">@lang('faqother.toppage')</a>
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
