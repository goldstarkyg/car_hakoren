@extends('layouts.adminapp')

@section('template_title')
    記事タグを編集
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
@endsection

@section('content')
     
    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <link href="{{URL::to('/')}}/css/navtab.css" rel="stylesheet">
    <style>
        .chosen-container .chosen-drop {
            border-bottom: 0;
            border-top: 1px solid #aaa;
            top: auto;
            bottom: 40px;
        }
        form .active {
            color: #585757;
        }
        .bootstrap-timepicker-widget{
            background-color: #ffffff;
        }
        .tab-font{
            font-size: 14px !important;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>記事タグを編集する: {{ $posttag->name }}
                    <a href="{{URL::to('/')}}/adminblog/posttags/{{$posttag->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/adminblog/posttags" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="panel with-nav-tabs panel-default shadow-box">
                 
                 {!! Form::model($posttag, array('method' => 'PATCH', 'action' => array('PostTagsController@update', $posttag->id),  'class' => 'form-horizontal', 'id'=>'editform')) !!}
                 
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr class="{{ $errors->has('title') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="title" >記事タグタイトル</label>
                                </td>
                                <td class="col-md-9">
                                    {!! Form::text('title', NULL,
                                                array('id' => 'title',
                                                'class' => 'form-control',
                                                'placeholder' => 'Post Tag Title')) !!}
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="{{ $errors->has('title_en') ? ' has-error ' : '' }}" >
                                <td class="col-md-3 left-back" >
                                    <label class="control-label" for="title" >記事タグタイトル(en)</label>
                                </td>
                                <td class="col-md-9">
                                    {!! Form::text('title_en', NULL,
                                                array('id' => 'title_en',
                                                'class' => 'form-control',
                                                'placeholder' => 'Post Tag Title(en)')) !!}
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
                                                'placeholder' => 'Slug')) !!}
                                    @if ($errors->has('slug'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('slug') }}</strong>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                             
                             
 				 <tr valign="middle" >
                    <td style="vertical-align: middle;" align="center" colspan="2">
                        <label>
                {!! Form::button(
                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                     array(
                        'class' 		 	=> 'btn btn-success disableddd',
                        'type' 			 	=> 'submit',
                        'data-title' 		=> 'Save Post Tag'
                )) !!}
                        </label>
             {!! Form::close() !!}
                         
            <label>
                
                 {!! Form::open(['method' => 'DELETE', 'url' =>URL::to('/').'/adminblog/posttags/' . $posttag->id, 'data-toggle' => 'tooltip', 'title' => 'Delete'])!!}
                
                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                    <span class="hidden-xs hidden-sm">削除</span>',
                    array('class' => 'btn btn-danger',
                        'type' => 'button' ,
                        'data-toggle' => 'modal',
                        'data-target' => '#confirmDelete',
                        'data-title' => '記事タグを削除',
                        'data-message' => 'この記事タグを本当に削除しますか？この操作を取り消すことはできません。')) !!}
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
	<script type="text/javascript">
	    @include('scripts.delete-modal-script')
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
	</script> 
     

@endsection