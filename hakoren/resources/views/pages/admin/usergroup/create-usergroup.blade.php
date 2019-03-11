@extends('layouts.adminapp1')

@section('template_title')
    Add New User Group
@endsection

@section('template_fastload_css')
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>Add New User Group
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="{{URL::to('/')}}/settings/usergroup" class="btn btn-info btn-xs pull-right">
                                <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                                一覧へ戻る
                            </a>
                        </div>
                    </div>
                </h2>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default">

                    <div class="panel-body">

                        {!! Form::open(array('action' => 'UserGroupController@store', 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal')) !!}

                        {!! csrf_field() !!}

                        <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                            {!! Form::label('group-name', 'Group Name', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-6">
                                <div>
                                    {!! Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'Group Name')) !!}
                                </div>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback row {{ $errors->has('alias') ? ' has-error ' : '' }}">
                            {!! Form::label('group-alias', 'Group Alias', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-6">
                                <div>
                                    {!! Form::text('alias', NULL, array('id' => 'alias', 'class' => 'form-control', 'placeholder' => 'Group Alias')) !!}
                                </div>
                                @if ($errors->has('alias'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('alias') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {!! Form::button('Create User Group',
                                    array('class' => 'btn btn-success btn-flat margin-bottom-1  col-md-6 col-md-offset-3 ',
                                    'type' => 'submit', )) !!}

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
@endsection