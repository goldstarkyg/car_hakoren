@extends('layouts.adminapp')

@section('template_title')
    簡易フォーム
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }
        .datepicker{
            background: #ffffff;
        }
        .table-responsive {
           overflow-x:hidden !important;
        }
    </style>
@endsection

@section('content')
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>簡易フォーム /
				    @if(!empty($status_name)){{$status_name->name}}@endif
                    @if($location == "huku")(福岡空港店)@endif
                    @if($location == "okina")(那覇空港店)@endif
                    <div class="btn-group pull-right btn-group-xs">
                        <div class="pull-right">
                            <a href="{{URL::to('/')}}/simpleform/export" class="btn btn-info btn-xs pull-right downloaduser">
                                <i class="fa fa-lock"></i>&nbsp;csvデータをダウンロード
                            </a>
                        </div>
                    </div>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size:16px; color:#222;">
                            表示条件の設定
                            <a href="#" class="btn btn-info btn-xs" id="rerfresh" style="background-color:#909090;border-color:#909090">
                                <i class="fa fa-refresh"></i>&nbsp;
                            </a>
                        </div>
                        <div>
                          <form id="searchform" action="{{URL::to('/')}}/simpleform" method="post">
                                {!! csrf_field() !!}
                                {!! Form::hidden('status_id',null,['id' => 'status_id']) !!}
                                {!! Form::hidden('location',null,['locatoin' => 'location']) !!}
                                <div class="row">
                                    <div class="col-md-2">
                                    送信日期間
                                    </div>
                                    <div class="col-md-4">
                                    <input type="text" class="form-control" id="dateinterval" name="dateinterval"
                                       value="{{ $dateinterval }}" readonly onchange="clicksearch()" placeholder="日付を選択してください"
                                       >
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 5px;">
                                    <div class="col-md-2">
                                        出発日
                                    </div>
                                   <div class="col-md-4" >
                                       <div id="startdate" class="input-group date" >
                                           <input type="text" name="startdate" readonly class="form-control"
                                                  value="{{$startdate}}" onchange="clicksearch()" placeholder="日付を選択してください" >
                                           <div class="input-group-addon">
                                               <span class="glyphicon glyphicon-th"></span>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="col-md-2">
                                        返却日
                                    </div>
                                    <div class="col-md-4" >
                                        <div id="enddate"  class="input-group date" >
                                            <input type="text" name="enddate" readonly class="form-control"
                                                   value="{{$enddate}}" onchange="clicksearch()" placeholder="日付を選択してください" >
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="text-center" style="margin-bottom: 10px;">
                            <span style="font-size:16px; font-weight:700;color:#555;">
                                @if($location == "huku")
                                    福岡
                                @elseif($location == "okina")
                                    那覇
                                @endif

                                空港店のステータス @if(!empty($status_name))（{{$status_name->name}}） @endif /上記条件に当てはまるフォーム件数は{{count($simpleforms)}}件です。
                            </span>
                        </div>
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>送信日</th>

                                    <th class="hidden-xs">氏名</th>
                                    <th class="hidden-xs">車種</th>
                                    <th class="hidden-xs">出発/返却</th>
                                    <th class="hidden-xs">状態</th>
                                    <th class="hidden-xs">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($simpleforms as $simple )
                                    <tr  valign="middle">
                                        <td style="vertical-align:middle">{{str_pad($simple->id, 6, '0', STR_PAD_LEFT)}}</td>
                                        <td style="vertical-align:middle">{{date('Y/n/j',strtotime($simple->created_at))}}</td>
                                        <td class="hidden-xs" style="vertical-align:middle">{{$simple->firstname.' '.$simple->lastname}}</td>
                                        <td class="hidden-xs" style="vertical-align:middle">{{$simple->cartitle}} </td>
                                        <td class="hidden-xs" style="vertical-align:middle" >
                                            {{date('Y/n/j',strtotime($simple->startdate)).' ~ '.date('Y/n/j',strtotime($simple->enddate))}}</td>
                                        <td class="hidden-xs" style="vertical-align: middle;">
                                            @if(empty($simple->status_id))
                                                未対応
                                            @else
                                                {{$simple->status_name.' / '.$simple->final_person}}
                                            @endif
                                        </td>
                                        <td style="vertical-align:middle">
                                                <div style="margin-bottom: 3px;">
                                                    <a class="btn btn-sm" href="{{URL::to('/')}}/simpleform/{{$simple->id}}" title ="詳細"
                                                       style="-webkit-border-radius: 2px;-moz-border-radius: 2px; border-radius: 2px; background:#979797; color:#fff; padding:3px 4px; font-size:0.9em; font-weight: 400; ">
                                                       <span class="hidden-xs hidden-sm">詳細</span>
                                                    </a>
                                                </div>
                                                <div>
                                                    <a class="btn btn-sm" href="{{ URL::to('simpleform/' . $simple->id . '/edit') }}" title="編集"
                                                       style="-webkit-border-radius: 2px;-moz-border-radius: 2px; border-radius: 2px; background:#979797; color:#fff; padding:3px 4px; font-size:0.9em; font-weight: 400; ">
                                                        <span class="hidden-xs hidden-sm">編集</span>
                                                    </a>
                                                </div>
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

@endsection

@section('footer_scripts')

    <link href="{{URL::to('/')}}/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    {{--<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">--}}
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">

    @include('scripts.simpleformdatatable')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    {{--
        @include('scripts.tooltips')
    --}}
    {{--<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>--}}
    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>

    <!-- Date range picker -->
    <script src="{{URL::to('/')}}/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script>
        function clicksearch(){
            $('#searchform').submit();
        }
        $('#dateinterval').daterangepicker(
                {
                    format: 'YYYY/MM/DD',
                    "locale": {
                        "fromLabel": "から",
                        "toLabel": "に",
                        "daysOfWeek": [
                            "日",
                            "月",
                            "火",
                            "水",
                            "木",
                            "金",
                            "土"
                        ],
                        "monthNames": [
                            "1月",
                            "2月",
                            "3月",
                            "4月",
                            "5月",
                            "6月",
                            "7月",
                            "8月",
                            "9月",
                            "10月",
                            "11月",
                            "12月"
                        ],
                    }
                }, function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                    $('#reportrange span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
                });

        $('#startdate').datepicker({
            language: "ja",
            format: 'yyyy/mm/dd',
            orientation: "bottom",
            autoclose: true,
        });

        $('#enddate').datepicker({
            language: "ja",
            format: 'yyyy/mm/dd',
            orientation: "bottom",
            autoclose: true,
        });

        $('#rerfresh').click(function() {
            $('#dateinterval').val("");
            $('input[name="startdate"]').val("");
            $('input[name="enddate"]').val("");
            $('#searchform').submit();
        });
    </script>
@endsection