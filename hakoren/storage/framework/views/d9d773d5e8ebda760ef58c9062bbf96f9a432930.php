<?php $__env->startSection('template_title'); ?>
    店舗情報の編集
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
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/timepicker/bootstrap-timepicker.css" rel="stylesheet">

    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/css/home.css">
    <link href="<?php echo e(URL::to('/')); ?>/css/navtab.css" rel="stylesheet">
    <style>
        .chosen-container .chosen-drop {
            border-bottom: 0;
            border-top: 1px solid #aaa;
            top: auto;
            bottom: 40px;
        }
        form .active { color: #585757; }
        .bootstrap-timepicker-widget{ background-color: #ffffff; }
        .tab-font{ font-size: 14px !important; }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>編集: <?php echo e($shop->name); ?>

                    <a href="<?php echo e(URL::to('/')); ?>/shopbasic/shop/<?php echo e($shop->id); ?>" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="<?php echo e(URL::to('/')); ?>/shopbasic/shop" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="panel with-nav-tabs panel-default shadow-box">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li <?php if(session('tag') == 'address' || !session('tag')): ?> class="active" <?php endif; ?> >
                            <a class="tab-font" data-toggle="tab" href="#address">一般情報</a></li>
                        <li <?php if(session('tag') == 'hour1'): ?> class="active" <?php endif; ?> >
                            <a class="tab-font" data-toggle="tab" href="#time1">営業時間</a></li>
                        <li <?php if(session('tag') == 'hour2'): ?> class="active" <?php endif; ?>>
                            <a class="tab-font" data-toggle="tab" href="#time2">時間変更</a></li>
                        <li <?php if(session('tag') == 'hour2'): ?> class="active" <?php endif; ?>>
                            <a class="tab-font" data-toggle="tab" href="#time3">店舗コメント</a></li>
                        <li <?php if(session('tag') == 'pickup'): ?> class="active" <?php endif; ?>>
                            <a class="tab-font" data-toggle="tab" href="#pickup">送迎情報</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div id="address" class="tab-pane fade <?php if(session('tag') == 'address' || !session('tag')): ?> in active <?php endif; ?> ">
                            <?php echo $__env->make('pages.admin.shopbasic.shop-address', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>

                        <div id="time1" class="tab-pane fade <?php if(session('tag') == 'hour1'): ?> in active <?php endif; ?>">
                            <?php echo $__env->make('pages.admin.shopbasic.shop-business', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>

                        <div id="time2" class="tab-pane fade <?php if(session('tag') == 'hour2'): ?> in active <?php endif; ?> ">
                            <?php echo $__env->make('pages.admin.shopbasic.shop-business-custom', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>

                        <div id="time3" class="tab-pane fade <?php if(session('tag') == 'hour2'): ?> in active <?php endif; ?> ">
                            <?php echo $__env->make('pages.admin.shopbasic.shop-store-comment', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>

                        <div id="pickup" class="tab-pane fade <?php if(session('tag') == 'pickup'): ?> in active <?php endif; ?> ">
                            <?php echo $__env->make('pages.admin.shopbasic.shop-pickup', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>
                    </div>
                    <!---->



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
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/timepicker/bootstrap-timepicker.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/home.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('input.inputtime').timepicker();
        });
    </script>

	<script src="<?php echo e(URL::to('/')); ?>/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
	<script type="text/javascript">
        CKEDITOR.replace( 'content1',
        {
            customConfig : 'config-code-editor.js',
            toolbar : 'simple'
        })
        CKEDITOR.replace( 'content1_en',
            {
                customConfig : 'config-code-editor.js',
                toolbar : 'simple'
            })
	</script> 
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>