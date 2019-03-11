<?php

namespace App\Http\Controllers;

use App;
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
use DateTime;
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
class BookingManagementController extends Controller
{

    /** * Create a new controller instance. *
     * @return void */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search_date = $request->has('search_date')? $request->input('search_date') : date('Y/m/d').' - '.date('Y/m/d');
        $condition   = $request->has('condition')? $request->input('condition'): 'submit_date';
        $portal_cond = $request->has('portal_condition')? $request->input('portal_condition') : '';

        $current_user = Auth::user();
        $related_shop = \DB::table('admin_shop')->where('admin_id', $current_user->id)->first();
        $user_shop_id = is_null($related_shop)? 0 : $related_shop->shop_id;

        if($request->has('search_shop')) {
            $search_shop = intval($request->input('search_shop'));
        }else {
            $search_shop = $user_shop_id;
        }

        $dates = explode("-", $search_date);
        $start_date = date("Y-m-d",strtotime($dates[0]));
        $end_date   = date("Y-m-d",strtotime($dates[1]));
        $shops = Shop::all();

        if($condition == 'cancel') {
            $statuses = array("9");
        } else {
            $statuses = array("1","2","3","4","5","6","7","8","10"); //except 8=end, 9 = cancel
        }

        $route = Route::getFacadeRoot()->current()->uri();
        $route_path = explode('/',$route);
        $subroute = $route_path[1];
        $bookings = \DB::table('bookings as b')
            ->leftjoin('users as u', 'b.client_id','=','u.id')
            ->leftjoin('profiles as p', 'b.client_id', '=', 'p.user_id')
            ->leftjoin('car_inventory as ci', 'ci.id','=','b.inventory_id')
            ->leftjoin('booking_status as bs','bs.status','=','b.status')
            ->leftjoin('car_class as cc','cc.id','=','b.class_id')
            ->leftjoin('car_model as cm','cm.id','=','b.model_id')
            ->leftjoin('car_shop as csp','csp.id','=','b.pickup_id')
            ->leftjoin('car_shop as csd','csd.id','=','b.dropoff_id')
            ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
            ->leftjoin('flight_lines as fl','fl.id','=','b.flight_line')
            ->leftjoin('users as ad', 'ad.id','=','b.admin_id')
            ->select(['b.*','ci.model_id','bs.name as booking_status','u.first_name','u.last_name','p.fur_first_name','p.fur_last_name',
                'p.phone','u.email','ci.numberplate1','ci.numberplate2','ci.numberplate3','ci.numberplate4','cc.id as class_id','cc.name as class_name','cm.id as model_id',
                'cm.name as model_name','ci.smoke','csp.id as pickup_id','csp.name as pickup_name',
                'csd.id as drop_id','csd.name as drop_name','ps.name as portal_name','fl.title as flight_name','ci.shortname',
                'ad.last_name as admin_last_name','ad.first_name as admin_first_name'])
            ->whereIn('b.status',$statuses);
        if($search_shop != 0)
            $bookings = $bookings->where('b.pickup_id','=', $search_shop);
        if($portal_cond == 'all') {
            $bookings = $bookings->where('b.portal_flag', '1');
        }
        if($portal_cond == 'assign') {
            $bookings = $bookings->where('b.portal_flag', '1')
                                ->where('b.inventory_id','!=' ,'0');
        }
        if($portal_cond == 'unassign') {
            $bookings = $bookings->where('b.portal_flag', '1')
                ->where('b.inventory_id','0');
        }

        if($condition == 'cancel') {
            $bookings = $bookings->whereDate('b.canceled_at','>=', $start_date)
                ->whereDate('b.canceled_at','<=', $end_date);
        }

        if($condition == 'submit_date') {
            $bookings = $bookings->whereDate('b.created_at','>=', $start_date)
                ->whereDate('b.created_at','<=', $end_date);
        }

        if($condition == 'depart_return') {
            $bookings = $bookings
                ->where(function ($query) use ($start_date, $end_date) {
                    $query->where(function ($query1) use ($start_date, $end_date) {
                                    $query1->whereDate('b.departing', '>=', $start_date)
                                        ->whereDate('b.departing', '<=', $end_date);

                                    })
                        ->orwhere(function ($query2) use ($start_date, $end_date) {
                                    $query2->whereDate('b.returning', '>=', $start_date)
                                        ->whereDate('b.returning', '<=', $end_date)
                                        ->whereDate('b.returning_updated','<=', $end_date);
                                    })
                        ->orwhere(function ($query3) use ($start_date, $end_date) {
                            $query3->whereDate('b.returning_updated', '>=', $start_date)
                                ->whereDate('b.returning_updated', '<=', $end_date);
                        });
                    });
        }
        $bookings = $bookings->orderBy('id', 'desc')
            ->get();
        $count = 0;
        $count_hp = 0;
        $count_phone = 0;
        $count_portal = [];

        foreach($bookings as $booking) {
            $bmail = $booking->email;
            if(strpos($bmail, 'dummy') !== false)
                $bookings[$count]->email = '情報なし';

            $portal_flag    = $booking->portal_flag;

            if($portal_flag == 0) {
                $count_hp++;
            } else {
                if ($booking->portal_name == '電話')
                    $count_phone++;
                else {
                    $portal_name = $booking->portal_name;
                    if(!array_key_exists($portal_name, $count_portal))
                        $count_portal[$portal_name] = 1;
                    else
                        $count_portal[$portal_name]++;
                }

            }
            $portal_inform  = json_decode($booking->portal_info);
            $model_id       = $booking->model_id;
            $paid_options   = explode("," ,$booking->paid_options);
            $free_options   = $booking->free_options;
            $fops = explode(',', $free_options);
            if(empty($free_options)) $free_options = 0;
            $free_option_object = \DB::table('car_option')->where('id', $free_options)->first();
            if(!empty($free_option_object))
                $bookings[$count]->free_options = $free_option_object->google_column_number;
            else
                $bookings[$count]->free_options = 0;

            // check if exist pickup
            // 1. get google column numbers
            $gcps = [];
            foreach ($fops as $op) {
                $gcp = \DB::table('car_option')->where('id', $op)->first();
                if(!is_null($gcp)) $gcps[] = $gcp->google_column_number;
            }
            foreach ($paid_options as $op) {
                $gcp = \DB::table('car_option')->where('id', $op)->first();
                if(!is_null($gcp)) $gcps[] = $gcp->google_column_number;
            }
            if(in_array('38', $gcps) || in_array('106', $gcps)){
                $bookings[$count]->pickup_column = 'スマ';
            } elseif(in_array('101', $gcps) || in_array('102', $gcps)) {
                $bookings[$count]->pickup_column = '必要';
            } else {
                $bookings[$count]->pickup_column = '--';
            }

            $paid_options_price = explode("," ,$booking->paid_options_price);
            $paid_options_number= explode("," ,$booking->paid_option_numbers);
            //add option from bookings price
            $addition_bookings = \DB::table('bookings_price')->where('book_id',$booking->id)->get();

            foreach ($addition_bookings as $addition) {
                $add_opions = $addition->paid_options;
                $add_number = $addition->paid_options_number;
                $add_price  = $addition->paid_options_price;
                if($add_opions != '') {
                    $paid_options_origin = implode(',', $paid_options);
                    $paid_options_number_origin = implode(',', $paid_options_number);
                    $paid_options_price_origin  = implode(',', $paid_options_price);
                    if(!empty($paid_options)) {
                        $paid_options_origin = $paid_options_origin . ',' . $add_opions;
                        $paid_options_number_origin = $paid_options_number_origin . ',' . $add_number;
                        $paid_options_price_origin = $paid_options_price_origin . ',' . $add_price;
                    }else {
                        $paid_options_origin        =  $add_opions;
                        $paid_options_number_origin =  $add_number;
                        $paid_options_price_origin  =  $add_price;
                    }
                    $paid_options           = explode("," ,$paid_options_origin);
                    $paid_options_number    = explode("," ,$paid_options_number_origin);
                    $paid_options_price     = explode("," ,$paid_options_price_origin);
                }
                $add_insurance1 = $addition->insurance1;
                $add_insurance2 = $addition->insurance2;
                if(empty($bookings[$count]->insurance1)) $bookings[$count]->insurance1 =  $add_insurance1;
                if(empty($bookings[$count]->insurance2)) $bookings[$count]->insurance2 =  $add_insurance2;
            }

            //get duplicated flag
            $duplicated_car = $this->duplicate_car($booking->inventory_id,$booking->departing, $booking->returning);
            $bookings[$count]->duplicated_car = $duplicated_car;
            //get days for rental
            $depart_date    = date('Y-m-d', strtotime($booking->departing));
            $depart_time    = date('H:i:s', strtotime($booking->departing));
            $return_date    = date('Y-m-d', strtotime($booking->returning));
            $return_time    = date('H:i:s', strtotime($booking->returning));
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night = $request_days['night'];
            $day = $request_days['day'];
            $bookings[$count]->night = $night . '泊';
            $bookings[$count]->day = $day . '日';
            if(strtotime($booking->returning_updated) > strtotime('1970-01-01 00:00:00')) {
                $return_date = date('Y-m-d',strtotime($booking->returning_updated));
                $extend_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
                $extend_night  = $extend_days['night'];
                $extend_day    = $extend_days['day'];
                $bookings[$count]->night = $extend_night . '泊';;
                $bookings[$count]->day   = $extend_day . '日';;
            }
            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option as co')
                    ->whereIn('co.id', $paid_options)
                    ->select(['co.id as option_id', 'co.abbriviation as option_name', 'co.price as option_price','co.google_column_number as index'])
                    ->get();
                for($i=0; $i<count($paid_options); $i++){
                    $m = 0;
                    foreach($caroptions as $op) {
                        if($paid_options[$i] == $op->option_id) {
                            $pa_op_price = 0;
                            if(!empty($paid_options_price[$i])) {
                                $pa_op_price = intval($paid_options_price[$i]) * intval($paid_options_number[$i]);
                            }
                            $caroptions[$m]->option_price = $pa_op_price;
                            $pa_op_number = 0;
                            if(!empty($paid_options_number[$i])) $pa_op_number = $paid_options_number[$i];
                            $caroptions[$m]->option_number = $pa_op_number;
                            if($op->index == '38') { //smart driveoption service
                                //$bookings[$count]->wait_status = '3';
                            }
                        }
                        $m++;
                    }
                }
                $bookings[$count]->options = $caroptions;
            }else {
                $bookings[$count]->options = array();
            }

            $web_status_flag = 0;
            $web_status = $booking->web_status;
            $pay_status = $booking->pay_status;
            if($web_status == 1) $web_status_flag = '1';
            if($web_status == 2) $web_status_flag = '2';
            if($web_status == 3) $web_status_flag = '3';
            //if($pay_status == 1) $web_status_flag = '3';
            $bookings[$count]->web_status_flag = $web_status_flag;
            $bookings[$count]->car_number = $booking->numberplate4;
            $bookings[$count]->paidamount = $this->paidmount($booking->id);
            $bookings[$count]->unpaidamount = $this->unpaidmount($booking->id);

            //poratal settig
            if(count((array)$portal_inform) == 0 ) {
                $portal_flag = 0;
                $booking->portal_flag = 0;
                $bookings[$count]->portal_flag = 0;
            }
            if($portal_flag == 1) {
                //booking
                $bookings[$count]->booking  = $portal_inform->booking;
                $bookings[$count]->phone    = $portal_inform->phone;
                $bookings[$count]->email    = $portal_inform->email;
                $bookings[$count]->last_name        = $portal_inform->last_name;
                $bookings[$count]->first_name       = $portal_inform->first_name;
                $bookings[$count]->fur_last_name    = $portal_inform->fu_last_name;
                $bookings[$count]->fur_first_name   = $portal_inform->fu_first_name;
                if($portal_inform->smoke == '喫煙')  $bookings[$count]->smoke = '1';
                if($portal_inform->smoke == '禁煙')  $bookings[$count]->smoke = '0';
                //$bookings[$count]->smoke            = $portal_inform->smoke;
                $bookings[$count]->free_option_name =  $portal_inform->free_option_name;
                $bookings[$count]->returning_point =  $portal_inform->returning_point;
            }

            $cartype = \DB::table('car_type as ct')
                ->join('car_model as cm','cm.type_id','=','ct.id')
                ->where('cm.id', $model_id)
                ->select(['ct.id as type_id','ct.name as type_name'])
                ->first();
            if($cartype) {
                $bookings[$count]->type_id     = $cartype->type_id;
                $bookings[$count]->type_name   = $cartype->type_name;
            }else{
                $bookings[$count]->type_id     = '0';
                $bookings[$count]->type_name   = '';
            }
            $count++;
        }
        $count_all = $count_hp + $count_phone + array_sum($count_portal);

        return View('pages.admin.booking.view-all',
                    compact('bookings','route','subroute','search_date',
                            'condition','shops','search_shop','portal_cond',
                            'count_hp', 'count_phone', 'count_portal', 'count_all'
                            ));
    }
    //duplicate flag
    public function duplicate_car($inventory_id, $deaparting ,$returning) {
        $statuses = array("1","2","3","4","5","6","7"); //except 8=end, 9 = cancel , 10 =ignore
        $flag = false;
        $bookings = \DB::table('bookings')
            ->where('inventory_id', $inventory_id)
            ->whereBetween('departing',[$deaparting, $returning])
            ->whereIn('status', $statuses)
            ->select(DB::raw("count(*) as countbooking"))->first();
        if($bookings->countbooking > 1) {
            $flag = true;
        }
        return $flag;
    }
    /*** Show the form for creating a new resource.*
     * @return \Illuminate\Http\Response*/
    public function create(Request $request )
    {
        $admin = Auth::user();
        if(!Auth::check() || !$admin->isAdmin())
            return Redirect::back();

        $validator = Validator::make($request->all(),
            [
                'admin_id'          => 'integer|required',
                'user_id'           => 'integer|required',
                'client_first_name' => 'required',
                'client_last_name'  => 'required',
                'client_phone'      => 'required',
                'client_email'      => 'required|email|unique:users,email',
                'emergency_phone'   => 'required',
                'passengers'        => 'integer|required',
                'pickup_id'         => 'required',
                'dropoff_id'        => 'required',
                'inventory_id'      => 'required',
                'depart_date'       => 'date_format:"Y/m/d"|required',
                'depart_time'       => 'date_format:"H:i"|required',
                'return_date'       => 'date_format:"Y/m/d"|required',
                'return_time'       => 'date_format:"H:i"|required',
                'reservations'      => 'integer|required',
                'subtotal'          => 'numeric|required',
                'discount'          => 'numeric|required',
                'tax'               => 'numeric|required',
                'total_pay'         => 'numeric|required'
            ]
        );

        if ($validator->fails()) {
            $this->throwValidationException( $request, $validator );
        } else {
            $route          = ServerPath::getRoute();
            $subroute       = ServerPath::getSubRoute();

            $client_id = $request->input('user_id');
            DB::beginTransaction();
            if( $client_id == 0) {
                $ipAddress  = new CaptureIpTrait;
                $profile    = new Profile;
                // user register
                $user = User::create([
                    'first_name'        => $request->input('client_first_name'),
                    'last_name'         => $request->input('client_last_name'),
                    'email'             => $request->input('client_email'),
                    'password'          => bcrypt('123456'),
                    'token'             => str_random(64),
                    'admin_ip_address'  => $ipAddress->getClientIp(),
                    'activated'         => 1
                ]);
                // profile set
                $profile->user_id         = $user->id;
                $profile->fur_first_name  = $request->input('client_first_name');
                $profile->fur_last_name   = $request->input('client_last_name');
                $profile->phone           = $request->input('client_phone');
                $profile->emergency_phone = $request->input('emergency_phone');

                $user->profile()->save($profile);
                $user->attachRole($request->input('role'));
                $user->save();
                if(!$user) {
                    DB::rollBack();
                }
                $client_id = $user->id;
            }

            $today = date('ymd');
            $todayLastBook = \DB::select('SELECT * FROM bookings WHERE DATE(created_at) = DATE(NOW()) ORDER BY id DESC limit 0,1 ');
            var_dump($todayLastBook);
            if(count($todayLastBook) == 0){
                $booking_id = $today.'-0001';
            } else {
                $split = explode('-', $todayLastBook[0]->booking_id);
                $booking_id = $today.'-'.str_pad(($split[1] + 1), 4, '0', STR_PAD_LEFT);
            }

            $depart_date = $request->input('depart_date');
            $depart_time = $request->input('depart_time');
            $return_date = $request->input('return_date');
            $return_time = $request->input('return_time');
            $split = explode('/', $depart_date);
            $depart_datetime = $split[0].'-'.$split[1].'-'.$split[2].' '.$depart_time.':00';
            $split = explode('/', $return_date);
            $return_datetime = $split[0].'-'.$split[1].'-'.$split[2].' '.$return_time.':00';
            $booking_status = \DB::table('booking_status')
                ->where('status','1')->first();// status is submit
            $inventory_ids  = explode("_",$request->input('inventory_id'));
            $inventory_id   = $inventory_ids[0];
            $class_id       = $inventory_ids[1];
            $model_id       = $inventory_ids[2];
            $type_id        = $inventory_ids[3];

            //get insurance
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night = $request_days['night'];
            $day   = $request_days['day'];
            $insurance_condition   =  $request->input('insurance');
            $insurance1 = 0 ;
            $insurance2 = 0;
            if($insurance_condition == '1') {
                $insurance1 = ServerPath::getInsurancePrice(1, $class_id) * $day;
            }
            if($insurance_condition == '2') {
                $insurance1 = ServerPath::getInsurancePrice(1, $class_id) * $day;
                $insurance2 = ServerPath::getInsurancePrice(1, $class_id) * $day;
            }
            $insertbooking = \DB::table('bookings')->insert(
                [
                    'admin_id'      => $request->input('admin_id'),
                    'booking_id'    => $booking_id,
                    'status'        => $booking_status->status,
                    'client_id'     => $client_id,
                    'driver_name'   => $request->input('driver_name'),
                    'emergency_phone' => $request->input('emergency_phone'),
                    'flight_line'   => $request->input('flight_line'),
                    'flight_number' => $request->input('flight_number'),
                    'pickup_id'     => $request->input('pickup_id'),
                    'dropoff_id'    => $request->input('dropoff_id'),
                    'inventory_id'  => $inventory_id,
                    'class_id'      => $class_id,
                    'basic_price'   => $request->input('car_price'),
                    'model_id'      => $model_id,
                    'free_options'  => $request->input('free_options'),
                    'paid_options'  => $request->input('paid_options'),
                    'paid_options_price'  => $request->input('paid_options_price'),
                    'insurance1'    => $insurance1,
                    'insurance2'    => $insurance2,
                    'option_price'  => $request->input('option_price'),
                    'departing'     => $depart_datetime,
                    'returning'     => $return_datetime,
                    'rent_days'     => $request->input('rentdays_val'),
                    'client_message' => $request->input('client_message'),
                    'admin_memo'    => $request->input('admin_memo'),
                    'reservation_id'=> $request->input('reservations'),
                    'plan_id'       => $request->input('plan_id', 0),
                    'given_points'  => $request->input('given_points'),
                    'subtotal'      => $request->input('subtotal'),
                    'discount'      => $request->input('discount'),
                    'tax'           => $request->input('tax'),
                    'payment'       => $request->input('total_pay'),
                    'prepaid'       => $request->input('prepaid'),
                    'pay_method'    => $request->input('paymethod'),
                    'pay_id'        => $request->input('pay_id'),
                    'trans_id'      => $request->input('trans_id'),
                    'user_pay_id'   => $request->input('client_pay_id'),
                    'user_trans_id' => $request->input('client_trans_id')
                ]
            );
            if(!$insertbooking){
                DB::rollBack();
            }
            DB::commit();
            return redirect('/booking/all')->with('success', trans('booking.createSuccess'));
        }

    }

    public function detail($book_id) {
        $route = Route::getFacadeRoot()->current()->uri();
        $route_path = explode('/',$route);
        $subroute = $route_path[1];
        $booking = \DB::table('bookings as b')
            ->leftjoin('users as u','b.client_id','=','u.id')
            ->leftjoin('profiles as p' ,'b.client_id','=','p.user_id')
            ->leftjoin('car_inventory as ci','ci.id','=','b.inventory_id')
            ->leftjoin('booking_status as bs','b.status','=','bs.status')
            ->leftjoin('car_class as cc','cc.id','=','b.class_id')
            ->leftjoin('car_model as cm','cm.id','=','b.model_id')
            ->leftjoin('car_shop as csp','csp.id','=','b.pickup_id')
            ->leftjoin('car_shop as csd','csd.id','=','b.dropoff_id')
            ->leftjoin('credit_card as cr','b.pay_method','=','cr.id')
            ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
            ->leftjoin('flight_lines as fl','b.flight_line','=','fl.id')
            ->leftjoin('reservations as r','b.reservation_id','=','r.id')
            ->leftjoin('users as ad', 'ad.id','=','b.admin_id')
            ->select(['b.*','ci.model_id','bs.name as booking_status',
                'u.first_name','u.last_name','p.fur_first_name','p.fur_last_name', 'p.phone','u.email',
                'ci.numberplate1 as car_number1','ci.numberplate2 as car_number2',
                'ci.numberplate3 as car_number3','ci.numberplate4 as car_number4','ci.shortname',
                'cc.id as class_id','cc.name as class_name','cm.id as model_id','p.postal_code','p.prefecture','p.address1','p.address2',
                'cm.name as model_name','ci.smoke','cr.name as card_name','csp.id as pickup_id','csp.name as pickup_name',
                'csd.id as drop_id','csd.name as drop_name','ps.name as portal_name','fl.title as flight_name','csp.shop_number',
                'ad.last_name as admin_last_name','ad.first_name as admin_first_name','r.title as reservation_method',
                'cr.name as paymethod'])
            ->where('b.id',$book_id)->orderBy('b.booking_id','desc')->first();

        if(!empty($booking)) {
            $model_id = $booking->model_id;
            if(empty($booking->client_id)) $booking->client_id = 0;
            $paid_options                   = explode("," ,$booking->paid_options);
            $paid_options_price             = explode("," ,$booking->paid_options_price);
            $paid_options_number            = explode("," ,$booking->paid_option_numbers);
            $booking->paid_option_numbers   = $paid_options_number;
            $free_options                   = explode("," ,$booking->free_options);
            //get days for rental
            $depart_date    = date('Y-m-d', strtotime($booking->departing));
            $depart_time    = date('H:i', strtotime($booking->departing));
            $return_date    = date('Y-m-d', strtotime($booking->returning));
            $return_time    = date('H:i', strtotime($booking->returning));
            $booking->depart_date = $depart_date;
            $booking->depart_time = $depart_time;
            $booking->return_date = $return_date;
            $booking->return_time = $return_time;
            $class_id = $booking->class_id;
            //get selected options
            $selected_option = array();
            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option as co')
                    ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                    ->where('co.type', 0)
                    ->where('coc.class_id', $class_id)
                    ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                    ->orderby('co.order','asc')
                    ->get();
                $m = 0;
                foreach($caroptions as $op) {
                    $pa_op_price = 0;
                    $pa_op_number = 0;
                    for($i=0; $i<count($paid_options); $i++){
                        if($paid_options[$i] == $op->option_id) {
                            if(!empty($paid_options_price[$i])) $pa_op_price = $paid_options_price[$i]*$paid_options_number[$i];
                            if(!empty($paid_options_number[$i])) $pa_op_number = $paid_options_number[$i];
                        }
                    }
                    $caroptions[$m]->option_price  = $pa_op_price;
                    $caroptions[$m]->option_number = $pa_op_number;
                    if($pa_op_price != 0) $selected_option[] = $caroptions[$m];
                    $m++;
                }
            }
            $additional_prices = \DB::table('bookings_price')->where('type','add')->where('book_id', $booking->id)->orderby('created_at','asc')->get();
            foreach($additional_prices as $ad) {
                //options
                $ad_option_ids      = explode(',', $ad->paid_options);
                $ad_option_number   = explode(',', $ad->paid_options_number);
                $ad_option_price    = explode(',', $ad->paid_options_price);
                $all_option_price   = 0;
                $adoptions = \DB::table('car_option as co')
                    ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                    ->where('co.type', 0)
                    ->where('coc.class_id', $class_id)
                    ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                    ->orderby('co.order','asc')
                    ->get();
                $m = 0;
                foreach($adoptions as $op) {
                    $pa_op_price = 0;
                    $pa_op_number = 0;
                    for($i=0; $i<count($ad_option_ids); $i++){
                        if($ad_option_ids[$i] == $op->option_id) {
                            if(!empty($ad_option_price[$i]))  $pa_op_price = $ad_option_price[$i]*$ad_option_number[$i];
                            if(!empty($ad_option_number[$i])) $pa_op_number = $ad_option_number[$i];
                        }
                    }
                    $adoptions[$m]->option_price  = $pa_op_price;
                    $adoptions[$m]->option_number = $pa_op_number;
                    if($pa_op_price != 0) $selected_option[] = $adoptions[$m];
                    $m++;
                }
            }
            $booking->selected_options    =  $selected_option;
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night = $request_days['night'];
            $day = $request_days['day'];
            $booking->night = $night . '泊';
            $booking->day = $day . '日';

            if(strtotime($booking->returning_updated) > strtotime('1970-01-01 00:00:00')) {
                $return_date = date('Y-m-d',strtotime($booking->returning_updated));
                $extend_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
                $extend_night  = $extend_days['night'];
                $extend_day    = $extend_days['day'];
                $booking->night = $extend_night . '泊';
                $booking->day   = $extend_day . '日';
                $booking->extend_set_day = $extend_day;
            }else {
                $booking->extend_set_day = 0;
            }

            $p_options = \DB::table('car_option as co')
                ->whereIn('co.id', $paid_options)
                ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price'])
                ->get();
//dd($p_options);
            $booking->paid_options = $p_options;
            $f_options = \DB::table('car_option as co')
                ->whereIn('co.id', $free_options)
                ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price', 'co.google_column_number'])
                ->get();

            $booking->free_options = $f_options;

            $cartype = \DB::table('car_type as ct')
                ->join('car_model as cm', 'cm.type_id', '=', 'ct.id')
                ->where('cm.id', $model_id)
                ->select(['ct.id as type_id', 'ct.name as type_name'])
                ->first();
            if ($cartype) {
                $booking->type_id = $cartype->type_id;
                $booking->type_name = $cartype->type_name;
            } else {
                $booking->type_id = '0';
                $booking->type_name = '';
            }

            $adminID = $booking->admin_id;
            $user = \DB::table('users')->where('id', '=', $adminID)->first();
            if(!empty($user)) {
                $user->profile = \DB::table('profiles')->where('user_id', '=', $adminID)->first();
            }
            if(!empty($user)) {
                $booking->admin_name = $user->last_name . " " . $user->first_name;
            }else {
                $booking->admin_name = '';
            }
            $clientID = $booking->client_id;
            $user = \DB::table('users')->where('id', '=', $clientID)->first();
            if(!empty($user)) {
                $user->profile = \DB::table('profiles')->where('user_id', '=', $clientID)->first();
            }
            if(!empty($user)) {
                $booking->member_name = $user->last_name . " " . $user->first_name;
            }else {
                $booking->member_name = '';
            }

            //get driver license inform
            $driver_license_images = \DB::table('bookings_driver_licences')
                ->where('booking_id','=',$booking->id)
                ->get();
            $booking->driver_license_images = $driver_license_images;



            $booking->created_at = ($booking->created_at == null)? '' : date('Y/m/d H:i',strtotime($booking->created_at));
            $booking->updated_at = ($booking->updated_at == null)? '' : date('Y/m/d H:i',strtotime($booking->updated_at));
            $booking->canceled_at = ($booking->canceled_at == null)? '' : date('Y/m/d',strtotime($booking->canceled_at));
            $portal_inform = json_decode($booking->portal_info);
            if(count((array)$portal_inform) == 0) {
                $booking->portal_flag = '0';
            }
            $booking->paid_payment      = $this->paidmount($booking->id);
            $booking->unpaid_payment    = $this->unpaidmount($booking->id);
            $booking->option_price_sum  = $this->allOptionPrice($book_id);
            $booking->insurance1_sum    = $this->allInsurance1($book_id);
            $booking->insurance2_sum    = $this->allInsurance2($book_id);
            $booking->alladjustment     = $this->alladjustment($book_id);
            $booking->allextendnight    = $this->allextendnight($book_id);
            $booking->allextendnight_basic          =$this->allextendnight_basic($book_id);
            $booking->allextendnight_extend_day     =$this->allextendnight_extend_day($book_id);
            $booking->allextendnight_optionprice    =$this->allextendnight_optionprice($book_id);
            $booking->allextendnight_insurance      =$this->allextendnight_insurance($book_id);


            if($booking->portal_flag == '1') {
                $booking->booking  = $portal_inform->booking;
                $booking->phone    = $portal_inform->phone;
                $booking->email    = $portal_inform->email;
                $booking->last_name        = $portal_inform->last_name;
                $booking->first_name       = $portal_inform->first_name;
                $booking->fur_last_name    = $portal_inform->fu_last_name;
                $booking->fur_first_name   = $portal_inform->fu_first_name;
                if($portal_inform->smoke == '喫煙')  $booking->request_smoke = '1';
                if($portal_inform->smoke == '禁煙')  $booking->request_smoke = '0';
                //$booking->smoke            = $portal_inform->smoke;
                $booking->free_option_name =  $portal_inform->free_option_name;
                $booking->returning_point  =  $portal_inform->returning_point;
                if(empty($portal_inform->bad_flag)) $portal_inform->bad_flag = '0';
                $booking->bad_flag         =  $portal_inform->bad_flag;// bad price
            }

            $data = [
                'route'         => $route,
                'subroute'      => $subroute,
                'book'          => $booking,
                'user'          => $user,
            ];
            //dd($data['book']);
            return View('pages.admin.booking.view-detail')->with($data);
        }
        else {
            return redirect('/booking/all');
        }
    }

    public function today_tom($condition) {
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $date = date('Y-m-d');
        if($condition == 'tom')
            $date = date('Y-m-d', strtotime('tomorrow'));
        $pickup_ids = [101, 38, 106, 251, 252, 2253, 254, 255, 250];

        $departings = \DB::table('bookings as b')
            ->leftjoin('users as u', 'b.client_id','=','u.id')
            ->leftjoin('profiles as p', 'b.client_id', '=', 'p.user_id')
            ->leftjoin('car_inventory as ci', 'ci.id','=','b.inventory_id')
            ->leftjoin('booking_status as bs','bs.status','=','b.status')
            ->leftjoin('car_class as cc','cc.id','=','b.class_id')
            ->leftjoin('car_model as cm','cm.id','=','b.model_id')
            ->leftjoin('car_shop as csp','csp.id','=','b.pickup_id')
            ->leftjoin('car_shop as csd','csd.id','=','b.dropoff_id')
            ->leftjoin('credit_card as crc','crc.id','=','b.pay_method')
            ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
            ->leftjoin('flight_lines as fl','fl.id','=','b.flight_line')
            ->leftjoin('users as ad', 'ad.id','=','b.admin_id')
            ->select(['b.*','ci.model_id','ci.shortname','bs.name as booking_status','u.first_name','u.last_name','p.fur_first_name','p.fur_last_name',
                'p.phone','u.email', 'ci.numberplate1','ci.numberplate2','ci.numberplate3','ci.numberplate4', 'ci.numberplate1 as car_number','cc.id as class_id','cc.name as class_name','cm.id as model_id',
                'cm.name as model_name','ci.smoke','crc.name as card_name','csp.id as pickup_id','csp.name as pickup_name',
                'csd.id as drop_id','csd.name as drop_name','ps.name as portal_name','fl.title as flight_name',
                'ad.last_name as admin_last_name','ad.first_name as admin_first_name'])
            ->where('b.status','<','8')
            ->where('b.departing','LIKE', '%'.$date.'%')
            ->orderBy('id','asc')->get();
        $count = 0;
        foreach($departings as $departing) {
            $portal_flag    = $departing->portal_flag;
            $portal_inform  = json_decode($departing->portal_info);
            $model_id       = $departing->model_id;
            $free_options   = explode(',', $departing->free_options);
            $paid_options   = explode("," ,$departing->paid_options);
            $paid_options_price = explode("," ,$departing->paid_options_price);
            $paid_options_number = explode("," ,$departing->paid_option_numbers);
            //get days for rental
            $depart_date    = date('Y-m-d', strtotime($departing->departing));
            $depart_time    = date('H:i:s', strtotime($departing->departing));
            $return_date    = date('Y-m-d', strtotime($departing->returning));
            $return_time    = date('H:i:s', strtotime($departing->returning));
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night = $request_days['night'];
            $day = $request_days['day'];
            $departings[$count]->night = $night . '泊';
            $departings[$count]->day = $day . '日';
            $pickups = [];
            if(!empty($free_options)) {
                $caroptions = \DB::table('car_option')
                    ->whereIn('id', $free_options)
                    ->select(['id as option_id', 'abbriviation as option_name', 'google_column_number as gnumber'])
                    ->get();
                foreach($caroptions as $m => $op) {
                    if (in_array($op->gnumber, $pickup_ids)) {
                        $pickups[] = $op;
                    }
                }
            }
            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option')
                    ->whereIn('id', $paid_options)
//                    ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price'])
                    ->select(['id as option_id', 'abbriviation as option_name', 'price as option_price', 'google_column_number as gnumber'])
                    ->get();
                foreach($caroptions as $m => $op) {
                    if(in_array($op->gnumber, $pickup_ids)){
                        $pickups[] = $op;
                    }
                    for($i=0; $i<count($paid_options); $i++){
                        if($paid_options[$i] == $op->option_id) {
                            $pa_op_price = 0;
                            $pa_op_number = 0;
                            if(!empty($paid_options_price[$i])) $pa_op_price = $paid_options_price[$i];
                            if(!empty($paid_options_number[$i])) $pa_op_number = $paid_options_number[$i];
                            $caroptions[$m]->option_price = $pa_op_price;
                            $caroptions[$m]->option_number = $pa_op_number;
                        }
                    }
                }
                $departings[$count]->options = $caroptions;
            }else {
                $departings[$count]->options = array();
            }
            $departings[$count]->pickups = $pickups;
            $web_status_flag = 0;
            $web_status = $departing->web_status;
            $pay_status = $departing->pay_status;
            if($web_status == 1) $web_status_flag = '1';
            if($web_status == 2) $web_status_flag = '2';
            if($web_status == 3) $web_status_flag = '3';
            //if($pay_status == 1) $web_status_flag = '3';
            $departings[$count]->web_status_flag = $web_status_flag;
            $departings[$count]->paidamount = $this->paidmount($departing->id);
            $departings[$count]->unpaidamount = $this->unpaidmount($departing->id);


            //poratal settig
            if(count((array)$portal_inform) == 0) {
                $portal_flag = 0 ;
            }
            if($portal_flag == 1) {
                //booking
                $departings[$count]->booking  = $portal_inform->booking;
                $departings[$count]->phone    = $portal_inform->phone;
                $departings[$count]->email    = $portal_inform->email;
                $departings[$count]->last_name        = $portal_inform->last_name;
                $departings[$count]->first_name       = $portal_inform->first_name;
                $departings[$count]->fur_last_name    = $portal_inform->fu_last_name;
                $departings[$count]->fur_first_name   = $portal_inform->fu_first_name;
                if($portal_inform->smoke == '喫煙')  $departings[$count]->smoke = '1';
                if($portal_inform->smoke == '禁煙')  $departings[$count]->smoke = '0';
                $departings[$count]->free_option_name =  $portal_inform->free_option_name;
                $departings[$count]->returning_point  =  $portal_inform->returning_point;
                if(empty($portal_inform->bad_flag)) $portal_inform->bad_flag = '0';
                $departings[$count]->bad_flag         =  $portal_inform->bad_flag;// bad price
            }

            $cartype = \DB::table('car_type as ct')
                ->join('car_model as cm','cm.type_id','=','ct.id')
                ->where('cm.id', $model_id)
                ->select(['ct.id as type_id','ct.name as type_name'])
                ->first();
            if($cartype) {
                $departings[$count]->type_id     = $cartype->type_id;
                $departings[$count]->type_name   = $cartype->type_name;
            }else {
                $departings[$count]->type_id     = '0';
                $departings[$count]->type_name   = '';
            }
            $count++;
        }
            $statuses = array("1", "2", "3","4","5","6","7","8","10"); //except the cancel
            $returnings = \DB::table('bookings as b')
            ->leftjoin('users as u', 'b.client_id','=','u.id')
            ->leftjoin('profiles as p', 'b.client_id', '=', 'p.user_id')
            ->leftjoin('car_inventory as ci', 'ci.id','=','b.inventory_id')
            ->leftjoin('booking_status as bs','bs.status','=','b.status')
            ->leftjoin('car_class as cc','cc.id','=','b.class_id')
            ->leftjoin('car_model as cm','cm.id','=','b.model_id')
            ->leftjoin('car_shop as csp','csp.id','=','b.pickup_id')
            ->leftjoin('car_shop as csd','csd.id','=','b.dropoff_id')
            ->leftjoin('credit_card as crc','crc.id','=','b.pay_method')
            ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
            ->leftjoin('flight_lines as fl','fl.id','=','b.flight_line')
            ->leftjoin('users as ad', 'ad.id','=','b.admin_id')
            ->select(['b.*','ci.model_id','bs.name as booking_status','u.first_name','u.last_name','p.fur_first_name','p.fur_last_name',
                'p.phone','u.email','ci.numberplate1','ci.numberplate2','ci.numberplate3','ci.numberplate4','ci.numberplate1 as car_number','cc.id as class_id','cc.name as class_name','cm.id as model_id',
                'cm.name as model_name','ci.smoke','ci.shortname','crc.name as card_name','csp.id as pickup_id','csp.name as pickup_name',
                'csd.id as drop_id','csd.name as drop_name','ps.name as portal_name','fl.title as flight_name',
                'ad.last_name as admin_last_name','ad.first_name as admin_first_name'])
            //->where('b.status','<', '5')
            ->where(function ($query) use($date) {
                $query->where(function ($query2) use($date) {
                            $query2->whereDate('b.returning', '=', $date)
                                   ->where('b.returning_updated','0000-01-01 00:00:00');
                        })
                        ->orwhere(function ($query1) use($date) {
                                $query1->whereDate('b.returning_updated', '=', $date);
                        });
            })
            //->where('b.returning','LIKE', '%'.$date.'%')
            ->whereIn('b.status', $statuses)
            ->orderBy('id','asc')->get();
        $count = 0;

        foreach($returnings as $returning) {
            $portal_flag    = $returning->portal_flag;
            $portal_inform  = json_decode($returning->portal_info);
            $model_id       = $returning->model_id;
            $free_options   = explode("," ,$returning->free_options);
            $paid_options   = explode("," ,$returning->paid_options);
            $paid_options_price = explode("," ,$returning->paid_options_price);
            $paid_options_number = explode("," ,$returning->paid_option_numbers);
            //get days for rental
            $depart_date    = date('Y-m-d', strtotime($returning->departing));
            $depart_time    = date('H:i:s', strtotime($returning->departing));
            $return_date    = date('Y-m-d', strtotime($returning->returning));
            $return_time    = date('H:i:s', strtotime($returning->returning));
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night = $request_days['night'];
            $day = $request_days['day'];
            $returnings[$count]->night = $night . '泊';
            $returnings[$count]->day = $day . '日';

            $pickups = [];
            if(!empty($free_options)) {
                $caroptions = \DB::table('car_option')
                    ->whereIn('id', $free_options)
                    ->select(['id as option_id', 'abbriviation as option_name', 'price as option_price', 'google_column_number as gnumber'])
                    ->get();
                foreach($caroptions as $m => $op) {
                    if (in_array($op->gnumber, $pickup_ids)) {
                        $pickups[] = $op;
                    }
                }
            }
            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option')
                    ->whereIn('id', $paid_options)
//                    ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price'])
                    ->select(['id as option_id', 'abbriviation as option_name', 'price as option_price', 'google_column_number as gnumber'])
                    ->get();
                foreach($caroptions as $m => $op) {
                    if(in_array($op->gnumber, $pickup_ids)){
                        $pickups[] = $op;
                    }
                    for($i=0; $i<count($paid_options); $i++){
                        if($paid_options[$i] == $op->option_id) {
                            $pa_op_price = 0;
                            $pa_op_number = 0;
                            if(!empty($paid_options_price[$i])) $pa_op_price = $paid_options_price[$i];
                            if(!empty($paid_options_number[$i])) $pa_op_number = $paid_options_number[$i];
                            $caroptions[$m]->option_price = $pa_op_price;
                            $caroptions[$m]->option_number = $pa_op_number;
                        }
                    }
                }
                $returnings[$count]->options = $caroptions;
            }else {
                $returnings[$count]->options = array();
            }
            $returnings[$count]->pickups = $pickups;
            $web_status_flag = 0;
            $web_status = $returning->web_status;
            $pay_status = $returning->pay_status;
            if($web_status == 1) $web_status_flag = '1';
            if($web_status == 2) $web_status_flag = '2';
            if($web_status == 3) $web_status_flag = '3';
            //if($pay_status == 1) $web_status_flag = '3';
            $returnings[$count]->web_status_flag = $web_status_flag;
            $returnings[$count]->paidamount = $this->paidmount($returning->id);
            $returnings[$count]->unpaidamount = $this->unpaidmount($returning->id);
            //poratal settig
            if(count((array)$portal_inform) == 0) {
                $portal_flag = 0 ;
            }
            if($portal_flag == 1) {
                //booking
                $returnings[$count]->booking  = $portal_inform->booking;
                $returnings[$count]->phone    = $portal_inform->phone;
                $returnings[$count]->email    = $portal_inform->email;
                $returnings[$count]->last_name        = $portal_inform->last_name;
                $returnings[$count]->first_name       = $portal_inform->first_name;
                $returnings[$count]->fur_last_name    = $portal_inform->fu_last_name;
                $returnings[$count]->fur_first_name   = $portal_inform->fu_first_name;
                if($portal_inform->smoke == '喫煙')  $returnings[$count]->smoke = '1';
                if($portal_inform->smoke == '禁煙')  $returnings[$count]->smoke = '0';
                $returnings[$count]->free_option_name =  $portal_inform->free_option_name;
                $returnings[$count]->returning_point  =  $portal_inform->returning_point;
                if(empty($portal_inform->bad_flag)) $portal_inform->bad_flag = '0';
                $returnings[$count]->bad_flag         =  $portal_inform->bad_flag;// bad price

            }


            $cartype = \DB::table('car_type as ct')
                ->join('car_model as cm','cm.type_id','=','ct.id')
                ->where('cm.id', $model_id)
                ->select(['ct.id as type_id','ct.name as type_name'])
                ->first();
            if($cartype) {
                $returnings[$count]->type_id     = $cartype->type_id;
                $returnings[$count]->type_name   = $cartype->type_name;
            }else {
                $returnings[$count]->type_id     = '0';
                $returnings[$count]->type_name   = '';
            }

            $count++;
        }
        $data = [
            'route'         => $route,
            'subroute'      => $subroute,
            'departings'    => $departings,
            'returnings'    => $returnings,
        ];
        return $data;
//        return View('pages.admin.booking.view-today')->with($data);
    }

    public function add($user_id = 0) {

        $admin = Auth::user();
        if(!Auth::check() || !$admin->isAdmin())
            return Redirect::back();
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        if($user_id > 0){
            $user = \DB::table('users as u')
                ->join('profiles as p', 'u.id', '=', 'p.user_id')
                ->select(['u.id','u.email','u.first_name','u.last_name','p.phone'])
                ->first();
        } else {
            $user = array('id'=>'0', 'email'=>'', 'first_name'=>'', 'last_name'=>'', 'phone'=>'');
        }
        $reservations   = \DB::table('reservations')->orderby('order', 'asc')->get();
        $flight_lines   = \DB::table('flight_lines')->orderby('order', 'asc')->get();
        $paymethods     = \DB::table('credit_card')->orderby('id', 'asc')->get();
        $booking_status = \DB::table('booking_status')->orderby('order', 'asc')->get();
        $insurances     = \DB::table('car_insurance')->get();
        $shops          = \DB::table('car_shop')->orderby('id', 'asc')->get();
        $carclasses     = \DB::table('car_inventory as ci')
            ->join('car_model as cm','cm.id','=','ci.model_id')
            ->join('car_class_model as ccm','ccm.model_id','=','ci.model_id')
            ->join('car_type as ct','ct.id','=','cm.type_id')
            ->join('car_class as cc','cc.id','=','ccm.class_id')
            ->select(['ci.id','ci.numberplate1','ci.numberplate2','ci.numberplate3','ci.numberplate4','cm.id as model_id','cm.name as model_name','cc.id as class_id',
                'cc.name as class_name','ct.id as type_id','ct.name as type_name'])
            ->where('ci.status','1')
            ->where('ci.delate_flag','0')
            ->get();

        $hours          = ['09:00','09:30','10:00','10:30',
            '11:00','11:30','12:00','12:30','13:00','13:30','14:00',
            '14:30','15:00','15:30','16:00','16:30','17:00','17:30',
            '18:00','18:30','19:00','19:30'];
        $data = [
            'route'         =>  $route  ,
            'subroute'      =>  $subroute  ,
            'admin'         => $admin,
            'user'          => (object)$user,
            'booking_status'=> $booking_status,
            'reservations'  => $reservations,
            'flight_lines'  => $flight_lines,
            'shops'         => $shops,
            'paymethods'    => $paymethods,
            'carclasses'    => $carclasses,
            'insurances'    => $insurances,
            'hour'          => $hours,
        ];

        return View('pages.admin.booking.add-manual')->with($data);
    }

    /*** Store a newly created resource in storage.*
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response */
    public function update(Request $request)
    {

        $admin = Auth::user();
        if(!Auth::check() || !$admin->isAdmin())
            return Redirect::back();
        $portal_flag = $request->input('portal_flag');
        $validate_rule = [
            'admin_id'          => 'integer|required',
            'book_id'           => 'integer|required',
            'user_id'           => 'integer|required',
            'phone'             => '',
            //'emergency_phone'   => 'required',
            'passengers'        => 'integer|required',
            'pickup_id'            => 'required',
            'dropoff_id'           => 'required',
            'inventory_id'      => 'required',
            'discount'          => 'integer',
            //'given_points'      => 'integer',
            'depart_date'       => 'date_format:"Y/m/d"|required',
            'depart_time'       => 'date_format:"H:i"|required',
            'return_date'       => 'date_format:"Y/m/d"|required',
            'return_time'       => 'date_format:"H:i"|required',
            'reservations'      => 'integer|required',
        ];

        if($portal_flag == '0') {
           // $validate_rule['email'] = 'required|email';
           // $validate_rule['last_name'] = 'required';
           // $validate_rule['first_name'] = 'required';
        }

        $validator = Validator::make($request->all(), $validate_rule );

        if ($validator->fails()) {
            $this->throwValidationException( $request, $validator );
        } else {
            $route          = ServerPath::getRoute();
            $subroute       = ServerPath::getSubRoute();

            $client_id = $request->input('user_id');
            DB::beginTransaction();
            if($portal_flag == '0') {// case of our system. this means this is not from portal
                // user register
            }
            $depart_date = $request->input('depart_date');
            $depart_time = $request->input('depart_time');
            $depart_time    = ServerPath::changeTime($depart_time);
            $return_date = $request->input('return_date');
            $return_time = $request->input('return_time');
            $return_time    = ServerPath::changeTime($return_time);
            $split = explode('/', $request->input('depart_date'));
            $depart_datetime = $split[0].'-'.$split[1].'-'.$split[2].' '.$depart_time.':00';
            $split = explode('/', $request->input('return_date'));
            $return_datetime = $split[0].'-'.$split[1].'-'.$split[2].' '.$return_time.':00';
            $inventory_ids  = explode("_",$request->input('inventory_id'));

            if(count($inventory_ids) > 2) {
                $inventory_id   = $inventory_ids[0];
                $class_id       = $inventory_ids[1];
                $model_id       = $inventory_ids[2];
                $type_id        = $inventory_ids[3];
            }else {
                $inventory_id   = 0;
                $class_id       = 0;
                $model_id       = 0;
                $type_id        = 0;
            }
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night = $request_days['night'];
            $day   = $request_days['day'];
            $status              = $request->input('status');
            $change_status       = $request->input('change_status');
            $wait_status         = $request->input('wait_status');
            $free_option         = $request->input('free_options');
            $free_options_category       = $request->input('free_options_category','0');
            if($free_options_category == '1') {//domestic
                $option         = CarOption::where('google_column_number', '101')->first();
                $free_option    = $option->id;
                $wait_status    = '1';
            }
            if($free_options_category == '2') { //international
                $option         = CarOption::where('google_column_number', '102')->first();
                $free_option    = $option->id;
                $wait_status    = '1';
            }
            if($free_options_category == '3' || $free_options_category == '0' ) { //smart driveout paid option
                $free_option    = '0'; // no pickup service
                $wait_status    = '0';
            }

            $req = array();
            //change departure date and return date
            $depart_task = $request->input('depart_task','0');
            $return_task = $request->input('return_task','0');
            if($depart_task == '0'){
                $req['departing']          = $depart_datetime;
                $req['rent_days']          = $night.'_'.$day;
            }
            if($return_task == '0'){
                $req['returning']          = $return_datetime;
                $req['returning_updated']  = $return_datetime;
                $req['rent_days']          = $night.'_'.$day;
            }

            //update original price
            if($request->has('original_update')) {
                //get insurance
                $ins_search_condition   = $request->input('ins_search_condition');
                $ins_flags              = $request->input('ins_flags');
                $ins_basic_price        = $request->input('ins_basic_prices');
                $insurance1 = 0 ;
                $insurance2 = 0;
                $ins = 0;
                if(!empty($ins_search_condition)) {
                    foreach ($ins_search_condition as $index) {
                        if ($index == '1') {
                            if ($ins_flags[$ins] == '1') {
                                $insurance1 = $ins_basic_price[$ins] * $day;
                            }
                        }
                        if ($index == '2') {
                            if ($ins_flags[$ins] == '1') {
                                $insurance2 = $ins_basic_price[$ins] * $day;
                                //$insurance2 = ServerPath::getInsurancePrice(2, $class_id) * $day;
                            }
                        }
                        $ins++;
                    }
                }

                $option_ids          = $request->input('option_ids');
                $option_numbers      = $request->input('option_numbers');
                $option_basic_prices = $request->input('option_basic_prices');

                $option_order = array();
                for($i=0; $i < count($option_numbers) ;$i++) {
                    if($option_numbers[$i] == '0') array_push($option_order,$i);
                }
                foreach($option_order as $order) {
                    unset($option_ids[$order]);
                    unset($option_numbers[$order]);
                    unset($option_basic_prices[$order]);
                }
                if(!empty($option_ids)) {
                    $option_ids = array_values($option_ids);
                    $option_numbers = array_values($option_numbers);
                    $option_basic_prices = array_values($option_basic_prices);
                }

                $option_price        = 0;
                $m = 0;
                if(!empty($option_basic_prices)) {
                    foreach ($option_basic_prices as $price) {
                        $option_price += $price * $option_numbers[$m];
                        $m++;
                    }
                }
                $basic_price    = $request->input('basic_price');
                $virtual_payment = $request->input('virtual_payment');
                $extend_day = 0;
                if(!empty($request->input('extend_day'))) $extend_day = $request->input('extend_day');
                $extend_return_date = date('Y-m-d');
                if(!empty($request->input('extend_return_date'))) $extend_return_date = $request->input('extend_return_date');
                $extend_insurance1 = 0;
                if(!empty($request->input('extend_insurance1'))) $extend_insurance1 = $request->input('extend_insurance1');
                $extend_insurance2 = 0;
                if(!empty($request->input('extend_insurance2'))) $extend_insurance2 = $request->input('extend_insurance2');
                $extend_basic_price = 0;
                if(!empty($request->input('extend_basic_price'))) $extend_basic_price = $request->input('extend_basic_price');
                $extend_payment = 0;
                if(!empty($request->input('extend_payment'))) $extend_payment = $request->input('extend_payment');
                $extend_options_price = 0;
                if(!empty($request->input('option_price_snow'))) $extend_options_price = $request->input('option_price_snow');
                $extend_options_id = 0;
                if(!empty($request->input('option_id_snow'))) $extend_options_id = $request->input('option_id_snow');
                $extend_options_number = 0;
                if($extend_options_price > 0) {
                    $extend_options_number = 1;
                }

                $extend_pay_method = $request->input('extend_pay_method');
                $extend_pay_status = $request->input('extend_pay_status');
                if($extend_payment == 0) {
                    $extend_pay_method = 0;
                    $extend_pay_status = 0;
                }
                $discount = $request->input('discount');
                $given_points = $request->input('given_points');

                if(!empty($given_points))
                    $req['given_points']       = $given_points;
                $etc_card = $request->input('etc_card');
                if(!is_numeric($etc_card)) $etc_card = 0;
                $payment = $basic_price+$insurance1+$insurance2+$option_price+$extend_payment+$discount + $etc_card + $virtual_payment;

                if(!empty($option_ids)) {
                    $req['paid_options'] = implode(',', $option_ids);
                    $req['paid_options_price'] = implode(',', $option_basic_prices);
                    $req['paid_option_numbers'] = implode(',', $option_numbers);
                }else {
                    $req['paid_options'] = '';
                    $req['paid_options_price'] = '';
                    $req['paid_option_numbers'] = '';
                }

                $req['insurance1']         = $insurance1;
                $req['insurance2']         = $insurance2;
                $req['etc_card']           = $etc_card;
                $req['option_price']       = $option_price;
                $req['departing']          = $depart_datetime;
                $req['returning']          = $return_datetime;
                $req['returning_updated']  = $return_datetime;
                $req['rent_days']          = $night.'_'.$day;
                $req['discount']           = $discount;
                $req['extend_return_date'] = $extend_return_date;
                if(!empty($request->input('extend_return_date'))&& $extend_payment > 0) {
                    $extend_return_date = date('Y-m-d', strtotime($extend_return_date)).' '.date('H:i', strtotime($return_time));
                    $extend_return_date = date('Y-m-d H:i', strtotime($extend_return_date));
                    $req['returning_updated'] = $extend_return_date;
                    $start_date = $return_date." ".$return_time;
                    $start_date = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($start_date)));
                    $check_book = ServerPath::getconfirmBookingExtend($inventory_id, $start_date, $extend_return_date, '');
                    $check_inspection = ServerPath::getConfirmInspection($inventory_id, $start_date , $extend_return_date, '', '');
                    if($check_book == true && $check_inspection == true) {
                    }else{
                        return back()->with('error', trans('booking.duplicatebooking'));
                    }
                }
               // if($extend_day == '0') $req['returning_updated'] = '0000-01-01 00:00:00';
                $req['extend_day']         = $extend_day;
                $req['extend_pay_method']  = $extend_pay_method;
                $req['extend_pay_status']  = $extend_pay_status;
                $req['extend_insurance1']  = $extend_insurance1;
                $req['extend_insurance2']  = $extend_insurance2;
                $req['extend_basic_price'] = $extend_basic_price;
                $req['extend_payment']     = $extend_payment;
                $req['extend_options_id']  = $extend_options_id;
                $req['extend_options_number']   = $extend_options_number;
                $req['extend_options_price']    = $extend_options_price;
                $req['extend_option_price']     = $extend_options_price;
                $req['payment']            = $payment;
                $pay_method                = $request->input('pay_method');
                $req['pay_method']         = $pay_method;
                $pay_status = 0;
                if($pay_method > 0) {
                    $pay_status = 1;
                    $req['paid_date']      = date('Y-m-d');
                }
                $req['pay_status']         = $pay_status;
            }
            $pay_method     = $request->input('pay_method');
            $paid_date      = $request->input('paid_date');
            if($paid_date == '----/--/--') $paid_date =  date('Y-m-d');
            if($pay_method > 0) {
                $req['paid_date']      = $paid_date;
            }
            //update padi date if additional paid date exist
            $add_paid_date              = $request->input('additional_paid_date');
            $add_paid_date_id           = $request->input('additional_paid_date_id');
            if(!empty($add_paid_date)) {
                for($d=0; $d < count($add_paid_date);$d++ ) {
                    $p_date     = date('Y-m-d',strtotime($add_paid_date[$d]));
                    $p_date_id  = $add_paid_date_id[$d];
                    $updateaddpaid = \DB::table('bookings_price')
                        ->where('id', $p_date_id)
                        ->update(['paid_date'=>$p_date]);
                }
            }
            //update additional price
            if ($request->has('additional_update')) {
                $additional = array();
                $additional_id              = $request->input('additional_id');
                $additional['id']           = $additional_id;
                $additional['type']         = 'add';
                $add_ins_search_condition   = $request->input('add_ins_search_condition');
                $add_ins_flags              = $request->input('add_ins_flags');
                $add_ins_basic_prices       = $request->input('add_ins_basic_prices');
                $add_ins = 0;
                $add_insurance1 = 0;
                $add_insurance2 = 0;
                if(!empty($add_ins_search_condition)) {
                    foreach ($add_ins_search_condition as $index) {
                        if ($index == '1') {
                            if ($add_ins_flags[$add_ins] == '1') {
                                $add_insurance1 = $add_ins_basic_prices[$add_ins] * $day;
                            }
                        }
                        if ($index == '2') {
                            if ($add_ins_flags[$add_ins] == '1') {
                                $add_insurance2 = $add_ins_basic_prices[$add_ins] * $day;
                            }
                        }
                        $add_ins++;
                    }
                }
                $additional['insurance1']           = $add_insurance1;
                $additional['insurance2']           = $add_insurance2;
                $add_option_ids                     = $request->input('add_option_ids');
                $add_option_numbers                 = $request->input('add_option_numbers');
                $add_option_basic_prices            = $request->input('add_option_basic_prices');

                $add_option_order = array();
                for($i=0; $i < count($add_option_numbers) ;$i++) {
                    if($add_option_numbers[$i] == '0') array_push($add_option_order,$i);
                }
                foreach($add_option_order as $order) {
                    unset($add_option_ids[$order]);
                    unset($add_option_numbers[$order]);
                    unset($add_option_basic_prices[$order]);
                }
                $option_price = 0;
                if(!empty($add_option_ids)) {
                    //$c = 0;
                    foreach ($add_option_basic_prices as  $key => $value) {
                        $option_price += $add_option_numbers[$key] * $add_option_basic_prices[$key];
                        //$c++;
                    }
                    $add_option_ids = array_values($add_option_ids);
                    $additional['paid_options'] = implode(',', $add_option_ids);
                    $add_option_numbers = array_values($add_option_numbers);
                    $additional['paid_options_number'] = implode(',', $add_option_numbers);
                    $add_option_basic_prices = array_values($add_option_basic_prices);
                    $additional['paid_options_price'] = implode(',', $add_option_basic_prices);
                    $additional['option_price'] = $option_price;
                }
                $add_all_insurance_price              = $request->input('add_all_insurance_price');
                $add_all_option_price                 = $request->input('add_all_option_price');
                $add_adjustment_price                 = $request->input('add_adjustment_price');
                $additional['adjustment_price']       = $add_adjustment_price;
                $add_pay_method                       = $request->input('add_pay_method');
                $additional['pay_method']             = $add_pay_method;
                //$add_pay_status                       = $request->input('add_pay_status');
                $add_pay_status                       = 0;
                if($add_pay_method > 0) {
                    $add_pay_status = 1;
                    $additional['paid_date']          = date('Y-m-d');
                }
                $additional['pay_status']             = $add_pay_status;

                //get extend night price
                $add_extended_day = 0;
                if(!empty($request->input('add_extend_day'))) $add_extended_day = $request->input('add_extend_day');
                $additional['extend_day'] = $add_extended_day;
                $add_extend_return_date = date('Y-m-d');
                if(!empty($request->input('add_extend_return_date'))) $add_extend_return_date = $request->input('add_extend_return_date');
                $additional['extend_return_date'] = $add_extend_return_date;
                $add_extend_insurance1 = 0;
                if(!empty($request->input('add_extend_insurance1'))) $add_extend_insurance1 = $request->input('add_extend_insurance1');
                $additional['extend_insurance1'] = $add_extend_insurance1;
                $add_extend_insurance2 = 0;
                if(!empty($request->input('add_extend_insurance2'))) $add_extend_insurance2 = $request->input('add_extend_insurance2');
                $additional['extend_insurance2'] = $add_extend_insurance2;
                $add_extend_basic_price = 0;
                if(!empty($request->input('add_extend_basic_price'))) $add_extend_basic_price = $request->input('add_extend_basic_price');
                $additional['extend_basic_price'] = $add_extend_basic_price;
                $add_extend_payment = 0;
                if(!empty($request->input('add_extend_payment'))) $add_extend_payment = $request->input('add_extend_payment');
                $additional['extend_payment'] = $add_extend_payment;
                $add_extend_options_price = 0;
                if(!empty($request->input('add_option_price_snow'))) $add_extend_options_price = $request->input('add_option_price_snow');
                $additional['extend_option_price'] = $add_extend_options_price;
                $additional['extend_options_price'] = $add_extend_options_price;
                $add_extend_options_id = 0;
                if(!empty($request->input('add_option_id_snow'))) $add_extend_options_id = $request->input('add_option_id_snow');
                $additional['extend_options_id'] = $add_extend_options_id;
                $add_extend_options_number = 0;
                if($add_extend_options_price > 0) {
                    $add_extend_options_number = 1;
                }
                $additional['extend_options_number'] = $add_extend_options_number;
                $add_etc_card = $request->input('add_etc_card');
                if(!is_numeric($add_etc_card)) $add_etc_card = 0;
                $additional['etc_card'] = $add_etc_card;
                $add_total_price                     = $add_all_insurance_price + $add_all_option_price + $add_adjustment_price+$add_extend_payment + $add_etc_card;
                $additional['total_price']           = $add_total_price;

                $updatebookingprice = \DB::table('bookings_price')
                    ->where('id', $additional_id)
                    ->update($additional);
                if (!$updatebookingprice) {
                    DB::rollBack();
                }

                if(!empty($request->input('add_extend_return_date'))&& $add_extend_payment > 0)
                {
                    $add_extend_return_date = date('Y-m-d', strtotime($add_extend_return_date)).' '.date('H:i',strtotime($return_time));
                    $add_extend_return_date = date('Y-m-d H:i', strtotime($add_extend_return_date));
                    $req['returning_updated'] = $add_extend_return_date;
                }
                if($add_extended_day == '0') $req['returning_updated'] = '0000-01-01 00:00:00';
            }

            //get additional price

            if($change_status != '0') {
                $status = $change_status;
                $req['status']    = $status;

                if($change_status == '6') { //complete depart task
                    //check pay method ' status
                    $book_id =  $request->input('book_id');
                    //compare previous status
                    $original_status = \DB::table('bookings')->where('id', $book_id)
                        ->where('status', '6')
                        ->first();
                    //if(empty($original_status)) {
                        $pay_flag = true;
                        $check_payment = \DB::table('bookings')->where('id', $book_id)
                            ->where('pay_status', '0')
                            ->first();
                        if (!empty($check_payment)) $pay_flag = false;
                        $check_payment_sub = \DB::table('bookings_price')
                            ->where('book_id', $book_id)
                            ->where('pay_status', '0')
                            ->where('price_type','1') // price_type=1 means depart task price
                            ->first();
                        if (!empty($check_payment_sub)) $pay_flag = false;
                        if ($pay_flag == false)
                            return back()->with('error', trans('booking.faildpaymethod'));
                        $req['depart_task'] = '1';
                        $req['com_status'] = '1';
                        $req['depart_task_date'] = date('Y-m-d');
                    //}
                }
                if($change_status == '8') { // complete return task
                    //check pay method ' status
                    $book_id =  $request->input('book_id');
                    //compare previous status
                    $original_status = \DB::table('bookings')->where('id', $book_id)
                        ->where('status', '8')
                        ->first();
                    //if(empty($original_status)) {
                        $pay_flag = true;
                        $check_payment = \DB::table('bookings')->where('id', $book_id)
                            ->where('pay_status', '0')
                            ->first();
                        if (!empty($check_payment)) $pay_flag = false;
                        $check_payment_sub = \DB::table('bookings_price')
                            ->where('book_id', $book_id)
                            ->where('pay_status', '0')
                            ->first();
                        if (!empty($check_payment_sub)) $pay_flag = false;
                        if ($pay_flag == false)
                            return back()->with('error', trans('booking.faildpaymethod'));
                        //change miles status
                        if ($request->input('depart_task') == '1') { // completed status
                            $miles = $request->input('miles');
                            $before_miles = $request->input('before_miles');
                            if (intval($before_miles) < intval($miles)) {
                                $update = \DB::table('car_inventory')
                                    ->where('id', $inventory_id)
                                    ->update(['current_mileage' => $miles]);
                                $req['miles'] = $miles;
                                $req['previous_miles'] = $before_miles;
                                $req['end_status'] = '1';
                                $req['return_task'] = '1';
                                $req['mile_status'] = '1';
                                $req['end_status'] = '1';
                                $req['return_task_date'] = date('Y-m-d');
                            } else {
                                return back()->with('error', trans('booking.faildmiles'));
                            }
                        }
                    //}
                }

                if( $change_status == '1' ) {//reservation
                    //check pay method ' status
                    $book_id =  $request->input('book_id');
                    //compare previous status
                    $original_status = \DB::table('bookings')->where('id', $book_id)
                        ->first();
                    $current_status = $original_status->status;
                    //if($current_status != '1') { // reservation
                        $req['depart_task'] = '0';
                        $req['com_status'] = '0';
                        $req['return_task'] = '0';
                        $req['end_status'] = '0';
                        $req['cancel_total'] = 0;
                        $req['cancel_basic'] = 0;
                        $req['cancel_percent'] = 0;
                        $req['cancel_fee'] = 0 ;
                        $req['cancel_status'] = 0;
                        $req['cancel_date'] = null;
                    //}
                }
                if($change_status == '9') { // cancel status
                    //check pay method ' status
                    $book_id =  $request->input('book_id');
                    //compare previous status
                    $original_status = \DB::table('bookings')->where('id', $book_id)
                        ->where('status', '9')
                        ->first();
                    //if(empty($original_status)) {
                        //set cancellation price
                        $cancel_date_status = $request->input('cancel_date_status');
                        $cancel_date_status_nofee = $request->input('cancel_date_status_nofee');

                        if ($request->input('cancel_fee') > 0 ) {
                            $req['cancel_basic'] = $request->input('cancel_basic');
                            $req['cancel_percent'] = $request->input('cancel_percent');
                            $req['cancel_fee'] = $request->input('cancel_fee');
                            $req['cancel_total'] = $request->input('cancel_total');
                            $req['cancel_status'] = $request->input('cancel_status');
                            $req['cancel_date'] = date('Y/m/d', strtotime($request->input('cancel_date')));
                            $req['canceled_at'] = date('Y-m-d');
                        }
                        if ($request->input('cancel_fee') == 0) {
                            $req['cancel_percent'] = 0;
                            $req['cancel_basic'] = 0;
                            $req['cancel_fee'] = 0;
                            $req['cancel_status'] = 0;
                            $req['cancel_date'] = date('Y/m/d', strtotime($request->input('cancel_date_nofee')));
                            $req['cancel_total'] = $request->input('cancel_total');
                            $req['canceled_at'] = date('Y-m-d');
                        }

                    //}
                    $book_id =  $request->input('book_id');
                    $check_paid = \DB::table('bookings')->where('id', $book_id)
                        ->where('web_payment', '>', 0)
                        ->where('pay_status', 0)->first();
                    $cancel_fee = $request->input('cancel_fee', 0);
                    $cancel_fee_rate = $request->input('cancel_percent', 0);

                }
            }

            $admin_id   = $request->input('admin_id') ;
            if(!empty($admin_id)) $req['admin_id'] = $admin_id;
            $req['status']              = $status;
            $req['updated_at']          = date('Y-m-d');
            $req['client_id']           = $client_id;
            if(!empty($request->input('driver_name')))
            $req['driver_name']         = $request->input('driver_name');
            if(!empty($request->input('emergency_phone')))
            $req['emergency_phone']     = $request->input('emergency_phone');
            if(!empty($request->input('flight_line')))
            $req['flight_line']         = $request->input('flight_line');
            if(!empty($request->input('flight_number')))
            $req['flight_number']       = $request->input('flight_number');
            if(!empty($request->input('pickup_id')))
            $req['pickup_id']           = $request->input('pickup_id');
            if(!empty($request->input('dropoff_id')))
            $req['dropoff_id']          = $request->input('dropoff_id');
            $req['inventory_id']        = $inventory_id;
            $req['class_id']            = $class_id;
            if(!empty($request->input('basic_price')))
                    $req['basic_price']         = $request->input('basic_price');
            $req['model_id']            = $model_id;
            $req['free_options']        = $free_option;
            $req['free_options_category'] = $free_options_category;
            $req['wait_status']         = $wait_status;
            $req['passengers']          = $request->input('passengers');
            $req['admin_memo']          = $request->input('admin_memo');
            $req['reservation_id']      = $request->input('reservations');
            if(!empty($request->input('paying_memo')))
            $req['paying_memo']         = $request->input('paying_memo');
            $req['updated_at']          = date('Y-m-d H;i:s');

            $updatebooking = \DB::table('bookings')->where('id', $request->input('book_id'))->update($req);
            if (!$updatebooking) {
                DB::rollBack();
            }
            DB::commit();
            return redirect('/booking/detail/'.$request->input('book_id'));
        }
    }

    // send notification when create cancellation fee
    public function sendnotifi_cancel(Request $request) {
            $ret = array();
            $cancel_total   = $request->input('cancel_total');
            $cancel_basic   = $request->input('cancel_basic');
            $cancel_percent = $request->input('cancel_percent');
            $cancel_fee     = $request->input('cancel_fee');
            $cancel_date    = $request->input('cancel_date');
            $cancel_status  = $request->input('cancel_status');
            $fee_status     = $request->input('fee');
            $book_id        = $request->input('book_id');

            $bo      = \DB::table('bookings as b')
                    ->join('car_class as cc','cc.id','=','b.class_id')
                    ->join('users as u', 'b.client_id','=','u.id')
                    ->join('profiles as p', 'b.client_id', '=', 'p.user_id')
                    ->select(['b.*','u.first_name','u.last_name','cc.name as class_name','u.email as user_email'])
                    ->where('b.id', $book_id)->first();
            if(empty($bo)) {
                $ret['code'] = '401';
                return Response::json($ret);
            }

            $pay_status = $bo->pay_status;
            $lang = ServerPath::lang();
            if ($lang == 'en') {
                if($pay_status == 1) {  // paid
                    if($fee_status == 'no_fee') { // without fee
                        $template = \DB::table('mail_templates')->where('mailname', 'paid_cancel_nofee_complete_user_en')->first();
                    } else {    // with fee
                        $template = \DB::table('mail_templates')->where('mailname', 'paid_cancel_fee_complete_user_en')->first();
                    }
                } else {    // unpaid
                    if($fee_status == 'no_fee') { // without fee
                        $template = \DB::table('mail_templates')->where('mailname', 'unpaid_cancel_nofee_complete_user_en')->first();
                    } else {    // with fee
                        $template = \DB::table('mail_templates')->where('mailname', 'unpaid_cancel_fee_complete_user_en')->first();
                    }
                }
            } else {
                if($pay_status == 1) {  // paid
                    if($fee_status == 'no_fee') { // without fee
                        $template = \DB::table('mail_templates')->where('mailname', 'paid_cancel_nofee_complete_user')->first();
                    } else {    // with fee
                        $template = \DB::table('mail_templates')->where('mailname', 'paid_cancel_fee_complete_user')->first();
                    }
                } else {    // unpaid
                    if($fee_status == 'no_fee') { // without fee
                        $template = \DB::table('mail_templates')->where('mailname', 'unpaid_cancel_nofee_complete_user')->first();
                    } else {    // with fee
                        $template = \DB::table('mail_templates')->where('mailname', 'unpaid_cancel_fee_complete_user')->first();
                    }
                }
            }
            if($lang == 'ja'){
                $depart_datetime = date('Y年m月d日H時i分', strtotime($bo->departing));
                $return_datetime = date('Y年m月d日H時i分', strtotime($bo->returning));
            } else {
                $depart_datetime = date('Y/m/d H:i', strtotime($bo->departing));
                $return_datetime = date('Y/m/d H:i', strtotime($bo->returning));
            }

            if(!empty($template)) {
                $subject = $template->subject;
                $content = $template->content;
                $user = \DB::table('users')->where('id', $bo->client_id)->first();
                $user_name = $user->last_name.$user->first_name;
                $content = str_replace('{user_name}', $user_name, $content);
                $content = str_replace('{booking_id}', $bo->booking_id, $content);
                $shop = \DB::table('car_shop')->where('id', $bo->pickup_id)->first();
                $content = str_replace('{shop_name}', $lang == 'ja'? $shop->name : $shop->name_en, $content);
                $content = str_replace('{departing_time}', $depart_datetime, $content);
                $content = str_replace('{returning_time}', $return_datetime, $content);
                $car = \DB::table('car_inventory')->where('id', $bo->inventory_id)->first();
                $car_model = \DB::table('car_model')->where('id', $car->model_id)->first();
                if($lang == 'ja')
                    $smoke = $car->smoke == 1? '喫煙' : '禁煙';
                else
                    $smoke = $car->smoke == 1? 'smoking' : 'non-smoking';
                $content = str_replace('{car_model_name}', $car_model->name, $content);
                $content = str_replace('{car_short_name}', $car->shortname, $content);
                $content = str_replace('{smoke}', $smoke, $content);
                $content = str_replace('{car_capacity}', $car->max_passenger.($lang=='ja'?'人乗り':' peoples'), $content);
                $insurance_type = '';
                $insurance_part = '';
                $insurance_price1 = $bo->insurance1;
                if($insurance_price1 == '') $insurance_price1 = 0;
                $insurance_price2 = $bo->insurance2;
                if($insurance_price2 == '') $insurance_price2 = 0;

                if($lang == 'ja'){
                    if($insurance_price1 == 0 && $insurance_price2 == 0) {
                        $insurance_part = '';
                        $insurance_type = 'なし';
                    }
                    if($insurance_price1 > 0 && $insurance_price2 == 0) {
                        $insurance_part = '免責補償：'.$insurance_price1.'円<br>';
                        $insurance_type = '免責補償';
                    }
                    if($insurance_price1 > 0 && $insurance_price2 > 0) {
                        $insurance_part = '免責補償：'.$insurance_price1.'円<br>ワイド免責補償：'.$insurance_price2.'円<br>';
                        $insurance_type = '免責補償/ワイド免責補償';
                    }
                } else {
                    if($insurance_price1 == 0 && $insurance_price2 == 0) {
                        $insurance_part = '';
                        $insurance_type = 'none';
                    }
                    if($insurance_price1 > 0 && $insurance_price2 == 0) {
                        $insurance_part = 'Exemption of Liability Compensation：'.$insurance_price1.'yen<br>';
                        $insurance_type = 'Exemption of Liability Compensation';
                    }
                    if($insurance_price1 > 0 && $insurance_price2 > 0) {
                        $insurance_part = 'Exemption of Liability Compensation：'.$insurance_price1.'yen<br>Wide Protection Package：'.$insurance_price2.'yen<br>';
                        $insurance_type = 'Exemption of Liability Compensation/Wide Protection Package';
                    }
                }
                $content = str_replace('{insurance_type}', $insurance_type, $content);
                $content = str_replace('{insurance_part}', $insurance_part, $content);
                $paid_opt_ids = explode(',', $bo->paid_options);
                $paid_opt_nums = explode(',', $bo->paid_option_numbers);

                $rent_dates = explode('_', $bo->rent_days)[1];
                $option_names = [];
                $option_prices = [];
                $option_price = 0;
                if(count($paid_opt_ids) > 0) {
                    $options = \DB::table('car_option')->whereIn('id', $paid_opt_ids)->get();
                    if(!empty($options)) {
                        foreach ($options as $key=>$option) {
                            $vid = array_search($option->id, $paid_opt_ids);
                            $opt_num = $paid_opt_nums[$vid];

                            if($lang == 'ja') {
                                array_push($option_names, $option->name.'('.$opt_num.')'); // チャイルドシート(1)
                                if($option->charge_system == 'one') {
                                    array_push($option_prices, $option->name.' '.$option->price.'円'.' x '.$opt_num.'個'); // childseat 540円 x 1個
                                    $option_price += $option->price;
                                } else {
                                    array_push($option_prices, $option->name.' '.$option->price.'円'.' x '. $rent_dates.'日');
                                    $option_price += $option->price * $rent_dates;
                                }
                            } else {
                                array_push($option_names, $option->name_en.'('.$opt_num.')'); // チャイルドシート(1)
                                if($option->charge_system == 'one') {
                                    array_push($option_prices, $option->name_en.' '.$option->price.'yen'.' x '.$opt_num.''); // childseat 540円 x 1個
                                    $option_price += $option->price;
                                } else {
                                    array_push($option_prices, $option->name_en.' '.$option->price.'yen'.' x '. $rent_dates.'dates');
                                    $option_price += $option->price * $rent_dates;
                                }
                            }
                        }
                    }
                }
                $free_opts = $bo->free_options;
                $free_opt_ids = [];
                if(strpos(':', $free_opts) !== false) {
                    $vs = \GuzzleHttp\json_decode($free_opts);
                    foreach($vs as $v) {
                        $free_opt_ids[] = $v->option_id;
                    }
                } else {
                    $free_opt_ids = explode(',', $free_opts);
                }
                if( !empty($free_opt_ids) ) {
                    foreach ($free_opt_ids as $fopt) {
                        $fop = \DB::table('car_option')->where('id', $fopt)->first();
                        if(!is_null($fop)){
                            if($lang == 'ja'){
                                array_push($option_names, '無料'.$fop->name);
                                array_push($option_prices, '無料'.$fop->name.' 0円');
                            } else {
                                array_push($option_names, 'free'.$fop->name);
                                array_push($option_prices, 'free'.$fop->name.' 0yen');
                            }
                        }
                    }
                }
                $content = str_replace('{options}', implode(',', $option_names), $content);
                $content = str_replace('{option_detail}', implode(',', $option_prices), $content);
                $content = str_replace('{option_price}', $option_price, $content);
                $content = str_replace('{base_price}', $bo->basic_price, $content);
                $total_price = $bo->basic_price + $option_price + $insurance_price1 + $insurance_price2;
                $content = str_replace('{total_price}', $total_price, $content);
                $content = str_replace('{paid_price}', $bo->web_payment, $content);
                $content = str_replace('{cancel_date}', $lang == 'ja'? date('Y年m月d日'):date('Y/m/d'), $content);
                $content = str_replace('{cancel_fee}', $cancel_fee, $content);
                $content = str_replace('{cancel_fee_rate}', $cancel_percent, $content);
//                $content = $message;
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' =>$template->sender,  'email' =>$bo->user_email);
                $data[] = $data1;
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' =>$template->sender,  'email' =>'sinchong1989@gmail.com');
                $data[] = $data1;
            }
            //send to admin
            if($fee_status == 'no_fee') { // without fee
                $admin_template = \DB::table('mail_templates')->where('mailname', 'cancel_nofee_admin')->first();
            } else {    // with fee
                $admin_template = \DB::table('mail_templates')->where('mailname', 'cancel_fee_admin')->first();
            }
            if(!empty($admin_template)){
                $subject = $admin_template->subject;
                $content = $admin_template->content;
                $user = \DB::table('users')->where('id', $bo->client_id)->first();
                $profile = \DB::table('profiles')->where('user_id', $bo->client_id)->first();
                $user_name = $user->last_name.$user->first_name;
                $content = str_replace('{booking_id}', $bo->booking_id, $content);
                $content = str_replace('{user_name}', $user_name, $content);
                $content = str_replace('{phone}', $profile->phone, $content);
                $content = str_replace('{email}', $user->email, $content);

                $shop = \DB::table('car_shop')->where('id', $bo->pickup_id)->first();
                $content = str_replace('{shop_name}',$shop->name, $content);

                $depart_datetime = date('Y年m月d日H時i分', strtotime($bo->departing));
                $return_datetime = date('Y年m月d日H時i分', strtotime($bo->returning));
                $content = str_replace('{departing}', $depart_datetime, $content);
                $content = str_replace('{returning}', $return_datetime, $content);

                $car_class = \DB::table('car_class')->where('id', $bo->class_id)->first();
                $content = str_replace('{car_class}', $car_class->name, $content);
                $car_inventory = \DB::table('car_inventory')->where('id', $bo->inventory_id)->first();
                $content = str_replace('{inventory_number}', $car_inventory->numberplate4, $content);

                $insurance_price1 = $bo->insurance1;
                if($insurance_price1 == '') $insurance_price1 = 0;
                $insurance_price2 = $bo->insurance2;
                if($insurance_price2 == '') $insurance_price2 = 0;
                if($insurance_price1 == 0 && $insurance_price2 == 0) {
                    $insurance_part = '';
                    $insurance_type = 'なし';
                }
                if($insurance_price1 > 0 && $insurance_price2 == 0) {
                    $insurance_part = '免責補償：'.$insurance_price1.'円<br>';
                    $insurance_type = '免責補償';
                }
                if($insurance_price1 > 0 && $insurance_price2 > 0) {
                    $insurance_part = '免責補償：'.$insurance_price1.'円<br>ワイド免責補償：'.$insurance_price2.'円<br>';
                    $insurance_type = '免責補償/ワイド免責補償';
                }
                $content = str_replace('{insurances}', $insurance_type.' '.$insurance_part, $content);

                $paid_opt_ids = explode(',', $bo->paid_options);
                $paid_opt_nums = explode(',', $bo->paid_option_numbers);
                $rent_dates = explode('_', $bo->rent_days)[1];
                $option_names = [];
                if(count($paid_opt_ids) > 0) {
                    $options = \DB::table('car_option')->whereIn('id', $paid_opt_ids)->get();
                    if(!empty($options)) {
                        foreach ($options as $key=>$option) {
                            $vid = array_search($option->id, $paid_opt_ids);
                            $opt_num = $paid_opt_nums[$vid];
                            array_push($option_names, $option->name.'('.$opt_num.')'); // チャイルドシート(1)
                        }
                    }
                }
                $free_opts = $bo->free_options;
                $free_opt_ids = [];
                if(strpos(':', $free_opts) !== false) {
                    $vs = json_decode($free_opts);
                    foreach($vs as $v) {
                        $free_opt_ids[] = $v->option_id;
                    }
                } else {
                    $free_opt_ids = explode(',', $free_opts);
                }
                if( !empty($free_opt_ids) ) {
                    foreach ($free_opt_ids as $fopt) {
                        $fop = \DB::table('car_option')->where('id', $fopt)->first();
                        if(!is_null($fop)){
                                array_push($option_names, '無料'.$fop->name);
                        }
                    }
                }
                $content = str_replace('{options}', implode(',', $option_names), $content);
                $total_price = $this->paidmount($bo->id) + $this->unpaidmount($bo->id);
                $content = str_replace('{total_price}', $total_price, $content);
                $book_date = date('Y年m月d日H時i分', strtotime($bo->created_at));
                $content = str_replace('{book_date}', $book_date, $content);
                $book_class = $car_class->name;
                $content = str_replace('{book_class}', $book_class, $content);
                $book_price = $total_price;
                $content = str_replace('{book_price}', $book_price, $content);
                $content = str_replace('{cancel_percent}', $cancel_percent, $content);
                $content = str_replace('{cancel_fee}', $cancel_fee, $content);
                $content = str_replace('{cancel_date}', date('Y年m月d日H時i分'), $content);
                $server = \DB::table('server_name')->orderby('id')->first();
                if(strpos($server->name, 'hakoren') === false){
                    // for motocle8 test
                    $mail_addresses = [
                        'future.syg1118@gmail.com',
                        'mailform@motocle.com ',
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
                    $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $admin_template->sender, 'email' => $address);
                    $data[] = $data1;
                }
            }
            $finaldata = array('data' => json_encode($data, JSON_UNESCAPED_UNICODE));
//        return $finaldata;
        try {
            $ch = array();
            $mh = curl_multi_init();
            $ch[0] = curl_init();

            // set URL and other appropriate options
            $domain = 'http://motocle7.sakura.ne.jp/projects/rentcar/hakoren/public';
            curl_setopt($ch[0], CURLOPT_URL, url('/') . "/mail/vaccine/medkenmail.php");
            //                    curl_setopt($ch[0], CURLOPT_URL, $protocol . $domain . "/mail/vaccine/medkenmail.php");
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

    // send notification when create cancellation fee
    public function sendnotifi_cancel_bk(Request $request) {
            $ret = array();
            $cancel_total   = $request->input('cancel_total');
            $cancel_basic   = $request->input('cancel_basic');
            $cancel_percent = $request->input('cancel_percent');
            $cancel_fee     = $request->input('cancel_fee');
            $cancel_date    = $request->input('cancel_date');
            $cancel_status  = $request->input('cancel_status');
            $fee_status     = $request->input('fee');
            $book_id        = $request->input('book_id');
            $bo      = \DB::table('bookings as b')
                    ->join('car_class as cc','cc.id','=','b.class_id')
                    ->join('users as u', 'b.client_id','=','u.id')
                    ->join('profiles as p', 'b.client_id', '=', 'p.user_id')
                    ->select(['b.*','u.first_name','u.last_name','cc.name as class_name','u.email as user_email'])
                    ->where('b.id', $book_id)->first();
            if(empty($bo)) {
                $ret['code'] = '401';
                return Response::json($ret);
            }

            $template = \DB::table('mail_templates')->where('mailname', 'cancel_fee_user')->first();
            if(!empty($template)) {
                $subject = $template->subject;
                $message = $template->content;
                $message = str_replace('{user_name}', $bo->last_name." ".$bo->first_name, $message);
                $message = str_replace('{book_date}', date('Y年m月d日 H時i分', strtotime($bo->departing)), $message);
                $message = str_replace('{book_class}', $bo->class_name, $message);
                $message = str_replace('{book_price}', $cancel_basic, $message);
                $message = str_replace('{cancel_percent}', $cancel_percent, $message);
                $message = str_replace('{cancel_fee}', $cancel_fee, $message);
                $message = str_replace('{cancel_date}', date('Y年m月d日 H時i分', strtotime($cancel_date)), $message);
                $content = $message;
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' =>$template->sender,  'email' =>$bo->user_email);
                $data[] = $data1;
            }

            $template = \DB::table('mail_templates')->where('mailname', 'cancel_fee_admin')->first();
            if(!empty($template)) {
                $subject = $template->subject;
                $message = $template->content;
                $message = str_replace('{user_name}', $bo->last_name." ".$bo->first_name, $message);
                $message = str_replace('{book_date}', date('Y年m月d日 H時i分', strtotime($bo->departing)) , $message);
                $message = str_replace('{book_class}', $bo->class_name, $message);
                $message = str_replace('{book_price}', $cancel_basic, $message);
                $message = str_replace('{cancel_percent}', $cancel_percent, $message);
                $message = str_replace('{cancel_fee}', $cancel_fee, $message);
                $message = str_replace('{cancel_date}', $cancel_date, $message);
                $content = $message;

                if(strpos($_SERVER['HTTP_HOST'], 'hakoren') === false){
                    // for motocle8 test
                    $mail_addresses = [
                        'future.syg1118@gmail.com',
                        'business@motocle.com'
                    ];
                } else {
                    // for hakoren staffs
                    $mail_addresses = [
    //                        'sinchong1989@gmail.com',
                        'reservation-f@hakoren.com',
                        'reservation-o@hakoren.com',
                        'hakoren2016@gmail.com',
                        'n.08041134223@gmail.com',
                        'sarue0525@gmail.com',
                        'mailform@motocle.com'
                    ];
                }

                foreach ($mail_addresses as $address){
                    $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $template->sender, 'email' => $address);
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
            $domain = 'http://motocle7.sakura.ne.jp/projects/rentcar/hakoren/public';
            curl_setopt($ch[0], CURLOPT_URL, url('/') . "/mail/vaccine/medkenmail.php");
            //                    curl_setopt($ch[0], CURLOPT_URL, $protocol . $domain . "/mail/vaccine/medkenmail.php");
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
    //save additionla price
    public function saveAdditionalPrice(Request $request) {
        $book_id                = $request->input('book_id');
        $ins_search_condition   = $request->input('modal_ins_search_condition');
        $ins_flags              = $request->input('modal_ins_flags');
        $ins_basic_price        = $request->input('modal_ins_basic_prices');
        $departing              = $request->input('modal_departing');
        $returning              = $request->input('modal_returning');
        $depart_task            = $request->input('modal_depart_task');
        $price_type     = '1';
        if($depart_task == '0') $price_type = '1';
        if($depart_task == '1') $price_type = '2';
        $depart_date    = date('Y-m-d', strtotime($departing));
        $depart_time    = date('H:i', strtotime($departing));
        $return_date    = date('Y-m-d', strtotime($returning));
        $return_time    = date('H:i', strtotime($returning));
        $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
        $night = $request_days['night'];
        $day = $request_days['day'];
        $insurance1 = 0 ;
        $insurance2 = 0;
        $ins = 0;
        if(!empty($ins_search_condition)) {
            foreach ($ins_search_condition as $index) {
                if ($index == '1') {
                    if ($ins_flags[$ins] == '1') {
                        $insurance1 = $ins_basic_price[$ins] * $day;
                    }
                }
                if ($index == '2') {
                    if ($ins_flags[$ins] == '1') {
                        $insurance2 = $ins_basic_price[$ins] * $day;
                    }
                }
                $ins++;
            }
        }
        $option_ids          = $request->input('modal_option_ids');
        $option_numbers      = $request->input('modal_option_numbers');
        $option_basic_prices = $request->input('modal_option_basic_prices');


        $option_order = array();
        for($i=0; $i < count($option_numbers) ;$i++) {
            if($option_numbers[$i] == '0') array_push($option_order,$i);
        }
        foreach($option_order as $order) {
            unset($option_ids[$order]);
            unset($option_numbers[$order]);
            unset($option_basic_prices[$order]);
        }
        $option_price = 0;
        if(!empty($option_ids)) {
            $option_ids = array_values($option_ids);
            $option_numbers = array_values($option_numbers);
            $option_basic_prices = array_values($option_basic_prices);

            $count = 0;
            foreach ($option_basic_prices as $pr) {
                $option_price += $option_numbers[$count] * $option_basic_prices[$count];
                $count++;
            }
        }
        $modal_adjustment_price = $request->input('modal_adjustment_price');
        $modal_pay_method = $request->input('modal_pay_method');
        //$modal_pay_status = $request->input('modal_pay_status');
        $modal_pay_status = 0;
        if($modal_pay_method > 0 ) $modal_pay_status = 1;
        if($modal_pay_status == '1' ) $req['paid_date'] = date('Y-m-d');

        $modal_etc_card   = $request->input('modal_etc_card');
        if(!is_numeric($modal_etc_card)) $modal_etc_card = 0;
        $total  = $insurance1+$insurance2+$option_price+$modal_adjustment_price+$modal_etc_card;

        $ret = array();
        $ret['book_id']         = $book_id;
        $ret['type']            = 'add';
        $ret['insurance1']      = $insurance1;
        $ret['insurance2']      = $insurance2;
        if(!empty($option_ids)) {
            $ret['paid_options'] = implode(',', $option_ids);
            $ret['paid_options_number'] = implode(',', $option_numbers);
            $ret['paid_options_price'] = implode(',', $option_basic_prices);
            $ret['option_price'] = $option_price;
        }
        $ret['adjustment_price']       = $modal_adjustment_price;
        $ret['etc_card']               = $modal_etc_card;
        $ret['pay_method']             = $modal_pay_method;
        $ret['pay_status']             = $modal_pay_status;
        $ret['price_type']             = $price_type;
        if(!empty($request->input('modal_extend_day')))
            $ret['extend_day']           = $request->input('modal_extend_day');
        if(!empty($request->input('modal_extend_return_date')))
            $ret['extend_return_date']   = $request->input('modal_extend_return_date');
        if(!empty($request->input('modal_extend_insurance1')))
            $ret['extend_insurance1']    = $request->input('modal_extend_insurance1');
        if(!empty($request->input('modal_extend_insurance2')))
            $ret['extend_insurance2']    = $request->input('modal_extend_insurance2');
        if(!empty($request->input('modal_extend_basic_price')))
            $ret['extend_basic_price']   = $request->input('modal_extend_basic_price');
        $extend_payment = 0 ;
        if(!empty($request->input('modal_extend_payment'))) {
            $ret['extend_payment'] = $request->input('modal_extend_payment');
            $extend_payment = $request->input('modal_extend_payment');
        }
        if($extend_payment > 0) {
            $book = \DB::table('bookings')->where('id', $book_id)->first();
            $inventory_id = $book->inventory_id;
            $start_date = $return_date." ".$return_time;
            $start_date = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($start_date)));
            $extend_return_date =  $request->input('modal_extend_return_date');
            $check_book = ServerPath::getconfirmBookingExtend($inventory_id, $start_date, $extend_return_date, '');
            $check_inspection = ServerPath::getConfirmInspection($inventory_id, $start_date , $extend_return_date, '', '');
            if($check_book == true && $check_inspection == true) {
            }else{
                return back()->with('error', trans('booking.duplicatebooking'));
            }
        }
        $extend_options_price = 0;
        if(!empty($request->input('modal_extend_option_price'))) {
            $extend_options_price = $request->input('modal_extend_option_price');
            $ret['extend_option_price'] = $extend_options_price;
            $ret['extend_options_price'] = $extend_options_price;
            $extend_options_number = 0;
            if ($extend_options_price > 0) {
                $extend_options_number = 1;
            }
            $ret['extend_options_number'] = $extend_options_number;
        }

        $total  = $insurance1+$insurance2+$option_price+$modal_adjustment_price+$modal_etc_card  + $extend_payment;
        $ret['total_price']  = $total;

        if(!empty($request->input('modal_option_id_snow')))
            $ret['extend_options_id'] = $request->input('modal_option_id_snow');
        if($modal_pay_method > 0)
           $ret['paid_date'] = date('Y-m-d');
        $inserprice = \DB::table('bookings_price')->insert($ret);

        if(!empty($request->input('modal_extend_return_date'))&& !empty($request->input('modal_extend_payment'))) {
            $extend_date = $request->input('modal_extend_return_date');
            $prefix_date = date('Y-m-d',strtotime($extend_date));
            $after_date = $return_time;
            $extend_date = date('Y-m-d H:i',strtotime($prefix_date.' '.$after_date));
            //if($request->input('modal_extend_day') == '0') $extend_date = '0000-01-01 00:00:00';
            if($request->input('modal_extend_day') != '0') {
                $updatebooking = \DB::table('bookings')->where('id', $book_id)->update(['returning_updated' => $extend_date]);
            }
        }
        return redirect('/booking/edit/'.$book_id);
    }

    /*** Show the form for editing the specified resource.*
     * @param  int  book_id
     * @return \Illuminate\Http\Response */
    public function edit($book_id)
    {

        $admin = Auth::user();
        if(!Auth::check() || !$admin->isAdmin())
            return Redirect::back();
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $booking_statuses = \DB::table('booking_status')->get();
        $booking        = \DB::table('bookings as b')
            ->leftjoin('users as u','b.client_id','=','u.id')
            ->leftjoin('profiles as p' ,'b.client_id','=','p.user_id')
            ->leftjoin('car_inventory as ci','ci.id','=','b.inventory_id')
            ->leftjoin('booking_status as bs','b.status','=','bs.status')
            ->leftjoin('car_class as cc','cc.id','=','b.class_id')
            ->leftjoin('car_model as cm','cm.id','=','b.model_id')
            ->leftjoin('car_type as ct','cm.type_id','=','ct.id')
            ->leftjoin('car_shop as csp','csp.id','=','b.pickup_id')
            ->leftjoin('car_shop as csd','csd.id','=','b.dropoff_id')
            ->leftjoin('credit_card as cr','b.pay_method','=','cr.id')
            ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
            ->leftjoin('flight_lines as fl','b.flight_line','=','fl.id')
            ->leftjoin('reservations as r','b.reservation_id','=','r.id')
            ->leftjoin('users as ad', 'ad.id','=','b.admin_id')
            ->select(['b.*','ci.model_id','bs.name as booking_status','u.first_name','u.last_name','p.fur_first_name','p.fur_last_name',
                'p.phone','u.email', 'ci.numberplate1 as car_number1','ci.numberplate2 as car_number2','ci.numberplate3 as car_number3',
                'ci.numberplate4 as car_number4','cc.id as class_id','cc.name as class_name','cm.id as model_id','csp.slug as shop_slug',
                'cm.type_id', 'ct.name as type_name','p.postal_code','p.prefecture','p.address1','p.address2','csp.shop_number',
                'cm.name as model_name','ci.smoke','cr.name as card_name','csp.id as pickup_id','csp.name as pickup_name',
                'csd.id as drop_id','csd.name as drop_name','ps.name as portal_name','fl.title as flight_name','ci.current_mileage as before_miles',
                'ad.last_name as admin_last_name','ad.first_name as admin_first_name','r.title as reservation_method',
                'cr.name as paymethod' ])
            ->where('b.id',$book_id)
            ->first();

        if($booking) {
            $portal_flag    = $booking->portal_flag;
            $portal_inform  = json_decode($booking->portal_info);
            if(empty($booking->before_miles)) $booking->before_miles = '0';
            if($booking->previous_miles > 0 ) $booking->before_miles = $booking->previous_miles;
            $status = $booking->status;
            $status_name = $this->getStatusName($status);
            $booking->status_name = $status_name;
            $class_id = $booking->class_id;
            $model_id = $booking->model_id;
            if(empty($booking->client_id)) $booking->client_id = 0;
            $paid_options       = explode("," ,$booking->paid_options);
            $paid_options_price = explode("," ,$booking->paid_options_price);
            $paid_options_number= explode("," ,$booking->paid_option_numbers);
            $free_options       = explode("," ,$booking->free_options);
            //get days for rental
            $depart_date    = date('Y-m-d', strtotime($booking->departing));
            $depart_time    = date('H:i', strtotime($booking->departing));
            $depart_time    = ServerPath::changeTime($depart_time);
            $return_date    = date('Y-m-d', strtotime($booking->returning));
            $return_time    = date('H:i', strtotime($booking->returning));
            $return_time    = ServerPath::changeTime($return_time);

            $booking->depart_date = $depart_date;
            $booking->depart_time = $depart_time;
            $booking->return_date = $return_date;
            $booking->return_time = $return_time;
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night  = $request_days['night'];
            $day    = $request_days['day'];
            $booking->night = $night . '泊';
            $booking->day = $day . '日';
            $booking->set_day = $day;
            $booking->rent_days = $night."_".$day;


            if(strtotime($booking->returning_updated) > strtotime('1970-01-01 00:00:00')) {
                $return_date = date('Y-m-d',strtotime($booking->returning_updated));
                $extend_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
                $extend_night  = $extend_days['night'];
                $extend_day    = $extend_days['day'];
                $booking->extend_days = $extend_night."_".$extend_day;
                $booking->extend_set_day = $extend_day;
            }else {
                $booking->extend_days = "0_0";
                $booking->extend_set_day = 0;
            }
            $select_return_date = $booking->returning;
            if(strtotime($booking->returning_updated) > strtotime('1970-01-01 00:00:00')) {
                $select_return_date = $booking->returning_updated;
            }
            $next_statuses = array("1","2","3","4","5","6","7"); //except 8=end, 9 = cancel, 10= igonored
            $next_booking = \DB::table('bookings')
                        //->where('class_id',$class_id)
                        ->whereIn('status',$next_statuses)
                        ->where('inventory_id',$booking->inventory_id)
                        ->wheredate('departing', '>' ,$select_return_date)
                        ->orderby('departing','asc')
                        ->first();
            $inspections = \DB::table('car_inspections')
                ->where('inventory_id', $booking->inventory_id)
                ->where('delete_flag','=', 0)
                ->where('status','<', 3)
                ->wheredate('begin_date', '>' ,$select_return_date)
                ->orderBy('begin_date','asc')->first();

            if(!empty($next_booking) || !empty($inspections)) {
                $second_day = 0 ;
                if(!empty($next_booking) && !empty($inspections)) {
                    $next_booking_date = strtotime(date('Y-m-d', strtotime($next_booking->departing)));
                    $inspection_date = strtotime(date('Y-m-d', strtotime($inspections->begin_date)));
                    if ($next_booking_date > $inspection_date)
                        $second_day = $inspection_date;
                    else
                        $second_day = $next_booking_date;
                }else {
                    if (!empty($next_booking)) {
                        $next_booking_date = strtotime(date('Y-m-d', strtotime($next_booking->departing)));
                        if (!empty($inspections)) {
                            $inspection_date = strtotime(date('Y-m-d', strtotime($inspections->begin_date)));
                            if ($next_booking_date > $inspection_date)
                                $second_day = $inspection_date;
                            else
                                $second_day = $next_booking_date;
                        } else {
                            $second_day = $next_booking_date;
                        }
                    }
                    if (!empty($inspections)) {
                        $inspection_date = strtotime(date("Y-m-d", strtotime($inspections->begin_date)));
                        if (!empty($next_booking)) {
                            $next_booking_date = strtotime(date('Y-m-d', strtotime($next_booking->departing)));
                            if ($next_booking_date > $inspection_date)
                                $second_day = $inspection_date;
                            else
                                $second_day = $next_booking_date;
                        } else {
                            $second_day = $inspection_date;
                        }
                    }
                }
                $first_day  = strtotime($booking->returning);
               // $second_day =  strtotime($next_booking->departing);
                $diff = ceil(($second_day-$first_day)/(60*60*24));
                if($diff > 15) $diff = 11;
                //echo date('Y-m-d H:i:s', $first_day).":::".date('Y-m-d  H:i:s', $second_day).":::".$diff; return;
                $booking->extended_day = ($diff-1);
            }else {
                $booking->extended_day = 10;
            }

            $f_options = \DB::table('car_option')
                ->where('type', 1)
                ->select(['id as option_id', 'name as option_name', 'price as option_price'])
                ->get();
            $booking->free_options = $f_options;
            $booking->free_option_ids = $free_options;

            $cartype = \DB::table('car_type as ct')
                ->join('car_model as cm','cm.type_id','=','ct.id')
                ->where('cm.id', $model_id)
                ->select(['ct.id as type_id','ct.name as type_name'])
                ->first();
            if($cartype) {
                $booking->type_name  = $cartype->type_name;
            }else {
                $booking->type_name  = "";
            }

            $booking->admin_id = $admin->id;
            $booking->adminName = $admin->last_name . $admin->first_name;
            $adminID = $booking->admin_id;
            $user = \DB::table('users')->where('id', '=', $adminID)->first();
            $booking->admin_name = $user->last_name ." ". $user->first_name;

            $booking->created_at = ($booking->created_at == null) ? '' : date('m/d/Y', strtotime($booking->created_at));
            $booking->updated_at = ($booking->updated_at == null) ? '' : date('m/d/Y', strtotime($booking->updated_at));
            $booking->canceled_at = ($booking->canceled_at == null) ? '' : date('m/d/Y', strtotime($booking->canceled_at));

            $flight_lines   = \DB::table('flight_lines')->orderby('order', 'asc')->get();
            $paymethods     = \DB::table('credit_card')->orderby('id', 'asc')->get();
            $reservations   = \DB::table('reservations')->orderby('order', 'asc')->get();
            $shops          = \DB::table('car_shop')->orderby('id', 'asc')->get();

            $carclasses     = \DB::table('car_inventory as ci')
                ->leftjoin('car_model as cm','cm.id','=','ci.model_id')
                ->leftjoin('car_class_model as ccm','ccm.model_id','=','ci.model_id')
                ->leftjoin('car_type as ct','ct.id','=','cm.type_id')
                ->leftjoin('car_class as cc','cc.id','=','ccm.class_id')
                ->select(['ci.id','ci.numberplate1','ci.numberplate2','ci.numberplate3','ci.numberplate4','cm.id as model_id','cm.name as model_name','cc.id as class_id',
                    'cc.name as class_name','ct.id as type_id','ct.name as type_name'])
                ->where('ci.status','1')
                ->where('ci.delete_flag','0')
                ->get();

            $portal_inform = json_decode($booking->portal_info);
            if(count((array)$portal_inform) == 0) {
                $booking->portal_flag = '0';
            }
            $insurance1 = $booking->insurance1;
            if(empty($insurance1)) $insurance1 = 0;
            $insurance2 = $booking->insurance2;
            if(empty($insurance2)) $insurance2 = 0;
            $booking->insurance_price = $insurance1+$insurance2;
            $carins = \DB::table('car_insurance as ci')
                ->leftjoin('car_class_insurance as cci','cci.insurance_id','=','ci.id')
                ->where('cci.class_id', $class_id)
                ->select(['ci.*','cci.price as basic_price'])
                ->get();
            if(!empty($carins)) {
                $c = 0;
                foreach ($carins as $in) {
                   if($in->search_condition == '1') $carins[$c]->price = $insurance1;
                   if($in->search_condition == '2') $carins[$c]->price = $insurance2;
                   $c++;
                }
            }

            $booking->insurances = $carins;
            $selected_option = array();
            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option as co')
                    ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                    //->whereIn('co.id', $paid_options)
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
                            $pa_op_number = $op_number;
                        }
                    }
                    if($op->max_number == '1') {
                        $option_falg_modal = $this->confirmOption($booking->id, $op->option_id);
                        $op->option_flag_modal = $option_falg_modal;
                    }else {
                        $op->option_flag_modal = false;
                    }
                    $caroptions[$m]->option_flag_modal =  $op->option_flag_modal;
                    $caroptions[$m]->option_basic_price  = $op->option_price;
                    $caroptions[$m]->option_price  = $pa_op_price;
                    $caroptions[$m]->option_number = $pa_op_number;
                    if($pa_op_price != 0) $selected_option[] = $caroptions[$m];
                    $all_option_price +=$pa_op_price;
                    $m++;
                }

                $booking->options = $caroptions;
                $booking->option_price = $all_option_price;
            }else {
                $booking->options = array();
            }
            //echo json_encode( $booking->options) ;return;
            if(empty($booking->discount)) $booking->discount = 0;
            if(empty($booking->given_points)) $booking->given_points = 0;
            //poratal settig
            if(count((array)$portal_inform) == 0) {
                $booking->portal_flag = 0;
            }

            //get additional/extend price
            $additional_prices = \DB::table('bookings_price')->where('type','add')->where('book_id', $booking->id)->orderby('created_at','asc')->get();
            $additional_flag = false;
            if($booking->pay_status == '1') $additional_flag = true;
            if($booking->pay_status == '0') $additional_flag = false;
            $saved_additional   = array();
            $cu_additional      = array();
            $insurance1_add_flag = true;
            $insurance1_add_flag_modal = true;
            if($booking->insurance1 != 0){
                $insurance1_add_flag = false;
                $insurance1_add_flag_modal = false;
            }
            $insurance2_add_flag = true;
            $insurance2_add_flag_modal = true;
            if($booking->insurance2 != 0) {
                $insurance2_add_flag = false;
                $insurance2_add_flag_modal = false;
            }
            $extend_add_flag = true;
            $extend_add_flag_modal = true;
            if($booking->extend_payment != 0) {
                $extend_add_flag = false;
                $extend_add_flag_modal = false;
            }

            foreach($additional_prices as $ad) {
                if($ad->pay_status == '0' && $booking->pay_status == '1') {
                    $additional_flag = false;
                }
                $ad_insurance1 = $ad->insurance1;
                $ad_insurance2 = $ad->insurance2;
                $ad_insurance  = $ad_insurance1 + $ad_insurance2;
                $ad->insurance_price = $ad_insurance;
                //check previous insurance price for edit page
                $add_insurance1_previous = \DB::table('bookings_price')
                            ->where('type','add')
                            ->where('insurance1','!=','0')
                            ->where('book_id', $booking->id)
                            ->where('id','!=', $ad->id)
                            ->first();
                if(!empty($add_insurance1_previous)) $insurance1_add_flag = false;
                //check previous insurance price for modal page
                $add_insurance1_previous_modal = \DB::table('bookings_price')
                    ->where('type','add')
                    ->where('insurance1','!=','0')
                    ->where('book_id', $booking->id)
                    ->first();
                if(!empty($add_insurance1_previous_modal)) $insurance1_add_flag_modal = false;

                $add_insurance2_previous = \DB::table('bookings_price')
                    ->where('type','add')
                    ->where('insurance2','!=','0')
                    ->where('book_id', $booking->id)
                    ->where('id','!=', $ad->id)
                    ->first();
                if(!empty($add_insurance2_previous))  $insurance2_add_flag = false;
                $add_insurance2_previous_modal = \DB::table('bookings_price')
                    ->where('type','add')
                    ->where('insurance2','!=','0')
                    ->where('book_id', $booking->id)
                    ->first();
                if(!empty($add_insurance2_previous_modal))  $insurance2_add_flag_modal = false;

                //insurance
                $ad_carins = \DB::table('car_insurance as ci')
                    ->leftjoin('car_class_insurance as cci','cci.insurance_id','=','ci.id')
                    ->where('cci.class_id', $class_id)
                    ->select(['ci.*','cci.price as basic_price'])
                    ->orderby('ci.search_condition', 'asc')
                    ->get();
                if(!empty($ad_carins)) {
                    $c = 0;
                    foreach ($ad_carins as $in) {
                        if($in->search_condition == '1') {
                            $ad_carins[$c]->price = $ad_insurance1;
                            if($insurance1_add_flag == false) $ad_carins[$c]->basic_price = 0;
                        }
                        if($in->search_condition == '2') {
                            $ad_carins[$c]->price = $ad_insurance2;
                            if($insurance2_add_flag == false) $ad_carins[$c]->basic_price = 0;
                        }
                        $c++;
                    }
                }
                $ad->insurance = $ad_carins;
                //options
                $ad_option_ids      = explode(',', $ad->paid_options);
                $ad_option_number   = explode(',', $ad->paid_options_number);
                $ad_option_price    = explode(',', $ad->paid_options_price);
                $all_option_price   = 0;
                $adoptions = \DB::table('car_option as co')
                    ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
                    ->where('co.type', 0)
                    ->where('coc.class_id', $class_id)
                    ->select(['co.id as option_id', 'co.name as option_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                    ->orderby('co.order','asc')
                    ->get();
                $m = 0;
                foreach($adoptions as $op) {
                    $pa_op_price = 0;
                    $pa_op_number = 0;
                    for($i=0; $i<count($ad_option_ids); $i++){
                        if($ad_option_ids[$i] == $op->option_id) {
                            $ad_op_price  = $ad_option_price[$i];
                            if(!is_numeric($ad_op_price)) $ad_op_price = 0;
                            $ad_op_number = $ad_option_number[$i];
                            if(!is_numeric($ad_op_number)) $ad_op_number = 0;
                            $pa_op_price = $ad_op_price * $ad_op_number;
                            $pa_op_number = $ad_op_number;
                        }
                    }
                    if($op->max_number == '1') {
                        $option_flag_add = $this->confirmOptionAdd($booking->id, $op->option_id, $ad->id);
                        $op->option_flag_add = $option_flag_add;
                    }else {
                        $op->option_flag_add = false;
                    }
                    $adoptions[$m]->option_flag_add  = $op->option_flag_add;
                    $adoptions[$m]->option_basic_price  = $op->option_price;
                    $adoptions[$m]->option_price  = $pa_op_price;
                    $adoptions[$m]->option_number = $pa_op_number;
                    if($pa_op_price != 0) $selected_option[] = $adoptions[$m];
                    $all_option_price += $pa_op_price;
                    $m++;
                }
                $ad->options = $adoptions;
                $ad->option_price = $all_option_price;
                //get extend flag
                $add_extend_previous = \DB::table('bookings_price')
                    ->where('type','add')
                    ->where('extend_payment','!=','0')
                    ->where('book_id', $booking->id)
                    ->where('id','!=', $ad->id)
                    ->first();
                if(!empty($add_extend_previous)) $extend_add_flag = false;
                $add_extend_previous_modal = \DB::table('bookings_price')
                    ->where('type','add')
                    ->where('extend_payment','!=','0')
                    ->where('book_id', $booking->id)
                    ->first();
                if(!empty($add_extend_previous_modal)) $extend_add_flag_modal = false;

                if($ad->pay_status == '1') $saved_additional[] = $ad;
                if($ad->pay_status == '0') $cu_additional[] = $ad;
            }
            $booking->selected_options    =  $selected_option;
            $booking->insurance1_add_flag = $insurance1_add_flag;
            $booking->insurance2_add_flag = $insurance2_add_flag;
            $booking->insurance1_add_flag_modal = $insurance1_add_flag_modal;
            $booking->insurance2_add_flag_modal = $insurance2_add_flag_modal;
            $booking->extend_add_flag       = $extend_add_flag;
            $booking->extend_add_flag_modal = $extend_add_flag_modal;
            $booking->saved_additional  = $saved_additional;
            $booking->cu_additional     = $cu_additional;
            $booking->additional_flag   = $additional_flag;
            $booking->paid_payment      = $this->paidmount($booking->id);
            $booking->unpaid_payment    = $this->unpaidmount($booking->id);

            $extend_flag                = false;
            if($booking->extend_pay_status == '1') $extend_flag = true;
            $booking->extend_flag       = $extend_flag;
            //get all option pricee
            $booking->option_price_sum  = $this->allOptionPrice($book_id);
            $booking->insurance1_sum    = $this->allInsurance1($book_id);
            $booking->insurance2_sum    = $this->allInsurance2($book_id);
            $booking->alladjustment     = $this->alladjustment($book_id);
            $booking->allextendnight    = $this->allextendnight($book_id);


            if($booking->portal_flag == '1') {
                $booking->booking  = $portal_inform->booking;
                $booking->phone    = $portal_inform->phone;
                $booking->email    = $portal_inform->email;
                $booking->last_name        = $portal_inform->last_name;
                $booking->first_name       = $portal_inform->first_name;
                $booking->fur_last_name    = $portal_inform->fu_last_name;
                $booking->fur_first_name   = $portal_inform->fu_first_name;
                if($portal_inform->smoke == '喫煙')  $booking->smoke = '1';
                if($portal_inform->smoke == '禁煙')  $booking->smoke = '0';
                $booking->free_option_name =  $portal_inform->free_option_name;
                $booking->returning_point =  $portal_inform->returning_point;
                if(empty($portal_inform->bad_flag)) $portal_inform->bad_flag = '0';
                $booking->bad_flag         =  $portal_inform->bad_flag;// bad price
            }

            $hours          = ['09:00','09:30','10:00','10:30',
                '11:00','11:30','12:00','12:30','13:00','13:30','14:00',
                '14:30','15:00','15:30','16:00','16:30','17:00','17:30',
                '18:00','18:30','19:00','19:30'];

            $cars = $this->searchCarsHasSameCondition($book_id);
//            $booking->licences = \DB::table('bookings_driver_licences')->where('booking_id', $booking->id)->get();
            $lic_images = \DB::table('bookings_driver_licences')->where('booking_id', $booking->id)->get();
            $tmp = [];
            foreach ($lic_images as $lic) {
                if(!is_null($lic->representative_license_surface)){
                    $tmp[] = (object) array('id' => $lic->id, 'url' => $lic->representative_license_surface, 'side' => 'front');                            }
                if(!is_null($lic->representative_license_back)){
                    $tmp[] = (object) array('id' => $lic->id, 'url' => $lic->representative_license_back, 'side' => 'back');                                }
            }
            $booking->licences = $tmp;

            $data = [
                'route'         => $route,
                'subroute'      => $subroute,
                'admin'         => $admin,
                'book'          => $booking,
                'reservations'  => $reservations,
                'flight_lines'  => $flight_lines,
                'shops'         => $shops,
                'paymethods'    => $paymethods,
                'carclasses'    => $carclasses,
                'booking_statuses'=> $booking_statuses,
                'hour'          => $hours,
                'cars'          => $cars
            ];

            return View('pages.admin.booking.edit')->with($data);
        }
        else {
            return redirect('/booking/all');
        }
    }

    //confirm option previous selected.
    public function confirmOption($book_id, $option_id) {
        $flag = false;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web->paid_options)) {
            $options = explode(',' ,$web->paid_options);
            foreach($options as $op) {
                if($op == $option_id) {
                    $flag = true;
                    break;
                }
            }
        }
        if($flag == false) {
            $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
            foreach($adds as $ad){
                $options = explode(',' ,$ad->paid_options);
                foreach($options as $op) {
                    if($op == $option_id) {
                        $flag = true;
                        break;
                    }
                }
                if($flag == true) break;
            }
        }
        return $flag;
    }

    //confirm option previous selected.
    public function confirmOptionAdd($book_id, $option_id,$addition_id) {
        $flag = false;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web->paid_options)) {
            $options = explode(',' ,$web->paid_options);
            foreach($options as $op) {
                if($op == $option_id) {
                    $flag = true;
                    break;
                }
            }
        }
        if($flag == false) {
            $adds = \DB::table('bookings_price')->where('book_id',$book_id)->where('id','!=',$addition_id)->get();
            foreach($adds as $ad){
                $options = explode(',' ,$ad->paid_options);
                foreach($options as $op) {
                    if($op == $option_id) {
                        $flag = true;
                        break;
                    }
                }
                if($flag == true) break;
            }
        }
        return $flag;
    }

    //public get option price
    public function allOptionPrice($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            if(empty($web->option_price))
                $price += 0;
            else
                $price += $web->option_price;
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            $options = explode(",", $ad->paid_options_price );
            foreach($options as $op) {
                if(empty($op)) $op = 0;
                $price += $op;
            }
        }
        return $price;
    }

    //public get insurance1
    public function allInsurance1($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            $price = $web->insurance1;
        }
        if($price == 0) {
            $adds = \DB::table('bookings_price')->where('book_id', $book_id)->get();
            foreach ($adds as $ad) {
                if($price == 0)
                    $price = $ad->insurance1;
            }
        }
        return $price;
    }

    public function allInsurance2($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            $price = $web->insurance2;
        }
        if($price == 0) {
            $adds = \DB::table('bookings_price')->where('book_id', $book_id)->get();
            foreach ($adds as $ad) {
                if ($price == 0)
                    $price = $ad->insurance2;
            }
        }
        return $price;
    }

    public function alladjustment($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
                $price += $web->discount;
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            $price += $ad->adjustment_price;
        }
        return $price;
    }

    public function allextendnight($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            $price += $web->extend_payment;
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            $price += $ad->extend_payment;
        }
        return $price;
    }

    public function allextendnight_basic($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            $price += $web->extend_basic_price;
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            $price += $ad->extend_basic_price;
        }
        return $price;
    }

    public function allextendnight_extend_day($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            $price += $web->extend_day;
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            $price += $ad->extend_day;
        }
        return $price;
    }

    public function allextendnight_optionprice($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            $price += $web->extend_option_price;
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            $price += $ad->extend_option_price;
        }
        return $price;
    }

    public function allextendnight_insurance($book_id) {
        $price = 0;
        $web = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($web)) {
            $price += $web->extend_insurance1 + $web->extend_insurance2;
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            $price += $ad->extend_insurance1 + $ad->extend_insurance2;
        }
        return $price;
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
            if($web->cancel_status == '1' || $web->cancel_status == '2' || $web->cancel_status == '5'){
                $price += $web->cancel_fee;
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
            if($web->cancel_status == '10') {
                $price += $web->cancel_fee;
            }
        }
        $adds = \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($adds as $ad) {
            if($ad->pay_status == '0') $price += $ad->total_price;
        }
        return $price;
    }

    //all pay for unpaid payment
    public function unpaidpay(Request $request) {
        $book_id    = $request->input('book_id');
        $pay_method = $request->input('pay_method');
        $booking    =  \DB::table('bookings')->where('id',$book_id)->first();
        DB::beginTransaction();
        if($booking->pay_status == '0') {
            $bookingupdate = \DB::table('bookings')
                ->where('id',$book_id)
                ->update([ 'pay_status' => '1',
                           'pay_method'=> $pay_method ]);
            if(!$bookingupdate) {
                DB::rollBack();
            }
        }
        if($booking->extend_pay_status == '0') {
            $extend_bookingupdate = \DB::table('bookings')
                ->where('id',$book_id)
                ->update([ 'extend_pay_status' => '1',
                    'extend_pay_method'=> $pay_method ]);
            if(!$extend_bookingupdate) {
                DB::rollBack();
            }
        }
        $bookings_price   =  \DB::table('bookings_price')->where('book_id',$book_id)->get();
        foreach($bookings_price as $bo) {
            if($bo->pay_status == '0') {
                $id = $bo->id;
                $addition_update   =  \DB::table('bookings_price')
                        ->where('id',$id)
                        ->update([ 'pay_status' => '1',
                                   'pay_method'=> $pay_method ]);
                if(!$addition_update) {
                    DB::rollBack();
                }
            }
        }
        DB::commit();
        return Response::json(array());
    }
    //get extened price
    public function extendgetprice(Request $request) {
        $ret = array();
        $book_id    = $request->input('book_id');
        $class_id   = $request->input('class_id');
        $extend_day = $request->input('extend_day');
        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');
        $start_time = $request->input('start_time');
        $end_time   = $request->input('end_time');
        $insurance1 = 0;
        $insurance1_flag = $request->input('insurance1_flag');
        $insurance2 = 0;
        $insurance2_flag = $request->input('insurance2_flag');
        $option_snow = $request->input('option_snow');
        /*test
        $extend_day = '1';
        $book_id    = '13266';
        $start_date = '2018-03-20';
        $end_date   = '2018-03-21';
        $start_time = '09:00';
        $end_time   = '18:00';
        $class_id   = '4';
        $insurance1_flag = 'undefined';
        $insurance2_flag= '1';
        $option_snow = '0';
        */
        $bookings = \DB::table('bookings')->where('id',$book_id)->first();
        if(!empty($bookings)) {
            $car_ins = \DB::table('car_insurance as ci')
                ->leftJoin('car_class_insurance as cci', 'cci.insurance_id', '=', 'ci.id')
                ->where('cci.class_id', $bookings->class_id);
            $ins1 = clone $car_ins;
            $ins1 = $ins1->where('ci.search_condition','1')->select(['ci.*','cci.price'])->first();
            if(!empty($ins1))
                $insurance1 = $ins1->price;
            $ins2 = clone $car_ins;
            $ins2 = $ins2->where('ci.search_condition','2')->select(['ci.*','cci.price'])->first();
            if(!empty($ins2))
                $insurance2 = $ins2->price;
        }

        $ins_flag1 = false ;
        $ins_flag2 = false ;
        $option_snow_flag = false;
        if(!empty($bookings->insurance1) || $insurance1_flag > 0 ) {
            $ins_flag1 = true ;
        }
        if(!empty($bookings->insurance2) || $insurance2_flag > 0) {
            $ins_flag2 = true ;
        }
        if(!empty($bookings->paid_options)) {
            $ids = explode("," ,$bookings->paid_options);
            $numbers = explode("," , $bookings->paid_option_numbers);
            $prices = explode("," , $bookings->paid_options_price);
            $co = 0;
            foreach($ids as $id) {
                $option = \DB::table('car_option as co')->where('id',$id)->first();
                if(!empty($option)) {
                    if($option->google_column_number == '26') { //check snow tire
                        if($numbers[$co] > 0) {
                            $option_snow_flag = true;
                            $option_snow =  $option->price;
                        }
                    }
                }
                $co++;
            }
        }

        if($option_snow > 0) $option_snow_flag = true;
        $additionas = \DB::table('bookings_price')->where('book_id', $book_id)->get();
        foreach($additionas as $ad) {
            if($ins_flag1 == false) {
                if($ad->insurance1 > 0) $ins_flag1 = true;
            }
            if($ins_flag2 == false) {
                if($ad->insurance2 > 0) $ins_flag2 = true;
            }
            if($option_snow_flag == false) {
                $co = 0;
                $paid_options = explode("," , $ad->paid_options) ;
                $paid_numbers = explode("," , $ad->paid_options_number);
                $paid_prices = explode("," , $ad->paid_options_price);
                foreach($paid_options as $id) {
                    $option = \DB::table('car_option as co')->where('id',$id)->first();
                    if(!empty($option)) {
                        if($option->google_column_number == '26') { //check snow tire
                            if($paid_numbers[$co] > 0) {
                                $option_snow_flag = true;
                                $option_snow =  $option->price;
                            }
                        }
                    }
                    $co++;
                }
            }
        }


        $start_date = date('Y-m-d', strtotime('+1 day', strtotime($end_date)));
        $end_date   = date('Y-m-d', strtotime('+'.$extend_day.' day', strtotime($end_date)));
        $request_days = ServerPath::showRentDays($start_date, $start_time, $end_date, $end_time);
        $night = $request_days['night'];
        $day = $request_days['day'];
        $origin_start = $start_date;
        $origin_end = $end_date;
        //$basic_price = ServerPath::getPriceFromClass($start_date,$end_date,$class_id,$night."_".$day, $origin_start, $origin_end);
        //$basic_price = ServerPath::getOnedayNormalPriceFromClass($class_id)*$day;
        $basic_price = ServerPath::getPriceFromClassExtend($start_date,$end_date,$class_id,$night."_".$day, $origin_start, $origin_end);
        if($ins_flag1 == true)
            $insurance1 = $insurance1*$day;
        else
            $insurance1 = 0;
        if($ins_flag2 == true)
            $insurance2 = $insurance2*$day;
        else
            $insurance2 = 0;

        if($option_snow_flag == true)
            $option_snow = $option_snow*$day;
        else
            $option_snow = 0;
        $insurance = $insurance1+$insurance2;
        $sum = $basic_price+$insurance1+$insurance2 + $option_snow;
        $ret['basic_price'] = $basic_price;
        $ret['insurance1']  = $insurance1;
        $ret['insurance2']  = $insurance2;
        $ret['insurance']   = $insurance;
        $ret['option_snow'] = $option_snow;
        $ret['sum']         = $sum;
        $ret['return_date'] = $end_date;
        $ret['start_date'] = $start_date;
        return Response::json($ret);
    }

    //change returning time
    public function changereturn(Request $request) {
        $ret = array();
        $book_id     = $request->input('booking_id');
        $return_date = $request->input('returning');
        $return_time = $request->input('return_time');
        $date = date('Y-m-d', strtotime($return_date))." ".$return_time.":00";
        $booking = \DB::table('bookings')->where('id', $book_id)->first();
        if($booking->returning != $booking->returning_updated) {
            if($booking->returning_updated != '0000-01-01 00:00:00') {
                $updatebooking = \DB::table('bookings')->
                                where('id', $book_id)->update(['returning_updated' => $date]);
            }else {
                $updatebooking = \DB::table('bookings')->
                where('id', $book_id)->update(['returning' => $date]);
            }
        }else {
            $updatebooking = \DB::table('bookings')->
                                    where('id', $book_id)->update(['returning' => $date,'returning_updated' => $date]);
        }
        return Response::json($ret);
    }
    //change depatring time
    public function changedepart(Request $request) {
        $ret = array();
        $book_id     = $request->input('booking_id');
        $depart_date = $request->input('departing');
        $depart_time = $request->input('depart_time');
        $date = date('Y-m-d', strtotime($depart_date))." ".$depart_time.":00";
        $updatebooking = \DB::table('bookings')->
        where('id', $book_id)->update(['departing' => $date]);
        return Response::json($ret);
    }
    //update price status
    public function updatepricestatus(Request$request ) {
        $ret = array();
        $book_id        = $request->input('book_id');
        $pay_method     = $request->input('pay_method');
        $child_id       = $request->input('child_id');
        $cond           = $request->input('cond');
        $paid_date      = date('Y-m-d');
        if($cond == 'origin') {
            $updatebooking = \DB::table('bookings')->
            where('id', $book_id)->update(['pay_method' => $pay_method, 'pay_status' => 1,'paid_date'=>$paid_date]);
        }else {
            $updatebooking = \DB::table('bookings_price')->
            where('book_id', $book_id)->where('id', $child_id)->update(['pay_method' => $pay_method, 'pay_status' => 1,'paid_date'=>$paid_date]);
        }
        return Response::json($ret);
    }
    // get status name from status index
    public function getStatusName($status) {
        $status_name = "";
        switch($status)
        {
            case "1" :
                $status_name = '予約済'; //submit
                break;
            case "2" :
                $status_name = 'pending';
                break;
            case "3" :
                $status_name = 'confiremd';
                break;
            case "4" :
                $status_name = '支払い済'; //paid
                break;
            case "5" :
                $status_name = 'paid/check-in ';
                break;
            case "6" :
                $status_name = '使用中'; //using
                break;
            case "7" :
                $status_name = '遅れ';
                break;
            case "8" :
                $status_name = '対応終了';
                break;
            case "9" :
                $status_name = 'キャンセル';//cancel
                break;
            case "10" :
                $status_name = '連絡なし';
                break;
        }
        return $status_name;
    }

    /*** delete booking and show view-all booking. *
     * @param  int  $book_id
     * @return \Illuminate\Http\Response */
    public function delete($book_id)
    {
        \DB::table('bookings')->where('id',$book_id)->delete();

        return redirect('/booking/all');
    }


    /*** cancel booking and show view-all booking. *
     * @param  int  $book_id
     * @return \Illuminate\Http\Response */
    public function cancel($book_id)
    {
        \DB::table('bookings')
            ->where('id', $book_id)
            ->update([
                'status'        => 9,
                'canceled_at'   => date('Y-m-d')
            ]);

        return redirect('/booking/all');
    }

    /*** Update the specified resource in storage. *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response */
    public function update1(Request $request, $id)
    {

        $currentUser = Auth::user();
        $user        = User::find($id);
        $emailCheck = false;
        if(($request->input('email') != '') && ($request->input('email') === $user->email)){
            $emailCheck = true;
        }

        if ($emailCheck) {
            $validator = Validator::make($request->all(), [
                'first_name'      => 'required|max:255',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'first_name'=> 'required|max:255',
                'email'     => 'email|max:255|unique:users',
            ]);
        }

        if($request->input('password')) {
            if($request->input('password') == '' ) {
                return back()->with('error', trans('auth.passwordnull'));
            }
            $validator = Validator::make($request->all(), [
                'password'  => 'nullable|confirmed|min:6'
            ],
                [
                    'passowrd.nullable'     => trans('auth.passwordnull'),
                    'password.confirmed'     => trans('auth.passwordConfirmed'),
                    'password.min'          => trans('auth.PasswordMin')
                ]);
        }


        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            if ($emailCheck) {
                $user->email = $request->input('email');
            }
            if ($request->input('password') != null) {
                $user->password = bcrypt($request->input('password'));
            }
            $user->detachAllRoles();
            $user->attachRole($request->input('role_id'));
            $user->activated = $request->input('activated');
            $profile    = Profile::where('user_id',$id)->first();
            \DB::transaction(function() use($user,$profile,$request) {
                $profile->user_id = $user->id;
                $profile->fur_first_name = $request->input('fur_first_name');
                $profile->fur_last_name = $request->input('fur_first_name');
                $profile->sex = $request->input('sex');
                $profile->birth = date('Y-m-d', strtotime($request->input('birth')));
                $profile->category_id = $request->input('category_id');
                $profile->phone = $request->input('phone');
                $profile->emergency_phone = $request->input('emergency_phone');
                $profile->postal_code = $request->input('postal_code');
                $profile->prefecture = $request->input('prefecture');
                $profile->city = $request->input('city');
                $profile->address1 = $request->input('address1');
                $profile->address2 = $request->input('address2');
                $profile->emergency_phone = $request->input('emergency_phone');
                $profile->foreign_address = $request->input('foreign_address');
                $profile->foreign_city = $request->input('foreign_city');
                $profile->foreign_state = $request->input('foreign_state');
                $profile->foreign_country = $request->input('foreign_country');
                $profile->foreign_zip_code = $request->input('foreign_zip_code');
                $profile->company_name = $request->input('company_name');
                $profile->company_postal_code = $request->input('company_postal_code');
                $profile->company_prefecture = $request->input('company_prefecture');
                $profile->company_address1 = $request->input('company_address1');
                $profile->company_city = $request->input('company_city');
                $profile->company_address2 = $request->input('company_address2');
                $profile->credit_card_type = $request->input('credit_card_type');
                $profile->credit_card_number = $request->input('credit_card_number');
                $profile->credit_card_expiration = date('Y-m-d', strtotime($request->input('credit_card_expiration')));
                $profile->credit_card_code = $request->input('credit_card_code');

                $user->profile()->save($profile);
                $user->attachRole($request->input('role'));
                $user->save();

                //save user group
                $groups = explode(",", $request->input('groups'));

                $user_group = array();
                for ($i = 0; $i < count($groups); $i++) {
                    $group = array("user_id" => $user->id, "group_id" => $groups[$i]);
                    array_push($user_group, $group);
                }
                $groupdelete = \DB::table('users_group_tag')->where('user_id', $user->id)->delete();
                $groupinsert = \DB::table('users_group_tag')->insert($user_group);
            });
            return back()->with('success', trans('usersmanagement.updateSuccess'));
        }
    }

    //plan search page for admin
    public function searchPlans(Request $request)
    {
        $route = Route::getFacadeRoot()->current()->uri();
        $route_path = explode('/',$route);
        $subroute = $route_path[1];

        $hours = ['09:00','09:30','10:00','10:30','11:00',
            '11:30','12:00','12:30','13:00','13:30','14:00',
            '14:30','15:00','15:30','16:00','16:30','17:00',
            '17:30','18:00','18:30','19:00','19:30'];

        $shops      = Shop::all();
        $categorys  = \DB::table('car_type_category')->get();
        $passengerTags = CarPassengerTags::orderBy('show_order')->get();

        $locale = App::getLocale();
        if(Session::has('locale') == '' )
            Session::put('locale', $locale);
        config(['app.locale' =>  Session::get('locale')]);

        $depart_date    = date('Y-m-d');
        $depart_time    = $hours[0];
        $return_date    = date('Y-m-d', strtotime('tomorrow'));
        $return_time    = $hours[0];
        $category = \DB::table('car_type_category')->where('name', '乗用車')->first();
        if(!empty($category)) $car_category = $category->id;

        // get user_id of admin
        $current_admin = Auth::user();
        if($request->has('staff')) {
            $current_staff = $request->get('staff');
        } else {
            $current_staff = $current_admin->id;
        }

        $related_shop = \DB::table('admin_shop')->where('admin_id', $current_staff)->first();

        $all_admins = \DB::table('admin_shop as s')
            ->leftJoin('users as u', 's.admin_id', '=', 'u.id')
            ->leftJoin('profiles as p', 's.admin_id', '=', 'p.user_id')
            ->select(['s.*', 'u.last_name','u.first_name','p.fur_last_name','p.fur_first_name'])
            ->whereNull('u.deleted_at')
            ->orderBy('s.shop_id')
            ->get();

        $search = (object)array();
        $search->depart_date = $depart_date;
        $search->depart_time = $depart_time;
        $search->return_date = $return_date;
        $search->return_time = $return_time;
        $search->depart_shop = is_null($related_shop)? '' : $related_shop->shop_id;
        $search->depart_shop_name = '';
        $search->return_shop = is_null($related_shop)? '' : $related_shop->shop_id;
        $search->car_category= $car_category;
        $search->passenger   = 'all';
        $search->real_passenger   = '';
        $search->insurance   = '1';
        $search->smoke       = '1';
        $search->options     = '';
        $search->option_numbers = '';
        $search->pickup      = '1';
        $search->staff       = $current_staff;

        //get date diff(night_day)
        $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
        $search->rentdates = $request_days['day'];

        $flight_lines = \DB::table('flight_lines')->orderBy('order')->get();

        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'shops'     => $shops,
            'categorys' => $categorys,
            'search'    => $search,
            'hours'     => $hours,
            'psgtags'   => $passengerTags,
            'flights'   => $flight_lines,
            'staffs'    => $all_admins
        ];

        return View('pages.admin.booking.search-plans')->with($data);
    }

    // search class for admin
    public function searchClasses(Request $request)
    {
        $depart_date    = $request->input('depart_date', date('Y-m-d'));
        $depart_time    = $request->input('depart_time', '09:00');
        $return_date    = $request->input('return_date', date('Y-m-d', strtotime('tomorrow')));
        $return_time    = $request->input('return_time', '09:00');
        $depart_shop    = $request->input('depart_shop', '');
        $return_shop    = $request->input('return_shop', '');
        $car_category   = $request->input('car_category', '');
        if($car_category == '') {
            $category = \DB::table('car_type_category')->where('name', '乗用車')->first();
            if(!empty($category)) $car_category = $category->id;
        }
        $passenger      = $request->input('passenger', 'all');
        $real_passenger = $request->input('real_passenger', '');
        if($passenger == 'all') {
            if($real_passenger != '') $passenger = $real_passenger;
        } else if($real_passenger != '' && $real_passenger > $passenger)
            $passenger = $real_passenger;

        $insurance      = $request->input('insurance', '1');
        $options        = $request->input('options', []);
        $smoke          = $request->input('smoke', 'both');
        $search_condition = $request->input('search_condition', '');
        $tmp            = $request->input('option_number', []);
        // remove 0 from option numbers
        $option_numbers = [];
        foreach ($tmp as $on) {
            if(intval($on) > 0) array_push($option_numbers, intval($on));
        }

        $start  = $depart_date." ".$depart_time.':00';
        $end    = $return_date." ".$return_time.':00';

        //get date diff(night_day)
        $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
        $night = $request_days['night'];
        $day = $request_days['day'];

        // search start
        $allClasses = \DB::table('car_class')
            ->where('status', 1)
            ->where('delete_flag', 0);
        if($depart_shop !== '') {
            $allClasses = $allClasses->where('car_shop_name', $depart_shop);
        }

        $allClasses = $allClasses->orderBy('car_class_priority')->get();
//        return '1';

        $search_class = [];  // array to contain filtered classes
        foreach($allClasses as $cls){
            // check if this class has all options of search condition
            $select_options = array();
            if(!empty($options)){
                $select_options = $this->getOptionsFromClass($cls->id, $options);
            }

            if(count($select_options) < count($options)) continue;

            // get class insurance
            $cis = \DB::table('car_class_insurance')->where('class_id',$cls->id)->orderby('insurance_id','asc')->get();
            $tins = array_fill(0, 3, 0);
            foreach ($cis as $ci){
                $tins[$ci->insurance_id] = $ci->price;
            }

            // get all models in this class
            $models = \DB::table('car_class_model as cm')
                ->leftJoin('car_model as m', 'm.id', '=', 'cm.model_id' )
                ->leftJoin('car_type as ct', 'm.type_id', '=', 'ct.id')
                ->where('cm.class_id', '=', $cls->id);
            // check category
            if($car_category != '') {
                $models = $models->where('ct.category_id','=',$car_category);
            }

            $models = $models->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id');
            // check smoke
            if($smoke != '' && $smoke != 'both')
                $models = $models->where('ci.smoke', $smoke);
            // check shop
            if($depart_shop != '')
                $models = $models->where('ci.shop_id',$depart_shop);

            // check dropoff
            $cars = $models->where('ci.delete_flag', 0)
                ->where('ci.max_passenger', '>=', $passenger)
                ->where('ci.status', 1)
                ->select(['ci.*','cm.class_id', 'm.name as model_name', 'cm.model_id'])->get();

            if(empty($cars)) continue;  // if no car matching condition
            // check if car is available
            $count = 0;
            $mids = [];
            $mnames = [];
            $impossibles = [];
            $max_passengers = [];

            foreach ($cars as $car) {
                $checkBook = ServerPath::getconfirmBooking($car->id, $start, $end, '');
                $checkInspection = ServerPath::getConfirmInspection($car->id, $start, $end, '', '');
                if($checkBook && $checkInspection){

                    $impossibles[] = $car->shortname;
                    $mp = $car->max_passenger;
                    if(array_key_exists($mp, $max_passengers)){
                        $max_passengers[$mp]++;
                    } else {
                        $max_passengers[$mp] = 1;
                    }
                    $count++;
                    if(!array_search($car->model_id, $mids)){
                        array_push($mids, $car->model_id);
                        array_push($mnames, $car->model_name);
                    }
                }
            }
            if($count == 0) // if no car available
                continue;

            $class = (object)array();
            $class->thum_path   = (trim($cls->thumb_path)!= '')? trim($cls->thumb_path) : '/images/blank.jpg';
            $class->id          = $cls->id;
            $class->class_name  = $cls->name;
            $class->model_id    = $mids;
            $class->model_name  = $mnames;
            $class->depart_date = $depart_date;
            $class->depart_time = $depart_time;
            $class->return_date = $return_date;
            $class->return_time = $return_time;
            $class->message = "Test message";
            $class->car_count = $count;
            $class->max_passengers = $max_passengers;
            $class->priority = $cls->car_class_priority;
            $class->insurance1 = $tins['1'];
            $class->insurance2 = $tins['2'];
            $class->smoke = $smoke;
            $class->night_day = $night . '泊' . $day . '日';
            $class->rent_dates = $day;
            $class->impossibles = $impossibles;

            //get price
            $class_price = ServerPath::getPriceFromClass($depart_date, $return_date, $cls->id, $night . "_" . $day,$depart_date, $return_date);
            $class->base_price = $class_price;

            $class->options = $select_options;
            $option_price = 0;
            $option_prices = [];
            if (!empty($select_options)) {
                foreach ($select_options as $key => $op) {
                    if($op->charge_system == 'one') {
                        $pr = $op->price * intval($option_numbers[$key]);
                        $option_price += $pr;
                    } else {
                        $pr = $op->price * intval($option_numbers[$key]) * $day;
                        $option_price += $pr;
                    }
                    $option_prices[] = $pr;
                    $op->number = 1;
                }
            }
            $class->option_prices = implode(',', $option_prices);
            $class->option_price = $option_price;
            $class->free_options = '';
            $class->shop_id = $depart_shop;
            $class->shop_name = $this->getShopName($depart_shop);
            if(empty($car_category)) $car_category = "";
            $class->category_id = $car_category;
            $class->category_name = $this->getCategoryName($car_category);

            //get insurance
            $insurance_price = ServerPath::getInsurancePrice($insurance, $cls->id) * $day;
            $class->insurance_price = $insurance_price;
            $class->all_price = $class_price + $option_price + $insurance_price;

            //get flag about returnshop
            array_push($search_class, $class);
        }

        return $search_class;
    }

    //plan search page for admin
    private function searchCarsHasSameCondition($book_id)
    {
        $result = [];
        $shops      = Shop::all();
        $categorys  = \DB::table('car_type_category')->get();
        $passengerTags = CarPassengerTags::all();
        $book = \DB::table('bookings')->where('id', '=', $book_id)->first();

        $car_id = $book->inventory_id;
        $car = CarInventory::find($car_id);
        //end
        $passengers     = $book->passengers;
        // find model of car and get capacity of model
        $departing      = $book->departing;
        $returning      = $book->returning;
        $depart_shop    = $book->pickup_id;
        $return_shop    = $book->dropoff_id;
        $class_id       = $book->class_id;

        $category = \DB::table('car_type_category')->where('name', '乗用車')->first();
        if(!is_null($category)) $car_category = $category->id;

        $cars = \DB::table('car_class_model as cm')
            ->join('car_class as c', 'c.id', '=', 'cm.class_id')
            ->leftJoin('car_model as m', 'cm.model_id', '=', 'm.id')
            ->leftJoin('car_type as t', 't.id', '=', 'm.type_id')
            ->leftJoin('car_inventory as i', 'm.id', '=', 'i.model_id');
        if($return_shop != $depart_shop){
            $cars = $cars->leftJoin('car_inventory_dropoff as d', 'i.id', '=', 'd.inventory_id')
                ->where('d.shop_id','=',$return_shop);
        };

        $cars = $cars->select(['t.name as type_name', 'i.smoke', 'i.numberplate1', 'i.numberplate2', 'i.numberplate3', 'i.numberplate4', 'c.name as class_name', 'm.name as model_name', 'cm.class_id', 'cm.model_id', 'm.type_id', 'i.id as car_id'])
            ->where('cm.class_id', '=', $class_id)
            ->where('i.shop_id', $depart_shop)
            ->where('i.delete_flag', '=', 0)
            ->where('i.max_passenger', '>=', $passengers)
            ->where('i.status', '=', 1);

            if(!empty($car_category)) {
             //$cars = $cars ->where('t.category_id', '=', $car_category);
            }
        $cars = $cars->orderBy('i.priority','asc')
            ->get();


        if(count($cars) == 0) return [];  // if no car matching condition

        // check if car is available

        foreach ($cars as $car) {
            $car->smoke = ($car->smoke == 0)? '禁煙' : '喫煙';
            if($car_id == $car->car_id) {
                array_push($result, $car);
                continue;
            }
            $checkBook = ServerPath::getconfirmBooking($car->car_id, $departing, $returning, '');
            $checkInspection = ServerPath::getConfirmInspection($car->car_id, $departing, $returning,'', '');
            if($checkBook && $checkInspection){
                array_push($result, $car);
            }
        }

        return $result;
    }

    public function getUserList() {
        $users = \DB::table('users as u')
            ->join('profiles as p', 'u.id', '=', 'p.user_id')
            ->join('role_user as r', 'u.id', '=', 'r.user_id')
            ->select('u.id','u.email','p.phone','u.first_name','u.last_name','p.fur_first_name','p.fur_last_name')
            ->where('r.role_id', '=', 2)
            ->get();
        if(empty($users))
            return array('id'=>0,'email'=>'','phone'=>'','first_name'=>'','last_name'=>'','fur_first_name'=>'','fur_last_name'=>'');
        else
            return $users;
    }

    // search user
    public function searchUser(Request $request) {
//        dd($_POST);
        $last_name = $request->get('last_name');
        $first_name = $request->get('first_name');
        $furi_last_name = $request->get('furi_last_name');
        $furi_first_name = $request->get('furi_first_name');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $users = \DB::table('users as u')
            ->join('profiles as p', 'u.id', '=', 'p.user_id');
        if($last_name !== '') {
            $users = $users->where('u.last_name','like', '%'.$last_name.'%');
        }
        if($first_name !== '') {
            $users = $users->where('u.first_name','like', '%'.$first_name.'%');
        }
        if($furi_last_name !== '') {
            $users = $users->where('p.fur_last_name','like', '%'.$furi_last_name.'%');
        }
        if($furi_first_name !== '') {
            $users = $users->where('p.fur_first_name','like', '%'.$furi_first_name.'%');
        }
        if($phone !== '') {
            $users = $users->where('p.phone','like', '%'.$phone.'%');
        }
        if($email !== '') {
            $users = $users->where('u.email','like', '%'.$email.'%');
        }
        $users = $users->select('u.id','u.email','p.phone','u.first_name','u.last_name','p.fur_first_name','p.fur_last_name')
            ->where('activated', 1)
            ->get();

        if(empty($users))
            return array('id'=>0,'email'=>'','phone'=>'','first_name'=>'','last_name'=>'','fur_first_name'=>'','fur_last_name'=>'');
        else
            return $users;
    }

    // save booking from search_confirm
    public function searchSave(Request $request) {

        $admin = Auth::user();
        if(!Auth::check() || !$admin->isAdmin())
            return Redirect::back();

        $staff = $request->get('data_staff');
        // find user
        $uid = $request->get('data_member');
        $user_email = $request->input('email');
        $user_phone = $request->input('phone');
        $user_first_name = $request->input('first_name');
        $user_last_name = $request->input('last_name');
        $email_inputed = $user_email != '';
        $new_user = $uid == '';
        if($new_user) // new user
        {
            if( $email_inputed ) {
                $validator = Validator::make($request->all(),
                    [
                        'email' => 'required|email|max:255|unique:users',
//                    'name'  => 'required|unique:users'
                    ],
                    [
                        'email.required'    => 'email address is required',
                        'email.email'       => 'wrong email address',
                        'email.unique'      => 'already registered email address',
//                    'name.unique'       => 'Your name already used'
                    ]
                );

                if ($validator->fails()) {
//                return back()->withErrors($validator)->withInput();
                    $error = array('success'=>false, 'errors'=>$validator->errors()->all());
                    return $error;
                }
            }
            $ipAddress  = new CaptureIpTrait;
            $profile    = new Profile;
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $password = substr( str_shuffle( $chars ), 0, 8 );

            $user =  User::create([
//                'name'              => $request->input('last_name').$request->input('first_name'),
                'first_name'        => $user_first_name,
                'last_name'         => $user_last_name,
                'email'             => $user_email,
                'password'          => bcrypt($password),
                'token'             => str_random(64),
                'admin_ip_address'  => $ipAddress->getClientIp(),
                'activated'         => 1
            ]);

            $profile->fur_first_name = $request->input('furi_first_name');
            $profile->fur_last_name = $request->input('furi_last_name');
            $profile->phone = $user_phone;

            $user->profile()->save($profile);
            $user->attachRole(2); //
            $user->save();

            $user_id = $user->id;
            $user_name = str_pad($user_id, 6, '0', STR_PAD_LEFT);
            $user->update(array('name'=> $user_name));
        }
        else    // registered user
        {
            $user = User::with('profile')->findOrFail($uid);

            if(is_null($user)){
                return array('success'=>false, 'errors'=>['Can not find user']);
//            return redirect('/search-confirm')
//                ->with('no_user_error', 'There is no user with this email and phone number.')
//                ->withInput();
            }
            $user_id = $user->id;
        }

        $user = User::with('profile')->findOrFail($user_id);
//        $user_id = $user->id;
//        $profile = $user->profile();
        $user_phone = $user->profile->phone;
        $user_name = $user->last_name.$user->first_name;
        if($user_name == '')
            $user_name = $user->profile->fur_last_name.$user->profile->fur_first_name;
        $user_email = $user->email;

        // create booking
        $today = date('ymd'); // 2018-03-22 => 180322
//        $todayLastBook = \DB::select('SELECT * FROM bookings WHERE DATE(created_at) = DATE(NOW()) ORDER BY id DESC limit 0,1 ');
        $todayLastBook = \DB::table('bookings')->whereRaw('DATE(created_at) = DATE(NOW())')->orderBy('id', 'desc')->first();
//            var_dump($todayLastBook);
        if($todayLastBook == null){
            $booking_id = $today.'-0001';
        } else {
            $split = explode('-', $todayLastBook->booking_id);
            $booking_id = $today.'-'.str_pad(($split[1] + 1), 4, '0', STR_PAD_LEFT);
        }

        $depart_datetime = $request->input('data_depart_date').' '.$request->input('data_depart_time').':00';
        $return_datetime = $request->input('data_return_date').' '.$request->input('data_return_time').':00';
        $rentdays = $request->input('data_rent_days'); // 1泊 1日
        if($rentdays === ''){
            $rentdays = '0_0';
        } else {
            $rentdays = str_replace(['泊','日'], ['_',''], $rentdays);
        }
        $rentcost = $request->input('data_price_rent');

        $oids = $request->input('data_option_ids');
        $onums = $request->input('data_option_numbers');
        $ocosts = $request->input('data_option_costs');
        $oprices = $request->input('data_option_prices');
        $arr_onums = explode(',', $onums);
        $arr_oids = explode(',', $oids);
        $arr_oprices = explode(',', $oprices);

        $option_cost = 0;
        $tmp_num = []; $tmp_id = []; $tmp_price = [];
        foreach ($arr_oprices as $key => $pr){
//                $option_cost += ServerPath::getOptionPriceFromNumberDates($opt_ids[$key], $num, $rent_dates);
            $option_cost += intval($pr);
            if(intval($pr) !== 0) {
                $tmp_num[] = $arr_onums[$key];
                $tmp_id[] = $arr_oids[$key];
                $tmp_price[] = $arr_oprices[$key]/$arr_onums[$key];
            }
        }

        $sub_total = $rentcost + $option_cost;
        $tax = $sub_total * 0.08;

        //pickup satus
        $option_indexs = explode(',',$request->input('data_option_indexs'));
        $wait_status_flag = false;
        $wait_status = 0;
        if(in_array('38', $option_indexs)) { //if selected smart driveoption
            $wait_status_flag = true;
            $wait_status = '3';
        }

        // find car in car inventory
        // get cars from car_inventory with model ids
        $passengers = $request->input('data_passenger');
        $real_passengers = $request->input('data_real_passengers');
        if($real_passengers != '') {
            $passengers = $real_passengers;
        }

        $passenger_number = $request->input('data_passenger_number');

        $cars = \DB::table('car_class_model as c')
            ->leftjoin('car_model as m', 'm.id', '=', 'c.model_id')
            ->leftJoin('car_inventory as i', 'c.model_id','=','i.model_id')
            ->where('i.delete_flag', 0)
            ->where('i.status', 1)
            ->where('i.max_passenger', $passenger_number)
//            ->where('i.max_passenger', '>=', $passengers)
            ->where('c.class_id', $request->input('data_class_id') )
            ->where('i.shop_id', $request->input('data_depart_shop') );
        $smoke = $request->input('data_smoke');
        if($smoke != 'both'){
            $cars = $cars->where('i.smoke', $smoke);
        }
        $cars = $cars->select(['i.id AS car_id', 'i.model_id', 'i.priority AS car_priority', 'c.priority AS model_priority']);
        if($smoke == 'both'){
            $cars = $cars->orderBy('i.smoke', 'desc');
        }

        $cars = $cars->orderby('c.priority')->orderby('i.priority')->get();

        // check bookings for checking if car is available
        $inventory_id = 0;
        $model_id = 0;
        foreach ($cars as $car){
            // status departing returning inventory_id class_id model_id : bookings table
            $cid =$car->car_id;
            if($cid == null || $cid == '') continue;
            $checkBook = ServerPath::getconfirmBooking($cid, $depart_datetime, $return_datetime, '');
            $checkInspection = ServerPath::getConfirmInspection($cid, $depart_datetime, $return_datetime,'', '');
            if($checkBook && $checkInspection){
                $inventory_id = $cid;
                $model_id = $car->model_id;
                break;
            }
        }
        $price_all = intval($request->input('data_price_all'));
        $price_insurance1 = intval($request->input('data_insurance_price1'));
        $price_insurance2 = intval($request->input('data_insurance_price2'));
        $insurance = intval($request->input('data_insurance'));
        $rent_dates = intval($request->input('data_rendates'));

        switch($insurance) {
            case 0 : $price_insurance = 0;
                    $price_ins1 = 0; $price_ins2 = 0; break;
            case 1 : $price_insurance = $price_insurance1 * $rent_dates;
                    $price_ins1 = $price_insurance1;
                    $price_ins2 = 0; break;
            case 2 : $price_insurance = ($price_insurance1 + $price_insurance2) * $rent_dates;
                    $price_ins1 = $price_insurance1;
                    $price_ins2 = $price_insurance2; break;
        }

        $passengers = $request->input('data_passenger');
        if($passengers == 'all') $passengers = 0;
        if($request->input('data_real_passengers') != '') {
            $passengers = $request->input('data_real_passengers');
        }

        //pickup status
        if($wait_status_flag == false) {
            if($request->input('data_pickup') == '1') {
                $wait_status = '1' ;
            }
        }

        //
        $pickup = $request->input('data_pickup');
        $car_option = \DB::table('car_option')->where('id', $pickup)->first();
        $free_options_category = '0';
        if(!empty($car_option)) {
            if($car_option->google_column_number == '101') $free_options_category = '1';
            if($car_option->google_column_number == '102') $free_options_category = '2';
        }

        $depart_shop_id = $request->input('data_depart_shop');
        $return_shop_id = $request->input('data_return_shop');
        $class_id = $request->input('data_class_id');
        $total_price = $rentcost + $option_cost + $price_insurance;
        $request_smoke = 0;
        if($smoke == 'both') $request_smoke = '2';
        else $request_smoke = $smoke;
        if($inventory_id != 0 && $model_id != 0) {
            $insertbooking = \DB::table('bookings')->insertGetId(
                [
                    'admin_id'      => $staff, //   $admin->id,
                    'booking_id'    => $booking_id,
                    'status'        => 1,   // $booking_status->status,
                    'client_id'     => $user_id,
                    'emergency_phone' => $request->input('data_emergency_phone'),
                    'pickup_id'     => $depart_shop_id,
                    'dropoff_id'    => $return_shop_id,
                    'passengers'    => ($real_passengers != '' && $real_passengers < $passenger_number)? $real_passengers : $passenger_number,
                    'request_smoke' => $request_smoke,
                    'inventory_id'  => $inventory_id,
                    'class_id'      => $class_id,
                    'model_id'      => $model_id,
                    'paid_options'  => implode(',', $tmp_id), //$oids,
                    'paid_option_numbers'  => implode(',', $tmp_num),//$onums,
                    'paid_options_price'  => implode(',', $tmp_price),//$oprices, // $ocosts,
                    'option_price'  => $option_cost,
                    'departing'     => $depart_datetime,
                    'returning'     => $return_datetime,
                    'returning_updated' => $return_datetime,
                    'rent_days'     => $rentdays,
                    'reservation_id'=> 1,   // via homepage
                    'subtotal'      => $rentcost,
                    'basic_price'   => $rentcost,
                    'tax'           => $tax,
                    'payment'       => $total_price,
                    'insurance1'    => $price_ins1 * $rent_dates,
                    'insurance2'    => $price_ins2 * $rent_dates,
                    'flight_line'   => $request->input('data_flight_line'),
                    'flight_number' => $request->input('data_flight_number'),
                    'admin_memo'    => $request->input('data_memo'),
                    'free_options'  => $pickup,
                    'free_options_category' => $free_options_category,
                    'wait_status'   => $wait_status,
                    'portal_flag'   => 0,
                    'portal_id'     => 10001
                ]
            );

            $lang = ServerPath::lang();

            $book = \DB::table('bookings')->where('id', $insertbooking)->first();
            $booking_submit_time = ($lang == 'ja')? date('Y年m月d日 H時i分', strtotime($book->created_at)) : date('Y/m/d H:i', strtotime($book->created_at));

            //Don't delete
            // data for notification
            $depart_shop = Shop::findOrFail($depart_shop_id);
            $car_model = CarModel::findOrFail($model_id);
            $insurance = $request->input('data_insurance');

            if($insurance == '0') {
                $insurance_part = '';
                $insurance_type_ja = 'なし';
            } elseif($insurance == '1') {
                $insurance_part = '免責補償：'.($price_ins1 * $rent_dates).'円<br>';
                $insurance_type_ja = '免責補償';
            } else {
                $insurance_part = '免責補償：'.($price_ins1 * $rent_dates).'円<br>ワイド免責補償：'.($price_ins2 * $rent_dates).'円<br>';
                $insurance_type_ja = '免責補償/ワイド免責補償';
            }

            if($lang == 'ja') {
                if($insurance == '0') {
                    $insurance_part = '';
                    $insurance_type = 'なし';
                } elseif($insurance == '1') {
                    $insurance_part = '免責補償：'.($price_ins1 * $rent_dates).'円<br>';
                    $insurance_type = '免責補償';
                } else {
                    $insurance_part = '免責補償：'.($price_ins1 * $rent_dates).'円<br>ワイド免責補償：'.($price_ins2 * $rent_dates).'円<br>';
                    $insurance_type = '免責補償/ワイド免責補償';
                }
            } else {
                if($insurance == '0') {
                    $insurance_part = '';
                    $insurance_type = 'none';
                } elseif($insurance == '1') {
                    $insurance_part = 'Exemption of Liability Compensation：'.($price_ins1 * $rent_dates).'yen<br>';
                    $insurance_type = 'Exemption of Liability Compensation';
                } else {
                    $insurance_part = 'Exemption of Liability Compensation：'.($price_ins1 * $rent_dates).'yen<br>Wide Protection Package：'.($price_ins2 * $rent_dates).'yen<br>';
                    $insurance_type = 'Exemption of Liability Compensation/Wide Protection Package';
                }
            }
            $car = CarInventory::find($book->inventory_id);
            if($car->smoke == 1) {
                $smoke = ($lang == 'ja')? '喫煙' : 'smoking';
            } else {
                $smoke = ($lang == 'ja')? '禁煙' : 'non-smoking';
            }
            $class = \DB::table('car_class as c')
                ->leftJoin('car_class_passenger as p', 'c.id', '=', 'p.class_id')
                ->leftJoin('car_passenger_tags as t', 'p.passenger_tag', '=', 't.id')
                ->select(['c.*','t.name as tagname','p.passenger_tag'])
                ->where('c.id','=', $class_id)
                ->orderBy('t.show_order', 'desc')->first();
            $option_ids = explode(',', $oids);
            $option_numbers = explode(',', $onums);
            $option_names = []; $option_names_ja = [];
            $option_prices = [];
            if(count($option_ids) > 0) {
                $options = \DB::table('car_option');
                foreach ($option_ids as $key=>$oid) {
                    if ($key == 0)
                        $options = $options->where('id', $oid);
                    else
                        $options = $options->orWhere('id', $oid);
                }
                $options = $options->get();
                if(!empty($options)) {
                    foreach ($options as $key=>$option) {
                        $vid = array_search($option->id, $option_ids);
                        $opt_num = $option_numbers[$vid];
                        array_push($option_names_ja, $option->name.'('.$opt_num.')');
                        if($lang == 'ja') {
                            array_push($option_names, $option->name.'('.$opt_num.')'); // チャイルドシート(1)
                            if($option->charge_system == 'one') {
                                array_push($option_prices, $option->name.' '.$option->price.'円'.' x '.$opt_num.'個'); // childseat 540円 x 1個
                            } else {
                                array_push($option_prices, $option->name.' '.$option->price.'円'.' x '. $rent_dates.'日');
                            }
                        } else {
                            array_push($option_names, $option->name_en.'('.$opt_num.')'); // チャイルドシート(1)
                            if($option->charge_system == 'one') {
                                array_push($option_prices, $option->name_en.' '.$option->price.'yen'.' x '.$opt_num); // childseat 540円 x 1個
                            } else {
                                array_push($option_prices, $option->name_en.' '.$option->price.'yen'.' x '. $rent_dates.' days');
                            }
                        }
                    }
                }
            }

            if( $pickup != '' ) {
                $fop = \DB::table('car_option')->where('id', $pickup)->first();
                if($lang == 'ja') {
                    array_push($option_names, '無料'.$fop->name);
                    array_push($option_prices, '無料'.$fop->name.' 0円');
                } else {
                    array_push($option_names, 'free '.$fop->name_en);
                    array_push($option_prices, 'free'.$fop->name_en.' 0yen');
                }
                array_push($option_names_ja, '無料'.$fop->name);
            }

            $depart_dt_ja = date_format(date_create($depart_datetime),"Y年m月d日 H時i分");
            $return_dt_ja = date_format(date_create($return_datetime),'Y年m月d日 H時i分');

            if($lang == 'ja') {
                $depart_dt = date_format(date_create($depart_datetime),"Y年m月d日 H時i分");
                $return_dt = date_format(date_create($return_datetime),'Y年m月d日 H時i分');
            } else {
                $depart_dt = date_format(date_create($depart_datetime),"Y/m/d H:i");
                $return_dt = date_format(date_create($return_datetime),'Y/m/d H:i');
            }

            // send notification to registered user and admin
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            //$protocol = "https://";
            $domain = $_SERVER['HTTP_HOST'];
            $data = array();

            if( $new_user ) {
                if( $email_inputed ) {
                    if($lang == 'ja')
                        $template = \DB::table('mail_templates')->where('mailname', 'user_register_book_user')->first();
                    else
                        $template = \DB::table('mail_templates')->where('mailname', 'user_register_book_user_en')->first();
                    if(!empty($template)) {
                        $subject = $template->subject;
                        $message = $template->content;
                        $message = str_replace('{user_name}', $user_name, $message);
                        $message = str_replace('{login_url}', '<a href="'.url('/login').'" target="_blank">'.url('/login').'</a>', $message);
                        $shop_url = url('/shop/'.$depart_shop->slug);
                        $message = str_replace('{shop_url}', '<a href="'.$shop_url.'" target="_blank">'.$shop_url.'</a>', $message);
                        $message = str_replace('{user_email_address}', $user_email, $message);
                        $message = str_replace('{user_password}', $password, $message);
                        $message = str_replace('{quick_start_url}', '', $message);
                        $message = str_replace('{booking_id}', $booking_id, $message);
                        $message = str_replace('{shop_name}', ($lang=='ja')? $depart_shop->name : $depart_shop->name_en, $message);
                        $message = str_replace('{shop_phone}', $depart_shop->phone, $message);
                        $message = str_replace('{car_model_name}', $car_model->name, $message);
                        $message = str_replace('{car_short_name}', $car->shortname, $message);
                        $message = str_replace('{car_capacity}', $class->tagname, $message);
                        $message = str_replace('{smoke}', $smoke, $message);
                        $message = str_replace('{insurance_type}', $insurance_type, $message);
                        $message = str_replace('{options}', implode(', ',$option_names), $message);
                        $message = str_replace('{departing_time}', $depart_dt, $message);
                        $message = str_replace('{returning_time}', $return_dt, $message);
                        $message = str_replace('{base_price}', $rentcost, $message);
                        $message = str_replace('{insurance_part}', $insurance_part, $message);
                        $message = str_replace('{option_price}', $option_cost, $message);
                        $message = str_replace('{option_detail}', implode(', ', $option_prices), $message);
                        $message = str_replace('{total_price}', $total_price, $message);
                        $content = $message;
                        if(strpos($_SERVER['HTTP_HOST'], 'hakoren') >= 0) {
                            $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $template->sender, 'email' => 'bluexie0455@gmail.com');
                            $data[] = $data1;
                        }
                        $data1 = array('content' => $content, 'subject' => $subject, 'fromname' =>$template->sender,  'email' => $user->email);
                        $data[] = $data1;
                    }
                }
            }
            else // already registered user
            {
                if(ServerPath::lang() == 'ja')
                    $template = \DB::table('mail_templates')->where('mailname', 'member_book_confirm_user')->first();
                else
                    $template = \DB::table('mail_templates')->where('mailname', 'member_book_confirm_user_en')->first();
                if(!empty($template)) {
                    $subject = $template->subject;
                    $message = $template->content;
                    $message = str_replace('{user_name}', $user_name, $message);
                    $message = str_replace('{login_url}', '<a href="'.url('/').'/login'.'" target="_blank">'.url('/').'/login'.'</a>', $message);
                    $shop_url = url('/shop/'.$depart_shop->slug);
                    $message = str_replace('{shop_url}', '<a href="'.$shop_url.'" target="_blank">'.$shop_url.'</a>', $message);
                    $message = str_replace('{quick_start_url}', '', $message);
                    $message = str_replace('{booking_id}', $booking_id, $message);
                    $message = str_replace('{shop_name}', ($lang=='ja')? $depart_shop->name:$depart_shop->name_en, $message);
                    $message = str_replace('{car_model_name}', $car_model->name, $message);
                    $message = str_replace('{car_short_name}', $car->shortname, $message);
                    $message = str_replace('{car_capacity}', $class->tagname, $message);
                    $message = str_replace('{smoke}', $smoke, $message);
                    $message = str_replace('{insurance_type}', $insurance_type, $message);
                    $message = str_replace('{options}', implode(', ',$option_names), $message);
                    $message = str_replace('{departing_time}', $depart_dt, $message);
                    $message = str_replace('{returning_time}', $return_dt, $message);
                    $message = str_replace('{base_price}', $rentcost, $message);
                    $message = str_replace('{insurance_part}', $insurance_part, $message);
                    $message = str_replace('{option_price}', $option_cost, $message);
                    $message = str_replace('{option_detail}', implode(', ', $option_prices), $message);
                    $message = str_replace('{total_price}', $total_price, $message);
                    $content = $message;
                    if(strpos($_SERVER['HTTP_HOST'], 'hakoren') >= 0) {
                        $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $template->sender, 'email' => 'bluexie0455@gmail.com');
                        $data[] = $data1;
                    }
                    $data1 = array('content' => $content, 'subject' => $subject, 'fromname' =>$template->sender,  'email' => $user_email);
                    $data[] = $data1;
                }
                // Don't delete
            }
            $template = \DB::table('mail_templates')->where('mailname', 'user_register_book_admin')->first();
            if(!empty($template)) {
//                    【HR予約】★{new_repeat}-{shop_name}-{class_name}-{dent_days}
                $subject = $template->subject;
                $subject = str_replace('HR', 'HR AD', $subject);
                $subject = str_replace('{route}', '', $subject);
                $subject = str_replace('{new_repeat}', $new_user? '新規':'リピーター', $subject);
                $subject = str_replace('{shop_name}', $depart_shop->name, $subject);
                $subject = str_replace('{class_name}', $class->name, $subject);
                $subject = str_replace('{rent_days}', $request->input('data_rent_days'), $subject);

                $message = $template->content;
                $message = str_replace('{user_name}', $user_name, $message);
                $message = str_replace('{booking_id}', $booking_id, $message);
                $message = str_replace('{booking_submit_time}', $booking_submit_time, $message);

                if(!$new_user) {
                    if(strpos('dummy', $user_email) == false && $user_email != ''){
                        $message = str_replace('{user_email_address}', $user_email, $message);
                    } else {
                        $message = str_replace('{user_email_address}', '情報なし', $message);
                    }
                } else{
                    if(!$email_inputed) {
                        $message = str_replace('{user_email_address}', '情報なし', $message);
                    } else {
                        $message = str_replace('{user_email_address}', $user_email, $message);
                    }
                }
//                    $message = str_replace('{user_password}', $password, $message);
                $message = str_replace('{user_phone}', $user_phone, $message);
                $message = str_replace('{shop_name}', $depart_shop->name, $message);
                $message = str_replace('{car_model_name}', $car_model->name, $message);
                $message = str_replace('{car_short_name}', $car->shortname, $message);
                $message = str_replace('{car_capacity}', $class->tagname, $message);
                $message = str_replace('{smoke}', $smoke, $message);
                $message = str_replace('{option_items}', implode(', ',$option_names_ja), $message);
                $message = str_replace('{insurance_type}', $insurance_type_ja, $message);
                $message = str_replace('{departing_time}', $depart_dt_ja, $message);
                $message = str_replace('{returning_time}', $return_dt_ja, $message);
                $message = str_replace('{total_price}', $total_price, $message);
                $message = str_replace('{car_class_name}', $class->name, $message);
                $message = str_replace('{car_plate_number}', $car->numberplate4, $message);
                $content = $message;

                if(strpos($_SERVER['HTTP_HOST'], 'hakoren') === false){
                    // for motocle8 test
                    $mail_addresses = [
                        'sinchong1989@gmail.com',
                        'business@motocle.com'
                    ];
                } else {
                    // for hakoren staffs
                    $mail_addresses = [
                        'bluexie0455@gmail.com',
                        'reservation-f@hakoren.com',
                        'reservation-o@hakoren.com',
                        'hakoren2016@gmail.com',
                        'n.08041134223@gmail.com',
                        'sarue0525@gmail.com',
                        'mailform@motocle.com'
                    ];
                }

                foreach ($mail_addresses as $address){
                    $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $template->sender, 'email' => $address);
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
                $domain = 'http://motocle7.sakura.ne.jp/projects/rentcar/hakoren/public';
                curl_setopt($ch[0], CURLOPT_URL, url('/') . "/mail/vaccine/medkenmail.php");
                //                    curl_setopt($ch[0], CURLOPT_URL, $protocol . $domain . "/mail/vaccine/medkenmail.php");
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

        } else { // when there is no car to allocate
            return array('success'=>false, 'errors'=>['No car to allocate']);
//            return redirect('/search-confirm')
//                ->with('no_car_error', 'There is no car fit to your request.')
//                ->withInput();
        }

        $flight = $request->input('data_flight_line');

        if($flight == 0) {
            $flight = '';
        } else {
            $flight = \DB::table('flight_lines')->where('id', $flight)->first();
            $flight = is_null($flight)? '' : $flight->title;
        }

        $data = [
            'name'          => $user_name,
            'phone'         => $user_phone,
            'email'         => $new_user? '' : $user_email,
            'flight'        => $flight,
            'memo'          => empty($request->input('data_memo'))? '' : $request->input('data_memo'),
            'start_date'    => date('Y/m/d', strtotime($depart_datetime)),
            'return_date'   => date('Y/m/d', strtotime($return_datetime)),
            'class_name'    => $class->name,
            'smoke'         => $smoke,
            'insurance_type' => $insurance_type,
            'option'        => implode(',', $option_prices),
            'total'         => number_format($total_price)
            ];
        return array('success'=>true, 'errors'=>'', 'data' => $data);
    }

    // get options like model
    public function getOptionsfromModel(Request $request) {
        $inventory_id = $request->get('inventory_id','0');
        $class_id = 0;
        if($inventory_id != 0){
            $invens     = explode("_",$inventory_id);
            $class_id   = $invens[1];
        }

        $options_null = array();

        $options = \DB::table('car_option as co')
            ->join('car_option_class as coc','coc.option_id','=','co.id')
            ->select(['co.*'])
            ->where('coc.class_id',$class_id)
            ->orderby('id', 'asc')
            ->get();

        if(!empty($options)) {
            return Response::json($options);
        }else {
            return Response::json($options_null);
        }
    }

    //get pirce from startdate, inventory_id, request_Date
    public function getPrice(Request $request) {

        $start_date     = $request->get('start_date');
        $end_date       = $request->get('end_date');
        $inventory      = $request->get('inventory_id');
        $group          = explode("_", $inventory);
        $inventory_id   = $group[0];
        $selected_day   = explode("_", $request->get('selected_day'));
        $price          = ServerPath::getPrice($start_date, $end_date, $inventory_id, $selected_day);

        return $price;
    }

    //get insurance price
    //get pirce from startdate, inventory_id, request_Date
    public function getInsurancePrice(Request $request) {

        $depart_date    = $request->get('depart_date');
        $depart_time    = $request->get('depart_time');
        $return_date    = $request->get('return_date');
        $return_time    = $request->get('return_time');
        $inventory      = $request->get('inventory_id');
        $group          = explode("_", $inventory);
        $class_id       = $group[1];

        $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
        $night = $request_days['night'];
        $day   = $request_days['day'];
        $insurance_condition   =  $request->input('insurance');
        $insurance1 = 0 ;
        $insurance2 = 0;
        $price      = 0;

        if($insurance_condition == '1') {
            $insurance1 = ServerPath::getInsurancePrice(1, $class_id) * $day;
            $price = $insurance1;
        }
        if($insurance_condition == '2') {
            $insurance1 = ServerPath::getInsurancePrice(1, $class_id) * $day;
            $insurance2 = ServerPath::getInsurancePrice(2, $class_id) * $day;
            $price      = $insurance1+$insurance2;
        }
        return $price;
    }

    /*
    * task rent and return of cars from customers
    */
    function task(Request $request) {
        $input = Input::all();
        if($request->has('target_object')) {
            $target_object = $input['target_object'];
        }else{
            $target_object = 'page-wrapper';
        }
        if($request->has('cflag')) {
            $cflag = $input['cflag'];
        }else {
            $cflag = '0';
        }
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $shops          = Shop::all();
        $task_date  = 'today' ;
        $category   = 'all';
        $shop_id    = '0';
        $booking_status = '';
        if(!empty($input['task_date'])) $task_date = $input['task_date'];
        $date = '';
        switch ($task_date) {
            case 'today':
                $date = date('Y-m-d');
                break;
            case 'tom':
                $datetime = new \DateTime();
                $datetime->modify('+1 day');
                $date = $datetime->format('Y-m-d');
                break;
            case 'week':
                $date=date('Y-m-d',strtotime('+7 days'));
                break;
            default:
                $date = date('Y-m-d');
                break;
        }


        $current_user = Auth::user();

        $related_shop = \DB::table('admin_shop')->where('admin_id', $current_user->id)->first();
        $user_shop_id = 0;
        if(!empty($related_shop))
            $user_shop_id = $related_shop->shop_id;

        if(!empty($input['category'])) $category = $input['category'];
        
        if(!empty($input['shop_id'])) {
            $shop_id = $input['shop_id'];
        }else if(!empty($user_shop_id)){
            $shop_id = $user_shop_id;
        }else{
            $shop_id = $shops[0]->id;
        }

        $shop_property  = Shop::where('id', $shop_id)->first();
        $shop_slug      = $shop_property->slug;
//        if($shop_slug == 'naha-airport') { //If shop is Okinawa
//            $car_option     = CarOption::where('type','0')->get(); //paid option
//        }else{
//            //If shop is not Okinawa
//            $car_option     = CarOption::where('type','0')->where('google_column_number','!=','38')->get();        }

        $car_option = \DB::table('car_option as o')
            ->leftJoin('car_option_shop as s', 'o.id', '=', 's.option_id')
            ->select(['o.*', 's.shop_id'])
            ->where('s.shop_id', $shop_id)
            ->where('o.type', 0)
            ->get();

        //********departing********
        $departings    = $this->getbooks($date, $shop_id, 'depart' , $task_date) ; // departing
        $departings    = $this->getProperty($date, $departings,'depart');
        $rent_count = count($departings);

        //**********returning**********
        $returnings   = $this->getbooks($date,$shop_id, 'return',$task_date) ; // returning
        $returnings    = $this->getProperty($date, $returnings,'return');
        $return_count = count($returnings);

        //**********task completed depart
        $departing_end    = $this->getbooks($date, $shop_id, 'depart_end',$task_date) ; // completed departing
        $departing_end    = $this->getProperty($date, $departing_end,'depart');
        $rent_end_count = count($departing_end);

        //**********task completed returning
        $returning_end    = $this->getbooks($date, $shop_id, 'return_end',$task_date) ; // completed departing
        $returning_end    = $this->getProperty($date, $returning_end,'return');
        $returning_end_count    = count($returning_end);

        if($category == 'rent'){
            $returnings = array();
            $return_count = 0;
            $returning_end = array();
            $returning_end_count = 0;
        }
        if($category == 'return'){
            $departings = array();
            $rent_count = 0;
            $departing_end = array();
            $rent_end_count = 0;
        }

        $all_count  = $rent_count+$rent_end_count+$return_count+$returning_end_count;
        $rent_all = $rent_count + $rent_end_count;
        $rent_ratio = 0;
        if($rent_all != 0) $rent_ratio = round(100/$rent_all)* $rent_end_count;
        if($rent_ratio > 100) $rent_ratio =100;

        $return_all = $return_count + $returning_end_count;
        $return_ratio = 0;
        if($return_all != 0) $return_ratio = round(100/$return_all)* $returning_end_count;
        if($return_ratio > 100) $return_ratio = 100;
        $rent_display =  $rent_count + $return_count;
        $return_display =  $rent_end_count + $returning_end_count;

        $hours          = ['9:00','9:30','10:00','10:30',
            '11:00','11:30','12:00','12:30','13:00','13:30','14:00',
            '14:30','15:00','15:30','16:00','16:30','17:00','17:30',
            '18:00','18:30','19:00','19:30'];

        $current_user = Auth::user();

        $related_shop = \DB::table('admin_shop')->where('admin_id', $current_user->id)->first();
        $user_shop_id = 0;
        if(!empty($related_shop))
            $user_shop_id = $related_shop->shop_id;

        $portal_sites = \DB::table('portal_site')->orderby('id')->get();
        $sites = [];
        foreach ($portal_sites as $p) {
            $sites[$p->id] = $p->name;
        }

        $data = [
            'route'         => $route ,
            'subroute'      => $subroute ,
            'shops'         => $shops ,
            'shop_id'       => $shop_id,
            'rents'         => $departings,
            'rents_all'     => $rent_all,
            'rents_count'       => $rent_count,
            'rents_end'         => $departing_end,
            'rents_end_count'   => $rent_end_count,
            'returns'           => $returnings,
            'return_all'        => $return_all,
            'returns_count'     => $return_count,
            'returns_end'       => $returning_end,
            'returns_end_count' => $returning_end_count,
            'task_date'     => $task_date,
            'date'          => $date,
            'category'      => $category,
            'caroptions'    => $car_option,
            'all_count'     => $all_count,
            'rent_ratio'    => $rent_ratio,
            'return_ratio'  => $return_ratio,
            'rent_display'  =>$rent_display,
            'return_display'  =>$return_display,
            'target_object'=>$target_object,
            'cflag'         =>$cflag,
            'shop_slug'     =>$shop_slug,
            'hour'          => $hours,
            'portal_sites'  => $sites,
        ];
        return View('pages.admin.booking.task')->with($data);
    }

    function task1(Request $request) {
        $input = Input::all();
        if($request->has('target_object')) {
            $target_object = $input['target_object'];
        }else{
            $target_object = 'page-wrapper';
        }
        $cflag = '';
        if($request->has('cflag')) {
            $cflag = $input['cflag'];
        }else {
            $cflag = '0';
        }

        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $shops          = Shop::all();
        $task_date  = 'today' ;
        $category   = 'all';
        $shop_id    = '0';
        $booking_status = '';
        if(!empty($input['task_date'])) $task_date = $input['task_date'];
        $date = '';
        switch ($task_date) {
            case 'today':
                $date = date('Y-m-d');
                break;
            case 'tom':
                $datetime = new \DateTime();
                $datetime->modify('+1 day');
                $date = $datetime->format('Y-m-d');
                break;
            case 'week':
                $date=date('Y-m-d',strtotime('+7 days'));
                break;
            default:
                $date = date('Y-m-d');
                break;
        }


        $current_user = Auth::user();

        $related_shop = \DB::table('admin_shop')->where('admin_id', $current_user->id)->first();
        $user_shop_id = 0;
        if(!empty($related_shop))
            $user_shop_id = $related_shop->shop_id;

        if(!empty($input['category'])) $category = $input['category'];

        if(!empty($input['shop_id'])) {
            $shop_id = $input['shop_id'];
        }else if(!empty($user_shop_id)){
            $shop_id = $user_shop_id;
        }else{
            $shop_id = $shops[0]->id;
        }

        $shop_property  = Shop::where('id', $shop_id)->first();
        $shop_slug      = $shop_property->slug;
//        if($shop_slug == 'naha-airport') { //If shop is Okinawa
//            $car_option     = CarOption::where('type','0')->get(); //paid option
//        }else{
//            //If shop is not Okinawa
//            $car_option     = CarOption::where('type','0')->where('google_column_number','!=','38')->get();        }

        $car_option = \DB::table('car_option as o')
            ->leftJoin('car_option_shop as s', 'o.id', '=', 's.option_id')
            ->select(['o.*', 's.shop_id'])
            ->where('s.shop_id', $shop_id)
            ->where('o.type', 0)
            ->get();

        //********departing********
        $departings    = $this->getbooks($date, $shop_id, 'depart' , $task_date) ; // departing
        $departings    = $this->getProperty($date,$departings,'depart');
        $rent_count = count($departings);

        //**********returning**********
        $returnings   = $this->getbooks($date,$shop_id, 'return',$task_date) ; // returning
        $returnings    = $this->getProperty($date,$returnings,'return');
        usort($returnings, function($a, $b) {
            return strcmp(date('H:i',strtotime($a->return_set_day)),date("H:i", strtotime($b->return_set_day)));
        });
        $return_count = count($returnings);

        //**********task completed depart
        $departing_end    = $this->getbooks($date, $shop_id, 'depart_end',$task_date) ; // completed departing
        $departing_end    = $this->getProperty($date,$departing_end,'depart');
        $rent_end_count = count($departing_end);

        //**********task completed returning
        $returning_end    = $this->getbooks($date, $shop_id, 'return_end',$task_date) ; // completed departing
        $returning_end    = $this->getProperty($date,$returning_end, 'return');
        $returning_end_count    = count($returning_end);

        if($category == 'rent'){
            $returnings = array();
            $return_count = 0;
            $returning_end = array();
            $returning_end_count = 0;
        }
        if($category == 'return'){
            $departings = array();
            $rent_count = 0;
            $departing_end = array();
            $rent_end_count = 0;
        }

        $all_count  = $rent_count+$rent_end_count+$return_count+$returning_end_count;
        $rent_all = $rent_count + $rent_end_count;
        $rent_ratio = 0;
        if($rent_all != 0) $rent_ratio = round(100/$rent_all)* $rent_end_count;
        if($rent_ratio > 100) $rent_ratio =100;

        $return_all = $return_count + $returning_end_count;
        $return_ratio = 0;
        if($return_all != 0) $return_ratio = round(100/$return_all)* $returning_end_count;
        if($return_ratio > 100) $return_ratio = 100;
        $rent_display =  $rent_count + $return_count;
        $return_display =  $rent_end_count + $returning_end_count;

        $hours          = ['9:00','9:30','10:00','10:30',
            '11:00','11:30','12:00','12:30','13:00','13:30','14:00',
            '14:30','15:00','15:30','16:00','16:30','17:00','17:30',
            '18:00','18:30','19:00','19:30'];

        $current_user = Auth::user();

        $related_shop = \DB::table('admin_shop')->where('admin_id', $current_user->id)->first();
        $user_shop_id = 0;
        if(!empty($related_shop))
            $user_shop_id = $related_shop->shop_id;

        $portal_sites = \DB::table('portal_site')->orderby('id')->get();
        $sites = [];
        foreach ($portal_sites as $p) {
            $sites[$p->id] = $p->name;
        }

        $data = [
            'route'         => $route ,
            'subroute'      => $subroute ,
            'shops'         => $shops ,
            'shop_id'       => $shop_id,
            'rents'         => $departings,
            'rents_all'     => $rent_all,
            'rents_count'       => $rent_count,
            'rents_end'         => $departing_end,
            'rents_end_count'   => $rent_end_count,
            'returns'           => $returnings,
            'return_all'        => $return_all,
            'returns_count'     => $return_count,
            'returns_end'       => $returning_end,
            'returns_end_count' => $returning_end_count,
            'task_date'     => $task_date,
            'date'          => $date,
            'category'      => $category,
            'caroptions'    => $car_option,
            'all_count'     => $all_count,
            'rent_ratio'    => $rent_ratio,
            'return_ratio'  => $return_ratio,
            'rent_display'  =>$rent_display,
            'return_display'  =>$return_display,
            'target_object'=>$target_object,
            'cflag'         => $cflag,
            'shop_slug'     =>$shop_slug,
            'hour'          => $hours,
            'portal_sites'  => $sites,
            'select_date'   => $date,
        ];
        return View('pages.admin.booking.task1')->with($data);
    }

    function printtask($booking_id){
        $bookingInfo = \DB::table('bookings')
                        ->where('id', $booking_id)
                        ->first();
        $carClass    = \DB::table('car_class')->where('id', $bookingInfo->class_id)->orderBy('car_class_priority')->first();
        $userInfo    = User::find($bookingInfo->client_id);
        $class_shop_id = $carClass->car_shop_name;
        $car_shop = \DB::table('car_shop')->where('id',$class_shop_id)->first();   
        $bookingInfo = \DB::table('bookings as b')
                    ->leftjoin('users as u', 'b.client_id','=','u.id')
                    ->leftjoin('profiles as p', 'b.client_id', '=', 'p.user_id')
                    ->leftjoin('car_inventory as ci', 'ci.id','=','b.inventory_id')
                    ->leftjoin('car_class as cc','cc.id','=','b.class_id')
                    ->leftjoin('car_model as cm','cm.id','=','b.model_id')           
                    ->select(['b.*','ci.model_id','u.first_name','u.last_name','p.fur_first_name','p.fur_last_name',
                        'p.phone','u.email', 'ci.numberplate1 as car_number1','ci.numberplate2 as car_number2','ci.numberplate3 as car_number3',
                        'ci.numberplate4 as car_number4','cc.id as class_id','cc.name as class_name','cm.id as model_id','ci.shortname',
                        'cm.name as model_name'])
                    ->where('b.id', $booking_id)
                    ->first();

        if($bookingInfo->portal_flag == 0) {
            $user_name = $bookingInfo->last_name.' '.$bookingInfo->first_name;
            if($user_name == ' ')
            {
                $user_name = $bookingInfo->fur_last_name.' '.$bookingInfo->fur_first_name;
            }
        } else {
            $portal_info = \GuzzleHttp\json_decode($bookingInfo->portal_info);
            $user_name = $portal_info->last_name.' '.$portal_info->first_name;
            if($user_name == ' ') {
                $user_name = $portal_info->fu_last_name.' '.$portal_info->fu_first_name;
            }
        }

        $data   = [
            'customer_name' => $user_name,
            'departure_date'=> date('Y/m/d', strtotime($bookingInfo->departing)) ,
            'car_class'     => $bookingInfo->class_name,
            'car_number1'   => $bookingInfo->car_number1,
            'car_number2'   => $bookingInfo->car_number2,
            'car_number3'   => $bookingInfo->car_number3,
            'car_number4'   => $bookingInfo->car_number4,
            'shortname'     => $bookingInfo->shortname,
            'customer_phone'=> $bookingInfo->phone,
            'car_shop_name' => $car_shop->name,
            'car_shop_phone'=> $car_shop->phone, 
            'car_shop_address_1' => $car_shop->address1,                 
            'car_shop_address_2' => $car_shop->address2,
            'car_shop_prefecture' => $car_shop->prefecture,
            'car_shop_city'       => $car_shop->city,                
        ];
         
        return View('pages.admin.booking.printtask')->with(['data'=>$data]);
    }

    function printalltask(Request $request, $car_shop_id){

        $input = Input::all();
        if($request->has('target_object')) {
            $target_object = $input['target_object'];
        }else{
            $target_object = 'page-wrapper';
        }
        if($request->has('cflag')) {
            $cflag = $input['cflag'];
        }else {
            $cflag = '0';
        }
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $shops          = Shop::all();
        $task_date  = 'today' ;
        $category   = 'all';
        $shop_id    = '0';
        $booking_status = '';
        if(!empty($input['task_date'])) $task_date = $input['task_date'];
        $date = '';
        switch ($task_date) {
            case 'today':
                $date = date('Y-m-d');
                break;
            case 'tom':
                $datetime = new \DateTime();
                $datetime->modify('+1 day');
                $date = $datetime->format('Y-m-d');
                break;
            case 'week':
                $date=date('Y-m-d',strtotime('+7 days'));
                break;
            default:
                $date = date('Y-m-d');
                break;
        }

        if(!empty($input['category'])) $category = $input['category'];
        if(!empty($input['shop_id'])) {
            $shop_id   = $input['shop_id'];
        }else {
            $shop_id    = $shops[0]->id;
        }
        $shop_property  = Shop::where('id', $shop_id)->first();
        $shop_slug      = $shop_property->slug;
        if($shop_slug == 'naha-airport') { //If shop is Okinawa
            $car_option     = CarOption::where('type','0')->get(); //paid option
        }else{
            //If shop is not Okinawa
            $car_option     = CarOption::where('type','0')->where('google_column_number','!=','38')->get(); //paid option
        }

        $car_shop_data =  Shop::where('id', $car_shop_id)->first();

//        if(!empty($bookingInfo->last_name) && !empty($bookingInfo->first_name))
//        {
//            $user_name = $bookingInfo->last_name.' '.$bookingInfo->first_name;
//        }
//        else
//        {
//            $user_name = $bookingInfo->fur_last_name.' '.$bookingInfo->fur_first_name;
//        }


        //********departing********
        $departings    = $this->getbooks($date, $car_shop_id, 'depart' , $task_date) ; // departing
        $departings    = $this->getProperty($date, $departings , 'depart');
        $car_shop_name     = $car_shop_data->name;
        $car_shop_phone    = $car_shop_data->phone;
        $car_shop_address_1 = $car_shop_data->address1;
        $car_shop_address_2 = $car_shop_data->address2;
        $car_shop_prefecture    = $car_shop_data->prefecture;
        $car_shop_city          = $car_shop_data->city;
        return View('pages.admin.booking.printalltasks')
            ->with(
                [   'data' => $departings,
                    'car_shop_name' => $car_shop_name,
                    'car_shop_phone' => $car_shop_phone,
                    'car_shop_address_1' => $car_shop_address_1,
                    'car_shop_address_2' => $car_shop_address_2,
                    'car_shop_prefecture' => $car_shop_prefecture,
                    'car_shop_city' => $car_shop_city,
//                    'customer_name' => $user_name,
                ]);
            
    }
    
	function printalltaskempty(Request $request, $car_shop_id){

        $input = Input::all();
        if($request->has('target_object')) {
            $target_object = $input['target_object'];
        }else{
            $target_object = 'page-wrapper';
        }
        if($request->has('cflag')) {
            $cflag = $input['cflag'];
        }else {
            $cflag = '0';
        }
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $shops          = Shop::all();
        $task_date  = 'today' ;
        $category   = 'all';
        $shop_id    = '0';
        $booking_status = '';
        if(!empty($input['task_date'])) $task_date = $input['task_date'];
        $date = '';
        switch ($task_date) {
            case 'today':
                $date = date('Y-m-d');
                break;
            case 'tom':
                $datetime = new \DateTime();
                $datetime->modify('+1 day');
                $date = $datetime->format('Y-m-d');
                break;
            case 'week':
                $date=date('Y-m-d',strtotime('+7 days'));
                break;
            default:
                $date = date('Y-m-d');
                break;
        }
        if(!empty($input['category'])) $category = $input['category'];
        if(!empty($input['shop_id'])) {
            $shop_id   = $input['shop_id'];
        }else {
            $shop_id    = $shops[0]->id;
        }
        $shop_property  = Shop::where('id', $shop_id)->first();
        $shop_slug      = $shop_property->slug;
        if($shop_slug == 'naha-airport') { //If shop is Okinawa
            $car_option     = CarOption::where('type','0')->get(); //paid option
        }else{
            //If shop is not Okinawa
            $car_option     = CarOption::where('type','0')->where('google_column_number','!=','38')->get(); //paid option
        }

        $car_shop_data =  Shop::where('id', $car_shop_id)->first();

//        if(!empty($bookingInfo->last_name) && !empty($bookingInfo->first_name))
//        {
//            $user_name = $bookingInfo->last_name.' '.$bookingInfo->first_name;
//        }
//        else
//        {
//            $user_name = $bookingInfo->fur_last_name.' '.$bookingInfo->fur_first_name;
//        }


        //********departing********
        $departings    = $this->getbooks($date, $car_shop_id, 'depart' , $task_date) ; // departing
        $departings    = $this->getProperty($date, $departings , 'depart');
        $car_shop_name     = $car_shop_data->name;
        $car_shop_phone    = $car_shop_data->phone;
        $car_shop_address_1 = $car_shop_data->address1;
        $car_shop_address_2 = $car_shop_data->address2;
        $car_shop_prefecture    = $car_shop_data->prefecture;
        $car_shop_city          = $car_shop_data->city;
        return View('pages.admin.booking.printalltasksempty')
            ->with(
                [   'data' => $departings,
                    'car_shop_name' => $car_shop_name,
                    'car_shop_phone' => $car_shop_phone,
                    'car_shop_address_1' => $car_shop_address_1,
                    'car_shop_address_2' => $car_shop_address_2,
                    'car_shop_prefecture' => $car_shop_prefecture,
                    'car_shop_city' => $car_shop_city,
//                    'customer_name' => $user_name,
                ]);
            
    }
    
    //getProperty from booking
    function getProperty($cu_date, $departings, $cond) {
        $ret = array();
        $count = 0;
        $pickup_options = explode(",",'250, 255, 254, 253, 252, 251, 106, 38, 101');
        //rent car
        foreach($departings as $departing) {
            if($cond == 'return' ) { //if there is a extedn date, then change from origin to this date
                $ret_day = $departing->returning;
                $ext_day = $departing->returning_updated;
                if(strtotime($ext_day) > strtotime($ret_day)) {
                    if (date('Y-m-d', strtotime($cu_date)) != date('Y-m-d', strtotime($ext_day))) {
                        $count++;
                        continue;
                    }
                }
               $return_set_day =   $ret_day;
               if($ext_day != '0000-01-01 00:00:00') $return_set_day =   $ext_day;
                $departings[$count]->return_set_day = $return_set_day;
            }
            if(empty($departing->before_miles)) $departings[$count]->before_miles = '0';
            if($departing->previous_miles > 0 ) $departings[$count]->before_miles = $departing->previous_miles;
            $insurance1_flag = false;
            $insurance2_flag = false;
            $etc_flag = false;
            $return_pay_status = false;
            if(!empty($departing->insurance1) && $departing->insurance1 > 0 ) $insurance1_flag = true;
            if(!empty($departing->insurance2) && $departing->insurance2 > 0 ) $insurance2_flag = true;
            $portal_flag    = $departing->portal_flag;
            $portal_inform  = json_decode($departing->portal_info);
            $model_id       = $departing->model_id;
            $paid_options   = explode("," ,$departing->paid_options);
            $paid_options_price = explode("," ,$departing->paid_options_price);
            $paid_options_number = explode("," ,$departing->paid_option_numbers);
            $free_options   = $departing->free_options;
            if(empty($free_options)) $free_options = 0;
            $free_option_object = \DB::table('car_option')->where('id', $free_options)->first();
            if(!empty($free_option_object))
                $departings[$count]->free_options = $free_option_object->google_column_number;
            else
                $departings[$count]->free_options = 0;
            //get days for rental
            $depart_date    = date('Y-m-d', strtotime($departing->departing));
            $depart_time    = date('H:i:s', strtotime($departing->departing));
            $return_date    = date('Y-m-d', strtotime($departing->returning));
            $return_time    = date('H:i:s', strtotime($departing->returning));
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night = $request_days['night'];
            $day = $request_days['day'];
            $departings[$count]->night = $night;
            $departings[$count]->day = $day;
            if(strtotime($departing->returning_updated) > strtotime('1970-01-01 00:00:00')) {
                $return_date = date('Y-m-d',strtotime($departing->returning_updated));
                $extend_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
                $extend_night  = $extend_days['night'];
                $extend_day    = $extend_days['day'];
                $departings[$count]->night = $extend_night;
                $departings[$count]->day = $extend_day;
            }

            $departings[$count]->child_seat = '0';
            $departings[$count]->baby_seat  = '0';
            $departings[$count]->junior_seat= '0';
            $departings[$count]->etc_card   = '0';
            $departings[$count]->snow_tire  = '0';
            $departings[$count]->smart_driveout  = '0';
            $client_id      = $departing->client_id;
            $bookedcount    = $this->getBookedCount($client_id);
            $departings[$count]->bookedcount=$bookedcount;
            $class_id       = $departing->class_id;
            $basic_insurances = \DB::table('car_class_insurance as cci')
                ->leftjoin('car_insurance as ci', 'ci.id','=','cci.insurance_id')
                ->where('cci.class_id', $class_id)
                ->select(['cci.*'])
                ->orderby('ci.search_condition', 'asc')
                ->get();
            if(!empty($basic_insurances[0])) {
                $departings[$count]->basic_insurance1 = $basic_insurances[0]->price;
            }else{
                $departings[$count]->basic_insurance1 = 0;
            }
            if(!empty($basic_insurances[1])) {
                $departings[$count]->basic_insurance2 = $basic_insurances[1]->price;
            }else{
                $departings[$count]->basic_insurance2 = 0;
            }
            $pickup_options_list = array();
            if(!empty($paid_options)) {
                $caroptions = \DB::table('car_option as co')
                    ->whereIn('co.id', $paid_options)
                    ->select(['co.id as option_id', 'co.name as option_name', 'co.abbriviation as short_name', 'co.price as option_price','co.google_column_number as index'])
                    ->get();
                $m = 0;
                foreach($caroptions as $op) {
                    $pa_op_price = 0;
                    $pa_op_number = 0;
                    for($i=0; $i<count($paid_options); $i++){
                        if($paid_options[$i] == $op->option_id) {
                            if(!empty($paid_options_price[$i])&& !empty($paid_options_number[$i])) $pa_op_price = $paid_options_price[$i]*$paid_options_number[$i];
                            if(!empty($paid_options_number[$i])) $pa_op_number = $paid_options_number[$i];

                            //child seat(22), baby seat(23), junior seat(24), etc cards(25), snow tire(26)
                            if($op->index == '22'){
                                $departings[$count]->child_seat   = $pa_op_price;
                                $departings[$count]->child_seat_number   = $pa_op_number;
                            }
                            if($op->index == '23'){
                                $departings[$count]->baby_seat    = $pa_op_price;
                                $departings[$count]->baby_seat_number    = $pa_op_number;
                            }
                            if($op->index == '24'){
                                $departings[$count]->junior_seat  = $pa_op_price;
                                $departings[$count]->junior_seat_number    = $pa_op_number;
                            }
                            if($op->index == '25'){
                                $departings[$count]->etc_card     = $pa_op_price;
                                $departings[$count]->etc_card_number     = $pa_op_number;
                                if($pa_op_price > 0) {
                                    $etc_flag = true;
                                }
                            }
                            if($op->index == '26'){
                                $departings[$count]->snow_tire    = $pa_op_price;
                                $departings[$count]->snow_tire_number    = $pa_op_number;
                            }
                            if($op->index == '38'){
                                $departings[$count]->smart_driveout    = $pa_op_price;
                                $departings[$count]->smart_driveout_number    = $pa_op_number;
                                //$departings[$count]->wait_status = '3'; // disable free service
                            }
                            //add pick up

                            //if( array_search($op->index, $pickup_options)) $pickup_options_list[]= $op->option_name;
                            if( array_search($op->index, $pickup_options)) $pickup_options_list[]= $op->short_name;
                        }
                    }
                    $caroptions[$m]->option_price = $pa_op_price;
                    $caroptions[$m]->option_number = $pa_op_number;
                    $m++;
                }
                $departings[$count]->options = $caroptions;
            }else {
                $departings[$count]->options = array();
            }
            if(!empty($free_option_object))
                $pickup_options_list[] = $free_option_object->name;
            $departings[$count]->pickup_options = $pickup_options_list;
            //get driver license inform
            $driver_license_images = \DB::table('bookings_driver_licences')
                            ->where('booking_id','=',$departing->id)
                            ->get();
            $departings[$count]->driver_license_images = $driver_license_images;
            //poratal settig
            if(count((array)$portal_inform) == 0) {
                $portal_flag = 0;
            }
            $pay_status_flag = true;
            if($departing->pay_status != '1') $pay_status_flag = false;
            //get additional/extend prices and options
            $price_type = '1' ;//departure
            if($cond == 'depart') $price_type = '1';
            if($cond == 'return') $price_type = '2';
            $additional_prices = \DB::table('bookings_price')->where('type','add')->where('book_id', $departing->id)->where('price_type' ,$price_type)->orderby('created_at','asc')->get();
            $saved_additional   = array();
            $cu_additional      = array();
            foreach($additional_prices as $ad) {
                if($ad->pay_status == '0' && $departing->pay_status == '1') {
                    $additional_flag = false;
                }
                $ad_insurance1 = $ad->insurance1;
                $ad_insurance2 = $ad->insurance2;
                $ad_insurance  = $ad_insurance1 + $ad_insurance2;
                $ad->insurance_price = $ad_insurance;
                if(!empty($ad_insurance1) && $ad_insurance1 > 0 )$insurance1_flag = true;
                if(!empty($ad_insurance2) && $ad_insurance2 > 0 )$insurance2_flag = true;
                //insurance
                $ad_carins = \DB::table('car_insurance as ci')
                    ->leftjoin('car_class_insurance as cci','cci.insurance_id','=','ci.id')
                    ->where('cci.class_id', $class_id)
                    ->select(['ci.*','cci.price as basic_price'])
                    ->orderby('ci.search_condition', 'asc')
                    ->get();
                if(!empty($ad_carins)) {
                    $c = 0;
                    foreach ($ad_carins as $in) {
                        if($in->search_condition == '1') {
                            $ad_carins[$c]->price = $ad_insurance1;
                        }
                        if($in->search_condition == '2') {
                            $ad_carins[$c]->price = $ad_insurance2;
                        }
                        $c++;
                    }
                }
                $ad->insurance = $ad_carins;
                //options
                $ad_option_ids      = explode(',', $ad->paid_options);
                $ad_option_number   = explode(',', $ad->paid_options_number);
                $ad_option_price    = explode(',', $ad->paid_options_price);
                $all_option_price   = 0;
                $adoptions = \DB::table('car_option as co')
//                    ->leftjoin('car_option_class as coc','co.id','=','coc.option_id')
//                    ->where('co.type', 0)
//                    ->where('coc.class_id', $class_id)
                    ->whereIn('co.id', $ad_option_ids)
                    ->select(['co.id as option_id', 'co.name as option_name', 'co.abbriviation as short_name', 'co.price as option_price','co.google_column_number as index','co.max_number'])
                    ->orderby('co.order','asc')
                    ->get();
                $m = 0;
                foreach($adoptions as $op) {
                    $pa_op_price = 0;
                    $pa_op_number = 0;
                    for($i=0; $i<count($ad_option_ids); $i++){
                        if($ad_option_ids[$i] == $op->option_id) {
                            if(!empty($ad_option_price[$i]))  $pa_op_price = $ad_option_price[$i]*$ad_option_number[$i];
                            if(!empty($ad_option_number[$i])) $pa_op_number = $ad_option_number[$i];
                            if($op->index == '25' && $ad_option_price[$i] > 0 ) $etc_flag = true; // if etc option
                        }
                    }
                    if($op->max_number == '1') {
                        $option_flag_add = $this->confirmOptionAdd($departing->id, $op->option_id, $ad->id);
                        $op->option_flag_add = $option_flag_add;
                    }else {
                        $op->option_flag_add = false;
                    }
                    $adoptions[$m]->option_flag_add  = $op->option_flag_add;
                    $adoptions[$m]->option_basic_price  = $op->option_price;
                    $adoptions[$m]->option_price  = $pa_op_price;
                    $adoptions[$m]->option_number = $pa_op_number;
                    if($pa_op_price != 0) $selected_option[] = $adoptions[$m];
                    $all_option_price += $pa_op_price;
                    $m++;
                }
                $ad->options = $adoptions;
                $ad->option_price = $all_option_price;
                if($ad->pay_status == '1') $saved_additional[] = $ad;
                if($ad->pay_status == '0') {
                    $cu_additional[] = $ad;
                    $pay_status_flag = false;
                }
            }
            $departings[$count]->insurance1_flag  = $insurance1_flag;
            $departings[$count]->insurance2_flag  = $insurance2_flag;
            /// get etc _flag in addiotnal list
            $additional_prices = \DB::table('bookings_price')->where('type','add')->where('book_id', $departing->id)
                                    ->orderby('created_at','asc')->get();
            foreach($additional_prices as $ad) {
                $ad_option_ids = explode(',', $ad->paid_options);
                $ad_option_price = explode(',', $ad->paid_options_price);
                for ($i = 0; $i < count($ad_option_ids); $i++) {
                    if($ad_option_ids[$i] > 0 ) {
                        $option_id = $ad_option_ids[$i];
                        $option = \DB::table('car_option')
                            ->where('id', $option_id)->first();
                        if(!empty($option)) {
                            if ($option->google_column_number == '25') {
                                $etc_flag = true;
                                break;
                            } // if etc option
                        }
                    }
                }
                $pay_status = $ad->pay_status;
                $price_type = $ad->price_type;
                if($price_type == '2') {
                    if($pay_status == '1') {
                        $return_pay_status = true;
                    }else {
                        $return_pay_status = false;
                        break;
                    }
                }

            }
            ///
            $departings[$count]->etc_flag  = $etc_flag;
            $departings[$count]->return_pay_status  = $return_pay_status;
            $etc_card_used = 0;
            if($etc_flag == true) {
                $booking_price_etc = \DB::table('bookings_price')
                    ->where('book_id', $departings[$count]->id)
                    ->where('price_type','2')
                    ->where('etc_card','!=','0')
                    ->select(DB::raw("SUM(etc_card) as etc_card"))
                    ->first();
                if(!empty($booking_price_etc)) {
                    $etc_card_used = $booking_price_etc->etc_card;
                }
            }
            $unpaid_payment_return = 0;
            $total_return = 0;
            $booking_price_unpaid_total = \DB::table('bookings_price')
                ->where('book_id', $departings[$count]->id)
                ->where('price_type','2')
                ->where('pay_status','0')
                ->select(DB::raw("SUM(total_price) as total_price"))
                ->first();
            if(!empty($booking_price_unpaid_total)) {
                $unpaid_payment_return = $booking_price_unpaid_total->total_price;
            }
            $booking_price_return_total = \DB::table('bookings_price')
                ->where('book_id', $departings[$count]->id)
                ->where('price_type','2')
                ->select(DB::raw("SUM(total_price) as total_price"))
                ->first();
            if(!empty($booking_price_return_total)) {
                $total_return = $booking_price_return_total->total_price;
            }
            $departings[$count]->etc_card_used = $etc_card_used;
            $departings[$count]->unpaid_payment_return = $unpaid_payment_return;
            $departings[$count]->total_return = $total_return;
            $departings[$count]->saved_additional  = $saved_additional;
            $departings[$count]->cu_additional     = $cu_additional;
            $departings[$count]->pay_status_flag   = $pay_status_flag;
            $departings[$count]->paid_payment      = $this->paidmount($departing->id);
            $departings[$count]->unpaid_payment    = $this->unpaidmount($departing->id);
            //end additional prices
            if($portal_flag == 1) {
                //booking
                $departings[$count]->booking  = $portal_inform->booking;
                $departings[$count]->phone    = $portal_inform->phone;
                $departings[$count]->email    = $portal_inform->email;
                $departings[$count]->last_name        = $portal_inform->last_name;
                $departings[$count]->first_name       = $portal_inform->first_name;
                $departings[$count]->fur_last_name    = $portal_inform->fu_last_name;
                $departings[$count]->fur_first_name   = $portal_inform->fu_first_name;
                if($portal_inform->smoke == '喫煙')  $departings[$count]->smoke = '1';
                if($portal_inform->smoke == '禁煙')  $departings[$count]->smoke = '0';
                //$departings[$count]->smoke            = $portal_inform->smoke;
                $departings[$count]->free_option_name =  $portal_inform->free_option_name;
                $departings[$count]->returning_point  =  $portal_inform->returning_point;
                if(empty($portal_inform->bad_flag)) $portal_inform->bad_flag = '0';
                $departings[$count]->bad_flag         =  $portal_inform->bad_flag;// bad price
            }
            array_push($ret, $departings[$count]);
            $count++;
        }
        return $ret;
    }

    //get booking list
    function getbooks($date, $shop_id, $cond, $task_date) {
        $statuses = array("1", "2", "3","4","5","6","7","8","10"); //except the cancel
        $rent = \DB::table('bookings as b')
            ->leftjoin('users as u', 'b.client_id','=','u.id')
            ->leftjoin('profiles as p', 'b.client_id', '=', 'p.user_id')
            ->leftjoin('car_inventory as ci', 'ci.id','=','b.inventory_id')
            ->leftjoin('booking_status as bs','bs.status','=','b.status')
            ->leftjoin('car_class as cc','cc.id','=','b.class_id')
            ->leftjoin('car_model as cm','cm.id','=','b.model_id')
            ->leftjoin('car_shop as csp','csp.id','=','b.pickup_id')
            ->leftjoin('car_shop as csd','csd.id','=','b.dropoff_id')
            ->leftjoin('credit_card as crc','crc.id','=','b.pay_method')
            ->leftjoin('portal_site as ps','ps.id','=','b.portal_id')
            ->leftjoin('flight_lines as fl','fl.id','=','b.flight_line')
            ->leftjoin('users as ad', 'ad.id','=','b.admin_id')
            ->select(['b.*','ci.model_id','bs.name as booking_status','u.first_name','u.last_name','p.fur_first_name','p.fur_last_name',
                'p.phone','u.email', 'ci.numberplate1 as car_number1','ci.numberplate2 as car_number2','ci.numberplate3 as car_number3',
                'ci.numberplate4 as car_number4','ci.shortname','cc.id as class_id','cc.name as class_name','cm.id as model_id',
                'cm.name as model_name','ci.smoke','crc.name as card_name','csp.id as pickup_id','csp.name as pickup_name',
                'csd.id as drop_id','csd.name as drop_name','ps.name as portal_name','fl.title as flight_name', 'ps.show_flag',
                'ad.last_name as admin_last_name','ad.first_name as admin_first_name', 'ci.current_mileage as before_miles'])
//            ->where('ps.show_flag', '!=', 0)
            ->whereIn('b.status', $statuses);
        if($cond == 'depart') {
            if($task_date == 'week') {
                $today = date('Y-m-d');
                $rent = $rent->whereDate('b.departing', '<=', $date)
                    ->whereDate('b.departing', '>=', $today)
                    ->where('b.depart_task', '0');
            }else {
                $rent = $rent->whereDate('b.departing', '=', $date)
                    ->where('b.depart_task', '0');
            }
        }
        if($cond == 'depart_end') {
            if($task_date == 'week') {
                $today = date('Y-m-d');
                $rent = $rent->whereDate('b.departing', '<=', $date)
                    ->whereDate('b.departing', '>=', $today)
                    ->where('b.depart_task', '1');
            }else {
                $rent = $rent->whereDate('b.departing', '=', $date)
                    ->where('b.depart_task', '1');
            }
        }
        if($cond == 'return') {
            if($task_date == 'week') {
                $today = date('Y-m-d');
                $rent = $rent->whereDate('b.returning', '<=', $date)
                    ->whereDate('b.returning', '>=', $today)
                    //->where('b.depart_task', '1')
                    ->where('b.return_task', '0');
            }else {
                $rent = $rent->where(function ($query) use($date) {
                            $query->where(function ($query2) use($date) {
                                $query2->whereDate('b.returning', '=', $date)
                                        ->where('b.returning_updated','0000-01-01 00:00:00');
                            })->orwhere(function ($query1) use($date) {
                                    $query1->whereDate('b.returning_updated', '=', $date);
                                });
                    })
                       // where('b.returning', 'LIKE', '%' . $date . '%')
                    //->where('b.depart_task', '1')
                    ->where('b.return_task', '0');
            }
        }
        if($cond == 'return_end') {
            if($task_date == 'week') {
                $today = date('Y-m-d');
                $rent = $rent->whereDate('b.returning', '<=', $date)
                    ->whereDate('b.returning', '>=', $today)
                    ->where('b.depart_task', '1')
                    ->where('b.return_task', '1');
            }else {
                $rent = $rent->where(function ($query) use($date) {
                    $query->whereDate('b.returning', '=', $date)
                        ->orwhere(function ($query1) use($date) {
                            $query1->whereDate('b.returning_updated', '=', $date);
                        });
                })
                    //where('b.returning', 'LIKE', '%' . $date . '%')
                    ->where('b.depart_task', '1')
                    ->where('b.return_task', '1');
            }
        }
        if($cond == 'depart' || $cond == 'depart_end' ) {
            $rent = $rent->where('b.pickup_id', $shop_id)
                ->orderBy('departing', 'asc')->orderBy('returning', 'asc')->get();
        }else {
            $rent = $rent->where('b.pickup_id', $shop_id)
                ->orderBy('returning', 'asc')->get();
        }
        $tmp = [];
        foreach ($rent as $key => $item) {
//            if($item->show_flag === 0) {
//                continue;
//            }
            $tmp[] = $item;
        }
        return $tmp;

    }
    //get count booked about any user
    function getBookedCount($user_id) {
        if($user_id == null) {
            return 1;
        } else {
            $count = \DB::table('bookings')
                ->where('client_id', $user_id)
//            ->where('status','8') //completed=end
                ->count();
            return empty($count)? 0 : $count;
        }

    }
    //save status
    function savestatus(Request $request) {
        $input          = Input::all();
        $booking_id     = $input['booking_id'];
        $status_name    = $input['status_name'];
        $status_value   = $input['status_value'];
        $name           = '';
        if($status_name == 'web')       $name = 'web_status';
        if($status_name == 'wait')      $name = 'wait_status';
        if($status_name == 'explain')   $name = 'explain_status';
        if($status_name == 'pay')       $name = 'pay_status';
        if($status_name == 'shop_pay')  $name = 'shop_pay_status';
        if($status_name == 'return')    $name = 'return_status';
        if($status_name == 'clean')     $name = 'clean_status';
        if($status_name == 'wash')      $name = 'wash_status';
        if($status_name == 'end')       $name = 'end_status';
        $updatebooking = \DB::table('bookings')->
        where('id', $booking_id)->update([$name => $status_value]);
        if($status_name == 'pay') {
            if($status_value == '1') {
                $updatebooking = \DB::table('bookings')->
                where('id', $booking_id)->update(['pay_position' => '1']);
            }else {
                $updatebooking = \DB::table('bookings')->
                where('id', $booking_id)->update(['pay_position' => '0']);
            }
        }
        if($status_name == 'shop_pay') {
            if($status_value == '1') {
                $updatebooking = \DB::table('bookings')->
                where('id', $booking_id)->update(['pay_position' => '2']);
            }else {
                $booking = \DB::table('bookings')->where('id', $booking_id)->first();
                $pay_status = $booking->pay_status;
                if($pay_status == '1') {
                    $updatebooking = \DB::table('bookings')->
                    where('id', $booking_id)->update(['pay_position' => '1']);
                }else {
                    $updatebooking = \DB::table('bookings')->
                    where('id', $booking_id)->update(['pay_position' => '0']);
                }
            }
        }
        $receive = array();
        if($updatebooking) {
            $receive['data']    = 'true';
        }else {
            $receive['data']    = 'false';
        }
        return Response::json($receive);
    }

    //save memo
    function savememo(Request $request) {
        $input          = Input::all();
        $booking_id     = $input['booking_id'];
        $admin_memo     = $input['admin_memo'];
        $other_status   = $input['other_status'];

        $updatebooking = \DB::table('bookings')->
        where('id', $booking_id)->update(['admin_memo' => $admin_memo,'other_status'=>$other_status]);

        $receive = array();
        if($updatebooking) {
            $receive['data']    = 'true';
        }else {
            $receive['data']    = 'false';
        }
        return Response::json($receive);
    }

    //chage price insurance and option
    function changeoption(Request $request)
    {
        $input       = Input::all();
        $booking_id  = $input['booking_id'];
        $book = \DB::table('bookings as b')
                ->where('b.id', $booking_id)->first();
        $departing = $book->departing;
        $returning = $book->returning;
        $depart_date    = date('Y-m-d', strtotime($departing));
        $depart_time    = date('H:i:s', strtotime($departing));
        $return_date    = date('Y-m-d', strtotime($returning));
        $return_time    = date('H:i:s', strtotime($returning));
        $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
        $day = $request_days['day'];

        if($request->has('insurance1')) {
            $insurance1  = 0;
            //get insurnce from car class
            $ins = \DB::table('bookings as b')
                ->leftjoin('car_class_insurance as cci', 'cci.class_id','=','b.class_id')
                ->leftjoin('car_insurance as ci', 'ci.id','=','cci.insurance_id')
                ->where('b.id', $booking_id)
                ->where('ci.search_condition', '1')
                ->select(['cci.*'])
                ->first();
            if(!empty($ins)) $insurance1 = $ins->price*$day;
        }
        else $insurance1 = 0;
        if($request->has('insurance2')) {
            $insurance2  = 0;
            //get insurnce from car class
            $ins = \DB::table('bookings as b')
                ->leftjoin('car_class_insurance as cci', 'cci.class_id','=','b.class_id')
                ->leftjoin('car_insurance as ci', 'ci.id','=','cci.insurance_id')
                ->where('b.id', $booking_id)
                ->where('ci.search_condition', '2')
                ->select(['cci.*'])
                ->first();
            if(!empty($ins)) $insurance2 = $ins->price*$day;
        }
        else $insurance2 = 0;
        if(!empty($input['basic_price'])) $basic_price = $input['basic_price'];
        else $basic_price = 0;
        $keys        = array_keys($input);
        $ids    = array();
        $prices = array();
        $prices_number = array();
        $option_all_price = 0;

        foreach ($keys as $key) {
            if(strpos($key, 'option') !== false) {
                $option_ids   = explode("_", $key);
                $option_id    = $option_ids[1];
                $option_price = explode("_", $input[$key]);
                $option_all_price +=$option_price[0]*$option_price[1];//price * number
                array_push($ids, $option_id);
                array_push($prices, $option_price[0]);
                array_push($prices_number,$option_price[1]);
            }
        }

        $paid_options     = implode(',',$ids);
        $paid_options_price  = implode(',',$prices);
        $paid_option_number = implode(',',$prices_number);
        $payment = $basic_price+$insurance1 + $insurance2 +$option_all_price;
        $updatebooking = \DB::table('bookings')->
        where('id', $booking_id)
            ->update(['insurance1' => $insurance1,
                'insurance2' => $insurance2,
                'paid_options' => $paid_options,
                'paid_option_numbers' => $paid_option_number,
                'paid_options_price' => $paid_options_price,
                'option_price' => $option_all_price,
                'payment' => $payment ]);

        $receive = array();
        if($updatebooking) {
            $receive['data']    = 'true';
        }else {
            $receive['data']    = 'true';
        }
        return Response::json($receive);
    }
    //change insurance1, insurance and ETC
    public function changeRentInsuranceETC(Request $request) {
        $input       = Input::all();
        $book_id    = $input['book_id'];
        $cond       = $input['cond'];
        $cond_flag       = $input['flag'];
        $book = \DB::table('bookings')->where('id', $book_id)->first();
        $paid_options = $book->paid_options;
        $paid_options_numbers   = $book->paid_option_numbers;
        $paid_options_price     = $book->paid_options_price;
        //get price insurance1, insurance2, etc
        $depart_date    = date('Y-m-d', strtotime($book->departing));
        $depart_time    = date('H:i:s', strtotime($book->departing));
        $return_date    = date('Y-m-d', strtotime($book->returning));
        $return_time    = date('H:i:s', strtotime($book->returning));
        $class_id       = $book->class_id;

        $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
        $day   = $request_days['day'];
        $ins1 = \DB::table('car_insurance as ci')
            ->where('search_condition','1')->first();
        $ins1_prices = \DB::table('car_class_insurance')
            ->where('class_id', $class_id)
            ->where('insurance_id', $ins1->id)->first();
        $insurance1 = $ins1_prices->price * $day;

        $ins2 = \DB::table('car_insurance as ci')
            ->where('search_condition','2')->first();
        $ins2_prices = \DB::table('car_class_insurance')
            ->where('class_id', $class_id)
            ->where('insurance_id', $ins2->id)->first();
        $insurance2 = $ins2_prices->price * $day;
        
        $option     = \DB::table('car_option')->where('google_column_number', '25')->first();
        $option_id  = $option->id;
        $etc        = $option->price;
        $car_calss_option = \DB::table('car_option_class')->where('class_id',$class_id)->where('option_id',$option->id)->first();
        //check insurance1, insurance2, ETC
        if($cond == 'insurance1' ) {
            $ins1_flag = true;
            if($book->pay_status == '0' ) {
                $book_insurance1 = $book->insurance1;
                $book_insurance2 = $book->insurance2;
                if(empty($book_insurance1)) $book_insurance1 = 0;
                if(empty($book_insurance2)) $book_insurance2 = 0;
                if($cond_flag == '1') {
                    $payment = $book->payment - $book_insurance1 - $book_insurance2;
                    $new_insurance1 = 0;
                    $new_insurance2 = 0;
                }
                else {
                    $payment = $book->payment - $book_insurance1  + $insurance1 ;
                    $new_insurance1 = $insurance1;
                    $new_insurance2 = $book_insurance2;
                }
                $update = \DB::table('bookings')
                    ->where('id', $book_id)
                    ->update(['insurance1'=>$new_insurance1,'insurance2'=>$new_insurance2,'payment'=>$payment]);
                $ins1_flag = false;
            }

            $additional = \DB::table('bookings_price')->where('book_id', $book_id)->get();
            if(!empty($additional)) {
                $origin_ins1_flag = true;
                if($book->insurance1 <= 0) $origin_ins1_flag = false;
                foreach ($additional as $ad) {
                    if ($ad->pay_status == '0') {
                        $add_insurance1 = $ad->insurance1;
                        $add_insurance2 = $ad->insurance2;
                        if(empty($add_insurance1)) $add_insurance1 = 0;
                        if($cond_flag == '1') {
                            if($origin_ins1_flag == false) {
                                $total = $ad->total_price - $add_insurance1 - $add_insurance2;
                                $new_insurance1 = 0;
                                $new_insurance2 = 0;
                            }else {
                                $total = $ad->total_price;
                                $new_insurance1 = $add_insurance1;
                                $new_insurance2 = $add_insurance2;
                            }
                        }else {
                            $total = $ad->total_price - $add_insurance1 + $insurance1;
                            $new_insurance1 = $insurance1;
                            $new_insurance2 = $add_insurance2;
                        }

                        $id = $ad->id;
                        $addupdate = \DB::table('bookings_price')->where('id', $id)->update(['insurance1' => $new_insurance1, 'insurance2'=> $new_insurance2,'total_price'=> $total]);
                        $ins1_flag = false;
                    }else {
                        if($ad->insurance1 <= 0) $origin_ins1_flag = false;
                    }
                }
            }
            if($ins1_flag == true && $cond_flag == '0' ) {
                $addinsert = \DB::table('bookings_price')->insert(['book_id'=>$book_id,'insurance1'=>$insurance1,'type'=>'add','total_price' => $insurance1]);
            }
        }
        if($cond == 'insurance2' ) {
            $ins2_flag = true;
            if($book->pay_status == '0' ) {
                $book_insurance1 = $book->insurance1;
                $book_insurance2 = $book->insurance2;
                if(empty($book_insurance2)) $book_insurance2 = 0;

                if($cond_flag == '1') {
                    $payment = $book->payment - $book_insurance2;
                    $new_insurance2 = 0;
                }else {
                    if(is_numeric($book_insurance1) && intval($book_insurance1) > 0) {
                        $payment = $book->payment - $book_insurance2 + $insurance2;
                        $new_insurance2 = $insurance2;
                    }else {
                        $new_insurance2 = 0;
                        $payment = $book->payment;
                    }
                }

                $update = \DB::table('bookings')
                    ->where('id', $book_id)
                    ->update(['insurance2'=>$new_insurance2,'payment'=>$payment]);
                $ins2_flag = false;
            }else {
                $book_insurance1 = $book->insurance1;
                if($book_insurance1 <= 0 )  $ins2_flag = false;
            }

            $additiona2 = \DB::table('bookings_price')->where('book_id', $book_id)->get();
            if(!empty($additiona2)) {
                $ins1_flag = false;
                if($book->insurance1 > 0) $ins1_flag = true;
                foreach ($additiona2 as $ad) {
                    if($ad->insurance1 > 0) $ins1_flag = true;
                    if ($ad->pay_status == '0') {
                        $add_insurance1 = $ad->insurance1;
                        $add_insurance2 = $ad->insurance2;
                        if(empty($add_insurance2)) $add_insurance2 = 0;
                        if($cond_flag == '1') {
                            $total = $ad->total_price - $add_insurance2;
                            $new_insurance2 = 0;
                        }else {
                            if($ins1_flag == true) {
                                $total = $ad->total_price - $add_insurance2 + $insurance2;
                                $new_insurance2 = $insurance2;
                            }else {
                                $total = $ad->total_price;
                                $new_insurance2 = 0;
                            }
                        }
                        $id = $ad->id;
                        $addupdate = \DB::table('bookings_price')->where('id', $id)->update(['insurance2' => $new_insurance2,'total_price' => $total]);
                        $ins2_flag = false;
                    }else{
                      if($ins1_flag == false)   $ins2_flag = false;
                    }
                }
            }
            //if there is no insurcnae1
            if($ins2_flag == true && $cond_flag == '0') {
                $addinsert = \DB::table('bookings_price')->insert(['book_id'=>$book_id, 'insurance2'=>$insurance2,'type'=>'add', 'total_price'=>$insurance2]);
            }
        }
        if($cond == 'etc' ) {
            if(!empty($car_calss_option)) { // If this class inlcude etc option
                $etc_flag = true;
                if ($book->pay_status == '0') {
                    if($cond_flag == '1') {
                        $flag = false;
                        $paid_options_array = explode(',', $paid_options);
                        $count = 0;
                        $order = 0;
                        foreach($paid_options_array as $op) {
                            if($option_id == $op) {
                                $order = $count;
                                $flag = true;
                                break;
                            }
                            $count++;
                        }
                        $paid_options_numbers_array = explode(',', $paid_options_numbers);
                        $paid_options_price_array = explode(',', $paid_options_price);
                        $minusprice = 0;
                        if($flag == true) {
                            $minusprice = $paid_options_numbers_array[$order] * $paid_options_price_array[$order];
                            unset($paid_options_array[$order]);
                            unset($paid_options_numbers_array[$order]);
                            unset($paid_options_price_array[$order]);
                        }
                        $payment = $book->payment - $minusprice;
                        $price = 0;
                        for ($i = 0; $i < count($paid_options_array); $i++) {
                            $op_num = $paid_options_numbers_array[$i];
                            if(!is_numeric($op_num)) $op_num = 0;
                            $op_price = $paid_options_price_array[$i];
                            if(!is_numeric($op_price)) $op_price = 0;
                            $price += $op_num * $op_price;
                        }
                    }else {
                        $paid_options_array = explode(',', $paid_options);
                        $flag = true;
                        if (empty(array_search($option_id, $paid_options_array))) {
                            $paid_options_array[] = $option_id;
                            $flag = false;
                        }
                        $last_str = substr($paid_options_numbers , -1); // returns ","
                        if($last_str == ',') $paid_options_numbers = substr($paid_options_numbers,0, -1);
                        $paid_options_numbers_array = explode(',', $paid_options_numbers);
                        if ($flag == false) $paid_options_numbers_array[] = '1';
                        $paid_options_price_array = explode(',', $paid_options_price);
                        if ($flag == false) $paid_options_price_array[] = $etc;
                        $price = 0;
                        for($i=0; $i < count($paid_options_array); $i++) {
                            $op_num     = $paid_options_numbers_array[$i];
                            $op_price   = $paid_options_price_array[$i];
                            if(!is_numeric($op_num)) $op_num = 0;
                            if(!is_numeric($op_price)) $op_price = 0;
                            $price += $op_num * $op_price;
                        }
                        $book_option_price = $book->option_price;
                        if (empty($book_option_price)) $book_option_price = 0;
                        $payment = $book->payment - $book_option_price + $price;
                    }
                    $update = \DB::table('bookings')
                        ->where('id', $book_id)
                        ->update(['paid_options' => implode(',', $paid_options_array),
                            'paid_option_numbers' => implode(',', $paid_options_numbers_array),
                            'paid_options_price' => implode(',', $paid_options_price_array),
                            'option_price' => $price, 'payment' => $payment]);
                    $etc_flag = false;
                }
                $additiona2 = \DB::table('bookings_price')->where('book_id', $book_id)->get();
                if (!empty($additiona2)) {
                    foreach ($additiona2 as $ad) {
                        $paid_options = $ad->paid_options;
                        $paid_options_numbers = $ad->paid_options_number;
                        $paid_options_price = $ad->paid_options_price;
                        if($cond_flag == '1') {
                            $order = 0;
                            $flag = false ;
                            $paid_options_array = explode(',',$paid_options);
                            $paid_options_numbers_array = explode(',',$paid_options_numbers);
                            $paid_options_price_array = explode(',',$paid_options_price);
                            for ($i = 0; $i < count($paid_options_array); $i++) {
                                if($paid_options_array[$i] == $option_id) {
                                    $flag = true;
                                    $order= $i;
                                    break;
                                }
                            }

                            $minusprice = 0;
                            if($flag == true) {
                                $minusprice = $paid_options_numbers_array[$order] * $paid_options_price_array[$order];
                                unset($paid_options_array[$order]);
                                unset($paid_options_numbers_array[$order]);
                                unset($paid_options_price_array[$order]);
                            }
                            $total_price = $ad->total_price - $minusprice;

                        }else {
                            $origin_option_price = 0;
                            $paid_options_array = explode(',',$paid_options);
                            $paid_options_numbers_array = explode(',',$paid_options_numbers);
                            $paid_options_price_array = explode(',',$paid_options_price);
                            for ($i = 0; $i < count($paid_options_array); $i++) {
                                $op_num     = $paid_options_numbers_array[$i];
                                if(!is_numeric($op_num)) $op_num = 0;
                                $op_price   = $paid_options_price_array[$i];
                                if(!is_numeric($op_price)) $op_price = 0;
                                $origin_option_price += $op_num * $op_price ;
                            }

                            $flag = true;

                            if (empty(array_search($option_id, $paid_options_array))) {
                                $paid_options_array[] = $option_id;
                                $flag = false;
                            }
                            if ($flag == false) $paid_options_numbers_array[] = 1;
                            if ($flag == false) $paid_options_price_array[] = $etc;
                            $new_option_price = 0;
                            for ($i = 0; $i < count($paid_options_array); $i++) {
                                $op_num     = $paid_options_numbers_array[$i];
                                if(!is_numeric($op_num)) $op_num = 0;
                                $op_price   = $paid_options_price_array[$i];
                                if(!is_numeric($op_price)) $op_price = 0;
                                $new_option_price += $op_num * $op_price;
                            }
                            $total_price = $ad->total_price - $origin_option_price + $new_option_price;
                        }
                        if ($ad->pay_status == '0') {
                            $id = $ad->id;
                            $addupdate = \DB::table('bookings_price')->where('id', $id)
                                ->update(['paid_options' => implode(',', $paid_options_array),
                                    'paid_options_number' => implode(',', $paid_options_numbers_array),
                                    'paid_options_price' => implode(',', $paid_options_price_array),
                                    'total_price' => $total_price ]);
                            $etc_flag = false;
                        }
                    }
                }
                if ($etc_flag == true && $cond_flag == '0') {
                    $addinsert = \DB::table('bookings_price')
                        ->insert(['type'=>'add',
                            'book_id' => $book_id,
                            'paid_options' => $option_id,
                            'paid_options_number' => '1',
                            'paid_options_price' => $etc, 'total_price' => $etc]);
                }
            }
        }
        $receive = array();
        return Response::json($receive);
    }
    // booking compare like tomorrow
    public function comparebooking($book, $cond) {
        $date = '';
        if($cond == 'tomorrow') {
            $datetime = new \DateTime();
            $datetime->modify('+1 day');
            $date    = $datetime->format('Y-m-d');
        }
        $inventory_id   = $book->inventory_id;
        $class_id       = $book->class_id;
        $book = \DB::table('bookings as b')
            ->select(['b.*'])
            ->where('b.class_id', $class_id) // confirmed(3)= rent, using(6) = return
            ->where('b.departing','LIKE', '%'.$date.'%')
            ->where('b.inventory_id', $inventory_id)
            ->orderBy('id','asc')->get();
        if($book)
            return  true;
        else
            return false;
    }
    //complete status with task event
    function completeStatus(Request $request)
    {
        $input       = Input::all();
        $booking_id  = $input['booking_id'];
        $miles       = $input['miles'];
        $etc_card    = $input['etc_card'];
        $etc_card_pay_method    = $input['etc_card_pay_method'];
        $before_miles= $input['before_miles'];
        $task  = $input['task'];
        $invent  = \DB::table('bookings as b')
            ->leftjoin('car_inventory as ci', 'ci.id','=','b.inventory_id')
            ->where('b.id', $booking_id)
            ->select(['ci.*'])->first();
        if(!empty($invent)) {
            $beofre_mile = $invent->current_mileage;
            if(empty($beofre_mile)) $beofre_mile = 0;
            $new_miles       = $miles;
            $inventory_id    = $invent->id;
            if($task == 'return') {
                $update = \DB::table('car_inventory')
                    ->where('id', $inventory_id)
                    ->update(['current_mileage' => $new_miles]);
            }
        }
        $updatebooking = \DB::table('bookings')->where('id', $booking_id);
        if($task == 'depart') {
            $depart_task_date = date('Y-m-d');
            $updatebooking = $updatebooking
                            ->update(['depart_task' => 1,'depart_task_date'=> $depart_task_date , 'com_status' => 1, 'status' => 6]); // status changed to [using]
        }
        if($task == 'return') {
            $mile_status = 0;
            if(intval($before_miles) < intval($miles) ) {
                $mile_status = 1;
                $return_task_date = date('Y-m-d');
                $updatebooking = $updatebooking->
                update(['return_task' => 1, 'return_task_date' => $return_task_date , 'end_status' => 1, 'miles' => $miles, 'previous_miles'=>$before_miles , 'mile_status' => $mile_status, 'status' => 8]);
            }
            //add etc_card in additional list
            $booking_price = \DB::table('bookings_price')
                ->where('book_id',$booking_id)
                ->where('price_type','2')
                //->where('etc_card','!=','0')
                ->first();
            if(!empty($booking_price)) {
                $booking_price_id = $booking_price->id ;
                $insurance1 = $booking_price->insurance1;
                $insurance2 = $booking_price->insurance2;
                if(empty($etc_card_pay_method)) {
                    $etc_card_pay_method = $booking_price->pay_method;
                }
                $paid_options_number = explode(',',$booking_price->paid_options_number);
                $paid_options_price = explode(',',$booking_price->paid_options_price);
                $options_price = 0;
                for($i = 0 ; $i < count($paid_options_number) ; $i++) {
                    if(!is_numeric($paid_options_number[$i]))   $paid_options_number[$i] = 0;
                    if(!is_numeric($paid_options_price[$i]))    $paid_options_price[$i] = 0;
                    $options_price += $paid_options_number[$i] * $paid_options_price[$i];
                }
                $adjustment_price   = $booking_price->adjustment_price;
                $extend_payment     = $booking_price->extend_payment;
                $total = $insurance1 + $insurance2 + $options_price + $adjustment_price + $extend_payment;
                $total_price = $total + $etc_card;
                $ret = array();
                $ret['type']            = 'add';
                $ret['etc_card']        = $etc_card;
                $ret['pay_method']      = $etc_card_pay_method;
                $ret['pay_status']      = '1';
                $ret['total_price']      = $total_price;
                $ret['price_type']      = '2';
                $ret['paid_date']       = date('Y-m-d');
                $booking_price_update = \DB::table('bookings_price')
                    ->where('id',$booking_price_id)
                    ->update($ret);
            }else {
                $ret = array();
                $ret['book_id']         = $booking_id;
                $ret['type']            = 'add';
                $ret['etc_card']        = $etc_card;
                $ret['pay_method']      = $etc_card_pay_method;
                $ret['pay_status']      = '1';
                $ret['total_price']     = $etc_card;
                $ret['price_type']      = '2';
                $ret['paid_date']       =  date('Y-m-d') ;
                $inserBookingPrice = \DB::table('bookings_price')->insert($ret);
            }

        }
        $receive = array();
        if($updatebooking) {
            $receive['data']    = 'true';
        }else {
            $receive['data']    = 'false';
        }
        return Response::json($receive);
    }

    //get options like class
    public function getOptionsFromClass($class_id , $select_options){
        $ops = \DB::table('car_option as co')
            ->join('car_option_class as coc','coc.option_id','=', 'co.id')
            ->where('coc.class_id',$class_id)
            ->orderby('co.order')
            ->select(['co.*'])->get();
        $add_opions = array();
        if(!empty($select_options)) {
            if (!empty($ops)) {
                foreach ($ops as $op) {
                    if (in_array($op->id, $select_options)) {
                        array_push($add_opions, $op);
                    }
                }
            } else {
//                $add_opions = $ops;
            }
        }else {
//            $add_opions = $ops;
        }
        return $add_opions;
    }

    //get shop name from id
    public function getShopName($shop_id){
        $shop = \DB::table('car_shop')->where('id', $shop_id)->first();
        if(!empty($shop))
            return $shop->name;
        else
            return "";
    }

    //get category name from id
    public function getCategoryName($category_id){
        $category = \DB::table('car_type_category')
            ->where('id',$category_id)
            ->select(['*'])->first();
        if(!empty($category))
            return $category->name;
        else
            return "";
    }
    
    //make pdf
    public function pdfview(Request $request)
    {
        $filenamee = '';
        //PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $shop = $request->get('shop');
        if($request->get('cond') == 'today') {
            return View('pages.admin.booking.pdf-today')->with(['shop' => $shop]);
           //$pdf = PDF::loadView('pages.admin.booking.pdf-today');
            //$filenamee = date('Y-m-d').'_today';
        }
        if($request->get('cond') == 'tom') {
            return View('pages.admin.booking.pdf-tom')->with(['shop' => $shop]);
            //$pdf = PDF::loadView('pages.admin.booking.pdf-tom');
           // $filenamee = date('Y-m-d').'_tomorrow';
        }
      //  return $pdf->download($filenamee.'.pdf');
    }

    public function getDate($val) {
        $date = '';
        if($val == '1') $date = '月';
        if($val == '2') $date = '火';
        if($val == '3') $date = '水';
        if($val == '4') $date = '木';
        if($val == '5') $date = '金';
        if($val == '6') $date = '土';
        if($val == '7') $date = '日';
        return $date;
    }

    public function uploadLicense(Request $request) {
        $book_id = $request->get('book_id');
        if($thumbfile = $request->file('lic_front')) {
            $fileName = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/licenses/';
            $destinationPath = storage_path() . $folderName;
//            $folderName = '/images/licenses/';
//            $destinationPath = public_path() . $folderName;
            $safeName = strtotime("now").mt_rand(1,20);//.'.' . $extension;
            $thumbfile->move($destinationPath, $safeName);
//            $front_thumb_path = $folderName.$safeName;
            $front_thumb_path = '/file/'.$safeName;
        }
        if($thumbfile = $request->file('lic_back')) {
            $fileName = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/licenses/';
            $destinationPath = storage_path() . $folderName;
//            $folderName = '/images/licenses/';
//            $destinationPath = public_path() . $folderName;
            $safeName = strtotime("now").mt_rand(1,20);//.'.' . $extension;
            $thumbfile->move($destinationPath, $safeName);
//            $back_thumb_path = $folderName.$safeName;
            $back_thumb_path = '/file/'.$safeName;
        }
        $lic_insert = DB::table('bookings_driver_licences')
            ->insert([
                'booking_id'=>$book_id,
                'representative_license_surface' => $front_thumb_path,
                'representative_license_back' => $back_thumb_path,
            ]);
        return back();
    }

    public function deleteLicense(Request $request) {
        $lic_id = $request->get('lic_id');
        $lic_delete = DB::table('bookings_driver_licences')
            ->where('id', $lic_id)->delete();
        return back();
    }

    public function updateLicense(Request $request) {
        $id = $request->get('id');
        $side = $request->get('side');
        if($thumbfile = $request->file('photo')) {
            $folderName = '/licenses/';
            $destinationPath = storage_path() . $folderName;
            $safeName = strtotime("now").mt_rand(1,20);
            $thumbfile->move($destinationPath, $safeName);
            $thumb_path = '/file/'.$safeName;
            $data = ($side == 'front')?
                ['representative_license_surface' => $thumb_path] : ['representative_license_back' => $thumb_path];
            $lic_insert = DB::table('bookings_driver_licences')
                ->where('id', $id)
                ->update( $data);
            return ['success'=> true, 'id' => $id, 'side' => $side, 'url' => $thumb_path ];
        } else {
            return ['success'=> false, 'id' => $id, 'side' => $side, 'url' => '' ];
        }
    }
    //get paid price for booking
    public function getpaidprice(Request $request) {
        $book_id = $request->get('book_id');
        $price = $this->paidmount($book_id);
        $ret = array();
        $ret['code'] = '200';
        $ret['price'] = $price;
        return Response::json($ret);
    }
    //get confirm nextday booking
    public function nextBooking($inventory_id, $date) {
        $book_id  = 0;
        $departing = date('Y-m-d');
        $option_name = '';
        $ret = (object)array();
        $book = \DB::table('bookings')
            ->where('departing','like','%'.$date.'%')
            ->where('inventory_id', $inventory_id)
            ->where('status','!=','9')
            ->first();
        if(!empty($book)) {
            $book_id = $book->id;
            $departing = $book->departing;
            $paid_options   = explode("," ,$book->paid_options);
            $paid_options_number = explode("," ,$book->paid_option_numbers);
            if(!empty($paid_options)){
                $m = 0;
                foreach($paid_options as $op) {
                    $option_id = $paid_options[$m];
                    $caroptions = \DB::table('car_option as co')
                        ->where('co.id', $option_id)
                        ->select(['co.name as option_name'])
                        ->first();
                    if(!empty($caroptions)) {
                        $option_name .= $caroptions->option_name;
                        $option_name .= '(' . $paid_options_number[$m] . ')';
                    }
                    $m++;
                    if(count($paid_options) > $m )$option_name.=',';
                }
            }
        }
        $ret->book_id       = $book_id;
        $ret->departing     = $departing;
        $ret->option_name   = $option_name;
        return $ret;
    }
}
