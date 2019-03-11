@extends('layouts.adminapp')

@section('template_title')
    {{ trans('profile.templateTitle') }}
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="panel panel-heading ">
                <span class="tab-pane edit_account">
						パスワードの変更
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
                    <div class="panel-body">

                        @if ($user->profile)

                            @if (Auth::user()->id == $user->id)
                                <div id="changepw" class="tab-pane fade in active">
                                    {!! Form::model($user, array('action' => array('ProfilesController@updateUserPassword', $user->id), 'method' => 'PUT', 'autocomplete' => 'new-password')) !!}

                                    <div class="pw-change-container margin-bottom-2">

                                        <div class="form-group has-feedback row {{ $errors->has('password') ? ' has-error ' : '' }}">
                                            {!! Form::label('password', '新しいパスワード', array('class' => 'col-md-3 control-label')); !!}
                                            <div class="col-md-9">
                                                {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => '新しいパスワードを入力してください', 'autocomplete' => 'new-password')) !!}
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
														                <strong>
                                                                            @if(strpos( $errors->first('password'),'Weak - Try combining letters & numbers'))
                                                                                パスワードの強度 - 弱
                                                                            @elseif(strpos( $errors->first('password'),'strong password'))
                                                                                パスワードの強度 - 強
                                                                            @elseif(strpos( $errors->first('password'),'password match'))
                                                                                パスワードが一致しました
                                                                            @elseif(strpos( $errors->first('password'),'passwords do not match'))
                                                                                パスワードが一致していません
                                                                            @elseif(strpos( $errors->first('password'),'The password is too short'))
                                                                                パスワードが短すぎます
                                                                            @elseif(strpos( $errors->first('password'),'Medium - Try using special charecters'))
                                                                                中 - 特殊文字を使用してみてください
                                                                            @else
                                                                                {{ $errors->first('password') }}
                                                                            @endif
                                                                        </strong>
														            </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback row {{ $errors->has('password_confirmation') ? ' has-error ' : '' }}">
                                            {!! Form::label('password_confirmation', 'パスワードの確認', array('class' => 'col-md-3 control-label')) !!}
                                            <div class="col-md-9">
                                                {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => 'もう一度新しいパスワードを入力してください')) !!}
                                                <span id="pw_status"></span>
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
																        <strong>
                                                                            @if(strpos( $errors->first('password_confirmation'),'Weak - Try combining letters & numbers'))
                                                                                パスワードの強度 - 弱
                                                                            @elseif(strpos( $errors->first('password_confirmation'),'strong password'))
                                                                                パスワードの強度 - 強
                                                                            @elseif(strpos( $errors->first('password_confirmation'),'password match'))
                                                                                パスワードが一致しました
                                                                            @elseif(strpos( $errors->first('password_confirmation'),'passwords do not match'))
                                                                                パスワードが一致していません
                                                                            @elseif(strpos( $errors->first('password_confirmation'),'The password is too short'))
                                                                                パスワードが短すぎます
                                                                            @elseif(strpos( $errors->first('password_confirmation'),'Medium - Try using special charecters'))
                                                                                中 - 特殊文字を使用してみてください
                                                                            @else
                                                                                {{ $errors->first('password_confirmation') }}
                                                                            @endif
                                                                        </strong>
																    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-9 col-md-offset-3">
                                            {!! Form::button(
                                                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . 'パスワードの変更',
                                                 array(
                                                    'class' 		 	=> 'btn btn-warning',
                                                    'id' 				=> 'pw_save_trigger',
                                                    'disabled'			=> true,
                                                    'type' 			 	=> 'button',
                                                    'data-submit'       => trans('profile.submitButton'),
                                                    'data-target' 		=> '#confirmForm',
                                                    'data-modalClass' 	=> 'modal-warning',
                                                    'data-toggle' 		=> 'modal',
                                                    'data-title' 		=> trans('modals.edit_user__modal_text_confirm_title'),
                                                    'data-message' 		=> trans('modals.edit_user__modal_text_confirm_message')
                                            )) !!}
                                        </div>
                                    </div>
                                    {!! Form::close() !!}

                                </div>


                            @else

                                <p>{{ trans('profile.notYourProfile') }}</p>

                            @endif
                        @else

                            <p>{{ trans('profile.noProfileYet') }}</p>

                        @endif

                    </div>
        </div>
    </div>

    @include('modals.modal-form')

@endsection

@section('footer_scripts')
    <script src="/js/plugins/dropzone/dropzone.js"></script>
    <script src="/js/alterclass.js"></script>

    @include('scripts.form-modal-script')
    @include('scripts.gmaps-address-lookup-api3')

    <script type="text/javascript">

        $('.dropdown-menu li a').click(function() {
            $('.dropdown-menu li').removeClass('active');
        });

        $('.profile-trigger').click(function() {
            $('.panel').alterClass('panel-*', 'panel-default');
        });

        $('.settings-trigger').click(function() {
            $('.panel').alterClass('panel-*', 'panel-info');
        });

        $('.admin-trigger').click(function() {
            $('.panel').alterClass('panel-*', 'panel-warning');
            $('.edit_account .nav-pills li, .edit_account .tab-pane').removeClass('active');
            $('#changepw')
                    .addClass('active')
                    .addClass('in');
            $('.change-pw').addClass('active');
        });

        $('.warning-pill-trigger').click(function() {
            $('.panel').alterClass('panel-*', 'panel-warning');
        });

        $('.danger-pill-trigger').click(function() {
            $('.panel').alterClass('panel-*', 'panel-danger');
        });

        $('#user_basics_form').on('keyup change', 'input, select, textarea', function(){
            $('#account_save_trigger').attr('disabled', false);
        });

        $('#checkConfirmDelete').change(function() {
            var submitDelete = $('#delete_account_trigger');
            var self = $(this);

            if (self.is(':checked')) {
                $('#uncheckicon').css('display', 'none');
                $('#checkicon').css('display', 'block');
                submitDelete.attr('disabled', false);
            }
            else {
                $('#uncheckicon').css('display', 'block');
                $('#checkicon').css('display', 'none');
                submitDelete.attr('disabled', true);
            }
        });

        $("#password_confirmation").keyup(function() {
            checkPasswordMatch();
        });

        $("#password, #password_confirmation").keyup(function() {
            enableSubmitPWCheck();
        });

        //
        !function(a){"use strict";var e=function(e,s){function t(a){return a===-1?s.shortPass:a===-2?s.containsUsername:(a=a<0?0:a,a<34?s.badPass:a<68?s.goodPass:s.strongPass)}function n(a,e){var t=0;if(a.length<s.minimumLength)return-1;if(s.username){if(a.toLowerCase()===e.toLowerCase())return-2;if(s.usernamePartialMatch&&e.length){var n=new RegExp(e.toLowerCase());if(a.toLowerCase().match(n))return-2}}t+=4*a.length,t+=r(1,a).length-a.length,t+=r(2,a).length-a.length,t+=r(3,a).length-a.length,t+=r(4,a).length-a.length,a.match(/(.*[0-9].*[0-9].*[0-9])/)&&(t+=5);var o=".*[!,@,#,$,%,^,&,*,?,_,~]";return o=new RegExp("("+o+o+")"),a.match(o)&&(t+=5),a.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)&&(t+=10),a.match(/([a-zA-Z])/)&&a.match(/([0-9])/)&&(t+=15),a.match(/([!,@,#,$,%,^,&,*,?,_,~])/)&&a.match(/([0-9])/)&&(t+=15),a.match(/([!,@,#,$,%,^,&,*,?,_,~])/)&&a.match(/([a-zA-Z])/)&&(t+=15),(a.match(/^\w+$/)||a.match(/^\d+$/))&&(t-=10),t>100&&(t=100),t<0&&(t=0),t}function r(a,e){for(var s="",t=!1,n=0;n<e.length;n++){t=!0;for(var r=0;r<a&&r+n+a<e.length;r++)t=t&&e.charAt(r+n)===e.charAt(r+n+a);r<a&&(t=!1),t?(n+=a-1,t=!1):s+=e.charAt(n)}return s}function o(){var r=!0,o=s.showText,h=s.showPercent,i=a("<div>").addClass("pass-graybar"),c=a("<div>").addClass("pass-colorbar"),l=a("<div>").addClass("pass-wrapper").append(i.append(c));return e.parent().addClass("pass-strength-visible"),s.animate&&(l.css("display","none"),r=!1,e.parent().removeClass("pass-strength-visible")),s.showPercent&&(h=a("<span>").addClass("pass-percent").text("0%"),l.append(h)),s.showText&&(o=a("<span>").addClass("pass-text").html(s.enterPass),l.append(o)),e.after(l),e.keyup(function(){var r=s.username||"";r&&(r=a(r).val());var i=n(e.val(),r);e.trigger("password.score",[i]);var l=i<0?0:i;if(c.css({backgroundPosition:"0px -"+l+"px",width:l+"%"}),s.showPercent&&h.html(l+"%"),s.showText){var p=t(i);!e.val().length&&i<=0&&(p=s.enterPass),o.html()!==a("<div>").html(p).html()&&(o.html(p),e.trigger("password.text",[p,i]))}}),s.animate&&(e.focus(function(){r||l.slideDown(s.animateSpeed,function(){r=!0,e.parent().addClass("pass-strength-visible")})}),e.blur(function(){!e.val().length&&r&&l.slideUp(s.animateSpeed,function(){r=!1,e.parent().removeClass("pass-strength-visible")})})),this}var h={shortPass:"The password is too short",badPass:"Weak; try combining letters & numbers",goodPass:"Medium; try using special charecters",strongPass:"Strong password",containsUsername:"The password contains the username",enterPass:"Type your password",showPercent:!1,showText:!0,animate:!0,animateSpeed:"fast",username:!1,usernamePartialMatch:!0,minimumLength:4};return s=a.extend({},h,s),o.call(this)};a.fn.password=function(s){return this.each(function(){new e(a(this),s)})}}(jQuery);
        //$('#password, #password_confirmation').hidePassword(true);
        $('#password').password({
            shortPass: 'パスワードが短すぎます',
            badPass: 'パスワードの強度 - 弱',
            goodPass: '中 - 特殊文字を使用してみてください',
            strongPass: 'パスワードの強度 - 強',
            containsUsername: 'パスワードにはユーザー名',
            enterPass: false,
            showPercent: false,
            showText: true,
            animate: true,
            animateSpeed: 50,
            username: false, // select the username field (selector or jQuery instance) for better password checks
            usernamePartialMatch: true,
            minimumLength: 6
        });

        function checkPasswordMatch() {
            var password = $("#password").val();
            var confirmPassword = $("#password_confirmation").val();
            if (password != confirmPassword) {
                $("#pw_status").html("パスワードが一致していません");
            }
            else {
                $("#pw_status").html("パスワードが一致しました");
            }
        }

        function enableSubmitPWCheck() {
            var password = $("#password").val();
            var confirmPassword = $("#password_confirmation").val();
            var submitChange = $('#pw_save_trigger');
            if (password != confirmPassword) {
                submitChange.attr('disabled', true);
            }
            else {
                submitChange.attr('disabled', false);
            }
        }
        function clickBack(){
            history.back();
        }
    </script>

@endsection
