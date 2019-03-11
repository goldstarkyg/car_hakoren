@extends('layouts.adminapp')

@section('template_title')
    個別車両の編集
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

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>代用(修理)の編集: {{$repair->inspection_id}}
                    <a href="{{URL::to('/')}}/carrepair/{{$repair->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/carrepair" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($repair, array('action' => array('CarRepairController@update', $repair->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    <input type="hidden" name="inventory_id" id="inventory_id" value="{{$repair->inventory_id}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="model_id" class="col-sm-3 control-label">車両</label>
                        <div class="col-sm-9">
                            <label class="control-label">
                                {{$inventory->shortname}} {{$inventory->numberplate1}} {{$inventory->numberplate2}} {{$inventory->numberplate3}} {{$inventory->numberplate4}}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">所属店舗</label>
                        <div class="col-sm-9">
                            <label class="control-label">
                                @foreach($shops as $shop)
                                    @if($shop->id == $inventory->shop_id) {{$shop->name}} @endif
                                @endforeach
                            </label>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <label class="col-sm-3 control-label">禁煙？</label>
                        <div class="col-md-9">
                            <div id="smokeBtn" class="btn-group">
                                <span class="btn @if($inventory->smoke == 1) btn-primary @else btn-default @endif btn-md">喫煙</span>
                                <span class="btn @if($inventory->smoke == 0) btn-primary @else btn-default @endif btn-md">禁煙</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="kind" class="col-sm-3 control-label">タイプ</label>
                            <div class="col-md-9">
                                <div id="kindBtn" class="btn-group">
                                    <span class="btn btn-default btn-md @if($repair->kind == 1) active @endif" data-toggle="kind" data-value="1">修理/車検</span>
                                    <span class="btn btn-default btn-md @if($repair->kind == 2) active @endif" data-toggle="kind" data-value="2">代車特約</span>
                                    <span class="btn btn-default btn-md @if($repair->kind == 3) active @endif" data-toggle="kind" data-value="3">事故代車</span>
                                </div>
                                <input type="hidden" name="kind" id="kind" value="{{$repair->kind}}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">期間</label>
                        <div class="col-sm-9">
                            <div class="input-group" id="range-wrapper">
                                <input type="text" class="form-control" name="begin_end" id="begin_end" value="{{$repair->begin_date}} - {{$repair->end_date}}">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            </div>
                            <p id="alert-range" class="hidden">選択された期間は、他の予約または検査およびネストされます。</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">料金</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" name="price" value="{{$repair->price}}">
                                <div class="input-group-addon">
                                    <i class="fa fa-jpy"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="mileage-wrapper">
                        <label class="col-sm-3 control-label">走行距離</label>
                        <div class="col-sm-9">
                            <input type="number" name="mileage" id="mileage" class="form-control" required value="{{$inventory->current_mileage}}">
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="status" class="col-sm-3 control-label">ステータス</label>
                            <div class="col-md-9">
                                <div id="statusBtn" class="btn-group">
                                    <span class="btn btn-default btn-md @if($repair->status == 1) active @endif" data-toggle="status" data-value="1">処理前</span>
                                    <span class="btn btn-default btn-md @if($repair->status == 2) active @endif" data-toggle="status" data-value="2">処理中</span>
                                    <span class="btn btn-default btn-md @if($repair->status == 3) active @endif" data-toggle="status" data-value="3">処理終了</span>
                                </div>
                                <input type="hidden" name="status" id="status" value="{{$repair->status}}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="status" class="col-sm-3 control-label">メモ</label>
                            <div class="col-md-9">
                                {!! Form::textarea('memo', $repair->memo, ['class'=>'form-control', 'rows' => '4']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carrepair/' . $repair->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
                                {!! Form::button(
                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                                     array(
                                        'class' 		 	=> 'btn btn-success disableddd',
                                        'type' 			 	=> 'button',
                                        'data-target' 		=> '#confirmForm',
                                        'data-modalClass' 	=> 'modal-success',
                                        'data-toggle' 		=> 'modal',
                                        'data-title' 		=> trans('modals.edit_user__modal_text_confirm_title'),
                                        'data-message' 		=> trans('modals.edit_user__modal_text_confirm_message')
                                )) !!}
                                {!! Form::close() !!}
                            </label>
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carrepair/' . $repair->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => '情報を削除する',
                                        'data-message' => 'この情報を本当に削除しますか？この操作は取り消せません。')) !!}
                                {!! Form::close() !!}
                            </label>
                        </div>
                    </div>
                    {!! Form::close() !!}

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
    @include('scripts.check-changed')
    @include('scripts.gmaps-address-lookup-api3')
    @include('scripts.delete-modal-script')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/plugins/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/plugins/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/plugins/daterangepicker/daterangepicker.css" />

    <style>
        #statusBtn span.active {
            background-color: #3bb3e0;
            color: #fff;
        }
        #kindBtn span.active {
            background-color: #3bb3e0;
            color: #fff;
        }
        #alert-range {
            color: brown;
            font-weight: bold;
        }

    </style>
    
    <script>
        var inspection_id = {{$repair->id}};
        $('#begin_end').daterangepicker(
            {
                "minDate": "{{date('Y-m-d')}}",
                "locale": {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "applyLabel": "適用",
                    "cancelLabel": "キャンセル",
                    "fromLabel": "から",
                    "toLabel": "まで",
                    "customRangeLabel": "カスタム",
                    "weekLabel": "週",
                    "daysOfWeek": [ "日", "月", "火", "水", "木", "金","土" ],
                    "monthNames": [ "1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月" ],
                    "firstDay": 0
                },
            }
        );
        $('#statusBtn span').click(function () {
            $('#statusBtn span').removeClass('active');
            $(this).addClass('active');
            $('#status').val($(this).data('value'));
        });

        $('#kindBtn span').click(function () {
            $('#kindBtn span').removeClass('active');
            $(this).addClass('active');
            var kind = $(this).data('value');
            $('#kind').val(kind);
            if(kind == '1'){
                $('option.repair').show();
                $('option.substitution').hide();
                $('#mileage-wrapper').fadeOut();
            } else {
                $('option.repair').hide();
                $('option.substitution').show();
                $('#mileage-wrapper').fadeIn();
            }
        });

        function checkPossibleRange(begin, end, inspection_id) {
            var data = {
                _token  : $('input[name="_token"]').val(),
                cid     : $('#inventory_id').val(),
                begin   : begin,
                end     : end,
                ins_id  : inspection_id
            };

            $.ajax({
                url : '{{URL::to('/')}}/carrepair/checkdaterange',
                type: 'post',
                data: data,
                success : function(data, status) {
                    console.log(data);
                    if(data.enable == false){
                        $('#range-wrapper').addClass('alert alert-danger');
                        $('#alert-range').removeClass('hidden');

                        $('#btn-submit').prop('disabled', true);
                    } else {
                        $('#range-wrapper').removeClass('alert alert-danger');
                        $('#alert-range').addClass('hidden');

                        $('#btn-submit').prop('disabled', false);
                    }
                },
                error : function(xhr, status, error){
                    alert(error);
                }
            })
        }

        $('#begin_end').on('hide.daterangepicker', function(ev, picker) {
            checkPossibleRange(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'), inspection_id);
        });

    </script>

@endsection