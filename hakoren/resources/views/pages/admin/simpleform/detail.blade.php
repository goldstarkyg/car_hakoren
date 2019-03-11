@extends('layouts.adminapp')

@section('template_title')
    リクエストの承認 {{ $user_name }}
@endsection

@section('template_linked_css')
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
        .control-label{
            margin-top: -7px;;
        }
    </style>
@endsection

@section('content')

    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href='{{URL::to('/')}}/css/bootstrap-select.css' rel='stylesheet' type='text/css'>
    <link href="{{URL::to('/')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>フォーム情報の詳細: {{str_pad($simpleform->id, 6, '0', STR_PAD_LEFT)}}</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;">
                <a href="{{URL::to('/')}}/simpleform/{{$simpleform->id}}/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                    <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                    フォーム情報の編集
                </a>

                <a href="{{URL::to('/')}}/simpleform" class="btn btn-info btn-xs pull-right">
                    <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                    フォーム一覧 <!--a list of form -->
                </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div style="position: absolute; margin-top:20px ;right: 20px;">
                <label>
                <a class="btn btn-primary btn-sm pull-right" href="{{ URL::to('simpleform/' . $simpleform->id . '/edit') }}" title="編集"
                   style="margin-left: 1em;" >
                    <span class="hidden-xs hidden-sm">編集する</span>
                </a>
                </label>
                <label>
                {!! Form::open(array('url' => URL::to('/').'/simpleform/' . $simpleform->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                {!! Form::hidden('_method', 'DELETE') !!}
                {!! Form::button('
                    <span class="hidden-xs hidden-sm">削除する</span>',
                    array(
                        'class' => 'btn btn-danger btn-sm pull-right',
                        'type' => 'button' ,
                        'data-toggle' => 'modal',
                        'data-target' => '#confirmForm',
                        'data-title' => 'フォームの削除',
                        'data-message' => 'このフォームを完全に削除しますか？'
                        )) !!}
                {!! Form::close() !!}
                </label>
            </div>
            <div class="panel panel-default shadow-box">
                @if ($simpleform->id)
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active edit_profile">
                                {!! Form::model($simpleform, array('method' => 'PATCH', 'action' => array('SimpleFormController@update',
                                               $simpleform->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form',
                                               'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}

                                {{ csrf_field() }}
                                <div>
                                    <label style="font-size: 16px;">&#9312;</label>
                                    <label>送信者情報</label>
                                </div>
                                <div class="row">
                                        <div class="form-group">
                                            <label  class="col-sm-3 control-label">
                                                ID</label>
                                            <div class="col-sm-9">
                                                {{str_pad($simpleform->id, 6, '0', STR_PAD_LEFT)}}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">
                                                送信日時
                                            </label>
                                            <div class="col-sm-9">
                                                 {{$simpleform->created_at}}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  class="col-sm-3 control-label">
                                                名前
                                            </label>
                                            <div class="col-sm-9">
                                                 {{ $simpleform->firstname." ".$simpleform->lastname}}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">
                                                メール
                                            </label>
                                            <div class="col-sm-3">
                                                 {{ $simpleform->email }}
                                            </div>
                                            <label class="col-sm-3 control-label">
                                                電話番号
                                            </label>
                                            <div class="col-sm-3">
                                                {{ $simpleform->phone }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">
                                                その他利用者情報
                                            </label>
                                            <div class="col-sm-9">
                                                    {{ $simpleform->otherper_personal_info }}
                                            </div>
                                        </div>
                                </div>
                                <!--2 items for trip info-->
                                <hr>
                                <div>
                                    <label style="font-size: 16px;">&#9313;</label>
                                    <label>ご利用情報</label>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label  class="col-sm-3 control-label">
                                            ご利用店舗
                                        </label>
                                        <div class="col-sm-9">
                                                 @if($simpleform->location == 'huku')
                                                   福岡空港店
                                                 @elseif($simpleform->location == 'okina')
                                                      沖縄空港店
                                                 @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label  class="col-sm-3 control-label">
                                            車種
                                        </label>
                                        <div class="col-sm-9">
                                            <div>
                                                {{ $simpleform->cartitle }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label  class="col-sm-3 control-label">
                                            出発
                                        </label>
                                        <div class="col-sm-3">
                                                {{ $simpleform->startdate." ".$simpleform->starttime }}
                                        </div>
                                        <label class="col-sm-3 control-label">
                                            返却
                                        </label>
                                        <div class="col-sm-3">
                                            {{ $simpleform->enddate." ".$simpleform->endtime }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            メッセージ
                                        </label>
                                        <div class="col-sm-9">
                                            {{ $simpleform->message}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            オプション
                                        </label>
                                        <div class="col-sm-9">
                                            {{ $simpleform->car_option_service}}
                                        </div>
                                    </div>
                                </div>
                                <!--3 items memo for shop staff)-->
                                <hr>
                                <div>
                                    <label style="font-size: 16px;">&#9314;</label>
                                    <label>店舗側記録</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        店舗メモ
                                    </label>
                                    <div class="col-sm-9">
                                        {{ $simpleform->shop_memo}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        ステータス
                                    </label>
                                    <div class="col-sm-9">
                                        @foreach($simpleform_status as $status)
                                            @if($simpleform->status_id == $status->id)
                                                {{$status->name}}
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        最終担当者
                                    </label>
                                    <div class="col-sm-9">
                                        {{$simpleform->final_person}}
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="panel-body">
                        <p>{{ trans('simpleform.nosimpleformYet') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('modals.modal-form')
@endsection

@section('footer_scripts')
    <script src="{{URL::to('/')}}/js/alterclass.js"></script>
    @include('scripts.form-modal-script')
@endsection