<?php
    $user = Auth::user();
   $roles = $user->getroles();
   $role = $roles[0]->slug;

    $related_shop = \DB::table('admin_shop')
                    ->where('admin_id', $user->id)
                    ->orderby('shop_id')
                    ->first();
    $shop_id = is_null($related_shop)? 4 : $related_shop->shop_id;
?>

<style>
    .dropdown-submenu {
        position:relative;
    }
    .dropdown-submenu>.dropdown-menu {
        top:0;
        left:100%;
        /*margin-top:-6px;*/
        /*margin-left:-1px;*/
        -webkit-border-radius:0 6px 6px 6px;
        -moz-border-radius:0 6px 6px 6px;
        border-radius:0 6px 6px 6px;
    }
    .dropdown-submenu:hover>.dropdown-menu {
        display:block;
    }
    .dropdown-submenu>a:after {
        display:block;
        content:" ";
        float:right;
        width:0;
        height:0;
        border-color:transparent;
        border-style:solid;
        border-width:5px 0 5px 5px;
        border-left-color:#cccccc;
        margin-top:5px;
        margin-right:-10px;
    }
    .dropdown-submenu:hover>a:after {
        border-left-color:#ffffff;
    }
    .dropdown-submenu.pull-left {
        float:none;
    }
    .dropdown-submenu.pull-left>.dropdown-menu {
        left:-100%;
        margin-left:10px;
        -webkit-border-radius:6px 0 6px 6px;
        -moz-border-radius:6px 0 6px 6px;
        border-radius:6px 0 6px 6px;
    }

    /**/
    .ipad-logo{
        display: none !important;
    }
    @media  screen and  (max-width: 1024px){
    .desk-logo{
        display: none !important;
    }
    .ipad-logo{
        display: block !important;
    }
    }

    @media  screen and  (max-width: 768px){
    .task_booking ul{
        padding-left: 0 !important;
    }
    .task_booking ul li a{
        margin: 0 !important;
    }
    .navbar-header.task_icon{
        float: left;
    }
    .navbar-header.task_icon .navbar-brand{
        margin-left: 10px;
        margin-right: 15px;
    }
    .task_booking .bigusermenu {
        display: block !important;
    }
    .task_booking .bigusermenu .dropdown a{
        padding: 15px 0;
        font-size: 12px !important;
        line-height: 25px;
    }

    }


    @media  screen and (min-width: 768px) and (max-width: 1024px){
    .collapse.task_booking{
        display: block;
    }
    }
    /**/
</style>

<nav class="navbar navbar-static-top" role="navigation" >
    <div class="navbar-header task_icon">
        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
            <i class="fa fa-reorder"></i>
        </button>
        <a href="<?php echo e(URL::to('/')); ?>/admintop" class="navbar-brand">
            <img src="<?php echo e(URL::to('/')); ?>/images/hakoren-logo.png" class="img-responsive desk-logo" style="height:34px;margin-top:13px;"/>
            <img src="<?php echo e(URL::to('/')); ?>/img/hakoren-logo1.png" class="img-responsive ipad-logo" style="height:34px;margin-top:13px;"/>
        </a>
    </div>
    <div class="navbar-collapse collapse adminnavbar task_booking" id="navbar">
        <ul class="nav navbar-nav p28" style="">
            <?php if($role == 'admin'): ?>
                
                        
                
                <li>
                    <a href="<?php echo e(URL::to('/')); ?>/booking/task" >
                        <i class="fa fa-list-ol" aria-hidden="true" style="font-size: 19px;"></i>配車
                    </a>
                </li>
                <li>
                    <a href="#" data-toggle="dropdown">
                        <i class="fa fa-search" aria-hidden="true" style="font-size: 19px;"></i>予約
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo e(URL::to('/')); ?>/booking/search-plans">空車検索</a></li>
                        <li><a href="<?php echo e(URL::to('/')); ?>/booking/all">予約一覧</a></li>
                        <li><a href="https://docs.google.com/spreadsheets/d/1saI6LwZ997p_c_F2Jd4lQNLQ3zwUVDSP79BjLmLhQtM/edit#gid=1507390345" target="_blank">Gシート取込</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo e(URL::to('/')); ?>/carinventory/calendar/<?php echo e($shop_id); ?>" >
                        <i class="fa fa-calendar" aria-hidden="true" style="font-size: 19px;"></i>カレンダー
                    </a>
                    
                        
                        
                        
                        
                        
                        
                        
                    
                </li>
                <li>
                    <a href="#" data-toggle="dropdown">
                        <i class="fa fa-address-book-o" style="font-size: 16px;"></i>会員
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo e(URL::to('/')); ?>/members">会員一覧</a></li>
                        <li><a href="<?php echo e(URL::to('/settings/usergroup')); ?>">会員タグ</a></li>
                        <!--<li><a href="<?php echo e(URL::to('/')); ?>/tag">会員タグ</a></li>-->
                        <li><a><span style="padding:2px 5px; background:#ffa4a8;color:#af0008;font-size:10px; font-weight:400;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;">未</span> DM管理</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="dropdown">
                        <i class="fa fa-car" style="font-size: 19px;"></i>在庫
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo e(URL::to('/')); ?>/carinventory/inventory" >個別車両</a></li>
                        <li><a href="<?php echo e(URL::to('/')); ?>/carinventory/priority" >配車優先度</a></li>
                        
                            
                                
                            
                        
                        <li><a href="<?php echo e(URL::to('/')); ?>/carrepair" >車両検査</a></li>
                        
                    </ul>

                </li>
                <li>
                    <a href="#" data-toggle="dropdown">
                        <i class="fa fa-globe" style="font-size: 19px;"></i>Web
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo e(URL::to('/')); ?>/adminblog/blogposts">投稿</a></li>
                        <li><a href="<?php echo e(URL::to('/')); ?>/adminpage/webpages">ホームページ</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" data-toggle="dropdown">
                        <i class="fa fa-cog" style="font-size: 19px;"></i>設定
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo e(URL::to('/')); ?>/carbasic/carclass">車両管理</a></li>
                        <li><a href="<?php echo e(URL::to('/')); ?>/shopbasic/shop">店舗管理</a></li>
                        <li><a href="<?php echo e(URL::to('/')); ?>/mailtemplate">通知管理</a></li>
                        <li><a href="<?php echo e(URL::to('/')); ?>/settings/endusers">管理者</a></li>
                        <li><a href="<?php echo e(URL::to('/')); ?>/download_files">ダウンロード一覧</a></li>
                        <!--<li><a href="<?php echo e(URL::to('/')); ?>/settings/usergroup">User group</a></li>-->
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="dropdown">
                        <i class="fa fa-usd" style="font-size: 19px;"></i>売上管理
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo e(URL::to('/')); ?>/sales/salesmanagement">実際売上</a></li>
                        <li><a href="<?php echo e(URL::to('/')); ?>/sales/authmanagement">予約売上</a></li>
                    </ul>
                </li>
                
                
                
                
                


            <?php elseif($role == 'subadmin'): ?>
                
                        
                
            <?php endif; ?>
            <!--<li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle admindropdown" data-toggle="dropdown">
                    <i class="fa fa-address-book-o" style="font-size: 16px;"></i>dropdown<span class="caret"></span></a>
            </li>-->
        </ul>
        <ul class="nav navbar-top-links navbar-right bigusermenu">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle admindropdown" data-toggle="dropdown" role="button" aria-expanded="false">

                    <?php if((Auth::User()->profile) && Auth::user()->profile->avatar_status == 1): ?>
                            <img src="<?php echo e(Auth::user()->profile->avatar); ?>" alt="<?php echo e(Auth::user()->name); ?>" class="user-avatar-nav">
                    <?php else: ?>
                        <div class="user-avatar-nav"></div>
                    <?php endif; ?>
                    <?php echo e(Auth::user()->last_name); ?> <?php echo e(Auth::user()->first_name); ?>さん<span class="caret"></span>
                </a>
                <ul class="dropdown-menu adminmenu" role="menu">
                    <li <?php echo e(Request::is('profile/'.Auth::user()->name, 'profile/'.Auth::user()->name . '/edit') ? 'class=active' : null); ?>>
                        <?php echo HTML::link(url('/profile/'.Auth::user()->name), trans('titles.profile')); ?>

                    </li>
                    <li <?php echo e(Request::is('profile/'.Auth::user()->name, 'profile/'.Auth::user()->name . '/edit') ? 'class=active' : null); ?>>
                        <?php echo HTML::link(url('/profile/'.Auth::user()->name).'/setpassword', 'パスワード変更'); ?>

                    </li>
                    <li>
                        <a href="<?php echo e(url('logout')); ?>">
                            <?php echo trans('titles.logout'); ?>

                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
