    <?php echo Form::model($carclass, array('action' => array('CarClassController@update', $carclass->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')); ?>


    <?php echo e(csrf_field()); ?>

    <div class="form-group m-t-sm">
        <label for="car_class_priority" class="col-sm-3 control-label">クラスの表示優先度</label>
        <div class="col-sm-9">
            <?php echo Form::number('car_class_priority', $carclass->car_class_priority, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'car_class_priority']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-3 control-label">クラス名</label>
        <div class="col-sm-9">
            <?php echo Form::text('name', $carclass->name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name']); ?>

        </div>
    </div>


    <div class="form-group">
        <label for="name" class="col-sm-3 control-label">関連する店舗<br/>(1店舗のみ)</label>
        <div class="col-sm-9">
            <?php 
                $car_shop_id = \DB::table('car_class')->select('car_shop_name')->where('id',Request::segment(3))->first();
             ?>
            <?php if(!empty($car_shop_list)): ?>
                   <select name="car_shop_name" class="form-control select2">
                        <?php $__currentLoopData = $car_shop_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if($car_shop_id->car_shop_name == $shop->id) { echo "selected"; } ?> value="<?php echo e($shop->id); ?>"><?php echo e($shop->name); ?></option>                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                   </select>
            <?php endif; ?>              
        </div>
    </div>


    <div class="form-group">
        <label for="thumb_path" class="col-sm-3 control-label">クラス画像</label>
        <div class="col-sm-3">
            <?php if($carclass->thumb_path): ?>
                <img src="<?php echo e(URL::to('/').$carclass->thumb_path); ?>" class="img-thumbnail" id="class_thumb" style="width:100px; height: auto" >
            <?php else: ?>
                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" id="class_thumb" style="width:100px; height: auto" >
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
        <label for="thumb_path" class="col-sm-3 control-label">クラス画像(en)</label>
        <div class="col-sm-3">
            <?php if($carclass->thumb_path_en): ?>
                <img src="<?php echo e(URL::to('/').$carclass->thumb_path_en); ?>" class="img-thumbnail" id="class_thumb" style="width:100px; height: auto" >
            <?php else: ?>
                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" id="class_thumb" style="width:100px; height: auto" >
            <?php endif; ?>
        </div>
        <div class="col-sm-6">
            <?php echo Form::file('thumb_path_en', NULL,
                                   array('id' => 'thumb_path_en',
                                   'class' => 'form-control',
                                   'placeholder' => 'Select Image')); ?>

        </div>
    </div>

    <div class="form-group">
        <label for="thumb_paths" class="col-sm-3 control-label">車両画像<br/>（内装）</label>
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
        <label for="abbriviation" class="col-sm-3 control-label">クラスの略名</label>
        <div class="col-sm-9">
            <?php echo Form::text('abbriviation', $carclass->abbriviation, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'abbriviation']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-3 control-label">クラスの特徴</label>
        <div class="col-sm-9">
            <?php echo Form::textarea('description', $carclass->description, ['class' => 'form-control required', 'rows'=> '2','cols'=>'40',
                                'placeholder' => '', 'id' => 'description']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-3 control-label">スタッフコメント</label>
        <div class="col-sm-9">
            <?php echo Form::textarea('staff_comment', $carclass->staff_comment, ['class' => 'form-control required', 'rows'=> '2','cols'=>'40',
                                'placeholder' => '', 'id' => 'staff_comment']); ?>

        </div>
    </div>

    <div class="form-group">
        <label for="models" class="col-sm-3 control-label">定員数</label>
        <div class="col-md-9">
            <div>
                <?php
                $tagArray = [];
                foreach($carclass->carClassPassengerTags as $ptag){
                    array_push($tagArray, $ptag->passenger_tag);
                }
                ?>
                <input type="hidden" id="car_psgtags" name="car_psgtags" value="<?php echo e(implode(',', $tagArray)); ?>" />
                <select class="chosen-select form-control" name="car_psgtag" id="car_psgtag" data-placeholder="Choose passenger tags" multiple tabindex="2">
                    <?php $__currentLoopData = $psgtags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $select = ''; ?>
                        <?php $__currentLoopData = $carclass->carClassPassengerTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ptag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($ptag->passenger_tag == $tag->id): ?> <?php $select = 'selected' ?> <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tag->id); ?>" <?php echo e($select); ?>><?php echo e($tag->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php if($errors->has('passenger')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('passenger')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="models" class="col-sm-3 control-label">モデル</label>
        <div class="col-md-9">
            <div>
                <input type="hidden" id="car_models" name="car_models"  />
                <select class="chosen-select form-control" name="car_model" id="car_model" data-placeholder="モデルを選ぶ" multiple tabindex="2">
                    <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $select = ''; ?>
                        <?php $__currentLoopData = $carclass->carClassModel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($mo->model_id == $model->id): ?> <?php $select = 'selected' ?> <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($model->id); ?>" <?php echo e($select); ?> ><?php echo e($model->name); ?></option>
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="models" class="col-sm-3 control-label">推奨クラス</label>
        <div class="col-md-9">
            <div>
                <input type="hidden" id="suggest_list" name="suggest_list"  />
                <select class="chosen-select form-control" name="car_suggest_classes" id="car_suggest_classes" data-placeholder="クラスを選択してください" multiple tabindex="3">
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $select = ''; ?>
                        <?php $__currentLoopData = $suggests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($cls->id == $sg->suggest_class_id): ?> <?php $select = 'selected' ?> <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cls->id); ?>" <?php echo e($select); ?> ><?php echo e($cls->name); ?></option>
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="status" class="col-sm-3 control-label">検索対象</label>
        <div class="col-sm-9">
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
                <?php echo Form::open(array('url' => URL::to('/').'/carbasic/carclass/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')); ?>

                <?php echo Form::button(
                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                     array(
                        'class' 		 	=> 'btn btn-success disableddd btn-save',
                        'type' 			 	=> 'button',
                        'data-target' 		=> '#confirmForm',
                        'data-modalClass' 	=> 'modal-success',
                        'data-toggle' 		=> 'modal',
                        'data-title' 		=> '車両クラスを保存',
                        'data-message' 		=> 'この車両クラスの変更を保存しますか？'
                )); ?>

                <?php echo Form::close(); ?>

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
                        'data-title' => '車両クラスを削除',
                        'data-message' => 'この車両クラスを本当に削除しますか？この操作を取り消すことはできません。')); ?>

                <?php echo Form::close(); ?>

            </label>
        </div>
    </div>

    <?php echo Form::close(); ?>


    <script>
        $('.btn-save').click( function (e) {
            e.preventDefault();
            $('textarea[name="description"]').val($('textarea.cke_source').val());
            $('textarea[name="staff_comment"]').val($('textarea.cke_source').val());
            $('#save_form').submit();
        })
    </script>

<script type="text/javascript">
    $(".select2").select2();
</script>
