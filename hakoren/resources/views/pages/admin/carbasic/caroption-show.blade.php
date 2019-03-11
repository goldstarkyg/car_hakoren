@extends('layouts.adminapp')

@section('template_title')
    オプションの詳細
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

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>オプションの詳細: {{ $caroption->name }}
                    <a href="{{URL::to('/')}}/carbasic/caroption/{{$caroption->id}}/edit" class="btn btn-primary btn-xs pull-right " style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
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
                    {!! Form::model($caroption, array('action' => array('CarOptionController@update', $caroption->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">オプションの略名</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$caroption->abbriviation}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">オプション名</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$caroption->name}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name_en" class="col-sm-3 control-label">オプション名(en)</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$caroption->name_en}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-3 control-label">価格</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$caroption->price}}
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
                                        </span>
                                        <input type="number" readonly name="shop_count[]" class="form-control input-number"
                                               value="{{$service_caroption->getCarOptionCount($caroption->id,$shop->id)}}" min="0" max="10">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback row">
                        <div>
                            <label for="charge-system" class="col-sm-3 control-label">料金システム</label>
                            <div class="col-md-9">
                                <div id="radioBtn" class="btn-group">
                                    <span class="btn btn-primary btn-md active" data-toggle="charge_system" data-value="one">1 time charge for 1 rental</span>
                                    <span class="btn btn-default btn-md notActive" data-toggle="charge_system" data-value="daily"> daily charge for 1 rental</span>
                                </div>
                                <input type="hidden" name="charge_system" id="charge_system" value="{{$caroption->charge_system}}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('car_models') ? ' has-error ' : '' }}">
                        <label for="charge-system" class="col-sm-3 control-label">車両クラス</label>
                        <div class="col-md-9 m-t-xs">
                            <div>
                                @foreach($classes as $class)
                                    @foreach($caroption->carOptionClass as $class)
                                        <?php
                                        if($class->class_id == $class->id)
                                        {
                                            echo $class->name.", ";
                                            break;
                                        }
                                        ?>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="max_number" class="col-sm-3 control-label">Max number</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$caroption->max_number}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Google番号</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$caroption->google_column_number}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-3 control-label">オプションタイプ</label>
                        <div class="col-sm-9 m-t-xs">
                            @if($caroption->type == '0') 有料オプション @endif
                            @if($caroption->type == '1') 無料オプション @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="{{ URL::to('/carbasic/caroption/' . $caroption->id . '/edit') }}" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
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
                                        'data-title' => 'Delete Car Option',
                                        'data-message' => 'Do you want to delete this car option?')) !!}
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
    @include('scripts.admin.carbasic.caroption-show')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

@endsection