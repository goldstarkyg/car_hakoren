@extends('layouts.adminapp')

@section('template_title')
    車両タイプを作成する
@endsection

@section('template_fastload_css')
@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両タイプを作成する
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="{{URL::to('/')}}/carbasic/cartype" class="btn btn-info btn-xs pull-right">
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

                        {!! Form::open(array('action' => 'CarTypeController@store', 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal')) !!}

                        {!! csrf_field() !!}
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('category_id') ? ' has-error ' : '' }}" >
                                {!! Form::label('category_id',
                                            'カテゴリ',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        <select class="form-control" name="category_id">
                                            @foreach($category as $cate)
                                                <option value="{{$cate->id}}">{{$cate->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('category_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('category_id') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('abbriviation') ? ' has-error ' : '' }}" >
                                {!! Form::label('abbriviation',
                                            '車両タイプ略名',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::text('abbriviation', NULL,
                                                    array('id' => 'abbriviation',
                                                    'class' => 'form-control',
                                                    'placeholder' => '車両タイプの略名を入力してください')) !!}
                                    </div>
                                    @if ($errors->has('abbriviation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('abbriviation') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('name') ? ' has-error ' : '' }}" >
                                {!! Form::label('name',
                                            '車両タイプ名',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::text('name', NULL,
                                                    array('id' => 'name',
                                                    'class' => 'form-control',
                                                    'placeholder' => '車両タイプ名を入力してください')) !!}
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
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