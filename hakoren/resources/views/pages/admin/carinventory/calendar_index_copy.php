<?php
//        echo $carnames[0]->class_id.'<br>';
//var_dump($carnames[0]->occupied);
?>

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
    }

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
        <div class="panel panel-default">
            <div class="panel-body" style="padding:0px; margin-bottom: 0px;">
                <div class="col-md-6">
                    <label style="font-size: 22px;font-weight:bold;padding-right: 20px;">配車カレンダー</label>
                    @foreach($shops as $shop)
                    <label>
                        <a href="{{URL::to('/')}}/carinventory/calendar/{{$shop->id}}" style="padding: 5px" class="list-group-item @if($shop_id == $shop->id) active_menu @endif " >
                            <label>{{$shop->name}}</label>
                        </a>
                    </label>
                    @endforeach
                </div>
                <div class="col-md-4">
                    <div class="col-md-12">
                        <form id="searchform" action="{{URL::to('/')}}/carinventory/calendar/{{$shop_id}}" method="post">
                            {!! csrf_field() !!}
                            <div class="row" style="margin-top: 5px;">
                                <div class="col-md-4 col-md-offset-4">
                                    <div class="input-group" >
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
                                <div class="col-md-3  form-group">
                                    <select id="startdate" class="chosen-select form-control" name="startdate" onchange="send()" >
                                        @foreach($months as $month)
                                        <?php
                                        $select = ($month == $startdate)? 'selected' : '';
                                        ?>
                                        <option value="{{$month}}" {{$select}}>{{$month}}月</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div>
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    <div class="table-responsive users-table" style="width: 100%; height:60vh;overflow: auto">
                        <table class="table table-striped table-condensed data-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="150" class="text-center" style="vertical-align:middle;padding-right:5px;">車両</th>
                                @foreach($period as $per)
                                <th class="text-center" style="padding-right:5px;">{!! $per !!}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($carnames as $car)
                            <tr  valign="middle">
                                <td class="text-center" rowspan="2" style="vertical-align:middle;">
                                    {{--<td class="text-center" style="vertical-align:middle;">--}}
                                    <a href="{{URL::to('/')}}/carinventory/inventory/{{$car->inventory_id}}">
                                        <span class="font-size:8px;font-weight:500;">{{$car->shortname}}</span><br><span style="font-size:7px;font-weight:500;">{{$car->numberplate}}</span>
                                    </a>
                                </td>
                                <?php
                                $prev = 0;
                                foreach($car->occupied as $ocu) {
                                    $days = $ocu['days'];
                                    if($days > 0 && $prev == $days) continue;
                                    $color = ($ocu['value'] > 0)? 'background-color:'.$ocu['color'] : '';
                                    $colspan = ($days > 0)? 'colspan='.$days.'' : '';
                                    $data = ($ocu['value'] > 0)? $ocu['user_name'].'<br>'.$ocu['timeline'] : '';
                                    $prev = $days;
                                    $bookingbox = ($days > 0)? 'booking-box':'';
                                    $book_id = ($days > 0)? 'book_id='.$ocu['booking_id'].'' : '';
                                    ?>
                                    <td class="text-center {{ $bookingbox }}" {{ $book_id }} {{ $colspan }} style="font-weight:400; font-size:12px;color:#000;border-bottom:none;vertical-align:middle;padding:0;{{ $color }}">
                                        {!! $data !!}
                                    </td>
                                    @if($book_id !== '')
                                    <div class="info-box text-center" {{ $book_id }}>
                                        <p style="padding-bottom: 10px">
                                            <i class="fa fa-times info-close pull-right" {{ $book_id }}></i>
                                            <i class="fa fa-info-circle info-detail pull-right" {{ $book_id }} style="margin-right: 5px"></i>
                                        </p>
                                        {{ $ocu['user_name'] }}<br>
                                        {{ $ocu['phone'] }} <br>
                                        {{ ($car->smoke == 1)? '喫煙':'禁煙' }}<br>
                                    </div>

                                    @endif
                                    <?php
                                }
                                ?>
                            </tr>
                            <tr  valign="middle" style="height: 15px !important;">
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
                                    <td class="text-center" {{ $colspan }} style="border-top:none;vertical-align:middle;padding:0 !important;font-size: 10px;{{ $bgcolor }} {{ $color }}">
                                        {!! $data !!}
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{--@include('modals.modal-delete')--}}

@endsection

@section('footer_scripts')
<style>
    div.dataTables_wrapper {
        /*width: 1824px;*/
        margin: 0 auto;
    }
    .data-table thead tr th {
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
    .booking-box {
        cursor: pointer;
    }
</style>
<script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
<script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>
<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
<script src="{{URL::to('/')}}/js/plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/js/plugins/dataTables/dataTables.fixedColumns.min.js"></script>
<script>

    $('td.booking-box').click(function (e) {
        var book_id = $(this).attr('book_id');
        var selector = '[book_id="' + book_id + '"]';
        var $infobox = $('.info-box' + selector);
        var bw = $infobox.width(), bh = $infobox.height();
        var ww = $(window).width(), wh = $(window).height();
        var w = $(this).width(), h = $(this).height();
        var docViewTop = $(window).scrollTop(),
            docViewBottom = docViewTop + wh;
        var offset = $(this).position(),
            elemTop = offset.top, elemBottom = elemTop + h, ol = offset.left, or = ol + w;
        var bx = 0, by = 0;

        if(elemTop  <= docViewTop + bh) { by = elemBottom + 30; }
        else //if(elemBottom <= docViewBottom)
        { by = elemTop - bh - 10; }

        if(or + bw > ww ) {
            bx = ol - bw;
        } else if(ol < bw ) {
            bx = or;
        } else {
            bx = (ol + or - bw )/2;
        }
        $infobox.css('top', by).css('left', bx);
        $infobox.fadeIn();
    });
    $('td.booking-box').mouseenter(function (e) {
        // $(this).click();
    });
    $('td.booking-box').mouseleave(function (e) {
        // var book_id = $(this).attr('book_id');
        // var selector = '[book_id="' + book_id + '"]';
        // $('.info-box' + selector).fadeOut();
    });
    $('.info-close').click( function () {
        var book_id = $(this).attr('book_id');
        var selector = '[book_id="' + book_id + '"]';
        $('.info-box' + selector).fadeOut();
    });
    $('.info-detail').click( function () {
        var book_id = $(this).attr('book_id');
        alert(book_id);
    });
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
        var $table = $('.data-table').dataTable({
            deferRender:    true,
            scrollY:        200,
            scrollX:        true,
            scrollCollapse: true,
            scroller:       true,
            "fixedColumns": {
                leftColumns: 1
            },
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "pageLength" : 25,
            "serverSide": false,
            "ordering": false,
            "info": false,
            // "autoWidth": true,
            //"dom": 'T<"clear">lfrtip',
            "dom": '<"top-right"f>t',
            "sPaginationType": "full_numbers",
            "language": {
                "info": "_START_ ~ _END_ を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全ての項目 _TOTAL_"
            }
        });
    });
</script>
{{--@include('scripts.delete-modal-script')--}}
{{--@include('scripts.save-modal-script')--}}
@endsection