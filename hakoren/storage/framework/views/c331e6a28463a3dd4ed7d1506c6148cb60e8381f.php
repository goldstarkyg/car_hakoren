<?php

if(strpos($_SERVER['HTTP_HOST'], 'hakoren') === false ) {
    session(['noindex' => true]);
} else {
    session(['noindex' => false]);
}

?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<!DOCTYPE html>

<html lang="<?php echo e($util->lang()); ?>">
<head>

<?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false): ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src= 'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })(window,document,'script','dataLayer','GTM-MDPHDVS');</script>
    <!-- End Google Tag Manager -->
    <?php elseif(strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false): ?>
    <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-K6XPRRM');</script>
        <!-- End Google Tag Manager -->
    <?php endif; ?>

    <meta charset="utf-8" />
    <title><?php echo app('translator')->getFromJson('search.discount'); ?> | <?php if(trim($__env->yieldContent('template_title'))): ?><?php echo $__env->yieldContent('template_title'); ?> | <?php endif; ?> <?php echo e(config('app.name', Lang::get('titles.app'))); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<?php if(isset($noindex)): ?>
    <meta name="robots" content="noindex,nofollow" />
	<?php elseif(Session::get('noindex')): ?>
    <meta name="robots" content="noindex,nofollow" />
	<?php else: ?>
    <meta name="robots" content="index,follow" />
	<?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/favicon.ico">
    <?php $util = app('App\Http\DataUtil\ServerPath'); ?>
	<?php if(isset($meta_info)): ?>
        <?php
        $meta_description   = $util->Tr('meta_description');
        $meta_keywords      = $util->Tr('meta_keywords');
        $title              = $util->Tr('title');
        $featured_image     = $util->Tr('featured_image');
        ?>
		<meta name="description" content="<?php echo e($meta_info->$meta_description); ?>"/>
		<meta name="keywords" content="<?php echo e($meta_info->$meta_keywords); ?>"/>
		<meta property="og:title" content="<?php echo e($meta_info->$title); ?>" />
		<meta property="og:url" content="<?php echo e(url('/'.$meta_info->slug)); ?>" />
		<meta property="og:image" content="<?php echo URL::to('/').$meta_info->$featured_image; ?>" />
		<meta property="og:description" content="<?php echo $meta_info->$meta_description; ?>" />
		<meta property="og:site_name" content="<?php echo app('translator')->getFromJson('front.hako'); ?> />
	<?php else: ?>
		<?php echo $__env->yieldContent('og_tags'); ?>
		<meta name="description" content="<?php echo $__env->yieldContent('meta_description'); ?>"/>
		<meta name="keywords" content="福岡 レンタカー, 福岡空港, レンタカー, ハコレンタカー, 福岡空港格安レンタカー, 那覇空港, 沖縄 レンタカー, 沖縄格安レンタカー, 那覇空港レンタカー, 10人乗りハイエース, 格安ハイエース, 大人数用レンタカー, ワゴン車レンタカー, ボックスワゴン格安レンタカー, アルファード 格安レンタル, 空港レンタカー, マイクロバス レンタル"/>
	<?php endif; ?>
    <meta name="author" content="<?php echo app('translator')->getFromJson('front.moto'); ?>"/>
    <link rel="alternate" hreflang="ja" href="http://motocle.com/" />
    <!-- notosans fonts -->
    <link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,700i,900i" rel="stylesheet">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"/>
    <link href="<?php echo e(URL::to('/')); ?>/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo e(URL::to('/')); ?>/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::to('/')); ?>/admin_assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?php echo e(URL::to('/')); ?>/admin_assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?php echo e(URL::to('/')); ?>/admin_assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="<?php echo e(URL::to('/')); ?>/admin_assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::to('/')); ?>/admin_assets/layouts/layout/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="<?php echo e(URL::to('/')); ?>/admin_assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <!-- BEGIN THEME GLOBAL CUSTOM STYLES -->
    <link href="<?php echo e(URL::to('/')); ?>/css/common_custom.css" rel="stylesheet" id="style_components" type="text/css" />

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="<?php echo e(URL::to('/')); ?>/admin_assets/pages/css/about.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::to('/')); ?>/css/megamenu.css" rel="stylesheet"/>
    <!-- END PAGE LEVEL STYLES -->
    <!-- END THEME GLOBAL CUSTOM STYLES -->
    <?php echo $__env->yieldContent('template_linked_css'); ?>

<!--BEGIN CUSTOM ADD-->
    <script src="<?php echo e(URL::to('/')); ?>/js/jquery-2.1.1.js" ></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/bootstrap.min.js" ></script>

	<script src="<?php echo e(URL::to('/')); ?>/js/custom.js" ></script>

    <!--END CUSTOM-->
	<link href="<?php echo e(URL::to('/')); ?>/css/custom-style.css" rel="stylesheet" />
</head>

<body class="page-container-bg-solid page-boxed page-header-menu-fixed  drawer drawer--right">

<?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false): ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MDPHDVS" height="0" width="0" style="display:none; visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php elseif(strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false): ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K6XPRRM" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php endif; ?>

   <?php echo $__env->make('partials.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
   
<div class="page-container">
    <?php echo $__env->yieldContent('content'); ?>
</div>
<div class="footer" style="padding:0px;">
    <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>

<?php echo $__env->yieldContent('footer_scripts'); ?>
<script type="text/javascript">
$(function(){
  $('a[href^="#"].totop-link').click(function(){
    var speed = 500;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
});
</script>
</body>
</html>
