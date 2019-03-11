@extends('layouts.adminapp')

@section('template_title')
    店舗詳細
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
    <link href="{{URL::to('/')}}/css/multiimageupload.css" rel="stylesheet">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>店舗詳細: {{ $shop->name }}
                    <a href="{{URL::to('/')}}/shopbasic/shop/{{$shop->id}}/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="{{URL::to('/')}}/shopbasic/shop" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧に戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($shop, array('action' => array('ShopController@update', $shop->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">店舗名</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$shop->name}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">略称</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$shop->abbriviation}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">スラッグ</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$shop->slug}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">店舗画像</label>
                        <div class="col-sm-9">
                            @if($shop->thumb_path)
                                <img src="{{URL::to('/').$shop->thumb_path}}" class="img-thumbnail" style="width:100px; height: auto" >
                            @else
                                <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">電話番号</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$shop->phone}}
                        </div>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label class="col-sm-3 control-label">Business hour 1</label>--}}
                        {{--<div class="col-sm-9 m-t-xs">--}}
                            {{--Test Business 1--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-sm-3 control-label">Business hour 2</label>--}}
                        {{--<div class="col-sm-9 m-t-xs">--}}
                            {{--business hour 2--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">郵便番号</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$shop->postal}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">都道府県</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$shop->prefecture}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">市町村</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$shop->city}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">住所 1</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$shop->address1}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">住所 2</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$shop->address2}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">主任</label>
                        <div class="col-sm-9 m-t-xs">
                            @foreach($members as $member)
                                @if($member->id == $shop->member_id )
                                    {{$member->first_name}} {{$member->last_name}}
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="{{ URL::to('/shopbasic/shop/' . $shop->id . '/edit') }}" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
                            </label>
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/shopbasic/shop/' . $shop->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => 'この店舗を完全に削除する',
                                        'data-message' => 'この店舗および関連情報を全て削除しますか?<br/>この操作は取り消すことができません。')) !!}
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
    @include('scripts.delete-modal-script')
    @include('scripts.admin.shopbasic.shop-edit')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

@endsection