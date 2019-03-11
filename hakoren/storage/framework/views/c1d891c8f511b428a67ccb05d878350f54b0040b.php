<?php $__env->startSection('template_title'); ?>
    個別車両一覧
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<?php $__env->stopSection(); ?>
<?php $service = app('App\Http\Controllers\CarInventoryController'); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .btn-circle_active {
            width: 10px;
            height: 10px;
            padding: 3px 0;
            border-radius: 5px;
            margin-left: 5px;
            background-color: #13e313;
            position: absolute;
            margin-top: 5px;;
        }
        .btn-circle_inactive {
            width: 10px;
            height: 10px;
            padding: 3px 0;
            border-radius: 15px;
            margin-left: 5px;
            background-color:#f44336;
            position: absolute;
            margin-top: 5px;;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2 style="display: inline-block;margin-right:30px;">個別車両一覧</h2>
                <form class="form-inline" method="post" name="search" id="search" action="<?php echo e(URL::to('/')); ?>/carinventory/inventory">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label for="email">店舗&emsp;</label>
                        <select name="search_shop" id="search_shop" class="form-control" onchange="changeShop()">
                            
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($shop_id == $shop->id): ?> <?php echo e("selected"); ?> <?php endif; ?> value="<?php echo e($shop->id); ?>"><?php echo e($shop->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        &emsp;<label for="email">車両クラス&emsp;</label>
                        <select name="search_class" id="search_class" class="form-control" onchange="searchBook()">
                            <option value="all">すべて</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($class_id == $cls->id): ?> <?php echo e("selected"); ?> <?php endif; ?>  class="car_class" shop="<?php echo e($cls->car_shop_name); ?>" value="<?php echo e($cls->id); ?>"><?php echo e($cls->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </form>

                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="<?php echo e(URL::to('/')); ?>/carinventory/inventory/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
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
                        <div class="clearfix"></div>
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table" width="100%">
                                <thead>
                                <tr>
                                    <th>車両番号</th>
                                    
                                    <th>モデル</th>
                                    <th>略称</th>
                                    
                                    <th>所属店舗</th>
                                    <th>禁煙?</th>
                                    <th>最大乗車人数</th>
                                    <th> </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $invens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inven): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr align="middle" >
                                        <td style="vertical-align:middle;">
                                            <?php echo e($inven->numberplate1); ?> <?php echo e($inven->numberplate2); ?> <?php echo e($inven->numberplate3); ?><br/><?php echo e($inven->numberplate4); ?>

                                            <?php if($inven->status == '1'): ?>
                                                <label class="btn-circle_active"></label>
                                            <?php else: ?>
                                                <label class="btn-circle_inactive"></label>
                                            <?php endif; ?>
                                        </td>
                                        
                                            
                                        
                                        <td style="vertical-align: middle;"><?php echo e($inven->model_name); ?></td>
                                        <td style="vertical-align: middle;"><?php echo e($inven->shortname); ?></td>
                                        
                                        <td style="vertical-align: middle;"><?php echo e($inven->shop_name); ?></td>
                                        <td style="vertical-align: middle;"><?php if($inven->smoke ==1): ?> 喫煙 <?php else: ?> 禁煙 <?php endif; ?></td>
                                        <td style="vertical-align: middle;"><?php echo e($inven->max_passenger); ?></td>
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="<?php echo e(URL::to('/carinventory/inventory/' . $inven->id)); ?>" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="<?php echo e(URL::to('/carinventory/inventory/' . $inven->id . '/edit')); ?>" title="編集">
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


<script type="text/javascript">
    function changeShop() {
        $('#search_class').val('all');
        searchBook();
    }
    function searchBook() {
        $('#search').submit();
    }

    $('#search_shop').change( function () {
        var shop = $(this).val();
        $('.car_class').hide();
        $('.car_class[shop="'+ shop +'"]').show();
    });
</script>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <?php echo $__env->make('scripts.admin.carinventory.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminapp', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>