<?php $__env->startSection('template_title'); ?>
    配車カレンダー
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/css/plugins/dataTables/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/css/plugins/dataTables/fixedColumns.bootstrap.min.css">
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
        @media  screen and (max-width: 1280px){
            .calendar_drop {
                width: 123px;
            }
            .calendar_drop .chosen-container-single .chosen-single div b {
                background: url(/css/plugins/chosen/chosen-sprite.png) no-repeat 0px 2px !important;
            }
        }
        @media  screen and (max-width: 1024px){
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

        @media  screen and (max-width: 768px){
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <link id="bsdp-css" href="<?php echo e(URL::to('/')); ?>/css/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
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
                        <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label>
                                <a href="<?php echo e(URL::to('/')); ?>/carinventory/calendar/<?php echo e($shop->id); ?>" style="padding: 5px" class="list-group-item <?php if($shop_id == $shop->id): ?> active_menu <?php endif; ?> " >
                                    <label style="margin: 0"><?php echo e($shop->name); ?></label>
                                </a>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <form id="searchform" action="<?php echo e(URL::to('/')); ?>/carinventory/calendar/<?php echo e($shop_id); ?>" method="post">
                                <?php echo csrf_field(); ?>

                                <div class="row" style="margin-top: 5px;">
                                    <div class="col-md-3 form-group calendar_drop">
                                        <select id="startdate" class="chosen-select form-control" name="startdate" onchange="send()" >
                                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                $select = ($month == $startdate)? 'selected' : '';
                                                $ym = explode('-', $month);
                                                ?>
                                                <option value="<?php echo e($month); ?>" <?php echo e($select); ?>>
                                                    <?php if($ym[0] != date('Y')): ?> <?php echo e($ym[0]); ?>年 <?php endif; ?>
                                                    <?php echo e($ym[1]); ?>月
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group calendar_drop">
                                        <div class="input-group">
                                            <select class="chosen-select form-control" name="class_id" id="class_id" onchange="send()" >
                                                <option value="">全てのクラス</option>
                                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                    $select = '';
                                                    if($class_id == $class->id) $select="selected";
                                                    ?>
                                                    <option value="<?php echo e($class->id); ?>" <?php echo e($select); ?>> <?php echo e($class->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group calendar_drop">
                                        <select id="smoke_select" class="chosen-select form-control" name="smoke_select" onchange="send()">
                                            <option value="both" <?php if($smoke_select == 'both'): ?> selected <?php endif; ?>>喫煙&禁煙</option>
                                            <option value="1" <?php if($smoke_select == '1'): ?> selected <?php endif; ?>>喫煙</option>
                                            <option value="0" <?php if($smoke_select == '0'): ?> selected <?php endif; ?>>禁煙</option>
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
            <?php if($bookable_days == 0): ?>
                <h4>条件に合う在庫がありません。</h4>
            <?php else: ?>
                <?php 
//                $total_used = $booked_days + $inspect_days + $subst1_days + $subst2_days;
                $total_used = $booked_days + $subst1_days + $subst2_days;
                $total_perc = round($total_used / $bookable_days * 100, 1);
                $book_perc = round($booked_days / $bookable_days * 100, 1);
                $inspect_perc = round($inspect_days / $bookable_days * 100, 1);
                $subst1_perc = round($subst1_days / $bookable_days * 100, 1);
                $subst2_perc = round($subst2_days / $bookable_days * 100, 1);
                 ?>
                <h4><?php echo e(explode('-', $startdate)[1]); ?>月は<?php echo e($total_perc); ?>%の予約が埋まっています！</h4>
                詳細： 予約 <b><?php echo e($book_perc); ?>%</b> (<?php echo e($booked_days); ?>日)
                
                + 代車特約 <b><?php echo e($subst1_perc); ?>%</b> (<?php echo e($subst1_days); ?>日)
                + 事故代車 <b><?php echo e($subst2_perc); ?>%</b> (<?php echo e($subst2_days); ?>日)

                <?php if(explode('-', $startdate)[1] == date('m')): ?>
                    <?php 
                        $bookable_days_part2 = $bookable_days - $bookable_days_part1;
//                        $part1_used = $booked_days_part1 + $inspect_days_part1 + $subst1_days_part1 + $subst2_days_part1;
                        $part1_used = $booked_days_part1 + $subst1_days_part1 + $subst2_days_part1;
                        $part2_used = $total_used-$part1_used;
                        $part1_perc = ($bookable_days_part1 == 0)? 0 : round($part1_used * 100 / $bookable_days_part1, 1);
                        $part2_perc = ($bookable_days_part2 == 0)? 0 : round($part2_used * 100 / $bookable_days_part2, 1);
                     ?>
                    <h4 style="margin:10px 0 0 0">今月1日から本日(<?php echo e(date('d')); ?>日)までは<?php echo e($part1_perc); ?>% 、本日から月末までは<?php echo e($part2_perc); ?>%埋まっています。</h4>
                <?php endif; ?>
                <p style="margin-top:10px">現在時点の<b>今月の予約数は<?php echo e($booking_count); ?>件</b>で、<b>予約金額は<?php echo e(number_format($booking_price_all)); ?>円</b>です。</p>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="panel panel-default shadow-box calendar_event_ht" style="padding: 0">
                <div class="panel-body">
                    <div class="ContenedorTabla table-responsive users-table" style="width: 100%; height:75vh;overflow: auto">
                        <table id="calendar" class="fht-table table table-striped table-condensed table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th class="text-center first-column first-head" style="width:10%">車両</th>
                                <?php $__currentLoopData = $period; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $per): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="text-center second-head" style="padding-right:5px;"><?php echo $per; ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $carnames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr valign="middle">
                                    <td class="text-center first-column" <?php if(!empty($car->custom)): ?> rowspan="2" <?php endif; ?> style="vertical-align:middle;">
                                        
                                        <a href="<?php echo e(URL::to('/')); ?>/carinventory/inventory/<?php echo e($car->inventory_id); ?>" style="font-size:13px;font-weight:700;">
                                            <?php echo e($car->shortname); ?> <br>
                                            <?php echo e($car->numberplate); ?>&nbsp;
                                            <?php if($car->status == 0): ?> <span class="badge badge-danger">&nbsp;</span> <?php endif; ?>
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
                                    <?php if(!empty($data)): ?>
                                        <td class="text-center <?php echo e($box); ?>" <?php echo e($box_id); ?> <?php echo e($colspan); ?> style="font-weight:500; font-size:11px;color:#000;border-bottom:none;vertical-align:middle;padding:0;<?php echo e($color); ?>" onclick="showInfo(<?php echo e(json_encode($ocu)); ?>)">
                                            <?php echo $data; ?>

                                        </td>
                                    <?php else: ?>
                                        <td class="text-center " style="font-weight:400; font-size:12px;color:#000;border-bottom:none;vertical-align:middle;padding:0;">
                                        </td>
                                    <?php endif; ?>
                                    <?php
                                    }
                                    ?>
                                </tr>
                                <?php if(!empty($car->customs)): ?>
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
                                    <td class="text-center" <?php echo e($colspan); ?> style="border-top:none;border-right:solid #e7e7e7 1px !important ; vertical-align:middle;padding:0 !important;font-size: 10px;<?php echo e($bgcolor); ?> <?php echo e($color); ?>">
                                        <?php echo $data; ?>

                                    </td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
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
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/fullcalendar/moment.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/tableHeadFixer.js"></script>
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
                    '<p><a href="<?php echo e(URL::to('/')); ?>/booking/detail/' + data.booking_id + '" class="seedetail">詳細を見る</a></p>';
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
    
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp_calendar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>