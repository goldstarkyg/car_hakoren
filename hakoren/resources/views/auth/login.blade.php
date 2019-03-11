{{--@extends('layouts.login')--}}
@extends('layouts.frontend')
@section('content')
<div class="container" style="min-height:85vh; padding-top:10px;">
	<!-- BEGIN PAGE TITLE -->
	<div class="page-title">
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<a href="{{URL::to('/')}}"><i class="fa fa-home"></i></a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a href="#">@lang('login.memberlogin')</a>
			</li>
		</ul>
	</div>
	<!-- END PAGE TITLE -->

	<!-- BEGIN CONTENT HEADER -->
	<div class="dynamic-page-header dynamic-page-header-default">
		<div class="page-header-title">
			<h1 class="text-left">@lang('login.memberlogin')</h1>
			<p class="text-left">@lang('login.email')</p>
		</div>
	</div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 panel-login m_T0 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
					<div class="clearfix">
						<div class="col-md-10 col-md-offset-1 col-xs-12">
							<form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
								{{ csrf_field() }}
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label for="email" class="control-label">@lang('login.mail')</label>

										<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

										@if ($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
										@endif
								</div>

								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label for="password" class="control-label">@lang('login.password')</label>

										<input id="password" type="password" class="form-control" name="password" required>

										@if ($errors->has('password'))
											<span class="help-block">
												<strong>{{ $errors->first('password') }}</strong>
											</span>
										@endif
								</div>

								<div class="form-group">
									<div class="col-md-12 col-md-offset-0">
										<div class="checkbox">
											<label>
												<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('login.rememberpassword')
											</label>
										</div>
									</div>
								</div>

								<div class="form-group margin-bottom-3">
									<div class="row">
										<div class="col-md-12 col-md-offset-0">
                                            {{--<a href="{{URL::to('/')}}/forgotpassword" style="margin-top:7px;float:left;color: #337ab7;">Forgot password</a>--}}
											<button type="submit" class="btn btn-primary pull-right">
												@lang('login.login')
											</button>
										</div>
									</div>
								</div>

								{{--<p class="text-center margin-bottom-3">--}}
									{{--Or Login with--}}
								{{--</p>--}}

								{{--@include('partials.socials-icons')--}}

							</form>
                            {{--<a class="btn btn-link" href="{{ route('password.request') }}">Forgot password</a>--}}
						</div>
					</div>
                </div>
            </div><!-- panel -->
                                            <a href="{{ route('password.request') }}" style="margin-bottom:10px;color: #337ab7;display:block;">@lang('auth.forgot')</a>
				

        </div>
    </div>
</div>
@endsection
