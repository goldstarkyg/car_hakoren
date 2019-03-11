<?php
$profile = $user->profile;
$category = $profile->usercategory;
?>
@extends('layouts.adminapp1')

@section('template_title')
    会員の個別情報 {{ $user->name }}
@endsection

@section('content')
    <div>
        <div class="row">
            <div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12" style="padding:0px;">
                            <div class="col-xs-6" style="padding:0px;">
                                <h4 class="adminpagetitle" style="margin:0px;">{{ $user->last_name }} {{ $user->first_name }} 様の個別情報</h4>
                            </div>
                            <div class="col-md-6" style="text-align: right;padding-top: 10px;">
                                <a href="{{URL::to('/')}}/members" class="btn btn-info btn-xs pull-right">
                                    <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                                    会員一覧
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $colors = array('rgba(63, 183, 63, 1)',
                        'rgba(237, 240, 84, 1)',
                        'rgba(154, 227, 244, 1)',
                        'rgba(255, 165, 0, 1)',
                        'rgba(191, 191, 191, 1)');
        $blurcolors = array('rgba(63, 183, 63, 0.28)',
                            'rgba(237, 240, 84, 0.28)',
                            'rgba(154, 227, 244, 0.28)',
                            'rgba(255, 165, 0, 0.28)',
                            'rgba(191, 191, 191, 0.28)');
        ?>

        <div class="row">
            <div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div>
                            <div>
                                <div>
                                    <!--detail-->
                                    <div class="shadow-box" style="Font-size: 16px;font-weight:500;border-top:4px #edf058 solid;background:#fafbcf;padding-bottom:10px;">
                                        <!--user link-->
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p>{{ $profile->fur_last_name }}{{ $profile->fur_first_name }}</p>
                                                <p><span style="Font-size:20px; font-weight:700;">{{ $user->last_name }}{{ $user->first_name }}</span></p>
                                            </div>
                                            <div class="col-md-9" >
                                                {!! Form::open(array('url' => URL::to('/').'/members/' . $user->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                                    <span class="hidden-xs hidden-sm">この会員を削除する</span>',
                                                    array('class' => 'btn btn-link pull-right btn-action dst',
                                                        'type' => 'button' ,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#confirmDelete',
                                                        'data-title' => '会員の削除',
                                                        'data-message' => 'この会員を完全に削除しますか？'
                                                        )) !!}
                                                {!! Form::close() !!}

                                                <a href="{{URL::to('/')}}/members/{{$user->id}}/edit" class="btn-link btn-action pull-right" style="margin-right:5px;">
                                                    <i class="fa fa-pencil"></i>&nbsp;会員情報を編集する
                                                </a>
                                                    <!--delete modal-->
                                                <div class="modal fade modal-danger" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                {!! Form::open(array('url' => 'members/' . $user->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">会員の削除</h4>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <p>この会員を会員リストから削除します。</p>
                                                                    {{--<input type="text" class="form-control" id="deltext{{ $user->id }}" name="deltext{{ $user->id }}" value="" placeholder="会員権限の停止理由をお書きください。（オプション）">--}}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    {!! Form::button('<i class="fa fa-fw fa-close" aria-hidden="true"></i> 戻る',
                                                                        array('class' => 'btn btn-outline pull-left btn-flat',
                                                                              'type' => 'button',
                                                                              'data-dismiss' => 'modal' )) !!}
                                                                    {!! Form::submit('削除',
                                                                        array('class' => 'btn btn-danger pull-right btn-flat',
                                                                              'type' => 'button',
                                                                              'id' => 'confirm' )) !!}
                                                                </div>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <!--user detail-->
                                        <div class="row">
                                            <div class="col-md-4">
                                            <?php
                                                if ($profile->birth){
                                                    $nowyear = date('Y');
                                                    $year = date('Y', strtotime($profile->birth));
                                                    $age1 = $nowyear - $year;
                                                    $age_gender = $age1.'歳 / '.($profile->sex == 1)? '男性':'女性';
                                                    $dob = date('Y/m/d', strtotime($profile->birth));
                                                } else {
                                                    $age_gender = '年齢/性別'; $dob = '生年月日';
                                                }

                                                $address = '';
                                                $zip = '';
                                                $prefecture = '';
                                                if(!is_null($category) ) {
                                                    if(!is_null($profile)){
                                                        if($category->name == 'individual'){
                                                            $address = $profile->address1.$profile->address2;
                                                            $zip = $profile->postal_code.$profile->city;
                                                            $prefecture = $profile->prefecture;
                                                        } elseif($category->name == 'foreigner') {
                                                            $address = $profile->foreign_city.' '.$profile->foreign_address;
                                                            $zip = $profile->foreign_zip_code;
                                                            $prefecture = $profile->foreign_country.' '.$profile->foreign_state;
                                                        } elseif($category->name == 'corporate') {
                                                            $address = $profile->company_name.$profile->company_address1;
                                                            $zip = $profile->company_postal_code;
                                                            $prefecture = $profile->company_prefecture.$profile->company_city;
                                                        }
                                                    }
                                                }
                                                ?>

                                                <p><b>住所: </b>{!! $zip !!}</b></p>
                                                <p><b>電話番号: </b>{{ is_null($profile)? '<br>':$profile->phone }}</p
                                                <p><b>メール: </b>{{ $user->email }}</p>
                                                <p>{!! $prefecture !!}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><b>会員ID: </b>{!! $user->name !!}</p>
                                                <p><b>ご利用店舗:</b>{{ $user->used_shops }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><b>最終ご利用日:</b>{{ $user->last_use }}</p>
                                                <p><b>登録日: </b>{{ date('Y/m/d',strtotime($user->created_at)) }}</p>
                                                <p>{!! $prefecture !!}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!--user history-->
                                    <div class="m-t-lg" style="border-bottom: 1px solid #ddd !important;">
                                        <h2>ご利用履歴</h2>
                                    </div>
                                    <div class="m-t-sm" >
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>予約ID</th>
                                                    <th>予約日</th>
                                                    <th>経由</th>
                                                    <th>ご利用日</th>
                                                    <th>貸出車</th>
                                                    <th>迎え/送り</th>
                                                    <th>合計金額</th>
                                                    <th>担当</th>
                                                    <th>メモ</th>
                                                    <th>アクション</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($bookings as $book)
                                                <tr>
                                                    <td>{{ $book->booking_id }}</td>
                                                    <td>
                                                        {{ date('Y/m/d', strtotime($book->created_at)) }}<br>
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
                                                    <td>{{ $book->reservation }}</td>
                                                    <td>{{ date('Y/m/d', strtotime($book->departing)) }}</td>
                                                    <td>{{ $book->car_name }} @if($book->car_deleted == 1) 削除済 @endif</td>
                                                    <td>{{ $book->depart_shop }}<br>{{ $book->return_shop }}</td>
                                                    <td>{{ $book->payment }}</td>
                                                    <td>{{ $book->last_name }}{{ $book->first_name }}</td>
                                                    <td>{{ $book->admin_memo }}</td>
                                                    <td><a href="{{URL::to('/')}}/booking/detail/{{ $book->id }}" class="btn btn-primary">詳細</a></td>
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
            </div>
        </div>
    </div>
    </div>
    {{--@include('modals.modal-userdelete')--}}

@endsection

@section('footer_scripts')
    <style>
        .btn-action {
            background:#eee;
            padding:4px 10px;
            border-radius:2px;
            border:1px #999 solid;
            font-size: 14px;
        }
        .btn-action.dst{
            background:#eee;
            padding:4px 10px;
            border-radius:2px;
			color:#DB0000;
            border:1px #999 solid;
            font-size: 14px;
        }
		.btn-action:hover{
            background:#eee;
            border:1px #999 solid;}
		.btn-action.dst:hover{
			color:red;}
    </style>

    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/enduser.css">
    <link href="{{URL::to('/')}}/css/plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet">

    @include('scripts.admin.member_show')
    @include('scripts.delete-modal-script')
@endsection
