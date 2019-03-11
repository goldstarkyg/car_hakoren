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
                    <a href="{{URL::to('/')}}/carbasic/carpassenger/{{$carpassenger->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
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
                        <label for="name" class="col-sm-3 control-label">名前</label>
                        <div class="col-sm-9">
                            {!! Form::text('name', $carpassenger->name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name_en" class="col-sm-3 control-label">名前(en)</label>
                        <div class="col-sm-9">
                            {!! Form::text('name_en', $carpassenger->name_en, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name_en']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="max_passenger" class="col-sm-3 control-label">Max</label>
                        <div class="col-sm-9">
                            {!! Form::number('max_passenger', $carpassenger->max_passenger, ['class' => 'form-control required', 'id' => 'max_passenger']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="min_passenger" class="col-sm-3 control-label">Min</label>
                        <div class="col-sm-9">
                            {!! Form::number('min_passenger', $carpassenger->min_passenger, ['class' => 'form-control required', 'id' => 'min_passenger']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="show_order" class="col-sm-3 control-label">Order</label>
                        <div class="col-sm-9">
                            {!! Form::number('show_order', $carpassenger->show_order, ['class' => 'form-control required', 'id' => 'show_order']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carbasic/carpassenger/' . $carpassenger->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
                                {!! Form::button(
                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                                     array(
                                        'class' 		 	=> 'btn btn-success disableddd',
                                        'type' 			 	=> 'button',
                                        'data-target' 		=> '#confirmForm',
                                        'data-modalClass' 	=> 'modal-success',
                                        'data-toggle' 		=> 'modal',
                                        'data-title' 		=> trans('modals.edit_user__modal_text_confirm_title'),
                                        'data-message' 		=> trans('modals.edit_user__modal_text_confirm_message')
                                )) !!}
                                {!! Form::close() !!}
                            </label>
                            {{--<label>--}}
                                {{--{!! Form::open(array('url' => URL::to('/').'/carbasic/carpassenger/' . $carpassenger->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}--}}
                                {{--{!! Form::hidden('_method', 'DELETE') !!}--}}
                                {{--{!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>--}}
                                    {{--<span class="hidden-xs hidden-sm">削除</span>',--}}
                                    {{--array('class' => 'btn btn-danger',--}}
                                        {{--'type' => 'button' ,--}}
                                        {{--'data-toggle' => 'modal',--}}
                                        {{--'data-target' => '#confirmDelete',--}}
                                        {{--'data-title' => '免責補償を削除',--}}
                                        {{--'data-message' => 'この免責補償を本当に削除しますか？この操作を取り消すことはできません。')) !!}--}}
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