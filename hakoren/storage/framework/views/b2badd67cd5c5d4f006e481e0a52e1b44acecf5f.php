<?php $__env->startSection('template_title'); ?>
    予約の詳細
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/js/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/js/slick/slick-theme.css">
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>予約詳細：<?php echo e($book->booking_id); ?>/
                    <?php if($book->last_name == ''): ?>
                        <?php echo e($book->fur_last_name); ?>

                    <?php else: ?>
                        <?php echo e($book->last_name); ?>

                    <?php endif; ?>
                    様
                </h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                <!-- <a href="<?php echo e(URL::to('/')); ?>/booking/delete/<?php echo e($book->id); ?>" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                        削除する
                    </a>-->
                    &emsp;
                    <a href="<?php echo e(URL::to('/')); ?>/booking/edit/<?php echo e($book->id); ?>" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        編集する
                    </a>
                    &emsp;
                    <a href="<?php echo e(URL::to('/')); ?>/booking/all" class="btn btn-info btn-xs pull-right" style="margin-left: 1em;">
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
                            <?php if(!empty($book->bad_flag)&& $book->bad_flag == '1'): ?>
                            <td colspan="3" style="text-align:center;font-size:17px;font-weight:500;color:#940a0a;">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 初期取込データです！
                            </td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="status" >予約</label>
                            </td>
                            <td>

                                予約ID: <?php echo e($book->booking_id); ?><br/>
                                成約日：<?php echo e($book->created_at); ?><br/>
                                <?php if($book->portal_flag == '1'): ?>
                                外部：<?php echo e(date('Y/m/d H:i',strtotime($book->submited_at))); ?><br/>
                                <?php endif; ?>
								更新：<?php echo e($book->updated_at); ?><br/>
								担当：<?php echo e($book->admin_name); ?><br/>
                                経路：<?php if($book->portal_flag == 0): ?>
                                    <?php if($book->portal_id == 10000): ?>
                                        <?php if($book->language == 'ja'): ?>
                                            <span>自社HP</span>
                                        <?php else: ?>
                                            <span>自社HPEN</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span>自社HPAD</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="new_row" ><?php echo e($book->portal_name); ?></span>
                                    <span><?php echo e($book->booking); ?></span>
                                <?php endif; ?> <br/>
                                <!--経路：<?php echo e($book->reservation_method); ?> <br/>
                                プランID:<?php echo e($book->plan_id); ?>-->
                            </td>
                            <td class="left_back">
                                <label class="control-label">ステータス</label>
                            </td>
                            <td>
                                状況： 
                                <?php if($book->status == 9): ?>
                                    キャンセル
                                <?php else: ?>
                                    <?php if($book->depart_task == '0'): ?>
                                        <?php if(time() < strtotime($book->departing)): ?>
                                            成約 - 配車前
                                        <?php else: ?>
                                            成約
                                        <?php endif; ?>
                                    <?php elseif($book->depart_task == '1' && $book->return_task == '0'): ?>
                                        貸出中
                                    <?php elseif($book->return_task == '1'): ?>
                                        終了
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if($book->status == 9): ?>
                                    <br/>キャンセル日: <?php echo e($book->canceled_at); ?>

                                <?php endif; ?>
                                <hr/>
                                <span>
									クイック乗り出し：
                                    <?php if($book->web_status == 3): ?> 3/3
                                    <?php elseif($book->web_status == 2): ?> 2/3
                                    <?php elseif($book->web_status == 1): ?> 1/3
                                    <?php else: ?> --
                                    <?php endif; ?>
								</span><br/>
                                <span>
                                    支払：<?php if($book->paid_payment > 0 && $book->unpaid_payment == 0): ?> 済 <?php endif; ?>
                                     <?php if($book->paid_payment > 0 && $book->unpaid_payment > 0): ?>  一部 決済（追加：未払い）<?php endif; ?>
                                     <?php if($book->paid_payment == 0 && $book->unpaid_payment > 0): ?>  未払い <?php endif; ?>
                                </span><br/>
                                <?php if($book->cancel_status > 0 ): ?>
                                <span>
                                    キャンセル料金：
                                    <?php if($book->cancel_status  == '10'): ?> 未請求 <?php endif; ?>
                                    <?php if($book->cancel_status  == '11'): ?> 請求中 <?php endif; ?>
                                    <?php if($book->cancel_status  == '1'): ?> 現金支払済 <?php endif; ?>
                                    <?php if($book->cancel_status  == '2'): ?> カード支払済 <?php endif; ?>
                                    <?php if($book->cancel_status  == '5'): ?> 銀行振込済 <?php endif; ?>
                                    <?php echo e($book->cancel_fee); ?>円
                                </span>
                               <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td  class="left_back">
                                <label class="control-label" for="last_name" >
                                    会員
                                </label>
                            </td>
                            <td >
                                <?php echo e($book->last_name); ?> <?php echo e($book->first_name); ?> 様<br/>
                                (<?php echo e($book->fur_last_name); ?> <?php echo e($book->fur_first_name); ?>)<br/>
                                <?php if($book->portal_flag == '0'): ?>
                                    会員ID:(<?php echo e($book->client_id); ?>)
                                    <a href="<?php echo e(URL::to('/')); ?>/members/<?php echo e($book->client_id); ?>">
                                        >> 会員詳細
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="left_back">
                                <label class="control-label" for="first_name" >連絡先</label>
                            </td>
                            <td >
                                <?php echo e($book->phone); ?><br/>
                                <?php if($book->portal_flag == '0'): ?>
                                <a href="<?php echo e(URL::to('/')); ?>/members/<?php echo e($book->client_id); ?>">
                                    <?php echo e($book->email); ?>

                                </a><br/>
                                <?php else: ?>
                                    <?php echo e($book->email); ?> <br/>
                                <?php endif; ?>
                                緊急：<?php echo e($book->emergency_phone); ?>

                            </td>
                        </tr>
                        <?php if($book->portal_flag == '0'): ?>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="address1" >住所</label>
                            </td>
                            <td colspan="3">
                               <?php if(!empty($user->profile)): ?>
							    <?php echo e(substr_replace($user->profile->postal_code,"-",3,0)); ?> <?php echo e($user->profile->address1); ?>

                               <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="address1" >顧客メッセージ</label>
                            </td>
                            <td colspan="3">
                                <?php echo e($book->client_message); ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="address1" >担当者メモ</label>
                            </td>
                            <td colspan="3">
                                <?php echo e($book->admin_memo); ?>

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
                            <?php echo e($book->pickup_name); ?><!--/ <?php echo e($book->drop_name); ?>-->
                            </td>
                            <td class="left_back">
                                <label class="control-label">車両</label>
                            </td>
                            <td style="padding:8px">
                                <div class="cold-md-12" style="padding: 3px 0 4px 0; border-bottom: 1px solid #eee">
									お客様のご希望：
                                    <?php if($book->request_smoke == '2'): ?>
                                        どちらでも良い
                                    <?php elseif($book->request_smoke == '0'): ?>
                                        禁煙
                                    <?php elseif($book->request_smoke == '1'): ?>
                                        喫煙
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span style="float: left">
                                        <?php echo e($book->class_name); ?> / <?php echo e($book->shortname); ?> <br/>
                                        <?php echo e($book->car_number1); ?><?php echo e($book->car_number2); ?> <?php echo e($book->car_number3); ?> <?php echo e($book->car_number4); ?><br/>
                                    </span>
                                    <span style="float:right; border: 1px solid red;margin: 7px 5px;border-radius: 10px;padding:3px 10px 3px 10px;">
                                        <?php if($book->request_smoke == '0'): ?>
                                            禁煙
                                        <?php elseif($book->request_smoke == '1'): ?>
                                            喫煙
                                        <?php endif; ?>
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
                                出発：<?php echo e(date('Y/m/d H:i', strtotime($book->departing))); ?><br/>
                                返却：<?php if($book->extend_set_day > 0): ?>
                                    <?php echo e(date('Y/m/d', strtotime($book->returning_updated))); ?> <?php echo e(date('H:i', strtotime($book->returning))); ?><br/>
                                     <?php else: ?>
                                    <?php echo e(date('Y/m/d H:i', strtotime($book->returning))); ?><br/>
                                     <?php endif; ?>
                                <?php echo e($book->night); ?><?php echo e($book->day); ?><br/>
                            </td>
                            <td class="left_back">
                                <label class="control-label" for="first_name" >補償</label>
                            </td>
                            <td >

                                <?php if(intval($book->insurance1) > 0 && intval($book->insurance2) == 0): ?> 免責 <?php endif; ?>
                                <?php if(intval($book->insurance1) > 0 && intval($book->insurance2) > 0): ?> ワイド免責 <?php endif; ?>
								<!--<br/>
								免責補償： <?php echo e($book->insurance1); ?>円 <br/>
								ワイド免責： <?php echo e($book->insurance2); ?>円-->
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="address1" >選択オプション</label>
                            </td>
                            <td >
                                <?php if($book->shop_number == '1'): ?> <!--If shop is Fuku-->
                                    <div>無料オプション
                                        <?php if($book->portal_flag == '0'): ?>
                                            <?php if(count($book->free_options)): ?> (<?php echo count($book->free_options); ?> 個) <?php else: ?> <?php endif; ?>：
                                            <?php $__currentLoopData = $book->free_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span style="padding-right:10px;">
                                                    <?php if(($option->google_column_number == 101 || $option->google_column_number == 102) && strtotime($book->created_at) <= strtotime('2018-06-18 12:00:00')): ?>
                                                        無料空港送迎
                                                    <?php else: ?>
                                                    <?php echo e($option->option_name); ?>

                                                    <?php endif; ?>
                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                               <?php if($book->free_options_category == '0'): ?> 不要 <?php endif; ?>
                                               <?php if($book->free_options_category == '1'): ?> 国内空港送迎 <?php endif; ?>
                                               <?php if($book->free_options_category == '2'): ?> 国際空港送迎 <?php endif; ?>
                                               <?php if($book->free_options_category == '3'): ?> コインパーキング <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div>有料オプション <?php if(count($book->selected_options) > 0): ?> <?php else: ?> <?php endif; ?>：
                                    <?php $__currentLoopData = $book->selected_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span style="padding-right:10px;"> <?php echo e($op->option_name); ?>(<?php echo e($op->option_number); ?>個)</span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="phone" >返却情報</label>
                            </td>
                            <td >
                                <?php if($book->portal_flag == '1'): ?>
                                    <?php echo e($book->returning_point); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back" >
                                <label class="control-label" for="phone" >乗車</label>
                            </td>
                            <td >
                                <!--運転：<?php echo e($book->driver_name); ?> 様<br/>-->
                                乗車人数：<?php echo e($book->passengers); ?>名
                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="email" >フライト情報</label>
                            </td>
                            <td >
                                <?php if($book->flight_name != '' && $book->flight_number != ''): ?>
                                <?php echo e($book->flight_name); ?> <?php echo e($book->flight_number); ?>

                                <?php else: ?>
                                <?php echo e($book->flight_inform); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back">
                                <label class="control-label" for="postal_code" >クイック乗り出し</label>
                            </td>
                            <td>
                                <div>
								<span>
									 <?php if($book->web_status == 3): ?> <i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 住所/Web免許<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 同意<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> Web決済 ➡
											<?php if($book->bag_choosed == '1'): ?> フリスク
											<?php elseif($book->bag_choosed == '2'): ?> ぷっちょ
											<?php elseif($book->bag_choosed == '3'): ?> 酔い止め
											<?php endif; ?>
                                    <?php elseif($book->web_status == 2): ?>  <i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 住所/Web免許<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 同意<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#cfcfcf;"></i> Web決済
                                    <?php elseif($book->web_status == 1): ?>  <i class="fa fa-check-circle-o" aria-hidden="true" style="color:#00cc11;"></i> 住所/Web免許<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#cfcfcf;"></i> 同意<br/><i class="fa fa-check-circle-o" aria-hidden="true" style="color:#cfcfcf;"></i> Web決済
                                    <?php else: ?> --
                                    <?php endif; ?>
								</span><br/>
                                </div>
                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="prefecture" >運転免許証</label>
                            </td>
                            <td>
                                <?php $__currentLoopData = $book->driver_license_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license_img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-lg-12">
                                        <div class="imgInput col-md-6 col-sm-6 col-xs-12">
                                            <div class="sec-text"><p><span>免許証/表面</span></p></div>
                                            <span class="zoom license_surface">
											<img src="<?php echo e($license_img->representative_license_surface); ?>" alt="" class="imgView img-responsive">
										</span>
                                        </div>
                                        <div class="imgInput col-md-6 col-sm-6 col-xs-12">
                                            <div class="sec-text"><p><span>免許証/裏面</span></p></div>
                                            <span class="zoom license_back">
											<img src="<?php echo e($license_img->representative_license_back); ?>" alt="" class="imgView img-responsive">
										</span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
								<span style="font-size:25px;font-weight:700;">　<?php echo e($book->paid_payment); ?>円</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="left_back">
                                <?php if($book->portal_flag == '0'): ?>
                                    <label class="control-label">公式HP予約</label>
                                <?php else: ?>
                                    <label class="control-label">外部予約</label>
                                <?php endif; ?>
                            </td>
                            <td colspan="3">
								<div class="row">
									<div class="col-md-12" style="margin:10px 0;">
                                        <?php
                                        $plus_minus = '';
                                        if($book->alladjustment >= 0) $plus_minus = '+';
                                        else $plus_minus = '-';
                                        ?>
										合計金額：<?php echo e($book->paid_payment+$book->unpaid_payment); ?> 円 ( = 基本<?php echo e($book->basic_price); ?>円 + オプ<?php echo e($book->option_price_sum); ?>円 + 免責<?php echo e($book->insurance1_sum); ?>円 + ワ<?php echo e($book->insurance2_sum); ?>円 <?php echo e($plus_minus); ?> 調整<?php echo e(abs($book->alladjustment)); ?>円 + 延泊<?php echo e($book->allextendnight); ?>円 )
                                            ポ<?php echo e($book->given_points); ?>円
									</div>
								</div>
								
								
									
										
									
									
										
									
									
										
									
								
                            </td>
                        </tr>

                        <tr>
                            <td class="left_back">
                                <label class="control-label">延泊</label>
                            </td>
                            <td colspan="3">
								<div class="row">
									<div class="col-md-12" style="margin:10px 0;">
										合計金額：<?php echo e($book->allextendnight); ?>円 ( = 延泊基本<?php echo e($book->allextendnight_basic); ?>円（<?php echo e($book->allextendnight_extend_day); ?>日延泊） +延泊オプション<?php echo e($book->allextendnight_optionprice); ?>円 + 延泊補償<?php echo e($book->allextendnight_insurance); ?>円 )
									</div>
								</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_back" >
                                <label class="control-label" for="phone" >調整</label>
                            </td>
                            <td >
								<?php echo e($book->alladjustment); ?>円
                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="email" >ポイント</label>
                            </td>
                            <td >
								<?php echo e($book->given_points); ?>円
                            </td>
                        </tr>


                        <tr>
                            <td class="left_back" >
                                <label class="control-label" for="card_number" >カード末尾4桁</label>
                            </td>
                            <td >
								<?php echo $book->card_last4; ?>

                            </td>
                            <td class="left_back" >
                                <label class="control-label" for="card_brand" >カードブランド</label>
                            </td>
                            <td >
								<?php echo $book->card_brand; ?>

                            </td>
                        </tr>


                        <!--
						<tr>
                            <td class="left_back">
                                <label class="control-label">決済情報</label>
                            </td>
                            <td colspan="3">
								支払ID：<?php echo e(implode(PHP_EOL, str_split($book->pay_id, 27))); ?><br/>
								取引ID：<?php echo e(implode(PHP_EOL, str_split($book->trans_id, 27))); ?>

                            </td>
                        </tr>
						-->
                        <tr>
                            <td class="left_back">
                                <label class="control-label">支払メモ</label>
                            </td>
                            <td colspan="3">
								<?php echo e($book->paying_memo); ?>

                            </td>
                        </tr>
                    </table>
                    <!--end 3rd part-->
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="<?php echo e(URL::to('/booking/edit/' . $book->id )); ?>" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
                            </label>
                            <label>
                                <?php echo Form::open(array('url' => URL::to('/').'/booking/delete/' . $book->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')); ?>

                                <?php echo Form::hidden('_method', 'DELETE'); ?>

                                <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => '予約を削除',
                                        'data-message' => 'この予約を本当に削除しますか？この操作を取り消すことはできません。')); ?>

                                <?php echo Form::close(); ?>

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
                        <?php $__currentLoopData = $book->driver_license_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lic_img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($lic_img->representative_license_surface)): ?>
                                <div>
                                    <img src="<?php echo e(URL::to('/')); ?><?php echo e($lic_img->representative_license_surface); ?>" class="img-responsive center-block" >
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($lic_img->representative_license_back)): ?>
                                <div>
                                    <img src="<?php echo e(URL::to('/')); ?><?php echo e($lic_img->representative_license_back); ?>" class="img-responsive center-block" >
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="col-md-12">
                        <?php $iter = 0; ?>
                        <?php $__currentLoopData = $book->driver_license_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lic_img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($lic_img->representative_license_surface)): ?>
                                <div class="lic-thumb active">
                                    <img class="cardetail-thumbnail img-responsive center-block" src="<?php echo e(URL::to('/')); ?><?php echo e($lic_img->representative_license_surface); ?>" width="40" onclick="javascript:$('.model_list').slick('slickGoTo',<?php echo $iter*2; ?>)" style="cursor:pointer;">
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($lic_img->representative_license_back)): ?>
                                <div class="lic-thumb">
                                    <img class="cardetail-thumbnail img-responsive center-block" src="<?php echo e(URL::to('/')); ?><?php echo e($lic_img->representative_license_back); ?>" width="40" onclick="javascript:$('.model_list').slick('slickGoTo',<?php echo $iter*2 + 1; ?>)" style="cursor:pointer;">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="modal-footer hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <script src="<?php echo e(URL::to('/')); ?>/js/slick/slick.js" type="text/javascript" charset="utf-8"></script>
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
    <?php echo $__env->make('scripts.admin.member', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script src="<?php echo e(URL::to('/')); ?>/js/jquery.zoom.js"></script>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>