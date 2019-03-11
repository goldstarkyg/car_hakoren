<?php $__env->startSection('template_title'); ?>
    メールテンプレート
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
        .wrapper.wrapper-content{
            padding: 0px !important;
            padding-top:40px !important;
        }
        .container{
            padding:0px !important;
        }
        .tag {
            border: 1px solid #1ab394;
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            display: block;
            float: left;
            padding: 0px 5px;
            margin-right:3px;
            text-decoration: none;
            background: #1ab394;
            color: #fff;
            font-family: helvetica;
            font-size: 13px;
            margin-left: 0em !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        記事投稿時の通知｜文章テンプレート
                        <a href="/mailtemplate/new" class="btn btn-sm btn-success pull-right" style="">新しく作る</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                <tr>
                                    
                                    <th>メールタイプ</th>
                                    <th>件名</th>
                                    <th>差出人</th>
                                    <th style="width:40%">内容</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    /*if($template->catname == 'ワクチンQ&A'){
                                        $template->catname = 'その他の情報';
                                    }*/
                                    ?>
                                    <tr>
                                        <td><?php echo e($template->mailname); ?></td>
                                        <td><?php echo e($template->subject); ?></td>
                                        <td><?php echo e($template->sender); ?></td>
                                        <td><?php echo $template->content; ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-info btn-block" href="/mailtemplate/<?php echo e($template->id); ?>/edit">
                                                <i class="fa fa-pencil fa-fw" aria-hidden="true"></i>
                                                <span>編集する</span>
                                            </a>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>

    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.tooltips', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp_calendar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>