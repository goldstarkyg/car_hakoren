@extends('layouts.frontend')

@section('template_title')
{!! $page->title !!}
@endsection

@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_{{ $page->slug }}.css" rel="stylesheet" type="text/css">
    {{--<link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">--}}
    {{--<link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">--}}
    <style>
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
                            <a href="{{URL::to('/')}}"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span>{!! $page->title !!}</span>
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
            <div class="dynamic-page-header dynamic-page-header-default">
                <div class="container clearfix">
                    <div class="col-md-12 bottom-border ">
                        <div class="page-header-title">
								<h1>{!! $page->title !!}</h1>
                        </div>
                    </div>
                </div>
            </div>

			{!! $page->page_content !!}
        </div>
        <!-- END CONTENT -->
    </div>

    @include('modals.modal-membership')
    @include('modals.modal-error')
@endsection

@section('style')
@endsection

@section('footer_scripts')
@endsection

@section('meta_description', $page->meta_description)
@section('og_tags')
    <meta property="og:title" content="{!! $page['title'] !!}" />
    <meta property="og:url" content="{{ url('/'.$page['slug']) }}" />
    <meta property="og:image" content="{!! URL::to('/').$page->featured_image !!}" />
    <meta property="og:description" content="{!! $page->meta_description !!}" />
    <meta property="og:site_name" content="ハコレンタカー" />
@endsection
