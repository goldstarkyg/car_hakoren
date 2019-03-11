<?php $__env->startSection('template_title'); ?>
    車両モデルを編集する
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $service = app('App\Http\Controllers\CarModelController'); ?>
<?php $__env->startSection('content'); ?>
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/multiimageupload.css" rel="stylesheet">

    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両モデルを編集する: <?php echo e($carmodel->name); ?>

                    <a href="<?php echo e(URL::to('/')); ?>/carbasic/carmodel/<?php echo e($carmodel->id); ?>" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="<?php echo e(URL::to('/')); ?>/carbasic/carmodel" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    <?php echo Form::model($carmodel, array('method' => 'PATCH', 'action' => array('CarModelController@update', $carmodel->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')); ?>


                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">モデル名</label>
                        <div class="col-sm-9">
                            <?php echo Form::text('name', $carmodel->name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name']); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">モデル名(en)</label>
                        <div class="col-sm-9">
                            <?php echo Form::text('name_en', $carmodel->name_en, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name_en']); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">代表外観図</label>
                        <div class="col-sm-3">
                            <?php if(!$carmodel->thumb_path): ?>
                                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" >
                            <?php else: ?>
                                <img src="<?php echo e(URL::to('/').$carmodel->thumb_path); ?>" class="img-thumbnail" >
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <?php echo Form::file('thumb_path', NULL,
                                                   array('id' => 'thumb_path',
                                                   'class' => 'form-control',
                                                   'placeholder' => 'Select Image')); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="thumb_paths" class="col-sm-3 control-label">関連画像(内装）</label>
                        <div class="col-sm-9">
                            <div>
                                <div id="filediv">
                                    <input name="file[]" type="file" id="file" class="form-control" placeholder="Select Images" />
                                    <input name="deletethumbs" id="deletethumbs" type="hidden" />
                                </div>
                                <div>
                                    <button type="button" id="add_more" class="btn btn-secondary" >追加する</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type_id" class="col-sm-3 control-label">車両カテゴリ</label>
                        <div class="col-sm-9">
                            <div>
                                <select class="chosen-select form-control" name="category_id" id="category_id" >
                                    <option value="0">--選択してください--</option>
                                    <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $select = '';
                                        if($cate->id == $carmodel->category_id ) $select="selected";
                                        ?>
                                        <option value="<?php echo e($cate->id); ?>" <?php echo e($select); ?>><?php echo e($cate->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type_id" class="col-sm-3 control-label">車両タイプ</label>
                        <div class="col-sm-9">
                            <div>
                                <select class="chosen-select form-control" name="type_id" id="type_id" >
                                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $select = '';
                                        if($type->id == $carmodel->type_id ) $select="selected";
                                        ?>
                                        <option value="<?php echo e($type->id); ?>" <?php echo e($select); ?>><?php echo e($type->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor_id" class="col-sm-3 control-label">メーカー</label>
                        <div class="col-sm-9">
                            <div>
                                <select class="chosen-select form-control" name="vendor_id" id="vendor_id" >
                                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $select = '';
                                        if($vendor->id == $carmodel->vendor_id ) $select="selected";
                                        ?>
                                        <option value="<?php echo e($vendor->id); ?>" <?php echo e($select); ?>><?php echo e($vendor->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                        
                        
                            
                        
                    
                    <div class="form-group">
                        <label for="luggages" class="col-sm-3 control-label">荷物</label>
                        <div class="col-sm-9">
                            <?php echo Form::text('luggages', $carmodel->luggages, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'luggages']); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="doors" class="col-sm-3 control-label">ドア数</label>
                        <div class="col-sm-9">
                            <?php echo Form::text('doors', $carmodel->doors, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'doors']); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="transmission" class="col-sm-3 control-label">エンジン</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="transmission" id="transmission" >
                                <?php if(!$carmodel->transmission): ?>
                                    <option value="automatic"
                                            <?php if($carmodel->transmission == 'automatic'): ?> selected <?php endif; ?> >Automatic
                                    </option>
                                <?php else: ?>
                                    <option value="manual"
                                            <?php if($carmodel->transmission == 'manual'): ?> selected <?php endif; ?> >Manual
                                    </option>
                                    <option value="automatic"
                                            <?php if($carmodel->transmission == 'automatic'): ?> selected <?php endif; ?> >Automatic
                                    </option>
                                    <option value="semi-automatic"
                                            <?php if($carmodel->transmission == 'semi-automatic'): ?> selected <?php endif; ?> >Semi-automatic
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="insurance_2" class="col-sm-3 control-label">喫煙（登録車両数）</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($service->getnumberSmoking($carmodel->id,1)); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="insurance_2" class="col-sm-3 control-label">禁煙（登録車両数）</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($service->getnumberSmoking($carmodel->id,0)); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <?php echo Form::open(array('url' => URL::to('/').'/carbasic/carmodel/' . $carmodel->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')); ?>

                                <?php echo Form::button(
                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i><span class="hidden-xs hidden-sm"> '.trans('profile.submitButton').'</span> ' ,
                                     array(
                                        'class' 		 	=> 'btn btn-success disableddd',
                                        'type' 			 	=> 'button',
                                        'data-target' 		=> '#confirmForm',
                                        'data-modalClass' 	=> 'modal-success',
                                        'data-toggle' 		=> 'modal',
                                        'data-title' 		=> '車両モデルを保存',
                                        'data-message' 		=> 'この車両モデルの変更を保存しますか？'
                                )); ?>

                                <?php echo Form::close(); ?>

                            </label>
                            <label>
                                <?php echo Form::open(array('url' => URL::to('/').'/carbasic/carmodel/' . $carmodel->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')); ?>

                                <?php echo Form::hidden('_method', 'DELETE'); ?>

                                <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => '車両モデルを削除',
                                        'data-message' => 'この車両モデルを本当に削除しますか？この操作を取り消すことはできません。')); ?>

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
    <script>
        var public_url = '<?php echo e(URL::to('/')); ?>';
        var thumbs = [];
        var thumbs_ids = [];
        <?php $__currentLoopData = $thumbnails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            thumbs.push(public_url+'<?php echo e($thumb->thumb_path); ?>');
            thumbs_ids.push('<?php echo e($thumb->id); ?>');
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </script>
    <?php echo $__env->make('scripts.form-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.admin.carbasic.carmodel-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <!-- Jquery Validate -->
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/home.js"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>