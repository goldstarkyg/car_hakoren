<?php echo Form::model($carclass, array('method' => 'PATCH', 'action' => array('CarClassController@updateinsurance', $carclass->id),  'class' => 'form-horizontal', 'id'=>'insuranceform', 'role' => 'form', 'method' => 'POST', 'enctype' => 'multipart/form-data')); ?>


<?php echo e(csrf_field()); ?>

    <input type="hidden" name="class_id" value="<?php echo e($carclass->id); ?>" />
    <div class="form-group m-t-sm">
        <label for="car_class_priority" class="col-sm-3 control-label"><?php echo e($insurances[0]->name); ?></label>
        <input type="hidden" name="first_val" value="<?php echo e($insurances[0]->price); ?>_<?php echo e($insurances[0]->id); ?>" >
        <input type="hidden" name="first_ins_id" value="<?php echo e($insurances[0]->id); ?>" >
        <div class="col-sm-9">
            <?php echo Form::text('ins_first', $insurances[0]->price, ['class' => 'insurance_price form-control required', 'placeholder' => '', 'id' =>'ins_first']); ?>

        </div>
    </div>
    <div class="form-group m-t-sm">
        <label for="car_class_priority" class="col-sm-3 control-label"><?php echo e($insurances[1]->name); ?></label>
        <input type="hidden" name="second_val" value="<?php echo e($insurances[1]->price); ?>_<?php echo e($insurances[1]->id); ?>" >
        <input type="hidden" name="second_ins_id" value="<?php echo e($insurances[1]->id); ?>" >
        <div class="col-sm-9">
            <?php echo Form::text('ins_second', $insurances[1]->price, ['class' => 'insurance_price form-control required', 'placeholder' => '', 'id' =>'ins_second']); ?>

        </div>
    </div>

<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">
        <label>
            <?php echo Form::open(array('url' => URL::to('/').'/carbasic/carclass/updateinsurance/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')); ?>

            <?php echo Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success disableddd',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> '補償を保存',
                    'data-message' 		=> 'この補償の変更を保存しますか？'
            )); ?>

            <?php echo Form::close(); ?>

        </label>
        
        
        
        
        
        
        
        
        
        
        
        
        
    </div>
</div>

<?php echo Form::close(); ?>






