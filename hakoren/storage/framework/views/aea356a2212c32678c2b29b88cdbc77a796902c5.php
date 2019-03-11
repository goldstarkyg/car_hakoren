<?php $__env->startSection('template_title'); ?>
    車両タイプの詳細
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両タイプの詳細: <?php echo e($cartype->name); ?>

                    <a href="<?php echo e(URL::to('/')); ?>/carbasic/cartype/<?php echo e($cartype->id); ?>/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="<?php echo e(URL::to('/')); ?>/carbasic/cartype" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    <?php echo Form::model($cartype, array('method' => 'PATCH', 'action' => array('CarTypeController@update', $cartype->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')); ?>


                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">カテゴリ</label>
                        <div class="col-sm-9">
                            <?php echo e($cartype->category_name); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">車両タイプ略名</label>
                        <div class="col-sm-9">
                             <?php echo e($cartype->abbriviation); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">車両タイプ名</label>
                        <div class="col-sm-9">
                            <?php echo e($cartype->name); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="<?php echo e(URL::to('/carbasic/cartype/' . $cartype->id . '/edit')); ?>" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
                            </label>
                            <label>
                                <?php echo Form::open(array('url' => URL::to('/').'/carbasic/cartype/' . $cartype->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')); ?>

                                <?php echo Form::hidden('_method', 'DELETE'); ?>

                                <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => 'Delete Car Type',
                                        'data-message' => 'Do you want to delete this car type?')); ?>

                                <?php echo Form::close(); ?>

                            </label>
                        </div>
                    </div>

                    <?php echo Form::close(); ?>


                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('modals.modal-form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/dropzone/dropzone.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/alterclass.js"></script>

    <?php echo $__env->make('scripts.form-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.check-changed', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.gmaps-address-lookup-api3', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.admin.member_edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <!-- Jquery Validate -->
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/home.js"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>