@extends('layouts.adminapp')

@section('template_title')
    車両装備を編集する
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
                <h2>車両装備を編集する: {{ $carequip->name }}
                    <a href="{{URL::to('/')}}/carbasic/carequip/{{$carequip->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/carbasic/carequip" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($carequip, array('action' => array('CarEquipController@update', $carequip->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">車両装備略名</label>
                        <div class="col-sm-9">
                            {!! Form::text('abbriviation', $carequip->abbriviation, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'abbiriviation']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">車両装備名</label>
                        <div class="col-sm-9">
                            {!! Form::text('name', $carequip->name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'name']) !!}
                        </div>
                    </div>
                    <div class="form-group has-feedback row">
                        <label for="thumbnail" class="col-md-3 control-label">装備アイコン</label>
                        <div class="col-md-9">
                            <?php
                            $thumb = ($carequip->thumbnail != '')? $carequip->thumbnail : '/images/carequipment/no-icon.png';
                            ?>
                            <img src="{{ URL::to('/').$thumb }}" class="img-thumbnail pull-left" style="height:80px;width: 80px;margin-right:20px;" >
                            <input type="file" name="thumbnail" id="thumbnail">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carbasic/carequip/' . $carequip->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
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
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carbasic/carequip/' . $carequip->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => '車両装備を削除',
                                        'data-message' => 'この車両装備を本当に削除しますか？この操作を取り消すことはできません。')) !!}
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
    <style>
        #thumbnail { margin-top: 30px; }
    </style>
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