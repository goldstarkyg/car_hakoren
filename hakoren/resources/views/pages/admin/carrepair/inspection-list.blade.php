@extends('layouts.adminapp')

@section('template_title')
    車両修理一覧
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@endsection
{{--@inject('service', 'App\Http\Controllers\CarRepairController')--}}
@section('content')
    <style>
        td {
            vertical-align:middle;
        }
        .btn-circle_active {
            width: 10px;
            height: 10px;
            padding: 3px 0;
            border-radius: 5px;
            margin-left: 5px;
            background-color: #13e313;
            position: absolute;
            margin-top: 5px;;
        }
        .btn-circle_inactive {
            width: 10px;
            height: 10px;
            padding: 3px 0;
            border-radius: 15px;
            margin-left: 5px;
            background-color:#f44336;
            position: absolute;
            margin-top: 5px;;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2 style="display: inline-block;margin-right:30px;">車両{{$inventory->shortname}}の修理/車検一覧</h2>

                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="{{URL::to('/')}}/carrepair/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-car" aria-hidden="true"></i>
                        作成する
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">

                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>タイプ</th>
                                    {{--<th>車両略称</th>--}}
                                    {{--<th>モデル</th>--}}
                                    {{--<th>所属店舗</th>--}}
                                    <th>開始日</th>
                                    <th>終了日</th>
                                    {{--<th>修理店</th>--}}
                                    <th>価格(円)</th>
                                    <th>状態</th>
                                    <th> </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($repairs as $repair)
                                    <?php if($repair->kind == 1){
                                        $kind = '修理/車検';
                                    } else if($repair->kind == 2) {
                                        $kind = '代車特約';
                                    } else {
                                        $kind = '事故代車';
                                    }  ?>
                                    <tr align="middle">
                                        <td>{{$repair->inspection_id}}</td>
                                        <td>{{$kind}}</td>
                                        {{--<td>{{$inventory->shortname}}</td>--}}
                                        {{--<td>{{$repair->model_name}}</td>--}}
                                        {{--<td>{{$repair->shop_name}}</td>--}}
                                        <td>{{$repair->begin_date}}</td>
                                        <td>{{$repair->end_date}}</td>
{{--                                        <td>{{$repair->repair_shop}}</td>--}}
                                        <td>{{number_format($repair->price)}}</td>
                                        <?php
                                        if($repair->status == 1) $status = '設定';
                                        if($repair->status == 2) $status = '開始';
                                        if($repair->status == 3) $status = '終了';
                                        ?>
                                        <td>{{$status}}</td>
                                        <td>
                                            <label>
                                                <a class="btn btn-sm btn-success" href="{{ URL::to('/carrepair/' . $repair->id) }}" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="{{ URL::to('/carrepair/' . $repair->id . '/edit') }}" title="編集">
                                                    <span class="hidden-xs hidden-sm">編集</span>
                                                </a>
                                            </label>
                                        </td>
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

    @include('modals.modal-delete')


<script type="text/javascript">
    function searchRepair() {
        $('#search').submit();
    }
</script>


@endsection

@section('footer_scripts')
    @include('scripts.admin.carinventory.index')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
@endsection