@extends('layouts.adminapp')

@section('template_title')
    新規会員を追加する
@endsection

@section('template_fastload_css')
@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>新規会員を追加する
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="{{URL::to('/')}}/members" class="btn btn-info btn-xs pull-right">
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

                        {!! Form::open(array('action' => 'MemberManagementController@store', 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal')) !!}

                        {!! csrf_field() !!}

                        <div class="form-group has-feedback row {{ $errors->has('category_id') ? ' has-error ' : '' }}">
                            {!! Form::label('category_id',
                                        'カテゴリ',
                                        array('class' => 'col-md-2 control-label')); !!}
                            <div class="col-md-10">
                                <div>
                                    <select class="form-control" name="category_id" id="category_id" onchange="SelectCategory()">
                                        <option value="">選択してください</option>
                                        @if ($categories->count())
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->alias }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @if ($errors->has('category_id'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('category_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback row {{ $errors->has('user_group') ? ' has-error ' : '' }}">
                            {!! Form::label('user_group',
                                        'User Group',
                                        array('class' => 'col-md-2 control-label')); !!}
                            <div class="col-md-10">
                                <div>
                                    <input type="hidden" id="groups" name="groups" />
                                    <select class="chosen-select form-control" name="user_group" id="user_group" data-placeholder="Choose a User Group" multiple tabindex="2">
                                        @if ($users_groups->count())
                                            @foreach($users_groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->alias }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @if ($errors->has('user_group'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('user_group') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('first_name') ? ' has-error ' : '' }}">
                                {!! Form::label('first_name',
                                                trans('forms.create_user_label_firstname'),
                                                array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div>
                                        {!! Form::text('first_name', NULL,
                                                        array('id' => 'first_name',
                                                        'class' => 'form-control',
                                                        'placeholder' => trans('forms.create_user_ph_firstname'))) !!}
                                    </div>
                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="{{ $errors->has('last_name') ? ' has-error ' : '' }}">
                                {!! Form::label('last_name',
                                                trans('forms.create_user_label_lastname'),
                                                array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div>
                                        {!! Form::text('last_name', NULL,
                                                    array('id' => 'last_name',
                                                    'class' => 'form-control',
                                                    'placeholder' => trans('forms.create_user_ph_lastname'))) !!}
                                    </div>
                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('fur_first_name') ? ' has-error ' : '' }}">
                                {!! Form::label('fur_first_name',
                                                'フリガナ（セイ）',
                                                array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div>
                                        {!! Form::text('fur_first_name', NULL,
                                                        array('id' => 'fur_first_name',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Furigana First Name')) !!}
                                    </div>
                                    @if ($errors->has('fur_first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('fur_first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="{{ $errors->has('fur_last_name') ? ' has-error ' : '' }}">
                                {!! Form::label('fur_last_name',
                                                'フリガナ（メイ）',
                                                array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div>
                                        {!! Form::text('fur_last_name', NULL,
                                                    array('id' => 'fur_last_name',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Furigana Last Name')) !!}
                                    </div>
                                    @if ($errors->has('fur_last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('fur_last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('sex') ? ' has-error ' : '' }}" >
                                {!! Form::label('sex',
                                            '性別',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div>
                                        <select class="form-control" name="sex" id="sex">
                                            <option value="1" selected >男性</option>
                                            <option value="0" >女性</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div>
                                {!! Form::label('birth',
                                            '誕生日',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div id="birth" class="input-group date">
                                        <input type="text" name="birth" readonly class="form-control" required>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('email') ? ' has-error ' : '' }}" >
                                {!! Form::label('email',
                                            trans('forms.create_user_label_email'),
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div>
                                        {!! Form::text('email', NULL,
                                                    array('id' => 'email',
                                                    'class' => 'form-control',
                                                    'placeholder' => trans('forms.create_user_ph_email'))) !!}
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                            <div class="{{ $errors->has('phone') ? ' has-error ' : '' }}" >
                                {!! Form::label('phone',
                                            '電話',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div>
                                        {!! Form::text('phone', NULL,
                                                    array('id' => 'phone',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Phone Number')) !!}
                                    </div>
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('emergency_phone') ? ' has-error ' : '' }}" >
                                {!! Form::label('emergency_phone',
                                            '緊急連絡先',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('emergency_phone', NULL,
                                                    array('id' => 'emergency_phone',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Emergency Phone')) !!}
                                    </div>
                                    @if ($errors->has('emergency_phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('emergeny_phone') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--if category is individual-->
                        <div id="individual" style="display: none">
                            <div class="form-group has-feedback row {{ $errors->has('prefecture') ? ' has-error ' : '' }}">
                                {!! Form::label('prefecture',
                                            '都道府県',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('prefecture', Null,
                                                    array('id' => 'prefecture',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Prefecture')) !!}
                                    </div>
                                    @if ($errors->has('prefecture'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('prefecture') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('city') ? ' has-error ' : '' }}">
                                {!! Form::label('city',
                                            '市町村',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('city', Null,
                                                    array('id' => 'city',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'City')) !!}
                                    </div>
                                    @if ($errors->has('city'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('postal_code') ? ' has-error ' : '' }}">
                                {!! Form::label('postal_code',
                                            '郵便番号',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('postal_code', Null,
                                                    array('id' => 'postal_code',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Pstal Code')) !!}
                                    </div>
                                    @if ($errors->has('postal_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('postal_code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('address1') ? ' has-error ' : '' }}">
                                {!! Form::label('address1',
                                            '住所1',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('address1', Null,
                                                    array('id' => 'address1',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Address1')) !!}
                                    </div>
                                    @if ($errors->has('address1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('address1') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('address2') ? ' has-error ' : '' }}">
                                {!! Form::label('address2',
                                            '住所2',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('address2', Null,
                                                    array('id' => 'address2',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Address2')) !!}
                                    </div>
                                    @if ($errors->has('address2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('address2') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!---->

                        <!--if category is foreigner-->
                        <div id="foreigner" style="display: none">
                            <div class="form-group has-feedback row {{ $errors->has('foreign_country') ? ' has-error ' : '' }}">
                                {!! Form::label('foreign_country',
                                            'Country',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('foreign_counry', Null,
                                                    array('id' => 'foreign_country',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Country')) !!}
                                    </div>
                                    @if ($errors->has('foreign_country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('foreign_country') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('foreign_city') ? ' has-error ' : '' }}">
                                {!! Form::label('foreign_city',
                                            'City',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('foreign_city', Null,
                                                    array('id' => 'foreign_city',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'City')) !!}
                                    </div>
                                    @if ($errors->has('foreigh_city'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('foreign_city') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('foreign_state') ? ' has-error ' : '' }}">
                                {!! Form::label('foreign_state',
                                            'State',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('foreign_state', Null,
                                                    array('id' => 'foreign_state',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'State')) !!}
                                    </div>
                                    @if ($errors->has('prefecture'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('foreign_state') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('foreign_zip_code') ? ' has-error ' : '' }}">
                                {!! Form::label('foreign_zip_code',
                                            'Zip Code',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('foreign_zip_code', Null,
                                                    array('id' => 'foreign_zip_code',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Zip Code')) !!}
                                    </div>
                                    @if ($errors->has('foreign_zip_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('foreign_zip_code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('foreign_address') ? ' has-error ' : '' }}">
                                {!! Form::label('foreign_address',
                                            'Address',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('foreign_address', Null,
                                                    array('id' => 'foreign_adddress',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Address')) !!}
                                    </div>
                                    @if ($errors->has('foreign_address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('foreign_address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!---->

                        <!--if category is coporate -->
                        <div id="corporate" style="display: none">
                            <div class="form-group has-feedback row {{ $errors->has('company_name') ? ' has-error ' : '' }}">
                                {!! Form::label('company_name',
                                            '法人名',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('company_name', Null,
                                                    array('id' => 'company_name',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Company Name')) !!}
                                    </div>
                                    @if ($errors->has('company_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('company_prefecture') ? ' has-error ' : '' }}">
                                {!! Form::label('company_prefecture',
                                            '所在地（都道府県）',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('company_prefecture', Null,
                                                    array('id' => 'company_prefecture',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Compnay Prefecture')) !!}
                                    </div>
                                    @if ($errors->has('company_prefecture'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_prefecture') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('company_city') ? ' has-error ' : '' }}">
                                {!! Form::label('company_city',
                                            '所在地（市町村）',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('company_city', Null,
                                                    array('id' => 'company_city',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Company City')) !!}
                                    </div>
                                    @if ($errors->has('company_city'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_city') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('company_postal_code') ? ' has-error ' : '' }}">
                                {!! Form::label('company_postal_code',
                                            '郵便番号',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('company_postal_code', Null,
                                                    array('id' => 'company_postal_code',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Company Postal Code')) !!}
                                    </div>
                                    @if ($errors->has('company_postal_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_postal_code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('company_address1') ? ' has-error ' : '' }}">
                                {!! Form::label('company_address1',
                                            '住所1',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('company_address1', Null,
                                                    array('id' => 'company_address1',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Company Address1')) !!}
                                    </div>
                                    @if ($errors->has('company_address1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_address1') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('company_address2') ? ' has-error ' : '' }}">
                                {!! Form::label('company_address2',
                                            'Prefecture',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-10">
                                    <div>
                                        {!! Form::text('company_address2', Null,
                                                    array('id' => 'company_address2',
                                                    'class' => 'form-control ',
                                                    'placeholder' => 'Company Address2')) !!}
                                    </div>
                                    @if ($errors->has('company_address2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_address2') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!---->
                        <!--credit card-->
                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('credit_card_type') ? ' has-error ' : '' }}" >
                                {!! Form::label('credit_card_type',
                                            'Credit Card Type',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <select class="form-control" name="credit_card_type" id="credit_card_type">
                                            <option value="" selected >Select Card Type</option>
                                        @if ($credit_cards->count())
                                            @foreach($credit_cards as $card)
                                                <option value="{{ $card->id }}" >{{ $card->alias }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('credit_card_type'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('credit_card_type') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                            <div class="{{ $errors->has('credit_card_number') ? ' has-error ' : '' }}" >
                                {!! Form::label('credit_card_number',
                                            'Credit Card Number',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div>
                                        {!! Form::text('credit_card_number', NULL,
                                                    array('id' => 'credit_card_number',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Credit Card Number')) !!}
                                    </div>
                                    @if ($errors->has('credit_card_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('credit_card_number') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div class="{{ $errors->has('credit_card_expiration') ? ' has-error ' : '' }}" >
                                {!! Form::label('credit_card_expiration',
                                            'Credit Card Expiration',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div id="credit_card_expiration" class="input-group date">
                                        <input type="text" name="credit_card_expiration" readonly class="form-control" required>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="{{ $errors->has('credit_card_code') ? ' has-error ' : '' }}" >
                                {!! Form::label('credit_card_code',
                                            'Credit Card Code',
                                            array('class' => 'col-md-2 control-label')); !!}
                                <div class="col-md-4">
                                    <div>
                                        {!! Form::text('credit_card_code', NULL,
                                                    array('id' => 'credit_card_code',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Credit Card Code')) !!}
                                    </div>
                                    @if ($errors->has('credit_card_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('credit_card_code') }}</strong>
                                         </span>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <!--end credit card section-->

                        <div class="form-group has-feedback row">
                            {!! Form::label('role',
                                        trans('forms.create_user_label_role'),
                                        array('class' => 'col-md-2 control-label')); !!}
                            <div class="col-md-10">
                                <div>
                                    <select class="form-control" name="role" id="role">
                                        @if ($roles->count())
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" selected >{{ $role->description }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row ">
                            {!! Form::label('activated',
                                        'Status',
                                        array('class' => 'col-md-2 control-label')); !!}
                            <div class="col-md-10">
                                <div>
                                    <select class="form-control" name="activated" id="activated">
                                        <option value="1" selected >Active</option>
                                        <option value="0" >Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row {{ $errors->has('password') ? ' has-error ' : '' }}">
                            {!! Form::label('password',
                                        trans('forms.create_user_label_password'),
                                        array('class' => 'col-md-2 control-label')); !!}
                            <div class="col-md-10">
                                <div>
                                    {!! Form::password('password',
                                                array('id' => 'password',
                                                'class' => 'form-control ',
                                                'placeholder' => trans('forms.create_user_ph_password'))) !!}
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback row {{ $errors->has('password_confirmation') ? ' has-error ' : '' }}">
                            {!! Form::label('password_confirmation',
                                        trans('forms.create_user_label_pw_confirmation'),
                                        array('class' => 'col-md-2 control-label')); !!}
                            <div class="col-md-10">
                                <div>
                                    {!! Form::password('password_confirmation',
                                                    array('id' => 'password_confirmation',
                                                    'class' => 'form-control',
                                                    'placeholder' => trans('forms.create_user_ph_pw_confirmation'))) !!}
                                </div>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {!! Form::button('<i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;' . trans('forms.create_user_button_text'),
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