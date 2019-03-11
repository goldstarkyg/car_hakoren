@extends('layouts.adminapp')

@section('template_title')
    記事タグの詳細
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
                <h2>記事タグの詳細: {{ $posttag->title }}
                    <a href="{{URL::to('/')}}/adminblog/posttags/{{$posttag->id}}/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="{{URL::to('/')}}/adminblog/posttags" class="btn btn-info btn-xs pull-right">
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
                        <label class="col-sm-3 control-label">記事タグタイトル</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$posttag->title}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">記事タグタイトル(en)</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$posttag->title_en}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">スラッグ</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$posttag->slug}}
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