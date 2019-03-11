<?php $__env->startSection('template_title'); ?>
    記事投稿時の通知｜文章テンプレート
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_fastload_css'); ?>

<?php $__env->stopSection(); ?>

<?php 

$articleActive = [
'checked' => '',
'value' => 0,
'true'	=> '',
'false'	=> 'checked'
];


 ?>


<?php $__env->startSection('content'); ?>
    
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        記事投稿時の通知｜文章テンプレート
                        <a href="/mailtemplate" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                            <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                            一覧へ戻る
                        </a>
                    </div>

                    <form action="/mailtemplate/save" method="post" id="editmail" enctype="multipart/form-data">

                    <?php echo csrf_field(); ?>

                    <input type="hidden" id="tempid" name="tempid" value="">
                    <div class="panel-body">
                        <div class="form-group has-feedback row <?php echo e($errors->has('mailname') ? ' has-error ' : ''); ?>">
                            <?php echo Form::label('mailname', 'メールタイプ' , array('class' => 'col-md-2 control-label'));; ?>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <?php echo Form::text('mailname', old('mailname'), array('id' => 'mailname', 'class' => 'form-control', 'placeholder' => 'メールタイプを入力してください。 Ex. user_register_book_user')); ?>

                                    <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                                </div>
                                <?php if($errors->has('mailname')): ?>
                                    <span class="help-block">
										<strong><?php echo e($errors->first('mailname')); ?></strong>
									</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group has-feedback row <?php echo e($errors->has('subject') ? ' has-error ' : ''); ?>">
                            <?php echo Form::label('subject', '件名' , array('class' => 'col-md-2 control-label'));; ?>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <?php echo Form::text('subject', old('subject'), array('id' => 'subject', 'class' => 'form-control', 'placeholder' => '件名を入力してください')); ?>

                                    <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                                </div>
                                <?php if($errors->has('subject')): ?>
                                    <span class="help-block">
										<strong><?php echo e($errors->first('subject')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group has-feedback row <?php echo e($errors->has('sender') ? ' has-error ' : ''); ?>">
                            <?php echo Form::label('sender', '差出人' , array('class' => 'col-md-2 control-label'));; ?>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <?php echo Form::text('sender', old('sender'), array('id' => 'sender', 'class' => 'form-control', 'placeholder' => 'Please input the sender')); ?>

                                    <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                                </div>
                                <?php if($errors->has('sender')): ?>
                                    <span class="help-block">
										<strong><?php echo e($errors->first('sender')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group has-feedback row <?php echo e($errors->has('content') ? ' has-error ' : ''); ?>">
                            <?php echo Form::label('content', '内容' , array('class' => 'col-md-2 control-label'));; ?>

                            <div class="col-md-10">
                                <input type="hidden" id="content" name="content" value="">
                                <div class="ibox-content no-padding">
                                    <div class="summernote">
                                        <?php echo old('conent'); ?>

                                    </div>
                                </div>
                                <?php if($errors->has('content')): ?>
                                    <span class="help-block">
										<strong><?php echo e($errors->first('content')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                            
                            
                                
                                    
                                
                                
                                    
                                
                                
                                    
                                
                            
                        
                        <input type="hidden" id="mailaddr" name="mailaddr" value="">

                    </div>
                    <div class="panel-footer">

                        <div class="row">

                            <div class="col-sm-6 hidden">
                                <?php echo Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i>' . 'テスト送信', array('class' => 'btn btn-primary btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSubscribe', 'onclick'=>'clickUpdate()')); ?>

                            </div>
                            <div class="col-sm-6 col-sm-offset-3">
                                <?php echo Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i>' . '変更を保存する', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'onclick'=>'clickUpdate()', 'data-title' => Lang::get('modals.edit_user__modal_text_confirm_title'), 'data-message' => 'メールテンプレートの変更を保存しますか？')); ?>

                            </div>
                        </div>
                    </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('modals.modal-save', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="modal fade modal-success modal-save" id="confirmSubscribe" role="dialog" aria-labelledby="confirmSaveLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">テスト送信したいメールアドレスを入力してください </h4>
                </div>
                <div class="modal-body">
                    <div class="mailtitle" style="margin-bottom:10px;">
                        <?php echo Form::label('mailaddr1', 'メールアドレス' , array('class' => 'col-md-3 control-label'));; ?>

                        <div class="col-md-9">
                            <div class="input-group">
                                <?php echo Form::text('mailaddr1', null, array('id' => 'mailaddr1', 'class' => 'form-control', 'placeholder' => 'メールアドレスを入力してください')); ?>

                                <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="mailcontent">
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo Form::button('<i class="fa fa-fw '.Lang::get('modals.confirm_modal_button_cancel_icon').'" aria-hidden="true"></i> ' . Lang::get('modals.confirm_modal_button_cancel_text'), array('class' => 'btn btn-outline pull-left btn-flat', 'type' => 'button', 'data-dismiss' => 'modal' )); ?>

                    <?php echo Form::button('<i class="fa fa-fw '.Lang::get('modals.confirm_modal_button_save_icon').'" aria-hidden="true"></i> ' . '送信', array('class' => 'btn btn-success pull-right btn-flat', 'onclick'=>'sendSubscribe()', 'type' => 'button', 'id' => 'confirm' )); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>

    <style>
        .text-wrap{
            width: 100% !important;
        }
        .ibox-content {
            border:1px solid #CCCCCC;
        }
        /*button[data-event=codeview] {
            display:none !important;
        }*/
    </style>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.check-changed', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.toggleStatus', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script src="<?php echo e(URL::to('/')); ?>/js/jquery.uploadPreview.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/summernote/summernote.min.js"></script>
    <link rel="stylesheet" href="<?php echo e(URL::to('/')); ?>/textext/css/textext.core.css" type="text/css">
    <link rel="stylesheet" href="<?php echo e(URL::to('/')); ?>/textext/css/textext.plugin.tags.css" type="text/css">
    <link rel="stylesheet" href="<?php echo e(URL::to('/')); ?>/textext/css/textext.plugin.autocomplete.css" type="text/css">
    <link rel="stylesheet" href="<?php echo e(URL::to('/')); ?>/textext/css/textext.plugin.focus.css" type="text/css">
    <link rel="stylesheet" href="<?php echo e(URL::to('/')); ?>/textext/css/textext.plugin.prompt.css" type="text/css">
    <link rel="stylesheet" href="<?php echo e(URL::to('/')); ?>/textext/css/textext.plugin.arrow.css" type="text/css">
    <script src="<?php echo e(URL::to('/')); ?>/textext/js/textext.core.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/textext/js/textext.plugin.tags.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/textext/js/textext.plugin.autocomplete.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/textext/js/textext.plugin.suggestions.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/textext/js/textext.plugin.filter.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/textext/js/textext.plugin.focus.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/textext/js/textext.plugin.prompt.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/textext/js/textext.plugin.ajax.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/textext/js/textext.plugin.arrow.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote();
        });
        function clickUpdate(){
            var text = $(".summernote").code();
            console.log(text);
            $('#content').val(text);
        }
        function clickSubscribeUpdate(){
            var text = $(".summernote").code();
            console.log(text);
            $('#content').val(text);
            var sender = $('#sender').val();
            var title = $('#subject').val();
            $('.mailcontent').html(sender+'<br><br>'+text);
            $('.mailtitle').html(title);
        }
        function clickUsername(username){
            $('.summernote').summernote('code', username);
        }
        function sendSubscribe(){
            var mailaddr = $("#mailaddr1").val();
            $('#mailaddr').val(mailaddr);
            $('#editmail').submit();
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp_calendar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>