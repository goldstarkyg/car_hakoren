@extends('layouts.adminapp1')

@section('template_title')
    会員一覧
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
		.btn-edit.btn-sm{padding:2px 10px 4px; margin-bottom:0;}
.table.h-t tbody td{padding: 3px 8px;}
    </style>
@endsection

@section('content')
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>会員一覧</h2>
                <!--
				<div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="{{URL::to('/')}}/members/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-user" aria-hidden="true"></i>
                        新規会員の登録
                    </a>
                </div>
				-->
            </div>
        </div>
        {{--analysis of booking route--}}
        <div class="alert alert-success alert-dismissible">
            {{--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>--}}
            <h4>会員は全体で {{number_format($total_user)}}名で、{{date('n')}}月は {{number_format($new_users)}}名の会員が増えました。
                過去1年間で新規に登録した会員数は{{number_format($before_users)}}名で、このうち{{number_format($before_users_morebooking)}}%が2回以上の予約をしています。
            </h4>
        </div>
        {{--end of booking route--}}
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table h-t" width="100%">
                                <thead>
                                <tr>
                                    <th>会員ID</th>
                                    <th>氏名</th>
                                    <th>フリガナ</th>
                                    <th>県名</th>
                                    <th>メール</th>
                                    <th>電話番号</th>
                                    <th class="hidden-xs" style="min-width:55px!important;">店舗名</th>
                                    <th class="hidden-xs" >最後利用日</th>
                                    <th class="hidden-xs" >再利用</th>
                                    <th class="hidden-xs" >登録日</th>
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
                                        {{--会員ID, 氏名, Prefecture, メール, Phone, Store, Last use, Customer type, 登録日, Action--}}

                                        <td style="vertical-align: baseline;"><a class="" href="{{ URL::to('members/' . $user->id) }}" title="詳細">{{str_pad($user->id, 6, '0', STR_PAD_LEFT)}}</a></td>
                                        <td class="hidden-xs" style="vertical-align: baseline;">
                                            {{$user->last_name}}{{$user->first_name}}
                                        </td>
                                        <td class="hidden-xs" style="vertical-align: baseline;">
                                            {{$user->fur_last_name}}{{$user->fur_first_name}}
                                        </td>
                                        <td style="vertical-align: baseline;" >{{$user->prefecture}}</td>
                                        <td style="vertical-align: baseline;">{{$user->email}}</td>
                                        <td style="vertical-align: baseline;">{{$user->phone}}</td>
                                        <td style="vertical-align: baseline;" >{!! $user->store !!}</td>
                                        <td style="vertical-align: baseline;" >{{ $user->last_use}}</td>
                                        <td style="vertical-align: baseline;">
                                            @if($user->visit_count > 1) リピ{{ $user->visit_count }} @else 初 @endif
                                        </td>
                                        <td class="hidden-xs" style="vertical-align: baseline;"> {{ date('Y/n/j', strtotime($user->created_at))}} </td>
                                        <td style="vertical-align: baseline;">
                                            <label style="margin-bottom:0;">
                                                <a class="btn btn-sm btn-info btn-edit" href="{{ URL::to('/members/' . $user->id . '/edit') }}" title="編集">
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
    <style>
        div.dataTables_wrapper {
            /*width: 1824px;*/
            margin: 0 auto;
        }
        .data-table thead tr th {
            white-space: nowrap;
        }
    </style>
    @include('scripts.admin.member')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
@endsection
