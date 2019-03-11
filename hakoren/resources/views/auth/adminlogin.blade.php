@extends('layouts.adminloginapp')
@section('template_title')
    {!! trans('auth.login') !!}
@endsection
@section('content')
    <style>
        .body-content{
            padding-bottom: 10px !important;
        }
    </style>
    <style>
        body{
			background:url({{URL::to('/')}}/images/bg.png); background-repeat:repeat;
			background-color: #dad1d1 !important;
		}
        #page-wrapper{
            background: transparent !important;
        }
        .gray-bg{
            background-color: transparent;
        }
    </style>
    <div class="container logincontainer">
        <div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 panel-login">
				<div class="clearfix">
					<div class="col-sm-10 col-sm-offset-1 col-xs-12" style="margin-bottom:30px;">
						<img class="center-block img-responsive" src="{{ URL::to('/') }}/img/login-adminlogo.png" alt="">
					</div>
				</div>
                <div class="panel panel-default">
                    <div class="panel-body">
						<div class="clearfix">
							<div class="col-md-10 col-md-offset-1 col-xs-12">
								<form class="form-horizontal" role="form" method="POST" action="{{ route('mtclsecuredlogin') }}">
									{{ csrf_field() }}

									<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
										<label for="email" class="control-label">
										{!! trans('auth.email') !!}</label>

										<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
											   oninvalid="this.setCustomValidity('このフィールドを記入してください')" oninput="setCustomValidity('')" required autofocus>

										@if ($errors->has('email'))
											<span class="help-block">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
										@endif
									</div>

									<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
										<label for="password" class="control-label">
											{!! trans('auth.password') !!}
										</label>

										<input id="password" type="password" class="form-control" name="password"
											   oninvalid="this.setCustomValidity('このフィールドを記入してください')" oninput="setCustomValidity('')" required>

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
													<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
													{!! trans('auth.rememberMe') !!}
												</label>
											</div>
										</div>
									</div>

									<div class="form-group margin-bottom-3 ">
										<div class="row">
											<div class="col-md-12 col-md-offset-0">
												<button type="submit" class="btn btn-primary pull-right">
													{!! trans('auth.login') !!}
												</button>
											</div>
										</div>
									</div>

									<!--<p class="text-center margin-bottom-3">
									Or Login with
								</p>

								@include('partials.socials-icons')
											-->
								</form>
							</div>
						</div>
                    </div>
                </div><!-- panel -->
				

				<a class="btn btn-link" href="{{ route('password.request') }}">
					{!! trans('auth.forgot') !!}
				</a>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            var height =  $(window).height() - 50;
            $('.logincontainer').css('height', height+'px');

        });
    </script>
@endsection

