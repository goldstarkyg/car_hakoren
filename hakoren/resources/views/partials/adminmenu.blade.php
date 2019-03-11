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
@media screen and (max-width: 1024px){
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
@php $active_menu = '' @endphp
<div class="panel menu-left car_basic">
    <div class="list-group" id="#MainMenu">
        {{--<!--user management-->--}}
    @if(!empty($route))
        {{--<!--setting management-->--}}
        @if($route == 'settings' )
            <a href="{{URL::to('/')}}/settings/endusers" class="list-group-item" data-parent="#MainMenu">
                <label>管理者一覧</label>
            </a>
            <a href="{{URL::to('/')}}/settings/usergroup" class="list-group-item" data-parent="#MainMenu">
                <label>グループ管理</label>
            </a>
        @endif
        {{--<!--member mangement-->--}}
        @if($route == 'members' )
            <a href="{{URL::to('/')}}/members" class="list-group-item" data-parent="#MainMenu">
                <label>会員一覧</label>
            </a>
        @endif
        {{--<!--simpleform management-->--}}
        @if($route == 'simpleform')
            <a href="#huku" class="list-group-item" data-toggle="collapse" data-parent="#MainMenu">
               <label>福岡空港店</label>
            </a>
            @if($location == 'huku')
               <div class="collapse in" aria-expanded="true" id="huku">
            @else
              <div class="collapse" id="huku">
            @endif
               @if(!empty($status_list))
                   @foreach($status_list as $status)
                       @if($status_id == $status->id && $location == 'huku')
                              @php $active_menu = 'active_menu' @endphp
                       @else
                              @php $active_menu = '' @endphp
                       @endif
                    <a href="{{URL::to('/')}}/simpleform?status_id={{$status->id}}&location=huku" class="list-group-item {{$active_menu}}">
                        <label class="submenu"><i class="fa fa-caret-right" aria-hidden="true"></i> {{$status->name}}</label>
                    </a>
                   @endforeach
                @endif
            </div>
            <a href="#okina" class="list-group-item" data-toggle="collapse" data-parent="#MainMenu">
                <label>那覇空港店</label>
            </a>
            @if($location == 'okina')
                <div class="collapse in" aria-expanded="true" id="okina">
            @else
                <div class="collapse" id="okina">
            @endif
               @if(!empty($status_list))
                    @foreach($status_list as $status)
                            @if($status_id == $status->id && $location == 'okina')
                                @php $active_menu = 'active_menu' @endphp
                            @else
                                @php $active_menu = '' @endphp
                            @endif
                        <a href="{{URL::to('/')}}/simpleform?status_id={{$status->id}}&location=okina" class="list-group-item {{$active_menu}}  ">
                            <label class="submenu"><i class="fa fa-caret-right" aria-hidden="true"></i> {{$status->name}}</label>
                        </a>
                    @endforeach
               @endif
            </div>
        @endif

        {{--<!--car base menu-->--}}
        @if($route == 'carbasic' )
            <a href="{{URL::to('/')}}/carbasic/carclass" class="list-group-item @if($subroute == 'carclass' || $subroute == 'carclasspost') active_menu @endif " data-parent="#MainMenu">
                <label>車両クラス</label>
            </a>
            <a href="{{URL::to('/')}}/carbasic/carmodel" class="list-group-item @if($subroute == 'carmodel') active_menu @endif " data-parent="#MainMenu">
                <label>車両モデル</label>
            </a>
            <a href="{{URL::to('/')}}/carbasic/caroption" class="list-group-item @if($subroute == 'caroption')active_menu @endif " data-parent="#MainMenu">
                <label>オプション</label>
            </a>
            <a href="{{URL::to('/')}}/carbasic/cartype" class="list-group-item @if($subroute == 'cartype')active_menu @endif " data-parent="#MainMenu">
                <label>車両タイプ</label>
            </a>
            <a href="{{URL::to('/')}}/carbasic/carequip" class="list-group-item @if($subroute == 'carequip')active_menu @endif " data-parent="#MainMenu">
                <label>車両装備</label>
            </a>
            <a href="{{URL::to('/')}}/carbasic/carinsurance" class="list-group-item @if($subroute == 'carinsurance')active_menu @endif " data-parent="#MainMenu">
                <label>免責補償</label>
            </a>
            <a href="{{URL::to('/')}}/carbasic/carpassenger" class="list-group-item @if($subroute == 'passenger')active_menu @endif " data-parent="#MainMenu">
                <label>定員数</label>
            </a>
        @endif
        <!--shop basic menu-->
        @if($route == 'shopbasic')
            @foreach($shops as $shop)
                <a href="{{URL::to('/')}}/shopbasic/shop" class="list-group-item @if($subroute == 'shop')active_menu @endif " data-parent="#MainMenu">
                    <label>{{$shop->name}}</label>
                </a>
            @endforeach
        @endif
        
        @if($route == 'adminblog')
            <a href="{{URL::to('/')}}/adminblog/blogposts" class="list-group-item @if($subroute == 'blogposts')active_menu @endif " data-parent="#MainMenu">
                <label>投稿一覧</label>
            </a>                                  
            <a href="{{URL::to('/')}}/adminblog/posttags" class="list-group-item @if($subroute == 'posttags')active_menu @endif " data-parent="#MainMenu">
                <label>記事タグ</label>
            </a>            
            <a href="{{URL::to('/')}}/adminblog/blogtags" class="list-group-item @if($subroute == 'blogtags')active_menu @endif " data-parent="#MainMenu">
                <label>タグ管理</label>
            </a>                      
             
        @endif        

        <!--car invnetory-->
        @if($route == 'carinventory')
            <a href="{{URL::to('/')}}/carinventory/inventory" class="list-group-item @if($subroute == 'inventory')active_menu @endif " data-parent="#MainMenu">
                <label>在庫一覧</label>
            </a>
            <a href="{{URL::to('/')}}/carinventory/priority" class="list-group-item @if($subroute == 'priority')active_menu @endif " data-parent="#MainMenu">
                <label>モデル内優先度</label>
            </a>
            @foreach($shops as $shop)
                <a href="{{URL::to('/')}}/carinventory/calendar/{{$shop->id}}" class="list-group-item @if($shop_id == $shop->id)active_menu @endif " data-parent="#MainMenu">
                    <label>{{$shop->name}}</label>
                </a>
            @endforeach
            {{--<a href="{{URL::to('/')}}/carinventory/calendar" class="list-group-item @if($subroute == 'calendar')active_menu @endif " data-parent="#MainMenu">--}}
                {{--<label>Calendar</label>--}}
            {{--</a>--}}
        @endif

        <!--car repair-->
        @if($route == 'carrepair')
            <a href="{{URL::to('/')}}/carrepair/repair" class="list-group-item @if($subroute == 'repair')active_menu @endif " data-parent="#MainMenu">
                <label>車両修理</label>
            </a>
        @endif

        <!-- booking menu-->
        @if($route == 'booking' )
            <a href="{{URL::to('/')}}/booking/all" class="list-group-item @if($subroute == 'all') active_menu @endif " data-parent="#MainMenu">
                <label>全て</label>
            </a>
            {{--<a href="{{URL::to('/')}}/booking/today" class="list-group-item @if($subroute == 'today') active_menu @endif " data-parent="#MainMenu">--}}
                {{--<label>本日</label>--}}
            {{--</a>--}}
            {{--<a href="{{URL::to('/')}}/booking/tomorrow" class="list-group-item @if($subroute == 'tomorrow')active_menu @endif " data-parent="#MainMenu">--}}
                {{--<label>明日</label>--}}
            {{--</a>--}}
            {{--<a href="{{URL::to('/')}}/booking/new/0" class="list-group-item @if($subroute == 'new')active_menu @endif " data-parent="#MainMenu">--}}
                {{--<label>新規予約</label>--}}
            {{--</a>--}}
            <a href="{{URL::to('/')}}/booking/task" class="list-group-item @if($subroute == 'task')active_menu @endif " data-parent="#MainMenu">
                <label>タスク表</label>
            </a>
        @endif

    @else
        <a href="#" class="list-group-item" data-toggle="collapse" data-parent="#MainMenu">
            <label>ハコレンタカー管理者パネル</label>
        </a>
    @endif
    </div>
</div>
