    {!! Form::model($carclass, array('action' => array('CarClassController@update', $carclass->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

    {{ csrf_field() }}
    <div class="form-group m-t-sm">
        <label for="car_class_priority" class="col-sm-3 control-label">クラスの表示優先度</label>
        <div class="col-sm-9">
            {!! Form::number('car_class_priority', $carclass->car_class_priority, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'car_class_priority']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-3 control-label">クラス名</label>
        <div class="col-sm-9">
            {!! Form::text('name', $carclass->name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name']) !!}
        </div>
    </div>


    <div class="form-group">
        <label for="name" class="col-sm-3 control-label">関連する店舗<br/>(1店舗のみ)</label>
        <div class="col-sm-9">
            <?php 
                $car_shop_id = \DB::table('car_class')->select('car_shop_name')->where('id',Request::segment(3))->first();
             ?>
            @if(!empty($car_shop_list))
                   <select name="car_shop_name" class="form-control select2">
                        @foreach ($car_shop_list as $shop)
                            <option <?php if($car_shop_id->car_shop_name == $shop->id) { echo "selected"; } ?> value="{{ $shop->id }}">{{ $shop->name }}</option>                 @endforeach
                   </select>
            @endif              
        </div>
    </div>


    <div class="form-group">
        <label for="thumb_path" class="col-sm-3 control-label">クラス画像</label>
        <div class="col-sm-3">
            @if($carclass->thumb_path)
                <img src="{{URL::to('/').$carclass->thumb_path}}" class="img-thumbnail" id="class_thumb" style="width:100px; height: auto" >
            @else
                <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" id="class_thumb" style="width:100px; height: auto" >
            @endif
        </div>
        <div class="col-sm-6">
            {!! Form::file('thumb_path', NULL,
                                   array('id' => 'thumb_path',
                                   'class' => 'form-control',
                                   'placeholder' => 'Select Image')) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="thumb_path" class="col-sm-3 control-label">クラス画像(en)</label>
        <div class="col-sm-3">
            @if($carclass->thumb_path_en)
                <img src="{{URL::to('/').$carclass->thumb_path_en}}" class="img-thumbnail" id="class_thumb" style="width:100px; height: auto" >
            @else
                <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" id="class_thumb" style="width:100px; height: auto" >
            @endif
        </div>
        <div class="col-sm-6">
            {!! Form::file('thumb_path_en', NULL,
                                   array('id' => 'thumb_path_en',
                                   'class' => 'form-control',
                                   'placeholder' => 'Select Image')) !!}
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
            {!! Form::text('abbriviation', $carclass->abbriviation, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'abbriviation']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-3 control-label">クラスの特徴</label>
        <div class="col-sm-9">
            {!! Form::textarea('description', $carclass->description, ['class' => 'form-control required', 'rows'=> '2','cols'=>'40',
                                'placeholder' => '', 'id' => 'description']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-3 control-label">スタッフコメント</label>
        <div class="col-sm-9">
            {!! Form::textarea('staff_comment', $carclass->staff_comment, ['class' => 'form-control required', 'rows'=> '2','cols'=>'40',
                                'placeholder' => '', 'id' => 'staff_comment']) !!}
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
                <input type="hidden" id="car_psgtags" name="car_psgtags" value="{{ implode(',', $tagArray) }}" />
                <select class="chosen-select form-control" name="car_psgtag" id="car_psgtag" data-placeholder="Choose passenger tags" multiple tabindex="2">
                    @foreach($psgtags as $tag)
                        <?php $select = ''; ?>
                        @foreach($carclass->carClassPassengerTags as $ptag)
                            @if($ptag->passenger_tag == $tag->id) <?php $select = 'selected' ?> @endif
                        @endforeach
                        <option value="{{ $tag->id }}" {{ $select }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('passenger'))
                    <span class="help-block">
                        <strong>{{ $errors->first('passenger') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="models" class="col-sm-3 control-label">モデル</label>
        <div class="col-md-9">
            <div>
                <input type="hidden" id="car_models" name="car_models"  />
                <select class="chosen-select form-control" name="car_model" id="car_model" data-placeholder="モデルを選ぶ" multiple tabindex="2">
                    @foreach($models as $model)
                        <?php $select = ''; ?>
                        @foreach($carclass->carClassModel as $mo)
                            @if($mo->model_id == $model->id) <?php $select = 'selected' ?> @endif
                        @endforeach
                        <option value="{{ $model->id }}" {{$select}} >{{ $model->name }}</option>
                        {{--{{$service_caroption->getCarModelName($mo->model_id)}}--}}
                    @endforeach
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
                    @foreach($classes as $cls)
                        <?php $select = ''; ?>
                        @foreach($suggests as $sg)
                            @if($cls->id == $sg->suggest_class_id) <?php $select = 'selected' ?> @endif
                        @endforeach
                        <option value="{{ $cls->id }}" {{$select}} >{{ $cls->name }}</option>
                        {{--{{$service_caroption->getCarModelName($mo->model_id)}}--}}
                    @endforeach
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
            <input type="hidden" name="status" id="status" value="{{$carclass->status}}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <label>
                {!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
                {!! Form::button(
                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                     array(
                        'class' 		 	=> 'btn btn-success disableddd btn-save',
                        'type' 			 	=> 'button',
                        'data-target' 		=> '#confirmForm',
                        'data-modalClass' 	=> 'modal-success',
                        'data-toggle' 		=> 'modal',
                        'data-title' 		=> '車両クラスを保存',
                        'data-message' 		=> 'この車両クラスの変更を保存しますか？'
                )) !!}
                {!! Form::close() !!}
            </label>
            <label>
                {!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                {!! Form::hidden('_method', 'DELETE') !!}
                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                    <span class="hidden-xs hidden-sm">削除</span>',
                    array('class' => 'btn btn-danger',
                        'type' => 'button' ,
                        'data-toggle' => 'modal',
                        'data-target' => '#confirmDelete',
                        'data-title' => '車両クラスを削除',
                        'data-message' => 'この車両クラスを本当に削除しますか？この操作を取り消すことはできません。')) !!}
                {!! Form::close() !!}
            </label>
        </div>
    </div>

    {!! Form::close() !!}

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
