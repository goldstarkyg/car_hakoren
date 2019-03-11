@extends('layouts.adminapp1')

@section('template_title')
    Tag management
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
                <h2>Name: {{ $tag->name }}
                    <a href="{{URL::to('/')}}/tag/{{$tag->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/tag" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($tag, array('method' => 'PATCH', 'action' => array('MemberTagController@update', $tag->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">Abbriviation</label>
                        <div class="col-sm-9">
                            {!! Form::text('abbriviation', $tag->abbriviation, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'abbiriviation']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            {!! Form::text('name', $tag->name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            {!! Form::number('status', $tag->status, ['class' => 'form-control required', 'max' => '2','min'=>'1', 'id' => 'status']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/tag/' . $tag->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
                                {!! Form::button(
                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                                     array(
                                        'class' 		 	=> 'btn btn-success disableddd',
                                        'type' 			 	=> 'button',
                                        'data-target' 		=> '#confirmForm',
                                        'data-modalClass' 	=> 'modal-success',
                                        'data-toggle' 		=> 'modal',
                                        'data-title' 		=> trans('tag.modal_title'),
                                        'data-message' 		=> trans('tag.model_update_message')
                                )) !!}
                                {!! Form::close() !!}
                            </label>
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/tag/' . $tag->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => trans('tag.modal_title'),
                                        'data-message' => trans('tag.model_delete_message'))) !!}
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
    @include('scripts.check-changed')
    @include('scripts.gmaps-address-lookup-api3')
    @include('scripts.admin.member_edit')
    @include('scripts.delete-modal-script')
    <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

@endsection