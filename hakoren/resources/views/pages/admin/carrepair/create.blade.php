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
                <h2>代用(修理)情報の作成
                    {{--<a href="{{URL::to('/')}}/carrepair/{{$repair->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">--}}
                        {{--<i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>--}}
                        {{--詳細を見る--}}
                    {{--</a>--}}
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
                    <form action="{{URL::to('/')}}/carrepair" method="post" class="form-horizontal" id="editform" role="form" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">所属店舗</label>
                        <div class="col-sm-9">
                            <select name="shop" class="form-control" id="shop" onchange="shopChange()">
                                @foreach($shops as $shop)
                                    <option value="{{$shop->id}}" @if($shop->id == $shop_id) selected @endif>{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">クラス</label>
                        <div class="col-sm-9">
                            <select name="car_class" class="form-control" id="car_class" onchange="classChange()">
                                @foreach($classes as $cls)
                                    <option class="class @if($cls->car_shop_name != $shop_id) hidden @endif" value="{{$cls->id}}" shop="{{$cls->car_shop_name}}">{{$cls->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="model_id" class="col-sm-3 control-label">車両</label>
                        <div class="col-sm-9" id="alert-car">
                            <select name="car_inventory" class="form-control" id="car_inventory">
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">タイプ</label>
                        <div class="col-sm-9">
                            <div id="kindBtn" class="btn-group">
                                <span class="btn btn-default btn-md active" data-toggle="kind" data-value="1">修理/車検</span>
                                <span class="btn btn-default btn-md" data-toggle="kind" data-value="2">代車特約</span>
                                <span class="btn btn-default btn-md" data-toggle="kind" data-value="3">事故代車</span>
                            </div>
                            <input type="hidden" name="kind" id="kind" value="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">期間</label>
                        <div class="col-sm-9">
                            <div class="input-group" id="range-wrapper">
                                <input type="text" class="form-control" name="begin_end" id="begin_end" required >
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            </div>
                            <p id="alert-range" class="hidden">選択された期間は、他の予約または検査およびネストされます。</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">料金</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" name="price" value="0">
                                <div class="input-group-addon">
                                    <i class="fa fa-jpy"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="mileage-wrapper" style="display: none">
                        <label class="col-sm-3 control-label">走行距離</label>
                        <div class="col-sm-9">
                            <input type="number" name="mileage" id="mileage" class="form-control" required value="0">
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="status" class="col-sm-3 control-label">ステータス</label>
                            <div class="col-md-9">
                                <div id="statusBtn" class="btn-group">
                                    <span class="btn btn-default btn-md active" data-toggle="status" data-value="1">処理前</span>
                                    <span class="btn btn-default btn-md " data-toggle="status" data-value="2">処理中</span>
                                    <span class="btn btn-default btn-md " data-toggle="status" data-value="3">処理終了</span>
                                </div>
                                <input type="hidden" name="status" id="status" value="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="status" class="col-sm-3 control-label">メモ</label>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="4" name="memo" id="memo"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/carrepair', 'data-toggle' => 'tooltip', 'title' => 'store')) !!}
                                {!! Form::button(
                                    '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                                     array(
                                        'class' 		 	=> 'btn btn-success',
                                        'id' 		 	    => 'btn-submit',
                                        'type' 			 	=> 'button',
                                        /*'data-target' 		=> '#confirmForm',
                                        'data-modalClass' 	=> 'modal-success',
                                        'data-toggle' 		=> 'modal',
                                        'data-title' 		=> trans('modals.edit_user__modal_text_confirm_title'),
                                        'data-message' 		=> trans('modals.edit_user__modal_text_confirm_message'),*/
                                        'onclick'           => 'return check()'
                                )) !!}
                                {!! Form::close() !!}
                            </label>
                            <label>
                                <a href="{{URL::to('/')}}/carrepair" class="btn btn-default">
                                    <i class="fa fa-reply fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">キャンセル</span>
                                </a>
                            </label>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmForm" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        {{ trans('modals.form_modal_default_title') }}
                    </h4>
                </div>
                <div class="modal-body">
                    <p class="modal-msg">
                        {{ trans('modals.form_modal_default_message') }}
                    </p>
                </div>
                <div class="modal-footer">
                    {!! Form::button('<i class="fa fa-fw fa-close" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_cancel'), array('class' => 'btn pull-left', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                    {!! Form::button('<i class="fa fa-fw fa-check" aria-hidden="true"></i> ' . trans('modals.form_modal_default_btn_submit'), array('class' => 'btn btn-success pull-right', 'data-dismiss'=>'modal', 'type' => 'button', 'id' => 'confirm' )) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-warning modal-save" id="modalError" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">エラー </h4> {{-- Lang::get('modals.error_modal_default_title') --}}
                </div>
                <div class="modal-body">
                    <p class="error-text">{{ Lang::get('modals.error_modal_default_message') }} </p>
                </div>
                <div class="modal-footer text-center">
                    {!! Form::button('<i class="fa fa-fw '.Lang::get('modals.confirm_modal_button_cancel_icon').'" aria-hidden="true"></i> ' . Lang::get('modals.error_modal_button_cancel_text'), array('class' => 'btn btn-outline btn-flat', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
                    {{--{!! Form::button('<i class="fa fa-fw '.Lang::get('modals.confirm_modal_button_save_icon').'" aria-hidden="true"></i> ' . Lang::get('modals.confirm_modal_button_save_text'), array('class' => 'btn btn-success pull-right btn-flat', 'type' => 'button', 'id' => 'confirm' )) !!}--}}
                </div>
            </div>
        </div>
    </div>

{{--    @include('modals.modal-form')--}}
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/plugins/dropzone/dropzone.js"></script>
    <script src="{{URL::to('/')}}/js/alterclass.js"></script>

    @include('scripts.form-modal-script')
    @include('scripts.check-changed')
    @include('scripts.gmaps-address-lookup-api3')
    {{--@include('scripts.admin.carinventory.edit')--}}
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
        var drp;
        $(document).ready(function () {
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
            drp = $('#begin_end').data('daterangepicker');

            classChange();
        });

        $('#statusBtn span').click(function () {
            $('#statusBtn span').removeClass('active');
            $(this).addClass('active');
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

        $('#confirm').click( function(){
            $('#editform').submit();
        });

        function shopChange() {
            var shop_id = $('#shop').val();
            $('.class').addClass('hidden');
            $('.class[shop="' + shop_id + '"]').removeClass('hidden');
            $('#car_class').val(null);
            $('#car_inventory').empty();
            $('#alert-car').removeClass('alert alert-danger');
            $('#alert-range').removeClass('alert alert-danger');
            disableDatePicker(false);
            clearDatePicker();
        }

        function classChange() {
            var class_id = $('#car_class').val();
            var shop_id = $('#shop').val();
            if(class_id == undefined || shop_id == undefined) return;
            $.ajax({
                url : '{{URL::to('/')}}/carrepair/getinventory',
                data: { shop_id : shop_id, class_id : class_id, _token : $('input[name="_token"]').val() },
                type: 'post',
                success : function(data, status) {
                    $('#car_inventory').empty();
                    $('#car_inventory').append('<option value="" mileage="">車両クラスを選択してください</option>');
                    var cars = data.cars;
                    for(var k = 0; k < cars.length; k++) {
                        var car = cars[k];
                        var option = '<option value="' + car.id + '" mileage="' + car.current_mileage + '">' +
                                car.shortname + ' ' + car.numberplate1 +
                                ' ' + car.numberplate2 +
                                ' ' + car.numberplate3 +
                                ' ' + car.numberplate4 + '</option>';
                        $('#car_inventory').append(option);
                    }
                    $('#alert-car').removeClass('alert alert-danger');
                    $('#alert-range').removeClass('alert alert-danger');
                    disableDatePicker(false);
                    clearDatePicker();
                }
            })
        }

        $('#car_inventory').change(function () {
            var inv_id = $(this).val();
            if(inv_id == null) return;
            var mileage = $('#car_inventory option:selected').attr('mileage');
            $('#mileage').val(mileage);
            $('#alert-car').removeClass('alert alert-danger');
            $('#alert-range').removeClass('alert alert-danger');
            disableDatePicker(false);
            clearDatePicker();
        });

        function checkPossibleRange(begin, end) {
            var data = {
                _token  : $('input[name="_token"]').val(),
                cid     : $('#car_inventory').val(),
                begin   : begin,
                end     : end
            };
            $.ajax({
                url : '{{URL::to('/')}}/carrepair/checkdaterange',
                type: 'post',
                data: data,
                success : function(data, status) {
                    console.log(data);
                    disableDatePicker(data.enable == false);
                    if(data.enable == false) clearDatePicker();
                },
                error : function(xhr, status, error){
                    alert(error);
                    clearDatePicker();
                }
            })
        }

        $('#begin_end').on('hide.daterangepicker', function(ev, picker) {
            checkPossibleRange(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
        });

        function disableDatePicker(status) {
            if(status) {
                $('#range-wrapper').addClass('alert alert-danger');
                $('#alert-range').removeClass('hidden');
                $('#btn-submit').prop('disabled', true);
            } else {
                $('#range-wrapper').removeClass('alert alert-danger');
                $('#alert-range').addClass('hidden');
                $('#btn-submit').prop('disabled', false);
            }
        }

        function clearDatePicker() {
            $('#begin_end').val('');
        }

        function check() {
            var begin = drp.startDate.format('YYYY-MM-DD');
            var end = drp.endDate.format('YYYY-MM-DD');

            if(begin == '' || end == '') {
                $('#modalError .error-text').html('期間を選択してください。');
                $('#modalError').modal('show');
                $('#range-wrapper').addClass('alert alert-danger');
                $('#btn-submit').prop('disabled', true);
                return false;
            }

            var cid = $('#car_inventory').val();
            if(cid == null || cid === '') {
                $('#modalError .error-text').html('車両を選択してください。');
                $('#modalError').modal('show');
                $('#alert-car').addClass('alert alert-danger');
                $('#btn-submit').prop('disabled', true);
                return false;
            }

            var data = {
                _token  : $('input[name="_token"]').val(),
                cid     : cid,
                begin   : begin,
                end     : end
            };
            $.ajax({
                url : '{{URL::to('/')}}/carrepair/checkdaterange',
                type: 'post',
                data: data,
                async : true,
                success : function(data, status) {
                    console.log(data);
                    if(data.enable == false){
                        disableDatePicker(true);
                        // clearDatePicker();
                        $('#modalError .error-text').html('選択された期間は、他の予約または検査およびネストされます。');
                        $('#modalError').modal('show');
                        return false;
                    } else {
                        disableDatePicker(false);

                        /*'data-target' 		=> '#confirmForm',
'data-modalClass' 	=> 'modal-success',
'data-toggle' 		=> 'modal',
'data-title' 		=> trans('modals.edit_user__modal_text_confirm_title'),
'data-message' 		=> trans('modals.edit_user__modal_text_confirm_message'),*/
                        $('#confirmForm .modal-title').html('{{trans('modals.edit_user__modal_text_confirm_title')}}');
                        $('#confirmForm .modal-msg').html('{{trans('modals.edit_user__modal_text_confirm_message')}}');
                        $('#confirmForm').removeClass('modal-success').addClass('modal-success');
                        $('#confirmForm').modal('show');
                        return true;
                    }
                },
                error : function(xhr, status, error){
                    alert(error);
                    clearDatePicker();
                    return false;
                }
            })
        }

    </script>

@endsection