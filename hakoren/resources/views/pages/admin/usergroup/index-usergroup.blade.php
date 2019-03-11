@extends('layouts.adminapp1')

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
                    <a href="{{URL::to('/')}}/settings/usergroup/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        作成する
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                <tr>
                                    <th>会員ID</th>
                                    <th>Group Name</th>
                                    <th>Group Alias</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($usergroup as $group)
                                    <tr  valign="middle">
                                        <td style="vertical-align: baseline;">{{str_pad($group->id, 6, '0', STR_PAD_LEFT)}}</td>
                                        <td style="vertical-align: baseline;">{{$group->name}}</td>
                                        <td class="hidden-xs" style="vertical-align: baseline;">{{$group->alias}}</td>
                                        <td style="vertical-align: baseline;">
                                            <label>
                                            {!! Form::open(array('url' => URL::to('/').'/settings/usergroup/' . $group->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            {!! Form::button('<span class="hidden-xs hidden-sm">削除</span>',
                                                array('class' => 'btn btn-danger btn-sm','type' => 'button' ,'data-toggle' => 'modal',
                                                'data-target' => '#confirmDelete', 'data-title' => 'Delete User Group', 'data-message' => 'Do you wnat to delete this group.')) !!}
                                            {!! Form::close() !!}
                                            </label>
                                            <label>
                                            <a class="btn btn-sm" href="{{ URL::to('/settings/usergroup/' . $group->id . '/edit') }}" data-toggle="tooltip" title="編集" style="-webkit-border-radius: 2px;-moz-border-radius: 2px; border-radius: 2px; background:#979797; color:#fff; padding:3px 4px; font-size:1.1em; font-weight: 500; ">
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

@endsection

@section('footer_scripts')

    <link href="{{URL::to('/')}}/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">

    @include('scripts.admin.usergroup.index-usergroup')
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