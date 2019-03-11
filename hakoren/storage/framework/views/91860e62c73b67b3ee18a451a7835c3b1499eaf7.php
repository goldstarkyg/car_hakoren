<?php $__env->startSection('template_title'); ?>
    車両モデル一覧
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
         .attribute {
             background: url(<?php echo e(URL::to('/images/attributes.png')); ?>) no-repeat;
             color: #303740;
             display: block;
             font-size: 0.9em;
             height: 23px;
             line-height: 20px;
             padding: 0 5px 0 0;
             text-align: right;
             width: 38px;
         }
        .attribute-passengers {
            background-position: 0 0;
        }
        .attribute-luggages {
            background-position: 0 -23px;
        }
        .attribute-doors {
            background-position: 0 -46px;
        }
         .attribute_smoke {
             background: url(<?php echo e(URL::to('/images/smoke.png')); ?>) no-repeat;
             color: #303740;
             display: block;
             font-size: 0.9em;
             height: 23px;
             line-height: 20px;
             padding: 0 5px 0 0;
             text-align: right;
             width: 38px;
         }
         .attribute-smoke {
             background-position: 0 0;
         }
         .attribute-nonsmoke {
             background-position: 0 -23px;
         }
    </style>
<?php $__env->stopSection(); ?>
<?php $service = app('App\Http\Controllers\CarModelController'); ?>
<?php $__env->startSection('content'); ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両モデル一覧</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="<?php echo e(URL::to('/')); ?>/carbasic/carmodel/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
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
                            <table class="table table-striped table-condensed data-table" width="100%">
                                <thead>
                                <tr>
                                    <th>モデルID</th>
                                    <th>外観</th>
                                    <th>モデル名</th>
                                    <th>車両<br/>タイプ</th>
                                    <th>メーカー</th>
                                    
                                    <th>エンジン</th>
                                    <th>喫煙/禁煙</th>
                                    <th> </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr  valign="middle">
                                        <td style="vertical-align:middle;"><?php echo e(str_pad($model->id, 6, '0', STR_PAD_LEFT)); ?></td>
                                        <td class="hidden-xs" style="vertical-align: middle;width:100px; height: auto">
                                            <?php if(!$model->thumb_path): ?>
                                                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" width="80px" class="img-thumbnail">
                                            <?php else: ?>
                                                <img src="<?php echo e(URL::to('/').$model->thumb_path); ?>" width="80px" class="img-thumbnail">
                                            <?php endif; ?>
                                        </td>
                                        <td style="vertical-align:middle;">
                                            <span class="pj-table-cell-label">
                                                <?php echo e($model->name); ?>

                                                
                                                    
                                                
                                                <span class="attribute attribute-luggages float_left">
                                                    <?php echo e($model->luggages); ?>

                                                </span>
                                                <span class="attribute attribute-doors float_left">
                                                    <?php echo e($model->doors); ?>

                                                </span>
                                            </span>
                                        </td>
                                        <td style="vertical-align: middle;"><?php if(!empty($model->type_id)): ?><?php echo e(empty($model->type)? '':$model->type->name); ?> <?php endif; ?></td>
                                        <td style="vertical-align: middle;"><?php if($model->vendor_id != 0): ?><?php echo e($model->vendor->name); ?> <?php endif; ?></td>

                                        <td style="vertical-align: middle;"><?php echo e(ucfirst($model->transmission)); ?></td>
                                        <td style="vertical-align: middle;">
                                            <span class="attribute_smoke attribute-smoke float_left">
                                                    <?php echo e($service->getnumberSmoking($model->id,1)); ?>

                                            </span>
                                            <span class="attribute_smoke attribute-nonsmoke float_left">
                                                    <?php echo e($service->getnumberSmoking($model->id,0)); ?>

                                            </span>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="<?php echo e(URL::to('carbasic/carmodel/' . $model->id)); ?>" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="<?php echo e(URL::to('/carbasic/carmodel/' . $model->id . '/edit')); ?>" title="編集">
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
    <?php echo $__env->make('scripts.admin.carbasic.carmodel-index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>