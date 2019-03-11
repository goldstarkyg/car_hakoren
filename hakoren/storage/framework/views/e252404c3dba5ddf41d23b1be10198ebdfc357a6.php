<?php $__env->startSection('template_title'); ?>
    会員一覧
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>会員一覧</h2>
                <!--
				<div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="<?php echo e(URL::to('/')); ?>/members/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-user" aria-hidden="true"></i>
                        新規会員の登録
                    </a>
                </div>
				-->
            </div>
        </div>
        
        <div class="alert alert-success alert-dismissible">
            
            <h4>会員は全体で <?php echo e(number_format($total_user)); ?>名で、<?php echo e(date('n')); ?>月は <?php echo e(number_format($new_users)); ?>名の会員が増えました。
                過去1年間で新規に登録した会員数は<?php echo e(number_format($before_users)); ?>名で、このうち<?php echo e(number_format($before_users_morebooking)); ?>%が2回以上の予約をしています。
            </h4>
        </div>
        
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
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                        

                                        <td style="vertical-align: baseline;"><a class="" href="<?php echo e(URL::to('members/' . $user->id)); ?>" title="詳細"><?php echo e(str_pad($user->id, 6, '0', STR_PAD_LEFT)); ?></a></td>
                                        <td class="hidden-xs" style="vertical-align: baseline;">
                                            <?php echo e($user->last_name); ?><?php echo e($user->first_name); ?>

                                        </td>
                                        <td class="hidden-xs" style="vertical-align: baseline;">
                                            <?php echo e($user->fur_last_name); ?><?php echo e($user->fur_first_name); ?>

                                        </td>
                                        <td style="vertical-align: baseline;" ><?php echo e($user->prefecture); ?></td>
                                        <td style="vertical-align: baseline;"><?php echo e($user->email); ?></td>
                                        <td style="vertical-align: baseline;"><?php echo e($user->phone); ?></td>
                                        <td style="vertical-align: baseline;" ><?php echo $user->store; ?></td>
                                        <td style="vertical-align: baseline;" ><?php echo e($user->last_use); ?></td>
                                        <td style="vertical-align: baseline;">
                                            <?php if($user->visit_count > 1): ?> リピ<?php echo e($user->visit_count); ?> <?php else: ?> 初 <?php endif; ?>
                                        </td>
                                        <td class="hidden-xs" style="vertical-align: baseline;"> <?php echo e(date('Y/n/j', strtotime($user->created_at))); ?> </td>
                                        <td style="vertical-align: baseline;">
                                            <label style="margin-bottom:0;">
                                                <a class="btn btn-sm btn-info btn-edit" href="<?php echo e(URL::to('/members/' . $user->id . '/edit')); ?>" title="編集">
                                                    <span class="hidden-xs hidden-sm">編集</span>
                                                </a>
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>

    <link href="<?php echo e(URL::to('/')); ?>/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <style>
        div.dataTables_wrapper {
            /*width: 1824px;*/
            margin: 0 auto;
        }
        .data-table thead tr th {
            white-space: nowrap;
        }
    </style>
    <?php echo $__env->make('scripts.admin.member', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminapp1', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>