<link href="<?php echo e(URL::to('/')); ?>/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<form action="<?php echo e(URL::to('/').'/carbasic/carclass/update_model_order/'.$carclass->id); ?>" class="form-horizontal" id="priority_form" role="form" method="POST" enctype="multipart/form-data">
<?php echo e(csrf_field()); ?>


<div class="form-group has-feedback row <?php echo e($errors->has('model_priority') ? ' has-error ' : ''); ?>">
    <?php echo Form::label('model_priorities',
                'モデル優先度',
                array('class' => 'col-md-3 control-label'));; ?>

    <div class="col-md-9">
        <div>
            <input type="hidden" id="model_orders" name="model_orders"  />

            <ul id="model_sort" name="model_sort">
                <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="ui-state-default" order="<?php echo e($p->model_id); ?>">
                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo e($p->name); ?>

                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php if($errors->has('model_priority')): ?>
            <span class="help-block">
                <strong><?php echo e($errors->first('model_priority')); ?></strong>
            </span>
        <?php endif; ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">
        <label>

            <?php echo Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> 'モデル優先度を保存',
                    'data-message' 		=> 'このモデル優先度の変更を保存しますか？'
            )); ?>


        </label>
    </div>
</div>
</form>
<style>
    .ui-state-default {

    }
</style>





