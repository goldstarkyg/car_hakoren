@extends('layouts.adminapp')

@section('template_title')
	{{ trans('profile.templateTitle') }}
@endsection

@section('content')
	<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
	<div>
		<div class="row">
			<div class="panel panel-heading ">
				<a href="#" onclick="clickBack()" class="btn btn-info btn-xs pull-right" style="display: inline-flex;">
					<i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
					戻る
				</a>
				<a href=".edit_account" data-toggle="pill" class="btn btn-info btn-xs pull-right" style="display: inline-flex;margin-right:10px;">
					<i class="fa fa-password" aria-hidden="true"></i>
					パスワードの変更
				</a>

				<div class="tab-content">
							<span class="tab-pane active in edit_settings">
								アカウント設定
							</span>
							<span class="tab-pane edit_account">
								パスワードの変更
							</span>
				</div>
			</div>
		</div>
	</div>
	<div>
	    <div class="row">
			<div>
				<div class="panel panel-default">
					<div class="panel-body">

						@if ($user->profile)

							@if (Auth::user()->id == $user->id)

								<div class="tab-content">


									<div class="tab-pane fade in active edit_settings">

										{!! Form::model($user, array('action' => array('ProfilesController@updateUserAccount', $user->id), 'method' => 'PUT', 'id' => 'user_basics_form')) !!}

										{!! csrf_field() !!}

										<div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
											{!! Form::label('name', trans('forms.create_user_label_id') , array('class' => 'col-md-3 control-label')); !!}
											<div class="col-md-9">
												<div class="input-group">
													{!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('forms.ph-username'))) !!}
													<label class="input-group-addon" for="name"><i class="fa fa-fw fa-user }}" aria-hidden="true"></i></label>
												</div>
											</div>
										</div>

										<div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
											{!! Form::label('email', trans('forms.create_user_label_email') , array('class' => 'col-md-3 control-label')); !!}
											<div class="col-md-9">
												<div class="input-group">
													{!! Form::text('email', old('email'), array('id' => 'email', 'class' => 'form-control', 'placeholder' => trans('forms.ph-useremail'))) !!}
													<label class="input-group-addon" for="email"><i class="fa fa-fw fa-envelope " aria-hidden="true"></i></label>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<div class="col-md-9 col-md-offset-3">
												{!! Form::button(
                                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . '変更を保存',
                                                     array(
                                                        'class' 		 	=> 'btn btn-success',
                                                        'id' 				=> 'account_save_trigger',
                                                        'disabled'			=> false,
                                                        'type' 			 	=> 'button',
                                                        'onclick'			=> 'updateemail()',
                                                        'data-submit'       => trans('profile.submitProfileButton'),

                                                )) !!}
											</div>
										</div>

										{!! Form::close() !!}

									</div>
									<div class="tab-pane fade edit_account">
										<div class="tab-content">

											<div id="changepw" class="tab-pane fade in active">

												<!--<h3 class="margin-bottom-1">
													{{ trans('profile.changePwTitle') }}
														</h3>-->

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

											<div id="deleteAccount" class="tab-pane fade">

												<h3 class="margin-bottom-1 text-center text-danger">
													{{ trans('profile.deleteAccountTitle') }}
												</h3>
												<p class="margin-bottom-2 text-center">
													<i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
													<strong>Deleting</strong> your account is <u><strong>permanent</strong></u> and <u><strong>cannot</strong></u> be undone.
													<i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
												</p>

												<hr>

												<div class="row">
													<div class="col-sm-6 col-sm-offset-3 margin-bottom-3 text-center">

														{!! Form::model($user, array('action' => array('ProfilesController@deleteUserAccount', $user->id), 'method' => 'DELETE')) !!}

														<div class="btn-group btn-group-vertical margin-bottom-2" data-toggle="buttons">
															<label class="btn no-shadow" for="checkConfirmDelete" >
																<input type="checkbox" name='checkConfirmDelete' id="checkConfirmDelete">
																<i id="uncheckicon" class="fa fa-square-o fa-fw fa-2x" style="display:block"></i>
																<i id="checkicon" class="fa fa-check-square-o fa-fw fa-2x" style="display:none"></i>
																<span class="margin-left-2"> Confirm Account Deletion</span>
															</label>
														</div>

														{!! Form::button(
                                                            '<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> ' . trans('profile.deleteAccountBtn'),
                                                            array(
                                                                'class' 			=> 'btn btn-block btn-danger',
                                                                'id' 				=> 'delete_account_trigger',
                                                                'disabled'			=> true,
                                                                'type' 				=> 'button',
                                                                'data-toggle' 		=> 'modal',
                                                                'data-submit'       => trans('profile.deleteAccountBtnConfirm'),
                                                                'data-target' 		=> '#confirmForm',
                                                                'data-modalClass' 	=> 'modal-danger',
                                                                'data-title' 		=> trans('profile.deleteAccountConfirmTitle'),
                                                                'data-message' 		=> trans('profile.deleteAccountConfirmMsg')
                                                            )
                                                        ) !!}

														{!! Form::close() !!}

													</div>
												</div>
											</div>
										</div>
									</div>
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
		</div>
    </div>

	@include('modals.modal-form')
	<div class="modal fade" id="confirmEmailForm" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						{{ trans('modals.edit_user__modal_text_confirm_title') }}
					</h4>
				</div>
				<div class="modal-body">
					<p>
						{{ trans('modals.edit_user__modal_text_confirm_message') }}
					</p>
				</div>
				<div class="modal-footer">
					{!! Form::button('<i class="fa fa-fw fa-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn pull-left', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
					{!! Form::button('<i class="fa fa-fw fa-check" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_submit'), array('class' => 'btn btn-success pull-right', 'data-dismiss'=>'modal', 'type' => 'button', 'onclick'=>'saveEmailChange()', 'id' => 'confirm' )) !!}
				</div>
			</div>
		</div>
	</div>
@endsection

@section('footer_scripts')
	<script src="/js/plugins/dropzone/dropzone.js"></script>
	<script src="/js/alterclass.js"></script>
	<script src="/js/plugins/validate/jquery.validate.min.js"></script>
	<script src="/js/plugins/iCheck/icheck.min.js"></script>

	@include('scripts.form-modal-script')
	@include('scripts.gmaps-address-lookup-api3')

	<script type="text/javascript">
		console.log('aaaaa');
		$("#user_basics_form").validate({
			errorPlacement: function (error, element)
			{
				element.after(error);
			},
			rules: {
				email: {
					required: true,
					email: true,
					remote: {
						type: "POST",
						url: "{{ url('/admincheckemail') }}",
						data: {
							email: function(){
								var email = $("#email").val();
								return email;
							},
							_token: "{{ csrf_token() }}"
						}

					}
				}
			},
			messages: {
				email: {
					required: "メールアドレスを入力してください。",
					email: "メールアドレスを入力してください。",
					remote: "入力したメールアドレスは既に登録されています。",
				}
			}
		});

		function updateemail(){
			var isValid = $('#user_basics_form').valid();
			if($('#email').val() == '')
				return;
			if(!isValid)
				return;
			$('#confirmEmailForm').modal('show');
		}
		function saveEmailChange(){
			$('#user_basics_form').submit();
		}
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
