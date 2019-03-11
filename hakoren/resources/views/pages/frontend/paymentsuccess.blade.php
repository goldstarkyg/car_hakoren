@extends('layouts.frontend')
@section('template_linked_css')
<link href="{{URL::to('/')}}/css/page_quickstart_form.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
@section('content')
    <script>
        // data layer
        @if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)
        @if(isset($transaction_id))
        dataLayer.push({
            'ecommerce': {
                'purchase': {
                    'actionField': {
                        'id': '{{$transaction_id}}',                         // Transaction ID. Required for purchases and refunds. Must be unique
                        'revenue': '{{$amount_paid}}',                     // Total transaction value (incl. tax and shipping)
                        'tax':'0',
                        'shipping': '0'
                    },
                    'products': [
                        {
                            'name': '{{$class_name}}',
                            'id': '{{$class_id}}',
                            'price': '{{$amount_paid}}',
                            // 'brand': 'エスティマ',
                            'quantity': 1
                        },
                    ]
                }
            }
        });
        @endif
        @endif
    </script>
<div class="page-container">
  <!-- BEGIN PAGE HEAD-->
  <div class="page-head hidden-xs">
    <div class="container clearfix">
      <!-- BEGIN PAGE TITLE -->
      <div class="page-title">
        <ul class="page-breadcrumb breadcrumb">
          <li> <a href="{{URL::to('/')}}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> </li>
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
  <div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->

    <!-- BEGIN CONTENT HEADER -->
    <div class="dynamic-page-header dynamic-page-header-default">
      <div class="container clearfix">
        <div class="col-md-12 bottom-border ">
          <div class="page-header-title">
            <h1> @lang('qs3.qsform')</h1>
          </div>
        </div>
      </div>
    </div>
    <!-- begin search -->
    <div class="page-content">
      <div class="container">
        <div class="row">
          <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
            <h3> @lang('qs3.reservation') {{ $userInfo->last_name }} {{ $userInfo->first_name }} @lang('qs3.mr')</h3>

            @if(isset($transaction_id))
            <p>@lang('qs3.thank')</p>
            @endif

            <!-- quick start 3 -->
            <div class="box-shadow relative red-border-top">
              <div class="formcard-block-cont">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formcard-right">
                    <h3 class="text-center relative">@lang('qs3.completed')</h3>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formcard-right">
                    <div class="panel panel-default">
                      <div class="bg-grad-gray panel-heading"> <a id="credit-sec"></a>
                        @if(isset($transaction_id))
                        <h4>@lang('qs3.config')</h4>
                        @else
                        <h4>@lang('qs3.thank1')
                        </h4>
                        @endif
                      </div>
                    </div>

                    {{--
                    <div class="formcard-wrapper clearfix">
                      <div class="col-xs-12 only-nonmember　" style="padding: 0; margin-bottom:0px;">
                        <div><strong>お取引ID</strong><br />
                          {!! $transaction_id !!}<br />
                          <br />
                        </div>
                        <div><strong>お支払額</strong><br />
                          {!! $amount_paid !!}<br />
                          <br />
                        </div>
                        <div><strong>お取引日</strong><br />
                          {!! $payment_at !!}</div>
                      </div>
                    </div>
                    --}}

                    @if(isset($transaction_id))
                    <form method="post" id="search-thankyou" action="">
                    {!! csrf_field() !!}
                   	<input type="hidden" name="bag_choosed" id="bag_choosed" value="1" />
                    <input type="hidden" name="booking_id" id="booking_id" value="{!! $booking_id !!}" />
                    <div class="choose-content">
                        <div class="choose-common">
                            <label >
                            <input type="radio" name="bag_choose" value="1" class="bag_choose hidden" bid="1">
                            <div class="choose-inner-block active" bid="1">
                                <div class="small-block">
                                    <h2>@lang('qs3.choose')</h2>
                                </div>
                                <div class="choose-img-block">
                                    <?php $bag1 = $util->Tr('bag1'); ?>
                                    <img src="img/{{$bag1}}.jpg" alt="">
                                </div>
                                <div class="choose-text-block">
                                    <p>@lang('qs3.refresh')</p>
                                </div>
                            </div>
                            </label>
                        </div>
                        <div class="choose-common">
                            <label>
                            <input type="radio" name="bag_choose" value="2" class="bag_choose hidden" bid="2">
                            <div class="choose-inner-block" bid="2">
                                <div class="small-block" style="display: none">
                                    <h2>@lang('qs3.choose')</h2>
                                </div>
                                <div class="choose-img-block">
                                    <?php $bag2 = $util->Tr('bag2'); ?>
                                    <img src="img/{{$bag2}}.jpg" alt="">
                                </div>
                                <div class="choose-text-block">
                                    <p>@lang('qs3.adult')</p>
                                </div>
                            </div>
                            </label>
                        </div>
                        <div class="choose-common">
                            <label>
                            <input type="radio" name="bag_choose" value="3" class="bag_choose hidden" bid="3">
                            <div class="choose-inner-block" bid="3">
                                <div class="small-block" style="display: none">
                                    <h2>@lang('qs3.choose')</h2>
                                </div>
                                <div class="choose-img-block">
                                    <?php $bag3 = $util->Tr('bag3'); ?>
                                    <img src="img/{{$bag3}}.jpg" alt="">
                                </div>
                                <div class="choose-text-block">
                                    <p> @lang('qs3.enjoy')</p>
                                </div>
                            </div>
                            </label>
                        </div>
                    </div>

                    <div class="quick-button" id="show_message">
                        @if($util->lang() == 'ja')
                        <input type="button" name="submit" id="bagsubmit" onclick="javascript:choosebag();" class="submitBtn form-control h40" value="送信する">
                        @endif
                        @if($util->lang() == 'en')
                            <input type="button" name="submit" id="bagsubmit" onclick="javascript:choosebag();" class="submitBtn form-control h40" value="Send">
                        @endif
                    </div>
                    </form>
                    @endif

                  </div>
                </div>
              </div>
            </div>
            <!-- quick start 3 -->

          </div>
          <!-- END PAGE CONTENT INNER -->
          <div class="dynamic-sidebar col-lg-3 col-md-3 hidden-lg hidden-md hidden-sm hidden-xs">
            <div class="portlet portlet-fit light cont-box">
              <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10"> <a href="#"><img class="center-block img-responsive" src="" alt=""></a> </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 clearfix"> <a href="#" class="bg-carico totop-link">@lang('qs3.toppage')</a> </div>
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
    <script>
        $small_block = $('.small-block');
        $('.bag_choose').click( function () {
            var bag_id = $(this).attr('bid');
            $('.choose-inner-block').removeClass('active');
            $('.choose-inner-block div.small-block').fadeOut();

            $('.choose-inner-block[bid="' + bag_id + '"]').addClass('active');
            $('.choose-inner-block.active div.small-block').fadeIn();
            $('#bag_choosed').val(bag_id);
			console.log(bag_id);
        });


		function choosebag(){

            var bag_choosed = $('#bag_choosed').val();
			var booking_id  = $('#booking_id').val();
            $.ajax({
                url  : '{!! URL::to("/savebag_choose") !!}',
                type : 'post',
                data : {'booking_id':booking_id, 'bag_choosed':bag_choosed, '_token':$('input[name="_token"]').val()},
                success : function(result,status,xhr) {
                    @if($util->lang() == 'ja')
                   	 $('#show_message').html('<br>ご利用いただきまして、ありがとうございます。<br><br>当日はスタッフ一同楽しみにおまちしております！<br>');
                   	@endif
                    @if($util->lang() == 'en')
                    $('#show_message').html('<br>Thank you for using Hako Rent-a-car. <br><br> We look forward to meeting you on the day!<br>');
                    @endif
                },
                error : function(xhr,status,error){
                    alert(error);
                }
            });
		}


    </script>
@endsection
