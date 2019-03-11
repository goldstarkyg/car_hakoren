@extends('layouts.adminapp')

@section('template_title')
    定員数
@endsection

@section('template_fastload_css')
@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2> 定員数
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="{{URL::to('/')}}/carbasic/carpassenger" class="btn btn-info btn-xs pull-right">
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

                        {!! Form::open(array('action' => 'CarPassengerController@store', 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal')) !!}

                        {!! csrf_field() !!}
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('name') ? ' has-error ' : '' }}" >
                                {!! Form::label('name',
                                            '名前',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::text('name', NULL,
                                                    array('id' => 'name',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Please enter name!')) !!}
                                    </div>
                                    @if ($errors->has('abbriviation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('name_en') ? ' has-error ' : '' }}" >
                                {!! Form::label('name_en',
                                            '名前(en)',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::text('name_en', NULL,
                                                    array('id' => 'name_en',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Please enter english name')) !!}
                                    </div>
                                    @if ($errors->has('name_en'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name_en') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('max_passenger') ? ' has-error ' : '' }}" >
                                {!! Form::label('max_passenger',
                                            'Max',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::number('max_passenger', NULL,
                                                    array('id' => 'max_passenger',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Please enter english name')) !!}
                                    </div>
                                    @if ($errors->has('max_passenger'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('max_passenger') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('min_passenger') ? ' has-error ' : '' }}" >
                                {!! Form::label('min_passenger',
                                            'Min',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::number('min_passenger', NULL,
                                                    array('id' => 'min_passenger',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Please enter english name')) !!}
                                    </div>
                                    @if ($errors->has('min_passenger'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('min_passenger') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('show_order') ? ' has-error ' : '' }}" >
                                {!! Form::label('show_order',
                                            'Show otder',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::number('show_order', NULL,
                                                    array('id' => 'show_order',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Please enter english name')) !!}
                                    </div>
                                    @if ($errors->has('show_order'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('show_order') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {!! Form::button('<i class="fa fa-car" aria-hidden="true"></i>&nbsp;' . '新規作成する',
                                    array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right',
                                    'type' => 'submit', )) !!}

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    <style type="text/css">
        .datepicker {
            background: #fff;
            border: 1px solid #555;
        }
        .chosen-container-multi .chosen-choices{
            border: 1px solid #dedede;
            background-image:none;
        }
    </style>
    @include('scripts.admin.member_create')
@endsection