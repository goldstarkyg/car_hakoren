@extends('layouts.adminapp')

@section('template_title')
    リクエストの承認 {{ $user->name }}
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

    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    {{--<link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">--}}
    {{--<link href="{{URL::to('/')}}/css/plugins/datapicker/datepicker3.css" rel="stylesheet">--}}

    {{--<link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">--}}
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>管理者情報の編集: {{ $user->last_name }}　{{ $user->first_name }}さん
                    {{--<a href="{{URL::to('/')}}/settings/endusers/{{$user->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">--}}
                    {{--<i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>--}}
                    {{--ユーザー情報--}}
                    {{--</a>--}}
                    <a href="{{URL::to('/')}}/settings/endusers" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        管理者一覧
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        @if ($user->profile)
                            <div class="tab-content">

                                <div class="tab-pane fade in active edit_profile">

                                    {!! Form::model($user, array('method' => 'PATCH', 'action' => array('EndUsersManagementController@update', $user->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                                    {{ csrf_field() }}

                                    {{--<div class="form-group">--}}
                                    {{--<label for="workplace" class="col-sm-3 control-label" style="padding:5px;">勤務先名称 <span style="color:red;float:right;"></span></label>--}}
                                    {{--<div class="col-sm-9">--}}
                                    {{--<div class="col-sm-12" style="padding:5px;">--}}
                                    {{--{!! Form::text('name', null, ['class' => 'form-control required', 'placeholder' => '名前を入力してください。', 'id' => 'name']) !!}--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label" style="padding:5px;">名前</label>
                                        <div class="col-sm-9">
											<div class="col-sm-6" style="padding:5px;">
												{!! Form::text('last_name', null, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'last_name']) !!}
											</div>
											<div class="col-sm-6" style="padding:5px;">
												{!! Form::text('first_name', null, ['class' => 'form-control required', 'id' => 'first_name']) !!}
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="postal_code" class="col-sm-3 control-label" style="padding:5px;">メール</label>
                                        <div class="col-sm-9">
                                            <div class="col-sm-6" style="padding:5px;">
                                                {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'email']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="postal_code" class="col-sm-3 control-label" style="padding:5px;">役割</label>
                                        <div class="col-sm-9">
                                            <select name="role_id" id="role_id"  class="form-control">
                                                @foreach($roles as $ro)
                                                    @if(!empty($user->roleuser))
                                                        @if($ro->id == $user->roleuser->role_id)
                                                            <option value="{{$ro->id}}" selected >{{$ro->description}}</option>
                                                        @else
                                                            <option value="{{$ro->id}}" >{{$ro->description}}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{$ro->id}}" >{{$ro->description}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="postal_code" class="col-sm-3 control-label" style="padding:5px;">担当店舗</label>
                                        <div class="col-sm-9">
                                            <input type="hidden" name="shop_ids" id="shop_ids" value="{{implode(',', $admin_shops)}}">
                                            <select name="shop_id" id="shop_id"  class="form-control chosen-select">
                                                {{--                                                <option @if(empty($admin_shops)) selected @endif >選択してください。</option>--}}
                                                @foreach($shops as $shop)
                                                    @if(in_array($shop->id, $admin_shops))
                                                        <option value="{{$shop->id}}" selected >{{$shop->name}}</option>
                                                    @else
                                                        <option value="{{$shop->id}}" >{{$shop->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="pw-change-container">
                                        <div class="form-group has-feedback row">
                                            {!! Form::label('password', trans('forms.create_user_label_password'), array('class' => 'col-sm-3 control-label')); !!}
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => trans('forms.create_user_ph_password'))) !!}
                                                    <label class="input-group-addon" for="password"><i class="fa fa-fw {{ trans('forms.create_user_icon_password') }}" aria-hidden="true"></i></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row">
                                            {!! Form::label('password_confirmation', trans('forms.create_user_label_pw_confirmation'), array('class' => 'col-sm-3 control-label')); !!}
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_pw_confirmation'))) !!}
                                                    <label class="input-group-addon" for="password_confirmation"><i class="fa fa-fw {{ trans('forms.create_user_icon_pw_confirmation') }}" aria-hidden="true"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-3 col-sm-offset-3">
                                            <a href="#" class="btn btn-default btn-danger btn-change-pw" title="Change Password">
                                                <i class="fa fa-fw fa-lock" aria-hidden="true"></i>
                                                <span></span> パスワード再設定
                                            </a>
                                        </div>
                                        <div class="col-sm-3">

                                            {!! Form::button(
                                                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                                                 array(
                                                    'class' 		 	=> 'btn btn-success',
                                                    'type' 			 	=> 'button',
                                                    'data-target' 		=> '#confirmForm',
                                                    'data-modalClass' 	=> 'modal-success',
                                                    'data-toggle' 		=> 'modal',
                                                    'data-title' 		=> trans('modals.edit_user__modal_text_confirm_title'),
                                                    'data-message' 		=> trans('modals.edit_user__modal_text_confirm_message')
                                            )) !!}

                                        </div>
                                    </div>

                                    {!! Form::close() !!}

                                </div>
                            </div>

                        @else

                            <p>{{ trans('profile.noProfileYet') }}</p>

                        @endif

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
    <script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>

    @include('scripts.form-modal-script')
    @include('scripts.check-changed')
    {{--    @include('scripts.gmaps-address-lookup-api3')--}}
    <!-- Jquery Validate -->
    {{--<script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>--}}
    <script src="{{URL::to('/')}}/js/home.js"></script>

    <script>
        var shop_selector = $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        });
        shop_selector.change(function(){
            var ids = $('#shop_id').val().join();
            $('#shop_ids').val(ids);
            console.log(ids);
        });
    </script>

@endsection