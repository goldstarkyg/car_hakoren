@extends('layouts.frontend')
@section('template_linked_css')
<link href="{{ URL::asset('css/page_search.css') }}" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html">
<link href="{{ URL::asset('css/chosen.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900" rel="stylesheet">
<style>
.chosen-single{
	height: 2.2em !important;
}
/**/
@media screen and (max-width: 768px){ 
.view_post_wrap .blog-archive-left{
	margin-bottom: 50px;
}
}

@media screen and (max-width: 767px){ 
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

@media screen and (max-width: 425px){ 
.view_post_wrap .blog-archive-left{
	margin-bottom: 0px;
}
}

/**/
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
                            <a href="{!! url('/') !!}"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="{!! url('/info') !!}">旅先情報</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span>{!! $postinfo['title'] !!}</span>
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
										<h2 class="space_view">{!! $postinfo['title'] !!}</h2>
										<ul>
											<li><a href="{{ url('/info/'.$postinfo['shop']->slug) }}">{!! $postinfo['shop']->title !!}</a></li>
											<li class="date"><a href="javascript:void(0);">{!! date('Y/m/d', strtotime($postinfo['publish_date'])) !!}</a></li>
											<li><a href="{{ url('/info/'.$postinfo['shop']->slug.'/'.$postinfo['posttag']->slug) }}">{!! $postinfo['posttag']->title !!}</a></li>
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
											<img class="img-responsive" src="{!! URL::to('/').$postinfo['featured_image'] !!}" alt="">
										</div>
										<div class="blog-contents ">
											<p>{!! $postinfo['post_content'] !!}</p>
										</div>
									</div>
									 
									@if(count($postinfo['blogtag']))                                
									<div class="blog-detail-tag">
										<h2>この記事の関連タグ</h2>
										<ul>
											@foreach($postinfo['blogtag'] as $tag) 
											<li><a rel="Posts in tag" title="View all posts in {!! $tag->title !!}" href="{{ url('/blog-tags/'.$tag->slug) }}"><span class="hashtag02"><i class="fa fa-hashtag"></i></span>{!! $tag->title !!}</a></li>
											@endforeach                                         
										</ul>
									</div>
									@endif
								</div>
                                
                                @if(count($posts))  
								<div class="article-block detail-blog-article box-shadow">
									<h2>最近の記事</h2>
									@foreach($posts as $post) 
									<div class="article-listing">
										<div class="article-listing-left">
											<div class="article-listing-img">
												<img src="{!! URL::to('/').$post->featured_image !!}" alt="">
											</div>
										</div>
										<div class="article-listing-right">
											<div class="article-listing-content">
												<h2><a href="{{ url('/view-post/'.$post->slug) }}">{!! $post->title !!}</a></h2>
												<p>{!! substr(strip_tags($post->post_content),0,160) !!}..</p>
												<ul>
													<li><i class="fa fa-eye"></i>{!! $post->post_views !!}</li>
													<li><i class="fa fa-eye"></i><a href="{{ url('/info/'.$post->shop->slug.'/'.$post->posttag->slug) }}">&nbsp;{!! $post->posttag->title !!}</a></li>
												</ul>
											</div>
										</div>
									</div>
									@endforeach       
								</div>
                            	@endif
                                
							</div>
                        </div>
						@include('partials.blogsidebar') 
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
     
    @include('modals.modal-membership')
    @include('modals.modal-error')
@endsection

@section('style')
  <style>

  </style>
@endsection

@section('footer_scripts')
@endsection


@section('template_title', $postinfo['title'])
@section('meta_description', $postinfo['meta_description'])

@section('og_tags')
    <meta property="og:title" content="{!! $postinfo['title'] !!}" />
    <meta property="og:url" content="{{ url('/view-post/'.$postinfo['slug']) }}" />
    <meta property="og:image" content="{!! URL::to('/').$postinfo['featured_image'] !!}" />
    <meta property="og:description" content="{!! $postinfo['meta_description'] !!}" />
    <meta property="og:site_name" content="ハコレンタカー" />
@endsection