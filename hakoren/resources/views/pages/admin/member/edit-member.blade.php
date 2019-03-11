@extends('layouts.adminapp')

@section('template_title')
    会員情報の編集 {{ $user->name }}
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
                <h2>会員の個別情報の編集： {{ $user->last_name }} {{ $user->first_name }} 様
                    <a href="{{URL::to('/')}}/members/{{$user->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        会員の個別情報
                    </a>
                    <a href="{{URL::to('/')}}/members" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        会員一覧
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        @if ($user->profile)

                            {!! Form::model($user, array('action' => array('MemberManagementController@update', $user->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="category" class="col-sm-2 control-label">カテゴリ</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="category_id" id="category_id" onchange="SelectCategory()">
                                        <option value="">選択してください</option>
                                        @if ($categories->count())
                                            @foreach($categories as $category)
                                                @if($user->profile->category_id == $category->id )
                                                    <option value="{{ $category->id }}" selected>{{ $category->alias }}</option>
                                                @else
                                                    <option value="{{ $category->id }}">{{ $category->alias }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="user_group" class="col-sm-2 control-label">ユーザーグループ</label>
                                <div class="col-sm-10">
                                    <div>
                                        <input type="hidden" id="groups" name="groups"  />
                                        <select class="chosen-select form-control" name="user_group" id="user_group" data-placeholder="Choose a User Group" multiple tabindex="2">
                                            @if ($users_groups->count())
                                                @foreach($users_groups as $group)
                                                    <?php $select = ''; ?>
                                                    @foreach($groups_tag as $tag)
                                                        <?php
                                                        if($tag->group_id == $group->id)
                                                        {
                                                            $select = 'selected' ;
                                                            break;
                                                        }
                                                        ?>
                                                    @endforeach
                                                    <option value="{{ $group->id }}" <?php echo $select ?> >{{ $group->alias }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="col-sm-2 control-label" style="padding:5px;">姓</label>
                                <div class="col-sm-4">
                                    {!! Form::text('last_name', null, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'last_name']) !!}
                                </div>
                                <label for="last_name" class="col-sm-2 control-label" style="padding:5px;">名</label>
                                <div class="col-sm-4">
                                    {!! Form::text('first_name', null, ['class' => 'form-control required', 'id' => 'first_name']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fur_first_name" class="col-sm-2 control-label" >セイ</label>
                                <div class="col-sm-4">
                                    {!! Form::text('fur_last_name', $user->profile->fur_last_name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'fur_last_name']) !!}
                                </div>
                                <label for="fur_last_name" class="col-sm-2 control-label" >メイ</label>
                                <div class="col-sm-4">
                                    {!! Form::text('fur_first_name',$user->profile->fur_first_name, ['class' => 'form-control required', 'id' => 'fur_first_name']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sex" class="col-sm-2 control-label">性別</label>
                                <div class="col-sm-4">
                                    <div>
                                        <select class="form-control" name="sex" id="sex">
                                            @if($user->profile->sex == 1)
                                                <option value="1" selected >男性</option>
                                                <option value="0" >女性</option>
                                            @else
                                                <option value="1" >男性</option>
                                                <option value="0" selected >女性</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <label for="email" class="col-sm-2 control-label">メール</label>
                                <div class="col-sm-4">
                                    {!! Form::text('email', null, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'email']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="col-sm-2 control-label" >電話</label>
                                <div class="col-sm-4">
                                    {!! Form::text('phone', $user->profile->phone, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'phone']) !!}
                                </div>
                                <label for="emergency_phone" class="col-sm-2 control-label">緊急連絡先</label>
                                <div class="col-sm-4">
                                    {!! Form::text('emergency_phone', $user->profile->emergency_phone, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'emergency_phone']) !!}
                                </div>
                            </div>
                            <!--if category is individual-->
                            <div id="individual" style="display: none" >
                                <div class="form-group">
                                    <label for="prefecture" class="col-sm-2 control-label">都道府県</label>
                                    <div class="col-md-4">
                                        {!! Form::text('prefecture',$user->profile->prefecture, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'prefecture']) !!}
                                    </div>
                                    <label for="city" class="col-sm-2 control-label">市町村</label>
                                    <div class="col-md-4">
                                        {!! Form::text('city', $user->profile->city, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'city']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="postal_code" class="col-sm-2 control-label">郵便番号</label>
                                    <div class="col-md-4">
                                        {!! Form::text('postal_code', $user->profile->postal_code, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'postal_code']) !!}
                                    </div>
                                    <label for="address1" class="col-sm-2 control-label">住所</label>
                                    <div class="col-md-4">
                                        {!! Form::text('address1', $user->profile->address1, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'address1']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address2" class="col-sm-2 control-label">住所2</label>
                                    <div class="col-md-4">
                                        {!! Form::text('address2',$user->profile->address2, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'address2']) !!}
                                    </div>
                                </div>
                            </div>
                            <!---->
                            <!--if category is foreiigner-->
                            <div id="foreigner" style="display: none" >
                                <div class="form-group">
                                    <label for="foreign_country" class="col-sm-2 control-label">Foreign Country</label>
                                    <div class="col-md-4">
                                        {!! Form::text('foreign_country', $user->profile->foreign_country, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'foreign_country']) !!}
                                    </div>
                                    <label for="foreign_city" class="col-sm-2 control-label">Foreign City</label>
                                    <div class="col-md-4">
                                        {!! Form::text('foreign_city', $user->profile->foreign_city, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'foreign_city']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foreign_state" class="col-sm-2 control-label">Foreign State</label>
                                    <div class="col-md-4">
                                        {!! Form::text('foreign_state', $user->profile->foreign_state, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'foreign_state']) !!}
                                    </div>
                                    <label for="foreign_zip_code" class="col-sm-2 control-label">Foreign Zip Code</label>
                                    <div class="col-md-4">
                                        {!! Form::text('foreign_zip_code', $user->profile->foreign_zip_code, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'foreign_zip_code']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foreign_address" class="col-sm-2 control-label">Foreign Address</label>
                                    <div class="col-md-4">
                                        {!! Form::text('foreign_address', $user->profile->address, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'foreign_address']) !!}
                                    </div>
                                </div>
                            </div>
                            <!---->
                            <!--if category is coporate-->
                            <div id="corporate" style="display: none" >
                                <div class="form-group">
                                    <label for="company_name" class="col-sm-2 control-label">法人名</label>
                                    <div class="col-md-4">
                                        {!! Form::text('company_name', $user->profile->company_name, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'company_name']) !!}
                                    </div>
                                    <label for="company_prefecture" class="col-sm-2 control-label">Company Prefecture</label>
                                    <div class="col-md-4">
                                        {!! Form::text('company_prefecture', $user->profile->company_prefecture, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'company_prefecture']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="company_city" class="col-sm-2 control-label">所在地（都道府県）</label>
                                    <div class="col-md-4">
                                        {!! Form::text('company_city', $user->profile->company_city, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'company_city']) !!}
                                    </div>
                                    <label for="company_postal_code" class="col-sm-2 control-label">郵便番号</label>
                                    <div class="col-md-4">
                                        {!! Form::text('company_postal_code', $user->profile->company_postal_code, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'company_postal_code']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="company_address1" class="col-sm-2 control-label">事務所所在地</label>
                                    <div class="col-md-4">
                                        {!! Form::text('company_address1',$user->profile->company_address1, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'company_address1']) !!}
                                    </div>
                                    <label for="company_address2" class="col-sm-2 control-label">事務所所在地2</label>
                                    <div class="col-md-4">
                                        {!! Form::text('company_address2', $user->profile->company_address2, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'company_address2']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="padding-top:15px;margin-bottom:0;">
                                <div class="col-sm-12 text-center">
                                    <label>
                                        {!! Form::open(array('url' => URL::to('/').'/members/' . $user->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
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
                                    <label style="margin-left:5px;">
                                        {!! Form::open(array('url' => URL::to('/').'/members/' . $user->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                        {!! Form::hidden('_method', 'DELETE') !!}
                                        {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                            <span class="hidden-xs hidden-sm">削除</span>',
                                            array('class' => 'btn btn-danger',
                                                'type' => 'button' ,
                                                'data-toggle' => 'modal',
                                                'data-target' => '#confirmDelete',
                                                'data-title' => '会員の削除',
                                                'data-message' => 'この会員を本当に削除しますか？この操作を取り消すことはできません。')) !!}
                                        {!! Form::close() !!}
                                    </label>
                                </div>
                            </div>

                            {!! Form::close() !!}

                        @else

                            <p>{{ trans('profile.noProfileYet') }}</p>

                        @endif

                    </div>
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
    @include('scripts.check-changed')
    @include('scripts.gmaps-address-lookup-api3')
    @include('scripts.admin.member_edit')
    @include('scripts.delete-modal-script')
    <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

@endsection