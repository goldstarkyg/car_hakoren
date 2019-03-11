<?php echo Form::model($time_1, array('action' =>'ShopController@updatebusiness',  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'POST', 'enctype' => 'multipart/form-data')); ?>


<?php echo e(csrf_field()); ?>

 <input type="hidden" name="shop_id" value="<?php echo e($time_1->shop_id); ?>" />
 <div class="table-responsive m-t-sm">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="col-md-3">曜日</th>
            <th class="col-md-3">終日休業</th>
            <th class="col-md-3">開始時刻</th>
            <th class="col-md-3">終了時刻</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <label class="control-label">月</label>
            </td>
            <td>
                <input type="checkbox" name="monday_dayoff"  value="<?php echo e($time_1->monday_dayoff); ?>" <?php if($time_1->monday_dayoff == '1'): ?> checked <?php endif; ?>   >
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="monday_from" type="text" value="<?php echo e($time_1->monday_from); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="monday_to" type="text" value="<?php echo e($time_1->monday_to); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label class="control-label">火</label>
            </td>
            <td>
                <input type="checkbox" name="tuesday_dayoff" value="<?php echo e($time_1->tuesday_dayoff); ?>" <?php if($time_1->tuesday_dayoff == '1'): ?> checked <?php endif; ?>  >
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="tuesday_from" type="text" value="<?php echo e($time_1->tuesday_from); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="tuesday_to" type="text" value="<?php echo e($time_1->tuesday_to); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label class="control-label">水</label>
            </td>
            <td>
                <input type="checkbox" name="wednesday_dayoff" value="<?php echo e($time_1->wednesday_dayoff); ?>" <?php if($time_1->wednesday_dayoff == '1'): ?> checked <?php endif; ?>  >
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="wednesday_from" type="text" value="<?php echo e($time_1->wednesday_from); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="wednesday_to" type="text" value="<?php echo e($time_1->wednesday_to); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label class="control-label">木</label>
            </td>
            <td>
                <input type="checkbox" name="thursday_dayoff" value="<?php echo e($time_1->thursday_dayoff); ?>" <?php if($time_1->thursday_dayoff == '1'): ?> checked <?php endif; ?>  >
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="thursday_from" type="text" value="<?php echo e($time_1->thursday_from); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="thursday_to" type="text" value="<?php echo e($time_1->thursday_to); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label class="control-label">金</label>
            </td>
            <td>
                <input type="checkbox" name="friday_dayoff" value="<?php echo e($time_1->friday_dayoff); ?>" <?php if($time_1->friday_dayoff == '1'): ?> checked <?php endif; ?>   >
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="friday_from" type="text" value="<?php echo e($time_1->friday_from); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="friday_to" type="text" value="<?php echo e($time_1->friday_to); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label class="control-label">土</label>
            </td>
            <td>
                <input type="checkbox" name="saturday_dayoff" value="<?php echo e($time_1->saturday_dayoff); ?>" <?php if($time_1->saturday_dayoff == '1'): ?> checked <?php endif; ?>   >
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="saturday_from" type="text" value="<?php echo e($time_1->saturday_from); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="saturday_to" type="text" value="<?php echo e($time_1->saturday_from); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label class="control-label">日</label>
            </td>
            <td>
                <input type="checkbox" name="sunday_dayoff"  value="<?php echo e($time_1->sunday_dayoff); ?>" <?php if($time_1->sunday_dayoff == '1'): ?> checked <?php endif; ?>  >
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="sunday_from" type="text" value="<?php echo e($time_1->sunday_from); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
            <td>
                <div class="input-group bootstrap-timepicker timepicker">
                    <input  name="sunday_to" type="text" value="<?php echo e($time_1->sunday_to); ?>" class="form-control inputtime">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </td>
        </tr>

        </tbody>
    </table>
 </div>
<div class="form-group">
    <div class="pull-right m-r-md">
        <label>
            <?php echo Form::open(array('url' => URL::to('/').'/shopbasic/updatebusiness/' . $time_1->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')); ?>

            <?php echo Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success disableddd',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> '営業時間を保存',
                    'data-message' 		=> 'この営業時間を保存しますか？'
            )); ?>

            <?php echo Form::close(); ?>

        </label>
        <label>
            <?php echo Form::open(array('url' => URL::to('/').'/shopbasic/updatebusiness', 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')); ?>

            <?php echo Form::hidden('method', 'DELETE'); ?>

            <input type="hidden" name="shop_id" value="<?php echo e($time_1->shop_id); ?>" >
            <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                <span class="hidden-xs hidden-sm">削除</span>',
                array('class' => 'btn btn-danger',
                    'type' => 'button' ,
                    'data-toggle' => 'modal',
                    'data-target' => '#confirmDelete',
                    'data-title' => '営業時間を削除する',
                    'data-message' => 'この営業時間を本当に削除しますか？この操作を取り消すことはできません。')); ?>

            <?php echo Form::close(); ?>

        </label>
    </div>
</div>

<?php echo Form::close(); ?>


<script>
    $("input:checkbox").change(function(e) {
        var object_name = this.name;
        object_name = object_name.substring(0,object_name.indexOf('_'));
        if(this.checked) {
            //Do stuff
            $("input[name='"+object_name+"_dayoff'").val('1');
            $("input[name='"+object_name+"_from'").parent().hide();
            $("input[name='"+object_name+"_from'").val('00:00 AM');
            $("input[name='"+object_name+"_to'").parent().hide();
            $("input[name='"+object_name+"_to'").val('00:00 AM');
        }else {
            $("input[name='"+object_name+"_dayoff'").val('0');
            $("input[name='"+object_name+"_from'").parent().show();
            $("input[name='"+object_name+"_to'").parent().show();
        }
    });
    function loadcheckbox() {
        $('input[type=checkbox]').each(function () {
            if($(this).val() == '1') {
                var object_name = this.name;
                object_name = object_name.substring(0,object_name.indexOf('_'));
                $("input[name='"+object_name+"_from'").parent().hide();
                $("input[name='"+object_name+"_to'").parent().hide();
            }
        });
    }
    loadcheckbox();
</script>