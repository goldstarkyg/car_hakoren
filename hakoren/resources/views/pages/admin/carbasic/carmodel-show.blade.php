@extends('layouts.adminapp')

@section('template_title')
    車両モデルの詳細
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

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" typ  e="text/css" href="{{URL::to('/')}}/css/home.css">
    <link href="{{URL::to('/')}}/css/multiimageupload.css" rel="stylesheet">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両モデルの詳細: {{ $carmodel->name }}
                    <a href="{{URL::to('/')}}/carbasic/carmodel/{{$carmodel->id}}/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="{{URL::to('/')}}/carbasic/carmodel" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧に戻る
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
                        <label class="col-sm-3 control-label">モデル名</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carmodel->name}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">モデル名(en)</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carmodel->name_en}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">代表外観図</label>
                        <div class="col-sm-9 m-t-xs">
                            @if(!$carmodel->thumb_path)
                                <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" style="width:100px;height:auto" >
                            @else
                                <img src="{{URL::to('/').$carmodel->thumb_path}}" class="img-thumbnail" style="width:100px;height:auto" >
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">関連画像（内装）</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                @foreach($thumbnails as $thumb)
                                    <img class="col-md-3 img-thumbnail" src="{{URL::to('/').$thumb->thumb_path}}" style="height:100px;width:auto" >
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type_id" class="col-sm-3 control-label">車両カテゴリ</label>
                        <div class="col-sm-9 m-t-xs">
                            @if(!empty($carmodel->category_id)) {{$carmodel->category->name}} @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">車両タイプ</label>
                        <div class="col-sm-9 m-t-xs">
                           @if(!empty($carmodel->type_id)) {{$carmodel->type->name}} @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">メーカー</label>
                        <div class="col-sm-9 m-t-xs">
                            @if($carmodel->vendor_id != 0){{$carmodel->vendor->name}} @endif
                        </div>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label class="col-sm-3 control-label">定員数</label>--}}
                        {{--<div class="col-sm-9 m-t-xs">--}}
                            {{--{{$carmodel->passengers}}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">荷物数</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carmodel->luggages}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">ドア数</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carmodel->doors}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">エンジン</label>
                        <div class="col-sm-9 m-t-xs">
                            {{ucfirst($carmodel->transmission)}}
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
                                <a class="btn btn-info " href="{{ URL::to('/carbasic/carmodel/' . $carmodel->id . '/edit') }}" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
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
                                        'data-title' => 'Delete Car Model',
                                        'data-message' => 'Do you want to delete this car model?')) !!}
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
    @include('scripts.admin.carbasic.carmodel-edit')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

@endsection