@extends('layouts.adminapp')

@section('template_title')
    店舗情報の編集
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
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/timepicker/bootstrap-timepicker.css" rel="stylesheet">

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
        form .active { color: #585757; }
        .bootstrap-timepicker-widget{ background-color: #ffffff; }
        .tab-font{ font-size: 14px !important; }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>編集: {{ $shop->name }}
                    <a href="{{URL::to('/')}}/shopbasic/shop/{{$shop->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/shopbasic/shop" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="panel with-nav-tabs panel-default shadow-box">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li @if(session('tag') == 'address' || !session('tag')) class="active" @endif >
                            <a class="tab-font" data-toggle="tab" href="#address">一般情報</a></li>
                        <li @if(session('tag') == 'hour1') class="active" @endif >
                            <a class="tab-font" data-toggle="tab" href="#time1">営業時間</a></li>
                        <li @if(session('tag') == 'hour2') class="active" @endif>
                            <a class="tab-font" data-toggle="tab" href="#time2">時間変更</a></li>
                        <li @if(session('tag') == 'hour2') class="active" @endif>
                            <a class="tab-font" data-toggle="tab" href="#time3">店舗コメント</a></li>
                        <li @if(session('tag') == 'pickup') class="active" @endif>
                            <a class="tab-font" data-toggle="tab" href="#pickup">送迎情報</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div id="address" class="tab-pane fade @if(session('tag') == 'address' || !session('tag')) in active @endif ">
                            @include('pages.admin.shopbasic.shop-address')
                        </div>

                        <div id="time1" class="tab-pane fade @if(session('tag') == 'hour1') in active @endif">
                            @include('pages.admin.shopbasic.shop-business')
                        </div>

                        <div id="time2" class="tab-pane fade @if(session('tag') == 'hour2') in active @endif ">
                            @include('pages.admin.shopbasic.shop-business-custom')
                        </div>

                        <div id="time3" class="tab-pane fade @if(session('tag') == 'hour2') in active @endif ">
                            @include('pages.admin.shopbasic.shop-store-comment')
                        </div>

                        <div id="pickup" class="tab-pane fade @if(session('tag') == 'pickup') in active @endif ">
                            @include('pages.admin.shopbasic.shop-pickup')
                        </div>
                    </div>
                    <!---->



                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-form')
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/plugins/dropzone/dropzone.js"></script>
    <script src="{{URL::to('/')}}/js/alterclass.js"></script>
    @include('scripts.form-modal-script')
    @include('scripts.delete-modal-script')
    @include('scripts.admin.shopbasic.shop-edit')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/timepicker/bootstrap-timepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('input.inputtime').timepicker();
        });
    </script>

	<script src="{{ URL::to('/') }}/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
	<script type="text/javascript">
        CKEDITOR.replace( 'content1',
        {
            customConfig : 'config-code-editor.js',
            toolbar : 'simple'
        })
        CKEDITOR.replace( 'content1_en',
            {
                customConfig : 'config-code-editor.js',
                toolbar : 'simple'
            })
	</script> 
@endsection

