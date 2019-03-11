<?php $__env->startSection('template_title'); ?>
    車両クラス
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<?php $__env->stopSection(); ?>
<?php $service_caroption = app('App\Http\Controllers\CarClassController'); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .class_model {
            display: block;
            background-color: #eee;
            text-align: center;
            font-weight:300;
            padding-bottom: 2px;
        }
        .btn-circle_active {
            width: 10px;
            height: 10px;
            padding: 3px 0;
            border-radius: 5px;
            margin-left: 5px;
            background-color: #13e313;
            position: absolute;
            margin-top: 5px;;
        }
        .btn-circle_inactive {
            width: 10px;
            height: 10px;
            padding: 3px 0;
            border-radius: 15px;
            margin-left: 5px;
            background-color:#f44336;
            position: absolute;
            margin-top: 5px;;
        }
        .search_type {
            background-color: #eee;
            padding: 3px 5px 3px 5px;
            font-size: 12px;
            border: 1px solid #bebebe;
            font-weight: 300;
            border-radius: 5px;;
            margin-right: 7px;
            cursor: pointer;
        }
        .search_model {
            background-color: #eee;
            padding: 3px 5px 3px 5px;
            font-size: 12px;
            border: 1px solid #bebebe;
            font-weight: 300;
            border-radius: 5px;
            margin-right: 7px;;
            cursor: pointer;
        }
        .mynotActive{
            background-color: #DEDEDE;
            color:#585858;
        }
        .myactive{
            background-color: #808080;
            color: #fff;
        }

    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2 >車両クラス一覧</h2>
                <div style="position: absolute;left:250px;top: 20px;">
                    <form id="searchform" action="<?php echo e(URL::to('/')); ?>/carbasic/carclasspost" method="post">
                        <?php echo csrf_field(); ?>

                        <select class="form-control"  name="car_shop" id="car_shop">
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $select = '';
                                if($shop->id == $car_shop) $select =  'selected';
                                ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e($select); ?>> <?php echo e($shop->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </form>
                </div>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="<?php echo e(URL::to('/')); ?>/carbasic/carclass/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-car" aria-hidden="true"></i>
                        作成する
                    </a>
                </div>

            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-heading hidden" id="panel_head" style="cursor: pointer">
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size:16px; color:#222;">
                            <div>
                                表示条件を設定する <span class="glyphicon glyphicon-circle-arrow-down" id="view_search" style="margin-top: 3px; margin-left: 3px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table" width="100%">
                                <thead>
                                <tr>
                                    <th>表示優先度</th>
                                    <th>画像</th>
                                    <th>クラス名</th>
                                    <th>モデル</th>
                                    
                                    
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr  valign="middle">
                                        <td style="vertical-align:middle;"><?php echo e(str_pad($class->car_class_priority, 3, '0', STR_PAD_LEFT)); ?></td>
                                        <td class="hidden-xs" style="vertical-align: middle;width:130px;height: auto">
                                            <?php if(!$class->thumb_path): ?>
                                                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail">
                                            <?php else: ?>
                                                <img src="<?php echo e(URL::to('/').$class->thumb_path); ?>" class="img-thumbnail">
                                            <?php endif; ?>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <?php echo e($class->name); ?>

                                            <?php if($class->status == '1'): ?>
                                                <label class="btn-circle_active"></label>
                                            <?php else: ?>
                                                <label class="btn-circle_inactive"></label>
                                            <?php endif; ?>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <?php $__currentLoopData = $class->carClassModel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="class_model" ><?php echo e($service_caroption->getCarModelName($model->model_id)); ?></label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        
                                        
                                            
                                                
                                            
                                                
                                            
                                        
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="<?php echo e(URL::to('carbasic/carclass/' . $class->id)); ?>" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="<?php echo e(URL::to('/carbasic/carclass/' . $class->id . '/edit')); ?>" title="編集">
                                                    <span class="hidden-xs hidden-sm">編集</span>
                                                </a>
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <?php echo $__env->make('scripts.admin.carbasic.carclass-index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>