@extends('layouts.adminapp')

@section('template_title')
    車両モデルを作成する
@endsection

@section('template_fastload_css')
@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/multiimageupload.css" rel="stylesheet">
    <style>
        .chosen-container .chosen-drop {
            border-bottom: 0;
            border-top: 1px solid #aaa;
            top: auto;
            bottom: 40px;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両モデルを作成する
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="{{URL::to('/')}}/carbasic/carmodel" class="btn btn-info btn-xs pull-right">
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

                        {!! Form::open(array('action' => 'CarModelController@store',
                                'method' => 'POST', 'role' => 'form',
                                'class' => 'form-horizontal','enctype'=>'multipart/form-data')) !!}

                        {!! csrf_field() !!}
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr class="{{ $errors->has('name') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="name" >モデル名</label>
                                    </td>
                                    <td class="col-md-9">
                                        {!! Form::text('name', NULL,
                                                    array('id' => 'name',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'モデル名を入力してください')) !!}
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="{{ $errors->has('name_en') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="name" >モデル名(en)</label>
                                    </td>
                                    <td class="col-md-9">
                                        {!! Form::text('name_en', NULL,
                                                    array('id' => 'name_en',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'モデル名を入力してください')) !!}
                                        @if ($errors->has('name_en'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name_en') }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="{{ $errors->has('thumb_path') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="thumb_path" >代表外観図</label>
                                    </td>
                                    <td class="col-md-9">
                                        {!! Form::file('thumb_path', NULL,
                                                    array('id' => 'thumb_path',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Select Image')) !!}
                                        @if ($errors->has('thumb_path'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('thumb_path') }}</strong>
                                         </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="{{ $errors->has('thumb_paths') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="thumb_paths" >関連画像(内装）</label>
                                    </td>
                                    <td class="col-md-9">
                                        <div id="filediv">
                                            <input name="file[]" type="file" id="file" class="form-control" placeholder="Select Images" />
                                        </div>
                                        {{--<input type="button" id="add_more" class="upload" value="Add More Files"/>--}}
                                        <button type="button" id="add_more" class="btn btn-secondary">追加する</button>
                                        @if ($errors->has('thumb_paths'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('thumb_paths') }}</strong>
                                         </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="{{ $errors->has('category_id') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="category_id_id" >車両カテゴリ</label>
                                    </td>
                                    <td class="col-md-9">
                                        <div>
                                            <select class="form-control" name="category_id" id="category_id" >
                                                <option value="0">--選択してください--</option>
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
                                    </td>
                                </tr>

                                <tr class="{{ $errors->has('type_id') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="type_id" >車両タイプ</label>
                                    </td>
                                    <td class="col-md-9">
                                        <div>
                                            <select class="chosen-select form-control" name="type_id" id="type_id" >
                                                {{--<option value="0">--選択してください--</option>--}}
                                                {{--@foreach($types as $type)--}}
                                                    {{--<option value="{{$type->id}}">{{$type->name}}</option>--}}
                                                {{--@endforeach--}}
                                            </select>
                                        </div>
                                        @if ($errors->has('type_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('type_id') }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="{{ $errors->has('vendor_id') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="vendor_id" >メーカー</label>
                                    </td>
                                    <td class="col-md-9">
                                        <div>
                                            <select class="chosen-select form-control" name="vendor_id" id="vendor_id" >
                                                <option value="0">--選択してください--</option>
                                                @foreach($vendors as $vendor)
                                                    <option value="{{$vendor->id}}"> {{$vendor->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('vendor_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('vendor_id') }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                {{--<tr class="{{ $errors->has('passengers') ? ' has-error ' : '' }}" >--}}
                                    {{--<td class="col-md-3 left-back" >--}}
                                        {{--<label class="control-label" for="name" >Passengers</label>--}}
                                    {{--</td>--}}
                                    {{--<td class="col-md-9">--}}
                                        {{--{!! Form::text('passengers', NULL,--}}
                                                    {{--array('id' => 'passengers',--}}
                                                    {{--'class' => 'form-control',--}}
                                                    {{--'placeholder' => 'Please enter passengers')) !!}--}}
                                        {{--@if ($errors->has('name'))--}}
                                            {{--<span class="help-block">--}}
                                                {{--<strong>{{ $errors->first('passengers') }}</strong>--}}
                                            {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                <tr class="{{ $errors->has('luggages') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="luggages" >荷物数</label>
                                    </td>
                                    <td class="col-md-9">
                                        {!! Form::text('luggages', NULL,
                                                    array('id' => 'luggages',
                                                    'class' => 'form-control',
                                                    'placeholder' => '荷物数を入力してください')) !!}
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('luggages') }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="{{ $errors->has('doors') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="doors" >ドア数</label>
                                    </td>
                                    <td class="col-md-9">
                                        {!! Form::text('doors', NULL,
                                                    array('id' => 'doors',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'ドア数を入力してください')) !!}
                                        @if ($errors->has('doors'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('doors') }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="{{ $errors->has('transmission') ? ' has-error ' : '' }}" >
                                    <td class="col-md-3 left-back" >
                                        <label class="control-label" for="transmission" >エンジン</label>
                                    </td>
                                    <td class="col-md-9">
                                        <div>
                                            <select class="form-control" name="transmission" id="transmission" >
                                                <option value="manual">Manual</option>
                                                <option value="automatic" selected >Automatic</option>
                                                <option value="semi-automatic">Semi-automatic</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('transmission'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('transmission') }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
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
        .left-back{
            background-color: #e2e1e1;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #929297;
        }
        table.table-bordered{
            border:1px solid #929297;
            margin-top:20px;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #929297;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid #929297;
        }
    </style>
    @include('scripts.admin.carbasic.carmodel-create')
@endsection