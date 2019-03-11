<?php $__env->startSection('template_title'); ?>
    車両クラスの詳細
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $service_caroption = app('App\Http\Controllers\CarClassController'); ?>
<?php $__env->startSection('content'); ?>
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/css/home.css">
    <link href="<?php echo e(URL::to('/')); ?>/css/multiimageupload.css" rel="stylesheet">
    <style>
        .class_model {
            font-weight:300;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両クラスの詳細: <?php echo e($carclass->name); ?>

                    <a href="<?php echo e(URL::to('/')); ?>/carbasic/carclass/<?php echo e($carclass->id); ?>/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="<?php echo e(URL::to('/')); ?>/carbasic/carclass" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    <?php echo Form::model($carclass, array('method' => 'PATCH', 'action' => array('CarClassController@update', $carclass->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')); ?>


                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラスの表示優先度</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($carclass->car_class_priority); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラス名</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($carclass->name); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラス画像</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php if($carclass->thumb_path): ?>
                                <img src="<?php echo e(URL::to('/').$carclass->thumb_path); ?>" class="img-thumbnail" style="width:100px; height: auto" >
                            <?php else: ?>
                                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラス画像(en)</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php if($carclass->thumb_path_en): ?>
                                <img src="<?php echo e(URL::to('/').$carclass->thumb_path_en); ?>" class="img-thumbnail" style="width:100px; height: auto" >
                            <?php else: ?>
                                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">関連画像<br/>（内装）</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                <?php $__currentLoopData = $thumbnails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <img class="col-md-3 img-thumbnail" src="<?php echo e(URL::to('/').$thumb->thumb_path); ?>" style="height:100px;width:auto" >
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラスの略名</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($carclass->abbriviation); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラスの特徴</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo $carclass->description; ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">スタッフコメント</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo $carclass->staff_comment; ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">推奨クラス</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php $__currentLoopData = $suggests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$sg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($sg->name); ?>

                                <?php if($key < count($suggests) -1): ?> / <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">モデル</label>
                        <div class="col-sm-9 m-t-xs">
                            <div style="margin-top: 10px;">
                                <?php $__currentLoopData = $carclass->carClassModel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $model = $service_caroption->getCarModelInform($mo->model_id)?>
                                    <?php if($model): ?>
                                    <div  style="padding-top:5px;border-top:1px solid #999696;">
                                        <div class="form-group" >
                                            <label class="col-sm-3 class_model">モデル名 :</label>
                                            <label class="col-sm-9 class_model">
                                                <?php echo e($model->name); ?>

                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 class_model">画像 :</label>
                                            <label class="col-sm-9 class_model">
                                                <?php if(!$model->thumb_path): ?>
                                                    <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" style="width: 100px; height: auto" >
                                                <?php else: ?>
                                                    <img src="<?php echo e(URL::to('/').$model->thumb_path); ?>" class="img-thumbnail" style="width: 100px; height: auto"  >
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 class_model">車両タイプ :</label>
                                            <label class="col-sm-9 class_model" style="text-align: left">
                                                <?php echo e($model->type_name); ?>

                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 class_model">メーカー :</label>
                                            <label class="col-sm-9 class_model">
                                                <?php echo e($model->vendor_name); ?>

                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 class_model">関連店舗 :</label>
                                            <label class="col-sm-9 class_model">
                                                <?php $__currentLoopData = $model->cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                   <p> <?php echo e($car); ?> </p>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">検索対象</label>
                        <div class="col-sm-9 m-t-xs">
                            <div id="statusBtn" class="btn-group">
                                <span class="btn btn-primary btn-md active" data-toggle="status" data-value="1">対象</span>
                                <span class="btn btn-default btn-md notActive" data-toggle="status" data-value="0">非対象</span>
                            </div>
                            <input type="hidden" name="status" id="status" value="<?php echo e($carclass->status); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="<?php echo e(URL::to('/carbasic/carclass/' . $carclass->id . '/edit')); ?>" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
                            </label>
                            <label>
                                <?php echo Form::open(array('url' => URL::to('/').'/carbasic/carclass/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')); ?>

                                <?php echo Form::hidden('_method', 'DELETE'); ?>

                                <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => 'クラスを削除する',
                                        'data-message' => '本当にこのクラスを削除しますか？<br/>この操作は取り消せません。')); ?>

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
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
            <!-- Jquery Validate -->
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/home.js"></script>
    <script>
        $(document).ready(function() {

            //select charge system
            var sel = $('#status').val();
            var tog = $('#statusBtn span').data('toggle');
            $('#' + tog).val(sel);
            $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
            $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');

        });



    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>