@extends('layouts.adminapp_calendar')

@section('template_title')
    配車カレンダー
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/plugins/dataTables/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/plugins/dataTables/fixedColumns.bootstrap.min.css">
    <style type="text/css" media="screen">
        .datepicker{
            background: #ffffff;
        }
        .users-table {
            border: 1px;
        }
        .users-table tr td:first-child {
            padding-left: 3px;
            white-space: nowrap;
        }
        .users-table tr td:last-child {
            padding-right: 3px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }

        .info-box {
            background: white;
            position: absolute;
            top: -200px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px;
            z-index: 10;
            display: none;
        }
		
		.seedetail {
			background:#fb4d4d;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
			font-weight:500;
			color:#fff;
			padding:3px 6px;
			margin:7px;
		}
        .calendar_drop {
            width: 121px;
            float: left;
            padding: 0 10px 0 0;
            display: inline-block;
        }
        .calendar_drop .chosen-container{
            width: 100% !important;
            display: inline-block;
        }
        .calendar_drop .input-group{
            width: 100%;
            display: inline-block;
        } 
        /**/
        @media screen and (max-width: 1280px){
            .calendar_drop {
                width: 123px;
            }
            .calendar_drop .chosen-container-single .chosen-single div b {
                background: url(/css/plugins/chosen/chosen-sprite.png) no-repeat 0px 2px !important;
            }
        }
        @media screen and (max-width: 1024px){
            .calendar_drop .chosen-container-single .chosen-single div b {
                display: block !important;
                width: 100% !important;
                height: 100% !important;
                background: url(/css/plugins/chosen/chosen-sprite.png) no-repeat 0px 2px !important;
            }
            .calendar_drop {
                width: 140px;
            }
            .calendar_drop .chosen-container{
                width: 122px !important;
                display: inline-block;
            }
            .calendar_event_ht table tbody tr td.text-center.booking-box {
                height: 50px !important;
            }
        }

        @media screen and (max-width: 768px){
            .calendar_drop {
                width: 100%;
                display: inline-block;
            }
            .calendar_drop .input-group{
                width: 100%;
                display: inline-block;
            }
            .calendar_drop .chosen-container{
                width: 100% !important;
                display: inline-block;
            }
        }
        /**/
    </style>
@endsection

@section('content')
    <link id="bsdp-css" href="{{URL::to('/')}}/css/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <style>
        .active_menu {
            background-color: #ececec;
        }
        .chosen-container .chosen-results {
            max-height:150px;
        }
    </style>
    <div>
        <div class="row m-t-n-lg">
            <div class="panel panel-default" style="margin-bottom: 10px;">
                <div class="panel-body" style="height: 40px;padding:0; margin-bottom: 0;">
                    <div class="col-md-6">
                        <label style="font-size: 22px;font-weight:bold;padding-right: 20px;">配車カレンダー</label>
                        @foreach($shops as $shop)
                            <label>
                                <a href="{{URL::to('/')}}/carinventory/calendar/{{$shop->id}}" style="padding: 5px" class="list-group-item @if($shop_id == $shop->id) active_menu @endif " >
                                    <label style="margin: 0">{{$shop->name}}</label>
                                </a>
                            </label>
                        @endforeach
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <form id="searchform" action="{{URL::to('/')}}/carinventory/calendar/{{$shop_id}}" method="post">
                                {!! csrf_field() !!}
                                <div class="row" style="margin-top: 5px;">
                                    <div class="col-md-3 form-group calendar_drop">
                                        <select id="startdate" class="chosen-select form-control" name="startdate" onchange="send()" >
                                            @foreach($months as $month)
                                                <?php
                                                $select = ($month == $startdate)? 'selected' : '';
                                                $ym = explode('-', $month);
                                                ?>
                                                <option value="{{$month}}" {{$select}}>
                                                    @if($ym[0] != date('Y')) {{ $ym[0] }}年 @endif
                                                    {{$ym[1]}}月
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group calendar_drop">
                                        <div class="input-group">
                                            <select class="chosen-select form-control" name="class_id" id="class_id" onchange="send()" >
                                                <option value="">全てのクラス</option>
                                                @foreach($classes as $class)
                                                    <?php
                                                    $select = '';
                                                    if($class_id == $class->id) $select="selected";
                                                    ?>
                                                    <option value="{{$class->id}}" {{$select}}> {{$class->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group calendar_drop">
                                        <select id="smoke_select" class="chosen-select form-control" name="smoke_select" onchange="send()">
                                            <option value="both" @if($smoke_select == 'both') selected @endif>喫煙&禁煙</option>
                                            <option value="1" @if($smoke_select == '1') selected @endif>喫煙</option>
                                            <option value="0" @if($smoke_select == '0') selected @endif>禁煙</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row alert alert-success alert-dismissible" role="alert" style="margin-bottom: 10px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            @if($bookable_days == 0)
                <h4>条件に合う在庫がありません。</h4>
            @else
                @php
//                $total_used = $booked_days + $inspect_days + $subst1_days + $subst2_days;
                $total_used = $booked_days + $subst1_days + $subst2_days;
                $total_perc = round($total_used / $bookable_days * 100, 1);
                $book_perc = round($booked_days / $bookable_days * 100, 1);
                $inspect_perc = round($inspect_days / $bookable_days * 100, 1);
                $subst1_perc = round($subst1_days / $bookable_days * 100, 1);
                $subst2_perc = round($subst2_days / $bookable_days * 100, 1);
                @endphp
                <h4>{{explode('-', $startdate)[1]}}月は{{$total_perc}}%の予約が埋まっています！</h4>
                詳細： 予約 <b>{{$book_perc}}%</b> ({{$booked_days}}日)
                {{--+ 修理車検 <b>{{$inspect_perc}}%</b> ({{$inspect_days}}日)--}}
                + 代車特約 <b>{{$subst1_perc}}%</b> ({{$subst1_days}}日)
                + 事故代車 <b>{{$subst2_perc}}%</b> ({{$subst2_days}}日)
{{--                @if($startdate == date('m'))--}}
                @if(explode('-', $startdate)[1] == date('m'))
                    @php
                        $bookable_days_part2 = $bookable_days - $bookable_days_part1;
//                        $part1_used = $booked_days_part1 + $inspect_days_part1 + $subst1_days_part1 + $subst2_days_part1;
                        $part1_used = $booked_days_part1 + $subst1_days_part1 + $subst2_days_part1;
                        $part2_used = $total_used-$part1_used;
                        $part1_perc = ($bookable_days_part1 == 0)? 0 : round($part1_used * 100 / $bookable_days_part1, 1);
                        $part2_perc = ($bookable_days_part2 == 0)? 0 : round($part2_used * 100 / $bookable_days_part2, 1);
                    @endphp
                    <h4 style="margin:10px 0 0 0">今月1日から本日({{date('d')}}日)までは{{$part1_perc}}% 、本日から月末までは{{$part2_perc}}%埋まっています。</h4>
                @endif
                <p style="margin-top:10px">現在時点の<b>今月の予約数は{{ $booking_count }}件</b>で、<b>予約金額は{{ number_format($booking_price_all) }}円</b>です。</p>
            @endif
        </div>

        <div class="row">
            <div class="panel panel-default shadow-box calendar_event_ht" style="padding: 0">
                <div class="panel-body">
                    <div class="ContenedorTabla table-responsive users-table" style="width: 100%; height:75vh;overflow: auto">
                        <table id="calendar" class="fht-table table table-striped table-condensed table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th class="text-center first-column first-head" style="width:10%">車両</th>
                                @foreach($period as $per)
                                    <th class="text-center second-head" style="padding-right:5px;">{!! $per !!}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($carnames as $car)
                                <tr valign="middle">
                                    <td class="text-center first-column" @if(!empty($car->custom)) rowspan="2" @endif style="vertical-align:middle;">
                                        {{--<td class="text-center" style="vertical-align:middle;">--}}
                                        <a href="{{URL::to('/')}}/carinventory/inventory/{{$car->inventory_id}}" style="font-size:13px;font-weight:700;">
                                            {{$car->shortname}} <br>
                                            {{$car->numberplate}}&nbsp;
                                            @if($car->status == 0) <span class="badge badge-danger">&nbsp;</span> @endif
                                        </a>
                                    </td>
                                    <?php
                                    $prev_id = 0;
                                    foreach($car->occupied as $ocu) {
                                    $days = $ocu['days'];

                                    //                                        if($days > 0 && $prev_id == $ocu['booking_id']) continue;
                                    $type = $ocu['type'];
                                    $color = ($ocu['type'] != '')? 'background-color:'.$ocu['color'] : '';
                                    $colspan = ($days > 0)? 'colspan='.$days : '';
                                    if($type == '') {
                                        $data = '';
                                        $box = '';
                                        $box_id = '';
                                    } else if($type == 'booking') {
                                        $data = $ocu['user_name'].'<br>'.$ocu['timeline'];
                                        $box = 'booking-box';
                                        $box_id = 'book_id='.$ocu['booking_id'];
                                    } else {
                                        if($ocu['inspection']->kind == 1) $kind = '修理/車検';
                                        if($ocu['inspection']->kind == 2) $kind = '代車特約';
                                        if($ocu['inspection']->kind == 3) $kind = '事故代車';
                                        $data = $kind.'<br>'.$ocu['period'];
                                        $box = 'inspection-box';
                                        $box_id = 'ins_id='.$ocu['id'];
                                    }
                                    ?>
                                    @if(!empty($data))
                                        <td class="text-center {{ $box }}" {{ $box_id }} {{ $colspan }} style="font-weight:500; font-size:11px;color:#000;border-bottom:none;vertical-align:middle;padding:0;{{ $color }}" onclick="showInfo({{json_encode($ocu)}})">
                                            {!! $data !!}
                                        </td>
                                    @else
                                        <td class="text-center " style="font-weight:400; font-size:12px;color:#000;border-bottom:none;vertical-align:middle;padding:0;">
                                        </td>
                                    @endif
                                    <?php
                                    }
                                    ?>
                                </tr>
                                @if(!empty($car->customs))
                                <tr  valign="middle" style="height: 15px !important;">
                                    <td> </td>
                                    <?php
                                    $prev = 0;
                                    foreach($car->customs as $cst) {
                                    $days = $cst['days'];
                                    if($days > 0 && $prev == $days) continue;
                                    $bgcolor = ($cst['value'] > 0)? 'background-color:'.$cst['bgcolor'].';' : '';
                                    $color = ($cst['value'] > 0)? 'color:'.$cst['color'].';' : '';
                                    $colspan = ($days > 0)? 'colspan='.$days.'' : '';
                                    $data = ($cst['value'] > 0)? $cst['data'] : '';
                                    $prev = $days;
                                    ?>
                                    <td class="text-center" {{ $colspan }} style="border-top:none;border-right:solid #e7e7e7 1px !important ; vertical-align:middle;padding:0 !important;font-size: 10px;{{ $bgcolor }} {{ $color }}">
                                        {!! $data !!}
                                    </td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--@include('modals.modal-delete')--}}

    <div class="modal fade" id="mdlInfo" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="info-title"></h4>
                </div>
                <div class="modal-body" id="info_content">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success pull-right" data-dismiss="modal" type="button">閉じる</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <style>
        div.dataTables_wrapper {
            margin: 0 auto;
        }
        .users-table thead tr th {
            white-space: nowrap;
        }
        .users-table thead tr th {
            white-space: nowrap;
        }

        .top-right{
            position: absolute;
            right: 0;
            top: -11px;
        }
        .table-striped>tbody>tr {
            background-color: #f9f9f9 !important;
        }
        .info-close, .info-detail {
            font-size: 16px;
            cursor: pointer;
        }
        .booking-box, .inspection-box {
            cursor: pointer;
        }
        .badge-danger {
            width: 10px;
            height: 12px;
        }
    </style>
    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script src="{{URL::to('/')}}/js/tableHeadFixer.js"></script>
    <script>
        function showInfo(data) {
            console.log(data);
            var kind = data.type;
            var content = '', title = '';
            if(kind == 'booking'){
                title = '予約情報';
                content = '<p>'+ data.user_name + ' 様 / ' + data.repeated + '</p>' +
                    '<p>' + data.phone + '</p>' +
                    '<p style="font-weight:600;font-size:15px;">' + ((data.smoke == 1)? '喫煙':'禁煙') + '</p>' +
                    '<p>経路：' + data.portal_name + '</p>' +
                    '<p>';
                if(data.insurance1 > 0 && data.insurance2 == 0) content += '免責のみ';
                if(data.insurance1 > 0 && data.insurance2 > 0) content += '免責+ワイド';
                if(data.insurance1 == 0 && data.insurance2 == 0) content += '補償なし';
                content += '</p>';
                if( data.options != '') content += '<p>' + data.options + '</p>';
                //content += '<p>' + data.payment.toLocaleString() + '円</p>' +
                content += '<p>' + (parseInt(data.paidamount)+ parseInt(data.unpaidamount)).toLocaleString() + '円</p>' +
                    '<p><a href="{{URL::to('/')}}/booking/detail/' + data.booking_id + '" class="seedetail">詳細を見る</a></p>';
            } else {
                var ins = data.inspection;
                var car = data.car;
                var kind = ins.kind;
                if(kind == 1) kind = '修理/車検';
                if(kind == 2) kind = '代車特約';
                if(kind == 3) kind = '事故代車';
                title = '検査情報';
                content = '<p>ID : '+ ins.inspection_id + '</p>' +
                    '<p>タイプ: ' + kind + '</p>' +
                    '<p>期間: ' + ins.begin_date + ' - ' + ins.end_date + '</p>' +
                    '<p>車両: ' + car.shortname + ' ' + ((car.smoke == 1)? '喫煙':'禁煙') + '</p>' +
                    '<p>料金：' + ins.price.toLocaleString() + '円</p>' +
                    '<p>マイレージ: ' + ins.mileage + '</p>';
            }
            $('#info-title').html(title);
            $('#info_content').html(content);
            $('#mdlInfo').modal('show');
        }

        /*search and select*/
        $(".chosen-select").chosen({
            max_selected_options: 5,
            no_results_text: "何も見つかりません！"
        });

        //send
        function send() {
            $('#searchform').submit();
        }

        //table style
        $(document).ready(function() {
            $("#calendar").tableHeadFixer({"left" : 1});
        });
    </script>
    {{--@include('scripts.delete-modal-script')--}}
    {{--@include('scripts.save-modal-script')--}}
@endsection