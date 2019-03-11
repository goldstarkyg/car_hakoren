<?php $__env->startSection('template_title'); ?>
    個別車両の詳細
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <style type="text/css">
        .btn-save,
        .pw-change-container {
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::to('/')); ?>/css/home.css">
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>個別車両の詳細 <?php echo e($inven->name); ?>

                    <a href="<?php echo e(URL::to('/')); ?>/carinventory/inventory/<?php echo e($inven->id); ?>/edit" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        編集する
                    </a>
                    <a href="<?php echo e(URL::to('/')); ?>/carinventory/inventory" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        一覧へ戻る
                    </a>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-body">
                    <?php echo Form::model($inven, array('method' => 'PATCH', 'action' => array('CarInventoryController@update', $inven->id),  'class' => 'form-horizontal', 'id'=>'editform', 'role' => 'form', 'method' => 'PUT', 'enctype' => 'multipart/form-data')); ?>


                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label for="model_id" class="col-sm-3 control-label">モデル</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($model->id == $inven->model_id): ?> <?php echo e($model->name); ?> <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">車両番号</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($inven->numberplate1); ?> <?php echo e($inven->numberplate2); ?> <?php echo e($inven->numberplate3); ?> <span style="font-size:120%;"><?php echo e($inven->numberplate4); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">コード名</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($inven->shortname); ?>

                        </div>
                    </div>

                    
                        
                        
                            
                        
                    

                    <div class="form-group">
                        <label for="abbiriviation" class="col-sm-3 control-label">所属店舗</label>
                        <div class="col-sm-9 m-t-xs">
                            <div>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($shop->id == $inven->shop_id): ?> <?php echo e($shop->name); ?> <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="smoke" class="col-sm-3 control-label">禁煙？</label>
                            <div class="col-md-9">
                                <div id="smokeBtn" class="btn-group">
                                    <span class="btn btn-primary btn-md active" data-toggle="smoke" data-value="1">喫煙</span>
                                    <span class="btn btn-default btn-md notActive" data-toggle="smoke" data-value="0">禁煙</span>
                                </div>
                                <input type="hidden" name="smoke" id="smoke" value="<?php echo e($inven->smoke); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">最大乗車人数</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($inven->max_passenger); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">現在の走行距離</label>
                        <div class="col-sm-9 m-t-xs">
                            <?php echo e($inven->current_mileage); ?> Km
                        </div>
                    </div>

                    <div class="form-group has-feedback row hidden" id="other_locations">
                        <div>
                            <label for="dropoff_id" class="col-sm-3 control-label">返却場所</label>
                            <div class="col-md-9 m-t-xs">
                                <div>
                                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $__currentLoopData = $dropoffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $drop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($drop->shop_id == $shop->id): ?>
                                               <?php echo e($shop->name); ?>

                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row">
                        <div>
                            <label for="status" class="col-sm-3 control-label">ステータス</label>
                            <div class="col-md-9">
                                <div id="statusBtn" class="btn-group">
                                    <span class="btn btn-primary btn-md active" data-toggle="status" data-value="1">稼働中</span>
                                    <span class="btn btn-default btn-md notActive" data-toggle="status" data-value="0">非稼働</span>
                                </div>
                                <input type="hidden" name="status" id="status" value="<?php echo e($inven->status); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="hidden">
                    <?php if(!empty($repair)): ?>
                        <?php 
                        if($repair->kind == 1) $kind = '修理/車検';
                        if($repair->kind == 2) $kind = '代車特約';
                        if($repair->kind == 3) $kind = '事故代車';
                         ?>
                        <div class="form-group has-feedback row">
                            <div>
                                <label for="smoke" class="col-sm-3 control-label">タイプ</label>
                                <div class="col-sm-9">
                                    <div class="btn-group">

                                        <span class="btn btn-md <?php if($repair->kind == 1): ?> btn-primary <?php else: ?> btn-default <?php endif; ?>">修理/車検</span>
                                        <span class="btn btn-md <?php if($repair->kind == 2): ?> btn-primary <?php else: ?> btn-default <?php endif; ?>">代車特約</span>
                                        <span class="btn btn-md <?php if($repair->kind == 3): ?> btn-primary <?php else: ?> btn-default <?php endif; ?>">事故代車</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="abbiriviation" class="col-sm-3 control-label"><?php echo e($kind); ?>期間</label>
                            <div class="col-sm-9 m-t-xs">
                                <div>
                                    <?php echo e($repair->begin_date); ?> ~ <?php echo e($repair->end_date); ?>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="abbiriviation" class="col-sm-3 control-label"><?php echo e($kind); ?>料金</label>
                            <div class="col-sm-9 m-t-xs">
                                <div>
                                    <?php echo e(number_format($repair->price)); ?>円
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div>
                                <label for="status" class="col-sm-3 control-label">ステータス</label>
                                <div class="col-md-9">
                                    <div id="statusBtn" class="btn-group">
                                        <span class="btn btn-md <?php if($repair->status == 1): ?> btn-primary <?php else: ?> btn-default <?php endif; ?>">処理前</span>
                                        <span class="btn btn-md <?php if($repair->status == 2): ?> btn-primary <?php else: ?> btn-default <?php endif; ?>">処理中</span>
                                        <span class="btn btn-md <?php if($repair->status == 3): ?> btn-primary <?php else: ?> btn-default <?php endif; ?>">処理終了</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            <div>
                                <label for="status" class="col-sm-3 control-label">メモ</label>
                                <div class="col-md-9">
                                    <?php echo Form::textarea('memo', $repair->memo, ['class'=>'form-control', 'rows' => '4', 'readonly'=>'true']); ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <label>
                                <a class="btn btn-info " href="<?php echo e(URL::to('/carinventory/inventory/' . $inven->id . '/edit')); ?>" title="編集">
                                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">編集</span>
                                </a>
                            </label>
                            <label>
                                <?php echo Form::open(array('url' => URL::to('/').'/carinventory/inventory/' . $inven->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')); ?>

                                <?php echo Form::hidden('_method', 'DELETE'); ?>

                                <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs hidden-sm">削除</span>',
                                    array('class' => 'btn btn-danger',
                                        'type' => 'button' ,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirmDelete',
                                        'data-title' => '個別車両を削除する',
                                        'data-message' => 'この個別車両を本当に削除しますか？この操作を取り消すことはできません。')); ?>

                                <?php echo Form::close(); ?>

                            </label>
                        </div>
                    </div>

                    <?php echo Form::close(); ?>


                    <div style="overflow-x:auto;">
                        <table id="info-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th style="vertical-align: middle;text-align: center;">使用者</th>
                                <th style="vertical-align: middle;text-align: center;">出発</th>
                                <th style="vertical-align: middle;text-align: center;">返却</th>
                                <th style="vertical-align: middle;text-align: center;">返車時の<br/>走行距離</th>
                                <th style="vertical-align: middle;text-align: center;">メモ</th>
                                <th style="vertical-align: middle;text-align: center;">予約<br/>ステータス</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php
                                    //1:booked 2:pending 3:updated 4:processed 5:end 6:canceled
//                                    switch($book->status) {
//                                        case 1 : $status = '成約'; break;
//                                        case 2 : $status = '成約 - 配車前'; break;
//                                        case 3 : $status = 'confirmed'; break;
//                                        case 4 : $status = 'paid'; break;
//                                        case 5 : $status = 'paid/check-in'; break;
//                                        case 6 : $status = '貸出中'; break;
//                                        case 7 : $status = 'delayed'; break;
//                                        case 8 : $status = '終了'; break;
//                                        case 9 : $status = 'キャンセル'; break;
//                                        case 10 : $status = 'ignored'; break;
//                                    }
                                    ?>
                                    <td><a href="<?php echo e(URL::to('/')); ?>/booking/detail/<?php echo e($book->id); ?>"><?php echo e($book->booking_id); ?></a></td>
                                    <td>
                                        <?php
                                        $username = $book->last_name.$book->first_name;
                                        $email = $book->email;
                                        if($email == '') {
                                            $portal_info = json_decode($book->portal_info);
                                            if($portal_info) $email = $portal_info->email;
                                        }

                                        if($username == '') {
                                            $username = $book->fur_last_name.$book->fur_first_name;
                                        }
                                        if($username == '') {
                                            $portal_info = json_decode($book->portal_info);
                                            if($portal_info){
                                                $username = $portal_info->last_name.$portal_info->first_name;
                                                if($username == '') {
                                                    $username = $portal_info->fu_last_name.$portal_info->fu_first_name;
                                                }
                                            }
                                        }

                                        ?>
                                        <a href="<?php echo e(URL::to('/')); ?>/members/<?php echo e($book->client_id); ?>">
                                            <?php echo e($username); ?><br><?php echo e($email); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e(date('Y/m/d', strtotime($book->departing))); ?></td>
                                    <td><?php echo e(date('Y/m/d', strtotime($book->returning))); ?></td>
                                    <td><?php if($book->miles == '0'): ?>  <!--<?php echo e($inven->current_mileage); ?>--> <?php else: ?> <?php echo e($book->miles); ?> <?php endif; ?></td>
                                    <td><?php echo e($book->admin_memo); ?></td>
                                    <td>
                                        
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

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $repairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php
                                    //1:booked 2:pending 3:updated 4:processed 5:end 6:canceled
                                    switch($rp->status) {
                                        case 1 : $status = 'submit'; break;
                                        case 2 : $status = 'using'; break;
                                        case 3 : $status = 'finished'; break;
                                    }
                                    ?>
                                    <td><a href="<?php echo e(URL::to('/')); ?>/carrepair/<?php echo e($rp->id); ?>">SB <?php echo e($rp->inspection_id); ?></a></td>
                                    <td>
                                        <?php if($rp->kind == 1): ?>
                                            修理/車検
                                        <?php elseif($rp->kind == 2): ?>
                                            代車特約
                                        <?php else: ?>
                                            事故代車
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(date('Y/m/d', strtotime($rp->begin_date))); ?></td>
                                    <td><?php echo e(date('Y/m/d', strtotime($rp->end_date))); ?></td>
                                    <td><?php echo e($rp->mileage); ?></td>
                                    <td><?php echo e($rp->memo); ?></td>
                                    <td><?php echo e($status); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #info-table th { vertical-align: middle;text-align: center;}
    </style>

    <?php echo $__env->make('modals.modal-form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/dropzone/dropzone.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/alterclass.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/dataTables/dataTables.bootstrap.min.js"></script>

    <?php echo $__env->make('scripts.form-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.check-changed', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.gmaps-address-lookup-api3', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.admin.carinventory.show', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <!-- Jquery Validate -->
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/home.js"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>