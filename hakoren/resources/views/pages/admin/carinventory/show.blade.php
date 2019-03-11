@extends('layouts.adminapp')

@section('template_title')
    個別車両の詳細
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
    <link href="{{URL::to('/')}}/css/plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet">

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>個別車両の詳細 {{ $inven->name }}
                    <a href="{{URL::to('/')}}/carinventory/inventory/{{$inven->id}}/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="{{URL::to('/')}}/carinventory/inventory" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    {!! Form::model($inven, array('method' => 'PATCH', 'action' => array('CarInventoryController@update', $inven->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="model_id" class="col-sm-3 control-label">モデル</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                @foreach($models as $model)
                                    @if($model->id == $inven->model_id) {{$model->name}} @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">車両番号</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$inven->numberplate1}} {{$inven->numberplate2}} {{$inven->numberplate3}} <span style="font-size:120%;">{{$inven->numberplate4}}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">コード名</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$inven->shortname}}
                        </div>
                    </div>

                    {{--<div class="form-group">--}}
                        {{--<label for="name" class="col-sm-3 control-label">優先度</label>--}}
                        {{--<div class="col-sm-9 m-t-xs">--}}
                            {{--{{$inven->priority}}--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">所属店舗</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                @foreach($shops as $shop)
                                    @if($shop->id == $inven->shop_id) {{$shop->name}} @endif
                                @endforeach
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
                        <div class="col-sm-9 m-t-xs">
                            {{$inven->max_passenger}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">現在の走行距離</label>
                        <div class="col-sm-9 m-t-xs">
                            {{$inven->current_mileage}} Km
                        </div>
                    </div>

                    <div class="form-group has-feedback row hidden" id="other_locations">
                        <div>
                            <label for="dropoff_id" class="col-sm-3 control-label">返却場所</label>
                            <div class="col-md-9 m-t-xs">
                                <div>
                                    @foreach($shops as $shop)
                                        @foreach($dropoffs as $drop)
                                            @if($drop->shop_id == $shop->id)
                                               {{$shop->name}}
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="status" class="col-sm-3 control-label">ステータス</label>
                            <div class="col-md-9">
                                <div id="statusBtn" class="btn-group">
                                    <span class="btn btn-primary btn-md active" data-toggle="status" data-value="1">稼働中</span>
                                    <span class="btn btn-default btn-md notActive" data-toggle="status" data-value="0">非稼働</span>
                                </div>
                                <input type="hidden" name="status" id="status" value="{{$inven->status}}">
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                    @if(!empty($repair))
                        @php
                        if($repair->kind == 1) $kind = '修理/車検';
                        if($repair->kind == 2) $kind = '代車特約';
                        if($repair->kind == 3) $kind = '事故代車';
                        @endphp
                        <div class="form-group has-feedback row">
                            <div>
                                <label for="smoke" class="col-sm-3 control-label">タイプ</label>
                                <div class="col-sm-9">
                                    <div class="btn-group">

                                        <span class="btn btn-md @if($repair->kind == 1) btn-primary @else btn-default @endif">修理/車検</span>
                                        <span class="btn btn-md @if($repair->kind == 2) btn-primary @else btn-default @endif">代車特約</span>
                                        <span class="btn btn-md @if($repair->kind == 3) btn-primary @else btn-default @endif">事故代車</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="abbiriviation" class="col-sm-3 control-label">{{$kind}}期間</label>
                            <div class="col-sm-9 m-t-xs">
                                <div>
                                    {{$repair->begin_date}} ~ {{$repair->end_date}}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="abbiriviation" class="col-sm-3 control-label">{{$kind}}料金</label>
                            <div class="col-sm-9 m-t-xs">
                                <div>
                                    {{number_format($repair->price)}}円
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div>
                                <label for="status" class="col-sm-3 control-label">ステータス</label>
                                <div class="col-md-9">
                                    <div id="statusBtn" class="btn-group">
                                        <span class="btn btn-md @if($repair->status == 1) btn-primary @else btn-default @endif">処理前</span>
                                        <span class="btn btn-md @if($repair->status == 2) btn-primary @else btn-default @endif">処理中</span>
                                        <span class="btn btn-md @if($repair->status == 3) btn-primary @else btn-default @endif">処理終了</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div>
                                <label for="status" class="col-sm-3 control-label">メモ</label>
                                <div class="col-md-9">
                                    {!! Form::textarea('memo', $repair->memo, ['class'=>'form-control', 'rows' => '4', 'readonly'=>'true']) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="{{ URL::to('/carinventory/inventory/' . $inven->id . '/edit') }}" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
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
                                        'data-title' => '個別車両を削除する',
                                        'data-message' => 'この個別車両を本当に削除しますか？この操作を取り消すことはできません。')) !!}
                                {!! Form::close() !!}
                            </label>
                        </div>
                    </div>

                    {!! Form::close() !!}

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
//                                    switch($book->status) {
//                                        case 1 : $status = '成約'; break;
//                                        case 2 : $status = '成約 - 配車前'; break;
//                                        case 3 : $status = 'confirmed'; break;
//                                        case 4 : $status = 'paid'; break;
//                                        case 5 : $status = 'paid/check-in'; break;
//                                        case 6 : $status = '貸出中'; break;
//                                        case 7 : $status = 'delayed'; break;
//                                        case 8 : $status = '終了'; break;
//                                        case 9 : $status = 'キャンセル'; break;
//                                        case 10 : $status = 'ignored'; break;
//                                    }
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
                                    <td>@if($book->miles == '0')  <!--{{$inven->current_mileage}}--> @else {{ $book->miles }} @endif</td>
                                    <td>{{ $book->admin_memo }}</td>
                                    <td>
                                        {{-- $status --}}
                                        @if($book->status == 9)
                                            キャンセル
                                        @else
                                            @if($book->depart_task == '0')
                                                @if(time() < strtotime($book->departing))
                                                    成約 - 配車前
                                                @else
                                                    成約
                                                @endif
                                            @elseif($book->depart_task == '1' && $book->return_task == '0')
                                                貸出中
                                            @elseif($book->return_task == '1')
                                                終了
                                            @endif
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            @foreach( $repairs as $rp )
                                <tr>
                                    <?php
                                    //1:booked 2:pending 3:updated 4:processed 5:end 6:canceled
                                    switch($rp->status) {
                                        case 1 : $status = 'submit'; break;
                                        case 2 : $status = 'using'; break;
                                        case 3 : $status = 'finished'; break;
                                    }
                                    ?>
                                    <td><a href="{{URL::to('/')}}/carrepair/{{ $rp->id }}">SB {{ $rp->inspection_id }}</a></td>
                                    <td>
                                        @if($rp->kind == 1)
                                            修理/車検
                                        @elseif($rp->kind == 2)
                                            代車特約
                                        @else
                                            事故代車
                                        @endif
                                    </td>
                                    <td>{{ date('Y/m/d', strtotime($rp->begin_date)) }}</td>
                                    <td>{{ date('Y/m/d', strtotime($rp->end_date)) }}</td>
                                    <td>{{$rp->mileage}}</td>
                                    <td>{{ $rp->memo }}</td>
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

    <style>
        #info-table th { vertical-align: middle;text-align: center;}
    </style>

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
    @include('scripts.admin.carinventory.show')
    @include('scripts.delete-modal-script')
            <!-- Jquery Validate -->
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="{{URL::to('/')}}/js/home.js"></script>

@endsection