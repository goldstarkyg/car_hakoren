{{--@extends('layouts.adminapp')--}}
@extends('layouts.adminapp_calendar')

@section('template_title')
    今日の予約
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
        .users-table { border: 0; }
        .users-table tr td:first-child { padding-left: 15px; }
        .users-table tr td:last-child { padding-right: 15px; }
        .users-table.table-responsive,
        .users-table.table-responsive table { margin-bottom: 0; }
        .tab-font{
            font-size: 14px !important;
        }
    </style>
@endsection

@section('content')
    <link href="{{URL::to('/')}}/css/navtab.css" rel="stylesheet">
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>今日の予約</h2>
                <div style="position: absolute; margin-top: -3.3em;left:170px;" >
                    <label>
                        <a href="{{URL::to('/')}}/booking/all" class="list-group-item @if($subroute == 'all') active_menu @endif " data-parent="#MainMenu">
                            <label>View All</label>
                        </a>
                    </label>
                    <label>
                        <a href="{{URL::to('/')}}/booking/today" class="list-group-item @if($subroute == 'today') active_menu @endif " data-parent="#MainMenu">
                            <label>Today</label>
                        </a>
                    </label>
                    <label>
                        <a href="{{URL::to('/')}}/booking/tomorrow" class="list-group-item @if($subroute == 'tomorrow')active_menu @endif " data-parent="#MainMenu">
                            <label>Tomorrow</label>
                        </a>
                    </label>
                    <label>
                        <a href="{{URL::to('/')}}/booking/new/0" class="list-group-item @if($subroute == 'new')active_menu @endif " data-parent="#MainMenu">
                            <label>Add Booking</label>
                        </a>
                    </label>
                </div>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="{{URL::to('/')}}/members/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-user" aria-hidden="true"></i>
                        作成する
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel with-nav-tabs panel-default shadow-box">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a class="tab-font" data-toggle="tab" href="#pane-depart">Departing</a></li>
                            <li class=""><a class="tab-font" data-toggle="tab" href="#pane-return">Returning</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content" style="padding-top: 15px;">
                        <div id="pane-depart" class="tab-pane fade in active">
                            {{--<h3>Departing</h3>--}}
                            <div class="table-responsive users-table">
                                <table class="table table-striped table-condensed data-table" id="depart-table" width="100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>{{--booking ID/status--}}
                                        <th>Submited</th>{{--submited date/time--}}
                                        <th>Via</th>{{--portal/booking number--}}
                                        <th>Name</th>{{--lastname+first name/furigana lst+first--}}
                                        <th>departing date</th>{{--departing date/time--}}
                                        <th>returning date</th>{{--date/time--}}
                                        <th>Days</th>{{--3 days/2night--}}
                                        <th>car class</th>{{--car class--}}
                                        <th>car number</th>{{--car number/smoking/nonsmokin--}}
                                        <th>insurance</th>{{--insurance--}}
                                        <th>Options</th>{{--list options/popup--}}
                                        <th>Pickup/Return</th>{{--pickup loacation/return--}}
                                        <th>Total amount</th>{{--total/web paid--}}
                                        <th>Flight name</th>{{--fligh/number--}}
                                        <th>Staff</th><!--staff-->
                                        <th>memo</th><!--memo-->
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($departings as $book)
                                    <?php $i++; ?>
                                            <!---->
                                    <tr valign="middle">
                                        <td class="cell"  >
                                            <span class="new_row">{{$book->booking_id}}</span>
                                            <span>{{$book->booking_status}}</span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">{{ date('Y/m/d', strtotime($book->created_at))}} </span>
                                            <span>{{ date('h:i', strtotime($book->created_at))}}</span>
                                        </td>
                                        <td class="cell">
                                            @if($book->portal_flag == '0')
                                                <span>自社HP</span>
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
                                            <span>{{ date('h:i', strtotime($book->departing)) }} </span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">{{ date('Y/m/d', strtotime($book->returning)) }}</span>
                                            <span>{{ date('h:i', strtotime($book->returning)) }}</span>
                                        </td>
                                        <td class="cell">
                                            <span>{{$book->night}}{{$book->day}}</span>
                                        </td>
                                        <td class="cell" style="text-align:center" >
                                            <span>{{$book->class_name}}</span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">{{$book->car_number }}</span>
                                            <span>@if($book->smoke == 1) smoke @else non smoke @endif</span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">@if(!empty($book->insurance1))免: {{$book->insurance1 }}円 @endif</span>
                                            <span>@if(!empty($book->insurance2))ワ: {{$book->insurance2 }}円 @endif</span>
                                        </td><!--insurance1 /insurance2-->
                                        <td class="cell">
                                            <?php $option_flag = false; ?>
                                            <div class="option_div" id="option_div_{{$i}}" style="display:none;z-index: 10">
                                                @foreach($book->options as $option )
                                                    @if($option->option_name != "") <?php $option_flag = true; ?>  @endif
                                                    <label class="option_detail">{{$option->option_name}}({{$option->option_price}}) </label>
                                                @endforeach
                                            </div>
                                            @if($option_flag == true)
                                                <button type="button" class="btn btn-default btn-xs" onclick="view_options('option_div_{{$i}}')">
                                                    <span class="glyphicon glyphicon-plus"></span> view
                                                </button>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="cell">
                                            @if($book->pickup_name == $book->drop_name )
                                                <span>{{$book->drop_name}}</span>
                                            @else
                                                <span class="new_row">{{$book->pickup_name}}</span>
                                                <span>{{$book->drop_name}}</span>
                                            @endif
                                        </td>
                                        <td class="cell price">
                                            <span class="new_row">{{$book->payment}} </span>
                                            <span>{{$book->card_name}}</span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">{{$book->flight_line}} </span>
                                            <span>{{$book->flight_number}}</span>
                                        </td>
                                        <td class="cell" >
                                            <span>{{$book->admin_last_name}} {{$book->admin_first_name}}</span>
                                        </td>
                                        <td class="cell" >
                                            <span>{{$book->admin_memo}}</span>
                                        </td>
                                        <td class="cell" style="text-align:center">
                                        <span class="new_row">
                                            <a class="btn btn-xs btn-success" href="{{ URL::to('/booking/detail/' . $book->id) }}" title="詳細" style="margin-bottom: 1px; margin-top: 1px;">
                                                <span class="hidden-xs hidden-sm">詳細</span>
                                            </a>
                                        </span>
                                        <span>
                                            <a class="btn btn-xs btn-info " href="{{ URL::to('/booking/edit/' . $book->id) }}" title="編集">
                                                <span class="hidden-xs hidden-sm">編集</span>
                                            </a>
                                        </span>
                                        </td>
                                    </tr>
                                    <!---->
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="pane-return" class="tab-pane fade">
                            {{--<h3>Returning</h3>--}}
                            <div class="table-responsive users-table">
                                <table class="table table-striped table-condensed data-table" id="return-table" width="100%" style="width: 1824px !important;">
                                    <thead>
                                    <tr>
                                        <th>ID</th>{{--booking ID/status--}}
                                        <th>Submited</th>{{--submited date/time--}}
                                        <th>Via</th>{{--portal/booking number--}}
                                        <th>Name</th>{{--lastname+first name/furigana lst+first--}}
                                        <th>departing date</th>{{--departing date/time--}}
                                        <th>returning date</th>{{--date/time--}}
                                        <th>Days</th>{{--3 days/2night--}}
                                        <th>car class</th>{{--car class--}}
                                        <th>car number</th>{{--car number/smoking/nonsmokin--}}
                                        <th>insurance</th>{{--insurance--}}
                                        <th>Options</th>{{--list options/popup--}}
                                        <th>Pickup/Return</th>{{--pickup loacation/return--}}
                                        <th>Total amount</th>{{--total/web paid--}}
                                        <th>Flight name</th>{{--fligh/number--}}
                                        <th>Staff</th><!--staff-->
                                        <th>memo</th><!--memo-->
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($returnings as $book)
                                    <?php $i++; ?>
                                            <!---->
                                    <tr valign="middle">
                                        <td class="cell"  >
                                            <span class="new_row">{{$book->booking_id}}</span>
                                            <span>{{$book->booking_status}}</span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">{{ date('Y/m/d', strtotime($book->created_at))}} </span>
                                            <span>{{ date('h:i', strtotime($book->created_at))}}</span>
                                        </td>
                                        <td class="cell">
                                            @if($book->portal_flag == '0')
                                                <span>自社HP</span>
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
                                            <span>{{ date('h:i', strtotime($book->departing)) }} </span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">{{ date('Y/m/d', strtotime($book->returning)) }}</span>
                                            <span>{{ date('h:i', strtotime($book->returning)) }}</span>
                                        </td>
                                        <td class="cell">
                                            <span>{{$book->night}}{{$book->day}}</span>
                                        </td>
                                        <td class="cell" style="text-align:center" >
                                            <span>{{$book->class_name}}</span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">{{$book->car_number }}</span>
                                            <span>@if($book->smoke == 1) smoke @else non smoke @endif</span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">@if(!empty($book->insurance1))免: {{$book->insurance1 }}円 @endif</span>
                                            <span>@if(!empty($book->insurance2))ワ: {{$book->insurance2 }}円 @endif</span>
                                        </td><!--insurance1 /insurance2-->
                                        <td class="cell">
                                            <?php $option_flag = false; ?>
                                            <div class="option_div" id="option_div_{{$i}}" style="display:none;z-index: 10">
                                                @foreach($book->options as $option )
                                                    @if($option->option_name != "") <?php $option_flag = true; ?>  @endif
                                                    <label class="option_detail">{{$option->option_name}}({{$option->option_price}}) </label>
                                                @endforeach
                                            </div>
                                            @if($option_flag == true)
                                                <button type="button" class="btn btn-default btn-xs" onclick="view_options('option_div_{{$i}}')">
                                                    <span class="glyphicon glyphicon-plus"></span> view
                                                </button>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="cell">
                                            @if($book->pickup_name == $book->drop_name )
                                                <span>{{$book->drop_name}}</span>
                                            @else
                                                <span class="new_row">{{$book->pickup_name}}</span>
                                                <span>{{$book->drop_name}}</span>
                                            @endif
                                        </td>
                                        <td class="cell price">
                                            <span class="new_row">{{$book->payment}} </span>
                                            <span>{{$book->card_name}}</span>
                                        </td>
                                        <td class="cell">
                                            <span class="new_row">{{$book->flight_line}} </span>
                                            <span>{{$book->flight_number}}</span>
                                        </td>
                                        <td class="cell" >
                                            <span>{{$book->admin_last_name}} {{$book->admin_first_name}}</span>
                                        </td>
                                        <td class="cell" >
                                            <span>{{$book->admin_memo}}</span>
                                        </td>
                                        <td class="cell" style="text-align:center">
                                        <span class="new_row">
                                            <a class="btn btn-xs btn-success" href="{{ URL::to('/booking/detail/' . $book->id) }}" title="詳細" style="margin-bottom: 1px; margin-top: 1px;">
                                                <span class="hidden-xs hidden-sm">詳細</span>
                                            </a>
                                        </span>
                                        <span>
                                            <a class="btn btn-xs btn-info " href="{{ URL::to('/booking/edit/' . $book->id) }}" title="編集">
                                                <span class="hidden-xs hidden-sm">編集</span>
                                            </a>
                                        </span>
                                        </td>
                                    </tr>
                                    <!---->
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>

    @include('modals.modal-delete')

@endsection

@section('footer_scripts')

    <link href="{{URL::to('/')}}/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <style>
        .data-table thead tr th {
            white-space: nowrap;
        }
        .data-table tbody tr td {
            white-space: nowrap;
        }
        .cell{
            padding: 0px 8px 0px 8px !important;
            vertical-align: middle !important;
        }
        .option_detail {
            background-color: #eee;
            padding: 1px 3px 1px 3px;
            font-size: 12px;
            border: 1px solid #bebebe;
            font-weight: 300;
            border-radius: 2px;;
            margin-right: 3px;
            cursor: pointer;
            margin-top: 2px;
            margin-left: 2px;
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
    </style>
    <script src="{{URL::to('/')}}/js/jquery.dataTables2.js"></script>
    <script src="{{URL::to('/')}}/js/dataTables.bootstrap.js"></script>

    <script type="text/javascript">
        var depart_table, return_table;
        $(document).ready(function() {
            depart_table = $('.data-table').dataTable({
                "scrollX": true,
                "order": [[ 0, 'asc' ]],
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "pageLength" : 25,
//                lengthMenu: [
//                    [ 10, 24, 50, 100],
//                    [ '10', '24', '50', '100' ]
//                ],
//                buttons: [
//                    'pageLength'
//                ],
                "serverSide": false,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "dom": 'T<"clear">lfrtip',
                "sPaginationType": "full_numbers",
                'columnDefs': [{ 'orderable': true, 'targets': 0,'width':'50px' }
                    ,{ 'orderable': true, 'targets': 1}
                    ,{ 'orderable': true, 'targets': 2}
                    ,{ 'orderable': true, 'targets':3}
                    ,{ 'orderable': true, 'targets':4}
                    ,{ 'orderable': true, 'targets':5}
                    ,{ 'orderable': true, 'targets':6}
                    ,{ 'orderable': true, 'targets':7}
                    ,{ 'orderable': true, 'targets':8}
                    ,{ 'orderable': true, 'targets':9}
                    ,{ 'orderable': true, 'targets':10}
                    ,{ 'orderable': true, 'targets':11}
                    ,{ 'orderable': true, 'targets':12}
                    ,{ 'orderable': true, 'targets':13}
                    ,{ 'orderable': true, 'targets':14}
                    ,{ 'orderable': true, 'targets':15}
                    ,{ 'orderable': false, 'targets':16}
                ],
                "language": {
                    "info": "_START_ ~ _END_ を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全ての管理者 _TOTAL_"
                }
            });
        });

    </script>
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    <script>
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
        //view contact
        function view_contact(option_item) {
            $( "#"+option_item ).toggle( "slow", function() {
                // Animation complete.
            });
        }

    </script>
@endsection