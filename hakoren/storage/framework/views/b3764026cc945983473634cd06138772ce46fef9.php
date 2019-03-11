<?php $__env->startSection('template_title'); ?>
    予約売上管理表
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
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
        @media  screen and (max-width: 1024px){
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

        @media  screen and (max-width: 768px){
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
        .sum_title {
            text-align: center; font-weight: bold
        }
        /**/
        th {
            text-align: center;
        }

    </style>
<?php $__env->stopSection(); ?>
<?php $service_booking = app('App\Http\Controllers\BookingManagementController'); ?>
<?php $__env->startSection('content'); ?>
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
        function displayEmpty($val) {
           if($val == '0') return '';
           else return $val;
        }
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-2">
                        <h2>予約売上管理表</h2>
                    </div>
                    <div class="col-md-10 m-t-sm">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="all_tabel_wrap">
                                    <label class="m-l-sm title_width">店舗</label>
                                    <label class="m-l-sm all_width">
                                        <select name="shop_id" id="shop_id" class="form-control" onchange="changedateshop()">
                                            <option value="0">全体</option>
                                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($shop->id); ?>" <?php if($shop_id == $shop->id): ?> selected <?php endif; ?>><?php echo e($shop->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </label>
                                    <label class="all_width" onchange="changedate(event)">
                                        <select name="cond" id="cond" class="form-control" >
                                            <option value="one" <?php if($cond == 'one'): ?> selected <?php endif; ?> >1日</option>
                                            <option value="day" <?php if($cond == 'day'): ?> selected <?php endif; ?> >日毎</option>
                                            <option value="month" <?php if($cond == 'month'): ?> selected <?php endif; ?> >月毎</option>
                                        </select>
                                    </label>
                                    <label class="m-l-sm title_width">対象期間</label>
                                    <label class="m-l-sm title_width" >
                                        <div class="input-group date"  id="datepicker" style="position: absolute; width: 200px;margin-top: -1.2em">
                                            <input type="text" name="date" id="date" class="form-control input-sm datetimepicker" value="<?php echo e($date); ?>" readonly required>
                                            <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <form method="POST" name="searchform" id="searchform" action="<?php echo e(URL::to('/')); ?>/sales/authmanagement" accept-charset="UTF-8" role="form" class="form-horizontal" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

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
                    <?php if($cond == 'one'): ?>
                        <!--first start section-->
                        <h2></h2>
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="sum_title">差し引き金額</th>
                                    </tr>
                                    <tr>
                                        <td class="sum_title"><?php echo e(displayEmpty(number_format($sum))); ?>円</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-1" style="vertical-align: middle">
                                <table class="table" style="margin-top: 20px;">
                                    <tr>
                                        <th rowspan="2" class="sum_title" style="font-size: 25px;"> = </th>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-3">
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="sum_title">予約合計金額(<?php echo e(displayEmpty(number_format($reser_price->original_count))); ?>件)</th>
                                    </tr>
                                    <tr>
                                        <td class="sum_title"><?php echo e(displayEmpty(number_format($reser_price->price))); ?>円</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-1" style="vertical-align: middle">
                                <table class="table" style="margin-top: 20px;">
                                    <tr>
                                        <th rowspan="2" class="sum_title" style="font-size: 25px;"> - </th>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-3">
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="sum_title">予約のキャンセル金額(<?php echo e(displayEmpty(number_format($cancel_price->count))); ?>件)</th>
                                    </tr>
                                    <tr>
                                        <td class="sum_title"><?php echo e(displayEmpty(number_format($cancel_price->price))); ?>円</td>
                                    </tr>
                                </table>
                            </div>
                      </div>
                        <!--end first section-->
                        <!--start reservation -->
                        <div class="row">
                            <h2>
                                予約 <?php echo e(displayEmpty(number_format($reser_price->original_count))); ?>件
                                <?php if($reser_price->additional_count > 0): ?>
                                    追加注文 <?php echo e(displayEmpty(number_format($reser_price->additional_count))); ?>件
                                <?php endif; ?>
                            </h2>
                            <table class="table table-bordered" style="width:auto;text-align:center">
                                <thead>
                                <tr>
                                    <th style="width:100px;text-align:center">ID</th> <!--id-->
                                    <th style="width:150px;text-align:center">氏名</th> <!--name-->
                                    <th style="width:150px;text-align:center">支払</th> <!--payment-->
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
                                <?php $__currentLoopData = $temporal_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $de): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><a href="<?php echo e(URL::to('/')); ?>/booking/detail/<?php echo e($de->book_id); ?>" title="予約詳細を見る">
                                                <span> <?php echo e($de->name); ?> </span>
                                            </a>
                                        </td> <!--id-->
                                        <td><?php echo e($de->user_name); ?></td> <!--id-->
                                        <td>
                                        <span class="new_row" >
                                            <label><?php echo e(displayEmpty(number_format($de->payment))); ?></label>
                                            円
                                        </span>
                                            <span><?php if(!empty($de->pay_method)): ?>(<?php echo e($de->pay_method); ?>)<?php endif; ?></span>
                                        </td> <!--portal payment-->
                                        <td><?php echo e(displayEmpty(number_format($de->basic_price))); ?></td> <!--basic price-->
                                        <td><?php echo e(displayEmpty(number_format($de->insurance1))); ?></td> <!--insurance1-->
                                        <td><?php echo e(displayEmpty(number_format($de->insurance2))); ?></td> <!--insurance2-->
                                        <td><?php echo e(displayEmpty(number_format($de->etc))); ?></td><!--etc-->
                                        <td><?php echo e(displayEmpty(number_format($de->baby_seat))); ?></td><!--bayby seat-->
                                        <td><?php echo e(displayEmpty(number_format($de->child_seat))); ?></td><!--child seat-->
                                        <td><?php echo e(displayEmpty(number_format($de->junior_seat))); ?></td><!--junior seat-->
                                        <td><?php echo e(displayEmpty(number_format($de->snow_type))); ?></td> <!--snow type-->
                                        <td><?php echo e(displayEmpty(number_format($de->smart_return))); ?></td> <!--drive smart out-->
                                        <!--<td><?php echo e(displayEmpty(number_format($de->hotel_return))); ?></td>--> <!--hote lreturn-->
                                        <td><?php echo e(displayEmpty(number_format($de->adjustment_price))); ?></td> <!--adjust price-->
                                        <td><?php echo e(displayEmpty(number_format($de->extend_price))); ?></td> <!--extend night-->
                                        <td><?php echo e(displayEmpty(number_format($de->point))); ?></td> <!--point-->
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
                                    $total_sub  = $total_basic_price+ $total_insurance1+ $total_insurance2 ;
                                    $total_sub  += $total_etc + $total_baby_seat + $total_child_seat + $total_junior_seat;
                                    $total_sub  += $total_snow_type + $total_smart_return + $total_hotel_return ;
                                    $total_sub  += $total_adjustment_price + $total_extend_price + $total_point;

                                    ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td colspan="2">小計</td> <!--id-->
                                    <td>
                                        <span class="new_row" >
                                            <label><?php echo e(displayEmpty(number_format($total_payment))); ?></label>
                                            円
                                        </span>
                                    </td> <!--portal payment-->
                                    <td><?php echo e(displayEmpty(number_format($total_basic_price))); ?></td> <!--basic price-->
                                    <td colspan="2" style="text-align: center"><?php echo e(displayEmpty(number_format($total_insurance1+$total_insurance2))); ?></td> <!--insurance1-->
                                    <td colspan="6" style="text-align: center"><?php echo e(displayEmpty(number_format($total_etc + $total_baby_seat +$total_child_seat + $total_junior_seat + $total_snow_type + $total_smart_return + $total_hotel_return))); ?></td><!--etc-->
                                    <td><?php echo e(displayEmpty(number_format($total_adjustment_price))); ?></td> <!--adjust price-->
                                    <td><?php echo e(displayEmpty(number_format($total_extend_price))); ?></td> <!--extend night-->
                                    <td><?php echo e(displayEmpty(number_format($total_point))); ?></td> <!--point-->
                                </tr>
                                <tr>
                                    <td colspan="2">合計</td>
                                    <td colspan="13"><?php echo e(displayEmpty(number_format($total_payment+ $total_point))); ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--end reservation -->
                        <!--start cancellatiion-->
                        <div class="row">
                        <h2>キャンセル予約　<?php echo e(displayEmpty(number_format($cancel_price->count))); ?>件</h2>
                        <table class="table table-bordered" style="width:auto;text-align:center">
                            <thead>
                            <tr>
                              <th style="width:100px;text-align:center">ID</th> <!--id-->
                              <th style="width:150px;text-align:center">氏名</th> <!--name-->
                              <th style="width:150px;text-align:center">支払</th> <!--payment-->
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
                            <?php $__currentLoopData = $canceltemporal_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $de): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><a href="<?php echo e(URL::to('/')); ?>/booking/detail/<?php echo e($de->book_id); ?>" title="予約詳細を見る">
                                            <span> <?php echo e($de->name); ?> </span>
                                        </a>
                                    </td> <!--id-->
                                    <td><?php echo e($de->user_name); ?></td> <!--name-->
                                    <td>
                                    <span class="new_row" >
                                        <label><?php echo e(displayEmpty(number_format($de->payment))); ?></label>
                                        円
                                    </span>
                                        <span><?php if(!empty($de->pay_method)): ?> (<?php echo e($de->pay_method); ?>) <?php endif; ?> </span>
                                    </td> <!--portal payment-->
                                    <td><?php echo e(displayEmpty(number_format($de->basic_price))); ?></td> <!--basic price-->
                                    <td><?php echo e(displayEmpty(number_format($de->insurance1))); ?></td> <!--insurance1-->
                                    <td><?php echo e(displayEmpty(number_format($de->insurance2))); ?></td> <!--insurance2-->
                                    <td><?php echo e(displayEmpty(number_format($de->etc))); ?></td><!--etc-->
                                    <td><?php echo e(displayEmpty(number_format($de->baby_seat))); ?></td><!--bayby seat-->
                                    <td><?php echo e(displayEmpty(number_format($de->child_seat))); ?></td><!--child seat-->
                                    <td><?php echo e(displayEmpty(number_format($de->junior_seat))); ?></td><!--junior seat-->
                                    <td><?php echo e(displayEmpty(number_format($de->snow_type))); ?></td> <!--snow type-->
                                    <td><?php echo e(displayEmpty(number_format($de->smart_return))); ?></td> <!--drive smart out-->
                                    <!--<td><?php echo e(displayEmpty(number_format($de->hotel_return))); ?></td>--> <!--hote lreturn-->
                                    <td><?php echo e(displayEmpty(number_format($de->adjustment_price))); ?></td> <!--adjust price-->
                                    <td><?php echo e(displayEmpty(number_format($de->extend_price))); ?></td> <!--extend night-->
                                    <td><?php echo e(displayEmpty(number_format($de->point))); ?></td> <!--point-->
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
                                $total_sub  = $total_basic_price+ $total_insurance1+ $total_insurance2 ;
                                $total_sub  += $total_etc + $total_baby_seat + $total_child_seat + $total_junior_seat;
                                $total_sub  += $total_snow_type + $total_smart_return + $total_hotel_return ;
                                $total_sub  += $total_adjustment_price + $total_extend_price + $total_point;
                                $total  +=$total_sub;
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td colspan="2">小計</td> <!--id-->
                                <td>
                                    <span class="new_row" >
                                        <label><?php echo e(displayEmpty(number_format($total_payment))); ?></label>
                                        円
                                    </span>
                                </td> <!--portal payment-->
                                <td><?php echo e(displayEmpty(number_format($total_basic_price))); ?></td> <!--basic price-->
                                <td colspan="2" style="text-align: center"><?php echo e(displayEmpty(number_format($total_insurance1+$total_insurance2))); ?></td> <!--insurance1-->
                                <td colspan="6" style="text-align: center"><?php echo e(displayEmpty(number_format($total_etc + $total_baby_seat +$total_child_seat + $total_junior_seat + $total_snow_type + $total_smart_return + $total_hotel_return))); ?></td><!--etc-->
                                <td><?php echo e(displayEmpty(number_format($total_adjustment_price))); ?></td> <!--adjust price-->
                                <td><?php echo e(displayEmpty(number_format($total_extend_price))); ?></td> <!--extend night-->
                                <td><?php echo e(displayEmpty(number_format($total_point))); ?></td> <!--point-->
                            </tr>
                            <tr>
                                <td colspan="2">合計</td>
                                <td colspan="13"><?php echo e(displayEmpty(number_format($total_payment+$total_point))); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                        <!--end cancellation-->
                   <?php endif; ?>
                   <?php if($cond == 'day'): ?>
                        <div class="row">
                        <h2></h2>
                        <table class="table table-bordered" style="text-align:center">
                            <thead>
                            <tr>
                                <th></th>
                                <th colspan="3">福岡空港店</th>
                                <th colspan="3">那覇空港店</th>
                                <th colspan="3">鹿児島店</th>
                                <th colspan="3">全体</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>予約件数</th>
                                <th>キャンセル件数</th>
                                <th>差し引き金額</th>
                                <th>予約件数</th>
                                <th>キャンセル件数</th>
                                <th>差し引き金額</th>
                                <th>予約件数</th>
                                <th>キャンセル件数</th>
                                <th>差し引き金額</th>
                                <th>予約件数</th>
                                <th>キャンセル件数</th>
                                <th>差し引き金額</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $fuku_reservation_number_total = 0;
                                $fuku_cancel_number_total = 0;
                                $fuku_price_total = 0;
                                $okina_reservation_number_total = 0;
                                $okina_cancel_number_total = 0;
                                $okina_price_total = 0;
                                $kagoshima_reservation_number_total = 0;
                                $kagoshima_cancel_number_total = 0;
                                $kagoshima_price_total = 0;
                            ?>
                            <?php $__currentLoopData = $result_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $fuku_price     = $sum->fuku_reservation_price - $sum->fuku_cancel_price;
                                    $okina_price    = $sum->okina_reservation_price - $sum->okina_cancel_price;
                                    $kagoshima_price    = $sum->kagoshima_reservation_price - $sum->kagoshima_cancel_price;

                                    $fuku_reservation_number_total  += $sum->fuku_reservation_number;
                                    $fuku_cancel_number_total       += $sum->fuku_cancel_number ;
                                    $fuku_price_total               += $fuku_price;

                                    $okina_reservation_number_total += $sum->okina_reservation_number;
                                    $okina_cancel_number_total      += $sum->okina_cancel_number;
                                    $okina_price_total              += $okina_price;

                                    $kagoshima_reservation_number_total += $sum->kagoshima_reservation_number;
                                    $kagoshima_cancel_number_total      += $sum->kagoshima_cancel_number;
                                    $kagoshima_price_total              += $kagoshima_price;

                                    $all_price              = $fuku_price + $okina_price + $kagoshima_price;
                                    $all_price_total        = $fuku_price_total + $okina_price_total + $kagoshima_price_total;
                                    $all_reservation_number = $sum->fuku_reservation_number + $sum->okina_reservation_number + $sum->kagoshima_reservation_number ;
                                    $all_cancel_number       = $sum->fuku_cancel_number +  $sum->okina_cancel_number + $sum->kagoshima_cancel_number;
                                    $all_reservation_number_total   = $fuku_reservation_number_total + $okina_reservation_number_total + $kagoshima_reservation_number_total;
                                    $all_cancel_number_total        = $fuku_cancel_number_total + $okina_cancel_number_total + $kagoshima_cancel_number_total;

                                ?>
                                <tr>
                                    <td><?php echo e($sum->day); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($sum->fuku_reservation_number))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($sum->fuku_cancel_number))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($fuku_price))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($sum->okina_reservation_number))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($sum->okina_cancel_number))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($okina_price))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($sum->kagoshima_reservation_number))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($sum->kagoshima_cancel_number))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($kagoshima_price))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($all_reservation_number))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($all_cancel_number))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($all_price))); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>小計</td>
                                    <td><?php echo e(displayEmpty(number_format($fuku_reservation_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($fuku_cancel_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($fuku_price_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($okina_reservation_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($okina_cancel_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($okina_price_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($kagoshima_reservation_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($kagoshima_cancel_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($kagoshima_price_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($all_reservation_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($all_cancel_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($all_price_total))); ?></td>
                                </tr>
                                <tr>
                                    <td>合計</td>
                                    <td colspan="12"><?php echo e(displayEmpty(number_format($fuku_price_total+$okina_price_total + $kagoshima_price_total))); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                   <?php endif; ?>
                    <?php if($cond == 'month'): ?>
                        <div class="row">
                            <h2></h2>
                            <table class="table table-bordered" style="text-align:center">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th colspan="3">福岡空港店</th>
                                    <th colspan="3">那覇空港店</th>
                                    <th colspan="3">鹿児島店</th>
                                    <th colspan="3">全体</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th>予約件数</th>
                                    <th>キャンセル件数</th>
                                    <th>差し引き金額</th>
                                    <th>予約件数</th>
                                    <th>キャンセル件数</th>
                                    <th>差し引き金額</th>
                                    <th>予約件数</th>
                                    <th>キャンセル件数</th>
                                    <th>差し引き金額</th>
                                    <th>予約件数</th>
                                    <th>キャンセル件数</th>
                                    <th>差し引き金額</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fuku_reservation_number_total = 0;
                                $fuku_cancel_number_total = 0;
                                $fuku_price_total = 0;
                                $okina_reservation_number_total = 0;
                                $okina_cancel_number_total = 0;
                                $okina_price_total = 0;
                                $kagoshima_reservation_number_total = 0;
                                $kagoshima_cancel_number_total = 0;
                                $kagoshima_price_total = 0;
                                ?>
                                <?php $__currentLoopData = $result_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $fuku_price     = $sum->fuku_reservation_price - $sum->fuku_cancel_price;
                                    $okina_price    = $sum->okina_reservation_price - $sum->okina_cancel_price;
                                    $kagoshima_price    = $sum->kagoshima_reservation_price - $sum->kagoshima_cancel_price;

                                    $fuku_reservation_number_total += $sum->fuku_reservation_number;
                                    $fuku_cancel_number_total += $sum->fuku_cancel_number ;
                                    $fuku_price_total += $fuku_price;

                                    $okina_reservation_number_total += $sum->okina_reservation_number;
                                    $okina_cancel_number_total += $sum->okina_cancel_number;
                                    $okina_price_total += $okina_price;

                                    $kagoshima_reservation_number_total += $sum->kagoshima_reservation_number;
                                    $kagoshima_cancel_number_total += $sum->kagoshima_cancel_number;
                                    $kagoshima_price_total += $kagoshima_price;

                                    $all_price              = $fuku_price + $okina_price + $kagoshima_price;
                                    $all_price_total        = $fuku_price_total + $okina_price_total + $kagoshima_price_total;
                                    $all_reservation_number = $sum->fuku_reservation_number + $sum->okina_reservation_number + $sum->kagoshima_reservation_number;
                                    $all_cancel_number       = $sum->fuku_cancel_number +  $sum->okina_cancel_number + $sum->kagoshima_cancel_number;
                                    $all_reservation_number_total   = $fuku_reservation_number_total + $okina_reservation_number_total + $kagoshima_reservation_number_total;
                                    $all_cancel_number_total        = $fuku_cancel_number_total + $okina_cancel_number_total + $kagoshima_cancel_number_total;
                                    ?>
                                    <tr>
                                        <td><?php echo e($sum->day); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($sum->fuku_reservation_number))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($sum->fuku_cancel_number))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($fuku_price))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($sum->okina_reservation_number))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($sum->okina_cancel_number))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($okina_price))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($sum->kagoshima_reservation_number))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($sum->kagoshima_cancel_number))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($kagoshima_price))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($all_reservation_number))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($all_cancel_number))); ?></td>
                                        <td><?php echo e(displayEmpty(number_format($all_price))); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>小計</td>
                                    <td><?php echo e(displayEmpty(number_format($fuku_reservation_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($fuku_cancel_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($fuku_price_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($okina_reservation_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($okina_cancel_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($okina_price_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($kagoshima_reservation_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($kagoshima_cancel_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($kagoshima_price_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($all_reservation_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($all_cancel_number_total))); ?></td>
                                    <td><?php echo e(displayEmpty(number_format($all_price_total))); ?></td>
                                </tr>
                                <tr>
                                    <td>合計</td>
                                    <td colspan="12"><?php echo e(displayEmpty(number_format($fuku_price_total+$okina_price_total + $kagoshima_price_total ))); ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
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

        @keyframes  spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/dataTables/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/fullcalendar/moment.min.js"></script>

    <script type="text/javascript">
        //moment.tz.setDefault("Europe/Berlin");
        var cond = '<?php echo e($cond); ?>';
        convertdate(cond);
        function convertdate(cond) {
            var new_options = {};
            if(cond == 'one') {
                new_options = {
                    language: "ja",
                    format: 'yyyy-mm-dd',
                    //endDate: '<?php echo e(date('Y-n-j')); ?>',
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
                    //endDate: '<?php echo e(date('Y-n')); ?>',
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
                    //endDate: '<?php echo e(date('Y-n')); ?>',
                    minViewMode: "years",
                    orientation: "bottom",
                    autoclose: true
                }
            }
            if(cond == 'year') {
                new_options = {}
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
            //if(cond != 'month')
                $('#datepicker').datepicker(new_options);
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminapp_calendar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>