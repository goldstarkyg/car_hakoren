<?php $__env->startSection('template_title'); ?>
    予約の編集
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('template_linked_css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/plugins/cropper/cropper.min.css">

    <style type="text/css" media="screen">
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
        }
        .title{
            margin-left: 0px;;
        }
        .modal_div div div {
            margin-top:15px;
        }
        .circle {
            border-radius: 50%;
            padding: 0 4px 0 3px;
            border: 1px solid #131313;
            color: #171717;
            text-align: center;
            font: 12px Arial, sans-serif;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>予約の編集：<?php echo e($book->booking_id); ?>/<?php echo e($book->fur_last_name); ?>様
                <a href="<?php echo e(URL::to('/')); ?>/booking/detail/<?php echo e($book->id); ?>" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                    <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                    詳細を見る
                </a>
                <a href="<?php echo e(URL::to('/')); ?>/booking/all" class="btn btn-info btn-xs pull-right">
                    <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                    一覧へ戻る
                </a>
                </h2>
            </div>
        </div>
        <!--eidt page start-->
        <?php $title_count = 1 ;?>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <form action="<?php echo e(URL::to('/')); ?>/booking/update" method="post" class="form-horizontal">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="book_id" value="<?php echo e($book->id); ?>" required>
                    <input type="hidden" name="admin_id" value="<?php echo e($book->admin_id); ?>" required>
                    <input type="hidden" name="user_id" value="<?php echo e($book->client_id); ?>" required>
                    <input type="hidden" name="depart_task" value="<?php echo e($book->depart_task); ?>" required>
                    <input type="hidden" name="return_task" value="<?php echo e($book->return_task); ?>" required>
                    <div class="panel-body">
                        <div style="border-bottom: 1px solid #000">
                            <label>
                                <h3 class="title">
                                    <?php if(empty($book->last_name)&& empty($book->first_name)): ?>
                                        <?php echo e($book->fur_last_name); ?> <?php echo e($book->fur_first_name); ?>

                                    <?php else: ?>
                                        <?php echo e($book->last_name); ?> <?php echo e($book->first_name); ?>

                                    <?php endif; ?> さんのご予約状況</h3>
                            </label>
                            <label>
                                <?php if($book->status == 9): ?>
                                    <h3>キャンセル</h3>
                                <?php else: ?>
                                    <?php if($book->depart_task == '0'): ?>
                                        <?php if(time() < strtotime($book->departing)): ?>
                                            <h3>成約 - 配車前</h3>
                                        <?php else: ?>
                                            <h3>成約</h3>
                                        <?php endif; ?>
                                    <?php elseif($book->depart_task == '1' && $book->return_task == '0'): ?>
                                        <h3>貸出中</h3>
                                    <?php elseif($book->return_task == '1'): ?>
                                        <h3>終了 </h3>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </label>
                            <label class="pull-right">
                                <?php if($book->portal_flag == '0'): ?>
                                    <?php if($book->portal_id == 10000): ?>
                                        <?php if($book->language == 'ja'): ?>
                                            <span>自社HP</span>
                                        <?php else: ?>
                                            <span>自社HPEN</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span>自社HPAD</span>
                                    <?php endif; ?>
                                <?php elseif( $book->portal_flag == '1'): ?>
                                    <span><?php echo e($book->portal_name); ?></span>
                                    <span><?php echo e($book->booking); ?></span>
                                <?php endif; ?>
                                    <input type="hidden" name="portal_flag" value="<?php echo e($book->portal_flag); ?>" />
                                    <input type="hidden" name="booking" value="">
                                    <input type="hidden" name="portal_id" value="<?php echo e($book->portal_id); ?>">
                            </label>
                        </div>

                        <div>
                            <!--start first part-->
                            <table class="table table-bordered users-table">
                                <tr>
                                    <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                       <span class="circle"><?php echo e($title_count); ?></span>予約/会員
                                    </td>
                                    <?php if(!empty($book->bad_flag)&& $book->bad_flag == '1'): ?>
                                        <td colspan="3" style="text-align:center;font-size:17px;font-weight:500;color:#940a0a;">
                                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 初期取込データです！
                                        </td>
                                    <?php endif; ?>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="status" >ステータス</label>
                                    </td>
                                    <td>
                                        <label style="padding-right: 20px;"><?php echo e($book->status_name); ?> </label>
                                        <label>
                                            <select name="change_status" id= "change_status" class="form-control">
                                                    <option value="0">選択してください</option>
                                                    <option value="1" <?php if($book->status == '1'): ?> selected <?php endif; ?> >予約</option> <!--reservaion -->
                                                    <option value="6" <?php if($book->status == '6'): ?> selected <?php endif; ?> >貸出中</option> <!--using -->
                                                    <option value="8" <?php if($book->status == '8'): ?> selected <?php endif; ?> >終了</option> <!--ended-->
                                                    <option value="9" <?php if($book->status == '9'): ?> selected <?php endif; ?> >キャンセル</option> <!--cancel-->
                                                    <!--<option value="7" <?php if($book->status == '7'): ?> selected <?php endif; ?>>遅れ</option>--><!--delay-->
                                                    <!--<option value="10" <?php if($book->status == '10'): ?> selected <?php endif; ?> >Ignore</option>--><!--ignore-->
                                            </select>
                                            <input type="hidden" name="status" id="status" value="<?php echo e($book->status); ?>">
                                        </label>
                                    </td>
                                    <td class="left_back">
                                        <label class="control-label">予約Id</label>
                                    </td>
                                    <td>
                                        <label class="control-label"><?php echo e($book->booking_id); ?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="left_back">
                                        <label class="control-label" for="last_name" >姓</label>
                                    </td>
                                    <td >
                                       <label>
                                           <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo e($book->last_name); ?>">
                                       </label>
                                    </td>
                                    <td class="left_back">
                                        <label class="control-label" for="first_name" >名</label>
                                    </td>
                                    <td >
                                        <label>
                                            <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo e($book->first_name); ?>">
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="left_back">
                                        <label class="control-label" for="fur_last_name" >フリガナ（セイ）</label>
                                    </td>
                                    <td >
                                        <label>
                                            <input type="text" name="fur_last_name" id="fur_last_name" class="form-control" value="<?php echo e($book->fur_last_name); ?>">
                                        </label>
                                    </td>
                                    <td class="left_back">
                                        <label class="control-label" for="fur_first_name" >フリガナ（メイ）</label>
                                    </td>
                                    <td >
                                        <label>
                                            <input type="text" name="fur_first_name" id="fur_first_name" class="form-control" value="<?php echo e($book->fur_first_name); ?>">
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back" >
                                        <label class="control-label" for="phone" >電話</label>
                                    </td>
                                    <td >
                                        <label>
                                            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo e($book->phone); ?>">
                                        </label>
                                    </td>
                                    <td class="left_back" >
                                        <label class="control-label" for="email" >メール</label>
                                    </td>
                                    <td >
                                        <label>
                                            <input type="text" name="email" id="email" class="form-control" value="<?php echo e($book->email); ?>">
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="postal_code" >郵便番号</label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" name="postal_code" id="postal_code" class="form-control" value="<?php echo e($book->postal_code); ?>">
                                        </label>
                                    </td>
                                    <td class="left_back" >
                                        <label class="control-label" for="prefecture" >都道府県</label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" name="prefecture" id="prefecture" class="form-control" value="<?php echo e($book->prefecture); ?>">
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="address1" >住所1</label>
                                    </td>
                                    <td colspan="3">
                                            <input type="text" name="address1" id="address1" class="form-control" value="<?php echo e($book->address1); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="address2" >住所2</label>
                                    </td>
                                    <td colspan="3">
                                        <input type="text" name="address2" id="address2" class="form-control" value="<?php echo e($book->address2); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label">緊急連絡先</label>
                                    </td>
                                    <td colspan="3">
                                        <input name="emergency_phone" class="form-control" value="<?php echo e($book->emergency_phone); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" >顧客コメント</label>
                                    </td>
                                    <td colspan="3">
                                        <input type="text" name="client_message" id="client_message" readonly value="<?php echo e($book->client_message); ?>" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" >スタッフメモ</label>
                                    </td>
                                    <td colspan="3">
                                        <input type="text" name="admin_memo" id="admin_memo" value="<?php echo e($book->admin_memo); ?>" class="form-control">
                                    </td>
                                </tr>
                            </table>
                            <!--end first part-->
                        </div>
                        <!--second section-->
                        <div>
                            <!--start second part-->
                            <table class="table table-bordered">
                                <tr>
                                    <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                        <span class="circle"><?php echo e($title_count+1); ?></span>レンタル内容
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back" >
                                        <label class="control-label" for="class" >車両番号</label>
                                    </td>
                                    <td colspan="3" >
                                        <select id="inventory_id" name="inventory_id" class="chosen-select form-control" >
                                            <option value=""> 選択してください </option>
                                            <?php $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($car->car_id); ?>_<?php echo e($car->class_id); ?>_<?php echo e($car->model_id); ?>_<?php echo e($car->type_id); ?>" <?php if($book->inventory_id == $car->car_id): ?> selected <?php endif; ?>>
                                                    Class:<?php echo e($car->class_name); ?> &nbsp;
                                                    Model:<?php echo e($car->model_name); ?> &nbsp;
                                                    Type:<?php echo e($car->type_name); ?> &nbsp;
                                                    Number: <?php echo e($car->numberplate1); ?> <?php echo e($car->numberplate2); ?> <?php echo e($car->numberplate3); ?> <?php echo e($car->numberplate4); ?> <?php echo e($car->smoke); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td  class="left_back" >
                                        <label class="control-label">ご利用店舗</label>
                                    </td>
                                    <td colspan="3" >
                                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($shop->id == $book->pickup_id): ?>
                                                <input type="hidden" name="pickup_id" value="<?php echo e($shop->id); ?>" >
                                                <input type="text" class="form-control"  value="<?php echo e($shop->name); ?>" readonly >
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <input type="hidden" name="dropoff_id" value="<?php echo $book->dropoff_id; ?>" >
                                </tr>

                                <tr>
                                    <td  class="left_back" >
                                        <label class="control-label">出発</label>
                                    </td>
                                    <td >
                                        <div class="input-group date col-lg-7 pull-left">
                                            <input type="text" name="depart_date" id="depart-date" class="form-control" readonly required>
                                        </div>
                                        <div class="col-lg-5" style="padding-right: 0">
                                            <select class="chosen-select form-control" name="depart_time" id="depart-time" required>
                                                <?php $__currentLoopData = $hour; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($h); ?>" <?php if($h==$book->depart_time): ?> selected <?php endif; ?> ><?php echo e($h); ?> </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="left_back" >
                                        <label class="control-label">返却</label>
                                    </td>
                                    <td >
                                        <div class="input-group date col-lg-7 pull-left" >
                                            <?php if($book->extend_set_day > 0 ): ?>
                                                <input type="hidden" name="return_date" id="return-date" class="form-control" readonly required>
                                                <input type="text"   class="form-control" readonly value="<?php echo e(date('Y/m/d', strtotime($book->returning_updated))); ?>" required>
                                            <?php else: ?>
                                                <input type="text" name="return_date" id="return-date" class="form-control" readonly required>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-lg-5" style="padding-right: 0">
                                            <select class="chosen-select form-control" name="return_time" id="return-time" required>
                                                <?php $__currentLoopData = $hour; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($h); ?>" <?php if($h==$book->return_time): ?> selected <?php endif; ?>><?php echo e($h); ?> </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" >利用期間</label>
                                    </td>
                                    <td colspan="3">

                                        <?php if($book->extend_set_day > 0 ): ?>
                                            <input type="hidden" name="total_rent_days" id="total_rent_days" class="form-control" value="<?php echo e(explode('_',$book->rent_days)[0]); ?>泊<?php echo e(explode('_',$book->rent_days)[1]); ?>日" readonly>
                                            <input name="rentdays_val" id="rentdays_val" type="hidden" value="<?php echo e($book->rent_days); ?>">
                                            <input type="text" class="form-control" value="<?php echo e(explode('_',$book->extend_days)[0]); ?>泊<?php echo e(explode('_',$book->extend_days)[1]); ?>日" readonly>
                                        <?php else: ?>
                                            <input type="text" name="total_rent_days" id="total_rent_days" class="form-control" value="<?php echo e(explode('_',$book->rent_days)[0]); ?>泊<?php echo e(explode('_',$book->rent_days)[1]); ?>日" readonly>
                                            <input name="rentdays_val" id="rentdays_val" type="hidden" value="<?php echo e($book->rent_days); ?>">
                                        <?php endif; ?>
                                    </td>
                                        <input type="hidden" name="basic_price" id="basic_price" class="form-control" readonly  value="<?php echo e($book->basic_price); ?>">
                                        <input type="hidden" name="virtual_payment" id="virtual_payment" class="form-control" readonly  value="<?php echo e($book->virtual_payment); ?>">
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" >運転手名</label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="driver_name" id="driver_name" class="form-control" value="<?php echo e($book->driver_name); ?>">
                                        </label>
                                        <label>
                                            <?php if(!empty($book->license_surface)): ?>
                                                <label onclick="viewlicense(<?php echo e(json_encode($book)); ?>)">免</label>
                                            <?php endif; ?>
                                        </label>
                                    </td>
                                    <td class="left_back">
                                        <label class="control-label" for="insurance" >乗車人数</label>
                                    </td>
                                    <td>
                                        <input name="passengers" class="form-control" value="<?php echo e($book->passengers); ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" >オプション情報</label>
                                    </td>
                                    <td colspan="3">
                                        <?php $__currentLoopData = $book->selected_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ops): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span style="padding-right: 10px;">
                                                  <?php echo e($ops->option_name); ?>(<?php echo e($ops->option_number); ?>)
                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                <tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label">航空会社/便名</label>
                                    </td>
                                    <td>
                                        <div class="col-lg-6 pull-left">
                                            <select name="flight_line" class=" chosen-select form-control">
                                                <option value="0">選択してください</option>
                                                <?php $__currentLoopData = $flight_lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight_line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($flight_line->id); ?>" <?php if($book->flight_line==$flight_line->id): ?> selected <?php endif; ?> >
                                                        <?php echo e($flight_line->title); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <input name="flight_number" class="form-control" value="<?php echo e($book->flight_number); ?>">
                                        </div>
                                    </td>
                                    <td class="left_back">
                                        <label class="control-label"  >予約方法</label>
                                    </td>
                                    <td>
                                        <select name="reservations" id="reservations" class="form-control" required>
                                            <option value="0">選択してください</option>
                                            <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($reservation->id); ?>" <?php if($book->reservation_id == $reservation->id): ?> selected <?php endif; ?>><?php echo e($reservation->title); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr> 
                                    <td class="left_back">
                                        <label class="control-label">運転免許証</label>
                                    </td>
                                    <td colspan="3">
                                        <label class="btn btn-success btn-sm"  data-toggle="modal" data-target="#lic-upload" style="float: left;top: 10px;margin-right: 10px;">
                                            追加する
                                        </label>
                                        <div id="lic_img_wrapper">
                                            <?php $__currentLoopData = $book->licences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="img_block">
                                                    <img src="<?php echo e($lic->url); ?>" class="img_view" lid="<?php echo e($lic->id); ?>" side="<?php echo e($lic->side); ?>" onclick="showEditModal()" style="max-height: 100%; cursor: pointer">
                                                    <i class="fa fa-ban remove_icon" onclick="deletePhoto(<?php echo e(json_encode($lic)); ?>)" ></i>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php if($book->shop_number == '1'): ?> <!--If shop is Fuku-->
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label">送迎オプション</label>
                                    </td>
                                    <td colspan="3" >
                                        <?php if($book->portal_flag == '1'): ?>
                                            <select id="free_options_category" name="free_options_category" class="form-control" >
                                                <option value="0" >不要</option>
                                                <option value="1" <?php if($book->free_options_category == '1' ): ?> selected <?php endif; ?>  >国内空港送迎 </option>
                                                <option value="2" <?php if($book->free_options_category == '2' ): ?> selected <?php endif; ?> >国際空港送迎</option>
                                                <option value="3" <?php if($book->free_options_category == '3' ): ?> selected <?php endif; ?> >コインパーキング</option>
                                            </select>
                                        <?php else: ?>
                                            <select id="free_options_category" name="free_options_category" class="form-control" >
                                                <option value="0" >送迎無し</option>
                                                <option value="1" <?php if($book->free_options_category == '1' ): ?> selected <?php endif; ?>  >国内空港送迎 </option>
                                                <option value="2" <?php if($book->free_options_category == '2' ): ?> selected <?php endif; ?> >国際空港送迎</option>
                                            </select>
                                            
                                                
                                                
                                                
                                            
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <input type="hidden" name="wait_status" id="wait_status" value="<?php echo e($book->wait_status); ?>" >
                                <input type="hidden" name="free_options" id="free_options" value="<?php echo e($book->free_options); ?>" >
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label">走行距離</label>
                                    </td>
                                    <td colspan="3" >
                                       <label>
                                           <input type="text" name="miles" id="miles" value="<?php echo e($book->miles); ?>" class="form-control" <?php if($book->return_task == '1'): ?> readonly <?php endif; ?> style="width:200px;">
                                           <input type="hidden" name="before_miles" id="before_miles" value="<?php echo e($book->before_miles); ?>" > 
                                       </label>
                                       <label>Km </label>
                                       <label  style="font-weight: 100; font-size: 12px;">( 貸出前 : <?php echo e($book->before_miles); ?>Km)</label>
                                    </td>
                                </tr>
                            </table>
                            </div>
                        <!--third section for payment module-->
                        <div>
                            <!--start 3rd part-->
                            <table class="table table-bordered users-table">
                                <tr>
                                    <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                        <span class="circle"><?php echo e($title_count+2); ?></span>料金
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label">現在の総支払額</label>
                                    </td>
                                    <td colspan="3">
                                        <span style="font-size:23px;font-weight:600;">　<?php echo e($book->paid_payment); ?>円</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label">
                                            <?php if($book->portal_flag == '0'): ?>
                                                公式HP予約
                                            <?php else: ?>
                                                外部予約
                                            <?php endif; ?>
                                        </label>
                                    </td>
                                    <td colspan="3">
                                        <div class="row">
                                            <div class="col-md-12" style="margin:10px 0;">
                                                <?php
                                                    $plus_minus = '';
                                                    if($book->alladjustment >= 0) $plus_minus = '+';
                                                    else $plus_minus = '-';
                                                ?>
                                                合計金額：<?php echo e($book->paid_payment+$book->unpaid_payment); ?>円 ( = 基本<?php echo e($book->basic_price); ?>円 + オプ<?php echo e($book->option_price_sum); ?>円 + 免責<?php echo e($book->insurance1_sum); ?>円 + ワ<?php echo e($book->insurance2_sum); ?>円  <?php echo e($plus_minus); ?> 調整<?php echo e(abs($book->alladjustment)); ?>円 + 延泊<?php echo e($book->allextendnight); ?>円 )
                                                        ポ<?php echo e($book->given_points); ?>円
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back" >
                                        <label class="control-label" for="card_number" >カード下4桁</label>
                                    </td>
                                    <td >
                                        <label><?php echo $book->card_last4; ?></label>
                                    </td>
                                    <td class="left_back" >
                                        <label class="control-label" for="card_brand" >カードブランド</label>
                                    </td>
                                    <td >
										<label><?php echo $book->card_brand; ?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label">
											支払いメモ
                                        </label>
                                    </td>
                                    <td colspan="3">
                                        <div class="row">
                                            <div class="col-md-12" style="margin:10px 0;">
                                                <input name="paying_memo" class="form-control" value="<?php echo $book->paying_memo; ?>">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <!--end 3rd part-->
                        </div>
                        <!--four section-->
                        <div>
                                <table class="table table-bordered">
                                <tr>
                                    <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                        <span class="circle"><?php echo e($title_count+3); ?></span>料金 - 初回注文
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <!--insurance start-->
                                        <table class="table" >
                                            <tr style="border-bottom: 1px solid #eee">
                                                <td colspan="3">基本</td>
                                                <td><span class="coin" id="basic_price"><?php echo e($book->basic_price); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td>項目</td>
                                                <td>単価</td>
                                                <td>個数</td>
                                                <td>料金</td>
                                            </tr>
                                            <input type="hidden" id="option_snow" name="option_snow" value="0" >
                                            <?php $ins_count = 0;?>
                                            <?php if($book->pay_status == '1'): ?>
                                                <?php $__currentLoopData = $book->insurances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($in->price != '0'): ?>
                                                        <tr>
                                                            <td>
                                                               <?php echo e($in->name); ?>

                                                                <input type="hidden" value="<?php echo e($in->search_condition); ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control coin " value="<?php echo e($in->basic_price); ?>" readonly >
                                                            <td>
                                                                <select class="form-control">
                                                                        <?php if($in->price != '0'): ?>
                                                                            <option value="1">Yes</option>
                                                                        <?php else: ?>
                                                                            <option value="0">No</option>
                                                                        <?php endif; ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control coin" value="<?php echo e($in->price); ?>" readonly >
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                     <?php $ins_count++; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <input type="hidden" value="<?php echo e($book->insurance_price); ?>" >
                                                <!--option start-->
                                                <?php $op_count = 0; ?>
                                                <?php $__currentLoopData = $book->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($op->option_price != '0'): ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo e($op->option_name); ?>

                                                                <input type="hidden" value="<?php echo e($op->option_id); ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control coin" value="<?php echo e($op->option_basic_price); ?>" readonly >
                                                            </td>
                                                            <td>
                                                                <select  class="form-control" >
                                                                        <?php if($op->max_number == '1'): ?>
                                                                            <?php if($op->index == '26'): ?>
                                                                                <option value="<?php echo e($book->set_day); ?>" >Yes</option>
                                                                            <?php else: ?>
                                                                                <?php if('1' == $op->option_number): ?>
                                                                                    <option value="1" >Yes</option>
                                                                                <?php endif; ?>
                                                                                <?php if('0' == $op->option_number): ?>
                                                                                    <option value="0">No</option>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            <option value="<?php echo e($op->option_number); ?>" ><?php echo e($op->option_number); ?></option>
                                                                        <?php endif; ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control coin" value="<?php echo e($op->option_price); ?>" readonly >
                                                            </td>
                                                        </tr>
                                                        <?php endif; ?>
                                                        <?php $op_count++; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <input type="hidden" value="<?php echo e($book->option_price); ?>" >
                                                <!--option end-->
                                                <!--extend pay status start-->
                                                <?php if($book->extend_payment > 0): ?>
                                                    <table class="table" >
                                                        <tr>
                                                            <td class="col-md-2"><label class="control-label" >延泊</label></td>
                                                            <td class="col-md-10">
                                                                <table class="table table-bordered" >
                                                                    <tr>
                                                                        <td style="width:100px;"> 延泊数</td>
                                                                        <td> 基本料金</td>
                                                                        <td> オプション料金</td>
                                                                        <td>  雪タ</td>
                                                                        <td> 延泊料金総計 </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <select class="form-control">
                                                                                <option value="<?php echo e($book->extend_day); ?>" selected ><?php echo e($book->extend_day); ?>泊</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control" value="<?php echo e($book->extend_basic_price); ?>" readonly >
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control" value="<?php echo e($book->extend_insurance1 + $book->extend_insurance2); ?>" readonly >
                                                                            <input type="hidden" class="form-control" value="<?php echo e($book->extend_insurance1); ?>" readonly >
                                                                            <input type="hidden" class="form-control" value="<?php echo e($book->extend_insurance2); ?>" readonly >
                                                                            <input type="hidden" class="form-control" value="<?php echo e($book->extend_return_date); ?>" readonly >
                                                                        </td>
                                                                        <td>
                                                                            <input type="hidden" class="form-control" value="<?php echo e($book->extend_options_price); ?>" readonly >
                                                                            <input type="text" class="form-control" value="<?php echo e($book->extend_option_price); ?>" readonly >
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control" value="<?php echo e($book->extend_payment); ?>" readonly >
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php endif; ?>
                                                <!--extend pay status end-->
                                                <!--etc card price start-->
                                                
                                                    
                                                        
                                                            
                                                            
                                                                
                                                            
                                                        
                                                    
                                                
                                                <!--etc  card price end-->
                                            <?php else: ?>
                                                <input type="hidden" name="original_update" value="1" >
                                                <?php $__currentLoopData = $book->insurances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo e($in->name); ?>

                                                            <input type="hidden" name="ins_search_condition[]" id="ins_search_<?php echo e($ins_count); ?>" value="<?php echo e($in->search_condition); ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control coin " id="ins_basic_price_<?php echo e($ins_count); ?>" name="ins_basic_prices[]" value="<?php echo e($in->basic_price); ?>" readonly >
                                                        <td>
                                                            <select name="ins_flags[]" id="ins_flag_<?php echo e($ins_count); ?>" onchange="changeprice('insurance','<?php echo e($ins_count); ?>','0')" class="form-control">
                                                                    <option value="1" <?php if($in->price != '0'): ?> selected <?php endif; ?>>Yes</option>
                                                                    <option value="0" <?php if($in->price == '0'): ?> selected <?php endif; ?>>No</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control coin" id="ins_price_<?php echo e($ins_count); ?>" name="ins_prices[]" value="<?php echo e($in->price); ?>" readonly >
                                                        </td>
                                                    </tr>
                                                    <?php $ins_count++; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <input type="hidden" id="all_insurance_price" value="<?php echo e($book->insurance_price); ?>" >
                                                <!--option start-->
                                                <?php $op_count = 0; ?>
                                                <?php $__currentLoopData = $book->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                    <tr>
                                                        <td>
                                                            <?php echo e($op->option_name); ?>

                                                            <input type="hidden" name="option_ids[]" value="<?php echo e($op->option_id); ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control coin" id="op_basic_<?php echo e($op_count); ?>" name="option_basic_prices[]" value="<?php echo e($op->option_basic_price); ?>" readonly >
                                                        </td>
                                                        <td>
                                                            <select name="option_numbers[]" id="op_number_<?php echo e($op_count); ?>" class="form-control" onchange="changeprice('option','<?php echo e($op_count); ?>','<?php echo e($op->index); ?>')">
                                                                    <?php if($op->max_number == '1'): ?>
                                                                        <?php if($op->index == '26'): ?> <!--snow tire option-->
                                                                            <option value="<?php echo e($book->set_day); ?>" <?php if($op->option_number == $book->set_day): ?> selected <?php endif; ?>>Yes</option>
                                                                            <option value="0" <?php if('0' == $op->option_number): ?> selected <?php endif; ?>>No</option>
                                                                        <?php else: ?>
                                                                            <option value="1" <?php if('1' == $op->option_number): ?> selected <?php endif; ?>>Yes</option>
                                                                            <option value="0" <?php if('0' == $op->option_number): ?> selected <?php endif; ?>>No</option>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        <?php
                                                                        for($i = 0 ; $i < $op->max_number+1 ; $i++) {
                                                                        ?>
                                                                        <option value="<?php echo e($i); ?>" <?php if($i == $op->option_number): ?> selected <?php endif; ?>><?php echo e($i); ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    <?php endif; ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control coin" id="op_price_<?php echo e($op_count); ?>" name="option_prices[]" value="<?php echo e($op->option_price); ?>" readonly >
                                                        </td>
                                                        <?php if($op->index == '26'): ?> <!--snow tire option to get price-->
                                                        <input type="hidden" class="form-control coin" id="option_id_snow" name="option_id_snow" value="<?php echo e($op->option_id); ?>" readonly >
                                                        <input type="hidden" class="form-control coin" id="option_price_snow" name="option_price_snow" value="<?php echo e($op->option_price); ?>" readonly >
                                                        <?php endif; ?>
                                                    </tr>
                                                    <?php $op_count++; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <input type="hidden" id="all_option_price" value="<?php echo e($book->option_price); ?>" >
                                                <!--option end-->
                                                <!--extend start-->
                                                    <table class="table" >
                                                        <tr>
                                                            <td class="col-md-2"><label class="control-label" >延泊</label></td>
                                                            <td class="col-md-10">
                                                                <table class="table table-bordered" >
                                                                    <tr>
                                                                        <td> 延泊数</td>
                                                                        <td> 基本料金</td>
                                                                        <td> オプション料金</td>
                                                                        <td>  雪タ</td>
                                                                        <td> 延泊料金総計 </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <select name="extend_day" id="extend_day" class="form-control" onchange="changeModalPrice()">
                                                                                <option  value="0">選択してください</option>
                                                                                <?php
                                                                                for($i = 1 ; $i < $book->extended_day+1 ; $i++) {
                                                                                ?>
                                                                                <option value="<?php echo e($i); ?>" <?php if($book->extend_day == $i): ?> selected <?php endif; ?> ><?php echo e($i); ?>泊</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" id="extend_basic_price" name="extend_basic_price" class="form-control" value="<?php echo e($book->extend_basic_price); ?>" readonly >
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" id='extend_insurance_price' name="extend_insurance_price" class="form-control" value="<?php echo e($book->extend_insurance1 + $book->extend_insurance2); ?>" readonly >
                                                                            <input type="hidden" id='extend_insurance1' name="extend_insurance1" class="form-control" value="<?php echo e($book->extend_insurance1); ?>" readonly >
                                                                            <input type="hidden" id='extend_insurance2' name="extend_insurance2" class="form-control" value="<?php echo e($book->extend_insurance2); ?>" readonly >
                                                                            <input type="hidden" id='extend_return_date' name="extend_return_date" class="form-control" value="<?php echo e($book->extend_return_date); ?>" readonly >
                                                                        </td>
                                                                        <td>
                                                                            <input type="hidden" id='extend_options_price' name="extend_options_price" class="form-control" value="<?php echo e($book->extend_options_price); ?>" readonly >
                                                                            <input type="text" id='extend_option_price' name="extend_option_price" class="form-control" value="<?php echo e($book->extend_option_price); ?>" readonly >
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" id='extend_payment' name="extend_payment" class="form-control" value="<?php echo e($book->extend_payment); ?>" readonly >
                                                                            <input type="hidden" id="extend_pay_method" name="extend_pay_method" value="<?php echo e($book->extend_pay_method); ?>" >
                                                                            <input type="hidden" id="extend_pay_status" name="extend_pay_status" value="<?php echo e($book->extend_pay_status); ?>" >
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <!--extend end-->
                                                <!--etc card price start-->
                                                
                                                    
                                                        
                                                            
                                                            
                                                                
                                                            
                                                        
                                                    
                                                 
                                                <!--etc  card price end-->
                                            <?php endif; ?>
                                        </table>
                                        <!--insurance end-->
                                        <!--option start-->
                                        <table class="table" >
                                            <tr>
                                                <td>
                                                    <label class="control-label" >調整価格</label>
                                                </td>
                                                <td colspan="3">
                                                    <input type="number" name="discount" id="discount" class="form-control" onkeyup="changeprice('discount',0,'0')" value="<?php echo e($book->discount); ?>" <?php if($book->pay_status == '1'): ?> readonly <?php endif; ?> >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td colspan="2"><label class="control-label" >明細の合計</label></td>
                                                <td >
                                                    <input id="total_pay" name="total_pay" class="form-control section_total" value="<?php echo e($book->basic_price + $book->option_price + $book->insurance_price + $book->extend_payment+$book->discount + $book->etc_card + $book->virtual_payment); ?>" readonly required>
                                                </td>
                                            </tr>
                                        </table>
                                        <!--option end-->
                                        <!--web pay type start-->
                                        <table class="table">
                                            <tr>
                                                <td>
                                                    <label class="control-label" >支払い方法</label>
                                                </td>
                                                <td >
                                                    <select name="pay_method" id="pay_method"  class="form-control">
                                                        <?php if($book->pay_status == '1'): ?>
                                                            <?php if($book->pay_method == '3' ): ?>
                                                                <option value="3">Web支払</option>
                                                            <?php elseif($book->pay_method == '4' ): ?>
                                                                <option value="4">Portal決済</option>
                                                            <?php elseif($book->pay_method == '1'): ?>
                                                              <option value="1">現金</option> <!--cash //1=cash, 2= credit, 3 =web, 4= portal-->
                                                            <?php elseif($book->pay_method == '2'): ?>
                                                              <option value="2" >カード</option> <!--credit card-->
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <option value="0">選択してください </option>
                                                            <option value="1" <?php if($book->pay_method == '1'): ?> selected <?php endif; ?>>現金</option> <!--cash-->
                                                            <option value="2" <?php if($book->pay_method == '2'): ?> selected <?php endif; ?> >カード</option> <!--credit card-->
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="control-label" >支払日</label>
                                                </td>
                                                <td >
                                                    <input type="text" name="paid_date" id="paid_date" class="form-control" value="<?php if(empty($book->paid_date)): ?> ----/--/-- <?php else: ?> <?php echo e(date('Y/m/d', strtotime($book->paid_date))); ?> <?php endif; ?>" readonly />
                                                </td>
                                            </tr>

                                        </table>
                                        <!--web pay type end-->
                                    </td>
                                </tr>
                        </table>
                    </div>
                     <?php $add_count = 1; ?>
                    <!--saved additional start-->
                    <?php $title_count1 = 3; ?>
                    <?php $__currentLoopData = $book->saved_additional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $title_count1++; ?>
                    <div>
                        <table class="table table-bordered">
                            <tr>
                                <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                    <span class="circle"><?php echo e($title_count+$title_count1); ?></span> 追加注文<?php echo e($add_count); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="table" >
                                        <!--insurance start-->
                                        <tr>
                                            <td>項目</td>
                                            <td>単価</td>
                                            <td>個数</td>
                                            <td>料金</td>
                                        </tr>
                                        <?php
                                            $ins_count = 0;
                                            $ins_total = 0;
                                            $ins_total = $ad->insurance1 + $ad->insurance2
                                        ?>
                                        <?php $__currentLoopData = $ad->insurance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($in->price != 0): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo e($in->name); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e(number_format($in->basic_price)); ?>

                                                    <td>
                                                        Yes
                                                    </td>
                                                    <td>
                                                        <?php echo e(number_format($in->price)); ?>

                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php
                                                $ins_count++;
                                            ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <!--insurance end-->
                                        <!--option start-->
                                            <?php
                                            $op_count = 0;
                                            $op_price = 0;
                                            ?>
                                            <?php $__currentLoopData = $ad->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($op->option_price != 0): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo e($op->option_name); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e(number_format($op->option_basic_price)); ?>

                                                    </td>
                                                    <td>
                                                        <?php if($op->max_number == '1'): ?>
                                                            <?php if('1' == $op->option_number): ?>
                                                                Yes
                                                            <?php endif; ?>
                                                            <?php if('0' == $op->option_number): ?>
                                                                No
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                           <?php echo e($op->option_number); ?>

                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo e(number_format($op->option_price)); ?>

                                                        <?php $op_price += $op->option_price; ?>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                                <?php $op_count++; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <!--option end-->
                                            <!--extend pay status start-->
                                            <?php if($ad->extend_payment != '0' ): ?>
                                                <tr>
                                                <td><label class="control-label" >延泊</label></td>
                                                <td colspan="3">
                                                    <table class="table table-bordered" >
                                                        <tr>
                                                            <td> 延泊数</td>
                                                            <td> 基本料金</td>
                                                            <td> オプション料金</td>
                                                            <td>  雪タ</td>
                                                            <td> 延泊料金総計 </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?php echo e($ad->extend_day); ?>泊
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="<?php echo e($ad->extend_basic_price); ?>"  readonly >
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="<?php echo e($ad->extend_insurance1 + $book->extend_insurance2); ?>"  readonly >
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="<?php echo e($ad->extend_option_price); ?>" readonly >
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="<?php echo e($ad->extend_payment); ?>" readonly >
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                           <!--extend pay status end-->
                                    </table>
                                    <!--etc card price start-->
                                    <?php if($book->depart_task == '1' && $ad->etc_card > 0): ?>
                                        <table class="table" >
                                            <tr>
                                                <td class="col-md-2"><label class="control-label" >ETC利用料金</label></td>
                                                <td class="col-md-10">
                                                    <input type="text" class="form-control" value="<?php echo e($ad->etc_card); ?>" readonly >
                                                </td>
                                            </tr>
                                        </table>
                                    <?php endif; ?>
                                    <!--etc  card price end-->

                                    <table class="table">
                                        <tr>
                                            <td colspan="3">調整/割引金額</td>
                                            <td >
                                                <?php echo e(number_format($ad->adjustment_price)); ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="2"><label class="control-label" >明細の合計</label></td>
                                            <td>
                                               <input type="text" class="form-control section_total" value="<?php echo e($ad->total_price); ?>" readonly />
                                            </td>
                                        </tr>
                                    </table>
                                    <!--web pay type start-->
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <label class="control-label" >支払い方法</label>
                                            </td>
                                            <td >
                                                <select class="form-control">
                                                        <?php if($ad->pay_method == '1'): ?>
                                                            <option value="1">現金</option> <!--cash-->
                                                        <?php endif; ?>
                                                        <?php if($ad->pay_method == '2'): ?>
                                                            <option value="2" >カード</option> <!--credit card-->
                                                        <?php endif; ?>
                                                </select>
                                            </td>
                                            
                                                
                                            
                                            
                                                
                                                        
                                                
                                            
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control-label" >支払日</label>
                                            </td>
                                            <td >
                                                <input type="hidden" name="additional_paid_date_id[]"  value="<?php echo e($ad->id); ?>" />
                                                <input type="text" class="form-control additional_paid_date" name="additional_paid_date[]"  value="<?php if(empty($ad->paid_date)): ?> ----/--/-- <?php else: ?> <?php echo e(date('Y/m/d', strtotime($ad->paid_date))); ?> <?php endif; ?>" readonly />
                                            </td>
                                        </tr>
                                    </table>
                                    <!--web pay type end-->
                                </td>
                            </tr>
                            <?php $add_count++; ?>
                        </table>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <!--saved additinal end-->
                    <?php $title_count1++; ?>
                    <?php if(!empty($book->cu_additional)): ?>
                        <div>
                            <table class="table table-bordered">
                                <tr>
                                    <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                        <span class="circle"><?php echo e($title_count+$title_count1); ?></span>  追加注文<?php echo e($add_count); ?>

                                    </td>
                                </tr>
                                <!--to update addtional-->
                                <?php $__currentLoopData = $book->cu_additional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <input type="hidden" name="additional_id" value="<?php echo e($ad->id); ?>" >
                                    <input type="hidden" name="additional_update" value="1" >
                                    <tr>
                                        <td>
                                            <table class="table" >
                                                <?php if($book->depart_task == '0'): ?>
                                                    <tr>
                                                        <td>項目</td>
                                                        <td>単価</td>
                                                        <td>個数</td>
                                                        <td>料金</td>
                                                    </tr>
                                                    <!--insurance start-->
                                                    <?php if($book->insurance1_add_flag == true || $book->insurance2_add_flag == true ): ?>
                                                        <?php
                                                            $add_ins_count = 0;
                                                        ?>
                                                        <?php $__currentLoopData = $ad->insurance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($in->basic_price != '0'): ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo e($in->name); ?>

                                                                    <input type="hidden" name="add_ins_search_condition[]" id="add_ins_search_<?php echo e($add_ins_count); ?>" value="<?php echo e($in->search_condition); ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control coin " id="add_ins_basic_price_<?php echo e($add_ins_count); ?>" name="add_ins_basic_prices[]" value="<?php echo e($in->basic_price); ?>" readonly >
                                                                <td>
                                                                    <select name="add_ins_flags[]" id="add_ins_flag_<?php echo e($add_ins_count); ?>" onchange="updateAdditionalPriceModal('insurance','<?php echo e($add_ins_count); ?>','0')" class="form-control">
                                                                        <option value="1" <?php if($in->price != '0'): ?> selected <?php endif; ?>>Yes</option>
                                                                        <option value="0" <?php if($in->price == '0'): ?> selected <?php endif; ?>>No</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control coin" id="add_ins_price_<?php echo e($add_ins_count); ?>" name="add_ins_prices[]" value="<?php echo e($in->price); ?>" readonly >
                                                                </td>
                                                            </tr>
                                                            <?php endif; ?>
                                                            <?php $add_ins_count++; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                    <input type="hidden" name="add_all_insurance_price" id="add_all_insurance_price" value="<?php echo e($ad->insurance_price); ?>" readonly>
                                                    <!--insurance end-->
                                                    <!--option start-->
                                                    <?php $add_op_count = 0; ?>
                                                    <?php $__currentLoopData = $ad->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($op->option_flag_add == false): ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo e($op->option_name); ?>

                                                                    <input type="hidden" name="add_option_ids[]" value="<?php echo e($op->option_id); ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control coin" id="add_op_basic_<?php echo e($add_op_count); ?>" name="add_option_basic_prices[]" value="<?php echo e($op->option_basic_price); ?>" readonly >
                                                                </td>
                                                                <td>
                                                                    <select name="add_option_numbers[]" id="add_op_number_<?php echo e($add_op_count); ?>" class="form-control" onchange="updateAdditionalPriceModal('option','<?php echo e($add_op_count); ?>','<?php echo e($op->index); ?>')">
                                                                        <?php if($op->max_number == '1'): ?>
                                                                        <?php if($op->index == '26'): ?> <!--snow tire option-->
                                                                        <option value="<?php echo e($book->set_day); ?>" <?php if($book->set_day == $op->option_number): ?> selected <?php endif; ?>>Yes</option>
                                                                        <option value="0" <?php if('0' == $op->option_number): ?> selected <?php endif; ?>>No</option>
                                                                        <?php else: ?>
                                                                            <option value="1" <?php if('1' == $op->option_number): ?> selected <?php endif; ?>>Yes</option>
                                                                            <option value="0" <?php if('0' == $op->option_number): ?> selected <?php endif; ?>>No</option>
                                                                        <?php endif; ?>
                                                                        <?php else: ?>
                                                                            <?php
                                                                            for($i = 0 ; $i < $op->max_number+1 ; $i++) {
                                                                            ?>
                                                                            <option value="<?php echo e($i); ?>" <?php if($i == $op->option_number): ?> selected <?php endif; ?>><?php echo e($i); ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control coin" id="add_op_price_<?php echo e($add_op_count); ?>" name="add_option_prices[]" value="<?php echo e($op->option_price); ?>" readonly >
                                                                    <?php if($op->index == '26'): ?> <!--snow tire option to get price-->
                                                                    <input type="hidden" class="form-control coin" id="add_option_id_snow" name="option_id_snow" value="<?php echo e($op->option_id); ?>" readonly >
                                                                    <input type="hidden" class="form-control coin" id="add_option_price_snow" name="option_price_snow" value="<?php echo e($op->option_price); ?>" readonly >
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <?php $add_op_count++; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <input type="hidden" name ="add_all_option_price" id="add_all_option_price" value="<?php echo e($ad->option_price); ?>" readonly>
                                                    <!--option end-->
                                                <?php endif; ?>
                                                    <!--extend pay status start-->
                                                    <?php if($book->extend_add_flag == true || $ad->extend_payment != '0' ): ?>
                                                        <tr>
                                                            <td><label class="control-label" >延泊</label></td>
                                                            <td colspan="3">
                                                                <table class="table table-bordered" >
                                                                    <tr>
                                                                        <td> 延泊数</td>
                                                                        <td> 基本料金</td>
                                                                        <td> オプション料金</td>
                                                                        <td>  雪タ</td>
                                                                        <td> 延泊料金総計 </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <select name="add_extend_day" id="add_extend_day" class="form-control" onchange="updatechangeModalPrice()">
                                                                                <option  value="0">選択してください</option>
                                                                                <?php
                                                                                for($i = 1 ; $i < $book->extended_day+1 ; $i++) {
                                                                                ?>
                                                                                <option value="<?php echo e($i); ?>" <?php if($ad->extend_day == $i): ?> selected <?php endif; ?> ><?php echo e($i); ?>泊</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" id="add_extend_basic_price" name="add_extend_basic_price" class="form-control" value="<?php echo e($ad->extend_basic_price); ?>"  readonly >
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" id='add_extend_insurance_price' name="add_extend_insurance_price" class="form-control" value="<?php echo e($ad->extend_insurance1 + $book->extend_insurance2); ?>"  readonly >
                                                                            <input type="hidden" id='add_extend_insurance1' name="add_extend_insurance1" class="form-control" value="<?php echo e($ad->extend_insurance1); ?>"  readonly >
                                                                            <input type="hidden" id='add_extend_insurance2' name="add_extend_insurance2" class="form-control" value="<?php echo e($ad->extend_insurance2); ?>" readonly >
                                                                            <input type="hidden" id='add_extend_return_date' name="add_extend_return_date" class="form-control" value="<?php echo e($ad->extend_return_date); ?>"  readonly >
                                                                        </td>
                                                                        <td>
                                                                            <input type="hidden" id='add_extend_options_price' name="add_extend_options_price" class="form-control" value="<?php echo e($ad->extend_options_price); ?>"  readonly >
                                                                            <input type="text" id='add_extend_option_price' name="add_extend_option_price" class="form-control" value="<?php echo e($ad->extend_option_price); ?>" readonly >
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" id='add_extend_payment' name="add_extend_payment" class="form-control" value="<?php echo e($ad->extend_payment); ?>" readonly >
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <!--extend pay status end-->
                                            </table>
                                            <!--etc card price start-->
                                            <?php if($book->depart_task == '1'): ?>
                                                <table class="table" >
                                                    <tr>
                                                        <td class="col-md-2"><label class="control-label" >ETC利用料金</label></td>
                                                        <td colspan="3" class="col-md-10">
                                                            <input type="number" name="add_etc_card" id="add_etc_card" class="form-control" value="<?php echo e($ad->etc_card); ?>" onkeyup="updateAdditionalPriceModal('adjustment','0','0')" >
                                                        </td>
                                                    </tr>
                                                </table>
                                            <?php endif; ?>
                                            <!--etc  card price end-->
                                            <table class="table" >
                                                <tr>
                                                    <td>調整/割引金額</td>
                                                    <td colspan="3">
                                                        <input type="number" class="form-control coin" id="add_adjustment_price" name="add_adjustment_price" value="<?php echo e($ad->adjustment_price); ?>" onkeyup="updateAdditionalPriceModal('adjustment','0','0')"  >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="2"><label class="control-label" >明細の合計</label></td>
                                                    <td >
                                                        <input id="add_total_pay" name="add_total_pay" class="form-control section_total" value="<?php echo e($ad->option_price + $ad->insurance_price +$ad->adjustment_price+$ad->extend_payment+$ad->etc_card); ?>" readonly required>
                                                    </td>
                                                </tr>
                                            </table>
                                            <!--web pay type start-->
                                            <table class="table">
                                                <tr>
                                                    <td>
                                                        <label class="control-label" >支払い方法</label>
                                                    </td>
                                                    <td >
                                                        <select name="add_pay_method" id="add_pay_method"  class="form-control">
                                                                <option value="0">選択してください </option>
                                                                <option value="1" <?php if($ad->pay_method == '1'): ?> selected <?php endif; ?>>現金</option> <!--cash-->
                                                                <option value="2" <?php if($ad->pay_method == '2'): ?> selected <?php endif; ?> >カード</option> <!--credit card-->
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                            <!--web pay type end-->
                                        </td>
                                    </tr>
                                    <? $add_count++; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </div>
                    <?php endif; ?>

                    <!--cancellation fee start no fee-->
                    <div id="cancel_section_nofee" style="display: none;">
                        <table class="table table-bordered">
                            <tr>
                                <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                    <span class="circle"><?php echo e($title_count+$title_count1); ?></span>キャンセル料金
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label class="control-label" >合計金額</label>
                                            <label id="nofi_cancel_email_icon" class="m-l-md">
                                                <button onclick="sendnotifi_cancel(event, '<?php echo e($book->id); ?>', 'no_fee')">通知 <i class="fa fa-envelope"></i></button>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="m-t-sm cancel_message">
                                        <div>
                                            <label class="control-label" style="font-size: 17px;"  >
                                                キャンセルによる返金 <span class="cancel_refund_span" ><?php echo e($book->cancel_total); ?></span>円
                                            </label>
                                        </div>
                                        <div class="m-t-md">
                                            <label class="m-r-md">
                                                クレジットカードの返金処理を行ってください
                                            </label>
                                        </div>
                                    </div>
                                    <div class="" style="margin-top: 10px; text-align: right;">
                                        <label class="control-label" style="font-size: 17px;"  >
                                            更新日:
                                        </label>
                                        <label class="m-r-md">
                                            <input type="hidden" name="cancel_date_status_nofee" id="cancel_date_status_nofee"  value="<?php echo e($book->cancel_status); ?>" >
                                            <input type="text" name="cancel_date_nofee" id="cancel_date_nofee" class="form-control" style="width:120px;" value="<?php if(empty($book->cancel_date)): ?> ----/--/-- <?php else: ?> <?php echo e(date('Y/m/d', strtotime($book->cancel_date))); ?> <?php endif; ?> " readonly >
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--cancellation no fee end-->
                    <!--cancellation fee start-->
                    <div id="cancel_section" style="display: none;">
                        <table class="table table-bordered">
                            <tr>
                                <td style="text-align:center;font-size:18px;font-weight:600;background:#b7d1ff;">
                                    <span class="circle"><?php echo e($title_count+$title_count1); ?></span>キャンセル料金
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label class="control-label" >合計金額</label>
                                            <?php if($book->depart_task == '0'): ?>
                                                <!--<label class="control-label m-l-md" > <a onclick="allsection_total()" >予約の基本料金</a></label>-->
                                            <?php endif; ?>
                                            <label id="cancel_email_icon" class="m-l-md">
                                                <button onclick="sendnotifi_cancel(event, '<?php echo e($book->id); ?>', 'fee')">通知 <i class="fa fa-envelope"></i></button>
                                            </label>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="control-label" >キャンセル料</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="control-label" >
                                                <input type="hidden" name="cancel_total" id="cancel_total" class="form-control" style="width:90px;" value="<?php echo e($book->cancel_total); ?>" readonly >
                                                <input type="text" name="cancel_basic" id="cancel_basic" class="form-control" style="width:90px;" value="<?php echo e($book->basic_price); ?>" readonly >
                                            </label>
                                            <label>円</label>
                                            <label class="control-label" >
                                                <input type="number" value="<?php echo e($book->cancel_percent); ?>" id="cancel_percent" name="cancel_percent" class="form-control m-l-md" style="width:80px;" onkeyup="changeCancelFee(event)"   />
                                            </label>
                                            <label class="control-label" style="font-size: 17px;"  >
                                                %適用
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label m-t-sm" >
                                                <img src="<?php echo e(URL::to('/')); ?>/img/arrow.png" >
                                            </label>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="control-label" style="font-size: 17px;"  >
                                                <input type="text" name="cancel_fee" id="cancel_fee" class="form-control" style="width:90px;" value="<?php echo e($book->cancel_fee); ?>" readonly >
                                            </label>
                                            <label>円</label>
                                            <label class="control-label m-l-sm" >
                                                <select name="cancel_status" id="cancel_status"  class="form-control">
                                                    <option value="0"  >選択してください </option> <!--no-->
                                                    <?php if($util->checking_portal($book->portal_id) == 'false' ): ?>
                                                    <option value="10" <?php if($book->cancel_status == '10'): ?> selected <?php endif; ?> >未請求</option> <!--claimd-->
                                                    <option value="11" <?php if($book->cancel_status == '11'): ?> selected <?php endif; ?> >請求中</option><!--unclaimd-->
                                                    <option value="2" <?php if($book->cancel_status == '2'): ?> selected <?php endif; ?> >カード</option><!--credit card-->
                                                    <option value="1" <?php if($book->cancel_status == '1'): ?> selected <?php endif; ?> >現金</option><!--cash-->
                                                    <option value="5" <?php if($book->cancel_status == '5'): ?> selected <?php endif; ?> >振込</option><!--bank transfer-->
                                                    
                                                    <?php else: ?>
                                                        <option value="4" <?php if($book->cancel_status == '4'): ?> selected <?php endif; ?> >Portal決済</option> <!--claimd-->
                                                    <?php endif; ?>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="pull-right">
                                        <label class="control-label" style="font-size: 17px;"  >
                                            更新日:
                                        </label>
                                        <label class="m-r-md">
                                            <input type="hidden" name="cancel_date_status" id="cancel_date_status" value="<?php echo e($book->cancel_status); ?>" >
                                            <input type="text" name="cancel_date" id="cancel_date" class="form-control" style="width:120px;" value="<?php if(empty($book->cancel_date)): ?> ----/--/-- <?php else: ?> <?php echo e(date('Y/m/d', strtotime($book->cancel_date))); ?> <?php endif; ?>" readonly >
                                        </label>
                                    </div>
                                    <?php if($book->portal_flag == '0'): ?>
                                    <div class="m-t-lg cancel_message">
                                        <div>
                                            <label class="control-label" style="font-size: 17px;"  >
                                                キャンセルによる返金 <span class="cancel_refund_span" ><?php echo e($book->cancel_total-$book->cancel_fee); ?></span>円
                                            </label>
                                        </div>
                                        <div class="m-t-md">
                                            <label class="m-r-md">
                                                クレジットカードの返金処理を行ってください
                                            </label>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--cancellation fee end-->
                    <div>
                        <table class="table table-bordered">
                            <!--new additinal layout-->
                            <?php if($book->additional_flag == true && $book->return_task == '0'): ?>
                                <tr>
                                    <td>
                                        <a class="btn btn-default" onclick="saveAdditinalPrice()">明細の追加</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        <!--end second part-->
                    </div>
                    <div class="col-lg-12 text-center" style="margin-top:15px" id="btnbookedit" >
                        <button class="btn btn-primary" >予約を保存</button>
                        <a href="<?php echo e(URL::to('/')); ?>/booking/all" class="btn btn-info">一覧に戻る</a>
                    </div>
                    </div>
                </form>
                <!--edit form page end-->
                <!--additionla price form start-->
                <form name="additionalmodalform" id="additionalmodalform" action="<?php echo e(URL::to('/')); ?>/booking/saveAdditionalPrice" method="post" class="form-horizontal">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="modal_departing" id="modal_departing" value="<?php echo e($book->departing); ?>" />
                    <input type="hidden" name="modal_returning" id="modal_returning" value="<?php echo e($book->returning); ?>" />
                    <input type="hidden" name="modal_depart_task" id="modal_depart_task" value="<?php echo e($book->depart_task); ?>" />
                    <input type="hidden" name="book_id" id="book_id" value="<?php echo e($book->id); ?>" />
                    <div class="panel-body">

                        <table class="table users-table" >
                            <?php if($book->depart_task == '0'): ?>
                                    <tr>
                                        <td>項目</td>
                                        <td>単価</td>
                                        <td style="width: 100px;">個数</td>
                                        <td>料金</td>
                                    </tr>
                                    <!-- insurance form start-->
                                    <?php
                                    $modal_ins_count = 0;
                                    ?>
                                    <?php $__currentLoopData = $book->insurances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $insurance_flag = false;
                                        if($modal_ins_count == 0) {
                                            if($book->insurance1_add_flag_modal == true) $insurance_flag = true;
                                        }
                                        if($modal_ins_count == 1) {
                                            if($book->insurance2_add_flag_modal == true) $insurance_flag = true;
                                        }
                                        ?>
                                        <?php if($insurance_flag == true): ?>
                                            <tr>
                                                <td>
                                                    <?php echo e($in->name); ?>

                                                    <input type="hidden" name="modal_ins_search_condition[]" id="modal_ins_search_<?php echo e($modal_ins_count); ?>" value="<?php echo e($in->search_condition); ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control coin " id="modal_ins_basic_price_<?php echo e($modal_ins_count); ?>" name="modal_ins_basic_prices[]" value="<?php echo e($in->basic_price); ?>" readonly >
                                                <td>
                                                    <select name="modal_ins_flags[]" id="modal_ins_flag_<?php echo e($modal_ins_count); ?>" onchange="changeAdditionalPriceModal('insurance','<?php echo e($modal_ins_count); ?>','0')" class="form-control">
                                                        <option value="1"  <?php if($in->price != '0'): ?> selected <?php endif; ?>>Yes</option>
                                                        <option value="0"  <?php if($in->price == '0'): ?> selected <?php endif; ?>>No</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control coin" id="modal_ins_price_<?php echo e($modal_ins_count); ?>" name="modal_ins_prices[]" value="<?php echo e($in->price); ?>" readonly >
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php $modal_ins_count++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php if($book->insurance1_add_flag_modal == true || $book->insurance2_add_flag_modal == true ): ?>
                                        <input type="hidden" id="modal_all_insurance_price" />
                                    <?php endif; ?>
                                    <!--insurance form end-->
                                    <!--option form start-->
                                    <?php $modal_op_count = 0 ; ?>
                                    <?php $__currentLoopData = $book->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($op->option_flag_modal == false): ?>
                                            <tr>
                                                <td>
                                                    <?php echo e($op->option_name); ?>

                                                    <input type="hidden" name="modal_option_ids[]" value="<?php echo e($op->option_id); ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control coin" id="modal_op_basic_<?php echo e($modal_op_count); ?>" name="modal_option_basic_prices[]" value="<?php echo e($op->option_basic_price); ?>" readonly >
                                                </td>
                                                <td>
                                                    <select name="modal_option_numbers[]" id="modal_op_number_<?php echo e($modal_op_count); ?>" class="form-control" onchange="changeAdditionalPriceModal('option','<?php echo e($modal_op_count); ?>','<?php echo e($op->index); ?>')">
                                                        <?php if($op->max_number == '1'): ?>
                                                            <?php if($op->index == '26'): ?> <!--snow tire option-->
                                                                <option value="<?php echo e($book->set_day); ?>" <?php if($book->set_day == $op->option_number): ?> selected <?php endif; ?>>Yes</option>
                                                                <option value="0" <?php if('0' == $op->option_number): ?> selected <?php endif; ?>>No</option>
                                                            <?php else: ?>
                                                                <option value="1" <?php if('1' == $op->option_number): ?> selected <?php endif; ?>>Yes</option>
                                                                <option value="0" <?php if('0' == $op->option_number): ?> selected <?php endif; ?>>No</option>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php
                                                            for($i = 0 ; $i < $op->max_number+1 ; $i++) {
                                                            ?>
                                                                <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control coin" id="modal_op_price_<?php echo e($modal_op_count); ?>" name="modal_option_prices[]"  readonly >
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if($op->index == '26'): ?> <!--snow tire option to get price-->
                                            <input type="hidden" class="form-control coin" id="modal_option_id_snow" name="modal_option_id_snow" value="<?php echo e($op->option_id); ?>" readonly >
                                            <input type="hidden" class="form-control coin" id="modal_option_price_snow" name="momdal_option_price_snow" value="<?php echo e($op->option_price); ?>" readonly >
                                        <?php endif; ?>
                                        <?php $modal_op_count++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <input type="hidden" id="modal_all_option_price" />
                                <?php endif; ?>
                                <!--extend pay status start-->
                                <?php if($book->extend_add_flag_modal == true ): ?>
                                    <tr>
                                        <td><label class="control-label" >延泊</label></td>
                                        <td colspan="3">
                                            <table class="table table-bordered" >
                                                <tr>
                                                    <td> 延泊数</td>
                                                    <td> 基本料金</td>
                                                    <td> オプション料金</td>
                                                    <td>  雪タ</td>
                                                    <td> 延泊料金総計 </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="modal_extend_day" id="modal_extend_day" class="form-control" onchange="createchangeModalPrice()">
                                                            <option  value="0"  >選択してください</option>
                                                            <?php
                                                            for($i = 1 ; $i < $book->extended_day+1 ; $i++) {
                                                            ?>
                                                                <option value="<?php echo e($i); ?>"><?php echo e($i); ?>泊</option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="modal_extend_basic_price" name="modal_extend_basic_price" class="form-control"  readonly >
                                                    </td>
                                                    <td>
                                                        <input type="text" id='modal_extend_insurance_price' name="modal_extend_insurance_price" class="form-control"  readonly >
                                                        <input type="hidden" id='modal_extend_insurance1' name="modal_extend_insurance1" class="form-control"  readonly >
                                                        <input type="hidden" id='modal_extend_insurance2' name="modal_extend_insurance2" class="form-control"  readonly >
                                                        <input type="hidden" id='modal_extend_return_date' name="modal_extend_return_date" class="form-control"  readonly >
                                                    </td>
                                                    <td>
                                                        <input type="hidden" id='modal_extend_options_price' name="modal_extend_options_price" class="form-control"  readonly >
                                                        <input type="text" id='modal_extend_option_price' name="modal_extend_option_price" class="form-control"  readonly >
                                                    </td>
                                                    <td>
                                                        <input type="text" id='modal_extend_payment' name="modal_extend_payment" class="form-control"  readonly >
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <!--extend pay status end-->
                                <!--etc card price start-->
                                <?php if($book->depart_task == '1'): ?>

                                        <tr>
                                            <td class="col-md-2"><label class="control-label" >ETC利用料金</label></td>
                                            <td colspan="3" class="col-md-10">
                                                <input type="number" name="modal_etc_card" id="modal_etc_card" class="form-control" value="0" onkeyup="changeAdditionalPriceModal('etc_card','0','0')" >
                                            </td>
                                        </tr>

                                <?php endif; ?>
                                <!--etc  card price end-->
                                <tr>
                                    <td ><label class="control-label" >調整/割引金額</label></td>
                                    <td colspan="3">
                                        <input type="number" id="modal_adjustment_price" name="modal_adjustment_price" value="0" class="form-control" onkeyup="changeAdditionalPriceModal('adjustment','0','0')"  required>
                                    </td>
                                </tr>
                                <!--option form end-->
                                <tr>
                                    <td></td>
                                    <td colspan="2"><label class="control-label" >明細の合計</label></td>
                                    <td >
                                        <input id="modal_total_pay" name="modal_total_pay" class="form-control"  readonly required>
                                    </td>
                                </tr>
                        </table>
                        <!--insurance end-->
                        <!--web pay type start-->
                        <table class="table">
                            <tr>
                                <td>
                                    <label class="control-label" >支払い方法</label>
                                </td>
                                <td >
                                    <select name="modal_pay_method" id="modal_pay_method"  class="form-control">
                                        <option value="0">選択してください </option>
                                        <option value="1" >現金</option> <!--cash-->
                                        <option value="2">カード</option> <!--credit card-->
                                    </select>
                                </td>
                                
                                    
                                
                                
                                    
                                        
                                        
                                    
                                
                            </tr>
                        </table>
                        <!--web pay type end-->
                        <div class="col-lg-12 text-center" style="margin-top:15px">
                            <button class="btn btn-primary">予約を保存</button>
                            <a href="<?php echo e(URL::to('/')); ?>/booking/all" class="btn btn-info">一覧に戻る</a>
                        </div>
                    </div>
                </form>
                <!--additional price form end-->
            </div>

        </div>

    </div>
    <!--message modal-->
    <div class="modal fade" id="priceModal" tabindex="-1" role="dialog" aria-labelledby="priceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" style="margin-top: -10px;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal_message">
                    <!--option html-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade modal-warning" id="confirmCancel" role="dialog" aria-labelledby="confirmCancelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="backStatus()"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">予約の取り消し</h4>
                </div>
                <div class="modal-body">
                    <p>この予約をキャンセルしますか？ <br> <br>
                       この操作を行うと割り当てられた車両を検索対象に戻します。
                    </p>
                </div>
                <div class="modal-footer">
                    <div class="col-md-4"><button class="btn btn-warning pull-left btn-flat" onclick="controlCancel('nofee')" >請求金無しのキャンセル</button> </div>
                    <div class="col-md-4"><button class="btn btn-success btn-flat" onclick="controlCancel('fee')" >請求金有りのキャンセル</button> </div>
                    <div class="col-md-4"><button class="btn btn-primary pull-right btn-flat" onclick="controlCancel('close')" >キャンセルしません</button> </div>
                </div>
            </div>
        </div>
    </div>

    <!--message modal-->
    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <!-- license image preview Modal -->
    <div id="lic-view" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header hidden">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="col-xs-12 text-center" style="width: 100%;height: 300px;border: 1px solid #555;padding: 0;">
                        <img id="img_edit">
                        <input type="hidden" id="img_id">
                        <input type="hidden" id="img_side">
                    </div>
                    <div class="col-xs-12 text-center" style="margin: 10px 0;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="btn-move"><span class="fa fa-arrows"></span></button>
                            <button type="button" class="btn btn-primary" id="btn-crop"><span class="fa fa-crop"></span></button>
                            <button type="button" class="btn btn-primary" id="btn-rot-left"><span class="fa fa-rotate-left"></span></button>
                            <button type="button" class="btn btn-primary" id="btn-rot-right"><span class="fa fa-rotate-right"></span></button>
                            
                            <button type="button" class="btn btn-primary" id="btn-upload"><span class="fa fa-upload"></span></button>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <?php $__currentLoopData = $book->licences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="lic-thumb active">
                            <img class="img_thumb img-responsive center-block" src="<?php echo e(URL::to('/')); ?><?php echo e($img->url); ?>" width="40" onclick="showPhoto(<?php echo e(json_encode($img)); ?>)" lid="<?php echo e($img->id); ?>" side="<?php echo e($img->side); ?>" style="cursor:pointer;">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="modal-footer hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- license image upload Modal -->
    <div id="lic-upload" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="/booking/upload-lic" method="post" enctype="multipart/form-data">
                    <div class="modal-body row">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="book_id" value="<?php echo e($book->id); ?>">
                        <div class="col-md-6 text-center">
                            <img id="img_front" class="img_preview img-thumbnail"><br>
                            <label class="btn btn-primary">表面
                                <input type="file" name="lic_front" id="lic_front" side="front" class="lic-select hidden" accept=".png,.jpg" required>
                            </label>
                        </div>

                        <div class="col-md-6 text-center">
                            <img id="img_back" class="img_preview img-thumbnail"><br>
                            <label class="btn btn-primary">裏面
                                <input type="file" name="lic_back" id="lic_back" side="back" class="lic-select hidden" accept=".png,.jpg" required>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="submit" class="btn btn-primary">アップロード</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- license image delete Modal -->
    <div id="lic-delete" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="/booking/delete-lic" method="post" enctype="multipart/form-data">
                    <div class="modal-header hidden">
                        
                        <h3>ライセンス写真の削除</h3>
                    </div>
                    <div class="modal-body row">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="lic_id" id="delete_lic_id">
                        ライセンス写真を削除することができます。 本当に削除しますか？
                    </div>
                    <div class="modal-footer ">
                        <button type="submit" class="btn btn-warning">削除</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">閉じる</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <style>
        .btn {
            cursor: pointer;
        }
        #img_edit {
            width: 100%; height: 100%;object-fit: contain;object-position: center; border: none;
        }
        .img_preview { width: 200px; height:200px; object-fit: contain; object-position: center; margin-bottom: 15px; }
        .slider { width: 100%; position: relative; margin-bottom: 20px; }
        .lic-thumb.active { border: 2px solid lightblue; }
        .slick-prev { z-index: 2000; }
        #lic_img_wrapper { height: 50px; float: left;}
        .img_block { float: left; height: 50px; margin-right: 10px;}
        .remove_icon {
            position: relative;
            top: -20px;
            font-size: 17px;
            color: orangered;
            left: -10px;
        }
        .lic-thumb { display: inline-block; margin-right: 10px;}
        .col-lg-12 { padding-bottom: 7px;}
        .datepicker {
            background: #fff;
            border: 1px solid #555;
        }
    </style>

    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/plugins/cropper/cropper.min.js" type="text/javascript" charset="utf-8"></script>

    <script>
        $(document).ready( function () {
            var image = document.getElementById('img_edit');
            var $image = $('#img_edit');
            var $modal = $('#lic-view');
            var cropper;
            var destroyCropper = function(cropper) {
                if(cropper != null) {
                    cropper.destroy();
                    cropper = null;
                }
            };
            var createCropper = function(){
                return new Cropper(image, {
                    autoCrop: false,
                    // aspectRatio: 1.333333,
                    viewMode: 2,
                    crop: function (e) {
                    }
                });
            };

            $('[data-toggle="tooltip"]').tooltip();

            $image.on('load', function (e) {
                destroyCropper(cropper);
                cropper = createCropper();
            });

            $modal.on('shown.bs.modal', function () {
                // cropper = createCropper();
            }).on('hidden.bs.modal', function () {
                destroyCropper(cropper);
            });

            $('#btn-move').click( function(){
                cropper.setDragMode('move');
            });

            $('#btn-crop').click( function(){
                cropper.setDragMode('crop');
            });

            $('#btn-rot-left').click( function(){
                cropper.rotate(-90);
            });

            $('#btn-rot-right').click( function(){
                cropper.rotate(90);
            });

            $('#btn-upload').click( function () {
                var initialAvatarURL;
                var canvas;

                $modal.modal('hide');

                if (cropper) {
                    canvas = cropper.getCroppedCanvas({
                        width: 400,
                        height: 300
                    });

                    initialAvatarURL = $image.attr('src');
                    $image.prop('src', canvas.toDataURL());
                    canvas.toBlob(function (blob) {
                        var formData = new FormData();

                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('photo', blob);
                        formData.append('id', $('#img_id').val());
                        formData.append('side', $('#img_side').val());

                        $.ajax('<?php echo e(URL::to('/')); ?>/booking/update-lic', {
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,

                            success: function (data) {
                                console.log(data);
                                if(data.success === true){
                                    var id = data.id, side = data.side, url = data.url;
                                    $('.img_thumb[lid="' + id + '"]')
                                        .filter('.img_thumb[side="' + side + '"]')
                                        .attr('src', url);
                                    $('.img_view[lid="' + id + '"]')
                                        .filter('.img_view[side="' + side + '"]')
                                        .attr('src', url);
                                }
                            },
                            error: function () {
                                initialAvatarURL.attr('src', initialAvatarURL);
                            },
                        });
                    });
                }
            });
        });

        $('.lic-select').change( function () {
            var side = $(this).attr('side');
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#img_' + side).attr('src', e.target.result);
                };

                reader.readAsDataURL(this.files[0]);
            }
        });

        $('.lic-thumb').click( function () {
            $('.lic-thumb.active').removeClass('active');
            $(this).addClass('active');
        });

        function showEditModal(){
            $('#lic-view').modal('show');
        }

        function showPhoto(lic_obj) {
            $('#img_edit').attr('src', lic_obj.url);
            $('#img_id').val(lic_obj.id);
            $('#img_side').val(lic_obj.side);

        }

        function deletePhoto(lic_obj) {
            $('#delete_lic_id').val(lic_obj.id);
            $('#lic-delete').modal('show');
        }

        $(document).ready(function () {
            // init depart time and return time
            var departDate = '<?php echo e(date_format(new \DateTime($book->departing),'Y/m/d')); ?>';
            var returnDate = '<?php echo e(date_format(new \DateTime($book->returning),'Y/m/d')); ?>';
            var departTime = '<?php echo e(date_format(new \DateTime($book->departing),'H:i')); ?>';
            var returnTime = '<?php echo e(date_format(new \DateTime($book->returning),'H:i')); ?>';
            $('#depart-date').val(departDate);
            $('#return-date').val(returnDate);

            <?php if($book->status == '9' && $book->cancel_fee > 0 ): ?>
                $('#cancel_section').show();
            <?php endif; ?>
             <?php if($book->status == '9' && $book->cancel_fee == 0 ): ?>
                $('#cancel_section_nofee').show();
            <?php endif; ?>
            <?php if($book->cancel_total == '0'): ?>
                $('.cancel_message').hide();
            <?php endif; ?>
        });

        $('#change_status').change(function(){
            var action = $(this).val();
            $('#cancel_section').hide();
            switch(action) {
                case '9' : $('#confirmCancel').modal('show'); break;
            }

        });

        function backStatus(){
            var status = '<?php echo e($book->status); ?>';
            $('#change_status').val(status);
        }

        function controlCancel(cond) {
            if(cond == 'nofee') {
                var price = 0;
                var web_payment='<?php echo e($book->web_payment); ?>';
                $('.section_total').each(function(i, obj) {
                    var val = $(obj).val();
                    price += parseInt(val);
                });
                $('#cancel_section_nofee').show();
                $('.cancel_refund_span').text(price);
                $('#cancel_total').val(price);
                $('#confirmCancel').modal('hide');
                $('#cancel_section').hide();
                //get paid price
                var book_id = '<?php echo e($book->id); ?>' ;
                price = getpaidprice(book_id);
                $('#cancel_total').val(price);
                if(price > 0) {
                    $('#cancel_section_nofee').show();
                    $('.cancel_message').show();
                }
                else
                    $('.cancel_message').hide();

                window.location.href="#cancel_section_nofee";
                $('#cancel_date_status_nofee').val('1');
                var d = new Date();

                var month = d.getMonth()+1;
                var day = d.getDate();

                var output = d.getFullYear() + '/' +
                        (month<10 ? '0' : '') + month + '/' +
                        (day<10 ? '0' : '') + day;
                $('#cancel_date_nofee').val(output);
                var cancel_refund = price;
                if(price == 0) cancel_refund = 0;
                $('.cancel_refund_span').text(cancel_refund);
            }
            if(cond == 'fee') {
                $('#cancel_section').show();
                $('#cancel_section_nofee').hide();
                window.location.href="#cancel_section";
                $('#confirmCancel').modal('hide');
            }
            if(cond == 'close') {
                $('#change_status').val(0);
                $('#confirmCancel').modal('hide');
                $('#cancel_section').hide();
            }
        }

        function getTodayString(){
            var today = new Date(),
                mm = today.getMonth() + 1,
                dd = today.getDate(),
                yyyy = today.getFullYear();
            if(mm < 10) mm = '0' + mm;
            if(dd < 10) dd = '0' + dd;
            return yyyy + '/' + mm + '/' + dd;
        }

        //caluclate day from packing day to return day .
        function calculateRentDates() {
            var departDate = $('#depart-date').val(),
                    returnDate = $('#return-date').val(),
                    departTime = $('#depart-time').val(),
                    returnTime = $('#return-time').val();
            if(departDate === '' || returnDate === '')
                return null;

            var start   = new Date(departDate+" " +departTime);
            var end     = new Date(returnDate+" "+returnTime);
            var diff    = new Date(end - start);
            var days    = diff/1000/60/60/24;
            days    = Math.round(days*10)/10;
            return days;
        }

        // calculate day from packing to return day
        function showRentDays() {
            var rentDate    = calculateRentDates();
            var night   = Math.floor(rentDate)
            var day = rentDate - night;
            if(rentDate != null && rentDate > 0 ) {
                if(day >= 0.5) day =  night + 1;
                else day =  night;
                $('#total_rent_days').val(night + '泊' + day + '日');
                $('#rentdays_val').val(night+"_"+day);
            }
            getPrice();
        }

        function isNumeric(num){
            return !isNaN(num)
        }

        var today = getTodayString();
        var datepicker_setting = {
            locale : 'ja',
            format: 'yyyy/mm/dd',
            startDate: today,
            orientation: "bottom",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        };
        var departDate, returnDate;
        $('#depart-datepicker').datepicker(datepicker_setting)
            .on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            departDate = minDate;
            $('#return-datepicker').datepicker('setStartDate', minDate);
            showRentDays();
        });

        $('#return-datepicker').datepicker(datepicker_setting)
            .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            returnDate = maxDate;
            $('#depart-datepicker').datepicker('setEndDate', maxDate);
            showRentDays();
        });

        $('#depart-time').change(function(){
            showRentDays();
        });
        $('#return-time').change(function(){
            showRentDays();
        });
        $('#cancel_date').datepicker({
            locale : 'ja',
            format: 'yyyy/mm/dd',
            orientation: "top",
            //startDate: today,
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });
        $('#paid_date').datepicker({
            locale : 'ja',
            format: 'yyyy/mm/dd',
            orientation: "top",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });
        $('.additional_paid_date').datepicker({
            locale : 'ja',
            format: 'yyyy/mm/dd',
            orientation: "top",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });
        $('#cancel_date_nofee').datepicker({
            locale : 'ja',
            format: 'yyyy/mm/dd',
            orientation: "top",
            startDate: today,
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        });
        //search and select
        $(".chosen-select").chosen({
            max_selected_options: 5,
            no_results_text: "Oops, nothing found!",
        });

        //get options list from car model of car inventory
        $('#inventory_id').on('change',function() {
            var id = this.value;
            var url = '<?php echo e(URL::to('/')); ?>/booking/getoptionsfrommodel';
            var token = $('input[name="_token"]').val();
            var data = [];
            data.push({name: 'inventory_id', value: id}, {name: '_token', value: token});
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                }
            });
        });

        //get price
        function getPrice() {
            var start_date     = $('#depart-date').val();
            var end_date       = $('#return-date').val();
            var inventory_id   = $('form select[name="inventory_id"]').val();
            var selected_day   = $('#rentdays_val').val();
            var url = '<?php echo e(URL::to('/')); ?>/booking/getprice';
            var token = $('input[name="_token"]').val();
            var data = [];
            data.push(  {name: 'start_date', value: start_date},
                    {name: '_token', value: token},
                    {name: 'end_date', value: end_date},
                    {name: 'inventory_id', value: inventory_id},
                    {name: 'selected_day', value: selected_day});
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "text",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                   // $('form input[name="basic_price"]').val(content);
                }
            });
        };
        //change price fro insurance
        function changeprice(object_name, count, item_index){
            if(object_name == 'insurance') {
                var flag = $('#ins_flag_'+count).val();
                var basic_price = $('#ins_basic_price_'+count).val();
                var search_condition = $('#ins_search_'+count).val();
                if(flag == 1) {
                    if(search_condition == '2') {
                        var search_condition1_flag =  $('#ins_flag_0').val();
                        if(search_condition1_flag == '0'){
                            $('#modal_message').html('免責補償を選択してください。');
                            $('#priceModal').modal('show');
                            $('#ins_flag_'+count).val('0');
                            return;
                        }
                    }
                 var day_price= parseInt('<?php echo e(explode('_',$book->rent_days)[1]); ?>')*parseInt(basic_price);
                    $('#ins_price_'+count).val(day_price);
                }else {
                    $('#ins_price_'+count).val(0);
                }
                var all_price =  parseInt($('#ins_price_0').val()) + parseInt($('#ins_price_1').val()) ;
                $('#all_insurance_price').val(all_price);
            }
            if(object_name == 'option') {
                var option_number = $('#op_number_'+count).val();
                var option_basic_price = $('#op_basic_'+count).val();
                var option_price = parseInt(option_number)*parseInt(option_basic_price);
                if(item_index == '26') { //snow option
                    var option_snow_price = parseInt(option_basic_price);
                    if(option_number == '0') option_snow_price = 0;
                    $('#option_snow').val(option_snow_price);
                }
                $('#op_price_'+count).val(option_price);
                var all_count = '<?php echo e($op_count); ?>';
                var all_price = 0;
                for(var i = 0; i < parseInt(all_count) ; i ++) {
                    all_price += parseInt($('#op_price_'+i).val());
                }
                $('#all_option_price').val(all_price);
            }
            var basic_price     = $('#basic_price').val();
            if(isNaN(basic_price) || basic_price == '') basic_price = 0;
            var virtual_payment     = $('#virtual_payment').val();
            if(isNaN(virtual_payment) || virtual_payment == '') virtual_payment = 0;
            var insurance_price = $('#all_insurance_price').val();
            if(isNaN(insurance_price) || insurance_price == '') insurance_price = 0;
            var option_price    = $('#all_option_price').val();
            if(isNaN(option_price) || option_price == '') option_price = 0;
            var extend_payment    = $('#extend_payment').val();
            if(isNaN(extend_payment) || extend_payment == '') extend_payment = 0;
            var discount    = $('#discount').val();
            if(isNaN(discount) || discount == '') discount = 0;
            var total_pay = parseInt(basic_price)+parseInt(virtual_payment)+parseInt(option_price)+parseInt(insurance_price)+parseInt(extend_payment)+parseInt(discount);
            $('#total_pay').val(total_pay);

            //changeFormat();
        }
        //update price for insurance in additional modal
        function updateAdditionalPriceModal(object_name, count,item_index){
            if(object_name == 'insurance') {
                var flag = $('#add_ins_flag_'+count).val();
                var basic_price = $('#add_ins_basic_price_'+count).val();
                var search_condition = $('#add_ins_search_'+count).val();
                if(flag == 1) {
                    if(search_condition == '2') {
                        var search_condition1_flag =  $('#add_ins_flag_0').val();
                        if(search_condition1_flag == '0'){
                            $('#modal_message').html('免責補償を選択してください。');
                            $('#priceModal').modal('show');
                            $('#add_ins_flag_1').val(0);
                            return;
                        }
                    }
                    var day_price= parseInt('<?php echo e(explode('_',$book->rent_days)[1]); ?>')*parseInt(basic_price);
                    $('#add_ins_price_'+count).val(day_price);
                }else {
                    $('#add_ins_price_'+count).val(0);
                }
                var add_ins_price_0 = $('#add_ins_price_0').val();
                if(add_ins_price_0 == '' || isNaN(add_ins_price_0)) add_ins_price_0 = 0;
                var add_ins_price_1 = $('#add_ins_price_1').val();
                if(add_ins_price_1 == '' || isNaN(add_ins_price_1)) add_ins_price_1 = 0;
                var all_price =  parseInt(add_ins_price_0) + parseInt(add_ins_price_1) ;
                $('#add_all_insurance_price').val(all_price);
            }
            if(object_name == 'option') {
                var option_number = $('#add_op_number_'+count).val();
                var option_basic_price = $('#add_op_basic_'+count).val();
                var option_price = parseInt(option_number)*parseInt(option_basic_price);
                if(item_index == '26') { //snow option
                    var option_snow_price = parseInt(option_basic_price);
                    if(option_number == '0') option_snow_price = 0;
                    $('#option_snow').val(option_snow_price);
                }
                $('#add_op_price_'+count).val(option_price);
                var all_count = '<?php echo e($op_count); ?>';
                var all_price = 0;
                for(var i = 0; i < parseInt(all_count) ; i ++) {
                    var add_op_price_integer = $('#add_op_price_'+i).val();
                    if(isNaN(add_op_price_integer) || add_op_price_integer == '') add_op_price_integer = 0;
                    all_price += parseInt(add_op_price_integer);
                }
                $('#add_all_option_price').val(all_price);
            }
            var insurance_price    = $('#add_all_insurance_price').val();
            if(insurance_price == '' || isNaN(insurance_price)) insurance_price = 0;
            var option_price = $('#add_all_option_price').val();
            if(option_price == '' || isNaN(option_price)) option_price = 0;
            var add_adjustment_price = $('#add_adjustment_price').val();
            if(add_adjustment_price == '' || isNaN(add_adjustment_price)) add_adjustment_price = 0;
            var extend_payment      = $('#add_extend_payment').val();
            if(extend_payment == '' || isNaN(extend_payment)) extend_payment = 0;
            var etc_card      = $('#add_etc_card').val();
            if(etc_card == '' || isNaN(etc_card)) etc_card = 0;
            var total_pay = parseInt(option_price)+parseInt(insurance_price)+parseInt(add_adjustment_price)+parseInt(extend_payment) + parseInt(etc_card);
            $('#add_total_pay').val(total_pay);

        }
        //change price fro insurance in additional modal
        function changeAdditionalPriceModal(object_name, count, item_index){
            if(object_name == 'insurance') {
                var flag = $('#modal_ins_flag_'+count).val();
                var basic_price = $('#modal_ins_basic_price_'+count).val();
                var search_condition = $('#modal_ins_search_'+count).val();
                if(flag == 1) {
                    if(search_condition == '2') {
                        var search_condition1_flag =  $('#modal_ins_flag_0').val();
                        if(search_condition1_flag == '0'){
                            $('#modal_message').html('免責補償を選択してください。');
                            $('#priceModal').modal('show');
                            $('#modal_ins_flag_1').val(0);
                            return;
                        }
                    }
                    var day_price= parseInt('<?php echo e(explode('_',$book->rent_days)[1]); ?>')*parseInt(basic_price);
                    $('#modal_ins_price_'+count).val(day_price);
                }else {
                    $('#modal_ins_price_'+count).val(0);
                }
                var modal_ins_price_0 = $('#modal_ins_price_0').val();
                if(modal_ins_price_0 == '' || isNaN(modal_ins_price_0)) modal_ins_price_0 = 0;
                var modal_ins_price_1 = $('#modal_ins_price_1').val();
                if(modal_ins_price_1 == '' || isNaN(modal_ins_price_1)) modal_ins_price_1 = 0;
                var all_price =  parseInt(modal_ins_price_0) + parseInt(modal_ins_price_1) ;
                $('#modal_all_insurance_price').val(all_price);
            }
            if(object_name == 'option') {
                var option_number = $('#modal_op_number_'+count).val();
                var option_basic_price = $('#modal_op_basic_'+count).val();
                var option_price = parseInt(option_number)*parseInt(option_basic_price);
                if(item_index == '26') { //snow option
                    var option_snow_price = parseInt(option_basic_price);
                    if(option_number == '0') option_snow_price = 0;
                    $('#option_snow').val(option_snow_price);
                }
                $('#modal_op_price_'+count).val(option_price);
                var all_count = '<?php echo e($op_count); ?>';
                var all_price = 0;
                for(var i = 0; i < parseInt(all_count) ; i ++) {
                    var price = parseInt($('#modal_op_price_'+i).val());
                    if(price == null || isNaN(price)) price = 0;
                    all_price += price;
                }
                $('#modal_all_option_price').val(all_price);
            }
            var insurance_price    = $('#modal_all_insurance_price').val();
            if(insurance_price == '' || isNaN(insurance_price)) insurance_price = 0;
            var option_price = $('#modal_all_option_price').val();
            if(option_price == '' || isNaN(option_price)) option_price = 0;
            var extend_payment      = $('#modal_extend_payment').val();
            if(extend_payment == '' || isNaN(extend_payment)) extend_payment = 0;
            var modal_adjustment_price = $('#modal_adjustment_price').val();
            if(modal_adjustment_price == '' || isNaN(modal_adjustment_price)) modal_adjustment_price = 0;
            var modal_etc_card = $('#modal_etc_card').val();
            if(modal_etc_card == '' || isNaN(modal_etc_card)) modal_etc_card = 0;
            var total_pay = parseInt(option_price)+parseInt(insurance_price)+parseInt(modal_adjustment_price)+parseInt(extend_payment) + parseInt(modal_etc_card);
            $('#modal_total_pay').val(total_pay);
        }
        //get price whe update extend day
        function updatechangeModalPrice(){
            var extend_day  = $('#add_extend_day').val();
            if(extend_day == '0') {
                $('#add_extend_basic_price').val(0);
                $('#add_extend_insurance_price').val(0);
                $('#add_extend_insurance1').val(0);
                $('#add_extend_insurance2').val(0);
                $('#add_extend_return_date').val();
                $('#add_extend_option_snow').val(0);
                $('#add_extend_options_snow').val(0);
                $('#add_extend_payment').val(0);
                var insurance_price    = $('#add_all_insurance_price').val();
                if(insurance_price == '' || isNaN(insurance_price)) insurance_price = 0;
                var option_price = $('#add_all_option_price').val();
                if(option_price == '' || isNaN(option_price)) option_price = 0;
                var extend_payment      = $('#add_extend_payment').val();
                if(extend_payment == '' || isNaN(extend_payment)) extend_payment = 0;
                var add_adjustment_price = $('#add_adjustment_price').val();
                if(add_adjustment_price == '' || isNaN(add_adjustment_price)) add_adjustment_price = 0;
                var total_pay = parseInt(option_price)+parseInt(insurance_price)+parseInt(add_adjustment_price)+parseInt(extend_payment);
                $('#add_total_pay').val(total_pay);
                return;
            };
            var book_id     = '<?php echo e($book->id); ?>';
            var class_id    = '<?php echo e($book->class_id); ?>';
            var start_date  = '<?php echo e($book->depart_date); ?>';
            var start_time  = '<?php echo e($book->depart_time); ?>';
            var end_date    = '<?php echo e($book->return_date); ?>';
            var end_time    = '<?php echo e($book->return_time); ?>';
            var url         = '<?php echo e(URL::to('/')); ?>/booking/extendgetprice';
            var token       = $('input[name="_token"]').val();
            var insurance1_flag  = $('#add_ins_flag_0').val();
            var insurance2_flag  = $('#add_ins_flag_1').val();
            //var option_snow = $('#add_option_price_snow').val();
            var option_snow = $('#option_snow').val();
            if(option_snow == '' || isNaN(option_snow)) option_snow = 0;
            var data        = [];
            data.push(  {name: 'extend_day',    value: extend_day},
                    {name: 'book_id',       value: book_id},
                    {name: '_token',        value: token},
                    {name: 'start_date',    value: start_date},
                    {name: 'end_date',      value: end_date},
                    {name: 'start_time',    value: start_time},
                    {name: 'end_time',      value: end_time},
                    {name: 'class_id',      value: class_id},
                    {name: 'insurance1_flag',    value: insurance1_flag},
                    {name: 'insurance2_flag',    value: insurance2_flag},
                    {name: 'option_snow',   value: option_snow}
            );
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    var rec = content;
                    $('#add_extend_basic_price').val(rec.basic_price);
                    $('#add_extend_insurance_price').val(rec.insurance);
                    $('#add_extend_insurance1').val(rec.insurance1);
                    $('#add_extend_insurance2').val(rec.insurance2);
                    $('#add_extend_return_date').val(rec.return_date);
                    $('#add_extend_option_price').val(rec.option_snow);
                    $('#add_extend_options_price').val(rec.option_snow);
                    $('#add_extend_payment').val(rec.sum);
                    var insurance_price    = $('#add_all_insurance_price').val();
                    if(insurance_price == '' || isNaN(insurance_price)) insurance_price = 0;
                    var option_price = $('#add_all_option_price').val();
                    if(option_price == '' || isNaN(option_price)) option_price = 0;
                    var extend_payment      = $('#add_extend_payment').val();
                    if(extend_payment == '' || isNaN(extend_payment)) extend_payment = 0;
                    var add_adjustment_price = $('#add_adjustment_price').val();
                    if(add_adjustment_price == '' || isNaN(add_adjustment_price)) add_adjustment_price = 0;
                    var total_pay = parseInt(option_price)+parseInt(insurance_price)+parseInt(add_adjustment_price)+parseInt(extend_payment);
                    $('#add_total_pay').val(total_pay);
                }
            });
        }
        //get price when change extend day
        function changeModalPrice(){
            var extend_day  = $('#extend_day').val();
            if(extend_day == '0') {
                $('#extend_basic_price').val(0);
                $('#extend_insurance_price').val(0);
                $('#extend_insurance1').val(0);
                $('#extend_insurance2').val(0);
                $('#extend_return_date').val();
                $('#extend_option_snow').val(0);
                $('#extend_options_snow').val(0);
                $('#extend_payment').val(0);
                $('#extend_option_price').val(0);
                var basic_price     = $('#basic_price').val();
                if(isNaN(basic_price) || basic_price == '') basic_price = 0;
                var virtual_payment     = $('#virtual_payment').val();
                if(isNaN(virtual_payment) || virtual_payment == '') virtual_payment = 0;
                var insurance_price    = $('#all_insurance_price').val();
                if(insurance_price == '' || isNaN(insurance_price)) insurance_price = 0;
                var option_price = $('#all_option_price').val();
                if(option_price == '' || isNaN(option_price)) option_price = 0;
                var extend_payment      = $('#extend_payment').val();
                if(extend_payment == '' || isNaN(extend_payment)) extend_payment = 0;
                var discount = $('#discount').val();
                if(discount == '' || isNaN(discount)) discount = 0;
                var total_pay = parseInt(basic_price)+parseInt(virtual_payment) + parseInt(option_price)+parseInt(insurance_price)+parseInt(discount)+parseInt(extend_payment);
                $('#total_pay').val(total_pay);
                return;
            };
            var book_id     = '<?php echo e($book->id); ?>';
            var class_id    = '<?php echo e($book->class_id); ?>';
            var start_date  = '<?php echo e($book->depart_date); ?>';
            var start_time  = '<?php echo e($book->depart_time); ?>';
            var end_date    = '<?php echo e($book->return_date); ?>';
            var end_time    = '<?php echo e($book->return_time); ?>';
            var url         = '<?php echo e(URL::to('/')); ?>/booking/extendgetprice';
            var token       = $('input[name="_token"]').val();
            var insurance1_flag  = $('#ins_flag_0').val();
            var insurance2_flag  = $('#ins_flag_1').val();
            //var option_snow = $('#option_price_snow').val();
            var option_snow = $('#option_snow').val();
            var data        = [];
            data.push(  {name: 'extend_day',    value: extend_day},
                        {name: 'book_id',       value: book_id},
                        {name: '_token',        value: token},
                        {name: 'start_date',    value: start_date},
                        {name: 'end_date',      value: end_date},
                        {name: 'start_time',    value: start_time},
                        {name: 'end_time',      value: end_time},
                        {name: 'class_id',      value: class_id},
                        {name: 'insurance1_flag',    value: insurance1_flag},
                        {name: 'insurance2_flag',    value: insurance2_flag},
                        {name: 'option_snow',   value: option_snow}
            );
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    var rec = content;
                    $('#extend_basic_price').val(rec.basic_price);
                    $('#extend_insurance_price').val(rec.insurance);
                    $('#extend_insurance1').val(rec.insurance1);
                    $('#extend_insurance2').val(rec.insurance2);
                    $('#extend_return_date').val(rec.return_date);
                    $('#extend_option_price').val(rec.option_snow);
                    $('#extend_options_price').val(rec.option_snow);
                    $('#extend_payment').val(rec.sum);
                    var basic_price     = $('#basic_price').val();
                    if(isNaN(basic_price) || basic_price == '') basic_price = 0;
                    var virtual_payment     = $('#virtual_payment').val();
                    if(isNaN(virtual_payment) || virtual_payment == '') virtual_payment = 0;
                    var insurance_price    = $('#all_insurance_price').val();
                    if(insurance_price == '' || isNaN(insurance_price)) insurance_price = 0;
                    var option_price = $('#all_option_price').val();
                    if(option_price == '' || isNaN(option_price)) option_price = 0;
                    var extend_payment      = $('#extend_payment').val();
                    if(extend_payment == '' || isNaN(extend_payment)) extend_payment = 0;
                    var discount = $('#discount').val();
                    if(discount == '' || isNaN(discount)) discount = 0;
                    var total_pay = parseInt(basic_price)+ parseInt(virtual_payment) + parseInt(option_price)+parseInt(insurance_price)+parseInt(discount)+parseInt(extend_payment);
                    $('#total_pay').val(total_pay);
                }
            });
        }

        //get price when add extend day in frist create forme
        function createchangeModalPrice(){
            var extend_day  = $('#modal_extend_day').val();
            if(extend_day == '0') {
                $('#modal_extend_basic_price').val(0);
                $('#modal_extend_insurance_price').val(0);
                $('#modal_extend_insurance1').val(0);
                $('#modal_extend_insurance2').val(0);
                $('#modal_extend_return_date').val();
                $('#modal_extend_option_snow').val(0);
                $('#modal_extend_options_snow').val(0);
                $('#modal_extend_payment').val(0);
                var insurance_price    = $('#modal_all_insurance_price').val();
                if(insurance_price == '' || isNaN(insurance_price)) insurance_price = 0;
                var option_price = $('#modal_all_option_price').val();
                if(option_price == '' || isNaN(option_price)) option_price = 0;
                var extend_payment      = $('#modal_extend_payment').val();
                if(extend_payment == '' || isNaN(extend_payment)) extend_payment = 0;
                var modal_adjustment_price = $('#modal_adjustment_price').val();
                if(modal_adjustment_price == '' || isNaN(modal_adjustment_price)) modal_adjustment_price = 0;
                var total_pay = parseInt(option_price)+parseInt(insurance_price)+parseInt(modal_adjustment_price)+parseInt(extend_payment);
                $('#modal_total_pay').val(total_pay);
                return;
            };
            var book_id     = '<?php echo e($book->id); ?>';
            var class_id    = '<?php echo e($book->class_id); ?>';
            var start_date  = '<?php echo e($book->depart_date); ?>';
            var start_time  = '<?php echo e($book->depart_time); ?>';
            var end_date    = '<?php echo e($book->return_date); ?>';
            var end_time    = '<?php echo e($book->return_time); ?>';
            var url         = '<?php echo e(URL::to('/')); ?>/booking/extendgetprice';
            var token       = $('input[name="_token"]').val();
            var insurance1_flag = $('#modal_ins_flag_0').val();
            var insurance2_flag = $('#modal_ins_flag_1').val();
            //var option_snow = $('#modal_option_price_snow').val();
            var option_snow = $('#option_snow').val();
            var data        = [];
            data.push(  {name: 'extend_day',    value: extend_day},
                    {name: 'book_id',       value: book_id},
                    {name: '_token',        value: token},
                    {name: 'start_date',    value: start_date},
                    {name: 'end_date',      value: end_date},
                    {name: 'start_time',    value: start_time},
                    {name: 'end_time',      value: end_time},
                    {name: 'class_id',      value: class_id},
                    {name: 'insurance1_flag',    value: insurance1_flag},
                    {name: 'insurance2_flag',    value: insurance2_flag},
                    {name: 'option_snow',   value: option_snow}
            );
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    var rec = content;
                    $('#modal_extend_basic_price').val(rec.basic_price);
                    $('#modal_extend_insurance_price').val(rec.insurance);
                    $('#modal_extend_insurance1').val(rec.insurance1);
                    $('#modal_extend_insurance2').val(rec.insurance2);
                    $('#modal_extend_return_date').val(rec.return_date);
                    $('#modal_extend_option_price').val(rec.option_snow);
                    $('#modal_extend_options_price').val(rec.option_snow);
                    $('#modal_extend_payment').val(rec.sum);
                    var insurance_price    = $('#modal_all_insurance_price').val();
                    if(insurance_price == '' || isNaN(insurance_price)) insurance_price = 0;
                    var option_price = $('#modal_all_option_price').val();
                    if(option_price == '' || isNaN(option_price)) option_price = 0;
                    var extend_payment      = $('#modal_extend_payment').val();
                    if(extend_payment == '' || isNaN(extend_payment)) extend_payment = 0;
                    var modal_adjustment_price = $('#modal_adjustment_price').val();
                    if(modal_adjustment_price == '' || isNaN(modal_adjustment_price)) modal_adjustment_price = 0;
                    var total_pay = parseInt(option_price)+parseInt(insurance_price)+parseInt(modal_adjustment_price)+parseInt(extend_payment);
                    $('#modal_total_pay').val(total_pay);
                }
            });
        }

        //save additonal price modal
        $('#additionalmodalform').hide();
        function saveAdditinalPrice(){
            $('#btnbookedit').hide();
            $('#additionalmodalform').show();
        }
        //pay event for unpaid
        function unpaidPay(book_id) {
            var unpaid_payment = '<?php echo e($book->unpaid_payment); ?>';
            if(unpaid_payment == 0) return;
            var url         = '<?php echo e(URL::to('/')); ?>/booking/unpaidpay';
            var token       = $('input[name="_token"]').val();
            var data        = [];
            var pay_method  = $('#unpaidpay').val();
            data.push(
                    {name: '_token',        value: token},
                    {name: 'book_id',       value: book_id},
                    {name: 'pay_method',    value: pay_method}
            );
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    window.location.href = "<?php echo e(URL::to('/')); ?>/booking/edit/"+book_id;
                }
            });
        }
        //change cancelfee
        function changeCancelFee(e){
            var book_id = '<?php echo e($book->id); ?>' ;
            var paid_total_price = getpaidprice(book_id);
            $('#cancel_total').val(paid_total_price);
            if(paid_total_price == 0) $('.cancel_message').hide();
            else  $('.cancel_message').show();
            var d = new Date();

            var month = d.getMonth()+1;
            var day = d.getDate();

            var output = d.getFullYear() + '/' +
                    (month<10 ? '0' : '') + month + '/' +
                    (day<10 ? '0' : '') + day;
            var price   = parseInt($('#cancel_basic').val());
            var total   = $('#cancel_total').val();
            var target  = $(e.currentTarget);
            var percent = parseInt(target.val());
            var fee = 0;
            if(price > 0 ) {
                fee = Math.round(price/100 * percent);
            }else {
                fee = 0;
            }
            $('#cancel_fee').val(fee);
            $('#cancel_date_status').val('1');
            $('#cancel_date').val(output);
            var cancel_refund = total - fee;
            if(total == 0) cancel_refund = 0;
            $('.cancel_refund_span').text(cancel_refund);
            email_icon_check();
        }
        //get total price for all section
        function allsection_total(){
            var price = 0;
            var basic_price = '<?php echo e($book->basic_price); ?>';
            var book_id = '<?php echo e($book->id); ?>' ;
            // $('.section_total').each(function(i, obj) {
            //     var val = $(obj).val();
            //     price += parseInt(val);
            // });
            //get paid price
            price = getpaidprice(book_id);
            $('#cancel_total').val(price);
            $('#cancel_basic').val(basic_price);
            $('#cancel_percent').val(0);
            $('#cancel_fee').val(0);
            $('#cancel_status').val(0);
            $('#cancel_date_status').val('1');
        }
        //get paid price
        function getpaidprice(book_id){
            var price = 0;
            var url         = '<?php echo e(URL::to('/')); ?>/booking/getpaidprice';
            var token       = $('input[name="_token"]').val();
            var data        = [];
            data.push(
                {name: '_token',        value: token},
                {name: 'book_id',       value: book_id}
            );
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    price = content.price;
                }
            });
            return price;
        }
        //send email to user and admin
        function sendnotifi_cancel(e, book_id, fee) {
            e.preventDefault();
            var url         = '<?php echo e(URL::to('/')); ?>/booking/sendnotifi_cancel';
            var token       = $('input[name="_token"]').val();
            var data        = [];
            var cancel_total  = $('#cancel_total').val();
            var cancel_percent  = $('#cancel_percent').val();
            var cancel_fee      = $('#cancel_fee').val();
            var cancel_date     = $('#cancel_date').val();
            var cancel_status   = $('#cancel_status').val();
            data.push(
                    {name: '_token',        value: token},
                    {name: 'book_id',       value: book_id},
                    {name: 'cancel_total',  value: cancel_total},
                    {name: 'cancel_percent',  value: cancel_percent},
                    {name: 'cancel_fee',  value: cancel_fee},
                    {name: 'cancel_date',  value: cancel_date},
                    {name: 'cancel_status',  value: cancel_status},
                    {name: 'fee',  value: fee}
            );
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                // async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    // console.log(content);
                    if(content.code == '200' ) {
                        if(fee == 'fee') {
                            $('#cancel_email_icon').text('通知送信済');
                        }else if(fee == 'no_fee') {
                            $('#nofi_cancel_email_icon').text('通知送信済');
                        }
                    }
                }
            });

        }
        email_icon_check();
        //email cicon for diable
        function email_icon_check() {
            var flag = true;
            var email= $('#email').val();
            if(email == '') flag = false;
            var cancel_percent = parseInt($('#cancel_percent').val());
            if( cancel_percent <= 0 && flag == true ) flag = false;
            var cancel_fee = parseInt($('#cancel_fee').val());
            if( cancel_fee <= 0 && flag == true ) flag = false;
            if(flag == true) {
                $('#cancel_email_icon').show();
            }else {
                $('#cancel_email_icon').hide();
            }
        }
    </script>
    <?php echo $__env->make('scripts.admin.member', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>