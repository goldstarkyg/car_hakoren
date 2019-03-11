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
        #tab-header {
            border-bottom: none;
            padding: 5px 5px 0 5px;
        }
        #tab-header li a {
            font-size: 16px;
            border-bottom: none;
        }
        #tab-header li.active {
            color: #555;
            background-color: #fff;
        }
        .msg-inactive {
            margin-top: 10px;
            color: brown;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet">

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>個別車両の編集: {{ $inven->name }}
                    <a href="{{URL::to('/')}}/carinventory/inventory/{{$inven->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/carinventory/inventory" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box with-nav-tabs">
                <input type="hidden" name="inventory_id" id="inventory_id" value="{{ $inven->id }}">
                <div class="panel-heading" id="tab-header">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#general">一般設定</a></li>
                        <li class=""><a data-toggle="tab" href="#reallocate" >予約の解除</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div id="general" class="tab-pane fade in active" style="padding: 15px 10px;">
                            {!! Form::model($inven, array('action' => array('CarInventoryController@update', $inven->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="model_id" class="col-sm-3 control-label">モデル</label>
                                <div class="col-sm-9">
                                    <div>
                                        <select class="chosen-select form-control" name="model_id" id="model_id" >
                                            <option value="">選択する</option>
                                            @foreach($models as $model)
                                                <?php
                                                $select="";
                                                if($model->id == $inven->model_id) $select='selected';
                                                ?>
                                                <option value="{{$model->id}}" {{$select}}>{{$model->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">車両番号</label>
                                <div class="col-sm-9">
                                    <div class="col-sm-3" style="padding: 0 10px;">
                                        {!! Form::text('numberplate1', $inven->numberplate1, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'numberplate1']) !!}
                                    </div>
                                    <div class="col-sm-3" style="padding: 0 10px;">
                                        {!! Form::text('numberplate2', $inven->numberplate2, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'numberplate2']) !!}
                                    </div>
                                    <div class="col-sm-3" style="padding: 0 10px;">
                                        {!! Form::text('numberplate3', $inven->numberplate3, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'numberplate3']) !!}
                                    </div>
                                    <div class="col-sm-3" style="padding: 0 10px;">
                                        {!! Form::text('numberplate4', $inven->numberplate4, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'numberplate4']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">コード</label>
                                <div class="col-sm-9">
                                    {!! Form::text('shortname', $inven->shortname, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'shortname']) !!}
                                </div>
                            </div>

                            <div class="form-group hidden">
                                <label for="name" class="col-sm-3 control-label">Car priority</label>
                                <div class="col-sm-9">
                                    {!! Form::text('priority', $inven->priority, ['class' => 'form-control required', 'placeholder' => '', 'id' => 'priority']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="abbiriviation" class="col-sm-3 control-label">所属店舗</label>
                                <div class="col-sm-9">
                                    <div>
                                        <select class="chosen-select form-control" name="shop_id" id="shop_id" >
                                            @foreach($shops as $shop)
                                                <?php
                                                $select="";
                                                if($shop->id == $inven->shop_id) $select='selected';
                                                ?>
                                                <option value="{{$shop->id}}" {{$select}}>{{$shop->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group has-feedback row">
                                <div>
                                    <label for="smoke" class="col-sm-3 control-label">禁煙？</label>
                                    <div class="col-md-9">
                                        <div id="smokeBtn" class="btn-group">
                                            <span class="btn btn-primary btn-md active" data-toggle="smoke" data-value="1">喫煙</span>
                                            <span class="btn btn-default btn-md notActive" data-toggle="smoke" data-value="0">禁煙</span>
                                        </div>
                                        <input type="hidden" name="smoke" id="smoke" value="{{$inven->smoke}}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">最大乗車人数</label>
                                <div class="col-sm-9">
                                    <select name="max_passenger" id="max_passenger" class="form-control">
                                        <option value="">選んでください。</option>
                                        @for($m = 1; $m <= 30; $m++)
                                        <option value="{{$m}}" @if($inven->max_passenger == $m) selected @endif>{{$m}}人乗り</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">走行距離(Km)</label>
                                <div class="col-sm-9">
                                    {!! Form::number('current_mileage', $inven->current_mileage, ['class' => 'form-control', 'placeholder' => '', 'id' => 'current_mileage']) !!}
                                </div>
                            </div>

                            <div class="form-group has-feedback row">
                                <div>
                                    <label for="status" class="col-sm-3 control-label">ステータス</label>
                                    {{--<input type="hidden" name="inactivable" id="inactivable" value="{{ $inactivable }}" >--}}
                                    <div class="col-md-9">
                                        <div id="statusBtn" class="btn-group">
                                            <span class="btn btn-primary btn-md active"
                                                  @if($inactivable && $inven->status==1) disabled @endif
                                                  data-toggle="status" data-value="1">稼働中</span>
                                            <span class="btn btn-default btn-md notActive"
                                                  @if($inactivable) disabled @endif
                                                  data-toggle="status" data-value="0">非稼働</span>
                                        </div>
                                        @if($inactivable)
                                        <p class="msg-inactive ">
                                            有効な予約がある場合は車両を非稼働にできません。 <br/> 予約の割り当て再度行ってください。
                                        </p>
                                        @endif
                                        <input type="hidden" name="status" id="status" value="{{$inven->status}}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <label>
                                        {!! Form::open(array('url' => URL::to('/').'/carinventory/inventory/' . $inven->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
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
                                        {!! Form::open(array('url' => URL::to('/').'/carinventory/inventory/' . $inven->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                        {!! Form::hidden('_method', 'DELETE') !!}
                                        {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                            <span class="hidden-xs hidden-sm">削除</span>',
                                            array('class' => 'btn btn-danger',
                                                'type' => 'button' ,
                                                'data-toggle' => 'modal',
                                                'data-target' => '#confirmDelete',
                                                'data-title' => '車両を削除する',
                                                'data-message' => 'この車両を本当に削除しますか？この操作は取り消せません。')) !!}
                                        {!! Form::close() !!}
                                    </label>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                        <div id="reallocate" class="tab-pane fade" style="padding: 15px 10px;">
                            <form class="form-horizontal" >

                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="model_id" class="col-sm-3 control-label">開始日</label>
                                    <div class="col-sm-9 input-group">
                                        <input type="text" class="form-control" name="start_date" id="start_date" value="{{date('Y-m-d')}}">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="model_id" class="col-sm-3 control-label">終了日</label>
                                    <div class="col-sm-9 input-group">
                                        <input type="text" class="form-control" name="end_date" id="end_date" value="">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="start_date" class="">
                                    <input type="hidden" name="end_date">
                                    {{--<label for="model_id" class="col-sm-3 control-label">終了日</label>--}}
                                    <div class="col-sm-6 col-sm-offset-4">
                                        <button class="btn btn-danger" type="button" data-target="#mdl_confirm" data-modalClass="modal-success" data-toggle="modal" data-title="一括解除 & 非稼働" data-message="下記に表示された予約と予定代車を同期間/同クラスの別在庫に割り当て、また修理/車検の予定がある場合には無効化します。この操作の後、この在庫を非稼働へと変更し予約が取れないようにします。<br/>この操作をやり直すことはできません。"  data-yes="すぐに実行する" data-no="いいえ" data-action="process('all')">
                                            <i class="fa fa-fw fa-trash" aria-hidden="true"></i>
                                            一括解除 & 非稼働
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">該当期間の予約数</label>
                                    <label class="col-sm-1 form-control-static"><span id="bookings">{{$booking_number}}</span>件</label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-danger" type="button" id="release-booking" data-target="#mdl_confirm" data-modalClass="modal-success" data-toggle="modal" data-title="予約/予定の再配車" data-message="該当期間の予約を同期間/同クラスの別在庫に割り当て直しますか？ <br/>この操作は取り消せません。" data-yes="すぐに実行する" data-no="いいえ" data-action="process('booking')" @if($booking_number==0) disabled @endif>
                                            <i class="fa fa-fw fa-trash" aria-hidden="true"></i>
                                            予約を解放する
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">該当期間の修理/車検</label>
                                    <label class="col-sm-1 form-control-static"><span id="inspections">{{$inspection_number}}</span>件</label>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-primary" id="edit-inspections"
                                        @if($inspection_number== 0) disabled @endif onclick="gotoInspectionList({{$inven->id}})">
                                            <i class="fa fa-fw fa-edit" aria-hidden="true"></i>
                                            編集ページを開く
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="name" class="col-sm-3 control-label">該当期間の代車</label>
                                    <label class="col-sm-1 form-control-static"><span id="substitutions">{{$substitution_number}}</span>件</label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-danger" type="button" id="release-substitution" data-target="#mdl_confirm" data-modalClass="modal-success" data-toggle="modal" data-title="予約/予定の再配車" data-message="該当期間の予約を同期間/同クラスの別在庫に割り当て直しますか？  <br>この操作は取り消せません。" data-yes="すぐに実行する" data-no="いいえ" data-action="process('substitution')" @if($substitution_number==0) disabled @endif >
                                            <i class="fa fa-fw fa-trash" aria-hidden="true"></i>
                                            予定を解放する
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div style="overflow-x:auto;">
                                <table id="info-table" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th style="vertical-align: middle;text-align: center;">使用者</th>
                                        <th style="vertical-align: middle;text-align: center;">出発</th>
                                        <th style="vertical-align: middle;text-align: center;">返却</th>
                                        <th style="vertical-align: middle;text-align: center;">返車時の<br/>走行距離</th>
                                        <th style="vertical-align: middle;text-align: center;">メモ</th>
                                        <th style="vertical-align: middle;text-align: center;">予約<br/>ステータス</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $bookings as $book )
                                        <tr>
                                            <?php
                                            //1:booked 2:pending 3:updated 4:processed 5:end 6:canceled
                                            switch($book->status) {
                                                case 1 : $status = 'submit'; break;
                                                case 2 : $status = 'pending'; break;
                                                case 3 : $status = 'confirmed'; break;
                                                case 4 : $status = 'paid'; break;
                                                case 5 : $status = 'paid/check-in'; break;
                                                case 6 : $status = 'using'; break;
                                                case 7 : $status = 'delayed'; break;
                                                case 8 : $status = 'end'; break;
                                                case 9 : $status = 'cancel'; break;
                                                case 10 : $status = 'ignored'; break;
                                            }
                                            ?>
                                            <td><a href="{{URL::to('/')}}/booking/detail/{{ $book->id }}">{{ $book->booking_id }}</a></td>
                                            <td>
                                                <?php
                                                $username = $book->last_name.$book->first_name;
                                                $email = $book->email;
                                                if($email == '') {
                                                    $portal_info = json_decode($book->portal_info);
                                                    if($portal_info) $email = $portal_info->email;
                                                }

                                                if($username == '') {
                                                    $username = $book->fur_last_name.$book->fur_first_name;
                                                }
                                                if($username == '') {
                                                    $portal_info = json_decode($book->portal_info);
                                                    if($portal_info){
                                                        $username = $portal_info->last_name.$portal_info->first_name;
                                                        if($username == '') {
                                                            $username = $portal_info->fu_last_name.$portal_info->fu_first_name;
                                                        }
                                                    }
                                                }

                                                ?>
                                                <a href="{{URL::to('/')}}/members/{{ $book->client_id }}">
                                                    {{ $username }}<br>{{ $email }}
                                                </a>
                                            </td>
                                            <td>{{ date('Y/m/d', strtotime($book->departing)) }}</td>
                                            <td>{{ date('Y/m/d', strtotime($book->returning)) }}</td>
                                            <td>@if($book->miles == '0')  {{$inven->current_mileage}} @else {{ $book->miles }} @endif</td>
                                            <td>{{ $book->admin_memo }}</td>
                                            <td>{{ $status }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="mdl_confirm" role="dialog" aria-labelledby="mdl_confirmLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">削除</h4>
                </div>
                <div class="modal-body">
                    <p>本当に削除しますか?</p>
                </div>
                <div class="modal-footer">
                    {!! Form::button('<i class="fa fa-fw fa-close" aria-hidden="true"></i> 戻る', array('class' => 'btn btn-default pull-right btn-flat', 'type' => 'button', 'data-dismiss' => 'modal', 'id'=>'btn-no')) !!}
                    {!! Form::button('<i class="fa fa-fw fa-trash-o" aria-hidden="true"></i> 削除', array('class' => 'btn btn-danger pull-right btn-flat', 'type' => 'button', 'id' => 'btn-yes' )) !!}
                </div>
            </div>
        </div>
    </div>

    <form action="{{URL::to('/')}}/carrepair/inspectionlist" method="post" id="form1">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{$inven->id}}">
        <input type="hidden" name="begin" id="edit-begin">
        <input type="hidden" name="end" id="edit-end">
    </form>

    @include('modals.modal-form')
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/plugins/dropzone/dropzone.js"></script>
    <script src="{{URL::to('/')}}/js/alterclass.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/dataTables/dataTables.bootstrap.min.js"></script>

    @include('scripts.form-modal-script')
    @include('scripts.check-changed')
    @include('scripts.gmaps-address-lookup-api3')
    @include('scripts.admin.carinventory.edit')
    @include('scripts.delete-modal-script')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

    <style>
        #btn-yes, #btn-no { margin-right: 20px; }
    </style>

    <script>
        var table_config =             {
            // "order": [[ 3, 'desc' ]],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "pageLength" : 25,
            "serverSide": false,
            // "ordering": true,
            "info": true,
            "autoWidth": true,
            "dom": 'T<"clear">lfrtip',
            // "sPaginationType": "full_numbers",
            "language": {
                processing:     "処理中...",
                search:         "検索:",
                lengthMenu:     "_MENU_個の要素を表示",
                info:           "_START_ ~ _END_  を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全項目 _TOTAL_",
                infoEmpty:      "0件中0件から0件までを表示",
                infoFiltered:   "（合計で_MAX_個のアイテムからフィルタリングされました）",
                infoPostFix:    "",
                loadingRecords: "読み込んでいます...",
                zeroRecords:    "表示する項目がありません",
                emptyTable:     "テーブルのデータがありません",
                paginate: {
                    first:      "最初",
                    previous:   "以前",
                    next:       "次に",
                    last:       "最終"
                },
                aria: {
                    sortAscending:  ": 列を昇順にソートする有効にします。",
                    sortDescending: ": 列を降順で並べ替えるためにアクティブにする"
                }
            }
        };
        var info_table = $('#info-table').dataTable( table_config );

        $('#max_passenger').chosen({
            max_shown_results : 6
        });

        $('#start_date, #end_date').datepicker({
            language: "ja",
            format: 'yyyy-mm-dd',
            orientation: "bottom",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });

        function gotoInspectionList(car_id) {
            $('#edit-begin').val( $('#start_date').val());
            $('#edit-end').val($('#end_date').val());
            $('#form1').submit();
        }

        function getNumbersOfBookingInspection(car_id, begin, end){
            $.ajax({
                url : '{{URL::to('/')}}/carrepair/getnumofbookinginspection',
                type: 'post',
                data: {
                    id : car_id,
                    begin : begin,
                    end : end,
                    _token : $('input[name="_token"]').val(),
                },
                success : function (data, status) {
                    var booking_num = data.booking;
                    var bookings = data.booking_data;
                    var repair_num = data.inspections;
                    var subst_num = data.substitutions;

                    $('#bookings').text(booking_num);
                    $('#release-booking').prop('disabled', booking_num == 0);

                    $('#substitutions').text(subst_num);
                    $('#release-substitution').prop('disabled', subst_num == 0);

                    $('#inspections').text(repair_num);
                    $('#edit-inspections').prop('disabled', repair_num == 0);
                    // var $table = $('#info-table tbody');
                    // $table.empty();

                    $('#info-table').dataTable().fnClearTable();
                    for( var k = 0; k < bookings.length; k++ ){
                        var book = bookings[k],
                            status = '';

                        switch(book.status) {
                            case 1 : status = 'submit'; break;
                            case 2 : status = 'pending'; break;
                            case 3 : status = 'confirmed'; break;
                            case 4 : status = 'paid'; break;
                            case 5 : status = 'paid/check-in'; break;
                            case 6 : status = 'using'; break;
                            case 7 : status = 'delayed'; break;
                            case 8 : status = 'end'; break;
                            case 9 : status = 'cancel'; break;
                            case 10 : status = 'ignored'; break;
                        }
                        var username = book.last_name + book.first_name,
                            email = book.email;
                        console.log(email);
                        if(email == null || email == undefined || email == '') {
                            var portal_info = JSON.parse(book.portal_info);
                            if(portal_info) email = portal_info.email;
                        }

                        if(username == '') {
                            username = book.fur_last_name + book.fur_first_name;
                        }
                        if(username == '') {
                            portal_info = JSON.parse(book.portal_info);
                            if(portal_info){
                                username = portal_info.last_name + portal_info.first_name;
                                if(username == '') {
                                    username = portal_info.fu_last_name + portal_info.fu_first_name;
                                }
                            }
                        }
                        var v = new Date(book.departing),
                            ddate = v.getFullYear() + '/' + (v.getMonth()+1) + '/' + v.getDate();
                        var w = new Date(book.returning),
                            rdate = w.getFullYear() + '/' + (w.getMonth()+1) + '/' + w.getDate();
                        var memo = book.admin_memo;
                        if(memo == null) memo = '';
                        $('#info-table').dataTable().fnAddData([
                            '<a href="{{URL::to('/')}}/booking/detail/'+ book.id +'">'+ book.booking_id +'</a>',
                            '<a href="{{URL::to('/')}}/members/'+ book.client_id + '">' + username +'<br>'+ email + '</a>',
                            ddate, rdate, book.miles, memo, status
                        ]);
                    }
                    // $('#info-table').dataTable( table_config );
                }
            })
        }

        $('#start_date').datepicker().on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#end_date').datepicker('setStartDate', minDate);

            // get number of bookings and inspections in this period
            getNumbersOfBookingInspection('{{$inven->id}}', $('#start_date').val(), $('#end_date').val());
        });

        $('#end_date').datepicker().on('changeDate', function (selected) {
            getNumbersOfBookingInspection('{{$inven->id}}', $('#start_date').val(), $('#end_date').val());
        });

        // CONFIRMATION DELETE MODAL
        $('#mdl_confirm').on('show.bs.modal', function (e) {
            var $target = $(e.relatedTarget);

            $(this).find('.modal-body p').html($target.data('message'));
            $(this).find('.modal-title').text($target.data('title'));
            $(this).find('.modal-footer #btn-yes')
                .text($target.data('yes'))
                .attr('onclick', $target.data('action'));
            $(this).find('.modal-footer #btn-no').text($target.data('no'));
        });

        function process(action) {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            $.ajax({
                url : '{{URL::to('/')}}/carinventory/reallocate/{{$inven->id}}',
                data: {
                    start_date : start_date,
                    end_date : end_date,
                    action : action,
                    _token : $('input[name="_token"]').val(),
                },
                type: 'post',
                success : function(data, status) {
                    $('#mdl_confirm').modal('hide');
                    if(data.success == true ) {
                        alert('reallocation was successfully finished');
                        getNumbersOfBookingInspection('{{$inven->id}}', $('#start_date').val(), $('#end_date').val());
                    } else {
                        alert(data.error);
                    }

                },
                error : function(xhr, status, error) {
                    $('#mdl_confirm').modal('hide');
                    alert(error);
                }
            })
        }

    </script>

@endsection


