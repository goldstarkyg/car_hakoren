@extends('layouts.adminapp')

@section('template_title')
    Create Car Invnetory
@endsection

@section('template_fastload_css')
@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>個別車両を作成する
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="{{URL::to('/')}}/carinventory/inventory" class="btn btn-info btn-xs pull-right">
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

{{--                        {!! Form::open(array('action' => URL::to('/').'/saveinventory', 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal')) !!}--}}
                        <Form action="{{URL::to('/')}}/saveinventory" method="POST" role="form" class="form-horizontal">

                        {!! csrf_field() !!}
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('model_id') ? ' has-error ' : '' }}" >
                                {!! Form::label('model_id',
                                            'モデル',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        <select class="chosen-select form-control" name="model_id" id="model_id" >
                                            <option value="0">--選択してください--</option>
                                            @foreach($models as $model)
                                                <option value="{{$model->id}}">{{$model->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('model_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('model_id') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{( $errors->has('numberplate1') || $errors->has('numberplate2') || $errors->has('numberplate3') || $errors->has('numberplate4')) ? ' has-error ' : '' }}" >
                                {!! Form::label('numberplate',
                                            '車両番号',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="col-sm-3" style="padding:0 10px;">
                                        {!! Form::text('numberplate1', NULL,
                                                    array('id' => 'numberplate1',
                                                    'class' => 'form-control',
                                                    'placeholder' => '')) !!}
                                    </div>
                                    <div class="col-sm-3" style="padding:0 10px;">
                                        {!! Form::text('numberplate2', NULL,
                                                    array('id' => 'numberplate2',
                                                    'class' => 'form-control',
                                                    'placeholder' => '')) !!}
                                    </div>
                                    <div class="col-sm-3" style="padding:0 10px;">
                                        {!! Form::text('numberplate3', NULL,
                                                    array('id' => 'numberplate3',
                                                    'class' => 'form-control',
                                                    'placeholder' => '')) !!}
                                    </div>
                                    <div class="col-sm-3" style="padding:0 10px;">
                                        {!! Form::text('numberplate4', NULL,
                                                    array('id' => 'numberplate4',
                                                    'class' => 'form-control',
                                                    'placeholder' => '')) !!}
                                    </div>
                                    @if ($errors->has('numberplate1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('numberplate1') }}</strong>
                                        </span>
                                    @endif
                                    @if ($errors->has('numberplate2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('numberplate2') }}</strong>
                                        </span>
                                    @endif
                                    @if ($errors->has('numberplate3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('numberplate3') }}</strong>
                                        </span>
                                    @endif
                                    @if ($errors->has('numberplate4'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('numberplate4') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('shortname') ? ' has-error ' : '' }}" >
                                {!! Form::label('shortname',
                                            'コード名',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    {!! Form::text('shortname', NULL,
                                                array('id' => 'shortname',
                                                'class' => 'form-control',
                                                'placeholder' => 'コード名を入力してください')) !!}
                                    @if ($errors->has('shortname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('shortname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

						<div class="form-group has-feedback row hidden">
                            <div class="{{ $errors->has('priority') ? ' has-error ' : '' }}" >
                                {!! Form::label('priority',
                                            '優先度',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                        {!! Form::text('priority', 100,
                                                    array('id' => 'priority',
                                                    'class' => 'form-control',
                                                    'placeholder' => '優先度を入力してください')) !!}
                                    @if ($errors->has('priority'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('priority') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('shop_id') ? ' has-error ' : '' }}" >
                                {!! Form::label('shop_id',
                                            '所属店舗',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        <select class="chosen-select form-control" name="shop_id" id="shop_id" >
                                            <option value="0">--選択してください--</option>
                                            @foreach($shops as $shop)
                                                <option value="{{$shop->id}}">{{$shop->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('shop_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('shop_id') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('smoke') ? ' has-error ' : '' }}" >
                                {!! Form::label('smoke',
                                            '禁煙？',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div id="smokeBtn" class="btn-group">
                                        <span class="btn btn-primary btn-md active" data-toggle="smoke" data-value="1">喫煙</span>
                                        <span class="btn btn-default btn-md notActive" data-toggle="smoke" data-value="0">禁煙</span>
                                    </div>
                                    <input type="hidden" name="smoke" id="smoke" value="1">
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('smoke') ? ' has-error ' : '' }}" >
                                {!! Form::label('max_passenger',
                                            '最大乗車人数',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    {!! Form::text('max_passenger', NULL,
                                                    array('id' => 'max_passenger',
                                                    'class' => 'form-control',
                                                    'placeholder' => '',
                                                    'required' => true)) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('current_mileage') ? ' has-error ' : '' }}" >
                                {!! Form::label('current_mileage',
                                            '現在の走行距離',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::text('current_mileage', 0,
                                                    array('id' => 'current_mileage',
                                                    'class' => 'form-control',
                                                    'placeholder' => '現在の走行距離を入力してください')) !!}
                                    </div>
                                    @if ($errors->has('current_mileage'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('current_mileage') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{--
						<div class="form-group has-feedback row">
                            <div class="{{ $errors->has('dropoff_availability') ? ' has-error ' : '' }}" >
                                {!! Form::label('dropoff_availability',
                                            '異なる返却場所',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div id="dropoffBtn" class="btn-group">
                                        <span class="btn btn-primary btn-md active" data-toggle="dropoff_availability" data-value="1">可能</span>
                                        <span class="btn btn-default btn-md notActive" data-toggle="dropoff_availability" data-value="0">不可能</span>
                                    </div>
                                    <input type="hidden" name="dropoff_availability" id="dropoff_availability" value="1">
                                </div>
                            </div>
                        </div>
						-->

                        <!--
						<div class="form-group has-feedback row" id="other_locations">
                            <div class="{{ $errors->has('dropoff_ids') ? ' has-error ' : '' }}" >
                                {!! Form::label('dropoff_ids',
                                            '返却場所',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        <input type="hidden" id="dropoff_ids" name="dropoff_ids" />
                                        <select class="chosen-select form-control" name="dropoff_id" id="dropoff_id" multiple tabindex="2">
                                            @foreach($shops as $shop)
                                                <option value="{{$shop->id}}">{{$shop->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('dropoff_id'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('dropoff_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
						--}}

                        <div class="form-group has-feedback row">
                            <div>
                                {!! Form::label('status',
                                            'ステータス',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div id="statusBtn" class="btn-group">
                                        <span class="btn btn-primary btn-md active" data-toggle="status" data-value="1">稼働中</span>
                                        <span class="btn btn-default btn-md notActive" data-toggle="status" data-value="0">非稼動</span>
                                    </div>
                                    <input type="hidden" name="status" id="staus" value="1">
                                </div>
                            </div>
                        </div>

                        {!! Form::button('<i class="fa fa-car" aria-hidden="true"></i>&nbsp;' . '新規作成する',
                                    array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right',
                                    'type' => 'submit', )) !!}
                        </Form>

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
    @include('scripts.admin.carinventory.create')
@endsection