<?php

namespace App\Http\DataUtil;


use App\Http\Requests;
use App\Models\CarOption;
use Illuminate\Support\Facades\Route;
use DB;
use DateTime;
use App;
use Session;
class ServerPath
{
    //get route
    public static function getRoute()
    {
        $route = Route::getFacadeRoot()->current()->uri();
        return $route;
    }
    //get sub route
    public static function getSubRoute()
    {
        $route = Route::getFacadeRoot()->current()->uri();
        $route_path = explode('/',$route);
        if(!empty($route_path[1])) $subroute = $route_path[1];
        else $subroute = "";
        return $subroute;
    }

    //get pirce from stardate, end_date, inventory_id, selected_day(night_day)
    public static function getPrice($start_date, $end_date, $inventory_id, $selected_day)
    {
        $price  = 0;
        //get class id
        $class     = \DB::table('car_inventory as ci')
            ->join('car_class_model as ccm','ccm.model_id','=','ci.model_id')
            ->join('car_class as cc','cc.id','=','ccm.class_id')
            ->select(['cc.*'])
            ->where('ci.id',$inventory_id)->first();

        if(empty($class)) return $price;

        $class_id = $class->id;
        //get from custom price
//        $prices = \DB::table('car_class_price_custom')
//            ->where('class_id',$class_id)
//            ->where('startdate',$start_date)->first();
        $prices = \DB::table('car_class_price_custom')
            ->where('class_id',$class_id)
            ->where(function ($query) use($start_date ,$end_date) {
                $query->where(function ($query1) use($start_date) {
                    $query1->wheredate('startdate', '<=', $start_date)
                        ->wheredate('enddate', '>=', $start_date);
                })->orwhere(function ($query1) use($end_date) {
                    $query1->wheredate('startdate', '<=', $end_date)
                        ->wheredate('enddate', '>=', $end_date);
                });
            })->orderby('startdate' ,'asc')
            ->first();

        if(!empty($prices)) {
            $price = ServerPath::getPriceFromClassCustom($start_date, $end_date, $class_id, $selected_day);
            return $price;
        }

        //get normal price
        if(empty($prices)){
            $prices = \DB::table('car_class_price')
                ->where('class_id',$class_id)->first();
        }

        $night  = (int)$selected_day[0];
        $day    = (int)$selected_day[1];
        switch(true) {
            case $night == 1&& $day == 1 :
                $price = ServerPath::setIntegerRound($prices->d1_total);
                break;
            case $night == 1&& $day > 1 :
                $price = ServerPath::setIntegerRound($prices->n1d2_total);
                break;
            case $night == 2 :
                $price = ServerPath::setIntegerRound($prices->n2d3_total);
                break;
            case $night == 3 :
                $price = ServerPath::setIntegerRound($prices->n3d4_total);
                break;
            case $night > 3 :
                $price = (ServerPath::setIntegerRound($prices->additional_total)*$night) ;
                break;
        }

        return $price;
    }
    //get basic price
    public static function getPricebasic($class_id, $origin_day ,$prev_day, $current_day) {
        $price = 0;

        $current_night  = (int)$current_day['night'];
        $current_day    = (int)$current_day['day'];
        $origin_night   = (int)$origin_day[0];
        $origin_day     = (int)$origin_day[1];
        $prices = \DB::table('car_class_price')
            ->where('class_id',$class_id)->first();

        if(!empty($prices)) {
            switch (true) {
                case $origin_night == 0 && $origin_day == 1 :
                    $price =  ServerPath::setIntegerRound($prices->d1_total);
                    break;
                case $origin_night == 1 && $origin_day == 1 :
                    $price =  ServerPath::setIntegerRound($prices->d1_total);
                    break;
                case $origin_night == 1 && $origin_day > 1 :
                    if($prev_day == 0) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n1d2_day1);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n1d2_day1) +  ServerPath::setIntegerRound($prices->n1d2_day2);
                    }
                    if($prev_day == 1) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n1d2_day2);
                    }
                    break;
                case $origin_night == 2 :
                    if($prev_day == 0) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n2d3_day1);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n2d3_day1) +  ServerPath::setIntegerRound($prices->n2d3_day2);
                        if ($current_day == 3)
                            $price =  ServerPath::setIntegerRound($prices->n2d3_day1) +  ServerPath::setIntegerRound($prices->n2d3_day2) +  ServerPath::setIntegerRound($prices->n2d3_day3);
                    }
                    if($prev_day == 1) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n2d3_day2);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n2d3_day2) +  ServerPath::setIntegerRound($prices->n2d3_day3) ;
                    }
                    if($prev_day == 2) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n2d3_day3);
                    }
                    break;
                case $origin_night == 3 :
                    if($prev_day == 0) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day1);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day1) +  ServerPath::setIntegerRound($prices->n3d4_day2);
                        if ($current_day == 3)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day1) +  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3);
                        if ($current_day == 4)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day1) +  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4);
                    }
                    if($prev_day == 1) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day2);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3);
                        if ($current_day == 3)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4);
                    }
                    if($prev_day == 2) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day3);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4);
                    }
                    if($prev_day == 3) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day4);
                    }
                    break;
                case $origin_night > 3 :
                    if($prev_day == 0) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day1);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day1) +  ServerPath::setIntegerRound($prices->n3d4_day2);
                        if ($current_day == 3)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day1) +  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3);
                        if ($current_day == 4)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day1) +  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4);
                        if ($current_day > 4)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_total) + ( ServerPath::setIntegerRound($prices->additional_total) * ($current_day - 4));
                    }
                    if($prev_day == 1) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day2);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3);
                        if ($current_day == 3)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4);
                        if ($current_day == 4)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4) +  ServerPath::setIntegerRound($prices->additional_total) ;
                        if ($current_day > 4)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day2) +  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4) + ( ServerPath::setIntegerRound($prices->additional_total)*($current_day-3)) ;
                    }
                    if($prev_day == 2) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day3);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4);
                        if ($current_day == 3)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4) +  ServerPath::setIntegerRound($prices->additional_total);
                        if ($current_day > 3)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day3) +  ServerPath::setIntegerRound($prices->n3d4_day4) + ( ServerPath::setIntegerRound($prices->additional_total)*($current_day-2)) ;
                    }
                    if($prev_day == 3) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day4);
                        if ($current_day == 2)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day4) +  ServerPath::setIntegerRound($prices->additional_total);
                        if ($current_day > 2)
                            $price =  ServerPath::setIntegerRound($prices->n3d4_day4) + ( ServerPath::setIntegerRound($prices->additional_total)*($current_day-1)) ;
                    }
                    if($prev_day == 4) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->additional_total);
                        if ($current_day > 1)
                            $price =  ServerPath::setIntegerRound($prices->additional_total)*$current_day ;
                    }
                    if($prev_day > 4) {
                        if ($current_day == 1)
                            $price =  ServerPath::setIntegerRound($prices->additional_total);
                        if ($current_day > 1)
                            $price =  ServerPath::setIntegerRound($prices->additional_total)*$current_day ;
                    }
                    break;
            }
        }
        return $price;
    }
    //get basic price with additional day for extend
    public static function getPricebasicExtend($class_id, $origin_day ,$prev_day, $current_day) {
        $price = 0;

        $current_night  = (int)$current_day['night'];
        $current_day    = (int)$current_day['day'];
        $origin_night   = (int)$origin_day[0];
        $origin_day     = (int)$origin_day[1];
        $prices = \DB::table('car_class_price')
            ->where('class_id',$class_id)->first();

        if(!empty($prices)) {
            $price =  ServerPath::setIntegerRound($prices->additional_day);
            $price = $price * $current_day;
        }
        return $price;
    }

    //get custom price
    public static function getPricecustom($id, $origin_day, $prev_day, $current_day,$start_date, $end_date) {
    $price = 0;
    $current_night  = (int)$current_day['night'];
    $current_day    = (int)$current_day['day'];
    $origin_night   = (int)$origin_day[0];
    $origin_day     = (int)$origin_day[1];

    $prices = \DB::table('car_class_price_custom')
        ->where('class_id',$id)->whereDate('startdate','<=',$start_date)->whereDate('enddate','>=',$end_date)->first();
    if(!empty($prices)) {
        switch (true) {
            case $origin_night == 0 && $origin_day == 1 :
                $price = ServerPath::setIntegerRound($prices->d1_total);
                break;
            case $origin_night == 1 && $origin_day == 1 :
                $price = ServerPath::setIntegerRound($prices->d1_total);
                break;
            case $origin_night == 1 && $origin_day > 1 :
                if($prev_day == 0) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n1d2_day1);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n1d2_day1) + ServerPath::setIntegerRound($prices->n1d2_day2);
                }
                if($prev_day == 1) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n1d2_day2);
                }
                break;
            case $origin_night == 2 :
                if($prev_day == 0) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n2d3_day1);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n2d3_day1) + ServerPath::setIntegerRound($prices->n2d3_day2);
                    if ($current_day == 3)
                        $price = ServerPath::setIntegerRound($prices->n2d3_day1) + ServerPath::setIntegerRound($prices->n2d3_day2) + ServerPath::setIntegerRound($prices->n2d3_day3);
                }
                if($prev_day == 1) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n2d3_day2);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n2d3_day2) + ServerPath::setIntegerRound($prices->n2d3_day3) ;
                }
                if($prev_day == 2) {
                    if ($current_day == 1)
                        $price = $prices->n2d3_day3;
                }
                break;
            case $origin_night == 3 :
                if($prev_day == 0) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day1);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day1) + ServerPath::setIntegerRound($prices->n3d4_day2);
                    if ($current_day == 3)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day1) + ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3);
                    if ($current_day == 4)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day1) + ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4);
                }
                if($prev_day == 1) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day2);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3);
                    if ($current_day == 3)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4);
                }
                if($prev_day == 2) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day3);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4);
                }
                if($prev_day == 3) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day4);
                }
                break;
            case $origin_night > 3 :
                if($prev_day == 0) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day1);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day1) + ServerPath::setIntegerRound($prices->n3d4_day2);
                    if ($current_day == 3)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day1) + ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3);
                    if ($current_day == 4)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day1) + ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4);
                    if ($current_day > 4)
                        $price = ServerPath::setIntegerRound($prices->n3d4_total) + (ServerPath::setIntegerRound($prices->additional_total) * ($current_day - 4));

                }
                if($prev_day == 1) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day2);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3);
                    if ($current_day == 3)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4);
                    if ($current_day == 4)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4) + ServerPath::setIntegerRound($prices->additional_total) ;
                    if ($current_day > 4)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day2) + ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4) + (ServerPath::setIntegerRound($prices->additional_total)*($current_day-3)) ;
                }
                if($prev_day == 2) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day3);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4);
                    if ($current_day == 3)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4) + ServerPath::setIntegerRound($prices->additional_total);
                    if ($current_day > 3)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day3) + ServerPath::setIntegerRound($prices->n3d4_day4) + (ServerPath::setIntegerRound($prices->additional_total)*($current_day-2)) ;
                }
                if($prev_day == 3) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day4);
                    if ($current_day == 2)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day4) + ServerPath::setIntegerRound($prices->additional_total);
                    if ($current_day > 2)
                        $price = ServerPath::setIntegerRound($prices->n3d4_day4) + (ServerPath::setIntegerRound($prices->additional_total)*($current_day-1)) ;
                }
                if($prev_day == 4) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->additional_total);
                    if ($current_day > 1)
                        $price = ServerPath::setIntegerRound($prices->additional_total)*$current_day ;
                }
                if($prev_day > 4) {
                    if ($current_day == 1)
                        $price = ServerPath::setIntegerRound($prices->additional_total);
                    if ($current_day > 1)
                        $price = ServerPath::setIntegerRound($prices->additional_total)*$current_day ;
                }
                break;
        }
    }
    return $price;
}

    public static function getPricecustomExtend($id, $origin_day, $prev_day, $current_day,$start_date, $end_date) {
        $price = 0;
        $current_night  = (int)$current_day['night'];
        $current_day    = (int)$current_day['day'];
        $origin_night   = (int)$origin_day[0];
        $origin_day     = (int)$origin_day[1];

        $prices = \DB::table('car_class_price_custom')
            ->where('class_id',$id)->whereDate('startdate','<=',$start_date)->whereDate('enddate','>=',$end_date)->first();
        if(!empty($prices)) {
            $price = ServerPath::setIntegerRound($prices->additional_day);
            $price = $price * $current_day;
        }
        return $price;
    }
    public static function getPriceFromClassCustomExist($start_date, $end_date, $class_id, $selected_day) {
        $flag = false;
        $prices = \DB::table('car_class_price_custom')
            ->where('class_id',$class_id)
            ->where(function ($query) use($start_date ,$end_date) {
                $query->where(function ($query1) use($start_date) {
                    $query1->wheredate('startdate', '<=', $start_date)
                        ->wheredate('enddate', '>=', $start_date);
                })->orwhere(function ($query1) use($end_date) {
                    $query1->wheredate('startdate', '<=', $end_date)
                        ->wheredate('enddate', '>=', $end_date);
                });
            })->orderby('startdate' ,'asc')
            ->first();
        if(!empty($prices)) $flag = true;
        return $flag;
    }
    //get custom price from start to end
    public static function getPriceFromClassCustom($start_date, $end_date, $class_id, $selected_day, $origin_start, $origin_end) {
        $price  = 0;
        $origin_day = explode("_",$selected_day);
        $night  = (int)$origin_day[0];
        $day    = (int)$origin_day[1];

        $prices = \DB::table('car_class_price_custom')
            ->where('class_id',$class_id)
            ->where(function ($query) use($start_date ,$end_date) {
                $query->where(function ($query1) use($start_date ,$end_date) {
                    $query1->wheredate('startdate', '<=', $start_date)
                        ->wheredate('enddate', '>=', $end_date);
                })->orwhere(function ($query1) use($start_date,$end_date) {
                    $query1->wheredate('startdate', '>=', $start_date)
                        ->wheredate('startdate', '<=', $end_date)
                        ->wheredate('enddate', '>=', $end_date);
                })->orwhere(function ($query1) use($start_date,$end_date) {
                    $query1->wheredate('startdate', '<=', $start_date)
                        ->wheredate('enddate', '>=', $start_date)
                        ->wheredate('enddate', '<=', $end_date);
                })->orwhere(function ($query1) use($start_date,$end_date) {
                    $query1->wheredate('startdate', '>=', $start_date)
                        ->wheredate('enddate', '<=', $end_date);
                });
            })->orderby('startdate' ,'asc')
            ->first();

        if(!empty($prices)) {
            $startdate_custom  = $prices->startdate;
            $enddate_custom    = $prices->enddate;

            if(strtotime($start_date) > strtotime($startdate_custom)) {

                if(strtotime($end_date) > strtotime($enddate_custom)) {
                    $custom_days = ServerPath::showRentDays($start_date, '00:00:00',$enddate_custom , '00:00:00');
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;

                    $custom_price = ServerPath::getPricecustom($class_id,$origin_day, $prev_day ,$custom_days,$start_date, $enddate_custom);

                    $first_day = date('Y-m-d', strtotime('1 day', strtotime($enddate_custom)));
                    $basic_days = ServerPath::showRentDays($first_day, '00:00:00',$end_date, '00:00:00');
                    //recheck next status whether exist custom price or not
                    $custom_flag = ServerPath::getPriceFromClassCustomExist($first_day, $end_date,$class_id,$selected_day);
                    if($custom_flag == true) {
                        $basic_price = ServerPath::getPriceFromClassCustom($first_day, $end_date, $class_id, $selected_day, $origin_start, $origin_end);
                    }else {
                        $prev_day = (int)$custom_days['day'];
                        if($first_day != $origin_start)
                            $prev_day = (strtotime($first_day) - strtotime($origin_start))/86400;
                        $basic_price = ServerPath::getPricebasic($class_id, $origin_day, $prev_day, $basic_days);
                    }
                    $price = $custom_price+$basic_price;
                }
                if(strtotime($end_date) <= strtotime($enddate_custom)) {
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;
                    $custom_days = ServerPath::showRentDays($start_date, '00:00:00', $end_date, '00:00:00');
                    $price = ServerPath::getPricecustom($class_id, $origin_day, $prev_day,$custom_days, $start_date, $end_date);
                }
            }

            if(strtotime($start_date) < strtotime($startdate_custom)) {

                if(strtotime($end_date) > strtotime($enddate_custom)) {

                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;

                    $next_day = date('Y-m-d', strtotime('-1 day', strtotime($startdate_custom)));
                    $basic_days_first = ServerPath::showRentDays($start_date, '00:00:00',$next_day , '00:00:00');
                    $basic_price_first = ServerPath::getPricebasic($class_id, $origin_day, $prev_day, $basic_days_first);

                    $prev_day = (int)$basic_days_first['day'];

                    if($startdate_custom != $origin_start)
                        $prev_day = (strtotime($startdate_custom) - strtotime($origin_start))/86400;

                    $custom_days = ServerPath::showRentDays($startdate_custom, '00:00:00',$enddate_custom , '00:00:00');
                    $custom_price = ServerPath::getPricecustom($class_id,$origin_day, $prev_day ,$custom_days,$startdate_custom,$enddate_custom);


                    $first_day = date('Y-m-d', strtotime('1 day', strtotime($enddate_custom)));
                    if($first_day != $origin_start)
                        $prev_day = (strtotime($first_day) - strtotime($origin_start))/86400;

                    $custom_flag = ServerPath::getPriceFromClassCustomExist($first_day, $end_date,$class_id,$selected_day);
                    if($custom_flag == true) {
                        $basic_price_second = ServerPath::getPriceFromClassCustom($first_day, $end_date, $class_id, $selected_day, $origin_start, $origin_end);
                    }else {
                        $basic_days_second = ServerPath::showRentDays($first_day, '00:00:00', $end_date, '00:00:00');
                        $basic_price_second = ServerPath::getPricebasic($class_id, $origin_day, $prev_day, $basic_days_second);
                    }
                    $price = $custom_price+$basic_price_first+$basic_price_second;
                }
                if(strtotime($end_date) <= strtotime($enddate_custom)) {
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;
                    $next_day = date('Y-m-d', strtotime('-1 day', strtotime($startdate_custom)));
                    $basic_days_first = ServerPath::showRentDays($start_date, '00:00:00',$next_day , '00:00:00');
                    $basic_price_first = ServerPath::getPricebasic($class_id, $origin_day, $prev_day, $basic_days_first);

                    $prev_day = (int)$basic_days_first['day'];
                    if($next_day != $origin_start)
                        $prev_day = (strtotime($startdate_custom) - strtotime($origin_start))/86400;
                    $custom_days = ServerPath::showRentDays($startdate_custom, '00:00:00',$end_date , '00:00:00');
                    $custom_price = ServerPath::getPricecustom($class_id,$origin_day, $prev_day ,$custom_days,$startdate_custom,$end_date);

                    $price = $basic_price_first+$custom_price;
                }
            }
            if(strtotime($start_date) == strtotime($startdate_custom)) {

                if(strtotime($end_date) > strtotime($enddate_custom)) {
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;
                    $custom_days = ServerPath::showRentDays($start_date, '00:00:00',$enddate_custom , '00:00:00');
                    $custom_price = ServerPath::getPricecustom($class_id,$origin_day,$prev_day,$custom_days,$start_date,$enddate_custom);

                    $prev_day = (int)$custom_days['day'];
                    $first_day = date('Y-m-d', strtotime('1 day', strtotime($enddate_custom)));
                    if($enddate_custom != $origin_start)
                        $prev_day = (strtotime($first_day) - strtotime($origin_start))/86400;

                    $basic_days = ServerPath::showRentDays($first_day, '00:00:00',$end_date, '00:00:00');
                    //recheck next status whether exist custom price or not
                    $custom_flag = ServerPath::getPriceFromClassCustomExist($first_day, $end_date,$class_id,$selected_day);

                    if($custom_flag == true) {
                        $basic_price = ServerPath::getPriceFromClassCustom($first_day, $end_date, $class_id, $selected_day , $origin_start, $origin_end);
                    }else {
                        $basic_price = ServerPath::getPricebasic($class_id, $origin_day, $prev_day, $basic_days);
                    }

                    $price = $custom_price + $basic_price;
                }
                if(strtotime($end_date) <= strtotime($enddate_custom)) {
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;
                    $custom_days = ServerPath::showRentDays($start_date, '00:00:00',$end_date , '00:00:00');
                    $price = ServerPath::getPricecustom($class_id,$origin_day, $prev_day , $custom_days,$start_date,$end_date);

                }
            }
        }
        return $price;
    }
    //get custome extend price  from start to end
    public static function getPriceFromClassCustomExtend($start_date, $end_date, $class_id, $selected_day, $origin_start, $origin_end) {
        $price  = 0;
        $origin_day = explode("_",$selected_day);
        $night  = (int)$origin_day[0];
        $day    = (int)$origin_day[1];

        $prices = \DB::table('car_class_price_custom')
            ->where('class_id',$class_id)
            ->where(function ($query) use($start_date ,$end_date) {
                $query->where(function ($query1) use($start_date ,$end_date) {
                    $query1->wheredate('startdate', '<=', $start_date)
                        ->wheredate('enddate', '>=', $end_date);
                })->orwhere(function ($query1) use($start_date,$end_date) {
                    $query1->wheredate('startdate', '>=', $start_date)
                        ->wheredate('startdate', '<=', $end_date)
                        ->wheredate('enddate', '>=', $end_date);
                })->orwhere(function ($query1) use($start_date,$end_date) {
                    $query1->wheredate('startdate', '<=', $start_date)
                        ->wheredate('enddate', '>=', $start_date)
                        ->wheredate('enddate', '<=', $end_date);
                })->orwhere(function ($query1) use($start_date,$end_date) {
                    $query1->wheredate('startdate', '>=', $start_date)
                        ->wheredate('enddate', '<=', $end_date);
                });
            })->orderby('startdate' ,'asc')
            ->first();

        if(!empty($prices)) {
            $startdate_custom  = $prices->startdate;
            $enddate_custom    = $prices->enddate;

            if(strtotime($start_date) > strtotime($startdate_custom)) {

                if(strtotime($end_date) > strtotime($enddate_custom)) {
                    $custom_days = ServerPath::showRentDays($start_date, '00:00:00',$enddate_custom , '00:00:00');
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;

                    $custom_price = ServerPath::getPricecustomExtend($class_id,$origin_day, $prev_day ,$custom_days,$start_date, $enddate_custom);

                    $first_day = date('Y-m-d', strtotime('1 day', strtotime($enddate_custom)));
                    $basic_days = ServerPath::showRentDays($first_day, '00:00:00',$end_date, '00:00:00');
                    //recheck next status whether exist custom price or not
                    $custom_flag = ServerPath::getPriceFromClassCustomExist($first_day, $end_date,$class_id,$selected_day);
                    if($custom_flag == true) {
                        $basic_price = ServerPath::getPriceFromClassCustomExtend($first_day, $end_date, $class_id, $selected_day, $origin_start, $origin_end);
                    }else {
                        $prev_day = (int)$custom_days['day'];
                        if($first_day != $origin_start)
                            $prev_day = (strtotime($first_day) - strtotime($origin_start))/86400;
                        $basic_price = ServerPath::getPricebasicExtend($class_id, $origin_day, $prev_day, $basic_days);
                    }
                    $price = $custom_price+$basic_price;
                }
                if(strtotime($end_date) <= strtotime($enddate_custom)) {
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;
                    $custom_days = ServerPath::showRentDays($start_date, '00:00:00', $end_date, '00:00:00');
                    $price = ServerPath::getPricecustomExtend($class_id, $origin_day, $prev_day,$custom_days, $start_date, $end_date);
                }
            }

            if(strtotime($start_date) < strtotime($startdate_custom)) {

                if(strtotime($end_date) > strtotime($enddate_custom)) {

                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;

                    $next_day = date('Y-m-d', strtotime('-1 day', strtotime($startdate_custom)));
                    $basic_days_first = ServerPath::showRentDays($start_date, '00:00:00',$next_day , '00:00:00');
                    $basic_price_first = ServerPath::getPricebasicExtend($class_id, $origin_day, $prev_day, $basic_days_first);

                    $prev_day = (int)$basic_days_first['day'];

                    if($startdate_custom != $origin_start)
                        $prev_day = (strtotime($startdate_custom) - strtotime($origin_start))/86400;

                    $custom_days = ServerPath::showRentDays($startdate_custom, '00:00:00',$enddate_custom , '00:00:00');
                    $custom_price = ServerPath::getPricecustomExtend($class_id,$origin_day, $prev_day ,$custom_days,$startdate_custom,$enddate_custom);


                    $first_day = date('Y-m-d', strtotime('1 day', strtotime($enddate_custom)));
                    if($first_day != $origin_start)
                        $prev_day = (strtotime($first_day) - strtotime($origin_start))/86400;

                    $custom_flag = ServerPath::getPriceFromClassCustomExist($first_day, $end_date,$class_id,$selected_day);
                    if($custom_flag == true) {
                        $basic_price_second = ServerPath::getPriceFromClassCustomExtend($first_day, $end_date, $class_id, $selected_day, $origin_start, $origin_end);
                    }else {
                        $basic_days_second = ServerPath::showRentDays($first_day, '00:00:00', $end_date, '00:00:00');
                        $basic_price_second = ServerPath::getPricebasicExtend($class_id, $origin_day, $prev_day, $basic_days_second);
                    }
                    $price = $custom_price+$basic_price_first+$basic_price_second;
                }
                if(strtotime($end_date) <= strtotime($enddate_custom)) {
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;
                    $next_day = date('Y-m-d', strtotime('-1 day', strtotime($startdate_custom)));
                    $basic_days_first = ServerPath::showRentDays($start_date, '00:00:00',$next_day , '00:00:00');
                    $basic_price_first = ServerPath::getPricebasicExtend($class_id, $origin_day, $prev_day, $basic_days_first);

                    $prev_day = (int)$basic_days_first['day'];
                    if($next_day != $origin_start)
                        $prev_day = (strtotime($startdate_custom) - strtotime($origin_start))/86400;
                    $custom_days = ServerPath::showRentDays($startdate_custom, '00:00:00',$end_date , '00:00:00');
                    $custom_price = ServerPath::getPricecustomExtend($class_id,$origin_day, $prev_day ,$custom_days,$startdate_custom,$end_date);

                    $price = $basic_price_first+$custom_price;
                }
            }
            if(strtotime($start_date) == strtotime($startdate_custom)) {

                if(strtotime($end_date) > strtotime($enddate_custom)) {
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;
                    $custom_days = ServerPath::showRentDays($start_date, '00:00:00',$enddate_custom , '00:00:00');
                    $custom_price = ServerPath::getPricecustomExtend($class_id,$origin_day,$prev_day,$custom_days,$start_date,$enddate_custom);

                    $prev_day = (int)$custom_days['day'];
                    $first_day = date('Y-m-d', strtotime('1 day', strtotime($enddate_custom)));
                    if($enddate_custom != $origin_start)
                        $prev_day = (strtotime($first_day) - strtotime($origin_start))/86400;

                    $basic_days = ServerPath::showRentDays($first_day, '00:00:00',$end_date, '00:00:00');
                    //recheck next status whether exist custom price or not
                    $custom_flag = ServerPath::getPriceFromClassCustomExist($first_day, $end_date,$class_id,$selected_day);

                    if($custom_flag == true) {
                        $basic_price = ServerPath::getPriceFromClassCustomExtend($first_day, $end_date, $class_id, $selected_day , $origin_start, $origin_end);
                    }else {
                        $basic_price = ServerPath::getPricebasicExtend($class_id, $origin_day, $prev_day, $basic_days);
                    }

                    $price = $custom_price + $basic_price;
                }
                if(strtotime($end_date) <= strtotime($enddate_custom)) {
                    $prev_day = 0;
                    if($start_date != $origin_start)
                        $prev_day = (strtotime($start_date) - strtotime($origin_start))/86400;
                    $custom_days = ServerPath::showRentDays($start_date, '00:00:00',$end_date , '00:00:00');
                    $price = ServerPath::getPricecustomExtend($class_id,$origin_day, $prev_day , $custom_days,$start_date,$end_date);

                }
            }
        }
        return $price;
    }
    //get pirce from stardate, end_date, class_id, selected_day(night_day)
    public static function getPriceFromClass($start_date, $end_date, $class_id, $selected_day, $origin_start, $origin_end)
{
    $price  = 0;
    $night_day = explode("_",$selected_day);
    $night  = (int)$night_day[0];
    $day    = (int)$night_day[1];
    //get from custom price
//        $prices = \DB::table('car_class_price_custom')
//            ->where('class_id',$class_id)
//            ->where('startdate',$start_date)->first();
    $prices = \DB::table('car_class_price_custom')
        ->where('class_id',$class_id)
        ->where(function ($query) use($start_date ,$end_date) {
            $query->where(function ($query1) use($start_date ,$end_date) {
                $query1->wheredate('startdate', '<=', $start_date)
                    ->wheredate('enddate', '>=', $end_date);
            })->orwhere(function ($query1) use($start_date,$end_date) {
                $query1->wheredate('startdate', '>=', $start_date)
                    ->wheredate('startdate', '<=', $end_date)
                    ->wheredate('enddate', '>=', $end_date);
            })->orwhere(function ($query1) use($start_date,$end_date) {
                $query1->wheredate('startdate', '<=', $start_date)
                    ->wheredate('enddate', '>=', $start_date)
                    ->wheredate('enddate', '<=', $end_date);
            })->orwhere(function ($query1) use($start_date,$end_date) {
                $query1->wheredate('startdate', '>=', $start_date)
                    ->wheredate('enddate', '<=', $end_date);
            });
        })->orderby('startdate' ,'asc')
        ->first();

    if(!empty($prices)) {
        $price = ServerPath::getPriceFromClassCustom($start_date, $end_date, $class_id, $selected_day, $origin_start, $origin_end);
        return $price;
    }
    //get normal price
    $prev_day = 0;
    $basic_days = ServerPath::showRentDays($start_date, '00:00:00',$end_date, '00:00:00');
    $origin_day = array();
    $origin_day[0] = $basic_days['night'];
    $origin_day[1] = $basic_days['day'];

    $price = ServerPath::getPricebasic($class_id, $origin_day, $prev_day, $basic_days);

    /*if(empty($prices)){
        $prices = \DB::table('car_class_price')
            ->where('class_id',$class_id)->first();
    }

    if(!empty($prices)) {
        switch (true) {
            case $night == 0 && $day == 1 :
                $price = $prices->d1_total;
                break;
            case $night == 1 && $day == 1 :
                $price = $prices->d1_total;
                break;
            case $night == 1 && $day > 1 :
                $price = $prices->n1d2_total;
                break;
            case $night == 2 :
                $price = $prices->n2d3_total;
                break;
            case $night == 3 :
                $price = $prices->n3d4_total;
                break;
            case $night > 3 :
                $price = $prices->n3d4_total + ($prices->additional_total * ($day - 4));
                break;
        }
    }
    */
    return $price;
}
  //aget additional day price for extend day
    public static function getPriceFromClassExtend($start_date, $end_date, $class_id, $selected_day, $origin_start, $origin_end)
    {
        $price  = 0;
        $night_day = explode("_",$selected_day);
        $night  = (int)$night_day[0];
        $day    = (int)$night_day[1];
        $prices = \DB::table('car_class_price_custom')
            ->where('class_id',$class_id)
            ->where(function ($query) use($start_date ,$end_date) {
                $query->where(function ($query1) use($start_date ,$end_date) {
                    $query1->wheredate('startdate', '<=', $start_date)
                        ->wheredate('enddate', '>=', $end_date);
                })->orwhere(function ($query1) use($start_date,$end_date) {
                    $query1->wheredate('startdate', '>=', $start_date)
                        ->wheredate('startdate', '<=', $end_date)
                        ->wheredate('enddate', '>=', $end_date);
                })->orwhere(function ($query1) use($start_date,$end_date) {
                    $query1->wheredate('startdate', '<=', $start_date)
                        ->wheredate('enddate', '>=', $start_date)
                        ->wheredate('enddate', '<=', $end_date);
                })->orwhere(function ($query1) use($start_date,$end_date) {
                    $query1->wheredate('startdate', '>=', $start_date)
                        ->wheredate('enddate', '<=', $end_date);
                });
            })->orderby('startdate' ,'asc')
            ->first();

        if(!empty($prices)) {
            $price = ServerPath::getPriceFromClassCustomExtend($start_date, $end_date, $class_id, $selected_day, $origin_start, $origin_end);
            return $price;
        }
        //get normal price
        $prev_day = 0;
        $basic_days = ServerPath::showRentDays($start_date, '00:00:00',$end_date, '00:00:00');
        $origin_day = array();
        $origin_day[0] = $basic_days['night'];
        $origin_day[1] = $basic_days['day'];

        $price = ServerPath::getPricebasicExtend($class_id, $origin_day, $prev_day, $basic_days);
        return $price;
    }


    //get normal price for oneday
    public static function getOnedayNormalPriceFromClass($class_id)
    {
        $price  = 0;
        $prices = \DB::table('car_class_price')
            ->where('class_id',$class_id)
            ->first();
        if(!empty($prices)) {
            if(!empty($prices->additional_day))
                $price = $prices->additional_day;
        }
        return $price;
    }

    //get oneday price
    public static function getPriceOneDay($class_id)
    {
        $price  = 0;
        $prices = \DB::table('car_class_price')
            ->where('class_id',$class_id)->first();
        if(!empty($prices))
            $price = ServerPath::setIntegerRound($prices->d1_total);
        return $price;
    }

    //get pirce from stardate, end_date, class_id, selected_day(night_day)
    public static function getPriceDayNight($class_id, $night , $day)
    {
        $price  = 0;
        $start_date = date('Y-m-d');
        if($day == '1')
            $end_date   = $start_date;
        if($day == '2')
            $end_date   = date('Y-m-d', strtotime("+1 day"));
        if($day == '3')
            $end_date   = date('Y-m-d', strtotime("+2 day"));
        if($day == '4')
            $end_date   = date('Y-m-d', strtotime("+3 day"));
        if($day == '0') {
            $end_date = $start_date;
            $prices = \DB::table('car_class_price_custom')
                ->where('class_id',$class_id)
                ->where(function ($query) use($start_date ,$end_date) {
                    $query->where(function ($query1) use($start_date) {
                        $query1->wheredate('startdate', '<=', $start_date)
                            ->wheredate('enddate', '>=', $start_date);
                    })->orwhere(function ($query1) use($end_date) {
                        $query1->wheredate('startdate', '<=', $end_date)
                            ->wheredate('enddate', '>=', $end_date);
                    });
                })->orderby('startdate' ,'asc')
                ->first();
            if(!empty($prices)) {
                $price = ServerPath::setIntegerRound($prices ->additional_total);
            }else {
                $prices = \DB::table('car_class_price')
                                ->where('class_id',$class_id)->first();;
                $price = ServerPath::setIntegerRound($prices->additional_total);
            }
            return $price;
        }
        $prices = \DB::table('car_class_price_custom')
            ->where('class_id',$class_id)
            ->where(function ($query) use($start_date ,$end_date) {
                $query->where(function ($query1) use($start_date) {
                    $query1->wheredate('startdate', '<=', $start_date)
                        ->wheredate('enddate', '>=', $start_date);
                })->orwhere(function ($query1) use($end_date) {
                    $query1->wheredate('startdate', '<=', $end_date)
                        ->wheredate('enddate', '>=', $end_date);
                });
            })->orderby('startdate' ,'asc')
            ->first();
        if(!empty($prices)) {
            $selected_day = $night."_".$day;
            if($day != '0')
                $price = ServerPath::getPriceFromClassCustom($start_date, $end_date, $class_id, $selected_day,$start_date, $end_date);
            else
                $price = ServerPath::setIntegerRound($prices->additional_day);
            return $price;
        }
        //get normal price
        $prev_day = 0;
        $basic_days = ServerPath::showRentDays($start_date, '00:00:00',$end_date, '00:00:00');
        $origin_day = array();
        $origin_day[0] = $basic_days['night'];
        $origin_day[1] = $basic_days['day'];

        $price = ServerPath::getPricebasic($class_id, $origin_day, $prev_day, $basic_days);
        /*$prices = \DB::table('car_class_price')
            ->where('class_id',$class_id)->first();
        if(!empty($prices)) {
            switch (true) {
                case $night == 0 && $day == 0:
                    $price = $prices->additional_total;
                    break;
                case $night == 0 && $day == 1 :
                    $price = $prices->d1_total;
                    break;
                case $night == 1 && $day == 1 :
                    $price = $prices->d1_total;
                    break;
                case $night == 1 && $day > 1 :
                    $price = $prices->n1d2_total;
                    break;
                case $night == 2 :
                    $price = $prices->n2d3_total;
                    break;
                case $night == 3 :
                    $price = $prices->n3d4_total;
                    break;
                case $night > 3 :
                    $price = $prices->n3d4_total + ($prices->additional_total * ($day - 4));
                    break;
            }
        }*/

        return $price;
    }
    /**
     * roud integer price*
     * Please round down number under 50.
     * Rule 1:  if 3933, 33 which is under 50, then Round down 33, Became 3900.
     * Rule 2:  if 3950, 50 which is equal 50, no need to change.
     * Rule 3:  if 3978, then round down number bigger than 50, became 3950.
    */
    public static function setIntegerRound($price) {
        $val = 0;
        if($price > 0) {
            $first = $price;
            $second = round($first, -2);
            $third = round($first % 100);
            $four = 0;
            if ($third >= 50) {
                $four = $second - 50;
            } else $four = $second;
            $val = $four;
        }
        return  $val;
    }
    // calculate day from packing to return day
    public static function showRentDays($start_date, $start_time, $return_date, $return_time)
    {
        $nightday = array();
        if ($start_date === '' || $return_date === '') {
            $nightday['night']  = 0;
            $nightday['day']    = 0;
        } else {
            $diff_date = abs(strtotime($return_date) - strtotime($start_date))/86400;
            $diff_hour = abs(strtotime($return_date.' '.$return_time) - strtotime($start_date.' '.$start_time))/3600;
            if($diff_hour < 12) $diff_date = 0;
            $nightday['night'] = $diff_date;
            $nightday['day'] = $diff_date + 1;
        }
        return $nightday;
    }

    //get insurance
    public static function getInsurancePrice($insurance, $class_id)
    {
        $price =0;
        $ins = \DB::table('car_insurance as ci')
            ->where('search_condition',$insurance)->first();
        if($insurance == '1') {
            $prices = \DB::table('car_class_insurance')
                ->where('class_id', $class_id)
                ->where('insurance_id', $ins->id)->first();

            $price = empty($prices)? 0 : $prices->price;
        }
        if($insurance == '2') {
            $prices = \DB::table('car_class_insurance')
                ->where('class_id', $class_id)
                ->select(DB::raw('SUM(price) as price'))->first();
            $price = empty($prices)? 0 : $prices->price;
//            $price = $prices;
        }
        return $price;
    }

    //get insurance
    public static function getInsurancePrice2($class_id)
    {
        $prices = \DB::table('car_class_insurance')
            ->select(['insurance_id', 'price'])
            ->where('class_id', $class_id)
            ->get();
        $ans = [0, 0, 0];
        foreach($prices as $key=>$price) {
            $ans[$price->insurance_id] = $price->price;
        }
        return ['ins1'=>$ans[1], 'ins2'=>$ans[2]];
    }

    // dateStr : 2018-04-20 or 2018/04/30
    public static function dateDiff($dateStr1, $dateStr2) {
        $diff = strtotime($dateStr2) - strtotime($dateStr1);
        return $diff/86400;
    }

    //confirm invnetory from booking
    public static function getconfirmBooking($inventory_id,$start_date, $end_date, $search_condition){
        // remake start and end dates
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        //1:submit,2:pending,3:confirmed,4:paid,5:paid/check-in,6:using,7:delayed,8:end,9:cancel,10:ignored
        $unfinished_books = \DB::select(
            "SELECT * FROM bookings 
                WHERE inventory_id=? 
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
            [
                $inventory_id,
                $start_date, $end_date,
                $start_date, $end_date,
                $start_date, $end_date,
                $start_date, $end_date, $end_date
            ]);
        return empty($unfinished_books);
    }

    //check extendday booking
    public static function getconfirmBookingExtend($inventory_id,$start_date, $end_date, $search_condition){
        // remake start and end dates
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        //1:submit,2:pending,3:confirmed,4:paid,5:paid/check-in,6:using,7:delayed,8:end,9:cancel,10:ignored
        $unfinished_books = \DB::table('bookings')
                                ->whereDate('departing', '>=',$start_date)
                                ->whereDate('returning','<=', $end_date)
                                ->where('inventory_id',$inventory_id)
                                ->first();
        return empty($unfinished_books);
    }

    //confirm invnetory from booking
    public static function checkBookableDate($inventory_id, $date){
        // remake start and end dates
        $date = date('Y-m-d', strtotime($date));

        //1:submit,2:pending,3:confirmed,4:paid,5:paid/check-in,6:using,7:delayed,8:end,9:cancel,10:ignored
        $count = \DB::table('bookings')
            ->where('inventory_id', $inventory_id)
            ->whereNotIn('status', [8, 9])
            ->whereDate('departing', '<=', $date)
            ->where( function ($query) use ($date) {
                $query->whereDate('returning', '>=', $date)
                      ->orWhereDate('returning_updated', '>=', $date);
            })->first();
        return is_null($count);
    }

    //confirm invnetory from booking
    public static function checkInspectableDate($inventory_id, $date){
        // remake start and end dates
        $date = date('Y-m-d', strtotime($date));

        //1:submit,2:pending,3:confirmed,4:paid,5:paid/check-in,6:using,7:delayed,8:end,9:cancel,10:ignored
        $count = \DB::table('car_inspections')
            ->where('inventory_id', $inventory_id)
            ->where('delete_flag', 0)
            ->where('status', '<', 3)
            ->where( function ($query) use ($date) {
                $query->whereDate('begin_date', '<=', $date)
                      ->whereDate('end_date', '>=', $date);
            })->first();
        return is_null($count);
    }
    //confirm invnetory from booking
    public static function getConfirmInspection($inventory_id,$start_date, $end_date, $inspection_id, $search_condition){
        // remake start and end dates
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        $query = "select * from car_inspections WHERE inventory_id=? AND delete_flag=0 AND status<3 AND (
(begin_date >= ? AND begin_date <= ?) OR (end_date >= ? AND end_date <= ?) OR (begin_date <= ? AND end_date >= ?))";
        $params = [$inventory_id, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date];
        if($inspection_id != '') {
            $query .= ' AND id!= ?';
            $params[] = $inspection_id;
        }
        $unfinished_inspections = \DB::select($query, $params);
        return empty($unfinished_inspections);
    }

    public static function portalColor($portal_id) {
        if($portal_id < 1 || $portal_id > 16) return 'transparent';
        $colors = [
            1 => '#bb20ff',     // 電話 ok
            2 => '#ffe95f',     // 楽天 ok
            3 => '#f3b873',     // じゃらん
            4 => '#f3b873',     // じゃらんパック 
            5 => '#ff41419c',     // レンナビ
            6 => '#88fff8',     // SKY ok
            7 => '#80620891',     // ドットコム
            8 => '#5bffb9',     // たびんふぉ
            9 => '#c0ff3c',     // 日本旅行
            10 => '#a03ffd',    // 知人 ok
            11 => '#a03ffd',    // 取引業者ok
            12 => '#ff6ae1',    // 来店ok
            13 => '#e737ff',    // 自社HP 他ポータル？
            14 => '#e737ff',    // 予約フォーム ok
            15 => '#6d6d6d',     // その他 ok
            16 => '#e737ff'     // その他 ok

        ];
        return $colors[$portal_id];
    }

    public static function getOptionPriceFromNumberDates($opt_id, $opt_number, $dates) {
        $option = CarOption::findOrFail($opt_id);
        $price = 0;
        if(!is_null($option)) {
            $price = $option->price;
            if($option->charge_system == 'one') {
                $price = $price * $opt_number;
            } else {
                $price = $price * $opt_number * $dates;
            }
        }
        return $price;
    }

    public static function getClassMaxPassengers($class_id, $shop_id) {
        $cars = \DB::table('car_class_model as cm')
            ->leftJoin('car_model as m', 'm.id', '=', 'cm.model_id' )
            ->leftJoin('car_type as ct', 'm.type_id', '=', 'ct.id')
            ->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id')
            ->select('ci.max_passenger')
            ->where('cm.class_id', '=', $class_id)
            ->where('ci.delete_flag', 0)
            ->where('ci.status', 1)
            ->where('ci.shop_id',$shop_id)
            ->whereNotNull('ci.max_passenger')
            ->groupBy('ci.max_passenger')
            ->orderBy('ci.max_passenger')
            ->get();
        return $cars;
    }

    public static function getClassAvailableMaxPassengers($class_id, $shop_id, $start, $end) {
        $max_passengers = self::getClassMaxPassengers($class_id, $shop_id);
        $tmp = [];
        foreach ($max_passengers as $key => $mp) {
            $cars = \DB::table('car_class_model as cm')
                ->leftJoin('car_model as m', 'm.id', '=', 'cm.model_id' )
                ->leftJoin('car_type as ct', 'm.type_id', '=', 'ct.id')
                ->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id')
                ->select('ci.*')
                ->where('cm.class_id', '=', $class_id)
                ->where('ci.delete_flag', 0)
                ->where('ci.status', 1)
                ->where('ci.shop_id',$shop_id)
                ->where('ci.max_passenger', $mp->max_passenger)
                ->get();

            $count = 0;
            foreach ($cars as $car) {
                $usable = self::getconfirmBooking($car->id, $start, $end,'');
                $usable = $usable && self::getConfirmInspection($car->id, $start, $end, '', '');
                if($usable) $count++;
            }
//            if($count > 0){
                $mp->count = $count;
                $tmp[] = $mp;
//            }
        }
        return $tmp;
    }

    //translate name
    public  static function Tr($name) {
        $lang = Session::get("lang");
        if(empty($lang)) $lang = 'ja';
        if($lang != 'ja') {
            $name = $name.'_'.$lang;
        }
        return $name;
    }
    //getlang
    public  static function lang() {
        $lang = Session::get("lang");
        if(empty($lang)) $lang = 'ja';
        return $lang;
    }
    //change date
    public  static function changeDate($date) {
        $lang = APP::getLocale();
        $date_night = mb_substr($date,0,1);
        $date_day   = mb_substr($date,2,1);
        $ret = '';
        if($lang == 'ja') {
           $ret = $date_night.'泊'.$date_day.'日';
        }
        if($lang == 'en') {
            $ret = $date_night.'N'.$date_day.'D';
        }
        return $ret;
    }
    //chacking portal
    public static  function checking_portal($portal_id){
        $portal = \DB::table('portal_site')
            ->where('id', $portal_id)
            ->where('portal_flag', '1')
            ->first();
        if(!empty($portal)) {
            return 'true';
        }else {
            return 'false';
        }
    }

    //change datetime
    public static function changeTime($original_time) {
        $original = explode(':', $original_time);
        if(intval($original[1]) < 30) $original[1] = '00';
        if(intval($original[1]) >= 30) $original[1] = '30';
        $original_time = implode(":",$original);
        return $original_time ;
    }

    //get shop property
    public static function getShopProperty($shop_id)
    {
        $region_code = '';
        $shop = \DB::table('car_shop')
            ->where('id', $shop_id)
            ->first();
        if(!empty($shop)) {
            $region_code = $shop->region_code;
        }
        return $region_code;

    }
}
