@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/about.min.css" rel="stylesheet" type="text/css" />
    <link href="{{URL::to('/')}}/css/page_search_thankyou.css" rel="stylesheet" id="style_components" type="text/css" />
    <style>
    </style>
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
@section('content')
{{--    @if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)--}}
    @if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false)
    <script>
        dataLayer.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField': {'step': 2, 'option': '{{ $booking_options }}' }, // Please change step number dynamically according to the checkout step.
                    'products': [{
                        'name'  : '{{$booking_class_name}}',
                        'id'    : '{{$booking_class_id}}',
                        'price' : '{{$booking_price}}',
                        // 'brand': 'エスティマ',
                        'quantity': 1
                    }]
                }
            }
        });

        dataLayer.push({
            'event': 'Thank you',
            'Ordervalue': '{{$booking_price}}'
        });
    </script>

    <span id="a8sales"></span>

    <script src="//statics.a8.net/a8sales/a8sales.js"></script>

    <script>
        a8sales({
            "pid": "s00000018752001",
            "order_number": "{{$order_number}}",
            "currency": "JPY",
            "items": [
                {
                    "code": "a8",
                    "price": 1,
                    "quantity": 1
                },
            ],
            "total_price": '{{$booking_price}}',
        });
    </script>
    @endif
    <div class="container" style=" padding: 0;margin-bottom: 140px !important;">
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
                            <span> @lang('thankyou.confirm')</span>
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
                            <h1> @lang('thankyou.confirm')</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">

                    <div class="row">
                        <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <!-- result -->
                            <div class="reserve-block box-shadow">
                                <div class="reserve-block-cont">
                                    <div class="row m_T20">
                                        <div class="col-sm-12">
                                            <div class="reservation-main">
                                            	<form method="post" id="search-thankyou" action="{!! url('/savebag_choose') !!}">
                                                 {{ csrf_field() }}
                                                <input type="hidden" name="bag_choosed" id="bag_choosed" value="0" />
                                                <div class="reservation-block">
                                                    <h2> @lang('thankyou.thank')</h2>
                                                    <h2> @lang('thankyou.confirmcar') </h2>
                                                </div>
                                                <div class="store-block">
                                                    @lang('thankyou.short')
                                                </div>
                                                <div class="quick-block">
                                                    @lang('thankyou.nostart')
                                                </div>
                                                <div class="txt-block">
												<p> @lang('thankyou.gift1')</p>
                                                </div>
                                                <?php
                                                    $license = $util->Tr('license');
                                                    $bag1    = $util->Tr('bag1');
                                                    $bag2    = $util->Tr('bag2');
                                                    $bag3    = $util->Tr('bag3');
                                                ?>
                                                <div class="driver-license-block row">
													<div class="col-sm-2 col-sm-offset-2">
														<img src="img/{{$license}}.jpg" alt="">
													</div>
													<div class="col-sm-8">
														<ol class="license-list">
															<li> @lang('thankyou.driverlicense')</li>
															<li> @lang('thankyou.contract')</li>
															<li> @lang('thankyou.creditcard')</li>
														</ol>
													</div>
                                                </div>
                                                <div class="favorite-block clearfix">
                                                    <img class="pull-right" src="img/car.jpg" alt="">
                                                </div>
                                                <div class="choose-block">

                                                    <div class="quick-button">
                                                     <a href="{!! url('quickstart-01') !!}">@lang('thankyou.extension')</a>
                                                    </div>
                                                </div>
                                                <div class="procedure-block m_T50">
													<div class="choose-content">
                                                        <div class="choose-common">
                                                            <label style="width:100%;">
                                                            <input type="radio" name="bag_choose" value="1" class="bag_choose hidden" bid="1">
                                                            <div class="choose-inner-block" bid="1">
                                                                <div class="small-block" style="display: none">
                                                                    <h2>選択中</h2>
                                                                </div>
                                                                <div class="choose-img-block">
                                                                    <img src="img/{{$bag1}}.jpg" alt="">
                                                                </div>
                                                                <div class="choose-text-block">
                                                                    <p>@lang('thankyou.refresh') <br> @lang('thankyou.anytime')</p>
                                                                </div>
                                                            </div>
                                                            </label>
                                                        </div>
                                                        <div class="choose-common">
                                                            <label>
                                                            <input type="radio" name="bag_choose" value="2" class="bag_choose hidden" bid="2">
                                                            <div class="choose-inner-block" bid="2">
                                                                <div class="small-block" style="display: none">
                                                                    <h2>選択中</h2>
                                                                </div>
                                                                <div class="choose-img-block">
                                                                    <img src="img/{{$bag2}}.jpg" alt="">
                                                                </div>
                                                                <div class="choose-text-block">
                                                                    <p> @lang('thankyou.children')<br> @lang('thankyou.driving')</p>
                                                                </div>
                                                            </div>
                                                            </label>
                                                        </div>
                                                        <div class="choose-common">
                                                            <label>
                                                            <input type="radio" name="bag_choose" value="3" class="bag_choose hidden" bid="3">
                                                            <div class="choose-inner-block" bid="3">
                                                                <div class="small-block" style="display: none">
                                                                    <h2>選択中</h2>
                                                                </div>
                                                                <div class="choose-img-block">
                                                                    <img src="img/{{$bag3}}.jpg" alt="">
                                                                </div>
                                                                <div class="choose-text-block">
                                                                    <p>@lang('thankyou.gift2')</p>
                                                                </div>
                                                            </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                </form>
												<p class="ast" style="font-size:11px;"> @lang('thankyou.confirmation')
												<br> @lang('thankyou.customer')
												</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- result -->
                        </div>

                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 hidden-lg hidden-md hidden-sm hidden-xs">
                            <div class="portlet portlet-fit light cont-box">
                                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10">
                                    <a href="#"><img class="center-block img-responsive" src="#" alt=""></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link"> @lang('thankyou.toppage')</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
        {{--form to go confirm page--}}
        {{--end form--}}
    </div>

    @include('modals.modal-membership')
    @include('modals.modal-error')
@endsection

@section('style')
  <style>

  </style>
@endsection

@section('footer_scripts')
    <script>
        /*$small_block = $('.small-block');
        $('.bag_choose').click( function () {
            var bag_id = $(this).attr('bid');
            $('.choose-inner-block').removeClass('active');
            $('.choose-inner-block div.small-block').fadeOut();

            $('.choose-inner-block[bid="' + bag_id + '"]').addClass('active');
            $('.choose-inner-block.active div.small-block').fadeIn();
            $('#bag_choosed').val(bag_id);
			console.log(bag_id);
        })*/
    </script>
@endsection
