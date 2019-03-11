@extends('layouts.frontend')
@section('template_title')
@lang('qs3.title')
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_quickstart_form.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
    <link rel="stylesheet" href="{{ URL::asset('css/sqpaymentform.css') }}" type="text/css">
	<script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script>
    <script>
	// Set the application ID
	var applicationId = "{!! env('SQUARE_APPLICATION_ID') !!}";
	// "sandbox-sq0idp-alUWCy0k-6Krcvh-eW4_Yw";

	// Set the location ID
	var locationId = "{!! env('SQUARE_LOCATION_ID') !!}";
	//"CBASEOnXb3pp-u53CJxVFHVpQrQgAQ";
	</script>
    <script type="text/javascript" src="{{ URL::asset('js/sqpaymentform.js') }}"></script>
@endsection
@section('content')

    <script>
        <?php
        $options = [];
        if(!empty($paid_options)) {
            foreach ($paid_options as $op) {
                $options[] = $op->name;
            }
        }
        ?>
        @if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)

        dataLayer.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField': {'step': 5, 'option': '{{implode(',', $options)}}' }, // Please note that on the site are 4 steps.
                    'products': [{
                        'name'  : '{{$carClassInfo->name}}',
                        'id'    : '{{$carClassInfo->id}}',
                        'price' : '{{$bookingInfo->payment}}',
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
                            <span>@lang('qs3.cartitle')</span>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE TITLE -->
            </div>
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper quick_main_wrapper">
            <!-- BEGIN CONTENT BODY -->
          
            <!-- BEGIN CONTENT HEADER -->
            <div class="dynamic-page-header dynamic-page-header-default">
                <div class="container clearfix">
                    <div class="col-md-12 bottom-border ">
                        <div class="page-header-title">
								<h1>@lang('qs3.cartitle')</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">

						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
                            <div class="quick_user_three">
							     <h3>@lang('qs3.reservation') @lang('qs3.mr_en') {{ $userInfo->last_name }} {{ $userInfo->first_name }} @lang('qs3.mr')</h3>
                            </div>

							<!-- quick start 3 -->
							<div class="box-shadow relative red-border-top">


                                <div class="formcard-block-cont">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formcard-right">
											<ol class="stepBar step3">
											  <li class="step">STEP1</li>
											  <li class="step">STEP2</li>
											  <li class="step current">STEP3</li>
											</ol>
											<h3 class="text-center relative">③@lang('qs3.payment')<span class="pay-local">@lang('qs3.paying')<a href="{!! url('/pay-locally') !!}">@lang('qs3.here')</a></span></h3>
										</div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 formcard-right">
											<div class="panel panel-default">
												<div class="bg-grad-gray panel-heading">
														<a id="credit-sec"></a>
														<h4>@lang('qs3.creditcard')</h4>
												</div>
											</div>

                                        <form id="nonce-form" novalidate action="{!! url('/savequickstart-03') !!}" method="post">
                                          {!! csrf_field() !!}
                                          <div class="formcard-wrapper clearfix">

                                            <div class="alert alert-danger" id="error_block" style="display:@if($errors->any()) block @else none @endif;">
                                              <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{!! $error !!}</li>
                                                @endforeach
                                              </ul>
                                            </div>

                                            <div class="col-xs-12 only-nonmember　" style="padding: 0; margin-bottom:0px;">
                                              <label class="col-lg-5 col-md-5 col-sm-5 col-xs-12" style="padding: 0px 0 3px; margin-bottom:3px;">@lang('qs3.cardnumber')<span class="req-red">@lang('qs3.required')</span></label>
                                              <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12" style="">
                                                <div id="sq-card-number"></div>
                                                <span class="error-class errorcard_num"></span>
                                                <ul class="asterisk">
                                                  <li>@lang('qs3.nothyphen')</li>
                                                </ul>
                                              </div>
                                            </div>
                                            <div class="col-xs-12 only-nonmember" style="padding: 0; margin-bottom:10px;">
                                              <label class="col-lg-5 col-md-5 col-sm-5 col-xs-12" style="padding: 4px 0 3px; margin-bottom:3px;">@lang('qs3.cardexpire')<span class="req-red">@lang('qs3.required')</span></label>
                                              <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                <div id="sq-expiration-date"></div>
                                                <ul class="asterisk">
                                                  <li>@lang('qs3.example') [01/19]</li>
                                                </ul>
                                                <span class="error-class errorcard_expired_m"></span> <span class="error-class errorcard_expired_y"></span> </div>
                                            </div>
                                            <div class="col-xs-12 only-nonmember" style="padding: 0; margin-bottom:10px;">
                                              <label class="col-lg-5 col-md-5 col-sm-5 col-xs-12" style="padding: 4px 0 3px; margin-bottom:3px;">@lang('qs3.securitycode')<span class="req-red">@lang('qs3.required')</span></label>
                                              <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                <div id="sq-cvv"></div>
                                                <span class="error-class errorsecure_num"></span> </div>
                                            </div>

                                          </div>
                                          <!-- wrapper -->

                                          <div align="center">
                                            <input type="button" name="button" id="sq-creditcard" class="submitBtn form-control h40" value="@lang('qs3.completepayment')" onClick="requestCardNonce(event)">
                                            <!--<button id="sq-creditcard" class="button-credit-card" onClick="requestCardNonce(event)">お支払いを完了する </button>-->                              </div>

                                          <input type="hidden" id="card-nonce" name="nonce">
                                        </form>


                                        </div>

                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 formcard-left">
                                            <span class="pay-step-three">
                                                @lang('qs3.payinglocal')
                                                <a href="{!! url('/pay-locally') !!}">@lang('qs3.here')</a>
                                            </span>
											<div class="panel panel-default" style="margin-bottom: 5px;">
                                                <div class="panel-heading bg-grad-gray">
                                                    @lang('qs3.paymentdetail')
                                                </div>
                                                <div class="panel-body" style="padding-bottom: 0px;">
                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left">@lang('qs3.customername')</label>
														<label class="pull-right">@lang('qs3.mr_en') {!! $userInfo->last_name.' '.$userInfo->first_name !!} @lang('qs3.mr')</label>
                                                    </div>
                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left">@lang('qs3.carclass')</label>
														<label class="pull-right">
                                                            @if(!empty($carClassInfo))
                                                                {!! $carClassInfo->name !!}
                                                            @endif
                                                            @if(!empty($modelInfo))
                                                                {{ $modelInfo->passengers }}@lang('qs3.riding')
                                                            @endif
                                                        </label>
                                                    </div>
                                                    <?php
                                                        $name = $util->Tr('name');
                                                    ?>
                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left">@lang('qs3.model')</label>
														<label class="pull-right">
                                                            @foreach($models as $model)
                                                                {{ $model->$name }} @if(!$loop->last)&nbsp;/@endif
                                                            @endforeach
                                                        </label>
                                                    </div>
                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left">@lang('qs3.departure')</label>
                                                        @if($util->lang() == 'ja')
														<label class="pull-right">{!! date('Y', strtotime($bookingInfo->departing)).'年'.date('m', strtotime($bookingInfo->departing)).'月'.date('d',strtotime($bookingInfo->departing)).'日'.date('G:i',strtotime($bookingInfo->departing)) !!} {!! $departShop->name !!}</label>
                                                        @endif
                                                        @if($util->lang() == 'en')
                                                            <label class="pull-right">{!! date('Y', strtotime($bookingInfo->departing)).'/'.date('m', strtotime($bookingInfo->departing)).'/'.date('d',strtotime($bookingInfo->departing)).'/'.date('G:i',strtotime($bookingInfo->departing)) !!} {!! $departShop->$name !!}</label>
                                                        @endif
                                                    </div>

                                                    <div class="form-group row-bordered-result row">
														<label class="pull-left">@lang('qs3.return')</label>
                                                        @if($util->lang() == 'ja')
														<label class="pull-right">{!! date('Y', strtotime($bookingInfo->returning)).'年'.date('m', strtotime($bookingInfo->returning)).'月'.date('d',strtotime($bookingInfo->returning)).'日'.date('G:i',strtotime($bookingInfo->returning)) !!} {!! $returnShop->name !!}</label>
                                                        @endif
                                                        @if($util->lang() == 'en')
                                                            <label class="pull-right">{!! date('Y', strtotime($bookingInfo->returning)).'/'.date('m', strtotime($bookingInfo->returning)).'/'.date('d',strtotime($bookingInfo->returning)).'/'.date('G:i',strtotime($bookingInfo->returning)) !!} {!! $returnShop->$name !!}</label>
                                                        @endif
                                                    </div>
                                                    <div class="form-group row-bordered-result row">
                                                        <label class="pull-left span_nightday" class_id="" >
                                                            @lang('qs3.basiccharge')（{!! $util->changeDate($rent_days) !!}）
                                                        </label>
                                                        <label class="pull-right basic_price" class_id="" >
{{--                                                            {!! number_format($basic_charge) !!}円--}}
                                                            @lang('qs3.yen_en'){!! number_format($bookingInfo->basic_price) !!}@lang('qs3.yen')
                                                        </label>
                                                        <input type="hidden" class="rent_days" class_id="" value="">
                                                        <input type="hidden" class="price_rent" class_id="" value="">
                                                    </div>

													<div class="form-group row-bordered-result row">
                                                        <div class="col-xs-6" style="padding: 0">
                                                            @lang('qs3.exemption')
                                                        </div>
                                                        <div class="col-xs-6" style="padding-right: 0;">
                                                            <label class="pull-right">
                                                                @lang('qs3.yen_en')<span class="insurance-price" class_id="">{!! number_format($bookingInfo->insurance1 + $bookingInfo->insurance2) !!}</span>@lang('qs3.yen')
                                                            </label>
                                                        </div>
                                                    </div>

                                                    @if($bookingInfo->paid_options)

                                                    @php
                                                        $options_price   = explode(',', $bookingInfo->paid_options_price);
                                                        $option_numbers  = explode(',', $bookingInfo->paid_option_numbers);
                                                        $loopindex		 = 0;
                                                    @endphp

                                                    @foreach($paid_options as $option)
                                                    <div class="option-wrapper" class_id="">
														<div class="form-group row-bordered-result row">
															<label class="pull-left">
																{!! $option->$name !!}
															</label>
															<label class="pull-right">
                                                                @lang('qs3.yen_en'){!! number_format($options_price[$loopindex] * $option_numbers[$loopindex]) !!}@lang('qs3.yen')
															</label>
														</div>
                                                    </div>
                                                    @php $loopindex++; @endphp
													@endforeach

													@endif

                                                    <div class="form-group row-bordered-result row">
                                                        <div class="col-xs-6" style="padding: 0">
                                                            @lang('qs3.non_smoking')
                                                        </div>
                                                        <div class="col-xs-6" style="padding-right: 0;">
                                                            <label class="pull-right">
                                                                <span class="insurance-price" class_id="">{!! $smokeInfo !!}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">

                                                        <div class="col-xs-6" style="padding: 0">

                                                            <div>@lang('qs3.total')</div>
                                                        </div>
                                                        <div class="col-xs-6" style="padding-right: 0;">
                                                            <label style="font-weight:bold; font-size:2em;" class="pull-right total_price font-red"  class_id="">
																@lang('qs3.yen_en'){!! number_format($bookingInfo->payment) !!}<span style="font-weight: 300">@lang('qs3.yen')</span>
															</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

											<div class="panel panel-default quick_over_tre" style="margin-bottom: 5px;">
                                                <div class="panel-heading bg-grad-gray">
                                                    @lang('qs3.about')
                                                </div>
                                                <div class="panel-body" style="padding-bottom: 0px;">
                                                    <p>@lang('qs3.content')</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
							</div>
							<!-- quick start 3 -->


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
                            <a href="#" class="bg-carico totop-link">@lang('qs3.toppage')</a>
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

<script type="text/javascript">
</script>

@endsection
