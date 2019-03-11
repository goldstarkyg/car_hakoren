@extends('layouts.adminapp')

@section('template_title')
    記事タグを作成する
@endsection

@section('template_fastload_css')

@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <style>
        .chosen-container .chosen-drop {
            border-bottom: 0;
            border-top: 1px solid #aaa;
            top: auto;
            bottom: 40px;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>記事タグを作成する
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                        <a href="{{URL::to('/')}}/adminblog/posttags" class="btn btn-info btn-xs pull-right">
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

                    {!! Form::open(array('action' => 'PostTagsController@store',
                            'method' => 'POST', 'role' => 'form',
                            'class' => 'form-horizontal','enctype'=>'multipart/form-data')) !!}

                    {!! csrf_field() !!}
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
                                    <label class="control-label" for="title_en" >記事タグタイトル(en)</label>
                                </td>
                                <td class="col-md-9">
                                    {!! Form::text('title_en', NULL,
                                                array('id' => 'title_en',
                                                'class' => 'form-control',
                                                'placeholder' => 'Post Tag Title')) !!}
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
    <style type="text/css">
        .datepicker {
            background: #fff;
            border: 1px solid #555;
        }
        .chosen-container-multi .chosen-choices{
            border: 1px solid #dedede;
            background-image:none;
        }
        .left-back{
            background-color: #e2e1e1;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #929297;
        }
        table.table-bordered{
            border:1px solid #929297;
            margin-top:20px;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #929297;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid #929297;
        }
    </style>
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
 
	</script>     
@endsection