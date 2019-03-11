@extends('layouts.adminapp')

@section('template_title')
    予約の詳細
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/js/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/js/slick/slick-theme.css">
    <style type="text/css" media="screen">
        .users-table { border: 0; }
        .users-table tr td:first-child { padding-left: 15px; }
        .users-table tr td:last-child { padding-right: 15px; width:25%;}
        .users-table.table-responsive,
        .users-table.table-responsive table { margin-bottom: 0; }
        .left_back{
            background-color: #dfeaff;
        }
        table.table-bordered{
            border:1px solid #a7a7a7;
            margin-top:20px;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #a7a7a7;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid #a7a7a7;
            width:25%;
        }
        .title{
            margin-left: 0px;;
        }
        .modal_div div div {
            margin-top:15px;
        }
        .lic-thumb { display: inline-block; margin-right: 10px;}
    </style>
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>予約詳細：{{$book->booking_id}}/
                    @if($book->last_name == '')
                        {{ $book->fur_last_name }}
                    @else
                        {{ $book->last_name }}
                    @endif
                    様
                </h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                <!-- <a href="{{URL::to('/')}}/booking/delete/{{ $book->id }}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                        削除する
                    </a>-->
                    &emsp;
                    <a href="{{URL::to('/')}}/booking/edit/{{ $book->id }}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        編集する
                    </a>
                    &emsp;
                    <a href="{{URL::to('/')}}/booking/all" class="btn btn-info btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                    &emsp;
                </div>
            </div>
        </div>
        <style>
            .col-lg-12 { margin-bottom:10px }
        </style>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div>
                    <!--start first part-->
                    <table class="table table-bordered users-table">
                        <tr>
                            <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                ①予約/会員
                            </td>
                            @if(!empty($book->bad_flag)&& $book->bad_flag == '1')
                            <td colspan="3" style="text-align:center;font-size:17px;font-weight:500;color:#940a0a;">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 初期取込データです！
                            </td>
                            @endif
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="status" >予約</label>
                            </td>
                            <td>

                                予約ID: {{$book->booking_id}}<br/>
                                成約日：{{ $book->created_at}}<br/>
                                @if($book->portal_flag == '1')
                                外部：{{ date('Y/m/d H:i',strtotime($book->submited_at))}}<br/>
                                @endif
								更新：{{$book->updated_at}}<br/>
								担当：{{ $book->admin_name }}<br/>
                                経路：@if($book->portal_flag == 0)
                                    @if($book->portal_id == 10000)
                                        @if($book->language == 'ja')
                                            <span>自社HP</span>
                                        @else
                                            <span>自社HPEN</span>
                                        @endif
                                    @else
                                        <span>自社HPAD</span>
                                    @endif
                                @else
                                    <span class="new_row" >{{$book->portal_name}}</span>
                                    <span>{{$book->booking}}</span>
                                @endif <br/>
                                <!--経路：{{$book->reservation_method}} <br/>
                                プランID:{{$book->plan_id}}-->
                            </td>
                            <td class="left_back">
                                <label class="control-label">ステータス</label>
                            </td>
                            <td>
                                状況： {{-- $book->booking_status --}}
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

                                @if($book->status == 9)
                                    <br/>キャンセル日: {{ $book->canceled_at }}
                                @endif
                                <hr/>
                                <span>
									クイック乗り出し：
                                    @if($book->web_status == 3) 3/3
                                    @elseif($book->web_status == 2) 2/3
                                    @elseif($book->web_status == 1) 1/3
                                    @else --
                                    @endif
								</span><br/>
                                <span>
                                    支払：@if($book->paid_payment > 0 && $book->unpaid_payment == 0) 済 @endif
                                     @if($book->paid_payment > 0 && $book->unpaid_payment > 0)  一部 決済（追加：未払い）@endif
                                     @if($book->paid_payment == 0 && $book->unpaid_payment > 0)  未払い @endif
                                </span><br/>
                                @if($book->cancel_status > 0 )
                                <span>
                                    キャンセル料金：
                                    @if($book->cancel_status  == '10') 未請求 @endif
                                    @if($book->cancel_status  == '11') 請求中 @endif
                                    @if($book->cancel_status  == '1') 現金支払済 @endif
                                    @if($book->cancel_status  == '2') カード支払済 @endif
                                    @if($book->cancel_status  == '5') 銀行振込済 @endif
                                    {{$book->cancel_fee}}円
                                </span>
                               @endif
                            </td>
                        </tr>
                        <tr>
                            <td  class="left_back">
                                <label class="control-label" for="last_name" >
                                    会員
                                </label>
                            </td>
                            <td >
                                {{ $book->last_name }} {{ $book->first_name }} 様<br/>
                                ({{ $book->fur_last_name }} {{ $book->fur_first_name }})<br/>
                                @if($book->portal_flag == '0')
                                    会員ID:({{ $book->client_id }})
                                    <a href="{{URL::to('/')}}/members/{{ $book->client_id }}">
                                        >> 会員詳細
                                    </a>
                                @endif
                            </td>
                            <td class="left_back">
                                <label class="control-label" for="first_name" >連絡先</label>
                            </td>
                            <td >
                                {{ $book->phone }}<br/>
                                @if($book->portal_flag == '0')
                                <a href="{{URL::to('/')}}/members/{{ $book->client_id }}">
                                    {{ $book->email }}
                                </a><br/>
                                @else
                                    {{ $book->email }} <br/>
                                @endif
                                緊急：{{ $book->emergency_phone }}
                            </td>
                        </tr>
                        @if($book->portal_flag == '0')
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="address1" >住所</label>
                            </td>
                            <td colspan="3">
                               @if(!empty($user->profile))
							    {{ substr_replace($user->profile->postal_code,"-",3,0) }} {{ $user->profile->address1 }}
                               @endif
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="address1" >顧客メッセージ</label>
                            </td>
                            <td colspan="3">
                                {{$book->client_message}}
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="address1" >担当者メモ</label>
                            </td>
                            <td colspan="3">
                                {{$book->admin_memo}}
                            </td>
                        </tr>
                    </table>
                    <!--end first part-->
                </div>

                <div>
                    <!--start 2nd part-->
                    <table class="table table-bordered users-table">
                        <tr>
                            <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                ②レンタル内容
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="status" >店舗</label>
                            </td>
                            <td>
                            {{ $book->pickup_name }}<!--/ {{ $book->drop_name }}-->
                            </td>
                            <td class="left_back">
                                <label class="control-label">車両</label>
                            </td>
                            <td style="padding:8px">
                                <div class="cold-md-12" style="padding: 3px 0 4px 0; border-bottom: 1px solid #eee">
									お客様のご希望：
                                    @if($book->request_smoke == '2')
                                        どちらでも良い
                                    @elseif($book->request_smoke == '0')
                                        禁煙
                                    @elseif($book->request_smoke == '1')
                                        喫煙
                                    @endif
                                </div>
                                <div>
                                    <span style="float: left">
                                        {{$book->class_name}} / {{$book->shortname}} <br/>
                                        {{$book->car_number1}}{{$book->car_number2}} {{$book->car_number3}} {{$book->car_number4}}<br/>
                                    </span>
                                    <span style="float:right; border: 1px solid red;margin: 7px 5px;border-radius: 10px;padding:3px 10px 3px 10px;">
                                        @if($book->request_smoke == '0')
                                            禁煙
                                        @elseif($book->request_smoke == '1')
                                            喫煙
                                        @endif
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td  class="left_back">
                                <label class="control-label" for="last_name" >
                                    ご利用期間
                                </label>
                            </td>
                            <td >
                                出発：{{ date('Y/m/d H:i', strtotime($book->departing)) }}<br/>
                                返却：@if($book->extend_set_day > 0)
                                    {{ date('Y/m/d', strtotime($book->returning_updated)) }} {{ date('H:i', strtotime($book->returning)) }}<br/>
                                     @else
                                    {{ date('Y/m/d H:i', strtotime($book->returning)) }}<br/>
                                     @endif
                                {{$book->night}}{{$book->day}}<br/>
                            </td>
                            <td class="left_back">
                                <label class="control-label" for="first_name" >補償</label>
                            </td>
                            <td >

                                @if(intval($book->insurance1) > 0 && intval($book->insurance2) == 0) 免責 @endif
                                @if(intval($book->insurance1) > 0 && intval($book->insurance2) > 0) ワイド免責 @endif
								<!--<br/>
								免責補償： {{$book->insurance1}}円 <br/>
								ワイド免責： {{$book->insurance2}}円-->
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="address1" >選択オプション</label>
                            </td>
                            <td >
                                @if($book->shop_number == '1') <!--If shop is Fuku-->
                                    <div>無料オプション
                                        @if($book->portal_flag == '0')
                                            @if(count($book->free_options)) ({!! count($book->free_options) !!} 個) @else (無) @endif：
                                            @foreach($book->free_options as $option)
                                                <span style="padding-right:10px;">
                                                    @if(($option->google_column_number == 101 || $option->google_column_number == 102) && strtotime($book->created_at) <= strtotime('2018-06-18 12:00:00'))
                                                        無料空港送迎
                                                    @else
                                                    {{$option->option_name}}
                                                    @endif
                                                </span>
                                            @endforeach
                                        @else
                                               @if($book->free_options_category == '0') 不要 @endif
                                               @if($book->free_options_category == '1') 国内空港送迎 @endif
                                               @if($book->free_options_category == '2') 国際空港送迎 @endif
                                               @if($book->free_options_category == '3') コインパーキング @endif
                                        @endif
                                    </div>
                                @endif
                                <div>有料オプション @if(count($book->selected_options) > 0) @else (無) @endif：
                                    @foreach($book->selected_options as $op)
                                        <span style="padding-right:10px;"> {{$op->option_name}}({{$op->option_number}}個)</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="phone" >返却情報</label>
                            </td>
                            <td >
                                @if($book->portal_flag == '1')
                                    {{ $book->returning_point }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back" >
                                <label class="control-label" for="phone" >乗車</label>
                            </td>
                            <td >
                                <!--運転：{{ $book->driver_name }} 様<br/>-->
                                乗車人数：{{ $book->passengers }}名
                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="email" >フライト情報</label>
                            </td>
                            <td >
                                @if($book->flight_name != '' && $book->flight_number != '')
                                {{ $book->flight_name }} {{ $book->flight_number }}
                                @else
                                {{ $book->flight_inform }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="postal_code" >クイック乗り出し</label>
                            </td>
                            <td>
                                <div>
								<span>
									 @if($book->web_status == 3) <i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 住所/Web免許<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 同意<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> Web決済 ➡
											@if($book->bag_choosed == '1') フリスク
											@elseif($book->bag_choosed == '2') ぷっちょ
											@elseif($book->bag_choosed == '3') 酔い止め
											@endif
                                    @elseif($book->web_status == 2)  <i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 住所/Web免許<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 同意<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#cfcfcf;"></i> Web決済
                                    @elseif($book->web_status == 1)  <i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 住所/Web免許<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#cfcfcf;"></i> 同意<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#cfcfcf;"></i> Web決済
                                    @else --
                                    @endif
								</span><br/>
                                </div>
                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="prefecture" >運転免許証</label>
                            </td>
                            <td>
                                @foreach($book->driver_license_images as $license_img)
                                    <div class="col-lg-12">
                                        <div class="imgInput col-md-6 col-sm-6 col-xs-12">
                                            <div class="sec-text"><p><span>免許証/表面</span></p></div>
                                            <span class="zoom license_surface">
											<img src="{{$license_img->representative_license_surface}}" alt="" class="imgView img-responsive">
										</span>
                                        </div>
                                        <div class="imgInput col-md-6 col-sm-6 col-xs-12">
                                            <div class="sec-text"><p><span>免許証/裏面</span></p></div>
                                            <span class="zoom license_back">
											<img src="{{$license_img->representative_license_back}}" alt="" class="imgView img-responsive">
										</span>
                                        </div>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                    <!--end 2nd part-->
                </div>

                <div>
                    <!--start 3rd part-->
                    <table class="table table-bordered users-table">
                        <tr>
                            <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                ③料金
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label">現在の総支払額</label>
                            </td>
                            <td colspan="3">
								<span style="font-size:25px;font-weight:700;">　{{$book->paid_payment}}円</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="left_back">
                                @if($book->portal_flag == '0')
                                    <label class="control-label">公式HP予約</label>
                                @else
                                    <label class="control-label">外部予約</label>
                                @endif
                            </td>
                            <td colspan="3">
								<div class="row">
									<div class="col-md-12" style="margin:10px 0;">
                                        <?php
                                        $plus_minus = '';
                                        if($book->alladjustment >= 0) $plus_minus = '+';
                                        else $plus_minus = '-';
                                        ?>
										合計金額：{{$book->paid_payment+$book->unpaid_payment}} 円 ( = 基本{{$book->basic_price}}円 + オプ{{$book->option_price_sum}}円 + 免責{{$book->insurance1_sum}}円 + ワ{{$book->insurance2_sum}}円 {{$plus_minus}} 調整{{abs($book->alladjustment)}}円 + 延泊{{$book->allextendnight}}円 )
                                            ポ{{$book->given_points}}円
									</div>
								</div>
								{{--<hr style="margin:5px;padding:5px;"/>--}}
								{{--<div class="row">--}}
									{{--<div class="col-md-4">--}}
										{{--未払い：{{$book->unpaid_payment}} 円--}}
									{{--</div>--}}
									{{--<div class="col-md-4">--}}
										{{--Web決済： {{$book->web_payment}}円<br/>Date: {{date('Y/m/d',strtotime($book->created_at))}}--}}
									{{--</div>--}}
									{{--<div class="col-md-4">--}}
										{{--追加注文： {{$book->extend_payment}}円<br/>Date: {{date('Y/m/d',strtotime($book->extend_return_date))}}--}}
									{{--</div>--}}
								{{--</div>								--}}
                            </td>
                        </tr>

                        <tr>
                            <td class="left_back">
                                <label class="control-label">延泊</label>
                            </td>
                            <td colspan="3">
								<div class="row">
									<div class="col-md-12" style="margin:10px 0;">
										合計金額：{{$book->allextendnight}}円 ( = 延泊基本{{$book->allextendnight_basic}}円（{{$book->allextendnight_extend_day}}日延泊） +延泊オプション{{$book->allextendnight_optionprice}}円 + 延泊補償{{$book->allextendnight_insurance}}円 )
									</div>
								</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back" >
                                <label class="control-label" for="phone" >調整</label>
                            </td>
                            <td >
								{{$book->alladjustment}}円
                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="email" >ポイント</label>
                            </td>
                            <td >
								{{$book->given_points}}円
                            </td>
                        </tr>


                        <tr>
                            <td class="left_back" >
                                <label class="control-label" for="card_number" >カード末尾4桁</label>
                            </td>
                            <td >
								{!! $book->card_last4 !!}
                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="card_brand" >カードブランド</label>
                            </td>
                            <td >
								{!! $book->card_brand !!}
                            </td>
                        </tr>


                        <!--
						<tr>
                            <td class="left_back">
                                <label class="control-label">決済情報</label>
                            </td>
                            <td colspan="3">
								支払ID：{{ implode(PHP_EOL, str_split($book->pay_id, 27)) }}<br/>
								取引ID：{{ implode(PHP_EOL, str_split($book->trans_id, 27)) }}
                            </td>
                        </tr>
						-->
                        <tr>
                            <td class="left_back">
                                <label class="control-label">支払メモ</label>
                            </td>
                            <td colspan="3">
								{{$book->paying_memo}}
                            </td>
                        </tr>
                    </table>
                    <!--end 3rd part-->
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="{{ URL::to('/booking/edit/' . $book->id ) }}" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
                            </label>
                            <label>
                                {!! Form::open(array('url' => URL::to('/').'/booking/delete/' . $book->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => '予約を削除',
                                        'data-message' => 'この予約を本当に削除しますか？この操作を取り消すことはできません。')) !!}
                                {!! Form::close() !!}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="lic-view" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header hidden">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="model_list slider">
                        @foreach($book->driver_license_images as $lic_img)
                            @if(!empty($lic_img->representative_license_surface))
                                <div>
                                    <img src="{{URL::to('/')}}{{$lic_img->representative_license_surface}}" class="img-responsive center-block" >
                                </div>
                            @endif
                            @if(!empty($lic_img->representative_license_back))
                                <div>
                                    <img src="{{URL::to('/')}}{{$lic_img->representative_license_back}}" class="img-responsive center-block" >
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-md-12">
                        <?php $iter = 0; ?>
                        @foreach($book->driver_license_images as $lic_img)
                            @if(!empty($lic_img->representative_license_surface))
                                <div class="lic-thumb active">
                                    <img class="cardetail-thumbnail img-responsive center-block" src="{{URL::to('/')}}{{$lic_img->representative_license_surface}}" width="40" onclick="javascript:$('.model_list').slick('slickGoTo',{!! $iter*2 !!})" style="cursor:pointer;">
                                </div>
                            @endif
                            @if(!empty($lic_img->representative_license_back))
                                <div class="lic-thumb">
                                    <img class="cardetail-thumbnail img-responsive center-block" src="{{URL::to('/')}}{{$lic_img->representative_license_back}}" width="40" onclick="javascript:$('.model_list').slick('slickGoTo',{!! $iter*2 + 1 !!})" style="cursor:pointer;">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    <link href="{{URL::to('/')}}/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <script src="{{URL::to('/')}}/js/slick/slick.js" type="text/javascript" charset="utf-8"></script>
    <style>
        .slider { width: 100%; position: relative; margin-bottom: 20px; }
        .lic-thumb.active { border: 2px solid lightblue; }
        .slick-prev { z-index: 2000; }

        div.dataTables_wrapper {
            width: 1824px;
            margin: 0 auto;
        }
        /* styles unrelated to zoom */
        * { border:0; margin:0; padding:0; }
        p { position:absolute; top:3px; right:28px; color:#555; font:bold 13px/1 sans-serif;}

        /* these styles are for the demo, but are not required for the plugin */
        .zoom {
            display:inline-block;
            position: relative;
        }

        /* magnifying glass icon */
        .zoom:after {
            content:'';
            display:block;
            width:33px;
            height:33px;
            position:absolute;
            top:0;
            right:0;
            /*background:url(icon.png);*/
        }

        .zoom img {
            display: block;
        }

        .zoom img::selection { background-color: transparent; }
    </style>
    @include('scripts.admin.member')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    <script src="{{URL::to('/')}}/js/jquery.zoom.js"></script>
    <script>

        $('.model_list').slick({
            // infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            // autoplaySpeed: 3333000,
        });

        $(document).ready(function(){
            $('.license_surface, .license_back').click(function () {
                $('#lic-view').modal('show');
            });
            // $('#license_surface').zoom({ on:'click' });
            // $('#license_back').zoom({ on:'click' });

            $('.lic-thumb').click( function () {
                $('.lic-thumb.active').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
@endsection
