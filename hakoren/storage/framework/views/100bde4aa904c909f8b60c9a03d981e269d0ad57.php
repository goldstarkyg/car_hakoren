<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">
<head>
    
    
    
                
                
                
        
    <!-- End Google Tag Manager -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noydir, noodp, nosnippet, noimageindex">
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>
        <?php if(trim($__env->yieldContent('template_title'))): ?><?php echo $__env->yieldContent('template_title'); ?> | <?php endif; ?>
            ハコレン管理者
    </title>
    <meta name="author" content="Motocle.com">
    <meta name="description" content="">
    <meta property="og:title" content="">
    <meta property="og:type" content="website">
    <meta property="og:description" content="">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <meta property="og:site_name" content="">

    <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/favicon.ico">
        <link href="<?php echo e(URL::to('/')); ?>/css/login.css" rel="stylesheet">
    
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
            background: url(<?php echo e(Gravatar::get(Auth::user()->email)); ?>) 50% 50% no-repeat;
            background-size: auto 100%;
        }
        <?php endif; ?>

    </style>

    
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>;
    </script>


    <link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />

    <link href="<?php echo e(URL::to('/')); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/jquery-ui.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/animate.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/style.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/mainadmin.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/responsive.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/main.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/toast/jquery.toast.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/jquery-ui/jquery-ui.css" rel="stylesheet">

    <!-- Mainly scripts -->
    <script src="<?php echo e(URL::to('/')); ?>/js/jquery-2.1.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?php echo e(URL::to('/')); ?>/js/inspinia.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/pace/pace.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/toast/jquery.toast.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/jquery-ui/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/jpushmenu/css/demo.css" />
    <!--load jPushMenu.css, required-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/jpushmenu/css/jPushMenu.css" />
    <script src="<?php echo e(URL::to('/')); ?>/jpushmenu/js/jPushMenu.js"></script>
    <?php echo $__env->yieldContent('head'); ?>

</head>
<body class="top-navigation" style="background: #f5f8fa;"><!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N3MVFMQ"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<script>
    $(document).ready(function(){
        $('.toggle-menu').jPushMenu();
    });
</script>
<div id="wrapper" class="mainwrapper" style="margin-top:65px;">
    <?php if(isset($lg) !== true): ?>
        <div id="page-wrapper" class="mainwrapper">
            <?php endif; ?>
            <div class="wrapper wrapper-content body-content" style="padding-top:0px;padding-bottom:150px;">
                <?php echo $__env->make('partials.form-status', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
        <?php if(isset($lg) !== true): ?>
</div>
<?php endif; ?>
<script>
    $(document).ready(function() {
        var height = $(window).height();
        console.log(height);
        $('.mainwrapper').css('min-height', height + 'px');
        $('.body-content').css('min-height', (height) + 'px');
    });
</script>
<?php echo $__env->yieldContent('footer_scripts'); ?>

</body>
</html>