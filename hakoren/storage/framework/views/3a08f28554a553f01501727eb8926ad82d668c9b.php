<?php echo Form::model($carclass, array('method' => 'PATCH', 'action' => array('CarClassController@updateequipment', $carclass->id),  'class' => 'form-horizontal', 'id'=>'insuranceform', 'role' => 'form', 'method' => 'POST', 'enctype' => 'multipart/form-data')); ?>


<?php echo e(csrf_field()); ?>

<input type="hidden" name="class_id" value="<?php echo e($carclass->id); ?>" />
<div class="form-group has-feedback row <?php echo e($errors->has('car_options') ? ' has-error ' : ''); ?>">
    <?php echo Form::label('car_equipment',
                '装備',
                array('class' => 'col-md-3 control-label'));; ?>

    <div class="col-md-9">
        <div>
            <input type="hidden" id="car_equipments" name="car_equipments"  />
            <select class="chosen-select form-control" name="car_equipment" id="car_equipment" data-placeholder="Choose a Car Equipment" multiple tabindex="2">
                <?php $__currentLoopData = $equipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $select = ''; ?>
                    <?php $__currentLoopData = $carclass->carClassEquipment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ce): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        if($ce->equipment_id == $equip->id)
                        {
                            $select = 'selected' ;
                            break;
                        }
                        ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($equip->id); ?>" <?php echo $select ?> ><?php echo e($equip->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <?php if($errors->has('car_equipment')): ?>
            <span class="help-block">
                    <strong><?php echo e($errors->first('car_eqipment')); ?></strong>
            </span>
        <?php endif; ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">
        <label>
            <?php echo Form::open(array('url' => URL::to('/').'/carbasic/carclass/updateequipment/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')); ?>

            <?php echo Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success disableddd',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> '装備を保存',
                    'data-message' 		=> 'この装備の変更を保存しますか？'
            )); ?>

            <?php echo Form::close(); ?>

        </label>
        
        
        
        
        
        
        
        
        
        
        
        
        
    </div>
</div>

<?php echo Form::close(); ?>






