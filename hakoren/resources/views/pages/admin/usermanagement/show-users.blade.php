@extends('layouts.adminapp')

@section('template_title')
    管理者一覧
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

    </style>
@endsection

@section('content')
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>管理者一覧</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                <a href="{{URL::to('/')}}/settings/endusers/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                    <i class="fa fa-fw fa-user" aria-hidden="true"></i>
                    管理者の追加
                </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        {{--<div class="panel-heading">--}}
                            {{--<div style="display: flex; justify-content: space-between; align-items: center; font-size:16px; color:#222;">--}}
                                {{--送信日期間--}}
                                {{--<div class="pull-left" style="margin-right:210px;">--}}
                                    {{--<form id="searchform" action="/settings/endusers" method="post">--}}
                                        {{--{!! csrf_field() !!}--}}
                                        {{--<input type="text" class="form-control" id="dateinterval" name="dateinterval"--}}
                                               {{--value = "{{$dateinterval }}" readonly onchange="clicksearch()" placeholder="日付を選択してください">--}}
                                    {{--</form>--}}
                                {{--</div>--}}
                                {{--<div class="btn-group pull-right btn-group-xs">--}}
                                    {{--<div class="pull-right">--}}
                                        {{--<a href="/settings/endusers/export" class="btn btn-info btn-xs pull-right downloaduser">--}}
                                            {{--<i class="fa fa-lock"></i>&nbsp;会員データをダウンロードします--}}
                                        {{--</a>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                <tr>
                                    {{--<th>会員ID</th>--}}
                                    <th>メール</th>
                                    <th>役割</th>
                                    <th class="hidden-xs">氏名</th>
                                    {{--<th class="hidden-xs">登録日</th>--}}
                                    <th> </th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <?php
                                    $created_at = new DateTime($user->created_at);
                                    $d1 = new DateTime(date('Y-m-d'));
                                    $d2 = new DateTime($user->created_at);
                                    $diffm = $d1->diff($d2)->m;
                                    $diffy = $d1->diff($d2)->y;
                                    if($diffy == 0){
                                        $diff = $diffm.'ヵ月';
                                    }else{
                                        $diff = $diffy.'年 '.$diffm.'ヵ月';
                                    }
                                    ?>
                                    <tr  valign="middle">
                                        {{--<td style="vertical-align: baseline;">{{str_pad($user->id, 6, '0', STR_PAD_LEFT)}}</td>--}}
                                        <td style="vertical-align: baseline;">{{$user->email}}</td>
                                        <td style="vertical-align: baseline;">
                                                @if ($user->role_slug == 'user')
                                                    @php $labelClass = 'primary' @endphp

                                                @elseif ($user->role_slug == 'admin')
                                                    @php $labelClass = 'warning' @endphp

                                                @elseif ($user->role_slug == 'unverified')
                                                    @php $labelClass = 'danger' @endphp

                                                @elseif ($user->role_slug == 'subadmin')
                                                    @php $labelClass = 'info' @endphp

                                                @else
                                                    @php $labelClass = 'default' @endphp

                                                @endif
                                                <span class="label label-{{$labelClass}}">{{ $user->role_slug }}</span>
                                         </td>
                                        <td class="hidden-xs" style="vertical-align: baseline;">{{$user->last_name}} {{$user->first_name}}</td>
                                        {{--<td class="hidden-xs" style="vertical-align: baseline;"> {{ $diff }} </td>--}}
                                        <td style="vertical-align: baseline;">
                                            {!! Form::open(array('url' => URL::to('/').'/settings/endusers/' . $user->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            <?php
                                            $params = array('class' => 'btn btn-danger btn-sm',
                                                'type' => 'button',
                                                'data-toggle' => 'modal',
                                                'data-target' => '#confirmDelete',
                                                'data-title' => '管理者の削除',
                                                'data-message' => 'この管理者を完全に削除しますか？');
                                            if($user->id == 1) {
                                                $params['disabled'] = 'disabled';
                                            }
                                            ?>
                                            {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                                <span class="hidden-xs hidden-sm">削除</span>', $params ) !!}
                                            {!! Form::close() !!}
                                        </td>
                                        <td style="vertical-align: baseline;">
                                            <a class="btn btn-sm btn-block" href="{{ URL::to('/settings/endusers/' . $user->id . '/edit') }}" data-toggle="tooltip" title="編集" style="-webkit-border-radius: 2px;-moz-border-radius: 2px; border-radius: 2px; background:#979797; color:#fff; padding:3px 4px; font-size:1.1em; font-weight: 500; ">
                                                <span class="hidden-xs hidden-sm">編集</span>
                                            </a>
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

    @include('scripts.endusersdatatables')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    {{--
        @include('scripts.tooltips')
    --}}
    <script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>

    <!-- Date range picker -->
    <script src="{{URL::to('/')}}/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script>
        function clicksearch(){
            $('#searchform').submit();
        }
        $('#dateinterval').daterangepicker(
                {
                    format: 'YYYY/MM/DD',
                }, function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                    $('#reportrange span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
                });
    </script>
@endsection