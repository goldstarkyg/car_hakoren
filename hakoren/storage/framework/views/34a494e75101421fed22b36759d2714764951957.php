<div class="modal fade modal-warning modal-save" id="modalError" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('modals.error_modal_default_title'); ?> </h4><!--<?php echo e(Lang::get('modals.error_modal_default_title')); ?>-->
      </div>
      <div class="modal-body">
        <p class="error-text"><?php echo e(Lang::get('modals.error_modal_default_message')); ?> </p>
      </div>
      <div class="modal-footer text-center">
        <?php echo Form::button('<i class="fa fa-fw '.Lang::get('modals.confirm_modal_button_cancel_icon').'" aria-hidden="true"></i> ' . Lang::get('modals.error_modal_button_cancel_text'), array('class' => 'btn btn-outline btn-flat', 'type' => 'button', 'data-dismiss' => 'modal' )); ?>

        
      </div>
    </div>
  </div>
</div>