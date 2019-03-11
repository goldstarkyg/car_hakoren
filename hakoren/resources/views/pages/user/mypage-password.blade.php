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
		display:inline-block;
	}
    </style>
@endsection
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
                            <span>@lang('mypage.changepass')</span>
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
								<h1>@lang('mypage.changepass')</h1>
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
							<div class="caution-block box-shadow p-block bg-darkred">
								<!--<h3 class="bg-grad-gray">パスワードを変更してください</h3>-->
								<div class="clearfix bg-white">
									<div class="col-lg-12 col-md-12 col-sm-12 co-xs-12">
                                            @if(Session::has('flash_message'))
                                            <div class="alert flash-message text-left alert-{!! Session::get('flash_type', 'info') !!} alert-dismissable">
                                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                              {!! Session::get('flash_message') !!} </div>
                                            @endif

                                         {!! Form::model($user, ['id' => 'password-form','method' => 'POST', 'route' => ['mypage.updatepassword']]) !!}
 
                                            											 
											<div class="row m_B20">
												<!--<div class="col-md-4 col-sm-4 col-xs-12 text-right">
													<label>現在のパスワード</label>
												</div>
												<div class="col-md-8 col-sm-8 col-xs-12">

                                              {!! Form::password('current_password', null, ['class' => 'form-control h40','maxlength'=>'25']) !!}
                                              {!! $errors->first('current_password', '<span class="error-class">:message</span>') !!}


												</div>-->
											</div>
                                            
                                           {{--  @if(!Session::get('flash_message')) --}}
											
                                            <div class="row m_B20">
												<div class="col-md-4 col-sm-4 col-xs-12 text-right xs-left">
													<label>@lang('mypage.newpass')</label>
												</div>
												<div class="col-md-8 col-sm-8 col-xs-12">

                              {!! Form::password('password', ['class' => 'form-control h40','maxlength'=>'25']) !!}
                              {!! $errors->first('password', '<span class="error-class">:message</span>') !!}

												</div>
											</div>
											<div class="row m_B20">
												<div class="col-md-4 col-sm-4 col-xs-12 text-right xs-left">
													<label>@lang('mypage.confirmpass')</label>
												</div>
												<div class="col-md-8 col-sm-8 col-xs-12">

                              {!! Form::password('password_confirmation', ['class' => 'form-control h40','maxlength'=>'25']) !!}
                              {!! $errors->first('password_confirmation', '<span class="error-class">:message</span>') !!}

												</div>
											</div>
										 	
                                            {{-- @endif  --}}
										   
											<div class="row m_B40">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4 col-sm-offset-4 col-xs-offset-0">
													<input type="submit" name="submit" class="submitBtn form-control h40" value="@lang('mypage.send')">
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



<div class="modal fade modal-success" id="mdlSuccess" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">成功</h4>
      </div>
      <div class="modal-body">
        <p id="mdl_success_msg">パスワードが変更されました</p>
      </div>
      <div class="modal-footer text-center">
        <a href="{{URL::to('/')}}/mypage/top" class="btn btn-success">はい</a>
      </div>
    </div>
  </div>
</div>


@endsection

@section('style')
  <style>

  </style>
@endsection

@section('footer_scripts')

<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/localization/messages_ja.js" type="text/javascript"></script>
<script type="text/javascript">
@if($user->password_updated and Session::has('flash_message') and (Session::has('flash_type') == 'success'))
	$('#mdlSuccess').modal('show');
@endif
</script>
@endsection
