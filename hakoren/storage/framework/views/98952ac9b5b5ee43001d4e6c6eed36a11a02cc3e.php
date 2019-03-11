<?php $__env->startSection('template_title'); ?>
    車両クラスを作成する
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_fastload_css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/multiimageupload.css" rel="stylesheet">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両クラスを作成する
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="<?php echo e(URL::to('/')); ?>/carbasic/carclass" class="btn btn-info btn-xs pull-right">
                                <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                                一覧へ戻る
                            </a>
                        </div>
                    </div>
                </h2>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default">

                    <div class="panel-body">

                        <?php echo Form::open(array('action' => 'CarClassController@store',
                                'method' => 'POST', 'role' => 'form',
                                'class' => 'form-horizontal','enctype'=>'multipart/form-data')); ?>


                        <?php echo csrf_field(); ?>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                
                                <tr class="<?php echo e($errors->has('name') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="name" >クラス名</label>
                                    </td>
                                    <td class="col-md-9">
                                        <?php echo Form::text('name', NULL,
                                                    array('id' => 'name',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'クラス名を入力してください')); ?>

                                        <?php if($errors->has('name')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('name')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                
                                
                                
                                <tr class="<?php echo e($errors->has('name') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="name" >関連する店舗<br/>(1店舗のみ)</label>
                                    </td>
                                    <td class="col-md-9">
									                                    
                                        <?php if(!empty($car_shop_list)): ?>
                                       <select name="car_shop_name" class="form-control select2">
                                            <?php $__currentLoopData = $car_shop_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                              <option <?php if(old('car_shop_name') == $shop->id) { echo "selected"; } ?> value="<?php echo e($shop->id); ?>">
                                              	<?php echo e($shop->name); ?>

                                              </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       </select>
                                        <?php endif; ?>
                                        <?php if($errors->has('car_shop_name')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('car_shop_name')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>                                
                                
                                
                                
                                <tr class="<?php echo e($errors->has('thumb_path') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="thumb_path" >クラス画像</label>
                                    </td>
                                    <td class="col-md-9">
                                        <?php echo Form::file('thumb_path', NULL,
                                                    array('id' => 'thumb_path',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Select Image')); ?>

                                        <?php if($errors->has('thumb_path')): ?>
                                            <span class="help-block">
                                            <strong><?php echo e($errors->first('thumb_path')); ?></strong>
                                         </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="<?php echo e($errors->has('thumb_paths') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="thumb_paths" >関連画像(内装)</label>
                                    </td>
                                    <td class="col-md-9">
                                        <div id="filediv">
                                            <input name="file[]" type="file" id="file" class="form-control" placeholder="Select Images" />
                                        </div>
                                        <button type="button" id="add_more" class="btn btn-secondary">追加する</button>
                                        <?php if($errors->has('thumb_paths')): ?>
                                            <span class="help-block">
                                            <strong><?php echo e($errors->first('thumb_paths')); ?></strong>
                                         </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="<?php echo e($errors->has('abbriviation') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="name" >クラスの略名</label>
                                    </td>
                                    <td class="col-md-9">
                                        <?php echo Form::text('abbriviation', NULL,
                                                    array('id' => 'abbriviatin',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'クラスの略名を入力してください')); ?>

                                        <?php if($errors->has('abbriviation')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('abbriviation')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="<?php echo e($errors->has('description') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="name" >クラスの特徴</label>
                                    </td>
                                    <td class="col-md-9">
                                        <?php echo Form::textarea('description', NULL,
                                                    array('id'  => 'description',
                                                        'class' => 'form-control',
                                                        'rows'  => '2',
                                                        'cols'  => '40',
                                                        'placeholder' => 'クラスの特徴を入力してください')); ?>

                                        <?php if($errors->has('description')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('description')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="<?php echo e($errors->has('staff_comment') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="name" >スタッフコメント</label>
                                    </td>
                                    <td class="col-md-9">
                                        <?php echo Form::textarea('staff_comment', NULL,
                                                    array('id'  => 'staff_comment',
                                                        'class' => 'form-control',
                                                        'rows'  => '2',
                                                        'cols'  => '40',
                                                        'placeholder' => 'スタッフコメントを入力してください')); ?>

                                        <?php if($errors->has('staff_comment')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('staff_comment')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="<?php echo e($errors->has('passenger') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="name" >定員数</label>
                                    </td>
                                    <td class="col-md-9">
                                        <input type="hidden" id="car_psgtags" name="car_psgtags"  />
                                        <select class="chosen-select form-control" name="car_psgtag" id="car_psgtag" data-placeholder="選択してください" multiple tabindex="2">
                                            <?php $__currentLoopData = $psgtags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($tag->id); ?>" ><?php echo e($tag->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php if($errors->has('passenger')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('passenger')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <tr class="<?php echo e($errors->has('car_model') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="model_id" >モデル</label>
                                    </td>
                                    <td class="col-md-9">
                                        <div>
                                            <input type="hidden" id="car_models" name="car_models" />
                                            <select class="chosen-select form-control" name="car_model" id="car_model" data-placeholder="選択してください" multiple tabindex="2">
                                                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($model->id); ?>"><?php echo e($model->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <?php if($errors->has('car_model')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('model_id')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="<?php echo e($errors->has('status') ? ' has-error ' : ''); ?>" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="status" >検索対象 </label>
                                    </td>
                                    <td class="col-md-9">
                                        <div id="statusBtn" class="btn-group">
                                            <span class="btn btn-primary btn-md active" data-toggle="status" data-value="1">対象</span>
                                            <span class="btn btn-default btn-md notActive" data-toggle="status" data-value="0">非対象</span>
                                        </div>
                                        <input type="hidden" name="status" id="status" value="1">
                                        <?php if($errors->has('status')): ?>
                                            <span class="staus">
                                                <strong><?php echo e($errors->first('status')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php echo Form::button('<i class="fa fa-car" aria-hidden="true"></i>&nbsp;' . '新規作成する',
                                    array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right',
                                    'type' => 'submit', )); ?>

                        <?php echo Form::close(); ?>


                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
<link href="<?php echo e(URL::asset('plugins/select2/select2.min.css')); ?>" rel="stylesheet">
<script src="<?php echo e(URL::asset('plugins/select2/select2.full.min.js')); ?>"></script> 
<script type="text/javascript">
    $(".select2").select2();
</script>

    <style type="text/css">
        .datepicker {
            background: #fff;
            border: 1px solid #555;
        }
        .chosen-container-multi .chosen-choices{
            border: 1px solid #dedede;
            background-image:none;
        }
        .left-back{
            background-color: #e2e1e1;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #929297;
        }
        table.table-bordered{
            border:1px solid #929297;
            margin-top:20px;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #929297;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid #929297;
        }
    </style>
    <?php echo $__env->make('scripts.admin.carbasic.carclass-create', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>