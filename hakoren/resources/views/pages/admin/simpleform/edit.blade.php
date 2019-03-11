@extends('layouts.adminapp')

@section('template_title')
    リクエストの承認 {{ $user_name }}
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
        .bootstrap-select > .btn {
            background-color: #ffffff;
            color: #505050;
        }
        .bootstrap-select > .btn:hover {
            background-color: #e6e4e4;
            color: #505050;
        }
    </style>
@endsection

@section('content')

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href='{{URL::to('/')}}/css/bootstrap-select.css' rel='stylesheet' type='text/css'>
    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>フォーム情報の編集: {{str_pad($simpleform->id, 6, '0', STR_PAD_LEFT)}}</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;">
                <a href="{{URL::to('/')}}/simpleform/{{$simpleform->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                    <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                    フォーム情報の詳細
                </a>
                <a href="{{URL::to('/')}}/simpleform" class="btn btn-info btn-xs pull-right">
                    <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                    一覧へ戻る <!--a list of form -->
                </a>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="panel panel-default shadow-box">
                    @if ($simpleform->id)
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active edit_profile">

                                    {!! Form::model($simpleform, array('method' => 'PATCH', 'action' => array('SimpleFormController@update',
                                                $simpleform->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form',
                                                'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                                    {{ csrf_field() }}
                                    <!--first item-->
                                    <div>
                                        <label style="font-size: 16px;">&#9312;</label>
                                        <label>送信者情報</label>
                                    </div>
                                    <div class="form-group">
                                        <label  class="col-sm-3 control-label">
                                            ID</label>
                                        <div class="col-sm-9">
                                            <div>
                                                {!! Form::hidden('id') !!}
                                                <label class="control-label">
                                                    {{str_pad($simpleform->id, 6, '0', STR_PAD_LEFT)}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label">
                                            送信日時
                                        </label>
                                        <div class="col-sm-9">
                                            <div>
                                                <label class="control-label">
                                                    {{$simpleform->created_at}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label">
                                            名前
                                        </label>
                                        <div class="col-sm-9">
                                           <div class="row">
                                            <div class="col-sm-6">
                                                {!! Form::text('firstname', null, ['class' => 'form-control', 'id' => 'firstname']) !!}
                                            </div>
                                            <div class="col-sm-6">
                                                {!! Form::text('lastname', null, ['class' => 'form-control', 'id' => 'lastname']) !!}
                                            </div>
                                           </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label">
                                            メール
                                        </label>
                                        <div class="col-sm-3">
                                            <div>
                                                {!! Form::text('email', null, ['class' => 'form-control required', 'id' => 'email']) !!}
                                            </div>
                                        </div>
                                        <label for="last_name" class="col-sm-3 control-label">
                                            電話番号
                                        </label>
                                        <div class="col-sm-3">
                                            <div>
                                                {!! Form::text('phone', null, ['class' => 'form-control required', 'id' => 'phone   ']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label">
                                            その他利用者情報
                                        </label>
                                        <div class="col-sm-9">
                                            <div>
                                                {!! Form::textarea('other_personal_info',null,['class' => 'form-control',
                                                 'id' => 'other_personal_info','rows' =>'3',
                                                 'style'=>'background-color:#ffeab5;border:1px solid #dbdbd9']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <!--2 items for trip info-->
                                    <hr>
                                    <div>
                                        <label style="font-size: 16px;">&#9313;</label>
                                        <label>ご利用情報</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label">
                                            ご利用店舗
                                        </label>
                                        <div class="col-sm-9">
                                            <div>
                                                <select class="form-control" name="location" id="location" onchange="changePerson(this)">
                                                    @if($simpleform->location == 'huku')
                                                        <option value="huku" selected>福岡空港店</option>
                                                        <option value="okina">沖縄空港店</option>
                                                    @else
                                                        <option value="huku">福岡空港店</option>
                                                        <option value="okina" selected >沖縄空港店</option>
                                                     @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label">
                                            車種
                                        </label>
                                        <div class="col-sm-9">
                                            <div>
                                                {!! Form::text('cartitle', null, ['class' => 'form-control', 'id' => 'cartitle']) !!}
                                                {{--<select class="form-control" name="cartitle" id="cartitle">--}}
                                                    {{--@foreach($cartitle_list as $carlist)--}}
                                                        {{--@if($simpleform->car_id == $carlist->id)--}}
                                                            {{--<option value="{{$carlist->id}}" selected>{{$carlist->name}}</option>--}}
                                                        {{--@else--}}
                                                            {{--<option value="{{$carlist->id}}">{{$carlist->name}}</option>--}}
                                                        {{--@endif--}}
                                                    {{--@endforeach--}}
                                                {{--</select>--}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label">
                                            出発
                                        </label>
                                        <div class="col-sm-3">
                                            <div id="startdate" class="input-group date" >
                                                {!! Form::text('startdate', null, ['class' => 'form-control required', 'id' => 'startdate']) !!}
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-sm-3 control-label">
                                            返却
                                        </label>
                                        <div class="col-sm-3">
                                            <div id="enddate" class="input-group date">
                                                {!! Form::text('enddate', null, ['class' => 'form-control required', 'id' => 'enddate']) !!}
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!---->
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label">

                                        </label>
                                        <div class="col-sm-3">
                                            <div id="starttime">
                                                <select name="starttime" id="starttime"  class="form-control selectpicker" data-size="4"
                                                        data-live-search="true"  >
                                                    @foreach($times as $time)
                                                        @if($simpleform->starttime == $time)
                                                            <option value="{{$time}}" selected >{{$time}}</option>
                                                        @else
                                                            <option value="{{$time}}" >{{$time}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <label class="col-sm-3 control-label">

                                        </label>
                                        <div class="col-sm-3">
                                            <div id="starttime">
                                                <select name="starttime" id="endtime"  class="form-control selectpicker" data-size="4"
                                                        data-live-search="true" >
                                                    @foreach($times as $time)
                                                        @if($simpleform->endtime == $time)
                                                            <option value="{{$time}}" selected >{{$time}}</option>
                                                        @else
                                                            <option value="{{$time}}" >{{$time}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            メッセージ
                                        </label>
                                        <div class="col-sm-9">
                                            <div>
                                                {!! Form::textarea('message',null,['class' => 'form-control',
                                                 'id' => 'message','rows' =>'2']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            オプション
                                        </label>
                                        <div class="col-sm-9">
                                            <div>
                                                {!! Form::text('car_option_service', null, ['class' => 'form-control required',
                                                 'id' => 'car_option_service','style'=>'background-color:#ffeab5;border:1px solid #dbdbd9']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <!--3 items memo for shop staff)-->
                                    <hr>
                                    <div>
                                        <label style="font-size: 16px;">&#9314;</label>
                                        <label>店舗側記録</label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            店舗メモ
                                        </label>
                                        <div class="col-sm-9">
                                            <div>
                                                {!! Form::textarea('shop_memo',null,['class' => 'form-control',
                                                 'id' => 'shop_memo','rows' =>'2','style'=>'background-color:#ffeab5;border:1px solid #dbdbd9']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="status_id_message" style="display: none">
                                        <label class="col-sm-3 control-label">

                                        </label>
                                        <div class="col-sm-9">
                                            <b style="color: red"> フォームのステータスを変更したら担当者を選択してください。</b>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            ステータス
                                        </label>
                                        <div class="col-sm-9">
                                            <div>
                                                <select class="form-control" name="status_id" id="status_id" style="background-color:#ffeab5;border:1px solid #dbdbd9">
                                                    @foreach($simpleform_status as $status)
                                                        @if($status->alias != "all")
                                                            @if($simpleform->status_id == $status->id)
                                                                <option value="{{$status->id}}" selected>{{$status->name}}</option>
                                                            @elseif($simpleform->status_id == null && $status->alias == 'notstart')
                                                                <option value="{{$status->id}}" selected>{{$status->name}}</option>
                                                            @else
                                                                <option value="{{$status->id}}">{{$status->name}}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        最終担当者
                                    </label>
                                    <div class="col-sm-9">
                                        <div>
                                            <select class="form-control" name="final_person" id="final_person" style="background-color:#ffeab5;border:1px solid #dbdbd9">
                                                <option value="{{$simpleform->final_person}}" selected>{{$simpleform->final_person}} </option>
                                                {{--@foreach($staffs as $staff)--}}
                                                    {{--@if($staff->id == $simpleform->staff_id)--}}
                                                        {{--<option value="{{$staff->id}}" selected>{{$staff->name}}</option>--}}
                                                    {{--@else--}}
                                                        {{--<option value="{{$staff->id}}">{{$staff->name}}</option>--}}
                                                    {{--@endif--}}
                                                {{--@endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    <!--save-->
                                    <div class="form-group">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <label>
                                            {!! Form::open(array('url' => URL::to('/').'/simpleform/' . $simpleform->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
                                            {!! Form::button(
                                                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                                                 array(
                                                    'class' 		 	=> 'btn btn-success disableddd',
                                                    'type' 			 	=> 'button',
                                                    'data-target' 		=> '#confirmForm',
                                                    'data-modalClass' 	=> 'modal-success',
                                                    'data-toggle' 		=> 'modal',
                                                    'data-title' 		=> trans('modals.edit_simpleform_modal_text_confirm_title'),
                                                    'data-message' 		=> trans('modals.edit_simpleform_modal_text_confirm_message')
                                            )) !!}
                                            {!! Form::close() !!}
                                            </label>
                                            <label>
                                            {!! Form::open(array('url' => URL::to('/').'/simpleform/' . $simpleform->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                                <span class="hidden-xs hidden-sm">削除する</span>',
                                                array(
                                                    'class' => 'btn btn-danger',
                                                    'type' => 'button' ,
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#confirmForm',
                                                    'data-title' => 'フォームの削除',
                                                    'data-message' => 'このフォームを完全に削除しますか？'
                                                    )) !!}
                                            {!! Form::close() !!}
                                            </label>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="panel-body">
                            <p>{{ trans('simpleform.nosimpleformYet') }}</p>
                        </div>
                    @endif
                </div>
            </div>
    </div>

    @include('modals.modal-form')

@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/alterclass.js"></script>
    @include('scripts.form-modal-script')
    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script src="{{URL::to('/')}}/js/bootstrap-select.js"></script>
    <script>
        // startdate event for datepicker
        $('#startdate').datepicker({
            language: "ja",
            format: 'yyyy/mm/dd',
            autoclose: true,
        });
        // enddate event for datepicker
        $('#enddate').datepicker({
            language: "ja",
            format: 'yyyy/mm/dd',
            autoclose: true,
        });
        //generate event when change status
        $( "#status_id" ).change(function() {
            var current_id = $(this).val();
            var status_id = '<?php $simpleform->status_id ;?>';
            if(status_id == '' || status_id == '7'  ) {
                // 7 = nostart
                $('#status_id_message').css("display","block");
            }
            if(current_id == '1' || current_id == '7') {
                $('#status_id_message').css("display","none");
            }
        });
        // change person as follow location
        function changePerson(sel) {
            var location = sel.value;
            finalPerson(location);
        }
        // change person as follow location
        function finalPerson(location) {
            var final_person = $('#final_person').val();
            $('#final_person').html("");
            if(location == 'huku') {
                $('#final_person').prepend("<option value='鳥居'>鳥居</option>");
                $('#final_person').prepend("<option value='上京'>上京</option>");
                $('#final_person').prepend("<option value='井上'>井上</option>");
                $('#final_person').prepend("<option value='山田'>山田</option>");
                $('#final_person').prepend("<option value='北村'>北村</option>");
                $('#final_person').prepend("<option value='主計'>主計</option>");
            }
            if(location == 'okina') {
                $('#final_person').prepend("<option value='鳥居'>鳥居</option>");
                $('#final_person').prepend("<option value='上京'>上京</option>");
                $('#final_person').prepend("<option value='井上'>井上</option>");
                $('#final_person').prepend("<option value='山田'>山田</option>");
                $('#final_person').prepend("<option value='北村'>北村</option>");
                $('#final_person').prepend("<option value='主計'>主計</option>");
                $('#final_person').prepend("<option value='上原'>上原</option>");
                $('#final_person').prepend("<option value='小西'>小西</option>");
            }
            $('#final_person').prepend("<option value='"+final_person+"' selected>"+final_person+"</option>");
        }

        $(document).ready(function() {
            var location = $('#location').val();
            finalPerson(location);
        });
    </script>
@endsection