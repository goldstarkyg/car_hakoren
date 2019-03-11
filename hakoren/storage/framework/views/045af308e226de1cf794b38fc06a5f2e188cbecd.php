<?php $__env->startSection('template_title'); ?>
    空車検索
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
	<link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/css/admin_search_plans.css">
    <link rel="stylesheet" href="<?php echo e(URL::to('/')); ?>/css/plugins/dataTables/dataTables.bootstrap.min.css">
    

    <style type="text/css" media="screen">
        .chosen-single{ height: 2.2em !important; }
        .opt_num { width:50px; text-align: center; }
        .users-table { border: 0; }
        .users-table tr td:first-child { padding-left: 15px; }
        .users-table tr td:last-child { padding-right: 15px; }
        .users-table.table-responsive,
        .users-table.table-responsive table { margin-bottom: 0; }
        .datepicker { background: #fff; }

        /**/
        .search_plan_wrap .form-group{
            margin-bottom: 10px;
        }
        .search_plan_wrap .form-group .control-label {
            padding: 0px;
            padding-top: 5px;
            font-size: 13px;
        }
        .search_plan_wrap .form_one{
            font-size: 16px;
            margin-bottom: 20px;
        }
        /*.search_plan_wrap .lbl-btn {*/
            /*!*width: 44px;*!*/
            /*margin-right: 5px;*/
        /*}*/
        .search_plan_wrap .bg-grad-red{
            width: 100%;
            margin-top: 25px;
        }
        .search_plan_wrap .checkbox{
            margin-bottom: 0px;
        }
        .search_plan_wrap #option_wrapper .form-group{
            margin-bottom: 5px;
        }
        .search_plan_wrap input#depart_date,
        .search_plan_wrap input#return_date{
            padding: 3px 5px;
        }
        @media  screen and (max-width: 1024px){
            .plan_drop .chosen-container{
                width: 100% !important;
            }
        }
        /**/
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>空車検索</h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="alert alert-success alert-dismissible" style="display: none">
                    <a class="close" onclick="hideAlert()">&times;</a>
                    <strong>確定!</strong> ご予約が確定しました。
                </div>

                <div class="panel-body">
					<div class="row search_plan_wrap">
						<div class="col-lg-6 col-md-12 box_plan">
							<div class="box-shadow relative search-panel" style="border: none !important;">
                                <div class="row" style="margin-left: 0px; margin-right: 0px;">
                                    <div class="col-xs-12" style="padding-top:10px;">
                                        
                                        
                                            <?php echo csrf_field(); ?>

                                        <div id="searchform">
                                            <div class="col-sm-7">
                                                <label for="title" class="row form_one">基本情報</label>
                                                <div class="form-group row" style="padding-bottom: 5px">
                                                    <label class="col-sm-2 control-label">利用店</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control input-sm" name="depart_shop" id="depart-shop" required>
                                                            <option value="0">選択してください </option>
                                                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($shop->id); ?>" <?php if($search->depart_shop == $shop->id): ?> selected <?php endif; ?>><?php echo e($shop->name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="padding-bottom: 5px">
                                                    <label class="col-sm-2 control-label">担当者</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control input-sm" name="staff" id="staff" required>
                                                            <option value="0" shop="0">選択してください </option>
                                                            <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                $staff_name = $staff->last_name.$staff->first_name;
                                                                if($staff_name == '') {
                                                                    $staff_name = $staff->fur_last_name.$staff->fur_first_name;
                                                                }
                                                                ?>
                                                                <?php if($staff_name != ''): ?>
                                                                <option value="<?php echo e($staff->admin_id); ?>" shop="<?php echo e($staff->shop_id); ?>" <?php if($search->staff == $staff->admin_id): ?> selected <?php endif; ?>><?php echo e($staff_name); ?></option>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="padding-bottom: 5px;">
                                                    <label class="col-sm-2 control-label">出発日</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group date col-sm-7 pull-left"  id="depart-datepicker">
                                                            <input type="text" name="depart_date" id="depart_date" class="form-control input-sm datetimepicker" value="<?php echo e($search->depart_date); ?>" readonly required>
                                                            <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                        <div class="col-sm-5 plan_drop" style="padding-right: 0">
                                                            <select class="chosen-select form-control select-md" name="depart_time" id="depart_time" required>
                                                                <?php $__currentLoopData = $hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($hour); ?>" <?php if($search->depart_time == $hour): ?> selected <?php endif; ?>>
                                                                        <?php echo e($hour); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="padding-bottom: 5px">
                                                    <label class="col-sm-2 control-label">返却日</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group date col-sm-7 pull-left" id="return-datepicker">
                                                            <input type="text" name="return_date" id="return_date" class="form-control input-sm datetimepicker" value="<?php echo e($search->return_date); ?>" readonly required>
                                                            <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                        <div class="col-sm-5 plan_drop" style="padding-right: 0">
                                                            <select class="chosen-select form-control input-sm " name="return_time" id="return_time" required>
                                                                <?php $__currentLoopData = $hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($hour); ?>" <?php if($search->return_time == $hour): ?> selected <?php endif; ?>>
                                                                        <?php echo e($hour); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row hide" style="padding-bottom: 5px">
                                                    <label class="col-sm-2 control-label">返却地</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control input-sm" name="return_shop" id="return-shop" >
                                                            <option value="0">選択してください </option>
                                                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($shop->id); ?>" <?php if($shop->id == $search->return_shop): ?> selected <?php endif; ?>><?php echo e($shop->name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="padding-bottom: 5px">
                                                    <label class="col-sm-2 control-label hidden">定員数</label>
                                                    <div class="col-sm-5 hidden">
                                                        <select class="form-control input-sm" name="passenger" id="passenger" >
                                                        <option id="search_passenger_all" value="all" selected>全て</option>
                                                        
                                                            
                                                        
                                                        </select>
                                                    </div>
                                                    <label class="col-sm-2 control-label" style="font-size:13px;">乗車人数</label>
                                                    <div class="col-xs-3">
                                                        <input class="form-control input-sm" name="real_passenger" id="real_passenger" type="text" style="width: 100%" value="<?php echo e($search->real_passenger); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="padding-bottom: 5px;margin-bottom: 0">
                                                    <label class="col-sm-2 control-label">禁/喫</label>
                                                    <div class="col-xs-10">
                                                        <select name="smoke" id="smoke" class="form-control input-sm">
                                                            <option value="1" <?php if($search->smoke == 1): ?> selected <?php endif; ?>>喫煙</option>
                                                            <option value="0" <?php if($search->smoke == 0): ?> selected <?php endif; ?>>禁煙</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="padding-bottom: 10px;padding-top: 10px;margin-bottom: 0">
                                                    <label class="col-sm-2 control-label">免責補償</label>
                                                    <div class="col-sm-10" style="padding-top: 5px">
                                                        <label class="pull-left" style="padding-right: 8px;">
                                                            <input type="radio" name="insurance" value="0" <?php if($search->insurance==0): ?> checked <?php endif; ?> >&nbsp;不要
                                                        </label>
                                                        <label class="pull-left" style="padding-right: 8px;">
                                                            <input type="radio" name="insurance" value="1" <?php if($search->insurance==1): ?> checked <?php endif; ?> >&nbsp;免責補償
                                                        </label>
                                                        <label class="pull-left">
                                                            <input type="radio" name="insurance" value="2" <?php if($search->insurance==2): ?> checked <?php endif; ?> >&nbsp;ワイド免責
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group row pickup_object" style="margin-bottom: 30px;">
                                                    <label class="col-sm-2 control-label">送迎</label>
                                                    <input type="hidden" name="pickup_list" id="pickup-list" value="">
                                                    <div class="col-sm-10 pickup_name" style="padding-top:5px">
                                                        <label class="pull-left lbl-btn" >
                                                            <input type="radio" name="pickup" id="pickup2" value="1" <?php if($search->pickup==1): ?> checked <?php endif; ?>> 要
                                                            <!-- <span class="btn btn-default" style="width: 100%">要</span> -->
                                                        </label>
                                                        <label class="pull-left lbl-btn">
                                                            <input type="radio" name="pickup" id="pickup1" value="0" <?php if($search->pickup==0): ?> checked <?php endif; ?>> 不要
                                                            <!-- <span class="btn btn-default" style="width: 100%">不要</span> -->
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <label class="form_one">オプション</label>
                                                <div class="form-group" style="margin-bottom: 0;">
                                                    <input type="hidden" name="option_list" id="option-list" value="<?php echo e($search->options); ?>">
                                                    <input type="hidden" name="option_num_list" id="option-num-list" value="<?php echo e($search->option_numbers); ?>">
                                                    <div class="col-sm-12" id="option_wrapper" style="margin-left: -10px;margin-bottom:70px"></div>
                                                </div>


                                            </div>

                                            <div class="col-sm-5 form-group row-bordered-0 row text-center" style="position: absolute;bottom: 0;right: 0;">
                                                <button class="btn btn-flat bg-grad-red" type="submit" id="btn-submit" onclick="submit_search()" >検索</button>
                                            </div>
                                            <div class="form-group row-bordered row m_B0 hidden">
                                                <label class="col-sm-2 control-label">車両カテゴリ</label>
                                                <div class="col-sm-10">
                                                    <?php
                                                    $car_category = $search->car_category;
                                                    foreach($categorys as $cate) {
                                                        if($cate->name == '乗用車') {
                                                            $car_category = $cate->id;
                                                        }
                                                    } ?>

                                                    <input type="hidden" name="car_category" id="car-category" value="<?php echo e($car_category); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <p></p>
                            <table class="table table-bordered table-searchplan">
                                <thead>
                                <tr>
                                    <th>クラス名</th>
                                    <th>最大乗車人数</th>
                                    <th>車両台数</th>
                                    <th>基本料金</th>
                                    <th>オプション</th>
                                    <th>免責補償</th>
                                    <th>合計</th>
                                    <th>アクション</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- class list hear -->
                                </tbody>
                            </table>
						</div>

                        <div class="col-lg-6 col-md-12 box_plan">
                            <div class="col-xs-12" style="padding: 0; border: none; background-color: #e3f0c2;margin-bottom: 20px;">
                                <input type="hidden" class="search-condition" name="data_member" id="data_member" value="">

                                <div class="col-xs-12" style="padding: 10px;">
                                    <div class="col-xs-7 plr5">
                                        <label class="form_one">顧客情報</label>
                                        <div class="col-xs-12 form-group" style="padding: 0 0 5px;">
                                            <div class="clearfix"></div>
                                            <label class="col-xs-2 control-label">姓</label>
                                            <div class="col-xs-4" style="padding:0 8px 0 8px;">
                                                <input type="text" class="form-control search-condition" name="last_name" id="last_name">
                                            </div>
                                            <label class="col-xs-2 control-label">名</label>
                                            <div class="col-xs-4" style="padding: 0">
                                                <input type="text" class="form-control search-condition" name="first_name" id="first_name">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 form-group" style="padding: 0 0 5px;">
                                            <label class="col-xs-2 control-label">セイ</label>
                                            <div class="col-xs-4" style="padding:0 8px 0 8px;">
                                                <input type="text" class="form-control search-condition" name="furi_last_name" id="furi_last_name">
                                            </div>
                                            <label class="col-xs-2 control-label">メイ</label>
                                            <div class="col-xs-4" style="padding: 0">
                                                <input type="text" class="form-control search-condition" name="furi_first_name" id="furi_first_name">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 form-group" style="padding: 0 0 5px;">
                                            <label class="col-xs-2 control-label">電話</label>
                                            <div class="col-xs-10" style="padding: 0 0 0 9px">
                                                <input type="text" class="form-control search-condition" name="phone" id="phone">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 form-group" style="padding: 0 0 5px;">
                                            <label class="col-xs-2 control-label">メール</label>
                                            <div class="col-xs-10" style="padding: 0 0 0 9px;">
                                                <input type="text" class="form-control search-condition" name="email" id="email">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 form-group" style="padding: 0 0 5px;">
                                            <label class="col-xs-2 control-label">フライト</label>
                                                <div class="col-xs-5" style="padding-left: 10px;">
                                                <select class="form-control search-condition" name="flight_line" id="flight_line">
                                                    <option value="0">選択してください</option>
                                                    <?php $__currentLoopData = $flights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($flight->id); ?>"><?php echo e($flight->title); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                </div>
                                                <label class="col-xs-2 control-label">便番</label>
                                                <div class="col-xs-3" style="padding-right: 0;">
                                                    <input type="text" class="form-control" name="flight_number" id="flight_number">
                                                </div>
                                        </div>
                                        <div class="col-xs-12 form-group" style="padding: 0 0 5px;">
                                            <label class="col-xs-2 control-label">緊急連絡先</label>
                                            <div class="col-xs-10" style="padding: 0 0 0 9px;">
                                                <input type="text" class="form-control" name="emergency_phone" id="emergency_phone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="col-xs-12 text-right" style="padding-bottom: 10px;">
                                            <button class="btn btn-info" id="btn_clear_user_info">
                                                <i class="fa fa-refresh"></i> 削除
                                            </button>
                                        </div>
                                        <div class="col-xs-12 form-group" style="padding-left: 0">
                                            <label class="col-xs-2 control-label">メモ</label>
                                            <div class="col-xs-10" style="padding: 0">
                                                <textarea type="text" class="form-control" name="memo" id="memo" rows="14"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <style>
                                .dataTables_filter{ float: left; font-size: 20px; }
                            </style>

                            <div class="col-xs-12" style="padding: 0">
                                <table class="table table-responsive table-striped table-bordered" data-length-change="false" id="user-list">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>姓名</th>
                                        <th>フリガナ</th>
                                        <th>電話</th>
                                        <th>メールアドレス</th>
                                        <th>アクション</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- searched user list here -->
                                    </tbody>
                                </table>
                                <input type="hidden" name="rent_dates" id="rent_dates">
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('modals.modal-error', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('modals.modal-success', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div id="confirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">予約内容の確認</h4>
                </div>
                <div class="modal-body" id="booking_content">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="submitBooking()">はい</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">いいえ</button>
                </div>
            </div>

        </div>
    </div>

    <div id="dialog-confirm" title="">
        <div id="confirm_content"></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/dataTables/dataTables.bootstrap.min.js"></script>
    

    
    <style>
        .with-select { padding-right:0; }
        .right-select { position: absolute; top:5px; right:0; }
        .lbl-btn {
            /*width: 60px;*/
            margin-right:10px;
        }
        /*.lbl-btn .btn { width:100%; }*/
        /*.lbl-btn .btn.btn-default {*/
            /*background:white;*/
            /*border: 1px solid #cc0000;*/
            /*color : #cc0000;*/
        /*}*/

        .pickup_name input[type="radio"]:checked + .btn {
            outline: none;
            background: #cc0000;
            color: #fff;
        }
        .data-table thead tr th {
            white-space: nowrap;
        }
        #user-list td, #user-list th { text-align: center; vertical-align: middle; }
        #user-list tr.active td { background-color: #fae5e5; }
        .data-table tbody tr td { white-space: nowrap; }
        .cell{
            padding: 0 8px !important;
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

    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
        var members = [];
        var booking_data;

        var $mdlError = $('#modalError');
        var $mdlSuccess = $('#mdlSuccess');
        var $mdl_success_msg = $('#mdl_success_msg');
        var $errorBox = $('#error-content');
        var rent_dates = '<?php echo e($search->rentdates); ?>' * 1;
        var today = new Date();
        var tomorrow = new Date();
        tomorrow.setDate(today.getDate()+1);

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

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

        $('#depart-datepicker, #return-datepicker').datepicker({
            <?php if(config('app.locale') == 'ja'): ?>
            language: "ja",
            <?php endif; ?>
            format: 'yyyy-mm-dd',
            startDate: '<?php echo e(date('Y-n-j')); ?>',
            orientation: "bottom",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });

        var $departPicker = $('#depart-datepicker');
        var $returnPicker = $('#return-datepicker');

        // time selector initialize
        var $dTimepicker = $('#depart_time'),
            $rTimepicker = $('#return_time');

        // initialize time pickers
        var all_hours_disable = updateTimepicker($dTimepicker, $departPicker.datepicker('getDate'), today, true);
        if( all_hours_disable ) {
            $departPicker.datepicker('setDate', tomorrow);
            updateTimepicker($dTimepicker, $departPicker.datepicker('getDate'), today, true);
        }
        updateTimepicker($rTimepicker, $returnPicker.datepicker('getDate'), today, false);

        function updateTimepicker(picker, date, cdate, isdepart) {

            var dYear = date.getFullYear(), dMonth = date.getMonth(), dDate = date.getDate();
            var cTime = cdate.getTime();// + 10800 * 1000;
            if(isdepart === true) cTime += 10800 * 1000;
            var hours = picker.find('option'), cnt = hours.length;
            var first = -1;
            var oldval = picker.val();

            // picker.find("option:selected").removeAttr("selected");
            for(var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]);
                var hr_min = hOption.val().split(':');
                var dTime = (new Date(dYear, dMonth, dDate, hr_min[0], hr_min[1], 0, 0)).getTime();
                if(dTime < cTime){
                    hOption.prop('disabled', true).css('display', 'none');
                }
                else{
                    hOption.prop('disabled', false).css('display', 'block');
                    if(first < 0) first = k;
                }
            }
            if(first >= 0) picker.val($(hours[first]).val());
            // if(picker.val() < oldval) picker.val(oldval);
            picker.trigger("chosen:updated");
            return first < 0;
        }

        $departPicker.datepicker().on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);

            updateTimepicker($dTimepicker, minDate, (new Date()), true);
        });

        $returnPicker.datepicker().on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            var tmp = $departPicker.datepicker('getDate');
            var departDate = tmp;
            // var tmp = maxDate;
            var departHourMin = $dTimepicker.val();
            if (departHourMin != null && departHourMin != undefined) {
                departHourMin = departHourMin.split(':');
                tmp.setHours(departHourMin[0] * 1 + 1);
                tmp.setMinutes(departHourMin[1]);
            }
            var all_hours_disable = updateTimepicker($rTimepicker, maxDate, departDate, false);
            if (all_hours_disable) {
                maxDate.setDate(maxDate.getDate() + 1);
                $returnPicker.datepicker('setStartDate', maxDate).datepicker('setDate', maxDate);
                updateTimepicker($rTimepicker, maxDate, maxDate, false);
            }
        });

        $dTimepicker.change( function () {

            var dDate = $departPicker.datepicker('getDate'),
                dy = dDate.getFullYear(), dm = dDate.getMonth(), dd = dDate.getDate();
            var rDate = $returnPicker.datepicker('getDate'),
                ry = rDate.getFullYear(), rm = rDate.getMonth(), rd = rDate.getDate();
            var hr_min = $(this).val().split(':');
            var dTime = (new Date(dy, dm, dd, hr_min[0], hr_min[1], 0, 0)).getTime() + 60 * 60 * 1000;

            var hours = $rTimepicker.find('option').removeAttr('disabled').css('display','block'),
                cnt = hours.length,
                oldval = rTimepicker.val();
            var index = -1;
            for (var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]), hm = hOption.val().split(':');
                var rTime = (new Date(ry, rm, rd, hm[0], hm[1], 0, 0)).getTime();
                if (rTime <= dTime)
                    hOption.attr('disabled', 'disabled').css('display', 'none');
                else {
                    hOption.removeAttr('disabled');
                    if (index < 0 && hOption.val() == oldval) {
                        hOption.attr('selected', true);
                        index = k;
                    }
                }
            }
            if (index < 0) {
                dDate.setDate(dDate.getDate() + 1);
                $returnPicker.datepicker('setStartDate', dDate).datepicker('setDate', dDate);
            }
            $rTimepicker.trigger("chosen:updated");
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
		//chosen select
        $(".chosen-select").chosen({
            max_selected_options: 5,
            no_results_text: "Oops, nothing found!",
        });
        //inital value for depart shop
        var shop_name= $('select[name="depart_shop"] option:selected').text();
        $('.shop_name').html(shop_name);
        $('select[name="depart_shop"]').change(function(){
            var shop_name= $('select[name="depart_shop"] option:selected').text();
            $('.shop_name').html(shop_name);

            $('#return-shop').val($('#depart-shop').val());
            var flag = $('#depart-shop').val() == '0' || $('#return-shop').val() == '0';
            if(flag == false){
                // $('#search_form').submit();
            }
        });

        //select category
        var search_cate_id = '0';
        <?php if($search->car_category !="" ): ?>
            search_cate_id = '<?php echo e($search->car_category); ?>';
        <?php else: ?>
            search_cate_id = '<?php echo e($categorys[0]->id); ?>';
        <?php endif; ?>
        // $('#search_cate_'+search_cate_id).removeClass('search_cartype').addClass('search_btn');
        $('input[name="car_category"]').val(search_cate_id);
        function selectCategory(cate_id, cate_name){
            $('.search_cate').removeClass('search_btn').addClass('search_cartype');
            $('#search_cate_'+cate_id).removeClass('search_cartype').addClass('search_btn');
            $('input[name="car_category"]').val(cate_id);
            $('input[name="option_list"]').val("");
            getOptions();
        }

        //inital passenger when laoding
        var passenger = $('input[name="passenger"]').val();
        <?php if($search->passenger == ""): ?>
            passenger = 'all';
        <?php endif; ?>

        selectPassenger(passenger);
        function selectPassenger(val){
            $('.search_passenger').removeClass('search_btn').addClass('search_cartype');
            $('#search_passenger_'+val).removeClass('search_cartype').addClass('search_btn');
            $('input[name="passenger"]').val(val);
        }
        //initail insurance when loading
        var insurance = $('input[name="insurance"]').val();

        //initail smoke when loading
        var smoke = $('input[name="smoke"]').val();
                // smoke = 'both';
        selectSmoke(smoke);
        function selectSmoke(val){
            $('.search_smoke').removeClass('search_btn').addClass('search_cartype');
            $('#search_smoke_'+val).removeClass('search_cartype').addClass('search_btn');
            $('input[name="smoke"]').val(val);
        }
        //initial options
        getOptions();
        filterStaffList(<?php echo e($search->depart_shop); ?>);

        var option_list = $('input[name="option_list"]').val();
        var option_content =  option_list.split(',');

        function optionCheck( e ) {
            var target = $(e.currentTarget);
            var $selector = $('select.option_number[oid="'+ target.val() +'"]');
            if(target.prop('checked') === false) {
                $selector.val('0');
            } else {
                if($selector.val() === '0') $selector.val('1');
            }
            $('.table-searchplan tbody').empty();
            e.stopPropagation();
        }

        function optionNumberChange() {
            $('.table-searchplan tbody').empty();
        }

        //event to get options list when click category
        function getOptions() {
            var category_id  = $('input[name="car_category"]').val();
            var url = '<?php echo e(URL::to('/')); ?>/search/getshopoption';
            var token = $('input[name="_token"]').val();
            var shop_id = $('#depart-shop').val();
            var data = [];
            data.push( {name: '_token', value: token},
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
                    var option_html = '';
                    var search_options = $('#option-list').val().split(',');
                    var search_option_numbers = $('#option-num-list').val().split(',');
                    for(var k = 0; k < search_options.length; k++){
                        search_options[k] = search_options[k] * 1;
                    }
                    content =  jQuery.parseJSON(content);
                    var paids = content.paid_options;
                    var frees = content.free_options;
                    for(k = 0; k < paids.length; k++){
                        var v = paids[k];
                        var index = search_options.indexOf(v.id);
                        var checked = (index > -1 )? 'checked' : '';
                        var option_number = (index > -1 )? search_option_numbers[index] : 0;
                        option_html += '<div class="col-xs-12 row form-group" style="padding: 0">';
                        option_html += '<label class="checkbox search_option with-select">';
                        var clickevent = "";
                        var google_col = v.google_column_number;
                        if(google_col == '38') {
                            clickevent = 'onclick="disableFreeOption(event)"';
                            if(checked == 'checked') {
                                $('#pickup1').prop('checked', true);
                                $('.pickup_object').hide();
                            }
                        }

                        option_html += '<input type="checkbox" name="options[]" class="car_option" value="'+ v.id+'" '+ checked + ' '+clickevent+' onchange="optionCheck(event)"/>';
                        option_html += '<input type="hidden" class="option_name" oid="' + v.id + '" value="'+ v.name+'">';
                        option_html += '<input type="hidden" class="option_index" oid="' + v.id + '" value="'+ google_col+'">';
                        option_html += '<input type="hidden" class="option_cost" oid="' + v.id + '" value="'+ v.price +'">';
                        option_html +=  '<span>'+ v.name+'</span></label>';

                        var select = '<select name="option_number[]" class="option_number right-select  '+ ((v.max_number == 1)? 'hidden':'') +'" oid="'+ v.id +'" onchange="optionNumberChange()">';
                        select += '<option value="0"> </option>';
                        for(var i = 1; i <= v.max_number; i++) {
                            var selected = (i == option_number)? 'selected' : '';
                            select += '<option value="' + i + '" ' + selected + '>' + i + '</option>';
                        }
                        select += '</select>';
                        option_html += select;

                        option_html +=  '</div>';
                    }

                    $('#option_wrapper').html(option_html);

                    var free_options = '<label class="pull-left lbl-btn">' +
                        '<input type="radio" name="pickup" value="" checked> 不要</label>';
                    for(k = 0; k < frees.length; k++) {
                        var op = frees[k];
                        free_options += '<label class="pull-left lbl-btn">' +
                            '<input type="radio" name="pickup" value="'+ op.id +'"> ' + op.name + '</label>';
                    }
                    $('.pickup_name').html(free_options);
                }
            });
        }

        function filterStaffList(shop) {
            $('#staff option').hide();
            $('#staff option[shop="' + shop + '"]').show();
            $('#staff').val(null);
        }

        //return shop event
        $('#return-shop').change(function() {
            getOptions();
            $('.pickup_object').show();
        });
        $('#depart-shop').change(function() {
            getOptions();
            // change staff list
            filterStaffList($(this).val());

            $('.pickup_object').show();
        });
        //disable free option when select smart driveout paid option
        function disableFreeOption(e){
            var target = $(e.currentTarget);
            var checked = target.prop('checked');
            if(checked == true) {
                $("#pickup1").prop("checked", true);
                $('.pickup_object').hide();
            }else{
                $('.pickup_object').show();
            }
        }

        function select_user(uid) {
            $('#user-list tr').removeClass('active');
            $('#user-list tr[uid="' + uid + '"]').addClass('active');
            var count = members.length;
            for(var i = 0; i < count; i++) {
                var member = members[i];
                if( member.id === uid ) {
                    $('#first_name').val(member.first_name);
                    $('#last_name').val(member.last_name);
                    $('#furi_first_name').val(member.fur_first_name);
                    $('#furi_last_name').val(member.fur_last_name);
                    $('#email').val(member.email);
                    $('#phone').val(member.phone);
                    $('#data_member').val(uid);
                }
            }
        }

        function get_user_search_result() {
            $.ajax({
                url : '<?php echo e(URL::to('/')); ?>/booking/search-user',
                type:'post',
                data: {
                    _token          : $('input[name="_token"]').val(),
                    first_name      : $('#first_name').val(),
                    last_name       : $('#last_name').val(),
                    furi_last_name  : $('#furi_last_name').val(),
                    furi_first_name : $('#furi_first_name').val(),
                    phone           : $('#phone').val(),
                    email           : $('#email').val()
                },
                success : function(users, status) {
                    console.log(users);
                    var count = users.length;
                    if (count > 0 ){
                        members = users;

                        var $tbody = $('#user-list tbody');
                        $tbody.empty();
                        for(var k = 0; k < count; k++) {
                            var user = users[k], uid = user.id;
                            var fname = (user.first_name == null)? '' : user.first_name;
                            var lname = (user.last_name == null)? '' : user.last_name;
                            var ffname = (user.fur_first_name == null)? '' : user.fur_first_name;
                            var flname = (user.fur_last_name == null)? '' : user.fur_last_name;
                            var phone = (user.phone == null)? '' : user.phone;
                            var row = '<tr uid="'+ uid +'"><td>'+ user.id +'</td>' +
                                '<td>'+ lname + fname +'</td>' +
                                '<td>'+ flname + ffname +'</td>' +
                                '<td>'+ phone +'</td>' +
                                '<td>'+ user.email +'</td>' +
                                '<td><button class="btn btn-primary" onclick="select_user(' + uid + ')">選択</button></td></tr>';
                            $tbody.append(row);
                        }
                        // $('#user-list').DataTable().draw();
                    }
                },
                error : function(xhr,status,error) {
                    console.log(error);
                }
            })
        }

        $(document).ready(function(){
            $('#depart-shop').on('change', function() {
              if ( this.value == '5')
              {
                $(".pickup_object").hide();
              }
              else
              {
                $(".pickup_object").show();
              }
            });
        });

        $(document).ready( function() {
            $('#user-list').dataTable({
                // "order": [[ 0, 'desc' ]],
                "paging": true,
                // "lengthChange": true,
                "searching": false,
                "pageLength" : 25,
                "serverSide": false,
                // "ordering": true,
                "info": false,
                "dom": 'T<"clear">lfrtip',
                "sPaginationType": "full_numbers",
                "language": {
                    processing:     "処理中...",
                    search:         "検索:",
                    lengthMenu:     "_MENU_個の要素を表示",
                    info:           "_START_ ~ _END_  を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全項目 _TOTAL_",
                    infoEmpty:      "0件中0件から0件までを表示",
                    infoFiltered:   "（合計で_MAX_個のアイテムからフィルタリングされました）",
                    infoPostFix:    "",
                    loadingRecords: "読み込んでいます...",
                    zeroRecords:    "表示する項目がありません",
                    emptyTable:     "テーブルのデータがありません",
                    paginate: {
                        first:      "最初",
                        previous:   "以前",
                        next:       "次に",
                        last:       "最終"
                    },
                    aria: {
                        sortAscending:  ": 列を昇順にソートする有効にします。",
                        sortDescending: ": 列を降順で並べ替えるためにアクティブにする"
                    }
                }
            });
        });

        $('.search-condition').keyup( function(e) {
            // if(e.which !== 13) return;
            var val = $(this).val();
            if(val == undefined) val='';
            if(val.length > 0) {
                get_user_search_result();
            }
        });


        $('#btn_clear_user_info').click(function () {
            $('.search-condition').val('');
            $('#user-list tbody').empty();
        });

        function number_format(x) {
            return x.toLocaleString('en');
        }

        // submit search
        function submit_search() {
            // checking
            var depart_shop = $('#depart-shop').val(),
                return_shop = $('#return-shop').val();
            if(depart_shop === '0') {
                showModal('select depart shop');
                return;
            }
            var depart_date = $('#depart_date').val(),
                depart_time = $('#depart_time').val(),
                return_date = $('#return_date').val(),
                return_time = $('#return_time').val(),
                departing = new Date(depart_date + ' ' + depart_time),
                returning = new Date(return_date + ' ' + return_time);

            if(departing.getTime() + 3600*1000 >= returning.getTime()) {
                showModal('出発日時は返却日より早くする必要があります');
                return;
            }

            // process options and option numbers;
            var $options = $('.car_option'),
                opt_ids = [], opt_nums = [];
            for(var i = 0; i < $options.length; i++){
                var $option = $($options[i]),
                    oid = $option.val();
                if($option.prop('checked')) {
                    opt_ids.push(oid);
                    opt_nums.push($('.option_number[oid='+oid+']').val());
                }
            }
            $('#option-list').val(opt_ids.join());
            $('#option-num-list').val(opt_nums.join());

            var data = {
                _token : $('input[name="_token"]').val(),
                depart_date     : depart_date,
                depart_time     : depart_time,
                return_date     : return_date,
                return_time     : return_time,
                depart_shop     : depart_shop,
                return_shop     : return_shop,
                passenger       : $('#passenger').val(),
                real_passenger  : $('#real_passenger').val(),
                smoke           : $('#smoke').val(),
                insurance       : $('input[name="insurance"]:checked').val(),
                options         : opt_ids,
                option_number   : opt_nums,
                option_list     : $('#option-list').val(),
                option_num_list : $('#option-num-list').val(),
                pickup          : $('input[name="pickup"]:checked').val(),
                car_category    : $('#car-category').val()
                };
            $.ajax({
                url : "<?php echo e(URL::to('/')); ?>/booking/search-class",
                data: data,
                type: 'post',
                success : function( classes, status ) {
                    if(status == 'success') {
                        console.log(classes);
                        var count = classes.length;
                        var tcontent = '';
                        for(var k = 0; k < count; k++) {
                            var $class = classes[k], cid = $class.id;
                            var max_passengers = $class.max_passengers;
                            for(var passenger_number in max_passengers) {
                                tcontent += '<tr>' +
                                    '<input type="hidden" value="' + $class.impossibles + '">' +
                                    '<td>' + $class.class_name + '</td>' +
                                    '<td>' + passenger_number + '</td>' +
                                    '<input type="hidden" class="passenger_number" class_id="'+ cid + '" value="' + passenger_number + '">' +
                                    '<td>' + max_passengers[passenger_number] + '台</td>' +
                                    '<td>' + number_format($class.base_price) + '円' +
                                    '<input type="hidden" class="rent_days" class_id="'+ cid + '" value="'+ $class.night_day + '">' +
                                    '<input type="hidden" class="price_rent" class_id="'+ cid + '" value="'+ $class.base_price + '">' +
                                    '</td>' +
                                    '<td>' + number_format($class.option_price) + '円';

                                var option_ids = [],
                                    option_names = [],
                                    option_costs = [],
                                    option_numbers = [],
                                    option_indexs  = [],
                                    $options = $class.options;
                                if($options.length > 0) {
                                    for( var i = 0; i < $options.length; i++){
                                        var $op = $options[i];
                                        option_ids.push($op.id);
                                        option_names.push($op.name);
                                        option_costs.push($op.price);
                                        option_numbers.push(1);
                                        option_indexs.push($op.google_column_number);
                                    }
                                }

                                tcontent += '<input type="hidden" class="option_ids" class_id="'+ cid + '" value="'+ option_ids.join() +'">' +
                                    '<input type="hidden" class="option_names" class_id="'+ cid + '" value="' + option_names.join() + '">' +
                                    '<input type="hidden" class="option_numbers" class_id="'+ cid + '" value="'+ option_numbers.join()+ '">' +
                                    '<input type="hidden" class="option_costs" class_id="'+ cid + '" value="'+ option_costs.join()+ '">' +
                                    '<input type="hidden" class="option_price" class_id="'+ cid + '" value="'+ $class.option_price + '">' +
                                    '<input type="hidden" class="option_indexs" class_id="'+ cid + '" value="'+ option_indexs.join() + '">' +
                                    '<input type="hidden" class="option_prices" class_id="'+ cid + '" value="'+ $class.option_prices + '">' +
                                    '</td>' +
                                    '<td>'+ number_format($class.insurance_price)+'円' +
                                    '<input type="hidden" class="insurance_price1" value="'+ $class.insurance1+ '" class_id="'+ cid + '">' +
                                    '<input type="hidden" class="insurance_price2" value="'+ $class.insurance2+ '" class_id="'+ cid + '">' +
                                    '</td>' +
                                    '<td>' + number_format($class.all_price) + '円' +
                                    '<input type="hidden" class="price_all" class_id="'+ cid + '" value="'+ $class.all_price +'">' +
                                    '</td>' +
                                    '<td><a class="btn btn-primary" onclick="confirm_booking('+ cid + ',\'' + $class.class_name + '\',' + $class.all_price + ')">予約</a></td>' +
                                    '</tr>';
                            }

                            $('#rent_dates').val($class.rent_dates);
                        }
                        $('.table-searchplan tbody').html(tcontent);
                    } else {
                        showModal('status is not success');
                    }
                },
                error : function(xhr, status, error) {
                    showModal('status:' + status + ' error:' + error);
                }
            })
        }

        // submit selection
        function confirm_booking(class_id, class_name, total_price) {
            var member_id = $('#data_member').val(),
                last_name = $('#last_name').val(),
                first_name = $('#first_name').val(),
                furi_last_name = $('#furi_last_name').val(),
                furi_first_name = $('#furi_first_name').val(),
                phone = $('#phone').val(),
                email = $('#email').val(),
                flight_line = $('#flight_line').val(),
                flight_number = $('#flight_number').val(),
                emergency_phone = $('#emergency_phone').val(),
                memo = $('#memo').val(),
                smoke = $('#smoke').val(),
                insurance_type = $('input[name="insurance"]:checked').val();

            if(member_id === '') {
                if(furi_last_name === '') {
                    showModal('フリガナ（セイ）を入力してください');
                    return;
                }

                if(furi_first_name === '') {
                    showModal('フリガナ（メイ）を入力してください');
                    return;
                }

                if(phone === '') {
                    showModal('電話番号を入力してください');
                    return;
                }
            }

            var depart = $('#depart-shop').val(),
                depart_selector = '#depart-shop option[value="'+ depart + '"]';
            if(depart == 0) {
                showModal('出発地を選択してください。');
                return;
            }

            var arrive = $('#return-shop').val(),
                arrive_selector = '#return-shop option[value="'+ arrive + '"]';
            if(arrive == 0) {
                showModal('返却地を選択してください。');
                return;
            }

            var depart_time = $('#depart_time').val();
            if( depart_time == null || depart_time == undefined) {
                showModal('出発時間を選択してください。');
                return;
            }

            var return_time = $('#return_time').val();
            if( return_time == null || return_time == undefined) {
                showModal('返却時間を選択してください。');
                return;
            }

            var depart_date = $('#depart_date').val();
            var return_date = $('#return_date').val();
            if( new Date(depart_date + ' ' + depart_time).getTime() >= new Date(return_date + ' ' + return_time).getTime()) {
                showModal('出発日時が返却日時より後である。');
                return;
            }

            var staff = $('#staff').val();
            if(staff == null || staff == '0') {
                showModal('担当者を選択してください。');
                return;
            }

            // get selected options
            var $car_options = $('.car_option');
            var option_ids = [], option_numbers = [], option_names = [], option_indexes = [], option_costs = [];
            for( var j = 0; j < $car_options.length; j++) {
                var $option = $($car_options[j]);
                if( $option.prop('checked') === true) {
                    var oid = $option.val();
                    option_numbers.push( Number($('.option_number[oid="' + oid + '"]').val()) );
                    option_names.push( $('.option_name[oid="' + oid + '"]').val() );
                    option_indexes.push( Number($('.option_index[oid="' + oid + '"]').val()) );
                    option_costs.push( Number($('.option_cost[oid="' + oid + '"]').val()) );
                    option_ids.push( Number(oid) );
                }
            }

            var class_selector = '[class_id="' + class_id + '"]';
            var rent_days = $('.rent_days' + class_selector).val(),
                rent_dates = $('#rent_dates').val(),
                free_pickup = $('input[name="pickup"]:checked').val();

            // check passenger number
            var real_passenger_number = parseInt($('#real_passenger').val());
            if(isNaN(real_passenger_number ) ) {
                showModal('乗車人数を選択してください。');
                $('#real_passenger').val('');
                return;
            }
            var selected_passenger_number = parseInt($('.passenger_number' + class_selector).val());
            if(selected_passenger_number < real_passenger_number){
                showModal('選択されたクラスの乗車人数が実際人数よりも少なくなります。');
                return;
            }

            booking_data = {
                _token                  : $('input[name="_token"]').val(),
                data_staff              : staff,
                data_member             : member_id,
                data_depart_shop        : depart,
                data_depart_shop_name   : $(depart_selector).html().trim(),
                data_return_shop        : arrive,
                data_return_shop_name   : $(arrive_selector).html().trim(),
                data_depart_date        : depart_date,
                data_depart_time        : depart_time,
                data_return_date        : return_date,
                data_return_time        : return_time,
                data_passenger          : $('#passenger').val(),
                data_insurance          : insurance_type,
                data_smoke              : smoke,
                data_class_id           : class_id,
                data_passenger_number   : selected_passenger_number,
                data_insurance_price1   : $('.insurance_price1' + class_selector).val(),
                data_insurance_price2   : $('.insurance_price2' + class_selector).val(),
                data_rent_days          : rent_days,
                data_price_rent         : $('.price_rent' + class_selector).val(),
                data_option_ids         : option_ids.join(','),
                data_option_names       : option_names.join(','),
                data_option_indexs      : option_indexes.join(','),
                data_option_numbers     : option_numbers.join(','),
                data_option_costs       : option_costs.join(','),
                data_option_prices      : $('.option_prices' + class_selector).val(),
                data_price_all          : $('.price_all' + class_selector).val(),
                data_rendates           : rent_dates,
                data_flight_line        : flight_line,
                data_flight_number      : flight_number,
                data_emergency_phone    : emergency_phone,
                data_memo               : memo,
                data_real_passengers    : real_passenger_number,
                data_pickup             : free_pickup,
                last_name               : last_name,
                first_name              : first_name,
                furi_last_name          : furi_last_name,
                furi_first_name         : furi_first_name,
                email                   : email,
                phone                   : phone
            };

            var lname = (last_name == undefined)? '' :last_name;
            var fname = (first_name== undefined)? '' :first_name;

            var user_name = lname + fname;
            if(user_name == '') {
                lname = (furi_last_name == undefined)? '' : furi_last_name;
                fname = (furi_first_name == undefined)? '' : furi_first_name;
                user_name = lname + fname;
            }
            phone = (phone == undefined)? '' : phone;
            email = (email == undefined)? '' : email;
            flight_line = (flight_line == undefined)? '' : flight_line;
            memo = (memo == undefined)? '' : memo;
            smoke = (smoke == 1)? '喫煙' : '禁煙';
            if( insurance_type == 0) {
                insurance_type = '不要';
            } else if(insurance_type == 1) {
                insurance_type = '免責補償';
            } else {
                insurance_type = 'ワイド免責';
            }
            var items = [];
            for(var k = 0; k < option_names.length; k++) {
                var v = option_names[k] + ' ';
                if(option_names[k] == 'スタッドレスタイヤ'){
                    v = v + option_costs[k] + '円 x ' + rent_dates + '日';
                } else {
                    v = v + option_costs[k] + '円 x ' + option_numbers[k] + '個';
                }
                items.push(v);
            }

            if(free_pickup !== '') {
                items.push('無料送迎 0円');
            }

            flight_line = (flight_line == 0)? '' : $('#flight_line option:selected').text();

            var info = 'ご予約内容を確認致します。<br><br>' +
                '【ご予約者情報】<br>' +
                'お名前： '+ user_name +' 様<br>' +
                'お電話番号： ' + phone + '<br>' +
                'メールアドレス： ' + email + '<br>' +
                '航空便：' + flight_line + '<br>' +
                'メモ：' + memo + '<br>' +
                '緊急連絡先:'+emergency_phone+'<br>'+
                '期間：' + depart_date + '-' + return_date + '<br>' +
                '車両クラス：' + class_name + ' ' + smoke + '<br>' +
                '補償：' + insurance_type + '<br>' +
                'オプション：<br>' + items.join('<br>') + '<br>' +
                '-------------------------------------<br>' +
                '<b style="font-size:130%;font-weight:600;">合計料金&emsp;'+ total_price +'円</b><br><br>' +
                '以上の内容でお間違いはないでしょうか。<br><br>';

            $('#booking_content').html(info);
            $('#confirmModal').modal('show');
        }

        function submitBooking() {
            $('.alert-success').fadeIn();

            $.ajax({
                url  : "<?php echo e(URL::to('/')); ?>/booking/search-save",
                data : booking_data,
                type : 'post',
                success : function(result, status) {
                    if(status == 'success'){
                        if(result.success == true){
                            // $mdl_success_msg.text('予約は正常に保存されました');
                            // $mdlSuccess.modal('show');

                            $('#user-list tbody').empty();
                            $('.search-condition').val('');
                            $('#flight_line').val(0);
                            $('#flight_number').val('');
                            $('#emergency_phone').val('');
                            $('#memo').val('');

                            // show info modal
                        } else {
                            showModal(result.errors);
                        }
                    } else {
                        showModal(result.error);
                    }
                },
                error : function(xhr, status, error) {
                    showModal(error);
                }
            });

        }

        function hideAlert() {
            $('.alert-success').fadeOut();
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminapp_calendar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>