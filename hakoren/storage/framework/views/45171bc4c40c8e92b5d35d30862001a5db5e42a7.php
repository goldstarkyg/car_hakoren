<?php echo Form::model($shop, array('action' => array('ShopController@update', $shop->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')); ?>


<?php echo e(csrf_field()); ?>

    <div class="form-group m-t-sm">
        <label for="name" class="col-sm-3 control-label">店舗名</label>
        <div class="col-sm-9">
            <?php echo Form::text('name', $shop->name, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'name']); ?>

        </div>
    </div>
    <div class="form-group m-t-sm">
        <label for="name" class="col-sm-3 control-label">店舗名(en)</label>
        <div class="col-sm-9">
            <?php echo Form::text('name_en', $shop->name_en, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'name_en']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="abbiriviation" class="col-sm-3 control-label">略称</label>
        <div class="col-sm-9">
            <?php echo Form::text('abbriviation', $shop->abbriviation, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'abbriviation']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="abbiriviation" class="col-sm-3 control-label">スラッグ</label>
        <div class="col-sm-9">-
            <?php echo Form::text('slug', $shop->slug, ['class' => 'form-control', 'required'=>'true', 'placeholder' => 'Please enter only character and - without space', 'id' => 'slug']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="abbiriviation" class="col-sm-3 control-label">ショップインデックス</label>
        <div class="col-sm-9">
            <?php echo Form::text('shop_number', $shop->shop_number, ['class' => 'form-control', 'required'=>'true', 'placeholder' => 'Please enter only character and - without space', 'id' => 'shop_number']); ?>

            福岡空港店: 1, 那覇空港店: 2, 鹿児島店:3
        </div>
    </div>
    <div class="form-group">
        <label for="thumb_path" class="col-sm-3 control-label">店舗の画像</label>
        <div class="col-sm-3">
            <?php if($shop->thumb_path): ?>
                <img src="<?php echo e(URL::to('/').$shop->thumb_path); ?>" class="img-thumbnail" style="width:100px; height: auto" >
            <?php else: ?>
                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
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
        <label for="thumb_path_en" class="col-sm-3 control-label">店舗の画像(en)</label>
        <div class="col-sm-3">
            <?php if($shop->thumb_path_en): ?>
                <img src="<?php echo e(URL::to('/').$shop->thumb_path_en); ?>" class="img-thumbnail" style="width:100px; height: auto" >
            <?php else: ?>
                <img src="<?php echo e(URL::to('/')); ?>/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
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
        <label for="phone" class="col-sm-3 control-label">電話番号</label>
        <div class="col-sm-9">
            <?php echo Form::text('phone', $shop->phone, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'phone']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="postal" class="col-sm-3 control-label">郵便番号</label>
        <div class="col-sm-9">
            <?php echo Form::text('postal', $shop->postal, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'postal']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="postal_en" class="col-sm-3 control-label">郵便番号(en)</label>
        <div class="col-sm-9">
            <?php echo Form::text('postal_en', $shop->postal_en, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'postal_en']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="prefecture" class="col-sm-3 control-label">都道府県</label>
        <div class="col-sm-9">
            <?php echo Form::text('prefecture', $shop->prefecture, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'prefecture']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="prefecture_en" class="col-sm-3 control-label">都道府県(en)</label>
        <div class="col-sm-9">
            <?php echo Form::text('prefecture_en', $shop->prefecture_en, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'prefecture_en']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="city" class="col-sm-3 control-label">市町村</label>
        <div class="col-sm-9">
            <?php echo Form::text('city', $shop->city, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'city']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="city_en" class="col-sm-3 control-label">市町村(en)</label>
        <div class="col-sm-9">
            <?php echo Form::text('city_en', $shop->city_en, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'city_en']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="address1" class="col-sm-3 control-label">住所 1</label>
        <div class="col-sm-9">
            <?php echo Form::text('address1', $shop->address1, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'address1']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="address1_en" class="col-sm-3 control-label">住所 1(en)</label>
        <div class="col-sm-9">
            <?php echo Form::text('address1_en', $shop->address1_en, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'address1_en']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="address2" class="col-sm-3 control-label">住所 2</label>
        <div class="col-sm-9">
            <?php echo Form::text('address2', $shop->address2, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'address2']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="address2_en" class="col-sm-3 control-label">住所 2(en)</label>
        <div class="col-sm-9">
            <?php echo Form::text('address2_en', $shop->address2_en, ['class' => 'form-control', 'required'=>'true', 'placeholder' => '', 'id' => 'address2_en']); ?>

        </div>
    </div>
    <div class="form-group">
        <label for="type_id" class="col-sm-3 control-label">主任</label>
        <div class="col-sm-9">
            <div>
                <select class="chosen-select form-control" name="member_id" id="member_id" >
                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $select = '';
                        if($member->id == $shop->member_id ) $select="selected";
                        ?>
                        <option value="<?php echo e($member->id); ?>" <?php echo e($select); ?>><?php echo e($member->first_name); ?> <?php echo e($member->last_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <label>
                <?php echo Form::open(array('url' => URL::to('/').'/shopbasic/shop/' . $shop->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')); ?>

                <?php echo Form::button(
                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                     array(
                        'class' 		 	=> 'btn btn-success disableddd',
                        'type' 			 	=> 'button',
                        'data-target' 		=> '#confirmForm',
                        'data-modalClass' 	=> 'modal-success',
                        'data-toggle' 		=> 'modal',
                        'data-title' 		=> '店舗を保存',
                        'data-message' 		=> 'この店舗の変更を保存しますか？'
                )); ?>

                <?php echo Form::close(); ?>

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
                        'data-title' => '店舗を削除',
                        'data-message' => 'この店舗を本当に削除しますか？この操作を取り消すことはできません。')); ?>

                <?php echo Form::close(); ?>

            </label>
        </div>
    </div>

<?php echo Form::close(); ?>