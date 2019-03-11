<div class="modal fade modal-danger" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">削除</h4>
      </div>
      <div class="modal-body">
        <p>本当に削除しますか?</p>
      </div>
      <div class="modal-footer">
        <?php echo Form::button('<i class="fa fa-fw fa-close" aria-hidden="true"></i> 戻る', array('class' => 'btn btn-outline pull-left btn-flat', 'type' => 'button', 'data-dismiss' => 'modal' )); ?>

        <?php echo Form::button('<i class="fa fa-fw fa-trash-o" aria-hidden="true"></i> 削除', array('class' => 'btn btn-danger pull-right btn-flat', 'type' => 'button', 'id' => 'confirm' )); ?>

      </div>
    </div>
  </div>
</div>