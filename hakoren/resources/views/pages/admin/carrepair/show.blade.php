@extends('layouts.adminapp')

@section('template_title')
    車両修理の詳細
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet">

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">

    <?php
    if(is_null($repair))
        if($repair->kind == 1){
            $kind = '修理';
        } else if($repair->kind == 2){
            $kind = '代用1';
        } else {
            $kind = '代用2';
        }

    else
        $kind = '修理(代用)';
    ?>

    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両{{$kind}}の詳細
                    <a href="{{URL::to('/')}}/carrepair/{{$repair->id}}/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="{{URL::to('/')}}/carrepair" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($repair, array('action' => array('CarRepairController@update', $repair->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="model_id" class="col-sm-3 control-label">モデル</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                @foreach($models as $model)
                                    @if($model->id == $inventory->model_id) {{$model->name}} @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">車両番号</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$inventory->numberplate1}} {{$inventory->numberplate2}} {{$inventory->numberplate3}} <span style="font-size:120%;">{{$inventory->numberplate4}}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">コード名</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$inventory->shortname}}
                        </div>
                    </div>

                    {{--<div class="form-group">--}}
                        {{--<label for="name" class="col-sm-3 control-label">優先度</label>--}}
                        {{--<div class="col-sm-9 m-t-xs">--}}
                            {{--{{$inven->priority}}--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">所属店舗</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                @foreach($shops as $shop)
                                    @if($shop->id == $inventory->shop_id) {{$shop->name}} @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="smoke" class="col-sm-3 control-label">禁煙？</label>
                            <div class="col-md-9">
                                <div class="btn-group">
                                    <span class="btn btn-md @if($inventory->smoke == 1) btn-primary @else btn-default @endif">喫煙</span>
                                    <span class="btn btn-md @if($inventory->smoke == 0) btn-primary @else btn-default @endif">禁煙</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">タイプ</label>
                        <div class="col-sm-9">
                            <div class="btn-group">
                            <span class="btn btn-md @if($repair->kind == 1) btn-primary @else btn-default @endif">修理/車検</span>
                            <span class="btn btn-md @if($repair->kind == 2) btn-primary @else btn-default @endif">代車特約</span>
                            <span class="btn btn-md @if($repair->kind == 3) btn-primary @else btn-default @endif">事故代車</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">現在の走行距離</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$inventory->current_mileage}}
                        </div>
                    </div>
					
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">{{$kind}}期間</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                {{$repair->begin_date}} ~ {{$repair->end_date}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">{{$kind}}料金</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                {{number_format($repair->price)}}円
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="status" class="col-sm-3 control-label">ステータス</label>
                            <div class="col-md-9">
                                <div id="statusBtn" class="btn-group">
                                    <span class="btn btn-md @if($repair->status == 1) btn-primary @else btn-default @endif">処理前</span>
                                    <span class="btn btn-md @if($repair->status == 2) btn-primary @else btn-default @endif">処理中</span>
                                    <span class="btn btn-md @if($repair->status == 3) btn-primary @else btn-default @endif">処理終了</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="status" class="col-sm-3 control-label">メモ</label>
                            <div class="col-md-9">
                                {!! Form::textarea('memo', $repair->memo, ['class'=>'form-control', 'rows' => '4', 'readonly'=>'true']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="{{ URL::to('/carrepair/' . $repair->id . '/edit') }}" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
                            </label>
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carrepair/' . $repair->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => '情報を削除する',
                                        'data-message' => 'この情報を本当に削除しますか？この操作を取り消すことはできません。')) !!}
                                {!! Form::close() !!}
                            </label>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    <style>
        #info-table th { vertical-align: middle;text-align: center;}
    </style>

    @include('modals.modal-form')
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/plugins/dropzone/dropzone.js"></script>
    <script src="{{URL::to('/')}}/js/alterclass.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/dataTables/dataTables.bootstrap.min.js"></script>

    @include('scripts.form-modal-script')
    @include('scripts.check-changed')
    @include('scripts.gmaps-address-lookup-api3')
{{--    @include('scripts.admin.carinventory.show')--}}
    @include('scripts.delete-modal-script')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

    <script>

    </script>

@endsection