@extends('layouts.adminapp_calendar')

@section('template_title')
    実際売上管理表
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
    <style type="text/css" media="screen">
        .users-table { border: 0; }
        .users-table tr td:first-child { padding-left: 15px; }
        .users-table tr td:last-child { padding-right: 15px; }
        .table-bordered td { font-size: 15px }
        .users-table.table-responsive,
        .users-table.table-responsive table { margin-bottom: 0; }

        /**/
        .all_tabel_wrap{
            float: left;
        }
        .book_all_wrap .dataTables_paginate{
            float: right;
        }
        .book_all_wrap .dataTables_paginate a.paginate_button {
            background-color: #FFFFFF;
            border: 1px solid #DDDDDD;
            color: inherit;
            float: left;
            line-height: 1.42857;
            margin-left: -1px;
            padding: 4px 10px;
            position: relative;
            text-decoration: none;
        }
        .book_all_wrap .dataTables_paginate a.paginate_button:hover{
            z-index: 3;
            color: #23527c;
            background-color: #eee;
            border-color: #ddd;
        }
        .book_all_wrap .dataTables_paginate span.ellipsis{
            display: none;
        }
        .mrg_l_15{
            margin-left: 15px;
        }
        .book_all_wrap table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child:before,
        .book_all_wrap table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child:before{
            background-color: gray;
        }
        .book_all_wrap table.dataTable.dtr-inline.collapsed>tbody>tr>td.child ul li{
            float: left;
        }
        @media screen and (max-width: 1024px){
            .all_tabel_wrap{
                float: none;
                width: 100%;
                display: inline-block;
                margin-bottom: 15px;
            }
            .all_tabel_wrap .all_width{
                width: 33%;
                margin: 0 0 15px 0;
            }
            .all_tabel_wrap .all_width2{
                width: 50%;
                margin: 0 0 15px 0;
            }
            .all_tabel_wrap .all_width2 a:first-child {
                margin-left: 0;
            }
            .all_tabel_wrap .title_width{
                display: none;
            }
            .all_tabel_wrap .input_all{
                width: 100%;
                max-width: 100% !important;
            }
            .save_btn {
                width: 100%;
                display: inline-block;
                right: 219px !important;
                margin-top: 0 !important;
                top: 49px;
            }
        }

        @media screen and (max-width: 768px){
            .save_btn{
                right: 51px !important;
            }
            .all_tabel_wrap {
                padding: 0 15px;
            }
            .all_tabel_wrap .all_width {
                width: 32%;
            }
        }
        /**/

    </style>
@endsection
@inject('service_booking', 'App\Http\Controllers\BookingManagementController')
@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    function displayEmpty($val) {
        if($val == '0') return '';
        else return number_format($val);
    }
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-2">
                        <h2>売上管理表</h2>
                    </div>
                    <div class="col-md-10 m-t-sm">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="all_tabel_wrap">
                                    <label class="m-l-sm title_width">店舗</label>
                                    <label class="m-l-sm all_width">
                                        <select name="shop_id" id="shop_id" class="form-control" onchange="changedateshop()">
                                            <option value="0">全体</option>
                                            @foreach($shops as $shop)
                                                <option value="{{$shop->id}}" @if($shop_id == $shop->id) selected @endif>{{$shop->name}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="all_width">
                                        <select name="cond" id="cond" class="form-control" onchange="changedate(event)" >
                                                <option value="one" @if($cond == 'one') selected @endif >1日</option>
                                                <option value="day" @if($cond == 'day') selected @endif>日毎</option>
                                                <option value="month" @if($cond == 'month') selected @endif>月毎</option>
                                                <option value="year" @if($cond == 'year') selected @endif>年毎</option>
                                        </select>
                                    </label>
                                    <label class="m-l-sm title_width">対象期間</label>
                                    <label class="m-l-sm title_width">
                                        <div class="input-group date"  id="datepicker" style="position: absolute; width: 200px;margin-top: -1.2em">
                                            <input type="text" name="date" id="date" class="form-control input-sm datetimepicker" value="{{$date}}" readonly required>
                                            <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <form method="POST" name="searchform" id="searchform" action="{{URL::to('/')}}/sales/salesmanagement" accept-charset="UTF-8" role="form" class="form-horizontal" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <input type="hidden" name="s_shop_id" id="s_shop_id" />
                            <input type="hidden" name="s_cond" id="s_cond" />
                            <input type="hidden" name="s_date" id="s_date" />
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="panel panel-default shadow-box book_all_wrap">
                <div class="panel-body">
                    @if($cond == 'one')
                        <!--(Sum)first section start-->
                        <div class="row">
                            <h2>総売上</h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    <th style=width:150px;text-align:center>総売上</th>
                                    <th style=width:150px;text-align:center>現金</th>{{--sum sales--}}
                                    <th style=width:150px;text-align:center>クレジットカード</th>{{--credit card--}}
                                    <th style=width:150px;text-align:center>システム(QS)</th>{{--system(QS)--}}
                                    <th style=width:150px;text-align:center>ポータルサイト</th>{{--portal--}}
                                    <th style=width:150px;text-align:center>ポイント</th>{{--point--}}
                                    {{--<th>Cancellation</th>--}}{{--cancellation--}}
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="font-weight:bold;font-size:20px">{{displayEmpty($sum->sumtotal)}}</td>
                                    <td>{{displayEmpty($sum->cashsum)}}</td>{{--sum sales--}}
                                    <td>{{displayEmpty($sum->creditsum)}}</td>{{--credit card--}}
                                    <td>{{displayEmpty($sum->websum)}}</td>{{--system(QS)--}}
                                    <td>{{displayEmpty($sum->portalsum)}}</td>{{--portal--}}
                                    <td>{{displayEmpty($sum->adjustmentsum)}}</td>{{--point--}}
                                    {{--<td>{{$sum->cancelsum}}</td>--}}{{--cancellation--}}
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--first section end-->
                        <!--(reservatin -portal list)second section start-->
                        <div class="row">
                            <h2>ポータルサイトからの売上</h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    @foreach($portal_list as $po)
                                       @if($po->flag == true)
                                            @if($po->id != '10000' && $po->id != '10001' )
                                            <th style="text-align:center;width:150px;background-color:{{$po->bgcolor}}">{{$po->name}}</th>
                                            @endif
                                       @endif
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach($portal_list as $po)
                                        @if($po->flag == true)
                                            @if($po->id != '10000' && $po->id != '10001' )
                                            <td>{{displayEmpty($po->price)}}</td>
                                            @endif
                                        @endif
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--second section end-->
                        <!--(departure list)third section start-->
                        <div class="row">
                            <h2>配車からの売上</h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    <th style="width:100px;text-align:center">ID</th> <!--id-->
                                    <th style="width:150px;text-align:center">氏名</th> <!--name-->
                                    <th style="width:150px;text-align:center">支払金額</th> <!--payment-->
                                    <th style="width:100px;text-align:center">基本料金</th> <!--basic price-->
                                    <th style="width:100px;text-align:center">免責補償</th> <!--insurance1-->
                                    <th style="width:100px;text-align:center">ワイド免責</th> <!--insurance2-->
                                    <th style="width:100px;text-align:center">ETC</th> <!--etc-->
                                    <th style="width:100px;text-align:center">ベビ</th> <!--baby seat-->
                                    <th style="width:100px;text-align:center">チャイ</th> <!--child seat-->
                                    <th style="width:100px;text-align:center">ジュ</th> <!-- junior seat-->
                                    <th style="width:100px;text-align:center">スノータイヤ</th> <!--snow type-->
                                    <th style="width:100px;text-align:center">スマ乗返</th> <!--smart drive out smart return-->
                                    <!--<th style="width:100px;text-align:center">ホテル乗返</th>--> <!--hotel drive out hotel return-->
                                    <th style="width:100px;text-align:center">調整金額</th> <!--adjust price-->
                                    <th style="width:100px;text-align:center">延泊料金</th> <!--e3xtend night-->
                                    <th style="width:100px;text-align:center">ポイント</th> <!--point-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $count = 0 ;
                                    $total = 0;
                                    $total_payment = 0;
                                    $total_basic_price = 0;
                                    $total_insurance1= 0;
                                    $total_insurance2 = 0;
                                    $total_etc = 0 ;
                                    $total_baby_seat = 0;
                                    $total_child_seat = 0;
                                    $total_junior_seat = 0;
                                    $total_snow_type = 0;
                                    $total_smart_return = 0;
                                    $total_hotel_return = 0;
                                    $total_adjustment_price = 0;
                                    $total_extend_price = 0;
                                    $total_point = 0;
                                ?>
                                @foreach($depart_list as $de)
                                <tr>

                                    <td><a href="{{URL::to('/')}}/booking/detail/{{ $de->book_id }}" title="予約詳細を見る">
                                            <span> {{$de->name}} </span>
                                        </a>
                                    </td> <!--id-->
                                    <td>{{$de->user_name}}</td> <!--name-->
                                    <td>
                                        <span class="new_row" >
                                            <label>{{displayEmpty($de->payment)}}</label>
                                            円
                                        </span>
                                        <span>({{$de->pay_method}})</span>
                                    </td> <!--portal payment-->
                                    <td>{{displayEmpty($de->basic_price)}}</td> <!--basic price-->
                                    <td>{{displayEmpty($de->insurance1)}}</td> <!--insurance1-->
                                    <td>{{displayEmpty($de->insurance2)}}</td> <!--insurance2-->
                                    <td>{{displayEmpty($de->etc)}}</td><!--etc-->
                                    <td>{{displayEmpty($de->baby_seat)}}</td><!--bayby seat-->
                                    <td>{{displayEmpty($de->child_seat)}}</td><!--child seat-->
                                    <td>{{displayEmpty($de->junior_seat)}}</td><!--junior seat-->
                                    <td>{{displayEmpty($de->snow_type)}}</td> <!--snow type-->
                                    <td>{{displayEmpty($de->smart_return)}}</td> <!--drive smart out-->
                                    <!--<td>{{displayEmpty($de->hotel_return)}}</td>--> <!--hote lreturn-->
                                    <td>{{displayEmpty($de->adjustment_price)}}</td> <!--adjust price-->
                                    <td>{{displayEmpty($de->extend_price)}}</td> <!--extend night-->
                                    <td>{{displayEmpty($de->point)}}</td> <!--point-->
                                </tr>
                                <?php
                                    $count++;
                                    $total_payment      += $de->payment;
                                    $total_basic_price  += $de->basic_price ;
                                    $total_insurance1   += $de->insurance1;
                                    $total_insurance2   += $de->insurance2;;
                                    $total_etc          += $de->etc ;
                                    $total_baby_seat    += $de->baby_seat;
                                    $total_child_seat   += $de->child_seat;
                                    $total_junior_seat  += $de->junior_seat;
                                    $total_snow_type    += $de->snow_type;
                                    $total_smart_return += $de->smart_return;
                                    $total_hotel_return += $de->hotel_return;
                                    $total_adjustment_price += $de->adjustment_price;
                                    $total_extend_price += $de->extend_price;
                                    $total_point       += $de->point;
                                ?>
                                @endforeach
                                <tr>
                                    <td colspan="2">小計</td> <!--id-->
                                    <td>
                                        <span class="new_row" >
                                            <label>{{displayEmpty($total_payment)}}</label>
                                            円
                                        </span>
                                    </td> <!--portal payment-->
                                    <td>{{displayEmpty($total_basic_price)}}</td> <!--basic price-->
                                    <td>{{displayEmpty($total_insurance1)}}</td> <!--insurance1-->
                                    <td>{{displayEmpty($total_insurance2)}}</td> <!--insurance2-->
                                    <td>{{displayEmpty($total_etc)}}</td><!--etc-->
                                    <td>{{displayEmpty($total_baby_seat)}}</td><!--bayby seat-->
                                    <td>{{displayEmpty($total_child_seat)}}</td><!--child seat-->
                                    <td>{{displayEmpty($total_junior_seat)}}</td><!--junior seat-->
                                    <td>{{displayEmpty($total_snow_type)}}</td> <!--snow type-->
                                    <td>{{displayEmpty($total_smart_return)}}</td> <!--drive smart out-->
                                    <!--<td>{{displayEmpty($total_hotel_return)}}</td>--> <!--hote lreturn-->
                                    <td>{{displayEmpty($total_adjustment_price)}}</td> <!--adjust price-->
                                    <td>{{displayEmpty($total_extend_price)}}</td> <!--extend night-->
                                    <td>{{displayEmpty($total_point)}}</td> <!--point-->
                                </tr>
                                <tr>
                                    <td colspan="2">合計</td>
                                    <td>
                                        <span class="new_row" >
                                            <label>{{displayEmpty($total_payment)}}</label>
                                            円
                                        </span>
                                    </td> <!--portal payment-->
                                    <td>{{displayEmpty($total_basic_price)}}</td> <!--basic price-->
                                    <td colspan="2"  style="text-align: center" >{{displayEmpty($total_insurance1 + $total_insurance2)}}</td> <!--insurance-->
                                    <td colspan="6" style="text-align: center" >{{displayEmpty($total_etc + $total_baby_seat + $total_child_seat + $total_junior_seat + $total_snow_type + $total_smart_return + $total_hotel_return) }}</td><!--etc-->
                                    <td>{{displayEmpty($total_adjustment_price)}}</td> <!--adjust price-->
                                    <td>{{displayEmpty($total_extend_price)}}</td> <!--extend night-->
                                    <td>{{displayEmpty($total_point)}}</td> <!--point-->
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--third section end-->
                        <!--(return list)fours section start-->
                        <div class="row">
                            <h2>返車からの売上</h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    <th style="width:100px;text-align:center">ID</th> <!--id-->
                                    <th style="width:150px;text-align:center">氏名</th> <!--name-->
                                    <th style="width:150px;text-align:center">支払</th> <!--payment-->
                                    <th style="width:100px;text-align:center">ETC利用料</th> <!--etc price-->
                                    <th style="width:100px;text-align:center">スマ返</th> <!--samrt return-->
                                    <!--<th style="width:100px;text-align:center">ホテル返</th>--> <!--htoel return-->
                                    <th style="width:100px;text-align:center">調整金額</th> <!--adjust ment price-->
                                    <th style="width:100px;text-align:center">延泊料金</th> <!--extend night-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $count = 0 ;
                                    $total = 0;
                                    $total_payment = 0;
                                    $total_etc_card = 0;
                                    $total_smart_return = 0;
                                    $total_hotel_return = 0;
                                    $total_adjustment_price = 0;
                                    $total_extend_price = 0;
                                ?>
                                @foreach($return_list as $de)
                                    <tr>
                                        <td><a href="{{URL::to('/')}}/booking/detail/{{ $de->book_id }}" title="予約詳細を見る">
                                                <span> {{$de->name}} </span>
                                            </a>
                                        </td> <!--id-->
                                        <td>{{$de->user_name}}</td> <!--name-->
                                        <td>
                                           <span class="new_row" >
                                            <label>{{displayEmpty($de->payment)}}</label>
                                            円
                                            </span>
                                            <span>({{$de->pay_method}})</span>
                                        </td> <!--portal payment-->
                                        <td>{{displayEmpty($de->etc_card)}}</td> <!--etc price-->
                                        <td>{{displayEmpty($de->smart_return)}}</td> <!--smart return-->
                                        <!--<td>{{displayEmpty($de->hotel_return)}}</td>--> <!--hotel return-->
                                        <td>{{displayEmpty($de->adjustment_price)}}</td><!--adjustment price-->
                                        <td>{{displayEmpty($de->extend_price)}}</td><!--extend night-->
                                    </tr>
                                    <?php
                                    $count++;
                                    $total_payment      += $de->payment;
                                    $total_etc_card     += $de->etc_card ;
                                    $total_smart_return += $de->smart_return;
                                    $total_hotel_return += $de->hotel_return;
                                    $total_adjustment_price += $de->adjustment_price;
                                    $total_extend_price += $de->extend_price;
                                    ?>
                                 @endforeach
                                    <tr>
                                        <td colspan="2">小計</td> <!--id-->
                                        <td>{{displayEmpty($total_payment)}}</td> <!--portal payment-->
                                        <td>{{displayEmpty($total_etc_card)}}</td> <!--etc price-->
                                        <td>{{displayEmpty($total_smart_return)}}</td> <!--smart return-->
                                        <!--<td>{{displayEmpty($total_hotel_return)}}</td>--> <!--hotel return-->
                                        <td>{{displayEmpty($total_adjustment_price)}}</td><!--adjustment price-->
                                        <td>{{displayEmpty($total_extend_price)}}</td><!--extend night-->
                                    </tr>
                                    <tr>
                                        <td colspan="2">合計</td>
                                        <td colspan="5">{{displayEmpty($total_payment)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--four section end-->
                        <!--(qs list)third section start-->
                        <div class="row">
                            <h2>システムQSからの売上</h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    <th style="width:100px;text-align:center">ID</th> <!--id-->
                                    <th style="width:150px;text-align:center">氏名</th> <!--name-->
                                    <th style="width:150px;text-align:center">支払金額</th> <!--payment-->
                                    <th style="width:100px;text-align:center">基本料金</th> <!--basic price-->
                                    <th style="width:100px;text-align:center">免責補償</th> <!--insurance1-->
                                    <th style="width:100px;text-align:center">ワイド免責</th> <!--insurance2-->
                                    <th style="width:100px;text-align:center">ETC</th> <!--etc-->
                                    <th style="width:100px;text-align:center">ベビ</th> <!--baby seat-->
                                    <th style="width:100px;text-align:center">チャイ</th> <!--child seat-->
                                    <th style="width:100px;text-align:center">ジュ</th> <!-- junior seat-->
                                    <th style="width:100px;text-align:center">スノータイヤ</th> <!--snow type-->
                                    <th style="width:100px;text-align:center">スマ乗返</th> <!--smart drive otu-->
                                    <!--<th style="width:100px;text-align:center">ホテル乗返</th>--> <!--hote ldrive out-->
                                    <th style="width:100px;text-align:center">調整金額</th> <!--adjust price-->
                                    <th style="width:100px;text-align:center">延泊料金</th> <!--e3xtend night-->
                                    <th style="width:100px;text-align:center">ポイント</th> <!--point-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 0 ;
                                $total = 0;
                                $total_payment = 0;
                                $total_basic_price = 0;
                                $total_insurance1= 0;
                                $total_insurance2 = 0;
                                $total_etc = 0 ;
                                $total_baby_seat = 0;
                                $total_child_seat = 0;
                                $total_junior_seat = 0;
                                $total_snow_type = 0;
                                $total_smart_return = 0;
                                $total_hotel_return = 0;
                                $total_adjustment_price = 0;
                                $total_extend_price = 0;
                                $total_point = 0;
                                ?>
                                @foreach($qs_list as $de)
                                    <tr>
                                        <td><a href="{{URL::to('/')}}/booking/detail/{{ $de->book_id }}" title="予約詳細を見る">
                                                <span> {{$de->name}} </span>
                                            </a>
                                        </td> <!--id-->
                                        <td>{{$de->user_name}}</td> <!--name-->
                                        <td>
                                        <span class="new_row" >
                                            <label>{{displayEmpty($de->payment)}}</label>
                                            円
                                        </span>
                                            <span>({{$de->pay_method}})</span>
                                        </td> <!--portal payment-->
                                        <td>{{displayEmpty($de->basic_price)}}</td> <!--basic price-->
                                        <td>{{displayEmpty($de->insurance1)}}</td> <!--insurance1-->
                                        <td>{{displayEmpty($de->insurance2)}}</td> <!--insurance2-->
                                        <td>{{displayEmpty($de->etc)}}</td><!--etc-->
                                        <td>{{displayEmpty($de->baby_seat)}}</td><!--bayby seat-->
                                        <td>{{displayEmpty($de->child_seat)}}</td><!--child seat-->
                                        <td>{{displayEmpty($de->junior_seat)}}</td><!--junior seat-->
                                        <td>{{displayEmpty($de->snow_type)}}</td> <!--snow type-->
                                        <td>{{displayEmpty($de->smart_return)}}</td> <!--drive smart out-->
                                        <!--<td>{{displayEmpty($de->hotel_return)}}</td>--> <!--hote lreturn-->
                                        <td>{{displayEmpty($de->adjustment_price)}}</td> <!--adjust price-->
                                        <td>{{displayEmpty($de->extend_price)}}</td> <!--extend night-->
                                        <td>{{displayEmpty($de->point)}}</td> <!--point-->
                                    </tr>
                                    <?php
                                    $count++;
                                    $total_payment      += $de->payment;
                                    $total_basic_price  += $de->basic_price ;
                                    $total_insurance1   += $de->insurance1;
                                    $total_insurance2   += $de->insurance2;;
                                    $total_etc          += $de->etc ;
                                    $total_baby_seat    += $de->baby_seat;
                                    $total_child_seat   += $de->child_seat;
                                    $total_junior_seat  += $de->junior_seat;
                                    $total_snow_type    += $de->snow_type;
                                    $total_smart_return += $de->smart_return;
                                    $total_hotel_return += $de->hotel_return;
                                    $total_adjustment_price += $de->adjustment_price;
                                    $total_extend_price += $de->extend_price;
                                    $total_point       += $de->point;
                                    ?>
                                @endforeach
                                <tr>
                                    <td colspan="2">小計</td> <!--id-->
                                    <td>
                                        <span class="new_row" >
                                            <label>{{displayEmpty($total_payment)}}</label>
                                            円
                                        </span>
                                    </td> <!--portal payment-->
                                    <td>{{displayEmpty($total_basic_price)}}</td> <!--basic price-->
                                    <td>{{displayEmpty($total_insurance1)}}</td> <!--insurance1-->
                                    <td>{{displayEmpty($total_insurance2)}}</td> <!--insurance2-->
                                    <td>{{displayEmpty($total_etc)}}</td><!--etc-->
                                    <td>{{displayEmpty($total_baby_seat)}}</td><!--bayby seat-->
                                    <td>{{displayEmpty($total_child_seat)}}</td><!--child seat-->
                                    <td>{{displayEmpty($total_junior_seat)}}</td><!--junior seat-->
                                    <td>{{displayEmpty($total_snow_type)}}</td> <!--snow type-->
                                    <td>{{displayEmpty($total_smart_return)}}</td> <!--drive smart out-->
                                    <!--<td>{{displayEmpty($total_hotel_return)}}</td>--> <!--hote lreturn-->
                                    <td>{{displayEmpty($total_adjustment_price)}}</td> <!--adjust price-->
                                    <td>{{displayEmpty($total_extend_price)}}</td> <!--extend night-->
                                    <td>{{displayEmpty($total_point)}}</td> <!--point-->
                                </tr>
                                <tr>
                                    <td colspan="2">合計</td>
                                    <td>
                                        <span class="new_row" >
                                            <label>{{displayEmpty($total_payment)}}</label>
                                            円
                                        </span>
                                    </td>
                                    <td>{{displayEmpty($total_basic_price)}}</td> <!--basic price-->
                                    <td colspan="2"  style="text-align: center" >{{displayEmpty($total_insurance1 + $total_insurance2)}}</td> <!--insurance-->
                                    <td colspan="6" style="text-align: center" >{{displayEmpty($total_etc + $total_baby_seat + $total_child_seat + $total_junior_seat + $total_snow_type + $total_smart_return + $total_hotel_return )}}</td><!--etc-->
                                    <td>{{displayEmpty($total_adjustment_price)}}</td> <!--adjust price-->
                                    <td>{{displayEmpty($total_extend_price)}}</td> <!--extend night-->
                                    <td>{{displayEmpty($total_point)}}</td> <!--point-->
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--qs list section end-->

                        <!--(qs cancellation list)section start-->
                        <div class="row">
                          <h2>システムQSのキャンセル</h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    <th style="width:100px;text-align:center">ID</th> <!--id-->
                                    <th style="width:150px;text-align:center">氏名</th> <!--name-->
                                    <th style="width:150px;text-align:center">キャンセル金額</th> <!--payment-->
                                    <th style="width:100px;text-align:center">基本料金</th> <!--basic price-->
                                    <th style="width:100px;text-align:center">免責補償</th> <!--insurance1-->
                                    <th style="width:100px;text-align:center">ワイド免責</th> <!--insurance2-->
                                    <th style="width:100px;text-align:center">ETC</th> <!--etc-->
                                    <th style="width:100px;text-align:center">ベビ</th> <!--baby seat-->
                                    <th style="width:100px;text-align:center">チャイ</th> <!--child seat-->
                                    <th style="width:100px;text-align:center">ジュ</th> <!-- junior seat-->
                                    <th style="width:100px;text-align:center">スノータイヤ</th> <!--snow type-->
                                    <th style="width:100px;text-align:center">スマ乗返</th> <!--smart drive otu-->
                                    <!--<th style="width:100px;text-align:center">ホテル乗返</th>--> <!--hote ldrive out-->
                                    <th style="width:100px;text-align:center">調整金額</th> <!--adjust price-->
                                    <th style="width:100px;text-align:center">延泊料金</th> <!--e3xtend night-->
                                    <th style="width:100px;text-align:center">ポイント</th> <!--point-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 0 ;
                                $total = 0;
                                $total_payment = 0;
                                $total_basic_price = 0;
                                $total_insurance1= 0;
                                $total_insurance2 = 0;
                                $total_etc = 0 ;
                                $total_baby_seat = 0;
                                $total_child_seat = 0;
                                $total_junior_seat = 0;
                                $total_snow_type = 0;
                                $total_smart_return = 0;
                                $total_hotel_return = 0;
                                $total_adjustment_price = 0;
                                $total_extend_price = 0;
                                $total_point = 0;
                                ?>
                                @foreach($qs_cancel_list as $de)
                                    <tr>
                                        <td><a href="{{URL::to('/')}}/booking/detail/{{ $de->book_id }}" title="予約詳細を見る">
                                                <span> {{$de->name}} </span>
                                            </a>
                                        </td> <!--id-->
                                        <td>{{$de->user_name}}</td> <!--name-->
                                        <td>
                                        <span class="new_row" >
                                            <label>{{displayEmpty($de->payment)}}</label>
                                            円
                                        </span>
                                            <span>({{$de->pay_method}})</span>
                                        </td> <!--portal payment-->
                                        <td>{{displayEmpty($de->basic_price)}}</td> <!--basic price-->
                                        <td>{{displayEmpty($de->insurance1)}}</td> <!--insurance1-->
                                        <td>{{displayEmpty($de->insurance2)}}</td> <!--insurance2-->
                                        <td>{{displayEmpty($de->etc)}}</td><!--etc-->
                                        <td>{{displayEmpty($de->baby_seat)}}</td><!--bayby seat-->
                                        <td>{{displayEmpty($de->child_seat)}}</td><!--child seat-->
                                        <td>{{displayEmpty($de->junior_seat)}}</td><!--junior seat-->
                                        <td>{{displayEmpty($de->snow_type)}}</td> <!--snow type-->
                                        <td>{{displayEmpty($de->smart_return)}}</td> <!--drive smart out-->
                                        <!--<td>{{displayEmpty($de->hotel_return)}}</td>--> <!--hote lreturn-->
                                        <td>{{displayEmpty($de->adjustment_price)}}</td> <!--adjust price-->
                                        <td>{{displayEmpty($de->extend_price)}}</td> <!--extend night-->
                                        <td>{{displayEmpty($de->point)}}</td> <!--point-->
                                    </tr>
                                    <?php
                                    $count++;
                                    $total_payment      += $de->payment;
                                    $total_basic_price  += $de->basic_price ;
                                    $total_insurance1   += $de->insurance1;
                                    $total_insurance2   += $de->insurance2;;
                                    $total_etc          += $de->etc ;
                                    $total_baby_seat    += $de->baby_seat;
                                    $total_child_seat   += $de->child_seat;
                                    $total_junior_seat  += $de->junior_seat;
                                    $total_snow_type    += $de->snow_type;
                                    $total_smart_return += $de->smart_return;
                                    $total_hotel_return += $de->hotel_return;
                                    $total_adjustment_price += $de->adjustment_price;
                                    $total_extend_price += $de->extend_price;
                                    $total_point       += $de->point;
                                    ?>
                                @endforeach
                                <tr>
                                    <td colspan="2">小計</td> <!--id-->
                                    <td>
                                        <span class="new_row" >
                                            <label>{{displayEmpty($total_payment)}}</label>
                                            円
                                        </span>
                                    </td> <!--portal payment-->
                                    <td>{{displayEmpty($total_basic_price)}}</td> <!--basic price-->
                                    <td>{{displayEmpty($total_insurance1)}}</td> <!--insurance1-->
                                    <td>{{displayEmpty($total_insurance2)}}</td> <!--insurance2-->
                                    <td>{{displayEmpty($total_etc)}}</td><!--etc-->
                                    <td>{{displayEmpty($total_baby_seat)}}</td><!--bayby seat-->
                                    <td>{{displayEmpty($total_child_seat)}}</td><!--child seat-->
                                    <td>{{displayEmpty($total_junior_seat)}}</td><!--junior seat-->
                                    <td>{{displayEmpty($total_snow_type)}}</td> <!--snow type-->
                                    <td>{{displayEmpty($total_smart_return)}}</td> <!--drive smart out-->
                                    <!--<td>{{displayEmpty($total_hotel_return)}}</td>--> <!--hote lreturn-->
                                    <td>{{displayEmpty($total_adjustment_price)}}</td> <!--adjust price-->
                                    <td>{{displayEmpty($total_extend_price)}}</td> <!--extend night-->
                                    <td>{{displayEmpty($total_point)}}</td> <!--point-->
                                </tr>
                                <tr>
                                    <td colspan="2">合計</td>
                                    <td>
                                        <span class="new_row" >
                                            <label>{{displayEmpty($total_payment)}}</label>
                                            円
                                        </span>
                                    </td>
                                    <td>{{displayEmpty($total_basic_price)}}</td> <!--basic price-->
                                    <td colspan="2"  style="text-align: center" >{{displayEmpty($total_insurance1 + $total_insurance2)}}</td> <!--insurance-->
                                    <td colspan="6" style="text-align: center" >{{displayEmpty($total_etc + $total_baby_seat + $total_child_seat + $total_junior_seat + $total_snow_type + $total_smart_return + $total_hotel_return )}}</td><!--etc-->
                                    <td>{{displayEmpty($total_adjustment_price)}}</td> <!--adjust price-->
                                    <td>{{displayEmpty($total_extend_price)}}</td> <!--extend night-->
                                    <td>{{displayEmpty($total_point)}}</td> <!--point-->
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--qs cancelllation list section end-->
                        <!--cancellation five section start-->
                        <div class="row">
                            <h2>キャンセルによる売上</h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    <th style="width:100px;text-align:center">ID</th> <!--id-->
                                    <th style="width:150px;text-align:center">氏名</th> <!--name-->
                                    <th style="width:150px;text-align:center">キャンセル料金</th> <!--paid price-->
                                    <th style="width:100px;text-align:center">基本料金</th> <!--basic price-->
                                    <th style="width:100px;text-align:center">キャンセルレート</th> <!--cancel rate-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                     $count =0 ;
                                     $total_cancel_fee = 0;
                                     $total_cancel_total = 0;
                                     $total_basic_price = 0;
                                 ?>
                                @foreach($cancel_list as $ca)
                                <tr>
                                    <td><a href="{{URL::to('/')}}/booking/detail/{{ $ca->book_id }}" title="予約詳細を見る">
                                            <span> {{$ca->name}} </span>
                                        </a></td> <!--id-->
                                    <td>{{$ca->user_name}}</td> <!--name-->
                                    <td>
                                        <span>{{displayEmpty($ca->cancel_fee)}}</span> 円
                                        <span>({{$ca->cancel_status}})</span>
                                    </td> <!--paid price-->
                                    <td>{{displayEmpty($ca->basic_price)}}</td> <!--booking price--> <!--Jin,Here is Basic price-->
                                    <td>{{displayEmpty($ca->cancel_percent)}}%</td> <!--cancel rate-->
                                </tr>
                                <?php
                                    $count++;
                                    $total_cancel_fee   += $ca->cancel_fee;
                                    $total_cancel_total += $ca->cancel_total;
                                    $total_basic_price  += $ca->basic_price;
                                ?>
                                @endforeach
                                <tr>
                                    <td colspan="2">小計</td> <!--id-->
                                    <td>{{displayEmpty($total_cancel_fee)}}</td> <!--paid price-->
                                    <td>{{displayEmpty($total_basic_price)}}</td> <!--booking price-->
                                    <td></td> <!--basic price-->
                                </tr>
                                <tr>
                                    <td colspan="2">合計</td>
                                    <td colspan="5">{{displayEmpty($total_cancel_fee)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--cancellation five section end-->
                    @endif

                    @if($cond == 'day')
                        <!--(Sum)first section start-->
                        <?php
                             $sum_price = 0;
                             $sum_number = 0;
                             $day = intval(date("d"));
                        ?>
                        @foreach($result_list as $sum)
                            <?php
                                //$allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum + $sum->cancelsum ;
                        $allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum;
                                $sum_price += $allsum;
                                $sum_number += $sum->number;
                            ?>
                        @endforeach
                        <div>
                            <h4>
                                <span>1日あたりの平均貸出件数 {{round($sum_number/$day,2)}} 件 </span>
                                <span>平均売上 {{round($sum_price/$day,2)}} 円</span>
                            </h4>
                        </div>
                        <div class="row">
                            <h2>Sum List</h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    <th style="width:150px;text-align:center">日付</th>
                                    <th style="width:150px;text-align:center">貸出件数</th>
                                    <th style="width:150px;text-align:center">売上金額</th>
                                    <th style="width:150px;text-align:center">現金</th>{{--sum sales--}}
                                    <th style="width:150px;text-align:center">クレジットカード</th>{{--credit card--}}
                                    <th style="width:150px;text-align:center">システムQS</th>{{--system(QS)--}}
                                    <th style="width:150px;text-align:center">ポータルサイト</th>{{--portal--}}
                                    <th style="width:150px;text-align:center">ポイント</th>{{--point--}}
                                    {{--<th>Cancellation</th>--}}{{--cancellation--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($result_list as $sum)
                                    <?php
                                      //$allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum + $sum->cancelsum ;
                                        $allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum   ;
                                    ?>
                                    <tr>
                                        <td>{{$sum->day}}</td>
                                        <td>{{displayEmpty($sum->number)}}</td>
                                        <td>{{displayEmpty($allsum)}}</td>
                                        <td>{{displayEmpty($sum->cashsum)}}</td>{{--sum sales--}}
                                        <td>{{displayEmpty($sum->creditsum)}}</td>{{--credit card--}}
                                        <td>{{displayEmpty($sum->websum)}}</td>{{--system(QS)--}}
                                        <td>{{displayEmpty($sum->portalsum)}}</td>{{--portal--}}
                                        <td>{{displayEmpty($sum->adjustmentsum)}}</td>{{--point--}}
                                        {{--<td>{{$sum->cancelsum}}</td>--}}{{--cancellation--}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--first section end-->
                    @endif

                    @if($cond == 'month')
                        <!--(Sum)first section start-->
                        <?php
                        $sum_price = 0;
                        $sum_number = 0;
                        $day = intval(date("m"));
                        ?>
                        @foreach($result_list as $sum)
                            <?php
                            //$allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum + $sum->cancelsum ;
                        $allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum  ;
                            $sum_price += $allsum;
                            $sum_number += $sum->number;
                            ?>
                        @endforeach
                        <div>
                            <h4>
                                <span>１ヶ月あたりの平均貸出件数 {{round($sum_number/$day,2)}} 件 </span>
                                <span>平均売上 {{round($sum_price/$day,2)}} 円</span>
                            </h4>
                        </div>
                        <div class="row">
                            <h2></h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    <th style="width:150px;text-align:center">月</th>
                                    <th style="width:150px;text-align:center">貸出件数</th>
                                    <th style="width:150px;text-align:center">売上金額</th>
                                    <th style="width:150px;text-align:center">現金</th>{{--sum sales--}}
                                    <th style="width:150px;text-align:center">クレジットカード</th>{{--credit card--}}
                                    <th style="width:150px;text-align:center">システムQS</th>{{--system(QS)--}}
                                    <th style="width:150px;text-align:center">ポータルサイト</th>{{--portal--}}
                                    <th style="width:150px;text-align:center">ポイント</th>{{--point--}}
                                    {{--<th>Cancellation</th>--}}{{--cancellation--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($result_list as $sum)
                                    <?php
                                        //$allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum + $sum->cancelsum ;
                                        $allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum  ;
                                    ?>
                                    <tr>
                                        <td>{{$sum->day}}</td>
                                        <td>{{displayEmpty($sum->number)}}</td>
                                        <td>{{displayEmpty($allsum)}}</td>
                                        <td>{{displayEmpty($sum->cashsum)}}</td>{{--sum sales--}}
                                        <td>{{displayEmpty($sum->creditsum)}}</td>{{--credit card--}}
                                        <td>{{displayEmpty($sum->websum)}}</td>{{--system(QS)--}}
                                        <td>{{displayEmpty($sum->portalsum)}}</td>{{--portal--}}
                                        <td>{{displayEmpty($sum->adjustmentsum)}}</td>{{--point--}}
                                        {{--<td>{{$sum->cancelsum}}</td>--}}{{--cancellation--}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--first section end-->
                    @endif

                    @if($cond == 'year')
                            <!--(Sum)first section start-->
                    <div class="row">
                        <h2></h2>
                        <table class="table table-bordered" style="width:auto;text-align:center">
                            <thead>
                            <tr>
                                <th style="width:150px;text-align:center">年</th>
                                <th style="width:150px;text-align:center">貸出件数</th>
                                <th style="width:150px;text-align:center">売上金額</th>
                                <th style="width:150px;text-align:center">現金</th>{{--sum sales--}}
                                <th style="width:150px;text-align:center">クレジットカード</th>{{--credit card--}}
                                <th style="width:150px;text-align:center">システムQS</th>{{--system(QS)--}}
                                <th style="width:150px;text-align:center">ポータルサイト</th>{{--portal--}}
                                <th style="width:150px;text-align:center">ポイント</th>{{--point--}}
                                {{--<th>Cancellation</th>--}}{{--cancellation--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($result_list as $sum)
                                <?php
                                    //$allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum + $sum->cancelsum ;
                                    $allsum = $sum->cashsum + $sum->creditsum + $sum->websum + $sum->portalsum + $sum->adjustmentsum ;
                                ?>
                                <tr>
                                    <td>{{$sum->day}}</td>
                                    <td>{{displayEmpty($sum->number)}}</td>
                                    <td>{{displayEmpty($allsum)}}</td>
                                    <td>{{displayEmpty($sum->cashsum)}}</td>{{--sum sales--}}
                                    <td>{{displayEmpty($sum->creditsum)}}</td>{{--credit card--}}
                                    <td>{{displayEmpty($sum->websum)}}</td>{{--system(QS)--}}
                                    <td>{{displayEmpty($sum->portalsum)}}</td>{{--portal--}}
                                    <td>{{displayEmpty($sum->adjustmentsum)}}</td>{{--point--}}
                                    {{--<td>{{$sum->cancelsum}}</td>--}}{{--cancellation--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--first section end-->
                    @endif

                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    <link href="{{URL::to('/')}}/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <style>
        li {
            list-style: none;
        }
        .bottom-align-text {
            position: absolute;
            bottom: 35px;
            right: 0;
        }
        .option-modal{
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%) !important;
        }
        .today-modal{
            width: 70%;
            top: 10%;
        }
        .modal-header .close {
            margin-top: -20px;
        }
        .today_deaprt_1 {
            background-color: #fff;
        }
        .today_depart_2{
            background-color: #daeef3;
        }
        .today_return_1 {
            background-color: #fff;
        }
        .today_return_2{
            background-color: #fde9d9;
        }
        .datepicker {
            background: #fff;
            border: 1px solid #555;
        }
        .data-table thead tr th {
            white-space: nowrap;
        }
        .data-table tbody tr td {
            white-space: nowrap;
        }
        .table-pdf thead tr th{
            text-align: center;
        }
        .table-pdf tbody tr td{
            text-align: center;
        }
        .cell{
            padding: 0px 8px 0px 8px !important;
            vertical-align: middle !important;
        }
        .option_detail {
            /*background-color: #eee;*/
            /*padding: 1px 3px 1px 3px;*/
            /*font-size: 12px;*/
            /*border: 1px solid #bebebe;*/
            font-weight:300;
            /*border-radius: 2px;;*/
            /*margin-right: 3px;*/
            /*cursor: pointer;*/
            /*margin-top: 2px;*/
            /*margin-left: 2px;*/
        }
        .option_detail::after{
            content: ',';
        }
        .option_div {
            position: absolute;
            background-color: #F5F5F5;
            padding: 1px 3px 1px 3px;
            font-size: 12px;
            border: 1px solid #3B3B3B;
            font-weight: 300;
            border-radius: 4px;;
            margin-right: 3px;
            margin-left: 55px;
        }
        .contact_div {
            position: absolute;
            background-color: #F5F5F5;
            padding: 1px 3px 15px 3px;
            font-size: 12px;
            border: 1px solid #747474;
            font-weight: 300;
            border-radius: 4px;;
            margin-right: 3px;
        }
        .new_row::after {
            content: '\A';
            white-space: pre;
        }
        tbody > tr:hover { background-color: #EEEEEE; cursor: pointer }

        .loader {
            border: 6px solid #d0d0d0;
            border-radius: 50%;
            border-top: 6px solid #3498db;
            width: 30px;
            height: 30px;
            -webkit-animation: spin 2s linear infinite; /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari for loader */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/dataTables/dataTables.bootstrap.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>

    <script type="text/javascript">
        var cond = '{{$cond}}';
        convertdate(cond);
        function convertdate(cond) {
            var new_options = {};
            if(cond == 'one') {
                new_options = {
                    language: "ja",
                    format: 'yyyy-mm-dd',
                    //endDate: '{{ date('Y-n-j') }}',
                    orientation: "bottom",
                    todayHighlight: true,
                    daysOfWeekHighlighted: "0,6",
                    autoclose: true
                }
            }
            if(cond == 'day') {
                new_options = {
                    language: "ja",
                    format: "yyyy-mm",
                    viewMode: "months",
                    //endDate: '{{ date('Y-n') }}',
                    minViewMode: "months",
                    orientation: "bottom",
                    autoclose: true
                }
            }
            if(cond == 'month') {
                new_options = {
                    language: "ja",
                    format: "yyyy",
                    viewMode: "years",
                    //endDate: '{{ date('Y-n') }}',
                    minViewMode: "years",
                    orientation: "bottom",
                    autoclose: true
                }
            }
            if(cond == 'year') {
                new_options = {
                    language: "ja",
                    format: "yyyy",
                    viewMode: "years",
                    //endDate: '{{ date('Y-n') }}',
                    minViewMode: "years",
                    orientation: "bottom",
                    autoclose: true
                }
            }
            // Save value
            //var val = $('#datepicker').datepicker('getDates');
            var val = $('#date').val();
            var today = new Date();
            var today_date = today.getDate();
            var today_month = today.getMonth()+1;
            var value = new Date(val);
            var year  = value.getFullYear();
            // Destroy previous datepicker
            $('#datepicker').datepicker('destroy');
            // Re-int with new options
            $('#datepicker').datepicker(new_options);
            // Set previous value
//            $(".datepicker").datepicker({
//                dateFormat: 'yyyy-mm-dd'
//            }).datepicker('setDate', value);
//            if(cond == 'one')   $('#date').val(year + "-" + today_month +"-" +today_date);
//            if(cond == 'day')   $('#date').val(year + "-" + today_month);
//            if(cond == 'month') $('#date').val(year);
//            if(cond == 'year') $('#date').val(year);
        }
        $("#datepicker").datepicker({
        }).on("change", function() {
            changedateshop();
        });
        //change date
        function changedate(e) {
            var target       = $(e.currentTarget);
            var cond          = target.val();
            convertdate(cond);
            var shop_id = $('#shop_id').val();
            var cond = $('#cond').val();
            var date = $('#date').val();
            $('#s_shop_id').val(shop_id);
            $('#s_cond').val(cond);
            $('#s_date').val(date);
            $('#searchform').submit();
        }
        function changedateshop() {
            var shop_id = $('#shop_id').val();
            var cond = $('#cond').val();
            var date = $('#date').val();
            $('#s_shop_id').val(shop_id);
            $('#s_cond').val(cond);
            $('#s_date').val(date);
            $('#searchform').submit();
        }

    </script>
@endsection
