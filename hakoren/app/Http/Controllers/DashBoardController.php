<?php
/**
 * Created by PhpStorm.
 * User: hh
 * Date: 6/4/2018
 * Time: 7:02 PM
 */

namespace App\Http\Controllers;

use App;
use phpDocumentor\Reflection\Types\Null_;
use Session;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Profile;
use App\Models\RoleUser;
use App\Models\Score;
use App\Models\User;
use App\Models\Shop;
use App\Models\CarOption;
use App\Models\CarPassengerTags;
use App\Models\CarInventory;
use App\Models\CarModel;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
use App\Http\DataUtil\ServerPath;
use Auth;
use Faker\Provider\zh_TW\DateTime;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use jeremykenedy\LaravelRoles\Models\Permission;
use jeremykenedy\LaravelRoles\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Illuminate\Support\Facades\Route;

use DB;
use Response;
use PDF;
use File;

class DashBoardController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function admintop(Request $request)
    {
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $user = Auth::user();
        $current_date = date("Y-m-d");
        $shops = Shop::all();
        $shop_id = '0';
        $related_shop = \DB::table('admin_shop')->where('admin_id', $user->id)->first();
        $user_shop_id = 0;
        if(!empty($related_shop))
            $user_shop_id = $related_shop->shop_id;
        if($request->has('shop_id')) {
            $shop_id = intval($request->input('shop_id'));
        }else {
            $shop_id = $user_shop_id;
        }
        $shop_kind = 'both';
        $shopname = \DB::table('car_shop')->where('id',$shop_id)->first();
        if(!empty($shopname)) {
            if($shopname->shop_number == '1') $shop_kind = 'fuku';
            if($shopname->shop_number == '2') $shop_kind = 'okina';
            if($shopname->shop_number == '3') $shop_kind = 'kagoshima';

        }

        $quick_check    = $this->quickCheck($current_date, $shop_id);
       
        $realsalechart      = $this->realsalechart($current_date, $shop_id);
        $realamountchart    = $this->realamountchart($current_date, $shop_id);
        $temporalchart  = $this->temporalchart($current_date, $shop_id);
        $temporalamount  = $this->bookingamountchart($current_date, $shop_id);
        $expectedchart  = $this->ecpectedchart($current_date, $shop_id);
        $realsalechart[] = $expectedchart[1];
        $expectedamount  = $this->expectedamountchart($current_date, $shop_id);
        $realamountchart[] = $expectedamount[0];
        $todaysalesmount    = $this->todaySalesMount($current_date, $shop_id);
        $todayinsurance = $this->todayInsurance($current_date, $shop_id);
        $todayoption    = $this->todayOption($current_date, $shop_id);
        $todaybooking   = $this->todayBooking($current_date, $shop_id);
        $todayportalbooking = $this->todayPortalBooking($current_date, $shop_id);
        $monthsalechart  = $this->monthsalechart($current_date, $shop_id);
        $monthbookingamount = $this->monthbookingamountchart($current_date, $shop_id);
        $monthportalbooking = $this->monthbookingportalamount($current_date, $shop_id);
//        var_dump($monthportalbooking); exit();
        //echo json_encode($monthbookingamount); return;
        $data = [
            'route'         => $route  ,
            'subroute'      => $subroute  ,
            'shops'         => $shops,
            'shop_id'       => $shop_id,
            'quick_check'   => $quick_check,
            'realsalechart'     => $realsalechart,
            'realamountchart'   => $realamountchart,
            'temporalchart'     => $temporalchart,
            'temporalamount'    => $temporalamount,
            'expectedchart'     => $expectedchart,
            'expectedamount'    => $expectedamount,
            'today_salesmount'  => $todaysalesmount,
            'todayinsurance'    => $todayinsurance,
            'todayoption'       => $todayoption,
            'todaybooking'      => $todaybooking,
            'todayportalbooking'  => $todayportalbooking,
            'monthsalechart'    => $monthsalechart,
            'monthbookingamount' => $monthbookingamount,
            'monthportalbooking' => $monthportalbooking,
            'shop_kind'         => $shop_kind
        ];
        return view('pages.admin.admintop')->with($data);
    }

    public function quickCheck($current_date, $shop_id) {
        $ret = array();
        $ret['current_date'] = date('n',strtotime($current_date))."月 ".date('j',strtotime($current_date))."日";
        $ret['current_day']  = $this->getday($current_date);
        $ret['expected_sales'] = $this->getexpectedsaleschart($current_date, $shop_id);
        $ret['depart_count']   = $this->getdepartreturncount($current_date, 'depart', $shop_id);
        $ret['return_count']   = $this->getdepartreturncount($current_date, 'return', $shop_id);
        $ret['cancel_count']   = $this->getCancelMount($current_date,$shop_id);
        $ret['real_depart_count'] = $this->getrealdepartreturnamount($current_date, 'depart', $shop_id);
        $ret['real_return_count'] = $this->getrealdepartreturnamount($current_date, 'return', $shop_id);
        $ret['created_count']   = $this->getcreatedcount($current_date, $shop_id);
        $current_month = date('Y-m', strtotime($current_date));
        $ret['cu_month'] = $current_month;
        $ret['cu_month_usedcar'] = $this->getusedcarmonth($current_month, $shop_id);
        $ret['cu_month_depart_count']   = $this->getdepartreturncount($current_month, 'depart', $shop_id);
        $next_month_date = date('Y-m-d', strtotime('first day of +1 month'));
        $next_month = date('Y-m', strtotime($next_month_date));
        $ret['next_month'] = $next_month;
        $ret['next_month_usedcar'] = $this->getusedcarmonth($next_month, $shop_id);
        $ret['next_month_depart_count']   = $this->getdepartreturncount($next_month, 'depart', $shop_id);
        $next_next_month_date = date('Y-m-d', strtotime('first day of +2 month'));
        $next_next_month = date('Y-m', strtotime($next_next_month_date));
        $ret['next_next_month'] = $next_next_month;
        $ret['next_next_month_usedcar'] = $this->getusedcarmonth($next_next_month, $shop_id);
        $ret['next_next_month_depart_count']   = $this->getdepartreturncount($next_next_month, 'depart', $shop_id);
        $ret['month_expected_sales'] = $this->getexpectedsaleschart($current_month, $shop_id);
        $ret['month_real_sales']     = $this->getrealsalesmonthloop($current_month, $shop_id);
        return $ret;
    }
    public static function quickCheckSchedule($current_date, $shop_id , $class_id) {
        $ret = array();
            $current_month = date('Y-m', strtotime($current_date));
            $ret['cu_month'] = date('m', strtotime($current_date));
            $cars = DashBoardController::getusedcarmonthSchedule($current_month, $shop_id , $class_id);
            $ret['cu_month_usedcar'] = $cars['total_percent'];
            $ret['cu_month_all_cells'] = $cars['all_cells'];
            $ret['cu_month_active_cells'] = $cars['active_cells'];
            $ret['cu_month_booking_cells'] = $cars['booking_cells'];
            $ret['cu_month_depart_count'] = DashBoardController::getdepartreturncountSchedule($current_month, 'depart', $shop_id , $class_id);
            $next_month_date = date('Y-m-d', strtotime('first day of +1 month'));
            $next_month = date('Y-m', strtotime($next_month_date));
            $ret['next_month'] = date('m', strtotime($next_month_date));
            $cars_next = DashBoardController::getusedcarmonthSchedule($next_month, $shop_id , $class_id);
            $ret['next_month_usedcar'] =  $cars_next['total_percent'];
            $ret['next_month_all_cells'] = $cars_next['all_cells'];
            $ret['next_month_active_cells'] = $cars_next['active_cells'];
            $ret['next_month_booking_cells'] = $cars_next['booking_cells'];
            $ret['next_month_depart_count'] = DashBoardController::getdepartreturncountSchedule($next_month, 'depart', $shop_id, $class_id);
        return $ret;
    }
    public function getday($date) {
        $day = date('N',strtotime($date));
        $ret = '';
        switch ($day) {
            case '1' :
             $ret = '月';
             break;
            case '2' :
                $ret = '火';
                break;
            case '3' :
                $ret = '水';
                break;
            case '4' :
                $ret = '木';
                break;
            case '5' :
                $ret = '金';
                break;
            case '6' :
                $ret = '土';
                break;
            case '7' :
                $ret = '日';
                break;
        }
        return $ret;
    }

    //get temporl sales
    public function gettemporalsales($date ,  $shop_id) {
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);

        $bookings = $bookings->where('created_at', 'LIKE', '%' . $date . '%');

        $booking_portal_1 = clone  $bookings;
        $booking_portal_1 = $booking_portal_1
            ->where('portal_flag','1')
            ->where('payment','0')
            ->select(DB::raw("SUM(web_payment) as sumpayment"))->first();
        $price += $booking_portal_1->sumpayment;

        $booking_portal_2 = clone  $bookings;
        $booking_portal_2 = $booking_portal_2
            ->where('portal_flag','1')
            ->where('payment','!=','0')
            ->select(DB::raw("SUM(payment) as sumpayment"))->first();
        $price += $booking_portal_2->sumpayment;

        $booking_portal_3 = clone  $bookings;
        $booking_portal_3 = $booking_portal_3
            ->where('portal_flag','0')
            ->select(DB::raw("SUM(payment) as sumpayment"))->first();
        $price += $booking_portal_3->sumpayment;

        $booking_portal_4 = clone  $bookings;
        $booking_portal_4 = $booking_portal_4
            ->select(DB::raw("SUM(given_points) as sumpayment"))->first();
        $price += $booking_portal_4->sumpayment;

        $bookings_price = \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id', '=', 'pr.book_id')
            ->whereIn('bo.status', $statuses)
            ->where('pr.created_at', 'LIKE', '%' . $date . '%');
        if ($shop_id != 0)
            $bookings_price = $bookings_price->where('bo.pickup_id', '=', $shop_id);

        $bookings_price = $bookings_price
                            ->select(DB::raw("SUM(pr.total_price) as sumpayment"))
                            ->first();
        $price += $bookings_price->sumpayment;
        return $price;
    }
    //get temporl sales (please check web payment on portal if this takes web payment)
    public function getcancelsales($date ,  $shop_id) {
        $price = 0;
        $statuses = array("9");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);
        $bookings_pay = clone $bookings;
        $bookings_pay = $bookings_pay->where('canceled_at', 'LIKE', '%' . $date . '%')
            ->select(DB::raw("SUM(payment) as sumpayment"))->first();
        $price += $bookings_pay->sumpayment;

        $bookings_given_point = clone $bookings;
        $bookings_given_point = $bookings_given_point->where('canceled_at', 'LIKE', '%' . $date . '%')
            ->select(DB::raw("SUM(given_points) as sumpayment"))->first();
        $price += $bookings_given_point->sumpayment;

//        $bookings_virtual = clone $bookings;
//        $bookings_virtual = $bookings_virtual->where('canceled_at', 'LIKE', '%' . $date . '%')
//            ->select(DB::raw("SUM(virtual_payment) as sumpayment"))->first();
//        $price += $bookings_virtual->sumpayment;
        return $price;
    }
    //get temporal count
    public function gettemporalamount($date ,  $shop_id) {
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);

        $bookings = $bookings->where('created_at', 'LIKE', '%' . $date . '%')
            ->select(DB::raw('count(*) as paid_count'))->first();
        $price += $bookings->paid_count;

//        $bookings_price = \DB::table('bookings_price as pr')
//            ->leftjoin('bookings as bo', 'bo.id', '=', 'pr.book_id')
//            ->whereIn('bo.status', $statuses)
//            ->where('pr.created_at', 'LIKE', '%' . $date . '%');
//        if ($shop_id != 0)
//            $bookings_price = $bookings_price->where('bo.pickup_id', '=', $shop_id);
//
//        $bookings_price = $bookings_price
//            ->select(DB::raw('count(*) as paid_count'))
//            ->first();
//        $price += $bookings_price->paid_count;
        return $price;
    }

    //get cancel count
    public function getcancelamount($date ,  $shop_id) {
        $price = 0;
        $statuses = array("9");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);

        $bookings = $bookings->where('canceled_at', 'LIKE', '%' . $date . '%')
            ->select(DB::raw('count(*) as paid_count'))->first();
        $price += $bookings->paid_count;
        return $price;
    }

    //get booking count
    public function getexpectedamount($date ,  $shop_id) {
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);
        $bookingsde = clone $bookings;
        $bookingsde = $bookingsde->where('departing', 'LIKE', '%' . $date . '%')
            ->select(DB::raw('count(*) as paid_count'))->first();
        $price += $bookingsde->paid_count;

        return $price;
    }

     // Bai 's explain
    //if DEPARTING = today
    //if STATUS is not = 9
    //if PAY_METHOD is not =3
    public function getexpectedsaleschart($date ,$shop_id) {
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);
        $bookings_depart = clone  $bookings;
        $bookings_depart = $bookings_depart->where('departing', 'LIKE', '%' . $date . '%')
            ->where(function ($query) use($date){
                $query->where('pay_method','!=', '3')
                    ->orWhereNull('pay_method');
            })
            ->select(DB::raw("SUM(payment) as sumpayment"))->first();
        $price += $bookings_depart->sumpayment;
        return $price;
    }
    //get reservation price
    public static function getrealsales($date ,$shop_id) {
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->where('pay_method', '>', '0')
            ->where('pay_status','1');
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);

        $bookings_cancel = \DB::table('bookings');
        if($shop_id != 0)
            $bookings_cancel = $bookings_cancel->where('pickup_id','=', $shop_id);

        $pay_method = 4 ;
        $booking_portal = clone $bookings;
        $booking_portal = $booking_portal
            ->where('depart_task', '1')
            ->where(function ($query1) use ($pay_method) {
                $query1->where('pay_method', $pay_method)
                    ->orwhere('cancel_status', $pay_method);
            })
            ->where(function ($query2) use ($date) {
                $query2->where('departing','LIKE', '%'.$date.'%')
                    ->orwhere('cancel_date','LIKE', '%'.$date.'%');
            })
            ->select(DB::raw("SUM(web_payment) as sumpayment, sum(cancel_fee) as cancelsum"))->first();
        $price += $booking_portal->sumpayment + $booking_portal->cancelsum;

        $pay_method = 3;
        $booking_web = clone $bookings;
        $booking_web = $booking_web
            ->where(function ($query1) use ($pay_method) {
                $query1->where('pay_method', $pay_method)
                    ->orwhere('cancel_status', $pay_method);
            })
            ->where('paid_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(web_payment) as web_payment"))->first();
        $web_payment = $booking_web->web_payment;
        $booking_web_cancel = clone $bookings;
        $booking_web_cancel = $booking_web_cancel
            ->where('pay_method', $pay_method)
            ->where('cancel_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(cancel_total) as cancel_total"))->first();
        $web_cancel = $booking_web_cancel->cancel_total;
        $price += $web_payment - $web_cancel;
        //////////
        $pay_method = 1;
        $booking_cash = clone $bookings;
        $booking_cash = $booking_cash
            ->where('portal_flag', '0')
            ->where('depart_task', '1')
            ->where('pay_method', $pay_method)
            ->where('departing','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(payment) as payment"))->first();
        $price += $booking_cash->payment;

        $booking_cash2 = clone $bookings;
        $booking_cash2 = $booking_cash2
            ->where('portal_flag', '1')
            ->where('depart_task', '1')
            ->where('pay_method', $pay_method)
            //->where('paid_date','LIKE', '%'.$sel_date.'%')
            ->where('departing','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(payment) as payment"))->first();
        $price += $booking_cash2->payment;

        $booking_cash1 = clone $bookings_cancel;
        $booking_cash1 = $booking_cash1
            ->where(function ($query1) use($date) {
                $query1->where('cancel_status', '1')
                    ->orwhere('cancel_status', '5');
            })
            ->where('cancel_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(cancel_fee) as cancel_fee"))->first();
        $price += $booking_cash1->cancel_fee;
        /////////////
        $pay_method = 2;
        $booking_credit = clone $bookings;
        $booking_credit = $booking_credit
            ->where('portal_flag','0')
            ->where('depart_task','1')
            ->where('pay_method', $pay_method)
            ->where('departing','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(payment) as payment"))->first();
        $price += $booking_credit->payment;

        $booking_credit1 = clone $bookings;
        $booking_credit1 = $booking_credit1
            ->where('portal_flag','1')
            ->where('depart_task','1')
            ->where('pay_method', $pay_method)
            //->where('paid_date','LIKE', '%'.$sel_date.'%')
            ->where('departing','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(payment) as payment"))->first();
        $price += $booking_credit1->payment;

        $booking_credit2 = clone $bookings_cancel;
        $booking_credit2 = $booking_credit2
            ->where('cancel_status', $pay_method)
            ->where('cancel_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(cancel_fee) as cancel_fee"))->first();
        $price += $booking_credit2->cancel_fee;

        //get gicven point
        $bookings_point = clone $bookings;
        $bookings_point = $bookings_point
            ->where('departing', 'LIKE', '%' . $date . '%')
            ->where('depart_task', '1')
            ->select(DB::raw("SUM(given_points) as sumpayment"))->first();
        $price += $bookings_point->sumpayment;
        ///////////////////get booking price_list
        $pay_method = 1 ;
        $booking_prices =  \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
            ->where('pr.pay_method',$pay_method)
            ->where('pr.pay_status','1')
            ->whereIn('bo.status', $statuses);
        if($shop_id != 0)
            $booking_prices = $booking_prices->where('bo.pickup_id','=', $shop_id);
        $bookings_price_depart  = clone $booking_prices;
        $bookings_price_depart  = $bookings_price_depart
            ->where('bo.departing','LIKE', '%'.$date.'%')
            ->where('bo.depart_task', '1')
            ->where('pr.price_type', '1')
            ->select(DB::raw("SUM(total_price) as sumpayment"))->first();
        $price += $bookings_price_depart->sumpayment;

        $bookings_price_return  = clone $booking_prices;
        $bookings_price_return  = $bookings_price_return
            ->where(function ($query1) use($date) {
                $query1->where(function($query2) use($date){
                    $query2 ->whereDate('bo.returning','>=', 'bo.returning_updated')
                        ->where('bo.returning','LIKE', '%'.$date.'%');
                })
                    ->orwhere(function($query4) use($date){
                        $query4 ->where('bo.returning_updated','0000-01-01 00:00:00')
                            ->where('bo.returning','LIKE', '%'.$date.'%');
                    })
                    ->orwhere(function($query3) use($date){
                        $query3 ->whereDate('bo.returning','<', 'bo.returning_updated')
                            ->where('bo.returning_updated','LIKE', '%'.$date.'%');
                    });
            })
            ->where('bo.return_task', '1')
            ->where('pr.price_type', '2')
            ->select(DB::raw("SUM(total_price) as sumpayment"))->first();
        $price += $bookings_price_return->sumpayment;
        /////////
        $pay_method = 2 ;
        $booking_prices =  \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
            ->where('pr.pay_method',$pay_method)
            ->where('pr.pay_status','1')
            ->whereIn('bo.status', $statuses);
        if($shop_id != 0)
            $booking_prices = $booking_prices->where('bo.pickup_id','=', $shop_id);
        $bookings_price_depart  = clone $booking_prices;
        $bookings_price_depart  = $bookings_price_depart
            ->where('bo.departing','LIKE', '%'.$date.'%')
            ->where('bo.depart_task', '1')
            ->where('pr.price_type', '1')
            ->select(DB::raw("SUM(total_price) as sumpayment"))->first();
        $price += $bookings_price_depart->sumpayment;

        $bookings_price_return  = clone $booking_prices;
        $bookings_price_return  = $bookings_price_return
            ->where(function ($query1) use($date) {
                $query1->where(function($query2) use($date){
                    $query2 ->whereDate('bo.returning','>=', 'bo.returning_updated')
                        ->where('bo.returning','LIKE', '%'.$date.'%');
                })
                    ->orwhere(function($query4) use($date){
                        $query4 ->where('bo.returning_updated','0000-01-01 00:00:00')
                            ->where('bo.returning','LIKE', '%'.$date.'%');
                    })
                    ->orwhere(function($query3) use($date){
                        $query3 ->whereDate('bo.returning','<', 'bo.returning_updated')
                            ->where('bo.returning_updated','LIKE', '%'.$date.'%');
                    });
            })
            ->where('bo.return_task', '1')
            ->where('pr.price_type', '2')
            ->select(DB::raw("SUM(total_price) as sumpayment"))->first();
        $price += $bookings_price_return->sumpayment;

        return $price;
    }
    //get real sale month
    public function getrealsalesmonthloop($date ,$shop_id) {
        $last_day = date("Y-m-t", strtotime($date));
        $last_number = intval(explode("-", $last_day)[2]);
        $price = 0;
        for ($d = 1; $d <= $last_number; $d++) {
            $date = date("Y-m", strtotime($date));
            $date = date('Y-m-d', strtotime($date."-".$d));
            $price += $this->getrealsales($date, $shop_id);
        }
        return $price;
    }

    public function getrealamount($date ,$shop_id) {
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->where('pay_method', '>', '0')
            ->where('depart_task', '1')
            ->whereIn('status',$statuses);

        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);

        $bookings = $bookings
            //->where('pay_method', '!=', '4')
            ->where('departing', 'LIKE', '%' . $date . '%')
            ->select(DB::raw('count(*) as paid_count'))->first();
        $price += $bookings->paid_count;
        ///////////////////get booking price_list
//        $bookings_price = \DB::table('bookings_price as pr')
//            ->leftjoin('bookings as bo', 'bo.id', '=', 'pr.book_id')
//            ->where('pr.pay_method','>','0')
//            ->where('pr.paid_date', 'LIKE', '%' . $date . '%')
//            ->whereIn('bo.status', $statuses);
//        if ($shop_id != 0)
//            $bookings_price = $bookings_price->where('bo.pickup_id', '=', $shop_id);
//        $bookings_price = $bookings_price
//            ->select(DB::raw('count(*) as paid_count'))->first();
//        $price += $bookings_price->paid_count;
        return $price;
    }
    //get depart count
    public function getdepartreturncount($date ,$cond, $shop_id) {
        $count = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);        
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        if($cond == 'depart') {
            $bookings = $bookings->where('departing','LIKE', '%'.$date.'%');
        }
        if($cond == 'return') {
            $bookings = $bookings->where(function ($query) use($date) {
                                    $query->where(function ($query2) use ($date) {
                                        $query2->where('returning', 'LIKE', '%'.$date.'%')
                                            ->where('returning_updated', '0000-01-01 00:00:00');
                                    })->orwhere(function ($query1) use ($date) {
                                        $query1->whereDate('returning_updated', 'LIKE', '%'.$date.'%');
                                    });
            });
        }
//        $bookings = $bookings->select(DB::raw('count(*) as booking_count')) -> first();
//        $count = $bookings->booking_count;
        $count = $bookings->count();
        return $count;
    }

    //get depart count
    public static function getdepartreturncountSchedule($date ,$cond, $shop_id, $class_id) {
        $count = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        if($cond == 'depart') {
            $bookings = $bookings->where('departing','LIKE', '%'.$date.'%');
        }
        if($class_id != 0)
            $bookings = $bookings->where('class_id', $class_id);

        if($cond == 'return') {
            $bookings = $bookings->where(function ($query) use($date) {
                $query->where(function ($query2) use ($date) {
                    $query2->where('returning', 'LIKE', '%'.$date.'%')
                        ->where('returning_updated', '0000-01-01 00:00:00');
                })->orwhere(function ($query1) use ($date) {
                    $query1->whereDate('returning_updated', 'LIKE', '%'.$date.'%');
                });
            });
        }
//        $bookings = $bookings->select(DB::raw('count(*) as booking_count')) -> first();
//        $count = $bookings->booking_count;
        $count = $bookings->count();
        return $count;
    }
    //get cancelcount
    public function getCancelMount($date, $shop_id) {
        $bookings = \DB::table('bookings')
            ->where('status', '9');
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);

        $bookings    = $bookings->where('cancel_date','LIKE', '%'.$date.'%')
                            ->select(DB::raw('count(*) as cancel_count'))
                            -> first();

        $count = 0;
        if(!empty($bookings))
            $count = $bookings->cancel_count;
        return $count;
    }
    //get realsales deapt count and return count
    public function getrealdepartreturnamount($date ,$cond, $shop_id) {
        $count = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        if($cond == 'depart') {
            $bookings = $bookings
                        ->where('pay_method','>', '0')
                        ->where('departing','LIKE', '%'.$date.'%')
                        ->where('depart_task','1');
            $bookings = $bookings->select(DB::raw('count(*) as booking_count')) -> first();
            $count = $bookings->booking_count;
        }
        if($cond == 'return') {
            $bookings = $bookings
                ->where('pay_method','>', '0')
                ->where(function($query) use($date){
                    $query->where(function($query1) use($date){
                        $query1->where('returning','LIKE', '%'.$date.'%')
                            ->where('returning_updated','LIKE', '0000-01-01');
                        })->orwhere(function($query2) use ($date){
                        $query2->where('returning_updated','LIKE', '%'.$date.'%');
                    });
                })
                ->where('return_task','1');
            $bookings = $bookings->select(DB::raw('count(*) as booking_count')) -> first();
            $count = $bookings->booking_count;
        }
        return $count;
    }
    //get depart count
    public function getusedcarmonth($date , $shop_id) {
        $curent_month = date('Y-m-d', strtotime($date));
        $first = date('Y-m-01', strtotime($curent_month));
        $last = date('Y-m-t', strtotime($curent_month));
        $daysInMonth = (int)date('t', strtotime($first));
        $used_car_indices = [];
        $count_active_cars  = 0;
        $count_booked_dates  = 0;
        $count_inspect_dates = 0;
        $count_subst1_dates  = 0;
        $count_subst2_dates  = 0;

        $carnames   = \DB::table('car_inventory as i')
            ->leftjoin('car_class_model as ccm','ccm.model_id','=', 'i.model_id')
            ->leftjoin('car_class as cc','cc.id','=', 'ccm.class_id')
            ->leftjoin('car_model as cm' ,'cm.id','=','i.model_id')
            ->select(\DB::raw("i.status, i.shortname, i.id as inventory_id,i.smoke,CONCAT_WS(i.numberplate3, i.numberplate4) as numberplate,cm.name as modelname,ccm.class_id as class_id,cc.car_shop_name"));
        if($shop_id != 0)
                $carnames   = $carnames->where('i.shop_id',$shop_id)
                                        ->where('cc.car_shop_name', $shop_id );
        $carnames   = $carnames
            ->where('i.delete_flag', 0)
            ->orderBy('cc.car_class_priority')
            ->orderBy('ccm.priority')
            ->orderBy('i.smoke', 'desc')
            ->orderBy('i.priority')
            ->get();

        foreach ($carnames as $key=>$car) {
            $car_id = $car->inventory_id;
            if (!in_array($car_id, $used_car_indices)) {
                $used_car_indices[] = $car_id;
            } else {
                unset($carnames[$key]);
                continue;
            }
            if ($car->status == 1) $count_active_cars++;
            $statuses = [1, 2, 3, 4, 5, 6, 7, 10];

            $used_bookings = \DB::table('bookings as b')
                ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
                ->leftjoin('booking_status as bs','bs.status','=','b.status')
                ->where('b.inventory_id', $car->inventory_id)
                ->whereDate('b.departing', '<=', date('Y-m-d', strtotime($last)))
                ->whereDate('b.returning', '>=', date('Y-m-d', strtotime($first)))
                ->whereIn('b.status',$statuses)
                ->select(['b.*','ps.name as portal_name','bs.name as booking_status'])
                ->orderBy('b.id')->get();


            foreach ($used_bookings as $booking) {
                $depart = date('Y-m-d', strtotime($booking->departing));
                $return = date('Y-m-d', strtotime($booking->returning));
                if (strtotime($booking->returning_updated) > strtotime('1970-01-01 00:00:00'))
                    $return = date('Y-m-d', strtotime($booking->returning_updated));
                if(ServerPath::dateDiff($first, $depart) < 0 ) {
                    $depart = $first;
                }
                if(ServerPath::dateDiff($last, $return) > 0 ) {
                    $return = $last;
                }
                $days = ServerPath::dateDiff($depart, $return) + 1;
                $count_booked_dates += $days;
            }
            //inspection
            $inspections = \DB::table('car_inspections')
                ->where('inventory_id', $car_id)
                ->where('delete_flag', '=', 0)
                ->where('status', '<', 3)
                ->orderBy('id', 'desc')->get();
            foreach ($inspections as $ins) {
                $ins_begin = $ins->begin_date;
                $ins_end = $ins->end_date;
                $flag1 = ServerPath::dateDiff($first, $ins_begin) >= 0 && ServerPath::dateDiff($ins_begin, $last) >= 0;
                $flag2 = ServerPath::dateDiff($first, $ins_end) >= 0 && ServerPath::dateDiff($ins_end, $last) >= 0;
                $flag3 = ServerPath::dateDiff($ins_begin, $first) >= 0 && ServerPath::dateDiff($last, $ins_end) >= 0;
                if ($flag1 || $flag2 || $flag3) {
                    if ($ins_begin < $first) {
                        $ins_begin = $first;
                    }
                    if ($ins_end > $last) {
                        $ins_end = $last;
                    }
                    $days = ServerPath::dateDiff($ins_begin, $ins_end) + 1;
                    if ($ins->kind == 1) {
                        $count_inspect_dates += $days;
                    }
                    if ($ins->kind == 2) {
                        $count_subst1_dates += $days;
                    }
                    if ($ins->kind == 3) {
                        $count_subst2_dates += $days;
                    }
                }
            }
        }

        $total_bookable_dates = $count_active_cars * $daysInMonth - $count_inspect_dates;
        $total_used = $count_booked_dates + $count_subst1_dates + $count_subst2_dates;
//  2018-10-09 change
//        $total_bookable_dates = $count_active_cars * $daysInMonth;
//        $total_used = $count_booked_dates + $count_inspect_dates + $count_subst1_dates + $count_subst2_dates;
        //echo $curent_month."::".$total_used."::".$total_bookable_dates."<br>"; return;
        if($total_bookable_dates != 0)
            $total_perc = round($total_used / $total_bookable_dates * 100, 1);
        else
            $total_perc = 0;
        return $total_perc;
    }

    //get depart count
    public static function getusedcarmonthSchedule($date , $shop_id , $class_id) {
        $curent_month = date('Y-m-d', strtotime($date));
        $first = date('Y-m-01', strtotime($curent_month));
        $last = date('Y-m-t', strtotime($curent_month));
        $daysInMonth = (int)date('t', strtotime($first));
        $used_car_indices = [];
        $count_active_cars  = 0;
        $count_booked_dates  = 0;
        $count_inspect_dates = 0;
        $count_subst1_dates  = 0;
        $count_subst2_dates  = 0;

        $carnames   = \DB::table('car_inventory as i')
            ->leftjoin('car_class_model as ccm','ccm.model_id','=', 'i.model_id')
            ->leftjoin('car_class as cc','cc.id','=', 'ccm.class_id')
            ->leftjoin('car_model as cm' ,'cm.id','=','i.model_id')
            ->select(\DB::raw("i.status, i.shortname, i.id as inventory_id,i.smoke,CONCAT_WS(i.numberplate3, i.numberplate4) as numberplate,cm.name as modelname,ccm.class_id as class_id,cc.car_shop_name"));
        if($shop_id != 0)
            $carnames   = $carnames->where('i.shop_id',$shop_id)
                ->where('cc.car_shop_name', $shop_id );
        if($class_id != 0)
            $carnames   = $carnames->where('cc.id',$class_id);

        $carnames   = $carnames
            ->where('i.delete_flag', 0)
            ->orderBy('cc.car_class_priority')
            ->orderBy('ccm.priority')
            ->orderBy('i.smoke', 'desc')
            ->orderBy('i.priority')
            ->get();

        foreach ($carnames as $key=>$car) {
            $car_id = $car->inventory_id;
            if (!in_array($car_id, $used_car_indices)) {
                $used_car_indices[] = $car_id;
            } else {
                unset($carnames[$key]);
                continue;
            }
            if ($car->status == 1) $count_active_cars++;
            $statuses = [1, 2, 3, 4, 5, 6, 7, 10];

            $used_bookings = \DB::table('bookings as b')
                ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
                ->leftjoin('booking_status as bs','bs.status','=','b.status')
                ->where('b.inventory_id', $car->inventory_id)
                ->whereDate('b.departing', '<=', date('Y-m-d', strtotime($last)))
                ->whereDate('b.returning', '>=', date('Y-m-d', strtotime($first)))
                ->whereIn('b.status',$statuses)
                ->select(['b.*','ps.name as portal_name','bs.name as booking_status'])
                ->orderBy('b.id')->get();


            foreach ($used_bookings as $booking) {
                $depart = date('Y-m-d', strtotime($booking->departing));
                $return = date('Y-m-d', strtotime($booking->returning));
                if (strtotime($booking->returning_updated) > strtotime('1970-01-01 00:00:00'))
                    $return = date('Y-m-d', strtotime($booking->returning_updated));
                if(ServerPath::dateDiff($first, $depart) < 0 ) {
                    $depart = $first;
                }
                if(ServerPath::dateDiff($last, $return) > 0 ) {
                    $return = $last;
                }
                $days = ServerPath::dateDiff($depart, $return) + 1;
                $count_booked_dates += $days;
            }
            //inspection
            $inspections = \DB::table('car_inspections')
                ->where('inventory_id', $car_id)
                ->where('delete_flag', '=', 0)
                ->where('status', '<', 3)
                ->orderBy('id', 'desc')->get();
            foreach ($inspections as $ins) {
                $ins_begin = $ins->begin_date;
                $ins_end = $ins->end_date;
                $flag1 = ServerPath::dateDiff($first, $ins_begin) >= 0 && ServerPath::dateDiff($ins_begin, $last) >= 0;
                $flag2 = ServerPath::dateDiff($first, $ins_end) >= 0 && ServerPath::dateDiff($ins_end, $last) >= 0;
                $flag3 = ServerPath::dateDiff($ins_begin, $first) >= 0 && ServerPath::dateDiff($last, $ins_end) >= 0;
                if ($flag1 || $flag2 || $flag3) {
                    if ($ins_begin < $first) {
                        $ins_begin = $first;
                    }
                    if ($ins_end > $last) {
                        $ins_end = $last;
                    }
                    $days = ServerPath::dateDiff($ins_begin, $ins_end) + 1;
                    if ($ins->kind == 1) {
                        $count_inspect_dates += $days;
                    }
                    if ($ins->kind == 2) {
                        $count_subst1_dates += $days;
                    }
                    if ($ins->kind == 3) {
                        $count_subst2_dates += $days;
                    }
                }
            }
        }

        $total_bookable_dates = $count_active_cars * $daysInMonth;
        $total_used = $count_booked_dates + $count_inspect_dates + $count_subst1_dates + $count_subst2_dates;
        //echo $curent_month."::".$total_used."::".$total_bookable_dates."<br>"; return;
        if($total_bookable_dates != 0)
            $total_perc = round($total_used / $total_bookable_dates * 100, 1);
        else
            $total_perc = 0;
        $ret = array();
        $ret['count_active_cars'] = $count_active_cars;
        $ret['total_percent'] = $total_perc;
        $ret['all_cells'] = $total_bookable_dates;
        $ret['active_cells'] = $total_bookable_dates - $count_inspect_dates ;
        $ret['booking_cells'] = $count_booked_dates ;
        return $ret;
    }

    //get depart count
    public function getcreatedcount($date , $shop_id) {
        $count = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        $bookings = $bookings->where('created_at','LIKE', '%'.$date.'%');
        $bookings = $bookings->select(DB::raw('count(*) as booking_count')) -> first();
        $count = $bookings->booking_count;
        return $count;
    }
    //real departure mount
    public function getrealdepartreturncount($date ,$cond, $shop_id) {
        $count = 0;
        $bookings = \DB::table('bookings');
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);

        $bookings = $bookings->where('departing','LIKE', '%'.$date.'%')
                    ->where('pay_method', '>','0')
                    ->where('depart_task','1');
        $bookings = $bookings->select(DB::raw('count(*) as booking_count')) -> first();
        $count = $bookings->booking_count;
        return $count;
    }
    //get booking number
    public function getportalbookingnumber($date ,$cond, $shop_id, $portal_id) {
        $count = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","10");
        $bookings = \DB::table('bookings')
            ->where('portal_id', $portal_id)
            ->whereIn('status',$statuses);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        if($cond == 'depart') {
            $bookings = $bookings
                ->where('departing','LIKE', '%'.$date.'%');
        }
//        $bookings = $bookings->select(DB::raw('count(*) as booking_count')) -> first();
//        $count = $bookings->booking_count;
        $count = $bookings->count();
        return $count;
    }

    public function getportalbookingnumbercancel($date , $shop_id) {
        $count = 0;
        $bookings = \DB::table('bookings');
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        $bookings = $bookings
            ->where('canceled_at','LIKE', '%'.$date.'%');
        $bookings = $bookings->select(DB::raw('count(*) as booking_count')) -> first();
        $count = $bookings->booking_count;
        return $count;
    }

    //saelchart
    public function realsalechart($current_date,$shop_id) {
        $this_year = date("Y");
        $today_number = date('d');
        $last_year = intval($this_year) - 1;
        $last_day = date("Y-m-t", strtotime($current_date));
        $last_number = intval(explode("-", $last_day)[2]);
        $ret = array();
        $count = 0;
        for($y = $last_year ; $y< $this_year+1 ; $y++) {
            $one = (object)array();
            $one->key = $y.'年';
            $color= '';
            if($count == 0) $color = '#1f77b4';
            if($count == 1) $color = '#ff7f0e';
            $one->color = $color;
            $count++;
            $val = array();
            $price = 0;
            for ($d = 1; $d <= $last_number; $d++) {
                $val[$d] = array();
                $m = date("m");
                $month = date("Y-m",strtotime($y."-".$m."-1"));
                $val[$d]['x'] = $d; //date
                $date = date('Y-m-d', strtotime($month . "-" . ($d)));
                //$sales = $this->getrealsales($date, $shop_id);//sales
                $today = date('Y-m-d');
                if(strtotime($date) <= strtotime($today)) {
                    $sales = $this->getrealsales($date, $shop_id);//sales
                }else {
                    $sales = null;
                }
                $val[$d]['y'] = $sales;//sales
                if($today_number >= $d) {
                    $price += $sales;
                }
            }
            $val = array_values($val);
            $one->values  = $val;
            array_push($ret, $one);
        }
        return $ret;
    }
    /*departchart*/
    public function realamountchart($current_date,$shop_id) {
        $result =array();
        $last_day = date("Y-m-t", strtotime($current_date));
        $last_number = intval(explode("-", $last_day)[2]);
        $ret = (object)array();
        $ret->key = "実際配車数";
        $val = array();
        for ($d = 0; $d < $last_number; $d++) {
            $val[$d] = (object)array();
            $month = date("Y-m",strtotime($current_date));
            $val[$d]->x = ($d + 1); //date
            $date = date('Y-m-d', strtotime($month . "-" . ($d + 1)));
            $mount = $this->getrealamount($date, $shop_id);//sales
            $val[$d]->y = $mount;//sales
        }
        $val = array_values($val);
        $ret->values  = $val;
        $ret->color  = '#0e69ff';
        array_push($result, $ret);
        return $result;
    }
    //monthchart
    public function monthsalechart($current_date,$shop_id) {
        $this_year = date("Y");
        $today_number = date('m');
        $last_year = intval($this_year) - 1;
        $ret = array();
        $count = 0;
        for($y = $last_year ; $y< $this_year+1 ; $y++) {
            $one = (object)array();
            $one->key = $y.'年';
            if($count == 0) $color = '#1f77b4';
            if($count == 1) $color = '#ff7f0e';
            $one->color = $color;
            $count++;
            $val = array();
            $price = 0;
            for ($m = 1; $m <= 12 ; $m++) {
                $val[$m] = array();                
                $date = date("Y-m",strtotime($y."-".($m)));
                $date1 = date("Y-m",strtotime("2018-".$m));
                $val[$m]['x'] = $m; //date
                $sales = $this->getrealsales($date, $shop_id);//sales
                $val[$m]['y'] = $sales;//sales
                if($today_number >= $m) {
                    $price += $sales;
                }
            }
            $val = array_values($val);

            $one->values  = $val;
            $one->strokeWidth = 2;
//            if($price != 0) $mean = round($price/$today_number);
//            else $mean = 0;
//            $one->mean = $mean ;
            array_push($ret, $one);
        }
        return $ret;
    }


    //temporalchart
    public function temporalchart($current_date,$shop_id) {
        $this_year = date("Y");
        $today_number = date('d');
        $last_year = intval($this_year) - 1;
        $last_day = date("Y-m-t", strtotime($current_date));
        $last_number = intval(explode("-", $last_day)[2]);
        $ret = array();
        $count = 0;
        for($y = $last_year ; $y< $this_year+1 ; $y++) {
            $one = (object)array();
            $one->key = $y.'年';
            if($count == 0) $color = '#1f77b4';
            if($count == 1) $color = '#ff7f0e';
            $one->color = $color;
            $count++;
            $val = array();
            $price = 0;
            for ($d = 1; $d <= $last_number; $d++) {
                $val[$d] = array();
                $m = date("m");
                $month = date("Y-m",strtotime($y."-".$m."-1"));
                $val[$d]['x'] = $d; //date
                $date = date('Y-m-d', strtotime($month . "-" . ($d)));
                $sales = $this->gettemporalsales($date, $shop_id);//sales
                $val[$d]['y'] = $sales;//sales
                if($today_number >= $d) {
                    $price += $sales;
                }
            }
            $val = array_values($val);

            $one->values  = $val;
            array_push($ret, $one);
        }
        //add cancel on current year
        $one = (object)array();
        $y = date('Y');
        $one->key = 'キャンセル';
        $val = array();
        $price = 0;
        for ($d = 1; $d <= $last_number; $d++) {
            $val[$d] = array();
            $m = date("m");
            $month = date("Y-m",strtotime($y."-".$m."-1"));
            $val[$d]['x'] = $d; //date
            $date = date('Y-m-d', strtotime($month . "-" . ($d)));
            $sales = $this->getcancelsales($date, $shop_id);//sales
            $val[$d]['y'] = $sales;//sales
            if($today_number >= $d) {
                $price += $sales;
            }
        }
        $val = array_values($val);
        $one->area = true;
        $one->values  = $val;
        $one->fillOpacity = '.3';
        $one->color = '#e1e1e3';
        array_push($ret, $one);
        return $ret;
    }
    //temporalchart
    public function ecpectedchart($current_date,$shop_id) {
        $this_year = date("Y");
        $today_number = date('d');
        $last_year = intval($this_year) - 1;
        $last_day = date("Y-m-t", strtotime($current_date));
        $last_number = intval(explode("-", $last_day)[2]);
        $ret = array();
        $count = 0;
        for($y = $last_year ; $y< $this_year+1 ; $y++) {
            $one = (object)array();
            $one->key = $y.'年(見込み)';
            $color= '';
            if($count == 0) $color = '#1fb42d';
            if($count == 1) $color = '#b115cd';
            $one->color = $color;
            $count++;
            $val = array();
            $price = 0;
            for ($d = 1; $d <= $last_number; $d++) {
                $val[$d] = array();
                $m = date("m");
                $month = date("Y-m",strtotime($y."-".$m."-1"));
                $val[$d]['x'] = $d; //date
                $date = date('Y-m-d', strtotime($month . "-" . ($d)));
                $today = date('Y-m-d');
                if(strtotime($date) >= strtotime($today)) {
                    $sales = $this->getexpectedsaleschart($date, $shop_id);//sales
                }else {
                    $sales = null;
                }
                $val[$d]['y'] = $sales;//sales
                if($today_number >= $d) {
                    $price += $sales;
                }
            }
            $val = array_values($val);
            $one->values  = $val;
            $one->classed  = 'dashed';
            array_push($ret, $one);
        }
        return $ret;
    }
    //get amount  booking
    public function bookingamountchart($current_date,$shop_id) {
        $result =array();
        $last_day = date("Y-m-t", strtotime($current_date));
        $last_number = intval(explode("-", $last_day)[2]);
        $ret = (object)array();
        $ret->key = "予約数";
        $val = array();
        for ($d = 0; $d < $last_number; $d++) {
            $val[$d] = (object)array();
            $month = date("Y-m",strtotime($current_date));
            $val[$d]->x = ($d + 1); //date
            $date = date('Y-m-d', strtotime($month . "-" . ($d + 1)));
            $mount = $this->gettemporalamount($date, $shop_id);//sales
            $val[$d]->y = $mount ;//sales
        }
        $val = array_values($val);
        $ret->values  = $val;
        $ret->color  = '#0e69ff';
        array_push($result, $ret);
        //cancel amount
        $ret_1 = (object)array();
        $ret_1->key = "キャンセル数";
        $val = array();
        for ($d = 0; $d < $last_number; $d++) {
            $val[$d] = (object)array();
            $month = date("Y-m",strtotime($current_date));
            $val[$d]->x = ($d + 1); //date
            $date = date('Y-m-d', strtotime($month . "-" . ($d + 1)));
            $mount = $this->getcancelamount($date, $shop_id);//sales
            $val[$d]->y = $mount ;//sales
        }
        $val = array_values($val);
        $ret_1->values  = $val;
        $ret_1->color  = '#e1e1e3';
        array_push($result, $ret_1);
        return $result;
    }
    //get expected amount
    //get amount  booking
    public function expectedamountchart($current_date,$shop_id) {
        $result =array();
        $last_day = date("Y-m-t", strtotime($current_date));
        $last_number = intval(explode("-", $last_day)[2]);
        $ret = (object)array();
        $ret->key = "見込み配車数";
        $val = array();
        for ($d = 0; $d < $last_number; $d++) {
            $val[$d] = (object)array();
            $month = date("Y-m",strtotime($current_date));
            $val[$d]->x = ($d + 1); //date
            $date = date('Y-m-d', strtotime($month . "-" . ($d + 1)));

            $today = date('Y-m-d');
            if(strtotime($today) > strtotime($date)) $mount = 0;
            else
                $mount = $this->getexpectedamount($date, $shop_id);//sales
            $val[$d]->y = $mount ;//sales
        }
        $val = array_values($val);
        $ret->values  = $val;
        $ret->color  = '#b115cd';
        array_push($result, $ret);
        return $result;
    }
    public function monthbookingamountchart($current_date,$shop_id) {
        $result =array();
        $this_year = date("Y");
        $last_year = intval($this_year) - 1;
        $count = 0;
        for($y = $last_year ; $y< $this_year+1 ; $y++) {
            $last_number = 12;
            $ret = (object)array();
            $ret->key = $y;
            $val = array();
            for ($m = 1; $m <= $last_number; $m++) {
                $val[$m] = (object)array();
                $date =  $y."-".$m."-1";
                $date = date("Y-m", strtotime($date));
                $mount_depart = $this->getrealdepartreturncount($date, 'depart', $shop_id);//sales
                //$mount_return = $this->getrealdepartreturncount($date, 'return', $shop_id);//sales
                $val[$m]->x = $m;
                //$val[$m]->y = $mount_depart + $mount_return;//sales
                $val[$m]->y = $mount_depart ;//sales
            }
            $val = array_values($val);
            $ret->values = $val;
            if($count == 0)
                $ret->color = '#3366cc';
            else
                $ret->color = '#ff7f0e';
            array_push($result, $ret);
            $count++;
        }
        return $result;
    }
    //get month booking portal
    public function monthbookingportalamount($current_date,$shop_id) {
        $result =array();
        //get portal list
        $portals = \DB::table('portal_site')
//            ->where('show_flag','1')
            ->select(['id','name'])->get();
        $c = count($portals);
        $portals[$c] = (object)array();
        $portals[$c]->id= '10000';
        $portals[$c]->name= '自社HP';
        $portals[$c+1] = (object)array();
        $portals[$c+1]->id= '10001';
        $portals[$c+1]->name= '自社HPAD';
        $portals[$c+2] = (object)array();
        $portals[$c+2]->id= '50000';
        $portals[$c+2]->name= 'キャンセル';
        $total = array();
        for($p = 0 ; $p< count($portals) ; $p++) {
            $portal_name = $portals[$p]->name;
            $portal_id   = $portals[$p]->id;
//            $last_number = 12;
            $last_number = 18;
            $ret = (object)array();
            $ret->key = $portal_name;
            $ret->order = 30;
            switch ($portal_name) {
                case '自社HP':
                case '自社HPAD':
                        $ret->order = 1;
                        break;
                case  '他ポータル':
                case '予約フォーム':
                case '旧自社HP':
                case '来店':
                    $ret->order = 2;
                    break;
                case '電話':                   
                case '取引業者':
                case '知人':
                    $ret->order = 3;
                    break;
                case 'SKY':
                case 'たびんふぉ':
                    $ret->order = 4;
                    break;
                case 'じゃらん':
                case 'じゃらんパック':
                    $ret->order = 5;
                    break;
                case '楽天':
                    $ret->order = 6;
                    break;
                case 'キャンセル':
                    $ret->order = 61;
                    break;
            }
            $val = array();
            for ($m = 1; $m <= $last_number; $m++) {
                $val[$m] = (object)array();
                $year = date("Y");
//                $date =  $year."-".$m."-1";
                $first_date =  $year."-01-01";
                $date = date('Y-m', strtotime($first_date.' +'.($m-1).' months'));
//                if($m <= 12)
//                    $key = date('Y年m月', strtotime($first_date.' +'.($m-1).' months'));
//                else
                    $key = date('Y年n月', strtotime($first_date.' +'.($m-1).' months'));

                $date = date("Y-m", strtotime($date));
                if($portal_id == '50000') {//cancel
                    $mount_cancel = $this->getportalbookingnumbercancel($date, $shop_id);
//                    $val[$m]->x = $m;
                    if($m)
                    $val[$m]->x = $key;//$m;
                    $val[$m]->y = $mount_cancel ;
                }else {
                    $mount_depart = $this->getportalbookingnumber($date, 'depart', $shop_id, $portal_id);//sales
                    //$mount_return = $this->getportalbookingnumber($date, 'return', $shop_id, $portal_id);//sales
                    $mount_return =0;
                    $val[$m]->x = $key;//$m;
                    $val[$m]->y = $mount_depart + $mount_return;//sales
                }
                if(empty($total[$m])) {
                    $total[$m] = (object)array();
                    $total[$m]->x = $val[$m]->x;
                    $total[$m]->y = $val[$m]->y;
                }else{
                    $total[$m]->x = $val[$m]->x;
                    $total[$m]->y = $total[$m]->y + $val[$m]->y;
                }
            }
            $val = array_values($val);
            $ret->values = $val;
            $bgcolor        = ServerPath::portalColor($portal_id);
            if($portal_id == '10000' ) $bgcolor = '#e544fc';
            if($portal_id == '10001') $bgcolor = '#e544fc';
            if($portal_id == '50000') $bgcolor = '#d7d7d9';
            $ret->color = $bgcolor;
            array_push($result, $ret);
        }
        //get total
//        $ret = (object)array();
//        $ret->key = "全て";
//        $ret->order = 62;
//        $val = array_values($total);
//        $ret->values = $val;
//        $ret->color = "05c113";
//        array_push($result, $ret);

        usort($result, function($a, $b)
        {
            return strcmp($a->order, $b->order);
        });
        return $result;
    }
    //get sales and mount of today
    public function todaySalesMount($current_date,$shop_id) {
        $ret = (object)array();
        $all_sales = $this->getrealsales($current_date,0);
        $all_mount = $this->getrealdepartreturncount($current_date,'depart',0);
        //get fukuoka id;
        $fuku = \DB::table('car_shop')->where('shop_number','1')->first();
        $fuku_id = $fuku->id;
        $fuku_sales = $this->getrealsales($current_date,$fuku_id);
        $fuku_mount = $this->getrealdepartreturncount($current_date,'depart',$fuku_id);
        $fuku_percent = 0;
        if($all_mount != 0) $fuku_percent = round((100/$all_mount)*$fuku_mount);

        $okina = \DB::table('car_shop')->where('shop_number','2')->first();
        $okina_id = $okina->id;
        $okina_sales = $this->getrealsales($current_date,$okina_id);
        $okina_mount = $this->getrealdepartreturncount($current_date,'depart',$okina_id);
        $okina_percent = 0;
        if($all_mount != 0) $okina_percent = round((100/$all_mount)*$okina_mount);

        $kagoshima = \DB::table('car_shop')->where('shop_number','3')->first();
        if(!empty($kagoshima)) {
            $kagoshima_id = $kagoshima->id;
            $kagoshima_sales = $this->getrealsales($current_date, $kagoshima_id);
            $kagoshima_mount = $this->getrealdepartreturncount($current_date, 'depart', $kagoshima_id);
            $kagoshima_percent = 0;
            if ($all_mount != 0) $kagoshima_percent = round((100 / $all_mount) * $kagoshima_mount);
        }else{
            $kagoshima_id = 0;
            $kagoshima_sales = 0;
            $kagoshima_mount = 0;
            $kagoshima_percent = 0;
            $kagoshima_percent = 0;
        }
        $ret->all_sales         = $all_sales;
        $ret->all_mount         = $all_mount;
        $ret->fuku_sales        = $fuku_sales;
        $ret->fuku_mount        = $fuku_mount;
        $ret->fuku_percent      = $fuku_percent;
        $ret->okina_sales       = $okina_sales;
        $ret->okina_mount       = $okina_mount;
        $ret->okina_percent     = $okina_percent;
        $ret->kagoshima_sales   = $kagoshima_sales;
        $ret->kagoshima_mount   = $kagoshima_mount;
        $ret->kagoshima_percent = $kagoshima_percent;
        return $ret;
    }
    //get sales and mount of today
    public function todaytemporalMount($current_date,$shop_id) {
        $ret = (object)array();
        $all_sales = $this->gettemporalsales($current_date,0);
        $all_mount = $this->gettemporalamount($current_date, 0);
        //get fukuoka id;
        $fuku = \DB::table('car_shop')->where('shop_number','1')->first();
        $fuku_id = $fuku->id;
        $fuku_sales = $this->gettemporalsales($current_date,$fuku_id);
        $fuku_mount = $this->gettemporalamount($current_date,'depart',$fuku_id);
        $fuku_percent = 0;
        if($all_mount != 0) $fuku_percent = round((100/$all_mount)*$fuku_mount);

        $okina = \DB::table('car_shop')->where('shop_number','2')->first();
        $okina_id = $okina->id;
        $okina_sales = $this->gettemporalsales($current_date,$okina_id);
        $okina_mount = $this->gettemporalamount($current_date,'depart',$okina_id);
        $okina_percent = 0;
        if($all_mount != 0) $okina_percent = round((100/$all_mount)*$okina_mount);
        $ret->all_sales     = $all_sales;
        $ret->all_mount     = $all_mount;
        $ret->fuku_sales    = $fuku_sales;
        $ret->fuku_mount    = $fuku_mount;
        $ret->fuku_percent   = $fuku_percent;
        $ret->okina_sales    = $okina_sales;
        $ret->okina_mount    = $okina_mount;
        $ret->okina_percent   = $okina_percent;
        return $ret;
    }
    //get insurance price
    public function getSaleInsurance($date, $shop_id) {
        $price = 0;
        $ret = (object)array();
        $book = array();
        $statuses = array("1", "2", "3","4","5","6","7","8","10");
        $bookings = \DB::table('bookings')
            ->where('pay_method', '>', '0')
            ->whereIn('status',$statuses);

        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);


        $booking_portal = clone $bookings;
        $booking_portal = $booking_portal
            ->where('portal_flag', '1')
            ->where('pay_method', '>', '0')
            ->where('depart_task', '1')
            ->where('depart_task_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(insurance1) as ins1, sum(insurance2) as ins2"))->first();
        $price += $booking_portal->ins1 + $booking_portal->ins2;

        $booking_portal = clone $bookings;
        $booking_portal = $booking_portal
            ->where('portal_flag', '0')
            ->where('pay_method', '>', '0')
            ->where('paid_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("SUM(insurance1) as ins1, sum(insurance2) as ins2"))->first();
        $price += $booking_portal->ins1 + $booking_portal->ins2;

        ///////////////////get booking price_list
        $bookings_price = \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id', '=', 'pr.book_id')
            ->where('pr.paid_date', 'LIKE', '%' . $date . '%')
            ->where('pr.pay_method','>','0')
            ->whereIn('bo.status', $statuses);
        if ($shop_id != 0)
            $bookings_price = $bookings_price->where('bo.pickup_id', '=', $shop_id);
        $bookings_price = $bookings_price
            ->select(DB::raw("SUM(pr.insurance1) as ins1, SUM(pr.insurance2) as ins2"))->first();
        $price += $bookings_price->ins1 + $bookings_price->ins2;
        $ret->insurance = $price;

        $booking_count = 0;
        $booking_portal = clone $bookings;
        $booking_portal = $booking_portal
            ->where('portal_flag', '1')
            ->where('pay_method', '>' ,'0')
            ->where('depart_task', '1')
            ->where('depart_task_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("count(*) as booking_count"))->first();
        $booking_count += $booking_portal->booking_count;

        $booking_portal = clone $bookings;
        $booking_portal = $booking_portal
            ->where('portal_flag', '0')
            ->where('pay_method', '>' ,'0')
            ->where('paid_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("count(*) as booking_count"))->first();
        $booking_count += $booking_portal->booking_count;
        $ret->mount     = $booking_count;
        return $ret;
    }
    //get insurance today insurance
    public function todayInsurance($current_date, $shop_id) {
        $ret = (object)array();
        $all_insurance = $this->getSaleInsurance($current_date,0);
        $ret->all_insurance_price = $all_insurance->insurance;
        $ret->all_insurance_mount = $all_insurance->mount;
        //get fukuoka id;
        $fuku = \DB::table('car_shop')->where('shop_number','1')->first();
        $fuku_id = $fuku->id;
        $fuku_insurance = $this->getSaleInsurance($current_date, $fuku_id);
        $ret->fuku_insurance_price = $fuku_insurance->insurance;
        $ret->fuku_insurance_mount = $fuku_insurance->mount;
        //get okina id;
        $okina = \DB::table('car_shop')->where('shop_number','2')->first();
        $okina_id = $okina->id;
        $okina_insurance = $this->getSaleInsurance($current_date, $okina_id);
        $ret->okina_insurance_price = $okina_insurance->insurance;
        $ret->okina_insurance_mount = $okina_insurance->mount;
        //get kagoshima id;
        $kagoshima = \DB::table('car_shop')->where('shop_number','3')->first();
        if(!empty($kagoshima)) {
            $kagoshima_id = $kagoshima->id;
            $kagoshima_insurance = $this->getSaleInsurance($current_date, $kagoshima_id);
            $ret->kagoshima_insurance_price = $kagoshima_insurance->insurance;
            $ret->kagoshima_insurance_mount = $kagoshima_insurance->mount;
        }else {
            $ret->kagoshima_insurance_price = 0;
            $ret->kagoshima_insurance_mount = 0;
        }
        return $ret;
    }
    //get insurance price
    public function getSaleOption($date, $shop_id) {
        $price = 0;
        $ret = (object)array();
        $book = array();
        $statuses = array("1", "2", "3","4","5","6","7","8","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);

        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);
        //like with departing
        $bookings_depart = clone  $bookings;
        $bookings_depart = $bookings_depart
            ->where('portal_flag', '1')
            ->where('paid_date', 'LIKE', '%' . $date . '%')
            ->where('depart_task_date', '1')
            ->where('pay_method', '>', '0')
            ->select(DB::raw("SUM(option_price) as option_price"))->first();
        $price += $bookings_depart->option_price;

        $bookings_depart = clone  $bookings;
        $bookings_depart = $bookings_depart
            ->where('portal_flag', '0')
            ->where('paid_date', 'LIKE', '%' . $date . '%')
            ->where('pay_method', '>', '0')
            ->select(DB::raw("SUM(option_price) as option_price"))->first();
        $price += $bookings_depart->option_price;


        ///////////////////get booking price_list
        $bookings_price = \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id', '=', 'pr.book_id')
            ->where('pr.paid_date', 'LIKE', '%' . $date . '%')
            ->where('pr.pay_method','>','0')
            ->whereIn('bo.status', $statuses);
        if ($shop_id != 0)
            $bookings_price = $bookings_price->where('bo.pickup_id', '=', $shop_id);
        $bookings_price = $bookings_price
            ->select(DB::raw("SUM(pr.option_price) as option_price"))->first();
        $price += $bookings_price->option_price;
        $ret->option = $price;

        $booking_count = 0;
        $booking_portal = clone $bookings;
        $booking_portal = $booking_portal
            ->where('portal_flag', '1')
            ->where('pay_method', '>' ,'0')
            ->where('depart_task', '1')
            ->where('depart_task_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("count(*) as booking_count"))->first();
        $booking_count += $booking_portal->booking_count;

        $booking_portal = clone $bookings;
        $booking_portal = $booking_portal
            ->where('portal_flag', '0')
            ->where('pay_method', '>' ,'0')
            ->where('paid_date','LIKE', '%'.$date.'%')
            ->select(DB::raw("count(*) as booking_count"))->first();
        $booking_count += $booking_portal->booking_count;
        $ret->mount     = $booking_count;
        return $ret;
    }
    //get option for today
    public function todayOption($current_date, $shop_id) {
        $ret = (object)array();
        $all_option = $this->getSaleOption($current_date,0);
        $ret->all_option_price = $all_option->option;
        $ret->all_option_mount = $all_option->mount;
        //get fukuoka id;
        $fuku = \DB::table('car_shop')->where('shop_number','1')->first();
        $fuku_id = $fuku->id;
        $fuku_option = $this->getSaleOption($current_date, $fuku_id);
        $ret->fuku_option_price = $fuku_option->option;
        $ret->fuku_option_mount = $fuku_option->mount;
        //get okina id;
        $okina = \DB::table('car_shop')->where('shop_number','2')->first();
        $okina_id = $okina->id;
        $okina_option = $this->getSaleOption($current_date, $okina_id);
        $ret->okina_option_price = $okina_option->option;
        $ret->okina_option_mount = $okina_option->mount;

        //get kagoshima id;
        $kagoshima = \DB::table('car_shop')->where('shop_number','3')->first();
        if(!empty($kagoshima)) {
            $kagoshima_id = $kagoshima->id;
            $kagoshima_option = $this->getSaleOption($current_date, $kagoshima_id);
            $ret->kagoshima_option_price = $kagoshima_option->option;
            $ret->kagoshima_option_mount = $kagoshima_option->mount;
        }else {
            $ret->kagoshima_option_price = 0;
            $ret->kagoshima_option_mount = 0;
        }
        return $ret;
    }
    //get booking list
    public function todayBooking($current_date,$shop_id)
    {
        $bookings = \DB::table('bookings as b')
            ->leftjoin('users as u', 'b.client_id','=','u.id')
            ->leftjoin('booking_status as bs','bs.status','=','b.status')
            ->leftjoin('car_inventory as ci', 'ci.id','=','b.inventory_id')
            ->leftjoin('car_class as cc','cc.id','=','b.class_id')
            ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
            ->select(['b.*','u.first_name','u.last_name','ps.show_flag','cc.id as class_id','cc.name as class_name',
                'ci.smoke','ps.name as portal_name']) ;

        if($shop_id != 0)
            $bookings = $bookings->where('b.pickup_id','=', $shop_id);
            $bookings = $bookings
                            ->where('b.created_at', 'LIKE', '%' . $current_date . '%')
                            ->orderBy('id', 'desc')
                            //->where('ps.show_flag' ,'1')
                            ->get();
        $ret = array();
        $ret['date'] = date('n/j');
        $shop_name = '';
        if($shop_id != '0') {
            $shop = \DB::table('car_shop')->where('id', $shop_id)->first();
            $shop_name = $shop->name;
        }
        $ret['shop_name'] = '本日予約された予約一覧   '.$shop_name;
        $list =array();
        foreach($bookings as $bo) {
            //if($bo->show_flag != '1' && $bo->portal_flag == '1') continue;
            $id         = $bo->id;
            $booking_id = $bo->booking_id;
            $departing      = $bo->departing;
            $month_day      = date('n/j', strtotime($departing));
            $day            = date('N',strtotime($departing));
            $day            = $this->getday($day);
            $hour_min       = date('H:i',strtotime($departing));
            $depart         = $month_day."(".$day.")".$hour_min;
            $returning      = $bo->returning;
            $month_day      = date('n/j', strtotime($returning));
            $day            = date('N',strtotime($returning));
            $day            = $this->getday($day);
            $hour_min       = date('H:i',strtotime($returning));
            $return         = $month_day."(".$day.")".$hour_min;
            $period         = $depart."~".$return;
            $bgcolor        = ServerPath::portalColor($bo->portal_id);
            $portal_name    = $bo->portal_name;
            $portal_id      = $bo->portal_id;
            if($portal_id == '10000') {
                $portal_name='自社HP';
                $bgcolor    = '#e544fc';
            }
            if($portal_id == '10001') {
                $portal_name='自社HPAD';
                $bgcolor    = '#e544fc';
            }
            $class_name     = $bo->class_name;
            $loop_user      = '';
            if($bo->portal_flag != '1') {
                $client_id = $bo->client_id;
                $count = \DB::table('bookings')
                    ->where('client_id', $client_id)->count();
                if($count == 1) $loop_user = '初';
                if($count > 1) $loop_user  = 'リピート';
            }
            $smoke          = '';
            if($bo->smoke == '1')  $smoke = '喫煙';
            if($bo->smoke == '0')  $smoke = '禁煙';
            $one = (object)array();
            $one->id         = $id;
            $one->booking_id = $booking_id;
            $one->period        = $period;
            $one->portal_name = $portal_name;
            $one->color       = $bgcolor;
            $one->class_name  = $class_name;
            $one->loop_user   = $loop_user;
            $one->smoke       = $smoke;
            array_push($list, $one);
        }
        $ret['list'] = $list;
        return $ret;
    }
    //get booking list
    public function todayPortalBooking($current_date,$shop_id)
    {
        $bookings = \DB::table('bookings as b')
            ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
            ->select(['b.*','ps.show_flag','ps.name as portal_name']) ;

        if($shop_id != 0)
            $bookings = $bookings->where('b.pickup_id','=', $shop_id);
        $bookings = $bookings
            ->where('b.created_at', 'LIKE', '%' . $current_date . '%')
           // ->where('ps.show_flag' ,'1')
            ->orderBy('id', 'desc')
            ->get();
        $ret = array();
        $ret['date'] = date('n/j');
        $shop_name = '';
        if($shop_id != '0') {
            $shop = \DB::table('car_shop')->where('id', $shop_id)->first();
            $shop_name = $shop->name;
        }
        $ret['shop_name'] = '本日予約された予約一覧   '.$shop_name;
        $list =array();
        $all_count = 0;
        foreach($bookings as $bo) {
            if($bo->show_flag != '1' && $bo->portal_flag == '1') continue;
            $portal_id  = $bo->portal_id;
            $portal_name= $bo->portal_name;
            $bgcolor        = ServerPath::portalColor($bo->portal_id);
            if(empty($list[$portal_id])) {
                $list[$portal_id] = (object)array();
                $name = $portal_name;
                if($portal_id == '10000'){
                    $bgcolor    = '#e544fc';
                    $name = '自社HP';
                    $portal_name = $name;
                }
                if($portal_id == '10001') {
                    $name = '自社HPAD';
                    $bgcolor    = '#e544fc';
                    $portal_name = $name;
                }
                $list[$portal_id]->id = $portal_id;
                $list[$portal_id]->y = 0;
                $list[$portal_id]->key = $name;
                $list[$portal_id]->color = $bgcolor;
                $list[$portal_id]->order = 30;
            }
            switch ($portal_name) {
                case '自社HP':
                case '自社HPAD':
                    $list[$portal_id]->order = 1;
                    break;
                case  '他ポータル':
                case '予約フォーム':
                    $list[$portal_id]->order = 2;
                    break;
                case '電話':
                case '取引業者':
                case '知人':
                    $list[$portal_id]->order = 3;
                    break;
                case 'SKY':
                case 'たびんふぉ':
                    $list[$portal_id]->order = 4;
                    break;
                case 'じゃらん':
                case 'じゃらんパック':
                    $list[$portal_id]->order = 5;
                    break;
                case '楽天':
                    $list[$portal_id]->order = 6;
                    break;
                case 'SKY':
                case 'たびんふぉ':
                    $list[$portal_id]->order = 7;
                    break;
                case 'キャンセル':
                    $list[$portal_id]->order = 61;
                    break;
            }
            $list[$portal_id]->y = $list[$portal_id]->y + 1;
            $all_count++;
        }
        usort($list, function($a, $b)
        {
            return strcmp($a->order, $b->order);
        });
        $ret['list'] = array_values($list);
        $ret['all'] = $all_count;
        return $ret;
    }

    public static function PriceMountYesterday(){
        $subject    = '■ 昨日のパフォーマンス';
        $sender     ='ハコレンシステム';
        $day        = date('Y-m-d',strtotime("-1 days"));
        $yest = DashBoardController::yesterday($day);
//        $template   = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
//        $template   .= '<html xmlns="http://www.w3.org/1999/xhtml">';
//        $template   .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
//        $template   .= '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
//        $template   .= '<head>';
//        $template   .= '<meta charset="utf-8">';
//        $template   .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
//        $template   .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
//        $template   .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
//        $template   .= '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>';
//        $template   .= '</head>';
        $template   = '<div style="font-size: 12px;">';
        $template   .= '<h2>&nbsp;</h2>';
        $template   .= '<p>おはようございます！</p>';
        $template   .= "<p>現時点での".date('m',strtotime($day))."月".date('d',strtotime($day))."日の予約を報告します。</p>";
        $template   .= '<p><strong> 売上 </strong></p>';
        $template   .= '<table cellpadding="0" cellspacing="0" border="0">';
        $template   .= '<thead>';
        $template   .= '<tr>';
        $template   .= '<th style="border-top: 1px solid; text-align: center; padding: 5px 20px 5px 20px;">店舗</th>';
        $template   .= '<th style="border-top: 1px solid;text-align: center;padding: 5px 20px 5px 20px; ">件数</th>';
        $template   .= '<th style="border-top: 1px solid;text-align: center;padding: 5px 20px 5px 20px;" >売上金額（円）</th>';
        $template   .= '</tr>';
        $template   .= '</thead>';
        $template   .= '<tbody>';
        $template   .= '<tr>';
        $template   .= '<td style="border-top: 1px solid;padding: 5px 20px 5px 20px;" >福岡</td>';
        $template   .= '<td style="border-top: 1px solid; text-align: center;padding:5px 20px 5px 20px;">'.$yest->real_fuku_mount.'</td>';
        $template   .= '<td style="border-top: 1px solid; text-align: center;padding:5px 20px 5px 20px;">'.number_format($yest->real_fuku_sales).' </td>';
        $template   .= '</tr>';
        $template   .= '<tr>';
        $template   .= '<td style="padding:5px 20px 5px 20px;" >沖縄 </td>';
        $template   .= '<td style="text-align: center;padding:5px 20px 5px 20px;">'.$yest->real_okina_mount.'</td>';
        $template   .= '<td style="text-align: center;padding:5px 20px 5px 20px;" >'.number_format($yest->real_okina_sales).'</td>';
        $template   .= '</tr>';
        $template   .= '<tr>';
        $template   .= '<td style="padding:5px 20px 5px 20px;" >鹿児島</td>';
        $template   .= '<td style="text-align: center;padding:5px 20px 5px 20px;" >'.$yest->real_kagoshima_mount.'</td>';
        $template   .= '<td style="text-align: center;padding:5px 20px 5px 20px;" >'.number_format($yest->real_kagoshima_sales).'</td>';
        $template   .= '</tr>';
        $template   .= '<tr>';
        $template   .= '<td style="border-top: 1px solid;padding:5px 20px 5px 20px;" >合計</td>';
        $template   .= '<td style="border-top: 1px solid;text-align: center;padding: 5px;">'.$yest->real_all_mount.'</td>';
        $template   .= '<td style="border-top: 1px solid;text-align: center;padding: 5px;" >'.number_format($yest->real_all_sales).'</td>';
        $template   .= '</tr>';
        $template   .= '</tbody>';
        $template   .= '</table>';

        $template   .= '<table  cellpadding="0" cellspacing="0" border="0" style="margin-top: 20px;" >';
        $template   .= '<thead>';
        $template   .= '<tr>';
        $template   .= '<th style="text-align:center;padding:5px 10px 5px 10px;" ></th>';
        $template   .= '<th style="text-align:center;padding:5px 10px 5px 10px;" colspan="2">予約</th>';
        $template   .= '<th style="text-align:center;padding:5px 0px 5px 0px;" colspan="2">キャンセル</th>';
        $template   .= '</tr>';
        $template   .= '<tr>';
        $template   .= '<th style="text-align:center;border-top:1px solid;padding:5px 20px 5px 20px;" >店舗 </th>';
        $template   .= '<th style="text-align:center;border-top:1px solid;border-left:1px solid;padding:5px 20px 5px 20px;" >件数</th>';
        $template   .= '<th style="text-align:center;border-top:1px solid;padding:5px 20px 5px 20px;" >売上 &nbsp; </th>';
        $template   .= '<th style="text-align:center;border-top:1px solid ;border-left: 1px solid;padding:5px 20px 5px 20px;" >件数 </th>';
        $template   .= '<th style="text-align:center;border-top:1px solid;padding:5px 20px 5px 20px;" >金額</th>';
        $template   .= '</tr>';
        $template   .= '</thead>';
        $template   .= '<tbody>';
        $template   .= '<tr>';
        $template   .= '<td style="border-top:1px solid;padding:5px 20px 5px 20px;">福岡</td>';
        $template   .= '<td style="text-align:center;border-left: 1px solid;border-top:1px solid;padding:5px 20px 5px 20px;">'.$yest->temporal_fuku_mount.'</td>';
        $template   .= '<td style="text-align:center;border-top:1px solid;padding:5px 20px 5px 20px;">'.number_format($yest->temporal_fuku_sales).' </td>';
        $template   .= '<td style="text-align:center;border-left: 1px solid;border-top:1px solid;padding:5px 20px 5px 20px;" >'.$yest->temporal_fuku_mount_cancel.'</td>';
        $template   .= '<td style="text-align:center;border-top:1px solid;padding:5px 20px 5px 20px;">'.number_format($yest->temporal_fuku_sales_cancel).'</td>';
        $template   .= '</tr>';
        $template   .= '<tr>';
        $template   .= '<td style="padding:5px 20px 5px 20px;">沖縄</td>';
        $template   .= '<td style="text-align:center;border-left: 1px solid;padding:5px 20px 5px 20px;">'.$yest->temporal_okina_mount.'</td>';
        $template   .= '<td style="text-align:center;padding:5px 20px 5px 20px;">'.number_format($yest->temporal_okina_sales).'</td>';
        $template   .= '<td style="text-align:center;border-left: 1px solid;padding:5px 20px 5px 20px;" >'.$yest->temporal_okina_mount_cancel.'</td>';
        $template   .= '<td style="text-align:center;padding:5px 20px 5px 20px;">'.number_format($yest->temporal_okina_sales_cancel).'</td>';
        $template   .= '</tr>';
        $template   .= '<tr>';
        $template   .= '<td style="padding:5px 20px 5px 20px;">鹿児島 </td>';
        $template   .= '<td style="text-align:center;border-left: 1px solid;padding:5px 20px 5px 20px;">'.$yest->temporal_kagoshima_mount.'</td>';
        $template   .= '<td style="text-align:center;padding:5px 20px 5px 20px;">'.number_format($yest->temporal_kagoshima_sales).'</td>';
        $template   .= '<td style="text-align:center;border-left: 1px solid;padding:5px 20px 5px 20px;">'.$yest->temporal_kagoshima_mount_cancel.'</td>';
        $template   .= '<td style="text-align:center;padding:5px 20px 5px 20px;">'.number_format($yest->temporal_kagoshima_sales_cancel).'</td>';
        $template   .= '</tr>';
        $template   .= '<tr>';
        $template   .= '<td style="padding:5px 20px 5px 20px;">合計</td>';
        $template   .= '<td style="text-align:center;border-left: 1px solid;padding:5px 20px 5px 20px;">'.$yest->temporal_all_mount.'</td>';
        $template   .= '<td style="text-align:center;padding:5px 20px 5px 20px;" >'.number_format($yest->temporal_all_sales).'</td>';
        $template   .= '<td style="text-align:center;border-left: 1px solid;padding:5px 20px 5px 20px;" >'.$yest->temporal_all_mount_cancel.'</td>';
        $template   .= '<td style="text-align:center;padding:5px 20px 5px 20px;">'.number_format($yest->temporal_all_sales_cancel).'</td>';
        $template   .= '</tr>';
        $template   .= '<tr>';
        $template   .= '<td style="border-top:1px solid;padding:5px 20px 5px 20px;">差引金額</td>';
        $template   .= '<td colspan="4" style="text-align: center;border-top:1px solid;">'.number_format($yest->temporal_all_sales - $yest->temporal_all_sales_cancel).'</td>';
        $template   .= '</tr>';
        $template   .= '</tbody>';
        $template   .= '</table>';

        $detail = DashBoardController::getYesDetails($day);
        $huku = $detail['huku'];
        $template   .= '<table  cellpadding="0" cellspacing="0" border="0" style="margin-top: 20px;" >';
        for($i=0; $i < count($huku) ; $i++) {
            if($i== 0) {
                $template   .= '<thead>';
                $template   .= '<tr>';
                $template   .= '<th style="text-align:center;border-bottom:1px solid;border-top:1px solid;padding:5px 20px 5px 20px;" >'.$huku[$i]['t1'].'</th>';
                $template   .= '<th style="text-align:center;border-bottom:1px solid;border-left: 1px solid;border-top:1px solid;padding:5px 20px 5px 20px;" >'.$huku[$i]['t2'].'</th>';
                $template   .= '<th style="text-align:center;border-bottom:1px solid;border-top:1px solid;padding:5px 20px 5px 20px;" >'.$huku[$i]['t3'].'</th>';
                $template   .= '</tr>';
                $template   .= '</thead>';
                $template   .= '<tbody>';
            }else{
                $template   .= '<tr>';
                $template   .= '<td style="text-align:left;padding:5px 20px 5px 20px;" >'.$huku[$i]['t1'].'</td>';
                $template   .= '<td style="text-align:center;border-left: 1px solid;padding:5px 20px 5px 20px;" >'.$huku[$i]['t2'].'</td>';
                $template   .= '<td style="text-align:center;padding:5px 20px 5px 20px;">'.$huku[$i]['t3'].'</td>';
                $template   .= '</tr>';
            }
        }
        $template   .='<tr> <td colspan="3" style="border-top: 1px solid"></td></tr>';
        $template   .= '</tbody>';
        $template   .= '</table>';

        $okina = $detail['okina'];
        $template   .= '<table cellpadding="0" cellspacing="0" border="0" style="margin-top: 20px;" >';
        for($i=0; $i < count($okina) ; $i++) {
            if($i== 0) {
                $template   .= '<thead>';
                $template   .= '<tr>';
                $template   .= '<th style="text-align:center;border-bottom:1px solid;border-top:1px solid;padding:5px 20px 5px 20px;"  >'.$okina[$i]['t1'].'</th>';
                $template   .= '<th style="text-align:center;border-bottom:1px solid;border-left: 1px solid;border-top:1px solid;padding:5px 20px 5px 20px;" >'.$okina[$i]['t2'].'</th>';
                $template   .= '<th style="text-align:center;border-bottom:1px solid;border-top:1px solid;padding:5px 20px 5px 20px;">'.$okina[$i]['t3'].' </th>';
                $template   .= '</tr>';
                $template   .= '</thead>';
                $template   .= '<tbody>';
            }else{
                $template   .= '<tr>';
                $template   .= '<td style="text-align:left;padding:5px 20px 5px 20px;">'.$okina[$i]['t1'].'</td>';
                $template   .= '<td style="text-align:center;border-left: 1px solid;padding:5px 20px 5px 20px;" >'.$okina[$i]['t2'].'</td>';
                $template   .= '<td style="text-align:center;padding:5px 20px 5px 20px;">'.$okina[$i]['t3'].'</td>';
                $template   .= '</tr>';
            }
        }
        $template   .='<tr> <td colspan="3" style="border-top: 1px solid"></td></tr>';
        $template   .= '</tbody>';
        $template   .= '</table>';



//        $template   .= 'おはようございます！<br/>';
//        $template   .="現時点での".date('m',strtotime($day))."月".date('d',strtotime($day))."日の予約を報告します。<br/><br/>";
//        $template   .="■ 売上 <br/>"; //real sales
//        $template   .=" 総計：".$yest->real_all_mount."件 / ".number_format($yest->real_all_sales)."円 <br/>"; //  the total number of departing cars yesterday / amount of real sales
//        $template   .="&nbsp; - 福岡：".$yest->real_fuku_mount."件 / ".number_format($yest->real_fuku_sales)."円<br/>"; //福岡空港店 shop
//        $template   .="&nbsp; - 沖縄：".$yest->real_okina_mount."件 / ".number_format($yest->real_okina_sales)."円<br/>"; //那覇空港店 shop
//        $template   .="&nbsp; - 鹿児島：".$yest->real_kagoshima_mount."件 / ".number_format($yest->real_kagoshima_sales)."円<br/>"; //鹿児島店 shop

//        $template   .="■ 予約 <br>"; //temporal sales
//        $template   .=" 差引: ".number_format($yest->temporal_all_sales - $yest->temporal_all_sales_cancel)."円</br><br/>";
//        $template   .=" 予約総計： ".$yest->temporal_all_mount."件 /".number_format($yest->temporal_all_sales)."円 <br/>";
//        $template   .=" キャンセル合計： ".$yest->temporal_all_mount_cancel."件 /".number_format($yest->temporal_all_sales_cancel)."円 <br/>";
//
//        $template   .=" &nbsp; - 福岡：".$yest->temporal_fuku_mount."件 / ".number_format($yest->temporal_fuku_sales)."円 &nbsp; (キャ：".$yest->temporal_fuku_mount_cancel."件 / ".number_format($yest->temporal_fuku_sales_cancel)."円) <br/>"; //福岡空港店 shop
//        $template   .=" &nbsp; - 沖縄：".$yest->temporal_okina_mount."件 / ".number_format($yest->temporal_okina_sales)."円 &nbsp; (キャ：".$yest->temporal_okina_mount_cancel."件 / ".number_format($yest->temporal_okina_sales_cancel)."円） <br/>"; //那覇空港店 shop
//        $template   .=" &nbsp; - 鹿児島：".$yest->temporal_kagoshima_mount."件 / ".number_format($yest->temporal_kagoshima_sales)."円 &nbsp; (キャ：".$yest->temporal_kagoshima_mount_cancel."件 / ".number_format($yest->temporal_kagoshima_sales_cancel)."円） <br/>"; //鹿児島店 shop
//        $template   .= "== <br/>";

        $template   .= "<p>以上、よろしくお願いいたします。</p>";
        $template   .= '</div>';
        //$template   .='</html>';
       // echo $template; return;
        $protocol = "https://";
        $server = \DB::table('server_name')->orderby('id')->first();
        $domain = $server->name;
        if(!empty($template)) {
            $content = $template;
            if(strpos($server->name, 'hakoren') === false){
                // for motocle8 test
                $mail_addresses = [
                    'future.syg1118@gmail.com',
                    'mailform@motocle.com ',
                    //'business@motocle.com'
                ];
            } else {
                // for hakoren staffs
                $mail_addresses = [
                    //'future.syg1118@gmail.com',
                    'mailform@motocle.com',
                    'ko.ume@motocle.com',
                    'kopei1107@gmail.com',
                    'm.kazue525@gmail.com',
                    'n.08041134223@gmail.com',
                    'h.shuya@mobinc.jp'
                ];
            }

            foreach ($mail_addresses as $address){
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $sender, 'email' => $address);
                $data[] = $data1;
            }
            //========================
        }
        $finaldata = array('data' => json_encode($data, JSON_UNESCAPED_UNICODE));


        try {
            $ch = array();
            $mh = curl_multi_init();
            $ch[0] = curl_init();

            // set URL and other appropriate options
            curl_setopt($ch[0], CURLOPT_URL, $protocol.$domain. "/mail/vaccine/medkenmail.php");
            curl_setopt($ch[0], CURLOPT_HEADER, 0);
            curl_setopt($ch[0], CURLOPT_POST, true);
            curl_setopt($ch[0], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch[0], CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch[0], CURLOPT_CAINFO, '/etc/httpd/conf/server.pem');
            curl_setopt($ch[0], CURLOPT_USERPWD, 'motocle:m123');
            curl_setopt($ch[0], CURLOPT_POST, true);
            curl_setopt($ch[0], CURLOPT_POSTFIELDS, $finaldata);
            curl_setopt($ch[0], CURLOPT_SSL_VERIFYPEER, 0);
            curl_multi_add_handle($mh, $ch[0]);
            $active = null;
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

            while ($active && $mrc == CURLM_OK) {
                // add this line
                while (curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM) ;

                if (curl_multi_select($mh) != -1) {
                    do {
                        $mrc = curl_multi_exec($mh, $active);
                        if ($mrc == CURLM_OK) {
                        }
                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                }
            }
            //close the handles
            curl_multi_remove_handle($mh, $ch[0]);
            curl_multi_close($mh);
        } catch (Exception $e) {
        }

        $ret['code'] = '200';
        return Response::json($ret);
    }

    public static function yesterday($date) {
        $ret = (object)array();
        $real = DashBoardController::todaySalesMountSchedule($date, 0);
        $ret->real_all_sales    = $real->all_sales;
        $ret->real_all_mount    = $real->all_mount;
        $ret->real_fuku_sales   = $real->fuku_sales;
        $ret->real_fuku_mount   = $real->fuku_mount;
        $ret->real_okina_sales  = $real->okina_sales;
        $ret->real_okina_mount  = $real->okina_mount;
        $ret->real_kagoshima_sales  = $real->kagoshima_sales;
        $ret->real_kagoshima_mount  = $real->kagoshima_mount;
        $temporal = DashBoardController::todaytemporalMountSchedule($date,0);
        $ret->temporal_all_sales        = $temporal->all_sales;
        $ret->temporal_all_mount        = $temporal->all_mount;
        $ret->temporal_all_sales_cancel = $temporal->all_sales_cancel;
        $ret->temporal_all_mount_cancel = $temporal->all_mount_cancel;
        $ret->temporal_fuku_sales       = $temporal->fuku_sales;
        $ret->temporal_fuku_mount       = $temporal->fuku_mount;
        $ret->temporal_fuku_sales_cancel= $temporal->fuku_sales_cancel;
        $ret->temporal_fuku_mount_cancel= $temporal->fuku_mount_cancel;
        $ret->temporal_okina_sales      = $temporal->okina_sales;
        $ret->temporal_okina_mount      = $temporal->okina_mount;
        $ret->temporal_okina_sales_cancel  = $temporal->okina_sales_cancel;
        $ret->temporal_okina_mount_cancel  = $temporal->okina_mount_cancel;
        $ret->temporal_kagoshima_sales          = $temporal->kagoshima_sales;
        $ret->temporal_kagoshima_mount          = $temporal->kagoshima_mount;
        $ret->temporal_kagoshima_sales_cancel   = $temporal->kagoshima_sales_cancel;
        $ret->temporal_kagoshima_mount_cancel   = $temporal->kagoshima_mount_cancel;
        return $ret;
    }

    public static function todaySalesMountSchedule($current_date,$shop_id) {
        $ret = (object)array();
        $all_sales = DashBoardController::getrealsales($current_date,0);
        $all_mount = DashBoardController::getrealdepartreturncountSchedule($current_date,'depart',0);
        //get fukuoka id;
        $fuku = \DB::table('car_shop')->where('shop_number','1')->first();
        $fuku_id = $fuku->id;
        $fuku_sales = DashBoardController::getrealsales($current_date,$fuku_id);
        $fuku_mount = DashBoardController::getrealdepartreturncountSchedule($current_date,'depart',$fuku_id);
        $fuku_percent = 0;
        if($all_mount != 0) $fuku_percent = round((100/$all_mount)*$fuku_mount);

        //get okina
        $okina = \DB::table('car_shop')->where('shop_number','2')->first();
        $okina_id = $okina->id;
        $okina_sales = DashBoardController::getrealsales($current_date,$okina_id);
        $okina_mount = DashBoardController::getrealdepartreturncountSchedule($current_date,'depart',$okina_id);
        $okina_percent = 0;
        if($all_mount != 0) $okina_percent = round((100/$all_mount)*$okina_mount);

        //get kagoshima
        $kagoshima = \DB::table('car_shop')->where('shop_number','3')->first();
        if(!empty($kagoshima)) {
            $kagoshima_id = $kagoshima->id;
            $kagoshima_sales = DashBoardController::getrealsales($current_date, $kagoshima_id);
            $kagoshima_mount = DashBoardController::getrealdepartreturncountSchedule($current_date, 'depart', $kagoshima_id);
            $kagoshima_percent = 0;
            if ($all_mount != 0) $kagoshima_percent = round((100 / $all_mount) * $kagoshima_mount);
        }else {
            $kagoshima_sales = 0;
            $kagoshima_mount = 0;
            $kagoshima_percent = 0;
        }

        $ret->all_sales         = $all_sales;
        $ret->all_mount         = $all_mount;
        $ret->fuku_sales        = $fuku_sales;
        $ret->fuku_mount        = $fuku_mount;
        $ret->fuku_percent      = $fuku_percent;
        $ret->okina_sales       = $okina_sales;
        $ret->okina_mount       = $okina_mount;
        $ret->okina_percent     = $okina_percent;
        $ret->kagoshima_sales    = $kagoshima_sales;
        $ret->kagoshima_mount    = $kagoshima_mount;
        $ret->kagoshima_percent  = $kagoshima_percent;
        return $ret;
    }

    //real departure mount
    public static function  getrealdepartreturncountSchedule($date ,$cond, $shop_id) {
        $statuses = array("1", "2", "3","4","5","6","7","8","10");
        $count = 0;
        $bookings = \DB::table('bookings')->whereIn('status',$statuses);;
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);

        $bookings = $bookings->where('departing','LIKE', '%'.$date.'%')
            ->where('pay_method', '>','0')
            ->where('depart_task','1');
        $bookings = $bookings->select(DB::raw('count(*) as booking_count')) -> first();
        $count = $bookings->booking_count;
        return $count;
    }

    //get sales and mount of today
    public static function todaytemporalMountSchedule($current_date,$shop_id) {
        $ret = (object)array();
        $all_sales  = DashBoardController::gettemporalsalesSchedule($current_date,0);
        $all_cancel = DashBoardController::getcancelsalesSchedule($current_date,0);
        $all_mount = DashBoardController::gettemporalamountSchedule($current_date, 0);
        $all_mount_cancel = DashBoardController::gettemporalcancelamountSchedule($current_date, 0);

        //get fukuoka id;
        $fuku = \DB::table('car_shop')->where('shop_number','1')->first();
        $fuku_id = $fuku->id;
        $fuku_sales = DashBoardController::gettemporalsalesSchedule($current_date,$fuku_id);
        $fuku_cancel = DashBoardController::getcancelsalesSchedule($current_date,$fuku_id);
        $fuku_mount = DashBoardController::gettemporalamountSchedule($current_date,$fuku_id);
        $fuku_mount_cancel = DashBoardController::gettemporalcancelamountSchedule($current_date, $fuku_id);
        $fuku_percent = 0;
        if($all_mount != 0) $fuku_percent = round((100/$all_mount)*$fuku_mount);
        //get okina
        $okina = \DB::table('car_shop')->where('shop_number','2')->first();
        $okina_id = $okina->id;
        $okina_sales = DashBoardController::gettemporalsalesSchedule($current_date,$okina_id);
        $okina_cancel = DashBoardController::getcancelsalesSchedule($current_date,$okina_id);
        $okina_mount = DashBoardController::gettemporalamountSchedule($current_date,$okina_id);
        $okina_mount_cancel = DashBoardController::gettemporalcancelamountSchedule($current_date, $okina_id);
        $okina_percent = 0;
        if($all_mount != 0) $okina_percent = round((100/$all_mount)*$okina_mount);

        //get kagoshima
        $kagoshima = \DB::table('car_shop')->where('shop_number','3')->first();
        if(!empty($kagoshima)) {
            $kagoshima_id = $kagoshima->id;
            $kagoshima_sales = DashBoardController::gettemporalsalesSchedule($current_date, $kagoshima_id);
            $kagoshima_cancel = DashBoardController::getcancelsalesSchedule($current_date, $kagoshima_id);
            $kagoshima_mount = DashBoardController::gettemporalamountSchedule($current_date, $kagoshima_id);
            $kagoshima_mount_cancel = DashBoardController::gettemporalcancelamountSchedule($current_date, $kagoshima_id);
            $kagoshima_percent = 0;
            if ($all_mount != 0) $kagoshima_percent = round((100 / $all_mount) * $kagoshima_mount);
        }else {
            $kagoshima_sales = 0;
            $kagoshima_cancel = 0;
            $kagoshima_mount = 0;
            $kagoshima_mount_cancel = 0;
            $kagoshima_percent = 0;
        }
        $ret->all_sales             = $all_sales;
        $ret->all_sales_cancel      = $all_cancel;
        $ret->all_mount             = $all_mount;
        $ret->all_mount_cancel      = $all_mount_cancel;
        $ret->fuku_sales            = $fuku_sales;
        $ret->fuku_sales_cancel     = $fuku_cancel;
        $ret->fuku_mount            = $fuku_mount;
        $ret->fuku_mount_cancel     = $fuku_mount_cancel;
        $ret->fuku_percent          = $fuku_percent;
        $ret->okina_sales           = $okina_sales;
        $ret->okina_sales_cancel    = $okina_cancel;
        $ret->okina_mount           = $okina_mount;
        $ret->okina_mount_cancel    = $okina_mount_cancel;
        $ret->okina_percent         = $okina_percent;
        $ret->kagoshima_sales           = $kagoshima_sales;
        $ret->kagoshima_sales_cancel    = $kagoshima_cancel;
        $ret->kagoshima_mount           = $kagoshima_mount;
        $ret->kagoshima_mount_cancel    = $kagoshima_mount_cancel;
        $ret->kagoshima_percent         = $kagoshima_percent;
        return $ret;
    }

    //get temporl sales
    public static function gettemporalsalesSchedule($date ,  $shop_id) {
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);

        $bookings = $bookings->where('created_at', 'LIKE', '%' . $date . '%');

        $booking_portal_1 = clone  $bookings;
        $booking_portal_1 = $booking_portal_1
            ->where('portal_flag','1')
            ->where('payment','0')
            ->select(DB::raw("SUM(web_payment) as sumpayment"))->first();
        $price += $booking_portal_1->sumpayment;

         $booking_portal_2 = clone  $bookings;
         $booking_portal_2 = $booking_portal_2
             ->where('portal_flag','1')
             ->where('payment','!=','0')
             ->select(DB::raw("SUM(payment) as sumpayment"))->first();
        $price += $booking_portal_2->sumpayment;

        $booking_portal_3 = clone  $bookings;
         $booking_portal_3 = $booking_portal_3
             ->where('portal_flag','0')
             ->select(DB::raw("SUM(payment) as sumpayment"))->first();
        $price += $booking_portal_3->sumpayment;

        $booking_portal_4 = clone  $bookings;
         $booking_portal_4 = $booking_portal_4
             ->select(DB::raw("SUM(given_points) as sumpayment"))->first();
        $price += $booking_portal_4->sumpayment;


        $bookings_price = \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id', '=', 'pr.book_id')
            ->whereIn('bo.status', $statuses)
            ->where('pr.created_at', 'LIKE', '%' . $date . '%');
        if ($shop_id != 0)
            $bookings_price = $bookings_price->where('bo.pickup_id', '=', $shop_id);

        $bookings_price = $bookings_price
            ->select(DB::raw("SUM(pr.total_price) as sumpayment"))
            ->first();
        $price += $bookings_price->sumpayment;
        return $price;
    }
    //get cancel sales
    public static function getcancelsalesSchedule($date ,  $shop_id) {
        $price = 0;
        $bookings = \DB::table('bookings')
            ->where('status','9');
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', $shop_id);

        $bookings = $bookings->where('cancel_date', 'LIKE', '%' . $date . '%')
                             ->get();
        foreach ($bookings as $bo) {
            $price += $bo->payment + $bo->given_points ;
//            $cancel_fee = $bo->cancel_fee;
//            $price -= $cancel_fee;
        }
        return $price;
    }

    //get temporal count
    public static function gettemporalamountSchedule($date ,  $shop_id) {
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status',$statuses);
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);

        $bookings = $bookings
            ->where('created_at', 'LIKE', '%' . $date . '%')
            ->select(DB::raw('count(*) as paid_count'))->first();
        $price = $bookings->paid_count;
        return $price;
    }
    //get temporal cancel count
    public static function gettemporalcancelamountSchedule($date ,  $shop_id) {
        $price = 0;
        $bookings = \DB::table('bookings')
            ->where('status','9');
        if ($shop_id != 0)
            $bookings = $bookings->where('pickup_id', '=', $shop_id);

        $bookings = $bookings
            ->where('cancel_date', 'LIKE', '%' . $date . '%')
            ->select(DB::raw('count(*) as cancel_count'))->first();
        $price = $bookings->cancel_count;
        return $price;
    }

    //get detail fpr summary
    public static function getYesDetails($date){
        $detail = array();
        $shops = \DB::table('car_shop')->get();
        foreach($shops as $shop) {
            $shop_id = $shop->id;
            $shop_name = $shop->name;
            $shop_number = $shop->shop_number;
            if($shop_number == '1') {
                $detail['huku'] = array();
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id , 0);
                //title
                $detail['huku'][0]['t1'] = $shop_name;
                $detail['huku'][0]['t2'] = $quick_check['cu_month'].'月';;
                $detail['huku'][0]['t3'] = $quick_check['next_month'].'月';

                //reservation
                $detail['huku'][1]['t1'] = '予約件数';
                $detail['huku'][1]['t2'] = $quick_check['cu_month_depart_count'];
                $detail['huku'][1]['t3'] = $quick_check['next_month_depart_count'];

                // all cells
                $detail['huku'][2]['t1'] = 'all cells';
                $detail['huku'][2]['t2'] = $quick_check['cu_month_all_cells'];
                $detail['huku'][2]['t3'] = $quick_check['next_month_all_cells'];

                // active cells
                $detail['huku'][3]['t1'] = 'active cells';
                $detail['huku'][3]['t2'] = $quick_check['cu_month_active_cells'];
                $detail['huku'][3]['t3'] = $quick_check['next_month_active_cells'];

                // booking cslls
                $detail['huku'][4]['t1'] = 'booking cells';
                $detail['huku'][4]['t2'] = $quick_check['cu_month_booking_cells'];
                $detail['huku'][4]['t3'] = $quick_check['next_month_booking_cells'];

                //all percent
                $detail['huku'][5]['t1'] = '全体';
                $detail['huku'][5]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['huku'][5]['t3'] = $quick_check['next_month_usedcar'].'%';

                //CW2 percent
                $class = \DB::table('car_class')->where('name', 'CW2')->first();
                $class_id = 0;
                if(!empty($class)) $class_id = $class->id;
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id , $class_id);
                $detail['huku'][6]['t1'] = 'CW2';
                $detail['huku'][6]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['huku'][6]['t3'] = $quick_check['next_month_usedcar'].'%';

                //W1 percent
                $class = \DB::table('car_class')->where('name', 'W1')->first();
                $class_id = 0;
                if(!empty($class)) $class_id = $class->id;
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id , $class_id);
                $detail['huku'][7]['t1'] = 'W1';
                $detail['huku'][7]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['huku'][7]['t3'] = $quick_check['next_month_usedcar'].'%';

                //W2 percent
                $class = \DB::table('car_class')->where('name', 'W2')->first();
                $class_id = 0;
                if(!empty($class)) $class_id = $class->id;
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id , $class_id);
                $detail['huku'][8]['t1'] = 'W2';
                $detail['huku'][8]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['huku'][8]['t3'] = $quick_check['next_month_usedcar'].'%';

                //HG200 percent
                $class = \DB::table('car_class')->where('name', 'HG200')->first();
                $class_id = 0;
                if(!empty($class)) $class_id = $class->id;
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id , $class_id);
                $detail['huku'][9]['t1'] = 'HG200';
                $detail['huku'][9]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['huku'][9]['t3'] = $quick_check['next_month_usedcar'].'%';

            }

            if($shop_number == '2') {
                $detail['okina'] = array();
                //title
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id, 0);
                $detail['okina'][0]['t1'] = $shop_name;
                $detail['okina'][0]['t2'] = $quick_check['cu_month'].'月';
                $detail['okina'][0]['t3'] = $quick_check['next_month'].'月';

                //reservation
                $detail['okina'][1]['t1'] = '予約件数';
                $detail['okina'][1]['t2'] = $quick_check['cu_month_depart_count'];
                $detail['okina'][1]['t3'] = $quick_check['next_month_depart_count'];

                // all cells
                $detail['okina'][2]['t1'] = 'all cells';
                $detail['okina'][2]['t2'] = $quick_check['cu_month_all_cells'];
                $detail['okina'][2]['t3'] = $quick_check['next_month_all_cells'];

                // active cells
                $detail['okina'][3]['t1'] = 'active cells';
                $detail['okina'][3]['t2'] = $quick_check['cu_month_active_cells'];
                $detail['okina'][3]['t3'] = $quick_check['next_month_active_cells'];

                // booking cslls
                $detail['okina'][4]['t1'] = 'booking cells';
                $detail['okina'][4]['t2'] = $quick_check['cu_month_booking_cells'];
                $detail['okina'][4]['t3'] = $quick_check['next_month_booking_cells'];


                //all percent
                $detail['okina'][5]['t1'] = '全体';
                $detail['okina'][5]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['okina'][5]['t3'] = $quick_check['next_month_usedcar'].'%';

                //SWO percent
                $class = \DB::table('car_class')->where('name', 'SWO')->first();
                $class_id = 0;
                if(!empty($class)) $class_id = $class->id;
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id , $class_id);
                $detail['okina'][6]['t1'] = 'SWO';
                $detail['okina'][6]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['okina'][6]['t3'] =  $quick_check['next_month_usedcar'].'%';

                //WO1 percent
                $class = \DB::table('car_class')->where('name', 'WO1')->first();
                $class_id = 0;
                if(!empty($class)) $class_id = $class->id;
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id , $class_id);
                $detail['okina'][7]['t1'] = 'WO1';
                $detail['okina'][7]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['okina'][7]['t3'] = $quick_check['next_month_usedcar'].'%';

                //WEO percent
                $class = \DB::table('car_class')->where('name', 'WEO')->first();
                $class_id = 0;
                if(!empty($class)) $class_id = $class->id;
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id , $class_id);
                $detail['okina'][8]['t1'] = 'WEO';
                $detail['okina'][8]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['okina'][8]['t3'] = $quick_check['next_month_usedcar'].'%';

                //HGO200 percent
                $class = \DB::table('car_class')->where('name', 'HGO200')->first();
                $class_id = 0;
                if(!empty($class)) $class_id = $class->id;
                $quick_check    = DashBoardController::quickCheckSchedule($date, $shop_id , $class_id);
                $detail['okina'][9]['t1'] = 'HGO200';
                $detail['okina'][9]['t2'] = $quick_check['cu_month_usedcar'].'%';
                $detail['okina'][9]['t3'] = $quick_check['next_month_usedcar'].'%';
            }
        }

        return $detail;
    }
}