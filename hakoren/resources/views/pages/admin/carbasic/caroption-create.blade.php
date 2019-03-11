@extends('layouts.adminapp')

@section('template_title')
    オプションを作成する
@endsection

@section('template_fastload_css')
@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/multiimageupload.css" rel="stylesheet">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>オプションを作成する
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="{{URL::to('/')}}/carbasic/caroption" class="btn btn-info btn-xs pull-right">
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

                        {!! Form::open(array('action' => 'CarOptionController@store', 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal','enctype'=>'multipart/form-data')) !!}

                        {!! csrf_field() !!}
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('abbriviation') ? ' has-error ' : '' }}" >
                                {!! Form::label('abbriviation',
                                            'オプションの略名',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::text('abbriviation', NULL,
                                                    array('id' => 'abbriviation',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'オプションの略名を入力してください')) !!}
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
                                            'オプション名',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::text('name', NULL,
                                                    array('id' => 'name',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'オプション名を入力してください')) !!}
                                    </div>
                                    @if ($errors->has('name'))
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
                                            'オプション名(en)',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        {!! Form::text('name_en', NULL,
                                                    array('id' => 'name_en',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'オプション名を入力してください')) !!}
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
                            <div class="{{ $errors->has('price') ? ' has-error ' : '' }}" >
                                {!! Form::label('price',
                                            '価格',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        <input type="number" class="form-control" name="price" id="price" placeholder="価格を入力してください">
                                    </div>
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('price') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('icons') ? ' has-error ' : '' }}" >
                                {!! Form::label('icons',
                                            '画像',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div id="filediv">
                                        <input name="file[]" type="file" id="file" class="form-control" placeholder="Select Images" />
                                    </div>
                                    <button type="button" id="add_more" class="btn btn-secondary">追加する</button>
                                    @if ($errors->has('icons'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('icons') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div>
                                {!! Form::label('shop-location',
                                            '店舗',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    @foreach($shops as $shop)
                                    <div class="input-group m-b-sm">
                                        <span class="input-group-btn">
                                          <button type="button" class="btn btn-default btn-number" style="width: 250px;">
                                              {{$shop->name}}
                                          </button>
                                          <input type="hidden" name="shop_id[]" value="{{$shop->id}}" >
                                        </span>
                                        <input type="number" name="shop_count[]" class="form-control input-number"  min="0" max="10">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div>
                                {!! Form::label('charge-system',
                                            '料金システム',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div id="radioBtn" class="btn-group">
                                        <span class="btn btn-primary btn-md active" data-toggle="charge_system" data-value="one">1 time charge for 1 rental</span>
                                        <span class="btn btn-default btn-md notActive" data-toggle="charge_system" data-value="daily"> daily charge for 1 rental</span>
                                    </div>
                                    <input type="hidden" name="charge_system" id="charge_system" value="one">
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('max_number') ? ' has-error ' : '' }}" >
                                {!! Form::label('max_number',
                                            'Max Number',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div>
                                        <input type="number" class="form-control" name="max_number" id="max_number" value="1" placeholder="Max Number">
                                    </div>
                                    @if ($errors->has('max_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('max_number') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row {{ $errors->has('car_classes') ? ' has-error ' : '' }}">
                            {!! Form::label('car_class',
                                        'クラス',
                                        array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div>
                                    <input type="hidden" id="car_classes" name="car_classes" />
                                    <select class="chosen-select form-control" name="car_class" id="car_class" data-placeholder="選択してください" multiple tabindex="2">
                                        @foreach($classes as $class)
                                            <option value="{{$class->id}}">{{$class->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('car_classes'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('car_classes') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group has-feedback row {{ $errors->has('type') ? ' has-error ' : '' }}">
                            {!! Form::label('type',
                                        'オプションタイプ',
                                        array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div>
                                    <select class="form-control" name="type" id="type" data-placeholder="Choose a car type">
                                        <option value="0">有料オプション</option>
                                        <option value="1">無料オプション</option>
                                    </select>
                                </div>
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('google_column_number') ? ' has-error ' : '' }}" >
                                {!! Form::label('google_column_number',
                                            'Google番号',
                                            array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-2">
                                    <div>
                                        {!! Form::number('google_column_number', 0,
                                                    array('id' => 'google_column_number',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Google Column Number')) !!}
                                    </div>
                                    @if ($errors->has('google_column_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('google_column_number') }}</strong>
                                         </span>
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    チャイルド(22), ベビー(23), ジュニア(24), ETC(25), SNOW(26), 送迎(101), スマ(38)
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
        .chosen-container-multi .chosen-choices{
            border: 1px solid #dedede;
            background-image:none;
        }
    </style>
    @include('scripts.admin.carbasic.caroption-create')
@endsection