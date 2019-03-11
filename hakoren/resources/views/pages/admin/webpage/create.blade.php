@extends('layouts.adminapp')

@section('template_title')
    ブログを作成する
@endsection

@section('template_fastload_css')

@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
     
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>ブログを作成する
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="{{URL::to('/')}}/adminblog/blogposts" class="btn btn-info btn-xs pull-right">
                                <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                                一覧へ戻る
                            </a>
                        </div>
                    </div>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default">

                <div class="panel-body">

                    {!! Form::open(array('action' => 'BlogPostsController@store',
                            'method' => 'POST', 'role' => 'form','files' => true,
                            'class' => 'form-horizontal','enctype'=>'multipart/form-data')) !!}

                    {!! csrf_field() !!}
                    <div class="table-responsive">
                        <table class="table table-bordered">

                            <tr class="{{ $errors->has('shop_id') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="title">店舗</label>
                                </td>
                                <td class="col-md-9">                                  
                                    {!! Form::select('shop_id',[''=>'選択してください']+$shop,  NULL,
                                                array('id' => 'shop_id',
                                                'class' => 'form-control')) !!}
                                    @if ($errors->has('shop_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('shop_id') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="{{ $errors->has('post_tag_id') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="post_tag_id" >記事タグ</label>
                                </td>
                                <td class="col-md-9">                                  
                                    {!! Form::select('post_tag_id',[''=>'選択してください']+$posttagdata,  NULL,
                                                array('id' => 'post_tag_id',
                                                'class' => 'form-control')) !!}
                                    @if ($errors->has('post_tag_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('post_tag_id') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="{{ $errors->has('title') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="title" >タイトル</label>
                                </td>
                                <td class="col-md-9">
                                    {!! Form::text('title', NULL,
                                                array('id' => 'title',
                                                'class' => 'form-control',
                                                'placeholder' => 'ブログタイトル')) !!}
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
                                    {!! Form::text('title_en', NULL,
                                                array('id' => 'title_en',
                                                'class' => 'form-control',
                                                'placeholder' => 'ブログタイトル')) !!}
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
                                    {!! Form::text('slug', NULL,
                                                array('id' => 'slug',
                                                'class' => 'form-control',
                                                'placeholder' => 'スラッグ')) !!}
                                    @if ($errors->has('slug'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('slug') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            
                            <tr class="{{ $errors->has('publish_date') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="publish_date" >公開日</label>
                                </td>
                                <td class="col-md-9">
                                 
                                    <div id="publish_date" class="col-md-6 input-group date">
                                        {!! Form::text('publish_date', NULL,
                                                array('id' => 'publish_date',
                                                'class' => 'form-control',
                                                'placeholder' => 'Post Publish Date')) !!}
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                 
                                    @if ($errors->has('publish_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('publish_date') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            
                            <tr class="{{ $errors->has('featured_image') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="name" >アイキャッチ画像</label>
                                </td>
                                <td class="col-md-9">
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
                                    <label class="control-label" for="name" >アイキャッチ画像(en)</label>
                                </td>
                                <td class="col-md-9">
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

                            <tr class="{{ $errors->has('post_content') ? ' has-error ' : '' }}">
                            	<td colspan="2"><label class="control-label" for="name" >内容</label></td>
                            </tr>
                            
                            <tr class="{{ $errors->has('post_content') ? ' has-error ' : '' }}">
                                <td class="col-md-9" colspan="2">                                 
                                    {!! Form::textarea('post_content', NULL,
                                                array('id' => 'post_content',
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


                            <tr class="{{ $errors->has('blog_tag_id') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="blog_tag_id" >タグ</label>
                                </td>
                                <td class="col-md-9">                                  
                                    {!! Form::select('blog_tag_id[]',$blogtagdata,  NULL,
                                                array('id' => 'blog_tag_id',
                                                'class' => 'form-control select2',
                                                'multiple'=>'multiple',
                                                'data-placeholder'=>'タグを選択してください'
                                                )) !!}
                                    @if ($errors->has('blog_tag_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('blog_tag_id') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>                             



                        </table>
                    </div>
                    {!! Form::button('<i class="fa fa-car" aria-hidden="true"></i>&nbsp;' . '新規作成する',
                                array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right',
                                'type' => 'submit', )) !!}
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
<link href="{{ URL::asset('plugins/select2/select2.min.css') }}" rel="stylesheet">
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
	
function slugify(Text){
    return Text
        .toLowerCase()        
        .replace(/ +/g,'-')
		.replace(/[^\w-]+/g,'')
        ;
}
 	
	$("#title").on("keyup",function(){
		var Text = $(this).val();
		Text = slugify(Text);
		$("#slug").val(Text);
	});
	
	$("#title").on("blur",function(){
		var Text = $(this).val();
		Text = slugify(Text);
		$("#slug").val(Text);
	});			
 
 /*change birthday*/
  var d 	   = new Date();
  var month    = d.getMonth()+1;
  var day      = d.getDate();
   var cur_date = d.getFullYear() + '/' +  (month<10 ? '0' : '') + month  +'/' +(day<10 ? '0' : '') + day ;
  $('input[name="publish_date"]').val(cur_date);
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
		var editor = CKEDITOR.replace( 'ckeditor' );
		CKFinder.setupCKEditor( editor, prefix + '/plugins/ckfinder/') ;
 
   $(".select2").select2();
   
</script>      
@endsection