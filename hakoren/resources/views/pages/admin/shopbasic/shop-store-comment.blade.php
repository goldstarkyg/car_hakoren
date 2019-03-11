{!! Form::model($shop, array('action' => array('ShopController@updatecomment', $shop->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
    {{ csrf_field() }}
   

    <div class="form-group m-t-sm">
        <label for="name" class="col-sm-2 control-label">コメント</label>
        <div class="col-sm-10">
            {!! Form::textArea('comment', $shop->comment, ['class' => 'form-control', 'required', 'id' => 'comment']) !!}
        </div>
    </div>
    <div class="form-group m-t-sm">
        <label for="comment_en" class="col-sm-2 control-label">コメント(en)</label>
        <div class="col-sm-10">
            {!! Form::textArea('comment_en', $shop->comment_en, ['class' => 'form-control', 'required', 'id' => 'comment_en']) !!}
        </div>
    </div>
     <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <label>
                {!! Form::open(array('url' => URL::to('/').'/shopbasic/shop/' . $shop->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
                {!! Form::button(
                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                     array(
                        'class'             => 'btn btn-success disableddd',
                        'type'              => 'button',
                        'data-target'       => '#confirmForm',
                        'data-modalClass'   => 'modal-success',
                        'data-toggle'       => 'modal',
                        'data-title'        => '店舗を保存',
                        'data-message'      => 'この店舗の変更を保存しますか？'
                )) !!}
                {!! Form::close() !!}
            </label>
            <label>
                {!! Form::open(array('url' => URL::to('/').'/shopbasic/shop/' . $shop->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                {!! Form::hidden('_method', 'DELETE') !!}
                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                    <span class="hidden-xs hidden-sm">削除</span>',
                    array('class' => 'btn btn-danger',
                        'type' => 'button' ,
                        'data-toggle' => 'modal',
                        'data-target' => '#confirmDelete',
                        'data-title' => '店舗を削除',
                        'data-message' => 'この店舗を本当に削除しますか？この操作を取り消すことはできません。')) !!}
                {!! Form::close() !!}
            </label>
        </div>
    </div>
{!! Form::close() !!}
