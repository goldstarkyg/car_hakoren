<?php $__env->startSection('template_linked_css'); ?>
<link href="<?php echo e(URL::asset('css/page_search.css')); ?>" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html">
<link href="<?php echo e(URL::asset('css/chosen.css')); ?>" rel="stylesheet">
<link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900" rel="stylesheet">
<style>
.chosen-single{
	height: 2.2em !important;
}
/**/
@media  screen and (max-width: 768px){ 
.view_post_wrap .blog-archive-left{
	margin-bottom: 50px;
}
}

@media  screen and (max-width: 767px){ 
.view_post_wrap .blog-archive-right{
    display: none;
}
.view_post_wrap .space_view {
    padding: 10px;
    width: 100%;
    display: inline-block;
}
.view_post_wrap .blog-socail-block {
    padding: 0 10px;
}
}

@media  screen and (max-width: 425px){ 
.view_post_wrap .blog-archive-left{
	margin-bottom: 0px;
}
}

/**/
</style>    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>    
    <div class="page-container">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head hidden-xs">
            <div class="container clearfix">
                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo url('/'); ?>"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo url('/info'); ?>">旅先情報</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span><?php echo $postinfo['title']; ?></span>
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
            
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row view_post_wrap">
                        <div class="col-sm-8 blog-archive-left">
                            <div>
								<div class="article-block  blog-detail-block box-shadow" style="margin-top:0px !important;">
									<div class="blog-top-block">
										<h2 class="space_view"><?php echo $postinfo['title']; ?></h2>
										<ul>
											<li><a href="<?php echo e(url('/info/'.$postinfo['shop']->slug)); ?>"><?php echo $postinfo['shop']->title; ?></a></li>
											<li class="date"><a href="javascript:void(0);"><?php echo date('Y/m/d', strtotime($postinfo['publish_date'])); ?></a></li>
											<li><a href="<?php echo e(url('/info/'.$postinfo['shop']->slug.'/'.$postinfo['posttag']->slug)); ?>"><?php echo $postinfo['posttag']->title; ?></a></li>
										</ul>
										<div class="blog-socail-block">
											<ul>								 
												<a href="javascript:void(0);" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;">
													<li class="facebook">
														<span>Facebook</span>
													</li>
												</a>
												<a href="javascript:void(0);" onclick="window.open('http://twitter.com/intent/tweet?status='+encodeURIComponent(location.href),'twitter-share-dialog','width=626,height=436');return false;">
													<li class="twitter">													
															<span>Twitter</span>
													</li>
												</a>				
												<a href="javascript:void(0);" onclick="window.open('https://social-plugins.line.me/lineit/share?url='+encodeURIComponent(location.href),'line-share-dialog','width=626,height=436');return false;">
													<li class="line">													
														<span>Line</span>													
													</li>
												</a>
											</ul>
										</div>
										<div class="blog-top-img">
											<img class="img-responsive" src="<?php echo URL::to('/').$postinfo['featured_image']; ?>" alt="">
										</div>
										<div class="blog-contents ">
											<p><?php echo $postinfo['post_content']; ?></p>
										</div>
									</div>
									 
									<?php if(count($postinfo['blogtag'])): ?>                                
									<div class="blog-detail-tag">
										<h2>この記事の関連タグ</h2>
										<ul>
											<?php $__currentLoopData = $postinfo['blogtag']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
											<li><a rel="Posts in tag" title="View all posts in <?php echo $tag->title; ?>" href="<?php echo e(url('/blog-tags/'.$tag->slug)); ?>"><span class="hashtag02"><i class="fa fa-hashtag"></i></span><?php echo $tag->title; ?></a></li>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                         
										</ul>
									</div>
									<?php endif; ?>
								</div>
                                
                                <?php if(count($posts)): ?>  
								<div class="article-block detail-blog-article box-shadow">
									<h2>最近の記事</h2>
									<?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
									<div class="article-listing">
										<div class="article-listing-left">
											<div class="article-listing-img">
												<img src="<?php echo URL::to('/').$post->featured_image; ?>" alt="">
											</div>
										</div>
										<div class="article-listing-right">
											<div class="article-listing-content">
												<h2><a href="<?php echo e(url('/view-post/'.$post->slug)); ?>"><?php echo $post->title; ?></a></h2>
												<p><?php echo substr(strip_tags($post->post_content),0,160); ?>..</p>
												<ul>
													<li><i class="fa fa-eye"></i><?php echo $post->post_views; ?></li>
													<li><i class="fa fa-eye"></i><a href="<?php echo e(url('/info/'.$post->shop->slug.'/'.$post->posttag->slug)); ?>">&nbsp;<?php echo $post->posttag->title; ?></a></li>
												</ul>
											</div>
										</div>
									</div>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>       
								</div>
                            	<?php endif; ?>
                                
							</div>
                        </div>
						<?php echo $__env->make('partials.blogsidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
                    </div>
					<div class="row">
						<div class="col-md-12 clearfix">
							<a href="#" class="bg-carico totop-link">ページトップへ</a>
						</div>
					</div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->
        
    </div>
     
    <?php echo $__env->make('modals.modal-membership', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('modals.modal-error', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
  <style>

  </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('template_title', $postinfo['title']); ?>
<?php $__env->startSection('meta_description', $postinfo['meta_description']); ?>

<?php $__env->startSection('og_tags'); ?>
    <meta property="og:title" content="<?php echo $postinfo['title']; ?>" />
    <meta property="og:url" content="<?php echo e(url('/view-post/'.$postinfo['slug'])); ?>" />
    <meta property="og:image" content="<?php echo URL::to('/').$postinfo['featured_image']; ?>" />
    <meta property="og:description" content="<?php echo $postinfo['meta_description']; ?>" />
    <meta property="og:site_name" content="ハコレンタカー" />
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>