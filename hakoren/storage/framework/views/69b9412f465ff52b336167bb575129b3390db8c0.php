<?php $__env->startSection('template_title'); ?>
    店舗管理
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>店舗一覧</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="<?php echo e(URL::to('/')); ?>/shopbasic/shop/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-car" aria-hidden="true"></i>
                        新規登録
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>画像</th>
                                    <th>店舗名</th>
                                    <th>略称</th>
                                    
                                    <th>都道府県</th>
                                    <th> </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr  valign="middle">
                                        <td style="vertical-align:middle;"><?php echo e(str_pad($shop->id, 6, '0', STR_PAD_LEFT)); ?></td>
                                        <td class="hidden-xs" style="vertical-align: middle;width:50px;height: auto">
                                            <?php if($shop->thumb_path): ?>
                                                <img src="<?php echo e(URL::to('/').$shop->thumb_path); ?>" class="img-thumbnail" style="width:100px; height: auto" >
                                            <?php else: ?>
                                                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
                                            <?php endif; ?>
                                        </td>
                                        <td style="vertical-align:middle;"><?php echo e($shop->name); ?></td>
                                        <td style="vertical-align: middle;"><?php echo e($shop->abbriviation); ?></td>
                                        
                                        <td style="vertical-align: middle;"><?php echo e($shop->prefecture); ?></td>
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="<?php echo e(URL::to('shopbasic/shop/' . $shop->id)); ?>" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="<?php echo e(URL::to('/shopbasic/shop/' . $shop->id . '/edit')); ?>" title="編集">
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
    <?php echo $__env->make('scripts.admin.shopbasic.shop-index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>