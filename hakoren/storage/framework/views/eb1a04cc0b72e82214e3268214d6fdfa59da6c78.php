<?php $__env->startSection('template_title'); ?>
    <?php echo app('translator')->getFromJson('search.search'); ?>
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('template_linked_css'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/page_search.css" rel="stylesheet" type="text/css"
          xmlns="http://www.w3.org/1999/html"/>
    <link id="bsdp-css" href="<?php echo e(URL::to('/')); ?>/css/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css"
          rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/js/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/js/slick/slick-theme.css">

    <style>
        .chosen-single {
            height: 2.2em !important;
        }

        .opt_num {
            width: 60px;
            text-align: center;
        }

        .search_head { z-index: 3 !important; }

        #depart-time,
        #return-time {
            -webkit-appearance: menulist;
            -moz-appearance: menulist;
            appearance: menulist;
        }

        .left { display: none; }

        .left.active { display: block; }

        .alert-msg {
            color: brown;
            float: right;
            font-weight: 600;
        }

        .slick-prev:before, .slick-next:before { color: black; }
        .slick-slide { height: auto; }
        @media  screen and (max-width: 425px){
            #passenger-block {
                display: none;
            }
            #passenger-block.content_display {
                display: block;
            }
            #passenger-toggle-btn {
                border: none;
                padding: 10px;
                border-radius: 5px !important;
                width: 100%;
                background-color: #e5e5e5;
            }
            #passenger-toggle-btn:after {
                content: "\f107";
                font: normal normal normal 14px/1 FontAwesome;
                float: right;
                line-height: 18px;
            }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php
//var_dump($search);
?>
<?php $__env->startSection('content'); ?>
    <?php if(!empty($classes)): ?>
        <script>
            <?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false ): ?>
            dataLayer.push({
                'ecommerce': {
                    'currencyCode': 'JPY',
                    'impressions': [
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        {
                            'name': '<?php echo e($cls->class_name); ?>',
                            'id': '<?php echo e($cls->id); ?>',
                            'price': '<?php echo e($cls->price); ?>',
                            
                            'list': 'Search Car',
                            'position': <?php echo e($key + 1); ?>

                        },
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    ]
                }
            });
            <?php endif; ?>
        </script>
    <?php endif; ?>

    <div class="page-container">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head hidden-xs">
            <div class="container clearfix">
                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo e(URL::to('/')); ?>"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="hidden">
                            <a href="#"><?php echo e(trans('fs.parent')); ?></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span><?php echo app('translator')->getFromJson('search.breadcrumb'); ?></span>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE TITLE -->
            </div>
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->

            <!-- BEGIN CONTENT HEADER -->
            <div class="dynamic-page-header dynamic-page-header-default">
                <div class="container clearfix">
                    <div class="col-md-12 bottom-border ">
                        <div class="page-header-title">
                            <h1>&nbsp;<?php echo app('translator')->getFromJson('search.title'); ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">
                        <!--search condition-->
                        <div class="content-main col-xs-12">
                            <div class="box-shadow relative search-panel mobile_bg">
                                <div class="search_head" onclick="searchView()">
                                    <h2><i class="fa fa-calendar"></i><?php echo app('translator')->getFromJson('search.search-conditions'); ?></h2> 
                                    <span id="view_search" class="glyphicon glyphicon-circle-arrow-down"
                                          id="view_search"></span>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form method="POST" action="<?php echo e(URL::to('/search-car')); ?>" accept-charset="UTF-8"
                                              role="form" class="form-horizontal" enctype="multipart/form-data">
                                            
                                            <?php echo csrf_field(); ?>

                                            <div id="searchform" class="search-main">
                                                <div class="row-bordered search_wrap">
                                                    <label class="col-md-2 col-sm-3 col-xs-12 control-label"><span><?php echo app('translator')->getFromJson('search.pickup-date'); ?></span></label>
                                                    <div class="col-md-4 col-sm-9 col-xs-12 search_input">
                                                        <div class="input-group date col-sm-7 col-xs-7 pull-left"
                                                             id="depart-datepicker">
                                                            <input type="text" name="depart_date" id="depart-date"
                                                                   class="form-control input-sm"
                                                                   value="<?php echo e(date('Y/m/d',strtotime($search->depart_date))); ?>"
                                                                   readonly required>
                                                            <span class="input-group-addon input-sm"><span
                                                                        class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                        <div class="col-sm-5 col-xs-5 dp-time">
                                                            <select class="form-control select-md" name="depart_time"
                                                                    id="depart-time" required>
                                                                <?php $__currentLoopData = $hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                    $selected = ($search->depart_time == $hour) ? 'selected' : '';
                                                                    ?>
                                                                    <option value="<?php echo e($hour); ?>" <?php echo e($selected); ?> >
                                                                        <?php echo e($hour); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <label class="col-md-2 col-sm-3 col-xs-12 control-label return-text"><span><?php echo app('translator')->getFromJson('search.dropoff-date'); ?></span></label>
                                                    <div class="col-md-4 col-sm-9 col-xs-12 search_input">
                                                        <div class="input-group date col-sm-7 col-xs-7 pull-left"
                                                             id="return-datepicker">
                                                            <input type="text" name="return_date" id="return-date"
                                                                   class="form-control input-sm"
                                                                   value="<?php echo e(date('Y/m/d', strtotime($search->return_date))); ?>"
                                                                   readonly required>
                                                            <span class="input-group-addon input-sm"><span
                                                                        class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                        <div class="col-sm-5 col-xs-5 dp-time" style="padding-right: 0">
                                                            <select class="form-control input-sm " name="return_time"
                                                                    id="return-time" required>
                                                                <?php $__currentLoopData = $hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                    $selected = ($search->return_time == $hour) ? 'selected' : '';
                                                                    ?>
                                                                    <option value="<?php echo e($hour); ?>" <?php echo e($selected); ?>><?php echo e($hour); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row-bordered search_wrap">
                                                    <label class="col-md-2 col-sm-3 col-xs-12 control-label"><span><?php echo app('translator')->getFromJson('search.location'); ?></span></label>
                                                    <div class="col-md-4 col-sm-9 col-xs-12 search_input">
                                                        <?php
                                                            $name = $util->Tr('name');
                                                        ?>
                                                        <select class="form-control input-sm" name="depart_shop"
                                                                id="depart-shop" required>
                                                            <option value="0"><?php echo app('translator')->getFromJson('search.select-lacation'); ?></option>
                                                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($shop->id); ?>"
                                                                        <?php if($search->depart_shop == $shop->id): ?> selected <?php endif; ?>><?php echo e($shop->$name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                        
                                                    </div>
                                                    <label class="col-sm-2 control-label hide"><?php echo app('translator')->getFromJson('search.return-lacation'); ?></label>
                                                    <div class="col-sm-4 hide">
                                                        <select class="form-control input-sm" name="return_shop"
                                                                id="return-shop">
                                                            <option value="0"><?php echo app('translator')->getFromJson('search.select-lacation'); ?></option>
                                                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($shop->id); ?>"
                                                                        <?php if($shop->id == $search->return_shop): ?> selected <?php endif; ?>><?php echo e($shop->name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row-bordered hidden">
                                                    <label class="col-sm-2 control-label"><?php echo app('translator')->getFromJson('search.car-category'); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $car_category = $search->car_category;
                                                        foreach ($categorys as $cate) {
                                                            if ($cate->name == '乗用車') {
                                                                $car_category = $cate->id;
                                                            }
                                                        } ?>

                                                        <input type="hidden" name="car_category" id="car-category"
                                                               value="<?php echo e($car_category); ?>">
                                                    </div>
                                                </div>
                                                <div class="row-bordered search_wrap">
                                                    <div class="mobile_btn">
                                                        <button id="passenger-toggle-btn" type="button"><?php echo app('translator')->getFromJson('search.seats'); ?></button>
                                                    </div>


                                                    <label class="col-md-2 col-sm-3 hidden-xs control-label"><span><?php echo app('translator')->getFromJson('search.seats'); ?></span></label>
                                                    <div class="col-md-10 col-sm-9 col-xs-12" id="passenger-block">
                                                        <input type="hidden" name="passenger" id="passenger"
                                                               value="<?php echo e($search->passenger); ?>">
                                                        <label class="search_cartype pull-left search_passenger "
                                                               id="search_passenger_all"
                                                               onclick="selectPassenger('all')">
                                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                            <?php echo app('translator')->getFromJson('search.seats-all'); ?></label>
                                                        <?php $__currentLoopData = $psgtags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <label class="search_cartype pull-left search_passenger"
                                                                   onclick="selectPassenger(<?php echo e($tag->min_passenger); ?>)"
                                                                   id="search_passenger_<?php echo e($tag->min_passenger); ?>">
                                                                <i class="fa fa-check-circle"
                                                                   aria-hidden="true"></i> <?php echo e($tag->$name); ?></label>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                                <div class="row-bordered hidden">
                                                    <label class="col-sm-2 control-label">免責補償</label>
                                                    <div class="col-sm-10">
                                                        <?php $search->insurance = 0; ?>
                                                        <input type="hidden" name="insurance" id="insurance"
                                                               value="<?php echo e($search->insurance); ?>">
                                                    </div>
                                                </div>
                                                <div class="row-bordered hidden">
                                                    <label class="col-sm-2 control-label">禁煙/喫煙</label>
                                                    <div class="col-sm-10">
                                                        <input type="hidden" name="smoke" id="smoke"
                                                               value="<?php echo e($search->smoke); ?>">
                                                        <label class="search_cartype pull-left search_smoke"
                                                               id="search_smoke_both" onclick="selectSmoke('both')">
                                                            <i class="fa fa-check-circle" aria-hidden="true"></i>どちらでも良い</label>
                                                        <label class="search_cartype pull-left search_smoke"
                                                               id="search_smoke_1" onclick="selectSmoke('1')">
                                                            <i class="fa fa-check-circle"
                                                               aria-hidden="true"></i>喫煙</label>
                                                        <label class="search_cartype pull-left search_smoke"
                                                               id="search_smoke_0" onclick="selectSmoke('0')">
                                                            <i class="fa fa-check-circle"
                                                               aria-hidden="true"></i>禁煙</label>
                                                    </div>
                                                </div>
                                                <div class="mobile_btn">
                                                    <button class="click_btn" type="button"><?php echo app('translator')->getFromJson('search.extras'); ?></button>
                                                </div>

                                                <div id="option_wrapper"
                                                     class="mobile_toggle <?php if($search->depart_shop == ''): ?> hidden <?php endif; ?>">
                                                    <div class="row-bordered-0 search_wrap">
                                                        <label class="col-md-2 col-sm-3 col-xs-12 control-label"><span><?php echo app('translator')->getFromJson('search.extras'); ?></span></label>
                                                        <input type="hidden" name="option_list" id="option-list"
                                                               value="<?php echo e($search->options); ?>">
                                                        <div class="col-md-10 col-sm-9 col-xs-12 option-list option_name">
                                                            <ul>
                                                                <?php $__currentLoopData = $paid_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                    if (in_array($op->id, explode(',', $search->options))) {
                                                                        $checked = 'checked';
                                                                        $active = 'active';
                                                                    } else {
                                                                        $checked = '';
                                                                        $active = '';
                                                                    }
                                                                    ?>
                                                                    <li class="search_option <?php echo e($active); ?>">
                                                                        <input type="checkbox"
                                                                               id="option_check_<?php echo e($key); ?>"
                                                                               name="options[]"
                                                                               value="<?php echo e($op->id); ?>" <?php echo e($checked); ?>>
                                                                        <label id="option_label_<?php echo e($key); ?>"
                                                                               for="option_check_<?php echo e($key); ?>">
                                                                            <span><?php echo e($op->$name); ?></span>
                                                                        </label>
                                                                    </li>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <!---->
                                                    <div class="row-bordered-0  pickup_object" style="padding-top: 0px; padding-bottom: 0px;">
                                                        <label class="col-md-2 col-sm-3 col-xs-12"></label>
                                                        <div class="col-md-10 col-sm-9 col-xs-12 etc_message">
                                                            <?php echo app('translator')->getFromJson('search.etc_message'); ?>
                                                        </div>
                                                    </div>
                                                    <!---->
                                                    <div class="row-bordered-0 pickup_object search_wrap"
                                                         style=" <?php if($search->depart_shop == '5'): ?> display:none; <?php endif; ?>">
                                                        <label class="col-md-2 col-sm-3 col-xs-12 control-label free_opt"><span><?php echo app('translator')->getFromJson('search.free-pickup-service'); ?></span></label>
                                                        <input type="hidden" name="free_list" id="free_list"
                                                               value="<?php echo e($search->free_options); ?>">
                                                        <div class="col-md-10 col-sm-9 col-xs-12 option-list">
                                                            <ul id="free_options">
                                                                <?php $__currentLoopData = $free_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                    if (in_array($op->id, explode(',', $search->free_options))) {
                                                                        $checked = 'checked';
                                                                        $active = 'active';
                                                                    } else {
                                                                        $checked = '';
                                                                        $active = '';
                                                                    }
                                                                    $ispickup = ''; $free_opt_name = 'free_options[]';
                                                                    if ($op->google_column_number == 101 || $op->google_column_number == 102) {
                                                                        $ispickup = 'pickup';
                                                                        $free_opt_name = 'pickup';
                                                                    }

                                                                    ?>
                                                                    <li class="<?php echo e($active); ?>">
                                                                        <input id="free<?php echo e($key); ?>" class="<?php echo e($ispickup); ?>"
                                                                               type="checkbox"
                                                                               name="<?php echo e($free_opt_name); ?>"
                                                                               value="<?php echo e($op->id); ?>" <?php echo e($checked); ?> >
                                                                        <label for="free<?php echo e($key); ?>"><span><?php echo e($op->$name); ?></span></label>
                                                                    </li>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row-bordered-0 search-btn-block">
                                                    <label>
                                                        <button class="search-btn" type="submit"
                                                                onclick="return submitSearch();">
                                                            <span><?php echo app('translator')->getFromJson('search.searchbtn'); ?></span><br/><span
                                                                    class="large"><?php echo app('translator')->getFromJson('search.searchbtn-2'); ?></span></button>
                                                    </label>
                                                    <label style="margin-left: 20px;cursor: pointer"
                                                           onclick="searchView()">
                                                        [ &times; ] 
                                                    </label>
                                                </div>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end search condition-->
                        <!--search result-->
                        <div class="content-main col-xs-12 margin-bottom-30 search-result form_last_content"
                             id="result_block" style="margin-top:10px;font-size: 16px;">
                            <span class="result-title bottom_content"><?php echo app('translator')->getFromJson('search.search-result'); ?></span>
                            <span class="mobile_bot" id="search_result_info">
                                <?php if($search->depart_shop_name != ''): ?>
                                    <?php if(count($classes) > 0): ?>
                                        <?php if($util->lang() == 'ja'): ?>
                                            <span class="highlight"><?php echo e(date('Y年m月d日', strtotime($search->depart_date))); ?>

                                                ～<?php echo e(date('Y年m月d日', strtotime($search->return_date))); ?></span> <?php echo e($search->depart_shop_name); ?>

                                            で上記条件に合う車両クラスは<span class="highlight"><?php echo e(count($classes)); ?>件</span>です。
                                        <?php endif; ?>
                                        <?php if($util->lang() == 'en'): ?>
                                            <span class="highlight"><?php echo e(date('Y/m/d/', strtotime($search->depart_date))); ?>

                                                ～<?php echo e(date('Y/m/d/', strtotime($search->return_date))); ?></span> <?php echo e($search->depart_shop_name_en); ?>

                                                The vehicles that meets the above conditions is <span class="highlight"><?php echo e(count($classes)); ?> </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span id="NoResults">
                                        <?php echo app('translator')->getFromJson('search.no-class'); ?>
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo app('translator')->getFromJson('search.enter-condition'); ?>
                                <?php endif; ?>
                            </span>

                        </div>
                        <!--class-->

                        <!--loop for search-->
                        <?php
                            $thumb_path = $util->Tr('thumb_path');
                            $shop_name  = $util->Tr('shop_name');
                            $category_name = $util->Tr('category_name');
                        ?>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $cid = $class->id; ?>
                            <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 listing_wrap">
                                <div class="box-shadow relative search-result-panel ">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="car_title m_T10 m_B20" class_id="<?php echo e($cid); ?>"
                                                 style="margin-left: 10px">
                                                <?php echo e($class->class_name); ?>

                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="col-sm-6 col-xs-12">
                                                <input type="hidden" class="car_photo" class_id="<?php echo e($cid); ?>" value="<?php echo e($class->thumb_path); ?>">
                                                <div class="col-xs-12 thumb_slider" style="margin-bottom: 10px">
                                                    <img src="<?php echo e(URL::to('/').$class->$thumb_path); ?>" class="img-responsive center-block" >
                                                    <?php $__currentLoopData = $class->thumbnails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <img src="<?php echo e(URL::to('/').$thumb->thumb_path); ?>" class="img-responsive center-block">
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                                <p class="sml-txt">
                                                    <label class="result_shop">
                                                        <?php if($class->shop_name =="" ): ?>
															<?php echo app('translator')->getFromJson('search.unselected'); ?>
                                                        <?php else: ?>
                                                            <?php echo e($class->$shop_name); ?>

                                                        <?php endif; ?>
                                                    </label>
                                                    <label class="result_shop car_category" class_id="<?php echo e($cid); ?>" style="margin-left: 20px;">
                                                        <?php if($class->category_name =="" ): ?>
															<?php echo app('translator')->getFromJson('search.carcate-unselected'); ?>
                                                        <?php else: ?>
                                                            <?php echo e($class->$category_name); ?>

                                                        <?php endif; ?>
                                                    </label>
                                                </p>
                                            </div>
                                            <div class="col-sm-6 col-xs-12 padding-right-0">
                                                <div class="panel panel-default" style="margin-bottom: 5px;">
                                                    <div class="panel-heading bg-grad-gray">
															<?php echo app('translator')->getFromJson('search.estimatedprice'); ?>
                                                    </div>
                                                    <div class="panel-body" style="padding-bottom: 0px;">
                                                        <div class="form-group row-bordered-result row">
                                                            <label class="col-md-7 col-sm-7 col-xs-6 label_manage"
                                                                   style="padding-left: 0;padding-top: 3px;
    margin-bottom: 10px;">

                                                                <?php
                                                                if($util->lang() == 'ja') {
                                                                    $dt1 = date('Y年n月j日', strtotime($class->depart_date)) . ' ';
                                                                    $dt1 .= date('G時', strtotime($class->depart_time));
                                                                    $min = intval(date('i', strtotime($class->depart_time)));
                                                                    if ($min > 0) $dt1 .= $min . '分';
                                                                    $dt2 = date('Y年n月j日', strtotime($class->return_date)) . ' ';
                                                                    $dt2 .= date('G時', strtotime($class->return_time));
                                                                    $min = intval(date('i', strtotime($class->return_time)));
                                                                    if ($min > 0) $dt2 .= $min . '分';
                                                                }else{
                                                                    $dt1 = date('Y/n/j', strtotime($class->depart_date)) . ' ';
                                                                    $dt1 .= date('G:', strtotime($class->depart_time));
                                                                    $min = date('i', strtotime($class->depart_time));
                                                                    if ($min >= 0) $dt1 .= $min . ' ';
                                                                    $dt2 = date('Y/n/j/', strtotime($class->return_date)) . ' ';
                                                                    $dt2 .= date('G:', strtotime($class->return_time));
                                                                    $min = date('i', strtotime($class->return_time));
                                                                    if ($min >= 0) $dt2 .= $min . ' ';
                                                                }
                                                                ?>
                                                                <div>
                                                                    <label><?php echo app('translator')->getFromJson('search.depart'); ?></label>
                                                                    <label><?php echo e($dt1); ?></label>
                                                                </div>
                                                                <div>
                                                                    <label><?php echo app('translator')->getFromJson('search.return'); ?></label>
                                                                    <label><?php echo e($dt2); ?></label>
                                                                </div>
                                                            </label>
                                                            <label class="col-md-5 col-sm-5 col-xs-6" style="padding-right: 0">
                                                                <div class="bubble-wrap toltip_wrap" style="width: 100%">

                                                                    <?php
                                                                    $leftmany = ($class->car_count >= 10) ? 'active' : '';
                                                                    $leftfew = ($class->car_count <= 9 && $class->car_count >= 4) ? 'active' : '';
                                                                    $leftafew = ($class->car_count <= 3) ? 'active' : '';
                                                                    ?>
                                                                    <div class="bubble left many <?php echo e($leftmany); ?>" class_id="<?php echo e($cid); ?>" style="font-size: 16px">
                                                                        <?php echo app('translator')->getFromJson('search.available'); ?>
                                                                    </div>
                                                                    <div class="bubble left few <?php echo e($leftfew); ?>" class_id="<?php echo e($cid); ?>" style="font-size: 16px">
                                                                        <?php echo app('translator')->getFromJson('search.few'); ?>
                                                                    </div>
                                                                    <div class="bubble left afew <?php echo e($leftafew); ?>" class_id="<?php echo e($cid); ?>" style="font-size: 16px">
                                                                        <?php echo app('translator')->getFromJson('search.stocknumber1'); ?><span class="bubble_write car_count" class_id="<?php echo e($cid); ?>"><?php echo e($class->car_count); ?></span><?php echo app('translator')->getFromJson('search.stocknumber2'); ?>
                                                                    </div>

                                                                </div>
                                                            </label>
                                                        </div>
                                                        <div class="form-group row-bordered-result row">
                                                            <label class="pull-left span_nightday" class_id="<?php echo e($cid); ?>">
                                                                <?php echo app('translator')->getFromJson('search.basic_charge'); ?> (<?php if ($class->night_day == "0泊1日") {
                                                                    if($util->lang() == 'ja') {
                                                                        echo "当日返却";
                                                                        }elseif($util->lang() == 'en') {
                                                                        echo "Return on the day";
                                                                    }
                                                                } else {
                                                                    if($util->lang() == 'ja'){
                                                                        echo $class->night_day;
                                                                        }elseif($util->lang() == 'en') {
                                                                        echo $class->night_day_en;
                                                                    }
                                                                } ?>)
                                                            </label>
                                                            <label class="pull-right basic_price" class_id="<?php echo e($cid); ?>">
                                                               <?php echo app('translator')->getFromJson('search.yen_en'); ?> <?php echo e(number_format($class->price)); ?> <?php echo app('translator')->getFromJson('search.yen'); ?>
                                                            </label>
                                                            <input type="hidden" class="rent_days" class_id="<?php echo e($cid); ?>"
                                                                   value="<?php echo e($class->night_day); ?>">
                                                            <input type="hidden" class="price_rent"
                                                                   class_id="<?php echo e($cid); ?>" value="<?php echo e($class->price); ?>">
                                                        </div>
                                                        <?php
                                                        $option_ids = [];
                                                        $option_names = [];
                                                        $option_costs = [];
                                                        $option_numbers = [];
                                                        $option_prices = [];
                                                        if (!empty($class->options)) {
                                                            foreach ($class->options as $op) {
                                                                $option_ids[] = $op->id;
                                                                if($util->lang() == 'ja')
                                                                    $option_names[] = $op->name;
                                                                if($util->lang() == 'en')
                                                                    $option_names[] = $op->name_en;
                                                                $option_costs[] = $op->price;
                                                                $option_numbers[] = 1;
                                                                $vp = 0;
                                                                if ($op->charge_system == 'one') {
                                                                    $vp = $op->price;
                                                                } else {
                                                                    $vp = $op->price * $search->rentdates;
                                                                }
                                                                $option_prices[] = $vp;
                                                            }
                                                        }
                                                        ?>
                                                        <?php if(!empty($class->options)): ?>
                                                            <div class="option-wrapper" class_id="<?php echo e($cid); ?>">
                                                                <table style="width: 100%">
                                                                    <tbody style="font-size: 13px">
                                                                    <?php $__currentLoopData = $class->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <tr class=" row-bordered-result">
                                                                            <td style="text-align: left"><?php echo e($op->$name); ?>

                                                                                (<?php echo app('translator')->getFromJson('search.extras'); ?>)
                                                                            </td>
                                                                            <td style="text-align: center">
                                                                                <input type="hidden" name="opt_charge"
                                                                                       class="opt_charge"
                                                                                       class_id="<?php echo e($cid); ?>"
                                                                                       oid="<?php echo e($op->id); ?>"
                                                                                       value="<?php echo e($op->charge_system); ?>">
                                                                                <?php if($op->max_number == 1): ?>

                                                                                    <select name="opt_num"
                                                                                            class="opt_num selectpicker"
                                                                                            class_id="<?php echo e($cid); ?>"
                                                                                            oid="<?php echo e($op->id); ?>"
                                                                                            value="<?php echo e($op->number); ?>"
                                                                                            min="0">
                                                                                        <option value="1"
                                                                                                <?php if($op->number == '1'): ?> selected <?php endif; ?>>
                                                                                            <?php echo app('translator')->getFromJson('search.need'); ?>
                                                                                        </option>
                                                                                        <option value="0"
                                                                                                <?php if($op->number == '0'): ?> selected <?php endif; ?>>
                                                                                            <?php echo app('translator')->getFromJson('search.noneed'); ?>
                                                                                        </option>
                                                                                    </select>
                                                                                <?php else: ?>

                                                                                    <select name="opt_num"
                                                                                            class="opt_num selectpicker"
                                                                                            class_id="<?php echo e($cid); ?>"
                                                                                            oid="<?php echo e($op->id); ?>"
                                                                                            value="<?php echo e($op->number); ?>"
                                                                                            min="0">
                                                                                        <?php for($k = 0; $k < $op->max_number; $k++): ?>
                                                                                            <option value="<?php echo e($k); ?>"
                                                                                                    <?php if($op->number == $k): ?> selected <?php endif; ?>><?php echo e($k); ?>

                                                                                                <?php echo app('translator')->getFromJson('search.individual'); ?>
                                                                                            </option>
                                                                                        <?php endfor; ?>
                                                                                    </select>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td style="text-align: right">
                                                                                <?php
                                                                                if ($op->charge_system == 'one') {
                                                                                    $oprice = $op->price * $op->number;
                                                                                } else {
                                                                                    $oprice = $op->price * $op->number * $search->rentdates;
                                                                                }
                                                                                ?>
                                                                                <?php echo app('translator')->getFromJson('search.yen_en'); ?> <span class="opt_cost" oid="<?php echo e($op->id); ?>"
                                                                                      class_id="<?php echo e($cid); ?>"><?php echo e(number_format($oprice)); ?></span> <?php echo app('translator')->getFromJson('search.yen'); ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <input type="hidden" class="option_ids"
                                                                   class_id="<?php echo e($cid); ?>"
                                                                   value="<?php echo e(implode(',', $option_ids)); ?>">
                                                            <input type="hidden" class="option_names"
                                                                   class_id="<?php echo e($cid); ?>"
                                                                   value="<?php echo e(implode(',', $option_names)); ?>">
                                                            <input type="hidden" class="option_numbers"
                                                                   class_id="<?php echo e($cid); ?>"
                                                                   value="<?php echo e(implode(',', $option_numbers)); ?>">
                                                            <input type="hidden" class="option_costs"
                                                                   class_id="<?php echo e($cid); ?>"
                                                                   value="<?php echo e(implode(',', $option_costs)); ?>">
                                                            <input type="hidden" class="option_prices"
                                                                   class_id="<?php echo e($cid); ?>"
                                                                   value="<?php echo e(implode(',', $option_prices)); ?>">
                                                        <?php endif; ?>

                                                        <?php if(!empty($class->insurance)): ?>
                                                            <div class="form-group row-bordered-result row hidden">
                                                                <div class="col-xs-6" style="padding: 0">
                                                                    免責保障
                                                                    <select name="insurance"
                                                                            class="insurance pull-right"
                                                                            class_id="<?php echo e($cid); ?>">
                                                                        <option value="0">不要</option>
                                                                        <option value="<?php echo e($class->insurance[1]); ?> selected">
                                                                            免責補償
                                                                        </option>
                                                                        <option value="<?php echo e($class->insurance[1]+$class->insurance[2]); ?>">
                                                                            ワイド免責補償
                                                                        </option>
                                                                    </select>
                                                                    <input type="hidden" class="insurance_price1"
                                                                           value="<?php echo e($class->insurance_price1); ?>"
                                                                           class_id="<?php echo e($cid); ?>">
                                                                    <input type="hidden" class="insurance_price2"
                                                                           value="<?php echo e($class->insurance_price2); ?>"
                                                                           class_id="<?php echo e($cid); ?>">
                                                                </div>
                                                                <div class="col-xs-6" style="padding-right: 0;">
                                                                    <label class="pull-right">
                                                                        <span class="insurance-price"
                                                                              class_id="<?php echo e($cid); ?>">0</span>円
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php 
                                                            $max_passengers = $class->max_passengers;
                                                            $noset_maxpassenger = count($max_passengers) == 0;
                                                            $count_notempty = 0; $mps = [];
                                                            foreach($max_passengers as $key=>$mp){
                                                                if($mp->count > 0) {
                                                                    $count_notempty++;
                                                                    $mps[] = $mp;
                                                                }
                                                            }
                                                            $noset_maxpassenger = $count_notempty == 0;
                                                         ?>

                                                        <div class="form-group row-bordered-result row">
                                                            <div class="col-xs-12" style="padding: 0">
                                                                <?php echo app('translator')->getFromJson('search.nonsmoke'); ?>/<?php echo app('translator')->getFromJson('search.smoke'); ?>
                                                                <select name="car_smoking"
                                                                        class="car_smoking pull-right <?php if($util->lang() == 'ja'): ?> jpwd34 <?php endif; ?>"
                                                                        class_id="<?php echo e($cid); ?>"
                                                                        <?php if($noset_maxpassenger): ?> disabled <?php endif; ?>>
                                                                    <option value="0"
                                                                            <?php if($class->smoke == '0'): ?> selected <?php endif; ?>> <?php echo app('translator')->getFromJson('search.nonsmoke'); ?>
                                                                    </option>
                                                                    <option value="1"
                                                                            <?php if($class->smoke == '1'): ?> selected <?php endif; ?>> <?php echo app('translator')->getFromJson('search.smoke'); ?>
                                                                    </option>
                                                                    <option value="both"
                                                                            <?php if($class->smoke == 'both'): ?> selected <?php endif; ?>>
                                                                        <?php echo app('translator')->getFromJson('search.bothsmoke'); ?>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row-bordered-result row">
                                                            <?php if(count($max_passengers) == 1): ?>
                                                                <div class="col-xs-12" style="padding: 0">
                                                                    <b><?php echo e($max_passengers[0]->max_passenger); ?></b> <?php echo app('translator')->getFromJson('search.passenger'); ?>
                                                                    <input type="hidden" name="car_passenger"
                                                                           class="car_passenger <?php if($util->lang() == 'ja'): ?> jpwd34 <?php endif; ?>"
                                                                           value="<?php echo e($max_passengers[0]->max_passenger); ?>"
                                                                           class_id="<?php echo e($cid); ?>">
                                                                </div>
                                                            <?php elseif(count($max_passengers) > 1): ?>
                                                                <div class="col-xs-12" style="padding: 0">
                                                                    <?php if($count_notempty > 1): ?>
                                                                        <?php echo app('translator')->getFromJson('search.capacity'); ?>
                                                                        <select name="car_passenger"
                                                                                class="car_passenger pull-right  <?php if($util->lang() == 'ja'): ?> jpwd34 <?php endif; ?>"
                                                                                class_id="<?php echo e($cid); ?>">
                                                                            <?php $__currentLoopData = $mps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($pt->max_passenger); ?>"><?php echo e($pt->max_passenger); ?>

                                                                                    <?php echo app('translator')->getFromJson('search.riding'); ?>
                                                                                </option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                    <?php elseif($count_notempty == 1): ?>
                                                                        <span>
                                                                        <?php echo app('translator')->getFromJson('search.stock'); ?> <b><?php echo e($mps[0]->max_passenger); ?></b> <?php echo app('translator')->getFromJson('search.called'); ?></span>
                                                                        <input type="hidden" name="car_passenger"
                                                                               class="car_passenger  <?php if($util->lang() == 'ja'): ?> jpwd34 <?php endif; ?>"
                                                                               value="<?php echo e($mps[0]->max_passenger); ?>"
                                                                               class_id="<?php echo e($cid); ?>">
                                                                    <?php else: ?>
                                                                        <span class="alert-msg"> <?php echo app('translator')->getFromJson('search.nostock'); ?></span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="col-xs-12" style="padding: 0"> <?php echo app('translator')->getFromJson('search.capacity'); ?>
                                                                    <span class="alert-msg"> <?php echo app('translator')->getFromJson('search.nocapacity'); ?></span>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php if(count($free_options)>0): ?>
                                                            <div class="form-group row-bordered-result row">
                                                                <div class="col-xs-12" style="padding: 0">
                                                                    <?php echo app('translator')->getFromJson('search.pickup'); ?>
                                                                    <select name="car_pickup" class="car_pickup pull-right <?php if($util->lang() == 'ja'): ?> jpwd34 <?php endif; ?>" class_id="<?php echo e($cid); ?>">
                                                                        <?php $__currentLoopData = $free_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($fr->id); ?>"
                                                                                    <?php if($fr->id == $search->pickup): ?> selected <?php endif; ?>><?php echo e($fr->$name); ?></option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="" <?php if($search->pickup == ''): ?> selected <?php endif; ?>>
                                                                            <?php echo app('translator')->getFromJson('search.noneed_airport'); ?>
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="form-group">
                                                            <label class="col-sm-5 padding-0">
                                                                <div> <?php echo app('translator')->getFromJson('search.tax'); ?></div>
                                                            </label>
                                                            <label class="col-sm-7 padding-0" style="color: #b63432">
                                                                <div style="padding-top: 15px;">
                                                                    <?php echo app('translator')->getFromJson('search.yen_en'); ?>
																	<label style="font-weight:bold;font-size: 55px; color:#e60707;/*margin-top: -20px; margin-bottom: -20px;*/" class="total_price" class_id="<?php echo e($cid); ?>">
                                                                        <?php echo e(number_format($class->all_price)); ?>

                                                                    </label><label style="font-weight: 300"> <?php echo app('translator')->getFromJson('search.yen'); ?> </label>
                                                                </div>
                                                                <input type="hidden" class="price_all" class_id="<?php echo e($cid); ?>" value="<?php echo e($class->all_price); ?>">
                                                                <div style="margin-top:6px;"> <?php echo app('translator')->getFromJson('search.lowestprice'); ?> </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center xsmb8">
                                                    <label>
                                                        <button class="btn bg-grad-red btn_book<?php echo e($cid); ?>" style=" margin-top:10px;padding: 10px 50px 10px 50px"
                                                                onclick="submit_booking(<?php echo e($cid); ?>, '<?php echo e($class->class_name); ?>')"
                                                                <?php if($noset_maxpassenger): ?>disabled <?php endif; ?>>
                                                            <?php echo app('translator')->getFromJson('search.reservation'); ?>
                                                        </button>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <!--end loop-->

                        
                        <?php $__currentLoopData = $static_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 listing_wrap">
                                <div class="box-shadow relative search-result-panel ">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="car_title m_T10 m_B20" class_id="<?php echo e($class->id); ?>"
                                                 style="margin-left: 10px">
                                                <?php if($class->name == 'SSP2'): ?>
                                                    <?php echo app('translator')->getFromJson('search.lexus460'); ?>
                                                <?php elseif($class->name == 'SSP3'): ?>
                                                    <?php echo app('translator')->getFromJson('search.lexus4601'); ?>
                                                <?php else: ?>
                                                    <?php echo e($class->name); ?>

                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="col-sm-6 col-xs-12">
                                                <?php if ($class->name == 'SSP3') $class->thumb = '/img/ssp3.jpg'; ?>
                                                <img src="<?php echo e(URL::to('/').$class->thumb); ?>"
                                                     class="img-responsive center-block m_Txs60">
                                            </div>
                                            <div class="col-sm-6 col-xs-12 padding-right-0">
                                                <div class="panel panel-default" style="margin-bottom: 5px;">
                                                    <div class="panel-body" style="padding-bottom: 0px;">
                                                        <div class="form-group row-bordered-result row">
                                                            <h3><?php echo app('translator')->getFromJson('search.number_passenger'); ?>：
                                                                <?php if($class->minPsg == 0): ?>
                                                                <?php elseif($class->minPsg == $class->maxPsg): ?>
                                                                    <?php echo e($class->minPsg); ?>

                                                                <?php else: ?>
                                                                    <?php echo e($class->minPsg); ?>~<?php echo e($class->maxPsg); ?>

                                                                <?php endif; ?>
                                                                <?php echo app('translator')->getFromJson('search.name'); ?>
                                                            </h3>
                                                        </div>
                                                        <div class="form-group row-bordered-result row">
                                                            <label class="col-sm-4 col-xs-5 label_manage"
                                                                   style="padding-left: 0;padding-top: 3px; margin-bottom: 10px;">
                                                                <?php echo app('translator')->getFromJson('search.model'); ?>
                                                            </label>
                                                            <label class="col-sm-8 col-xs-7" style="padding-right: 0">
                                                                <?php if($class->name == 'SSP2'): ?>
                                                                    <?php echo app('translator')->getFromJson('search.lexus460'); ?>
                                                                <?php elseif($class->name == 'SSP3'): ?>
                                                                    <?php echo app('translator')->getFromJson('search.lexus4601'); ?>
                                                                <?php else: ?>
                                                                    <?php echo e($class->name); ?>

                                                                <?php endif; ?>
                                                            </label>
                                                        </div>
                                                        <div class="form-group row-bordered-result row">
                                                            <label class="col-sm-4 col-xs-5 label_manage"
                                                                   style="padding-left: 0;padding-top: 3px; margin-bottom: 10px;">
                                                                <?php echo app('translator')->getFromJson('search.option'); ?>
                                                            </label>
                                                            <?php
                                                                $option_names = $util->Tr('option_names');
                                                            ?>
                                                            <label class="col-sm-8 col-xs-7" style="padding-right: 0">
                                                                <?php echo e($class->$option_names); ?>

                                                            </label>
                                                        </div>
                                                        <div class="form-group row-bordered-result row"
                                                             style="border-bottom:0;">
                                                            <label class="col-sm-4 col-xs-5 label_manage"
                                                                   style="padding-left: 0;padding-top: 3px; margin-bottom: 10px;">
                                                                <?php echo app('translator')->getFromJson('search.price'); ?>
                                                            </label>
                                                            <label class="col-sm-8 col-xs-7" style="padding-right: 0;">

                                                                <?php if($class->name == 'MB'): ?>
																	<?php if($util->lang() == 'ja'): ?>
																		<?php echo app('translator')->getFromJson('search.mbprice'); ?> <br/>
																	<?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php echo app('translator')->getFromJson('search.lexusdesc1'); ?>
                                                                <?php endif; ?>
                                                                <br/>
																		<?php echo app('translator')->getFromJson('search.rental'); ?>

                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center xsmb8" style="padding-top: 15px;">
                                                    <label>
                                                        <a class="btn bg-grad-red"
                                                           href="<?php echo e(URL::to('/contact')); ?>"> <?php echo app('translator')->getFromJson('search.inquiries'); ?> </a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
        
        <form action="<?php echo e(URL::to('/')); ?>/search-confirm" method="POST" name="booking-submit" id="booking-submit">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="data_depart_date" id="data_depart_date">
            <input type="hidden" name="data_depart_time" id="data_depart_time">
            <input type="hidden" name="data_return_date" id="data_return_date">
            <input type="hidden" name="data_return_time" id="data_return_time">
            <input type="hidden" name="data_depart_shop" id="data_depart_shop">
            <input type="hidden" name="data_depart_shop_name" id="data_depart_shop_name">
            <input type="hidden" name="data_return_shop" id="data_return_shop">
            <input type="hidden" name="data_return_shop_name" id="data_return_shop_name">
            <input type="hidden" name="data_car_category" id="data_car_category">
            <input type="hidden" name="data_passenger" id="data_passenger">
            <input type="hidden" name="data_insurance" id="data_insurance">
            <input type="hidden" name="data_insurance_price1" id="data_insurance_price1">
            <input type="hidden" name="data_insurance_price2" id="data_insurance_price2">
            <input type="hidden" name="data_smoke" id="data_smoke">
            <input type="hidden" name="data_option_list" id="data_option_list">
            <input type="hidden" name="data_class_id" id="data_class_id">
            <input type="hidden" name="data_class_name" id="data_class_name">
            <input type="hidden" name="data_class_category" id="data_class_category">
            <input type="hidden" name="data_car_photo" id="data_car_photo">
            <input type="hidden" name="data_rent_days" id="data_rent_days">
            <input type="hidden" name="data_rent_dates" id="data_rent_dates" value="<?php echo e($search->rentdates); ?>">
            <input type="hidden" name="data_price_rent" id="data_price_rent">
            <input type="hidden" name="data_option_ids" id="data_option_ids">
            <input type="hidden" name="data_option_names" id="data_option_names">
            <input type="hidden" name="data_option_numbers" id="data_option_numbers">
            <input type="hidden" name="data_option_costs" id="data_option_costs">
            <input type="hidden" name="data_option_prices" id="data_option_prices">
            <input type="hidden" name="data_price_all" id="data_price_all">
            <input type="hidden" name="data_member" id="data_member">
            <input type="hidden" name="data_pickup" id="data_pickup">

        </form>
        
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('modals.modal-error', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->startSection('style'); ?>
    <style>
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js"
            charset="UTF-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/slick/slick.js" type="text/javascript" charset="utf-8"></script>
		
    <script type="text/javascript">

        $(function () {
            $(".click_btn").click(function () {
				var lang = "<?php echo e($util->lang()); ?>";
				if(lang == "ja"){
					$(this).text(function (i, text) {
						return text === "オプションを追加する" ? "閉じる" : "オプションを追加する";
					})
				}else if(lang == "en"){
					$(this).text(function (i, text) {
						return text === "Extras " ? "Close" : "Extras ";
					})
				}
                $(".mobile_toggle").slideToggle(1000);
                $(".mobile_toggle").toggleClass("content_display");
            });

            $("#passenger-toggle-btn").click(function () {
				var lang = "<?php echo e($util->lang()); ?>";
				if(lang == "ja"){
					$(this).text(function (i, text) {
						return text === "乗車人数を選択" ? "閉じる" : "乗車人数を選択";
					})
				}else if(lang == "en"){
					$(this).text(function (i, text) {
						return text === "Seats" ? "Close" : "Seats";
					})
				}
                $("#passenger-block").slideToggle(1000).toggleClass("content_display");
            });
        })

        $(document).ready(function () {
            <?php if($request_page != 'toppage'): ?>
                <?php if(!empty($classes)): ?>
                $('html, body').animate({
                    scrollTop: $("#result_block").offset().top
                }, 1000);
                <?php endif; ?>
            <?php endif; ?>
            $('.thumb_slider').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                // autoplay: true,
                // autoplaySpeed: 3333000,
            });

        });
        var modelError = $('#modalError');
        var rent_dates = '<?php echo e($search->rentdates); ?>' * 1;
        var today = new Date();
        today = new Date(today.getFullYear(), today.getMonth(), today.getDate(),0,0,0,0);
        var tomorrow = new Date();
        tomorrow.setDate(today.getDate() + 1);

        function showModal(msg) {
            $('.error-text').text(msg);
            modelError.modal('show');
        }

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

        $('.opt_num').change(function () {
            var num = $(this).val();
            var oid = $(this).attr('oid');
            var cid = $(this).attr('class_id');
            var $cost = $('.opt_cost[oid="' + oid + '"][class_id="' + cid + '"]'),
                $charge = $('.opt_charge[oid="' + oid + '"][class_id="' + cid + '"]').val(),
                $option_costs = $('.option_costs[class_id="' + cid + '"]'),
                $option_prices = $('.option_prices[class_id="' + cid + '"]'),
                $option_numbers = $('.option_numbers[class_id="' + cid + '"]');
            var option_ids = $('.option_ids[class_id="' + cid + '"]').val();
            if (option_ids === '') return;
            option_ids = option_ids.split(',');
            var $ocosts = $option_costs.val();
            if ($ocosts === '') return;
            $ocosts = $ocosts.split(',');
            var option_numbers = $option_numbers.val();
            if (option_numbers === '') return;
            option_numbers = option_numbers.split(',');
            var option_prices = $option_prices.val();
            if (option_prices === '') return;
            option_prices = option_prices.split(',');

            var ind = option_ids.indexOf(oid);

            if (ind === -1) return;

            var oprice = 0;
            if ($charge == 'one') {
                oprice = $ocosts[ind] * num;
            } else {
                oprice = $ocosts[ind] * num * rent_dates;
            }
            $cost.text(oprice);
            option_numbers[ind] = num;
            option_prices[ind] = oprice;
            $option_numbers.val(option_numbers.join(','));
            $option_prices.val(option_prices.join(','));

            // change total price
            var price_insurance = $('.insurance[class_id="' + cid + '"]').val() * 1 * rent_dates;
            $('.insurance-price[class_id="' + cid + '"]').text(price_insurance.toLocaleString('en'));

            var price_rent = $('.price_rent[class_id="' + cid + '"]').val();
            var option_costs = 0;
            for (var i = 0; i < $ocosts.length; i++) {
                option_costs += option_prices[i] * 1;
                // option_costs += $ocosts[i] * option_numbers[i];
            }
            var total = price_rent * 1 + option_costs + price_insurance;

            $('.price_all[class_id="' + cid + '"]').val(total);
            $('.total_price[class_id="' + cid + '"]').text(total.toLocaleString('en'));
        });

        function class_search(class_id) {
            var smoke = $('.car_smoking[class_id="' + class_id + '"]').val();
            // var passenger = $('#passenger').val();
            // if(passenger == 'all') {
            var passenger = $('.car_passenger[class_id="' + class_id + '"]').val();
            // }
            var data = {
                _token: $('input[name="_token"]').val(),
                class_id: class_id,
                smoke: smoke,
                depart_date: $('#depart-date').val(),
                depart_time: $('#depart-time').val(),
                return_date: $('#return-date').val(),
                return_time: $('#return-time').val(),
                depart_shop: $('#depart-shop').val(),
                return_shop: $('#return-shop').val(),
                category: $('#car-category').val(),
                passenger: passenger,
                // insurance   : $('.insurance[class_id="' + cid + '"]').val(),
                // option_list : $('#option-list').val()
            };
            $.ajax({
                url: '<?php echo e(URL::to('/')); ?>/class-search',
                type: 'post',
                data: data,
                success: function (result, status, xhr) {
                    if (status != 'success') return;
                    if (isJson(result)) {
                        var data = JSON.parse(result);
                        if (data.error != '' || data.success != 'true') return;
                        var cls = data.class;
                        console.log(cls);
                        var cid = cls.id;
                        // use new values
                        $('.car_count[class_id="' + cid + '"]').text(cls.car_count);
                        $('.left[class_id=' + cid + ']').removeClass('active');

                        var leftclass = '';
                        if (cls.car_count >= 10) leftclass = 'many';
                        if (cls.car_count <= 9 && cls.car_count >= 4) leftclass = 'few';
                        if (cls.car_count <= 3) leftclass = 'afew';
                        $('.left.' + leftclass + '[class_id=' + cid + ']').addClass('active');

                        if (cls.car_count === 0) {
                            $('.btn_book' + cid).removeAttr('disabled').attr('disabled', 'disabled');

                        } else {
                            $('.btn_book' + cid).removeAttr('disabled');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    alert(error);
                }
            });

        }

        $('.car_smoking').change(function () {
            var cid = $(this).attr('class_id');
            class_search(cid);
        });

        $('.car_passenger').change(function () {
            var cid = $(this).attr('class_id');
            class_search(cid);
        });

        //chosen select
        $(".chosen-select").chosen({
            max_selected_options: 5,
            no_results_text: "Oops, nothing found!"
        });

        $('#depart-datepicker, #return-datepicker').datepicker({
            
            language: "ja",
            
            format: 'yyyy/mm/d',
            startDate: '<?php echo e(date('Y/n/j')); ?>',
            endDate: '<?php echo e(date('Y/m/d',strtotime(date("Y-m-d", time()) . " + 1 year"))); ?>',
            orientation: "bottom",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });
        var departPicker = $('#depart-datepicker');
        var returnPicker = $('#return-datepicker');

        // time selector initialize
        var dTimepicker = $('#depart-time'),
            rTimepicker = $('#return-time');

        function getAfterHours(hr) {
            // get time string after <hr> hours
            var crt = new Date(),
                crthour = crt.getHours(),
                crtmin = crt.getMinutes(),
                hrAfter = crthour * 1 + hr;
            if(hrAfter < 10) hrAfter = '0' + hrAfter;
            return hrAfter + ':' + crtmin;
        }

        function selectFirstOrLastTime(picker, cond) {
            // cond is 'first' or 'last'
            picker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
            picker.val(picker.find('option:'+ cond).val());
            picker.trigger('chosen:updated');
        }

        // initialize time pickers
        var all_hours_disable = updateTimepicker(dTimepicker, departPicker.datepicker('getDate'), today, getAfterHours(3));
        if (all_hours_disable) {
            departPicker.datepicker('setStartDate', tomorrow).datepicker('setDate', tomorrow);
            selectFirstOrLastTime(dTimepicker, 'first');
        }
        all_hours_disable = updateTimepicker(rTimepicker, returnPicker.datepicker('getDate'), today, getAfterHours(4));
        if(all_hours_disable) {
            returnPicker.datepicker('setStartDate', tomorrow).datepicker('setDate', tomorrow);
            selectFirstOrLastTime(rTimepicker, 'last');
        } else {
            if(compareDateWithToday(returnPicker.datepicker('getDate')) > 0)
                if(rTimepicker.val() == '09:00')
                    selectFirstOrLastTime(rTimepicker, 'last');
        }

        function compareDateWithToday(date) {
            var q = new Date(),
                today = new Date(q.getFullYear(),q.getMonth(),q.getDate(),0,0,0,0);
            if(date.getTime() > today.getTime())
                return 1;
            else if(date.getTime() == today.getTime())
                return 0;
            else
                return -1;
        }

        function updateTimepicker(picker, date, refdate, reftime) {
            var dYear = date.getFullYear(), dMonth = date.getMonth(), dDate = date.getDate();
            var refhm = reftime.split(':');

            var cTime = (new Date(refdate.getFullYear(), refdate.getMonth(), refdate.getDate(), refhm[0], refhm[1],0,0)).getTime();// + 10800 * 1000;
            // if(isdepart === true) cTime += 10800 * 1000;
            var hours = picker.find('option'), cnt = hours.length;
            var first = -1;
            var oldval = picker.val();

            for (var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]);
                var hr_min = hOption.val().split(':');
                var dTime = (new Date(dYear, dMonth, dDate, hr_min[0], hr_min[1], 0, 0)).getTime();
                if (dTime < cTime) {
                    hOption.prop('disabled', true);
                    hOption.css('display', 'none');
                }
                else {
                    hOption.prop('disabled', false);
                    hOption.css('display', 'block');
                    if (first < 0) first = k;
                }
            }
            if (picker.val() < oldval) picker.val(oldval);
            // if (compareDateWithToday(date) === 0 && first >= 0) picker.val($(hours[first]).val());
            picker.trigger("chosen:updated");
            return first < 0;
        }

        departPicker.datepicker().on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
            if(compareDateWithToday(minDate) == 0){ // if today
                var disable1 = updateTimepicker(dTimepicker, minDate, today, getAfterHours(3));
                var disable2 = updateTimepicker(rTimepicker, minDate, today, getAfterHours(4));
                if(disable1 == true ) {
                    dTimepicker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');
                    if(disable2 == true)
                        rTimepicker.find('option').removeAttr('disabled').removeAttr('selected').css('display', 'block');

                    minDate.setDate(minDate.getDate() + 1);
                    departPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(dTimepicker, 'first');
                    returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(rTimepicker, 'last');
                }
                if(disable1 == false && disable2 == true) {
                    minDate.setDate(minDate.getDate() + 1);
                    returnPicker.datepicker('setStartDate', minDate).datepicker('setDate', minDate);
                    selectFirstOrLastTime(rTimepicker,'last');
                }
            } else if(compareDateWithToday(minDate) > 0) {
                selectFirstOrLastTime(dTimepicker,'first');
                selectFirstOrLastTime(rTimepicker,'last');
            }
        });

        returnPicker.datepicker().on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            var departDate = departPicker.datepicker('getDate');
            var departTime = dTimepicker.val().split(':');
            departTime[0] = departTime[0] * 1 + 1;
            if(departTime[0] < 10) departTime[0] = '0' + departTime[0];
            var all_hours_disable = updateTimepicker(rTimepicker, maxDate, departDate, departTime[0]+':'+departTime[1]);
            if (all_hours_disable) {
                maxDate.setDate(maxDate.getDate() + 1);
                returnPicker.datepicker('setStartDate', maxDate).datepicker('setDate', maxDate);
                selectFirstOrLastTime(rTimepicker,'last');
            }
        });

        dTimepicker.change(function () {
            var dDate = departPicker.datepicker('getDate'),
                dy = dDate.getFullYear(), dm = dDate.getMonth(), dd = dDate.getDate();
            var rDate = returnPicker.datepicker('getDate'),
                ry = rDate.getFullYear(), rm = rDate.getMonth(), rd = rDate.getDate();
            var hr_min = $(this).val().split(':');
            var dTime = (new Date(dy, dm, dd, hr_min[0], hr_min[1], 0, 0)).getTime() + 3600 * 1000;

            var hours = rTimepicker.find('option').removeAttr('disabled').css('display','block'),
                cnt = hours.length, oldval = rTimepicker.val();
            var index = -1;
            for (var k = 0; k < cnt; k++) {
                var hOption = $(hours[k]), hm = hOption.val().split(':');
                var rTime = (new Date(ry, rm, rd, hm[0], hm[1], 0, 0)).getTime();
                if (rTime <= dTime)
                    hOption.attr('disabled', 'disabled').css('display', 'none');
                else {
                    // hOption.removeAttr('disabled').css('display','block');
                    if (index < 0 && hOption.val() == oldval) {
                        hOption.attr('selected', true);
                        index = k;
                    }
                }
            }
            if (index < 0) {
                dDate.setDate(dDate.getDate() + 1);
                returnPicker.datepicker('setStartDate', dDate).datepicker('setDate', dDate);
                selectFirstOrLastTime(rTimepicker,'last');
            }
            rTimepicker.trigger("chosen:updated");
        });

        //inital value for depart shop
        var shop_name = $('select[name="depart_shop"] option:selected').text();
        $('.shop_name').html(shop_name);
        $('select[name="depart_shop"]').change(function () {
            var shop_name = $('select[name="depart_shop"] option:selected').text();
            $('.shop_name').html(shop_name);
            $('#return-shop').val($('#depart-shop').val());
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

        function selectCategory(cate_id, cate_name) {
            $('.search_cate').removeClass('search_btn').addClass('search_cartype');
            $('#search_cate_' + cate_id).removeClass('search_cartype').addClass('search_btn');
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

        function selectPassenger(val) {
            $('.search_passenger').removeClass('search_btn').addClass('search_cartype');
            $('#search_passenger_' + val).removeClass('search_cartype').addClass('search_btn');
            $('input[name="passenger"]').val(val);
        }

        //initail insurance when loading
        
        
        
        
        
        
        
        
        
        
        //initail smoke when loading
        var smoke = $('input[name="smoke"]').val();
        <?php if($search->smoke == ""): ?>
            smoke = 'both';
        <?php endif; ?>
        selectSmoke(smoke);

        function selectSmoke(val) {
            $('.search_smoke').removeClass('search_btn').addClass('search_cartype');
            $('#search_smoke_' + val).removeClass('search_cartype').addClass('search_btn');
            $('input[name="smoke"]').val(val);
        }

        //initial options
        // getOptions();
        var option_list = $('input[name="option_list"]').val();
        var option_content = option_list.split(',');

        $(':checkbox').each(function (i) {
            var val = $(this).val();
            if (jQuery.inArray(val, option_content) != -1) {
                $(this).attr('checked', 'true');
            }
            //val[i] = $(this).val();
        });

        //event to get options list when click category
        function getOptions() {
            var category_id = $('input[name="car_category"]').val();
            var url = '<?php echo e(URL::to('/')); ?>/search/getshopoption';
            var token = $('input[name="_token"]').val();
            var shop_id = $('#depart-shop').val();
            var data = [];
            data.push({name: '_token', value: token},
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
                    var option_html = '<ul>';
                    var search_options = $('#option-list').val().split(',');
                    for (var k = 0; k < search_options.length; k++) {
                        search_options[k] = search_options[k] * 1;
                    }
                    var free_options = $('#free_list').val();
                    if (free_options != '') {
                        free_options = free_options.split(',')
                    }
                    for (k = 0; k < free_options.length; k++) {
                        free_options[k] = free_options[k] * 1;
                    }
                    content = jQuery.parseJSON(content);
                    var paids = content.paid_options;
                    var frees = content.free_options;
                    for (k = 0; k < paids.length; k++) {
                        var v = paids[k];
                        var checked = (search_options.indexOf(v.id) > -1) ? 'checked' : '';
                        var active = (checked == 'checked') ? 'active' : '';
                        option_html += '<li class="search_option ' + active + ' ">';
                        var clickevent = "";

                        option_html += ' ';
                        option_html += '<input type="checkbox" id="option_check_' + k + '" name="options[]" value="' + v.id + '" ' + checked + ' >';
                        <?php if($util->lang() == 'ja'): ?>
                            option_html += '<label id="option_label_' + k + '" for="option_check_' + k + '"><span>' + v.name + '</span></label></li>';
                        <?php endif; ?>
                        <?php if($util->lang() == 'en'): ?>
                            option_html += '<label id="option_label_' + k + '" for="option_check_' + k + '"><span>' + v.name_en + '</span></label></li>';
                        <?php endif; ?>
                    }
                    option_html += '</ul>';
                    $('.option_name').html(option_html);

                    option_html = '';
                    for (k = 0; k < frees.length; k++) {
                        var v = frees[k];
                        var checked = (free_options.indexOf(v.id) > -1) ? 'checked' : '';
                        var active = (checked == 'checked') ? 'active' : '';
                        var ispickup = '', free_opt_name = 'free_options[]';
                        if (v.google_column_number == 101 || v.google_column_number == 102) {
                            ispickup = 'pickup';
                            free_opt_name = 'pickup';
                        }

                        option_html += '<li class="' + active + '">' +
                            '<input id="free' + k + '" class="' + ispickup + '" type="checkbox" name="' + free_opt_name + '" value="' + v.id + '" ' + checked + ' >' +
                            <?php if($util->lang() == 'ja'): ?>
                            '<label for="free' + k + '"><span>' + v.name + '</span></label>' +
                            <?php endif; ?>
                            <?php if($util->lang() == 'en'): ?>
                                '<label for="free' + k + '"><span>' + v.name_en + '</span></label>' +
                            <?php endif; ?>
                            '</li>';
                    }
                    $('#free_options').html(option_html);
                }
            });
        }

        $('#free_options').on('change', '.pickup', function () {
            var checked = $(this).prop('checked');
            $('.pickup').prop('checked', false)
                .closest('li').removeClass('active');
            $(this).prop('checked', checked).closest('li').addClass('active');
            $('#free_list').val($('.pickup:checked').val());
        });

        //return shop event
        $('#return-shop').change(function () {
            getOptions();
            $('.pickup_object').show();
        });

        $('#depart-shop').change(function () {
            getOptions();
            if ($(this).val() == '5') {
                $('.pickup').prop('checked', false);
                $('.pickup_object').hide();
            } else {
                $('.pickup_object').show();
            }
            if ($(this).val() == '0') {
                $('#option_wrapper').removeClass('hidden').addClass('hidden');
            } else {
                $('#option_wrapper').removeClass('hidden');
            }
        });

        //disable free option when select smart driveout paid option
        function disableFreeOption(e) {
            var target = $(e.currentTarget);
            var checked = target.prop('checked');
            if (checked == true) {
                $('#pickup').prop('checked', false);
                $('.pickup_object').hide();
            } else {
                $('.pickup_object').show();
            }
        }

        //search view
        var view_flag = '1';

        function searchView() {
            if (view_flag == 0) {
                $('#view_search').removeClass('glyphicon-circle-arrow-down').addClass('glyphicon-circle-arrow-up');
                view_flag = 1;
                $("#searchform").fadeIn("slow");
            }
            else {
                $('#view_search').removeClass('glyphicon-circle-arrow-up').addClass('glyphicon-circle-arrow-down');
                view_flag = 0;
                $("#searchform").fadeOut("slow");
            }
            //event.stopPropagation();
        }

        // submit search
        function submitSearch() {
            if ($('#depart-shop').val() === '0') {
                showModal('<?php echo app('translator')->getFromJson('search.nodepartshop'); ?>');
                return false;
            }
            if ($('#return-shop').val() === '0') {
                showModal('返却地を選択してください。');
                return false;
            }
            if ($('#depart-time').val() === null) {
                showModal('出発時間を選択してください。');
                return false;
            }
            if ($('#return-time').val() === null) {
                showModal('<?php echo app('translator')->getFromJson('search.nodropoffdatetime'); ?>');
                return false;
            }
            return true;
        }

        // insurance change
        $('.insurance').change(function () {
        });

        // submit selection
        function submit_booking(class_id, class_name) {
            var depart_time = $('#depart-time').val();
            if (depart_time === null) {
                showModal('出発時間を選択してください。');
                return false;
            }
            var return_time = $('#return-time').val();
            if (return_time === null) {
                showModal('返却時間を選択してください。');
                return false;
            }

            $('#data_depart_date').val($('#depart-date').val());
            $('#data_depart_time').val(depart_time);
            $('#data_return_date').val($('#return-date').val());
            $('#data_return_time').val(return_time);
            var depart = $('#depart-shop').val(),
                selector = '#depart-shop option[value="' + depart + '"]';
            if (depart == 0) {
                showModal('<?php echo app('translator')->getFromJson('search.nodepartshop'); ?>');
                return;
            }
            $('#data_depart_shop').val(depart);
            $('#data_depart_shop_name').val($(selector).html().trim());
            var arrive = $('#return-shop').val();
            selector = '#return-shop option[value="' + arrive + '"]';
            if (arrive == 0) {
                showModal('返却地を選択してください。');
                return;
            }
            // var search_passenger = $('#passenger').val();
            var search_passenger = $('.car_passenger[class_id="' + class_id + '"]').val();
            // if(search_passenger == 'all') {
            //     search_passenger = class_passenger;
            // } else {
            //     if(class_passenger != 'all')
            //         search_passenger = class_passenger;
            // }

            var rent_price = $('.price_rent[class_id="' + class_id + '"]').val(),
                all_price = $('.price_all[class_id="' + class_id + '"]').val();
            <?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false ): ?>
            dataLayer.push({
                'event': 'addToCart',
                'ecommerce': {
                    'currencyCode': 'JPY',
                    'add': {                       // 'add' actionFieldObject measures.
                        'actionField': {'list': 'Search Car'},
                        'products': [{              //  adding a product to a shopping cart.
                            'name': class_name,
                            'id': class_id,
                            'price': all_price,
                            // 'brand': 'エスティマ',
                            'quantity': 1
                        }]
                    }
                }
            });
            <?php endif; ?>
            $('#data_return_shop').val(arrive);
            $('#data_return_shop_name').val($(selector).html().trim());
            $('#data_car_category').val($('#car-category').val());
            $('#data_passenger').val(search_passenger);
            $('#data_insurance').val($('#insurance').val());
            $('#data_class_id').val(class_id);
            $('#data_smoke').val($('.car_smoking[class_id="' + class_id + '"]').val());
            $('#data_class_name').val($('.car_title[class_id="' + class_id + '"]').html().trim());
            $('#data_insurance_price1').val($('.insurance_price1[class_id="' + class_id + '"]').val());
            $('#data_insurance_price2').val($('.insurance_price2[class_id="' + class_id + '"]').val());
            $('#data_class_category').val($('.car_category[class_id="' + class_id + '"]').html().trim());
            $('#data_car_photo').val($('.car_photo[class_id="' + class_id + '"]').val());
            $('#data_rent_days').val($('.rent_days[class_id="' + class_id + '"]').val());
            $('#data_price_rent').val(rent_price);
            $('#data_option_ids').val($('.option_ids[class_id="' + class_id + '"]').val());
            $('#data_option_names').val($('.option_names[class_id="' + class_id + '"]').val());
            $('#data_option_numbers').val($('.option_numbers[class_id="' + class_id + '"]').val());
            $('#data_option_costs').val($('.option_costs[class_id="' + class_id + '"]').val());
            $('#data_option_prices').val($('.option_prices[class_id="' + class_id + '"]').val());
            $('#data_price_all').val(all_price);
            $('#data_pickup').val($('.car_pickup[class_id="' + class_id + '"]').val());

            $('#booking-submit').submit();
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>