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
    
    @media screen and (max-width: 767px){    
    .promotion_recent .blog-archive-right{
        display: none;
    }
    }

    @media screen and (max-width: 700px){
    .promo_wrap .article-listing .article-listing-left{
        width: 100%;
        display: inline-block;
        padding: 0;
    }
    .promo_wrap .article-listing-right {
        float: none;
        width: 100%;
        padding: 0;
        margin: 15px 0 50px 0;
        display: inline-block;
    }

    }

    @media screen and (max-width: 425px){
    .promo_wrap .article-listing-right {
        margin: 15px 0 0 0;
    }

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
                            <a href="{!! url('/') !!}"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="{!! url('/info') !!}">旅先情報</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span>{!! $articletag->title !!}</span>
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
                    <div class="row promotion_recent">
                        <div class="col-sm-8 blog-archive-left">
                            <div class="blog-archive-image">
								<img src="{{ URL::asset('img/blog-top-img.jpg') }}" alt="">
								<h2>ハコレンタカーのもっと自由な車旅ブログ</h2>
							</div>
							<div class="article-block promo_wrap">
								<h2>最新記事</h2>
								@if(count($posts)) 
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
											<h3><a href="{{ url('/info/'.$post->shop->slug) }}">{!! $post->shop->name !!}</a></h3>
											<ul>
												<li><i class="fa fa-eye"></i>{!! $post->post_views !!}</li>
												<li><a href="{{ url('/info/'.$post->shop->slug.'/'.$post->posttag->slug) }}">{!! $post->posttag->title !!}</a></li>
											</ul>
										</div>
										</div>
								</div>
                                @endforeach
                                @endif
							</div>
							<div class="pagination-block">
                                {{ $posts->appends($all_inputs)->links('pagination.default') }}                                								 
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

@section('template_title', 'Latest Articles')
@section('meta_description', 'Latest Articles')
 
 
 @section('og_tags')
    <meta property="og:title" content="{!! $shop->name !!} Latest Articles In {!! $articletag->title !!}" />
    <meta property="og:url" content="{{ url('/info/'.$shop->slug.'/'.$articletag->slug) }}" />
    <meta property="og:image" content="" />
    <meta property="og:description" content="{!! $shop->name !!} Latest Articles In {!! $articletag->title !!}" />
    <meta property="og:site_name" content="ハコレンタカー" />
@endsection