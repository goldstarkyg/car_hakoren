@extends('layouts.adminapp')

@section('template_title')
    ブログの詳細
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
		.form-group{
		 padding: 10px 0 !important;
		}
    </style>
@endsection

@section('content')
  
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
  
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>ブログ詳細: {{ $blogpost->title }}
                    <a href="{{URL::to('/')}}/adminblog/blogposts/{{$blogpost->id}}/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                      　 編集する
                    </a>
                    <a href="{{URL::to('/')}}/adminblog/blogposts" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">タイトル</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$blogpost->title}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">スラッグ</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$blogpost->slug}}
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">店舗</label>
                        <div class="col-sm-9 m-t-xs">
                            {!! $blogpost->shop->name !!}
                        </div>
                    </div>                    
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">記事タグ</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$blogpost->posttag->title}}
                        </div>
                    </div>                                        
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">公開日</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$blogpost->publish_date}}
                        </div>
                    </div>                                                
                    
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">アイキャッチ画像</label>
                        <div class="col-sm-9 m-t-xs">
                            @if($blogpost->featured_image)
                                <img src="{{URL::to('/').$blogpost->featured_image}}" class="img-thumbnail" style="width:100px; height: auto" >
                            @endif
                        </div>
                    </div>                                                   
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">メタディスクプリション/label>
                        <div class="col-sm-9 m-t-xs">
                            {{$blogpost->meta_description}}
                        </div>
                    </div>                                                    
                    
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">内容</label>
                        <div class="col-sm-9 m-t-xs">
                            {!! $blogpost->post_content !!}
                        </div>
                    </div>                                                                                                                                            
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">タグ</label>
                        <div class="col-sm-9 m-t-xs">
                            @foreach($blogpost->blogtag as $tag) 
                            {!! $tag->title !!},  
                            @endforeach
                        </div>
                    </div>                    
                    

                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/plugins/dropzone/dropzone.js"></script>
    <script src="{{URL::to('/')}}/js/alterclass.js"></script>  
    <script src="{{URL::to('/')}}/js/home.js"></script>
@endsection