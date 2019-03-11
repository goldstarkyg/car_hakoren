<a class="btn btn-danger btn-sm" href="javascript:void(0);" title="Delete" data-toggle="modal" data-target="#modal-delete-<?php echo $data->id; ?>" data-original-title="Delete"> <span class="hidden-xs hidden-sm">削除</span>
</a>
                            
<div class="modal fade modal-danger" id="modal-delete-<?php echo $data->id; ?>" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
     
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">特別料金の削除</h4>
      </div>
      <div class="modal-body">
        <p>
         選択した特別料金を削除しますか？<br/>この操作は取り消せません。
        </p>
      </div>
      <div class="modal-footer">
        <a href="<?php echo URL::to('carbasic/carclass/deletepricecustom/' . $data->id); ?>" class="btn btn-danger">はい</a>
        <a href="javascript:void(0);" class="btn btn-default" data-dismiss="modal">いいえ</a>
      </div>
      
    </div>
  </div>
</div>