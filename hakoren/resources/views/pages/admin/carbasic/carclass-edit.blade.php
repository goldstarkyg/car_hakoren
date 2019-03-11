@extends('layouts.adminapp')

@section('template_title')
    車両クラスを編集する
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
        .tab-font{
            font-size: 14px !important;
        }
        #model_sort { list-style-type: none; margin: 0; padding: 0; width: 60%; }
        #model_sort li { margin: 0 3px 3px 3px; padding: 0.4em; cursor: all-scroll; }
        #model_sort li span { /*position: absolute; margin-left: -1.3em; */ }
        a.tab-font { padding: 10px 15px !important; }
    </style>
@endsection
@inject('service_caroption', 'App\Http\Controllers\CarClassController')
@section('content')
    <link id="bsdp-css" href="{{URL::to('/')}}/css/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/navtab.css" rel="stylesheet">
    <link href="{{ URL::asset('plugins/select2/select2.min.css') }}" rel="stylesheet">
    {{--<link href="{{URL::to('/')}}/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">--}}

    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <link href="{{URL::to('/')}}/css/multiimageupload.css" rel="stylesheet">
    <script src="{{ URL::asset('plugins/select2/select2.full.min.js') }}"></script> 
    {{--<script src="{{URL::to('/')}}/plugins/jquery-ui-1.12.1/jquery-ui.min.js" charset="UTF-8"></script>--}}

    <div>
        <input type="hidden" name="class_id" id="class_id" value="{{$carclass->id}}">
        <div class="row">
            <div class="panel panel-default">
                <h2>編集中のクラス: {{ $carclass->name }}
                    <a href="{{URL::to('/')}}/carbasic/carclass/{{$carclass->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/carbasic/carclass" class="btn btn-info btn-xs pull-right">
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
                        <li @if(session('tag') == 'class' || !session('tag')) class="active" @endif >
                            <a class="tab-font" data-toggle="tab" href="#class" >一般設定</a></li>
                        <li @if(session('tag') == 'normal') class="active" @endif >
                            <a class="tab-font" data-toggle="tab" href="#normal">通常料金</a></li>
                        <li @if(session('tag') == 'custom') class="active" @endif>
                            <a class="tab-font" data-toggle="tab" href="#custom" id="tab_custom">特別料金</a></li>
                        <li @if(session('tag') == 'option') class="active" @endif>
                            <a class="tab-font" data-toggle="tab" href="#option" id="tab_option">オプション</a></li>
                        <li @if(session('tag') == 'insurance') class="active" @endif>
                            <a class="tab-font" data-toggle="tab" href="#insurance" id="tab_insurance">補償</a></li>
                        <li @if(session('tag') == 'equip') class="active" @endif>
                            <a class="tab-font" data-toggle="tab" href="#equip" id="tab_equip">装備</a></li>
                        <li  @if(session('tag') == 'priority') class="active" @endif>
                            <a class="tab-font" data-toggle="tab" href="#priority" id="tab_priority">モデル優先度</a></li>
                    </ul>
               </div>
               <div class="panel-body">
                    <div class="tab-content">
                        <div id="class" class="tab-pane fade @if(session('tag') == 'class' || !session('tag')) in active @endif ">
                            @include('pages.admin.carbasic.carclass-class')
                        </div>

                        <div id="normal" class="tab-pane fade @if(session('tag') == 'normal') in active @endif">
                            @include('pages.admin.carbasic.carclass-normal')
                        </div>

                        <div id="custom" class="tab-pane fade @if(session('tag') == 'custom') in active @endif ">
                            @include('pages.admin.carbasic.carclass-custom')
                        </div>

                        <div id="option" class="tab-pane fade @if(session('tag') == 'option') in active @endif ">
                            @include('pages.admin.carbasic.carclass-option')
                        </div>

                        <div id="insurance" class="tab-pane fade @if(session('tag') == 'insurance') in active @endif ">
                            @include('pages.admin.carbasic.carclass-insurance')
                        </div>

                        <div id="equip" class="tab-pane fade @if(session('tag') == 'equip') in active @endif ">
                            @include('pages.admin.carbasic.carclass-equip')
                        </div>

                        <div id="priority" class="tab-pane fade @if(session('tag') == 'priority') in active @endif ">
                            @include('pages.admin.carbasic.carclass-priority')
                        </div>
                    </div>
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
    <script src="{{ URL::asset('plugins/select2/select2.full.min.js') }}"></script> 
    <script>
        var public_url = '{{URL::to('/')}}';
        var thumbs = [];
        var thumbs_ids = [];
        @foreach($thumbnails as $thumb)
            thumbs.push(public_url+'{{$thumb->thumb_path}}');
        thumbs_ids.push('{{$thumb->id}}');
        @endforeach
    </script>
    @include('scripts.form-modal-script')
    @include('scripts.delete-modal-script')
    @include('scripts.admin.carbasic.carclass-edit')

    <script src="{{URL::to('/')}}/js/home.js"></script>
    <script src="{{ URL::to('/') }}/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'description',
                {
                    customConfig : 'config-code-editor.js',
                    toolbar : 'simple',
                    height:50,
                });
        CKEDITOR.replace( 'staff_comment',
                {
                    customConfig : 'config-code-editor.js',
                    toolbar : 'simple',
                    height:50,
                });
        $('.insurance_price').keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    </script>

@endsection