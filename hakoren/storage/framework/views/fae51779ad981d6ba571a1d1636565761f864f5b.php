<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Mainly scripts -->
    <link href="http://fonts.googleapis.com/earlyaccess/mplus1p.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
	<link href="https://fonts.googleapis.com/earlyaccess/sawarabigothic.css" rel="stylesheet" type="text/css" />


   <style>
        body {
            color: #000000 !important;
            font-size:12px;
            font-family: "Sawarabi Gothic", sans-serif;
			font-weight:500;
			padding:0 !important;
			margin:0 !important;
        }
		.row, hr, div {padding:0 !important; margin:0 !important;}
		.noto { font-family: "Sawarabi Gothic"; font-size:16px; }
        .tr_head {
            background-color: #CDCDCD;
        }
        .table-pdf thead tr th {
            text-align: center;
            background-color: #bfbfbf;
            font-style: normal !important;
            text-align: center;
            color: #000000;
        }
        .table-pdf tbody tr td{
            text-align: center;
            color:#000000 !important;
        }
        .cell{
            padding: 0px !important;
			margin:0 !important;
            vertical-align: middle !important;
			text-align:center;
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
        .fa-btn {
            margin-right: 6px;
        }
		.printbooking {
			font-size: 7px;
			font-weight: 500;
		}
		.mb15 {margin-bottom:15px !important;}

		.opstyle {font-size: 6px !important;}
		.line {line-height: 1.2 !important;}
		.portalname {
			padding:0;margin:0;text-align:left !important;vertical-align:top !important;font-size:4px !important;
		}
        .new_row::after {
            content: '\A';
            white-space: pre;
        }
   </style>
</head>
<body>

<?php $service_booking = app('App\Http\Controllers\BookingManagementController'); ?>
<?php
$today   = $service_booking->today_tom('tom');
$today_depart = $today['departings'];
$today_return = $today['returnings'];
$n = 0;
$today_depart_naha = Array();
$today_depart_fukuoka = Array();
$today_return_naha = Array();
$today_return_fukuoka = Array();
	foreach($today_depart as $b){
		if($b->drop_id == 4){
			$today_depart_fukuoka[$n] = $b;
		}elseif($b->drop_id == 5){
			$today_depart_naha[$n] = $b;
		}
		$n++;
	}
	$o = 0;
	foreach($today_return as $r){
		if($r->drop_id == 4){
			$today_return_fukuoka[$o] = $r;
		}elseif($r->drop_id == 5){
			$today_return_naha[$o] = $r;
		}
		$o++;
	}
	if(!empty($today_return_fukuoka)){
	foreach ($today_return_fukuoka as $val) $keys[] = $val->returning;
array_multisort($keys, SORT_ASC, $today_return_fukuoka);
}
	if(!empty($today_return_naha)){
	foreach ($today_return_naha as $val) $keys1[] = $val->returning;
array_multisort($keys1, SORT_ASC, $today_return_naha);
	}
	
	if(!empty($today_depart_fukuoka)){
	foreach ($today_depart_fukuoka as $val) $keys3[] = $val->departing;
array_multisort($keys3, SORT_ASC, $today_depart_fukuoka);
	}
	if(!empty($today_depart_naha)){
	foreach ($today_depart_naha as $val) $keys4[] = $val->departing;
array_multisort($keys4, SORT_ASC, $today_depart_naha);
	}

if($shop == '4') {
    $fukuoka_hidden = '';
    $okinawa_hidden = 'hidden';
    $return_count = count($today_return_fukuoka);
    $depart_count = count($today_depart_fukuoka);
    $shop_name = '福岡空港店';
} else if($shop == '5') {
    $fukuoka_hidden = 'hidden';
    $okinawa_hidden = '';
    $return_count = count($today_return_naha);
    $depart_count = count($today_depart_naha);
    $shop_name = '那覇空港店';
} else {
    $fukuoka_hidden = '';
    $okinawa_hidden = '';
    $return_count = count($today_return_fukuoka) + count($today_return_naha);;
    $depart_count = count($today_depart_fukuoka) + count($today_depart_naha);;
    $shop_name = '';
}

?>
<div class="container">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="window.print();" title="Print Now">印刷</a></td>
    <td align="right"><!--<a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="window.close();" title="Close Window">[X]Close</a>--></td>
  </tr>
</table>

    <div class="row printbooking">
        <!--departting-->
        <div style="margin-top:-50px;font-size:23px;" align="center">
            <span class="noto">【<?php echo e($shop_name); ?>タスク表】 </span>
            <span><?php echo e(date('Y',strtotime("+1 day")).'年 '.date('n',strtotime("+1 day")).'月 '.date('j',strtotime("+1 day")).'日'); ?> (<?php echo e($service_booking->getDate(date('N',strtotime("+1 day")))); ?>) </span>
            <span> 全 <?php echo e($return_count + $depart_count); ?> 件 </span>
        </div>
        <hr>
        <div class="<?php echo e($fukuoka_hidden); ?>">
            <span class="noto"><?php if($shop == '0'): ?> 福岡空港店 <?php endif; ?> 配車 <?php echo e(count($today_depart_fukuoka)); ?> 件</span>
        </div>
        <table class="table table-pdf table-striped table-bordered nowrap mb30 <?php echo e($fukuoka_hidden); ?>" width="100%">
            <thead>
            <tr class="tr_head ">
                <td style="width:3%;">&nbsp;</td>
                <td style="width:2%;">#</td>
                <td style="width:14%;">氏名</td> <!--name-->
                <td style="width:5%;">出</td>
                <td style="width:6%;">返</td>
                <td style="width:9%;">車両</td>
                <td style="width:3%;">数</td>
                <td style="width:7%;">送迎</td>
                <td style="width:3%;" >免</td>
                <td style="width:3%;">ワ</td>
                <td style="width:20%;">オプション(数)</td>
                <td style="width:4%;">QS</td>
                <td style="width:12%;">未払い金</td>
                <td style="width:9%;">他</td>
            </tr>
            </thead>
            <tbody class="">
            <?php $i = 0; ?>
            <?php $__currentLoopData = $today_depart_fukuoka; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $i++;
                $user_name = $book->last_name.' '.$book->first_name;
                if($user_name == ' ') {
                    $user_name = $book->fur_last_name.' '.$book->fur_first_name;
                }
                if($user_name == ' ') {
                    $portal = json_decode($book->portal_info);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $user_name = $portal->last_name.' '.$portal->first_name;
                        if($user_name == ' '){
                            $user_name = $portal->fu_last_name.' '.$portal->fu_first_name;
                        }
                    }
                }
                ?>
                <!---->
                <tr class="<?php if(($i%2) == 1): ?> today_deaprt_1 <?php else: ?> today_depart_2 <?php endif; ?> " valign="middle">
                    <td class="cell" style="padding:0 !important; margin:0 !important;" >&nbsp;

                    </td>
                    <td class="cell"  >
                        <?php echo e($i); ?>

                    </td>
                    <td class="cell line"  >
                        <span class="new_row  "><?php echo e($user_name); ?> 様<br/><?php echo e($book->phone); ?></span>
                    </td>
                    <td class="cell">
                        <span><?php echo e(date('H:i', strtotime($book->departing))); ?> </span>
                    </td>
                    <td class="cell line">
                        <span class="new_row " ><?php echo e(date('m/d', strtotime($book->returning))); ?><br/><?php echo e(date('H:i', strtotime($book->returning))); ?> </span>
                    </td>
                    <td class="cell line">
                        <span class="new_row " ><?php echo e($book->shortname); ?><br/><?php echo e($book->numberplate3); ?><?php echo e($book->numberplate4); ?></span>
                    </td>
                    <td class="cell">
                        <span class=" "><?php echo e($book->passengers); ?></span>
                    </td>
                    <td class="cell">
                        <?php $__currentLoopData = $book->pickups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pickup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="option_detail opstyle"><?php echo e($pickup->option_name); ?></span>/
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="cell">
                        <?php if(!empty($book->insurance1)): ?>
                            <span style="font-size:10px;">免</span>
                        <?php endif; ?>
                    </td>
                    <td class="cell" style="text-align:center" >
                        <?php if(!empty($book->insurance2)): ?>
                            <span style="font-size:10px;">ワ</span>
                        <?php endif; ?>
                    </td>
                    <td class="cell line">
                        <?php $__currentLoopData = $book->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="option_detail opstyle"><?php echo e($option->option_name); ?>(<?php echo e($option->option_number); ?>) </span>/
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($book->given_points > 0): ?> &nbsp;ポ<?php echo e($book->given_points); ?>円 <?php endif; ?>
                    </td>
                    <td class="cell">
                        <span >
                            <?php if($book->web_status == 0): ?> -- <?php else: ?> <?php echo e($book->web_status); ?>/3 <?php endif; ?>
                        </span>
                    </td>
                    <td class="cell">
                        <span class="new_row"><?php echo e(number_format($book->unpaidamount)); ?>円 </span>
                        <span> (<?php echo e(number_format($book->paidamount)); ?>円)</span>
                    </td>
                    <td class="cell portalname">
                        <?php if($book->unpaidamount > 0 && $book->paidamount > 0 ): ?> 一部支払い <?php endif; ?>
                        <?php if($book->portal_name != '自社HP' && $book->portal_name != '電話'): ?> <?php echo e($book->portal_name); ?> <?php endif; ?>
						<?php if($book->web_status_flag == 3): ?>
							<?php if($book->bag_choosed == '1'): ?> フリスク <?php endif; ?>
							<?php if($book->bag_choosed == '2'): ?> ぷっちょ <?php endif; ?>
							<?php if($book->bag_choosed == '3'): ?> 酔い止め <?php endif; ?>
						<?php endif; ?>

                    </td>
                </tr>
                <!---->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <!--retunring-->
        <div  class="<?php echo e($fukuoka_hidden); ?>">
            <span class="noto"><?php if($shop == '0'): ?> 福岡空港店 <?php endif; ?> 返車 <?php echo e(count($today_return_fukuoka)); ?> 件</span>
        </div>
        <table class="table table-pdf table-striped table-bordered nowrap mb30 <?php echo e($fukuoka_hidden); ?>" width="100%">
            <thead>
            <tr class="tr_head">
                <td style="width:3%;">&nbsp;</td>
                <td style="width:2%;">#</td>
                <td style="width:14%;">氏名</td> <!--name-->
                <td style="width:6%;">返</td>
                <td style="width:9%;">車両</td>
                <td style="width:3%;" >免</td>
                <td style="width:3%;">ワ</td>
                <td style="width:20%;">オプション(数)</td>
                <td style="width:7%;">送迎</td>
                <td style="width:40%;">他</td>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0;
            ?>
            <?php $__currentLoopData = $today_return_fukuoka; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $i++;
                $user_name = $book->last_name.' '.$book->first_name;
                if($user_name == ' ') {
                    $user_name = $book->fur_last_name.' '.$book->fur_first_name;
                }
                if($user_name == ' ') {
                    $portal = json_decode($book->portal_info);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $user_name = $portal->last_name.' '.$portal->first_name;
                        if($user_name == ' '){
                            $user_name = $portal->fu_last_name.' '.$portal->fu_first_name;
                        }
                    }
                }
                ?>
                <!---->
                <tr class="<?php if(($i%2) == 1): ?> today_return_1 <?php else: ?> today_return_2 <?php endif; ?> " valign="middle">
                    <td class="cell"  >&nbsp;

                    </td>
                    <td class="cell"  >
                        <?php echo e($i); ?>

                    </td>
                    <td class="cell line"  >
                        <span class="new_row  "><?php echo e($user_name); ?> 様<br/><?php echo e($book->phone); ?></span>
                    </td>
                    <td class="cell">
                        <span><?php echo e(date('H:i', strtotime($book->returning))); ?> </span>
                    </td>
                    <td class="cell line">
                        <span class="new_row " ><?php echo e($book->shortname); ?> <br/><?php echo e($book->numberplate3); ?><?php echo e($book->numberplate4); ?></span>
                    </td>
                    <td class="cell">
                        <?php if(!empty($book->insurance1)): ?>
                            <span style="font-size:10px;">免</span>
                        <?php endif; ?>
                    </td>
                    <td class="cell" style="text-align:center" >
                        <?php if(!empty($book->insurance2)): ?>
                            <span style="font-size:10px;">ワ</span>
                        <?php endif; ?>
                    </td>
                    <td class="cell line">
                    <span class="option_detail opstyle">
                    <?php $__currentLoopData = $book->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($option->option_name); ?>(<?php echo e($option->option_number); ?>)/
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </span>
                    </td>
                    <td class="cell">
                        <?php $__currentLoopData = $book->pickups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pickup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="option_detail opstyle"><?php echo e($pickup->option_name); ?></span>/
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="cell portalname">
                        <?php if($book->portal_name != '自社HP' && $book->portal_name != '電話'): ?> <?php echo e($book->portal_name); ?> <?php endif; ?>
                    </td>
                </tr>
                <!---->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <!--end fukuoka-->

        <hr>
        <div class="<?php echo e($okinawa_hidden); ?>">
            <span class="noto"><?php if($shop == '0'): ?> 那覇空港店 <?php endif; ?> 配車 <?php echo e(count($today_depart_naha)); ?> 件</span>
        </div>
        <table class="table table-pdf table-striped table-bordered nowrap mb30 <?php echo e($okinawa_hidden); ?>" width="100%">
            <thead>
            <tr class="tr_head ">
                <td style="width:3%;">&nbsp;</td>
                <td style="width:2%;">#</td>
                <td style="width:14%;">氏名</td> <!--name-->
                <td style="width:5%;">出</td>
                <td style="width:6%;">返</td>
                <td style="width:9%;">車両</td>
                <td style="width:3%;">数</td>
                <td style="width:7%;">送迎</td>
                <td style="width:3%;" >免</td>
                <td style="width:3%;">ワ</td>
                <td style="width:20%;">オプション(数)</td>
                <td style="width:4%;">QS</td>
                <td style="width:12%;">総計</td>
                <td style="width:9%;">他</td>
            </tr>
            </thead>
            <tbody class="">
            <?php $i = 0; ?>
            <?php $__currentLoopData = $today_depart_naha; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $i++;
                $user_name = $book->last_name.' '.$book->first_name;
                if($user_name == ' ') {
                    $user_name = $book->fur_last_name.' '.$book->fur_first_name;
                }
                if($user_name == ' ') {
                    $portal = json_decode($book->portal_info);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $user_name = $portal->last_name.' '.$portal->first_name;
                        if($user_name == ' '){
                            $user_name = $portal->fu_last_name.' '.$portal->fu_first_name;
                        }
                    }
                }
                ?>
                <!---->
                <tr class="<?php if(($i%2) == 1): ?> today_deaprt_1 <?php else: ?> today_depart_2 <?php endif; ?> " valign="middle">
                    <td class="cell" style="padding:0 !important; margin:0 !important;" >&nbsp;

                    </td>
                    <td class="cell"  >
                        <?php echo e($i); ?>

                    </td>
                    <td class="cell line"  >
                        <span class="new_row  "><?php echo e($user_name); ?> 様<br/><?php echo e($book->phone); ?></span>
                    </td>
                    <td class="cell">
                        <span><?php echo e(date('H:i', strtotime($book->departing))); ?> </span>
                    </td>
                    <td class="cell line">
                        <span class="new_row " ><?php echo e(date('m/d', strtotime($book->returning))); ?><br/><?php echo e(date('H:i', strtotime($book->returning))); ?> </span>
                    </td>
                    <td class="cell line">
                        <span class="new_row " ><?php echo e($book->shortname); ?><br/><?php echo e($book->numberplate3); ?><?php echo e($book->numberplate4); ?></span>
                    </td>
                    <td class="cell">
                        <span class=" "><?php echo e($book->passengers); ?></span>
                    </td>
                    <td class="cell">
                        <?php $__currentLoopData = $book->pickups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pickup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="option_detail opstyle"><?php echo e($pickup->option_name); ?></span>/
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="cell">
                        <?php if(!empty($book->insurance1)): ?>
                            <span style="font-size:10px;">免</span>
                        <?php endif; ?>
                    </td>
                    <td class="cell" style="text-align:center" >
                        <?php if(!empty($book->insurance2)): ?>
                            <span style="font-size:10px;">ワ</span>
                        <?php endif; ?>
                    </td>
                    <td class="cell line">
                        <?php $__currentLoopData = $book->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="option_detail opstyle"><?php echo e($option->option_name); ?>(<?php echo e($option->option_number); ?>)</span>/
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($book->given_points > 0): ?> &nbsp;ポ<?php echo e($book->given_points); ?>円 <?php endif; ?>
                    </td>
                    <td class="cell">
                        <span ><?php if($book->web_status_flag == 0): ?> --
                            <?php else: ?>
                                <?php if($book->web_status_flag == 3): ?>
                                <?php else: ?>
                                    <?php echo e($book->web_status_flag); ?>/3
                                <?php endif; ?>
                            <?php endif; ?>
                        </span>
                    </td>
                    <td class="cell">
                        <span class=""><?php echo e(number_format($book->paidamount + $book->unpaidamount)); ?>円</span>
                    </td>
                    <td class="cell">&nbsp;
                        <?php if($book->portal_name != '自社HP' && $book->portal_name != '電話'): ?> <?php echo e($book->portal_name); ?> <?php endif; ?>
						<?php if($book->web_status_flag == 3): ?>
							<?php if($book->bag_choosed == '1'): ?> フリスク <?php endif; ?>
							<?php if($book->bag_choosed == '2'): ?> ぷっちょ <?php endif; ?>
							<?php if($book->bag_choosed == '3'): ?> 酔い止め <?php endif; ?>
						<?php endif; ?>
                    </td>
                </tr>
                <!---->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <!--retunring-->
        <div class="<?php echo e($okinawa_hidden); ?>">
            <span class="noto"><?php if($shop == '0'): ?> 那覇空港店 <?php endif; ?> 返車 <?php echo e(count($today_return_naha)); ?> 件</span>
        </div>
        <table class="table table-pdf table-striped table-bordered nowrap mb30 <?php echo e($okinawa_hidden); ?>" width="100%">
            <thead>
            <tr class="tr_head">
                <td style="width:3%;">&nbsp;</td>
                <td style="width:2%;">#</td>
                <td style="width:14%;">氏名</td> <!--name-->
                <td style="width:6%;">返</td>
                <td style="width:9%;">車両</td>
                <td style="width:3%;" >免</td>
                <td style="width:3%;">ワ</td>
                <td style="width:20%;">オプション(数)</td>
                <td style="width:7%;">送迎</td>
                <td style="width:40%;">他</td>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0; ?>
            <?php $__currentLoopData = $today_return_naha; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $i++;
                $user_name = $book->last_name.' '.$book->first_name;
                if($user_name == ' ') {
                    $user_name = $book->fur_last_name.' '.$book->fur_first_name;
                }
                if($user_name == ' ') {
                    $portal = json_decode($book->portal_info);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $user_name = $portal->last_name.' '.$portal->first_name;
                        if($user_name == ' '){
                            $user_name = $portal->fu_last_name.' '.$portal->fu_first_name;
                        }
                    }
                }
                ?>
                <!---->
                <tr class="<?php if(($i%2) == 1): ?> today_return_1 <?php else: ?> today_return_2 <?php endif; ?> " valign="middle">
                    <td class="cell"  >&nbsp;

                    </td>
                    <td class="cell"  >
                        <?php echo e($i); ?>

                    </td>
                    <td class="cell line"  >
                        <span class="new_row  "><?php echo e($user_name); ?> 様<br/><?php echo e($book->phone); ?></span>
                    </td>
                    <td class="cell">
                        <span><?php echo e(date('H:i', strtotime($book->returning))); ?> </span>
                    </td>
                    <td class="cell line">
                        <span class="new_row " ><?php echo e($book->shortname); ?> <br/><?php echo e($book->numberplate3); ?><?php echo e($book->numberplate4); ?></span>
                    </td>
                    <td class="cell">
                        <?php if(!empty($book->insurance1)): ?>
                            <span style="font-size:10px;">免</span>
                        <?php endif; ?>
                    </td>
                    <td class="cell" style="text-align:center" >
                        <?php if(!empty($book->insurance2)): ?>
                            <span style="font-size:10px;">ワ</span>
                        <?php endif; ?>
                    </td>
                    <td class="cell line">
                    <span class="option_detail opstyle">
                    <?php $__currentLoopData = $book->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($option->option_name); ?>(<?php echo e($option->option_number); ?>)/
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </span>
                    </td>
                    <td class="cell">
                        <?php $__currentLoopData = $book->pickups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pickup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="option_detail opstyle"><?php echo e($pickup->option_name); ?></span>/
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td class="cell">
                        <?php if($book->portal_name != '自社HP' && $book->portal_name != '電話'): ?> <?php echo e($book->portal_name); ?> <?php endif; ?>
                    </td>
                </tr>
                <!---->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <!--end naha-->
    </div>
</div>
</body>
</html>