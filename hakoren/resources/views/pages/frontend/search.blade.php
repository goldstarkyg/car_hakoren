@extends('layouts.frontend')

@section('template_title')
    @lang('search.search')
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_search.css" rel="stylesheet" type="text/css"
          xmlns="http://www.w3.org/1999/html"/>
    <link id="bsdp-css" href="{{URL::to('/')}}/css/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css"
          rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/js/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/js/slick/slick-theme.css">

    <style>
        .chosen-single {
            height: 2.2em !important;
        }

        .opt_num {
            width: 60px;
            text-align: center;
        }

        .search_head { z-index: 3 !important; }

        #depart-time,
        #return-time {
            -webkit-appearance: menulist;
            -moz-appearance: menulist;
            appearance: menulist;
        }

        .left { display: none; }

        .left.active { display: block; }

        .alert-msg {
            color: brown;
            float: right;
            font-weight: 600;
        }

        .slick-prev:before, .slick-next:before { color: black; }
        .slick-slide { height: auto; }
        @media screen and (max-width: 425px){
            #passenger-block {
                display: none;
            }
            #passenger-block.content_display {
                display: block;
            }
            #passenger-toggle-btn {
                border: none;
                padding: 10px;
                border-radius: 5px !important;
                width: 100%;
                background-color: #e5e5e5;
            }
            #passenger-toggle-btn:after {
                content: "\f107";
                font: normal normal normal 14px/1 FontAwesome;
                float: right;
                line-height: 18px;
            }
        }
    </style>
@endsection
<?php
//var_dump($search);
?>
@section('content')
    @if(!empty($classes))
        <script>
            @if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false )
            dataLayer.push({
                'ecommerce': {
                    'currencyCode': 'JPY',
                    'impressions': [
                            @foreach($classes as $key=>$cls)
                        {
                            'name': '{{ $cls->class_name }}',
                            'id': '{{ $cls->id }}',
                            'price': '{{ $cls->price }}',
                            {{--'brand': '{{ $cls->abbriviation }}',--}}
                            'list': 'Search Car',
                            'position': {{ $key + 1 }}
                        },
                        @endforeach
                    ]
                }
            });
            @endif
        </script>
    @endif

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
                            <span>@lang('search.breadcrumb')</span>
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
                            <h1>&nbsp;@lang('search.title')</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">
                        <!--search condition-->
                        <div class="content-main col-xs-12">
                            <div class="box-shadow relative search-panel mobile_bg">
                                <div class="search_head" onclick="searchView()">
                                    <h2><i class="fa fa-calendar"></i>@lang('search.search-conditions')</h2> {{-- date('H:i') --}}
                                    <span id="view_search" class="glyphicon glyphicon-circle-arrow-down"
                                          id="view_search"></span>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form method="POST" action="{{URL::to('/search-car')}}" accept-charset="UTF-8"
                                              role="form" class="form-horizontal" enctype="multipart/form-data">
                                            {{--{!! Form::open(array('action' => 'Front\SearchController@search', 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal','enctype'=>'multipart/form-data')) !!}--}}
                                            {!! csrf_field() !!}
                                            <div id="searchform" class="search-main">
                                                <div class="row-bordered search_wrap">
                                                    <label class="col-md-2 col-sm-3 col-xs-12 control-label"><span>@lang('search.pickup-date')</span></label>
                                                    <div class="col-md-4 col-sm-9 col-xs-12 search_input">
                                                        <div class="input-group date col-sm-7 col-xs-7 pull-left"
                                                             id="depart-datepicker">
                                                            <input type="text" name="depart_date" id="depart-date"
                                                                   class="form-control input-sm"
                                                                   value="{{date('Y/m/d',strtotime($search->depart_date))}}"
                                                                   readonly required>
                                                            <span class="input-group-addon input-sm"><span
                                                                        class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                        <div class="col-sm-5 col-xs-5 dp-time">
                                                            <select class="form-control select-md" name="depart_time"
                                                                    id="depart-time" required>
                                                                @foreach($hours as $hour)
                                                                    <?php
                                                                    $selected = ($search->depart_time == $hour) ? 'selected' : '';
                                                                    ?>
                                                                    <option value="{{$hour}}" {{ $selected }} {{--$disabled--}}>
                                                                        {{$hour}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <label class="col-md-2 col-sm-3 col-xs-12 control-label return-text"><span>@lang('search.dropoff-date')</span></label>
                                                    <div class="col-md-4 col-sm-9 col-xs-12 search_input">
                                                        <div class="input-group date col-sm-7 col-xs-7 pull-left"
                                                             id="return-datepicker">
                                                            <input type="text" name="return_date" id="return-date"
                                                                   class="form-control input-sm"
                                                                   value="{{date('Y/m/d', strtotime($search->return_date))}}"
                                                                   readonly required>
                                                            <span class="input-group-addon input-sm"><span
                                                                        class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                        <div class="col-sm-5 col-xs-5 dp-time" style="padding-right: 0">
                                                            <select class="form-control input-sm " name="return_time"
                                                                    id="return-time" required>
                                                                @foreach($hours as $hour)
                                                                    <?php
                                                                    $selected = ($search->return_time == $hour) ? 'selected' : '';
                                                                    ?>
                                                                    <option value="{{$hour}}" {{$selected}}>{{$hour}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row-bordered search_wrap">
                                                    <label class="col-md-2 col-sm-3 col-xs-12 control-label"><span>@lang('search.location')</span></label>
                                                    <div class="col-md-4 col-sm-9 col-xs-12 search_input">
                                                        <?php
                                                            $name = $util->Tr('name');
                                                        ?>
                                                        <select class="form-control input-sm" name="depart_shop"
                                                                id="depart-shop" required>
                                                            <option value="0">@lang('search.select-lacation')</option>
                                                            @foreach($shops as $shop)
                                                                <option value="{{$shop->id}}"
                                                                        @if($search->depart_shop == $shop->id) selected @endif>{{$shop->$name}}</option>
                                                            @endforeach
                                                        </select>
                                                        {{--</label>--}}
                                                    </div>
                                                    <label class="col-sm-2 control-label hide">@lang('search.return-lacation')</label>
                                                    <div class="col-sm-4 hide">
                                                        <select class="form-control input-sm" name="return_shop"
                                                                id="return-shop">
                                                            <option value="0">@lang('search.select-lacation')</option>
                                                            @foreach($shops as $shop)
                                                                <option value="{{$shop->id}}"
                                                                        @if($shop->id == $search->return_shop) selected @endif>{{$shop->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row-bordered hidden">
                                                    <label class="col-sm-2 control-label">@lang('search.car-category')</label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $car_category = $search->car_category;
                                                        foreach ($categorys as $cate) {
                                                            if ($cate->name == '乗用車') {
                                                                $car_category = $cate->id;
                                                            }
                                                        } ?>

                                                        <input type="hidden" name="car_category" id="car-category"
                                                               value="{{$car_category}}">
                                                    </div>
                                                </div>
                                                <div class="row-bordered search_wrap">
                                                    <div class="mobile_btn">
                                                        <button id="passenger-toggle-btn" type="button">@lang('search.seats')</button>
                                                    </div>


                                                    <label class="col-md-2 col-sm-3 hidden-xs control-label"><span>@lang('search.seats')</span></label>
                                                    <div class="col-md-10 col-sm-9 col-xs-12" id="passenger-block">
                                                        <input type="hidden" name="passenger" id="passenger"
                                                               value="{{$search->passenger}}">
                                                        <label class="search_cartype pull-left search_passenger "
                                                               id="search_passenger_all"
                                                               onclick="selectPassenger('all')">
                                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                            @lang('search.seats-all')</label>
                                                        @foreach( $psgtags as $tag)
                                                            <label class="search_cartype pull-left search_passenger"
                                                                   onclick="selectPassenger({{ $tag->min_passenger }})"
                                                                   id="search_passenger_{{ $tag->min_passenger }}">
                                                                <i class="fa fa-check-circle"
                                                                   aria-hidden="true"></i> {{ $tag->$name }}</label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="row-bordered hidden">
                                                    <label class="col-sm-2 control-label">免責補償</label>
                                                    <div class="col-sm-10">
                                                        <?php $search->insurance = 0; ?>
                                                        <input type="hidden" name="insurance" id="insurance"
                                                               value="{{$search->insurance}}">
                                                    </div>
                                                </div>
                                                <div class="row-bordered hidden">
                                                    <label class="col-sm-2 control-label">禁煙/喫煙</label>
                                                    <div class="col-sm-10">
                                                        <input type="hidden" name="smoke" id="smoke"
                                                               value="{{$search->smoke}}">
                                                        <label class="search_cartype pull-left search_smoke"
                                                               id="search_smoke_both" onclick="selectSmoke('both')">
                                                            <i class="fa fa-check-circle" aria-hidden="true"></i>どちらでも良い</label>
                                                        <label class="search_cartype pull-left search_smoke"
                                                               id="search_smoke_1" onclick="selectSmoke('1')">
                                                            <i class="fa fa-check-circle"
                                                               aria-hidden="true"></i>喫煙</label>
                                                        <label class="search_cartype pull-left search_smoke"
                                                               id="search_smoke_0" onclick="selectSmoke('0')">
                                                            <i class="fa fa-check-circle"
                                                               aria-hidden="true"></i>禁煙</label>
                                                    </div>
                                                </div>
                                                <div class="mobile_btn">
                                                    <button class="click_btn" type="button">@lang('search.extras')</button>
                                                </div>

                                                <div id="option_wrapper"
                                                     class="mobile_toggle @if($search->depart_shop == '') hidden @endif">
                                                    <div class="row-bordered-0 search_wrap">
                                                        <label class="col-md-2 col-sm-3 col-xs-12 control-label"><span>@lang('search.extras')</span></label>
                                                        <input type="hidden" name="option_list" id="option-list"
                                                               value="{{$search->options}}">
                                                        <div class="col-md-10 col-sm-9 col-xs-12 option-list option_name">
                                                            <ul>
                                                                @foreach($paid_options as $key => $op)
                                                                    <?php
                                                                    if (in_array($op->id, explode(',', $search->options))) {
                                                                        $checked = 'checked';
                                                                        $active = 'active';
                                                                    } else {
                                                                        $checked = '';
                                                                        $active = '';
                                                                    }
                                                                    ?>
                                                                    <li class="search_option {{$active}}">
                                                                        <input type="checkbox"
                                                                               id="option_check_{{$key}}"
                                                                               name="options[]"
                                                                               value="{{$op->id}}" {{$checked}}>
                                                                        <label id="option_label_{{$key}}"
                                                                               for="option_check_{{$key}}">
                                                                            <span>{{$op->$name}}</span>
                                                                        </label>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <!---->
                                                    <div class="row-bordered-0  pickup_object" style="padding-top: 0px; padding-bottom: 0px;">
                                                        <label class="col-md-2 col-sm-3 col-xs-12"></label>
                                                        <div class="col-md-10 col-sm-9 col-xs-12 etc_message">
                                                            @lang('search.etc_message')
                                                        </div>
                                                    </div>
                                                    <!---->
                                                    <div class="row-bordered-0 pickup_object search_wrap"
                                                         style=" @if($search->depart_shop == '5') display:none; @endif">
                                                        <label class="col-md-2 col-sm-3 col-xs-12 control-label free_opt"><span>@lang('search.free-pickup-service')</span></label>
                                                        <input type="hidden" name="free_list" id="free_list"
                                                               value="{{$search->free_options}}">
                                                        <div class="col-md-10 col-sm-9 col-xs-12 option-list">
                                                            <ul id="free_options">
                                                                @foreach($free_options as $key=>$op)
                                                                    <?php
                                                                    if (in_array($op->id, explode(',', $search->free_options))) {
                                                                        $checked = 'checked';
                                                                        $active = 'active';
                                                                    } else {
                                                                        $checked = '';
                                                                        $active = '';
                                                                    }
                                                                    $ispickup = ''; $free_opt_name = 'free_options[]';
                                                                    if ($op->google_column_number == 101 || $op->google_column_number == 102) {
                                                                        $ispickup = 'pickup';
                                                                        $free_opt_name = 'pickup';
                                                                    }

                                                                    ?>
                                                                    <li class="{{$active}}">
                                                                        <input id="free{{$key}}" class="{{$ispickup}}"
                                                                               type="checkbox"
                                                                               name="{{$free_opt_name}}"
                                                                               value="{{$op->id}}" {{$checked}} >
                                                                        <label for="free{{$key}}"><span>{{$op->$name}}</span></label>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row-bordered-0 search-btn-block">
                                                    <label>
                                                        <button class="search-btn" type="submit"
                                                                onclick="return submitSearch();">
                                                            <span>@lang('search.searchbtn')</span><br/><span
                                                                    class="large">@lang('search.searchbtn-2')</span></button>
                                                    </label>
                                                    <label style="margin-left: 20px;cursor: pointer"
                                                           onclick="searchView()">
                                                        [ &times; ] {{-- hide this box --}}
                                                    </label>
                                                </div>
                                            </div>
                                            {{--{!! Form::close() !!}--}}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end search condition-->
                        <!--search result-->
                        <div class="content-main col-xs-12 margin-bottom-30 search-result form_last_content"
                             id="result_block" style="margin-top:10px;font-size: 16px;">
                            <span class="result-title bottom_content">@lang('search.search-result')</span>
                            <span class="mobile_bot" id="search_result_info">
                                @if($search->depart_shop_name != '')
                                    @if(count($classes) > 0)
                                        @if($util->lang() == 'ja')
                                            <span class="highlight">{{ date('Y年m月d日', strtotime($search->depart_date)) }}
                                                ～{{ date('Y年m月d日', strtotime($search->return_date)) }}</span> {{ $search->depart_shop_name }}
                                            で上記条件に合う車両クラスは<span class="highlight">{{ count($classes) }}件</span>です。
                                        @endif
                                        @if($util->lang() == 'en')
                                            <span class="highlight">{{ date('Y/m/d/', strtotime($search->depart_date)) }}
                                                ～{{ date('Y/m/d/', strtotime($search->return_date)) }}</span> {{ $search->depart_shop_name_en }}
                                                The vehicles that meets the above conditions is <span class="highlight">{{ count($classes) }} </span>
                                        @endif
                                    @else
                                        <span id="NoResults">
                                        @lang('search.no-class')
                                        </span>
                                    @endif
                                @else
                                    @lang('search.enter-condition')
                                @endif
                            </span>

                        </div>
                        <!--class-->

                        <!--loop for search-->
                        <?php
                            $thumb_path = $util->Tr('thumb_path');
                            $shop_name  = $util->Tr('shop_name');
                            $category_name = $util->Tr('category_name');
                        ?>
                        @foreach($classes as $class)
                            <?php $cid = $class->id; ?>
                            <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 listing_wrap">
                                <div class="box-shadow relative search-result-panel ">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="car_title m_T10 m_B20" class_id="{{ $cid }}"
                                                 style="margin-left: 10px">
                                                {{$class->class_name}}
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="col-sm-6 col-xs-12">
                                                <input type="hidden" class="car_photo" class_id="{{ $cid }}" value="{{ $class->thumb_path }}">
                                                <div class="col-xs-12 thumb_slider" style="margin-bottom: 10px">
                                                    <img src="{{URL::to('/').$class->$thumb_path}}" class="img-responsive center-block" >
                                                    @foreach($class->thumbnails as $thumb)
                                                        <img src="{{URL::to('/').$thumb->thumb_path}}" class="img-responsive center-block">
                                                    @endforeach
                                                </div>
                                                <p class="sml-txt">
                                                    <label class="result_shop">
                                                        @if($class->shop_name =="" )
															@lang('search.unselected')
                                                        @else
                                                            {{$class->$shop_name}}
                                                        @endif
                                                    </label>
                                                    <label class="result_shop car_category" class_id="{{ $cid }}" style="margin-left: 20px;">
                                                        @if($class->category_name =="" )
															@lang('search.carcate-unselected')
                                                        @else
                                                            {{$class->$category_name}}
                                                        @endif
                                                    </label>
                                                </p>
                                            </div>
                                            <div class="col-sm-6 col-xs-12 padding-right-0">
                                                <div class="panel panel-default" style="margin-bottom: 5px;">
                                                    <div class="panel-heading bg-grad-gray">
															@lang('search.estimatedprice')
                                                    </div>
                                                    <div class="panel-body" style="padding-bottom: 0px;">
                                                        <div class="form-group row-bordered-result row">
                                                            <label class="col-md-7 col-sm-7 col-xs-6 label_manage"
                                                                   style="padding-left: 0;padding-top: 3px;
    margin-bottom: 10px;">

                                                                <?php
                                                                if($util->lang() == 'ja') {
                                                                    $dt1 = date('Y年n月j日', strtotime($class->depart_date)) . ' ';
                                                                    $dt1 .= date('G時', strtotime($class->depart_time));
                                                                    $min = intval(date('i', strtotime($class->depart_time)));
                                                                    if ($min > 0) $dt1 .= $min . '分';
                                                                    $dt2 = date('Y年n月j日', strtotime($class->return_date)) . ' ';
                                                                    $dt2 .= date('G時', strtotime($class->return_time));
                                                                    $min = intval(date('i', strtotime($class->return_time)));
                                                                    if ($min > 0) $dt2 .= $min . '分';
                                                                }else{
                                                                    $dt1 = date('Y/n/j', strtotime($class->depart_date)) . ' ';
                                                                    $dt1 .= date('G:', strtotime($class->depart_time));
                                                                    $min = date('i', strtotime($class->depart_time));
                                                                    if ($min >= 0) $dt1 .= $min . ' ';
                                                                    $dt2 = date('Y/n/j/', strtotime($class->return_date)) . ' ';
                                                                    $dt2 .= date('G:', strtotime($class->return_time));
                                                                    $min = date('i', strtotime($class->return_time));
                                                                    if ($min >= 0) $dt2 .= $min . ' ';
                                                                }
                                                                ?>
                                                                <div>
                                                                    <label>@lang('search.depart')</label>
                                                                    <label>{{$dt1}}</label>
                                                                </div>
                                                                <div>
                                                                    <label>@lang('search.return')</label>
                                                                    <label>{{$dt2}}</label>
                                                                </div>
                                                            </label>
                                                            <label class="col-md-5 col-sm-5 col-xs-6" style="padding-right: 0">
                                                                <div class="bubble-wrap toltip_wrap" style="width: 100%">

                                                                    <?php
                                                                    $leftmany = ($class->car_count >= 10) ? 'active' : '';
                                                                    $leftfew = ($class->car_count <= 9 && $class->car_count >= 4) ? 'active' : '';
                                                                    $leftafew = ($class->car_count <= 3) ? 'active' : '';
                                                                    ?>
                                                                    <div class="bubble left many {{$leftmany}}" class_id="{{ $cid }}" style="font-size: 16px">
                                                                        @lang('search.available')
                                                                    </div>
                                                                    <div class="bubble left few {{$leftfew}}" class_id="{{ $cid }}" style="font-size: 16px">
                                                                        @lang('search.few')
                                                                    </div>
                                                                    <div class="bubble left afew {{$leftafew}}" class_id="{{ $cid }}" style="font-size: 16px">
                                                                        @lang('search.stocknumber1')<span class="bubble_write car_count" class_id="{{ $cid }}">{{ $class->car_count }}</span>@lang('search.stocknumber2')
                                                                    </div>

                                                                </div>
                                                            </label>
                                                        </div>
                                                        <div class="form-group row-bordered-result row">
                                                            <label class="pull-left span_nightday" class_id="{{ $cid }}">
                                                                @lang('search.basic_charge') (<?php if ($class->night_day == "0泊1日") {
                                                                    if($util->lang() == 'ja') {
                                                                        echo "当日返却";
                                                                        }elseif($util->lang() == 'en') {
                                                                        echo "Return on the day";
                                                                    }
                                                                } else {
                                                                    if($util->lang() == 'ja'){
                                                                        echo $class->night_day;
                                                                        }elseif($util->lang() == 'en') {
                                                                        echo $class->night_day_en;
                                                                    }
                                                                } ?>)
                                                            </label>
                                                            <label class="pull-right basic_price" class_id="{{ $cid }}">
                                                               @lang('search.yen_en') {{number_format($class->price)}} @lang('search.yen')
                                                            </label>
                                                            <input type="hidden" class="rent_days" class_id="{{ $cid }}"
                                                                   value="{{ $class->night_day }}">
                                                            <input type="hidden" class="price_rent"
                                                                   class_id="{{ $cid }}" value="{{ $class->price }}">
                                                        </div>
                                                        <?php
                                                        $option_ids = [];
                                                        $option_names = [];
                                                        $option_costs = [];
                                                        $option_numbers = [];
                                                        $option_prices = [];
                                                        if (!empty($class->options)) {
                                                            foreach ($class->options as $op) {
                                                                $option_ids[] = $op->id;
                                                                if($util->lang() == 'ja')
                                                                    $option_names[] = $op->name;
                                                                if($util->lang() == 'en')
                                                                    $option_names[] = $op->name_en;
                                                                $option_costs[] = $op->price;
                                                                $option_numbers[] = 1;
                                                                $vp = 0;
                                                                if ($op->charge_system == 'one') {
                                                                    $vp = $op->price;
                                                                } else {
                                                                    $vp = $op->price * $search->rentdates;
                                                                }
                                                                $option_prices[] = $vp;
                                                            }
                                                        }
                                                        ?>
                                                        @if(!empty($class->options))
                                                            <div class="option-wrapper" class_id="{{ $cid }}">
                                                                <table style="width: 100%">
                                                                    <tbody style="font-size: 13px">
                                                                    @foreach($class->options as $op)
                                                                        <tr class=" row-bordered-result">
                                                                            <td style="text-align: left">{{$op->$name}}
                                                                                (@lang('search.extras'))
                                                                            </td>
                                                                            <td style="text-align: center">
                                                                                <input type="hidden" name="opt_charge"
                                                                                       class="opt_charge"
                                                                                       class_id="{{ $cid }}"
                                                                                       oid="{{$op->id}}"
                                                                                       value="{{$op->charge_system}}">
                                                                                @if($op->max_number == 1)

                                                                                    <select name="opt_num"
                                                                                            class="opt_num selectpicker"
                                                                                            class_id="{{ $cid }}"
                                                                                            oid="{{$op->id}}"
                                                                                            value="{{ $op->number }}"
                                                                                            min="0">
                                                                                        <option value="1"
                                                                                                @if($op->number == '1') selected @endif>
                                                                                            @lang('search.need')
                                                                                        </option>
                                                                                        <option value="0"
                                                                                                @if($op->number == '0') selected @endif>
                                                                                            @lang('search.noneed')
                                                                                        </option>
                                                                                    </select>
                                                                                @else

                                                                                    <select name="opt_num"
                                                                                            class="opt_num selectpicker"
                                                                                            class_id="{{ $cid }}"
                                                                                            oid="{{$op->id}}"
                                                                                            value="{{ $op->number }}"
                                                                                            min="0">
                                                                                        @for($k = 0; $k < $op->max_number; $k++)
                                                                                            <option value="{{ $k }}"
                                                                                                    @if($op->number == $k) selected @endif>{{ $k }}
                                                                                                @lang('search.individual')
                                                                                            </option>
                                                                                        @endfor
                                                                                    </select>
                                                                                @endif
                                                                            </td>
                                                                            <td style="text-align: right">
                                                                                <?php
                                                                                if ($op->charge_system == 'one') {
                                                                                    $oprice = $op->price * $op->number;
                                                                                } else {
                                                                                    $oprice = $op->price * $op->number * $search->rentdates;
                                                                                }
                                                                                ?>
                                                                                @lang('search.yen_en') <span class="opt_cost" oid="{{$op->id}}"
                                                                                      class_id="{{$cid}}">{{number_format($oprice)}}</span> @lang('search.yen')
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <input type="hidden" class="option_ids"
                                                                   class_id="{{ $cid }}"
                                                                   value="{{ implode(',', $option_ids) }}">
                                                            <input type="hidden" class="option_names"
                                                                   class_id="{{ $cid }}"
                                                                   value="{{ implode(',', $option_names) }}">
                                                            <input type="hidden" class="option_numbers"
                                                                   class_id="{{ $cid }}"
                                                                   value="{{ implode(',', $option_numbers) }}">
                                                            <input type="hidden" class="option_costs"
                                                                   class_id="{{ $cid }}"
                                                                   value="{{ implode(',', $option_costs) }}">
                                                            <input type="hidden" class="option_prices"
                                                                   class_id="{{ $cid }}"
                                                                   value="{{ implode(',', $option_prices) }}">
                                                        @endif

                                                        @if(!empty($class->insurance))
                                                            <div class="form-group row-bordered-result row hidden">
                                                                <div class="col-xs-6" style="padding: 0">
                                                                    免責保障
                                                                    <select name="insurance"
                                                                            class="insurance pull-right"
                                                                            class_id="{{ $cid }}">
                                                                        <option value="0">不要</option>
                                                                        <option value="{{ $class->insurance[1] }} selected">
                                                                            免責補償
                                                                        </option>
                                                                        <option value="{{ $class->insurance[1]+$class->insurance[2] }}">
                                                                            ワイド免責補償
                                                                        </option>
                                                                    </select>
                                                                    <input type="hidden" class="insurance_price1"
                                                                           value="{{ $class->insurance_price1 }}"
                                                                           class_id="{{ $cid }}">
                                                                    <input type="hidden" class="insurance_price2"
                                                                           value="{{ $class->insurance_price2 }}"
                                                                           class_id="{{ $cid }}">
                                                                </div>
                                                                <div class="col-xs-6" style="padding-right: 0;">
                                                                    <label class="pull-right">
                                                                        <span class="insurance-price"
                                                                              class_id="{{ $cid }}">0</span>円
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @php
                                                            $max_passengers = $class->max_passengers;
                                                            $noset_maxpassenger = count($max_passengers) == 0;
                                                            $count_notempty = 0; $mps = [];
                                                            foreach($max_passengers as $key=>$mp){
                                                                if($mp->count > 0) {
                                                                    $count_notempty++;
                                                                    $mps[] = $mp;
                                                                }
                                                            }
                                                            $noset_maxpassenger = $count_notempty == 0;
                                                        @endphp

                                                        <div class="form-group row-bordered-result row">
                                                            <div class="col-xs-12" style="padding: 0">
                                                                @lang('search.nonsmoke')/@lang('search.smoke')
                                                                <select name="car_smoking"
                                                                        class="car_smoking pull-right @if($util->lang() == 'ja') jpwd34 @endif"
                                                                        class_id="{{ $cid }}"
                                                                        @if($noset_maxpassenger) disabled @endif>
                                                                    <option value="0"
                                                                            @if($class->smoke == '0') selected @endif> @lang('search.nonsmoke')
                                                                    </option>
                                                                    <option value="1"
                                                                            @if($class->smoke == '1') selected @endif> @lang('search.smoke')
                                                                    </option>
                                                                    <option value="both"
                                                                            @if($class->smoke == 'both') selected @endif>
                                                                        @lang('search.bothsmoke')
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row-bordered-result row">
                                                            @if(count($max_passengers) == 1)
                                                                <div class="col-xs-12" style="padding: 0">
                                                                    <b>{{$max_passengers[0]->max_passenger}}</b> @lang('search.passenger')
                                                                    <input type="hidden" name="car_passenger"
                                                                           class="car_passenger @if($util->lang() == 'ja') jpwd34 @endif"
                                                                           value="{{$max_passengers[0]->max_passenger}}"
                                                                           class_id="{{ $cid }}">
                                                                </div>
                                                            @elseif(count($max_passengers) > 1)
                                                                <div class="col-xs-12" style="padding: 0">
                                                                    @if($count_notempty > 1)
                                                                        @lang('search.capacity')
                                                                        <select name="car_passenger"
                                                                                class="car_passenger pull-right  @if($util->lang() == 'ja') jpwd34 @endif"
                                                                                class_id="{{ $cid }}">
                                                                            @foreach($mps as $pt)
                                                                                <option value="{{$pt->max_passenger}}">{{$pt->max_passenger}}
                                                                                    @lang('search.riding')
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    @elseif($count_notempty == 1)
                                                                        <span>
                                                                        @lang('search.stock') <b>{{$mps[0]->max_passenger}}</b> @lang('search.called')</span>
                                                                        <input type="hidden" name="car_passenger"
                                                                               class="car_passenger  @if($util->lang() == 'ja') jpwd34 @endif"
                                                                               value="{{$mps[0]->max_passenger}}"
                                                                               class_id="{{ $cid }}">
                                                                    @else
                                                                        <span class="alert-msg"> @lang('search.nostock')</span>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="col-xs-12" style="padding: 0"> @lang('search.capacity')
                                                                    <span class="alert-msg"> @lang('search.nocapacity')</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @if(count($free_options)>0)
                                                            <div class="form-group row-bordered-result row">
                                                                <div class="col-xs-12" style="padding: 0">
                                                                    @lang('search.pickup')
                                                                    <select name="car_pickup" class="car_pickup pull-right @if($util->lang() == 'ja') jpwd34 @endif" class_id="{{ $cid }}">
                                                                        @foreach($free_options as $fr)
                                                                            <option value="{{ $fr->id }}"
                                                                                    @if($fr->id == $search->pickup) selected @endif>{{ $fr->$name }}</option>
                                                                        @endforeach
                                                                        <option value="" @if($search->pickup == '') selected @endif>
                                                                            @lang('search.noneed_airport')
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="form-group">
                                                            <label class="col-sm-5 padding-0">
                                                                <div> @lang('search.tax')</div>
                                                            </label>
                                                            <label class="col-sm-7 padding-0" style="color: #b63432">
                                                                <div style="padding-top: 15px;">
                                                                    @lang('search.yen_en')
																	<label style="font-weight:bold;font-size: 55px; color:#e60707;/*margin-top: -20px; margin-bottom: -20px;*/" class="total_price" class_id="{{ $cid }}">
                                                                        {{number_format($class->all_price)}}
                                                                    </label><label style="font-weight: 300"> @lang('search.yen') </label>
                                                                </div>
                                                                <input type="hidden" class="price_all" class_id="{{ $cid }}" value="{{ $class->all_price }}">
                                                                <div style="margin-top:6px;"> @lang('search.lowestprice') </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center xsmb8">
                                                    <label>
                                                        <button class="btn bg-grad-red btn_book{{$cid}}" style=" margin-top:10px;padding: 10px 50px 10px 50px"
                                                                onclick="submit_booking({{ $cid }}, '{{$class->class_name}}')"
                                                                @if($noset_maxpassenger)disabled @endif>
                                                            @lang('search.reservation')
                                                        </button>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    <!--end loop-->

                        {{--section of static classes--}}
                        @foreach($static_classes as $class)
                            <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 listing_wrap">
                                <div class="box-shadow relative search-result-panel ">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="car_title m_T10 m_B20" class_id="{{ $class->id }}"
                                                 style="margin-left: 10px">
                                                @if($class->name == 'SSP2')
                                                    @lang('search.lexus460')
                                                @elseif($class->name == 'SSP3')
                                                    @lang('search.lexus4601')
                                                @else
                                                    {{ $class->name }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="col-sm-6 col-xs-12">
                                                <?php if ($class->name == 'SSP3') $class->thumb = '/img/ssp3.jpg'; ?>
                                                <img src="{{URL::to('/').$class->thumb}}"
                                                     class="img-responsive center-block m_Txs60">
                                            </div>
                                            <div class="col-sm-6 col-xs-12 padding-right-0">
                                                <div class="panel panel-default" style="margin-bottom: 5px;">
                                                    <div class="panel-body" style="padding-bottom: 0px;">
                                                        <div class="form-group row-bordered-result row">
                                                            <h3>@lang('search.number_passenger')：
                                                                @if($class->minPsg == 0)
                                                                @elseif($class->minPsg == $class->maxPsg)
                                                                    {{ $class->minPsg }}
                                                                @else
                                                                    {{ $class->minPsg }}~{{ $class->maxPsg }}
                                                                @endif
                                                                @lang('search.name')
                                                            </h3>
                                                        </div>
                                                        <div class="form-group row-bordered-result row">
                                                            <label class="col-sm-4 col-xs-5 label_manage"
                                                                   style="padding-left: 0;padding-top: 3px; margin-bottom: 10px;">
                                                                @lang('search.model')
                                                            </label>
                                                            <label class="col-sm-8 col-xs-7" style="padding-right: 0">
                                                                @if($class->name == 'SSP2')
                                                                    @lang('search.lexus460')
                                                                @elseif($class->name == 'SSP3')
                                                                    @lang('search.lexus4601')
                                                                @else
                                                                    {{ $class->name }}
                                                                @endif
                                                            </label>
                                                        </div>
                                                        <div class="form-group row-bordered-result row">
                                                            <label class="col-sm-4 col-xs-5 label_manage"
                                                                   style="padding-left: 0;padding-top: 3px; margin-bottom: 10px;">
                                                                @lang('search.option')
                                                            </label>
                                                            <?php
                                                                $option_names = $util->Tr('option_names');
                                                            ?>
                                                            <label class="col-sm-8 col-xs-7" style="padding-right: 0">
                                                                {{ $class->$option_names}}
                                                            </label>
                                                        </div>
                                                        <div class="form-group row-bordered-result row"
                                                             style="border-bottom:0;">
                                                            <label class="col-sm-4 col-xs-5 label_manage"
                                                                   style="padding-left: 0;padding-top: 3px; margin-bottom: 10px;">
                                                                @lang('search.price')
                                                            </label>
                                                            <label class="col-sm-8 col-xs-7" style="padding-right: 0;">

                                                                @if($class->name == 'MB')
																	@if($util->lang() == 'ja')
																		@lang('search.mbprice') <br/>
																	@endif
                                                                @else
                                                                    @lang('search.lexusdesc1')
                                                                @endif
                                                                <br/>
																		@lang('search.rental')

                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center xsmb8" style="padding-top: 15px;">
                                                    <label>
                                                        <a class="btn bg-grad-red"
                                                           href="{{URL::to('/contact')}}"> @lang('search.inquiries') </a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
        {{--form to go confirm page--}}
        <form action="{{URL::to('/')}}/search-confirm" method="POST" name="booking-submit" id="booking-submit">
            {!! csrf_field() !!}
            <input type="hidden" name="data_depart_date" id="data_depart_date">
            <input type="hidden" name="data_depart_time" id="data_depart_time">
            <input type="hidden" name="data_return_date" id="data_return_date">
            <input type="hidden" name="data_return_time" id="data_return_time">
            <input type="hidden" name="data_depart_shop" id="data_depart_shop">
            <input type="hidden" name="data_depart_shop_name" id="data_depart_shop_name">
            <input type="hidden" name="data_return_shop" id="data_return_shop">
            <input type="hidden" name="data_return_shop_name" id="data_return_shop_name">
            <input type="hidden" name="data_car_category" id="data_car_category">
            <input type="hidden" name="data_passenger" id="data_passenger">
            <input type="hidden" name="data_insurance" id="data_insurance">
            <input type="hidden" name="data_insurance_price1" id="data_insurance_price1">
            <input type="hidden" name="data_insurance_price2" id="data_insurance_price2">
            <input type="hidden" name="data_smoke" id="data_smoke">
            <input type="hidden" name="data_option_list" id="data_option_list">
            <input type="hidden" name="data_class_id" id="data_class_id">
            <input type="hidden" name="data_class_name" id="data_class_name">
            <input type="hidden" name="data_class_category" id="data_class_category">
            <input type="hidden" name="data_car_photo" id="data_car_photo">
            <input type="hidden" name="data_rent_days" id="data_rent_days">
            <input type="hidden" name="data_rent_dates" id="data_rent_dates" value="{{ $search->rentdates }}">
            <input type="hidden" name="data_price_rent" id="data_price_rent">
            <input type="hidden" name="data_option_ids" id="data_option_ids">
            <input type="hidden" name="data_option_names" id="data_option_names">
            <input type="hidden" name="data_option_numbers" id="data_option_numbers">
            <input type="hidden" name="data_option_costs" id="data_option_costs">
            <input type="hidden" name="data_option_prices" id="data_option_prices">
            <input type="hidden" name="data_price_all" id="data_price_all">
            <input type="hidden" name="data_member" id="data_member">
            <input type="hidden" name="data_pickup" id="data_pickup">

        </form>
        {{--end form--}}
    </div>
@endsection

@include('modals.modal-error')

@section('style')
    <style>
    </style>
@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js"
            charset="UTF-8"></script>
    <script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script src="{{URL::to('/')}}/js/slick/slick.js" type="text/javascript" charset="utf-8"></script>
		
    <script type="text/javascript">

        $(function () {
            $(".click_btn").click(function () {
				var lang = "{{$util->lang()}}";
				if(lang == "ja"){
					$(this).text(function (i, text) {
						return text === "オプションを追加する" ? "閉じる" : "オプションを追加する";
					})
				}else if(lang == "en"){
					$(this).text(function (i, text) {
						return text === "Extras " ? "Close" : "Extras ";
					})
				}
                $(".mobile_toggle").slideToggle(1000);
                $(".mobile_toggle").toggleClass("content_display");
            });

            $("#passenger-toggle-btn").click(function () {
				var lang = "{{$util->lang()}}";
				if(lang == "ja"){
					$(this).text(function (i, text) {
						return text === "乗車人数を選択" ? "閉じる" : "乗車人数を選択";
					})
				}else if(lang == "en"){
					$(this).text(function (i, text) {
						return text === "Seats" ? "Close" : "Seats";
					})
				}
                $("#passenger-block").slideToggle(1000).toggleClass("content_display");
            });
        })

        $(document).ready(function () {
            @if($request_page != 'toppage')
                @if(!empty($classes))
                $('html, body').animate({
                    scrollTop: $("#result_block").offset().top
                }, 1000);
                @endif
            @endif
            $('.thumb_slider').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                // autoplay: true,
                // autoplaySpeed: 3333000,
            });

        });
        var modelError = $('#modalError');
        var rent_dates = '{{ $search->rentdates }}' * 1;
        var today = new Date();
        today = new Date(today.getFullYear(), today.getMonth(), today.getDate(),0,0,0,0);
        var tomorrow = new Date();
        tomorrow.setDate(today.getDate() + 1);

        function showModal(msg) {
            $('.error-text').text(msg);
            modelError.modal('show');
        }

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

        $('.opt_num').change(function () {
            var num = $(this).val();
            var oid = $(this).attr('oid');
            var cid = $(this).attr('class_id');
            var $cost = $('.opt_cost[oid="' + oid + '"][class_id="' + cid + '"]'),
                $charge = $('.opt_charge[oid="' + oid + '"][class_id="' + cid + '"]').val(),
                $option_costs = $('.option_costs[class_id="' + cid + '"]'),
                $option_prices = $('.option_prices[class_id="' + cid + '"]'),
                $option_numbers = $('.option_numbers[class_id="' + cid + '"]');
            var option_ids = $('.option_ids[class_id="' + cid + '"]').val();
            if (option_ids === '') return;
            option_ids = option_ids.split(',');
            var $ocosts = $option_costs.val();
            if ($ocosts === '') return;
            $ocosts = $ocosts.split(',');
            var option_numbers = $option_numbers.val();
            if (option_numbers === '') return;
            option_numbers = option_numbers.split(',');
            var option_prices = $option_prices.val();
            if (option_prices === '') return;
            option_prices = option_prices.split(',');

            var ind = option_ids.indexOf(oid);

            if (ind === -1) return;

            var oprice = 0;
            if ($charge == 'one') {
                oprice = $ocosts[ind] * num;
            } else {
                oprice = $ocosts[ind] * num * rent_dates;
            }
            $cost.text(oprice);
            option_numbers[ind] = num;
            option_prices[ind] = oprice;
            $option_numbers.val(option_numbers.join(','));
            $option_prices.val(option_prices.join(','));

            // change total price
            var price_insurance = $('.insurance[class_id="' + cid + '"]').val() * 1 * rent_dates;
            $('.insurance-price[class_id="' + cid + '"]').text(price_insurance.toLocaleString('en'));

            var price_rent = $('.price_rent[class_id="' + cid + '"]').val();
            var option_costs = 0;
            for (var i = 0; i < $ocosts.length; i++) {
                option_costs += option_prices[i] * 1;
                // option_costs += $ocosts[i] * option_numbers[i];
            }
            var total = price_rent * 1 + option_costs + price_insurance;

            $('.price_all[class_id="' + cid + '"]').val(total);
            $('.total_price[class_id="' + cid + '"]').text(total.toLocaleString('en'));
        });

        function class_search(class_id) {
            var smoke = $('.car_smoking[class_id="' + class_id + '"]').val();
            // var passenger = $('#passenger').val();
            // if(passenger == 'all') {
            var passenger = $('.car_passenger[class_id="' + class_id + '"]').val();
            // }
            var data = {
                _token: $('input[name="_token"]').val(),
                class_id: class_id,
                smoke: smoke,
                depart_date: $('#depart-date').val(),
                depart_time: $('#depart-time').val(),
                return_date: $('#return-date').val(),
                return_time: $('#return-time').val(),
                depart_shop: $('#depart-shop').val(),
                return_shop: $('#return-shop').val(),
                category: $('#car-category').val(),
                passenger: passenger,
                // insurance   : $('.insurance[class_id="' + cid + '"]').val(),
                // option_list : $('#option-list').val()
            };
            $.ajax({
                url: '{{URL::to('/')}}/class-search',
                type: 'post',
                data: data,
                success: function (result, status, xhr) {
                    if (status != 'success') return;
                    if (isJson(result)) {
                        var data = JSON.parse(result);
                        if (data.error != '' || data.success != 'true') return;
                        var cls = data.class;
                        console.log(cls);
                        var cid = cls.id;
                        // use new values
                        $('.car_count[class_id="' + cid + '"]').text(cls.car_count);
                        $('.left[class_id=' + cid + ']').removeClass('active');

                        var leftclass = '';
                        if (cls.car_count >= 10) leftclass = 'many';
                        if (cls.car_count <= 9 && cls.car_count >= 4) leftclass = 'few';
                        if (cls.car_count <= 3) leftclass = 'afew';
                        $('.left.' + leftclass + '[class_id=' + cid + ']').addClass('active');

                        if (cls.car_count === 0) {
                            $('.btn_book' + cid).removeAttr('disabled').attr('disabled', 'disabled');

                        } else {
                            $('.btn_book' + cid).removeAttr('disabled');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    alert(error);
                }
            });

        }

        $('.car_smoking').change(function () {
            var cid = $(this).attr('class_id');
            class_search(cid);
        });

        $('.car_passenger').change(function () {
            var cid = $(this).attr('class_id');
            class_search(cid);
        });

        //chosen select
        $(".chosen-select").chosen({
            max_selected_options: 5,
            no_results_text: "Oops, nothing found!"
        });

        $('#depart-datepicker, #return-datepicker').datepicker({
            {{--            @if(config('app.locale') == 'ja')--}}
            language: "ja",
            {{--@endif--}}
            format: 'yyyy/mm/d',
            startDate: '{{ date('Y/n/j') }}',
            endDate: '{{ date('Y/m/d',strtotime(date("Y-m-d", time()) . " + 1 year")) }}',
            orientation: "bottom",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });
        var departPicker = $('#depart-datepicker');
        var returnPicker = $('#return-datepicker');

        // time selector initialize
        var dTimepicker = $('#depart-time'),
            rTimepicker = $('#return-time');

        function getAfterHours(hr) {
            // get time string after <hr> hours
            var crt = new Date(),
                crthour = crt.getHours(),
                crtmin = crt.getMinutes(),
                hrAfter = crthour * 1 + hr;
            if(hrAfter < 10) hrAfter = '0' + hrAfter;
            return hrAfter + ':' + crtmin;
        }

        function selectFirstOrLastTime(picker, cond) {
            // cond is 'first' or 'last'
            picker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
            picker.val(picker.find('option:'+ cond).val());
            picker.trigger('chosen:updated');
        }

        // initialize time pickers
        var all_hours_disable = updateTimepicker(dTimepicker, departPicker.datepicker('getDate'), today, getAfterHours(3));
        if (all_hours_disable) {
            departPicker.datepicker('setStartDate', tomorrow).datepicker('setDate', tomorrow);
            selectFirstOrLastTime(dTimepicker, 'first');
        }
        all_hours_disable = updateTimepicker(rTimepicker, returnPicker.datepicker('getDate'), today, getAfterHours(4));
        if(all_hours_disable) {
            returnPicker.datepicker('setStartDate', tomorrow).datepicker('setDate', tomorrow);
            selectFirstOrLastTime(rTimepicker, 'last');
        } else {
            if(compareDateWithToday(returnPicker.datepicker('getDate')) > 0)
                if(rTimepicker.val() == '09:00')
                    selectFirstOrLastTime(rTimepicker, 'last');
        }

        function compareDateWithToday(date) {
            var q = new Date(),
                today = new Date(q.getFullYear(),q.getMonth(),q.getDate(),0,0,0,0);
            if(date.getTime() > today.getTime())
                return 1;
            else if(date.getTime() == today.getTime())
                return 0;
            else
                return -1;
        }

        function updateTimepicker(picker, date, refdate, reftime) {
            var dYear = date.getFullYear(), dMonth = date.getMonth(), dDate = date.getDate();
            var refhm = reftime.split(':');

            var cTime = (new Date(refdate.getFullYear(), refdate.getMonth(), refdate.getDate(), refhm[0], refhm[1],0,0)).getTime();// + 10800 * 1000;
            // if(isdepart === true) cTime += 10800 * 1000;
            var hours = picker.find('option'), cnt = hours.length;
            var first = -1;
            var oldval = picker.val();

            for (var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]);
                var hr_min = hOption.val().split(':');
                var dTime = (new Date(dYear, dMonth, dDate, hr_min[0], hr_min[1], 0, 0)).getTime();
                if (dTime < cTime) {
                    hOption.prop('disabled', true);
                    hOption.css('display', 'none');
                }
                else {
                    hOption.prop('disabled', false);
                    hOption.css('display', 'block');
                    if (first < 0) first = k;
                }
            }
            if (picker.val() < oldval) picker.val(oldval);
            // if (compareDateWithToday(date) === 0 && first >= 0) picker.val($(hours[first]).val());
            picker.trigger("chosen:updated");
            return first < 0;
        }

        departPicker.datepicker().on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
            if(compareDateWithToday(minDate) == 0){ // if today
                var disable1 = updateTimepicker(dTimepicker, minDate, today, getAfterHours(3));
                var disable2 = updateTimepicker(rTimepicker, minDate, today, getAfterHours(4));
                if(disable1 == true ) {
                    dTimepicker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
                    if(disable2 == true)
                        rTimepicker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');

                    minDate.setDate(minDate.getDate() + 1);
                    departPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(dTimepicker, 'first');
                    returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(rTimepicker, 'last');
                }
                if(disable1 == false && disable2 == true) {
                    minDate.setDate(minDate.getDate() + 1);
                    returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(rTimepicker,'last');
                }
            } else if(compareDateWithToday(minDate) > 0) {
                selectFirstOrLastTime(dTimepicker,'first');
                selectFirstOrLastTime(rTimepicker,'last');
            }
        });

        returnPicker.datepicker().on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            var departDate = departPicker.datepicker('getDate');
            var departTime = dTimepicker.val().split(':');
            departTime[0] = departTime[0] * 1 + 1;
            if(departTime[0] < 10) departTime[0] = '0' + departTime[0];
            var all_hours_disable = updateTimepicker(rTimepicker, maxDate, departDate, departTime[0]+':'+departTime[1]);
            if (all_hours_disable) {
                maxDate.setDate(maxDate.getDate() + 1);
                returnPicker.datepicker('setStartDate', maxDate).datepicker('setDate', maxDate);
                selectFirstOrLastTime(rTimepicker,'last');
            }
        });

        dTimepicker.change(function () {
            var dDate = departPicker.datepicker('getDate'),
                dy = dDate.getFullYear(), dm = dDate.getMonth(), dd = dDate.getDate();
            var rDate = returnPicker.datepicker('getDate'),
                ry = rDate.getFullYear(), rm = rDate.getMonth(), rd = rDate.getDate();
            var hr_min = $(this).val().split(':');
            var dTime = (new Date(dy, dm, dd, hr_min[0], hr_min[1], 0, 0)).getTime() + 3600 * 1000;

            var hours = rTimepicker.find('option').removeAttr('disabled').css('display','block'),
                cnt = hours.length, oldval = rTimepicker.val();
            var index = -1;
            for (var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]), hm = hOption.val().split(':');
                var rTime = (new Date(ry, rm, rd, hm[0], hm[1], 0, 0)).getTime();
                if (rTime <= dTime)
                    hOption.attr('disabled', 'disabled').css('display', 'none');
                else {
                    // hOption.removeAttr('disabled').css('display','block');
                    if (index < 0 && hOption.val() == oldval) {
                        hOption.attr('selected', true);
                        index = k;
                    }
                }
            }
            if (index < 0) {
                dDate.setDate(dDate.getDate() + 1);
                returnPicker.datepicker('setStartDate', dDate).datepicker('setDate', dDate);
                selectFirstOrLastTime(rTimepicker,'last');
            }
            rTimepicker.trigger("chosen:updated");
        });

        //inital value for depart shop
        var shop_name = $('select[name="depart_shop"] option:selected').text();
        $('.shop_name').html(shop_name);
        $('select[name="depart_shop"]').change(function () {
            var shop_name = $('select[name="depart_shop"] option:selected').text();
            $('.shop_name').html(shop_name);
            $('#return-shop').val($('#depart-shop').val());
        });
        //select category
        var search_cate_id = '0';
        @if($search->car_category !="" )
            search_cate_id = '{{$search->car_category}}';
        @else
            search_cate_id = '{{$categorys[0]->id}}';
        @endif
        // $('#search_cate_'+search_cate_id).removeClass('search_cartype').addClass('search_btn');
        $('input[name="car_category"]').val(search_cate_id);

        function selectCategory(cate_id, cate_name) {
            $('.search_cate').removeClass('search_btn').addClass('search_cartype');
            $('#search_cate_' + cate_id).removeClass('search_cartype').addClass('search_btn');
            $('input[name="car_category"]').val(cate_id);
            $('input[name="option_list"]').val("");
            getOptions();
        }

        //inital passenger when laoding
        var passenger = $('input[name="passenger"]').val();
        @if($search->passenger == "")
            passenger = 'all';
        @endif
        selectPassenger(passenger);

        function selectPassenger(val) {
            $('.search_passenger').removeClass('search_btn').addClass('search_cartype');
            $('#search_passenger_' + val).removeClass('search_cartype').addClass('search_btn');
            $('input[name="passenger"]').val(val);
        }

        //initail insurance when loading
        {{--var insurance = $('input[name="insurance"]').val();--}}
        {{--@if($search->insurance == "")--}}
        {{--insurance = 'no';--}}
        {{--@endif--}}
        {{--selectInsurance(insurance);--}}
        {{--function selectInsurance(val){--}}
        {{--$('.search_ins').removeClass('search_btn').addClass('search_cartype');--}}
        {{--$('#search_ins_'+val).removeClass('search_cartype').addClass('search_btn');--}}
        {{--$('input[name="insurance"]').val(val);--}}
        {{--}--}}
        //initail smoke when loading
        var smoke = $('input[name="smoke"]').val();
        @if($search->smoke == "")
            smoke = 'both';
        @endif
        selectSmoke(smoke);

        function selectSmoke(val) {
            $('.search_smoke').removeClass('search_btn').addClass('search_cartype');
            $('#search_smoke_' + val).removeClass('search_cartype').addClass('search_btn');
            $('input[name="smoke"]').val(val);
        }

        //initial options
        // getOptions();
        var option_list = $('input[name="option_list"]').val();
        var option_content = option_list.split(',');

        $(':checkbox').each(function (i) {
            var val = $(this).val();
            if (jQuery.inArray(val, option_content) != -1) {
                $(this).attr('checked', 'true');
            }
            //val[i] = $(this).val();
        });

        //event to get options list when click category
        function getOptions() {
            var category_id = $('input[name="car_category"]').val();
            var url = '{{URL::to('/')}}/search/getshopoption';
            var token = $('input[name="_token"]').val();
            var shop_id = $('#depart-shop').val();
            var data = [];
            data.push({name: '_token', value: token},
                {name: 'shop_id', value: shop_id},
                {name: 'category_id', value: category_id});
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                // async: false,
                dataType: "text",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    var option_html = '<ul>';
                    var search_options = $('#option-list').val().split(',');
                    for (var k = 0; k < search_options.length; k++) {
                        search_options[k] = search_options[k] * 1;
                    }
                    var free_options = $('#free_list').val();
                    if (free_options != '') {
                        free_options = free_options.split(',')
                    }
                    for (k = 0; k < free_options.length; k++) {
                        free_options[k] = free_options[k] * 1;
                    }
                    content = jQuery.parseJSON(content);
                    var paids = content.paid_options;
                    var frees = content.free_options;
                    for (k = 0; k < paids.length; k++) {
                        var v = paids[k];
                        var checked = (search_options.indexOf(v.id) > -1) ? 'checked' : '';
                        var active = (checked == 'checked') ? 'active' : '';
                        option_html += '<li class="search_option ' + active + ' ">';
                        var clickevent = "";

                        option_html += ' ';
                        option_html += '<input type="checkbox" id="option_check_' + k + '" name="options[]" value="' + v.id + '" ' + checked + ' >';
                        @if($util->lang() == 'ja')
                            option_html += '<label id="option_label_' + k + '" for="option_check_' + k + '"><span>' + v.name + '</span></label></li>';
                        @endif
                        @if($util->lang() == 'en')
                            option_html += '<label id="option_label_' + k + '" for="option_check_' + k + '"><span>' + v.name_en + '</span></label></li>';
                        @endif
                    }
                    option_html += '</ul>';
                    $('.option_name').html(option_html);

                    option_html = '';
                    for (k = 0; k < frees.length; k++) {
                        var v = frees[k];
                        var checked = (free_options.indexOf(v.id) > -1) ? 'checked' : '';
                        var active = (checked == 'checked') ? 'active' : '';
                        var ispickup = '', free_opt_name = 'free_options[]';
                        if (v.google_column_number == 101 || v.google_column_number == 102) {
                            ispickup = 'pickup';
                            free_opt_name = 'pickup';
                        }

                        option_html += '<li class="' + active + '">' +
                            '<input id="free' + k + '" class="' + ispickup + '" type="checkbox" name="' + free_opt_name + '" value="' + v.id + '" ' + checked + ' >' +
                            @if($util->lang() == 'ja')
                            '<label for="free' + k + '"><span>' + v.name + '</span></label>' +
                            @endif
                            @if($util->lang() == 'en')
                                '<label for="free' + k + '"><span>' + v.name_en + '</span></label>' +
                            @endif
                            '</li>';
                    }
                    $('#free_options').html(option_html);
                }
            });
        }

        $('#free_options').on('change', '.pickup', function () {
            var checked = $(this).prop('checked');
            $('.pickup').prop('checked', false)
                .closest('li').removeClass('active');
            $(this).prop('checked', checked).closest('li').addClass('active');
            $('#free_list').val($('.pickup:checked').val());
        });

        //return shop event
        $('#return-shop').change(function () {
            getOptions();
            $('.pickup_object').show();
        });

        $('#depart-shop').change(function () {
            getOptions();
            if ($(this).val() == '5') {
                $('.pickup').prop('checked', false);
                $('.pickup_object').hide();
            } else {
                $('.pickup_object').show();
            }
            if ($(this).val() == '0') {
                $('#option_wrapper').removeClass('hidden').addClass('hidden');
            } else {
                $('#option_wrapper').removeClass('hidden');
            }
        });

        //disable free option when select smart driveout paid option
        function disableFreeOption(e) {
            var target = $(e.currentTarget);
            var checked = target.prop('checked');
            if (checked == true) {
                $('#pickup').prop('checked', false);
                $('.pickup_object').hide();
            } else {
                $('.pickup_object').show();
            }
        }

        //search view
        var view_flag = '1';

        function searchView() {
            if (view_flag == 0) {
                $('#view_search').removeClass('glyphicon-circle-arrow-down').addClass('glyphicon-circle-arrow-up');
                view_flag = 1;
                $("#searchform").fadeIn("slow");
            }
            else {
                $('#view_search').removeClass('glyphicon-circle-arrow-up').addClass('glyphicon-circle-arrow-down');
                view_flag = 0;
                $("#searchform").fadeOut("slow");
            }
            //event.stopPropagation();
        }

        // submit search
        function submitSearch() {
            if ($('#depart-shop').val() === '0') {
                showModal('@lang('search.nodepartshop')');
                return false;
            }
            if ($('#return-shop').val() === '0') {
                showModal('返却地を選択してください。');
                return false;
            }
            if ($('#depart-time').val() === null) {
                showModal('出発時間を選択してください。');
                return false;
            }
            if ($('#return-time').val() === null) {
                showModal('@lang('search.nodropoffdatetime')');
                return false;
            }
            return true;
        }

        // insurance change
        $('.insurance').change(function () {
        });

        // submit selection
        function submit_booking(class_id, class_name) {
            var depart_time = $('#depart-time').val();
            if (depart_time === null) {
                showModal('出発時間を選択してください。');
                return false;
            }
            var return_time = $('#return-time').val();
            if (return_time === null) {
                showModal('返却時間を選択してください。');
                return false;
            }

            $('#data_depart_date').val($('#depart-date').val());
            $('#data_depart_time').val(depart_time);
            $('#data_return_date').val($('#return-date').val());
            $('#data_return_time').val(return_time);
            var depart = $('#depart-shop').val(),
                selector = '#depart-shop option[value="' + depart + '"]';
            if (depart == 0) {
                showModal('@lang('search.nodepartshop')');
                return;
            }
            $('#data_depart_shop').val(depart);
            $('#data_depart_shop_name').val($(selector).html().trim());
            var arrive = $('#return-shop').val();
            selector = '#return-shop option[value="' + arrive + '"]';
            if (arrive == 0) {
                showModal('返却地を選択してください。');
                return;
            }
            // var search_passenger = $('#passenger').val();
            var search_passenger = $('.car_passenger[class_id="' + class_id + '"]').val();
            // if(search_passenger == 'all') {
            //     search_passenger = class_passenger;
            // } else {
            //     if(class_passenger != 'all')
            //         search_passenger = class_passenger;
            // }

            var rent_price = $('.price_rent[class_id="' + class_id + '"]').val(),
                all_price = $('.price_all[class_id="' + class_id + '"]').val();
            @if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false )
            dataLayer.push({
                'event': 'addToCart',
                'ecommerce': {
                    'currencyCode': 'JPY',
                    'add': {                       // 'add' actionFieldObject measures.
                        'actionField': {'list': 'Search Car'},
                        'products': [{              //  adding a product to a shopping cart.
                            'name': class_name,
                            'id': class_id,
                            'price': all_price,
                            // 'brand': 'エスティマ',
                            'quantity': 1
                        }]
                    }
                }
            });
            @endif
            $('#data_return_shop').val(arrive);
            $('#data_return_shop_name').val($(selector).html().trim());
            $('#data_car_category').val($('#car-category').val());
            $('#data_passenger').val(search_passenger);
            $('#data_insurance').val($('#insurance').val());
            $('#data_class_id').val(class_id);
            $('#data_smoke').val($('.car_smoking[class_id="' + class_id + '"]').val());
            $('#data_class_name').val($('.car_title[class_id="' + class_id + '"]').html().trim());
            $('#data_insurance_price1').val($('.insurance_price1[class_id="' + class_id + '"]').val());
            $('#data_insurance_price2').val($('.insurance_price2[class_id="' + class_id + '"]').val());
            $('#data_class_category').val($('.car_category[class_id="' + class_id + '"]').html().trim());
            $('#data_car_photo').val($('.car_photo[class_id="' + class_id + '"]').val());
            $('#data_rent_days').val($('.rent_days[class_id="' + class_id + '"]').val());
            $('#data_price_rent').val(rent_price);
            $('#data_option_ids').val($('.option_ids[class_id="' + class_id + '"]').val());
            $('#data_option_names').val($('.option_names[class_id="' + class_id + '"]').val());
            $('#data_option_numbers').val($('.option_numbers[class_id="' + class_id + '"]').val());
            $('#data_option_costs').val($('.option_costs[class_id="' + class_id + '"]').val());
            $('#data_option_prices').val($('.option_prices[class_id="' + class_id + '"]').val());
            $('#data_price_all').val(all_price);
            $('#data_pickup').val($('.car_pickup[class_id="' + class_id + '"]').val());

            $('#booking-submit').submit();
        }

    </script>
@endsection
