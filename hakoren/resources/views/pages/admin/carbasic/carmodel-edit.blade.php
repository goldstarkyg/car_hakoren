@extends('layouts.adminapp')

@section('template_title')
    車両モデルを編集する
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
@endsection
@inject('service', 'App\Http\Controllers\CarModelController')
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
                <h2>車両モデルを編集する: {{ $carmodel->name }}
                    <a href="{{URL::to('/')}}/carbasic/carmodel/{{$carmodel->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/carbasic/carmodel" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($carmodel, array('method' => 'PATCH', 'action' => array('CarModelController@update', $carmodel->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">モデル名</label>
                        <div class="col-sm-9">
                            {!! Form::text('name', $carmodel->name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">モデル名(en)</label>
                        <div class="col-sm-9">
                            {!! Form::text('name_en', $carmodel->name_en, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name_en']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">代表外観図</label>
                        <div class="col-sm-3">
                            @if(!$carmodel->thumb_path)
                                <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" >
                            @else
                                <img src="{{URL::to('/').$carmodel->thumb_path}}" class="img-thumbnail" >
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
                        <label for="thumb_paths" class="col-sm-3 control-label">関連画像(内装）</label>
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
                        <label for="type_id" class="col-sm-3 control-label">車両カテゴリ</label>
                        <div class="col-sm-9">
                            <div>
                                <select class="chosen-select form-control" name="category_id" id="category_id" >
                                    <option value="0">--選択してください--</option>
                                    @foreach($category as $cate)
                                        <?php
                                        $select = '';
                                        if($cate->id == $carmodel->category_id ) $select="selected";
                                        ?>
                                        <option value="{{$cate->id}}" {{$select}}>{{$cate->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type_id" class="col-sm-3 control-label">車両タイプ</label>
                        <div class="col-sm-9">
                            <div>
                                <select class="chosen-select form-control" name="type_id" id="type_id" >
                                    @foreach($types as $type)
                                        <?php
                                        $select = '';
                                        if($type->id == $carmodel->type_id ) $select="selected";
                                        ?>
                                        <option value="{{$type->id}}" {{$select}}>{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor_id" class="col-sm-3 control-label">メーカー</label>
                        <div class="col-sm-9">
                            <div>
                                <select class="chosen-select form-control" name="vendor_id" id="vendor_id" >
                                    @foreach($vendors as $vendor)
                                        <?php
                                        $select = '';
                                        if($vendor->id == $carmodel->vendor_id ) $select="selected";
                                        ?>
                                        <option value="{{$vendor->id}}" {{$select}}>{{$vendor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label for="passengers" class="col-sm-3 control-label">Passengers</label>--}}
                        {{--<div class="col-sm-9">--}}
                            {{--{!! Form::text('passengers', $carmodel->passengers, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'passengers']) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label for="luggages" class="col-sm-3 control-label">荷物</label>
                        <div class="col-sm-9">
                            {!! Form::text('luggages', $carmodel->luggages, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'luggages']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="doors" class="col-sm-3 control-label">ドア数</label>
                        <div class="col-sm-9">
                            {!! Form::text('doors', $carmodel->doors, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'doors']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="transmission" class="col-sm-3 control-label">エンジン</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="transmission" id="transmission" >
                                @if(!$carmodel->transmission)
                                    <option value="automatic"
                                            @if($carmodel->transmission == 'automatic') selected @endif >Automatic
                                    </option>
                                @else
                                    <option value="manual"
                                            @if($carmodel->transmission == 'manual') selected @endif >Manual
                                    </option>
                                    <option value="automatic"
                                            @if($carmodel->transmission == 'automatic') selected @endif >Automatic
                                    </option>
                                    <option value="semi-automatic"
                                            @if($carmodel->transmission == 'semi-automatic') selected @endif >Semi-automatic
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="insurance_2" class="col-sm-3 control-label">喫煙（登録車両数）</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$service->getnumberSmoking($carmodel->id,1)}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="insurance_2" class="col-sm-3 control-label">禁煙（登録車両数）</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$service->getnumberSmoking($carmodel->id,0)}}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carbasic/carmodel/' . $carmodel->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
                                {!! Form::button(
                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i><span class="hidden-xs hidden-sm"> '.trans('profile.submitButton').'</span> ' ,
                                     array(
                                        'class' 		 	=> 'btn btn-success disableddd',
                                        'type' 			 	=> 'button',
                                        'data-target' 		=> '#confirmForm',
                                        'data-modalClass' 	=> 'modal-success',
                                        'data-toggle' 		=> 'modal',
                                        'data-title' 		=> '車両モデルを保存',
                                        'data-message' 		=> 'この車両モデルの変更を保存しますか？'
                                )) !!}
                                {!! Form::close() !!}
                            </label>
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carbasic/carmodel/' . $carmodel->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => '車両モデルを削除',
                                        'data-message' => 'この車両モデルを本当に削除しますか？この操作を取り消すことはできません。')) !!}
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
    <script>
        var public_url = '{{URL::to('/')}}';
        var thumbs = [];
        var thumbs_ids = [];
        @foreach($thumbnails as $thumb)
            thumbs.push(public_url+'{{$thumb->thumb_path}}');
            thumbs_ids.push('{{$thumb->id}}');
        @endforeach
    </script>
    @include('scripts.form-modal-script')
    @include('scripts.delete-modal-script')
    @include('scripts.admin.carbasic.carmodel-edit')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

@endsection