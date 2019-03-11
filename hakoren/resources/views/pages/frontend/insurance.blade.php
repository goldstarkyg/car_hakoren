@extends('layouts.frontend')
@inject('util', 'App\Http\DataUtil\ServerPath')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_insurance.css" rel="stylesheet" type="text/css">
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
                            <span>@lang('insurance.tl')</span>
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
							<h1>@lang('insurance.headertitle')</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
					<div class="box-shadow">
						<div class="row">
							<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<!-- insurance 1 -->
								<div class="bg-blu relative p_T30">
									<div class="row">
										<div class="col-lg-8 col-md-offset-2 col-md-8 col-sm-12 col-xs-12">

											<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/pages/insurance/{{$util->Tr('hosyou_title_01')}}.png" alt="">
											<h3 class="red-border-title">@lang('insurance.subtitle_insurance')</h3>
											<p class="exp-txt">@lang('insurance.concept_insurance')</p>
											<h3 class="red-border-title">@lang('insurance.subtitle_disclaimer')</h3>
											<p class="exp-txt">@lang('insurance.concept_disclaimer')</p>
											<h3 class="red-border-title wide">@lang('insurance.subtitle_noc')</h3>
											<p class="exp-txt">@lang('insurance.concept_noc')</p>
										</div>
									</div>


								</div>
								<!-- insurance 2 -->
								<div class="bg-blu relative red-border-top">
									<h3 class="ins2-topttl">@lang('insurance.t1')</h3>
									<p class="text-center fs13 fw600">@lang('insurance.p1')</p>
									<div class="row m_T20">
										<div class="col-lg-10 col-md-offset-1 col-md-10 col-sm-12 col-xs-12">
											<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/{{$util->Tr('hosyou_hoken00')}}.png" alt="">
										<p>@lang('insurance.p2')</p>
										</div>
									</div>
								</div>
								<!-- insurance 基本の補償 -->
								<div class="box-shadow bg-border-blu m_T20 relative red-border-top">
									<h4 class="ins2-ttl fw600">@lang('insurance.t2')</h4>
									<p class="ins2-secondttl">@lang('insurance.p3')</p>
									<p class="text-center fs13 fw600">@lang('insurance.p4')</p>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_T20">
											<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/pages/insurance/{{$util->Tr('hosyou_hoken01')}}.png" alt="">
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_T20">
											<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/pages/insurance/{{$util->Tr('hosyou_hoken02')}}.png" alt="">
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_T20">
											<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/pages/insurance/{{$util->Tr('hosyou_hoken03')}}.png" alt="">
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 m_T20">
											<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/pages/insurance/{{$util->Tr('hosyou_hoken04')}}.png" alt="">
										</div>
									</div>
									<div class="row m_T20">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="row m_T20">
												<div class="col-lg-8 col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
													<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/pages/insurance/{{$util->Tr('hosyou_hoken06')}}.png" alt="">
												</div>
											</div>
											<h5 class="m_L20 m_T20">@lang('insurance.t3')</h5>
											<ul class="disc">
												<li>@lang('insurance.l1')</li>
												<li>@lang('insurance.l2')</li>
												<li>@lang('insurance.l3')</li>
												<li>@lang('insurance.l4')</li>
												<li>@lang('insurance.l5')</li>
											</ul>
										</div>
									</div>
								</div>
								<!-- insurance -->
								<!-- insurance 選べる補償 -->
								<div class="box-shadow bg-border-grn m_T20 relative red-border-top">
									<h4 class="ins2-ttl fw600">@lang('insurance.h1')</h4>
									<p class="ins3-secondttl">@lang('insurance.p5')</p>
									<p class="text-center fs13 fw600">@lang('insurance.p6')</p>
									<div class="row m_T20">
										<div class="col-lg-8 col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
											<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/pages/insurance/{{$util->Tr('hosyou_hoken07')}}.png" alt="">
										</div>
									</div>
									<div class="row m_T20">
										<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-sm-10 col-xs-12">
											<h5 class="m_L20 m_T20">@lang('insurance.h2')</h5>
										</div>
										<div class="col-lg-5 col-md-5 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-sm-6 col-xs-12">
											<ul class="disc">
												<li>@lang('insurance.l6')</li>
												<li>@lang('insurance.l7')</li>
												<li>@lang('insurance.l8')
													<ol>
														<li>(1) @lang('insurance.o1')</li>
														<li>(2) @lang('insurance.o2')</li>
														<li>(3) @lang('insurance.o3')</li>
														<li>(4) @lang('insurance.o4')</li>
														<li>(5) @lang('insurance.o5')</li>
													</ol>
												</li>
											</ul>
										</div>
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
											<ul class="disc">
												<li>@lang('insurance.l9')
													<ol>
														<li>(1) @lang('insurance.o6')</li>
														<li>(2) @lang('insurance.o7')</li>
														<li>(3) @lang('insurance.o8')</li>
														<li>(4) @lang('insurance.o9')</li>
														<li>(5) @lang('insurance.o10')</li>
														<li>(6) @lang('insurance.o11')</li>
														<li>(7) @lang('insurance.o12')</li>
													</ol>
												</li>
												<li>@lang('insurance.l10')
													<ol>
														<li>(1) @lang('insurance.o13')</li>
														<li>(2) @lang('insurance.o14')</li>
														<li>(3) @lang('insurance.o15')</li>
														<li>(4) @lang('insurance.o16')</li>
													</ol>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<!-- insurance ワイド免責補償 -->
								<div class="box-shadow bg-border-ylw m_T20 relative red-border-top">
									<h4 class="ins2-ttl fw600 w60">@lang('insurance.h3')</h4>
									<p class="ins4-secondttl">@lang('insurance.p7')</p>
									<p class="text-center fs13 fw600">@lang('insurance.p8')</p>

									<div class="row m_T20">
										<div class="col-lg-8 col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
											<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/pages/insurance/{{$util->Tr('hosyou_hoken09')}}.png" alt="">
										</div>
									</div>
									{{--
									<h5 class="m_L20 m_T20">免責補償が適用されない条件について</h5>
									<p>レンタカー使用中の事故により車両損害が発生した場合には、損傷の程度や修理期間にかかわらず、営業補償の一部として下記のノンオペレーションチャージを申し受けます。</p>
									<ul class="disc">
										<li>予定の営業店にレンタカーを返還した場合（自走可能な場合）→ 20,000円</li>
										<li>予定の営業店にレンタカーを返還できなかった場合（自走不可能な場合）→ 50,000円</li>
									</ul>
									<p>※ノンオペレーションチャージは、免責補償制度にご加入の場合でもご負担いただきます。<br/>※車両移動に伴うレッカー代はお客様のご負担となります。</p>
									--}}
								</div>
								<!-- insurance -->
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
					</div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link">@lang('insurance.bl')</a>
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
