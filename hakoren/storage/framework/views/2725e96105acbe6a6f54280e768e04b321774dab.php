<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noydir, noodp, nosnippet, noimageindex">
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php if(trim($__env->yieldContent('template_title'))): ?><?php echo $__env->yieldContent('template_title'); ?> | <?php endif; ?>
        ハコレン管理者
    </title>
    <meta name="description" content="">
    <meta name="author" content="Motocle.com">
    <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/adminfavicon.ico">

    <link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />
    <link href="<?php echo e(URL::to('/')); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/style.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/admin.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/mainadmin.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/toast/jquery.toast.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/jquery-ui/jquery-ui.css" rel="stylesheet">
    <!-- BEGIN THEME GLOBAL STYLES -->
    
    
    <!-- END THEME GLOBAL STYLES -->

    
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    
    <?php echo $__env->yieldContent('template_linked_fonts'); ?>

    

    <?php echo $__env->yieldContent('template_linked_css'); ?>

    <style type="text/css">
        <?php echo $__env->yieldContent('template_fastload_css'); ?>

            <?php if(Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0)): ?>
                .user-avatar-nav {
        
        /*background-size: auto 100%;*/
        }
        <?php endif; ?>

    </style>

    
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>;
    </script>

    <!-- Mainly scripts -->
    <script src="<?php echo e(URL::to('/')); ?>/js/jquery-2.1.1.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/toast/jquery.toast.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/jquery-ui/jquery-ui.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?php echo e(URL::to('/')); ?>/js/inspinia.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/pace/pace.min.js"></script>

    <?php echo $__env->yieldContent('head'); ?>

</head>
<body class="top-navigation">

<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="clearfix border-bottom white-bg adminnav">
            <?php echo $__env->make('partials.adminnav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
        <div class="wrapper wrapper-content row" style="min-height:1000px;">
            <?php echo $__env->make('partials.form-status', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="fullcontainer">
                <div class="col-md-12">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
        <div class="footer" style="background: #aaa;text-align: center;">
            <div style="padding:0px;padding:3px 0px;">
                <span style="color:#fff">Copyright @ <?php echo e(date('Y')); ?> Hakoren All Rights Reserved.</span>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->yieldContent('footer_scripts'); ?>

</body>
</html>