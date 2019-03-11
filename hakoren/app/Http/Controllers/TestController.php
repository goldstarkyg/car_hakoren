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

class TestController extends Controller
{
    public function __construct()
    {

    }

    //sales management
    public function test(Request  $request) {
        $date = '2018-08-28';
        $bookig_price_return = \DB::table('bookings_price as pr')
            ->leftjoin('bookings as bo', 'bo.id','=','pr.book_id')
            ->leftjoin('users as u', 'bo.client_id','=','u.id')
            ->where('pr.pay_method','>','0')
            ->where('pr.pay_status','1')
            ->where('bo.return_task','1')
            ->where('pr.price_type','2');
        $bookig_price_return = $bookig_price_return
            ->where(function ($query1) use($date) {
                $query1->where(function($query2) use($date){
                    $query2 ->where('bo.returning','>=', 'bo.returning_updated')
                        ->where('bo.returning','LIKE', '%'.$date.'%');
                })
                    ->orwhere(function($query4) use($date){
                        $query4 ->where('bo.returning_updated','0000-01-01 00:00:00')
                            ->where('bo.returning','LIKE', '%'.$date.'%');
                    })
                    ->orwhere(function($query3) use($date){
                        $query3 ->where('bo.returning','<', 'bo.returning_updated')
                            ->where('bo.returning_updated','LIKE', '%'.$date.'%');
                    });
            })
            ->select(['pr.book_id','pr.total_price','bo.departing','bo.returning','bo.returning_updated'])
            ->get();
        echo json_encode($bookig_price_return) ;return;
    }


}