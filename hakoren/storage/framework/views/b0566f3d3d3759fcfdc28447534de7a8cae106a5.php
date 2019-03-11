<?php $__env->startSection('template_title'); ?>
    店舗配車表
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('template_linked_css'); ?>

<?php $__env->stopSection(); ?>
<?php $service = app('App\Http\Controllers\BookingManagementController'); ?>
<?php $__env->startSection('content'); ?>
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
        @media  screen and (max-width: 1024px){
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
        @media  screen and (max-width: 768px){
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

        @media  screen and (max-width: 425px){
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
                    <div class="m-l-lg m-r-lg">
                        <div class="dispatch">
                            <h2>タスク表</h2>
                        </div>
                        <div class="dispatch">
                            <span>配車表</span>
                            <ul class="btn_manage">
                                <li><a class="menu_title" onclick="searchBook('today','date')">
                                        <?php if($task_date == 'today'): ?><i class="fa fa-check-circle"></i> <?php endif; ?> 本日</a></li>
                                <li><a class="menu_title" onclick="searchBook('tom','date')">
                                        <?php if($task_date == 'tom'): ?><i class="fa fa-check-circle"></i> <?php endif; ?> 明日</a></li>
                            </ul>
                        </div>
                        <div class="said-block">
                            <span>表示：</span>
                            <ul class="btn_manage2">
                                <li><a class="menu_title" onclick="searchBook('all' ,'category')"><?php if($category == 'all'): ?> <i class="fa fa-check-circle"></i> <?php endif; ?> 全て</a></li>
                                <li><a class="menu_title" onclick="searchBook('rent' ,'category')"><?php if($category == 'rent'): ?> <i class="fa fa-check-circle"></i> <?php endif; ?> 配車</a></li>
                                <li><a class="menu_title" onclick="searchBook('return' ,'category')"><?php if($category == 'return'): ?> <i class="fa fa-check-circle"></i> <?php endif; ?> 返車</a></li>
                            </ul>
                        </div>

                        <div class="said-block" align="right"><a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="javascript:printalltask();" title="Print All">チェックシート印刷</a><a class="btn btn-sm btn-empty m_L5" href="javascript:void(0);" onclick="javascript:printalltaskempty();" title="Print All">空チェックシート</a></div>

                        
                        <div class="shop-block">
                            <select name="shopid" id="shopid" class="form-control" onchange="searchBook('all' ,'shop')" >
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($shop->id); ?>" <?php if($shop_id == $shop->id): ?> selected <?php endif; ?>><?php echo e($shop->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                    </div>

                    <form method="post" name="search" id="search" action="<?php echo e(URL::to('/')); ?>/booking/task">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="task_date" value="<?php echo e($task_date); ?>" >
                        <input type="hidden" name="category" value="<?php echo e($category); ?>" >
                        <input type="hidden" name="shop_id" value="<?php echo e($shop_id); ?>" >
                        <input type="hidden" name="cflag" value="0" >
                        <input type="hidden" name="target_object" value="<?php echo e($target_object); ?>" >
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
                                    <?php if($task_date == 'today'): ?>本日、<?php endif; ?>
                                    <?php if($task_date == 'tom'): ?>明日、<?php endif; ?>
                                    <?php if($task_date == 'today'||$task_date == 'tom'): ?>
                                        <?php echo e(date('n', strtotime($date))); ?>月<?php echo e(date('j', strtotime($date))); ?>日の
                                    <?php else: ?>
                                        1週間の
                                    <?php endif; ?>
                                    「<span>
                                        <?php if($category == 'all'): ?> 配車&返車 <?php endif; ?>
                                        <?php if($category == 'rent'): ?> 配車 <?php endif; ?>
                                        <?php if($category == 'return'): ?> 返車 <?php endif; ?>
                                    </span>」は全<span><?php echo e($all_count); ?></span>件です。</h2>
                            </div>
                            <div class="col-sm-7 col-xs-12 view-right-block">
                                <div class="progress-block">
                                    <ul>
                                        <li><span>配車残り <?php echo e($rents_all-$rents_end_count); ?>件</span><div class="w3-light-grey">
                                                <div class="w3-green" style="font-weight:500;width:<?php echo e($rent_ratio); ?>%;<?php if($rent_ratio == 0): ?> background-color:#d2d2d2;<?php endif; ?>"><?php echo e($rent_ratio); ?>%</div>
                                            </div></li>
                                        <li><span>返車残り <?php echo e($return_all-$returns_end_count); ?>件</span><div class="w3-light-grey">
                                                <div class="w3-green" style="font-weight:500;width:<?php echo e($return_ratio); ?>%;<?php if($return_ratio == 0): ?> background-color:#d2d2d2;<?php endif; ?>"><?php echo e($return_ratio); ?>%</div>
                                            </div></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if($cflag == '1'): ?>
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
                    <?php endif; ?>
                    <div class="car-listing-main active">
                        <div class="col-sm-12">
                            <?php echo csrf_field(); ?>

                                <!--start rent part-->
                                <?php $count_number = 0; ?>

                                <?php $__currentLoopData = $rents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="car-common-block active tabel_box" id="target_<?php echo e($count_number); ?>" >
                                        <div class="car-common-inner">
                                            <div class="number-block">
                                                <h2><?php echo e($rent_display-$count_number); ?></h2>
                                            </div>
                                            <div class="common-content-block">
                                                <div class="user-block" onclick="showHide('row_<?php echo e($count_number); ?>')">
                                                    <h2>配車</h2>
                                                    <?php if($rent->portal_flag == 0): ?>
                                                        <?php if($rent->bookedcount == 1): ?>
                                                            <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px;margin-left: 5px;" >初</h2>
                                                        <?php else: ?>
                                                            <h2 class="repeat">リピート <?php if($rent->bookedcount > 1): ?> <?php echo e($rent->bookedcount); ?> <?php endif; ?></h2>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php
                                                        $color = \App\Http\DataUtil\ServerPath::portalColor($rent->portal_id);
                                                        $pname = $portal_sites[$rent->portal_id];
                                                        ?>
                                                        <h2 class="repeat" style="font-weight:500;color:#111;background-color: <?php echo e($color); ?>;border: <?php echo e($color); ?> 1px solid;padding: 1px 5px;margin-left: 5px;" ><?php echo e($pname); ?></h2>
                                                    <?php endif; ?>

                                                    <?php if($rent->web_status == '3'): ?>

                                                        <?php if($rent->bag_choosed == '1'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 3px;margin-left: 5px;" >QS フリスク</h2> <?php endif; ?>
                                                        <?php if($rent->bag_choosed == '2'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS ぷっちょ</h2> <?php endif; ?>
                                                        <?php if($rent->bag_choosed == '3'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS 酔い止め</h2> <?php endif; ?>

                                                    <?php endif; ?>

                                                    <h3><span class="user-name">
                                                            <?php echo e($rent->last_name); ?> <?php echo e($rent->first_name); ?>

                                                            (<?php echo e($rent->fur_last_name); ?> <?php echo e($rent->fur_first_name); ?>)
                                                        </span> 様は
                                                        <span class="time">
                                                            <?php echo e($date_val); ?>

                                                            
                                                            <?php echo e(date('G:i', strtotime($rent->departing))); ?>

                                                            <label id="changed_return_time_<?php echo e($count_number); ?>" onclick="changeDepartTime(event,<?php echo e(json_encode($rent)); ?>,'<?php echo e(date('G:i', strtotime($rent->departing))); ?>','<?php echo e($count_number); ?>')" style="cursor: pointer;">
                                                                <i class="fa fa-phone-square" aria-hidden="true"></i>
                                                            </label>
                                                                <div class="change_return" id="change_return_<?php echo e($count_number); ?>">
                                                                    <div> 連絡先: <span id="change_return_tel">--<?php echo e($rent->phone); ?>--</span></div>
                                                                    <div style="margin-top: 5px;">
                                                                        <select name="change_return_time_<?php echo e($count_number); ?>" id="change_return_time_<?php echo e($count_number); ?>" style="height:28px;" onclick="return_select(event);">
                                                                            <?php $__currentLoopData = $hour; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($h); ?>"><?php echo e($h); ?> </option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                        <a class="btn btn-primary btn-sm" onclick="savedepartTime(event)" style="margin-top: -4px;" >確定</a>
                                                                    </div>
                                                                </div>
                                                        </span> 出発です。
                                                    </h3>
                                                    <!--menu link-->
                                                    <h3 class="pull-right" style="width:20px;
     height: 30px;">
                                                        <span class="glyphicon glyphicon-cog  dropdown-toggle" style="font-size:24px; margin-top:4px;"  data-toggle="dropdown" onclick="eventPrevent(event,'<?php echo e($count_number); ?>')"> &nbsp;</span>
                                                        <ul class="dropdown-menu"  id="dropdown_<?php echo e($count_number); ?>" style="width:30px;"  >
                                                            <li><a data-value="return" tabIndex="-1" href="javascript:void(0);" onclick="javascript:print_booking(event, '<?php echo $rent->id; ?>')" title="詳細" ><label style="font-weight:300;margin-top: 5px;"> 印刷 </label></a></li>
                                                            <li><a data-value="return" tabIndex="-1" onclick="event.stopPropagation();"  href="<?php echo e(URL::to('/')); ?>/booking/edit/<?php echo e($rent->id); ?>" target="_blank"  title="編集" > <label style="font-weight:300;">編集</label></a></li>
                                                        </ul>
                                                    </h3>
                                                    <!---->
                                                </div>
                                                <div id="row_<?php echo e($count_number); ?>" class="row user-block-content" style="margin-top: 35px;" <?php if($count_number > 4): ?> style="display: none;" <?php endif; ?> >
                                                    <div class="col-md-5 col-sm-12" style="padding-left: 90px;">
                                                        <div class="row" style="margin-bottom:5px;">
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="smoking-block p_lsm5" style="padding-right:0px;">
                                                                    <div class="smoking-left">
                                                                        <div class="smoking-content">
                                                                            <h2><?php echo e($rent->car_number1); ?> <?php echo e($rent->car_number2); ?></h2>
                                                                            <span><?php echo e($rent->car_number3); ?></span>
                                                                            <h3><?php echo e($rent->car_number4); ?></h3>
                                                                        </div>
                                                                    </div>
                                                                    <div class="smoking-right" style="vertical-align: middle">
                                                                        <?php if($rent->smoke == 0): ?>
                                                                            <img src="<?php echo e(URL::to('/')); ?>/images/cartask/smoking-icon.png">
                                                                        <?php else: ?>
                                                                            <img src="<?php echo e(URL::to('/')); ?>/images/cartask/smoking-icon2.png">
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="smoking-right" style="vertical-align: middle">
                                                                        <b style="font-size:1.5em;"><?php echo e($rent->shortname); ?></b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row"  style="font-size: 15px;margin-left: 0px;">
                                                                <div class="date-block" style="padding-right:0px;">
                                                                    <li style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center; background:#fff;"><span><i class="fa fa-undo"></i> 返却</span></li>
                                                                    <li style="display: inline-block; padding-left: 10px;">
                                                                        <?php if($rent->night == '0' && $rent->day == '1'): ?>
                                                                            当日返し
                                                                        <?php else: ?>
                                                                            <?php echo e($rent->night); ?>泊<?php echo e($rent->day); ?>日
                                                                        <?php endif; ?>
                                                                    </li>
                                                                    <li style="display: inline;padding-left: 5px;">
                                                                        <?php if(strtotime($rent->returning_updated) > strtotime($rent->returning)): ?>
                                                                            <?php echo e(date('n月j日', strtotime($rent->returning_updated))); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(date('n月j日', strtotime($rent->returning))); ?>

                                                                        <?php endif; ?>
                                                                    </li>
                                                                    <li style="display: inline;padding-left: 5px;">
                                                                        <?php if(strtotime($rent->returning_updated) > strtotime($rent->returning)): ?>
                                                                            <?php echo e(date('G:i', strtotime($rent->returning_updated))); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(date('G:i', strtotime($rent->returning))); ?>

                                                                        <?php endif; ?>
                                                                    </li>
                                                                </div>
                                                                <!--QS status start-->
                                                                <?php if($rent->web_status > 0 ): ?>
                                                                <div class="row date-block" style="margin-top: 5px;">
                                                                    <div class="col-md-2" style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center; background:#fff;">
                                                                        <span><i class="fa fa-clock-o"></i> QS</span>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <?php if($rent->web_status == '1'): ?>
                                                                            <span>Web免許</span>
                                                                            <?php if(!empty($rent->driver_license_images)): ?>
                                                                                <span class="license" onclick="viewlicense(<?php echo e(json_encode($rent)); ?>)">免</span>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                        <?php if($rent->web_status == '2'): ?>
                                                                            <span>Web免許</span>
                                                                            <?php if(!empty($rent->driver_license_images)): ?>
                                                                                <span class="license" onclick="viewlicense(<?php echo e(json_encode($rent)); ?>)">免</span>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                        <?php if($rent->web_status == '3'): ?>
                                                                            <span>Web免許 </span>
                                                                                <?php if(!empty($rent->driver_license_images)): ?>
                                                                                    <span class="license" onclick="viewlicense(<?php echo e(json_encode($rent)); ?>)">免</span>
                                                                                <?php endif; ?>
                                                                            <span>&nbsp;Web決済</span>
                                                                            <span>&nbsp;[末尾<?php echo e($rent->card_last4); ?>] </span>
                                                                        <?php endif; ?>
                                                                        <?php if($rent->bag_choosed == '1'): ?>
                                                                              <span>  フリスクをご希望</span>
                                                                        <?php endif; ?>
                                                                        <?php if($rent->bag_choosed == '2'): ?>
                                                                            <span>ぷっちょをご希望 </span>
                                                                        <?php endif; ?>
                                                                        <?php if($rent->bag_choosed == '3'): ?>
                                                                            <span>酔い止めをご希望 </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                                <!--QS status end-->
                                                                <!--pick up start-->
                                                                <?php if(!empty($rent->pickup_options)): ?>
                                                                <div class="row date-block" style="margin-top: 5px;">
                                                                    <div class="col-md-2" style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center; background:#fff;"><span><i class="fa fa-car"></i> 送迎</span></div>
                                                                    <div class="col-md-8">
                                                                        <?php $c = 0; ?>
                                                                        <?php if($rent->free_options_category == '1'): ?> 国内線送迎 <?php endif; ?>
                                                                        <?php if($rent->free_options_category == '2'): ?> 国際線送迎 <?php endif; ?>
                                                                        <?php if($rent->free_options_category == '3'): ?> コインパーキング <?php endif; ?>
                                                                        
                                                                            
                                                                        
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                                <!--end pick up-->
                                                                <!--admin memo start-->
                                                                <?php if(!empty($rent->admin_memo)): ?>
                                                                <div class="row date-block" style="margin-top: 5px;">
                                                                    <div class="col-md-2" style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center; background:#fff;"><span><i class="fa fa-pencil"></i> メモ</span></div>
                                                                    <div class="col-md-8" >
                                                                        <?php echo e($rent->admin_memo); ?>

                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                                <!--end-->
                                                            </div>
                                                            <div class="row" style="font-size: 15px;margin-left: 0px;">
                                                                <?php if(!empty($rent->client_message)): ?>
                                                                    <div class="row date-block clearfix" style="margin-top: 5px;">
                                                                        <li style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center;float:left; background:#fff;"><span><i class="fa fa-user-o"></i> お客様</span></li>
                                                                        <li style="display: inline-block; padding-left: 10px; padding-right: 10px;max-width: 220px;  float:left;">
                                                                                <?php echo e($rent->client_message); ?>

                                                                        </li>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7 col-sm-12 check_items" style="padding-left: 60px;">
                                                        <div>
                                                            <div>
                                                                <div <?php if($rent->insurance1_flag == true ): ?> class="span-items-on" <?php else: ?> class="span-items-off" <?php endif; ?> onclick="changeRentInsuranceETC('<?php echo e($rent->id); ?>','insurance1', '<?php echo e($count_number); ?>', '<?php echo e($rent->insurance1_flag); ?>' )" >
                                                                   <?php if($rent->insurance1_flag == true ): ?> <i class="fa fa-check-circle" style="color:#22c03c"></i> <span style="font-weight: bold" >免責</span>
                                                                   <?php else: ?> <span> 免責</span> <?php endif; ?> </div>
                                                                <div  <?php if($rent->insurance2_flag == true ): ?> class="span-items-on" <?php else: ?> class="span-items-off" <?php endif; ?>  onclick="changeRentInsuranceETC('<?php echo e($rent->id); ?>','insurance2', '<?php echo e($count_number); ?>','<?php echo e($rent->insurance2_flag); ?>')" >
                                                                   <?php if($rent->insurance2_flag == true ): ?> <i class="fa fa-check-circle" style="color:#22c03c" ></i> <span style="font-weight: bold">ワ補</span>
                                                                   <?php else: ?> <span>ワ補</span>  <?php endif; ?> </div>
                                                                <?php if($util->getShopProperty($rent->pickup_id) != 'Okinawa'): ?>
                                                                <div  <?php if($rent->etc_flag == true ): ?> class="span-items-on" <?php else: ?> class="span-items-off" <?php endif; ?>  onclick="changeRentInsuranceETC('<?php echo e($rent->id); ?>','etc', '<?php echo e($count_number); ?>','<?php echo e($rent->etc_flag); ?>')" >
                                                                   <?php if($rent->etc_flag == true ): ?> <i class="fa fa-check-circle" style="color:#22c03c" ></i> <span style="font-weight: bold" >ETC</span>
                                                                   <?php else: ?> <span>ETC</span> <?php endif; ?> </div>
                                                                 <?php endif; ?>
                                                            </div>
                                                            <!--price list-->
                                                            <div>
                                                                <table class="table tbl-no-border" style="font-size: 12px;" >
                                                                    <!--original price start-->
                                                                    <?php if($rent->payment > 0): ?>
                                                                    <tr>
                                                                        <td>
                                                                            <b <?php if($rent->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> ><span class="outstanding-number coin" style="font-size:45px;" >
                                                                                    <?php echo e(number_format($rent->basic_price + $rent->insurance1 + $rent->insurance2 + $rent->option_price + $rent->extend_payment + $rent->discount + $rent->virtual_payment)); ?> </span> <span>円</span> </b>
                                                                            <div  class="smoking-right" style="font-size: 15px;vertical-align:bottom;margin-bottom: 7px;">
                                                                                <b>
																				<span>
                                                                                <?php if($rent->pay_status == '1'): ?>
                                                                                        <?php if($rent->pay_method == '3' ): ?> Web支払 <?php endif; ?>
                                                                                        <?php if($rent->pay_method == '4' ): ?> Portal決済 <?php endif; ?>
                                                                                        <?php if($rent->pay_method == '1' ): ?> 現金 <?php endif; ?>
                                                                                        <?php if($rent->pay_method == '2' ): ?> カード <?php endif; ?>
                                                                                    <?php else: ?>
																				</span>
																				</b>
                                                                                    <?php if($rent->pay_status != '1'): ?>
                                                                                        <select onchange="updatePriceStatus(event,'<?php echo e($rent->id); ?>','0','origin')" style="font-size:14px; border: 1px solid #333; color:#e20001;font-weight: bold">
                                                                                            <option value="0" style="color: #e20001;font-weight: bold">未決済</option>
                                                                                            <option value="1" style="color: #333;font-weight: bold">現金</option> <!--cash-->
                                                                                            <option value="2" style="color: #333;font-weight: bold" >カード</option> <!--credit card-->
                                                                                        </select>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
																	<tr>
                                                                        <td>

                                                                            <div class="row" style="font-size: 15px;">
                                                                                <div class="col-md-12 li-float clearfix">
                                                                                    <?php if($rent->basic_price != '0'): ?> <li>基本：<span class="coin"><?php echo e(number_format($rent->basic_price)); ?></span>円 </li> <?php endif; ?>
                                                                                    <?php if($rent->insurance1 != '0'): ?>  <li>免責：<span class="coin"><?php echo e(number_format($rent->insurance1)); ?></span>円 </li> <?php endif; ?>
                                                                                    <?php if($rent->insurance2 != '0'): ?>  <li>ワ補：<span class="coin"><?php echo e(number_format($rent->insurance2)); ?></span>円 </li> <?php endif; ?>
                                                                                    <?php $__currentLoopData = $rent->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <?php if($op->option_price > 0): ?>
                                                                                                <li><?php echo e($op->short_name); ?>：<span class="coin"><?php echo e(number_format($op->option_price)); ?></span>円</li>
                                                                                        <?php endif; ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    <?php if($rent->extend_payment != 0): ?>
                                                                                        <li>延泊 : <?php echo e(number_format($rent->extend_payment)); ?>円</li>
                                                                                    <?php endif; ?>
                                                                                    <?php if($rent->discount != 0): ?>
                                                                                        <li>調整 : <?php echo e(number_format($rent->discount)); ?>円</li>
                                                                                    <?php endif; ?>
                                                                                    <?php if($rent->given_points > 0 ): ?>
                                                                                        <li>ポ: <?php echo e(number_format($rent->given_points)); ?>円</li>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endif; ?>
                                                                    <!--original price end-->
                                                                    <!--saved additional price start-->
                                                                    <?php $__currentLoopData = $rent->saved_additional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr class="extra-options">
                                                                            <td>
                                                                                <b <?php if($rent->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> >
                                                                                    <span class="outstanding-number coin" style="font-size: 45px;" ><?php echo e(number_format($ad->total_price)); ?></span> <span>円</span></b>
                                                                                <b style="font-size: 15px;">
                                                                                  <span class="outstanding">
                                                                                      <?php if($ad->pay_status != '1'): ?>
                                                                                          <span style="color:#f31805;"> 未決済 </span>
                                                                                      <?php else: ?>
                                                                                          <?php if($ad->pay_status == '1'): ?>
                                                                                              <?php if($ad->pay_method == '1' ): ?> 現金 <?php endif; ?>
                                                                                              <?php if($ad->pay_method == '2' ): ?> カード <?php endif; ?>
                                                                                          <?php endif; ?>
                                                                                      <?php endif; ?>
                                                                                  </span>
                                                                                </b>
                                                                            </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="row" style="font-size: 15px;">
                                                                                <div class="col-md-12  li-float clearfix">
                                                                                    <?php $__currentLoopData = $ad->insurance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <?php if($in->price != '0'): ?>
                                                                                            <?php if($in->name == '免責補償'): ?>
                                                                                                <li> 免責：<span class="coin"><?php echo e(number_format($in->price)); ?></span>円</li>
                                                                                            <?php endif; ?>
                                                                                            <?php if($in->name == 'ワイド免責補償'): ?>
                                                                                                <li> ワ補：<span class="coin"><?php echo e(number_format($in->price)); ?></span>円</li>
                                                                                            <?php endif; ?>
                                                                                        <?php endif; ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    <?php $__currentLoopData = $ad->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <?php if($op->option_price != '0'): ?>
                                                                                                <li><?php echo e($op->short_name); ?>：<span class="coin"><?php echo e(number_format($op->option_price)); ?></span>円</li>
                                                                                        <?php endif; ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    <?php if($ad->extend_payment != 0): ?>
                                                                                        <li>延泊 : <?php echo e(number_format($ad->extend_payment)); ?>円</li>
                                                                                    <?php endif; ?>
                                                                                    <?php if($ad->adjustment_price != 0): ?>
                                                                                        <li>調整価格 : <?php echo e(number_format($ad->adjustment_price)); ?>円</li>
                                                                                    <?php endif; ?>
                                                                                    <?php if($rent->depart_task == '1' && $ad->etc_card > 0): ?>
                                                                                        <li>ETC利用料金:  <?php echo e(number_format($ad->etc_card)); ?>円</li>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <!--saved additional price end-->
                                                                    <!--current additional price start-->
                                                                    <?php $__currentLoopData = $rent->cu_additional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                      <?php if($ad->total_price != 0): ?>
                                                                        <tr class="extra-options">
                                                                            <td>
                                                                                <b <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?>>
                                                                                    <span class="outstanding-number coin" style="font-size:2.5em;padding-right: 5px;" ><?php echo e(number_format($ad->total_price)); ?></span><span style="padding-right: 5px;"> 円</span></b>
                                                                                <?php if($ad->pay_status != '1'): ?>
                                                                                    <div  class="smoking-right" style="font-size: 15px;vertical-align:bottom;margin-bottom: 7px;">
                                                                                        <b>
                                                                                            <span style="font-size: 15px;">
                                                                                                <select onchange="updatePriceStatus(event,'<?php echo e($rent->id); ?>', '<?php echo e($ad->id); ?>','addition')" style="border: 1px solid #333; font-weight: bold;">
                                                                                                    <option value="0" style="font-weight: bold">支払い方法</option>
                                                                                                    <option value="1" style="font-weight: bold">現金</option> <!--cash-->
                                                                                                    <option value="2" style="font-weight: bold">カード</option> <!--credit card-->
                                                                                                </select>
                                                                                            </span>
                                                                                        </b>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="row" style="font-size: 15px;">
                                                                                    <div class="col-md-12 li-float clearfix">
                                                                                        <?php $__currentLoopData = $ad->insurance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <?php if($in->price != '0'): ?>
                                                                                                <?php if($in->name == '免責補償'): ?>
                                                                                                    <li> 免責：<span class="coin"><?php echo e(number_format($in->price)); ?></span>円</li>
                                                                                                <?php endif; ?>
                                                                                                <?php if($in->name == 'ワイド免責補償'): ?>
                                                                                                    <li> ワ補：<span class="coin"><?php echo e(number_format($in->price)); ?></span>円</li>
                                                                                                <?php endif; ?>
                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        <?php $__currentLoopData = $ad->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <?php if($op->option_price != '0'): ?>
                                                                                                    <li><?php echo e($op->short_name); ?>：<span class="coin"><?php echo e(number_format($op->option_price)); ?></span>円</li>
                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        <?php if($ad->extend_payment != 0): ?>
                                                                                                <li> 延泊 : <?php echo e(number_format($ad->extend_payment)); ?>円</li>
                                                                                        <?php endif; ?>
                                                                                        <?php if($ad->adjustment_price != 0): ?>
                                                                                            <li> 調整価格 : <?php echo e(number_format($ad->adjustment_price)); ?>円</li>
                                                                                        <?php endif; ?>
                                                                                        <?php if($rent->depart_task == '1' && $ad->etc_card > 0): ?>
                                                                                            <li> ETC利用料金:: <?php echo e(number_format($ad->etc_card)); ?>円</li>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                      <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <!--current additional price end-->
                                                                </table>
                                                            </div>
                                                            <!---->
                                                        </div>
                                                        <div>
                                                            <div class="memo-block pull-right">
                                                                <a onclick="taskComplete(<?php echo e($rent->options); ?>,'<?php echo e(count($rent->options)); ?>','<?php echo e($count_number); ?>','<?php echo e($rent->id); ?>',<?php echo e(json_encode($rent)); ?>)">配車完了</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $count_number++; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <!--end rent part-->
                                <!--start return part-->
                                <?php $count_number_return = 0; ?>
                                <?php $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="car-common-block inactive   <?php if($service->comparebooking($rent,'tomorrow')): ?> box-hide <?php endif; ?> " id="target_<?php echo e($count_number); ?>" >
                                        <div class="car-common-inner">
                                            <div class="number-block">
                                                <h2><?php echo e($rent_display -$count_number); ?></h2>
                                            </div>
                                            <div class="common-content-block">
                                                <div class="user-block"  onclick="showHide('row_<?php echo e($count_number); ?>')">
                                                    <h2>返車</h2>
                                                    <?php if($rent->bookedcount > 0): ?>
                                                        <?php if($rent->bookedcount == 1): ?>
                                                            <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px;margin-left: 5px;">初
                                                            </h2>
                                                        <?php else: ?>
                                                            <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px 2px;margin-left: 3px;">リピート <?php if($rent->bookedcount > 1): ?> <?php echo e($rent->bookedcount); ?> <?php endif; ?>

                                                            </h2>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <?php if($rent->web_status == '3'): ?>

                                                        <?php if($rent->bag_choosed == '1'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS フリスク</h2> <?php endif; ?>
                                                        <?php if($rent->bag_choosed == '2'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS ぷっちょ</h2> <?php endif; ?>
                                                        <?php if($rent->bag_choosed == '3'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS 酔い止め</h2> <?php endif; ?>

                                                    <?php endif; ?>
                                                    <h3><span class="user-name">
                                                            <?php echo e($rent->last_name); ?> <?php echo e($rent->first_name); ?>

                                                            (<?php echo e($rent->fur_last_name); ?> <?php echo e($rent->fur_first_name); ?>)
                                                        </span> 様は <span class="time">
                                                            <?php echo e($date_val); ?>

                                                            <label id="changed_return_time_<?php echo e($count_number); ?>" onclick="changeReturnTime(event,<?php echo e(json_encode($rent)); ?>,'<?php echo e(date('G:i', strtotime($rent->returning))); ?>','<?php echo e($count_number); ?>')" style="cursor: pointer;"><?php echo e(date('G:i', strtotime($rent->return_set_day))); ?></label>
                                                            <i class="fa fa-phone-square" aria-hidden="true"></i>
                                                            <div class="change_return" id="change_return_<?php echo e($count_number); ?>">
                                                                <div> 連絡先: <span id="change_return_tel">--<?php echo e($rent->phone); ?>--</span></div>
                                                                <div style="margin-top: 5px;">
                                                                    <select name="change_return_time_<?php echo e($count_number); ?>" id="change_return_time_<?php echo e($count_number); ?>" style="height:28px;" onclick="return_select(event);">
                                                                        <?php $__currentLoopData = $hour; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($h); ?>"><?php echo e($h); ?> </option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                    <a class="btn btn-primary btn-sm" onclick="saveReturnTime(event)" style="margin-top: -4px;" >確定</a>
                                                                </div>
                                                            </div>
                                                        </span> 返却です。</h3>




                                                        <h3 class="pull-right"  style="width:20px;
     height: 30px;">
                                                            <span class="glyphicon glyphicon-cog  dropdown-toggle" style="font-size:24px; margin-top:4px;" data-toggle="dropdown" onclick="eventPrevent(event,'<?php echo e($count_number); ?>')"> &nbsp;
                                                            </span>
                                                            <ul class="dropdown-menu"  id="dropdown_<?php echo e($count_number); ?>" style="width:30px;"  >
                                                                <li><a data-value="return" tabIndex="-1" href="javascript:void(0);" onclick="javascript:print_booking(event, '<?php echo $rent->id; ?>')" title="詳細" ><label style="font-weight:300;margin-top: 5px;"> 印刷 </label></a></li>
                                                                <li><a data-value="return" tabIndex="-1" onclick="event.stopPropagation();"  href="<?php echo e(URL::to('/')); ?>/booking/edit/<?php echo e($rent->id); ?>" target="_blank"  title="編集" > <label style="font-weight:300;">編集</label></a></li>
                                                            </ul>
                                                        </h3>
                                                </div>
                                                <div id="row_<?php echo e($count_number); ?>" class="row" <?php if($count_number > 4): ?> style="display: none;" <?php endif; ?> >
                                                    <div class="col-sm-5 col-md-5 col-sm-offset-1">

                                                        <div class="child-baby-block">

                                                            <ul>
                                                                <li>
                                                                    <?php if($rent->etc_flag == true): ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/etc-icon.png" alt="">
                                                                    <?php else: ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/etc-icon_grey.png" alt="">
                                                                    <?php endif; ?>
                                                                </li>
                                                                <li class="<?php if($rent->child_seat == '0'): ?> child-grey <?php else: ?> child-blue <?php endif; ?>">チャイ<span>
                                                                        <?php if(!empty($rent->child_seat_number)): ?><?php echo e($rent->child_seat_number); ?>個 <?php endif; ?></span></li>
                                                                <li class="<?php if($rent->baby_seat == '0'): ?> child-grey <?php else: ?> child-blue <?php endif; ?>">ベビー<span>
                                                                        <?php if(!empty($rent->baby_seat_number)): ?><?php echo e($rent->baby_seat_number); ?>個 <?php endif; ?></span></li>
                                                                <li class="<?php if($rent->junior_seat == '0'): ?> child-grey <?php else: ?> child-blue <?php endif; ?> ">ジュニ<span>
                                                                        <?php if(!empty($rent->junior_seat_number)): ?><?php echo e($rent->junior_seat_number); ?>個 <?php endif; ?></span></li>
                                                                <li>
                                                                    <?php if($rent->snow_tire != '0'): ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/etc-icon2.png" alt="">
                                                                    <?php else: ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/etc-icon2_grey.png" alt="">
                                                                    <?php endif; ?>
                                                                </li>
                                                            </ul>
														</div>
                                                        <div class="model-block">
                                                            <ul class="taskbox_returnstyle01 taskbox-cartitlebox">
                                                                <li class="model-car"><img src="<?php echo e(URL::to('/')); ?>/images/cartask/car-icon-small.png" alt=""></li>
                                                                <li class="car-class"><?php echo e($rent->shortname); ?></li>
                                                                <li><span>
                                                                        <?php echo e($rent->car_number3); ?> <?php echo e($rent->car_number4); ?>

                                                                    </span></li>
                                                                <li class="model-smoking">
                                                                    <?php if($rent->smoke == 0): ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/smoking-icon-small.png" alt="">
                                                                    <?php else: ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/smoking-icon-small2.png" alt="">
                                                                    <?php endif; ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="child-baby-block">
                                                            <div style="margin-top: 10px;">
                                                                <div>
                                                                    <li style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center;background:#fff;"><span><i class="fa fa-car"></i> 配車</span></li>
                                                                    <li style="display: inline;padding-left: 10px;">
                                                                        <?php echo e(date('n月j日', strtotime($rent->departing))); ?>

                                                                    </li>
                                                                    <li style="display: inline;padding-left: 10px;"><?php echo e(date('G:i', strtotime($rent->departing))); ?></li>
                                                                    <li style="display: inline-block; padding-left: 10px;">
                                                                        <?php if($rent->night == '0' && $rent->day == '1'): ?>
                                                                           ( 当日返し)
                                                                        <?php else: ?>
                                                                            (<?php echo e($rent->night); ?>泊<?php echo e($rent->day); ?>日)
                                                                        <?php endif; ?>
                                                                    </li>
                                                                </div>
                                                            </div>
                                                            <!--admin memo start-->
                                                            <div>
                                                            <?php if(!empty($rent->admin_memo)): ?>
                                                                <div class="row date-block" style="padding-left:0px;margin-top:5px;">
                                                                    <div class="col-md-2" style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center;background:#fff;"><span> <i class="fa fa-pencil"></i>メモ</span></div>
                                                                    <div class="col-md-8" >
                                                                        <?php echo e($rent->admin_memo); ?>

                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <!--end-->

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <?php
                                                        $datetime = new DateTime($select_date);
                                                        $datetime->modify('+1 day');
                                                        $tom      =  $datetime->format('Y-m-d');
                                                        $return = date('Y-m-d',strtotime($rent->returning));
                                                        if(strtotime($rent->returning_updated) > strtotime($rent->returning))
                                                            $return = date('Y-m-d',strtotime($rent->returning_updated));
                                                        $next_booking = $service->nextBooking($rent->inventory_id, $tom);
                                                        ?>
                                                        
                                                        <?php if($next_booking->book_id != '0'): ?>
                                                        <div class="alert-block alert-block-tom " >
                                                            <a href="<?php echo e(URL::to('/')); ?>/booking/detail/<?php echo e($next_booking->book_id); ?>" target="_blank">
                                                                <h2>
                                                                    <?php if($task_date == 'today'): ?> この車両は明日 <?php endif; ?>
                                                                    <?php if($task_date == 'tom'): ?> この車両は明後日 <?php endif; ?>
                                                                     <?php echo e(date('Y/m/d H:i', strtotime($next_booking->departing))); ?> に使用予定
                                                                    <span>
                                                                       <?php if($next_booking->option_name != ''): ?> (オプション有) <?php endif; ?>
                                                                    </span>
                                                                    
                                                                        
                                                                    
                                                                    <img src="<?php echo e(URL::to('/')); ?>/images/cartask/link-icon.png" alt=""></h2></a>
                                                        </div>
                                                        <?php endif; ?>
                                                        <!--start miles part-->
                                                        <div class="m-t-sm">
                                                            <div class="list-inline taskbox_returnstyle01">
                                                                <li class="status-done taskbox_returnstyle01" style="margin-right: 10px;">貸出後のメーター：</li>
                                                                <li class="status-done ">
                                                                    <input type="text" class="miles_number" name="miles_<?php echo e($count_number); ?>" onkeyup="checkMiles(event,'<?php echo e($count_number); ?>')" value="<?php echo e($rent->miles); ?>" style="width:50px;" /> km
                                                                </li>
                                                            </div>
                                                            <div>
                                                                <li class="status-done" style="font-size:10px;font-weight:400;">( <span class="coin">貸出前：<?php echo e($rent->before_miles); ?></span>km )</li>
                                                                <input type="hidden" name="before_miles_<?php echo e($count_number); ?>" value="<?php echo e($rent->before_miles); ?>" />
                                                            </div>
                                                        </div>
                                                        <!--end miles part-->
                                                        <!--etc card price start-->
                                                        <input type="hidden"  name="etc_card_status_<?php echo e($count_number); ?>"  value="<?php echo e($rent->etc_flag); ?>" />
                                                        <?php if($rent->etc_flag == true): ?>
                                                            <div class="m-t-sm">
                                                                <div class="list-inline taskbox_returnstyle01">
                                                                    <li class="status-done taskbox_returnstyle01" style="margin-right: 10px;">ETC利用料金：</li>
                                                                    <li class="status-done ">
                                                                        <input type="number" class="miles_number" name="etc_card_<?php echo e($count_number); ?>"  value="<?php echo e($rent->etc_card_used); ?>" onkeyup="changeEtcCard('<?php echo e($rent->etc_card_used); ?>','<?php echo e($rent->unpaid_payment_return); ?>','<?php echo e($count_number); ?>')" style="width:100px;" />円
                                                                    </li>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <!--etc card price end-->
                                                        <!--start pay  part-->
                                                        <div >
                                                            <?php if($rent->depart_task == '1'): ?>
                                                            <div <?php if(intval($rent->total_return) > 0 ): ?> style="border-top:dotted 1px #f6b484; margin-top:15px;padding-top:5px;" <?php endif; ?> >
                                                                <!--saved additional price start-->
                                                                <?php $__currentLoopData = $rent->saved_additional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if($ad->total_price != 0 ): ?>
                                                                        <div>
                                                                            <label>
                                                                                <?php if($ad->extend_payment > 0): ?>
                                                                                <div>
                                                                                    <label style="width:100px;font-size: 14px;">延泊: </label>
                                                                                    <label style="font-size:14px;"><span class="outstanding-number coin" <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> ><?php echo e(number_format($ad->extend_payment)); ?></span></label>
                                                                                    <label>円</label>
                                                                                    <span>(<?php echo e($rent->night); ?>泊<?php echo e($rent->day); ?>日)</span>
                                                                                </div>
                                                                                <?php endif; ?>
                                                                                <?php if($ad->adjustment_price != '0'): ?>
                                                                                    <div>
                                                                                        <label style="width:100px;font-size: 14px;">調整価格:</label>
                                                                                        <label class="outstanding-number coin" <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> ><?php echo e(number_format($ad->adjustment_price)); ?></label>
                                                                                        <label>円</label>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                                <?php if($ad->etc_card != '0'): ?>
                                                                                    <div>
                                                                                        <label style="width:100px;font-size: 14px;">ETC利用料: </label>
                                                                                        <label class="outstanding-number coin">
                                                                                            <?php echo e(number_format($ad->etc_card)); ?>

                                                                                        </label>
                                                                                        <label>円</label>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </label>
                                                                        </div>
                                                                        <div>
                                                                            <label style="font-size: 40px;">
                                                                                <b>
                                                                                  <span class="outstanding">
                                                                                      <?php if($ad->pay_status != '1'): ?>
                                                                                          <span style="color:#f31805;"> 未決済  </span>
                                                                                          <span style="color:#f31805;"> <?php echo e(number_format($ad->total_price)); ?>  </span>
                                                                                      <?php else: ?>
                                                                                          <?php echo e(number_format($ad->total_price)); ?>

                                                                                      <?php endif; ?>
                                                                                  </span>
                                                                                </b>
                                                                            </label>
                                                                            <label>
                                                                                <?php if($ad->pay_method == '1' ): ?> 店舗現金 <?php endif; ?>
                                                                                <?php if($ad->pay_method == '2' ): ?> 店舗カード <?php endif; ?>
                                                                                <?php if($ad->pay_method == '3' ): ?> Web決済 <?php endif; ?>
                                                                                <?php if($ad->pay_method == '4' ): ?> Portal決済 <?php endif; ?>
                                                                            </label>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <!--saved additional price end-->
                                                                <!--current additional price start-->
                                                                <?php $__currentLoopData = $rent->cu_additional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if($ad->extend_payment != 0): ?>
                                                                    <div>
                                                                        <label style="width:100px;font-size: 14px;">延泊: </label>
                                                                        <label class="outstanding-number coin" <?php if($ad->pay_status != '1'): ?> style="color:#f31805; font-size: 14px;" <?php endif; ?> >
                                                                            <?php echo e(number_format($ad->extend_payment)); ?>

                                                                        </label>
                                                                        <label <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> >円</label>
                                                                        <span>(<?php echo e($rent->night); ?>泊<?php echo e($rent->day); ?>日)</span>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    <?php if($ad->adjustment_price != '0'): ?>
                                                                    <div>
                                                                        <label style="width:100px;font-size: 14px;">調整価格:</label>
                                                                        <label class="outstanding-number coin" <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> >
                                                                            <?php echo e(number_format($ad->adjustment_price)); ?>

                                                                        </label>
                                                                        <label <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> >円</label>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    <?php if($rent->etc_flag == true): ?>
                                                                    <div>
                                                                        <label style="width:100px;font-size: 14px;">ETC利用料: </label>
                                                                        <label class="outstanding-number coin"  id="etc_card_usage_<?php echo e($count_number); ?>"  <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> > <?php echo e($ad->etc_card); ?></label>
                                                                        <label <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> >円</label>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <!--current additional price end-->

                                                            </div>
                                                            <!--etc card price start-->
                                                            <?php if($rent->return_pay_status == false): ?>
                                                                <?php if($rent->unpaid_payment_return >= 0 ): ?>
                                                                    <div class="m-t-sm" id="unpaid_payment_div_<?php echo e($count_number); ?>" style="border-top:dotted 1px #f6b484; <?php if($rent->unpaid_payment_return== 0): ?> display:none <?php endif; ?>">
                                                                        <div class="list-inline taskbox_returnstyle01">
                                                                            <b <?php if($rent->return_pay_status == false): ?> style="color:#f31805;" <?php endif; ?> >
                                                                                <span class="outstanding-number coin" id="unpaid_total_<?php echo e($count_number); ?>" style="font-size: 45px;" >
                                                                                     <?php echo e(number_format($rent->unpaid_payment_return)); ?>

                                                                                </span>
                                                                                <span>円</span>
                                                                            </b>
                                                                            <b style="font-size: 15px;">
                                                                                <select id="etc_card_pay_method_<?php echo e($count_number); ?>" name="etc_card_pay_method_<?php echo e($count_number); ?>" style="font-size:14px; border: 1px solid #333; font-weight: bold">
                                                                                    <option value="0" style="color: #e20001;font-weight: bold">未決済</option>
                                                                                    <option value="1" style="color: #333;font-weight: bold">現金</option> <!--cash-->
                                                                                    <option value="2" style="color: #333;font-weight: bold" >カード</option> <!--credit card-->
                                                                                </select>
                                                                            </b>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <!--etc card price end-->
                                                            <?php endif; ?>
                                                            <div class="memo-block  pull-right">
                                                                <a onclick="taskreturnComplete(<?php echo e($rent->options); ?>,'<?php echo e(count($rent->options)); ?>','<?php echo e($count_number); ?>', '<?php echo e($rent->id); ?>', <?php echo e(json_encode($rent)); ?>)">返車完了</a>
                                                            </div>
                                                        </div>
                                                        <!--end pay  part-->
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $count_number++; $count_number_return++ ; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <!--ent return part-->

                                <?php if($rents_end_count > 0 || $returns_end_count > 0 ): ?>
                                    <div class="completed-block">
                                        <h2>完了したタスク</h2>
                                    </div>
                                <?php endif; ?>
                                <!--start rent end part-->
                                <?php $count_number_rent_end = 0; ?>
                                <?php $__currentLoopData = $rents_end; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="car-common-block completed" id="target_<?php echo e($count_number); ?>" >
                                        <div class="car-common-inner">
                                            <div class="number-block">
                                                <h2><?php echo e($count_number_rent_end+1); ?></h2>
                                            </div>
                                            <div class="common-content-block"  onclick="showHide('row_<?php echo e($count_number); ?>')">
                                                <div class="user-block">
                                                    <h2 class="done">完了</h2>
                                                    <h2>配車</h2>
                                                    <?php if($rent->bookedcount > 0): ?>
                                                        <?php if($rent->bookedcount == 1): ?>
                                                            <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px;margin-left: 5px;">初
                                                            </h2>
                                                        <?php else: ?>
                                                            <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px 2px;margin-left: 3px;">リピート <?php if($rent->bookedcount > 1): ?> <?php echo e($rent->bookedcount); ?> <?php endif; ?>

                                                            </h2>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php if($rent->web_status == '3'): ?>
                                                        <?php if($rent->bag_choosed == '1'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 3px;margin-left: 5px;" >QS フリスク</h2> <?php endif; ?>
                                                        <?php if($rent->bag_choosed == '2'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS ぷっちょ</h2> <?php endif; ?>
                                                        <?php if($rent->bag_choosed == '3'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS 酔い止め</h2> <?php endif; ?>
                                                    <?php endif; ?>
                                                    <h3><span class="user-name">
                                                        <?php echo e($rent->last_name); ?> <?php echo e($rent->first_name); ?>

                                                            (<?php echo e($rent->fur_last_name); ?> <?php echo e($rent->fur_first_name); ?>)
                                                    </span> 様は
                                                    <span class="time">
                                                        <?php echo e($date_val); ?>

                                                        <?php echo e(date('G:i', strtotime($rent->departing))); ?>

                                                    </span> 出発です。</h3>
                                                    <!--menu link-->

                                                    <h3 class="pull-right" style="width:20px;
     height: 30px;">
                                                        <span class="glyphicon glyphicon-cog  dropdown-toggle" style="font-size:24px; margin-top:4px;" data-toggle="dropdown" onclick="eventPrevent(event,'<?php echo e($count_number); ?>')"  >
                                                            &nbsp;
                                                        </span>
                                                        <ul class="dropdown-menu" id="dropdown_<?php echo e($count_number); ?>" style="width:30px;"  >
                                                            <li><a data-value="return" tabIndex="-1" href="javascript:void(0);" onclick="javascript:print_booking(event, '<?php echo $rent->id; ?>');" title="詳細" ><label style="font-weight:300;margin-top: 5px;"> 印刷 </label></a></li>
                                                            <li><a data-value="return" tabIndex="-1" onclick="event.stopPropagation();"  href="<?php echo e(URL::to('/')); ?>/booking/edit/<?php echo e($rent->id); ?>" target="_blank"  title="編集" > <label style="font-weight:300;margin-top: 5px;">編集</label></a></li>
                                                        </ul>
                                                    </h3>
                                                    <!---->
                                                </div>
                                                <div id="row_<?php echo e($count_number); ?>" class="row user-block-content" <?php if($count_number > 4): ?> style="display: none;" <?php endif; ?>>
                                                    <div class="col-sm-5 col-sm-12" style="padding-left: 90px;">
                                                        <div class="row" style="margin-bottom:5px;">
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="smoking-block p_lsm5" style="padding-right:0px;">
                                                                    <div class="smoking-left">
                                                                        <div class="smoking-content">
                                                                            <h2><?php echo e($rent->car_number1); ?> <?php echo e($rent->car_number2); ?></h2>
                                                                            <span><?php echo e($rent->car_number3); ?></span>
                                                                            <h3><?php echo e($rent->car_number4); ?></h3>
                                                                        </div>
                                                                    </div>
                                                                    <div class="smoking-right" style="vertical-align: middle">
                                                                        <?php if($rent->smoke == 0): ?>
                                                                            <img src="<?php echo e(URL::to('/')); ?>/images/cartask/smoking-icon.png">
                                                                        <?php else: ?>
                                                                            <img src="<?php echo e(URL::to('/')); ?>/images/cartask/smoking-icon2.png">
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="smoking-right" style="vertical-align: middle">
                                                                        <b style="font-size:1.5em;"><?php echo e($rent->shortname); ?></b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row"  style="font-size: 15px;margin-left: 0px;">
                                                                <div class="date-block" style="padding-right:0px;">
                                                                    <li style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center"><span><i class="fa fa-undo"></i> 返却</span></li>
                                                                    <li style="display: inline-block; padding-left: 10px;">
                                                                        <?php if($rent->night == '0' && $rent->day == '1'): ?>
                                                                            当日返し
                                                                        <?php else: ?>
                                                                            <?php echo e($rent->night); ?>泊<?php echo e($rent->day); ?>日
                                                                        <?php endif; ?>
                                                                    </li>
                                                                    <li style="display: inline;padding-left: 10px;">
                                                                        <?php echo e(date('n月j日', strtotime($rent->returning))); ?>

                                                                    </li>
                                                                    <li style="display: inline;padding-left: 10px;"><?php echo e(date('G:i', strtotime($rent->returning))); ?></li>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <!--QS status start-->
                                                                <?php if($rent->web_status > 0 ): ?>
                                                                    <div class="row date-block" style="margin-top: 5px;">
                                                                        <div class="col-md-2" style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center;">
                                                                            <span><i class="fa fa-clock-o"></i> QS</span>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <?php if($rent->web_status == '1'): ?>
                                                                                <span>Web免許</span>
                                                                                <?php if(!empty($rent->driver_license_images)): ?>
                                                                                    <span class="license" onclick="viewlicense(<?php echo e(json_encode($rent)); ?>)">免</span>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                            <?php if($rent->web_status == '2'): ?>
                                                                                <span>Web免許</span>
                                                                                <?php if(!empty($rent->driver_license_images)): ?>
                                                                                    <span class="license" onclick="viewlicense(<?php echo e(json_encode($rent)); ?>)">免</span>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                            <?php if($rent->web_status == '3'): ?>
                                                                                <span>Web免許 </span>
                                                                                    <?php if(!empty($rent->driver_license_images)): ?>
                                                                                        <span class="license" onclick="viewlicense(<?php echo e(json_encode($rent)); ?>)">免</span>
                                                                                    <?php endif; ?>
                                                                                <span>&nbsp;Web決済</span>
                                                                                <span>&nbsp;[末尾<?php echo e($rent->card_last4); ?>] </span>
                                                                            <?php endif; ?>
                                                                            <?php if($rent->bag_choosed == '1'): ?>
                                                                                <span>  フリスクをご希望</span>
                                                                            <?php endif; ?>
                                                                            <?php if($rent->bag_choosed == '2'): ?>
                                                                                <span>ぷっちょをご希望 </span>
                                                                            <?php endif; ?>
                                                                            <?php if($rent->bag_choosed == '3'): ?>
                                                                                <span>酔い止めをご希望 </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                            <!--QS status end-->
                                                                    <!--pick up start-->
                                                                    <?php if($rent->free_options_category > 0): ?>
                                                                        <div class="row date-block" style="margin-top: 5px;">
                                                                            <div class="col-md-2" style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center"><span>送迎</span></div>
                                                                            <div class="col-md-8">
                                                                                <?php $c = 0; ?>
                                                                                <?php if($rent->free_options_category == '1'): ?> 国内線送迎 <?php endif; ?>
                                                                                <?php if($rent->free_options_category == '2'): ?> 国際線送迎 <?php endif; ?>
                                                                                <?php if($rent->free_options_category == '3'): ?> コインパーキング <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                                <!--end pick up-->
                                                                        <!--admin memo start-->
                                                                        <?php if(!empty($rent->admin_memo)): ?>
                                                                            <div class="row date-block" style="margin-top: 5px;">
                                                                                <div class="col-md-2" style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center"><span><i class="fa fa-pencil"></i> メモ</span></div>
                                                                                <div class="col-md-8" >
                                                                                    <?php echo e($rent->admin_memo); ?>

                                                                                </div>
                                                                            </div>
                                                                            <?php endif; ?>
                                                                                    <!--end-->
                                                            </div>
                                                            <div>
                                                                <?php if(!empty($rent->client_message)): ?>
                                                                    <div class="row date-block clearfix" style="margin-top: 5px;">
                                                                        <li style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center;float:left;"><span>お客様</span></li>
                                                                        <li style="display: inline-block; padding-left: 10px; padding-right: 10px;max-width: 220px; float:left;">
                                                                            <?php echo e($rent->client_message); ?>

                                                                        </li>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-sm-12">
                                                        <div>
                                                            <div class="check_items">
                                                                <div <?php if($rent->insurance1_flag == true ): ?> class="span-items-on" <?php else: ?> class="span-items-off" <?php endif; ?>  style="" >
                                                                    <?php if($rent->insurance1_flag == true ): ?> <i class="fa fa-check-circle" style="color:#22c03c"></i> <span style="font-weight: bold" >免責</span>
                                                                    <?php else: ?> <span> 免責</span> <?php endif; ?> </div>
                                                                <div <?php if($rent->insurance2_flag == true ): ?> class="span-items-on" <?php else: ?> class="span-items-off" <?php endif; ?> >
                                                                    <?php if($rent->insurance2_flag == true ): ?> <i class="fa fa-check-circle" style="color:#22c03c" ></i> <span style="font-weight: bold">ワ補</span>
                                                                    <?php else: ?> <span>ワ補</span>  <?php endif; ?> </div>
                                                                <div <?php if($rent->etc_flag == true ): ?> class="span-items-on" <?php else: ?> class="span-items-off" <?php endif; ?> >
                                                                    <?php if($rent->etc_flag == true ): ?> <i class="fa fa-check-circle" style="color:#22c03c" ></i> <span style="font-weight: bold" >ETC</span>
                                                                    <?php else: ?> <span>ETC</span> <?php endif; ?> </div>
                                                            </div>
                                                            <!--price list-->
                                                            <div>
                                                                <table class="table tbl-no-border" style="font-size: 12px;" >
                                                                    <!--original price start-->
                                                                    <?php if($rent->payment > 0): ?>
                                                                    <tr>
                                                                        <td>
                                                                            <b><span class="outstanding-number coin fs45" <?php if($rent->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> ><?php echo e(number_format($rent->payment)); ?> </span><span>円</span> </b>
																			
																			<div  class="smoking-right" style="font-size: 15px;vertical-align:bottom;margin-bottom: 7px;">
																				 <span style="color:#ccc;"> / </span> 
                                                                                <b> 
																				<span>

																					<?php if($rent->pay_status == '1'): ?>
																						<?php if($rent->pay_method == '3' ): ?> Web支払 <?php endif; ?>
																						<?php if($rent->pay_method == '4' ): ?> Portal決済 <?php endif; ?>
																						<?php if($rent->pay_method == '1' ): ?> 現金 <?php endif; ?>
																						<?php if($rent->pay_method == '2' ): ?> カード <?php endif; ?>
																					<?php else: ?>
																						<?php if($rent->pay_status != '1'): ?>
																							<select onchange="updatePriceStatus(event,'<?php echo e($rent->id); ?>','0','origin')" style="border: 1px solid #333">
																								<option value="0">選択... </option>
																								<option value="1">現金</option> <!--cash-->
																								<option value="2">カード</option> <!--credit card-->
																							</select>
																						<?php endif; ?>
																					<?php endif; ?>
																				</span>
																				</b>
																			</div>
																		</td>
                                                                    </tr>
																	<tr>
                                                                        <td>
                                                                            <div class="row" style="font-size: 15px;">
                                                                                <div class="col-md-12 li-float clearfix">
                                                                                    <?php if($rent->basic_price != '0'): ?> <li>基本：<span class="coin"><?php echo e(number_format($rent->basic_price)); ?></span>円</li> <?php endif; ?>
                                                                                    <?php if($rent->insurance1 != '0'): ?>  <li>免責：<span class="coin"><?php echo e(number_format($rent->insurance1)); ?></span>円</li> <?php endif; ?>
                                                                                    <?php if($rent->insurance2 != '0'): ?>  <li>ワ補：<span class="coin"><?php echo e(number_format($rent->insurance2)); ?></span>円</li> <?php endif; ?>
                                                                                    <?php $op_count = 0 ; ?>
                                                                                    <?php $__currentLoopData = $rent->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <?php if($op->option_price > 0): ?>
                                                                                            <?php if($op_count < 2): ?>
                                                                                                <li><?php echo e($op->short_name); ?>：<span class="coin"><?php echo e(number_format($op->option_price)); ?></span>円</li>
                                                                                            <?php endif; ?>
                                                                                            <?php $op_count++; ?>
                                                                                        <?php endif; ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    <?php $op_count = 0 ; ?>
                                                                                    <?php $__currentLoopData = $rent->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <?php if($op->option_price > 0): ?>
                                                                                            <?php if($op_count > 1): ?>
                                                                                                <li><?php echo e($op->short_name); ?>：<span class="coin"><?php echo e(number_format($op->option_price)); ?></span>円</li>
                                                                                            <?php endif; ?>
                                                                                            <?php $op_count++; ?>
                                                                                        <?php endif; ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <?php if($rent->extend_payment != 0): ?>
                                                                                    <div class="col-md-6">
                                                                                        延泊 : <?php echo e(number_format($rent->extend_payment)); ?>円
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                                <?php if($rent->discount != 0): ?>
                                                                                    <div class="col-md-6">
                                                                                        調整価格 : <?php echo e(number_format($rent->discount)); ?>円
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                                <?php if($rent->given_points > 0 ): ?>
                                                                                    <div class="col-md-6">
                                                                                        ポ: <?php echo e(number_format($rent->given_points)); ?>円
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endif; ?>
                                                                    <!--original price end-->
                                                                    <!--saved additional price start-->
                                                                    <?php $__currentLoopData = $rent->saved_additional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if($ad->total_price > 0): ?>
                                                                            <tr class="extra-options">
                                                                            <td><b>
                                                                                  <span class="outstanding">
                                                                                      <?php if($ad->pay_status != '1'): ?>
                                                                                          <span style="color:#f31805;"> 未決済 </span>
                                                                                      <?php else: ?>
                                                                                          &nbsp;
                                                                                      <?php endif; ?>
                                                                                  </span>
                                                                                </b>
                                                                                <b><span class="outstanding-number coin fs25" <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> ><?php echo e(number_format($ad->total_price)); ?>円</span></b><span style="color:#ccc;"> / </span> 
                                                                                <b><span>
																				<?php if($ad->pay_status == '1'): ?>
                                                                                    <?php if($ad->pay_method == '1' ): ?> 現金 <?php endif; ?>
                                                                                    <?php if($ad->pay_method == '2' ): ?> カード <?php endif; ?>
                                                                                <?php endif; ?>
																				</span></b>
                                                                                <div class="row" style="font-size: 12px;">
                                                                                    <div class="col-md-12 li-float clearfix">
                                                                                        <?php $__currentLoopData = $ad->insurance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <?php if($in->price != '0'): ?>
                                                                                                <?php if($in->name == '免責補償'): ?>
                                                                                                <li> 免責：<span class="coin"><?php echo e(number_format($in->price)); ?></span>円</li>
                                                                                                <?php endif; ?>
                                                                                                <?php if($in->name == 'ワイド免責補償'): ?>
                                                                                                <li> ワ補：<span class="coin"><?php echo e(number_format($in->price)); ?></span>円</li>
                                                                                                <?php endif; ?>
                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        <?php $op_count = 0 ; ?>
                                                                                        <?php $__currentLoopData = $ad->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <?php if($op->option_price != '0'): ?>
                                                                                                <?php if($op_count < 4 ): ?>
                                                                                                    <li><?php echo e($op->short_name); ?>：<span class="coin"><?php echo e(number_format($op->option_price)); ?></span>円</li>
                                                                                                <?php endif; ?>
                                                                                                <?php $op_count++; ?>
                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																						
																						
                                                                                        <?php $op_count = 0 ; ?>
                                                                                        <?php $__currentLoopData = $ad->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <?php if($op->option_price != '0'): ?>
                                                                                                <?php if($op_count > 3 ): ?>
                                                                                                    <li><?php echo e($op->short_name); ?>：<span class="coin"><?php echo e(number_format($op->option_price)); ?></span>円</li>
                                                                                                <?php endif; ?>
                                                                                                <?php $op_count++; ?>
                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    
                                                                                    <?php if($ad->extend_payment != 0): ?>
                                                                                        <li>
                                                                                            延泊 : <?php echo e(number_format($ad->extend_payment)); ?>円
                                                                                        </li>
                                                                                    <?php endif; ?>
                                                                                    <?php if($ad->adjustment_price != 0): ?>
                                                                                        <li>
                                                                                            調整価格 : <?php echo e(number_format($ad->adjustment_price)); ?>円
                                                                                        </li>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                     <!--saved additional price end-->
                                                                </table>
                                                            </div>
                                                            <!---->
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $count_number++;$count_number_rent_end++; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <!--end rent end part-->
                                <!--start return end part-->
                                <?php $count_number_return_end = 0; ?>
                                <?php $__currentLoopData = $returns_end; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="car-common-block completed  <?php if($service->comparebooking($rent,'tomorrow')): ?> box-hide <?php endif; ?> " id="target_<?php echo e($count_number); ?>" >
                                        <div class="car-common-inner">
                                            <div class="number-block">
                                                <h2><?php echo e($count_number_rent_end+1); ?></h2>
                                            </div>
                                            <div class="common-content-block">
                                                <div class="user-block"  onclick="showHide('row_<?php echo e($count_number); ?>')" >
                                                    <h2 class="done">完了</h2>
                                                    <h2 style="background-color: #f5d7bf;color: #d87f39;border-color: #d87f39;">返車</h2>
                                                    <?php if($rent->bookedcount > 0): ?>
                                                        <?php if($rent->bookedcount == 0): ?>
                                                            <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px;margin-left: 5px;">初

                                                            </h2>
                                                        <?php else: ?>
                                                            <h2 class="repeat" style="color: #a40000;border-color: #a40000;background-color: transparent;padding: 1px 2px;margin-left: 3px;">リピート <?php if($rent->bookedcount > 1): ?> <?php echo e($rent->bookedcount); ?> <?php endif; ?>
                                                            </h2>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <?php if($rent->web_status == '3'): ?>

                                                        <?php if($rent->bag_choosed == '1'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS フリスク</h2> <?php endif; ?>
                                                        <?php if($rent->bag_choosed == '2'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS ぷっちょ</h2> <?php endif; ?>
                                                        <?php if($rent->bag_choosed == '3'): ?><h2 style="color: #026E11;border-color: #026E11;background-color: transparent;padding: 1px 2px;margin-left: 3px;" >QS 酔い止め</h2> <?php endif; ?>

                                                    <?php endif; ?>

                                                    <h3><span class="user-name">
                                                            <?php echo e($rent->last_name); ?> <?php echo e($rent->first_name); ?>

                                                            (<?php echo e($rent->fur_last_name); ?> <?php echo e($rent->fur_first_name); ?>)
                                                         </span> 様は <span class="time">
                                                            <?php echo e($date_val); ?>

                                                            
                                                            <?php echo e(date('G:i', strtotime($rent->returning))); ?>

                                                        </span> 返却です。
                                                    </h3>
                                                    <!--menu link-->



                                                    <h3 class="pull-right"  style="width:20px;
     height: 30px;">
                                                        <span class="glyphicon glyphicon-cog  dropdown-toggle" style="font-size:24px; margin-top:4px;"   data-toggle="dropdown" onclick="eventPrevent(event,'<?php echo e($count_number); ?>')">
                                                            &nbsp;
                                                        </span>
                                                        <ul class="dropdown-menu"  id="dropdown_<?php echo e($count_number); ?>" style="width:30px;"  >
                                                            <li><a data-value="return" tabIndex="-1" href="javascript:void(0);" onclick="javascript:print_booking(event, '<?php echo $rent->id; ?>')" title="詳細" ><label style="font-weight:300;margin-top: 5px;"> 印刷 </label></a></li>
                                                            <li><a data-value="return" tabIndex="-1" onclick="event.stopPropagation();"  href="<?php echo e(URL::to('/')); ?>/booking/edit/<?php echo e($rent->id); ?>" target="_blank"  title="編集" > <label style="font-weight:300;">編集</label></a></li>
                                                        </ul>
                                                    </h3>
                                                    <!---->
                                                </div>
                                                <div id="row_<?php echo e($count_number); ?>" class="row user-block-content" <?php if($count_number > 4): ?> style="display: none;" <?php endif; ?>>
                                                    <div class="col-sm-5 col-md-5 col-sm-offset-1">
														
                                                        <div class="child-baby-block">
                                                            <ul>
                                                                <li>
                                                                    <?php if($rent->etc_flag == true): ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/etc-icon.png" alt="">
                                                                    <?php else: ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/etc-icon_grey.png" alt="">
                                                                    <?php endif; ?>
                                                                </li>
                                                                <li class="<?php if($rent->child_seat == '0'): ?> child-grey <?php else: ?> child-blue <?php endif; ?>">チャイ<span>
                                                        <?php if(!empty($rent->child_seat_number)): ?><?php echo e($rent->child_seat_number); ?>個 <?php endif; ?></span></li>
                                                                <li class="<?php if($rent->baby_seat == '0'): ?> child-grey <?php else: ?> child-blue <?php endif; ?>">ベビー<span>
                                                        <?php if(!empty($rent->baby_seat_number)): ?><?php echo e($rent->baby_seat_number); ?>個 <?php endif; ?></span></li>
                                                                <li class="<?php if($rent->junior_seat == '0'): ?> child-grey <?php else: ?> child-blue <?php endif; ?> ">ジュニ<span>
                                                        <?php if(!empty($rent->junior_seat_number)): ?><?php echo e($rent->junior_seat_number); ?>個 <?php endif; ?></span></li>
                                                                <li>
                                                                    <?php if($rent->snow_tire != '0'): ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/etc-icon2.png" alt="">
                                                                    <?php else: ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/etc-icon2_grey.png" alt="">
                                                                    <?php endif; ?>
                                                                </li>
                                                            </ul>
														</div>
                                                        <div class="model-block">
                                                            <ul class="taskbox_returnstyle01 taskbox-cartitlebox">
                                                                <li class="model-car"><img src="<?php echo e(URL::to('/')); ?>/images/cartask/car-icon-small.png" alt=""></li>
                                                                <li class="car-class"><?php echo e($rent->shortname); ?></li>
                                                                <li><span>
                                                                        <?php echo e($rent->car_number3); ?> <?php echo e($rent->car_number4); ?>

                                                                     </span></li>
                                                                <li class="model-smoking">
                                                                    <?php if($rent->smoke == 0): ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/smoking-icon-small.png" alt="">
                                                                    <?php else: ?>
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/smoking-icon-small2.png" alt="">
                                                                    <?php endif; ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="child-baby-block">
                                                            <div style="margin-top: 10px;">
                                                                <div>
                                                                    <li style="width:67px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center"><span><i class="fa fa-car"></i> 配車</span></li>
                                                                    <li style="display: inline;padding-left: 10px;">
                                                                        <?php echo e(date('n月j日', strtotime($rent->departing))); ?>

                                                                    </li>
                                                                    <li style="display: inline;padding-left: 10px;"><?php echo e(date('G:i', strtotime($rent->departing))); ?></li>
                                                                    <li style="display: inline-block; padding-left: 10px;">
                                                                        <?php if($rent->night == '0' && $rent->day == '1'): ?>
                                                                            ( 当日返し)
                                                                        <?php else: ?>
                                                                            (<?php echo e($rent->night); ?>泊<?php echo e($rent->day); ?>日)
                                                                        <?php endif; ?>
                                                                    </li>
                                                                </div>
                                                            </div>
                                                            <!--admin memo start-->
                                                            <div>
                                                                <?php if(!empty($rent->admin_memo)): ?>
                                                                    <div class="row date-block" style="padding-left:0px;margin-top:5px;">
                                                                        <div class="col-md-2" style="width:75px;border: 1px solid #1c2222;border-radius: 2px;padding:2px 3px 2px 3px;display:inline-block;text-align: center"><span><i class="fa fa-pencil"></i> メモ</span></div>
                                                                        <div class="col-md-10" >
                                                                            <?php echo e($rent->admin_memo); ?>

                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <!--end-->
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <?php
                                                        $datetime = new DateTime($select_date);
                                                        $datetime->modify('+1 day');
                                                        $tom      =  $datetime->format('Y-m-d');
                                                        $return = date('Y-m-d',strtotime($rent->returning));
                                                        if(strtotime($rent->returning_updated) > strtotime($rent->returning))
                                                            $return = date('Y-m-d',strtotime($rent->returning_updated));
                                                        $next_booking = $service->nextBooking($rent->inventory_id, $tom);
                                                        ?>
                                                        
                                                        <?php if($next_booking->book_id != '0'): ?>
                                                            <div class="alert-block alert-block-tom" >
                                                                <a href="<?php echo e(URL::to('/')); ?>/booking/detail/<?php echo e($next_booking->book_id); ?>" target="_blank">
                                                                    <h2>
                                                                        <?php if($task_date == 'today'): ?> この車両は明日 <?php endif; ?>
                                                                        <?php if($task_date == 'tom'): ?> この車両は明後日 <?php endif; ?>
                                                                        <?php echo e(date('Y/m/d H:i', strtotime($next_booking->departing))); ?> に使用予定
                                                        <span>
                                                           <?php if($next_booking->option_name != ''): ?> (オプション有) <?php endif; ?>
                                                        </span>
                                                        
                                                            
                                                        
                                                                        <img src="<?php echo e(URL::to('/')); ?>/images/cartask/link-icon.png" alt=""></h2></a>
                                                            </div>
                                                        <?php endif; ?>
                                                        <!--start miles part-->
                                                        <div class="m-t-sm">
                                                            <div class="list-inline taskbox_returnstyle01">
                                                                <li class="status-done taskbox_returnstyle01" style="margin-right: 10px;">貸出後のメーター：</li>
                                                                <li class="status-done taskbox_returnstyle01">
                                                                    <?php echo e(number_format($rent->miles)); ?> km
                                                                    
                                                                </li>
                                                            </div>
                                                            <div>
                                                                <li class="status-done" style="font-size:10px;font-weight:400;">( <span class="coin">貸出前：<?php echo e($rent->before_miles); ?></span>km )</li>
                                                                
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div>
                                                                <?php if($rent->depart_task == '1'): ?>
                                                                        <!--deaprt task == 1-->
                                                                <table class="table" style="font-size: 12px;" >
                                                                    <!--saved additional price start-->
                                                                    <?php $__currentLoopData = $rent->saved_additional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if($ad->total_price != 0 ): ?>
                                                                            <div>
                                                                                <label>
                                                                                    <?php if($ad->extend_payment > 0): ?>
                                                                                        <div>
                                                                                            <label style="width:100px;font-size: 14px;">延泊: </label>
                                                                                            <label style="font-size:14px;"><span class="outstanding-number coin" <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> ><?php echo e(number_format($ad->extend_payment)); ?></span></label>
                                                                                            <label>円</label>
                                                                                            <span>(<?php echo e($rent->night); ?>泊<?php echo e($rent->day); ?>日)</span>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                    <?php if($ad->adjustment_price != '0'): ?>
                                                                                        <div>
                                                                                            <label style="width:100px;font-size: 14px;">調整価格:</label>
                                                                                            <label class="outstanding-number coin" <?php if($ad->pay_status != '1'): ?> style="color:#f31805;" <?php endif; ?> ><?php echo e(number_format($ad->adjustment_price)); ?></label>
                                                                                            <label>円</label>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                    <?php if($ad->etc_card != '0'): ?>
                                                                                        <div>
                                                                                            <label style="width:100px;font-size: 14px;">ETC利用料: </label>
                                                                                            <label class="outstanding-number coin">
                                                                                                <?php echo e(number_format($ad->etc_card)); ?>

                                                                                            </label>
                                                                                            <label>円</label>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </label>
                                                                            </div>
                                                                            <div>
                                                                                <label style="font-size: 40px;">
                                                                                    <b>
                                                                                  <span class="outstanding">
                                                                                      <?php if($ad->pay_status != '1'): ?>
                                                                                          <span style="color:#f31805;"> 未決済  </span>
                                                                                          <span style="color:#f31805;"> <?php echo e(number_format($ad->total_price)); ?>  </span>
                                                                                      <?php else: ?>
                                                                                          <?php echo e(number_format($ad->total_price)); ?>

                                                                                      <?php endif; ?>
                                                                                  </span>
                                                                                    </b>
                                                                                </label>
                                                                                <label>
                                                                                    <?php if($ad->pay_method == '1' ): ?> 店舗現金 <?php endif; ?>
                                                                                    <?php if($ad->pay_method == '2' ): ?> 店舗カード <?php endif; ?>
                                                                                    <?php if($ad->pay_method == '3' ): ?> Web決済 <?php endif; ?>
                                                                                    <?php if($ad->pay_method == '4' ): ?> Portal決済 <?php endif; ?>
                                                                                </label>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <!--saved additional price end-->
                                                                </table>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <!--end pay  part-->
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $count_number++; $count_number_rent_end++ ; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <!--end rent end part-->
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
                    <h5 class="modal-title" id="optionModalLabel">
                        
                        下記の事項の対応はお済でしょうか？
                    </h5>
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
                    
                    <button type="button" class="close" style="margin-top: -10px;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label><strong id="noselectModalText" style="font-size: 14px;">必須のタスクリストを完了させてください。</strong> </label>
                </div>
            </div>
        </div>
    </div>
    <!--update price modal-->
    <!-- Modal -->
    <div class="modal fade" id="updatepricestatusModal" tabindex="-1" role="dialog" aria-labelledby="optionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionModalLabel">支払い方法の変更</h5>
                    <button type="button" class="close" style="margin-top: -20px;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        支払い方法を変更しますか？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="updatePriceStatusModalBack()">いいえ</button>
                    <button type="button" class="btn btn-primary" onclick="updatePriceStatusModal()">はい</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <script src="<?php echo e(URL::to('/')); ?>/js/jquery.zoom.js"></script>
    <style>
        .common-content-block .dropdown-menu {
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
        .return_td{
            vertical-align: middle !important;
            padding: 3px !important;
        }
    </style>
    <script>
        function print_booking(e, booking_id){
            e.stopPropagation();
            window.open("<?php echo URL::to('/booking/printtask'); ?>/"+booking_id, "BookingPrintWindow", "width=500,height=500");
        }

        function printalltask(){
            var shop_id = $('#shopid').val();
            //alert(shop_id);
            window.open("<?php echo URL::to('/booking/printalltask'); ?>/"+shop_id+"?task_date="+task_date, "BookingAllPrintWindow", "width=500,height=500");
        }

        function printalltaskempty(){
            var shop_id = $('#shopid').val();
            //alert(shop_id);
            window.open("<?php echo URL::to('/booking/printalltaskempty'); ?>/"+shop_id+"?task_date="+task_date, "BookingAllPrintWindow", "width=500,height=500");
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
                scrollTop: $("#<?php echo e($target_object); ?>").offset().top-400
            }, 300);
            var target_value = '<?php echo e($target_object); ?>';
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
            var url = '<?php echo e(URL::to('/')); ?>/booking/savestatus';
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
            var url = '<?php echo e(URL::to('/')); ?>/booking/savestatus';
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
            var url = '<?php echo e(URL::to('/')); ?>/booking/savememo';
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
        function taskComplete(lists,count,index, rent_id, book ){
            $('#emptyoption').hide();
            object_index = index;
            booking_id = rent_id;
            var pass = true;
            var current_flag = true ;
            if(book.pay_status_flag == false) current_flag = false;
            if(current_flag == true) {
                $('#optionModal').modal('hide');
            }else {
                $('#noselectModalText').text("必須項目が完了していません。");
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
                    var checked = '';
                    var disabled = '';
                    //}
                    //if(el.index != '38' || el.index != '106' ) //except smart driveout option service
                    if(el.index != '106' ) //except smart driveout option service
                        option_hrml +='<li><label style="cursor:pointer"><input type="checkbox" '+checked+' '+disabled+' />&nbsp;<span style="position: relative; left:10px; top: -2px;">'+el.option_name+' '+el.option_number+'個</span></label></li>';
                });
                pass = false;
            }
            //loop additional
            var additional = book.saved_additional;
            additional.forEach(function (item) {
                var add_options = item.options;
                if(add_options.length > 0) {
                    add_options.forEach(function (el) {
                        var checked = '';
                        var disabled = '';
                        if(el.option_number > 0) {
                            if (el.index != '38' || el.index != '106') //except smart driveout option service
                                option_hrml += '<li><label style="cursor:pointer"><input type="checkbox" ' + checked + ' ' + disabled + ' />&nbsp;<span style="position: relative; left:10px; top: -2px;">' + el.option_name + ' ' + el.option_number + '個</span></label></li>';
                        }
                    });
                    pass = false;
                }
            });

            if(parseInt(book.bag_choosed) > 0 ){
                var checked = '';
                var disabled = '';
                var bag_choosed = '';
                if(book.bag_choosed == '1') bag_choosed = 'QS フリスク';
                if(book.bag_choosed == '2') bag_choosed = 'QS ぷっちょ';
                if(book.bag_choosed == '3') bag_choosed = 'QS 酔い止め';

                option_hrml +='<li><label style="cursor:pointer"><input type="checkbox" '+checked+' '+disabled+' />';
                option_hrml +='&nbsp;<span style="position: relative; left:10px; top: -2px;">'+bag_choosed+'</span></label></li>';
                pass = false;
            }
            if(book.driver_license_images.length == 0) {
                var checked = '';
                var disabled = '';
                option_hrml +='<li><label style="cursor:pointer"><input type="checkbox" '+checked+' '+disabled+' />';
                option_hrml +='&nbsp;<span style="position: relative; left:10px; top: -2px;"> 運転者の免許情報 </span></label></li>';
                pass = false;
            }
            if(pass == false) {
                $('#taskcomplete').html(option_hrml);
                $('#optionModal').modal('show');
            }else {
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
        function taskreturnComplete(lists,count,index, return_id, book){
            $('#returnemptyoption').hide();
            object_index = index;
            booking_id   = return_id;
            var pass = true;
            var current_flag = true ;
            //if(book.pay_status_flag == false) current_flag = false;
            //var return_status = $('input[name="return_'+object_index+'"]').val();
            //if(return_status != '1') current_flag = false;
            //var clean_status = $('input[name="clean_'+object_index+'"]').val();
            //if(clean_status != '1') current_flag = false;
            /*var wash_status = $('input[name="wash_'+object_index+'"]').val();
             if(wash_status != '1') current_flag = false;
             */
            if(book.depart_task == '0') {
                $('#noselectModalText').text("この予約の配車は完了していません。配車を完了してから返車を完了してください。");
                $('#noselectModal').modal('show');
                return;
            }
            var miles = $('input[name="miles_' + object_index + '"]').val();
            var before_miles = $('input[name="before_miles_' + object_index + '"]').val();
            if(parseInt(before_miles) >= parseInt(miles)) current_flag = false;
            if(current_flag == true) {
                $('#returnoptionModal').modal('hide');
            }else {
                $('#noselectModalText').text("貸出前の走行距離より少ない値を入れることはできません。");
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
                    <?php $__currentLoopData = $caroptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        var icon = '';
            selected_price = 0;
            selected_number = 0;
            var compareoption = compareOption('<?php echo e($op->id); ?>',book.options);
            if(compareoption == true) {
                icon = 'fa fa-check-circle';
                price_items.push({name:'option_<?php echo e($op->id); ?>', value: selected_price+"_"+selected_number });
            }
            html +='<tr class="op_'+count+'"><td><i class="'+icon+' op_'+count+'_icon" style="color:#0fa12a" ></i> <?php echo e($op->name); ?></td>';
            html +='<td class="modal_price" ><?php echo e($op->price); ?></td>';
            <?php if($op->google_column_number == "38"): ?>
                html += '<td><input type="number"  name="refresh_op_' + count + '" onkeyup="changeicon(\'op_' + count + '\',\'<?php echo e($op->id); ?>\')"  class="form-control" value="1" readonly style="width:120px"></td>';
            <?php else: ?>
                html += '<td><input type="number"  name="refresh_op_' + count + '" onkeyup="changeicon(\'op_' + count + '\',\'<?php echo e($op->id); ?>\')"  class="form-control" value="' + selected_number + '" style="width:120px"></td>';
            <?php endif; ?>
            html +='<td>';
            html +='<a class="btn btn-xs btn-success" onclick="changeOption(\'option\',\'add\',\'op_'+count+'\',\'<?php echo e($op->id); ?>\',\''+book.id+'\')" >';
            html +='<span>追加</span></a>&nbsp;';
            html +='<a class="btn btn-xs btn-warning" onclick="changeOption(\'option\',\'delete\',\'op_'+count+'\',\'<?php echo e($op->id); ?>\',\''+book.id+'\')">';
            html +='<span>削除</span></a></td></tr>';
            count++;
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
            var url = '<?php echo e(URL::to('/')); ?>/booking/changeoption';
            var token = $('input[name="_token"]').val();
            var data = [];
            var shop_id = '<?php echo e($shop_id); ?>';
            var task_date = '<?php echo e($task_date); ?>';
            var category  = '<?php echo e($category); ?>';
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
                        //window.location.href = "<?php echo e(URL::to('/')); ?>/booking/task?target_object="+target_object+"&shop_id="+shop_id+"&task_date="+task_date+"&category="+category;
                    }
                }
            });
        }
        //booking and rent status update
        function completeStatus(booking_id,task , index){
            var url = '<?php echo e(URL::to('/')); ?>/booking/completestatus';
            var token = $('input[name="_token"]').val();
            var data = [];
            var miles = 0;
            var etc_card = 0;
            var etc_card_pay_method = 0;
            var etc_card_status = false;
            var before_miles = 0;
            var shop_id = '<?php echo e($shop_id); ?>';
            var category = '<?php echo e($category); ?>';
            var task_date = '<?php echo e($task_date); ?>';
            if(task == 'return')
            {
                miles = $('input[name="miles_' + index + '"]').val();
                etc_card = $('input[name="etc_card_' + index + '"]').val();
                etc_card_pay_method = $('#etc_card_pay_method_' + index).val();
                if(etc_card == undefined) etc_card = 0;
                before_miles = $('input[name="before_miles_' + index + '"]').val();
                etc_card_status =  $('input[name="etc_card_status_' + index + '"]').val();
                if(etc_card_pay_method == 0 && etc_card_status == true) {
                   // $('#noselectModal').modal('show'); //display error
                   // return;
                }

            }
            if(miles ==  0 && task == 'return' ) {
                $('#noselectModal').modal('show'); //display error
                return;
            }
            data.push({name: 'booking_id', value: booking_id},
                    {name: '_token', value: token},
                    {name: 'miles', value: miles},
                    {name: 'etc_card', value: etc_card},
                    {name: 'etc_card_pay_method', value: etc_card_pay_method},
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
                    //window.location.href = "<?php echo e(URL::to('/')); ?>/booking/task?shop_id ="+shop_id+"&cflag=1&task_Date="+task_date+"&category="+category;
                }
            });
        }
        //search
        var task_date = '<?php echo e($task_date); ?>';
        var category = '<?php echo e($category); ?>';
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
        //change unpaid total
        function changeEtcCard(etc_card, unpaid_total, index){
            var new_etc = $('input[name="etc_card_' + index + '"]').val();
            if(unpaid_total == '') unpaid_total = 0;
            if(etc_card == '')  etc_card = 0;
            if(new_etc == '')   new_etc = 0;
            var sum = parseInt(unpaid_total) - parseInt(etc_card) + parseInt(new_etc);
            if(sum > 0) $('#unpaid_payment_div_'+index).show();
            else $('#unpaid_payment_div_'+index).hide();
            $('#unpaid_total_'+index).text(sum);
            $('#etc_card_usage_'+index).text(parseInt(new_etc));
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
            var url = '<?php echo e(URL::to('/')); ?>/bookingtask/changereturn';
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
            var url = '<?php echo e(URL::to('/')); ?>/bookingtask/changedepart';
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
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        //update statis pay method and pay status.
        var updateprice_book_id = 0 ;
        var updateprice_pay_method = '';
        var updateprice_child_id = '';
        var updateprice_cond = '';
        var target_name;
        function updatePriceStatus(e, book_id, child_id, cond) {
            var target              = $(e.currentTarget);
            target_name = target;
            var pay_method          = target.val();
            if(pay_method != 0) {
                updateprice_book_id = book_id;
                updateprice_pay_method = pay_method;
                updateprice_child_id = child_id;
                updateprice_cond = cond;

                $('#updatepricestatusModal').modal('show');
            }
        }
        //complete button for departure
        function updatePriceStatusModal() {
            var url = '<?php echo e(URL::to('/')); ?>/bookingtask/updatepricestatus';
            var token = $('input[name="_token"]').val();
            var data = [];
            data.push({name: 'book_id', value: updateprice_book_id},
                    {name: '_token', value: token},
                    {name: 'pay_method', value: updateprice_pay_method},
                    {name: 'child_id', value: updateprice_child_id},
                    {name: 'cond', value: updateprice_cond}
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
                    //location.reload();
                    searchBook('all' ,'shop')
                }
            });
        }
        //move before status when no
        function updatePriceStatusModalBack(){
            target_name.val(0);
        }
        //change insurance1, insurance2 and etc
        function changeRentInsuranceETC(book_id, cond , number, flag) {
            target_object = 'target_'+number;
            var url = '<?php echo e(URL::to('/')); ?>/booking/changeRentInsuranceETC';
            var token = $('input[name="_token"]').val();
            var data = [];
            var shop_id = '<?php echo e($shop_id); ?>';
            var task_date = '<?php echo e($task_date); ?>';
            var category  = '<?php echo e($category); ?>';
            if(flag == '') flag = 0;
            data.push({name: 'book_id', value: book_id},
                    {name: 'cond', value: cond},
                    {name: 'flag', value: flag},
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
                    $('#search input[name="task_Date"]').val(task_date);
                    $('#search input[name="category"]').val(category);
                    $('#search input[name="shop_id"]').val(shop_id);
                    $('#search input[name="target_object"]').val(target_object);
                    $('#search').submit();
                }
            });
        }

        //event prevent
        function eventPrevent(e ,index){
            e.stopPropagation();
            $('#dropdown_'+index).toggle();
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminapp_calendar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>