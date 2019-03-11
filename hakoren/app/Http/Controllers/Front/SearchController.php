<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Session;
use App\Models\Shop;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\Profile;
use App\Models\CarPassengerTags;
use App\Models\RoleUser;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
use Auth;
use App\Models\CarClass;
use App\Models\CarOption;
use App\Models\CarModel;
use App\Models\CarInventory;
use Response;
use App\Http\DataUtil\ServerPath;
use DateTime;
use Validator;
use Input;
use View;
use App\Models\Page;
use Hash;
use DatePeriod;
use DateInterval;


class SearchController extends Controller
{

    private  $hours = ['09:00','09:30','10:00','10:30', '11:00', '11:30', '12:00',
        '12:30','13:00','13:30','14:00', '14:30','15:00','15:30','16:00',
        //'16:30','17:00','17:30', '18:00','18:30','19:00','19:30'];
        '16:30','17:00','17:30', '18:00','18:30','19:00'];

    //search result
    public function search(Request $request, $shop = null)
    {
        if(!is_null($shop)) {
            $shop_code = ucfirst($shop);
            $depart_shop = \DB::table('car_shop')->where('region_code', $shop_code)->first();
            if(!is_null($depart_shop)) {
                $depart_shop = $depart_shop->id;
                $return_shop = $depart_shop;
            }
        } else {
            $depart_shop = null;
            $return_shop = null;
        }
        $input = $request->all();
        $request_page    = $request->input('request_page', '');
//        var_dump($input); exit();

        $locale = App::getLocale();
        if(Session::has('locale') == '' )
            Session::put('locale', $locale);
        config(['app.locale' =>  Session::get('locale')]);
        //get default time
        $de_date = date('Y-m-d');
        $cu_min_sec     = date('H:i');
        $cu_min_sec_arr = explode(":", $cu_min_sec);
        $de_hour           = $cu_min_sec_arr[0];
        $de_min            = $cu_min_sec_arr[1];
        if(intval($de_hour) > 19){
            $de_date = date('Y-m-d', strtotime('tomorrow'));
        }
        if($de_min < 30) {
            $de_hour = intval($de_hour) + 3;
            $de_min = '30';
        }
        else {
            $de_hour = intval($de_hour) + 4;
            $de_min = '00';
        }
        $default_de_min_sec = $de_hour.":".$de_min;
        //echo "==============".$de_hour.":".$de_min ; return;
        $depart_date    = $request->input('depart_date', $de_date);
        $depart_time    = $request->input('depart_time', $default_de_min_sec);

        $return_date    = $request->input('return_date', date('Y-m-d', strtotime('tomorrow')));
        $return_time    = $request->input('return_time', '09:00');
        if(is_null($depart_shop)) {
            $depart_shop    = $request->input('depart_shop', '');
        }
        if(is_null($return_shop)) {
            $return_shop    = $request->input('return_shop', '');
        }
        $car_category   = $request->input('car_category', '');
        if($car_category == '') {
            $category = \DB::table('car_type_category')->where('name', '乗用車')->first();
            if(!empty($category)) $car_category = $category->id;
        }
        $passenger      = $request->input('passenger', '');
        $insurance      = 2; //$empty_request? '' : $request->get('insurance');
        if($request->has('options')) {
            if(is_array($input['options'])) {
                $options =  $input['options'];
            } else {
                $v =  $input['options'];
                $options = explode(',', $v);
            }

        } else {
            $options =  [];
        }

        $smoke          = $request->input('smoke', '');
        $pickup         = $request->input('pickup', '');
        $free_options   = $request->input('free_options', []);
        if($pickup != '') $free_options[] = $pickup;
        $search_condition = '';

        $shops      = Shop::all();
        $categorys  = \DB::table('car_type_category')->get();

        // get depart_shop name
        $depart_shop_name = '';
        $depart_shop_name_en = '';
        if($depart_shop != ''){
            $shop1 = \DB::table('car_shop')->where('id','=', $depart_shop)->first();
            if(!empty($shop1)) {
                $depart_shop_name = $shop1->name;
                $depart_shop_name_en = $shop1->name_en;
            }
        }

        $search = (object)array();
        $search->depart_date = $depart_date;
        $search->depart_time = $depart_time;
        $search->return_date = $return_date;
        $search->return_time = $return_time;
        $search->depart_shop = $depart_shop;
        $search->depart_shop_name = $depart_shop_name;
        $search->depart_shop_name_en = $depart_shop_name_en;
        $search->return_shop = $return_shop;
        $search->car_category= $car_category;
        $search->passenger   = $passenger;
        $search->insurance   = $insurance;
        $search->smoke       = $smoke;
        $search->options     = implode(',', $options);
        $search->free_options   = implode(',', $free_options);
        $search->pickup      = $pickup;

        $start  = $depart_date." ".$depart_time.':00';
        $end    = $return_date." ".$return_time.':00';

        //get date diff(night_day)
        $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
        $night = $request_days['night'];
        $day = $request_days['day'];
        $search->rentdates = $day;

        // search start
        $allClasses = \DB::table('car_class as c')
            ->join('car_class_price as p', 'c.id', '=', 'p.class_id')
            ->select(['c.*', 'p.n1d2_day1 as price'])
            ->where('c.car_shop_name', $depart_shop)
            ->where('c.status', 1)
            ->where('c.static_flag', 0)
            ->orderBy('c.car_class_priority')->get();

        $search_class = [];  // array to contain filtered classes
        foreach($allClasses as $cls){

            if($passenger != 'all') {
                $psgRanges = \DB::table('car_class_passenger AS p')
                    ->leftJoin('car_passenger_tags AS t', 'p.passenger_tag', '=','t.id')
                    ->where('p.class_id','=',$cls->id)
                    ->where('t.min_passenger','<=',$passenger)
                    ->where('t.max_passenger','>=',$passenger)
                    ->orderBy('t.show_order')
                    ->count();

                if( $psgRanges == 0 ) continue;
            }

            if( !empty( $options )) {
                $class_options = \DB::table('car_option_class')->where('class_id',$cls->id )->get();

                //check if class has all options of search condition
                $count_option_in_search = 0;
                foreach( $class_options as $cop ) {
                    if( in_array($cop->option_id, $options) ) {
                        $count_option_in_search++;
                    }
                }
                if( $count_option_in_search < count($options) ) continue;
            }

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
            //    $models = $models->where('ct.category_id','=',$car_category);
            }

            $models = $models->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id')
                            ->where('ci.delete_flag', 0)
                            ->where('ci.status', 1);
            if($passenger != 'all') {
                $models = $models->where('ci.max_passenger','>=', $passenger );
            } else {
                $models = $models->whereNotNull('ci.max_passenger' );
            }
            // check smoke
            if($smoke != '' && $smoke != 'both')
                $models = $models->where('ci.smoke', $smoke);
            // check shop
            if($depart_shop != '')
                $models = $models->where('ci.shop_id',$depart_shop);
            // check dropoff
            if($return_shop != '' && $return_shop != $depart_shop){
                $models = $models->leftJoin('car_inventory_dropoff as dr', 'ci.id', '=', 'dr.inventory_id')
                    ->where('dr.shop_id','=',$return_shop);
            }

            $cars = $models->select(['ci.*','cm.class_id', 'm.name as model_name', 'cm.model_id'])->get();

            if(empty($cars)) continue;  // if no car matching condition
            // check if car is available
            $count = 0;
            $mids = [];
            $mnames = [];
            foreach ($cars as $car) {
                $checkBook = ServerPath::getconfirmBooking($car->id, $depart_date, $return_date, $search_condition);     // if car usable, true else false
                $checkInspection = ServerPath::getConfirmInspection($car->id, $depart_date, $return_date,'', '');
                if($checkBook && $checkInspection){
                    $count++;
                    if(!array_search($car->model_id, $mids)){
                        array_push($mids, $car->model_id);
                        array_push($mnames, $car->model_name);
                    }
                }
            }
            if($count == 0) // if no car available
                continue;

//            $psgTags = \DB::table('car_class_passenger AS p')
//                ->leftJoin('car_passenger_tags AS t', 'p.passenger_tag', '=','t.id')
//                ->where('p.class_id','=', $cls->id)
//                ->orderBy('t.show_order')
//                ->get();
            $thumbnails = \DB::table('car_class_thumb')
                ->where('class_id', $cls->id)->get();

            $class = (object)array();
            $class->thumb_path       = (trim($cls->thumb_path)!= '')? trim($cls->thumb_path) : '/images/blank.jpg';
            $class->thumb_path_en    = (trim($cls->thumb_path_en)!= '')? trim($cls->thumb_path_en) : '/images/blank.jpg';
            $class->thumbnails  = $thumbnails;
            $class->id          = $cls->id;
            $class->price       = $cls->price;
            $class->class_name  = $cls->name;
            $class->model_id    = $mids;
            $class->model_name  = $mnames;
            $class->depart_date = $depart_date;
            $class->depart_time = $depart_time;
            $class->return_date = $return_date;
            $class->return_time = $return_time;
            $class->message = "Test message";
            $class->car_count = $count;
            $class->priority = $cls->car_class_priority;
            $class->insurance = $tins;
            $class->smoke = $smoke;
            $class->night_day = $night . '泊' . $day . '日';
            $class->night_day_en = $night . 'N' . $day . 'D';
//            $class->passengerTags = $psgTags;

            //get price
            $class_price = ServerPath::getPriceFromClass($depart_date, $return_date, $cls->id, $night . "_" . $day, $depart_date, $return_date);
            $class->price = $class_price;

            //get option for search
            if(!empty($options))
                $select_options = $this->getOptionsFromClass($cls->id, $options);
            else
                $select_options = array();
            $class->options = $select_options;
            $option_price = 0;
            if (!empty($select_options)) {
                foreach ($select_options as $op) {
                    if($op->charge_system == 'one') {
                        $option_price += $op->price;
                    } else {
                        $option_price += $op->price * $day;
                     }
                    $op->number = 1;
                }
            }
            $class->option_price = $option_price;
            $class->free_options = $this->getOptionsFromClass($cls->id, $free_options);
            $class->shop_id = $depart_shop;
            $class->shop_name       = $this->getShopName($depart_shop);
            $class->shop_name_en    = $this->getShopName_en($depart_shop);
            if(empty($car_category)) $car_category = "";
            $class->category_id = $car_category;
            $class->category_name = $this->getCategoryName($car_category);
            $class->category_name_en = $this->getCategoryName_en($car_category);
            //get insurance
//            $insurance_price = ServerPath::getInsurancePrice($insurance, $cls->id) * $day;
//            $class->insurance_price1 = $insurance_price;
            $insurance_price = ServerPath::getInsurancePrice2( $cls->id);
            $class->insurance_price1 = $insurance_price['ins1'] * $day;
            $class->insurance_price2 = $insurance_price['ins2'] * $day;
            $allPrice = $class_price + $option_price;
            $class->all_price = $allPrice;
//            $class->max_passengers = ServerPath::getClassMaxPassengers($cls->id, $depart_shop);
            $class->max_passengers = ServerPath::getClassAvailableMaxPassengers($cls->id, $depart_shop, $depart_date, $return_date);
            //get flag about returnshop
            array_push($search_class, $class);
        }

        // get passenger tags from car_passenger_tags
        $passengerTags = CarPassengerTags::orderBy('show_order')->get();

        $static_classes = [];
        if($depart_shop == 4){
            $static_classes = \DB::table('car_class')
                ->where('car_shop_name', $depart_shop)
                ->where('static_flag', 1)
                ->orderBy('car_class_priority')->get();
            foreach($static_classes as $cls) {
                $models = \DB::table('car_class_model as cm')
                    ->leftJoin('car_model as m', 'cm.model_id', 'm.id')
                    ->where('class_id', $cls->id)
                    ->orderBy('cm.priority')
                    ->get();
                $cls->thumb = $cls->thumb_path;
                $tmp = [];

                foreach ($models as $key=>$mod) {
                    if($key == 0) {
                        $cls->thumb = $mod->thumb_path;
                    }
                    array_push($tmp, $mod->name);
                }
                $cls->model_names = implode(', ', $tmp);

                $passengerTag = \DB::table('car_class as c')
                    ->leftJoin('car_class_passenger as p', 'c.id', '=', 'p.class_id')
                    ->leftJoin('car_passenger_tags as t', 'p.passenger_tag', '=', 't.id')
                    ->where('c.id', $cls->id)
                    ->orderby('t.show_order', 'desc')
                    ->first();
                $cls->minPsg = is_null($passengerTag)? 0 : $passengerTag->min_passenger;
                $cls->maxPsg = is_null($passengerTag)? 0 : $passengerTag->max_passenger;

                $options = \DB::table('car_option_class as c')
                    ->leftJoin('car_option as o', 'c.option_id', '=', 'o.id')
                    ->where('c.class_id', $cls->id)
                    ->orderby('o.order')
                    ->get();
                $tmp = [];
                $tmp_en = [];
                foreach($options as $opt) {
                    $tmp[] = $opt->name;
                    $tmp_en[] = $opt->name_en;
                }
                $cls->option_names = implode(', ', $tmp);
                $cls->option_names_en = implode(', ', $tmp_en);
            }
        }

        $free_options = \DB::table('car_option as o')
            ->leftJoin('car_option_shop as s', 'o.id', '=', 's.option_id')
            ->where('s.shop_id', $depart_shop)
            ->where('o.type', 1)
            ->select('o.*')
            ->orderby('o.order', 'asc')
            ->get();

        $paid_options = \DB::table('car_option as o')
            ->leftJoin('car_option_shop as s', 'o.id', '=', 's.option_id')
            ->where('s.shop_id', $depart_shop)
            ->where('o.type', 0)
            ->where('o.google_column_number', '<', 200)
            ->select('o.*')
            ->orderby('o.order', 'asc')
            ->get();

        $data = [
            'shops'     => $shops,
            'categorys' => $categorys,
            'search'    => $search,
            'hours'     => $this->hours,
            'classes'   => $search_class,
            'psgtags'   => $passengerTags,
            'static_classes' => $static_classes,
            'free_options'   => $free_options,
            'paid_options'   => $paid_options,
            'request_page'  => $request_page
        ];

		$meta_info = Page::where('title','空車検索')->first();
		
        return View('pages.frontend.search')->with($data)->with('meta_info',$meta_info);
    }

    //search result
    public function search_classes(Request $request)
    {
        $input = $request->all();

        $locale = App::getLocale();
        if(Session::has('locale') == '' )
            Session::put('locale', $locale);
        config(['app.locale' =>  Session::get('locale')]);

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
        $passenger      = $request->input('passenger', '');
        $insurance      = 2; //$empty_request? '' : $request->get('insurance');
        if($request->has('options')) {
            if(is_array($input['options'])) {
                $options =  $input['options'];
            } else {
                $v =  $input['options'];
                $options = explode(',', $v);
            }

        } else {
            $options =  [];
        }

        $smoke          = $request->input('smoke', '');
        $pickup         = $request->input('pickup', '');
        $free_options   = $request->input('free_options', []);
        if($pickup != '') $free_options[] = $pickup;
        $search_condition = '';

        // get depart_shop name
        $depart_shop_name = '';
        if($depart_shop != ''){
            $shop1 = \DB::table('car_shop')->where('id','=', $depart_shop)->first();
            if(!empty($shop1)) $depart_shop_name = $shop1->name;
        }

        $search = (object)array();
        $search->depart_date = $depart_date;
        $search->depart_time = $depart_time;
        $search->return_date = $return_date;
        $search->return_time = $return_time;
        $search->depart_shop = $depart_shop;
        $search->depart_shop_name = $depart_shop_name;
        $search->return_shop = $return_shop;
        $search->car_category= $car_category;
        $search->passenger   = $passenger;
        $search->insurance   = $insurance;
        $search->smoke       = $smoke;
        $search->options     = implode(',', $options);
        $search->free_options   = implode(',', $free_options);
        $search->pickup      = $pickup;

        //get date diff(night_day)
        $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
        $night = $request_days['night'];
        $day = $request_days['day'];
        $search->rentdates = $day;

        // search start
        $allClasses = \DB::table('car_class as c')
            ->join('car_class_price as p', 'c.id', '=', 'p.class_id')
            ->select(['c.*', 'p.n1d2_day1 as price'])
            ->where('c.car_shop_name', $depart_shop)
            ->where('c.status', 1)
            ->where('c.static_flag', 0)
            ->orderBy('c.car_class_priority')->get();

        $search_class = [];  // array to contain filtered classes
        foreach($allClasses as $cls){

            if($passenger != 'all') {
                $psgRanges = \DB::table('car_class_passenger AS p')
                    ->leftJoin('car_passenger_tags AS t', 'p.passenger_tag', '=','t.id')
                    ->where('p.class_id','=',$cls->id)
                    ->where('t.min_passenger','<=',$passenger)
                    ->where('t.max_passenger','>=',$passenger)
                    ->orderBy('t.show_order')
                    ->count();

                if( $psgRanges == 0 ) continue;
            }

            if( !empty( $options )) {
                $class_options = \DB::table('car_option_class')->where('class_id',$cls->id )->get();

                //check if class has all options of search condition
                $count_option_in_search = 0;
                foreach( $class_options as $cop ) {
                    if( in_array($cop->option_id, $options) ) {
                        $count_option_in_search++;
                    }
                }
                if( $count_option_in_search < count($options) ) continue;
            }

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

            $models = $models->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id')
                            ->where('ci.delete_flag', 0)
                            ->where('ci.status', 1);
            if($passenger != 'all') {
                $models = $models->where('ci.max_passenger','>=', $passenger );
            } else {
                $models = $models->whereNotNull('ci.max_passenger' );
            }
            // check smoke
            if($smoke != '' && $smoke != 'both')
                $models = $models->where('ci.smoke', $smoke);
            // check shop
            if($depart_shop != '')
                $models = $models->where('ci.shop_id',$depart_shop);
            // check dropoff
            if($return_shop != '' && $return_shop != $depart_shop){
                $models = $models->leftJoin('car_inventory_dropoff as dr', 'ci.id', '=', 'dr.inventory_id')
                    ->where('dr.shop_id','=',$return_shop);
            }

            $cars = $models->select(['ci.*','cm.class_id', 'm.name as model_name', 'cm.model_id'])->get();

            if(empty($cars)) continue;  // if no car matching condition
            // check if car is available
            $count = 0;
            $mids = [];
            $mnames = [];
            foreach ($cars as $car) {
                $checkBook = ServerPath::getconfirmBooking($car->id, $depart_date, $return_date, $search_condition);     // if car usable, true else false
                $checkInspection = ServerPath::getConfirmInspection($car->id, $depart_date, $return_date,'', '');
                if($checkBook && $checkInspection){
                    $count++;
                    if(!array_search($car->model_id, $mids)){
                        array_push($mids, $car->model_id);
                        array_push($mnames, $car->model_name);
                    }
                }
            }
            if($count == 0) // if no car available
                continue;

//            $psgTags = \DB::table('car_class_passenger AS p')
//                ->leftJoin('car_passenger_tags AS t', 'p.passenger_tag', '=','t.id')
//                ->where('p.class_id','=', $cls->id)
//                ->orderBy('t.show_order')
//                ->get();

            $class = (object)array();
            $class->thum_path   = (trim($cls->thumb_path)!= '')? trim($cls->thumb_path) : '/images/blank.jpg';
            $class->id    = $cls->id;
            $class->price = $cls->price;
            $class->class_name  = $cls->name;
            $class->model_id    = $mids;
            $class->model_name  = $mnames;
            $class->depart_date = $depart_date;
            $class->depart_time = $depart_time;
            $class->return_date = $return_date;
            $class->return_time = $return_time;
            $class->message = "Test message";
            $class->car_count = $count;
            $class->priority = $cls->car_class_priority;
            $class->insurance = $tins;
            $class->smoke = $smoke;
            $class->night_day = $night . '泊' . $day . '日';
//            $class->passengerTags = $psgTags;

            //get price
            $class_price = ServerPath::getPriceFromClass($depart_date, $return_date, $cls->id, $night . "_" . $day, $depart_date, $return_date);
            $class->price = $class_price;

            //get option for search
            if(!empty($options))
                $select_options = $this->getOptionsFromClass($cls->id, $options);
            else
                $select_options = array();
            $class->options = $select_options;
            $option_price = 0;
            if (!empty($select_options)) {
                foreach ($select_options as $op) {
                    if($op->charge_system == 'one') {
                        $option_price += $op->price;
                    } else {
                        $option_price += $op->price * $day;
                     }
                    $op->number = 1;
                }
            }
            $class->option_price = $option_price;
            $class->free_options = $this->getOptionsFromClass($cls->id, $free_options);
            $class->shop_id = $depart_shop;
            $class->shop_name = $this->getShopName($depart_shop);
            if(empty($car_category)) $car_category = "";
            $class->category_id = $car_category;
            $class->category_name = $this->getCategoryName($car_category);

            //get insurance
//            $insurance_price = ServerPath::getInsurancePrice($insurance, $cls->id) * $day;
//            $class->insurance_price1 = $insurance_price;
            $insurance_price = ServerPath::getInsurancePrice2( $cls->id);
            $class->insurance_price1 = $insurance_price['ins1'] * $day;
            $class->insurance_price2 = $insurance_price['ins2'] * $day;
            $allPrice = $class_price + $option_price;
            $class->all_price = $allPrice;
//            $class->max_passengers = ServerPath::getClassMaxPassengers($cls->id, $depart_shop);
            $class->max_passengers = ServerPath::getClassAvailableMaxPassengers($cls->id, $depart_shop, $depart_date, $return_date);
            //get flag about returnshop
            array_push($search_class, $class);
        }

        $free_options = \DB::table('car_option as o')
            ->leftJoin('car_option_shop as s', 'o.id', '=', 's.option_id')
            ->where('s.shop_id', $depart_shop)
            ->where('o.type', 1)
            ->select('o.*')
            ->get();

        return view('partials.search_class_block', compact('search_class', 'search', 'free_options'));
    }

    public function class_search(Request $request) {

        $input = $request->all();
        $locale = App::getLocale();
        if(Session::has('locale') == '' )
            Session::put('locale', $locale);
        config(['app.locale' =>  Session::get('locale')]);
/*
_token class_id smoke depart_date depart_time return_date return_time depart_shop return_shop category passenger insurance option_list
*/
        $empty_request  = empty($request->get('_token'));
        if($empty_request) {
            echo \GuzzleHttp\json_encode(array('success'=>'false', 'error'=>'empty token'));
        } else {
            $class_id       = $request->get('class_id');
            $depart_date    = $request->get('depart_date');
            $depart_time    = $request->get('depart_time');
            $return_date    = $request->get('return_date');
            $return_time    = $request->get('return_time');
            $depart_shop    = $request->get('depart_shop');
            $return_shop    = $request->get('return_shop');
            $car_category   = $request->get('category');
            $passenger      = $request->get('passenger');
//            $insurance      = $request->get('insurance');
//            $options        = $request->get('option_list');
            $smoke          = $request->get('smoke');

            $start  = $depart_date." ".$depart_time.':00';
            $end    = $return_date." ".$return_time.':00';

            // search start
            $cls = \DB::table('car_class')->where('id', '=', $class_id)->first();

            // get class insurance
            $cis = \DB::table('car_class_insurance')->where('class_id',$class_id)->orderby('insurance_id','asc')->get();
            $tins = array_fill(0, 3, 0);
            foreach ($cis as $ci){
                $tins[$ci->insurance_id] = $ci->price;
            }
            $insurance_prices = [ $tins[0], $tins[1], $tins[1] + $tins[2] ];

            // get all models in this class
            $models = \DB::table('car_class_model as cm')
                ->leftJoin('car_model as m', 'm.id', '=', 'cm.model_id' )
                ->leftJoin('car_type as ct', 'm.type_id', '=', 'ct.id')
                ->where('cm.class_id', '=', $cls->id);
            // check category
            if($car_category != '') {
                $models = $models->where('ct.category_id','=',$car_category);
            }
            $models = $models->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id')
                            ->where('ci.delete_flag', 0)
                            ->where('ci.status', 1);
            // check seats
            if($passenger != 'all'){
                $models = $models->where('ci.max_passenger', '>=', $passenger);
            }
            // check smoke
            if($smoke != 'both') // smoke or none-smoke
                $models = $models->where('ci.smoke', $smoke);
            // check shop
            if($depart_shop != '')
                $models = $models->where('ci.shop_id',$depart_shop);
            // check dropoff
            if($return_shop != '' && $return_shop != $depart_shop){
                $models = $models->leftJoin('car_inventory_dropoff as dr', 'ci.id', '=', 'dr.inventory_id')
                    ->where('dr.shop_id','=',$return_shop);
            }

            $cars = $models->select(['ci.*','cm.class_id', 'm.name as model_name', 'cm.model_id'])->get();

            $count = 0;
            if(!empty($cars)){   // if exist cars matching condition
                foreach ($cars as $car) {
                    $checkBook = ServerPath::getconfirmBooking($car->id, $start, $end, '');     // car usable=>true else false
                    $checkInspection = ServerPath::getConfirmInspection($car->id, $start, $end,'', '');
                    if($checkBook && $checkInspection){
                        $count++;
                    }
                }
            }
            $class              = (object)array();
            $class->id          = $cls->id;
            $class->car_count   = $count;

            echo \GuzzleHttp\json_encode(array('success'=>'true', 'error'=>'', 'class'=>$class));
        }
    }

    public function get_class_passengers(Request $request) {
        if(empty($request->get('_token'))) {
            echo \GuzzleHttp\json_encode(array('success'=>'false', 'error'=>'empty token'));
        } else {
            $class_id = $request->get('class_id');
            $depart_date = $request->get('depart_date');
            $return_date = $request->get('return_date');
            $depart_shop = $request->get('depart_shop');
            $smoke = $request->get('smoke');

            $max_passengers = ServerPath::getClassMaxPassengers($class_id, $depart_shop);
            $tmp = [];
            foreach ($max_passengers as $key => $mp) {
                $cars = \DB::table('car_class_model as cm')
                    ->leftJoin('car_model as m', 'm.id', '=', 'cm.model_id')
                    ->leftJoin('car_type as ct', 'm.type_id', '=', 'ct.id')
                    ->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id')
                    ->select('ci.*')
                    ->where('cm.class_id', '=', $class_id)
                    ->where('ci.delete_flag', 0)
                    ->where('ci.status', 1);
                if($smoke !== 'both') $cars = $cars->where('ci.smoke', $smoke);
                $cars = $cars->where('ci.shop_id', $depart_shop)
                    ->where('ci.max_passenger', $mp->max_passenger)
                    ->get();
                $count = 0;
                foreach ($cars as $car) {
                    $usable = ServerPath::getconfirmBooking($car->id, $depart_date, $return_date, '');
                    $usable = $usable && ServerPath::getConfirmInspection($car->id, $depart_date, $return_date, '', '');
                    if ($usable) $count++;
                }
                $mp->count = $count;
                $tmp[] = $mp;
            }
            echo \GuzzleHttp\json_encode(array('success'=>'true', 'error'=>'', 'max_passengers'=>$tmp));
        }
    }

    // search confirm
    public function search_confirm(Request $request)
    {
		$no_sp_tel_icon = 1;
        $inputs    = $request->all();
        $class_id = $request->get('data_class_id');
        $models = \DB::table('car_class_model as cm')
            ->leftJoin('car_model as m', 'cm.model_id', '=', 'm.id')
            ->where('cm.class_id', $class_id)
            ->select('m.*', 'cm.class_id')
            ->get();
        foreach ($models as $model) {
            $category = \DB::table('car_type_category')->where('id', $model->category_id)->orderby('id','desc')->first();
            $model->category_name = !empty($category)? $category->name : '';
            $type = \DB::table('car_type')->where('id', $model->type_id)->orderby('id','desc')->first();
            if(empty($type))
                $model->type_name = "";
            else
                $model->type_name = $type->name;
            $vendor = \DB::table('car_vendor')->where('id', $model->vendor_id)->orderby('id','desc')->first();
            $model->vendor_name = $vendor->name;
			
            if($inputs["data_smoke"] != 'both'){
                $model->smoke_cars = \DB::table('car_inventory')
                    ->where('model_id',$model->id)
                    ->where('smoke', $inputs["data_smoke"])
                    ->where('delete_flag', 0)
                    ->where('status', 1)
                    ->count();
			}else{
				$model->smoke_cars = "both";
			}
        }
        // get max capacity of class
        $max_capacity = \DB::table('car_class_passenger AS p')
            ->leftJoin('car_passenger_tags AS t', 'p.passenger_tag','=','t.id')
            ->select('t.name','t.name_en')
            ->where('p.class_id', '=', $class_id)
            ->orderBy('t.show_order','DESC')
            ->first();
        $option_columns = array();
        $option_names   = array();
        $option_indexs = "";
        $option_abbriviation = "";
        $optionids  = explode("," ,$request->get('data_option_ids'));
        if(!empty($optionids)) {
            foreach ($optionids as $id) {
                $option = \DB::table('car_option')
                    ->where('id', $id)->first();
                $column_number  = 0;
                $option_name    = '';
                if(!empty($option)) {
                    $column_number = $option->google_column_number;
                    $option_name    = $option->abbriviation;
                }
                array_push($option_columns,$column_number);
                array_push($option_names,$option_name);
            }
            $option_indexs = implode(',', $option_columns);
            $option_abbriviation = implode(',', $option_names);
        }

        $capacity_str = is_null($max_capacity)? '' : $max_capacity->name;
        $capacity_str_en = is_null($max_capacity)? '' : $max_capacity->name_en;
        $class_name = $request->get('data_class_name');
        if($class_name == 'W3' || $class_name == 'CW3H')
            $capacity_str = '7人乗り';

        $data['models'] = $models;
        $data['data'] = [
            'depart_date'       => $request->get('data_depart_date')
            ,'depart_time'      => $request->get('data_depart_time')
            ,'return_date'      => $request->get('data_return_date')
            ,'return_time'      => $request->get('data_return_time')
            ,'depart_shop'      => $request->get('data_depart_shop')
            ,'depart_shop_name' => $request->get('data_depart_shop_name')
            ,'return_shop'      => $request->get('data_return_shop')
            ,'return_shop_name' => $request->get('data_return_shop_name')
            ,'car_category'     => $request->get('data_car_category')
            ,'passenger'        => $request->get('data_passenger')
            ,'insurance'        => $request->get('data_insurance')
            ,'insurance_price1'  => $request->get('data_insurance_price1')
            ,'insurance_price2'  => $request->get('data_insurance_price2')
            ,'smoke'            => $request->get('data_smoke')
//            ,'option_list'  => $request->get('data_option_list')
            ,'class_id'         => $request->get('data_class_id')
            ,'class_name'       => $class_name
            ,'class_category'   => $request->get('data_class_category')
            ,'car_photo'        => $request->get('data_car_photo')
            ,'rent_days'        => $request->get('data_rent_days')
            ,'price_rent'       => $request->get('data_price_rent')
            ,'option_ids'       => $request->get('data_option_ids')
            ,'option_names'     => $request->get('data_option_names')
            ,'option_numbers'   => $request->get('data_option_numbers')
            ,'option_costs'     => $request->get('data_option_costs')
            ,'option_prices'    => $request->get('data_option_prices')
            ,'option_indexs'    => $option_indexs
            ,'option_abbriviations'    => $option_abbriviation
            ,'price_all'        => $request->get('data_price_all')
            ,'max_capacity'     => $capacity_str
            ,'max_capacity_en'  => $capacity_str_en
            ,'pickup'           => $request->input('data_pickup','')
            ,'quick_booking'    => $request->get('data_quick_booking')
            ,'rent_dates'       => $request->get('data_rent_dates')
        ];
//dd($data['data']);
        return View('pages.frontend.search-confirm')->with($data)->with("no_sp_tel_icon",$no_sp_tel_icon);

    }

    // save booking from search_confirm
    public function search_save(Request $request) {
        $input = $request->all();
        // register user
        $new_user = $request->input('member_check');
        $user_email = $request->input('email');
        $user_phone = $request->input('phone');
        $user_first_name = $request->input('first_name');
        $user_last_name = $request->input('last_name');

        $user_pass  = $request->input('password');

        // echo bcrypt($user_pass);
        // exit;
        $lang = ServerPath::lang();

        if( $new_user == 0 ) {
            $validator = Validator::make($request->all(),
                [
                    'email' => 'required|email|max:255|unique:users',
//                    'name'  => 'required|unique:users'
                ],
                [
                    'email.required'    => trans('auth.emailRequired'),
                    'email.email'       => trans('auth.emailInvalid'),
                    'email.unique'      => trans('auth.emailUnique'),
//                    'name.unique'       => 'Your name already used'
                ]
            );

            if ($validator->fails()) {
//                return back()->withErrors($validator)->withInput();
                $error = array('success'=>false, 'errors'=>$validator->errors());
                return $error;
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
        } else {
            $user = \DB::table('users')
                    ->join('profiles', 'users.id', '=', 'profiles.user_id')
                    ->where('users.email',$user_email)
                    ->where('users.activated', 1)
                    ->first();
            // echo "<pre>";
            // print_r($user);
            // exit;
            if(is_null($user)){
//                return array('success'=>false, 'errors'=>['このメールアドレスでの登録はありません。']);
				if($lang == "ja"){
                return array('success'=>false, 'errors'=>['メールアドレスの登録が確認できません。']);
				}else if($lang == "en"){
                return array('success'=>false, 'errors'=>['Registration of this mail address can not be confirmed.']);
				}
            }

            if (!Hash::check($user_pass, $user->password)) {
				if($lang == "ja"){
                    return array('success'=>false, 'errors'=>['メールアドレスかパスワードに間違いがあります。']);
				}else if($lang == "en"){
                    return array('success'=>false, 'errors'=>['There is a mistake in your email address or password.']);
				}
            }
            $user_id = $user->user_id;
            $password = '';
        }

        $user = User::with('profile')->findOrFail($user_id);
        $user_phone = $user->profile->phone;
        $user_name = $user->last_name.$user->first_name;
        if($user_name == '')
        {
            $user_name = $user->profile->fur_last_name.$user->profile->fur_first_name;
        }
        $user_email = $user->email;
        session()->put('register_user_id', $user_id);

        // create booking
        $today = date('ymd'); // 2018-03-22 => 180322
        $todayLastBook = \DB::table('bookings')->whereRaw('DATE(created_at) = DATE(NOW())')->orderBy('id', 'desc')->first();

        if($todayLastBook == null){
            $booking_id = $today.'-0001';
        } else {
            $split = explode('-', $todayLastBook->booking_id);
            $booking_id = $today.'-'.str_pad(($split[1] + 1), 4, '0', STR_PAD_LEFT);
        }

        $depart_datetime = $request->input('depart_date').' '.$request->input('depart_time').':00';
        $return_datetime = $request->input('return_date').' '.$request->input('return_time').':00';
        $rentdays = $request->input('rent_days'); // 1泊 1日
        if($rentdays === ''){
            $rentdays = '0_0';
        } else {
            $rentdays = str_replace('日','', str_replace('泊', '_',$rentdays));
        }
        $rentcost = $request->input('price_rent');
        $oids = $request->input('option_ids');
        $onums = $request->input('option_numbers');
        $ocosts = $request->input('option_costs');
        $oprices = $request->input('option_prices');
        $rent_dates = intval($request->input('rent_dates'));
        $option_cost = 0;
        $arr_onums = explode(',', $onums);
        $arr_oids = explode(',', $oids);
        $arr_oprices = explode(',', $oprices);

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

        //pickup satus
        $option_indexs = explode(',',$request->input('option_indexs'));
        $wait_status_flag = false;
        $wait_status = 0;
        if(in_array('38', $option_indexs)) { //if selected smart driveoption
            $wait_status_flag = true;
            $wait_status = '3';
        }
        $depart_shop_id = $request->input('depart_shop');
        $smoke = $request->input('smoke');
        session()->put('smokeSelection', $smoke);
        // find car in car inventory
        // get cars from car_inventory with model ids
        $passengers = $request->input('passenger');
        if($passengers == 'all') $passengers = 0;

        $cars = \DB::table('car_class_model as c')
            ->leftjoin('car_model as m', 'm.id', '=', 'c.model_id')
            ->leftJoin('car_inventory as i', 'c.model_id','=','i.model_id')
            ->where('c.class_id', $request->input('class_id') );

        if($passengers != 0){
            $cars = $cars->where('i.max_passenger', '>=', $passengers);
        };

        $cars = $cars->where('i.shop_id', $depart_shop_id )
            ->where('i.delete_flag', 0)
            ->where('i.status', 1);
        if($smoke != 'both') {
            $cars = $cars->where('i.smoke', $request->input('smoke'));
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
            $cid = $car->car_id;
            if($cid == null || $cid == '') continue;
            $checkBook = ServerPath::getconfirmBooking($cid, $depart_datetime, $return_datetime, '');     // car usable=>true else false
            $checkInspection = ServerPath::getConfirmInspection($cid, $depart_datetime, $return_datetime,'', '');
            if($checkBook && $checkInspection){
                $inventory_id = $cid;
                $model_id = $car->model_id;
                break;
            }
        }
//        $price_all = $request->input('price_all')*1;
        $price_insurance1 = intval($request->input('insurance_price1'));
        $price_insurance2 = intval($request->input('insurance_price2'));

        $depart_shop_id = $request->input('depart_shop');
        $return_shop_id = $request->input('return_shop');
        $class_id = $request->input('class_id');
        $total_price = $rentcost + $price_insurance1 + $price_insurance2 + $option_cost;

        $pickup_id = \DB::table('car_option')->where('google_column_number', 101)->first();
        $pickup_id = !empty($pickup_id)? $pickup_id->id : 0;
        //pickup status
        if($wait_status_flag == false) {
            if($request->input('pickup') == '1') {
                $wait_status = '1' ;
            }
        }

        $pickup_yes = $request->input('pickup', '');
        $car_option = \DB::table('car_option')->where('id', $pickup_yes)->first();
        $free_options_category = '0';
        if(!empty($car_option)) {
            if($car_option->google_column_number == '101') $free_options_category = '1';
            if($car_option->google_column_number == '102') $free_options_category = '2';
        }
        $request_smoke = 0;
        if($smoke == 'both') $request_smoke = '2';
        else  $request_smoke = $smoke;
        if($inventory_id != 0 && $model_id != 0) {
            $insertbooking = \DB::table('bookings')->insertGetId(
                [
                    'admin_id'      => 0,
                    'booking_id'    => $booking_id,
                    'status'        => 1,   // $booking_status->status,
                    'client_id'     => $user_id,
                    'emergency_phone' => $user_phone,
                    'pickup_id'     => $depart_shop_id,
                    'dropoff_id'    => $return_shop_id,
                    'passengers'    => $passengers,
                    'request_smoke' => $request_smoke,
                    'inventory_id'  => $inventory_id,
                    'class_id'      => $class_id,
                    'model_id'      => $model_id,
                    'paid_options'  => implode(',', $tmp_id), //$oids,
                    'paid_option_numbers'  => implode(',', $tmp_num), //$onums,
                    'paid_options_price'  => implode(',', $tmp_price), //$oprices,
                    'option_price'  => $option_cost,
                    'departing'     => $depart_datetime,
                    'returning'     => $return_datetime,
                    'returning_updated' =>$return_datetime,
                    'rent_days'     => $rentdays,
                    'reservation_id'=> 1,   // via homepage
                    'subtotal'      => $rentcost,
                    'basic_price'   => $rentcost,
                    'tax'           => 0,
                    'payment'       => $total_price,
                    'insurance1'    => $price_insurance1,
                    'insurance2'    => $price_insurance2,
                    'free_options'  => $pickup_yes, //? $pickup_id : '',
                    'free_options_category' => $free_options_category,
                    'wait_status'   => $wait_status,
                    'portal_flag'   => 0,
                    'portal_id'     => 10000,
                    'language'      => App::getLocale(),
                ]
            );

            session()->put('booking_id', $insertbooking);
            session()->put('register_user_id', $user_id);
            session()->put('confirm-submit', true);
            session()->put('order_number', $insertbooking.'-'.$user_id);       // for affiliate tag

            $book = \DB::table('bookings')->where('id', $insertbooking)->first();

            $lang = ServerPath::lang();
            $booking_submit_time = $lang == 'ja' ? date('Y年m月d日 H時i分', strtotime($book->created_at)):date('Y/m/d H:i', strtotime($book->created_at));

            //Don't delete
            // data for notification
            $depart_shop = Shop::findOrFail($depart_shop_id);
            $car_model = CarModel::findOrFail($model_id);
            $insurance = $request->input('insurance');
            if($insurance == '0') {
                $insurance_part_ja = '';
                $insurance_type_ja = 'なし';
            } elseif($insurance == '1') {
                $insurance_part_ja = '免責補償：'.$price_insurance1.'円';
                $insurance_type_ja = '免責補償';
            } else {
                $insurance_part_ja = '免責補償：'.$price_insurance1.'円<br>ワイド免責補償：'.$price_insurance2.'円';
                $insurance_type_ja = '免責補償/ワイド免責補償';
            }
            if($lang == 'ja') {
                if($insurance == '0') {
                    $insurance_part = '';
                    $insurance_type = 'なし';
                } elseif($insurance == '1') {
                    $insurance_part = '免責補償：'.$price_insurance1.'円';
                    $insurance_type = '免責補償';
                } else {
                    $insurance_part = '免責補償：'.$price_insurance1.'円<br>ワイド免責補償：'.$price_insurance2.'円';
                    $insurance_type = '免責補償/ワイド免責補償';
                }
            } else {
                if($insurance == '0') {
                    $insurance_part = '';
                    $insurance_type = 'none';
                } elseif($insurance == 1) {
                    $insurance_part = 'Exemption of Liability Compensation：'.$price_insurance1.'yen';
                    $insurance_type = 'Exemption of Liability Compensation';
                } else {
                    $insurance_part = 'Exemption of Liability Compensation：'.$price_insurance1.'yen<br>Wide Protection Package：'.$price_insurance2.'yen';
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

            session()->put('booking_class_name', $class->name);
            session()->put('booking_class_id', $class_id);
            session()->put('booking_price', $total_price);

            $option_ids = explode(',', $oids);
            $option_numbers = explode(',', $onums);
            $option_names = []; $option_names_ja = [];
            $option_prices = []; $option_prices_ja = [];
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
                        array_push($option_names_ja, $option->name . '(' . $opt_num . ')');
                        if($lang == 'ja') {
                            array_push($option_names, $option->name . '(' . $opt_num . ')');
                            if ($option->charge_system == 'one') {
                                array_push($option_prices, $option->name . ' ' . $option->price . '円' . ' x ' . $opt_num . '個');
                                array_push($option_prices_ja, $option->name . ' ' . $option->price . '円' . ' x ' . $opt_num . '個');
                            } else {
                                array_push($option_prices, $option->name . ' ' . $option->price . '円' . ' x ' . $rent_dates . '日');
                                array_push($option_prices_ja, $option->name . ' ' . $option->price . '円' . ' x ' . $rent_dates . '日');
                            }
                        } else {
                            array_push($option_names, $option->name_en . '(' . $opt_num . ')');
                            if ($option->charge_system == 'one') {
                                array_push($option_prices, $option->name_en . ' ' . $option->price . 'yen' . ' x ' . $opt_num . '');
                                array_push($option_prices_ja, $option->name . ' ' . $option->price . '円' . ' x ' . $opt_num . '個');
                            } else {
                                array_push($option_prices, $option->name_en . ' ' . $option->price . 'yen' . ' x ' . $rent_dates . ' days');
                                array_push($option_prices_ja, $option->name . ' ' . $option->price . '円' . ' x ' . $rent_dates . '日');
                            }
                        }
                    }
                }
            }

            session()->put('booking_options', implode(',', $option_names));

            if( $pickup_yes != '' ) {
                $fop = \DB::table('car_option')->where('id', $pickup_yes)->first();
                array_push($option_names_ja, '無料' . $fop->name);
                array_push($option_prices_ja, '無料' . $fop->name . ' 0円');
                if($lang == 'ja') {
                    array_push($option_names, '無料' . $fop->name);
                    array_push($option_prices, '無料' . $fop->name . ' 0円');
                } else {
                    array_push($option_names, 'free'.$fop->name_en);
                    array_push($option_prices, 'free'.$fop->name_en.' 0yen');
                }
            }
            if(empty($option_names))
            {
                array_push($option_names, $lang=='ja'? 'なし':'none');
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

            if( $new_user == 0 ) {
                if(ServerPath::lang() == 'ja')
                    $template = \DB::table('mail_templates')->where('mailname', 'user_register_book_user')->first();
                else
                    $template = \DB::table('mail_templates')->where('mailname', 'user_register_book_user_en')->first();
                if(!empty($template)) {
                    $subject = $template->subject;
                    $message = $template->content;
                    $message = str_replace('{user_name}', $user_name, $message);
                    $message = str_replace('{login_url}', '<a href="'.url('/').'/login'.'" target="_blank">'.url('/').'/login'.'</a>', $message);
                    $message = str_replace('{user_email_address}', $user_email, $message);
                    $message = str_replace('{user_password}', $password, $message);
                    $message = str_replace('{quick_start_url}', '', $message);
                    $message = str_replace('{booking_id}', $booking_id, $message);
                    $message = str_replace('{shop_name}', $depart_shop->name, $message);
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
                    $data1 = array('content' => $content, 'subject' => $subject, 'fromname' =>$template->sender,  'email' => $user_email);
                    $data[] = $data1;
                }
                // Don't delete
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
                $route = '';
                if(App::getLocale() != 'ja') $route = strtoupper(App::getLocale());
                $subject = str_replace('{route}', $route, $subject);
                $subject = str_replace('{new_repeat}', ($new_user == 0)? '新規':'リピーター', $subject);
                $subject = str_replace('{shop_name}', $depart_shop->name, $subject);
                $subject = str_replace('{class_name}', $class->name, $subject);
                $subject = str_replace('{rent_days}', $request->input('rent_days'), $subject);

                $message = $template->content;
                $message = str_replace('{user_name}', $user_name, $message);
                $message = str_replace('{booking_id}', $booking_id, $message);
                $message = str_replace('{booking_submit_time}', $booking_submit_time, $message);
                $message = str_replace('{user_email_address}', $user_email, $message);
//              $message = str_replace('{user_password}', $password, $message);
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
                        'business@motocle.com',
//                        'future.syg1118@gmail.com'
                        ];
                } else {
                    // for hakoren staffs
                    $mail_addresses = [
//                        'sinchong1989@gmail.com',
                        'reservation-f@hakoren.com',
                        'reservation-o@hakoren.com',
                        'info@hakoren.com',
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
//                var_dump($data); return;

             try {
                 $ch = array();
                 $mh = curl_multi_init();
                 $ch[0] = curl_init();

                 // set URL and other appropriate options
                 $domain = 'http://motocle7.sakura.ne.jp/projects/rentcar/hakoren/public';
                 curl_setopt($ch[0], CURLOPT_URL, url('/') . "/mail/vaccine/medkenmail.php");
                 //  curl_setopt($ch[0], CURLOPT_URL, $protocol . $domain . "/mail/vaccine/medkenmail.php");
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
//                      add this line
                     while (curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM) ;

                     if (curl_multi_select($mh) != -1) {
                         do {
                             $mrc = curl_multi_exec($mh, $active);
                             if ($mrc == CURLM_OK) {
                             }
                         } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                     }
                 }
//                 close the handles
                 curl_multi_remove_handle($mh, $ch[0]);
                 curl_multi_close($mh);
             } catch (Exception $e) {
             }


            return array('success'=>true, 'error'=>'', 'password'=>$password);
        } else { // when there is no car to allocate
            $error = array('success'=>false, 'errors'=>['条件に合う車両は見つかりませんでした。','同じ日付で別のお車を検索させてください！']);
            return $error;
        }

//            $booking_status = \DB::table('booking_status')->where('status','1')->first();// status is submit

        // Add register_user_id in session, so that it will easily available for quick start steps
//            return redirect('/thankyou')->with('success', trans('usersmanagement.createSuccess'));
    }

    // save booking from search_confirm
    public function book_campaign(Request $request) {
        $input = $request->all();
//        var_dump($input); return;
        // register user
        $new_user = $request->input('member_check');
        $user_email = $request->input('email');
        $user_phone = $request->input('phone');
        $user_first_name = $request->input('first_name');
        $user_last_name = $request->input('last_name');

        $user_pass  = $request->input('password');

        if( $new_user == 0 ) {
            $validator = Validator::make($request->all(),
                [
                    'email' => 'required|email|max:255|unique:users',
                ],
                [
                    'email.required'    => trans('auth.emailRequired'),
                    'email.email'       => trans('auth.emailInvalid'),
                    'email.unique'      => trans('auth.emailUnique'),
                ]
            );

            if ($validator->fails()) {
                $error = array('success'=>false, 'errors'=>$validator->errors());
                return $error;
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
        } else {
            $user = \DB::table('users')
                    ->join('profiles', 'users.id', '=', 'profiles.user_id')
                    ->where('users.email',$user_email)
                    ->where('users.activated', 1)
                    ->first();

				$lang = ServerPath::lang();
            if(is_null($user)){
				if($lang == "ja"){
                return array('success'=>false, 'errors'=>['メールアドレスの登録が確認できません。']);
				}else if($lang == "en"){
                return array('success'=>false, 'errors'=>['Registration of this mail address can not be confirmed.']);
				}
            }

            if (!Hash::check($user_pass, $user->password)) {
				if($lang == "ja"){
                return array('success'=>false, 'errors'=>['メールアドレスかパスワードに間違いがあります。']);
				}else if($lang == "en"){
                return array('success'=>false, 'errors'=>['There is a mistake in your email address or password.']);
				}
            }
            $user_id = $user->user_id;
            $password = '';
        }

        $user = User::with('profile')->findOrFail($user_id);
        $user_phone = $user->profile->phone;
        $user_name = $user->last_name.$user->first_name;
        if($user_name == '')
        {
            $user_name = $user->profile->fur_last_name.$user->profile->fur_first_name;
        }
        $user_email = $user->email;
        session()->put('register_user_id', $user_id);

        // create booking
        $today = date('ymd'); // 2018-03-22 => 180322
        $todayLastBook = \DB::table('bookings')->whereRaw('DATE(created_at) = DATE(NOW())')->orderBy('id', 'desc')->first();

        if($todayLastBook == null){
            $booking_id = $today.'-0001';
        } else {
            $split = explode('-', $todayLastBook->booking_id);
            $booking_id = $today.'-'.str_pad(($split[1] + 1), 4, '0', STR_PAD_LEFT);
        }

        $inventory_id = $request->input('inventory_id',0);
        $depart_datetime = $request->input('depart_date').' '.$request->input('depart_time').':00';
        $return_datetime = $request->input('return_date').' '.$request->input('return_time').':00';
        $rentdates = $request->input('rent_dates'); //
        $rentdays = ($rentdates-1).'_'.$rentdates;
        $rentcost = $request->input('price_rent');
        $oids = $request->input('option_ids',[]);
        $onums = $request->input('option_numbers',[]);
        $ocosts = $request->input('option_costs',[]);
        $rent_dates = intval($request->input('rent_dates'));

        $option_price = 0;
        foreach($onums as $key=>$on) {
            $pop = \DB::table('car_option')->where('id', $oids[$key])->first();
            if($pop->charge_system == 'one')
                $option_price += $on * $ocosts[$key];
            else {
                $option_price += $on * $ocosts[$key] * $rent_dates;
                $ocosts[$key] = $ocosts[$key] * $rent_dates;
            }
        }

        //pickup satus
        $option_indexs = $request->input('option_indexs',[]);
        $wait_status_flag = false;
        $wait_status = 0;
        if(in_array('38', $option_indexs)) { //if selected smart driveoption
            $wait_status_flag = true;
            $wait_status = '3';
        }
        $depart_shop_id = $request->input('depart_shop');
        $smoke = $request->input('smoke');
        session()->put('smokeSelection', $smoke);
        // find car in car inventory
        // get cars from car_inventory with model ids
        $passengers = $request->input('passenger');
//        if($passengers == 'all') $passengers = 0;

        $ins_type = $request->input('insurance');

        $price_insurance1 = intval($request->input('insurance_price1') * $rent_dates);
        $price_insurance2 = intval($request->input('insurance_price2') * $rent_dates);
        if($ins_type == '0') {
            $price_insurance1 = 0; $price_insurance2 = 0;
        } elseif($ins_type == '1') {
            $price_insurance2 = 0;
        }

        $depart_shop_id = $request->input('depart_shop');
        $return_shop_id = $request->input('return_shop');
        $class_id = $request->input('class_id');
        $model_id = $request->input('model_id');
        $total_price = $rentcost + $price_insurance1 + $price_insurance2 + $option_price;

        $pickup_id = \DB::table('car_option')->where('google_column_number', 101)->first();
        $pickup_id = !empty($pickup_id)? $pickup_id->id : 0;
        //pickup status
        if($wait_status_flag == false) {
            if($request->input('pickup') !== '') {
                $wait_status = '1' ;
            }
        }

        $pickup_yes = $request->input('pickup', '');
        $car_option = \DB::table('car_option')->where('id', $pickup_yes)->first();
        $free_options_category = '0';
        if(!empty($car_option)) {
            if($car_option->google_column_number == '101') $free_options_category = '1';
            if($car_option->google_column_number == '102') $free_options_category = '2';
        }
        $request_smoke = 0;
        if($smoke == 'both') $request_smoke = '2';
        else  $request_smoke = $smoke;
        if($inventory_id != 0 && $model_id != 0) {
            $insertbooking = \DB::table('bookings')->insertGetId(
                [
                    'admin_id'      => 0,
                    'booking_id'    => $booking_id,
                    'status'        => 1,   // $booking_status->status,
                    'client_id'     => $user_id,
                    'emergency_phone' => $user_phone,
                    'pickup_id'     => $depart_shop_id,
                    'dropoff_id'    => $return_shop_id,
                    'passengers'    => $passengers,
                    'request_smoke' => $request_smoke,
                    'inventory_id'  => $inventory_id,
                    'class_id'      => $class_id,
                    'model_id'      => $model_id,
                    'paid_options'  => implode(',', $oids),
                    'paid_option_numbers'  => implode(',', $onums),
                    'paid_options_price'  => implode(',', $ocosts),
                    'option_price'  => $option_price,
                    'departing'     => $depart_datetime,
                    'returning'     => $return_datetime,
                    'returning_updated' =>$return_datetime,
                    'rent_days'     => $rentdays,
                    'reservation_id'=> 1,   // via homepage
                    'subtotal'      => $rentcost,
                    'basic_price'   => $rentcost,
                    'tax'           => 0,
                    'payment'       => $total_price,
                    'insurance1'    => $price_insurance1,
                    'insurance2'    => $price_insurance2,
                    'free_options'  => $pickup_yes,
                    'free_options_category' => $free_options_category,
                    'wait_status'   => $wait_status,
                    'portal_flag'   => 0,
                    'portal_id'     => 10000,
                    'language'      => App::getLocale(),
                    'admin_memo'    => 'キャンペーン予約',
                ]
            );

            session()->put('booking_id', $insertbooking);
            session()->put('register_user_id', $user_id);
            session()->put('confirm-submit', true);
            session()->put('order_number', $insertbooking.'-'.$user_id);       // for affiliate tag

            $book = \DB::table('bookings')->where('id', $insertbooking)->first();
            $booking_submit_time = date('Y年m月d日 H時i分', strtotime($book->created_at));

            //Don't delete
            // data for notification
            $depart_shop = Shop::findOrFail($depart_shop_id);
            $car_model = CarModel::findOrFail($model_id);
            $insurance = $request->input('insurance');
            if($insurance == '0') {
                $insurance_part = '';
                $insurance_type = 'なし';
            } elseif($insurance == 1) {
                $insurance_part = '免責補償：'.$price_insurance1.'円';
                $insurance_type = '免責補償';
            } else {
                $insurance_part = '免責補償：'.$price_insurance1.'円<br>ワイド免責補償：'.$price_insurance2.'円';
                $insurance_type = '免責補償/ワイド免責補償';
            }
            $car = CarInventory::find($book->inventory_id);
            if($car->smoke == 1) {
                $smoke = '喫煙';
            } else {
                $smoke = '禁煙';
            }
            $class = \DB::table('car_class as c')
                ->leftJoin('car_class_passenger as p', 'c.id', '=', 'p.class_id')
                ->leftJoin('car_passenger_tags as t', 'p.passenger_tag', '=', 't.id')
                ->select(['c.*','t.name as tagname','p.passenger_tag'])
                ->where('c.id','=', $class_id)
                ->orderBy('t.show_order', 'desc')->first();

            session()->put('booking_class_name', $class->name);
            session()->put('booking_class_id', $class_id);
            session()->put('booking_price', $total_price);

            $option_ids = $oids;
            $option_numbers = $onums;
            $option_names = [];
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
//                            チャイルドシート(1)
                        array_push($option_names, $option->name.'('.$opt_num.')');
//                            childseat 540円 x 1個
                        if($option->charge_system == 'one') {
                            array_push($option_prices, $option->name.' '.$option->price.'円'.' x '.$opt_num.'個');
                        } else {
//                            array_push($option_prices, $option->name.' '.$option->price * intval($rent_dates).'円'.' x '.$opt_num.'個');
                            array_push($option_prices, $option->name.' '.$option->price.'円'.' x '. $rent_dates.'日');
                        }
                    }
                }
            }

            session()->put('booking_options', implode(',', $option_names));

            if( $pickup_yes != '' ) {
                $fop = \DB::table('car_option')->where('id', $pickup_yes)->first();
                array_push($option_names, '無料'.$fop->name);
                array_push($option_prices, '無料'.$fop->name.' 0円');
            }
            if(empty($option_names))
            {
                array_push($option_names, 'なし');
            }

            // send notification to registered user and admin
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            //$protocol = "https://";
            $domain = $_SERVER['HTTP_HOST'];
            $data = array();

            if( $new_user == 0 ) {
                $template = \DB::table('mail_templates')->where('mailname', 'user_register_book_user')->first();
                if(!empty($template)) {
                    $subject = $template->subject;
                    $message = $template->content;
                    $message = str_replace('{user_name}', $user_name, $message);
                    $message = str_replace('{login_url}', '<a href="'.url('/').'/login'.'" target="_blank">'.url('/').'/login'.'</a>', $message);
                    $message = str_replace('{user_email_address}', $user_email, $message);
                    $message = str_replace('{user_password}', $password, $message);
                    $message = str_replace('{quick_start_url}', '', $message);
                    $message = str_replace('{booking_id}', $booking_id, $message);
                    $message = str_replace('{shop_name}', $depart_shop->name, $message);
                    $message = str_replace('{shop_phone}', $depart_shop->phone, $message);
                    $message = str_replace('{car_model_name}', $car_model->name, $message);
                    $message = str_replace('{car_short_name}', $car->shortname, $message);
                    $message = str_replace('{car_capacity}', $class->tagname, $message);
                    $message = str_replace('{smoke}', $smoke, $message);
                    $message = str_replace('{insurance_type}', $insurance_type, $message);
                    $message = str_replace('{options}', implode(', ',$option_names), $message);
                    $message = str_replace('{departing_time}', date_format(date_create($depart_datetime),'Y年m月d日 H時i分'), $message);
                    $message = str_replace('{returning_time}', date_format(date_create($return_datetime),'Y年m月d日 H時i分'), $message);
                    $message = str_replace('{base_price}', $rentcost, $message);
                    $message = str_replace('{insurance_part}', $insurance_part, $message);
                    $message = str_replace('{option_price}', $option_price, $message);
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
            else // already registered user
            {
                $template = \DB::table('mail_templates')->where('mailname', 'member_book_confirm_user')->first();
                if(!empty($template)) {
                    $subject = $template->subject;
                    $message = $template->content;
                    $message = str_replace('{user_name}', $user_name, $message);
                    $message = str_replace('{login_url}', '<a href="'.url('/').'/login'.'" target="_blank">'.url('/').'/login'.'</a>', $message);
                    $shop_url = url('/shop/'.$depart_shop->slug);
                    $message = str_replace('{shop_url}', '<a href="'.$shop_url.'" target="_blank">'.$shop_url.'</a>', $message);
                    $message = str_replace('{quick_start_url}', '', $message);
                    $message = str_replace('{booking_id}', $booking_id, $message);
                    $message = str_replace('{shop_name}', $depart_shop->name, $message);
                    $message = str_replace('{car_model_name}', $car_model->name, $message);
                    $message = str_replace('{car_short_name}', $car->shortname, $message);
                    $message = str_replace('{car_capacity}', $class->tagname, $message);
                    $message = str_replace('{smoke}', $smoke, $message);
                    $message = str_replace('{insurance_type}', $insurance_type, $message);
                    $message = str_replace('{options}', implode(', ',$option_names), $message);
                    $message = str_replace('{departing_time}', date_format(date_create($depart_datetime),'Y年m月d日 H時i分'), $message);
                    $message = str_replace('{returning_time}', date_format(date_create($return_datetime),'Y年m月d日 H時i分'), $message);
                    $message = str_replace('{base_price}', $rentcost, $message);
                    $message = str_replace('{insurance_part}', $insurance_part, $message);
                    $message = str_replace('{option_price}', $option_price, $message);
                    $message = str_replace('{option_detail}', implode(', ', $option_prices), $message);
                    $message = str_replace('{total_price}', $total_price, $message);
                    $content = $message;
                    if(strpos($_SERVER['HTTP_HOST'], 'hakoren') >= 0){
                        $data1 = array('content' => $content, 'subject' => $subject, 'fromname' =>$template->sender,  'email' => 'bluexie0455@gmail.com');
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
                $route = 'Ca';
                if(App::getLocale() != 'ja')
                    $route = $route.' '.strtoupper(App::getLocale());
                $subject = str_replace('{route}', $route, $subject);
                $subject = str_replace('{new_repeat}', ($new_user == 0)? '新規':'リピーター', $subject);
                $subject = str_replace('{shop_name}', $depart_shop->name, $subject);
                $subject = str_replace('{class_name}', $class->name, $subject);
                $subject = str_replace('{rent_days}', ($rentdates-1).'泊'.$rentdates.'日', $subject);

                $message = $template->content;
                $message = str_replace('{user_name}', $user_name, $message);
                $message = str_replace('{booking_id}', $booking_id, $message);
                $message = str_replace('{booking_submit_time}', $booking_submit_time, $message);
                $message = str_replace('{user_email_address}', $user_email, $message);
//              $message = str_replace('{user_password}', $password, $message);
                $message = str_replace('{user_phone}', $user_phone, $message);
                $message = str_replace('{shop_name}', $depart_shop->name, $message);
                $message = str_replace('{car_model_name}', $car_model->name, $message);
                $message = str_replace('{car_short_name}', $car->shortname, $message);
                $message = str_replace('{car_capacity}', $class->tagname, $message);
                $message = str_replace('{smoke}', $smoke, $message);
                $message = str_replace('{option_items}', implode(', ',$option_names), $message);
                $message = str_replace('{insurance_type}', $insurance_type, $message);
                $message = str_replace('{departing_time}', date_format(date_create($depart_datetime),'Y年m月d日 H時i分'), $message);
                $message = str_replace('{returning_time}', date_format(date_create($return_datetime),'Y年m月d日 H時i分'), $message);
                $message = str_replace('{total_price}', $total_price, $message);
                $message = str_replace('{car_class_name}', $class->name, $message);
                $message = str_replace('{car_plate_number}', $car->numberplate4, $message);
                $content = $message;

                if(strpos($_SERVER['HTTP_HOST'], 'hakoren') === false){
                    // for motocle8 test
                    $mail_addresses = [
                        'sinchong1989@gmail.com',
                        'business@motocle.com',
                        'future.syg1118@gmail.com'
                        ];
                } else {
                    // for hakoren staffs
                    $mail_addresses = [
//                        'sinchong1989@gmail.com',
                        'reservation-f@hakoren.com',
                        'reservation-o@hakoren.com',
                        'info@hakoren.com',
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
//                var_dump($data); return;

            try {
                 $ch = array();
                 $mh = curl_multi_init();
                 $ch[0] = curl_init();

                 // set URL and other appropriate options
                 $domain = 'http://motocle7.sakura.ne.jp/projects/rentcar/hakoren/public';
                 curl_setopt($ch[0], CURLOPT_URL, url('/') . "/mail/vaccine/medkenmail.php");
                 //  curl_setopt($ch[0], CURLOPT_URL, $protocol . $domain . "/mail/vaccine/medkenmail.php");
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
//                      add this line
                     while (curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM) ;

                     if (curl_multi_select($mh) != -1) {
                         do {
                             $mrc = curl_multi_exec($mh, $active);
                             if ($mrc == CURLM_OK) {
                             }
                         } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                     }
                 }
//                 close the handles
                 curl_multi_remove_handle($mh, $ch[0]);
                 curl_multi_close($mh);
             } catch (Exception $e) {
             }

            return array('success'=>true, 'error'=>'', 'camp_id'=>$request->get('camp_id'), 'password'=>$password);
        } else { // when there is no car to allocate
            $error = array('success'=>false, 'errors'=>['条件に合う車両は見つかりませんでした。','同じ日付で別のお車を検索させてください！']);
            return $error;
        }

//            $booking_status = \DB::table('booking_status')->where('status','1')->first();// status is submit

        // Add register_user_id in session, so that it will easily available for quick start steps
//            return redirect('/thankyou')->with('success', trans('usersmanagement.createSuccess'));
    }

    //carclass detail view
    public function carclass_detail(Request $request, $class_id) {
        if($request->exists('depart_date')) {
            $depart_date = $request->get('depart_date');
        } else {
            if(time() >= strtotime(date('Y-m-d 18:30:00'))) {
                $depart_date = date('Y-m-d', strtotime('tomorrow'));
            } else {
                $depart_date = date('Y-m-d');
            }
        }

        $depart_time    = $request->input('depart_time', '09:00');
        $return_date    = $request->input('return_date', date('Y-m-d', strtotime('tomorrow')));
        $return_time    = $request->input('return_time', '09:00');
        $car_number     = $request->input('car_number', 1);
        $paid_opt_ids   = $request->input('paid_options', []);
        $free_opt_ids   = $request->input('free_options', []);
        $paid_opt_nums  = $request->input('paid_option_numbers', []);
        $free_opt_nums  = $request->input('free_option_numbers', []);
        $smoking        = $request->input('smoking', 'both');
        $insurance      = '2'; // $request->input('insurance', '2');
        $search_cond    = $request->input('search_cond', '0');
        $real_passenger = $request->input('max_passenger', 0);

        $option_columns = [];
        foreach ( $paid_opt_ids as $key=>$opi ) {
            $paid_opt_ids[$key] = intval($opi);
            $vop = \DB::table('car_option')->where('id', $opi)->first();
            $option_columns[] = $vop->google_column_number;
        }

        $vclass = CarClass::findOrFail($class_id);
        if(!$vclass) {
            return back()->withErrors(['no-class'=>'Can not find class']);
        }
        $depart_shop = $vclass->car_shop_name;
        $return_shop = $depart_shop;

        // remove 0 numbers from option number arrays
        $tmp = [];
        foreach ( $paid_opt_nums as $opn ) {
            if($opn != '0') array_push($tmp, intval($opn));
        }
        $paid_opt_nums = $tmp;
        $tmp = [];
        foreach ( $free_opt_nums as $opn ) {
            if($opn != '0') array_push($tmp, intval($opn));
        }
        $free_opt_nums = $tmp;

        $class = CarClass::where('id', $class_id)->first();
        if(empty($class_id)) $class_id = $request->get('class_id');
        $models = \DB::table('car_class_model as ccm')
            ->leftJoin('car_model as m', 'ccm.model_id', '=', 'm.id')
            ->where('ccm.class_id', $class_id)->select(['m.*'])->get();
        $class->models = $models;
        $class_options = \DB::table('car_option_thumb as cot')
                ->leftJoin('car_option_class as coc', 'coc.option_id', '=', 'cot.option_id')
                ->where('coc.class_id',$class_id)
                ->select(['cot.thumb_path','cot.option_id'])
                ->get();
        $class->options = $class_options;
        $class->insurance = ServerPath::getInsurancePrice2($class_id);
        $passenger = \DB::table('car_class_passenger as ccp')
            ->leftJoin('car_passenger_tags as p', 'ccp.passenger_tag', '=', 'p.id')
            ->where('ccp.class_id', $class_id)->orderby('max_passenger','desc')->first();
        if(empty($passenger)){
            $passenger = (object) array() ;
            $passenger->min_passenger = 0 ;
            $passenger->max_passenger = 0 ;
        }

        $max_passenger = $passenger->max_passenger ;
        $min_passenger = $passenger->min_passenger ;

        $class->passenger = $passenger;
        $thumbnails = \DB::table('car_class_thumb')
            ->where('class_id', $class_id)->select(['*'])->get();
        if(!empty($thumbnails)) {
            $class->thumbnails = $thumbnails;
        }
        $basic_price = ServerPath::getPriceOneDay($class_id);
        if(empty($basic_price)) $basic_price = 0;
        $class->basic_price = $basic_price;

        $pick = \DB::table('car_shop')->where('id', $depart_shop)->first();
        if(!is_null($pick))  $pickup_name = $pick->name;
        else $pickup_name = '';

        $drop = \DB::table('car_shop')->where('id', $return_shop)->first();
        if(!is_null($drop)) $dropoff_name = $drop->name;
        else $dropoff_name = '';

        $equipments = \DB::table('car_class_equipment as ce')
            ->leftjoin('car_equip as e', 'ce.equipment_id', 'e.id')
            ->where('ce.class_id', $class_id)->get();

        $shops      = \DB::table('car_shop')->orderby('id', 'asc')->get();
        $all_paid_options    = \DB::table('car_option_class as s')
            ->leftJoin('car_option as o', 'o.id', '=', 's.option_id')
            ->where('s.class_id', $class_id)
            ->where('o.type', 0)
            ->where('o.google_column_number', '<', 200)
            ->select(['o.*'])
            ->orderby('order', 'asc')->get();
        $all_free_options    = \DB::table('car_option_class as s')
            ->leftJoin('car_option as o', 'o.id', '=', 's.option_id')
            ->where('s.class_id', $class_id)
            ->where('o.type', 1)
            ->select(['o.*'])
            ->orderby('order', 'asc')->get();

        // count smokes and non-smokes
        $smoke_cars = \DB::table('car_class_model as cm')
            ->leftJoin('car_model as m', 'cm.model_id','=', 'm.id')
            ->leftJoin('car_inventory as i', 'm.id','=','i.model_id')
            ->where('cm.class_id', $class_id)
            ->where('i.delete_flag', 0)
            ->where('i.status', 1)
            ->where('i.smoke', 1)->count();
        $nonsmoke_cars = \DB::table('car_class_model as cm')
            ->leftJoin('car_model as m', 'cm.model_id','=', 'm.id')
            ->leftJoin('car_inventory as i', 'm.id','=','i.model_id')
            ->where('cm.class_id', $class_id)
            ->where('i.delete_flag', 0)
            ->where('i.status', 1)
            ->where('i.smoke', 0)->count();

        /////////  suggested class section /////////////
        $tmp = \DB::table('car_class_suggests as s')
            ->leftjoin('car_class as c', 's.suggest_class_id','=','c.id')
            ->where('s.class_id', $class_id)
            ->orderby('c.car_class_priority')
            ->select(['s.suggest_class_id','c.car_class_priority'])
            ->get();

        $allClasses = [CarClass::find($class_id)];
        if(count($tmp) > 0) {
            foreach ($tmp as $t){
                $vc = CarClass::find($t->suggest_class_id);
                if(!is_null($vc))
                    array_push($allClasses, $vc);
            }
        }

        $search_class = [];  // array to contain filtered classes
        $suggest_class = []; // suggest class high price than this class
        $virtual_allprice = [];
        $suggest_price = 0;

        foreach($allClasses as $cls){
            $book_passenger = \DB::table('car_class_passenger as ccp')
                ->leftJoin('car_passenger_tags as p', 'ccp.passenger_tag', '=', 'p.id')
                ->where('ccp.class_id', $cls->id)
                ->orderby('max_passenger', 'desc')
                ->first();
            if (empty($book_passenger)) {
                $book_passenger = (object)array();
                $book_passenger->min_passenger = 0;
                $book_passenger->max_passenger = 0;
            }
            $book_insurance_price = 0;
            $book_insurance = \DB::table('car_class_insurance as cci')
                ->leftJoin('car_insurance as ci', 'ci.id', '=', 'cci.insurance_id')
                ->where('cci.class_id', $cls->id) ;
            if($insurance == '1') {
                $book_insurance = $book_insurance->where('ci.search_condition' , '1')->first() ;
                if(!is_null($book_insurance))
                    $book_insurance_price = $book_insurance->price;
            } elseif($insurance == '2'){
                $book_insurance = $book_insurance
                    ->select(['ci.*','cci.price'])->orderby('ci.search_condition','asc')->get();
                foreach($book_insurance as $ins) {
                    $book_insurance_price += $ins->price;
                }
            }

            // get all models in this class
            $car_models = \DB::table('car_class_model as ccm')
                ->leftJoin('car_model as cm', 'ccm.model_id', '=', 'cm.id' )
                ->where('ccm.class_id', '=', $cls->id)->select(['cm.*'])->get();
            $book_cars = array();
            $book_smoking = 0;
            $book_nonsmoking = 0;
            $all_cars = [];
            foreach($car_models as $mo) {
                $model_id  = $mo->id;
                $car_in = \DB::table('car_inventory as ci')
                    ->where('ci.max_passenger', '>=', $real_passenger)  // 2018/06/06 add
                    ->where('ci.model_id', $model_id)
                    ->where('ci.delete_flag', 0)
                    ->where('ci.status', 1);
                // check smoke
                if ($smoking != 'both') {
//                    $sm = $smoking;
//                    if($sm == '2') $sm = '1';
                    $car_in = $car_in->where('ci.smoke', $smoking);
                }

                // check shop
                if (!empty($depart_shop)) {
                    $car_in = $car_in->where('ci.shop_id', $depart_shop);
                }

                if (!empty($dropoff_id) && $dropoff_id != $depart_shop) {
                    $car_in = $car_in->leftJoin('car_inventory_dropoff as dr', 'ci.id', '=', 'dr.inventory_id')
                        ->where('dr.shop_id', '=', $dropoff_id);
                }

                $cars = $car_in->select(['ci.*'])->get();

                if(count($cars) > 0) {
                    foreach($cars as $car) {
                        if ($car->smoke == '1') $book_smoking++;
                        if ($car->smoke == '0') $book_nonsmoking++;
                        array_push($all_cars, $car);
                    }
                    array_push($book_cars, $mo);
                }
            }

            // check if car is available
            $count = 0;
            foreach ($all_cars as $car) {
                $bdate = date('Y-m-d',strtotime($depart_date));
                $edate = date('Y-m-d',strtotime($return_date));
                $checkBook = ServerPath::getconfirmBooking($car->id, $bdate, $edate, '');     // car usable=>true else false
                $checkInspection = ServerPath::getConfirmInspection($car->id, $bdate, $edate, '','');
                if($checkBook && $checkInspection){
                    $count++;
                }
            }

            //get date diff(night_day)
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night = $request_days['night'];
            $day = $request_days['day'];
            $all_price = 0;
            $book_class = (object)array();
            $book_class->class_id    = $cls->id;
            $book_class->id          = $cls->id;
            $book_class->class_name  = $cls->name;
            $book_class->thumb_path  = $cls->thumb_path;
            $book_class->thum_path   = (trim($cls->thumb_path)!= '')? trim($cls->thumb_path) : '/images/blank.jpg';
            $book_class->smoking     = $book_smoking;
            $book_class->nonsmoking  = $book_nonsmoking;
            $book_class->passenger   = $book_passenger;
//            $book_insurance_price    = $book_insurance_price * $car_number;
            $book_class->insurance   = $book_insurance_price * $day;
            $all_price              += $book_insurance_price;
            $book_class->models      = $book_cars;
            $book_class->car_count   = $count;
            $book_class->priority    = $cls->car_class_priority;
            $book_class->night       = $night;
            $book_class->day         = $day;
            $book_class->pick_year   = date('Y',strtotime($depart_date));
            $book_class->pick_month  = date('n',strtotime($depart_date));
            $book_class->pick_day    = date('j',strtotime($depart_date));
            $book_class->drop_year   = date('Y',strtotime($return_date));
            $book_class->drop_month  = date('n',strtotime($return_date));
            $book_class->drop_day    = date('j',strtotime($return_date));
            $book_class->insurances  = ServerPath::getInsurancePrice2($cls->id);

            //get price
            $class_price = ServerPath::getPriceFromClass($depart_date, $return_date, $cls->id, $night . "_" . $day,$depart_date, $return_date);
//            $class_price = $class_price * $car_number ;
            $book_class->price = $class_price ;
            $all_price        += $class_price;

            //get option for search
            $select_options = empty($paid_opt_ids)? [] : $this->getOptionsFromClass($cls->id, $paid_opt_ids);

            $option_price = 0;
            $selected_option_ids = [];
            $selected_option_nums = [];
            $selected_option_prices = [];
            $selected_option_indexes = [];

            if (!empty($select_options)) {
                foreach ($select_options as $key=>$op) {
                    $index = array_search($op->id, $paid_opt_ids);
                    if($index === false) {
                        if($op->charge_system == 'one') {
                            $opr = $op->price;
                            $option_price += $opr;
                        } else {
                            $opr = $op->price * $day;
                            $option_price += $opr;
                        }
                        $select_options[$key]->number = 1;
                    } else {
                        if($op->charge_system == 'one'){
                            $opr = $op->price * $paid_opt_nums[$index];
                        } else {
                            $opr = $op->price * $paid_opt_nums[$index] * $day;
                        }
                        $option_price += $opr;
                        $select_options[$key]->price = $opr;
                        $select_options[$key]->number = $paid_opt_nums[$index];
                    }
                    $selected_option_ids[] = $op->id;
                    $selected_option_indexes[] = $op->google_column_number;
                    $selected_option_nums[] = $select_options[$key]->number;
                    $selected_option_prices[] = $opr;
                }
            }

            $book_class->selected_option_ids = implode(',', $selected_option_ids);
            $book_class->selected_option_indexes = implode(',', $selected_option_indexes);
            $book_class->selected_option_nums = implode(',', $selected_option_nums);
            $book_class->selected_option_prices = implode(',', $selected_option_prices);
            $book_class->options = $select_options;
//            $option_price   = $option_price * $car_number ;
            $book_class->option_price = $option_price ;
            $book_class->shop_id = $depart_shop;
            $book_class->shop_name = $this->getShopName($depart_shop);
            //get insurance
            $all_price += $option_price;
            $book_class->all_price = $all_price;

            //get flag about returnshop
            if($cls->id == $class_id) {
                $suggest_price  = $all_price;
                if($book_class->car_count > 0) {
                    array_push($search_class, $book_class);
                }

            }else{
                if($max_passenger === $book_passenger->max_passenger &&
                    $min_passenger === $book_passenger->min_passenger) {
                    array_push($virtual_allprice, $all_price);
                    $suggest_class[$all_price] = $book_class;
                }
            }
        }
//        rsort($virtual_allprice);
        $suggest = array();
        if(!empty($suggest_class)) {
            if (!empty($virtual_allprice[0]))
                $suggest[0] = $suggest_class[$virtual_allprice[0]];
            if (!empty($virtual_allprice[1]))
                $suggest[1] = $suggest_class[$virtual_allprice[1]];
            if (!empty($virtual_allprice[2]))
                $suggest[1] = $suggest_class[$virtual_allprice[2]];
        }

        // when class has no available cars, to search all classes in shops and show top 3 classes
        $top_threes = [];
        if(count($search_class) == 0) {
            $car_category   = $request->input('car_category', '');
            if($car_category == '') {
                $category = \DB::table('car_type_category')->where('name', '乗用車')->first();
                if(!empty($category)) $car_category = $category->id;
            }
            $passenger      = $real_passenger;
            $insurance      = 2; //$empty_request? '' : $request->get('insurance');
//            if($request->has('options')) {
                if(is_array($paid_opt_ids)) {
                    $options =  $paid_opt_ids;
                } else {
                    $v =  $paid_opt_ids;
                    $options = explode(',', $v);
                }

//            } else {
//                $options =  [];
//            }

            $smoke          = $smoking;
            $pickup         = $request->input('pickup', '');
            $free_options   = $free_opt_ids;
            if($pickup != '') $free_options[] = $pickup;
            $search_condition = '';

            // get depart_shop name
            $depart_shop_name = '';
            if($depart_shop != ''){
                $shop1 = \DB::table('car_shop')->where('id','=', $depart_shop)->first();
                if(!empty($shop1)) $depart_shop_name = $shop1->name;
            }

            $search = (object)array();
            $search->depart_date = $depart_date;
            $search->depart_time = $depart_time;
            $search->return_date = $return_date;
            $search->return_time = $return_time;
            $search->depart_shop = $depart_shop;
            $search->depart_shop_name = $depart_shop_name;
            $search->return_shop = $depart_shop;
            $search->car_category= $car_category;
            $search->passenger   = $passenger;
            $search->insurance   = $insurance;
            $search->smoke       = $smoke;
            $search->options     = implode(',', $options);
            $search->free_options   = implode(',', $free_options);
            $search->pickup      = $pickup;

            //get date diff(night_day)
            $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
            $night = $request_days['night'];
            $day = $request_days['day'];
            $search->rentdates = $day;

            // search start
            $allClasses = \DB::table('car_class as c')
                ->join('car_class_price as p', 'c.id', '=', 'p.class_id')
                ->select(['c.*', 'p.n1d2_day1 as price'])
                ->where('c.car_shop_name', $depart_shop)
                ->where('c.status', 1)
                ->where('c.static_flag', 0)
                ->orderBy('c.car_class_priority')->get();

            foreach($allClasses as $cls){

                if($passenger != 'all') {
                    $psgRanges = \DB::table('car_class_passenger AS p')
                        ->leftJoin('car_passenger_tags AS t', 'p.passenger_tag', '=','t.id')
                        ->where('p.class_id','=',$cls->id)
                        ->where('t.min_passenger','<=',$passenger)
                        ->where('t.max_passenger','>=',$passenger)
                        ->orderBy('t.show_order')
                        ->count();

                    if( $psgRanges == 0 ) continue;
                }

                if( !empty( $options )) {
                    $class_options = \DB::table('car_option_class')->where('class_id',$cls->id )->get();

                    //check if class has all options of search condition
                    $count_option_in_search = 0;
                    foreach( $class_options as $cop ) {
                        if( in_array($cop->option_id, $options) ) {
                            $count_option_in_search++;
                        }
                    }
                    if( $count_option_in_search < count($options) ) continue;
                }

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

                $models = $models->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id')
                    ->where('ci.delete_flag', 0)
                    ->where('ci.status', 1);
                if($passenger != 'all') {
                    $models = $models->where('ci.max_passenger','>=', $passenger );
                } else {
                    $models = $models->whereNotNull('ci.max_passenger' );
                }
                // check smoke
                if($smoke != '' && $smoke != 'both')
                    $models = $models->where('ci.smoke', $smoke);
                // check shop
                if($depart_shop != '')
                    $models = $models->where('ci.shop_id',$depart_shop);
                // check dropoff
                if($return_shop != '' && $return_shop != $depart_shop){
                    $models = $models->leftJoin('car_inventory_dropoff as dr', 'ci.id', '=', 'dr.inventory_id')
                        ->where('dr.shop_id','=',$return_shop);
                }

                $cars = $models->select(['ci.*','cm.class_id', 'm.name as model_name', 'cm.model_id'])->get();

                if(empty($cars)) continue;  // if no car matching condition
                // check if car is available
                $count = 0;
                $mids = [];
                $mnames = [];
                foreach ($cars as $car) {
                    $checkBook = ServerPath::getconfirmBooking($car->id, $depart_date, $return_date, $search_condition);     // if car usable, true else false
                    $checkInspection = ServerPath::getConfirmInspection($car->id, $depart_date, $return_date,'', '');
                    if($checkBook && $checkInspection){
                        $count++;
                        if(!array_search($car->model_id, $mids)){
                            array_push($mids, $car->model_id);
                            array_push($mnames, $car->model_name);
                        }
                    }
                }
                if($count == 0) // if no car available
                    continue;

                $vcls = (object)array();
                $vcls->thum_path   = (trim($cls->thumb_path)!= '')? trim($cls->thumb_path) : '/images/blank.jpg';
                $vcls->id    = $cls->id;
                $vcls->price = $cls->price;
                $vcls->class_name  = $cls->name;
                $vcls->model_id    = $mids;
                $vcls->model_name  = $mnames;
                $vcls->depart_date = $depart_date;
                $vcls->depart_time = $depart_time;
                $vcls->return_date = $return_date;
                $vcls->return_time = $return_time;
                $vcls->message = "Test message";
                $vcls->car_count = $count;
                $vcls->priority = $cls->car_class_priority;
                $vcls->insurance = $tins;
                $vcls->smoke = $smoke;
                $vcls->night_day = $night . '泊' . $day . '日';

                //get price
                $class_price = ServerPath::getPriceFromClass($depart_date, $return_date, $cls->id, $night . "_" . $day, $depart_date, $return_date);
                $vcls->price = $class_price;

                //get option for search
                if(!empty($options))
                    $select_options = $this->getOptionsFromClass($cls->id, $options);
                else
                    $select_options = array();
                $vcls->options = $select_options;

                $option_price = 0;
                if (!empty($select_options)) {
                    foreach ($select_options as $op) {
                        $index = array_search($op->id, $options);
                        $op_num = $paid_opt_nums[$index];
                        if($op->charge_system == 'one') {
                            $option_price += $op->price * $op_num;
                        } else {
                            $option_price += $op->price * $day * $op_num;
                        }
                        $op->number = $op_num;
                    }
                }
                $vcls->option_price = $option_price;
                $vcls->free_options = $this->getOptionsFromClass($cls->id, $free_options);
                $vcls->shop_id = $depart_shop;
                $vcls->shop_name = $this->getShopName($depart_shop);
                if(empty($car_category)) $car_category = "";
                $vcls->category_id = $car_category;
                $vcls->category_name = $this->getCategoryName($car_category);

                $insurance_price = ServerPath::getInsurancePrice2( $cls->id);
                $vcls->insurance_price1 = $insurance_price['ins1'] * $day;
                $vcls->insurance_price2 = $insurance_price['ins2'] * $day;
                $allPrice = $class_price + $option_price;
                $vcls->all_price = $allPrice;
                $vcls->max_passengers = ServerPath::getClassAvailableMaxPassengers($cls->id, $depart_shop, $depart_date, $return_date);

                array_push($top_threes, $vcls);
//                if(count($top_threes) == 3) break;
            }
        }

        // max passengers
//        $possible_passengers = ServerPath::getClassMaxPassengers($class_id, $pickup_id);
        $possible_passengers = ServerPath::getClassAvailableMaxPassengers($class_id, $depart_shop, $depart_date, $return_date);

        /////////////////////
        $data = [
            'smoke_count'   => $smoke_cars,
            'non_smoke_count'   => $nonsmoke_cars,
            'class'         => $class,
            'class_id'      => $class_id,
            'hour'          => $this->hours,
            'shops'         => $shops,
            'smoking'       => $smoking,
            'insurance'     => $insurance,
            'paid_options'  => $all_paid_options,
            'free_options'  => $all_free_options,
            'searching'     => $search_class,
            'suggest'       => $suggest,
            'suggest_price' => $suggest_price,
            'depart_date'   => $depart_date,
            'depart_time'   => $depart_time,
            'return_date'   => $return_date,
            'return_time'   => $return_time,
            'pickup_id'     => $depart_shop,
            'pickup_name'   => $pickup_name,
            'dropoff_id'    => $return_shop,
            'dropoff_name'  => $dropoff_name,
            'car_number'    => $car_number,
            'free_option_ids'    => $free_opt_ids,
            'paid_option_ids'    => $paid_opt_ids,
            'free_option_nums'   => $free_opt_nums,
            'paid_option_nums'   => $paid_opt_nums,
            'paid_option_indexs' => implode(',', $option_columns),
            'search_cond'   => $search_cond,
            'equipments'    => $equipments,
            'max_passengers'    => $possible_passengers,
            'max_passenger'     => $real_passenger,
        ];
        if(count($search_class) == 0) {
            $data['top_three']      = $top_threes;
            $data['shop_search']    = $search;
        }
        return View('pages.frontend.carclass-detail')->with($data);

    }

    //car class list
    public function shopCarClassList($shop_id){
        $shop = Shop::findOrFail($shop_id);
        if(is_null($shop)) return array();

        $classes = CarClass::where('car_shop_name', $shop_id)
            ->orderBy('car_class_priority')
            ->get();

        $tmp = [];
        foreach ($classes as $cls) {
            array_push($tmp, $cls->id);
        }

        $classes = \DB::table('car_class as c')
                    ->leftJoin('car_shop as cs', 'c.car_shop_name','=','cs.id')
                    ->whereIn('c.id', $tmp)
                    ->orderby('car_class_priority')
                    ->select('c.id as class_id','c.status as carclass_status','cs.name as csname','cs.id as cs_id')
                    ->get();


        $result     = array();
        foreach($classes as $cls) {
            $class = CarClass::findOrFail($cls->class_id);
            $models = \DB::table('car_class_model as cm')
                        ->leftJoin('car_model as m', 'cm.model_id', '=', 'm.id')
                        ->select(['cm.model_id', 'm.*'])
                        ->where('cm.class_id','=',$cls->class_id)
                        ->orderBy('cm.priority')->get();
            $first = true;
            $model_names = [];
            $model_names_en = [];
            $thumbnail = '';
            $capacity = '';
            foreach ($models as $model) {
                if($first == true) {
                    $thumbnail = $model->thumb_path;
                    $capacity = $model->passengers;
                    $first = false;
                }
                array_push($model_names, $model->name);
                array_push($model_names_en, $model->name_en);
            }

            $options    = \DB::table('car_option as co')
                ->leftJoin('car_option_class  as coc', 'coc.option_id', '=', 'co.id')
                ->where('coc.class_id', $cls->class_id)->select(['co.*'])->get();

            $book_passenger = \DB::table('car_passenger_tags as p')
                ->leftJoin('car_class_passenger as ccp', 'ccp.passenger_tag', '=', 'p.id')
                ->where('ccp.class_id', $cls->class_id)
                ->orderby('max_passenger', 'desc')->first();
            if (empty($book_passenger)) {
                $book_passenger = (object)array();
                $book_passenger->min_passenger = 0;
                $book_passenger->max_passenger = 0;
                $book_passenger->name = 0;
            }
            $minP = $book_passenger->min_passenger;
            $maxP = $book_passenger->max_passenger;

            $tag = ($minP == $maxP)? $minP : $minP.'~'.$maxP;
            if( $class->name == 'CW3H' || $class->name == 'W3')
                $tag = $minP;

            //get price
            $cls->name      = $class->name;
            $cls->price     = ServerPath::getPriceDayNight($cls->class_id, 1, 2)/2;
            $cls->thumb     = $thumbnail;
//            $cls->passenger = $tag;
            $cls->passenger = $book_passenger;
            $cls->options   = $options;
            $cls->models    = $model_names;
            $cls->models_en = $model_names_en;
            $cls->smoke     = '0';
            $cls->tag       = $tag;
            array_push($result, $cls);
        }

        return $result;
    }

    //car class list
    public function carclassList($shop_slug){
       // $classes    = CarClass::orderby('car_class_priority','asc')->get();
        $shop = \DB::table('car_shop')->where('slug', $shop_slug)->first();

        $classes = \DB::table('car_class')
                    ->leftJoin('car_shop as cs', 'car_class.car_shop_name','=','cs.id')
                    ->orderby('car_class_priority')
                    ->select('car_class.*','cs.name as csname','cs.id as cs_id')
                    ->where('car_class.status',1)
                    ->get();

        $result     = array();
        foreach($classes as $cls) {
            $book_passenger = \DB::table('car_passenger_tags as p')
                ->leftJoin('car_class_passenger as ccp', 'ccp.passenger_tag', '=', 'p.id')
                ->where('ccp.class_id', $cls->id)->orderby('max_passenger', 'desc')->first();
            if (empty($book_passenger)) {
                $book_passenger = (object)array();
                $book_passenger->min_passenger = 0;
                $book_passenger->max_passenger = 0;
            }
            $options    = \DB::table('car_option as co')
                ->leftJoin('car_option_class  as coc', 'coc.option_id', '=', 'co.id')
                ->where('coc.class_id', $cls->id)
                ->where('co.google_column_number', '<', 200)
                ->select(['co.*'])->get();

            $car_models = \DB::table('car_model as cm')
                ->leftJoin('car_class_model as ccm', 'ccm.model_id', '=', 'cm.id' )
                ->where('ccm.class_id', '=', $cls->id)
                ->select(['cm.*'])
                ->get();

            $count = 0;
            $smoke = '0';
            $all_shop_names = [];
            foreach($car_models as $mo) {
                $model_id  = $mo->id;
                $car_in = \DB::table('car_inventory as ci')
                    ->leftJoin('car_shop as cs', 'cs.id', '=', 'ci.shop_id' )
                    ->where('ci.model_id',$model_id)
                    ->where('ci.delete_flag', 0)
                    ->where('ci.status', 1)
                    ->select(['ci.*','cs.name as shop_name','cs.abbriviation as shop_abbriviation'])->get();
                $shop_name  = '';
                $smoking    = '0';
                foreach ($car_in as $car) {
                    $shop_name = $car->shop_name;
                    $smoking   = $car->smoke;
                    if($smoking == '1') $smoke = '1';
                    if($shop_name != '' && !in_array($shop_name, $all_shop_names))
                        array_push($all_shop_names, $shop_name);
                }

                $car_models[$count]->smoking   = $smoking;
                $car_models[$count]->shop_name = $shop_name;
                $count++;
            }
            //get price
            $cls->price2_1  = ServerPath::getPriceDayNight($cls->id, 1, 2);
            $cls->price1_0  = $cls->price2_1 / 2 ;
            $cls->passenger = $book_passenger;
            $cls->options   = $options;
            $cls->models    = $car_models;
            $cls->smoke     = $smoke;
            $cls->all_shops = $all_shop_names;
            array_push($result, $cls);
        }

        $data = [
            'classes'   => $result,
            'shop'      => $shop,
        ];
		
		$meta_info = Page::where('slug','carclasslist/'.$shop_slug)->first();
		
        return View('pages.frontend.carclass-list')->with($data)->with('meta_info',$meta_info);
    }

    //shop detail view 7.png
    public function showShopdetail($slug) {
        $shops = Shop::all();
        $shop = \DB::table('car_shop')->where('slug', '=', $slug)->first();
        if(is_null( $shop )) {
            return back()->withErrors(array('slug'=>'There is no shop with slug '.$slug ))->withInput();
        }
        $pickup =  \DB::table('car_pickup')->where('shop_id',$shop->id)->first();

        $posts = \DB::table('blog_posts')
            ->select(['id','title','slug','publish_date','featured_image','shop_id','post_tag_id'])
            ->where('shop_id', '=', $shop->id)
            ->where('post_tag_id','<',3)
            ->orderBy('id','desc')
            ->limit(3)
            ->get();
        $data = [
            'shops'     => $shops,
            'shop'      => $shop,
            'posts'     => $posts,
            'pickup'     => $pickup,
            'classes'   => $this->shopCarClassList($shop->id)
        ];
		$meta_info = Page::where('slug','shop/'.$slug)->first();
        return View('pages.frontend.shop-detail')->with($data)->with('meta_info',$meta_info);

    }

    //shop detail view 7.png
    public function showCampaigndetail(Request $request, $region_code, $page = 0 ) {
        $shop = \DB::table('car_shop')->where('region_code', '=', $region_code)->first();
        if(is_null( $shop )) {
            return back()->withErrors(array('slug'=>'There is no shop with region_code '.$region_code ))->withInput();
        }

        $start = $request->input('start', date('Y-m-d', strtotime('tomorrow')));
        $end = $request->input('end', date('Y-m-d', strtotime('tomorrow +13 days')));

//        echo $request->method(); //exit();

        $data = $this->get_campaigns($region_code,$start,$end, 'all', $page);
        $campaigns = $data['campaigns'];
        $campaign_count = $data['count'];

        foreach ($campaigns as $camp) {
            $imgs = [];
            $class = CarClass::where('id', $camp->class_id)->first();
            if(!is_null($class)) $imgs[] = $class->thumb_path;

            $thumbs = \DB::table('car_class_thumb')->where('class_id', $camp->class_id)->get();
            foreach($thumbs as $thumb) {
                $imgs[] = $thumb->thumb_path;
            }
            $camp->imgs = $imgs;
            // get insurances
            $insurances = ServerPath::getInsurancePrice2($camp->class_id);
            $camp->insurances = $insurances;
        }

        $data = [
            'start_date'=> $start,
            'end_date'  => $end,
            'hours'     => $this->hours,
            'shop'      => $shop,
            'campaigns' => $campaigns,
            'camp_count'=> $campaign_count,
            'page'      => $page
        ];
		$meta_info = Page::where('slug','campaign/'.$region_code)->first();
        return View('pages.frontend.campaign-detail')->with($data)->with('meta_info',$meta_info);

    }

    //quick start form
    public function quickstart_01(Request $request) {

		if (Auth::check()){
			session()->put('register_user_id', Auth::user()->id);
		}
        if(!session()->has('register_user_id') or session()->get('register_user_id') == 0){
            return redirect('/')->with('error', trans('usersmanagement.errorNotsave'));
        }

        $booking_id  = session()->get('booking_id');
        $bookingInfo = \DB::table('bookings')->where('id', '=', $booking_id)->first();
		if($bookingInfo->web_status == 3){
			return redirect('/')->with('error', 'この予約はすでに支払い済みです');
		}
		
        $psgRanges   = \DB::table('car_class_passenger AS p')
                        ->leftJoin('car_passenger_tags AS t', 'p.passenger_tag', '=','t.id')
                        ->where('p.class_id','=',$bookingInfo->class_id)
                        ->orderBy('t.max_passenger', 'desc')
                        ->first();				
		
		$driverLicences = \DB::table('bookings_driver_licences')
							->where('booking_id', '=', $booking_id)
							->get();

        $userInfo = User::with('profile')
						->find(session()->get('register_user_id'));
						
		$maxPerson = range(0,$psgRanges->max_passenger);

		$flight_lines = \DB::table('flight_lines')->orderby('order')->get();

        return View('pages.frontend.quickstart-01')
            ->with([
                'userInfo'      => $userInfo,
                'psgRanges'     => $psgRanges,
                'bookingInfo'   => $bookingInfo,
                'driverLicences'=> $driverLicences,
                'maxPerson'     => $maxPerson,
                'booking_class_name'    => session()->get('booking_class_name', ''),
                'booking_class_id'      => session()->get('booking_class_id',''),
                'booking_price'         => session()->get('booking_price',''),
                'flight_lines'  => $flight_lines
            ]);
    }


    public function savebag_choose(Request $request){
        $bag_choosed  = $request->get('bag_choosed');
        $booking_id   = $request->get('booking_id');
        \DB::table('bookings')->where('id', '=', $booking_id)
                         ->update([
                            'bag_choosed' => ($bag_choosed?$bag_choosed:1)
                         ]);

        session()->forget('booking_id');
        session()->forget('register_user_id');
        session()->forget('smokeSelection');
        echo "success"; die;
       //return redirect("quickstart-01");
    }


    public function savequickstart_01(Request $request) {
        if(!session()->has('register_user_id') and session()->has('register_user_id') == 0){
            return redirect('/')->with('error', trans('usersmanagement.errorNotsave'));
        }

        $inputs    = $request->all();
           if(ServerPath::lang() == 'ja') {
               $rules = [
                   'email' => 'required',
                   'last_name' => 'required',
                   'first_name' => 'required',
                   'furi_last_name' => 'required',
                   'furi_first_name' => 'required',
                   'phone' => 'required',
                   'zip11' => 'required',
                   'address' => 'required',
                   'person_number' => 'required',
                   'driver_number' => 'required',
               ];
           }else {
               $rules = [
                   'email' => 'required',
                   'last_name' => 'required',
                   'first_name' => 'required',
                   'phone' => 'required',
                   'zip11' => 'required',
                   'address' => 'required',
                   'person_number' => 'required',
                   'driver_number' => 'required',
               ];
           }

        $messages = [];
        /*
		for($i = 0; $i < $inputs['driver_number']; $i++) {
            $rules['license_surface.'.$i] = 'required|image|mimes:jpeg,jpg,png|max:2048';
            $rules['license_back.'.$i]    = 'required|image|mimes:jpeg,jpg,png|max:2048';
            $messages['license_surface.'.$i.'.required'] = 'Driver '.($i+1).' license surface image is required';
            $messages['license_back.'.$i.'.required'] = 'Driver '.($i+1).' license back image is required';
        }
		*/
        $validator = \Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {
            return redirect("quickstart-01")
                        ->withErrors($validator)
                        ->withInput();
        }else{

            $user_id    = session()->get('register_user_id');
            $userInfo   = User::with('profile')->find($user_id);
            $userInfo->first_name   =  $inputs['first_name'];
            $userInfo->last_name    =  $inputs['last_name'];
            $userInfo->save();

            $profile = $userInfo->profile;
            if(empty($inputs['furi_first_name']) && ServerPath::lang() == 'en') $inputs['furi_first_name'] = '';
            if(empty($inputs['furi_last_name']) && ServerPath::lang() == 'en') $inputs['furi_last_name'] = '';
            $profile->fur_first_name    =  $inputs['furi_first_name'];
            $profile->fur_last_name     =  $inputs['furi_last_name'];
            $profile->phone             =  $inputs['phone'];
            $profile->postal_code       =  $inputs['zip11'];
            $profile->address1          =  $inputs['address'];
            $profile->update();

            $booking_id             = session()->get('booking_id');
            \DB::table('bookings')->where('id', '=', $booking_id)
                             ->update([
                                 'passengers' => $inputs['person_number'],
                                 'web_status' => 1,
                                 'drivers'    => $inputs['driver_number'],
                                 'client_message'   => $inputs['comment'],
                                 'flight_inform'    => $inputs['flight_inform']
                             ]);

            if(array_key_exists('license_surface', $inputs) and count($inputs['license_surface'])) {
                $loopIndex = 1;
                \DB::table('bookings_driver_licences')
                    ->where('booking_id', $booking_id)
                    ->delete();

                foreach ($inputs['license_surface'] as $key => $value) {

                    $folderName = '/images/licences/';
                    $destinationPath = public_path() . $folderName;

                    $surfacefile = $request->file('license_surface')[$key];
                    $fileName = $surfacefile->getClientOriginalName();
                    $extension = $surfacefile->getClientOriginalExtension();
                    $safeName = 'surface_' . $booking_id . '-' . $loopIndex . '.' . $extension;
                    $surfacefile->move($destinationPath, $safeName);
                    $surface_img = $folderName . $safeName;

                    $backfile = $request->file('license_back')[$key];
                    $fileName = $backfile->getClientOriginalName();
                    $extension = $backfile->getClientOriginalExtension();
                    $safeName = 'back_' . $booking_id . '-' . $loopIndex . '.' . $extension;
                    $backfile->move($destinationPath, $safeName);
                    $back_img = $folderName . $safeName;

                    \DB::table('bookings_driver_licences')
                        ->insert([
                            'booking_id' => $booking_id,
                            'representative_license_surface' => $surface_img,
                            'representative_license_back' => $back_img
                        ]);
                    $loopIndex++;
                }
            }

            // change web_status = 1
            /*\DB::table('bookings')
                ->where('id', '=', $booking_id)
                ->update(['web_status' => 1]);*/

            return redirect("quickstart-02");
        }

    }

    //quick start form
    public function quickstart_02(Request $request) {
        if(!session()->has('register_user_id') and session()->has('register_user_id') == 0){
            return redirect('/')->with('error', trans('usersmanagement.errorNotsave'));
        }
		
        $booking_id  = session()->get('booking_id');
        $bookingInfo = \DB::table('bookings')->where('id', '=', $booking_id)->first();
		if($bookingInfo->web_status == 3){
			return redirect('/')->with('error', 'この予約はすでに支払い済みです');
		}		
		
        $userInfo = User::find(session()->get('register_user_id'));
		$booking_class_name = session()->pull('booking_class_name', '');
		$booking_class_id = session()->pull('booking_class_id','');
		$booking_price = session()->pull('booking_price','');

        return View('pages.frontend.quickstart-02')
            ->with([
                'userInfo'=>$userInfo,
                'booking_class_name'=> $booking_class_name,
                'booking_class_id'  => $booking_class_id,
                'booking_price'     => $booking_price
            ]);
    }

    public function savequickstart_02(Request $request) {

        $inputs             =  $request->all();
        $booking_id         =  session()->get('booking_id');
        $inputs             =  $request->all();
        $rules              =  ['agree_terms_conditions'  =>  'required'];
        $messages           =  ['agree_terms_conditions.required' => 'Please accept HakoRentACar terms & conditions'];

        $validator = \Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {
            return redirect("quickstart-02")
                        ->withErrors($validator)
                        ->withInput();
        }else{
            \DB::table('bookings')->where('id', '=', $booking_id)
                         ->update([ 'agree_terms_conditions' => 1, 'web_status' => 2 ]);

            // change web_status = 2
            /*\DB::table('bookings')
                ->where('id', '=', $booking_id)
                ->update(['web_status' => 2]);*/

            return redirect("quickstart-03");
        }

    }

    //quick start form
    public function quickstart_03(Request $request) {
        if(!session()->has('register_user_id') and session()->has('register_user_id') == 0){
            return redirect('/')->with('error', trans('usersmanagement.errorNotsave'));
        }
        $userInfo    = User::find(session()->get('register_user_id'));
        $bookingInfo = \DB::table('bookings')
                            ->where('id', session()->get('booking_id'))
                            ->first();

		if($bookingInfo->web_status == 3){
			return redirect('/')->with('error', 'この予約はすでに支払い済みです');
		}
        $carClassInfo= CarClass::find($bookingInfo->class_id);

        $modelInfo   = \DB::table('car_class_model as cm')
            ->leftJoin('car_model as m', 'm.id', '=', 'cm.model_id' )
            ->leftJoin('car_type as ct', 'm.type_id', '=', 'ct.id')
            ->where('m.passengers', '>=', $bookingInfo->passengers)
            ->where('cm.class_id', '=', $bookingInfo->class_id)
            ->select(['cm.class_id', 'm.name as model_name', 'cm.model_id', 'm.passengers'])
            ->first();

        $models = \DB::table('car_class_model as cm')
            ->leftJoin('car_model as m', 'cm.model_id', '=', 'm.id')
            ->where('cm.class_id', $bookingInfo->class_id)
            ->select('m.*', 'cm.class_id')
            ->get();
        $departShop = Shop::find($bookingInfo->pickup_id);
        $returnShop = Shop::find($bookingInfo->dropoff_id);


        $depart_date = date('Y-m-d', strtotime($bookingInfo->departing));
        $depart_time = date('H:i', strtotime($bookingInfo->departing));
        $return_date = date('Y-m-d', strtotime($bookingInfo->returning));
        $return_time = date('H:i', strtotime($bookingInfo->returning));

        $rent_days_arr= explode('_',$bookingInfo->rent_days);
        $rent_days    = "";
        if($rent_days_arr[0]){ $rent_days .= $rent_days_arr[0].'泊'; $rent_dates = $rent_days_arr[0]; }
        if($rent_days_arr[1]){ $rent_days .= $rent_days_arr[1].'日'; $rent_dates = $rent_days_arr[1]; }

        $basic_charge = ServerPath::getPriceFromClass($depart_date, $return_date,$bookingInfo->class_id, $rent_days_arr[0] . "_" . $rent_days_arr[1],$depart_date, $return_date);
        $paid_options = '';
        if($bookingInfo->paid_options){
            $paid_options = CarOption::whereIn('id', explode(',',$bookingInfo->paid_options))
                            ->orderByRaw('FIELD(id, '.$bookingInfo->paid_options.')')
                            ->get();
        }

        if(session()->get('smokeSelection') == '0'){
            if(ServerPath::lang() == 'ja') {
                $smokeInfo = '禁煙';
            }
            if(ServerPath::lang() == 'en') {
                $smokeInfo = 'Non smoking';
            }
        }else if(session()->get('smokeSelection') == '1'){
            if(ServerPath::lang() == 'ja') {
                $smokeInfo = '喫煙';
            }
            if(ServerPath::lang() == 'en') {
                $smokeInfo = 'Smoking';
            }
        }else{
            if(ServerPath::lang() == 'ja') {
                $smokeInfo = 'どちらでも良い';
            }
            if(ServerPath::lang() == 'en') {
                $smokeInfo = 'Both are fine';
            }
        }
        return View('pages.frontend.quickstart-03')
                ->with([
                    'userInfo'      => $userInfo,
                    'bookingInfo'   => $bookingInfo,
                    'carClassInfo'  => $carClassInfo,
                    'modelInfo'     => $modelInfo,
                    'models'        => $models,
                    'departShop'    => $departShop,
                    'returnShop'    => $returnShop,
                    'rent_days'     => $rent_days,
                    'rent_dates'    => $rent_dates,
                    'basic_charge'  => $basic_charge,
                    'paid_options'  => $paid_options,
                    'smokeInfo'     => $smokeInfo
            ]);
    }

    public function savequickstart_03(Request $request) {

        $access_token =  env('SQUARE_ACCESS_TOKEN'); //'sandbox-sq0atb-YzR6NpdwLdvem8eJVPwDcQ';
        $inputs       =  $request->all();
        $rules        =  ['nonce'          =>  'required'];
        $messages     =  ['nonce.required' => 'Please check credit card details'];

        $validator = \Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {
            return redirect("quickstart-03")
                        ->withErrors($validator)
                        ->withInput();
        }else{

            \SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($access_token);
            $locations_api = new \SquareConnect\Api\LocationsApi();
            try {
              $locations = $locations_api->listLocations();
              # We look for a location that can process payments
              $location = current(array_filter($locations->getLocations(), function($location) {
                $capabilities = $location->getCapabilities();
                return is_array($capabilities) &&
                  in_array('CREDIT_CARD_PROCESSING', $capabilities);
              }));

            } catch (\SquareConnect\ApiException $e) {

                $response_body = $e->getResponseBody();
                $error = reset($response_body->errors);

                $validator->errors()->add('nonce', $error->detail);
                return redirect("quickstart-03")
                        ->withErrors($validator)
                        ->withInput();

            }

            $userInfo    = User::find(session()->get('register_user_id'));
            $bookingInfo = \DB::table('bookings')
                            ->where('id', session()->get('booking_id'))
                            ->first();
            $request_body = array(
                "given_name"    => $userInfo->first_name,
                "family_name"   => $userInfo->last_name,
                "email_address" => $userInfo->email,
                "address"=> [
                    "address_line_1"=> $userInfo->profile->address1,
                    "address_line_2"=> $userInfo->profile->address2,
                    "locality"=> $userInfo->profile->prefecture,
                    "administrative_district_level_1"=> $userInfo->profile->city,
                    "postal_code"=> $userInfo->profile->postal_code,
                    "country"=> "JP"
                ],
                "phone_number"=> $userInfo->profile->phone,
                "reference_id"=> $bookingInfo->booking_id,
                "note"        => 'Customer created for '.$bookingInfo->booking_id." HakoRentACar"
            );
            $api_instance = new \SquareConnect\Api\CustomersApi();
            $body         = new \SquareConnect\Model\CreateCustomerRequest($request_body);

            try {
                $result = $api_instance->createCustomer($body);
                $customerInfo = $result->getCustomer();

            } catch (Exception $e) {

                $response_body = $e->getResponseBody();
                $error = reset($response_body->errors);

                $validator->errors()->add('nonce', $error->detail);
                return redirect("quickstart-03")
                        ->withErrors($validator)
                        ->withInput();

            }

            $transactions_api = new \SquareConnect\Api\TransactionsApi();

            # To learn more about splitting transactions with additional recipients,
            # see the Transactions API documentation on our [developer site]
            # (https://docs.connect.squareup.com/payments/transactions/overview#mpt-overview).
            $request_body = array (

                "card_nonce" => $inputs['nonce'],
                "note"     => $bookingInfo->booking_id." HakoRentACar Payment Transaction",
                "customer_id"=> $customerInfo->getId(),
                "delay_capture"=> false,
                "shipping_address"=> [
                    "address_line_1"=> $userInfo->profile->address1,
                    "locality"=> $userInfo->profile->prefecture,
                    "administrative_district_level_1"=> $userInfo->profile->city,
                    "postal_code"=> $userInfo->profile->postal_code,
                    "country"=> "JP"
                ],
                "billing_address"=> [
                    "address_line_1"=> $userInfo->profile->address1,
                    "address_line_2"=> $userInfo->profile->address2,
                    "administrative_district_level_1"=> $userInfo->profile->city,
                    "locality"=> $userInfo->profile->prefecture,
                    "postal_code"=> $userInfo->profile->postal_code,
                    "country"=> "JP"
                ],
                # Monetary amounts are specified in the smallest unit of the applicable currency.
                # This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
                "amount_money" => array (
                    "amount"   => $bookingInfo->payment,
                    "currency" => "JPY"
                ),

                # Every payment you process with the SDK must have a unique idempotency key.
                # If you're unsure whether a particular payment succeeded, you can reattempt
                # it with the same idempotency key without worrying about double charging
                # the buyer.
                "idempotency_key" => uniqid()
            );

            try {

                $result = $transactions_api->charge($location->getId(), $request_body);
				$card_last4 = ($result->getTransaction()->getTenders()[0]->getCardDetails()->getCard()->getLast4());
				$card_brand = ($result->getTransaction()->getTenders()[0]->getCardDetails()->getCard()->getCardBrand());
				$web_payment=  $result->getTransaction()->getTenders()[0]->getAmountMoney()->getAmount();
				$now_date   = date('Y-m-d');

                \DB::table('bookings')->where('id', '=', session()->get('booking_id'))
                             ->update([
                                'pay_id'        => $result->getTransaction()->getId(),
                                'web_status'    => 3,
                                'pay_position'  => 1,
								'card_last4'	=> $card_last4,
								'card_brand'	=> $card_brand,
								'web_payment'	=> $web_payment,
                                'trans_id'      => $result->getTransaction()->getId(),
                                //'portal_flag'   => 1,
                                //'portal_info'   => json_encode($result),
                                'pay_status'    => 1,
                                'payment'       => $web_payment,
                                'pay_method'    => 3, //1=cash, 2= credit, 3 =web, 4= portal
                                'paid_date'     =>$now_date
                             ]);

                $transaction_id = $result->getTransaction()->getId();
                $amount_paid    = $result->getTransaction()->getTenders()[0]->getAmountMoney()->getAmount();
                $payment_at     = date("Y-m-d H:i:s",strtotime($result->getTransaction()->getCreatedAt()));

                $booking_id = session()->get('booking_id');
                session()->forget('booking_id');
                session()->forget('register_user_id');
                session()->forget('smokeSelection');

                // send notification to user and admin

                //Don't delete
                // data for notification
//                $book = \DB::table('bookings')->where('id', '=', $booking_id)->first();
                $depart_shop = Shop::findOrFail($bookingInfo->pickup_id);
                $car_model = CarModel::findOrFail($bookingInfo->model_id);
                $car = CarInventory::findOrFail($bookingInfo->inventory_id);
                $price_insurance1 = intval($bookingInfo->insurance1);
                $price_insurance2 = intval($bookingInfo->insurance2);

                if($price_insurance2 == 0 && $price_insurance1 == 0) $insurance = '0';
                if($price_insurance2 == 0 && $price_insurance1 > 0) $insurance = '1';
                if($price_insurance2 > 0 && $price_insurance1 > 0) $insurance = '2';

                $lang = ServerPath::lang();

                if($insurance == '0') {
                    $insurance_type_ja = 'なし';
                } elseif($insurance == 1) {
                    $insurance_type_ja = '免責補償';
                } else {
                    $insurance_type_ja = '免責補償/ワイド免責補償';
                }

                if($lang == 'ja') {
                    if($insurance == '0') {
                        $insurance_part = '';
                        $insurance_type = 'なし';
                    } elseif($insurance == 1) {
                        $insurance_part = '免責補償：'.$price_insurance1.'円';
                        $insurance_type = '免責補償';
                    } else {
                        $insurance_part = '免責補償：'.$price_insurance1.'円<br>ワイド免責補償：'.$price_insurance2.'円';
                        $insurance_type = '免責補償/ワイド免責補償';
                    }
                } else {
                    if($insurance == '0') {
                        $insurance_part = '';
                        $insurance_type = 'none';
                    } elseif($insurance == 1) {
                        $insurance_part = 'Exemption of Liability Compensation：'.$price_insurance1.'yen';
                        $insurance_type = 'Exemption of Liability Compensation';
                    } else {
                        $insurance_part = 'Exemption of Liability Compensation：'.$price_insurance1.'yen<br>Wide Protection Package：'.$price_insurance2.'yen';
                        $insurance_type = 'Exemption of Liability Compensation/Wide Protection Package';
                    }
                }
                $car = CarInventory::find($bookingInfo->inventory_id);
                if($car->smoke == 1) {
                    $smoke = ($lang == 'ja')? '喫煙':'smoking';
                } else {
                    $smoke = ($lang == 'ja')? '禁煙':'non-smoking';
                }
                $class = \DB::table('car_class as c')
                    ->leftJoin('car_class_passenger as p', 'c.id', '=', 'p.class_id')
                    ->leftJoin('car_passenger_tags as t', 'p.passenger_tag', '=', 't.id')
                    ->select(['c.*','t.name as tagname','p.passenger_tag'])
                    ->where('c.id','=', $bookingInfo->class_id)
                    ->orderBy('p.passenger_tag', 'desc')->first();
                $option_ids = explode(',', $bookingInfo->paid_options);
                $option_numbers = explode(',', $bookingInfo->paid_option_numbers);
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
                    $depart_date = date("Y-m-d", strtotime($bookingInfo->departing));
                    $depart_time = date("H:i:s" , strtotime($bookingInfo->departing));
                    $return_date = date("Y-m-d", strtotime($bookingInfo->returning));
                    $return_time = date("H:i:s", strtotime($bookingInfo->returning));
                    $request_days = ServerPath::showRentDays($depart_date, $depart_time, $return_date, $return_time);
                    $rent_dates   = $request_days['day'];
                    $options = $options->get();
                    if(!empty($options)) {
                        foreach ($options as $key=>$option) {
                            $vid = array_search($option->id, $option_ids);
                            $opt_num = $option_numbers[$vid];
                            if($lang == 'ja') {
                                array_push($option_names, $option->name.'('.$opt_num.')');
                                array_push($option_names_ja, $option->name.'('.$opt_num.')');
//                                array_push($option_prices, $option->name.' '.$option->price.'円'.' x '.$opt_num.'個');
                                if($option->charge_system == 'one') {
                                    array_push($option_prices, $option->name.' '.$option->price.'円'.' x '.$opt_num.'個'); // childseat 540円 x 1個
                                } else {
                                    array_push($option_prices, $option->name.' '.$option->price.'円'.' x '. $rent_dates.'日');
                                }
                            } else {
                                array_push($option_names, $option->name_en.'('.$opt_num.')');
//                                array_push($option_prices, $option->name_en.' '.$option->price.'yen'.' x '.$opt_num.'');
                                if($option->charge_system == 'one') {
                                    array_push($option_prices, $option->name_en.' '.$option->price.'yen'.' x '.$opt_num); // childseat 540円 x 1個
                                } else {
                                    array_push($option_prices, $option->name_en.' '.$option->price.'yen'.' x '. $rent_dates.' days');
                                }
                            }
                        }
                    }
                }

                $free_options = $bookingInfo->free_options;
                $pickup_yes = !is_null($free_options) && ($free_options != '');

                if($pickup_yes) {
                    if(!empty($free_options) && is_array($free_options)) {
                        foreach ($free_options as $op) {
                            $cop = \DB::table('car_option')->where('id', $op)->first();
                            if (!is_null($cop) && ($cop->google_column_number == 101 || $cop->google_column_number == 102)) {
                                array_push($option_names_ja, '無料' . $cop->name);
                                if($lang == 'ja'){
                                    array_push($option_names, '無料' . $cop->name);
                                    array_push($option_prices, '無料' . $cop->name . ' 0円');
                                } else {
                                    array_push($option_names, 'free' . $cop->name_en);
                                    array_push($option_prices, 'free' . $cop->name_en . ' 0yen');
                                }
                            }
                        }
                    }
                }

                $bag_choosed = $bookingInfo->bag_choosed;
                $bag = '未選択';
                if($bag_choosed == 1) $bag = 'フリスク';
                if($bag_choosed == 2) $bag = 'ぷっちょ';
                if($bag_choosed == 3) $bag = '酔い止め';

                $rents = explode('_', $bookingInfo->rent_days);
                $rent_days = ($lang == 'ja')? $rents[0].'泊'.$rents[1].'日':$rents[0].'N'.$rents[1].'D';
                $shop_url = url('/shop/'.$depart_shop->slug);
                $user_msg = $bookingInfo->client_message;
                if(is_null($user_msg)) $user_msg = '';

                // send notification to registered user and admin
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                //$protocol = "https://";
                $domain = $_SERVER['HTTP_HOST'];
                $data = array();

                $depart_time = $lang == 'ja'? date('Y年m月d日 H時i分', strtotime($bookingInfo->departing)) : date('Y/m/d H:i', strtotime($bookingInfo->departing));
                $return_time = $lang == 'ja'? date('Y年m月d日 H時i分', strtotime($bookingInfo->returning)) : date('Y/m/d H:i', strtotime($bookingInfo->returning));
                $depart_time_ja = date('Y年m月d日 H時i分', strtotime($bookingInfo->departing));
                $return_time_ja = date('Y年m月d日 H時i分', strtotime($bookingInfo->returning));

                if($lang == 'ja')
                    $template = \DB::table('mail_templates')->where('mailname', 'web_payment_done_user')->first();
                else
                    $template = \DB::table('mail_templates')->where('mailname', 'web_payment_done_user_en')->first();
                if(!empty($template)) {
                    $subject = $template->subject;
                    $message = $template->content;
                    $message = str_replace('{user_name}', $userInfo->last_name . $userInfo->first_name, $message);

					$message = str_replace('{card_number}', '****'.$card_last4, $message);
					$message = str_replace('{card_brand}', $card_brand, $message);
                    $message = str_replace('{shop_url}', '<a href="'.$shop_url.'" target="_blank">'.$shop_url.'</a>', $message);
                    $message = str_replace('{snack}', $bag, $message);

                    $message = str_replace('{booking_id}', $bookingInfo->booking_id, $message);
                    $message = str_replace('{shop_name}', $lang == 'ja'? $depart_shop->name:$depart_shop->name_en, $message);
                    $message = str_replace('{car_model_name}', $car_model->name, $message);
                    $message = str_replace('{car_short_name}', $car->shortname, $message);
                    $message = str_replace('{car_capacity}', $class->tagname, $message);
                    $message = str_replace('{smoke}', $smoke, $message);
                    $message = str_replace('{insurance_type}', $insurance_type, $message);
                    $message = str_replace('{options}', implode(', ',$option_names), $message);
                    $message = str_replace('{departing_time}', $depart_time, $message);
                    $message = str_replace('{returning_time}', $return_time, $message);
                    $message = str_replace('{base_price}', $bookingInfo->basic_price, $message);
                    $message = str_replace('{insurance_part}', $insurance_part, $message);
                    $message = str_replace('{option_price}', $bookingInfo->option_price, $message);
                    $message = str_replace('{option_detail}', implode(', ', $option_prices), $message);
                    $message = str_replace('{total_price}', $bookingInfo->payment, $message);
//                    $message = str_replace('{car_class_name}', $class->name, $message);
//                    $message = str_replace('{car_plate_number}', $car->numberplate4, $message);
                    $content = $message;
                    $data1 = array('content' => $content, 'subject' => $subject, 'fromname' =>$template->sender,  'email' => $userInfo->email);
                    $data[] = $data1;
                }
                $template = \DB::table('mail_templates')->where('mailname', 'web_payment_success_admin')->first();
                if(!empty($template)) {
//                    【HR予約】★{shop_name}-{class_name}-{dent_days}
                    $subject = $template->subject;
                    $subject = str_replace('{shop_name}', $depart_shop->name, $subject);
                    $subject = str_replace('{class_name}', $class->name, $subject);
                    $subject = str_replace('{rent_days}', $rent_days, $subject);
                    $subject = str_replace('{total_price}', $bookingInfo->payment, $subject);

                    $message = $template->content;
                    $message = str_replace('{user_name}', $userInfo->last_name . $userInfo->first_name, $message);
                    $message = str_replace('{booking_id}', $bookingInfo->booking_id, $message);
                    $message = str_replace('{snack}', $bag, $message);

					$message = str_replace('{card_number}', '****'.$card_last4, $message);
					$message = str_replace('{card_brand}', $card_brand, $message);

                    $message = str_replace('{payment_submit_time}', date('Y年m月d日 H時i分', strtotime($payment_at)), $message);
                    $message = str_replace('{user_email_address}', $userInfo->email, $message);
//                    $message = str_replace('{user_password}', $password, $message);
                    $message = str_replace('{user_phone}', $userInfo->profile->phone, $message);
                    $message = str_replace('{shop_name}', $depart_shop->name, $message);
                    $message = str_replace('{car_model_name}', $car_model->name, $message);
                    $message = str_replace('{car_short_name}', $car->shortname, $message);
                    $message = str_replace('{car_capacity}', $class->tagname, $message);
                    $message = str_replace('{smoke}', $smoke, $message);
                    $message = str_replace('{option_items}', implode(', ',$option_names_ja), $message);
                    $message = str_replace('{insurance_type}', $insurance_type_ja, $message);
                    $message = str_replace('{departing_time}', $depart_time_ja, $message);
                    $message = str_replace('{returning_time}', $return_time_ja, $message);
                    $message = str_replace('{total_price}', $bookingInfo->payment, $message);
                    $message = str_replace('{car_class_name}', $class->name, $message);
                    $message = str_replace('{car_plate_number}', $car->numberplate4, $message);
                    $message = str_replace('{comment}', $user_msg, $message);
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
//                            'sinchong1989@gmail.com',
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
//                var_dump($data); return;

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
                $class_name = $class->name;
                $class_id = $class->id;
                return View('pages.frontend.paymentsuccess')->with(compact('transaction_id','class_name','class_id','amount_paid','payment_at','booking_id','userInfo'));

            } catch (\SquareConnect\ApiException $e) {

                $response_body = $e->getResponseBody();
                $error = reset($response_body->errors);

                $validator->errors()->add('nonce', $error->detail);
                return redirect("quickstart-03")
                        ->withErrors($validator)
                        ->withInput();

            }
        }
    }

    public function pay_offline(){
		
		// If user ping this page again then redirect to hme page
        if(!session()->has('register_user_id') and session()->get('register_user_id') == 0){
            return redirect('/')->with('error', trans('usersmanagement.errorNotsave'));
        }		
		
        $userInfo    = User::find(session()->get('register_user_id'));
        session()->forget('booking_id');
        session()->forget('register_user_id');
        session()->forget('smokeSelection');
        return View('pages.frontend.paymentsuccess')->with(compact('userInfo'));
    }
	
    //faq in frontpage
    public function showFaq($title) {
		$meta_info = Page::where('title','よくあるご質問')->first();
        if(!empty($meta_info))
		    $meta_info->slug = "faq/".$title;
       return View('pages.frontend.faq.faq_'.$title)->with('meta_info',$meta_info);
    }

    //contact in frontpage
    public function showContact() {
		$meta_info = Page::where('title','お問合せ')->first();
        return View('pages.frontend.contact')->with('meta_info',$meta_info);
    }

    //contact in frontpage
    public function showBusinessContact() {
		/*
		$fukuoka_classes = CarClass::where('car_shop_name', 4)
            ->orderBy('car_class_priority')
            ->get();
		$naha_classes = CarClass::where('car_shop_name', 5)
            ->orderBy('car_class_priority')
            ->get();
		*/
		$meta_info = Page::where('title','法人お問合せ')->first();
        return View('pages.frontend.business-contact')->with('meta_info',$meta_info);
    }

    //insurance in frontpage
    public function showInsurance() {
        return View('pages.frontend.insurance');
    }

    //transactions in frontpage
    public function showTransactions() {
        return View('pages.frontend.transactions');
    }

    //first page in frontpage
    public function showFirst() {
        return View('pages.frontend.first');
    }

    //Rules page in frontpage
    public function showRules() {
        return View('pages.frontend.rules');
    }

    //Agreement page in frontpage
    public function showAgreement() {
        return View('pages.frontend.agreement');
    }

    //Policy page in frontpage
    public function showPolicy() {
        return View('pages.frontend.policy');
    }

    //Booking page in frontpage
    public function showBooking() {
        return View('pages.frontend.booking');
    }

    //passing page in frontpage
    public function showPassing() {
        return View('pages.frontend.passing');
    }

    private function get_campaigns($region_code,$start,$end, $max_num, $page = 1) {

//        $start = date('Y-m-d',strtotime('today'));
//        $end = date('Y-m-d',strtotime('today +15 days'));
        $start = date('Y-m-d',strtotime($start.' -1 day'));
        $end = date('Y-m-d',strtotime($end.' +1 day'));
        $date_array = [];
        for($k = 0; $k < 16; $k++) {
            $date_array[] = date('Y-m-d', strtotime($start.' +'.$k.' days'));
        }

        if($region_code == 'all') {
            $shops = Shop::all();
        } else {
            $shops = Shop::where('region_code', '=', $region_code)->get();
        }

        $tmps = [];
        $dates = [];
        $count = 0;

        foreach ($shops as $shop) {
            $shop_name = $shop->name;
            $region_code = $shop->region_code;

            $cars = \DB::table('car_class_model AS cm')
                ->join('car_class AS c', 'cm.class_id', '=', 'c.id')
                ->leftjoin('car_inventory AS i', 'cm.model_id', '=', 'i.model_id')
                ->where('i.delete_flag', 0)
                ->where('i.status', 1)
                ->where('c.car_shop_name', $shop->id)
                ->where('i.shop_id', $shop->id)
                ->where('c.status', 1)
//                ->where('c.id', 49)
//                ->where('i.id', 15)
                ->select(['cm.class_id', 'i.numberplate4 AS car_no', 'c.name AS class_name', 'cm.model_id', 'i.id', 'i.shortname', 'i.max_passenger', 'i.smoke'])
                ->orderBy('c.car_class_priority')
                ->orderBy('cm.priority')
                ->orderBy('i.smoke', 'desc')
                ->orderBy('i.priority')
                ->get();
            $oldClassID = 0;
            foreach($cars as $car) {
                // get bookings
                $bookings = \DB::select(
                    "SELECT * FROM bookings 
                WHERE `inventory_id`=? 
                AND `status` IN (1,2,3,4,5,6,7,10) 
                AND ( (DATE( `departing` ) >= ? AND DATE( `departing` ) <= ?) 
                OR ( 
                    ( DATE( `returning` ) >= ? AND DATE( `returning` ) <= ? )  
                    OR ( DATE( `returning_updated` ) >= ? AND DATE( `returning_updated` ) <= ?)
                ) 
                OR (DATE( `departing` ) <= ? 
                    AND 
                    ( DATE( `returning` ) >= ? OR DATE(`returning_updated` ) >= ?)
                ))",
                    [ $car->id, $start, $end, $start, $end, $start, $end, $start, $end, $end]
                );

//                var_dump($bookings); //exit();

                // get inspoections
                $query = "select begin_date, end_date from car_inspections WHERE inventory_id=? AND delete_flag=0 AND status<3 AND (
(begin_date >= ? AND begin_date <= ?) OR (end_date >= ? AND end_date <= ?) OR (begin_date <= ? AND end_date >= ?))";
                $params = [$car->id, $start, $end, $start, $end, $start, $end];
                $inspections = \DB::select($query, $params);

                $array_used = array_fill(0, 16, 0);
                foreach ($bookings as $booking) {
                    $first = date('Y-m-d', strtotime($booking->departing));
                    $rt = date('Y-m-d', strtotime($booking->returning));
                    $rtu = date('Y-m-d', strtotime($booking->returning_updated));
                    if(strtotime($first) < strtotime($start)) { $first = $start; }
                    $last = (strtotime($rtu) > strtotime($rt) ) ? $rtu : $rt;
                    if(strtotime($last) > strtotime($end)) $last = $end;
                    $length = ServerPath::dateDiff($first, $last);
                    $pos = ServerPath::dateDiff($start, $first);
                    for($k = $pos; $k <= $pos + $length; $k++) {
                        $array_used[$k] = 1;
                    }
                }

                foreach ($inspections as $ins) {
                    $first = $ins->begin_date;
                    if(strtotime($first) < strtotime($start)) { $first = $start; }
                    $last = $ins->end_date;
                    if(strtotime($last) > strtotime($end)) $last = $end;

                    $length = ServerPath::dateDiff($first, $last);
                    $pos = ServerPath::dateDiff($start, $first);
                    for($k = $pos; $k <= $pos + $length; $k++) {
                        $array_used[$k] = 1;
                    }
                }

//                echo $car->id.' -> '.implode($array_used).'<br>';

                if(array_sum($array_used) == 16) continue;

                $tmp = [];
                $tmp['shop_id']         = $shop->id;
                $tmp['shop_name']       = $shop_name;
                $tmp['region_code']     = $shop->region_code;
                $tmp['car']             = $car->id;
                $tmp['smoke']           = $car->smoke;
                $tmp['class_id']        = $car->class_id;
                $tmp['model_id']        = $car->model_id;
                $tmp['class_name']      = $car->class_name;
                $tmp['max_passenger']   = $car->max_passenger;
                if($oldClassID !== $car->class_id) {
                    $paids = \DB::table('car_option_class as oc')
                        ->leftjoin('car_option as o', 'oc.option_id', '=', 'o.id')
                        ->where('oc.class_id', $car->class_id)
                        ->where('o.type', 0)
                        ->where('o.google_column_number', '<', 200)
                        ->where('o.delete_flag', 0)
                        ->get();
                    $frees = \DB::table('car_option_shop as os')
                        ->leftjoin('car_option as o', 'os.option_id','=','o.id')
                        ->where('os.shop_id', $shop->id)
                        ->where('o.type', 1)
                        ->where('o.delete_flag', 0)
                        ->get();
                    $oldClassID = $car->class_id;
                }
                $tmp['paid_options'] = $paids;
                $tmp['free_options'] = $frees;

                $search_string = implode($array_used);
                $offset = 0;
                $pos = strpos($search_string, '101', $offset);
                while ($pos !== false){
                    $begin = $date_array[$pos + 1];
                    $length = 1;
                    $tmp['date'] = $begin;
                    $tmp['vacancy'] = $length;
                    $tmp['week'] = ($pos < 7)? 1 : 2;
                    $end1 = $begin; //date('Y-m-d', strtotime($begin.' +'.($length-1).' days'));
                    $prices = ServerPath::getPriceFromClass($begin, $end1, $car->class_id, '0_1', $begin, $end1);
                    $tmp['original_price'] = $prices;
                    $coef = ($pos < 7)? 0.85 : 0.95;
                    $tmp['rent_price'] = round($prices * $coef);

                    $tmps[] = (object)$tmp;
                    $dates[] = $begin;
                    $count++;
                    if($count == $max_num) break;

                    $offset = $pos + 3;
                    $pos = strpos($search_string, '101', $offset);
                }

                $offset = 0;
                $pos = strpos($search_string, '1001', $offset);
                while ($pos !== false){
                    $begin = $date_array[$pos + 1];
                    $length = 2;
                    $tmp['date'] = $begin;
                    $tmp['vacancy'] = $length;
                    $tmp['week'] = ($pos < 7)? 1 : 2;
                    $end1 = date('Y-m-d', strtotime($begin.' +1 days'));
                    $prices = ServerPath::getPriceFromClass($begin, $end1, $car->class_id, '1_2', $begin, $end1);
                    $tmp['original_price'] = $prices;
                    $coef = ($pos < 7)? 0.85 : 0.95;
                    $tmp['rent_price'] = round($prices * $coef);

                    $tmps[] = (object)$tmp;
                    $dates[] = $begin;
//                    $count++;
//                    if($count == $max_num) break;

                    $offset = $pos + 4;
                    $pos = strpos($search_string, '1001', $offset);
                }
            }
        }
//exit();
        $availables = [];
        asort($dates);
        $keys = array_keys($dates);
        $count_all = count($tmps);

        if($max_num == 'all') {
            if($page > 0) {
                $start = ($page - 1) * 10;
                $last = $page * 10;
            } else {
                $start = 0;
                $last = count($tmps);
            }
        } else {
            $start = 0;
            $last = $max_num;
        }
        if($last > $count_all) $last = count($tmps);

        for($k = $start; $k < $last; $k++) {
            $availables[] = (object)$tmps[$keys[$k]];
        }
//        var_dump($availables); echo '<br>'; exit();
        return ['count' => $count_all, 'campaigns' => $availables];
    }

    //toppage in frontpage
    public function showToppage() {
//        echo ServerPath::countBookingsIncludeDate(16, '2018-07-15');
//        echo ServerPath::countInspectionsIncludeDate(16, '2018-07-15'); return;
        $categorys  = \DB::table('car_type_category')->get();
        $shops      = Shop::all();
		$caroptions = CarOption::where('type','0')->get();

        $blogposts  = BlogPost::orderBy('publish_date','desc')
                                ->take(5)
                                ->get();
		$lineup_ids = [3,49,6,51,10,54,32];
		$lineups = \DB::table('car_class')
                    ->leftJoin('car_shop as cs', 'car_class.car_shop_name','=','cs.id')
                    ->leftJoin('car_class_price as ccp', 'car_class.id','=','ccp.class_id')
                    ->select('car_class.*','cs.name as csname','cs.name_en as csname_en','cs.id as cs_id','ccp.n1d2_day1')
                    ->whereIn('car_class.id',$lineup_ids)
                    ->get();
		$lineups = $lineups->keyBy("id");
        if(time() >= strtotime(date('Y-m-d 18:30:00'))) {
            $depart_date = date('Y-m-d', strtotime('tomorrow'));
        } else {
            $depart_date = date('Y-m-d');
        }
        $return_date = date('Y-m-d', strtotime('tomorrow'));

        $start = date('Y-m-d', strtotime('tomorrow'));
        $end = date('Y-m-d', strtotime('tomorrow +13 days'));
        $data = $this->get_campaigns('all', $start, $end,'all', 0);
        $campaigns = $data['campaigns'];
        $hours = $this->hours;
//        var_dump($campaigns); return;

		$meta_info = Page::where('title','トップページ')->first();
        return View('pages.frontend.toppage',
            compact('hours', 'categorys', 'shops','caroptions','blogposts','lineups', 'depart_date', 'return_date','meta_info','campaigns'));
    }


    public function search_thankyou() {
        if(!session()->has('register_user_id') and session()->has('register_user_id') == 0){
            return redirect('/')->with('error', trans('usersmanagement.errorNotsave'));
        }

        return View('pages.frontend.search-thankyou')
            ->with([
                'booking_class_name'    => session()->get('booking_class_name', ''),
                'booking_class_id'      => session()->get('booking_class_id',''),
                'booking_price'         => session()->get('booking_price',''),
                'booking_options'       => session()->get('booking_options',''),
                'order_number'          => session()->get('order_number')   // for affiliate tag
            ]);
    }

    //get post from facebook
    public function getFBPost(){
        $client_id = '142845742891220';
        $client_secret = '097bea2b8bb8d5d221963b0da0701708';

        //$client_id = '192527314698639';
        //$client_secret = '76a153779d3f99b23b44a4d3d3d37523';


        if(!isset($_SESSION['accesstoken'])) {
            $token = 'https://graph.facebook.com/oauth/access_token?client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=client_credentials';
            $token = file_get_contents($token); // returns 'accesstoken=APP_TOKEN|APP_SECRET'
            $data11 = json_decode($token ,true);
            $_SESSION['accesstoken'] = $data11['access_token'];
        }else{
            $check = explode('access_token', $_SESSION['accesstoken']);
            if(count($check) > 2){
                unset($_SESSION['accesstoken']);
                $token = 'https://graph.facebook.com/oauth/access_token?client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=client_credentials';
                $token = file_get_contents($token); // returns 'accesstoken=APP_TOKEN|APP_SECRET'
                $data11 = json_decode($token ,true);
                $_SESSION['accesstoken'] = $data11['access_token'];
            }
        }
        $data11 = json_decode($_SESSION['accesstoken'],true);
        if(isset($data11['access_token'])){
            $_SESSION['accesstoken'] = $data11['access_token'];
        }

        $token = $_SESSION['accesstoken'];
        $data = file_get_contents("https://graph.facebook.com/2085163965028340/posts?fields=full_picture,id,created_time,message,link,name,permalink_url&limit=10&access_token=".$token);
        //$data = file_get_contents("https://graph.facebook.com/115150235773986/posts?fields=full_picture,id,created_time,message,link,name,permalink_url&limit=10&access_token=".$token);
        $fbposts = json_decode($data);

        $kk = 0;
        $html = '';
        if(!empty($fbposts)){
            $data = $fbposts;
            for($i = 0; $i < count($data->data); $i++){
                $latest_post =  $data->data[$i];
                $text = '';
                if(!empty($latest_post->message))
                    $text = $latest_post->message;
                $title = '';
                if(!empty($latest_post->name))
                    $title = $latest_post->name;
                $photo = '';
                if(!empty($latest_post->full_picture))
                    $photo = $latest_post->full_picture;
                if(!empty($photo) && $photo != ""){
                    $kk++;
                    if($kk > 4) break;
                    $link = '';
                    if(!empty($latest_post->permalink_url))
                        $link = $latest_post->permalink_url;
                    list($width, $height, $type, $attr) = getimagesize($photo);
                    $ratio = "height:150px;";
                    if($width <= $height){
                        $ratio = "width:100%";
                    }
                    $html .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="fb-box" style="    height: 300px;">
                            <div style="height:150px;margin-bottom:10px;">
                                <a href="'.$link.'" target="_blank"><p style="    margin: 0 0 0px;overflow:hidden;height:150px;"><img class="center-block img-responsive" src="'.$photo.'" style="width:auto;'.$ratio.';min-height:150px;min-width:100%;max-width:500%;max-height:500px;" alt="3ギガプラン"></p>
                            </div>
                            <a href="'.$link.'" target="_blank"><p style="    margin: 0 0 0px;">'.$title.'</p></a>
                            <p style="height:100px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" class="eee ellipsis">'.$text.'</p>
                        </div>
                    </div>';
//}
                }
            }
        }
        return $html;
    }

    //get options from category
    public function getOptions(Request $request) {
        $category_id = $request->get('category_id');
        $dropoff_id = $request->get('dropoff_id');
        $slug = '';
        $shop = \DB::table('car_shop')->where('id', $dropoff_id)->first();
        if(!empty($shop)) {
            $slug = $shop->slug;
        }
        $options_null = array();

        $options = \DB::table('car_option as co')->select(['co.*']);
        if($slug != 'naha-airport') { // if shop is not okinawa, except smart driveout paid option
            $options = $options->where('co.google_column_number','!=','38'); //
        }
        $options = $options->where('co.type','0') //paid option
            ->orderby('order', 'asc')
            ->get();

        if(!empty($options)) {
            return Response::json($options);
        }else {
            return Response::json($options_null);
        }
    }

    //get options of shop
    public function getShopOptions(Request $request) {
        $user = Auth::user();
        $level = '0' ;
        $level_flag = 'false';
        if(!empty($user)) {
            $id = $user->id;
            $da = \DB::table('role_user as ru')
                ->leftjoin('roles as r', 'r.id','=','ru.role_id')
                ->where('ru.user_id',$id)->select(['r.level'])->first();
            $level = $da->level;
        }
        if($level == '5' || $level == '4') {//admin , subadmin
            $level_flag = 'true';
        }
        $dropoff_id = $request->get('shop_id');
        $paid_options = \DB::table('car_option as o')
            ->leftJoin('car_option_shop as s', 'o.id', '=', 's.option_id')
            ->select(['o.*', 's.shop_id'])
            ->where('s.shop_id', $dropoff_id);
            if($level_flag == 'false') {
                $paid_options = $paid_options->where('o.google_column_number','<' ,200) ;
            };
        $paid_options = $paid_options
            ->where('o.type', 0) // 0: paid options, 1: free options
            ->orderby('order', 'asc')
            ->get();

        $free_options = \DB::table('car_option as o')
            ->leftJoin('car_option_shop as s', 'o.id', '=', 's.option_id')
            ->select(['o.*', 's.shop_id'])
            ->where('s.shop_id', $dropoff_id)
            ->where('o.type', 1) // 0: paid options, 1: free options
            ->orderby('order', 'asc')
            ->get();

        return Response::json(['paid_options'=>$paid_options, 'free_options'=>$free_options]);
    }

    //get class name from model_id
    public function getClass($model_id){
        $class = \DB::table('car_class as cl')
            ->join('car_class_model as clm','clm.class_id','=', 'cl.id')
            ->where('clm.model_id',$model_id)
            ->select(['cl.*'])->get();

        return $class;
    }

    //get options like class
    public function getOptionsFromClass($class_id , $select_options){
        $add_opions = array();
        if(empty($select_options)) {
            $ops = \DB::table('car_option as co')
                ->join('car_option_class as coc', 'coc.option_id', '=', 'co.id')
                ->where('coc.class_id', $class_id)
                ->select(['co.*'])->get();
            foreach ($ops as $op) {
                array_push($add_opions, $op);
            }
        } else {
            foreach ($select_options as $opt_id) {
                $op = \DB::table('car_option as co')
                    ->join('car_option_class as coc', 'coc.option_id', '=', 'co.id')
                    ->where('coc.class_id', $class_id)
                    ->where('co.id', $opt_id)
                    ->select(['co.*'])->first();
                if (!is_null($op)) {
                    array_push($add_opions, $op);
                }
            }
        }
        return $add_opions;
    }

    //get options like class
    public function getPaidOptionsFromClass($class_id , $select_options){
        $add_opions = array();
        if(empty($select_options)) {
            $ops = \DB::table('car_option as co')
                ->join('car_option_class as coc', 'coc.option_id', '=', 'co.id')
                ->where('coc.class_id', $class_id)
                ->where('co.type', 0)
                ->select(['co.*'])->get();
            foreach ($ops as $op) {
                array_push($add_opions, $op);
            }
        } else {
            foreach ($select_options as $opt_id) {
                $op = \DB::table('car_option as co')
                    ->join('car_option_class as coc', 'coc.option_id', '=', 'co.id')
                    ->where('coc.class_id', $class_id)
                    ->where('co.type', 0)
                    ->where('co.id', $opt_id)
                    ->select(['co.*'])->first();
                if (!is_null($op)) {
                    array_push($add_opions, $op);
                }
            }
        }
        return $add_opions;
    }

    //get options like class
    public function getFreeOptionsFromClass($class_id , $select_options){
        $add_opions = array();
        if(empty($select_options)) {
            $ops = \DB::table('car_option as co')
                ->join('car_option_class as coc', 'coc.option_id', '=', 'co.id')
                ->where('coc.class_id', $class_id)
                ->where('co.type', 1)
                ->select(['co.*'])->get();
            foreach ($ops as $op) {
                array_push($add_opions, $op);
            }
        } else {
            foreach ($select_options as $opt_id) {
                $op = \DB::table('car_option as co')
                    ->join('car_option_class as coc', 'coc.option_id', '=', 'co.id')
                    ->where('coc.class_id', $class_id)
                    ->where('co.id', $opt_id)
                    ->where('co.type', 1)
                    ->select(['co.*'])->first();
                if (!is_null($op)) {
                    array_push($add_opions, $op);
                }
            }
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
    public function getShopName_en($shop_id){
        $shop = \DB::table('car_shop')->where('id', $shop_id)->first();
        if(!empty($shop))
            return $shop->name_en;
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
    //get category name from id
    public function getCategoryName_en($category_id){
        $category = \DB::table('car_type_category')
            ->where('id',$category_id)
            ->select(['*'])->first();
        if(!empty($category))
            return $category->name_en;
        else
            return "";
    }

    //class list
    public function classlist(){
        $class = CarClass::orderby('car_class_priority')->get();
        $count = 0;
        foreach ($class as $cl) {
            $class_id = $cl->id;
            $models = \DB::table('car_model as cm')
                ->leftjoin('car_class_model as ccm','ccm.model_id','=', 'cm.id')
                ->where('ccm.class_id',$class_id)
                ->select(['cm.id', 'cm.thumb_path'])->orderby('ccm.priority','asc')->get();
            $model_thumb_path = $cl->thumb_path;
            if(!empty($models)) {
                foreach($models as $m) {
                    if(!empty($m->thumb_path)) {
                        $model_thumb_path = $m->thumb_path;
                        break;
                    }
                }
            }
            $class[$count]->model_thumb_path = $model_thumb_path;
            $count++;
        }
        return $class;
    }


    public function getCarOptions(Request $request){
        if($request->ajax()){
            $car_class_id = $request->get('car_class');
            $car_options  = CarClass::with('classOptions')
                                    ->where('id', $car_class_id)
                                    ->first();
            return Response::json($car_options); die;
        }
    }

    public function showbubblestep(Request $request){
        if($request->ajax()){

            $step_number = $request->get('step_num');
            $others      = $passArr     = [];
            if($step_number == 0){
                $passArr     = array('button'=>'step', 'step_number'=>$step_number);
            }
            if($step_number == 1){
                $carClass    = CarClass::whereIn('name',['CW2','HW','K2'])->get();
                $passArr     = array('button'=>'step', 'step_number'=>$step_number, 'carClass'=>$carClass);
            }
            if($step_number  == 2){
                $car_class_id = $request->get('car_class');
                $car_options  = CarClass::with('classOptions')
                                        ->where('id', $car_class_id)
                                        ->first();
                $insurance_price = ServerPath::getInsurancePrice2($car_class_id);
                $insurance_price1 = $insurance_price['ins1'];
                $insurance_price2 = $insurance_price['ins2'];
                $passArr      = array('button'=>'step', 'step_number'=>$step_number, 'car_options'=>$car_options,'insurance_price1'=>$insurance_price1,'insurance_price2'=>$insurance_price2);
            }
            if($step_number  == 3){
                $car_options  = $request->get('car_options');
                $shops        = Shop::all();

                // Booking Hours available
                 $current_hour = date('H');
                 if(strtotime(date('H:i')) >= strtotime('18:30')){
                    $time = '09:00';
                    $booking_available_date = date('Y-m-d',time()+86400);
                 }else if(strtotime(date('H:i')) <= strtotime('09:00')){
                    $time = '09:00';
                    $booking_available_date = date('Y-m-d');
                 }else{
                    $time = (date('i') < 30) ? ($current_hour).':30' : ($current_hour+1).':00';
                    $booking_available_date = date('Y-m-d');
                 }
                 $hours[] = $time;
                 for ($i = 0; $i <= 20; $i++){
                     $next = strtotime('+30mins', strtotime($time)); // add 30 mins
                     $time = date('H:i', $next); // format the next time
                     if((strtotime($time) > strtotime('19:30')) OR (strtotime($time) < strtotime('09:00')))
                     continue;
                     $hours[] = $time;
                 }

                $passArr      = array('button'=>'step', 'step_number'=>$step_number, 'shops'=>$shops, 'hours'=>$hours,'booking_available_date'=>$booking_available_date);
            }

            if($step_number  == 4){

            $data             = [];
            $input            = $request->all();
            $carClass         = \DB::table('car_class')->where('id', $input['car_class'])->orderBy('car_class_priority')->first();

            //get price
            $allPrice         = 0;
            $depart_date      = date('Y-m-d', strtotime($input['departing_date']));
            $night            = $input['numbers_nights'];
            $day              = $night+1; // to be fetched
            $return_date      = date('Y-m-d', strtotime($depart_date. ' + '.$night.' days')); // to be fetched
            $class_price      = ServerPath::getPriceFromClass($depart_date, $return_date, $input['car_class'], $night . "_" . $day,$depart_date, $return_date);

            $option_price     = 0;
            $data_option_ids    = $request->get('car_options');
            $data_option_names  = '';
            $data_option_numbers= '';
            $data_option_costs  = '';

            if($request->get('car_options')){
                $options          = explode(',',$input['car_options']);
                $select_options   = $this->getOptionsFromClass($input['car_class'], $options);
                foreach ($select_options as $op) {
                    $option_price        += $op->price;
                    $data_option_names   .= "$op->name,";
                    $data_option_numbers .= '1,';
                    $data_option_costs   .= "$op->price,";
                }
            }

            /*
            $others['data_insurance']              = 'no';
            $others['data_insurance_price1']       = 0;
            $others['data_insurance_price2']       = 0;
            */
            //$insurance_price1                    = 0;

            //if($request->get('car_insurance')){
                $insurance_price  = ServerPath::getInsurancePrice2($input['car_class']);
                $insurance_price1 = $insurance_price['ins1'] * $day;
                $insurance_price2 = $insurance_price['ins2'] * $day;

                $others['data_insurance']              = $request->get('car_insurance')?$request->get('car_insurance'):'no';
                $others['data_insurance_price1']       = $insurance_price1;
                $others['data_insurance_price2']       = $insurance_price2;
            //}

            $carPrice = $allPrice  = $class_price + $option_price;

            if($request->get('car_insurance')){
                $carPrice  = $allPrice + $insurance_price1+ $insurance_price2;
            }

            $car_count = 0;

            $models = \DB::table('car_class_model as cm')
                            ->leftJoin('car_model as m', 'm.id', '=', 'cm.model_id' )
                            ->leftJoin('car_type as ct', 'm.type_id', '=', 'ct.id')
                            ->where('cm.class_id', '=', $input['car_class']);
            $models = $models->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id')
                            ->where('ci.delete_flag', 0)
                            ->where('ci.status', 1);
            $models = $models->where('ci.shop_id',$input['departing_shop']);
            $cars   = $models->select(['ci.*','cm.class_id', 'm.name as model_name', 'cm.model_id'])->get();

            $start  = $depart_date." ".$input['departing_time'];
            $end    = $return_date." 19:30";
            $search_condition = "";
            foreach ($cars as $car) {
                $checkBook = ServerPath::getconfirmBooking($car->id, $start, $end, $search_condition);     // if car usable, true else false
                $checkInspection = ServerPath::getConfirmInspection($car->id, $start, $end,'', '');
                if($checkBook && $checkInspection){
                    $car_count++;
                }
            }

            $shopInfo  = Shop::where('id', $input['departing_shop'])->first();
            $others['data_depart_date'] = $depart_date;
            $others['data_depart_time'] = $input['departing_time'];
            $others['data_return_date'] = $return_date;
            $others['data_return_time'] = "19:30";
            $others['data_depart_shop'] = $input['departing_shop'];
            $others['data_depart_shop_name'] = $shopInfo->name;
            $others['data_return_shop'] = $input['departing_shop'];
            $others['data_return_shop_name'] = $shopInfo->name;
            $others['data_price_rent']  = $class_price;

            $others['data_option_ids']   = $data_option_ids;
            $others['data_option_names'] = rtrim($data_option_names,',');
            $others['data_option_numbers'] = rtrim($data_option_numbers,',');
            $others['data_option_costs'] = rtrim($data_option_costs,',');
            $others['data_price_all']    = rtrim($allPrice,',');
            $category  = \DB::table('car_type_category')->where('name','乗用車')->first();

            $others['data_car_category']   = $category->id;
            $others['data_class_name']     = $carClass->name;
            $others['data_car_photo']      = $carClass->thumb_path;
            $others['data_rent_days']      = $night.'泊'.$day.'日';
            $request_days = ServerPath::showRentDays($depart_date, $input['departing_time'], $return_date, "19:30");
            $others['data_rendates']       = $request_days['day'];
            $others['data_class_category'] = $category->name;

            $passArr = array('button'=>'step', 'step_number'=>$step_number, 'carClass'=>$carClass, 'carPrice'=> $carPrice,'car_count'=>$car_count);
            }
            return Response::json(['html'=>View::make('pages.frontend.topbubble',$passArr)->render(),'others'=>$others]);
        }
    }

    public function showbubbletext(Request $request){
        if($request->ajax()){
            $random_text = ["this is random text, lorem ipsum is simply dummy text in typing industry",
                            "There are many variations of passages of Lorem Ipsum available",
                            "Contrary to popular belief, Lorem Ipsum is not simply random text",
                            "This book is a treatise on the theory of ethics, very popular ",
                            "software like Aldus PageMaker including versions of Lorem Ipsum",
                            "It is a long established fact that a reader will be distracted by the ",
                            "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium do",
                            "avoids pleasure itself, because it is pleasure, but because those who do",
                            "On the other hand, we denounce with righteous indignation and dislike men who are so beguiled ",
                            "In a free hour, when our power of choice is untrammelled and when nothing",
                            "that they cannot foresee the pain and trouble that are bound to ensue",
                            "On the other hand, we denounce with righteous indignation and dislike",
                            "duty through weakness of will, which is the same as saying through shrinking from",
                            "every pleasure is to be welcomed and every pain avoided. But in certain circumstances and",
                            "The wise man therefore always holds in these matters to this principle of selection"
                            ];
            return Response::json(View::make('pages.frontend.topbubble', array('button'=>'text','step_number'=>9999, 'random_text'=>$random_text[array_rand($random_text, 1)]))->render());
        }
    }

    public function forgotpassword(Request $request) {
//        if(!$request->has('email')) {
//            return back()->withErrors(array('errors'=> ['email'=>'no-email']));
//        }
        return View('auth.passwords.forgotpassword');
    }

    public function resetpassword(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|max:255|exists:users',
                'password' => 'required|confirmed|min:6'
//                    'name'  => 'required|unique:users'
            ],
            [
                'email.required'    => trans('auth.emailRequired'),
                'email.email'       => trans('auth.emailInvalid'),
                'email.exists'      => trans('auth.emailExists'),
                'password.confirmed'=> trans('auth.passwordConfirmed')
//                    'name.unique'       => 'Your name already used'
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $password = $request->get('password');
        $returnValue = \DB::table('users')
        ->where('email', $request->get('email'))
        ->update(['password'=>bcrypt($password)]);
        if($returnValue == 0){
            return back()->with(['errors'=>['update'=>false]])->withInput();
        }

        return redirect('/login')->with('success', trans('usersmanagement.createSuccess'));
    }

    public function sendPwdResetLink(Request $request) {
        if(!$request->has('email')) {
            return array('status'=>'success', 'error'=>'no-email');
        }
        $email = $request->get('email');
        if(ServerPath::lang() == 'ja')
            $template = \DB::table('mail_templates')->where('mailname', 'user_password_reset_user')->first();
        else
            $template = \DB::table('mail_templates')->where('mailname', 'user_password_reset_user_en')->first();
        if(!empty($template)) {
            $subject = $template->subject;
            $message = $template->content;
            $message = str_replace('{user_name}', '親愛なるクライアント', $message);
            $message = str_replace('{url}', '', $message);
            $content = $message;
            $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $template->sender, 'email' => $email);
            $data[] = $data1;
        } else {
            return array('status'=>'error', 'error'=>'no-email-template');
        }
        $finaldata = array('data' => json_encode($data, JSON_UNESCAPED_UNICODE));
//                var_dump($data); return;

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
            return array('status'=>'success', 'error'=>'');
        } catch (Exception $e) {
            return array('status'=>'error', 'error'=>'email-sending-failure');
        }

    }

    public function getOptionsByShopid(Request $request)
    {
        $shop_id = $request->get('shop_id');

        $options = \DB::table('car_option_shop')
        ->leftJoin('car_option', function($join) {
              $join->on('car_option.id', '=', 'car_option_shop.option_id');
            })
        ->where('car_option_shop.shop_id','=', $shop_id)->get();

        $json['result'] = $options;
        echo json_encode($json);
    }
}
