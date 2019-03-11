@extends('layouts.adminapp')

@section('template_title')
    車両クラスの詳細
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
@endsection
@inject('service_caroption', 'App\Http\Controllers\CarClassController')
@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <link href="{{URL::to('/')}}/css/multiimageupload.css" rel="stylesheet">
    <style>
        .class_model {
            font-weight:300;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両クラスの詳細: {{ $carclass->name }}
                    <a href="{{URL::to('/')}}/carbasic/carclass/{{$carclass->id}}/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="{{URL::to('/')}}/carbasic/carclass" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($carclass, array('method' => 'PATCH', 'action' => array('CarClassController@update', $carclass->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラスの表示優先度</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carclass->car_class_priority}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラス名</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carclass->name}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラス画像</label>
                        <div class="col-sm-9 m-t-xs">
                            @if($carclass->thumb_path)
                                <img src="{{URL::to('/').$carclass->thumb_path}}" class="img-thumbnail" style="width:100px; height: auto" >
                            @else
                                <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラス画像(en)</label>
                        <div class="col-sm-9 m-t-xs">
                            @if($carclass->thumb_path_en)
                                <img src="{{URL::to('/').$carclass->thumb_path_en}}" class="img-thumbnail" style="width:100px; height: auto" >
                            @else
                                <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">関連画像<br/>（内装）</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                @foreach($thumbnails as $thumb)
                                    <img class="col-md-3 img-thumbnail" src="{{URL::to('/').$thumb->thumb_path}}" style="height:100px;width:auto" >
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラスの略名</label>
                        <div class="col-sm-9 m-t-xs">
                            {{ $carclass->abbriviation }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラスの特徴</label>
                        <div class="col-sm-9 m-t-xs">
                            {!! $carclass->description !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">スタッフコメント</label>
                        <div class="col-sm-9 m-t-xs">
                            {!! $carclass->staff_comment !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">推奨クラス</label>
                        <div class="col-sm-9 m-t-xs">
                            @foreach($suggests as $key=>$sg)
                                {{$sg->name}}
                                @if($key < count($suggests) -1) / @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">モデル</label>
                        <div class="col-sm-9 m-t-xs">
                            <div style="margin-top: 10px;">
                                @foreach($carclass->carClassModel as $mo)
                                    <?php $model = $service_caroption->getCarModelInform($mo->model_id)?>
                                    @if($model)
                                    <div  style="padding-top:5px;border-top:1px solid #999696;">
                                        <div class="form-group" >
                                            <label class="col-sm-3 class_model">モデル名 :</label>
                                            <label class="col-sm-9 class_model">
                                                {{$model->name}}
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 class_model">画像 :</label>
                                            <label class="col-sm-9 class_model">
                                                @if(!$model->thumb_path)
                                                    <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" style="width: 100px; height: auto" >
                                                @else
                                                    <img src="{{URL::to('/').$model->thumb_path}}" class="img-thumbnail" style="width: 100px; height: auto"  >
                                                @endif
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 class_model">車両タイプ :</label>
                                            <label class="col-sm-9 class_model" style="text-align: left">
                                                {{$model->type_name}}
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 class_model">メーカー :</label>
                                            <label class="col-sm-9 class_model">
                                                {{$model->vendor_name}}
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 class_model">関連店舗 :</label>
                                            <label class="col-sm-9 class_model">
                                                @foreach($model->cars as $car)
                                                   <p> {{$car}} </p>
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">検索対象</label>
                        <div class="col-sm-9 m-t-xs">
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
                                <a class="btn btn-info " href="{{ URL::to('/carbasic/carclass/' . $carclass->id . '/edit') }}" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
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
                                        'data-title' => 'クラスを削除する',
                                        'data-message' => '本当にこのクラスを削除しますか？<br/>この操作は取り消せません。')) !!}
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
    {{--@include('scripts.admin.carbasic.carclass-edit')           --}}
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>
    <script>
        $(document).ready(function() {

            //select charge system
            var sel = $('#status').val();
            var tog = $('#statusBtn span').data('toggle');
            $('#' + tog).val(sel);
            $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
            $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');

        });



    </script>
@endsection