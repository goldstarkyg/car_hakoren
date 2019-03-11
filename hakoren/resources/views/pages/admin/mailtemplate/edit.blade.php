@extends('layouts.adminapp_calendar')

@section('template_title')
    記事投稿時の通知｜文章テンプレート
@endsection

@section('template_fastload_css')

@endsection

@php

$articleActive = [
'checked' => '',
'value' => 0,
'true'	=> '',
'false'	=> 'checked'
];


@endphp


@section('content')
    {{--<link href="/css/article.css" rel="stylesheet">--}}
    <link href="{{URL::to('/')}}/css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        記事投稿時の通知｜文章テンプレート
                        <a href="/mailtemplate" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                            <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                            一覧へ戻る
                        </a>
                    </div>

                    {!! Form::model($template, array('url' => '/mailtemplate/update', 'method' => 'PUT', 'id'=>'editmail', 'enctype'=>'multipart/form-data')) !!}

                    {!! csrf_field() !!}
                    <input type="hidden" id="tempid" name="tempid" value="{{ $template->id }}">
                    <div class="panel-body">
                        <div class="form-group has-feedback row {{ $errors->has('mailname') ? ' has-error ' : '' }}">
                            {!! Form::label('mailname', 'mailname' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('mailname', old('mailname'), array('id' => 'mailname', 'readonly'=>'readonly', 'class' => 'form-control', 'placeholder' => 'Please input the mail type')) !!}
                                    <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                                </div>
                                @if ($errors->has('mailname'))
                                    <span class="help-block">
											<strong>{{ $errors->first('mailname') }}</strong>
										</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group has-feedback row {{ $errors->has('subject') ? ' has-error ' : '' }}">
                            {!! Form::label('subject', 'subject' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('subject', old('subject'), array('id' => 'subject', 'class' => 'form-control', 'placeholder' => 'Please input the subject')) !!}
                                    <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                                </div>
                                @if ($errors->has('subject'))
                                    <span class="help-block">
											<strong>{{ $errors->first('subject') }}</strong>
										</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group has-feedback row {{ $errors->has('sender') ? ' has-error ' : '' }}">
                            {!! Form::label('semder', 'sender' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('sender', old('sender'), array('id' => 'sender', 'class' => 'form-control', 'placeholder' => 'Please input the sender')) !!}
                                    <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                                </div>
                                @if ($errors->has('sender'))
                                    <span class="help-block">
											<strong>{{ $errors->first('sender') }}</strong>
										</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group has-feedback row {{ $errors->has('content') ? ' has-error ' : '' }}">
                            {!! Form::label('content', 'content' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <input type="hidden" id="content" name="content" value="{{ $template->content }}">
                                <div class="ibox-content no-padding">
                                    <div class="summernote">
                                        @if(old('content') == '' && $template->content == '')
                                            User name様<br>
                                        @else
                                            @if(old('message') == '')
                                                {!! $template->content !!}
                                            @else
                                                {!! old('conent') !!}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @if ($errors->has('content'))
                                    <span class="help-block">
										<strong>{{ $errors->first('content') }}</strong>
									  </span>
                                @endif
                            </div>
                        </div>
                        {{--<div class="form-group has-feedback row {{ $errors->has('content') ? ' has-error ' : '' }}">--}}
                            {{--{!! Form::label('', '' , array('class' => 'col-md-3 control-label')); !!}--}}
                            {{--<div class="col-md-9">--}}
                                {{--<p style="color:#aaa">--}}
                                    {{--"<span style="color:#555"> User name </span>" は自動的に会員の個人名に置き換わります。--}}
                                {{--</p>--}}
                                {{--<p style="color:#aaa">--}}
                                    {{--"<span style="color:#555"> Link URL </span>" は自動的に公開する記事のURLが入ります。--}}
                                {{--</p>--}}
                                {{--<p style="color:#aaa">--}}
                                    {{--"<span style="color:#555"> ArticleTitle </span>" は自動的に公開する記事の タイトル/質問が入ります。--}}
                                {{--</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <input type="hidden" id="mailaddr" name="mailaddr" value="">

                    </div>
                    <div class="panel-footer">

                        <div class="row">

                            <div class="col-sm-6">
                                {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i>' . 'テスト送信', array('class' => 'btn btn-primary btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSubscribe', 'onclick'=>'clickUpdate()')) !!}
                            </div>
                            <div class="col-sm-6">
                                {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i>' . '更新', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'onclick'=>'clickUpdate()', 'data-title' => Lang::get('modals.edit_user__modal_text_confirm_title'), 'data-message' => 'メールテンプレートの変更を保存しますか？')) !!}
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    @include('modals.modal-save')
    @include('modals.modal-delete')
    <div class="modal fade modal-success modal-save" id="confirmSubscribe" role="dialog" aria-labelledby="confirmSaveLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">テスト送信したいメールアドレスを入力してください </h4>
                </div>
                <div class="modal-body">
                    <div class="mailtitle" style="margin-bottom:10px;">
                        {!! Form::label('mailaddr1', 'メールアドレス' , array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::text('mailaddr1', null, array('id' => 'mailaddr1', 'class' => 'form-control', 'placeholder' => 'メールアドレスを入力してください')) !!}
                                <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="mailcontent">
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('<i class="fa fa-fw '.Lang::get('modals.confirm_modal_button_cancel_icon').'" aria-hidden="true"></i> ' . Lang::get('modals.confirm_modal_button_cancel_text'), array('class' => 'btn btn-outline pull-left btn-flat', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                    {!! Form::button('<i class="fa fa-fw '.Lang::get('modals.confirm_modal_button_save_icon').'" aria-hidden="true"></i> ' . '送信', array('class' => 'btn btn-success pull-right btn-flat', 'onclick'=>'sendSubscribe()', 'type' => 'button', 'id' => 'confirm' )) !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')

    <style>
        .text-wrap{
            width: 100% !important;
        }
        .ibox-content {
            border:1px solid #CCCCCC;
        }
        /*button[data-event=codeview] {
            display:none !important;
        }*/
    </style>
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.check-changed')
    @include('scripts.toggleStatus')
    <script src="{{URL::to('/')}}/js/jquery.uploadPreview.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/summernote/summernote.min.js"></script>
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.core.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.tags.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.autocomplete.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.focus.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.prompt.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.arrow.css" type="text/css">
    <script src="{{URL::to('/')}}/textext/js/textext.core.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.tags.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.autocomplete.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.suggestions.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.filter.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.focus.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.prompt.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.ajax.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.arrow.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote();
        });
        function clickUpdate(){
            var text = $(".summernote").code();
            console.log(text);
            $('#content').val(text);
        }
        function clickSubscribeUpdate(){
            var text = $(".summernote").code();
            console.log(text);
            $('#content').val(text);
            var sender = $('#sender').val();
            var title = $('#subject').val();
            $('.mailcontent').html(sender+'<br><br>'+text);
            $('.mailtitle').html(title);
        }
        function clickUsername(username){
            $('.summernote').summernote('code', username);
        }
        function sendSubscribe(){
            var mailaddr = $("#mailaddr1").val();
            $('#mailaddr').val(mailaddr);
            $('#editmail').submit();
        }
    </script>
@endsection