<?php $__env->startSection('template_title'); ?>
    店舗の新規登録
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_fastload_css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
    <style>
        .chosen-container .chosen-drop {
            border-bottom: 0;
            border-top: 1px solid #aaa;
            top: auto;
            bottom: 40px;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>店舗の新規登録
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="<?php echo e(URL::to('/')); ?>/shopbasic/shop" class="btn btn-info btn-xs pull-right">
                                <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                                一覧へ戻る
                            </a>
                        </div>
                    </div>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default">

                <div class="panel-body">

                    <?php echo Form::open(array('action' => 'ShopController@store',
                            'method' => 'POST', 'role' => 'form',
                            'class' => 'form-horizontal','enctype'=>'multipart/form-data')); ?>


                    <?php echo csrf_field(); ?>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr class="<?php echo e($errors->has('name') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >店舗名</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('name', NULL,
                                                array('id' => 'name',
                                                'class' => 'form-control',
                                                'placeholder' => '例） 福岡空港店')); ?>

                                    <?php if($errors->has('name')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('name')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('name') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name_en" >店舗名(en)</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('name_en', NULL,
                                                array('id' => 'name_en',
                                                'class' => 'form-control',
                                                'placeholder' => '例） 福岡空港店')); ?>

                                    <?php if($errors->has('name_en')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('name_en')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('abbriviation') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >Abbreviation</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('abbriviation', NULL,
                                                array('id' => 'abbriviation',
                                                'class' => 'form-control',
                                                'placeholder' => '略称を入力してください')); ?>

                                    <?php if($errors->has('abbriviation')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('abbriviation')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('slug') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >Slug</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('slug', NULL,
                                                array('id' => 'slug',
                                                'class' => 'form-control',
                                                'placeholder' => '例） fukuoka-airport')); ?>

                                    <?php if($errors->has('slug')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('slug')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('shop_number') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="shop_number" >ショップインデックス</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('shop_number', NULL,
                                                array('id' => 'shop_number',
                                                'class' => 'form-control',
                                                'placeholder' => '')); ?>

                                    <?php if($errors->has('shop_number')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('shop_number')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                    福岡空港店: 1, 那覇空港店: 2
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('thumb_path') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="thumb_path" >店舗の画像</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::file('thumb_path', NULL,
                                                array('id' => 'thumb_path',
                                                'class' => 'form-control',
                                                'placeholder' => '店舗の代表的な画像をアップロードして下さい')); ?>

                                    <?php if($errors->has('thumb_path')): ?>
                                        <span class="help-block">
                                        <strong><?php echo e($errors->first('thumb_path')); ?></strong>
                                     </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('thumb_path_en') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="thumb_path_en" >店舗の画像(en)</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::file('thumb_path_en', NULL,
                                                array('id' => 'thumb_path_en',
                                                'class' => 'form-control',
                                                'placeholder' => '店舗の代表的な画像をアップロードして下さい')); ?>

                                    <?php if($errors->has('thumb_path_en')): ?>
                                        <span class="help-block">
                                        <strong><?php echo e($errors->first('thumb_path_en')); ?></strong>
                                     </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('phone') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >電話番号</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('phone', NULL,
                                                array('id' => 'phone',
                                                'class' => 'form-control',
                                                'placeholder' => '000-000-0000')); ?>

                                    <?php if($errors->has('phone')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('phone')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('postal') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="postal" >郵便番号</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('postal', NULL,
                                                array('id' => 'postal',
                                                'class' => 'form-control',
                                                'placeholder' => '郵便番号をご入力ください')); ?>

                                    <?php if($errors->has('postal')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('postal')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('Prefecture') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="Prefecture" >都道府県</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('Prefecture', NULL,
                                                array('id' => 'Prefecture',
                                                'class' => 'form-control',
                                                'placeholder' => '都道府県をご入力ください')); ?>

                                    <?php if($errors->has('Prefecture')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('Prefecture')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('city') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="city" >市</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('city', NULL,
                                                array('id' => 'city',
                                                'class' => 'form-control',
                                                'placeholder' => '市町村をご入力ください')); ?>

                                    <?php if($errors->has('city')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('city')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('address1') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="address1" >住所 1</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('address1', NULL,
                                                array('id' => 'address1',
                                                'class' => 'form-control',
                                                'placeholder' => '住所 1 をご入力ください')); ?>

                                    <?php if($errors->has('address1')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('address1')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('address2') ? ' has-error ' : ''); ?>" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="address2" >住所 2</label>
                                </td>
                                <td class="col-md-9">
                                    <?php echo Form::text('address2', NULL,
                                                array('id' => 'address2',
                                                'class' => 'form-control',
                                                'placeholder' => '住所 2 をご入力ください')); ?>

                                    <?php if($errors->has('address2')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('address2')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo e($errors->has('member_id') ? ' has-error ' : ''); ?>"  >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="member_id" >主任</label>
                                </td>
                                <td class="col-md-9">
                                    <div>
                                        <select class="chosen-select form-control" name="type_id" id="member_id" >
                                            <option value="0">--Select--</option>
                                            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($member->id); ?>"><?php echo e($member->first_name); ?> <?php echo e($member->last_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <?php if($errors->has('member_id')): ?>
                                        <span class="help-block">
                                            <strong><?php echo e($errors->first('member_id')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php echo Form::button('<i class="fa fa-car" aria-hidden="true"></i>&nbsp;' . '店舗を登録する',
                                array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right',
                                'type' => 'submit', )); ?>

                    <?php echo Form::close(); ?>


                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
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
    <?php echo $__env->make('scripts.admin.shopbasic.shop-create', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>