@extends('layouts.adminapp')

@section('template_title')
    オプションを編集する
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
@endsection
@inject('service_caroption', 'App\Http\Controllers\CarOptionController')
@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/multiimageupload.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>オプションを編集する: {{ $caroption->name }}
                    <a href="{{URL::to('/')}}/carbasic/caroption/{{$caroption->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/carbasic/caroption" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($caroption, array('method' => 'PATCH', 'action' => array('CarOptionController@update', $caroption->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">オプションの略名</label>
                        <div class="col-sm-9">
                            {!! Form::text('abbriviation', $caroption->abbriviation, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'abbiriviation']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">オプション名</label>
                        <div class="col-sm-9">
                            {!! Form::text('name', $caroption->name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name_en" class="col-sm-3 control-label">オプション名(en)</label>
                        <div class="col-sm-9">
                            {!! Form::text('name_en', $caroption->name_en, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name_en']) !!}
                        </div>
                    </div>
                    <div class="form-group has-feedback row">
                        <label for="price" class="col-sm-3 control-label">価格</label>
                        <div class="col-sm-9">
                            {!! Form::text('price', $caroption->price, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'price']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="icons" class="col-sm-3 control-label">画像</label>
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
                    <div class="form-group has-feedback row">
                        <div>
                            <label for="shop-location" class="col-sm-3 control-label">店舗</label>
                            <div class="col-md-9">
                                @foreach($shops as $shop)
                                    <div class="input-group m-b-sm">
                                        <span class="input-group-btn">
                                          <button type="button" class="btn btn-default btn-number" style="width: 250px;">
                                              {{$shop->name}}
                                          </button>
                                          <input type="hidden" name="shop_id[]" value="{{$shop->id}}" >
                                        </span>
                                        <input type="number" name="shop_count[]" class="form-control input-number"
                                               value="{{$service_caroption->getCarOptionCount($caroption->id,$shop->id)}}" min="0" max="10">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback row">
                        <div>
                            {!! Form::label('charge-system',
                                        '料金システム',
                                        array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div id="radioBtn" class="btn-group">
                                    <span class="btn btn-primary btn-md active" data-toggle="charge_system" data-value="one">1 time charge for 1 rental</span>
                                    <span class="btn btn-default btn-md notActive" data-toggle="charge_system" data-value="daily"> daily charge for 1 rental</span>
                                </div>
                                <input type="hidden" name="charge_system" id="charge_system" value="{{$caroption->charge_system}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback row">
                        <label for="price" class="col-sm-3 control-label">Max Number</label>
                        <div class="col-sm-9">
                            {!! Form::text('max_number', $caroption->max_number, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'max_number']) !!}
                        </div>
                    </div>
                    <div class="form-group has-feedback row {{ $errors->has('car_classes') ? ' has-error ' : '' }}">
                        {!! Form::label('car_class',
                                    '車両クラス',
                                    array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div>
                                <input type="hidden" id="car_classes" name="car_classes"  />
                                <select class="chosen-select form-control" name="car_class" id="car_class" data-placeholder="Choose a Car Class" multiple tabindex="2">
                                        @foreach($classes as $class)
                                            <?php $select = ''; ?>
                                            @foreach($caroption->carOptionClass as $cl)
                                                <?php
                                                if($cl->class_id == $class->id)
                                                {
                                                    $select = 'selected' ;
                                                    break;
                                                }
                                                ?>
                                            @endforeach
                                            <option value="{{ $class->id }}" <?php echo $select ?> >{{ $class->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                            @if ($errors->has('car_classes'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('car_classes') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group has-feedback row {{ $errors->has('type') ? ' has-error ' : '' }}">
                        {!! Form::label('type',
                                    'オプションタイプ',
                                    array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div>
                                <select class="form-control" name="type" id="type" data-placeholder="Choose a Option Type">
                                    <option value="0" @if($caroption->type == '0') selected @endif > 有料オプション</option>
                                    <option value="1" @if($caroption->type == '1') selected @endif > 無料オプション</option>
                                </select>
                            </div>
                            @if ($errors->has('type'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="google_column_number" class="col-sm-3 control-label">Google 番号</label>
                        <div class="col-sm-2">
                            {!! Form::number('google_column_number', $caroption->google_column_number, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'google_column_number']) !!}
                        </div>
                        <div class="col-sm-7">
                            チャイルド(22), ベイビー(23), ジュニア(24), ETC(25), SNOW(26), 送迎(101), スマ(38)
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carbasic/caroption/' . $caroption->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
                                {!! Form::button(
                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                                     array(
                                        'class' 		 	=> 'btn btn-success disableddd',
                                        'type' 			 	=> 'button',
                                        'data-target' 		=> '#confirmForm',
                                        'data-modalClass' 	=> 'modal-success',
                                        'data-toggle' 		=> 'modal',
                                        'data-title' 		=> 'オプションを保存',
                                        'data-message' 		=> 'このオプションの変更を保存します'
                                )) !!}
                                {!! Form::close() !!}
                            </label>
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carbasic/caroption/' . $caroption->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => 'オプションを削除',
                                        'data-message' => 'このオプションを本当に削除しますか？この操作を取り消すことはできません。')) !!}
                                {!! Form::close() !!}
                            </label>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-form')
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/plugins/dropzone/dropzone.js"></script>
    <script src="{{URL::to('/')}}/js/alterclass.js"></script>

    @include('scripts.form-modal-script')
    @include('scripts.delete-modal-script')
    @include('scripts.admin.carbasic.caroption-edit')
    <script>
        var public_url = '{{URL::to('/')}}';
        var thumbs = [];
        var thumbs_ids = [];
        @foreach($thumbnails as $thumb)
            thumbs.push(public_url+'{{$thumb->thumb_path}}');
        thumbs_ids.push('{{$thumb->id}}');
        @endforeach
    </script>
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>
    <script src="{{URL::to('/')}}/js/multiimageupload.js"></script>

@endsection