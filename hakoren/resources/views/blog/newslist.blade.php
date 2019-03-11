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
            <li> <a href="{!! url('/') !!}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> </li>
            <li> <a href="{!! url('/info') !!}">Blog</a> <i class="fa fa-angle-right"></i> </li>
            <li> <span>News</span> </li>
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
          <div class="row">
            <div class="col-sm-8 common-block-left">
              <div class="notice-listing-block">
                <h2>最新記事</h2>
                
                @if(count($posts)) 
                @foreach($posts as $post) 
                 
                <div class="notice-inner-block">                
                  <h2> <span>{!! date('Y', strtotime($post->updated_at)).'年'.date('m', strtotime($post->updated_at)).'月'.date('d',strtotime($post->updated_at)).'日' !!}</span>
                    <ul class="notice-tag-block">
                        @if(count($post['blogtag']))
                        	@foreach($post['blogtag'] as $tag)                                
                            <li class="@if($loop->iteration %2 == 0) tag2 @elseif($loop->iteration %3 == 0) tag3 @else tag1 @endif">
                             <a title="View all posts in {!! $tag->title !!}" href="{{ url('/blog-tags/'.$tag->slug) }}">{!! $tag->title !!}</a>
                            </li>                             
                            @endforeach
                        @endif                      
                    </ul>
                    <p>{!! $post->title !!}</p>
                  </h2>
                  <p>{!! strip_tags($post->post_content) !!}</p>
                </div>
                
                @endforeach
                @else
                No News Found
                @endif
                
              </div>
              <div class="pagination-block">
                {!! $posts->appends($all_inputs)->links('pagination.default') !!}    
              </div>
            </div>
            <div class="col-sm-4 common-block-right">
              	  <div class="blog-listing-block"> 
              		<img class="img-responsive" src="{!! URL::asset('img/sidebar-demo1.jpg') !!}" alt=""> 
                    <img class="img-responsive" src="{!! URL::asset('img/sidebar-demo2.jpg') !!}" alt=""> 
                  </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end search --> 
    </div>
    <!-- END CONTENT --> 
    
  </div>
@endsection

@section('style')
<style>

  </style>
@endsection

@section('footer_scripts')
@endsection

 