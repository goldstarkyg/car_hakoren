<?php $__env->startSection('template_title'); ?>
    オプション一覧
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }
        .data-table thead tr th {
            white-space: nowrap;
        }
        .data-table tbody tr td {
            white-space: nowrap;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $service_caroption = app('App\Http\Controllers\CarOptionController'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .class_model {
            display: block;
            background-color: #eee;
            text-align: center;
            font-weight:300;
            padding-bottom: 2px;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>オプション一覧</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="<?php echo e(URL::to('/')); ?>/carbasic/caroption/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-car" aria-hidden="true"></i>
                        作成する
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>略名</th>
                                    <th>名前</th>
                                    <th>価格</th>
                                    <th>店舗</th>
                                    <th>料金制度</th>
                                    <th>クラス</th>
                                    <th>Google 番号</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr  valign="middle">
                                        <td style="vertical-align:middle;"><?php echo e(str_pad($option->id, 6, '0', STR_PAD_LEFT)); ?></td>
                                        <td class="hidden-xs" style="vertical-align:middle;"><?php echo e($option->abbriviation); ?></td>
                                        <td style="vertical-align:middle;"><?php echo e($option->name); ?></td>
                                        <td style="vertical-align:middle;"><?php echo e($option->price); ?></td>
                                        <td style="vertical-align:middle;">
                                            <?php $__currentLoopData = $option->carOptionShop; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="class_model"><?php echo e($service_caroption->getCarShopName($shop->shop_id)); ?>*<?php echo e($shop->option_count); ?> = <?php echo e($shop->price); ?></label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td style="vertical-align:middle;"><?php echo e($option->charge_system); ?></td>
                                        <td style="vertical-align:middle;">
                                            <?php $__currentLoopData = $option->carOptionClass; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="class_model"><?php echo e($service_caroption->getCarClassName($class->class_id)); ?> </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td style="vertical-align:middle;"><?php echo e($option->google_column_number); ?></td>
                                        <td style="vertical-align:middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="<?php echo e(URL::to('carbasic/caroption/' . $option->id)); ?>" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="<?php echo e(URL::to('/carbasic/caroption/' . $option->id . '/edit')); ?>" title="編集">
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
    <?php echo $__env->make('scripts.admin.carbasic.caroption-index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>