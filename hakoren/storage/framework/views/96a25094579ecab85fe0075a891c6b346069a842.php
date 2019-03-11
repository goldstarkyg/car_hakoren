<?php $__env->startSection('template_title'); ?>
    店舗詳細
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
    <link href="<?php echo e(URL::to('/')); ?>/css/multiimageupload.css" rel="stylesheet">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>店舗詳細: <?php echo e($shop->name); ?>

                    <a href="<?php echo e(URL::to('/')); ?>/shopbasic/shop/<?php echo e($shop->id); ?>/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="<?php echo e(URL::to('/')); ?>/shopbasic/shop" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧に戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    <?php echo Form::model($shop, array('action' => array('ShopController@update', $shop->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')); ?>


                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">店舗名</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($shop->name); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">略称</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($shop->abbriviation); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">スラッグ</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($shop->slug); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">店舗画像</label>
                        <div class="col-sm-9">
                            <?php if($shop->thumb_path): ?>
                                <img src="<?php echo e(URL::to('/').$shop->thumb_path); ?>" class="img-thumbnail" style="width:100px; height: auto" >
                            <?php else: ?>
                                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">電話番号</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($shop->phone); ?>

                        </div>
                    </div>
                    
                        
                        
                            
                        
                    
                    
                        
                        
                            
                        
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">郵便番号</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($shop->postal); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">都道府県</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($shop->prefecture); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">市町村</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($shop->city); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">住所 1</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($shop->address1); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">住所 2</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($shop->address2); ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">主任</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($member->id == $shop->member_id ): ?>
                                    <?php echo e($member->first_name); ?> <?php echo e($member->last_name); ?>

                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="<?php echo e(URL::to('/shopbasic/shop/' . $shop->id . '/edit')); ?>" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
                            </label>
                            <label>
                                <?php echo Form::open(array('url' => URL::to('/').'/shopbasic/shop/' . $shop->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')); ?>

                                <?php echo Form::hidden('_method', 'DELETE'); ?>

                                <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => 'この店舗を完全に削除する',
                                        'data-message' => 'この店舗および関連情報を全て削除しますか?<br/>この操作は取り消すことができません。')); ?>

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
    <?php echo $__env->make('scripts.admin.shopbasic.shop-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <!-- Jquery Validate -->
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/home.js"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>