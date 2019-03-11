@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_mypage.css" rel="stylesheet" type="text/css">
    {{--<link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">--}}
    {{--<link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">--}}
    <style>
	.error-class{
		text-align:left !important;
		color:#b63432;
		font-size:13px;
	}
    </style>
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
@section('content')
    <div class="page-container">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head hidden-xs">
            <div class="container clearfix">
                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="{{URL::to('/')}}"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="{!! url('/mypage/top') !!}">@lang('mypage.mypage')</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span>@lang('mypage.memberinformation')</span>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE TITLE -->
            </div>
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->

            <!-- BEGIN CONTENT HEADER -->
            <div class="dynamic-page-header dynamic-page-header-default">
                <div class="container clearfix">
                    <div class="col-md-12 bottom-border ">
                        <div class="page-header-title">
								<h1>@lang('mypage.memberinformation')</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">
                        
						<div class="content-main col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
							<!-- caution 2 -->
							<div class="caution-block box-shadow p-block bg-darkred" style="padding-top:10px; padding-bottom:10px;">
								<h3 class="bg-grad-gray ">@lang('mypage.confirmation')</h3>
								<div class="clearfix bg-white">			
									<div class="col-lg-12 col-md-12 col-sm-12 co-xs-12">
                                    
@if(Session::has('flash_message'))
<script type="text/javascript">
	$('#updateSuccess').modal('hide');
	var myVar = setInterval(function(){  clearInterval(myVar); $('#updateSuccess').modal('toggle'); }, 500);
</script>
@endif                                    
                                    
                                    
										<p>@lang('mypage.checkchange')</p><p>@lang('mypage.checkchange1')</p>
										
										 
                                         {!! Form::model($user, ['id' => 'profile-form','method' => 'POST', 'files' => true, 'route' => ['mypage.profile']]) !!}
											<div class="row">
												<div class="col-md-12 col-sm-12 col-xs-12">
													
													<p class="yoyaku_ttl">{{ $user->last_name }} {{ $user->first_name }} @lang('mypage.yourinformation')</p>
												</div>
											</div>											
											<div class="row m_B20">
												<div class="col-md-3 col-sm-3 col-xs-12 text-right">
													<label>@lang('mypage.name')</label>
												</div>
												<div class="col-md-9 col-sm-9 col-xs-12">
													<div class="row">
														<div class="col-md-6 col-sm-6 col-xs-6">
                                            			@if($util->lang() == 'ja')
                                                			{!! Form::text('last_name', null, ['class' => 'form-control h40','placeholder'=>'姓','maxlength'=>'255']) !!}
															{!! $errors->first('last_name', '<span class="error-class">:message</span>') !!}
                                                		@endif
														@if($util->lang() == 'en')
															{!! Form::text('last_name', null, ['class' => 'form-control h40','placeholder'=>'Sur Name','maxlength'=>'255']) !!}
															{!! $errors->first('last_name', '<span class="error-class">:message</span>') !!}
														@endif
														</div>
														<div class="col-md-6 col-sm-6 col-xs-6">
														@if($util->lang() == 'ja')
															{!! Form::text('first_name', null, ['class' => 'form-control h40','placeholder'=>'名','maxlength'=>'255']) !!}
															{!! $errors->first('first_name', '<span class="error-class">:message</span>') !!}
														@endif
														@if($util->lang() == 'en')
															{!! Form::text('first_name', null, ['class' => 'form-control h40','placeholder'=>'First Name','maxlength'=>'255']) !!}
															{!! $errors->first('first_name', '<span class="error-class">:message</span>') !!}
														@endif
														</div>
													</div>
												</div>
											</div>											
											<div class="row">
												<div class="error-box col-md-12 col-sm-12 col-xs-12">
													<span class="error-class erroremail"></span>
												</div>
											</div>
											<div class="row m_B20">
												<label class="col-md-3 col-sm-3 col-xs-12 text-right" style="margin-bottom:3px;">@lang('mypage.phonetic')</label>
												<div class="col-md-9 col-sm-9 col-xs-12">
													<div class="row">
														<div class="col-md-6 col-sm-6 col-xs-6">
														   @if($util->lang() == 'ja')
																{!! Form::text('furi_last_name', old('furi_last_name')?old('furi_last_name'):$user->profile->fur_last_name, ['class' => 'form-control h40','placeholder'=>'セイ','maxlength'=>'255']) !!}
																{!! $errors->first('furi_last_name', '<span class="error-class">:message</span>') !!}
															@endif
														   @if($util->lang() == 'en')
															   {!! Form::text('furi_last_name', old('furi_last_name')?old('furi_last_name'):$user->profile->fur_last_name, ['class' => 'form-control h40','placeholder'=>'Surname','maxlength'=>'255']) !!}
															   {!! $errors->first('furi_last_name', '<span class="error-class">:message</span>') !!}
														   @endif
														</div>
														<div class="col-md-6 col-sm-6 col-xs-6">
															@if($util->lang() == 'ja')
															  {!! Form::text('furi_first_name', old('furi_first_name')?old('furi_first_name'):$user->profile->fur_first_name, ['class' => 'form-control h40','placeholder'=>'メイ','maxlength'=>'255']) !!}
															  {!! $errors->first('furi_first_name', '<span class="error-class">:message</span>') !!}
															 @endif
															@if($util->lang() == 'en')
															{!! Form::text('furi_first_name', old('furi_first_name')?old('furi_first_name'):$user->profile->fur_first_name, ['class' => 'form-control h40','placeholder'=>'Last Name','maxlength'=>'255']) !!}
															{!! $errors->first('furi_first_name', '<span class="error-class">:message</span>') !!}
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="row m_B20">
												<div class="col-md-3 col-sm-3 col-xs-12 text-right">
													<label>@lang('mypage.mailaddress')</label>
												</div>
												<div class="col-md-9 col-sm-9 col-xs-12">
													@if($util->lang() == 'ja')
														  {!! Form::text('email', null, ['class' => 'form-control h40','placeholder'=>'メールアドレス','maxlength'=>'255','style'=>"margin-bottom:5px;"]) !!}
														  {!! $errors->first('email', '<span class="error-class">:message</span>') !!}
                                                    @endif
													@if($util->lang() == 'en')
														{!! Form::text('email', null, ['class' => 'form-control h40','placeholder'=>'Mail Address','maxlength'=>'255','style'=>"margin-bottom:5px;"]) !!}
														{!! $errors->first('email', '<span class="error-class">:message</span>') !!}
													@endif
												</div>
											</div>
											<div class="row m_B20">
												<div class="col-md-3 col-sm-3 col-xs-12 text-right">
													<label>@lang('mypage.phonenumber')</label>
												</div>
												<div class="col-md-9 col-sm-9 col-xs-12">
													@if($util->lang() == 'ja')
													  {!! Form::text('phone', old('phone')?old('phone'):$user->profile->phone, ['class' => 'form-control h40','placeholder'=>'日中に連絡がとれる電話番号をご入力ください','maxlength'=>'25']) !!}
													  {!! $errors->first('phone', '<span class="error-class">:message</span>') !!}
                                  					@endif
													@if($util->lang() == 'en')
														{!! Form::text('phone', old('phone')?old('phone'):$user->profile->phone, ['class' => 'form-control h40','placeholder'=>'Please enter the telephone number.','maxlength'=>'25']) !!}
														{!! $errors->first('phone', '<span class="error-class">:message</span>') !!}
													@endif
												</div>
											</div>
											<div class="row m_B20">
												<div class="col-md-3 col-sm-3 col-xs-12 text-right">
													<label>@lang('mypage.postalcode')</label>
												</div>
												<div class="col-md-9 col-sm-9 col-xs-12">
													@if($util->lang() == 'ja')
													  {!! Form::text('postal_code', old('postal_code')?old('postal_code'):$user->profile->postal_code, ['onKeyUp'=>"AjaxZip3.zip2addr(this,'','address','address');",'onBlur'=>"AjaxZip3.zip2addr(this,'','address','address');",'class' => 'form-control h40','placeholder'=>'郵便番号はハイフンなしの半角数字を入力ください。','maxlength'=>'200']) !!}
													  {!! $errors->first('postal_code', '<span class="error-class">:message</span>') !!}
                                                 	@endif
													@if($util->lang() == 'en')
														{!! Form::text('postal_code', old('postal_code')?old('postal_code'):$user->profile->postal_code, ['onKeyUp'=>"AjaxZip3.zip2addr(this,'','address','address');",'onBlur'=>"AjaxZip3.zip2addr(this,'','address','address');",'class' => 'form-control h40','placeholder'=>'Please enter the zip code.','maxlength'=>'200']) !!}
														{!! $errors->first('postal_code', '<span class="error-class">:message</span>') !!}
													@endif
												</div>
											</div>
											<div class="row m_B20">
												<div class="col-md-3 col-sm-3 col-xs-12 text-right">
													<label>@lang('mypage.address')</label>
												</div>
												<div class="col-md-9 col-sm-9 col-xs-12">
													@if($util->lang() == 'ja')
														  {!! Form::text('address', old('address')?old('address'):$user->profile->address1, ['class' => 'form-control h40','placeholder'=>'郵便番号を入力すると自動挿入されます。','maxlength'=>'255']) !!}
														  {!! $errors->first('address', '<span class="error-class">:message</span>') !!}
                                                 	@endif
													@if($util->lang() == 'en')
														{!! Form::text('address', old('address')?old('address'):$user->profile->address1, ['class' => 'form-control h40','placeholder'=>'When you enter a postal code, it is automatically inserted.','maxlength'=>'255']) !!}
														{!! $errors->first('address', '<span class="error-class">:message</span>') !!}
													@endif
												</div>
											</div>																					{{--	
											<div class="row m_B20">
												<div class="col-md-3 col-sm-3 col-xs-12 text-right">
													<label>免許証</label>
												</div>
												<div class="col-md-9 col-sm-9 col-xs-12">
													
															
													<div class="row">
														<div class="imgInput col-md-6 col-sm-6 col-xs-12">
															<div class="sec-text"><p><span>表面</span></p></div>
															<img id="license_surface_img" src="{!! ($user->profile->license_surface)?url('images/profile/'.$user->id.'/licensesurface/'.$user->profile->license_surface):url('/img/license_omote.png') !!}" alt="" class="imgView img-responsive">															
															
														</div>
														<div class="imgInput col-md-6 col-sm-6 col-xs-12">
															
															<div class="sec-text"><p><span>免許証を変更する場合は</span>アップロードして下さい。</p></div>
															 
				 
                <input type="file" accept="image/*" capture="camera" name="license_surface" onchange="javascript:readURL(this,'license_surface_img');">
                
                {!! $errors->first('license_surface', '
                <span class="error-class">:message</span>
                ') !!}
                															
															
														</div>
													</div>
													<hr/>
													<div class="row">
														<div class="imgInput col-md-6 col-sm-6 col-xs-12">
															<div class="sec-text"><p><span>裏面</span></p></div>
															                                                            
															<img id="license_back_img" src="{!! ($user->profile->license_back)?url('images/profile/'.$user->id.'/licenseback/'.$user->profile->license_back):url('/img/license_ura.png') !!}" alt="" class="imgView img-responsive">															
															
														</div>
														<div class="imgInput col-md-6 col-sm-6 col-xs-12">
															<div class="sec-text"><p><span>免許証を変更する場合は</span>					アップロードして下さい。</p></div>															 
                 
                <input type="file" accept="image/*" capture="camera" name="license_back" onchange="javascript:readURL(this,'license_back_img');">                  
                
                {!! $errors->first('license_back', '
                <span class="error-class ">:message</span>
                ') !!}                                                            
                                                            
														</div>
													</div>
													<hr/>
													
												</div>
												
											</div>
											--}}
											<div class="row">
												<div class="error-box col-md-12 col-sm-12 col-xs-12">
													<span class="error-class errorphone"></span>
												</div>
											</div>
											<div class="row m_B40">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3 col-xs-offset-0">
													<input type="submit" name="submit" class="submitBtn form-control h40" value="@lang('mypage.register')">
												</div>
											</div>
										 {!! Form::close() !!} 
										 
										 										
									</div>
								</div>
								
							
							</div>
							<!-- caution 2 -->					
									 
                        </div>
                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 col-sm-3 col-xs-12">
								
							<div class="mp-menu">
								<h3>@lang('mypage.pagemenu')</h3>
								<ul>
									<a href="{{URL::to('/')}}/mypage/top"><li>@lang('mypage.bookinglist')</li></a>
									<a href="{{URL::to('/')}}/mypage/log"><li>@lang('mypage.reservationhistory')</li></a>
									<a href="{{URL::to('/')}}/mypage/profile"><li>@lang('mypage.memberinformation')</li></a>
									<a href="{{URL::to('/mypage/faq')}}"><li>@lang('mypage.faq')</li></a>
									<a href="{{URL::to('/mypage/changepassword')}}"><li>@lang('mypage.changepass')</li></a>
									<a href="{{URL::to('/')}}/logout"><li>@lang('mypage.logout')</li></a>
								</ul>
							</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link">@lang('mypage.toppage')</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
 
    </div>
 
<div class="modal fade modal-success" id="updateSuccess" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('mypage.save')</h4>
      </div>
      <div class="modal-body">
        <p id="mdl_success_msg">@lang('mypage.yourchange')</p>
      </div>
      <div class="modal-footer text-center">
        <button class="btn btn-outline btn-success" data-dismiss="modal">@lang('mypage.closeup')</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('style')

@endsection

@section('footer_scripts')
 
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/localization/messages_ja.js" type="text/javascript"></script>
<script type="text/javascript">	
	//全角ひらがなのみ
	jQuery.validator.addMethod("katakana", function(value, element) {
	 return this.optional(element) || /^([ァ-ヶー]+)$/.test(value);
	 }, "全角カタカナを入力下さい"
	);
</script>
<script src="{{URL::to('/')}}/js/plugins/kana/jquery.autoKana.js" type="text/javascript"></script>
<script type="text/javascript"><!--
//jQuery.noConflict();
	$(function() {
		$.fn.autoKana('#first_name', '#furi_first_name', {
			katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
		});
		$.fn.autoKana('#last_name', '#furi_last_name', {
			katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
		});
	});
	-->
</script>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
@endsection