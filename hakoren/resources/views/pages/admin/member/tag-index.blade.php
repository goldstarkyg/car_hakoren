@extends('layouts.adminapp1')

@section('template_title')
    免責補償一覧
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
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>Member Tag Management</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="{{URL::to('/')}}/tag/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
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
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table" width="100%">
                                <thead>
                                <tr>
                                    <th> ID</th>
                                    <th>Abbriviation</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tag_list as $tag)
                                    <tr  valign="middle">
                                        <td style="vertical-align: baseline;">{{str_pad($tag->id, 6, '0', STR_PAD_LEFT)}}</td>
                                        <td class="hidden-xs" style="vertical-align: baseline;">{{$tag->abbriviation}}</td>
                                        <td style="vertical-align: baseline;">{{$tag->name}}</td>
                                        <td style="vertical-align: baseline;">@if($tag->status == '1')Enable @else Disable @endif</td>
                                        <td style="vertical-align: baseline;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="{{ URL::to('/tag/' . $tag->id) }}" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="{{ URL::to('/tag/' . $tag->id . '/edit') }}" title="編集">
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
    @include('scripts.admin.carbasic.carequip-index')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
@endsection