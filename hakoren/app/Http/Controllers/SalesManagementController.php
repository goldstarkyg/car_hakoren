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

class SalesManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //sales management
    public function sales(Request  $request) {
        $admin = Auth::user();
        if(!Auth::check() || !$admin->isAdmin())
            return Redirect::back();
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $shops = Shop::all();
        $date = date('Y-m-d');
        $related_shop = \DB::table('admin_shop')->where('admin_id', $admin->id)->first();
        $user_shop_id = is_null($related_shop)? 0 : $related_shop->shop_id;
        
        if($request->has('s_shop_id')) {
            $shop_id = intval($request->input('s_shop_id'));
        }else {
            $shop_id = $user_shop_id;
        }
        
        if($request->has('s_cond')) {
            $cond = $request->input('s_cond');
        }else {
            $cond = 'one';
        }
        if($request->has('s_date')) {            
            $date = date('Y-m-d',strtotime($request->input('s_date')));
            if($cond == 'one') $date = date('Y-m-d',strtotime($request->input('s_date')));
            if($cond == 'day') $date = date('Y-m',strtotime($request->input('s_date')));
            if($cond == 'month') $date = date('Y',strtotime($request->input('s_date')."-01-01"));
            if($cond == 'year') $date = date('Y',strtotime($request->input('s_date')));
        }

        $portal_list = array();
        $depart_list = array();
        $return_list = array();
        $qs_list     = array();
        $qs_cancel_list = array();
        $cancel_list = array();
        $sum = (object)array();
        if($cond == 'one') {
            $cashsum = $this->getSalesMount($date, $cond, $shop_id, '1'); //cash
            $creditsum = $this->getSalesMount($date, $cond, $shop_id, '2'); //credit
            $websum = $this->getSalesMount($date, $cond, $shop_id, '3'); //system QS
            $portalsum = $this->getSalesMount($date, $cond, $shop_id, '4'); //portal
            $adjustmentsum = $this->getSalesAdjustmentMount($date, $cond, $shop_id);//adjustment price
            $cancelsum = $this->getSalesCancelMount($date, $cond, $shop_id); // cancell sum
            //echo $cancelsum; return;
            $sumtotal = $cashsum + $creditsum + $websum + $portalsum + $adjustmentsum;

            $sum->cashsum = $cashsum;
            $sum->creditsum = $creditsum;
            $sum->websum = $websum;
            $sum->portalsum = $portalsum;
            $sum->adjustmentsum = $adjustmentsum;
            $sum->cancelsum = $cancelsum;
            $sum->sumtotal = $sumtotal;

            //get portal sitestes
            $portal_list = $this->salesPortalList($date, $cond, $shop_id);
            //get deaprtlist
            $depart_list = $this->getSalesdepatMount($date, $cond, $shop_id, 'depart', 'paid');

            //get returntlist
            $return_list = $this->getSalesdepatMount($date, $cond, $shop_id, 'return', 'paid');
            //get QS list
            $qs_list = $this->getSalesQSMount($date, $cond, $shop_id);
            //get qs-cancellation
            $qs_cancel_list = $this->getSalesQSCancelMount($date, $cond, $shop_id);
            //get cancellation
            $cancel_list = $this->getSalesCancellation($date, $cond, $shop_id);
        }
        $result_list = array();

        if($cond == 'day') {
            $last_day = date("Y-m-t", strtotime($date));
            $last_number = intval(explode("-", $last_day)[2]);
            for($i=0; $i < $last_number ; $i++) {
                $result_list[$i] = (object)array();
                $result_list[$i]->day = ($i+1).'日';
                $result_list[$i]->date = date("Y-m-d", strtotime($date."-".($i+1)));
            }

            //count
            $this->getSalescountDay($shop_id,$result_list);
            //cash
            $pay_method = '1';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //credit
            $pay_method = '2';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //web
            $pay_method = '3';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //portal
            $pay_method = '4';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //adjustment
            $this->getSalesAdjustmentMountDay($shop_id, $result_list);//adjustment price
            //camcel
            //$this->getSalesCancelMountDay($shop_id, $result_list);
        }
        if($cond == 'month') {
            $last_number = intval(12);
            for($i=0; $i < $last_number ; $i++) {
                $result_list[$i] = (object)array();
                $result_list[$i]->day = ($i+1).'月';
                $year = date("Y", strtotime($date."-01-01"));
                $result_list[$i]->date = date("Y-m", strtotime($year."-".($i+1)));
            }
            //count
            $this->getSalescountDay($shop_id,$result_list);
            //cash
            $pay_method = '1';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //credit
            $pay_method = '2';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //web
            $pay_method = '3';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //portal
            $pay_method = '4';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //adjustment
            $this->getSalesAdjustmentMountDay($shop_id, $result_list);//adjustment price
            //camcel
           // $this->getSalesCancelMountDay($shop_id, $result_list);
        }

        if($cond == 'year') {
            $last_number = intval(5);
            for($i= 0; $i < $last_number ; $i++) {
                $result_list[$i] = (object)array();
                $result_list[$i]->day = ($date - $i).'年';
                $result_list[$i]->date = $date - $i;
            }
            //count
            $this->getSalescountDay($shop_id,$result_list);
            //cash
            $pay_method = '1';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //credit
            $pay_method = '2';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //web
            $pay_method = '3';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //portal
            $pay_method = '4';
            $this->getSalesMountDay($shop_id, $pay_method, $result_list,$cond);
            //adjustment
            $this->getSalesAdjustmentMountDay($shop_id, $result_list);//adjustment price
            //camcel
            //$this->getSalesCancelMountDay($shop_id, $result_list);
        }



        $data = [
            'route'         => $route,
            'subroute'      => $subroute,
            'admin'         => $admin,
            'shops'         => $shops,
            'shop_id'       => $shop_id,
            'cond'          => $cond,
            'date'          => $date,
            'sum'           => $sum,
            'portal_list'   => $portal_list,
            'depart_list'   => $depart_list,
            'return_list'   => $return_list,
            'qs_list'       => $qs_list,
            'cancel_list'   => $cancel_list,
            'qs_cancel_list'=> $qs_cancel_list,
            'result_list'   => $result_list

        ];

        return View('pages.admin.booking.sales')->with($data);
    }
    public  function getpayMethodName($pay_method) {
        $ret = '';
        switch ($pay_method) {
            case '1' :
                $ret = '現金' ;
                break;
            case '2':
                $ret = 'カード' ;
                break;
            case '3':
                $ret = 'Web支払' ;
                break;
            case '4':
                $ret = 'Portal決済' ;
                break;
        }
        return $ret;
    }
    public  function getcancelPayMethodName($pay_method) {
        $ret = '';
        switch ($pay_method) {
            case '10' :
                $ret = '未請求' ;
                break;
            case '11':
                $ret = '請求中' ;
                break;
            case '2':
                $ret = 'カード' ;
                break;
            case '1':
                $ret = '現金' ;
                break;
            case '5':
                $ret = '振込' ;
                break;
//            case '6':
//                $ret = '未払いで完了' ;
//                break;
        }
        return $ret;
    }
    //authmanagement
    public function auth(Request  $request) {
        $admin = Auth::user();
        if(!Auth::check() || !$admin->isAdmin())
            return Redirect::back();
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $shops = Shop::all();
        $date = date('Y-m-d');
        $related_shop = \DB::table('admin_shop')->where('admin_id', $admin->id)->first();
        $user_shop_id = is_null($related_shop)? 0 : $related_shop->shop_id;

        if($request->has('s_shop_id')) {
            $shop_id = intval($request->input('s_shop_id'));
        }else {
            $shop_id = $user_shop_id;
        }

        if($request->has('s_cond')) {
            $cond = $request->input('s_cond');
        }else {
            $cond = 'one';
        }
        if($request->has('s_date')) {
            $date = date('Y-m-d',strtotime($request->input('s_date')));
            if($cond == 'one') $date = date('Y-m-d',strtotime($request->input('s_date')));
            if($cond == 'day') $date = date('Y-m',strtotime($request->input('s_date')));
            if($cond == 'month') $date = date('Y',strtotime($request->input('s_date')."-01-01"));
        }
        $reser_val = array();
        $cancel_price = array();
        $sum = 0;
        $temporal_list = array();
        $canceltemporal_list = array();
        if($cond == 'one') {
            $reser_val = $this->getAuthReservation($date, $cond, $shop_id);
            $cancel_price = $this->getAuthCancelMount($date, $cond, $shop_id);
            $sum = $reser_val->price - $cancel_price->price;

            //get deaprtlist
            $temporal_list = $this->getTemopralList($date, $cond, $shop_id, 'depart', 'unpaid');
            usort($temporal_list, function($a, $b)
            {
                return strcmp($a->book_id, $b->book_id);
            });
            $canceltemporal_list = $this->getCancelTemopralList($date, $cond, $shop_id, 'depart', 'unpaid');
            usort($canceltemporal_list, function($a, $b)
            {
                return strcmp($a->book_id, $b->book_id);
            });
        }
        $result_list = array();
        $fukuoka_id = 0;
        $okinawa_id = 0;
        $kagoshima_id = 0;
        $fukuoka = \DB::table('car_shop')->where('shop_number','1')->first();
        $fukuoka_id = $fukuoka->id;
        $okinawa = \DB::table('car_shop')->where('shop_number','2')->first();
        $okinawa_id = $okinawa->id;
        $kagoshima = \DB::table('car_shop')->where('shop_number','3')->first();
        if(!empty($kagoshima)) {
            $kagoshima_id = $kagoshima->id;
        }
        if($cond == 'day') {
            $last_day = date("Y-m-t", strtotime($date));
            $last_number = intval(explode("-", $last_day)[2]);
            for($i=0; $i < $last_number ; $i++) {
                $result_list[$i] = (object)array();
                $result_list[$i]->day = ($i+1).'日';
                $result_list[$i]->date = date("Y-m-d", strtotime($date."-".($i+1)));
            }
            $this->getAuthReservationcountDay($fukuoka_id,'fuku', $result_list);
            $this->getAuthReservationcountDay($okinawa_id, 'okina', $result_list);
            $this->getAuthReservationcountDay($kagoshima_id, 'kagoshima', $result_list);

            $this->getAuthCancellationcountDay($fukuoka_id , 'fuku', $result_list);
            $this->getAuthCancellationcountDay($okinawa_id , 'okina', $result_list);
            $this->getAuthCancellationcountDay($kagoshima_id , 'kagoshima', $result_list);

        }
        if($cond == 'month') {
            $last_number = intval(12);
            for($i=0; $i < $last_number ; $i++) {
                $result_list[$i] = (object)array();
                $result_list[$i]->day = ($i+1).'月';
                $year = date("Y", strtotime($date."-01-01"));
                $result_list[$i]->date = date("Y-m", strtotime($year."-".($i+1)));
            }
            $this->getAuthReservationcountDay($fukuoka_id,'fuku', $result_list);
            $this->getAuthReservationcountDay($okinawa_id , 'okina', $result_list);
            $this->getAuthReservationcountDay($kagoshima_id , 'kagoshima', $result_list);

            $this->getAuthCancellationcountDay($fukuoka_id,'fuku', $result_list);
            $this->getAuthCancellationcountDay($okinawa_id ,'okina', $result_list);
            $this->getAuthCancellationcountDay($kagoshima_id ,'kagoshima', $result_list);

        }

        $data = [
            'route'         => $route,
            'subroute'      => $subroute,
            'admin'         => $admin,
            'shops'         => $shops,
            'shop_id'       => $shop_id,
            'cond'          => $cond,
            'date'          => $date,
            'sum'           => $sum,
            'reser_price'   => $reser_val,
            'cancel_price'  => $cancel_price,
            'temporal_list'   => $temporal_list,
            'canceltemporal_list' => $canceltemporal_list,
             'result_list'   => $result_list
        ];

        return View('pages.admin.booking.salesauth')->with($data);
    }
    //get reservation price
    public function getAuthReservation($date , $cond , $shop_id) {
        $ret = (object)array();
        $booking = array();
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
                    ->whereIn('status',$statuses);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        $bookings    = $bookings->where('created_at','LIKE', '%'.$date.'%');

        $bookings = $bookings->orderBy('id','asc')->get();
        $count = 0;
        foreach ($bookings as $de) {
            if($de->portal_flag == '1') {
                if($de->payment == '0')
                    $default_price = $de->web_payment;
                else
                    $default_price = $de->payment;
            }else {
                $default_price = $de->payment;
            }
            $given_points = $de->given_points;
            $price += $default_price + $given_points ;
            $booking[$de->id] = $default_price;
            if(($default_price + $given_points )> 0)
                $count++;
        }
        $orignal_count = count($booking);
        $ret->original_count = $orignal_count;
        ///////////////////get booking price_list
        $bookings_price = \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
            ->whereIn('bo.status',$statuses);
        if($shop_id != 0)
            $bookings_price = $bookings_price->where('bo.pickup_id','=', $shop_id);
        $bookings_price     = $bookings_price->where('pr.created_at','LIKE', '%'.$date.'%')
                            ->select(['pr.*','bo.depart_task','bo.return_task'])->get();

        foreach ($bookings_price as $de) {
            $book_id = $de->book_id;
            $default_price  = $de->total_price;
            $price += $default_price;
            //if(!empty($booking[$book_id])) $booking[$book_id] = $booking[$book_id]+$default_price;
            //else $booking[$book_id] = $default_price;
            if($default_price > 0)
                $count++;
        }
        $additional_count = $count-$orignal_count;
        $ret->additional_count = $additional_count;
        $ret->price = $price;
        $ret->count = count($booking);
        return $ret;
    }
    //auth reservation
    public function getAuthReservationcountDay($fukuoka_id, $cond, &$result_list) {
        $status = array("1","2","3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status', $status);

            $booking_fuku = clone $bookings;
            $booking_fuku = $booking_fuku->where('pickup_id', '=', $fukuoka_id);
            $count = 0;
            foreach ($result_list as $re) {
                $sel_date = $re->date;
                $bookings_depart = clone $booking_fuku;

                $bookings_depart = $bookings_depart->where('created_at', 'LIKE', '%' . $sel_date . '%')->orderBy('id', 'asc')->get();

                $book_count = array();
                $increase = 0;
                $price = 0;
                foreach ($bookings_depart as $de) {
                    $price += $de->payment + $de->given_points;
                    $increase++;
                }

                //loop boooking price
                $booking_prices = \DB::table('bookings_price as pr')
                    ->leftjoin('bookings as bo', 'bo.id', '=', 'pr.book_id')
                    ->whereIn('bo.status', $status)
                    ->where('bo.pickup_id', $fukuoka_id)
                    ->where('pr.created_at', 'LIKE', '%' . $sel_date . '%')
                    ->orderBy('pr.id', 'asc')
                    ->select(['pr.*', 'bo.depart_task', 'bo.return_task'])->get();

                foreach ($booking_prices as $de) {
                    $price += $de->total_price;
                    //$increase++;
                }
                if($cond == 'fuku') {
                    $result_list[$count]->fuku_reservation_number = $increase;
                    $result_list[$count]->fuku_reservation_price = $price;
                }
                if($cond == 'okina') {
                    $result_list[$count]->okina_reservation_number = $increase;
                    $result_list[$count]->okina_reservation_price = $price;
                }
                if($cond == 'kagoshima') {
                    $result_list[$count]->kagoshima_reservation_number = $increase;
                    $result_list[$count]->kagoshima_reservation_price = $price;
                }

                $count++;
            }

    }
    // //get auth  cancellation
    public function getAuthCancellationcountDay($shop_id, $cond, &$result_list) {
        $status = array("9");
        $bookings = \DB::table('bookings')
            ->whereIn('status', $status);

            $booking_fuku = clone $bookings;
            $booking_fuku = $booking_fuku->where('pickup_id', '=', $shop_id);

            $count = 0;
            foreach ($result_list as $re) {
                $sel_date = $re->date;
                $bookings_depart = clone $booking_fuku;
                $bookings_depart = $bookings_depart->where('cancel_date', 'LIKE', '%' . $sel_date . '%')->orderBy('id', 'asc')->get();
                $increase = 0;
                $price = 0;
                foreach ($bookings_depart as $de) {
                    if($de->payment > 0) {
                        $price += $de->payment + $de->given_points;
                        $increase++;
                    }
                }
                //get price from bookings price
                $bookings_price = \DB::table('bookings_price as pr')
                    ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
                    ->where('bo.status','9');
                if($shop_id != 0)
                    $bookings_price = $bookings_price->where('bo.pickup_id','=', $shop_id);
                $bookings_price     = $bookings_price->where('bo.cancel_date','LIKE', '%'.$sel_date.'%')->orderBy('bo.id','asc')
                    ->select(['pr.*'])
                    ->get();
                foreach ($bookings_price as $de) {
                    $price += $de->total_price;
                }

                if($cond == 'fuku') {
                    $result_list[$count]->fuku_cancel_number = $increase;
                    $result_list[$count]->fuku_cancel_price = $price;
                }
                if($cond == 'okina') {
                    $result_list[$count]->okina_cancel_number = $increase;
                    $result_list[$count]->okina_cancel_price = $price;
                }
                if($cond == 'kagoshima') {
                    $result_list[$count]->kagoshima_cancel_number = $increase;
                    $result_list[$count]->kagoshima_cancel_price = $price;
                }
                $count++;
            }

    }
    //get sales sum for everyday
    public function getSalescountDay($shop_id, &$result_list) {
        $status = array("1","2","3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->where('pay_method', '>','0')
            ->whereIn('status', $status)
            ->where('pay_status','1');
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        $count = 0 ;
        foreach ($result_list as $re) {
            $sel_date = $re->date;
            $bookings_depart = clone $bookings;
            $bookings_depart = $bookings_depart
                //->where('paid_date', 'LIKE', '%' . $sel_date . '%')->orderBy('id', 'asc')
                        ->where('departing', 'LIKE', '%' . $sel_date . '%')->orderBy('id', 'asc')
                        ->where('depart_task', '1')
                        ->get();
            $book_count = array();
            foreach ($bookings_depart as $de) {
                //if($de->pay_method == '4' && $de->depart_task != '1' ) continue;
                $book_id = $de->id;
                $book_count[$book_id] = $de->payment;
            }

            //loop boooking price
//            $booking_prices = \DB::table('bookings_price as pr')
//                ->leftjoin('bookings as bo', 'bo.id', '=', 'pr.book_id')
//                ->where('pr.pay_method','>','0')
//                ->where('pr.pay_status', '1')
//                ->whereIn('bo.status', $status);
//            if ($shop_id != 0)
//                $booking_prices = $booking_prices->where('bo.pickup_id', '=', $shop_id);
//
//            $bookings_price_depart = clone $booking_prices;
//            $bookings_price_depart = $bookings_price_depart->where('pr.paid_date', 'LIKE', '%' . $sel_date . '%')
//                //->where('pr.price_type', '1')
//                //->where('bo.depart_task' , '1')
//                ->orderBy('pr.id', 'asc')->select(['pr.*', 'bo.depart_task', 'bo.return_task'])->get();
//
//            foreach ($bookings_price_depart as $de) {
//                $book_id = $de->book_id;
//                if($de->pay_method == '4' && $de->depart_task != '1' ) continue;
//                if(!empty($book_count[$book_id])) {
//                    $book_count[$book_id] = $book_count[$book_id] + $de->total_price;
//                }else {
//                    $book_count[$book_id] = $de->total_price;
//                }
//            }
            $result_list[$count]->number = count($book_count);
            $count ++;
        }
    }
    //get sales sum for everyday
    public function getSalesMountDay($shop_id, $pay_method, &$result_list,$cond) {
        $status = array("1","2","3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
            ->whereIn('status', $status)
            ->where(function ($query1) use ($pay_method) {
                $query1->where('pay_method', $pay_method)
                    ->orwhere('cancel_status', $pay_method);
            });
            //->where('pay_status','1');
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
//        if($pay_method == '4')
//            $bookings = $bookings->where('depart_task', '1');

        $bookings_cancel = \DB::table('bookings')
            ->whereIn('status', $status);
        if($shop_id != 0)
            $bookings_cancel = $bookings_cancel->where('pickup_id','=', $shop_id);

        $count = 0 ;

        foreach ($result_list as $re) {
            $price = 0;
            $sel_date = $re->date;
            $bookings_depart = clone $bookings;
            if($pay_method == 4) {
                $booking_portal = clone $bookings_depart;
                $booking_portal = $booking_portal
                    ->where('depart_task', '1')
                    ->where(function ($query2) use ($sel_date) {
                                $query2->where('departing','LIKE', '%'.$sel_date.'%')
                                        ->orwhere('cancel_date','LIKE', '%'.$sel_date.'%');
                    })
                    ->select(DB::raw("SUM(web_payment) as sumpayment, sum(cancel_fee) as cancelsum"))->first();
                $price = $booking_portal->sumpayment + $booking_portal->cancelsum;
            }
            //if pay method  == 3
            if($pay_method == 3) {
                $booking_web = clone $bookings_depart;
                $booking_web = $booking_web
                    ->where('paid_date','LIKE', '%'.$sel_date.'%')
                    ->select(DB::raw("SUM(web_payment) as web_payment"))->first();
                $web_payment = $booking_web->web_payment;
                $booking_web_cancel = clone $bookings_depart;
                $booking_web_cancel = $booking_web_cancel
                    ->where('cancel_date','LIKE', '%'.$sel_date.'%')
                    ->select(DB::raw("SUM(cancel_total) as cancel_total"))->first();
                $web_cancel = $booking_web_cancel->cancel_total;
                $price += $web_payment - $web_cancel;
            }
            if($pay_method == 1) {
                $booking_cash = clone $bookings_depart;
                $booking_cash = $booking_cash
                    ->where('portal_flag', '0')
                    ->where('depart_task', '1')
                    ->where('pay_method', $pay_method)
                    ->where('departing','LIKE', '%'.$sel_date.'%')
                    ->select(DB::raw("SUM(payment) as payment"))->first();
                $price += $booking_cash->payment;

                $booking_cash2 = clone $bookings_depart;
                $booking_cash2 = $booking_cash2
                    ->where('portal_flag', '1')
                    ->where('depart_task', '1')
                    ->where('pay_method', $pay_method)
                    ->where('departing','LIKE', '%'.$sel_date.'%')
                    ->select(DB::raw("SUM(payment) as payment"))->first();
                $price += $booking_cash2->payment;

                $booking_cash1 = clone $bookings_cancel;
                $booking_cash1 = $booking_cash1
                    ->where(function ($query1) use($sel_date) {
                        $query1->where('cancel_status', '1')
                            ->orwhere('cancel_status', '5');
                    })
                    ->where('cancel_date','LIKE', '%'.$sel_date.'%')
                    ->select(DB::raw("SUM(cancel_fee) as cancel_fee"))->first();
                $price += $booking_cash1->cancel_fee;
            }
            if($pay_method == 2) {
                $booking_credit = clone $bookings_depart;
                $booking_credit = $booking_credit
                    ->where('portal_flag','0')
                    ->where('depart_task','1')
                    ->where('pay_method', $pay_method)
                    ->where('departing','LIKE', '%'.$sel_date.'%')
                    ->select(DB::raw("SUM(payment) as payment"))->first();
                $price += $booking_credit->payment;

                $booking_credit1 = clone $bookings_depart;
                $booking_credit1 = $booking_credit1
                    ->where('portal_flag','1')
                    ->where('depart_task','1')
                    ->where('pay_method', $pay_method)
                    ->where('departing','LIKE', '%'.$sel_date.'%')
                    ->select(DB::raw("SUM(payment) as payment"))->first();
                $price += $booking_credit1->payment;

                $booking_credit2 = clone $bookings_cancel;
                $booking_credit2 = $booking_credit2
                    ->where('cancel_status', $pay_method)
                    ->where('cancel_date','LIKE', '%'.$sel_date.'%')
                    ->select(DB::raw("SUM(cancel_fee) as cancel_fee"))->first();
                $price += $booking_credit2->cancel_fee;
            }
            //loop boooking price
            $booking_prices = \DB::table('bookings_price as pr')
                ->leftjoin('bookings as bo', 'bo.id', '=', 'pr.book_id')
                ->where('pr.pay_status', '1')
                ->where('pr.pay_method', $pay_method)
                ->whereIn('bo.status', $status);
            if ($shop_id != 0)
                $booking_prices = $booking_prices->where('bo.pickup_id', '=', $shop_id);

            $bookings_price_depart = clone $booking_prices;
            $bookings_price_depart = $bookings_price_depart
                                    ->where('bo.departing','LIKE', '%'.$sel_date.'%')
                                    ->where('bo.depart_task', '1')
                                    ->where('pr.price_type', '1')
                                    ->select(DB::raw("SUM(total_price) as sumpayment"))->first();
            $price += $bookings_price_depart->sumpayment;

            $bookings_price_return  = clone $booking_prices;
            $bookings_price_return  = $bookings_price_return
                ->where(function ($query1) use($sel_date) {
                    $query1->where(function($query2) use($sel_date){
                        $query2 ->whereDate('bo.returning','>=', 'bo.returning_updated')
                            ->where('bo.returning','LIKE', '%'.$sel_date.'%');
                    })
                        ->orwhere(function($query4) use($sel_date){
                            $query4 ->where('bo.returning_updated','0000-01-01 00:00:00')
                                ->where('bo.returning','LIKE', '%'.$sel_date.'%');
                        })
                        ->orwhere(function($query3) use($sel_date){
                            $query3 ->whereDate('bo.returning','<', 'bo.returning_updated')
                                ->where('bo.returning_updated','LIKE', '%'.$sel_date.'%');
                        });
                })
                ->where('bo.return_task', '1')
                ->where('pr.price_type', '2')
                ->select(DB::raw("SUM(total_price) as sumpayment"))->first();
            $price += $bookings_price_return->sumpayment;


            if($pay_method == '1') $result_list[$count]->cashsum = $price;
            if($pay_method == '2') $result_list[$count]->creditsum = $price;
            if($pay_method == '3') $result_list[$count]->websum = $price;
            if($pay_method == '4') $result_list[$count]->portalsum = $price;
            $count ++;
        }
    }


    //get adjustment price
    public function getSalesAdjustmentMountDay($shop_id,&$result_list) {

        $status = array("1","2","3","4","5","6","7","8","9", "10");
        $pay_methods = array("1","2","3"); //cash, credit, web
        $bookings = \DB::table('bookings')
            ->where('pay_status','1')
            ->where('depart_task','1')
            ->whereIn('status', $status);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);

        $count = 0 ;
        foreach ($result_list as $re) {
            $price = 0;
            $sel_date = $re->date;
            $bookings_deaprt_portal = clone $bookings;
            $bookings_deaprt_portal = $bookings_deaprt_portal
                ->where('departing', 'LIKE', '%' . $sel_date . '%')
                ->select(DB::raw("SUM(given_points) as sumpayment"))->first();
            $price += $bookings_deaprt_portal->sumpayment;
            $result_list[$count]->adjustmentsum = $price;
            $count ++;
        }
    }

    public function getSalesCancelMountDay($shop_id, &$result_list) {

        $st = "9";//cancel status
        $price = 0;
        //$statuses = [3,4,5,6]; //1未請求＝Unclaimed , 2請求中＝Claiming, 3カード＝Paid by credit card,4現金＝Paid by cash , 5振込＝Paid by cash (bank transfer), 6未払いで完了＝finished with unclaim
        $bookings = \DB::table('bookings')
            ->where('status',$st);
          //  ->whereIn('cancel_status', $statuses);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        $count = 0 ;
        foreach ($result_list as $re) {
            $price = 0;
            $cash_price = 0;
            $credit_price = 0;
            $_price = 0;
            $sel_date = $re->date;
            $bookings_deaprt = clone $bookings;
            $bookings_deaprt = $bookings_deaprt->where('cancel_date', 'LIKE', '%' . $sel_date . '%')->orderBy('id', 'asc')->get();;

            foreach ($bookings_deaprt as $de) {
                if($de->pay_method != '3') continue;
                $payment = $de->cancel_total;
                $cancel_fee = $de->cancel_fee;
                if(empty($de->cancel_fee)) $cancel_fee = 0;
                $cancel_status = $de->cancel_status;
                if($cancel_status == '1' || $cancel_status == '5') {
                    $cash_price += $cancel_fee;
                }
                if($cancel_status == '2') {
                    $credit_price += $cancel_fee;
                }
                $price += $payment;
            }

            $result_list[$count]->cancelsum = -1 * $price;
            $result_list[$count]->cashsum = $result_list[$count]->cashsum + $cash_price ;
            $result_list[$count]->creditsum = $result_list[$count]->creditsum + $credit_price ;
            $count ++;
        }
    }
    //get cash
    public function getSalesMount($date , $cond , $shop_id, $pay_method) {
        $bookings_depart = array();
        $price = 0;
        $status = array("1","2","3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
                    ->where('pay_method', '>', '0')
                    ->where('pay_status','1');
                    //->whereIn('status', $status);

        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);

        $bookings_cancel = \DB::table('bookings');
        if($shop_id != 0)
            $bookings_cancel = $bookings_cancel->where('pickup_id','=', $shop_id);

        $sel_date = $date;
        $bookings_depart = clone $bookings;
        if($pay_method == 4) {
            $booking_portal = clone $bookings_depart;
            $booking_portal = $booking_portal
                ->where('depart_task', '1')
                ->where(function ($query1) use ($pay_method) {
                    $query1->where('pay_method', $pay_method)
                        ->orwhere('cancel_status', $pay_method);
                })
                ->where(function ($query2) use ($sel_date) {
                     $query2->where('departing','LIKE', '%'.$sel_date.'%')
                        ->orwhere('cancel_date','LIKE', '%'.$sel_date.'%');

                })
                ->select(DB::raw("SUM(web_payment) as sumpayment, sum(cancel_fee) as cancelsum"))->first();
            $price = $booking_portal->sumpayment + $booking_portal->cancelsum;
        }

        //if pay method  == 3
        if($pay_method == 3) {
            $booking_web = clone $bookings_depart;
            $booking_web = $booking_web
                ->where(function ($query1) use ($pay_method) {
                    $query1->where('pay_method', $pay_method)
                        ->orwhere('cancel_status', $pay_method);
                })
                ->where('paid_date','LIKE', '%'.$sel_date.'%')
                ->select(DB::raw("SUM(web_payment) as web_payment"))->first();
            $web_payment = $booking_web->web_payment;
            $booking_web_cancel = clone $bookings_depart;
            $booking_web_cancel = $booking_web_cancel
                ->where('pay_method','3')
                ->where('cancel_date','LIKE', '%'.$sel_date.'%')
                ->select(DB::raw("SUM(cancel_total) as cancel_total"))->first();
            $web_cancel = $booking_web_cancel->cancel_total;
            $price = $web_payment - $web_cancel;
        }

        if($pay_method == 1) {
            $booking_cash = clone $bookings_depart;
            $booking_cash = $booking_cash
                ->where('portal_flag', '0')
                ->where('depart_task', '1')
                ->where('pay_method', $pay_method)
                ->where('departing','LIKE', '%'.$date.'%')
                ->select(DB::raw("SUM(payment) as payment"))->first();
            $price = $booking_cash->payment;

            $booking_cash2 = clone $bookings_depart;
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
                ->where(function ($query1) use($sel_date) {
                    $query1->where('cancel_status', '1')
                        ->orwhere('cancel_status', '5');
                })
                ->where('cancel_date','LIKE', '%'.$sel_date.'%')
                ->select(DB::raw("SUM(cancel_fee) as cancel_fee"))->first();
            $price += $booking_cash1->cancel_fee;
        }

        if($pay_method == 2) {
            $booking_credit = clone $bookings_depart;
            $booking_credit = $booking_credit
                ->where('portal_flag','0')
                ->where('depart_task','1')
                ->where('pay_method', $pay_method)
                ->where('departing','LIKE', '%'.$date.'%')
                ->select(DB::raw("SUM(payment) as payment"))->first();
            $price = $booking_credit->payment;

            $booking_credit1 = clone $bookings_depart;
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
                ->where('cancel_date','LIKE', '%'.$sel_date.'%')
                ->select(DB::raw("SUM(cancel_fee) as cancel_fee"))->first();
            $price += $booking_credit2->cancel_fee;
        }
        //loop boooking price
        $booking_prices =  \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
            ->where('pr.pay_method',$pay_method)
            ->where('pr.pay_status','1')
            ->whereIn('bo.status', $status);
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

    //get adjustment price
    public function getSalesAdjustmentMount($date , $cond , $shop_id) {
        $price = 0;
        $status = array("1","2","3","4","5","6","7","8","9","10");
        $bookings = \DB::table('bookings')
                    ->where('pay_method','>','0')
                    ->where('pay_status','1')
                    ->whereIn('status', $status);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);

        $bookings_deaprt    = $bookings->where('departing','LIKE', '%'.$date.'%')
            ->where('depart_task','1')
            ->select(DB::raw("SUM(given_points) as sumpayment"))->first();
        $price += $bookings_deaprt->sumpayment;
        return $price;
    }

    //cancelllation fee   
    public function getSalesCancelMount($date , $cond , $shop_id) {
        $bookings_deaprt = array();
        $bookings_return = array();
        $st = "9";//cancel status
        $price = 0;
        $statuses = [3,4,5,6]; //1未請求＝Unclaimed , 2請求中＝Claiming, 3カード＝Paid by credit card,4現金＝Paid by cash , 5振込＝Paid by cash (bank transfer), 6未払いで完了＝finished with unclaim
        $bookings = \DB::table('bookings')
                    ->where('status',$st);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id','=', $shop_id);
        $bookings_depart    = $bookings->where('cancel_date','LIKE', '%'.$date.'%')->orderBy('id','asc')->get();;
        foreach ($bookings_depart as $de) {
            $price += $de->cancel_total;
        }
        return $price;
    }

    //cancelllation fee
    public function getAuthCancelMount($date , $cond , $shop_id) {
        $price = 0;
        $st = array("9");
        $statuses = array("1","2"); //1未請求＝Unclaimed , 2請求中＝Claiming, 3カード＝Paid by credit card,4現金＝Paid by cash , 5振込＝Paid by cash (bank transfer), 6未払いで完了＝finished with unclaim
        $bookings = \DB::table('bookings')
            ->where('status', $st);
            //->whereIn('cancel_status',$statuses);
        if($shop_id != 0)
            $bookings = $bookings->where('pickup_id' , $shop_id);

        $bookings    = $bookings->where('cancel_date','LIKE', '%'.$date.'%')->orderBy('id','asc')->get();

        $booking = array();
        $count = 0;
        foreach ($bookings as $de) {
            //$price += $de->cancel_total;
            $price += $de->payment + $de->given_points;
            $count++;
        }
        //calculate booking price
         $bookings_price = \DB::table('bookings_price as pr')
             ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
            ->where('bo.status', '9');
        if($shop_id != 0)
            $bookings_price = $bookings_price->where('bo.pickup_id' , $shop_id);

        $bookings_price    = $bookings_price->where('bo.cancel_date','LIKE', '%'.$date.'%')->get();
        foreach ($bookings_price as $de) {
            $price += $de->total_price;
        }

        $ret = (object)array();
        $ret->price = $price;
        $ret->count = $count;
        return $ret;
    }
    //get poratl list and mount
    public function salesPortalList($date, $cond, $shop_id) {
            $portal_list = \DB::table('portal_site')
                //->where('show_flag','1')
                ->get();
            $portals = array();
            foreach ($portal_list as $po) {
                $portal_name = $po->name;
                $flag = true;
                switch ($portal_name){
                    case '電話' :
                        $flag = false;
                        break;
                    case '知人' :
                        $flag = false;
                        break;
                    case '取引業者' :
                        $flag = false;
                        break;
                    case '他ポータル' :
                        $flag = false;
                        break;
                    case 'その他' :
                        $flag = false;
                        break;
                    case '予約フォーム' :
                        $flag = false;
                        break;
                }
                $po->price = 0;
                $bgcolor = ServerPath::portalColor($po->id);
                $po->bgcolor = $bgcolor;
                $po->flag = $flag;
                $portals[$po->id] = $po;
            }
            $hakoren_front= (object)array();
            $hakoren_front->id    = '10000';
            $hakoren_front->name  = '自社HP';
            $hakoren_front->alias = '自社HP';
            $hakoren_front->price = 0;
            $hakoren_front->bgcolor = '#e737ff';
            $hakoren_front->flag = true;
            $portals[10000] = $hakoren_front;

            $hakoren_admin= (object)array();
            $hakoren_admin->id    = '10001';
            $hakoren_admin->name  = '自社HPAD';
            $hakoren_admin->alias = '自社HPAD';
            $hakoren_admin->price = 0;
            $hakoren_admin->bgcolor = '#fcb722';
            $hakoren_admin->flag = true;
            $portals[10001] = $hakoren_admin;
            $status = array("1","2","3","4","5","6","7","8","9","10"); //except cancel(9)
            $bookings = \DB::table('bookings as b')                
                ->where('b.pay_status','1')
                ->where('b.depart_task','1')
                ->whereIn('b.status' ,$status)
                ->where('b.portal_flag','1');
                //->where('b.pay_method', '4') ;
            
            $bookings_deaprt = array();
            $bookings_return = array();        
            if($shop_id != 0)
                $bookings = $bookings->where('pickup_id','=', $shop_id);

            $bookings_deaprt    = $bookings->where('departing','LIKE', '%'.$date.'%')->orderBy('id','asc')->get();;

            $price = 0;
            foreach ($bookings_deaprt as $de) {
                $default_price = $de->payment;
                $portal_id = $de->portal_id;
                if(empty($de->portal_id)) continue;
                if($portal_id == '0' ) continue;
                if($de->portal_flag == '1') {
                    if ($de->pay_method == '4') $default_price = $de->web_payment;
                    else $default_price = 0;
                }
                $origin_price   = $portals[$portal_id]->price;
                $given_point    = $de->given_points;
                $book_id = $de->id;
                $price = intval($default_price) + intval($origin_price) + intval($given_point);
                $portals[$portal_id]->price = $price;
            }
        return $portals;
    }

    //get departure for sales items
    public function getSalesdepatMount($date , $cond , $shop_id, $list_cond, $reservation) {
        $ret = array();
        $bookings_depart = array();
        $bookings_return = array();
        $price = 0;
        $status = array("1","2","3","4","5","6","7","8","9","10"); //except cancel(9)
        $bookings = \DB::table('bookings as bo')
                    ->leftjoin('portal_site as po', 'po.id','=','bo.portal_id')
                    ->leftjoin('users as u', 'bo.client_id','=','u.id')
                    ->where('bo.pay_method', '>=', '1')
                    ->whereIn('bo.status' ,$status)
                    ->where('bo.pay_status', '1');

        if($shop_id != 0)
            $bookings = $bookings->where('bo.pickup_id','=', $shop_id);
        $bookings_depart    = clone $bookings;
        $bookings_depart    = $bookings_depart
                                    ->where('departing','LIKE', '%'.$date.'%')//
                                    ->orderBy('id','asc')
                                    ->select(['bo.*','po.name as portal_name','u.first_name','u.last_name'])->get();
        if($list_cond == 'depart') {
            foreach ($bookings_depart as $de) {
                if($de->web_payment > 0 && $de->pay_method == '3') continue;
                if( $de->pay_method != '3' && $de->depart_task == '0') continue;
                $one = (object)array();
                $portal_name    = $de->portal_name;
                if($de->portal_id == '10000' && $de->language == 'ja') $portal_name = '自社HP';
                if($de->portal_id == '10000' && $de->language == 'en') $portal_name = '自社HPEN';
                if($de->portal_id == '10001') $portal_name = '自社HPAD';
                $booking_id     = $de->booking_id;
                $book_id        = $de->id;
                $name           = $de->booking_id;
                $payment        = $de->payment;
                $basic_price    = $de->basic_price;
                $insurance1     = $de->insurance1;
                $insurance2     = $de->insurance2;
                $etc_card       = $de->etc_card;
                $paid_options       = explode("," ,$de->paid_options);
                $paid_options_price = explode("," ,$de->paid_options_price);
                $paid_options_number= explode("," ,$de->paid_option_numbers);
                $class_id       = $de->class_id;
                $etc            = 0;
                $baby_seat      = 0;
                $child_seat     = 0;
                $junior_seat    = 0;
                $snow_type      = 0;
                $smart_return   = 0;
                $hotel_return   = 0;

                if(!empty($paid_options)) {
                    $caroptions = \DB::table('car_option as co')
                        ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                        ->where('co.type', 0)
                        ->where('coc.class_id', $class_id)
                        ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                        ->orderby('co.order','asc')
                        ->get();
                    $m = 0;
                    $all_option_price = 0;
                    foreach($caroptions as $op) {
                        $pa_op_price = 0;
                        $pa_op_number = 0;
                        for($i=0; $i<count($paid_options); $i++){
                            if($paid_options[$i] == $op->option_id) {
                                $op_price = $paid_options_price[$i];
                                if(!is_numeric($op_price)) $op_price = 0;
                                $op_number = $paid_options_number[$i];
                                if(!is_numeric($op_number)) $op_number = 0;
                                $pa_op_price = $op_price * $op_number;
                            }
                        }
                        $index = $op->index;
                        if($index == '250') $index = '250';
                        if($index == '255') $index = '250';
                        if($index == '254') $index = '250';
                        if($index == '253') $index = '250';
                        if($index == '252') $index = '250';
                        if($index == '251') $index = '250';
                        switch($index) {
                            case '25':
                                $etc = $pa_op_price;
                                break;
                            case '23':
                                $baby_seat = $pa_op_price;
                                break;
                            case '22':
                                $child_seat = $pa_op_price;
                                break;
                            case '24':
                                $junior_seat = $pa_op_price;
                                break;
                            case '26':
                                $snow_type = $pa_op_price;
                                break;
                            case '106':
                                $smart_return += $pa_op_price;
                                break;
                            case '38':
                                $smart_return += $pa_op_price;
                                break;
                            case '250':
                                $hotel_return = $pa_op_price;
                                break;
                        }
                        $all_option_price +=$pa_op_price;
                    }

                }
                $adjustment_price   = $de->discount;
                $extend_price       = $de->extend_payment;
                $point              = $de->given_points;
                $one->booking_id    = $booking_id;
                $one->book_id        = $book_id;
                $one->portal_name   = $portal_name;
                $one->pay_method    = $this->getpayMethodName($de->pay_method);
                $one->name           = $name;
                $one->user_name      = $de->last_name." ".$de->first_name;
                if($de->portal_flag == '1') {
                    $portal =  (object)json_decode($de->portal_info);
                    $one->user_name = $portal->last_name . " " . $portal->first_name;
                }
                $one->payment        = $payment;
                $one->basic_price    = $basic_price;
                $one->insurance1     = $insurance1;
                $one->insurance2     = $insurance2;
                $one->etc_card       = $etc_card;
                $one->etc            = $etc;
                $one->baby_seat      = $baby_seat;
                $one->child_seat     = $child_seat;
                $one->junior_seat    = $junior_seat;
                $one->snow_type      = $snow_type;
                $one->smart_return   = $smart_return;
                $one->hotel_return   = $hotel_return;
                $one->adjustment_price   = $adjustment_price;
                $one->extend_price       = $extend_price;
                $one->point              = $point;
                array_push($ret,$one);
            }
            //depart booking _price
            $bookig_price_depart = \DB::table('bookings_price as pr')
                ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
                ->leftjoin('users as u', 'bo.client_id','=','u.id')
                ->where('pr.pay_method','>','0')
                ->where('pr.pay_status','1');
            if($shop_id != 0)
                $bookig_price_depart = $bookig_price_depart->where('bo.pickup_id','=', $shop_id);

            $bookig_price_depart = $bookig_price_depart
                ->where('bo.depart_task', '1')
                ->where('pr.price_type','1')
                ->where('bo.departing','LIKE', '%'.$date.'%')
                ->select(['pr.*','bo.depart_task','bo.portal_id','bo.language','bo.class_id','bo.booking_id','u.first_name','u.last_name','bo.portal_flag'])
                ->get();
            foreach($bookig_price_depart as $bo) {
                $one = (object)array();
                $book_id        = $bo->book_id;
                $insurance1     = $bo->insurance1;
                $insurance2     = $bo->insurance2;
                $portal_id      = $bo->portal_id;
                $booking_id     = $bo->booking_id;
                $name           = $bo->booking_id;
                $etc_card       = $bo->etc_card;
                $basic_price    = 0;
                $portal_name    = "";
                if($portal_id == '10000' || $portal_id == '10001') {
                    if($portal_id == '10000' && $bo->language == 'ja') $portal_name = '自社HP';
                    if($portal_id == '10000' && $bo->language == 'en') $portal_name = '自社HPEN';
                    if($portal_id == '10001') $portal_name = '自社HPAD';
                }else{
                    $portal = \DB::table('portal_site')->where('id',$portal_id)->first();
                    $portal_name = $portal->name;
                }

                $paid_options       = explode("," ,$bo->paid_options);
                $paid_options_price = explode("," ,$bo->paid_options_price);
                $paid_options_number= explode("," ,$bo->paid_options_number);
                $class_id       = $bo->class_id;
                $etc            = 0;
                $baby_seat      = 0;
                $child_seat     = 0;
                $junior_seat    = 0;
                $snow_type      = 0;
                $smart_return   = 0;
                $hotel_return   = 0;
                if(!empty($paid_options)) {
                    $caroptions = \DB::table('car_option as co')
                        ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                        ->where('co.type', 0)
                        ->where('coc.class_id', $class_id)
                        ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                        ->orderby('co.order','asc')
                        ->get();
                    foreach($caroptions as $op) {
                        $pa_op_price = 0;
                        for($i=0; $i<count($paid_options); $i++){
                            if($paid_options[$i] == $op->option_id) {
                                $op_price = $paid_options_price[$i];
                                if(!is_numeric($op_price)) $op_price = 0;
                                $op_number = $paid_options_number[$i];
                                if(!is_numeric($op_number)) $op_number = 0;
                                $pa_op_price = $op_price * $op_number;
                            }
                        }
                        $index = $op->index;
                        if($index == '250') $index = '250';
                        if($index == '255') $index = '250';
                        if($index == '254') $index = '250';
                        if($index == '253') $index = '250';
                        if($index == '252') $index = '250';
                        if($index == '251') $index = '250';
                        switch($index) {
                            case '25':
                                $etc = $pa_op_price;
                                break;
                            case '23':
                                $baby_seat = $pa_op_price;
                                break;
                            case '22':
                                $child_seat = $pa_op_price;
                                break;
                            case '24':
                                $junior_seat = $pa_op_price;
                                break;
                            case '26':
                                $snow_type  = $pa_op_price;
                                break;
                            case '106':
                                $smart_return = $pa_op_price;
                                break;
                            case '38':
                                $smart_return = $pa_op_price;
                                break;
                            case '250':
                                $hotel_return = $pa_op_price;
                                break;
                        }
                    }

                }
                $adjustment_price   = $bo->adjustment_price;
                $extend_price       = $bo->extend_payment;
                $payment            = $bo->total_price;
                $one->booking_id     = $booking_id;
                $one->book_id        = $book_id;
                $one->portal_name    = $portal_name;
                $one->pay_method    = $this->getpayMethodName($bo->pay_method);
                $one->name           = $name;
                $one->user_name      = $bo->last_name." ".$bo->first_name;
                if($de->portal_flag == '1') {
                    $portal =  (object)json_decode($de->portal_info);
                    $one->user_name = $portal->last_name . " " . $portal->first_name;
                }
                $one->payment        = $payment;
                $one->basic_price    = $basic_price;
                $one->insurance1     = $insurance1;
                $one->insurance2     = $insurance2;
                $one->etc_card       = $etc_card;
                $one->etc            = $etc;
                $one->baby_seat      = $baby_seat;
                $one->child_seat     = $child_seat;
                $one->junior_seat    = $junior_seat;
                $one->snow_type      = $snow_type;
                $one->smart_return   = $smart_return;
                $one->hotel_return   = $hotel_return;
                $one->adjustment_price   = $adjustment_price;
                $one->extend_price       = $extend_price;
                $one->point              = 0;
                array_push($ret,$one);
            }
            //
        }

        //return
        if($list_cond == 'return') {
            //depart booking _price
            $bookig_price_return = \DB::table('bookings_price as pr')
                ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
                ->leftjoin('users as u', 'bo.client_id','=','u.id')
                ->where('pr.pay_method','>','0')
                ->where('pr.pay_status','1')
                ->whereIn('bo.status', $status)
                ->where('bo.return_task','1')
                ->where('pr.price_type','2');
               if($shop_id != 0)
                   $bookig_price_return = $bookig_price_return->where('bo.pickup_id','=', $shop_id);
            $bookig_price_return = $bookig_price_return
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
                ->select(['pr.*','bo.portal_id','bo.language','bo.class_id','bo.portal_flag','bo.portal_info','bo.booking_id','u.first_name','u.last_name'])
                ->get();
            foreach($bookig_price_return as $bo) {
                $one = (object)array();
                $book_id        = $bo->book_id;
                $insurance1     = $bo->insurance1;
                $insurance2     = $bo->insurance2;
                $portal_id      = $bo->portal_id;
                $booking_id     = $bo->booking_id;
                $name           = $bo->booking_id;
                $etc_card       = $bo->etc_card;
                $basic_price    = 0;
                $portal_name    = "";
                if($portal_id == '10000' || $portal_id == '10001') {
                    if($portal_id == '10000' && $bo->language == 'ja') $portal_name = '自社HP';
                    if($portal_id == '10000' && $bo->language == 'en') $portal_name = '自社HPEN';
                    if($portal_id == '10001') $portal_name = '自社HPAD';
                }else{
                    $portal = \DB::table('portal_site')->where('id',$portal_id)->first();
                    $portal_name = $portal->name;
                }

                $paid_options       = explode("," ,$bo->paid_options);
                $paid_options_price = explode("," ,$bo->paid_options_price);
                $paid_options_number= explode("," ,$bo->paid_options_number);
                $class_id       = $bo->class_id;
                $etc            = 0;
                $baby_seat      = 0;
                $child_seat     = 0;
                $junior_seat    = 0;
                $snow_type      = 0;
                $smart_return   = 0;
                $hotel_return   = 0;
                if(!empty($paid_options)) {
                    $caroptions = \DB::table('car_option as co')
                        ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                        ->where('co.type', 0)
                        ->where('coc.class_id', $class_id)
                        ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                        ->orderby('co.order','asc')
                        ->get();
                    foreach($caroptions as $op) {
                        $pa_op_price = 0;
                        for($i=0; $i<count($paid_options); $i++){
                            if($paid_options[$i] == $op->option_id) {
                                $op_price = $paid_options_price[$i];
                                if(!is_numeric($op_price)) $op_price = 0;
                                $op_number = $paid_options_number[$i];
                                if(!is_numeric($op_number)) $op_number = 0;
                                $pa_op_price = $op_price * $op_number;
                            }
                        }
                        $index = $op->index;
                        if($index == '250') $index = '250';
                        if($index == '255') $index = '250';
                        if($index == '254') $index = '250';
                        if($index == '253') $index = '250';
                        if($index == '252') $index = '250';
                        if($index == '251') $index = '250';
                        switch($index) {
                            case '25':
                                $etc = $pa_op_price;
                                break;
                            case '23':
                                $baby_seat = $pa_op_price;
                                break;
                            case '22':
                                $child_seat = $pa_op_price;
                                break;
                            case '24':
                                $junior_seat = $pa_op_price;
                                break;
                            case '26':
                                $snow_type  = $pa_op_price;
                                break;
                            case '106':
                                $smart_return = $pa_op_price;
                                break;
                            case '38':
                                $smart_return = $pa_op_price;
                                break;
                            case '250':
                                $hotel_return = $pa_op_price;
                                break;
                        }
                    }

                }
                $adjustment_price   = $bo->adjustment_price;
                $extend_price       = $bo->extend_payment;
                $payment            = $bo->total_price;
                $one->booking_id     = $booking_id;
                $one->book_id        = $book_id;
                $one->portal_name    = $portal_name;
                $one->pay_method    = $this->getpayMethodName($bo->pay_method);
                $one->name           = $name;
                $one->user_name      = $bo->last_name." ".$bo->first_name;
                if($bo->portal_flag == '1') {
                    $portal =  (object)json_decode($bo->portal_info);
                    $one->user_name = $portal->last_name . " " . $portal->first_name;
                }
                $one->payment        = $payment;
                $one->basic_price    = $basic_price;
                $one->insurance1     = $insurance1;
                $one->insurance2     = $insurance2;
                $one->etc_card       = $etc_card;
                $one->etc            = $etc;
                $one->baby_seat      = $baby_seat;
                $one->child_seat     = $child_seat;
                $one->junior_seat    = $junior_seat;
                $one->snow_type      = $snow_type;
                $one->smart_return   = $smart_return;
                $one->hotel_return   = $hotel_return;
                $one->adjustment_price   = $adjustment_price;
                $one->extend_price       = $extend_price;
                $one->point              = 0;
                array_push($ret,$one);
            }
            //
        }
        usort($ret, function($a, $b)
        {
            return strcmp($a->book_id, $b->book_id);
        });
        return $ret;
    }
    //get departure for sales items with QS
    public function getSalesQSMount($date , $cond, $shop_id) {
        $ret = array();
        $bookings_depart = array();
        $price = 0;
        $status = array("1","2","3","4","5","6","7","8","9","10"); //exception cancel
        $bookings = \DB::table('bookings as bo')
            ->leftjoin('portal_site as po', 'po.id','=','bo.portal_id')
            ->leftjoin('users as u', 'bo.client_id','=','u.id')
            ->where('bo.pay_method', '=', '3')
            ->whereIn('bo.status' ,$status)
            ->where('bo.pay_status', '1');

        if($shop_id != 0)
            $bookings = $bookings->where('bo.pickup_id','=', $shop_id);
        $bookings_depart    = clone $bookings;
        $bookings_depart    = $bookings_depart->where('bo.paid_date','LIKE', '%'.$date.'%')->orderBy('id','asc');
        $bookings_depart    = $bookings_depart->select(['bo.*','po.name as portal_name','u.first_name','u.last_name'])->get();

            foreach ($bookings_depart as $de) {
                $one = (object)array();
                $portal_name    = $de->portal_name;
                if($de->portal_id == '10000' && $de->language == 'ja') $portal_name = '自社HP';
                if($de->portal_id == '10000' && $de->language == 'en') $portal_name = '自社HPEN';
                if($de->portal_id == '10001') $portal_name = '自社HPAD';
                $booking_id     = $de->booking_id;
                $book_id        = $de->id;
                $name           = $de->booking_id;
                $payment        = $de->payment;
                $basic_price    = $de->basic_price;
                $insurance1     = $de->insurance1;
                $insurance2     = $de->insurance2;
                $etc_card       = $de->etc_card;
                $paid_options       = explode("," ,$de->paid_options);
                $paid_options_price = explode("," ,$de->paid_options_price);
                $paid_options_number= explode("," ,$de->paid_option_numbers);
                $class_id       = $de->class_id;
                $etc            = 0;
                $baby_seat      = 0;
                $child_seat     = 0;
                $junior_seat    = 0;
                $snow_type      = 0;
                $smart_return   = 0;
                $hotel_return   = 0;

                if(!empty($paid_options)) {
                    $caroptions = \DB::table('car_option as co')
                        ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                        ->where('co.type', 0)
                        ->where('coc.class_id', $class_id)
                        ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                        ->orderby('co.order','asc')
                        ->get();
                    $m = 0;
                    $all_option_price = 0;
                    foreach($caroptions as $op) {
                        $pa_op_price = 0;
                        $pa_op_number = 0;
                        for($i=0; $i<count($paid_options); $i++){
                            if($paid_options[$i] == $op->option_id) {
                                $op_price = $paid_options_price[$i];
                                if(!is_numeric($op_price)) $op_price = 0;
                                $op_number = $paid_options_number[$i];
                                if(!is_numeric($op_number)) $op_number = 0;
                                $pa_op_price = $op_price * $op_number;
                            }
                        }
                        $index = $op->index;
                        if($index == '250') $index = '250';
                        if($index == '255') $index = '250';
                        if($index == '254') $index = '250';
                        if($index == '253') $index = '250';
                        if($index == '252') $index = '250';
                        if($index == '251') $index = '250';
                        switch($index) {
                            case '25':
                                $etc += $pa_op_price;
                                break;
                            case '23':
                                $baby_seat += $pa_op_price;
                                break;
                            case '22':
                                $child_seat += $pa_op_price;
                                break;
                            case '24':
                                $junior_seat += $pa_op_price;
                                break;
                            case '26':
                                $snow_type += $pa_op_price;
                                break;
                            case '106':
                                $smart_return += $pa_op_price;
                                break;
                            case '38':
                                $smart_return += $pa_op_price;
                                break;
                            case '250':
                                $hotel_return += $pa_op_price;
                                break;
                        }
                        $all_option_price +=$pa_op_price;
                    }

                }
                $adjustment_price   = $de->given_points;
                $extend_price       = $de->extend_payment;
                $point              = $de->given_points;
                $one->booking_id    = $booking_id;
                $one->book_id        = $book_id;
                $one->portal_name   = $portal_name;
                $one->pay_method    = $this->getpayMethodName($de->pay_method);
                $one->name           = $name;
                $one->user_name      = $de->last_name." ".$de->first_name;
                if($de->portal_flag == '1') {
                    $portal =  (object)json_decode($de->portal_info);
                    $one->user_name = $portal->last_name . " " . $portal->first_name;
                }
                $one->payment        = $payment;
                $one->basic_price    = $basic_price;
                $one->insurance1     = $insurance1;
                $one->insurance2     = $insurance2;
                $one->etc_card       = $etc_card;
                $one->etc            = $etc;
                $one->baby_seat      = $baby_seat;
                $one->child_seat     = $child_seat;
                $one->junior_seat    = $junior_seat;
                $one->snow_type      = $snow_type;
                $one->smart_return   = $smart_return;
                $one->hotel_return   = $hotel_return;
                $one->adjustment_price   = $adjustment_price;
                $one->extend_price       = $extend_price;
                $one->point              = $point;
                array_push($ret,$one);
            }
        return $ret;
    }
    //get departure for sales items with QS
    public function getSalesQSCancelMount($date , $cond, $shop_id) {
        $ret = array();
        $bookings_depart = array();
        $price = 0;
        $cancel_statuses = [3,4,5,6]; //1未請求＝Unclaimed , 2請求中＝Claiming, 3カード＝Paid by credit card,4現金＝Paid by cash , 5振込＝Paid by cash (bank transfer), 6未払いで完了＝finished with unclaim
        $status = array("9"); //except cancel(9)
        $bookings = \DB::table('bookings as bo')
            ->leftjoin('portal_site as po', 'po.id','=','bo.portal_id')
            ->leftjoin('users as u', 'bo.client_id','=','u.id')
            ->where('bo.pay_method', '=', '3')
            ->where('bo.status' ,'9')
            ->where('bo.pay_status', '1');

        if($shop_id != 0)
            $bookings = $bookings->where('bo.pickup_id','=', $shop_id);

        $bookings_depart    = $bookings->where('bo.cancel_date','LIKE', '%'.$date.'%')->orderBy('id','asc');
        $bookings_depart = $bookings_depart->select(['bo.*','po.name as portal_name','u.first_name','u.last_name'])->get();

        foreach ($bookings_depart as $de) {
            $one = (object)array();
            $portal_name    = $de->portal_name;
            if($de->portal_id == '10000' && $de->language == 'ja') $portal_name = '自社HP';
            if($de->portal_id == '10000' && $de->language == 'en') $portal_name = '自社HPEN';
            if($de->portal_id == '10001') $portal_name = '自社HPAD';
            $booking_id     = $de->booking_id;
            $book_id        = $de->id;
            $name           = $de->booking_id;
            $payment        = $de->cancel_total;
            $basic_price    = $de->basic_price;
            $insurance1     = $de->insurance1;
            $insurance2     = $de->insurance2;
            $etc_card       = $de->etc_card;
            $paid_options       = explode("," ,$de->paid_options);
            $paid_options_price = explode("," ,$de->paid_options_price);
            $paid_options_number= explode("," ,$de->paid_option_numbers);
            $class_id       = $de->class_id;
            $etc            = 0;
            $baby_seat      = 0;
            $child_seat     = 0;
            $junior_seat    = 0;
            $snow_type      = 0;
            $smart_return   = 0;
            $hotel_return   = 0;

            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option as co')
                    ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                    ->where('co.type', 0)
                    ->where('coc.class_id', $class_id)
                    ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                    ->orderby('co.order','asc')
                    ->get();
                $m = 0;
                $all_option_price = 0;
                foreach($caroptions as $op) {
                    $pa_op_price = 0;
                    $pa_op_number = 0;
                    for($i=0; $i<count($paid_options); $i++){
                        if($paid_options[$i] == $op->option_id) {
                            $op_price = $paid_options_price[$i];
                            if(!is_numeric($op_price)) $op_price = 0;
                            $op_number = $paid_options_number[$i];
                            if(!is_numeric($op_number)) $op_number = 0;
                            $pa_op_price = $op_price * $op_number;
                        }
                    }
                    $index = $op->index;
                    if($index == '250') $index = '250';
                    if($index == '255') $index = '250';
                    if($index == '254') $index = '250';
                    if($index == '253') $index = '250';
                    if($index == '252') $index = '250';
                    if($index == '251') $index = '250';
                    switch($index) {
                        case '25':
                            $etc = $pa_op_price;
                            break;
                        case '23':
                            $baby_seat = $pa_op_price;
                            break;
                        case '22':
                            $child_seat = $pa_op_price;
                            break;
                        case '24':
                            $junior_seat = $pa_op_price;
                            break;
                        case '26':
                            $snow_type = $pa_op_price;
                            break;
                        case '106':
                            $smart_return = $pa_op_price;
                            break;
                        case '38':
                            $smart_return = $pa_op_price;
                            break;
                        case '250':
                            $hotel_return = $pa_op_price;
                            break;
                    }
                    $all_option_price +=$pa_op_price;
                }

            }
            $adjustment_price   = $de->given_points;
            $extend_price       = $de->extend_payment;
            $point              = $de->given_points;
            $one->booking_id    = $booking_id;
            $one->book_id        = $book_id;
            $one->portal_name   = $portal_name;
            $one->pay_method    = $this->getpayMethodName($de->pay_method);
            $one->name           = $name;
            $one->user_name      = $de->last_name." ".$de->first_name;
            if($de->portal_flag == '1') {
                $portal =  (object)json_decode($de->portal_info);
                $one->user_name = $portal->last_name . " " . $portal->first_name;
            }
            $one->payment        = -1 * $payment;
            $one->basic_price    = $basic_price;
            $one->insurance1     = $insurance1;
            $one->insurance2     = $insurance2;
            $one->etc_card       = $etc_card;
            $one->etc            = $etc;
            $one->baby_seat      = $baby_seat;
            $one->child_seat     = $child_seat;
            $one->junior_seat    = $junior_seat;
            $one->snow_type      = $snow_type;
            $one->smart_return   = $smart_return;
            $one->hotel_return   = $hotel_return;
            $one->adjustment_price   = $adjustment_price;
            $one->extend_price       = $extend_price;
            $one->point              = $point;
            array_push($ret,$one);
        }
        return $ret;
    }
    //get temporal price list
    public function getTemopralList($date , $cond , $shop_id, $list_cond, $reservation) {
        $ret = array();
        $bookings_depart = array();
        $price = 0;
        $statuses = array("1", "2", "3","4","5","6","7","8","9","10"); //except the cancel
        $bookings = \DB::table('bookings as bo')
            ->leftjoin('users as u', 'bo.client_id','=','u.id')
            ->leftjoin('portal_site as po', 'po.id','=','bo.portal_id');

        if($shop_id != 0)
            $bookings = $bookings->where('bo.pickup_id','=', $shop_id);
        $bookings_depart    = $bookings->where('bo.created_at','LIKE', '%'.$date.'%')->orderBy('id','asc');

        $bookings_depart = $bookings_depart->select(['bo.*','po.name as portal_name','u.first_name','u.last_name'])->whereIn('bo.status',$statuses)->get();

            foreach ($bookings_depart as $de) {
                $one = (object)array();
                $portal_name    = $de->portal_name;
                $user_name      = $de->last_name." ".$de->first_name;
                if($de->portal_flag == '0') {
                    if($de->portal_id == '10000' && $de->language == 'ja') $portal_name = '自社HP';
                    if($de->portal_id == '10000' && $de->language == 'en') $portal_name = '自社HPEN';
                    if($de->portal_id == '10001')
                        $portal_name ='自社HPAD';
                }
                $book_id        = $de->id;
                $name           = $de->booking_id;
                if($de->portal_flag == '1') {
                   $portal = json_decode($de->portal_info);
                   $user_name      = $portal->last_name." ".$portal->first_name;
                   if($de->payment == '0')
                       $payment = $de->web_payment;
                   else
                       $payment = $de->payment;
                }else {
                    $payment = $de->payment;
                }
                $basic_price    = $de->basic_price;
                $insurance1     = $de->insurance1;
                $insurance2     = $de->insurance2;
                $paid_options       = explode("," ,$de->paid_options);
                $paid_options_price = explode("," ,$de->paid_options_price);
                $paid_options_number= explode("," ,$de->paid_option_numbers);
                $class_id       = $de->class_id;
                $etc            = 0;
                $baby_seat      = 0;
                $child_seat     = 0;
                $junior_seat    = 0;
                $snow_type      = 0;
                $smart_return   = 0;
                $hotel_return   = 0;

                if(!empty($paid_options)) {
                    $caroptions = \DB::table('car_option as co')
                        ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                        ->where('co.type', 0)
                        ->where('coc.class_id', $class_id)
                        ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                        ->orderby('co.order','asc')
                        ->get();
                    $m = 0;
                    $all_option_price = 0;
                    foreach($caroptions as $op) {
                        $pa_op_price = 0;
                        $pa_op_number = 0;
                        for($i=0; $i<count($paid_options); $i++){
                            if($paid_options[$i] == $op->option_id) {
                                $op_price = $paid_options_price[$i];
                                if(!is_numeric($op_price)) $op_price = 0;
                                $op_number = $paid_options_number[$i];
                                if(!is_numeric($op_number)) $op_number = 0;
                                $pa_op_price = $op_price * $op_number;
                            }
                        }
                        $index = $op->index;
                        if($index == '250') $index = '250';
                        if($index == '255') $index = '250';
                        if($index == '254') $index = '250';
                        if($index == '253') $index = '250';
                        if($index == '252') $index = '250';
                        if($index == '251') $index = '250';
                        switch($index) {
                            case '25':
                                $etc = $pa_op_price;
                                break;
                            case '23':
                                $baby_seat = $pa_op_price;
                                break;
                            case '22':
                                $child_seat = $pa_op_price;
                                break;
                            case '24':
                                $junior_seat = $pa_op_price;
                                break;
                            case '26':
                                $snow_type = $pa_op_price;
                                break;
                            case '106':
                                $smart_return += $pa_op_price;
                                break;
                            case '38':
                                $smart_return += $pa_op_price;
                                break;
                            case '250':
                                $hotel_return = $pa_op_price;
                                break;
                        }
                        $all_option_price +=$pa_op_price;
                    }

                }
                $adjustment_price   = $de->discount;
                $extend_price       = $de->extend_payment;
                $point              = $de->given_points;


                $one->book_id        = $book_id;
                $one->portal_name   = $portal_name;
                $one->pay_method    = $this->getpayMethodName($de->pay_method);
                $one->name           = $name;
                $one->user_name      = $user_name;
                $one->payment        = $payment;
                $one->basic_price    = $basic_price;
                $one->insurance1     = $insurance1;
                $one->insurance2     = $insurance2;
                $one->etc            = $etc;
                $one->baby_seat      = $baby_seat;
                $one->child_seat     = $child_seat;
                $one->junior_seat    = $junior_seat;
                $one->snow_type      = $snow_type;
                $one->smart_return   = $smart_return;
                $one->hotel_return   = $hotel_return;
                $one->adjustment_price   = $adjustment_price;
                $one->extend_price       = $extend_price;
                $one->point              = $point;
                if($payment > 0)
                    array_push($ret,$one);
            }

        //loop booking price
        $booking_prices = \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
            ->leftjoin('users as u', 'bo.client_id','=','u.id')
            ->whereIn('bo.status',$statuses);
        if($shop_id != 0)
            $booking_prices = $booking_prices->where('bo.pickup_id','=', $shop_id);
         $booking_prices    = $booking_prices->where('pr.created_at','LIKE', '%'.$date.'%')->orderBy('pr.id','asc')
                                ->select(['pr.*','bo.class_id','bo.portal_id','bo.language','bo.booking_id','bo.portal_flag','bo.portal_info','bo.admin_id','bo.depart_task','bo.return_task','u.first_name','u.last_name'])->get();
        foreach($booking_prices as $bo) {
            $one = (object)array();
            $book_id        = $bo->book_id;
            $portal_id      = $bo->portal_id;
            $price_type     = $bo->price_type;
            $depart_task    = $bo->depart_task;
            $return_task    = $bo->return_task;
            $name           = $bo->booking_id;
            $portal_name    = "";
            if($bo->portal_flag == '0') {
                if($bo->portal_id == '10000' && $bo->language == 'ja') $portal_name = '自社HP';
                if($bo->portal_id == '10000' && $bo->language == 'en') $portal_name = '自社HPEN';
                if($bo->portal_id == '10001')
                    $portal_name ='自社HPAD';
            }else {
                $portals = \DB::table('portal_site')->where('id',$portal_id)->first();
                if(!empty($portals))
                    $portal_name    = $portals->name;
                else
                    $portal_name = '';
            }
            $insurance1     = $bo->insurance1;
            $insurance2     = $bo->insurance2;
            $paid_options       = explode("," ,$bo->paid_options);
            $paid_options_price = explode("," ,$bo->paid_options_price);
            $paid_options_number= explode("," ,$bo->paid_options_number);
            $class_id       = $bo->class_id;
            $etc            = 0;
            $baby_seat      = 0;
            $child_seat     = 0;
            $junior_seat    = 0;
            $snow_type      = 0;
            $smart_return   = 0;
            $hotel_return   = 0;
            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option as co')
                    ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                    ->where('co.type', 0)
                    ->where('coc.class_id', $class_id)
                    ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                    ->orderby('co.order','asc')
                    ->get();
                foreach($caroptions as $op) {
                    $pa_op_price = 0;
                    for($i=0; $i<count($paid_options); $i++){
                        if($paid_options[$i] == $op->option_id) {
                            $op_price = $paid_options_price[$i];
                            if(!is_numeric($op_price)) $op_price = 0;
                            $op_number = $paid_options_number[$i];
                            if(!is_numeric($op_number)) $op_number = 0;
                            $pa_op_price = $op_price * $op_number;
                        }
                    }
                    $index = $op->index;
                    if($index == '250') $index = '250';
                    if($index == '255') $index = '250';
                    if($index == '254') $index = '250';
                    if($index == '253') $index = '250';
                    if($index == '252') $index = '250';
                    if($index == '251') $index = '250';
                    switch($index) {
                        case '25':
                            $etc = $pa_op_price;
                            break;
                        case '23':
                            $baby_seat = $pa_op_price;
                            break;
                        case '22':
                            $child_seat = $pa_op_price;
                            break;
                        case '24':
                            $junior_seat = $pa_op_price;
                            break;
                        case '26':
                            $snow_type = $pa_op_price;
                            break;
                        case '106':
                            $smart_return += $pa_op_price;
                            break;
                        case '38':
                            $smart_return += $pa_op_price;
                            break;
                        case '250':
                            $hotel_return = $pa_op_price;
                            break;
                    }
                }

            }
            $adjustment_price   = $bo->adjustment_price;
            $extend_price       = $bo->extend_payment;
            $payment            = $bo->total_price;

            $one->book_id        = $book_id;
            $one->portal_name    = $portal_name;
            $one->pay_method    = $this->getpayMethodName($bo->pay_method);
            $one->name           = $name;
            $one->user_name      = $bo->last_name." ".$bo->first_name;
            if($bo->portal_flag == '1') {
                $portal =  (object)json_decode($bo->portal_info);
                $one->user_name = $portal->last_name . " " . $portal->first_name;
            }
            $one->payment        = $payment;
            $one->basic_price    = 0;
            $one->insurance1     = $insurance1;
            $one->insurance2     = $insurance2;
            $one->etc            = $etc;
            $one->baby_seat      = $baby_seat;
            $one->child_seat     = $child_seat;
            $one->junior_seat    = $junior_seat;
            $one->snow_type      = $snow_type;
            $one->smart_return   = $smart_return;
            $one->hotel_return   = $hotel_return;
            $one->adjustment_price   = $adjustment_price;
            $one->extend_price       = $extend_price;
            $one->point              = 0;
            if($payment > 0)
                array_push($ret, $one);
        }
        return $ret;
    }
    //get  cancellation price list
    public function getCancelTemopralList($date , $cond , $shop_id, $list_cond, $reservation) {
        $ret = array();
        $bookings_depart = array();
        $price = 0;
        $statuses = array("9"); //except the cancel
        $cancel_statuses = array("1","2");
        $bookings = \DB::table('bookings as bo')
            ->leftjoin('users as u', 'bo.client_id','=','u.id')
            ->leftjoin('portal_site as po', 'po.id','=','bo.portal_id')
            ->whereIn('bo.status',$statuses);
        if($shop_id != 0)
            $bookings = $bookings->where('bo.pickup_id','=', $shop_id);
        $bookings_depart    = $bookings->where('bo.cancel_date','LIKE', '%'.$date.'%')->orderBy('id','asc')
                                ->select(['bo.*','po.name as portal_name','u.first_name','u.last_name'])->whereIn('bo.status',$statuses)->get();

        foreach ($bookings_depart as $de) {
            $one = (object)array();
            $portal_name    = $de->portal_name;
            $user_name      = $de->last_name." ".$de->first_name;
            if($de->portal_flag == '0') {
                if($de->portal_id == '10000' && $de->language == 'ja') $portal_name = '自社HP';
                if($de->portal_id == '10000' && $de->language == 'en') $portal_name = '自社HPEN';
                if($de->portal_id == '10001')
                    $portal_name ='自社HPAD';
            }else {
                $portal = json_decode($de->portal_info);
                $user_name      = $portal->last_name." ".$portal->first_name;
            }
            $book_id        = $de->id;
            $name           = $de->booking_id;
            $payment        = $de->payment;
            $basic_price    = $de->basic_price;
            $insurance1     = $de->insurance1;
            $insurance2     = $de->insurance2;
            $paid_options       = explode("," ,$de->paid_options);
            $paid_options_price = explode("," ,$de->paid_options_price);
            $paid_options_number= explode("," ,$de->paid_option_numbers);
            $class_id       = $de->class_id;
            $etc            = 0;
            $baby_seat      = 0;
            $child_seat     = 0;
            $junior_seat    = 0;
            $snow_type      = 0;
            $smart_return   = 0;
            $hotel_return   = 0;

            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option as co')
                    ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                    ->where('co.type', 0)
                    ->where('coc.class_id', $class_id)
                    ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                    ->orderby('co.order','asc')
                    ->get();
                $m = 0;
                $all_option_price = 0;
                foreach($caroptions as $op) {
                    $pa_op_price = 0;
                    $pa_op_number = 0;
                    for($i=0; $i<count($paid_options); $i++){
                        if($paid_options[$i] == $op->option_id) {
                            $op_price = $paid_options_price[$i];
                            if(!is_numeric($op_price)) $op_price = 0;
                            $op_number = $paid_options_number[$i];
                            if(!is_numeric($op_number)) $op_number = 0;
                            $pa_op_price = $op_price * $op_number;
                        }
                    }
                    $index = $op->index;
                    if($index == '250') $index = '250';
                    if($index == '255') $index = '250';
                    if($index == '254') $index = '250';
                    if($index == '253') $index = '250';
                    if($index == '252') $index = '250';
                    if($index == '251') $index = '250';
                    switch($index) {
                        case '25':
                            $etc = $pa_op_price;
                            break;
                        case '23':
                            $baby_seat = $pa_op_price;
                            break;
                        case '22':
                            $child_seat = $pa_op_price;
                            break;
                        case '24':
                            $junior_seat = $pa_op_price;
                            break;
                        case '26':
                            $snow_type = $pa_op_price;
                            break;
                        case '106':
                            $smart_return += $pa_op_price;
                            break;
                        case '38':
                            $smart_return += $pa_op_price;
                            break;
                        case '250':
                            $hotel_return = $pa_op_price;
                            break;
                    }
                    $all_option_price +=$pa_op_price;
                }

            }
            $adjustment_price   = $de->discount;
            $extend_price       = $de->extend_payment;
            $point              = $de->given_points;


            $one->book_id        = $book_id;
            $one->portal_name   = $portal_name;
            $one->name           = $name;
            $one->user_name      = $user_name;
            if($de->portal_flag == '1') {
                $portal =  (object)json_decode($de->portal_info);
                $one->user_name = $portal->last_name . " " . $portal->first_name;
            }

            $one->payment        = $payment;
            $one->pay_method    = $this->getpayMethodName($de->pay_method);
            $one->basic_price    = $basic_price;
            $one->insurance1     = $insurance1;
            $one->insurance2     = $insurance2;
            $one->etc            = $etc;
            $one->baby_seat      = $baby_seat;
            $one->child_seat     = $child_seat;
            $one->junior_seat    = $junior_seat;
            $one->snow_type      = $snow_type;
            $one->smart_return   = $smart_return;
            $one->hotel_return   = $hotel_return;
            $one->adjustment_price   = $adjustment_price;
            $one->extend_price       = $extend_price;
            $one->point              = $point;
            if($payment > 0)
                array_push($ret,$one);
        }

        //loop booking price for cancellation
        $booking_prices = \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
            ->leftjoin('users as u', 'bo.client_id','=','u.id')
            ->leftjoin('portal_site as po', 'po.id','=','bo.portal_id')
            ->whereIn('bo.status',$statuses);
        if($shop_id != 0)
            $booking_prices = $booking_prices->where('bo.pickup_id','=', $shop_id);
        $booking_prices    = $booking_prices->where('bo.cancel_date','LIKE', '%'.$date.'%')->orderBy('id','asc')
            ->select(['pr.*','bo.basic_price','bo.class_id','bo.portal_id','bo.language','bo.booking_id','bo.portal_flag','bo.portal_info','bo.admin_id','bo.depart_task','bo.return_task','po.name as portal_name','u.first_name','u.last_name'])
            ->whereIn('bo.status',$statuses)->get();

        foreach ($booking_prices as $de) {
            if($de->total_price == '0') break;
            $one = (object)array();
            $book_id        = $de->book_id;
            $portal_id      = $de->portal_id;
            $portal_name    = $de->portal_name;
            $user_name      = $de->last_name." ".$de->first_name;
            $name           = $de->booking_id;
            if($de->portal_flag == '0') {
                if($de->portal_id == '10000' && $de->language == 'ja') $portal_name = '自社HP';
                if($de->portal_id == '10000' && $de->language == 'en') $portal_name = '自社HPEN';
                if($de->portal_id == '10001')
                    $portal_name ='自社HPAD';
            }else {
                $portal = json_decode($de->portal_info);
                $user_name      = $portal->last_name." ".$portal->first_name;
            }

            $insurance1     = $de->insurance1;
            $insurance2     = $de->insurance2;
            $paid_options       = explode("," ,$de->paid_options);
            $paid_options_price = explode("," ,$de->paid_options_price);
            $paid_options_number= explode("," ,$de->paid_options_number);
            $class_id       = $de->class_id;
            $etc            = 0;
            $baby_seat      = 0;
            $child_seat     = 0;
            $junior_seat    = 0;
            $snow_type      = 0;
            $smart_return   = 0;
            $hotel_return   = 0;

            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option as co')
                    ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                    ->where('co.type', 0)
                    ->where('coc.class_id', $class_id)
                    ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                    ->orderby('co.order','asc')
                    ->get();
                $m = 0;
                $all_option_price = 0;
                foreach($caroptions as $op) {
                    $pa_op_price = 0;
                    $pa_op_number = 0;
                    for($i=0; $i<count($paid_options); $i++){
                        if($paid_options[$i] == $op->option_id) {
                            $op_price = $paid_options_price[$i];
                            if(!is_numeric($op_price)) $op_price = 0;
                            $op_number = $paid_options_number[$i];
                            if(!is_numeric($op_number)) $op_number = 0;
                            $pa_op_price = $op_price * $op_number;
                        }
                    }
                    $index = $op->index;
                    if($index == '250') $index = '250';
                    if($index == '255') $index = '250';
                    if($index == '254') $index = '250';
                    if($index == '253') $index = '250';
                    if($index == '252') $index = '250';
                    if($index == '251') $index = '250';
                    switch($index) {
                        case '25':
                            $etc = $pa_op_price;
                            break;
                        case '23':
                            $baby_seat = $pa_op_price;
                            break;
                        case '22':
                            $child_seat = $pa_op_price;
                            break;
                        case '24':
                            $junior_seat = $pa_op_price;
                            break;
                        case '26':
                            $snow_type = $pa_op_price;
                            break;
                        case '106':
                            $smart_return += $pa_op_price;
                            break;
                        case '38':
                            $smart_return += $pa_op_price;
                            break;
                        case '250':
                            $hotel_return = $pa_op_price;
                            break;
                    }
                    $all_option_price +=$pa_op_price;
                }

            }
            $adjustment_price   = $de->adjustment_price;
            $extend_price       = $de->extend_payment;
            $payment            = $de->total_price;
            $point              = 0 ;


            $one->book_id        = $book_id;
            $one->portal_name   = $portal_name;
            $one->name           = $name;
            $one->user_name      = $user_name;
            if($de->portal_flag == '1') {
                $portal =  (object)json_decode($de->portal_info);
                $one->user_name = $portal->last_name . " " . $portal->first_name;
            }
            $one->payment        = $payment;
            $one->pay_method    = $this->getpayMethodName($de->pay_method);
            $one->basic_price    = 0;
            $one->insurance1     = $insurance1;
            $one->insurance2     = $insurance2;
            $one->etc            = $etc;
            $one->baby_seat      = $baby_seat;
            $one->child_seat     = $child_seat;
            $one->junior_seat    = $junior_seat;
            $one->snow_type      = $snow_type;
            $one->smart_return   = $smart_return;
            $one->hotel_return   = $hotel_return;
            $one->adjustment_price   = $adjustment_price;
            $one->extend_price       = $extend_price;
            $one->point              = $point;
            if($payment > 0)
                array_push($ret,$one);
        }
        return $ret;
    }
    //get cancellation
    public function getSalesCancellation($date , $cond , $shop_id) {
        $ret = array();
        $price = 0;
        $bo_status = array('9');
        $statuses = array('1','2','5'); //1未請求＝Unclaimed , 2請求中＝Claiming, 1カード＝Paid by credit card,2現金＝Paid by cash , 5振込＝Paid by cash (bank transfer), 6未払いで完了＝finished with unclaim
        $bookings = \DB::table('bookings as bo')
            ->leftjoin('portal_site as po', 'po.id','=','bo.portal_id')
            ->leftjoin('users as u', 'bo.client_id','=','u.id')
            ->whereIn('bo.status',$bo_status)
            ->whereIn('bo.cancel_status',$statuses);
            //->where('bo.cancel_total','>','0');
        if($shop_id != 0)
            $bookings   = $bookings->where('bo.pickup_id','=', $shop_id);
         $bookings       = $bookings->where('bo.cancel_date','LIKE', '%'.$date.'%')->orderBy('id','asc')
                                    ->select(['bo.*','po.name as portal_name','u.first_name','u.last_name'])->get();


            foreach ($bookings as $de) {
                $one = (object)array();
                $portal_name    = $de->portal_name;
                if($de->portal_flag == '0') {
                    if($de->portal_id == '10000' && $de->language == 'ja') $portal_name = '自社HP';
                    if($de->portal_id == '10000' && $de->language == 'en') $portal_name = '自社HPEN';
                    if($de->portal_id == '10001') $portal_name ='自社HPAD';
                }
                $book_id            = $de->id;
                $name               = $de->booking_id;
                $cancel_percent     = $de->cancel_percent;
                $cancel_fee         = $de->cancel_fee;
                $cancel_status      = $de->cancel_status;
                $cancel_total       = $de->cancel_total;
                $one->book_id       = $book_id;
                $one->basic_price   = $de->basic_price;
                $one->name          = $name;
                $one->user_name      = $de->last_name." ".$de->first_name;
                if($de->portal_flag == '1') {
                    $portal =  (object)json_decode($de->portal_info);
                    $one->user_name = $portal->last_name . " " . $portal->first_name;
                }
                $one->portal_name    = $portal_name;
                $one->cancel_percent = $cancel_percent;
                $one->cancel_fee     = $cancel_fee;
                $one->cancel_status  = $this->getcancelPayMethodName($cancel_status) ;
                $one->cancel_total   = $cancel_total;
                array_push($ret,$one);
            }
        return $ret;
    }

}