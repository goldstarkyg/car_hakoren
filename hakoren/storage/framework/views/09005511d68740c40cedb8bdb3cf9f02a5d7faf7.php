<?php $__env->startSection('content'); ?>
<div class="container" style="min-height:85vh; padding-top:10px;">
	<!-- BEGIN PAGE TITLE -->
	<div class="page-title">
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<a href="<?php echo e(URL::to('/')); ?>"><i class="fa fa-home"></i></a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a href="#"><?php echo app('translator')->getFromJson('login.memberlogin'); ?></a>
			</li>
		</ul>
	</div>
	<!-- END PAGE TITLE -->

	<!-- BEGIN CONTENT HEADER -->
	<div class="dynamic-page-header dynamic-page-header-default">
		<div class="page-header-title">
			<h1 class="text-left"><?php echo app('translator')->getFromJson('login.memberlogin'); ?></h1>
			<p class="text-left"><?php echo app('translator')->getFromJson('login.email'); ?></p>
		</div>
	</div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 panel-login m_T0 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
					<div class="clearfix">
						<div class="col-md-10 col-md-offset-1 col-xs-12">
							<form class="form-horizontal" role="form" method="POST" action="<?php echo e(route('login')); ?>">
								<?php echo e(csrf_field()); ?>

								<div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
									<label for="email" class="control-label"><?php echo app('translator')->getFromJson('login.mail'); ?></label>

										<input id="email" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" required autofocus>

										<?php if($errors->has('email')): ?>
											<span class="help-block">
												<strong><?php echo e($errors->first('email')); ?></strong>
											</span>
										<?php endif; ?>
								</div>

								<div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
									<label for="password" class="control-label"><?php echo app('translator')->getFromJson('login.password'); ?></label>

										<input id="password" type="password" class="form-control" name="password" required>

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
												<input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>> <?php echo app('translator')->getFromJson('login.rememberpassword'); ?>
											</label>
										</div>
									</div>
								</div>

								<div class="form-group margin-bottom-3">
									<div class="row">
										<div class="col-md-12 col-md-offset-0">
                                            
											<button type="submit" class="btn btn-primary pull-right">
												<?php echo app('translator')->getFromJson('login.login'); ?>
											</button>
										</div>
									</div>
								</div>

								
									
								

								

							</form>
                            
						</div>
					</div>
                </div>
            </div><!-- panel -->
                                            <a href="<?php echo e(route('password.request')); ?>" style="margin-bottom:10px;color: #337ab7;display:block;"><?php echo app('translator')->getFromJson('auth.forgot'); ?></a>
				

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>