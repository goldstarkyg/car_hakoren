@extends('layouts.adminapp_calendar')

@section('template_title')
    店舗配車表
@endsection

@section('template_linked_css')

@endsection
@inject('service', 'App\Http\Controllers\BookingManagementController')
@section('content')
    <style>
        .active_menu {
            background-color: #ececec;
        }
        .alert-block-tom{
            visibility: visible !important;
        }

        .change_return {
            position:absolute;
            border:1px solid #5a8f00;
            color:#333;
            background:#fff;
            /* css3 */
            -webkit-border-radius:10px;
            -moz-border-radius:10px;
            border-radius:10px;
            z-index: 10;
            font-size: 13px;
            padding: 10px;;
            margin-left: 170px; ;
            display: none;
        }

        /**/
        body{
            background-color: #fff;
        }
        @media screen and (max-width: 1024px){
            /*.navbar-header{
                width: 100%;
                display: inline-block;
            }*/
        .tabel_box .pricing-main{
            width: 71%;
        }
        .tabel_box .etc-block{
            width: 28%;
            padding-left: 5px;
        }
        .box-wrap .said-block{
            margin-left: 20px;
        }

        }
        @media screen and (max-width: 768px){
        .box-wrap .m-l-lg,
        .box-wrap .m-r-lg {
            margin: 0;
        }
        .box-wrap .dispatch,
        .box-wrap .said-block {
            width: 100%;
            display: inline-block !important;
            margin: 0 0 10px 0;
        }
        .box-wrap .shop-block{
            float: none;
        }
        .box-wrap .dispatch span,
        .box-wrap .said-block span{
            width: 30%;
        }
        .box-wrap .dispatch ul,
        .box-wrap .said-block ul{
            width: 69%;
        }
        .pic_wrap .status-block {
            padding: 0;
            width: 100%;
            display: inline-block;
            text-align: center;
            margin-bottom: 15px;
        }
        .ret_wrap .date-block {
            margin: 20px 0 0;
            padding-left: 0;
        }
        .ret_wrap .date-block ul {
            max-width: 100%;
            text-align: center;
        }
        .tabel_box .pricing-main {
            width: 100%;
        }
        .tabel_box .pricing-main ul{
            width: 45%;
        }
        .tabel_box .etc-block {
            padding-left: 0;
            width: 100%;
            margin: 15px 0;
            text-align: center;
        }
        .tabel_box .memo-block textarea {
            margin: 9px 0 0;
            display: inline-block;
            width: 100%;
        }
        .tabel_box .memo-block a {
            width: 100%;
            margin: 15px 0 0;
            padding: 6px 12px;
        }
        .tabel_box .status-meno-block{
            text-align: center;
            padding: 0 15px;
        }
        .btn_manage,
        .btn_manage2{
            padding: 0 !important;
        }
        .btn_manage li{
            width: 50%;
        }
        .btn_manage li a{
            width: 100%;
            display: inline-block;
        }
        .btn_manage2 li{
            width: 33%;
        }
        .btn_manage2 li a{
            width: 100%;
            display: inline-block;
        }
        
        }

        @media screen and (max-width: 425px){
            .box-wrap .dispatch ul,
            .box-wrap .said-block ul{
                width: 67%;
            }
        }
        /**/
    </style>
    <div>
        <div class="container m-t-n-lg">
            <div class="">
                <div class="panel panel-default box-wrap">
                    {{--<h2>予約一覧</h2>--}}
                    {{--<div style="position: absolute; margin-top: -3.3em;left:170px;" >--}}
                        {{--<label>--}}
                            {{--<a href="{{URL::to('/')}}/booking/all" class="list-group-item @if($subroute == 'all') active_menu @endif " data-parent="#MainMenu">--}}
                                {{--<label>View All</label>--}}
                            {{--</a>--}}
                        {{--</label>--}}
                        {{--<label>--}}
                            {{--<a href="{{URL::to('/')}}/booking/today" class="list-group-item @if($subroute == 'today') active_menu @endif " data-parent="#MainMenu">--}}
                                {{--<label>Today</label>--}}
                            {{--</a>--}}
                        {{--</label>--}}
                        {{--<label>--}}
                            {{--<a href="{{URL::to('/')}}/booking/tomorrow" class="list-group-item @if($subroute == 'tomorrow')active_menu @endif " data-parent="#MainMenu">--}}
                                {{--<label>Tomorrow</label>--}}
                            {{--</a>--}}
                        {{--</label>--}}
                        {{--<label>--}}
                            {{--<a href="{{URL::to('/')}}/booking/new/0" class="list-group-item @if($subroute == 'new')active_menu @endif " data-parent="#MainMenu">--}}
                                {{--<label>Add Booking</label>--}}
                            {{--</a>--}}
                        {{--</label>--}}
                        {{--<label>--}}
                            {{--<a href="{{URL::to('/')}}/booking/task" class=" list-group-item @if($subroute == 'task') active_menu @endif " data-parent="#MainMenu" >--}}
                                {{--<label>配車表</label>--}}
                            {{--</a>--}}
                        {{--</label>--}}
                    {{--</div>--}}
                    {{--<div style="position: absolute; margin-top: -2.5em;right: 20px;" >--}}
                        {{--<a href="{{URL::to('/')}}/booking/new/0" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">--}}
                            {{--<i class="fa fa-book" aria-hidden="true"></i>&emsp;--}}
                            {{--新予約を作成--}}
                        {{--</a>--}}
                        {{--<a class="btn btn-success btn-xs pull-right" style="margin-left: 1em;" onclick="deleteGoogle()">--}}
                            {{--delete google sheet data for test--}}
                        {{--</a>--}}

                        {{--<a class="btn btn-success btn-xs pull-right" style="margin-left: 1em;" onclick="loadingGoogle()">--}}
                            {{--<i class="fa fa-download" aria-hidden="true"></i>&emsp;--}}
                            {{--load googlesheet--}}
                        {{--</a>--}}
                        {{--<label id="loading_icon" style="display:none">--}}
                            {{--<a class="loader pull-right m-t-n-xs"></a>--}}
                            {{--<a class="pull-right">Loading...</a>--}}
                        {{--</label>--}}
                    {{--</div>--}}
                    <!--top part-->
                    {{--<div class="dispatch-block shadow-box">--}}
                    <div class="m-l-lg m-r-lg">
                        <div class="dispatch">
                            <h2>タスク表</h2>
                        </div>
                        <div class="dispatch">
                            <span>配車表</span>
                            <ul class="btn_manage">
                                <li><a class="menu_title" onclick="searchBook('today','date')">
                                        @if($task_date == 'today')<i class="fa fa-check-circle"></i> @endif 本日</a></li>
                                <li><a class="menu_title" onclick="searchBook('tom','date')">
                                        @if($task_date == 'tom')<i class="fa fa-check-circle"></i> @endif 明日</a></li>
                                {{--<li><a class="menu_title" onclick="searchBook('week','date')">--}}
                                        {{--@if($task_date == 'week')<i class="fa fa-check-circle"></i> @endif 1 Week</a></li>--}}
                            </ul>
                        </div>
                        <div class="said-block">
                            <span>表示：</span>
                            <ul class="btn_manage2">
                                <li><a class="menu_title" onclick="searchBook('all' ,'category')">@if($category == 'all') <i class="fa fa-check-circle"></i> @endif 全て</a></li>
                                <li><a class="menu_title" onclick="searchBook('rent' ,'category')">@if($category == 'rent') <i class="fa fa-check-circle"></i> @endif 配車</a></li>
                                <li><a class="menu_title" onclick="searchBook('return' ,'category')">@if($category == 'return') <i class="fa fa-check-circle"></i> @endif 返車</a></li>
                            </ul>
                        </div>
                        
                        <div class="said-block" align="right"><a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="javascript:printalltask();" title="Print All">チェックシート印刷</a></div>

                        {{--shop selector--}}
                        <div class="shop-block">
                            <select name="shopid" id="shopid" class="form-control" onchange="searchBook('all' ,'shop')" >
                                @foreach($shops as $shop)
                                    <option value="{{$shop->id}}" @if($shop_id == $shop->id) selected @endif>{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <form method="post" name="search" id="search" action="{{URL::to('/')}}/booking/task">
                        {!! csrf_field() !!}
                        <input type="hidden" name="task_date" value="{{$task_date}}" >
                        <input type="hidden" name="category" value="{{$category}}" >
                        <input type="hidden" name="shop_id" value="{{$shop_id}}" >
                        <input type="hidden" name="cflag" value="{{$cflag}}" >
                        <input type="hidden" name="target_object" value="{{$target_object}}" >
                    </form>
                </div>
            </div>

        </div>

        <div class="container">
           <!--body part-->
            <div class="car-main-block shadow-box">
                <div class="car-inner-block">
                    <div class="view-block">
                        <div class="row">
                            <div class="col-sm-5 col-xs-12 view-left-block">
                                <h2>
                                    <?php
                                        $date_val = '';
                                        if($task_date == 'today')$date_val='本日';
                                        if($task_date == 'tom') $date_val='明日';
                                    ?>
                                    @if($task_date == 'today')本日、@endif
                                    @if($task_date == 'tom')明日、@endif
                                    @if($task_date == 'today'||$task_date == 'tom')
                                        {{date('n', strtotime($date))}}月{{date('j', strtotime($date))}}日の
                                    @else
                                        1週間の
                                    @endif
                                    「<span>
                                        @if($category == 'all') 配車&返車 @endif
                                        @if($category == 'rent') 配車 @endif
                                        @if($category == 'return') 返車 @endif
                                    </span>」は全<span>{{$all_count}}</span>件です。</h2>
                            </div>
                            <div class="col-sm-7 col-xs-12 view-right-block">
                                <div class="progress-block">
                                    <ul>
                                        <li><span>配車残り {{$rents_all-$rents_end_count}}件</span><div class="w3-light-grey">
                                                <div class="w3-green" style="font-weight:500;width:{{$rent_ratio}}%;@if($rent_ratio == 0) background-color:#d2d2d2;@endif">{{$rent_ratio}}%</div>
                                            </div></li>
                                        <li><span>返車残り {{$return_all-$returns_end_count}}件</span><div class="w3-light-grey">
                                                <div class="w3-green" style="font-weight:500;width:{{$return_ratio}}%;@if($return_ratio == 0) background-color:#d2d2d2;@endif">{{$return_ratio}}%</div>
                                            </div></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($cflag == '1')
                    <div id="completed_message" class="text-center m-b-md text-danger row">
                        <div class="col-md-6 col-md-offset-3 p-sm" style="border:1px solid #FF0000;">
                            <label><strong style="font-size: 17px;">お疲れ様でした！1つのタスクが完了しました !</strong> </label>
                            <label class="pull-right" style="position: relative;top:-10px;">
                                <button type="button" class="close text-danger" aria-label="Close" onclick="closemessage()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </label>
                        </div>
                    </div>
                    @endif
                    <div class="car-listing-main active">
                        <div class="col-sm-12">
                            {!! csrf_field() !!}
                            <!--start rent part-->
                            <?php $count_number = 0; ?>

                            @foreach($rents as $rent)
                                <div class="car-common-block active tabel_box" id="target_{{$count_number}}" >
                                    <div class="car-common-inner">
                                        <div class="number-block">
                                            <h2>{{$rent_display-$count_number}}</h2>
                                        </div>
                                        <div class="common-content-block">
                                            <div class="user-block" onclick="showHide('row_{{$count_number}}')">
                                                <h2>配車</h2>
                                                @if($rent->portal_flag == 0)
                                                    @if($rent->bookedcount == 1)
                                                        <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px;margin-left: 5px;" >初</h2>
                                                    @else
                                                        <h2 class="repeat">リピート @if($rent->bookedcount > 1) {{$rent->bookedcount}} @endif</h2>
                                                    @endif
                                                @else
                                                    <?php
                                                    $color = \App\Http\DataUtil\ServerPath::portalColor($rent->portal_id);
                                                    $pname = $portal_sites[$rent->portal_id];
                                                    ?>
                                                    <h2 class="repeat" style="font-weight:500;color:#111;background-color: {{$color}};border: {{$color}} 1px solid;padding: 1px 5px;margin-left: 5px;" >{{$pname}}</h2>
                                                @endif
                                                
                                                @if($rent->web_status == '3')
                                                
                                                @if($rent->bag_choosed == '1')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 3px;margin-left: 5px;" >QS フリスク</h2> @endif
                                                @if($rent->bag_choosed == '2')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS ぷっちょ</h2> @endif
                                                @if($rent->bag_choosed == '3')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS 酔い止め</h2> @endif
                                                
                                                @endif
                                                
                                                
                                                <h3><span class="user-name">
                                                        {{$rent->last_name}} {{$rent->first_name}}
                                                        ({{$rent->fur_last_name}} {{$rent->fur_first_name}})
                                                    </span> 様は
                                                    <span class="time">
                                                        {{$date_val}}
                                                        {{--@if($task_date != 'today') {{date('Y-n-j', strtotime($rent->departing))}} @endif --}}
                                                            {{date('G:i', strtotime($rent->departing))}}
                                                        <label id="changed_return_time_{{$count_number}}" onclick="changeDepartTime(event,{{json_encode($rent)}},'{{date('G:i', strtotime($rent->departing))}}','{{$count_number}}')" style="cursor: pointer;">
                                                            <i class="fa fa-phone-square" aria-hidden="true"></i>
                                                        </label>
                                                            <div class="change_return" id="change_return_{{$count_number}}">
                                                                <div> 連絡先: <span id="change_return_tel">--{{ $rent->phone }}--</span></div>
                                                                <div style="margin-top: 5px;">
                                                                    <select name="change_return_time_{{$count_number}}" id="change_return_time_{{$count_number}}" style="height:28px;" onclick="return_select(event);">
                                                                        @foreach($hour as $h)
                                                                            <option value="{{$h}}">{{$h}} </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <a class="btn btn-primary btn-sm" onclick="savedepartTime(event)" style="margin-top: -4px;" >確定</a>
                                                                </div>
                                                            </div>
                                                    </span> 出発です。                                                    
                                                    
                                                    <a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="javascript:print_booking({!! $rent->id !!});" title="詳細">
                                                   	 <span class="hidden-xs hidden-sm">印刷</span>
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{URL::to('/')}}/booking/edit/{{$rent->id}}"  title="編集">
                                                        <span class="hidden-xs hidden-sm">編集</span>
                                                    </a>
                                                    </h3>
                                            </div>
                                            <div id="row_{{$count_number}}" class="row user-block-content" @if($count_number > 4) style="display: none;" @endif >
                                                <div class="col-md-5 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-5 col-md-4 pic_wrap">
                                                            <div class="status-block">
                                                                <ul>
                                                                    @if($rent->web_status == '1')
                                                                        <li id="web_{{$count_number}}" class="status-done">
                                                                            <i class="fa fa-check-circle"></i><span>Web免許</span>
                                                                            @if(!empty($rent->driver_license_images))
                                                                                <span class="license" onclick="viewlicense({{json_encode($rent)}})">免</span>
                                                                            @endif
                                                                        </li>
                                                                    @elseif($rent->web_status == '2')
                                                                        <li id="web_{{$count_number}}" class="status-done">
                                                                            <i class="fa fa-check-circle"></i><span>Web免許</span>
                                                                            @if(!empty($rent->driver_license_images))
                                                                                <span class="license" onclick="viewlicense({{json_encode($rent)}})">免</span>
                                                                            @endif
                                                                        </li>
                                                                        <li id="web_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>Web同意</span></li>
                                                                    @elseif($rent->web_status == '3')
                                                                        <li id="web_{{$count_number}}" class="status-done">
                                                                            <i class="fa fa-check-circle"></i><span>Web免許</span>
                                                                            @if(!empty($rent->driver_license_images))
                                                                                <span class="license" onclick="viewlicense({{json_encode($rent)}})">免</span>
                                                                            @endif
                                                                        </li>
                                                                        <li id="web_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>Web同意</span></li>												
                                                                        @if($rent->pay_status == '1')
                                                                        <li id="pay_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>Web決済</span></li>
                                                                    	@endif
                                                                        
                                                                    @endif
                                                                    <input type="hidden" name="web_{{$count_number}}" value="{{$rent->web_status}}" />
                                                                    @if($rent->wait_status == '1')
                                                                            <li id="wait_{{$count_number}}" ><i></i><span>送迎</span></li>
                                                                    @elseif($rent->wait_status == '2')
                                                                            <li id="wait_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>送迎</span></li>
                                                                        {{--送迎待/中--}}
                                                                    @endif
                                                                    <input type="hidden" name="wait_{{$count_number}}" value="{{$rent->wait_status}}" />

                                                                    @if($rent->explain_status == '1')
                                                                        <li id="explain_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>来店/説明</span></li>
                                                                    @else
                                                                        <li id="explain_{{$count_number}}" ><i></i><span>来店/説明</span></li>
                                                                    @endif
                                                                    <input type="hidden" name="explain_{{$count_number}}" value="{{$rent->explain_status}}" />

                                                                    @if($rent->pay_status == '1')
                                                                        {{--<li id="pay_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>Web決済</span></li>--}}
                                                                    @else
                                                                        {{--<li id="pay_{{$count_number}}"><i></i><span>Web決済</span></li>--}}
                                                                    @endif
                                                                    <input type="hidden" name="pay_{{$count_number}}" value="{{$rent->pay_status}}" />

                                                                    @if($rent->pay_status == '0')
                                                                        @if($rent->shop_pay_status == '1')
                                                                            <li id="shop_pay_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>支払</span></li>
                                                                        @else
                                                                            <li id="shop_pay_{{$count_number}}"><i></i><span>支払</span></li>
                                                                        @endif
                                                                        <input type="hidden" name="shop_pay_{{$count_number}}" value="{{$rent->shop_pay_status}}" />
                                                                    @endif

                                                                    {{--@if($rent->com_status == '1')--}}
                                                                        {{--<li id="end_{{$count_number}}" class="status-done" ><i class="fa fa-check-circle"></i><span>完了</span></li>--}}
                                                                    {{--@else--}}
                                                                        {{--<li id="end_{{$count_number}}"><i></i><span>完了</span></li>--}}
                                                                    {{--@endif--}}
                                                                    @if($rent->other_status == '1')
                                                                        <li id="other_{{$count_number}}" class="status-done" ><i class="fa fa-question-circle" ></i><span>その他</span></li>
                                                                    @else
                                                                       <li id="other_{{$count_number}}"><i></i><span>その他</span></li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-4 col-md-5 no-padding">
                                                            <div class="class-main">
                                                                <div class="car-left">
                                                                    <img src="{{URL::to('/')}}/images/cartask/car-icon.png" alt="">
                                                                </div>
                                                                <div class="car-right">
                                                                    <h2>{{$rent->class_name}}</h2>
                                                                    <h3>{{$rent->model_name}}</h3>
                                                                </div>
                                                            </div>
                                                            <div class="smoking-block">
                                                                <div class="smoking-left">
                                                                    <div class="smoking-content">
                                                                        <h2>{{$rent->car_number1}} {{$rent->car_number2}}</h2>
                                                                        <span>{{$rent->car_number3}}</span>
                                                                        <h3>{{$rent->car_number4}}</h3>
                                                                    </div>
                                                                </div>
                                                                <div class="smoking-right">
                                                                    @if($rent->smoke == 0)
                                                                    <img src="{{URL::to('/')}}/images/cartask/smoking-icon.png">
                                                                    @else
                                                                    <img src="{{URL::to('/')}}/images/cartask/smoking-icon2.png">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-3 no-padding ret_wrap">
                                                            <div class="date-block">
                                                                <ul>
                                                                    <li><span>お返し</span></li>
                                                                    <li>
                                                                        @if($rent->night == '0' && $rent->day == '1')
                                                                            当日返し
                                                                        @else
                                                                        {{$rent->night}}泊{{$rent->day}}日
                                                                        @endif
                                                                    </li>
                                                                    <li>
                                                                        {{date('n月j日', strtotime($rent->returning))}}
                                                                    </li>
                                                                    <li>{{date('G:i', strtotime($rent->returning))}}</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-sm-12">
                                                    <div class="pricing-main">
                                                        <h2><span class="outstanding-number coin">{{$rent->paid_payment + $rent->unpaid_payment }}</span>円 <span class="outstanding">
                                                               @if($rent->shop_pay_status == '1')
                                                                   <span id="paid_status_{{$count_number}}" style="color: #22c03c;"> 支払い済 </span>
                                                               @elseif($rent->pay_status == '1')
                                                                   <span id="paid_status_{{$count_number}}" style="color: #22c03c;"> Web決済 </span>
                                                               @else
                                                                   <span id="paid_status_{{$count_number}}"> 未決済 </span>
                                                                @endif
                                                            </span>
                                                        </h2>
                                                        <ul>
                                                            <li class="@if($rent->basic_price == '0') dull @endif">基本：<span class="coin">{{$rent->basic_price}}</span>円</li>
                                                            <li class="@if($rent->insurance1 == '0') dull @endif">免責：<span class="coin">{{$rent->insurance1}}</span>円</li>
                                                            <li class="@if($rent->insurance2 == '0') dull @endif">ワ補：<span class="coin">{{$rent->insurance2}}</span>円</li>
                                                            <li class="@if($rent->etc_card == '0') dull @endif">ETC：<span class="coin">{{$rent->etc_card}}</span>円</li>
                                                            @if($shop_slug == 'naha-airport')
                                                                <li class="@if($rent->smart_driveout == '0') dull @endif">スマ：<span class="coin">{{$rent->smart_driveout}}</span>円</li>
                                                            @endif
                                                        </ul>
                                                        <ul>
                                                            <li class="@if($rent->child_seat == '0') dull @endif">チャ：<span class="coin">{{$rent->child_seat}}</span>円</li>
                                                            <li class="@if($rent->baby_seat == '0') dull @endif">ベビ：<span class="coin">{{$rent->baby_seat}}</span>円</li>
                                                            <li class="@if($rent->snow_tire == '0') dull @endif">スタ：<span class="coin">{{$rent->snow_tire}}</span>円</li>
                                                            <li class="@if($rent->junior_seat == '0') dull @endif">ジュ：<span class="coin">{{$rent->junior_seat}}</span>円</li>
                                                        </ul>
                                                        <a onclick="refreshPrice({{json_encode($rent)}}, '{{$count_number}}')"><i class="fa fa-undo"></i></a>
                                                    </div>
                                                    <div class="etc-block">
                                                        <h2>@if($rent->etc_card != '0')
                                                                <img src="{{URL::to('/')}}/images/cartask/etc-icon.png" width="45" alt="">
                                                            @else
                                                                <img src="{{URL::to('/')}}/images/cartask/etc-icon_grey.png" width="45" alt="">
                                                            @endif
                                                        </h2>
                                                        <h2>@if($rent->snow_tire != '0')
                                                                <img src="{{URL::to('/')}}/images/cartask/etc-icon2.png" width="45" alt="">
                                                            @else
                                                                <img src="{{URL::to('/')}}/images/cartask/etc-icon2_grey.png" width="45" alt="">
                                                            @endif
                                                        </h2>
                                                        <div class="etc-content">
                                                            <ul>
                                                                <li class="@if($rent->child_seat == '0') dull @endif">チャ
                                                                    @if(!empty($rent->child_seat_number)){{$rent->child_seat_number}}個@endif</li>
                                                                <li class="@if($rent->baby_seat == '0') dull @endif">ベビ
                                                                    @if(!empty($rent->baby_seat_number)){{$rent->baby_seat_number}}個@endif</li>
                                                                <li class="@if($rent->junior_seat == '0') dull @endif">ジュ
                                                                    @if(!empty($rent->junior_seat_number)){{$rent->junior_seat_number}}個@endif</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-12 status-meno-block">
                                                    <div class="col-lg-12" style="padding: 0px;">
                                                        <div class="button-group">
                                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                                <span class="glyphicon glyphicon-cog"> ステータス</span>
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                @if($rent->wait_status == '1' || $rent->wait_status == '2')
                                                                <li><a class="memo-block" data-value="wait" tabIndex="-1"
                                                                       onclick="changeStatus(event,'{{$count_number}}','{{$rent->id}}')" >
                                                                       <input type="checkbox"  @if($rent->wait_status == '2') checked @endif
                                                                        onclick="changeStatus_input(event,'{{$count_number}}','{{$rent->id}}','wait')" />&nbsp;送迎</a>
                                                                </li>
                                                                @endif
                                                                <li><a class="memo-block" data-value="explain" tabIndex="-1"
                                                                       onclick="changeStatus(event,'{{$count_number}}','{{$rent->id}}')" >
                                                                       <input type="checkbox" @if($rent->explain_status == '1') checked @endif
                                                                        onclick="changeStatus_input(event,'{{$count_number}}','{{$rent->id}}','explain')" />&nbsp;来店/説明</a>
                                                                </li>
                                                                @if($rent->pay_status == '1')
                                                                    <li><a class="memo-block" data-value="pay" tabIndex="-1" >
                                                                           {{--onclick="changeStatus(event,'{{$count_number}}','{{$rent->id}}')" >--}}
                                                                            <input type="checkbox" @if($rent->pay_status == '1') checked @endif disabled
                                                                            onclick="changeStatus_input(event,'{{$count_number}}','{{$rent->id}}','pay')" />&nbsp;Web決済</a>
                                                                    </li>
                                                                @endif
                                                                @if($rent->pay_status == '0')
                                                                    <li><a class="memo-block" data-value="shop_pay" tabIndex="-1"
                                                                           onclick="changeStatus(event,'{{$count_number}}','{{$rent->id}}')" >
                                                                           <input type="checkbox" @if($rent->shop_pay_status == '1') checked @endif
                                                                            onclick="changeStatus_input(event,'{{$count_number}}','{{$rent->id}}','shop_pay')" />&nbsp;支払</a>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="memo-block">
                                                        <textarea id="other_{{$count_number}}"
                                                                  onkeyup="changeOther(event,'{{$count_number}}','{{$rent->id}}')">{{$rent->admin_memo}}</textarea>
                                                        <a onclick="taskComplete({{$rent->options}},'{{count($rent->options)}}','{{$count_number}}','{{$rent->id}}')">配車<br>完了</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $count_number++; ?>
                            @endforeach
                            <!--end rent part-->
                            <!--start return part-->
                            <?php $count_number_return = 0; ?>
                            @foreach($returns as $rent)
                                    <div class="car-common-block inactive   @if($service->comparebooking($rent,'tomorrow')) box-hide @endif " id="target_{{$count_number}}" >
                                        <div class="car-common-inner">
                                            <div class="number-block">
                                                <h2>{{$rent_display -$count_number}}</h2>
                                            </div>
                                            <div class="common-content-block">
                                                <div class="user-block"  onclick="showHide('row_{{$count_number}}')">
                                                    <h2>返車</h2>
                                                    @if($rent->bookedcount > 0)
                                                        @if($rent->bookedcount == 1)
                                                            <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px;margin-left: 5px;">初
                                                            </h2>
                                                        @else
                                                            <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px 2px;margin-left: 3px;">リピート @if($rent->bookedcount > 1) {{$rent->bookedcount}} @endif
                                                            
															</h2>
                                                        @endif
                                                    @endif
                                                    
                                                     @if($rent->web_status == '3')
                                                    
                                                    @if($rent->bag_choosed == '1')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS フリスク</h2> @endif
                                                    @if($rent->bag_choosed == '2')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS ぷっちょ</h2> @endif
                                                    @if($rent->bag_choosed == '3')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS 酔い止め</h2> @endif
                                                    
                                                     @endif
                                                    <h3><span class="user-name">
                                                            {{$rent->last_name}} {{$rent->first_name}}
                                                            ({{$rent->fur_last_name}} {{$rent->fur_first_name}})
                                                        </span> 様は <span class="time">
                                                            {{$date_val}}
                                                            {{--@if($task_date != 'today') {{date('Y-n-j', strtotime($rent->returning))}} @endif--}}
                                                            <label id="changed_return_time_{{$count_number}}" onclick="changeReturnTime(event,{{json_encode($rent)}},'{{date('G:i', strtotime($rent->returning))}}','{{$count_number}}')" style="cursor: pointer;">{{date('G:i', strtotime($rent->returning))}}</label>
															<i class="fa fa-phone-square" aria-hidden="true"></i>
                                                            <div class="change_return" id="change_return_{{$count_number}}">
                                                                <div> 連絡先: <span id="change_return_tel">--携帯番号--</span></div>
                                                                <div style="margin-top: 5px;">
                                                                    <select name="change_return_time_{{$count_number}}" id="change_return_time_{{$count_number}}" style="height:28px;" onclick="return_select(event);">
                                                                        @foreach($hour as $h)
                                                                            <option value="{{$h}}">{{$h}} </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <a class="btn btn-primary btn-sm" onclick="saveReturnTime(event)" style="margin-top: -4px;" >確定</a>
                                                                </div>
                                                            </div>
                                                        </span> 返却です。</h3>
                                                </div>
                                                <div id="row_{{$count_number}}" class="row" @if($count_number > 4) style="display: none;" @endif >
                                                    <div class="col-sm-5 col-md-4">
                                                        <div class="model-block">
                                                            <ul class="taskbox_returnstyle01 taskbox-cartitlebox">
                                                                <li class="model-car"><img src="{{URL::to('/')}}/images/cartask/car-icon-small.png" alt=""></li>
                                                                <li class="car-class">{{$rent->class_name}}</li>
                                                                <li class="car-model">{{$rent->model_name}}</li>
                                                                <li><span>
                                                                        {{$rent->car_number3}} {{$rent->car_number4}}
                                                                    </span></li>
                                                                <li class="model-smoking">
                                                                    @if($rent->smoke == 0)
                                                                        <img src="{{URL::to('/')}}/images/cartask/smoking-icon-small.png" alt="">
                                                                    @else
                                                                        <img src="{{URL::to('/')}}/images/cartask/smoking-icon-small2.png" alt="">
                                                                    @endif
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="child-baby-block">
                                                            <ul>
                                                                <li>
                                                                    @if($rent->etc_card != '0')
                                                                        <img src="{{URL::to('/')}}/images/cartask/etc-icon.png" alt="">
                                                                    @else
                                                                        <img src="{{URL::to('/')}}/images/cartask/etc-icon_grey.png" alt="">
                                                                    @endif
                                                                </li>
                                                                <li class="@if($rent->child_seat == '0') child-grey @else child-blue @endif">チャイ<span>
                                                                        @if(!empty($rent->child_seat_number)){{$rent->child_seat_number}}個 @endif</span></li>
                                                                <li class="@if($rent->baby_seat == '0') child-grey @else child-blue @endif">ベビー<span>
                                                                        @if(!empty($rent->baby_seat_number)){{$rent->baby_seat_number}}個 @endif</span></li>
                                                                <li class="@if($rent->junior_seat == '0') child-grey @else child-blue @endif ">ジュニ<span>
                                                                        @if(!empty($rent->junior_seat_number)){{$rent->junior_seat_number}}個 @endif</span></li>
                                                                <li>
                                                                    @if($rent->snow_tire != '0')
                                                                        <img src="{{URL::to('/')}}/images/cartask/etc-icon2.png" alt="">
                                                                    @else
                                                                        <img src="{{URL::to('/')}}/images/cartask/etc-icon2_grey.png" alt="">
                                                                    @endif
                                                                </li>
                                                            </ul>
                                                            <div class="m-t-sm">
                                                                <div class="list-inline taskbox_returnstyle01">
                                                                    <li class="status-done taskbox_returnstyle01" style="margin-right: 10px;">貸出後のメーター：</li>
                                                                    <li class="status-done ">
                                                                        <input type="text" class="miles_number" name="miles_{{$count_number}}" onkeyup="checkMiles(event,'{{$count_number}}')" value="{{$rent->before_miles}}" style="width:50px;" /> km
                                                                    </li>
                                                                </div>
                                                                <div>
                                                                    <li class="status-done" style="font-size:10px;font-weight:400;">( <span class="coin">貸出前：{{$rent->before_miles}}</span>km )</li>
                                                                    <input type="hidden" name="before_miles_{{$count_number}}" value="{{$rent->before_miles}}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 col-md-6">
                                                        <?php
                                                            $datetime = new DateTime('tomorrow');
                                                            //$datetime = new DateTime();
                                                            $tom      =  $datetime->format('Y-m-d');
                                                            $return = date('Y-m-d',strtotime($rent->returning));
                                                        ?>
                                                        <div class="alert-block @if($tom == $return)alert-block-tom @endif" >
                                                            <a href="#" target="_blank">
                                                                <h2>この車両は明日 {{date('H:m', strtotime($rent->returning))}} に使用予定（
                                                                    <span>
                                                                        オプション有
                                                                    </span>
                                                                    <span style="font-weight: 200; font-size: 14px;">
                                                                        @foreach($rent->options as $op)
                                                                            {{$op->option_name}}({{$op->option_number}})
                                                                        @endforeach
                                                                    </span>
                                                                    ）<img src="{{URL::to('/')}}/images/cartask/link-icon.png" alt=""></h2></a>
                                                        </div>
                                                        <div class="status-block-inline taskbox_returnstyle01">
                                                            <ul>
                                                                @if($rent->return_status == '1')
                                                                    <li id="return_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>返車処理</span></li>
                                                                @else
                                                                    <li id="return_{{$count_number}}" ><i></i><span>返車処理</span></li>
                                                                @endif
                                                                    <input type="hidden" name="return_{{$count_number}}" value="{{$rent->return_status}}" />
                                                                @if($rent->clean_status == '1')
                                                                 <li id="clean_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>車内清掃</span></li>
                                                                @else
                                                                 <li id="clean_{{$count_number}}" ><i></i><span>車内清掃</span></li>
                                                                @endif
                                                                    <input type="hidden" name="clean_{{$count_number}}" value="{{$rent->clean_status}}" />
                                                                @if($rent->mile_status == '1')
                                                                    <li id="mile_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>走行距離</span></li>
                                                                @else
                                                                    <li id="mile_{{$count_number}}" ><i></i><span>走行距離</span></li>
                                                                @endif
                                                                <input type="hidden" name="mile_{{$count_number}}" value="{{$rent->mile_status}}" />

                                                                {{--@if($tom == $return)--}}
                                                                    {{--@if($rent->wash_status == '1')--}}
                                                                      {{--<li id="wash_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>洗車</span></li>--}}
                                                                    {{--@else--}}
                                                                    {{--<li id="wash_{{$count_number}}" ><i></i><span>洗車</span></li>--}}
                                                                    {{--@endif--}}
                                                                {{--@endif--}}
                                                                    <input type="hidden" name="wash_{{$count_number}}" value="{{$rent->wash_status}}" />
                                                                {{--@if($rent->end_status == '1')--}}
                                                                  {{--<li id="end_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>完了</span></li>--}}
                                                                {{--@else--}}
                                                                  {{--<li id="end_{{$count_number}}" ><i></i><span>完了</span></li>--}}
                                                                {{--@endif--}}
                                                                    {{--<input type="hidden" name="end_{{$count_number}}" value="{{$rent->end_status}}" />--}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 status-meno-block">
                                                        <div class="col-lg-12" style="padding: 0px;">
                                                            <div class="button-group">
                                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                                    <span class="glyphicon glyphicon-cog"> ステータス</span>
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="memo-block" data-value="return" tabIndex="-1"
                                                                           onclick="changeStatus(event,'{{$count_number}}','{{$rent->id}}')" >
                                                                            <input type="checkbox"  @if($rent->return_status == '1') checked @endif
                                                                            onclick="changeStatus_input(event,'{{$count_number}}','{{$rent->id}}','return')" />&nbsp;返車処理</a>
                                                                    </li>
                                                                    <li><a class="memo-block" data-value="clean" tabIndex="-1"
                                                                           onclick="changeStatus(event,'{{$count_number}}','{{$rent->id}}')" >
                                                                            <input type="checkbox" @if($rent->clean_status == '1') checked @endif
                                                                            onclick="changeStatus_input(event,'{{$count_number}}','{{$rent->id}}','clean')" />&nbsp;車内清掃</a>
                                                                    </li>
                                                                    @if($tom == $return)
                                                                    <li><a class="memo-block" data-value="wash" tabIndex="-1"
                                                                           onclick="changeStatus(event,'{{$count_number}}','{{$rent->id}}')" >
                                                                            <input type="checkbox" @if($rent->wash_status == '1') checked @endif
                                                                            onclick="changeStatus_input(event,'{{$count_number}}','{{$rent->id}}','wash')" />&nbsp;洗車</a>
                                                                    </li>
                                                                    @endif
                                                                    {{--<li><a class="memo-block" data-value="end" tabIndex="-1"--}}
                                                                           {{--onclick="changeStatus(event,'{{$count_number}}','{{$rent->id}}')" >--}}
                                                                            {{--<input type="checkbox" @if($rent->end_status == '1') checked @endif--}}
                                                                            {{--onclick="changeStatus_input(event,'{{$count_number}}','{{$rent->id}}','end')" />&nbsp;完了</a>--}}
                                                                    {{--</li>--}}
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="memo-block">
                                                            <textarea id="other_{{$count_number}}">{{$rent->admin_memo}}</textarea>
                                                            <a onclick="taskreturnComplete({{$rent->options}},'{{count($rent->options)}}','{{$count_number}}', '{{$rent->id}}')">返車完了</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                              <?php $count_number++; $count_number_return++ ; ?>
                            @endforeach
                            <!--end return part-->
                            @if($rents_end_count > 0 || $returns_end_count > 0 )
                            <div class="completed-block">
                                <h2>完了したタスク</h2>
                            </div>
                            @endif
                            <!--start end rent part-->
                            <?php $count_number_rent_end = 0; ?>
                            @foreach($rents_end as $rent)
                                <div class="car-common-block completed" id="target_{{$count_number}}" >
                                    <div class="car-common-inner">
                                        <div class="number-block">
                                            <h2>{{$count_number_rent_end+1}}</h2>
                                        </div>
                                        <div class="common-content-block"  onclick="showHide('row_{{$count_number}}')">
                                            <div class="user-block">
                                                <h2 class="done">完了</h2>
                                                <h2>配車</h2>
                                                @if($rent->bookedcount > 0)
                                                    @if($rent->bookedcount == 1)
                                                        <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px;margin-left: 5px;">初
                                                        </h2>
                                                    @else
                                                        <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px 2px;margin-left: 3px;">リピート @if($rent->bookedcount > 1) {{$rent->bookedcount}} @endif
                                                        
														</h2>
                                                    @endif
                                                @endif
                                                
                                                 @if($rent->web_status == '3')
                                                
                                                @if($rent->bag_choosed == '1')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS フリスク</h2> @endif
                                                @if($rent->bag_choosed == '2')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS ぷっちょ</h2> @endif
                                                @if($rent->bag_choosed == '3')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS 酔い止め</h2> @endif
                                                 
                                                 @endif
                                                
                                                <h3><span class="user-name">
                                                {{$rent->last_name}} {{$rent->first_name}}
                                                        ({{$rent->fur_last_name}} {{$rent->fur_first_name}})
                                            </span> 様は
                                            <span class="time">
                                                {{$date_val}}
                                                {{--@if($task_date != 'today') {{date('Y-n-j', strtotime($rent->departing))}}@endif--}}
                                                {{date('G:i', strtotime($rent->departing))}}
                                            </span> 出発です。</h3>
                                            </div>
                                            <div id="row_{{$count_number}}" class="row user-block-content" @if($count_number > 4) style="display: none;" @endif>
                                                <div class="col-sm-5">
                                                    <div class="row">
                                                        <div class="col-xs-5 col-sm-5 col-md-4">
                                                            <div class="status-block">
                                                                <ul>
                                                                    @if($rent->web_status == '1')
                                                                        <li id="web_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>Web免許</span></li>
                                                                        @if(!empty($rent->driver_license_images))
                                                                            <span class="license" onclick="viewlicense({{json_encode($rent)}})">免</span>
                                                                        @endif
                                                                    @elseif($rent->web_status == '2')
                                                                        <li id="web_{{$count_number}}" class="status-done">
                                                                            <i class="fa fa-check-circle"></i><span>Web免許</span>
                                                                            @if(!empty($rent->driver_license_images))
                                                                                <span class="license" onclick="viewlicense({{json_encode($rent)}})">免</span>
                                                                            @endif
                                                                        </li>
                                                                        <li id="web_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>Web同意</span></li>
                                                                    @else
                                                                        {{--<li id="web_{{$count_number}}"><i></i><span>Web免許/同意</span></li>--}}
                                                                    @endif
                                                                    <input type="hidden" name="web_{{$count_number}}" value="{{$rent->web_status}}" />

                                                                    @if($rent->wait_status == '2')
                                                                        <li id="wait_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>送迎</span></li>
                                                                    @elseif($rent->wait_status == '1')
                                                                        <li id="wait_{{$count_number}}" ><i></i><span>送迎</span></li>
                                                                    @endif
                                                                    <input type="hidden" name="wait_{{$count_number}}" value="{{$rent->wait_status}}" />

                                                                    @if($rent->explain_status == '1')
                                                                        <li id="explain_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>来店/説明</span></li>
                                                                    @else
                                                                        <li id="explain_{{$count_number}}" ><i></i><span>来店/説明</span></li>
                                                                    @endif
                                                                    <input type="hidden" name="explain_{{$count_number}}" value="{{$rent->explain_status}}" />

                                                                    @if($rent->pay_status == '1')
                                                                        <li id="pay_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>Web決済</span></li>
                                                                    @else
                                                                        {{--<li id="pay_{{$count_number}}"><i></i><span>Web決済</span></li>--}}
                                                                    @endif
                                                                    <input type="hidden" name="pay_{{$count_number}}" value="{{$rent->pay_status}}" />

                                                                    @if($rent->pay_status == '0')
                                                                        @if($rent->shop_pay_status == '1')
                                                                            <li id="shop_pay_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>支払</span></li>
                                                                        @else
                                                                            <li id="shop_pay_{{$count_number}}"><i></i><span>支払</span></li>
                                                                        @endif
                                                                        <input type="hidden" name="shop_pay_{{$count_number}}" value="{{$rent->shop_pay_status}}" />
                                                                    @endif

                                                                    @if($rent->com_status == '1')
                                                                        <li id="end_{{$count_number}}" class="status-done" ><i class="fa fa-check-circle"></i><span>完了</span></li>
                                                                    @else
                                                                        <li id="end_{{$count_number}}"><i></i><span>完了</span></li>
                                                                    @endif
                                                                    @if($rent->other_status == '1')
                                                                        <li id="other_{{$count_number}}" class="status-done" ><i class="fa fa-question-circle" ></i><span>その他</span></li>
                                                                    @else
                                                                        <li id="other_{{$count_number}}"><i></i><span>その他</span></li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-4 col-sm-4 col-md-5 no-padding">
                                                            <div class="class-main">
                                                                <div class="car-left">
                                                                    <img src="{{URL::to('/')}}/images/cartask/car-icon.png" alt="">
                                                                </div>
                                                                <div class="car-right">
                                                                    <h2>{{$rent->class_name}}</h2>
                                                                    <h3>{{$rent->model_name}}</h3>
                                                                </div>
                                                            </div>
                                                            <div class="smoking-block">
                                                                <div class="smoking-left">
                                                                    <div class="smoking-content">
                                                                        <h2>{{$rent->car_number1}} {{$rent->car_number2}}</h2>
                                                                        <span>{{$rent->car_number3}}</span>
                                                                        <h3>{{$rent->car_number4}}</h3>
                                                                    </div>
                                                                </div>
                                                                <div class="smoking-right">
                                                                    @if($rent->smoke == 0)
                                                                        <img src="{{URL::to('/')}}/images/cartask/smoking-icon2.png" alt="">
                                                                    @else
                                                                        <img src="{{URL::to('/')}}/images/cartask/smoking-icon.png" alt="">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3 col-sm-3 no-padding">
                                                            <div class="date-block">
                                                                <ul>
                                                                    <li><span>お返し</span></li>
                                                                    <li>{{$rent->night}}泊{{$rent->day}}日</li>
                                                                    <li>
                                                                        {{date('n', strtotime($rent->returning))}}月{{date('d', strtotime($rent->returning))}}日
                                                                    </li>
                                                                    <li>{{date('G:i', strtotime($rent->returning))}}</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="pricing-main">
                                                        <h2><span class="outstanding-number coin">{{$rent->payment}}</span>円 <span class="outstanding">
                                                                @if($rent->shop_pay_status == '1')
                                                                    <span id="paid_status_{{$count_number}}" style="color: #22c03c;"> 支払い済 </span>
                                                                @elseif($rent->pay_status == '1')
                                                                    <span id="paid_status_{{$count_number}}" style="color: #22c03c;"> Web決済 </span>
                                                                @else
                                                                    <span id="paid_status_{{$count_number}}"> 未決済 </span>
                                                                @endif
                                                            </span>
                                                        </h2>
                                                        <ul>
                                                            <li class="@if($rent->basic_price == '0') dull @endif">基本：<span class="coin">{{$rent->basic_price}}</span>円</li>
                                                            <li class="@if($rent->insurance1 == '0') dull @endif">免責：<span class="coin">{{$rent->insurance1}}</span>円</li>
                                                            <li class="@if($rent->insurance2 == '0') dull @endif">ワ補：<span class="coin">{{$rent->insurance2}}</span>円</li>
                                                            <li class="@if($rent->etc_card == '0') dull @endif">ETC：<span class="coin">{{$rent->etc_card}}</span>円</li>
                                                            @if($shop_slug == 'naha-airport')
                                                                <li class="@if($rent->smart_driveout == '0') dull @endif">スマ：<span class="coin">{{$rent->smart_driveout}}</span>円</li>
                                                            @endif
                                                        </ul>
                                                        <ul>
                                                            <li class="@if($rent->child_seat == '0') dull @endif">チャ：<span class="coin">{{$rent->child_seat}}</span>円</li>
                                                            <li class="@if($rent->baby_seat == '0') dull @endif">ベビ：<span class="coin">{{$rent->baby_seat}}</span>円</li>
                                                            <li class="@if($rent->snow_tire == '0') dull @endif">スタ：<span class="coin">{{$rent->snow_tire}}</span>円</li>
                                                            <li class="@if($rent->junior_seat == '0') dull @endif">ジュ：<span class="coin">{{$rent->junior_seat}}</span>円</li>
                                                        </ul>
                                                        {{--<a onclick="refreshPrice({{json_encode($rent)}}, '{{$count_number}}')"><i class="fa fa-undo"></i></a>--}}
                                                    </div>
                                                    <div class="etc-block">
                                                        <h2>@if($rent->etc_card != '0')
                                                                <img src="{{URL::to('/')}}/images/cartask/etc-icon.png" width="45" alt="">
                                                            @else
                                                                <img src="{{URL::to('/')}}/images/cartask/etc-icon_grey.png" width="45" alt="">
                                                            @endif
                                                        </h2>
                                                        <h2>@if($rent->snow_tire != '0')
                                                                <img src="{{URL::to('/')}}/images/cartask/etc-icon2.png" width="45" alt="">
                                                            @else
                                                                <img src="{{URL::to('/')}}/images/cartask/etc-icon2_grey.png" width="45" alt="">
                                                            @endif
                                                        </h2>
                                                        <div class="etc-content">
                                                            <ul>
                                                                <li class="@if($rent->child_seat == '0') dull @endif">チャ
                                                                    @if(!empty($rent->child_seat_number)){{$rent->child_seat_number}}個@endif</li></li>
                                                                <li class="@if($rent->baby_seat == '0') dull @endif">ベビ
                                                                    @if(!empty($rent->baby_seat_number)){{$rent->baby_seat_number}}個@endif</li></li>
                                                                <li class="@if($rent->junior_seat == '0') dull @endif">ジュ
                                                                    @if(!empty($rent->junior_seat_number)){{$rent->junior_seat_number}}個@endif</li></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 status-meno-block">
                                                    <div class="col-lg-12" style="padding: 0px;">

                                                    </div>
                                                    <div class="memo-block">
                                                        <textarea id="other_{{$count_number}}" readonly >{{$rent->admin_memo}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $count_number++;$count_number_rent_end++; ?>
                            @endforeach
                            <!--end rent part-->
                            <!--start end return part-->
                            <?php $count_number_return_end = 0; ?>
                            @foreach($returns_end as $rent)
                                <div class="car-common-block completed  @if($service->comparebooking($rent,'tomorrow')) box-hide @endif " id="target_{{$count_number}}" >
                                    <div class="car-common-inner">
                                        <div class="number-block">
                                            <h2>{{$count_number_rent_end+1}}</h2>
                                        </div>
                                        <div class="common-content-block">
                                            <div class="user-block"  onclick="showHide('row_{{$count_number}}')" >
                                                <h2 class="done">完了</h2>
                                                <h2 style="background-color: #f5d7bf;color: #d87f39;border-color: #d87f39;">返車</h2>
                                                @if($rent->bookedcount > 0)
                                                    @if($rent->bookedcount == 0)
                                                        <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px;margin-left: 5px;">初
                                                        
														</h2>
                                                    @else
                                                        <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px 2px;margin-left: 3px;">リピート @if($rent->bookedcount > 1) {{$rent->bookedcount}} @endif
                                                        </h2>
                                                    @endif
                                                @endif
                                                
                                                 @if($rent->web_status == '3')
                                                
                                                @if($rent->bag_choosed == '1')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS フリスク</h2> @endif
                                                @if($rent->bag_choosed == '2')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS ぷっちょ</h2> @endif
                                                @if($rent->bag_choosed == '3')<h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS 酔い止め</h2> @endif
                                                
                                                 @endif
                                                 
                                                <h3><span class="user-name">
                                                {{$rent->last_name}} {{$rent->first_name}}
                                                        ({{$rent->fur_last_name}} {{$rent->fur_first_name}})
                                            </span> 様は <span class="time">
                                                {{$date_val}}
                                                {{--@if($task_date != 'today') {{date('Y-n-j', strtotime($rent->returning))}} @endif--}}
                                                {{date('G:i', strtotime($rent->returning))}}
                                            </span> 返却です。</h3>
                                            </div>
                                            <div id="row_{{$count_number}}" class="row user-block-content" @if($count_number > 4) style="display: none;" @endif>
                                                <div class="col-sm-5 col-md-4">
                                                    <div class="model-block">
														<ul class="taskbox_returnstyle01 taskbox-cartitlebox">
                                                            <li class="model-car"><img src="{{URL::to('/')}}/images/cartask/car-icon-small.png" alt=""></li>
                                                            <li class="car-class">{{$rent->class_name}}</li>
                                                            <li class="car-model">{{$rent->model_name}}</li>
                                                            <li><span>
                                                                    {{$rent->car_number3}} {{$rent->car_number4}}
                                                        </span></li>
                                                            <li class="model-smoking">
                                                                @if($rent->smoke == 0)
                                                                    <img src="{{URL::to('/')}}/images/cartask/smoking-icon-small.png" alt="">
                                                                @else
                                                                    <img src="{{URL::to('/')}}/images/cartask/smoking-icon-small2.png" alt="">
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="child-baby-block">
                                                        <ul>
                                                            <li>
                                                                @if($rent->etc_card != '0')
                                                                    <img src="{{URL::to('/')}}/images/cartask/etc-icon.png" alt="">
                                                                @else
                                                                    <img src="{{URL::to('/')}}/images/cartask/etc-icon_grey.png" alt="">
                                                                @endif
                                                            </li>
                                                            <li class="@if($rent->child_seat == '0') child-grey @else child-blue @endif">チャイ<span>
                                                                    @if(!empty($rent->child_seat_number)){{$rent->child_seat_number}}個 @endif</span></li>
                                                            <li class="@if($rent->baby_seat == '0') child-grey @else child-blue @endif">ベビー<span>
                                                                    @if(!empty($rent->baby_seat_number)){{$rent->baby_seat_number}}個 @endif</span></li>
                                                            <li class="@if($rent->junior_seat == '0') child-grey @else child-blue @endif ">ジュニ<span>
                                                                    @if(!empty($rent->junior_seat_number)){{$rent->junior_seat_number}}個 @endif</span></li>
                                                            <li>
                                                                @if($rent->snow_tire != '0')
                                                                    <img src="{{URL::to('/')}}/images/cartask/etc-icon2.png" alt="">
                                                                @else
                                                                    <img src="{{URL::to('/')}}/images/cartask/etc-icon2_grey.png" alt="">
                                                                @endif
                                                            </li>
                                                        </ul>
                                                        <div class="m-t-sm">
                                                            <div class="list-inline">
                                                                <li class="status-done" style="margin-right: 10px;">貸出後のメーター： </li>
                                                                <li class="status-done ">
                                                                    <input type="text" name="miles_{{$count_number}}" readonly value="{{$rent->before_miles}}" style="width:50px;"  /> km
                                                                </li>
                                                             </div>
                                                            <div style="height: 10px;">
                                                                {{--<li class="status-done" style="margin-left: 120px;"><span class="coin">{{$rent->before_miles}}</span>Km</li>--}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5 col-md-6">
                                                    <div class="alert-block">
                                                        <a href="#" target="_blank"><h2>この車両は明日 {{date('H:m', strtotime($rent->departing))}} に使用予定（<span>オプション有</span>）<img src="{{URL::to('/')}}/images/cartask/link-icon.png" alt=""></h2></a>
                                                    </div>
                                                    <div class="status-block-inline">
                                                        <ul>
                                                            @if($rent->return_status == '1')
                                                                <li id="return_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>返車処理</span></li>
                                                            @else
                                                                <li id="return_{{$count_number}}" ><i></i><span>返車処理</span></li>
                                                            @endif
                                                            <input type="hidden" name="return_{{$count_number}}" value="{{$rent->return_status}}" />
                                                            @if($rent->clean_status == '1')
                                                                <li id="clean_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>車内清掃</span></li>
                                                            @else
                                                                <li id="clean_{{$count_number}}" ><i></i><span>車内清掃</span></li>
                                                            @endif
                                                            <input type="hidden" name="clean_{{$count_number}}" value="{{$rent->clean_status}}" />
                                                            @if($rent->mile_status == '1')
                                                                <li id="mile_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>走行距離</span></li>
                                                            @else
                                                                <li id="mile_{{$count_number}}" ><i></i><span>走行距離</span></li>
                                                            @endif
                                                            <input type="hidden" name="mile_{{$count_number}}" value="{{$rent->mile_status}}" />

                                                            {{--@if($rent->wash_status == '1')--}}
                                                                {{--<li id="wash_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>洗車</span></li>--}}
                                                            {{--@else--}}
                                                                {{--<li id="wash_{{$count_number}}" ><i></i><span>洗車</span></li>--}}
                                                            {{--@endif--}}
                                                            <input type="hidden" name="wash_{{$count_number}}" value="{{$rent->wash_status}}" />
                                                            @if($rent->end_status == '1')
                                                                <li id="end_{{$count_number}}" class="status-done"><i class="fa fa-check-circle"></i><span>完了</span></li>
                                                            @else
                                                                <li id="end_{{$count_number}}" ><i></i><span>完了</span></li>
                                                            @endif
                                                            <input type="hidden" name="end_{{$count_number}}" value="{{$rent->end_status}}" />
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 status-meno-block ">
                                                    <div class="memo-block">
                                                        <textarea id="other_{{$count_number}}" readonly >{{$rent->admin_memo}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $count_number++; $count_number_return_end++ ; ?>
                            @endforeach
                            <!--end return part-->
                            <div style="height: 30px;"> </div>
                        </div>
                    </div>
                </div>
            </div>
           <!--end part-->
        </div>
    </div>
    <!--price modal-->
    <div class="modal fade" id="priceModal" tabindex="-1" role="dialog" aria-labelledby="priceModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceModalLabel">Price</h5>
                    <button type="button" class="close" style="margin-top: -20px;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal_option">
                    <!--option html-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    <button type="button" class="btn btn-primary" onclick="changePrice()">保存</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="optionModal" tabindex="-1" role="dialog" aria-labelledby="optionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionModalLabel">オプションがあります！ 忘れていませんか？</h5>
                    <button type="button" class="close" style="margin-top: -20px;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="taskcomplete">
                        <!--if there are more option registered than cuurent options, dispaly with condition-->
                    </div>
                    <div id="emptyoption">
                        完了でしたらチェックをお願いします！
                    </div>
                </div>
                <div class="modal-header">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">忘れるところでした</button>
                    <button type="button" class="btn btn-primary" onclick="complete()">手配済です！</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal return -->
    <div class="modal fade" id="returnoptionModal" tabindex="-1" role="dialog" aria-labelledby="returnoptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnoptionModalLabel">オプションがあります！ 忘れていませんか？</h5>
                    <button type="button" class="close" style="margin-top: -20px;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="returntaskcomplete">
                        <!--if there are more option registered than cuurent options, dispaly with condition-->
                    </div>
                    <div id="returnemptyoption">
                        完了でしたらチェックをお願いします！
                    </div>
                </div>
                <div class="modal-header">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">忘れるところでした</button>
                    <button type="button" class="btn btn-primary" onclick="returncomplete()">手配済です！</button>
                </div>
            </div>
        </div>
    </div>
    <!--view license-->
    <!--price modal-->
    <div class="modal fade" id="licenseModal" tabindex="-1" role="dialog" aria-labelledby="licenseModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="licenseModalLabel">運転者の免許証</h5>
                    <button type="button" class="close" style="margin-top: -20px;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >
                    <!---->
                    <div id="drivers_licenses">

                    </div>
                    <!---->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                </div>
            </div>
        </div>
    </div>
    <!--no select check box error modal-->
    <div class="modal fade" id="noselectModal" tabindex="-1" role="dialog" aria-labelledby="noselectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<h5 class="modal-title" id="returnoptionModalLabel">タスクのステータス</h5>--}}
                    <button type="button" class="close" style="margin-top: -10px;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <label><strong style="font-size: 14px;">必須のタスクリストを完了させてください。</strong> </label>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/jquery.zoom.js"></script>
    <style>
        .dropdown-menu {
            width:100%;
            min-width: 100px;
            padding-bottom: 0px;
        }
        .dropdown-menu > li > a {
            padding: 0px;
            line-height: 0px;
        }
        .dropdown-menu > li > a:focus {
            outline: none;
        }
        li{
            list-style: none;
        }
        .license {
            border: 1px solid #003157;
            background-color: #eeeeee;
            font-size: 12px;
            color: #003157;
            font-weight: 300;
            border-radius: 2px;
            padding: 0px 5px 0px 5px;
        }
        /* styles unrelated to zoom */
        * { border:0; margin:0; padding:0; }
        p { position:absolute; top:3px; right:28px; color:#555; font:bold 13px/1 sans-serif;}

        /* these styles are for the demo, but are not required for the plugin */
        .zoom {
            display:inline-block;
            position: relative;
        }

        /* magnifying glass icon */
        .zoom:after {
            content:'';
            display:block;
            width:33px;
            height:33px;
            position:absolute;
            top:0;
            right:0;
            /*background:url(icon.png);*/
        }

        .zoom img {
            display: block;
        }

        .zoom img::selection { background-color: transparent; }
        #emptyoption{
            color: #fc2c25;
            font-weight: bold;
            font-size: 14px;
            display: none;
        }
        #returnemptyoption{
            color: #fc2c25;
            font-weight: bold;
            font-size: 14px;
            display: none;
        }
    </style>
    <script>
		function print_booking(booking_id){
			//alert(booking_id);
			window.open("{!! URL::to('/booking/printtask') !!}/"+booking_id, "BookingPrintWindow", "width=500,height=500");
		}
		
		function printalltask(){
            var shop_id = $('#shopid').val();
            //alert(shop_id);
			window.open("{!! URL::to('/booking/printalltask') !!}/"+shop_id+"?task_date="+task_date, "BookingAllPrintWindow", "width=500,height=500");
		}
		
		function printalltaskempty(){
            var shop_id = $('#shopid').val();
            //alert(shop_id);
			window.open("{!! URL::to('/booking/printalltaskempty') !!}/"+shop_id+"?task_date="+task_date, "BookingAllPrintWindow", "width=500,height=500");
		}
        function numberWithCommas(number) {
            var parts = number.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }
        //run to format
        $(document).ready(function() {
            $(".coin").each(function() {
                var num = $(this).text();
                var commaNum = numberWithCommas(num);
                $(this).text(commaNum);
            });
            $("#priceModal").draggable({
                handle: ".modal-header"
            });
			$('html, body').animate({
                scrollTop: $("#{{$target_object}}").offset().top-400
            }, 300);
            var target_value = '{{$target_object}}';
            var target_number = target_value.split("_");
            $('#row_'+target_number[1]).show();
            //close complete message after  5 seconds
            setTimeout(
                function()
                {
                    $('#completed_message').hide();
                }, 5000);
            //keydown for miles with number
            $('.miles_number').keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                            // Allow: Ctrl+A, Command+A
                        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                            // Allow: home, end, left, right, down, up
                        (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

        });
        //change status on rightside
        var booking_id  = '0';
        var object_index = '0';
        var selected_price = 0;
        var selected_number = 0;
        var target_object = 'target_0';

        function changeStatus(e, number, id){
            e.stopPropagation();
            var value = '0';
            var target = $(e.currentTarget),
                val    = target.attr( 'data-value' ),
                inp    = target.find( 'input' );
            var check_val = inp.prop( 'checked');
            if(check_val == false) {
                inp.prop('checked', true );
                $("#"+val+"_"+number+" i").addClass('fa fa-check-circle');
                $("#"+val+"_"+number).addClass('status-done');
                $('input[name="'+val+'_'+number+'"]').val('1');
                value = '1';
                if(val == 'wait') {
                    value = '2';
                    $('input[name="'+val+'_'+number+'"]').val('2');
                }
            }else {
                inp.prop( 'checked', false );
                $("#"+val+"_"+number+" i").removeClass('fa fa-check-circle');
                $("#"+val+"_"+number).removeClass('status-done');
                $('input[name="'+val+'_'+number+'"]').val('0');
                value = '0';
                if(val == 'wait') {
                    value = '1';
                    $('input[name="'+val+'_'+number+'"]').val('1');
                }
            }
            //send status to server
            var url = '{{URL::to('/')}}/booking/savestatus';
            var token = $('input[name="_token"]').val();
            var data = [];
            data.push({name: 'booking_id', value: id},
                        {name: '_token', value: token},
                        {name: 'status_name', value:val },
                        {name: 'status_value', value: value });
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    var rec = content.data;
                    if(val == 'shop_pay' && value == '1' ) {
                        $('#paid_status_'+number).html('支払い済');
                        $('#paid_status_'+number).css('color','#22c03c');
                    }else if(val == 'shop_pay' && value == '0' ){
                        var webpay = $('input[name="pay_'+number+'"]').val();
                        if(webpay == '1') {
                            $('#paid_status_' + number).html('Web決済');
                            $('#paid_status_' + number).css('color', '#22c03c');
                        }else {
                            $('#paid_status_' + number).html('未決済');
                            $('#paid_status_' + number).css('color', '#333');
                        }
                    }
                }
            });
            //
        }
        //check when cick input check box
        function changeStatus_input(e,number, id, val){
            e.stopPropagation();
            var value = '0';
            var input = 'input[name="'+val+'_'+number+'"]';
            var check_val = $(input).val();
            if(val == 'wait'){
                if (check_val == '1') {
                    $(this).prop('checked', true);
                    $("#" + val + "_" + number + " i").addClass('fa fa-check-circle');
                    $("#" + val + "_" + number).addClass('status-done');
                    $('input[name="' + val + '_' + number + '"]').val('2');
                    value = '2';
                } else {
                    $(this).prop('checked', false);
                    $("#" + val + "_" + number + " i").removeClass('fa fa-check-circle');
                    $("#" + val + "_" + number).removeClass('status-done');
                    $('input[name="' + val + '_' + number + '"]').val('1');
                    value = '1';
                }
            }else {
                if (check_val == '0') {
                    $(this).prop('checked', true);
                    $("#" + val + "_" + number + " i").addClass('fa fa-check-circle');
                    $("#" + val + "_" + number).addClass('status-done');
                    $('input[name="' + val + '_' + number + '"]').val('1');
                    value = '1';
                    if (val == 'wait') {
                        value = '2';
                        $('input[name="' + val + '_' + number + '"]').val('2');
                    }
                } else {
                    $(this).prop('checked', false);
                    $("#" + val + "_" + number + " i").removeClass('fa fa-check-circle');
                    $("#" + val + "_" + number).removeClass('status-done');
                    $('input[name="' + val + '_' + number + '"]').val('0');
                    value = '0';
                    if (val == 'wait') {
                        value = '1';
                        $('input[name="' + val + '_' + number + '"]').val('1');
                    }
                }
            }
            //send status to server
            var url = '{{URL::to('/')}}/booking/savestatus';
            var token = $('input[name="_token"]').val();
            var data = [];
            data.push({name: 'booking_id', value: id},
                    {name: '_token', value: token},
                    {name: 'status_name', value:val },
                    {name: 'status_value', value: value });
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    var rec = content.data;
                    if(val == 'shop_pay' && value == '1' ) {
                        $('#paid_status_'+number).html('支払い済');
                        $('#paid_status_'+number).css('color','#22c03c');
                    }else if(val == 'shop_pay' && value == '0' ){
                        var webpay = $('input[name="pay_'+number+'"]').val();
                        if(webpay == '1') {
                            $('#paid_status_' + number).html('Web決済');
                            $('#paid_status_' + number).css('color', '#22c03c');
                        }else {
                            $('#paid_status_' + number).html('未決済');
                            $('#paid_status_' + number).css('color', '#333');
                        }
                    }
                }
            });
            //
        }
        //change memo typing event
        function changeOther(e,number, booking_id){
            var target = $(e.currentTarget);
            if(target.val() == "")
                $("#other_"+number+" i").removeClass('fa fa-question-circle');
            else
                $("#other_"+number+" i").addClass('fa fa-question-circle');
            var url = '{{URL::to('/')}}/booking/savememo';
            var token = $('input[name="_token"]').val();
            var data = [];
            var other_status = '0';
            if(target.val() == "") other_status = '0';
            else other_status = '1';
            data.push({name: 'booking_id', value: booking_id},
                    {name: '_token', value: token},
                    {name: 'admin_memo', value: target.val() },
                    {name: 'other_status', value: other_status });
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: true,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                }
            });

        }
        //task complete event
        function taskComplete(lists,count,index, rent_id){
            $('#emptyoption').hide();
            object_index = index;
            booking_id = rent_id;
            var current_flag = true ;
//            var web_status = $('input[name="web_'+object_index+'"]').val();
//            if(web_status != '1' || web_status != '2') current_flag = false;
            var wait_status = $('input[name="wait_' + object_index + '"]').val();
            if(parseInt(wait_status) > 0) {
                if(wait_status != '3' ) {
                    if (wait_status != '2') current_flag = false;
                }
            }
            var explain_status = $('input[name="explain_'+object_index+'"]').val();
            if(explain_status != '1') current_flag = false;
            var pay_status = $('input[name="pay_'+object_index+'"]').val();
            var shop_pay_status = $('input[name="shop_pay_'+object_index+'"]').val();
            if(shop_pay_status != '1') {
                if(pay_status !='1'){
                    current_flag = false;
                }
            }
            if(current_flag == true) {
                $('#optionModal').modal('hide');
            }else {
                $('#noselectModal').modal('show');//display error
                return;
            }
            var option_hrml = '';
            $('#taskcomplete').html = "";
            if(count == 1) {
                lists.forEach(function (el) {//
                    if(el.index == '38' || el.index == '106' ) count = 0; //except smart drive checking
                });
            }
            if(count > 0 ) { //count is number count of options
                //child seat(22), baby seat(23), junior seat(24), etc cards(25), snow tire(26)
                lists.forEach(function (el) {
//                    var checked = 'checked';
//                    var disabled = 'disabled';
//                    if(el.index == '0') {
                        checked = '';
                        disabled = '';
                    //}
                    if(el.index != '38' || el.index != '106' ) //except smart driveout option service
                        option_hrml +='<li><label style="cursor:pointer"><input type="checkbox" '+checked+' '+disabled+' />&nbsp;<span style="position: relative; left:10px; top: -2px;">'+el.option_name+' '+el.option_number+'個</span></label></li>';
                });
                $('#taskcomplete').html(option_hrml);
                $('#optionModal').modal('show');
            }else{
                complete();
            }
        }
       //compalte task
        function complete(){
            var checked_flag = true;
            $('#taskcomplete input[type=checkbox]').each(function(index, data){
                var check= data.checked;
                if(check == false) checked_flag = false;
            });
            if(checked_flag == false) {
               $('#emptyoption').show();
               return;
            }
            completeStatus(booking_id, 'depart', object_index);
        }
        //return task complete
        function taskreturnComplete(lists,count,index, return_id){
            $('#returnemptyoption').hide();
            object_index = index;
            booking_id   = return_id;
            var current_flag = true ;
            var return_status = $('input[name="return_'+object_index+'"]').val();
            if(return_status != '1') current_flag = false;
            var clean_status = $('input[name="clean_'+object_index+'"]').val();
            if(clean_status != '1') current_flag = false;
            /*var wash_status = $('input[name="wash_'+object_index+'"]').val();
            if(wash_status != '1') current_flag = false;
            */
            var miles = $('input[name="miles_' + object_index + '"]').val();
            var before_miles = $('input[name="before_miles_' + object_index + '"]').val();
            if(parseInt(before_miles) >= parseInt(miles)) current_flag = false;
            if(current_flag == true) {
                $('#returnoptionModal').modal('hide');
            }else {
                $('#noselectModal').modal('show');
                return;
            }

            var option_hrml = '';
            if(count > 0 ) { //count is number count of options
                //child seat(22), baby seat(23), junior seat(24), etc cards(25), snow tire(26)
                lists.forEach(function (el) {
                    checked = '';
                    disabled = '';
                    if(el.index != '38') //except samrt driveoption servicew
                    option_hrml +='<li><label style="cursor:pointer"><input type="checkbox" '+checked+' '+disabled+' />&nbsp;<span style="position: relative; left:10px; top: -2px;">'+el.option_name+' '+el.option_number+'個</span></label></li>';
                });
                $('#returntaskcomplete').html(option_hrml);
                //$('#returnoptionModal').modal('show');
                returncomplete();
            }else {
                returncomplete();
            }
        }
        //return complete task
        function returncomplete(){
            /*var checked_flag = true;
            $('#returntaskcomplete input[type=checkbox]').each(function(index, data){
                var check= data.checked;
                if(check == false) checked_flag = false;
            });
            if(checked_flag == false) {
                $('#returnemptyoption').show();
                return;
            }*/
            completeStatus(booking_id, 'return', object_index);
        }

        //refreshprice
        function refreshPrice(book, number){
            target_object = 'target_'+number;
            $('#priceModal').modal('show');
            price_items = [];
            price_items.push({name: 'booking_id', value: book.id});
            price_items.push({name: 'basic_price', value: book.basic_price});
            var html ='<table class="table">';
                html +='<thead><tr>';
                html +='<th scope="col">項目</th>';
                html +='<th scope="col">料金</th>';
                html +='<th scope="col">個数</th>';
                html +='<th scope="col"></th>';
                html +='</tr></thead><tbody>';
                var ins1_icon = '';
                if(book.insurance1 != 0) {
                    ins1_icon = 'fa fa-check-circle';
                    price_items.push({name: 'insurance1', value: book.basic_insurance1});
                }
                html +='<tr class="ins1"><td><i class="'+ins1_icon+' ins1_icon" style="color:#0fa12a"></i> 免責補償</td>';
                html +='<td class="modal_price">'+book.basic_insurance1+'</td>'
                html +='<td>'+book.day+'</td>';
                html +='<td>';
                html +='<a class="btn btn-xs btn-success" onclick="changeOption(\'insurance\',\'add\',\'ins1\',\'1\',\''+book.id+'\')" >';
                html +='<span>追加</span></a>&nbsp;';
                html +='<a class="btn btn-xs btn-warning" onclick="changeOption(\'insurance\',\'delete\',\'ins1\',\'1\',\''+book.id+'\')">';
                html +='<span>削除</span></a></td></tr>';

                var ins2_icon = '';
                if(book.insurance2 != 0) {
                    ins2_icon = 'fa fa-check-circle';
                    price_items.push({name: 'insurance2', value: book.basic_insurance2});
                }
                html +='<tr class="ins2"><td><i class="'+ins2_icon+' ins2_icon" style="color:#0fa12a" ></i> ワイド免責</td>';
                html +='<td class="modal_price">'+book.basic_insurance2+'</td>'
                html +='<td>'+book.day+'</td>';
                html +='<td>';
                html +='<a class="btn btn-xs btn-success" onclick="changeOption(\'insurance\',\'add\',\'ins2\',\'2\',\''+book.id+'\')" >';
                html +='<span>追加</span></a>&nbsp;';
                html +='<a class="btn btn-xs btn-warning" onclick="changeOption(\'insurance\',\'delete\',\'ins2\',\'2\',\''+book.id+'\')" >';
                html +='<span>削除</span></a></td></tr>';
                var count = 0;
                @foreach($caroptions as $op)
                    var icon = '';
                    selected_price = 0;
                    selected_number = 0;
                    var compareoption = compareOption('{{$op->id}}',book.options);
                    if(compareoption == true) {
                        icon = 'fa fa-check-circle';
                        price_items.push({name:'option_{{$op->id}}', value: selected_price+"_"+selected_number });
                    }
                    html +='<tr class="op_'+count+'"><td><i class="'+icon+' op_'+count+'_icon" style="color:#0fa12a" ></i> {{$op->name}}</td>';
                    html +='<td class="modal_price" >{{$op->price}}</td>';
                    @if($op->google_column_number == "38")
                        html += '<td><input type="number"  name="refresh_op_' + count + '" onkeyup="changeicon(\'op_' + count + '\',\'{{$op->id}}\')"  class="form-control" value="1" readonly style="width:120px"></td>';
                    @else
                        html += '<td><input type="number"  name="refresh_op_' + count + '" onkeyup="changeicon(\'op_' + count + '\',\'{{$op->id}}\')"  class="form-control" value="' + selected_number + '" style="width:120px"></td>';
                    @endif
                    html +='<td>';
                    html +='<a class="btn btn-xs btn-success" onclick="changeOption(\'option\',\'add\',\'op_'+count+'\',\'{{$op->id}}\',\''+book.id+'\')" >';
                    html +='<span>追加</span></a>&nbsp;';
                    html +='<a class="btn btn-xs btn-warning" onclick="changeOption(\'option\',\'delete\',\'op_'+count+'\',\'{{$op->id}}\',\''+book.id+'\')">';
                    html +='<span>削除</span></a></td></tr>';
                    count++;
                @endforeach

                html +='</tbody></table>';
            $('#modal_option').html(html);
        }
        //compare option from current object
        function compareOption(option_id, cu_options){
            var flag = false;
            if(cu_options.length > 0) {
                for(var i =0; i < cu_options.length ; i ++) {
                    if(option_id == cu_options[i].option_id) {
                        flag = true ;
                        selected_price = cu_options[i].option_price;
                        selected_number = cu_options[i].option_number;
                        if(selected_number != 0 && selected_price !=0 )selected_price = selected_price/selected_number;
                        break;
                    }
                }
            }
            return flag;
        }
        //event to change option in modal
        var price_items = [];
        function changeOption(item, cond, name ,option_id, book_id) {
            var price = 0;
            booking_id  = book_id;
            $('#priceModal .'+name+' td').each(function() {
                var class_name = $(this).attr('class');
                if(class_name == 'modal_price') price = $(this).text();
            });
            var number = 1;
            if(item == 'option') {
                $('#priceModal .' + name + ' td input').each(function () {
                    number = $(this).val();
                });
            }
            if(item == "insurance") {
                if(cond == 'add') { //add to array
                    if(option_id == '1') {
                        price_items.push({name: 'insurance1', value: price});
                    }
                    if(option_id == '2') {
                        price_items.push({name: 'insurance1', value: price});
                        $(".ins1_icon").addClass("fa fa-check-circle");
                        price_items.push({name: 'insurance2', value: price});
                    }
                    $("."+name+"_icon").addClass("fa fa-check-circle");
                }else {//delete from array
                    var removeItem = '';
                    if(option_id == '1') {
                        removeItem = "insurance1";
                    }
                    if(option_id == '2') {
                        removeItem = "insurance1";
                        $(".ins1_icon").removeClass("fa fa-check-circle");
                        price_items = jQuery.grep(price_items, function(value) {
                            return value.name != removeItem;
                        });
                        removeItem = "insurance2";
                    }
                    price_items = jQuery.grep(price_items, function(value) {
                        return value.name != removeItem;
                    });
                    $("."+name+"_icon").removeClass("fa fa-check-circle");
                }
            }
            if(item == "option") {
                if(cond == 'add') {
                     //var price_ = number * price;
                     var price_ = price;
                     price_items.push({name: 'option_'+option_id , value: price_+"_"+number});
                    $("."+name+"_icon").addClass("fa fa-check-circle");
                }else {
                    removeItem = 'option_'+option_id;
                    price_items = jQuery.grep(price_items, function(value) {
                        return value.name != removeItem;
                    });
                    $("."+name+"_icon").removeClass("fa fa-check-circle");
                }
            }
        }
        //change icon when change option number in modal
        function changeicon(name, option_id){
            removeItem = 'option_'+option_id;
            price_items = jQuery.grep(price_items, function(value) {
                return value.name != removeItem;
            });
            $("."+name+"_icon").removeClass("fa fa-check-circle");
            // input numner event || miles number format when keypress
            $('input[name="refresh_'+name+'"]').keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                            // Allow: Ctrl+A, Command+A
                        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                            // Allow: home, end, left, right, down, up
                        (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }
        //send to server with change price
        function changePrice() {
            var url = '{{URL::to('/')}}/booking/changeoption';
            var token = $('input[name="_token"]').val();
            var data = [];
            var shop_id = '{{$shop_id}}';
            var task_date = '{{$task_date}}';
            var category  = '{{$category}}';
            data.push({name: 'booking_id', value: booking_id},
                    {name: '_token', value: token}
            );
            data = $.merge(data,price_items);
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    var rec = content.data;
                    if(rec == 'true') {
                        $('#search input[name="task_Date"]').val(task_date);
                        $('#search input[name="category"]').val(category);
                        $('#search input[name="shop_id"]').val(shop_id);
                        $('#search input[name="target_object"]').val(target_object);
                        $('#search').submit();
                        //window.location.href = "{{URL::to('/')}}/booking/task?target_object="+target_object+"&shop_id="+shop_id+"&task_date="+task_date+"&category="+category;
                    }
                }
            });
        }
        //booking and rent status update
        function completeStatus(booking_id,task , index){
            var url = '{{URL::to('/')}}/booking/completestatus';
            var token = $('input[name="_token"]').val();
            var data = [];
            var miles = 0;
            var before_miles = 0;
            var shop_id = '{{$shop_id}}';
            var category = '{{$category}}';
            var task_date = '{{$task_date}}';
            if(task == 'return')
            {
                miles = $('input[name="miles_' + index + '"]').val();
                before_miles = $('input[name="before_miles_' + index + '"]').val();
            }
            if(miles ==  0 && task == 'return' ) {
                $('#noselectModal').modal('show'); //display error
                return;
            }
            data.push({name: 'booking_id', value: booking_id},
                    {name: '_token', value: token},
                    {name: 'miles', value: miles},
                    {name: 'before_miles', value: before_miles},
                    {name: 'task', value: task}
            );
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    var rec = content.data;
                    $('#search input[name="task_Date"]').val(task_date);
                    $('#search input[name="category"]').val(category);
                    $('#search input[name="shop_id"]').val(shop_id);
                    $('#search input[name="cflag"]').val('1');
                    $('#search').submit();
                    //window.location.href = "{{URL::to('/')}}/booking/task?shop_id ="+shop_id+"&cflag=1&task_Date="+task_date+"&category="+category;
                }
            });
        }
        //search
        var task_date = '{{$task_date}}';
        var category = '{{$category}}';
        function searchBook(status, cond) {
            if(cond == 'date') {
                task_date = status;
            }
            if(cond == 'category') {
                category = status;
            }
            var shop_id = $('#shopid').val();
            $('#search input[name="task_date"]').val(task_date);
            $('#search input[name="category"]').val(category);
            $('#search input[name="shop_id"]').val(shop_id);
            $('#search input[name="cflag"]').val('0');
            $('#search').submit();
        }
        //toggle for show and hide
        function showHide(name){
           $( "#"+name ).slideToggle("slow", function() {
               // Animation complete.
           });
        }
        //Check miles
        function checkMiles(e , index){
            var target = $(e.currentTarget);
            var miles = $('input[name="miles_' + index + '"]').val();
            var before_miles = $('input[name="before_miles_' + index + '"]').val();
            if(parseInt(miles) > parseInt(before_miles)) {
                $("#mile_"+index+" i").addClass('fa fa-check-circle');
                $("#mile_"+index).addClass('status-done');
            }else {
                $("#mile_"+index+" i").removeClass('fa fa-check-circle');
                $("#mile_"+index).removeClass('status-done');
            }
        }
        //view license
        function viewlicense(book) {
            var licenses = book.driver_license_images;
            $('#licenseModal').modal('show');
            var html = '';
            for(var i = 0; i< licenses.length ; i++) {
                html += '<div class="row">';
                html += '<div class="imgInput col-md-6 col-sm-6 col-xs-12">';
                html += '<div class="sec-text"><p><span>表面</span></p></div>';
                html += '<span class="zoom" id="license_surface_'+i+'"><img src="' + licenses[i].representative_license_surface + '" alt="" class="imgView img-responsive"> </sapn>';
                html += '</div>';
                html += '<div class="imgInput col-md-6 col-sm-6 col-xs-12">';
                html += '<div class="sec-text"><p><span>裏面</span></p></div>';
                html += '<span class="zoom" id="license_back_'+i+'"><img src="' + licenses[i].representative_license_back + '" alt="" class="imgView img-responsive"> </sapn>'
                html += '</div>';
                html += '</div>';
            }
            $('#drivers_licenses').html(html);
            for(var i = 0; i< licenses.length ; i++) {
                $('#license_surface_'+i).zoom({on: 'click'});
                $('#license_back_'+i).zoom({on: 'click'});
            }
        }
        //close completed message
        function closemessage(){
            $('#completed_message').hide();
        }
        //change return time from return rent
        var change_return_book_id = '';
        var change_return_return_date = '';
        var change_count_number = 0;
        var flag = 0;
        //change return time
        function changeReturnTime(e, book, time, index){
            if(flag == 0) {
                $('#change_return_' + index).show();
                flag = 1 ;
            }else{
                $('#change_return_' + index).hide();
                flag = 0 ;
            }
            e.stopPropagation();
            var book_id = book.id;
            var phone = book.phone;
            change_return_book_id = book_id;
            change_return_return_date = book.returning;
            change_count_number = index,
            $('#change_return_tel_'+index).html(phone);
            $('#change_return_time_'+index).val(time);
        }
        //save return time
        function saveReturnTime(e){
            e.stopPropagation();
            var url = '{{URL::to('/')}}/bookingtask/changereturn';
            var token = $('input[name="_token"]').val();
            var return_time = $('#change_return_time_'+change_count_number).val();
            var data = [];
            data.push({name: 'booking_id', value: change_return_book_id},
                    {name: '_token', value: token},
                    {name: 'returning', value: change_return_return_date},
                    {name: 'return_time', value: return_time}
            );
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                   $('#changed_return_time_'+change_count_number).text(return_time);
                   $('#change_return_'+change_count_number).hide();
                }
            });
        }
        //change depart time
        function changeDepartTime(e, book, time, index){
            if(flag == 0) {
                $('#change_return_' + index).show();
                flag = 1 ;
            }else{
                $('#change_return_' + index).hide();
                flag = 0 ;
            }
            e.stopPropagation();
            var book_id = book.id;
            var phone = book.phone;
            change_return_book_id = book_id;
            change_return_return_date = book.departing;
            change_count_number = index,
                    $('#change_return_tel_'+index).html(phone);
            $('#change_return_time_'+index).val(time);
        }
        //save depart time
        function savedepartTime(e){
            e.stopPropagation();
            var url = '{{URL::to('/')}}/bookingtask/changedepart';
            var token = $('input[name="_token"]').val();
            var return_time = $('#change_return_time_'+change_count_number).val();
            var data = [];
            data.push({name: 'booking_id', value: change_return_book_id},
                    {name: '_token', value: token},
                    {name: 'departing', value: change_return_return_date},
                    {name: 'depart_time', value: return_time}
            );
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    $('#changed_return_time_'+change_count_number).text(return_time);
                    $('#change_return_'+change_count_number).hide();
                    location.reload();

                }
            });
        }
        //prevent other event
        function return_select(e) {
            e.stopPropagation();
        }


    </script>
@endsection