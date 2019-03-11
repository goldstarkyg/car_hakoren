{{--@extends('layouts.adminapp')--}}
@extends('layouts.adminapp_calendar')

@section('template_title')
    予約一覧
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
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
              <div class="row">
                <div class="col-md-2">
                    <h2>予約一覧</h2>
                </div>
                <div class="col-md-10 m-t-sm">
                   <div class="row">
                        <div class="col-md-9">
                            <div class="all_tabel_wrap">
                            <label class="m-l-sm title_width">店舗</label>
                            <label class="m-l-sm all_width">

                                <select name="shop" id="shop" class="form-control">
                                    <option value="0" @if($search_shop == '0') selected @endif>全て</option>
                                    @foreach($shops as $shop)
                                        <option value="{{$shop->id}}" @if($search_shop == $shop->id) selected @endif>{{$shop->name}}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="all_width">
                            <select name="cond" id="cond" class="form-control" >
                                <option value="submit_date" @if($condition == 'submite_date') selected @endif>送信日</option>
                                <option value="depart_return" @if($condition == 'depart_return') selected @endif >出/返</option>
                                <option value="cancel" @if($condition == 'cancel') selected @endif >キャンセル</option>
                            </select>
                            </label>
                            <label class="m-l-sm title_width">対象期間</label>
                            <label class="m-l-sm all_width">
                                <input type="text" name="daterange" readonly value="{{$search_date}}" class="form-control input_all" style="max-width:190px;"/>
                            </label>
                            <label class="all_width2">
                                <!--https://laravelcode.com/post/how-to-convert-html-to-pdf-laravel-54-->
                                <!--<a href="{{ route('generate-pdf',['download'=>'pdf']) }}" class="m-l-md">
                                    <i class="fa fa-file-pdf-o" style="font-size:14px;color:red"></i> [ 本日 ]
                                </a>-->
                                <a href="javascript:void(0);" onclick="javascript:printPdf('today', '{{$search_shop}}')" class="m-l-md">
                                    <i class="fa fa-print" aria-hidden="true" style="font-size:14px;color:#9a8989"></i> [ 本日配車表 ]
                                </a>
                                <a href="javascript:void(0);" onclick="javascript:printPdf('tom', '{{$search_shop}}')" class="m-l-md">
                                    <i class="fa fa-print" aria-hidden="true" style="font-size:14px;color:#9a8989"></i> [ 明日配車表 ]
                                </a>
                            </label>
                            </div>
                            {{--<label>--}}
                                {{--<select name="portal_cond" id="portal_cond" class="form-control" >--}}
                                    {{--<option value="">Select</option>--}}
                                    {{--<option value="all" @if($portal_cond == 'all') selected @endif >Google</option>--}}
                                {{--</select>--}}
                            {{--</label>--}}
                        </div>
                    </div>
                    {{--<label>--}}
                    {{--<a href="{{URL::to('/')}}/booking/all" class="list-group-item @if($subroute == 'all') active_menu @endif " data-parent="#MainMenu">--}}
                    {{--<label>全て</label>--}}
                    {{--</a>--}}
                    {{--</label>--}}
                    {{--<label>--}}
                    {{--<a href="{{URL::to('/')}}/booking/today" class="list-group-item @if($subroute == 'today') active_menu @endif " data-parent="#MainMenu">--}}
                    {{--<label>本日</label>--}}
                    {{--</a>--}}
                    {{--</label>--}}
                    {{--<label>--}}
                    {{--<a href="{{URL::to('/')}}/booking/tomorrow" class="list-group-item @if($subroute == 'tomorrow')active_menu @endif " data-parent="#MainMenu">--}}
                    {{--<label>明日</label>--}}
                    {{--</a>--}}
                    {{--</label>--}}
                    {{--<label>--}}
                    {{--<a href="{{URL::to('/')}}/booking/new/0" class="list-group-item @if($subroute == 'new')active_menu @endif " data-parent="#MainMenu">--}}
                    {{--<label>Add Booking</label>--}}
                    {{--</a>--}}
                    {{--</label>--}}
                    {{--<label>--}}
                    {{--<a href="{{URL::to('/')}}/booking/task" class=" list-group-item @if($subroute == 'task') active_menu @endif " data-parent="#MainMenu" >--}}
                    {{--<label>タスク表</label>--}}
                    {{--</a>--}}
                    {{--</label>--}}
                <form method="POST" name="searchform" id="searchform" action="{{URL::to('/')}}/booking/all" accept-charset="UTF-8" role="form" class="form-horizontal" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="search_date" id="search_date" />
                    <input type="hidden" name="condition" id="condition" />
                    <input type="hidden" name="portal_condition" id="portal_condition" />
                    <input type="hidden" name="search_shop" id="search_shop" value="{{$search_shop}}"/>
                </form>
                <div class="save_btn" style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    {{--<a href="{{URL::to('/')}}/booking/new/0" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">--}}
                        {{--<i class="fa fa-book" aria-hidden="true"></i>&emsp;--}}
                        {{--新予約を作成--}}
                    {{--</a>--}}
                    {{--<a class="btn btn-success btn-xs pull-right" style="margin-left: 1em;" onclick="deleteGoogle()">--}}
                    {{--delete google sheet data for test--}}
                    {{--</a>--}}
                    <a class="btn btn-success btn-xs pull-right" data-toggle="modal" href="#Modal_gss" data-target="#Modal_gss" style="margin-left: 1em;">
                        <i class="fa fa-download" aria-hidden="true"></i>&emsp;Gスプレッドシート
                    </a>
                    <label id="loading_icon" style="display:none">
                        <a class="loader pull-right m-t-n-xs"></a>
                        <a class="pull-right">Loading...</a>
                    </label>
					
					<div class="modal fade" id="Modal_gss" role="dialog" tabindex="-1" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body text-center">
									<h4>Gスプレッドシートを読み込みますか？</h4>
								</div>
								<div class="modal-footer text-center">
                                    <button onclick="loadingGoogle()" class="btn btn-success" data-dismiss="modal">はい</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
								</div>
							</div>
						</div>
					</div>
                </div>
                </div>
              </div>
            </div>
        </div>
        {{--analysis of booking route--}}
        <div class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            @if($count_all == 0)
                <h4>予約がありません。</h4>
            @else
                <?php
                if(array_key_exists('自社HP', $count_portal)){
                    $perc_hp_phone = round(($count_portal['自社HP']+$count_hp + $count_phone)/$count_all * 100);
                    $count_portal['自社HP'] += $count_hp;
                } else {
                    $perc_hp_phone = round(($count_hp + $count_phone)/$count_all * 100);
                    $count_portal['自社HP'] = $count_hp;
                }

                $perc_portal = [];
                if(array_key_exists('電話', $count_portal)) $count_portal['電話'] = $count_phone;
//                var_dump($count_portal);
                arsort($count_portal);
                foreach ($count_portal as $key => $cp){
                    $perc = round($cp/$count_all * 100, 1);
                    if($perc > 0)
                        $perc_portal[] = '<b>'. $perc.'%</b>が<b>'.$key.'</b>';
                }
                ?>
                <h4>{{$count_all}}件の予約のうち、{{$perc_hp_phone}}%が自社HP/電話です。</h4>
                詳細：{!! implode('、', $perc_portal) !!}です。
            @endif
        </div>
        {{--end of booking route--}}
        <div class="row">
            <div class="panel panel-default shadow-box book_all_wrap">
                <div class="panel-body">
                    <div class="table-responsive users-table">
                        <table id="booking" class="table table-borderless data-table" width="100%">
                            <thead>
                            <tr>
                                <th>編集</th>
                                <th>ID</th>{{--booking ID/status--}}
                                <th>経路</th>{{--portal/booking number--}}
                                <th>氏名</th>{{--lastname+first name/furigana lst+first--}}
                                <th>出発日</th>{{--departing date/time--}}
                                <th>返却日</th>{{--date/time--}}
                                <th>期間</th>{{--3 days/2night--}}
                                <th>車両番号</th>{{--car number/smoking/nonsmokin--}}
                                <th>総計</th>{{--total/web paid--}}
                                <th>補償</th>{{--insurance--}}
                                <th>オプション</th>{{--list options/popup--}}
								<th>送迎</th>{{--pickup loacation/return--}}
                                <th>QS</th>
                                <th>便名/#</th>{{--fligh/number--}}
                                <th>メモ</th><!--memo-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; ?>
                            @foreach($bookings as $book)
                            <?php $i++;
                             $color ='';
                             if($book->duplicated_car == true) $color ='#f3a7a7';
                            ?>
                                    <!---->
                            <tr valign="middle" style="background-color: {{$color}}">
                                <td class="cell" style="text-align:center">
                                        <span>
                                            <a class="btn btn-xs btn-info mrg_l_15" href="{{ URL::to('/booking/edit/' . $book->id) }}" title="予約を編集する">
                                                <span class=""><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                            </a>
                                        </span>
                                </td>
                                <td class="cell"  >
                                    <a href="{{URL::to('/')}}/booking/detail/{{ $book->id }}" title="予約詳細を見る">
                                        <span class="new_row">{{$book->booking_id}}</span>
                                    </a>
                                    <span>
                                        {{--$book->booking_status--}}
                                        @if($book->status == 9)
                                            キャンセル
                                        @else
                                            @if($book->depart_task == '0')
                                                @if(time() < strtotime($book->departing))
                                                    成約 - 配車前
                                                @else
                                                    成約
                                                @endif
                                            @elseif($book->depart_task == '1' && $book->return_task == '0')
                                                貸出中
                                            @elseif($book->return_task == '1')
                                                終了
                                            @endif
                                        @endif

                                    </span>
                                </td>
                                <td class="cell">
                                    @if($book->duplicated_car == true)
                                        <span class="new_row">
                                            <strong>注意!! ダブルブッキングです!!</strong>
                                        </span>
                                    @endif
                                    @if($book->portal_flag == 0)
                                        @if($book->portal_id == 10000)
                                            @if($book->language == 'ja')
                                            <span>自社HP</span>
                                            @else
                                                <span>自社HPEN</span>
                                            @endif
                                        @else
                                            <span>自社HPAD</span>
                                        @endif
                                    @else
                                        <span class="new_row" >{{$book->portal_name}}</span>
                                        <span>{{$book->booking}}</span>
                                    @endif
                                </td>
                                <td class="cell">
                                    <div class="contact_div" id="contact_div_{{$i}}" style="display:none;z-index: 10">
                                        <span style="padding-right: 10px;">{{$book->phone}} </span>
                                        <span style="padding-right: 10px;">{{$book->email}}</span>
                                                <span class="glyphicon glyphicon-eye-close" onclick="view_contact('contact_div_{{$i}}')"
                                                      style="cursor:pointer;margin-top: 2px;"></span>
                                    </div>
                                    <span class="new_row">
                                        {{ $book->last_name }} {{ $book->first_name }}
                                        <span class="glyphicon glyphicon-user" style="cursor: pointer"
                                            onclick="view_contact('contact_div_{{$i}}')"></span>
                                    </span>
                                    <span>{{ $book->fur_last_name }} {{ $book->fur_first_name }}</span>
                                </td>
                                <td class="cell">
                                    <span class="new_row">{{ date('Y/m/d', strtotime($book->departing)) }}</span>
                                    <span>{{ date('H:i', strtotime($book->departing)) }} </span>
                                </td>
                                <td class="cell">
                                    <span class="new_row">
                                         @if(strtotime($book->returning_updated) > strtotime('1970/01/01'))
                                            {{ date('Y/m/d', strtotime($book->returning_updated)) }}
                                        @else {{ date('Y/m/d', strtotime($book->returning)) }} @endif</span>
                                            <span>{{ date('H:i', strtotime($book->returning)) }}</span>
                                </td>
                                <td class="cell">
                                    <span>@if($book->night == '0泊') 日帰り @else {{$book->night}} {{$book->day}} @endif</span>
                                </td>
                                <td class="cell">
                                    <span class="new_row"><!--{{$book->class_name}}-->{{$book->shortname}}</span>
                                    <span>{{$book->car_number }}</span>
                                    <span>@if($book->smoke == 1) 喫煙 @else 禁煙 @endif</span>
                                </td>
                                <td class="cell">
                                    <span class="new_row">{{number_format($book->paidamount + $book->unpaidamount )}}円</span>
									<span>
                                        @if($book->pay_method == '1' && $book->pay_status = '1')
                                            現金
                                        @elseif($book->pay_method == '2' && $book->pay_status == '1' )
                                            カード
                                        @elseif($book->pay_method == '3' && $book->pay_status == '1' )
                                            Web決済
                                        @elseif($book->pay_method == '4' && $book->pay_status == '1' )
                                            Portal決済
                                        @endif</span>
                                </td>
                                <td class="cell">

                                    <span class="new_row">
                                        @if(!empty($book->insurance2))
                                            免+ワ
                                        @elseif(!empty($book->insurance1))
                                            免
                                        @else
                                            --
                                        @endif
                                    </span>
                                </td><!--insurance1 /insurance2-->
                                <td class="cell">
                                    <?php
                                        $option_flag = false;
                                        $count_option = 0;
                                    ?>
                                    <span class="new_row">
                                        @foreach($book->options as $option )
                                            @if($option->option_name != "") <?php $option_flag = true; ?>  @endif
                                            {{--<label class="option_detail">{{$option->option_name}}({{$option->option_number}}) </label>--}}
                                            <label class="option_detail">{{$option->option_name}} @if(!empty($option->option_number))({{$option->option_number}})@endif </label>
                                            <?php
                                                $count_option++;
                                                if($count_option > 2) break;
                                            ?>
                                        @endforeach
                                    </span>
                                    <?php
                                        $count_option = 0;
                                    ?>
                                    <span>
                                        @foreach($book->options as $option )
                                            @if($count_option > 2)
                                                <label class="option_detail">{{$option->option_name}}@if(!empty($option->option_number))({{$option->option_number}}) @endif </label>
                                            @endif
                                            <?php
                                            $count_option++;
                                            ?>
                                        @endforeach
                                    </span>
                                </td>

								<td>
                                    {{--@if($book->free_options == '101') 必要  @else -- @endif--}}
{{--                                    @if($book->wait_status == '1' || $book->wait_status == '2' ) 必要  @elseif($book->wait_status == '3') スマ @else -- @endif--}}
                                    {{ $book->pickup_column }}
                                </td>
                                <td>
                                   {{--@if($book->pay_method == '0' || empty($book->pay_method) )--}}
                                        @if($book->web_status == 0) -- @else {{$book->web_status}}/3 @endif
                                    {{--@endif--}}
                                </td>
                                <td class="cell">
                                    <span class="new_row">{{$book->flight_name}} </span>
                                    <span>{{$book->flight_number}}</span>
                                </td>
								<td class="cell" >
                                    <span>{{$book->admin_memo}}</span>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="optionModal" tabindex="-1" role="dialog" aria-labelledby="optionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm option-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionModalLabel">合計金額:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="row" id="modal_price">
                           <!--price part-->
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                </div>
            </div>
        </div>
    </div>
    <!--today list modal-->
    <?php
       $today = $service_booking->today_tom('today');
       $today_depart = $today['departings'];
       $today_return = $today['returnings'];
       $tom   = $service_booking->today_tom('tom');
       $tom_depart = $tom['departings'];
       $tom_return = $tom['returnings'];
    ?>
    <div class="modal fade" id="todayModal" tabindex="-1" role="dialog" aria-labelledby="todayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg today-modal" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <!--departting-->
                    <div>
                        <span>【配車予定】 </span>
                        <span>{{date('Y').'年 '.date('n').'月 '.date('j').'日'}} ({{$service_booking->getDate(date('N'))}}) </span>
                        <span> 全 {{count($today_depart)+count($today_return)}} 件 </span>
                    </div>
                    <hr>
                    <div>
                        <span> 配車 {{count($today_depart)}} 件 </span>
                    </div>
                    <table class="table table-pdf table-striped table-bordered nowrap" width="100%">
                        <thead>
                        <tr>
                            <th style="width:3%;">&nbsp;</th>
                            <th style="width:3%;">#</th>
                            <th style="width:10%;">氏名</th> <!--name-->
                            <th style="width:5%;">出</th>{{--departing time--}}
                            <th style="width:5%;">返</th>{{--returning time--}}
                            <th style="width:10%;">車両</th>{{--vehicle--}}
                            <th style="width:3%;">数</th>{{--number--}}
                            <th style="width:10%;">送迎</th>{{--pickup--}}
                            <th style="width:3%;" >免</th>{{--insurance1--}}
                            <th style="width:3%;">ワ</th>{{--insurance2--}}
                            <th style="width:20%;">(オプ)</th>{{--option--}}
                            <th style="width:5%;">QS</th>{{--list options/popup--}}
                            <th style="width:10%;">総計</th>{{--all payment--}}
                            <th style="width:10%;">他</th>{{--other--}}
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; ?>
                        @foreach($today_depart as $book)
                        <?php $i++; ?>
                                <!---->
                        <tr class="@if(($i%2) == 1) today_deaprt_1 @else today_depart_2 @endif " valign="middle">
                            <td class="cell"  >&nbsp;
                                
                            </td>
                            <td class="cell"  >
                                {{$i}}
                            </td>
                            <td class="cell"  >
                                <span class="new_row">{{$book->first_name.' '.$book->last_name}}</span>
                                <span>{{ $book->phone}}</span>
                            </td>
                            <td class="cell">
                                <span>{{ date('H:i', strtotime($book->departing))}} </span>
                            </td>
                            <td class="cell">
                                <span class="new_row" >{{ date('m/d', strtotime($book->returning))}}</span>
                                <span>{{ date('H:i', strtotime($book->returning))}} </span>
                            </td>
                            <td class="cell">
                                <span class="new_row" >{{$book->numberplate1}}-{{$book->numberplate2}}</span>
                                <span>{{$book->numberplate3}}-{{$book->numberplate4}}</span>
                            </td>
                            <td class="cell">
                                <span>{{$book->passengers}}</span>
                            </td>
                            <td class="cell">
                                <span>&nbsp;</span>
                            </td>
                            <td class="cell">
                                @if(!empty($book->insurance1))
                                    <span>O</span>
                                @endif
                            </td>
                            <td class="cell" style="text-align:center" >
                                @if(!empty($book->insurance2))
                                    <span>O</span>
                                @endif
                            </td>
                            <td class="cell">
                                @foreach($book->options as $option )
                                    <span class="option_detail">{{$option->option_name}}@if(!empty($option->option_number))({{$option->option_number}}) @endif </span>
                                @endforeach
                            </td>
                            <td class="cell">
                                <span >@if($book->web_status_flag == '0') -- @else {{$book->web_status_flag}}/3 @endif</span>
                            </td>
                            <td class="cell">
                                <span>{{number_format($book->paidamount + $book->unpaidamount)}}</span>
                            </td>
                            <td class="cell">&nbsp;
                              
                            </td>
                        </tr>
                        <!---->
                        @endforeach
                        </tbody>
                    </table>
                    <!--retunring-->
                    <div>
                        <span> 返車 {{count($today_return)}} 件 </span>
                    </div>
                    <table class="table table-pdf table-striped table-bordered nowrap" width="100%">
                        <thead>
                        <tr>
                            <th style="width:5%;">&nbsp;</th>
                            <th style="width:5%;">#</th>
                            <th style="width:10%;">氏名</th> <!--name-->
                            <th style="width:10%;">返</th>{{--return time--}}
                            <th style="width:10%;">車両</th>{{--plate number--}}
                            <th style="width:10%;">送迎</th>{{--pickup--}}
                            <th style="width:20%;">オプ</th>{{--option--}}
                            <th style="width:30%;">他</th>{{--other--}}
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; ?>
                        @foreach($today_return as $book)
                        <?php $i++; ?>
                                <!---->
                        <tr class="@if(($i%2) == 1) today_return_1 @else today_return_2 @endif " valign="middle">
                            <td class="cell"  >&nbsp;
                                
                            </td>
                            <td class="cell"  >
                                {{$i}}
                            </td>
                            <td class="cell"  >
                                <span class="new_row">{{$book->first_name.' '.$book->last_name}}</span>
                                <span>{{ $book->booking_id}}</span>
                            </td>
                            <td class="cell">
                                <span>{{ date('H:i', strtotime($book->returning))}} </span>
                            </td>
                            <td class="cell">
                                <span class="new_row" >{{$book->numberplate1}}-{{$book->numberplate2}}</span>
                                <span>{{$book->numberplate3}}-{{$book->numberplate4}} </span>
                            </td>
                            <td class="cell">
                                <span></span>
                            </td>
                            <td class="cell">
                                @foreach($book->options as $option )
                                    <span class="option_detail">{{$option->option_name}}@if(!empty($option->option_number))({{$option->option_number}}) @endif </span>
                                @endforeach
                            </td>
                            <td class="cell">&nbsp;
                                
                            </td>
                        </tr>
                        <!---->
                        @endforeach
                        </tbody>
                    </table>
                    <!--end-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    <button type="button" class="btn btn-primary" onclick="printPdf('today')">印刷</button>
                </div>
            </div>
        </div>
    </div>
    <!--tommodal-->
    <div class="modal fade" id="tomModal" tabindex="-1" role="dialog" aria-labelledby="tomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg today-modal" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <!--departting-->
                    <div>
                        <span>【配車予定】 </span>
                        <span>{{date('Y',strtotime("+1 day")).'年 '.date('n',strtotime("+1 day")).'月 '.date('j',strtotime("+1 day")).'日'}} ({{$service_booking->getDate(date('N'))}}) </span>
                        <span> 全 {{count($tom_depart)+count($tom_return)}} 件 </span>
                    </div>
                    <hr>
                    <div>
                        <span> 配車 {{count($tom_depart)}} 件 </span>
                    </div>
                    <table class="table table-pdf table-striped table-bordered nowrap" width="100%">
                        <thead>
                        <tr>
                            <th style="width:3%;">&nbsp;</th>
                            <th style="width:3%;">#</th>
                            <th style="width:10%;">氏名</th> <!--name-->
                            <th style="width:5%;">出</th>{{--departing time--}}
                            <th style="width:5%;">返</th>{{--returning time--}}
                            <th style="width:10%;">車両</th>{{--vehicle--}}
                            <th style="width:3%;">数</th>{{--number--}}
                            <th style="width:10%;">送迎</th>{{--pickup--}}
                            <th style="width:3%;" >免</th>{{--insurance1--}}
                            <th style="width:3%;">ワ</th>{{--insurance2--}}
                            <th style="width:20%;">(オプ)</th>{{--option--}}
                            <th style="width:5%;">QS</th>{{--list options/popup--}}
                            <th style="width:10%;">総計</th>{{--all payment--}}
                            <th style="width:10%;">他</th>{{--other--}}
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; ?>
                        @foreach($tom_depart as $book)
                        <?php $i++; ?>
                                <!---->
                        <tr class="@if(($i%2) == 1) today_deaprt_1 @else today_depart_2 @endif " valign="middle">
                            <td class="cell"  >&nbsp;
                                
                            </td>
                            <td class="cell"  >
                                {{$i}}
                            </td>
                            <td class="cell"  >
                                <span class="new_row">{{$book->first_name.' '.$book->last_name}}</span>
                                <span>{{ $book->phone}}</span>
                            </td>
                            <td class="cell">
                                <span>{{ date('H:i', strtotime($book->departing))}} </span>
                            </td>
                            <td class="cell">
                                <span class="new_row" >{{ date('m/d', strtotime($book->returning))}}</span>
                                <span>{{ date('H:i', strtotime($book->returning))}} </span>
                            </td>
                            <td class="cell">
                                <span class="new_row" >{{$book->numberplate1}}-{{$book->numberplate2}}</span>
                                <span>{{$book->numberplate3}}-{{$book->numberplate4}}</span>
                            </td>
                            <td class="cell">
                                <span>{{$book->passengers}}</span>
                            </td>
                            <td class="cell">
                                <span>&nbsp;</span>
                            </td>
                            <td class="cell">
                                @if(!empty($book->insurance1))
                                    <span>O</span>
                                @endif
                            </td>
                            <td class="cell" style="text-align:center" >
                                @if(!empty($book->insurance2))
                                    <span>O</span>
                                @endif
                            </td>
                            <td class="cell">
                                @foreach($book->options as $option )
                                    <span class="option_detail">{{$option->option_name}}({{$option->option_number}}) </span>
                                @endforeach
                            </td>
                            <td class="cell">
                                <span >@if($book->web_status_flag == 0) -- @else {{$book->web_status_flag}}/3 @endif</span>
                            </td>
                            <td class="cell">
                                <span>{{number_format($book->paidamount + $book->unpaidamount )}}</span>
                            </td>
                            <td class="cell">&nbsp;
                                
                            </td>
                        </tr>
                        <!---->
                        @endforeach
                        </tbody>
                    </table>
                    <!--retunring-->
                    <div>
                        <span> 返車 {{count($tom_return)}} 件 </span>
                    </div>
                    <table class="table table-pdf display table-striped table-bordered nowrap" width="100%">
                        <thead>
                        <tr>
                            <th style="width:5%;">&nbsp;</th>
                            <th style="width:5%;">#</th>
                            <th style="width:10%;">氏名</th> <!--name-->
                            <th style="width:10%;">返</th>{{--return time--}}
                            <th style="width:10%;">車両</th>{{--plate number--}}
                            <th style="width:10%;">送迎</th>{{--pickup--}}
                            <th style="width:20%;">(オプ)</th>{{--option--}}
                            <th style="width:30%;">他</th>{{--other--}}
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; ?>
                        @foreach($tom_return as $book)
                        <?php $i++; ?>
                                <!---->
                        <tr class="@if(($i%2) == 1) today_return_1 @else today_return_2 @endif " valign="middle">
                            <td class="cell"  >&nbsp;
                                
                            </td>
                            <td class="cell"  >
                                {{$i}}
                            </td>
                            <td class="cell"  >
                                <span class="new_row">{{$book->first_name.' '.$book->last_name}}</span>
                                <span>{{ $book->booking_id}}</span>
                            </td>
                            <td class="cell">
                                <span>{{ date('H:i', strtotime($book->returning))}} </span>
                            </td>
                            <td class="cell">
                                <span class="new_row" >{{$book->numberplate1}}-{{$book->numberplate2}}</span>
                                <span>{{$book->numberplate3}}-{{$book->numberplate4}} </span>
                            </td>
                            <td class="cell">
                                <span></span>
                            </td>
                            <td class="cell">
                                @foreach($book->options as $option )
                                    <span class="option_detail">{{$option->option_name}}({{$option->option_number}}) </span>
                                @endforeach
                            </td>
                            <td class="cell">&nbsp;
                                
                            </td>
                        </tr>
                        <!---->
                        @endforeach
                        </tbody>
                    </table>
                    <!--end-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    <button type="button" class="btn btn-primary" onclick="printPdf('tom')">印刷</button>
                </div>
            </div>
        </div>
    </div>
    <!--endtommodal-->
    <!--modela for google status-->
    <div class="modal fade" id="googleModal" tabindex="-1" role="dialog" aria-labelledby="googleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="googleModalLabel">Googleスプレッドシート</h5>
                    <button type="button" class="close" onclick="closeGoogle()" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="google_error">

                    </div>
                    <div id="google_success">
                        <p>Googleスプレッドシートから取込作業が完了しました。
                            下記の内容をご確認ください。
                        </p>
                        <p>
                            取込完了の予約 ==> <span id="google_passed"></span>件
                        </p>
                        <p>
                            取込失敗の予約 ==> <span id="google_failed"></span>件
                            <br/>
                            <a target="_blank" href="https://docs.google.com/spreadsheets/d/1saI6LwZ997p_c_F2Jd4lQNLQ3zwUVDSP79BjLmLhQtM/edit#gid=278340022" >[ 取込失敗の予約を見る ]</a>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeGoogle()" data-dismiss="modal">閉じる</button>
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
    @include('scripts.admin.booking.all')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    <script>
        $('input[name="daterange"]').daterangepicker({
            format: 'YYYY/MM/DD',
            separator: ' - ',
            locale: {
                customRangeLabel: 'Custom',
                applyLabel: '適応',
                cancelLabel: 'キャンセル',
                fromLabel: '出発',
                toLabel: '返却',
                daysOfWeek: [ "日", "月", "火", "水", "木", "金", "土" ],
                "monthNames": [ "1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月" ],
            },
        });
        //view pdf modal
        function pdfView(cond) {
            if(cond == 'today') $('#todayModal').modal('show');
            if(cond == 'tom')   $('#tomModal').modal('show');
        }

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            //do something, like clearing an input
            var condition = $('#cond').val();
            var search_date = $('input[name="daterange"]').val();
            $('#search_date').val(search_date);
            $('#condition').val(condition);
            $('#search_shop').val($('#shop').val());
            $('#searchform').submit();
        });

        $('#shop').change( function() {
            $('#search_date').val($('input[name="daterange"]').val());
            $('#condition').val($('#cond').val());
            $('#search_shop').val($('#shop').val());
            $('#searchform').submit();
        });

        $('#portal_cond').change( function() {
            $('#search_date').val($('input[name="daterange"]').val());
            $('#condition').val($('#cond').val());
            $('#portal_condition').val($('#portal_cond').val());
            $('#search_shop').val($('#shop').val());
            $('#searchform').submit();
        });

        function numberWithCommas(number) {
            var parts = number.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }
        //run to format
        $(document).ready(function() {
            $(".price").each(function() {
                var num = $(this).text();
                var commaNum = numberWithCommas(num);
                $(this).text(commaNum);
            });
        });
        //view otpion
        function view_options(option_item) {
            $( "#"+option_item ).toggle( "slow", function() {
                // Animation complete.
            });
        }
        //view price
        function view_price(book) {
            var html="";

            console.log(book);
            html += '<div class="row">';
            html += '<li><span class="col-md-5" >基本：</span><span class="coin">'+book.basic_price+'</span>円</li>';
            var insurance = '0';
            if(book.insurance1 != null && book.insurance1 > 0) {
                insurance = book.insurance1 ;
                html += '<li><span class="col-md-5" >免責：</span><span class="coin">'+insurance+'</span>円</li>';
            }
            if(book.insurance2 != null && book.insurance2 > 0) {
                insurance = book.insurance2;
                html += '<li><span class="col-md-5" >ワ補：</span><span class="coin">'+insurance+'</span>円</li>';
            }
            //html += '<li><span class="col-md-5" >免責：</span><span class="coin">'+insurance+'</span>円</li>';
            for (var i = 0; i < book.options.length; i++) {
                var name = book.options[i].option_name;
                var number = book.options[i].option_number;
                var price = book.options[i].option_price/number;
                html += '<li><span class="col-md-5" >'+name+'：</span> <span class="coin">'+price+'</span>円</li>';
            }
//            html +='</div>';
//            html +='<div class="col-md-6 bottom-align-text">';
            html +=' <li style="text-decoration: underline"><span class=" coin col-md-offset-5" > 合計 '+book.payment+'</span>円</li>';
            html +='</div>';

            $('#modal_price').html(html);
            $('#optionModal').modal('show');

            $(".coin").each(function() {
                var num = $(this).text();
                var commaNum = numberWithCommas(num);
                $(this).text(commaNum);
            });
            $('.modal-left').css('width','100px');
        }
        //view contact
        function view_contact(option_item) {
            $( "#"+option_item ).toggle( "slow", function() {
                // Animation complete.
            });
        }
        //laoding booking from google
		function showModal(msg) {
            console.log(msg);
            if(Array.isArray(msg)) {
                var errors = msg,
                    errorsHtml = '<ul>';

                $.each( errors, function( key, value ) {
                    errorsHtml += '<li>' + value + '</li>'; //showing only the first error.
                });
                errorsHtml += '</ul>';
                $('.error-text').html(errorsHtml);
            } else {
                $('.error-text').html(msg);
            }
            $mdlError.modal('show');
        }
        function loadingGoogle() {
				$('#loading_icon').show();
				var url = "{{ URL::to('/googlesheet') }}";
				var token = '{{ csrf_token() }}';
				var data = [];
				data.push({name: '_token', value: token});
				data = jQuery.param(data);
				$.ajax({
					type: "POST",
					url: url,
					data: data,
					async: true,
					dataType: "json",
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(errorThrown);
						$('#google_error').text(errorThrown);
						$('#loading_icon').hide();
						$('#google_error').show();
						$('#google_success').hide();
						$('#googleModal').modal('show');
					},
					success: function (content) {
						$('#loading_icon').hide();
						$('#google_error').hide();
						$('#google_success').show();
						var passed = content.passed;
						$('#google_passed').html(passed);
						var failed = content.failed;
						$('#google_failed').html(failed);
						$('#googleModal').modal('show');
						//window.location.href = "{{URL::to('/')}}/booking/all";
					}
				});
        };
        //refresh page when completed google
        function closeGoogle(){
            window.location.href = "{{URL::to('/')}}/booking/all";
        }
        //delete portal to test
        function deleteGoogle() {
            var url = "{{ URL::to('/deletegoogleportal') }}";
            var token = '{{ csrf_token() }}';
            var data = [];
            data.push({name: '_token', value: token});
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: true,
                dataType: "text",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    window.location.href = "{{URL::to('/')}}/booking/all";
                }
            });
        };
        //pdf modal
        function printPdf(cond, shop) {
			/*
            var url = '{{URL::to('/')}}/booking/pdf?download=pdf&cond='+cond;
            window.location.href = url;
			*/
			window.open("{!! URL::to('/booking/pdf?download=pdf&cond=') !!}"+cond+'&shop='+shop, "BookingWindow", "width=700,height=500");
        };
    </script>
@endsection