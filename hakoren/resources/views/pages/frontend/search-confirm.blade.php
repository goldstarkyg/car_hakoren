@section('template_title')
@lang('sconfirm.title')
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
<?php
//print_r($data);
if($util-> lang() == 'ja') {
    $depart_date = date('Y年n月j日', strtotime($data['depart_date']));
    $return_date = date('Y年n月j日', strtotime($data['return_date']));
}
if($util-> lang() == 'en') {
    $depart_date = date('Y/n/j/', strtotime($data['depart_date']));
    $return_date = date('Y/n/j/', strtotime($data['return_date']));
}

$name = $util->Tr('name');
//print_r($errors);
?>
@extends('layouts.frontend')
@section('template_linked_css')
<link href="{{URL::to('/')}}/css/page_search_confirm.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
{{--
<link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
--}}
    {{--
<link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
--}}
<style>
    </style>
@endsection
@section('content')

    <script>
        @if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)
        dataLayer.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField'   : {'step': 1, 'option': '{{$data['option_names']}}' }, // Please note that on the site are 4 steps.
                    'products'      : [{
                        'name'      : '{{$data['class_name']}}',
                        'id'        : '{{$data['class_id']}}',
                        'price'     : '{{$data['price_all'] + $data['insurance_price1'] + $data['insurance_price2']}}',
                        'quantity'  : 1
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
          <li> <a href="{{URL::to('/')}}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> </li>
          <li> <span> @lang('sconfirm.result')</span> </li>
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
            <h1> @lang('sconfirm.result')</h1>
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
                <div class="row">
                  <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 reserve-left confirm_table">
                    <p class="reserve-left-ttl"> @lang('sconfirm.title') </p>
                    <div class="reserve-tbl-wrapper"> @if(!empty($no_car_error))
                      <p style="color:orangered"> {{ $no_car_error }}</p>
                      @endif
                      <table class="table reserve-tbl" style="margin-bottom:0">
                        <tr>
                          <th><span> @lang('sconfirm.class')</span></th>
                          <td>{!! $data['class_name'] !!}
                              @if($util->lang() == 'ja')
                              ({{ $data['max_capacity'] }})
                              @endif
                              @if($util->lang() == 'en')
                                  ({{ $data['max_capacity_en'] }})
                              @endif
                          </td>
                        </tr>
                        <tr>
                          <th><span> @lang('sconfirm.model') </span></th>
                          <td class="car-type-td">

							@foreach($models as $model)
								@if(isset($model->smoke_cars))
									@if(!empty($model->smoke_cars))
							{{ $model->$name }} &nbsp;<span>/</span>
									@endif
								@endif
							@endforeach
						  </td>
                        </tr>
                        <tr>
                          <td class="confirm_car_table" colspan="2"><ul class="clearfix reserve-carlist" >
                              @foreach($models as $model)
								@if(isset($model->smoke_cars))
									@if(!empty($model->smoke_cars))
                              <li> <img src="{{ is_null($model->thumb_path)? URL::to('/').'/images/blank.jpg': URL::to('/').$model->thumb_path }}" class="model-photo img-responsive"> </li>
									@endif
								@endif
                              @endforeach
                            </ul></td>
                        </tr>
                        <tr class="hidden-lg hidden-md hidden-sm">
                          <th><span>利用店舗</span></th>
                          <td>{{$data['depart_shop_name']}}</td>
                        </tr>
                        <tr>
                          <th><span> @lang('sconfirm.departure') </span></th>
                          <td>{{$depart_date}}  {{$data['depart_time']}} <span class="hidden-xs">{{$data['depart_shop_name']}}</span></td>
                        </tr>
                        <tr>
                          <th><span> @lang('sconfirm.return')</span></th>
                          <td>{{$return_date}}  {{$data['return_time']}}  <span class="hidden-xs"><!--{{$data['return_shop_name']}}--> {{$data['depart_shop_name']}}</span></td>
                        </tr>
                        <tr>

                          <th class="mdmw180"  style="/*width: 200px !important;*/">
                              <span>
                                    <!-- 基本料金（{{ $data['rent_days'] }}） -->
                                  @lang('sconfirm.basiccharge')
                                        (
                                            @if($data['rent_days'] == "0泊1日")
                                                @lang('sconfirm.returnday')
                                            @else
                                                {{$util->changeDate($data['rent_days'])}}
                                            @endif
                                        )
                                </span>
                          </th>
                          <td>@lang('sconfirm.yen_en') {{ number_format($data['price_rent']) }} @lang('sconfirm.yen')</td>
                        </tr>
					</table>
                    <table class="table reserve-tbl">
                        <tr>
                          <th><span style="display:inline-block;"><a class="mens-modal" data-toggle="modal" href="#Modal_01" data-target="#Modal_01" style="color:#333; cursor: pointer;"> @lang('sconfirm.compensation') <i class="fa fa-question-circle" style="color:#4c4c4c;"></i></a></span>
                            <select name="insurance_type" id="insurance_type" style="max-width: 200px;">
                                <option value="2" @if(!$data['quick_booking']) selected @endif> @lang('sconfirm.addcompensation')</option>
                                <option value="1" @if($data['quick_booking'] and $data['insurance'] == '1') selected @endif> @lang('sconfirm.attach') </option>
                                <option value="0" @if($data['quick_booking'] and $data['insurance'] == 'no') selected @endif> @lang('sconfirm.notrequired')</option>
                            </select>
                          </th>
                          <td class="vta_b">
							@lang('sconfirm.yen_en')
                            <span id="insurance_cost">
                            @if($data['quick_booking'] and $data['insurance'] == 'no')
                            0
                            @elseif($data['quick_booking'] and $data['insurance'] == '1')
                                {{ number_format($data['insurance_price1']) }}
                            @else
                                {{ number_format($data['insurance_price1']+$data['insurance_price2']) }}
                            @endif
                            </span> @lang('sconfirm.yen') </td>
                        </tr>
						{{--<tr class="visible-xs">--}}
						<tr >
                          <th colspan="2" style="width:100%;">
							<a  data-toggle="modal" href="#Modal_01" class="added-txt" data-target="#Modal_01" style="background: #ffffcc;color: #333;">
									@lang('sconfirm.added') <i class="fa fa-question-circle" style="color:#4c4c4c;"></i>
							</a>
						  </th>
						</tr>
                        {{--
                        <tr>--}}
                          {{--
                          <th><span>免責補償</span></th>
                          --}}
                          {{--
                          <td>{{ $data['insurance_price1'] }}円</td>
                          --}}
                          {{--</tr>
                        --}}
                        {{--
                        <tr>--}}
                          {{--
                          <th><span>ワイド免責補償</span></th>
                          --}}
                          {{--
                          <td>{{ $data['insurance_price2'] }}円</td>
                          --}}
                          {{--</tr>
                        --}}
                        <tr>
                        @if(isset($data['option_names']) && !empty($data['option_names']))
                          <?php
                            $da = explode(',',$data['option_names']);
                            $c = count($da);
                            $na = explode(',',$data['option_costs']);
                            $ma = explode(',',$data['option_numbers']);
                            $in = explode(',',$data['option_indexs']);
                            $ab = explode(',',$data['option_names']);
                            $pr = explode(',', $data['option_prices']);
                        for($i = 0; $i < $c; $i++){
                          if($na[$i] * $ma[$i] != 0) {
                            ?>
                        <tr>
                          <th> <span>{{ $ab[$i] }}(@lang('sconfirm.option'))</span> </th>
{{--                          <td> {{ number_format($na[$i] * $ma[$i]) }}円 </td>--}}
                          <td> @lang('sconfirm.yen_en') {{ number_format(intval($pr[$i])) }}@lang('sconfirm.yen') </td>
                        </tr>
                        <?php
                            }
                         }?>
                        @endif

                        @if(!empty($data['pickup']))
                        <tr>
                          <th> @lang('sconfirm.airport') </th>
                          <td>@lang('sconfirm.yen_en') 0 @lang('sconfirm.yen') </td>
                        </tr>
                        @endif
                        <tr>
                          <th><small style="font-weight:500;"> @lang('sconfirm.fee')</small></th>
                          <td class="result">
							<span style="font-size: 0.5em"> @lang('sconfirm.yen_en')</span>
							<span id="payment">
                            @if($data['quick_booking'] and $data['insurance'] == 'no')
                            {!! number_format($data['price_all']) !!}
                            @elseif($data['quick_booking'] and $data['insurance'] == '1')
                            {{ number_format($data['price_all']+$data['insurance_price1']) }}
                            @else
                            {{ number_format($data['price_all']+$data['insurance_price1']+$data['insurance_price2']) }}
                            @endif </span> <span style="font-size: 0.5em"> @lang('sconfirm.yen')</span></td>
                        </tr>
                      </table>
                    </div>
                    <p>
						@lang('sconfirm.form')
					</p>

					<!-- Modal -->
					<div class="modal fade" id="Modal_01" tabindex="-1">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">
									<i class="fa fa-close pull-right" style="cursor:pointer; padding:10px;" data-dismiss="modal"></i>
									@if($util->lang() == 'ja')
									<img class="center-block img-responsive" src="{{ URL::to('/img/pages/insurance/hosyou_hoken12.png') }}" alt="免責補償とは？">
									@elseif($util->lang() == 'en')
									@endif
									<p> @lang('sconfirm.box1')</p>
									<img class="center-block img-responsive" src="{{ URL::to('/img/pages/insurance/hosyou_hoken12_en.png') }}" alt="About insurance">
									<p> @lang('sconfirm.box2') </p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal"> @lang('sconfirm.close') </button>
								</div>
							</div>
						</div>
					</div>
                    <a href="javascript:history.back()" style="background: #ef8385; color: white; padding: 5px 10px;text-decoration: none"> @lang('sconfirm.return') </a> </div>
                  <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 reserve-right">
                    <!-- <label for="check-member" style="position: absolute; right: 0; top: -15px; background: #ef8385; color: white; padding: 5px 10px;" id="lbl-member-check"> 会員様ご予約 </label> -->
                    <input type="checkbox" id="check-member" style="display: none;">
                    <div id="reserve-right-form-section" style="display: none">
                    <p class="reserve-right-ttl m0"> @lang('sconfirm.reservation') </p>
                    <button style="border-style: none" id="return-select-member" class="btn btn-danger pull-right"> @lang('sconfirm.return') </button>
                    <p class="reserve-right-ttl-small"><small> @lang('sconfirm.reservationmembership') </small></p>
                    {!! csrf_field() !!}
                    {{--<div class="alert alert-danger" id="error-content" style="display: none">--}}
                      {{--@if ($errors->any())--}}
                      {{--<ul>--}}
                        {{--@foreach ($errors->all() as $error)--}}
                        {{--<li>{{ $error }}</li>--}}
                        {{--@endforeach--}}
                        {{--</ul>--}}
                      {{--@endif--}}
                    {{--</div>--}}
                    <form id="reserve-right-form">
                        <div class="row">
                        <div class="col-xs-12">
                          <label> @lang('sconfirm.mail') <span class="req-red"> @lang('sconfirm.required')</span></label>
                          <input type="text" name="email" id="email" placeholder="メールアドレス" class="form-control h40" style="margin-bottom:5px;">
                            <div class="error-box col-md-12 col-sm-12 col-xs-12">
                              <span class="error-class erroremail"></span>
                            </div>
                        </div>
                      </div>
                      <div class="col-xs-12 only-nonmember" style="padding: 0; margin-bottom:0px;">
                        <label class="col-xs-12" style="padding: 0px 0 3px; margin-bottom:3px;"> @lang('sconfirm.name')<span class="req-red"> @lang('sconfirm.required')</span></label>
                        <div class="col-xs-12" style="padding: 0; margin-bottom:0px;">
                          <div class="col-xs-6" style="padding: 0 5px 0 0">
                            <input type="text" name="last_name" id="last_name" placeholder=" @lang('sconfirm.surname')" class="form-control h40" >
                            <span class="error-class errorlast_name"></span> </div>
                          <div class="col-xs-6" style="padding: 0 0 0 5px">
                            <input type="text" name="first_name" id="first_name" placeholder=" @lang('sconfirm.firstname')" class="form-control h40" >
                            <span class="error-class errorfirst_name"></span> </div>
                        </div>
                      </div>
                      @if($util->lang() == 'ja')
                      <div class="col-xs-12 only-nonmember" style="padding: 0; margin-bottom:10px;">
                        <label class="col-xs-12" style="padding: 4px 0 3px; margin-bottom:3px;"> @lang('sconfirm.phone') <span class="req-red"> @lang('sconfirm.required') </span></label>
                        <div class="col-xs-12" style="padding: 0">
                          <div class="col-xs-6" style="padding: 0 5px 0 0">
                            <input type="text" name="furi_last_name" id="furi_last_name" placeholder="@lang('sconfirm.furisurname')" class="form-control h40" >
                            <span class="error-class errorfuri_last_name"></span> </div>
                          <div class="col-xs-6" style="padding: 0 0 0 5px">
                            <input type="text" name="furi_first_name" id="furi_first_name" placeholder="@lang('sconfirm.furifirstname')" class="form-control h40" >
                            <span class="error-class errorfuri_first_name"></span> </div>
                        </div>
                      </div>
					  @endif
                      <div class="row">
                        <div class="col-xs-12 only-nonmember">
                          <label> @lang('sconfirm.phonenumber') <span class="req-red"> @lang('sconfirm.required') </span></label>
                          <input type="text" name="phone" id="phone" placeholder=" @lang('sconfirm.hyphen')" class="form-control h40">
                          <div class="error-box col-md-12 col-sm-12 col-xs-12"> <span class="error-class errorphone"></span> </div>
                        </div>
                      </div>
                      <div class="row password-already-member">
                        <div class="col-xs-12">
                          <label> @lang('sconfirm.password') <span class="req-red"> @lang('sconfirm.required') </span></label>
                          <input type="password" name="password" id="password" placeholder="@lang('sconfirm.enterpassword')" class="form-control h40">
                          <div class="error-box col-md-12 col-sm-12 col-xs-12"> <span class="error-class errorpassword"></span> </div>
                        </div>
                      </div>

                      <div id="forgot_password">
                        <a href="{{URL::to('/')}}/password/reset" style="margin-top:7px;float:left;color: #337ab7;"> @lang('sconfirm.resetpassword') </a>
                      </div>
                      <label for="check-it" class="check-change only-nonmember">
                        <input type="checkbox" name="accept-check" id="check-it" checked>
                        @lang('sconfirm.agree_en') <a data-toggle="modal" data-target="#myModal" href="#myModal" style="color: #337ab7;"> @lang('sconfirm.membership')</a> @lang('sconfirm.agree') </label>
                      <input type="submit" name="submit"  class="submitBtn form-control h40"  value="@lang('sconfirm.makereservation')">
                      <p class="only-nonmember m_B0"> @lang('sconfirm.sendemail')</p>


                    </form>
                    </div>

                    <div class="btn-main" id="btn-select-member" style="margin-top:100px;">
                      <button id="btn-new-member" class="btn-wrap"> @lang('sconfirm.firsttime')</button>
                      <button id="btn-existing-member" class="btn-wrap"> @lang('sconfirm.member')</button>
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
              <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10"> <a href="#"><img class="center-block img-responsive" src="" alt=""></a> </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 clearfix"> <a href="#" class="bg-carico totop-link"> @lang('sconfirm.toppage')</a> </div>
        </div>
      </div>
    </div>
    <!-- end search -->
  </div>
  <!-- END CONTENT -->
  <input type="hidden" id="ins_cost1" value="{{ $data['insurance_price1'] }}">
  <input type="hidden" id="ins_cost2" value="{{ $data['insurance_price2'] }}">
</div>
@include('modals.modal-membership')
    @include('modals.modal-error')
<script>
    var $errorBox = $('#error-content');
    var memberCheck = $("#check-it");
    var validate_rule = {
        last_name: { required: true },
        first_name: { required: true },
        furi_last_name: { required: true, katakana: true },
        furi_first_name: { required: true, katakana: true },
        email: { required: true, email: true },
        password: { required: true },
        phone: { required: true, number: true, minlength: 9, maxlength: 11 }
    };
    var ins_type    = 1;
    var ins_cost1   = {{ $data['insurance_price1'] }};
    var ins_cost2   = {{ $data['insurance_price2'] }};
    var price_rent  = {{$data['price_rent']}};
    var price_option = '{{ $data['option_costs'] }}';
    var price_all   = {{ $data['price_all'] }};

    function showModal(text) {
        $('#modalError p.error-text').html(text);
        $('#modalError').modal();
    }

    function selectModel(model_id) {
        $('input[name="model_id"]').val(model_id);
        // change style of selected tag
    }
    //check submit button prop disabled
    if(memberCheck.prop('checked') === true){
      $(".submitBtn").prop('disabled', false);
    }
    else{
      $(".submitBtn").prop('disabled', true);
    }

    memberCheck.click(function() {
      if($(this).prop('checked') === true){
        $(".submitBtn").prop('disabled', false).addClass('disabled');
      } else{
        $(".submitBtn").prop('disabled', true);
      }
    });

    $('#insurance_type').change( function () {
        var ins_cost = 0;
        switch($(this).val()) {
            case '0' :
                ins_cost = 0;
                $('#ins_cost1').val(0);
                $('#ins_cost2').val(0);
                break;
            case '1' :
                ins_cost = ins_cost1;
                $('#ins_cost1').val(ins_cost1);
                $('#ins_cost2').val(0);
                break;
            case '2' :
                ins_cost = ins_cost1 + ins_cost2;
                $('#ins_cost1').val(ins_cost1);
                $('#ins_cost2').val(ins_cost2);
                break;
        }
        $('#insurance_cost').html(ins_cost);
        $('#payment').html(price_all + ins_cost);
    });

    function check() {
        $(".submitBtn").prop('disabled', true);
        {{--form to go confirm page--}}
        var url = '{{URL::to('/')}}/search-save';
        var checked = $('#check-member').prop('checked') ? 1 : 0;
        var data = {
            "_token" :$('input[name="_token"]').val(),
            "email" : $('#email').val().trim(),
            "phone" : $('#phone').val().trim(),
            "password" : $('#password').val(),
            "depart_date" : "{{ $data['depart_date'] }}",
            "depart_time" : "{{ $data['depart_time'] }}",
            "return_date" : "{{ $data['return_date'] }}",
            "return_time" : "{{ $data['return_time'] }}",
            "depart_shop" : "{{ $data['depart_shop'] }}",
            "depart_shop_name" : "{{ $data['depart_shop_name'] }}",
            "return_shop" : "{{ $data['return_shop'] }}",
            "return_shop_name" : "{{ $data['return_shop_name'] }}",
            "car_category" : "{{ $data['car_category'] }}",
            "passenger" : "{{ $data['passenger'] }}",
            "insurance" : $('#insurance_type').val(),
            "insurance_price1" : $('#ins_cost1').val(),
            "insurance_price2" : $('#ins_cost2').val(),
            "smoke" : "{{ $data['smoke'] }}",
            "class_id" : "{{ $data['class_id'] }}",
            "class_name" : "{{ $data['class_name'] }}",
            "class_category" : "{{ $data['class_category'] }}",
            "car_photo" : "{{ $data['car_photo'] }}",
            "rent_days" : "{{ $data['rent_days'] }}",
            "price_rent" : price_rent,
            "option_ids" : "{{ $data['option_ids'] }}",
            "option_indexs" : "{{ $data['option_indexs'] }}",
            "option_names" : "{{ $data['option_names'] }}",
            "option_numbers" : "{{ $data['option_numbers'] }}",
            "option_costs" : price_option,
            "option_prices" : '{{ $data['option_prices'] }}',
            "price_all" : "{{ $data['price_all'] }}",
            "pickup" : "{{ $data['pickup'] }}",
            "member_check" : checked,
            "rent_dates" : '{{$data['rent_dates']}}'
        };
        if(checked === 0 ) { // when not member
            data['first_name'] = $('#first_name').val().trim();
            data['last_name'] = $('#last_name').val().trim();
            data['name'] = data.last_name + data.first_name;
            @if($util->lang() == 'ja')
            data['furi_first_name'] = $('#furi_first_name').val().trim();
            data['furi_last_name'] = $('#furi_last_name').val().trim();
            @endif
        }

        $.ajax({
            url : url,
            data : data,
            type : 'post',
            async: false,
            success : function(result, status) {
                console.log(result);
                if(status == 'success'){
                    if(result.success == true){
                        location.href = '{{URL::to('/')}}/thankyou';
                    } else {
                        console.log(result.errors);
                        var errors = result.errors,
                            errorsHtml = '<ul>';

                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>' + value + '</li>'; //showing only the first error.
                        });
                        errorsHtml += '</ul>';

                        showModal(errorsHtml);
                        // $errorBox.html(errorsHtml);
                        // $errorBox.fadeIn();
                    }
                } else {
                    showModal(result.error);
                    // $errorBox.html(result.error);
                    // $errorBox.fadeIn();
                }
            },
            error : function(xhr, status, error) {
                showModal(error);
                // $errorBox.html(error);
                // $errorBox.fadeIn();
            }
        })
    }

        // For existing member
    $("#btn-existing-member").click(function() {
        $('#check-it').attr('checked', false); // Unchecks it
        $('#check-member').prop('checked', true); // Unchecks it

        $('#lbl-member-check').text('@lang('sconfirm.nonmember')');
        $('.reserve-right-ttl').text('@lang('sconfirm.member')');
        $('.reserve-right-ttl-small').html('<small> @lang('sconfirm.hakorental')');
        $('#email').attr('placeholder', '@lang('sconfirm.youremail')');
        $('#phone').attr('placeholder', '@lang('sconfirm.phonenumberreg')');
        $('.submitbtn').val('@lang('sconfirm.gotoreservation')');
        $('.only-nonmember').fadeOut();
        $('#btn-select-member').hide();
        $('#reserve-right-form-section').show();
        $('.password-already-member').show();
        $('#forgot_password').show();
        $(".submitBtn").prop('disabled', false);

        validate_rule = {
          email: { required: true, email: true },
          password: { required: true}
        };
    });
        // For New member
        $("#btn-new-member").click(function() {
            $('#check-it').attr('checked', true); // Unchecks it
            $('#check-member').prop('checked', false); // Unchecks it
            $('#lbl-member-check').text('@lang('sconfirm.member')');
            $('.reserve-right-ttl').text('@lang('sconfirm.reservation')');
            $('.reserve-right-ttl-small').html('<small>@lang('sconfirm.reservationmembership')</small>');
            $('#email').attr('placeholder', '@lang('sconfirm.mail')');
            $('#phone').attr('placeholder', '@lang('sconfirm.hyphen')');
            $('.submitbtn').val('@lang('sconfirm.send')');
            $('.only-nonmember').fadeIn();
            $('#btn-select-member').hide();
            $('#reserve-right-form-section').show();
            $('.password-already-member').hide();
            $('#forgot_password').hide();
            //check submit button prop disabled
            if(memberCheck.prop('checked') === true){
              $(".submitBtn").prop('disabled', false);
            }
            else{
              $(".submitBtn").prop('disabled', true);
            }
            validate_rule = {
                last_name: { required: true },
                first_name: { required: true },
                furi_last_name: { required: true, katakana: true },
                furi_first_name: { required: true, katakana: true },
                email: { required: true, email: true },
                phone: { required: true, number: true, minlength: 9, maxlength: 11 }
            };
        });
        // For return select member Section
        $("#return-select-member").click(function() {
            $('#btn-select-member').show();
            $('#reserve-right-form-section').hide();
            $("#reserve-right-form")[0].reset();
        });
    </script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/localization/messages_ja.js" type="text/javascript"></script>
<script type="text/javascript">
  $('#reserve-right-form').validate({
      errorElement : 'span',
      errorPlacement: function(error, element) {
        var eP = $(".error"+element.attr("name"));
        error.appendTo(eP);
      },

      rules: validate_rule,
      messages: {
                email: {
                    required: jQuery.validator.format("@lang('sconfirm.requireemail')"),
                },
                last_name: {
                    required: jQuery.validator.format("@lang('sconfirm.requirelastname')"),
                },
                first_name: {
                    required: jQuery.validator.format("@lang('sconfirm.requireyourname')"),
                },
                furi_first_name: {
                    required: jQuery.validator.format("@lang('sconfirm.requireyourname')"),
                },
                furi_last_name: {
                    required: jQuery.validator.format("@lang('sconfirm.requirelastname')"),
                },
                phone: {
                    required: jQuery.validator.format("@lang('sconfirm.requirephonenumber')"),
                },
                password: {
                    required: jQuery.validator.format("@lang('sconfirm.requirepassword')"),
                },
                'checkboxes1[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                },
                'checkboxes2[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                }
            },
      submitHandler: function(form) {
        // do other things for a valid form
        // $("#contactForm").attr("action", "notification.php");
        // form.submit();
                check();
      }
    });

    //全角ひらがなのみ
    jQuery.validator.addMethod("katakana", function(value, element) {
     return this.optional(element) || /^([ァ-ヶー]+)$/.test(value);
         }, "全角カタカナを入力下さい"
    );
  </script>
@endsection

@section('style')
<style>

  </style>
@endsection

@section('footer_scripts')
<script src="{{URL::to('/')}}/js/plugins/kana/jquery.autoKana.js" type="text/javascript"></script>
<script type="text/javascript">
  //jQuery.noConflict();
    $(function() {
      $.fn.autoKana('#first_name', '#furi_first_name', {
        katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
      });
      $.fn.autoKana('#last_name', '#furi_last_name', {
        katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
      });
    });

  </script>
@endsection
