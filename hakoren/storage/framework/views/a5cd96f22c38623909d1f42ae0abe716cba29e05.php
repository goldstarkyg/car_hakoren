<?php
    if(!empty($route)) {
      $route = explode("/",$route)[0];
    }
    if(empty($status_id)) $status_id =1;
?>
<style>
.menu-left {
    margin-left: 20px;
    margin-right: 20px;
    padding: 0px;;
 }
 .submenu {
     padding-left: 20px;;
 }
 .active_menu {
     background-color: #ececec;
 }
/**/
@media  screen and (max-width: 1024px){
.car_wrap{
    width: 100%;
    display: inline-block;
}
.car_wrap .menu-left {
    margin-left: 0px;
    margin-right: 0px;
}
}
/**/


</style>
<?php  $active_menu = ''  ?>
<div class="panel menu-left car_basic">
    <div class="list-group" id="#MainMenu">
        
    <?php if(!empty($route)): ?>
        
        <?php if($route == 'settings' ): ?>
            <a href="<?php echo e(URL::to('/')); ?>/settings/endusers" class="list-group-item" data-parent="#MainMenu">
                <label>管理者一覧</label>
            </a>
            <a href="<?php echo e(URL::to('/')); ?>/settings/usergroup" class="list-group-item" data-parent="#MainMenu">
                <label>グループ管理</label>
            </a>
        <?php endif; ?>
        
        <?php if($route == 'members' ): ?>
            <a href="<?php echo e(URL::to('/')); ?>/members" class="list-group-item" data-parent="#MainMenu">
                <label>会員一覧</label>
            </a>
        <?php endif; ?>
        
        <?php if($route == 'simpleform'): ?>
            <a href="#huku" class="list-group-item" data-toggle="collapse" data-parent="#MainMenu">
               <label>福岡空港店</label>
            </a>
            <?php if($location == 'huku'): ?>
               <div class="collapse in" aria-expanded="true" id="huku">
            <?php else: ?>
              <div class="collapse" id="huku">
            <?php endif; ?>
               <?php if(!empty($status_list)): ?>
                   <?php $__currentLoopData = $status_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                       <?php if($status_id == $status->id && $location == 'huku'): ?>
                              <?php  $active_menu = 'active_menu'  ?>
                       <?php else: ?>
                              <?php  $active_menu = ''  ?>
                       <?php endif; ?>
                    <a href="<?php echo e(URL::to('/')); ?>/simpleform?status_id=<?php echo e($status->id); ?>&location=huku" class="list-group-item <?php echo e($active_menu); ?>">
                        <label class="submenu"><i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo e($status->name); ?></label>
                    </a>
                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <a href="#okina" class="list-group-item" data-toggle="collapse" data-parent="#MainMenu">
                <label>那覇空港店</label>
            </a>
            <?php if($location == 'okina'): ?>
                <div class="collapse in" aria-expanded="true" id="okina">
            <?php else: ?>
                <div class="collapse" id="okina">
            <?php endif; ?>
               <?php if(!empty($status_list)): ?>
                    <?php $__currentLoopData = $status_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($status_id == $status->id && $location == 'okina'): ?>
                                <?php  $active_menu = 'active_menu'  ?>
                            <?php else: ?>
                                <?php  $active_menu = ''  ?>
                            <?php endif; ?>
                        <a href="<?php echo e(URL::to('/')); ?>/simpleform?status_id=<?php echo e($status->id); ?>&location=okina" class="list-group-item <?php echo e($active_menu); ?>  ">
                            <label class="submenu"><i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo e($status->name); ?></label>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <?php if($route == 'carbasic' ): ?>
            <a href="<?php echo e(URL::to('/')); ?>/carbasic/carclass" class="list-group-item <?php if($subroute == 'carclass' || $subroute == 'carclasspost'): ?> active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>車両クラス</label>
            </a>
            <a href="<?php echo e(URL::to('/')); ?>/carbasic/carmodel" class="list-group-item <?php if($subroute == 'carmodel'): ?> active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>車両モデル</label>
            </a>
            <a href="<?php echo e(URL::to('/')); ?>/carbasic/caroption" class="list-group-item <?php if($subroute == 'caroption'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>オプション</label>
            </a>
            <a href="<?php echo e(URL::to('/')); ?>/carbasic/cartype" class="list-group-item <?php if($subroute == 'cartype'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>車両タイプ</label>
            </a>
            <a href="<?php echo e(URL::to('/')); ?>/carbasic/carequip" class="list-group-item <?php if($subroute == 'carequip'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>車両装備</label>
            </a>
            <a href="<?php echo e(URL::to('/')); ?>/carbasic/carinsurance" class="list-group-item <?php if($subroute == 'carinsurance'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>免責補償</label>
            </a>
            <a href="<?php echo e(URL::to('/')); ?>/carbasic/carpassenger" class="list-group-item <?php if($subroute == 'passenger'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>定員数</label>
            </a>
        <?php endif; ?>
        <!--shop basic menu-->
        <?php if($route == 'shopbasic'): ?>
            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(URL::to('/')); ?>/shopbasic/shop" class="list-group-item <?php if($subroute == 'shop'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                    <label><?php echo e($shop->name); ?></label>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        
        <?php if($route == 'adminblog'): ?>
            <a href="<?php echo e(URL::to('/')); ?>/adminblog/blogposts" class="list-group-item <?php if($subroute == 'blogposts'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>投稿一覧</label>
            </a>                                  
            <a href="<?php echo e(URL::to('/')); ?>/adminblog/posttags" class="list-group-item <?php if($subroute == 'posttags'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>記事タグ</label>
            </a>            
            <a href="<?php echo e(URL::to('/')); ?>/adminblog/blogtags" class="list-group-item <?php if($subroute == 'blogtags'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>タグ管理</label>
            </a>                      
             
        <?php endif; ?>        

        <!--car invnetory-->
        <?php if($route == 'carinventory'): ?>
            <a href="<?php echo e(URL::to('/')); ?>/carinventory/inventory" class="list-group-item <?php if($subroute == 'inventory'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>在庫一覧</label>
            </a>
            <a href="<?php echo e(URL::to('/')); ?>/carinventory/priority" class="list-group-item <?php if($subroute == 'priority'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>モデル内優先度</label>
            </a>
            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(URL::to('/')); ?>/carinventory/calendar/<?php echo e($shop->id); ?>" class="list-group-item <?php if($shop_id == $shop->id): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                    <label><?php echo e($shop->name); ?></label>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
                
            
        <?php endif; ?>

        <!--car repair-->
        <?php if($route == 'carrepair'): ?>
            <a href="<?php echo e(URL::to('/')); ?>/carrepair/repair" class="list-group-item <?php if($subroute == 'repair'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>車両修理</label>
            </a>
        <?php endif; ?>

        <!-- booking menu-->
        <?php if($route == 'booking' ): ?>
            <a href="<?php echo e(URL::to('/')); ?>/booking/all" class="list-group-item <?php if($subroute == 'all'): ?> active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>全て</label>
            </a>
            
                
            
            
                
            
            
                
            
            <a href="<?php echo e(URL::to('/')); ?>/booking/task" class="list-group-item <?php if($subroute == 'task'): ?>active_menu <?php endif; ?> " data-parent="#MainMenu">
                <label>タスク表</label>
            </a>
        <?php endif; ?>

    <?php else: ?>
        <a href="#" class="list-group-item" data-toggle="collapse" data-parent="#MainMenu">
            <label>ハコレンタカー管理者パネル</label>
        </a>
    <?php endif; ?>
    </div>
</div>
