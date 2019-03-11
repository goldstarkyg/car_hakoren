@extends('layouts.adminapp')

@section('template_title')
    固定ページの編集
@endsection

@section('template_linked_css')
<link href="{{ URL::asset('plugins/select2/select2.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
		
		
    </style>
@endsection

@section('content')
     
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>固定ページの編集: {{ $page->name }}
					
                    <a @if($meta_only == 0) href="{{ URL::to('/page/' . $page->slug) }}" @else href="{{ URL::to('/' . $page->slug) }}" @endif class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        公開ページを見る
                    </a>
                    <a href="{{URL::to('/')}}/adminpage/webpages" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="panel with-nav-tabs panel-default shadow-box">
                 
                 {!! Form::model($page, array('method' => 'PATCH', 'files' => true,'action' => array('Front\PageController@update', $page->id),  'class' => 'form-horizontal', 'id'=>'editform')) !!}
                 
                    <div class="table-responsive">
                        <table class="table table-bordered">

                            <tr class="{{ $errors->has('title') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="title" >タイトル</label>
                                </td>
                                <td class="col-md-9">
									@if($meta_only == 0)
                                    {!! Form::text('title', NULL,
                                                array('id' => 'title',
                                                'class' => 'form-control',
                                                'placeholder' => 'ページ名')) !!}
									@else
                                    {!! Form::text('title', NULL,
                                                array('id' => 'title',
                                                'class' => 'form-control',
                                                'disabled')) !!}
									@endif
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="{{ $errors->has('title_en') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="title_en" >タイトル(en)</label>
                                </td>
                                <td class="col-md-9">
                                    @if($meta_only == 0)
                                        {!! Form::text('title_en', NULL,
                                                    array('id' => 'title_en',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'ページ名')) !!}
                                    @else
                                        {!! Form::text('title_en', NULL,
                                                    array('id' => 'title_en',
                                                    'class' => 'form-control',
                                                    'disabled')) !!}
                                    @endif
                                    @if ($errors->has('title_en'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title_en') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="{{ $errors->has('slug') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >スラッグ</label>
                                </td>
                                <td class="col-md-9">
									@if($meta_only == 0)
                                    {!! Form::text('slug', NULL,
                                                array('id' => 'slug',
                                                'class' => 'form-control',
                                                'placeholder' => 'Slug')) !!}
									@else
                                    {!! Form::text('slug', NULL,
                                                array('id' => 'slug',
                                                'class' => 'form-control',
                                                'disabled')) !!}
                                    @endif
                                    @if ($errors->has('slug'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('slug') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            
                            <tr class="{{ $errors->has('featured_image') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >og画像</label>
                                </td>
                                <td class="col-md-9">
                            
                            @if($page->featured_image)
                                <img src="{{URL::to('/').$page->featured_image}}" class="img-thumbnail" style="width:100px; height: auto" >
                            @endif
                                                            
                                    {!! Form::file('featured_image', NULL,
                                                array('id' => 'featured_image',
                                                'class' => 'form-control')) !!}
                                    @if ($errors->has('featured_image'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('featured_image') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="{{ $errors->has('featured_image_en') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >og画像(en)</label>
                                </td>
                                <td class="col-md-9">

                                    @if($page->featured_image_en)
                                        <img src="{{URL::to('/').$page->featured_image_en}}" class="img-thumbnail" style="width:100px; height: auto" >
                                    @endif

                                    {!! Form::file('featured_image_en', NULL,
                                                array('id' => 'featured_image_en',
                                                'class' => 'form-control')) !!}
                                    @if ($errors->has('featured_image_en'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('featured_image_en') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            
                             
                            
                            <tr class="{{ $errors->has('meta_description') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >メタディスクリプション</label>
                                </td>
                                <td class="col-md-9">
                                    {!! Form::textarea('meta_description', NULL,
                                                array('id' => 'meta_description',
                                                'class' => 'form-control','style'=>'height:120px;')) !!}
                                    @if ($errors->has('meta_description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('meta_description') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="{{ $errors->has('meta_description_en') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >メタディスクリプション(en)</label>
                                </td>
                                <td class="col-md-9">
                                    {!! Form::textarea('meta_description_en', NULL,
                                                array('id' => 'meta_description_en',
                                                'class' => 'form-control','style'=>'height:120px;')) !!}
                                    @if ($errors->has('meta_description_en'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('meta_description_en') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            
                            <tr class="{{ $errors->has('meta_keywords') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >メタキーワード</label>
                                </td>
                                <td class="col-md-9">
                                    {!! Form::textarea('meta_keywords', NULL,
                                                array('id' => 'meta_keywords',
                                                'class' => 'form-control','style'=>'height:120px;')) !!}
                                    @if ($errors->has('meta_keywords'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('meta_keywords') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="{{ $errors->has('meta_keywords_en') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >メタキーワード(en)</label>
                                </td>
                                <td class="col-md-9">
                                    {!! Form::textarea('meta_keywords_en', NULL,
                                                array('id' => 'meta_keywords_en',
                                                'class' => 'form-control','style'=>'height:120px;')) !!}
                                    @if ($errors->has('meta_keywords_en'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('meta_keywords_en') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            @if($meta_only == 0)
                            <tr class="{{ $errors->has('post_content') ? ' has-error ' : '' }}">
                            	<td colspan="2"><label class="control-label" for="name" >内容</label></td>
                            </tr>
                            
                            <tr class="{{ $errors->has('post_content') ? ' has-error ' : '' }}">
                                <td class="col-md-9" colspan="2">                                 
                                    {!! Form::textarea('page_content', NULL,
                                                array('id' => 'page_content',
                                                'class' => 'form-control',
                                                'id' => 'ckeditor',
                                                'placeholder' => 'Slug')) !!}
                                    @if ($errors->has('post_content'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('post_content') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>     
                        @endif
                             
 				 <tr valign="middle" >
                    <td style="vertical-align: middle;" align="center" colspan="2">
                        <label>
                {!! Form::button(
                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                     array(
                        'class' 		 	=> 'btn btn-success disableddd',
                        'type' 			 	=> 'submit',
                        'data-title' 		=> 'Save Blog Post'
                )) !!}
                        </label>
             {!! Form::close() !!}
                         
            <label>
                
                 {!! Form::open(['method' => 'DELETE', 'url' =>URL::to('/').'/adminpage/webpages/' . $page->id, 'data-toggle' => 'tooltip', 'title' => 'Delete'])!!}
                
                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                    <span class="hidden-xs hidden-sm">削除</span>',
                    array('class' => 'btn btn-danger',
                        'type' => 'button' ,
                        'data-toggle' => 'modal',
                        'data-target' => '#confirmDelete',
                        'data-title' => '固定ページを削除',
                        'data-message' => 'このページを本当に削除しますか？この操作を取り消すことはできません。')) !!}
                {!! Form::close() !!}
            </label>
                    </td>
                 </tr>                             
                             
                             
                        </table>
                    </div>
				
                 
            </div>
        </div>
    </div>
 
    @include('modals.modal-delete')
@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    <style type="text/css">
        .datepicker {
            background: #fff;
            border: 1px solid #555;
        }
        .chosen-container-multi .chosen-choices{
            border: 1px solid #dedede;
            background-image:none;
        }
		
		.select2-container--default .select2-selection--multiple {		
			border: 1px solid #d2d6de;		
			border-radius: 0;		
		}
		
		.select2-container--default .select2-selection--multiple .select2-selection__choice {
			background-color: #3c8dbc;
			border-color: #367fa9;
			color: #fff;
			padding: 1px 10px;
		}
		
		.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
			color: rgba(255, 255, 255, 0.7);
			margin-right: 5px;
		}
		
		.select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
			border: 1px solid #d2d6de;
			border-radius: 0;
			height: 34px;
			padding: 6px 12px;
		}
				
    </style>
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
<script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>
<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script> 	
<script src="{{ URL::asset('plugins/ckeditor/ckeditor.js') }}"></script> 
<script src="{{ URL::asset('plugins/ckfinder/ckfinder.js') }}"></script>
<script src="{{ URL::asset('plugins/select2/select2.full.min.js') }}"></script> 
<script type="text/javascript">
			
 
 /*change birthday*/
 
  $('#publish_date').datepicker({
      language: "ja",
      format: 'yyyy/mm/dd',
      orientation: "bottom",
      todayHighlight: true,
      daysOfWeekHighlighted: "0,6",
      autoclose: true,
  }); 

		var prefix = '{!! asset("/") !!}';
		CKEDITOR.editorConfig = function( config ) {
		   config.filebrowserBrowseUrl = prefix + '/plugins/ckfinder/ckfinder.html';
		   config.filebrowserImageBrowseUrl = prefix + '/plugins/ckfinder/ckfinder.html?type=Images';
		   config.filebrowserFlashBrowseUrl = prefix + '/plugins/ckfinder/ckfinder.html?type=Flash';
		   config.filebrowserUploadUrl = prefix + '/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
		   config.filebrowserImageUploadUrl = prefix + '/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
		   config.filebrowserFlashUploadUrl = prefix + '/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
		};
		var editor = CKEDITOR.replace( 'ckeditor',
         {
          customConfig : 'config-code-editor.js'
          });
		CKFinder.setupCKEditor( editor, prefix + '/plugins/ckfinder/') ;
 
 $(".select2").select2();
 
</script>      
@endsection