@extends('layouts.adminapp')

@section('template_title')
    定員数
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

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>定員数: {{ $carpassenger->name }}
                    <a href="{{URL::to('/')}}/carbasic/carpassenger/{{$carpassenger->id}}/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="{{URL::to('/')}}/carbasic/carpassenger" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($carpassenger, array('method' => 'PATCH', 'action' => array('CarPassengerController@update', $carpassenger->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">名前</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carpassenger->name}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">名前(en)</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carpassenger->name_en}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Max</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carpassenger->max_passenger}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Min</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carpassenger->min_passenger}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Order</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$carpassenger->show_order}}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="{{ URL::to('/carbasic/carpassenger/' . $carpassenger->id . '/edit') }}" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
                            </label>
                            {{--<label>--}}
                                {{--{!! Form::open(array('url' => URL::to('/').'/carbasic/carpasssenger/' . $carpassenger->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}--}}
                                {{--{!! Form::hidden('_method', 'DELETE') !!}--}}
                                {{--{!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>--}}
                                    {{--<span class="hidden-xs hidden-sm">削除</span>',--}}
                                    {{--array('class' => 'btn btn-danger',--}}
                                        {{--'type' => 'button' ,--}}
                                        {{--'data-toggle' => 'modal',--}}
                                        {{--'data-target' => '#confirmDelete',--}}
                                        {{--'data-title' => 'Delete Car Type',--}}
                                        {{--'data-message' => 'Do you want to delete this this passenger?')) !!}--}}
                                {{--{!! Form::close() !!}--}}
                            {{--</label>--}}
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
    @include('scripts.check-changed')
    @include('scripts.gmaps-address-lookup-api3')
    @include('scripts.admin.member_edit')
    @include('scripts.delete-modal-script')
    <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

@endsection