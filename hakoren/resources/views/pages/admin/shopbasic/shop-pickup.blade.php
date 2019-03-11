{!! Form::model($shop, array('action' => array('ShopController@updatePickup', $shop->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
    {{ csrf_field() }}
    <div class="form-group">
        <label for="thumb_path" class="col-sm-2 control-label">画像</label>
        <div class="col-sm-4">
            @if($pickup->thumb_path)
                <img src="{{URL::to('/').$pickup->thumb_path}}" class="img-thumbnail" id="pickup_thumb" style="width:100px; height: auto" >
            @else
                <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" id="pickup_thumb" style="width:100px; height: auto" >
            @endif
        </div>
        <div class="col-sm-6">
            {!! Form::file('thumb_path', NULL, array('id' => 'thumb_path', 'class' => 'form-control', 'placeholder' => 'Select Image')) !!}
        </div>
    </div>

    <div class="form-group m-t-sm hidden">
        <label for="name" class="col-sm-2 control-label">タイトル</label>
        <div class="col-sm-10">
            {!! Form::text('title1', $pickup->title1, ['class' => 'form-control','required', 'id' => 'title1']) !!}
        </div>
    </div>

    <div class="form-group m-t-sm">
        <label for="name" class="col-sm-2 control-label">内容</label>
        <div class="col-sm-10">
            {!! Form::textArea('content1', $pickup->content1, ['class' => 'form-control', 'required', 'id' => 'content1']) !!}
        </div>
    </div>
    <div class="form-group m-t-sm">
        <label for="content1_en" class="col-sm-2 control-label">内容(en)</label>
        <div class="col-sm-10">
            {!! Form::textArea('content1_en', $pickup->content1_en, ['class' => 'form-control', 'required', 'id' => 'content1_en']) !!}
        </div>
    </div>

    <div class="form-group m-t-sm hidden">
        <label for="name" class="col-sm-2 control-label">Title2</label>
        <div class="col-sm-10">
            {!! Form::text('title2', $pickup->title2, ['class' => 'form-control', 'id' => 'title2']) !!}
        </div>
    </div>

    <div class="form-group m-t-sm hidden">
        <label for="name" class="col-sm-2 control-label">Content2</label>
        <div class="col-sm-10">
            {!! Form::textArea('content2', $pickup->content2, ['class' => 'form-control', 'id' => 'content2']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12 text-center">
            <label>
                {!! Form::open(array('url' => URL::to('/').'/shopbasic/shop/' . $shop->id, 'id'=>'save_form', 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
                {!! Form::button(
                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                     array(
                        'class' 		 	=> 'btn btn-success btn-save',
                        'type' 			 	=> 'button',
                        'data-target' 		=> '#confirmForm',
                        'data-modalClass' 	=> 'modal-success',
                        'data-toggle' 		=> 'modal',
                        'data-title' 		=> '送迎情報の保存',
                        'data-message' 		=> 'この送迎情報の変更を保存しますか？'
                )) !!}
                {!! Form::close() !!}
            </label>
            <label>
                {!! Form::open(array('url' => URL::to('/').'/shopbasic/deleteshoppickup/'. $shop->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                {!! Form::hidden('_method', 'DELETE') !!}
                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                    <span class="hidden-xs hidden-sm">削除</span>',
                    array('class' => 'btn btn-danger',
                        'type' => 'button' ,
                        'data-toggle' => 'modal',
                        'data-target' => '#confirmDelete',
                        'data-title' => '送迎情報の削除',
                        'data-message' => 'この送迎情報を本当に削除しますか？この操作を取り消すことはできません。　')) !!}
                {!! Form::close() !!}
            </label>
        </div>
    </div>

{!! Form::close() !!}

<script>
    $('.btn-save').click( function (e) {
        e.preventDefault();
        $('textarea[name="content1"]').val($('textarea.cke_source').val());
        $('textarea[name="content1_en"]').val($('textarea.cke_source').val());
        $('#save_form').submit();
    })
</script>