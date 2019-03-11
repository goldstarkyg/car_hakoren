<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CarClass;
use App\Models\CarEquip;
use App\Models\CarModel;
use App\Models\Shop;
use App\Models\CarInventory;
use App\Models\Profile;
use App\Models\RoleUser;
use App\Models\Score;
use App\Models\User;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
use Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use jeremykenedy\LaravelRoles\Models\Permission;
use jeremykenedy\LaravelRoles\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Illuminate\Support\Facades\Route;
use App\Http\DataUtil\ServerPath;

use DB;
use DateTime;
use DatePeriod;
use DateInterval;

class CarCalendarController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $shop_id)
    {
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $startdate      = $request->get('startdate','');
        $enddate        = $request->get('enddate','');
        $class_id       = $request->get('class_id','');
        $smoke_select   = $request->get('smoke_select', 'both');
//        $classes        = CarClass::all();
        $shops          = Shop::all();
        $months         = []; // array('1','2','3','4','5','6','7','8','9','10','11','12');
        $year           = date("Y");
        $date1_1        = date("Y-01-01");
        for($k = 0; $k < 18; $k++) {
            $months[] = date('Y-n', strtotime($date1_1.' +'.$k.' months'));
        }
        // get classes related this shop
        $classes = CarClass::where('car_shop_name','=', $shop_id)->get();

        // get period date
        if($startdate == '')  {
            $d = new DateTime('first day of this month');
            $start = $d->format('Y/m/d');
            $startdate = $d->format('Y-n');
        }else {
//            $d = new DateTime($year.'/'.$startdate.'/01');
            $d = new DateTime($startdate.'-01');
//            $d->modify('first day of this month');
            $start = $d->format('Y/m/d');
        }
        $end  = date('Y/m/t', strtotime($start));
        $daysInMonth = (int)date('t', strtotime($start));
        $period = $this->createDateRange($start, $end,'n/j (N)');

        // get model and plate number from car class
        $carnames   = \DB::table('car_inventory as i')
                    ->leftjoin('car_class_model as ccm','ccm.model_id','=', 'i.model_id')
                    ->leftjoin('car_class as cc','cc.id','=', 'ccm.class_id')
                    ->leftjoin('car_model as cm' ,'cm.id','=','i.model_id')
                    ->select(\DB::raw("i.status, i.shortname, i.id as inventory_id,i.smoke,CONCAT_WS(i.numberplate3, i.numberplate4) as numberplate,cm.name as modelname,ccm.class_id as class_id,cc.car_shop_name"));
        if($class_id != '') {
            $carnames = $carnames->where('ccm.class_id', $class_id);
        }
        if($smoke_select != 'both') {
            $carnames = $carnames->where('i.smoke', $smoke_select);
        }
        $carnames   = $carnames
                    ->where('i.shop_id',$shop_id)
                    ->where('i.delete_flag', 0)
//                    ->where('i.status', 1)
                    ->where('cc.car_shop_name', $shop_id )
                    ->orderBy('cc.car_class_priority')
                    ->orderBy('ccm.priority')
                    ->orderBy('i.smoke', 'desc')
                    ->orderBy('i.priority')
                    ->get();

        $count = count($period);
        $first = date('Y-m-d', strtotime($start));
        $last = date('Y-m-d', strtotime($end));
//        $colors = ['#f98585','#fdf69b','#64c3ef','#7acd89','#b79ac6','#7acd89','#7acacd','#ca7acd','#cdca7a','#cd7a8f'];
        $statuses = [1,2,3,4,5,6,7,10]; //except 8=end, 9 = cancel

        $options = \DB::table('car_option')->orderby('type')->get();
        $option_names = [];
        $option_ids = [];

        foreach ($options as $opt) {
            $option_names[$opt->id] = ['abbr'=>$opt->abbriviation, 'name'=>$opt->name];
            $option_ids[] = $opt->id;
        }

        $used_car_indices = [];
        $count_active_cars = 0;
        $count_booked_dates = 0;
        $count_inspect_dates = 0;
        $count_subst1_dates = 0;
        $count_subst2_dates = 0;
        $count_booked_dates_part1 = 0;
        $count_inspect_dates_part1 = 0;
        $count_subst1_dates_part1 = 0;
        $count_subst2_dates_part1 = 0;

        $booking_count = 0;
        $booking_price_all = 0;

        foreach ($carnames as $key=>$car) {
            $car_id = $car->inventory_id;
            if(!in_array($car_id, $used_car_indices)) {
                $used_car_indices[] = $car_id;
            } else {
                unset($carnames[$key]);
                continue;
            }
            if($car->status == 1) $count_active_cars++;
            // get booking data of car in period
            $bookings = \DB::table('bookings as b')
                ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
                ->leftjoin('booking_status as bs','bs.status','=','b.status')
                ->where('b.inventory_id', $car->inventory_id)
                ->whereDate('b.departing', '<=', date('Y-m-d', strtotime($end)))
                ->whereDate('b.returning', '>=', date('Y-m-d', strtotime($start)))
                ->whereIn('b.status',$statuses)
                ->select(['b.*','ps.name as portal_name','bs.name as booking_status'])
                ->orderBy('b.id')->get();

            $car->booking = $bookings;

            $occupied = array_fill(0, $count, array('type'=>'', 'value'=>0, 'data'=>'', 'color'=>'', 'days'=>0, 'options'=>''));

            $today = date('Y-m-d');

            foreach ($bookings as $booking) {
                $bookings_price = \DB::table('bookings_price')->where('book_id', $booking->id)->get();
                $insurance1 = 0;
                $insurance2 = 0;
                foreach($bookings_price as $bo) {
                    $insurance1 += $bo->insurance1;
                    $insurance2 += $bo->insurance2;
                }
                if($booking->portal_flag == 0) {
                    $bgcolor = '#e737ff';
                } else {
                    if($booking->portal_id == null) {
                        $bgcolor = '#ddd';
                    } else {
                        $bgcolor = ServerPath::portalColor($booking->portal_id);
                        if($booking->portal_id == 10000 && $booking->language == 'en') $bgcolor = '#dc3483';
                    }
                }
                $depart = date('Y-m-d', strtotime($booking->departing));
                $return = date('Y-m-d', strtotime($booking->returning));

                if(strtotime($booking->returning_updated) > strtotime('1970-01-01 00:00:00'))
                    $return = date('Y-m-d', strtotime($booking->returning_updated));

                if(ServerPath::dateDiff($first, $depart) < 0 ) {
                    $depart = $first;
                }
                if(ServerPath::dateDiff($last, $return) > 0 ) {
                    $return = $last;
                }

                $days = ServerPath::dateDiff($depart, $return) + 1;
                $count_booked_dates += $days;
                if($return < $today) {
                    $count_booked_dates_part1 += ServerPath::dateDiff($depart, $return) + 1;
                } elseif($depart <= $today and $today <= $return) {
                    $count_booked_dates_part1 += ServerPath::dateDiff($depart, $today) + 1;
                }

                $pos = ServerPath::dateDiff($first, $depart);

                if($booking->portal_flag == '0') {
                    $user = \DB::table('users as u')
                        ->where('u.id', $booking->client_id)
                        ->leftjoin('profiles as p', 'u.id','=','p.user_id')
                        ->select(['u.first_name', 'u.last_name', 'p.fur_first_name', 'p.fur_last_name', 'p.phone'])
                        ->first();
                    if(is_null($user)) continue;
//                var_dump($user);
                    $username = $user->last_name.$user->first_name;
                    if($username == '') {
                        $username = $user->fur_last_name.$user->fur_first_name;
                    }
                    $userphone = $user->phone;
                }
                else {
                    $portal_info = $booking->portal_info;
                    if($portal_info == '') continue;
                    $portal_info = \GuzzleHttp\json_decode($portal_info);
                    $username = $portal_info->last_name.$portal_info->first_name;
                    if($username == '') {
                        $username = $portal_info->fu_last_name.$portal_info->fu_first_name;
                    }
                    $userphone = $portal_info->phone;
                }
                $timeline = date('H:i', strtotime($booking->departing)).'~'.date('H:i', strtotime($booking->returning));
                $repetedcount = \DB::table('users as u')
                    ->where('u.id', $booking->client_id)
                    ->first();
//                for($k = $pos; $k < $pos + $days; $k++){
                $occupied[$pos]['type'] = 'booking';
                $occupied[$pos]['value']      = 1;
                $occupied[$pos]['user_name']  = $username;
                $occupied[$pos]['timeline']   = $timeline;
                if(empty($booking->portal_id)) $booking->portal_id = 0;

                $occupied[$pos]['color']      = $bgcolor;
                $occupied[$pos]['days']       = $days;
                $occupied[$pos]['booking_id'] = $booking->id;
                $occupied[$pos]['phone']      = $userphone;
                if(is_null($repetedcount))
                    $occupied[$pos]['repeated'] = '非会員';
                else
                    $occupied[$pos]['repeated'] = '会員';
                if($booking->portal_id == 0 || $booking->portal_id == 10000 ) {
                    $occupied[$pos]['portal_name'] = ( $booking->language == 'ja')? '自社HP' : '自社HPEN' ;
                }else if($booking->portal_id == '10001'){
                    $occupied[$pos]['portal_name'] = '自社HPAD';
                }else {
                    $occupied[$pos]['portal_name'] = $booking->portal_name;
                }
                $occupied[$pos]['booking_status'] = $booking->booking_status;
                $occupied[$pos]['insurance1']     = intval($booking->insurance1)+$insurance1;
                $occupied[$pos]['insurance2']     = intval($booking->insurance2)+$insurance2;
                $occupied[$pos]['payment']        = $booking->payment;
                $occupied[$pos]['smoke']          = $car->smoke;
                $occupied[$pos]['paidamount']     = $this->paidmount($booking->id) ;
                $occupied[$pos]['unpaidamount']   = $this->unpaidmount($booking->id) ;

                $popt_str = '';
                $poptions = $booking->paid_options;
                if(!is_null($poptions)){
                    $paid_opt_ids = explode(',', $poptions);
                    $paid_opt_nums = explode(',', $booking->paid_option_numbers);
                    $tmp = [];
                    foreach ($paid_opt_ids as $key=>$pid){
                        if(array_key_exists ( $pid , $option_names )){
                            $tmp[] = $option_names[$pid]['abbr'].'('.$paid_opt_nums[$key].')';
                        }
                    }
                    $popt_str = implode(', ', $tmp);
                }

                $fopt_str = '';
                $foptions = $booking->free_options;
                if(!is_null($foptions)){
                    $free_opt_ids = explode(',', $foptions);
                    $tmp = [];
                    foreach ($free_opt_ids as $key=>$fid){
                        if(in_array($fid, $option_ids)) $tmp[] = $option_names[$fid]['abbr'];
                    }
                    $fopt_str = implode(', ', $tmp);
                }

                if($fopt_str != '') $popt_str = $popt_str. ', '. $fopt_str;
                $occupied[$pos]['options'] = $popt_str;

                if($pos < $count - 1){ // not last cell
                    for($k = $pos + 1; $k < $pos + $days; $k++){
                        unset($occupied[$k]);
                    }
                }
//                }
            }


            /***** get inspection data *****/
            // get inspections of car
            $inspections = \DB::table('car_inspections')
                ->where('inventory_id', $car_id)
                ->where('delete_flag','=', 0)
                ->where('status','<', 3)
                ->orderBy('id','desc')->get();

//            $ins_periods = array_fill(0, $count, array('type'=>'inspection', 'price'=>'', 'color'=>'', 'days'=>0, 'period'=>''));

            foreach($inspections as $ins){
                $ins_begin = $ins->begin_date;
                $ins_end = $ins->end_date;
                $flag1 = ServerPath::dateDiff($first, $ins_begin)>=0 && ServerPath::dateDiff($ins_begin, $last)>=0;
                $flag2 = ServerPath::dateDiff($first, $ins_end )>=0 && ServerPath::dateDiff($ins_end, $last)>=0;
                $flag3 = ServerPath::dateDiff($ins_begin,$first)>=0 && ServerPath::dateDiff($last, $ins_end)>=0;
                if($flag1 || $flag2 || $flag3){
                    if($ins_begin < $first) {
                        $ins_begin = $first;
                    }
                    if($ins_end > $last ) {
                        $ins_end = $last;
                    }
                    $days = ServerPath::dateDiff($ins_begin, $ins_end) + 1;
                    if($ins->kind == 1) {
                        $count_inspect_dates += $days;
                        if(ServerPath::dateDiff($ins_end, $today)>=0){
                            $count_inspect_dates_part1 += $days;
                        } elseif(ServerPath::dateDiff($ins_begin, $today)>=0 &&
                            ServerPath::dateDiff($today, $ins_end)>=0){
                            $count_inspect_dates_part1 += ServerPath::dateDiff($ins_begin, $today) + 1;
                        }
                    }
                    if($ins->kind == 2) {
                        $count_subst1_dates += $days;
                        if(ServerPath::dateDiff($ins_end, $today)>=0){
                            $count_subst1_dates_part1 += $days;
                        } elseif(ServerPath::dateDiff($ins_begin, $today)>=0 &&
                            ServerPath::dateDiff($today, $ins_end)>=0){
                            $count_subst1_dates_part1 += ServerPath::dateDiff($ins_begin, $today) + 1;
                        }
                    }
                    if($ins->kind == 3) {
                        $count_subst2_dates += $days;
                        if(ServerPath::dateDiff($ins_end, $today)>=0){
                            $count_subst2_dates_part1 += $days;
                        } elseif(ServerPath::dateDiff($ins_begin, $today)>=0 &&
                            ServerPath::dateDiff($today, $ins_end)>=0){
                            $count_subst2_dates_part1 += ServerPath::dateDiff($ins_begin, $today) + 1;
                        }
                    }

                    $pos = ServerPath::dateDiff($first, $ins_begin);
                    $kind = $ins->kind;
                    if($kind == 1) { $bgcolor = '#9c9c9c'; }
                    else if($kind == 2) { $bgcolor = '#e68658'; }
                    else { $bgcolor = '#e68658'; }

                    $occupied[$pos]['type'] = 'inspection';
                    $occupied[$pos]['id'] = $ins->id;
                    $occupied[$pos]['ins_id'] = $ins->inspection_id;
                    $occupied[$pos]['inspection'] = $ins;
                    $occupied[$pos]['car'] = CarInventory::find($ins->inventory_id);
                    $occupied[$pos]['color'] = $bgcolor;
                    $occupied[$pos]['days'] = $days;
                    $occupied[$pos]['period'] = date('m/d',strtotime($ins_begin)).'-'.date('m/d',strtotime($ins_end));

                    if($pos < $count - 1){ // not last cell
                        for($k = $pos + 1; $k < $pos + $days; $k++){
                            unset($occupied[$k]);
                        }
                    }
                }
            }

            $car->occupied = $occupied;
//            $car->inspections = $ins_periods;
            /***** get custom price data in period *****/
            // get main price of car
            $price = \DB::table('car_class_price')
                ->where('class_id', $car->class_id)
                ->orderBy('id','desc')->first();
//            var_dump($price);
            $customs = array_fill(0, $count, array('value'=>0, 'data'=>'', 'color'=>'', 'days'=>0));
            if(!empty($price)){
                $main_price = $price->d1_day1;
                // get custom price of car
                // SELECT * FROM car_class_price_custom WHERE class_id=1 AND startdate <= '2018-03-31' AND enddate>='2018-01-01'
                $temps = \DB::table('car_class_price_custom')
                    ->where('class_id', $car->class_id)
                    ->where('startdate','<=', date('Y-m-d', strtotime($end)))
                    ->where('enddate','>=', date('Y-m-d', strtotime($start)))
                    ->orderBy('startdate')->get();
//var_dump($temps); return;
                foreach ($temps as $temp) {
                    $cp_begin = date('Y-m-d', strtotime($temp->startdate));
                    $cp_end = date('Y-m-d', strtotime($temp->enddate));
                    if(ServerPath::dateDiff($first, $cp_begin) < 0 ) {
                        $cp_begin = $first;
                    }
                    if(ServerPath::dateDiff($last, $cp_end) > 0 ) {
                        $cp_end = $last;
                    }
                    $days = ServerPath::dateDiff($cp_begin, $cp_end) + 1;
                    $pos = ServerPath::dateDiff($first, $cp_begin);
                    $custom_price = $temp->d1_day1;

                    if($custom_price > $main_price) {
                        $percent = $temp->percent;
                        $updown = ' ▲';
                        $bgcolor = '#e0ebff';
                        $color = '#1155cc';
                    } else {
                        $percent = $temp->percent;
                        $updown = ' ▼';
                        $bgcolor = '#f3cbd3';
                        $color = '#cc4125';
                    }

                    for($k = $pos; $k < $pos + $days; $k++){
                        $customs[$k]['value'] = 1;
                        $customs[$k]['data'] = $percent.'%'.$updown;
                        $customs[$k]['color'] = $color;
                        $customs[$k]['bgcolor'] = $bgcolor;
                        $customs[$k]['days'] = $days;
                    }
                }
            }
            $car->customs = $customs;
        }

//        $total_bookable_dates = $count_active_cars * $daysInMonth;
        $total_bookable_dates = $count_active_cars * $daysInMonth       // fix at 2018/10/09
                                - $count_inspect_dates;
//                                - $count_subst1_dates
//                                - $count_subst2_dates;
        $daysInPart1 = (int)date('d');
        $total_bookable_dates_part1 = $count_active_cars * $daysInPart1     // fix at 2018/10/09
                                    -$count_inspect_dates_part1;
//                                    -$count_subst1_dates_part1
//                                    -$count_subst2_dates_part1;
        // count bookings to depart in month
        $bks = \DB::table('bookings as b')
            ->where('b.pickup_id', $shop_id)
            ->whereDate('b.departing', 'LIKE', '%'.date('Y-m', strtotime($start)).'%')
//                ->whereDate('b.departing', '>=', date('Y-m-d', strtotime($start)))
            ->whereNotIn('b.status', [9]);
        $booking_count = $bks->count();
        $booking_price_all = $bks->where(function ($query) {
            $query->where('pay_method','!=', '3')
                ->orWhereNull('pay_method');
        })->sum('payment');

        $data = [
            'route'         =>  $route  ,
            'subroute'      =>  $subroute  ,
            'startdate'     =>  $startdate ,
            'enddate'       =>  $enddate ,
            'smoke_select'  =>  $smoke_select ,
            'classes'       =>  $classes,
            'class_id'      =>  $class_id,
            'period'        =>  $period,
            'carnames'      =>  $carnames,
            'shops'         =>  $shops,
            'shop_id'       =>  $shop_id,
            'months'        =>  $months,
            'bookable_days' =>  $total_bookable_dates,
            'booked_days'   =>  $count_booked_dates,
            'inspect_days'  =>  $count_inspect_dates,
            'subst1_days'   =>  $count_subst1_dates,
            'subst2_days'   =>  $count_subst2_dates,
            'bookable_days_part1' =>  $total_bookable_dates_part1,
            'booked_days_part1'   =>  $count_booked_dates_part1,
            'inspect_days_part1'  =>  $count_inspect_dates_part1,
            'subst1_days_part1'   =>  $count_subst1_dates_part1,
            'subst2_days_part1'   =>  $count_subst2_dates_part1,
            'booking_count'       =>  $booking_count,
            'booking_price_all'   =>  $booking_price_all,
//            'paid_options'  => $paid_options,
//            'free_options'  => $free_options
//            'admin_shop_id' => $admin_shop_id
        ];

        return View('pages.admin.carinventory.calendar_index')->with($data);
    }

    //get paid amount
    public function paidmount($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            if($web->pay_status == '1') {
                $price += $web->basic_price;
                $price += $web->option_price;
                $price += $web->insurance1;
                $price += $web->insurance2;
                $price += $web->discount;
                $price += $web->etc_card;
                $price += $web->extend_payment;
                $price += $web->virtual_payment;
            }
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            if($ad->pay_status == '1') $price += $ad->total_price;
        }
        return $price;
    }
    //get unpaid price
    public function unpaidmount($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            if($web->pay_status == '0') {
                $price += $web->basic_price;
                $price += $web->option_price;
                $price += $web->insurance1;
                $price += $web->insurance2;
                $price += $web->discount;
                $price += $web->etc_card;
                $price += $web->extend_payment;
                $price += $web->virtual_payment;
            }
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            if($ad->pay_status == '0') $price += $ad->total_price;
        }
        return $price;
    }
    /**
     * Returns every date between two dates as an array
     * @param string $startDate the start of the date range
     * @param string $endDate the end of the date range
     * @param string $format DateTime format, default is Y-m-d
     * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
     */
    function createDateRange($startDate, $endDate, $format = "Y-m-d")
    {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->add(new DateInterval('P1D'));

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $da = $date->format($format);
            $new = '';
            if(strpos($da, '(1)')) $new = str_replace('(1)','<br/>月',$da);
            if(strpos($da, '(2)')) $new = str_replace('(2)','<br/>火',$da);
            if(strpos($da, '(3)')) $new = str_replace('(3)','<br/>水',$da);
            if(strpos($da, '(4)')) $new = str_replace('(4)','<br/>木',$da);
            if(strpos($da, '(5)')) $new = str_replace('(5)','<br/>金',$da);
            if(strpos($da, '(6)')) $new = str_replace('(6)','<br/><span style="color:#23e5f1">土</span>',$da);
            if(strpos($da, '(7)')) $new = str_replace('(7)','<br/><span style="color:#f95a5a">日</span>',$da);
            $range[] = $new;
        }

        return $range;
    }

}
