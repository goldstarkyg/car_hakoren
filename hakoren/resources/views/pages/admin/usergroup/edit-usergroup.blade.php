@extends('layouts.adminapp1')

@section('template_title')
    リクエストの承認 {{ $usergroup->name }}
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

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>Group: {{ $usergroup->alias }}
                    <a href="{{URL::to('/')}}/settings/usergroup" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        Go to Group List
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        {!! Form::model($usergroup, array('method' => 'PATCH', 'action' => array('UserGroupController@update', $usergroup->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label" style="padding:5px;">Group Name</label>
                            <div class="col-sm-9">
                                <div class="col-sm-6" style="padding:5px;">
                                    {!! Form::text('name', null, ['class' => 'form-control required', 'id' => 'first_name']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alias" class="col-sm-3 control-label" style="padding:5px;">Group Alias</label>
                            <div class="col-sm-9">
                                <div class="col-sm-6" style="padding:5px;">
                                    {!! Form::text('alias', null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'email']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-3 col-sm-offset-3">

                                {!! Form::button(
                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                                     array(
                                        'class' 		 	=> 'btn btn-success disableddd',
                                        'type' 			 	=> 'button',
                                        'data-target' 		=> '#confirmForm',
                                        'data-modalClass' 	=> 'modal-success',
                                        'data-toggle' 		=> 'modal',
                                        'data-title' 		=> 'Group Update',
                                        'data-message' 		=> 'update group name and alias.'
                                )) !!}

                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-form')

@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/plugins/dropzone/dropzone.js"></script>
    <script src="{{URL::to('/')}}/js/alterclass.js"></script>

    @include('scripts.form-modal-script')
    @include('scripts.check-changed')
    @include('scripts.gmaps-address-lookup-api3')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

@endsection