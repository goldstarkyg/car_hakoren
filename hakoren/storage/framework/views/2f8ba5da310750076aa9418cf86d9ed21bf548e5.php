<?php $__env->startSection('template_title'); ?>
    <?php echo trans('auth.login'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .body-content{
            padding-bottom: 10px !important;
        }
    </style>
    <style>
        body{
			background:url(<?php echo e(URL::to('/')); ?>/images/bg.png); background-repeat:repeat;
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
						<img class="center-block img-responsive" src="<?php echo e(URL::to('/')); ?>/img/login-adminlogo.png" alt="">
					</div>
				</div>
                <div class="panel panel-default">
                    <div class="panel-body">
						<div class="clearfix">
							<div class="col-md-10 col-md-offset-1 col-xs-12">
								<form class="form-horizontal" role="form" method="POST" action="<?php echo e(route('mtclsecuredlogin')); ?>">
									<?php echo e(csrf_field()); ?>


									<div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
										<label for="email" class="control-label">
										<?php echo trans('auth.email'); ?></label>

										<input id="email" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>"
											   oninvalid="this.setCustomValidity('このフィールドを記入してください')" oninput="setCustomValidity('')" required autofocus>

										<?php if($errors->has('email')): ?>
											<span class="help-block">
											<strong><?php echo e($errors->first('email')); ?></strong>
										</span>
										<?php endif; ?>
									</div>

									<div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
										<label for="password" class="control-label">
											<?php echo trans('auth.password'); ?>

										</label>

										<input id="password" type="password" class="form-control" name="password"
											   oninvalid="this.setCustomValidity('このフィールドを記入してください')" oninput="setCustomValidity('')" required>

										<?php if($errors->has('password')): ?>
											<span class="help-block">
											<strong><?php echo e($errors->first('password')); ?></strong>
										</span>
										<?php endif; ?>
									</div>

									<div class="form-group">
										<div class="col-md-12 col-md-offset-0">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
													<?php echo trans('auth.rememberMe'); ?>

												</label>
											</div>
										</div>
									</div>

									<div class="form-group margin-bottom-3 ">
										<div class="row">
											<div class="col-md-12 col-md-offset-0">
												<button type="submit" class="btn btn-primary pull-right">
													<?php echo trans('auth.login'); ?>

												</button>
											</div>
										</div>
									</div>

									<!--<p class="text-center margin-bottom-3">
									Or Login with
								</p>

								<?php echo $__env->make('partials.socials-icons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
											-->
								</form>
							</div>
						</div>
                    </div>
                </div><!-- panel -->
				

				<a class="btn btn-link" href="<?php echo e(route('password.request')); ?>">
					<?php echo trans('auth.forgot'); ?>

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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminloginapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>