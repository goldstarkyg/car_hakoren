@extends('layouts.frontend')
@section('template_title')
@lang('qs2.title')
@endsection
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_quickstart_form.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
<style type="text/css">
.activeBtn{
background:#8EC11E !important;
color:#fff !important;
}
</style>
@endsection
@section('content')
	<script>
		@if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)
        dataLayer.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField': {'step': 4}, // Please change step number dynamically according to the checkout step.
                    'products': [{
                        'name'	: '{{$booking_class_name}}',
                        'id'	: '{{$booking_class_id}}',
                        'price'	: '{{$booking_price}}',
                        // 'brand': 'エスティマ',
                        'quantity': 1
                    }]
                }
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
                        <li>
                            <span>@lang('qs2.cartitle')</span>
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
								<h1>@lang('qs2.cartitle')</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">

						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
                            <div class="quick_user_two">
							     <h3>@lang('qs2.reservation') @lang('qs2.mr_en') {{ $userInfo->last_name }} {{ $userInfo->first_name }} @lang('qs2.mr')</h3>
                            </div>

                            <!-- quick start 2 -->
							<div class="box-shadow relative red-border-top">

                                <form id="quickstart-form" action="{!! url('/savequickstart-02') !!}" method="post">
                                {!! csrf_field() !!}
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<ol class="stepBar step3">
											  <li class="step">STEP1</li>
											  <li class="step current">STEP2</li>
											  <li class="step">STEP3</li>
											</ol>
											<h3 class="text-center q_title_two">@lang('qs2.compensation')</h3>
											<p>@lang('qs2.contract')</p>
                                            @if($errors->any())
                                                @foreach ($errors->all() as $error)
                                                    <div class="alert alert-danger">{!! $error !!}</div>
                                                @endforeach
                                            @endif
                                            <div style="height:400px; overflow-y:scroll; padding:2%; border:1px solid #eee;">
											  <ul class="m_L0 li-check">
                                                <li>1.&nbsp;
													 <span>@lang('qs2.about')</span>
													<p>@lang('qs2.businesshour')</p>
												</li>
												<li>2.&nbsp;
                                                	<span>@lang('qs2.aboutfuel')</span>
													<ul class="circle">
														<li>@lang('qs2.delivered')</li>
													</ul>
												</li>
												<li>3.&nbsp;
													<span>@lang('qs2.aboutinsurance')</span>
													<p>@lang('qs2.compensationcar')</p>
													<ul class="circle">
														<li>@lang('qs2.compensation1')</li>
														<li>@lang('qs2.objectcompensation')</li>
														<li>@lang('qs2.objectcompensation2')</li>
														<li>@lang('qs2.compensation2')</li>
													</ul>
													<p>@lang('qs2.automobile')</p>
												</li>
												<li>4.&nbsp;
													<span>@lang('qs2.followingcase')</span>
													<ul class="circle">
														<li>@lang('qs2.accidentreport')</li>
														<li>@lang('qs2.accident1')</li>
														<li>@lang('qs2.accident2')</li>
														<li>@lang('qs2.drunkdriving')</li>
														<li>@lang('qs2.selfcase')</li>
														<li>@lang('qs2.othercase')</li>
													</ul>
												</li>
												<li>5.&nbsp;
                                                	<span>@lang('qs2.exemption')</span>
													<p>@lang('qs2.subscribe')</p>
												</li>
												<li>6.&nbsp;
                                                	<span>@lang('qs2.option')</span>
													<p>@lang('qs2.payment')</p>
													</p>
												</li>
												<li>7.&nbsp;
                                                	<span>@lang('qs2.about')</span>
													<p>@lang('qs2.contact')</p>

												</li>

											</ul>
                                            </div>
                                            <div align="center" class="m_T20"><input type="checkbox" name="agree_terms_conditions" id="option8" value="1" onclick="toggleBoxes()">									<label for="option8" class="checkbox check_css"><span>@lang('qs2.agree')</span></label>
                                            </div>

										</div>
									</div>
									<input type="submit" name="submit" id="step2submit" disabled="disabled" class="submitBtn form-control h40" value="@lang('qs2.agree1')">
								</form>

							</div>
							<!-- quick start 2 -->

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
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link">@lang('qs2.toppage')</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>

    </div>



@endsection

@section('style')

@endsection

@section('footer_scripts')
<script src="{{URL::to('/')}}/js/plugins/kana/jquery.autoKana.js" type="text/javascript"></script>
<script type="text/javascript">
var toggle = true;
function toggleBoxes() {
    /*var objList = document.getElementsByName('options[]')
    for(i = 0; i < objList.length; i++)
        objList[i].checked = toggle;
    toggle = !toggle;*/

	if($("#option8").prop('checked') == true){
		$('#step2submit').prop('disabled', false).addClass('activeBtn');
	}else{
		$('#step2submit').prop('disabled', true).removeClass('activeBtn');
	}
}
</script>
@endsection
