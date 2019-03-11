<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CarClass;
use App\Models\CarClassModel;
use App\Models\CarInsurance;
use App\Models\CarInventory;
use App\Models\CarOption;
use App\Models\Shop;
use App\Models\Score;
use App\Models\PortalSite;
use Auth;
use Illuminate\Http\Request;
use Validator;
use App\Http\DataUtil\ServerPath;

use DB;
use File;
use Response;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;
use Ixudra\Curl\Facades\Curl;
use Google\Spreadsheet\CellFeed;
use Google\Spreadsheet\CellEntry;


class GoogleSpreadController extends Controller
{

    //save data in db from google sheet with command run
    public function saveData(Request $request)
    {
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $base_path  = base_path()."/public/googlespread/quickstart.php";
        $result     = passthru("php ".$base_path, $return);
        return;
    }
    //api send to google spreaqdshet
    public function getMenuData(Request $request) {
        $options_list = array();
        $options_list['first'] = "test aa";
        $options_list['second'] = "second test";

        return Response::json((array)$options_list);

    }

   /* public function getgooglesheet(Request $request){
        //refer this page(https://github.com/asimlqt/php-google-spreadsheet-client)
        ini_set('max_execution_time',300);
        //$public_path  = "http://motocle7.sakura.ne.jp/projects/rentcar/hakoren/public";
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domain = $_SERVER['HTTP_HOST'];
        $public_path = "";
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $public_path = $protocol.$domain;
        } else {
            $public_path = $protocol.$domain."/projects/rentcar/hakoren/public";
        }
        $token_run  = $public_path."/gettoken.php";
        $response   = Curl::to($token_run)->enableDebug(public_path().'/curllog.txt')->get();
        if($response === "token_true") {
            $token_url = $public_path.'/token.txt';
            $token = @file_get_contents($token_url);
            $tokens = json_decode($token, true);
            $token  = $tokens['access_token'];
            $serviceRequest = new DefaultServiceRequest($token);
            $serviceRequest->setSslVerifyPeer(false);
            ServiceRequestFactory::setInstance($serviceRequest);

            $spreadsheetService = new SpreadsheetService();
            $spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed();
            $spreadsheet = $spreadsheetFeed->getByTitle('予約フォームの項目');
            $worksheetFeed = $spreadsheet->getWorksheetFeed();
            $worksheet  = $worksheetFeed->getByTitle('取込シート');// main sheet
            $listFeed   = $worksheet->getListFeed();
            $secondworksheet     = $worksheetFeed->getByTitle('外部予約履歴');// second sheet to save history
            $secondlistFeed   = $secondworksheet->getListFeed();
            //test
            $deletebook = DB::table('bookings')->where('portal_flag','1')->delete();
            ////
            //echo count($listFeed->getEntries());return;
            $bookings   = array();
            $keys       = array();
            foreach ($listFeed->getEntries() as $entry) {
                $book   = array();
                $portal_info = array();
                $rows   = $entry->getValues();
                $keys   = array_keys($rows);
                $vals   = array_values($rows);
                //google sheedt sample data
                /*{"submiteddate":"2017\/08\/01","submitedtime":"6:00","departingshop":"\u90a3\u8987\u7a7a\u6e2f\u5e97",
                    "returningshop":"\u90a3\u8987\u7a7a\u6e2f\u5e97","via":"\u3058\u3083\u3089\u3093","lastname":"","firstname":"",
                    "furiganalast":"\u30de\u30b9\u30c0","furiganafirst":"","booking":"","departingdate":"2018\/01\/13","departingtime":"10:00",
                    "returningdate":"2018\/01\/15","returningtime":"17:00","carclass":"10\u7cfb","carnumber":"","smokenonsmoke":"",
                    "basicprice":"\u00a513,800","insurance1":"\u00a53,240","insurance2":"\u00a51,620","pointsusedfromportalsites":"",
                    "childseats":"","babyseats":"","juniorseats":"","etccards":"","snowtire":"","onlinepayment":"","totalamount":"#REF!",
                    "flightname":"","flight":"","rentinglocation":"","passenger":"5","phone":"090-5682-2222","emergencycall":"",
                    "email":"masuda@bitcat.net","staff":"","memo":"2018-03-15 11:36:57"}
                $submited_date  = $vals[0];    //A:1."submiteddate":"2017\/08\/01"
                $submited_time  = $vals[1];   //B:2."submitedtime":"6:00"
                $book['created_at'] = $submited_date." ".$submited_time;
                $depart_shop    = $vals[2];   //C:3."departingshop"

                $shop           = Shop::where('name',$depart_shop)->first();
                if(empty($shop)){
                    $book['pickup_id']  = 0;
                    $shop_id            = 0;
                }else {
                    $book['pickup_id']  = $shop->id;
                    $shop_id            = $shop->id;
                }
                $portal_info['depart_shop'] = $depart_shop;

                $return_shop    = $vals[3];   //D:4."returningshop"
                $shop_return           = Shop::where('name',$return_shop)->first();
                if(empty($shop_return)){
                    $book['dropoff_id'] = 0;
                }else {
                    $book['dropoff_id'] = $shop_return->id;
                }
                $portal_info['return_shop'] = $return_shop;

                $portal_name    = $vals[4];   //E:5."via":"\u3058\u3083\u3089\u3093"
                $portal         = PortalSite::where('name',$portal_name)->first();
                if(empty($portal)) {
                    $portal = new PortalSite;
                    $portal->name = $portal_name;
                    $portal->alias= $portal_name;
                    $portal->save();
                    }
                $book['portal_id']= $portal->id;
                $book['portal_flag'] = 1;

                $last_name      = $vals[5];   //F:6."lastname":""
                $portal_info['last_name'] = $last_name;
                $first_name     = $vals[6];   //G:7."firstname":""
                $portal_info['first_name'] = $first_name;
                $fu_last_name   = $vals[7];   //H:8."furiganalast":"\u30de\u30b9\u30c0"
                $portal_info['fu_last_name'] = $fu_last_name;
                $fu_first_name  = $vals[8];    //I:9."furiganafirst":""
                $portal_info['fu_first_name'] = $fu_first_name;
                $booking        = $vals[9];    //J:10."booking":""
                $portal_info['booking'] = $booking;
                $portal_info['booking_status'] = '成約';


                $departing_date = $vals[10];   //K:11."departingdate":"2018\/01\/13"
                $departing_time = $vals[11];  //L:12."departingtime":"10:00"
                $book['departing']=$departing_date." ".$departing_time;

                $returning_date = $vals[12];   //M:13."returningdate":"2018\/01\/15"
                $returning_time = $vals[13];   //N:14."returningtime":"17:00"
                $book['returning']=$returning_date." ".$returning_time;

                //get class
                $carclass       = $vals[14];   //O:15."carclass":"10\u7cfb"
                $car            = CarClass::where('name',$carclass)->first();
                $class_id         = 0;
                if(empty($car)) {
                    $book['class_id']= '0';
                }else {
                    $book['class_id'] = $car->id;
                    $class_id         = $car->id;
                }
                $portal_info['car_class'] = $carclass;

                //get model
                $classmodels    = carClassModel::where('class_id',$class_id)->get();
                $models = array();
                foreach($classmodels as $ob) {
                    $model_id = $ob->model_id;
                    $models[] = $model_id;
                }
                $carnumber      = $vals[15];   //P:16."carnumber":""
                $smoke_non          = $vals[16];   //Q:17."smokenonsmoke":""
                $smoke          = '0';
                if($smoke_non == '喫煙') $smoke = '1';//smoke
                if($smoke_non == '禁煙') $smoke = '0';//non smoke

                //get
                $carinven   = CarInventory::where('numberplate',$carnumber)->where('shop_id',$shop_id)
                    ->where('smoke',$smoke)->wherein('model_id', $models)->first();
                if(empty($carinven)) {
                    $book['model_id']       = 0;
                    $book['inventory_id']   = 0;
                }else {
                    $book['model_id']       = $carinven->model_id;
                    $book['inventory_id']   = $carinven->id;;
                }
                $portal_info['smoke']       = $smoke;
                $portal_info['car_number']  = $carnumber;

                //[ total price ] = [ car price ] + [ all option prices ] + [ additional insurance]- [pointsusedfromportalsites]
                $basic_price    = $vals[17];   //R:18."basicprice":"\u00a513,800"
                $basic_price    = $this->getInt($basic_price);
                $portal_info['basic_price'] = $basic_price;

                $insurance1     = $vals[18];   //S:19."insurance1":"\u00a53,240"
                $insurance1    = $this->getInt($insurance1);
                $portal_info['insurance1']  = $insurance1;

                $insurance2     = $vals[19];   //T:20."insurance2":"\u00a51,620"
                $insurance2    = $this->getInt($insurance2);
                $portal_info['insurance2']  = $insurance2;

                $pointed_portal = $vals[20];   //U:21."pointsusedfromportalsites" this is discount= refer top line.
                $pointed_portal    = $this->getInt($pointed_portal);
                $portal_info['pointed_portal'] = $pointed_portal;

                $option_price   = 0;
                $child_seat     = $vals[21];   //V:22."childseats":
                $child_seat    = $this->getInt($child_seat);
                $paid_options   = "";
                $paid_options_price = "";
                $car_options    ="";
                if($child_seat != 0){
                    $option_name = 'child seat';
                    $option = CarOption::where('name',$option_name)->first();
                    if(!empty($option)) {
                        $paid_options .= $option->id.",";
                        $paid_options_price.=$child_seat.",";
                    }
                    $car_options .=$option_name."_".$child_seat."|";
                    $option_price +=$child_seat;
                }

                $baby_seat      = $vals[22];   //W:23."babyseats":""
                $baby_seat      = $this->getInt($baby_seat);
                if($baby_seat != 0){
                    $option_name = 'baby seat';
                    $option = CarOption::where('name',$option_name)->first();
                    if(!empty($option)) {
                        $paid_options .= $option->id.",";
                        $paid_options_price.=$baby_seat.",";
                    }
                    $car_options .= $option_name."_".$child_seat."|";
                    $option_price +=$baby_seat;
                }

                $junior_seat    = $vals[23];   //X:24."juniorseats":""
                $junior_seat    = $this->getInt($junior_seat);
                if($junior_seat != 0){
                    $option_name = 'junior seat';
                    $option = CarOption::where('name',$option_name)->first();
                    if(!empty($option)) {
                        $paid_options .= $option->id.",";
                        $paid_options_price.=$junior_seat.",";
                    }
                    $car_options .= $option_name."_".$junior_seat."|";
                    $option_price +=$junior_seat;
                }

                $etocards       = $vals[24];   //Y:25."etccards":""
                $etocards       = $this->getInt($etocards);
                if($etocards != 0){
                    $option_name = 'etocards';
                    $option = CarOption::where('name',$option_name)->first();
                    if(!empty($option)) {
                        $paid_options .= $option->id.",";
                        $paid_options_price.=$etocards.",";
                    }
                    $car_options .= $option_name."_".$etocards."|";
                    $option_price +=$etocards;
                }

                $snowtire       = $vals[25];   //Z:26."snowtire":""
                $snowtire       = $this->getInt($snowtire);
                if($snowtire != 0){
                    $option_name = 'snowtire';
                    $option = CarOption::where('name',$option_name)->first();
                    if(!empty($option)) {
                        $paid_options .= $option->id.",";
                        $paid_options_price.=$snowtire.",";
                    }
                    $car_options .= $option_name."_".$snowtire."|";
                    $option_price +=$snowtire;
                }
                if($car_options != "") $car_options = rtrim($car_options,",");

                if(!empty($paid_options)) {
                    $paid_options = rtrim($paid_options, ",");
                    $paid_options_price = rtrim($paid_options_price,",");
                    $book['paid_options']= $paid_options;
                    $book['paid_options_price']= $paid_options_price;
                }
                $portal_info['car_option']  = $car_options;
                $book['option_price']= $option_price;


                $online_payment     = $vals[26];   //AA:27."onlinepayment" this is pre paid payment.
                $online_payment     = $this->getInt($online_payment);
                $book['prepaid']    = $online_payment;

                $total_mount        = $vals[27];    //AB:28."totalamount":"\u00a518,660"
                $total_mount        = $this->getInt($total_mount);
                $book['payment']    = $total_mount;

                $flight_name        = $vals[28];    //AC:29."flightname":""
                $flight = \DB::table("flight_lines")->where('title',$flight_name)->first();
                if(empty($flight)){
                    $flight = \DB::table('flight_lines')->insert(
                        [
                            'title'     => $flight_name,
                            'order'     => 1,
                        ]
                    );
                    $flight = \DB::table("flight_lines")->where('title',$flight_name)->first();
                }

                $flight_line        = $flight->id;
                $book['flight_line']= $flight_line;


                $flight_number  = $vals[29];    //AD:30."flight":""
                $book['flight_number']= $flight_number;

                $renting_location=$vals[30];    //AE:31."rentinglocation->this is option service"

                $passengers     = $vals[31];    //AF:32."passenger":"5".
                $passengers     = $this->getPassenger($passengers);
                $book['passengers']= $passengers;

                $phone          = $vals[32];    //AG:33."phone":"090-5682-2222"
                $portal_info['phone']   = $phone;

                $emergency_call = $vals[33];    //AH:34."emergencycall":""
                $portal_info['emergency_call'] = $emergency_call;
                $book['emergency_phone']= $emergency_call;

                $email          = $vals[34];    //AI:35."email":"masuda@bitcat.net".
                $portal_info['email']   = $email;

                $staff          = $vals[35];    //AJ:36."staff":""
                $user           = \DB::table('users as u')
                                   ->leftjoin('role_user as ru','ru.user_id','=','u.id')
                                   ->leftjoin('roles as r','r.id',"=",'ru.role_id')
                                   ->whereRaw("(r.level = 5 or r.level = 4) and u.last_name ='.$staff.'")
                                   ->first();

                if(!empty($user)) {
                    $book['admin_id'] = $user->id;
                }
                $portal_info['staff']   = $staff;

                $admin_memo     = $vals[36];    //AK:37."memo":""
                $book['admin_memo'] = $admin_memo;

                $book['portal_info']= json_encode($portal_info);

                $book['booking_id'] = $this->getbookingId();

                //insert database booking
                $insertbook = DB::table('bookings')->insert($book);

            }
        }
        return '1';
    }
    */
    //filter integer
    public function getInt($param) {
        $param = str_replace('¥','',$param);
        $param = str_replace(',','',$param);
        if(is_numeric($param)) {
            return $param;
        }else {
            //eg: #REF!
            return 0;
        }
    }
    //filter integer
    public function getPassenger($param) {
        if(is_numeric($param)) {
            return $param;
        }else {
            //eg: #REF!
            return 0;
        }
    }

    //get booking id
    public function getbookingId(){
        $today = date('ymd');
        $todayLastBook = \DB::select('SELECT * FROM bookings WHERE DATE(created_at) = DATE(NOW()) ORDER BY id DESC limit 0,1 ');
        if(count($todayLastBook) == 0){
            $booking_id = $today.'-0001';
        } else {
            $split = explode('-', $todayLastBook[0]->booking_id);
            $booking_id = $today.'-'.str_pad(($split[1] + 1), 4, '0', STR_PAD_LEFT);
        }
        return $booking_id;
    }
    //get booking id for only google spread submite date
    public function getbookingIdFromsubmite($date){
        $select_date = date('Y-m-d',strtotime($date));
        $today = date('ymd',strtotime($date));
        //$todayLastBook = \DB::select('SELECT * FROM bookings WHERE DATE(created_at) = DATE(NOW()) ORDER BY id DESC limit 0,1 ');
        $todayLastBook = \DB::select("SELECT * FROM bookings WHERE DAtE(created_at) = DATE('".$select_date."') ORDER BY booking_id DESC limit 0,1 ");

        if(count($todayLastBook) == 0){
            $booking_id = $today.'-0001';
        } else {
            $split = explode('-', $todayLastBook[0]->booking_id);
            $booking_id = $today.'-'.str_pad(($split[1] + 1), 4, '0', STR_PAD_LEFT);
        }
        return $booking_id;
    }
    //get val
    public function getVal($data){
        $cu_row = count($data);
        if($cu_row < 39 ) {
            for($i = $cu_row ; $i < 39 ;$i++) {
                $data[$i] = "";
            }
        }
        return $data;
    }

    //savegooglesheet
    public function savegooglesheet($data) {
        //echo $request->session()->token();
        $ret = array();
        $passed = array();
        $failed = array();
        $rows = json_decode($data);

        if(!empty($rows)) {
                foreach ($rows as $row) {
                    $book = array();
                    $portal_info = array();
                    $row_convert = $this->getVal($row);
                    $vals = array_values($row_convert);
                    //google sheedt sample data
                    $shop_name     =  $vals[0];  //A:1."shop name"
                    $shop = Shop::where('name', $shop_name)->first();

                    if (empty($shop)) {
                        $book['pickup_id'] = 0;
                        $book['dropoff_id'] = 0;
                        $shop_id = 0;
                        $failed[] = $this->ChangeInvnetoryFromGoogle($row,'店舗を正しく選択してください');//wrong-shop name
                        continue;
                    } else {
                        $book['pickup_id'] = $shop->id;
                        $book['dropoff_id'] = $shop->id;
                        $shop_id = $shop->id;
                    }

                    $submited_date = $vals[1];    //B:2."submiteddate":"2017\/08\/01"
                    $submited_time = $vals[2];   //C:3."submitedtime":"6:00"
                    $book['submited_at'] = date('Y-m-d H;i:s');
                    $book['created_at']  = $submited_date . " " . $submited_time;
                    if(empty($submited_date)) {
                        $failed[] = $this->ChangeInvnetoryFromGoogle($row, '予約日のフォーマットを確認してください');//wrong-submit date
                        continue;
                    }
                    if(empty($submited_time)) {
                        $failed[] = $this->ChangeInvnetoryFromGoogle($row, '予約時刻のフォーマットを確認してください');//wrong-submit time
                        continue;
                    }

                    $portal_name = $vals[3];   //D:4."via":"\u3058\u3083\u3089\u3093"
                    $portal = PortalSite::where('name', $portal_name)->first();
                    if (empty($portal)) {
                        $portal = new PortalSite;
                        $portal->name = $portal_name;
                        $portal->alias = $portal_name;
                        $portal->save();
                    }
                    $book['portal_id'] = $portal->id;
                    $book['portal_flag'] = 1;

                    $last_name = $vals[4];   //E:5."lastname":""
                    $portal_info['last_name'] = $last_name;
                    $first_name = $vals[5];   //F:6."firstname":""
                    $portal_info['first_name'] = $first_name;
                    $fu_last_name = $vals[6];   //G:7."furigana lastname"
                    $portal_info['fu_last_name'] = $fu_last_name;
                    $fu_first_name = $vals[7];    //H:8."furigana firstname"
                    $portal_info['fu_first_name'] = $fu_first_name;
                    $booking = $vals[8];    //I:9."booking id"
                    //$book['booking_id'] = $booking;
                    $portal_info['booking'] = $booking;
                    $book['status'] = '1'; //submited
                    //$portal_info['booking_status'] = '成約';

                    $departing_date = $vals[9];   //J:10."departingdate"
                    $departing_time = $vals[10];  //K:11."departingtime":"10:00"
                    $book['departing'] = $departing_date . " " . $departing_time;
                    if(empty($departing_date)) {
                        $failed[] = $this->ChangeInvnetoryFromGoogle($row, '出発日のフォーマットを確認してください');//worng deaprt date
                        continue;
                    }
                    if(empty($departing_time)) {
                        $failed[] = $this->ChangeInvnetoryFromGoogle($row, '出発時刻のフォーマットを確認してください');//wrong depart time
                        continue;
                    }

                    $returning_date = $vals[11];   //L:12."returningdate":"2018\/01\/15"
                    $returning_time = $vals[12];   //M:13."returningtime":"17:00"
                    $book['returning'] = $returning_date . " " . $returning_time;
                    if(empty($returning_date)) {
                        $failed[] = $this->ChangeInvnetoryFromGoogle($row, '返却日のフォーマットを確認してください');//wrong -return date
                        continue;
                    }
                    if(empty($returning_time)) {
                        $failed[] = $this->ChangeInvnetoryFromGoogle($row, '返却時刻のフォーマットを確認してください');// worng rreturn time
                        continue;
                    }

                    $request_days = ServerPath::showRentDays($departing_date, $departing_time, $returning_date, $returning_time);
                    $night = $request_days['night'];
                    $day = $request_days['day'];
                    $book['rent_days'] = $night . "_" . $day;

                    //get class
                    $carclass = $vals[13];   //N:14."carclass"
                    if(empty($carclass)) {
                        $failed[] = $this->ChangeInvnetoryFromGoogle($row, '在庫確認');//Wrong-class
                        continue;
                    }
                    $car = CarClass::where('name', $carclass)->first();
                    $class_id = 0;
                    if (empty($car)) {
                        $book['class_id'] = '0';
                    } else {
                        $book['class_id'] = $car->id;
                        $class_id = $car->id;
                    }
                    //$portal_info['car_class'] = $carclass;

                    //get model
                    $classmodels = CarClassModel::where('class_id', $class_id)->orderby('priority','asc')->get();
                    $models = array();
                    foreach ($classmodels as $ob) {
                        $model_id = $ob->model_id;
                        $models[] = $model_id;
                    }
                    $smoke_non = $vals[14];   //O:15."smokenonsmoke":""
                    $max_passenger = $vals[15];   //P:16."max passenger"
                    if(empty($max_passenger)) {
                        $failed[] = $this->ChangeInvnetoryFromGoogle($row, '在庫確認'); //wrong-invnetory smokde
                        continue;
                    }
                    $smoke = '0';
                    if ($smoke_non == '喫煙') $smoke = '1';//smoke
                    if ($smoke_non == '禁煙') $smoke = '0';//non smoke
                    if ($smoke_non == 'どちらでも良い') $smoke = '2';//non smoke

                    if($smoke == '2') {
                        $carinvens = \DB::table('car_inventory as ci')
                            ->Join('car_model as m', 'm.id', '=', 'ci.model_id' )
                            ->where('ci.shop_id', $shop_id)
                            ->wherein('ci.model_id', $models)
                            ->where('ci.delete_flag','0')
                            ->where('ci.max_passenger', $max_passenger)
                            ->where('ci.status','1')
                            ->orderby('ci.smoke','desc')
                            ->orderby('m.priority', 'asc')
                            ->orderby('ci.priority','asc')
                            ->select(['ci.*'])->get();
                        //$smoke = '1';
                    }else {
                        $carinvens = \DB::table('car_inventory as ci')
                            ->Join('car_model as m', 'm.id', '=', 'ci.model_id' )
                            ->where('ci.shop_id', $shop_id)
                            ->where('ci.smoke', $smoke)
                            ->where('ci.delete_flag','0')
                            ->where('ci.max_passenger', $max_passenger)
                            ->wherein('ci.model_id', $models)
                            ->where('ci.status','1')
                            ->orderby('m.priority', 'asc')
                            ->orderby('ci.priority','asc')
                            ->select(['ci.*'])
                            ->get();
                    }

                    $inventory_id = 0;
                    if (empty($carinvens) || count($carinvens) == 0) {
                        $book['model_id'] = 0;
                        $book['inventory_id'] = 0;
                        $unmached = $this->ChangeInvnetoryFromGoogle($row,'在庫確認'); //wrong-invnetory smokde
                        $failed[] = $unmached;
                        continue;
                    } else {
                        $carinven = $this->getInventory($carinvens, $departing_date, $returning_date, '');
                        //if(empty($carinven) || count((array)$carinven) == 0) {
                        if(empty($carinven)) {
                            $carinven = (object)$carinvens[0];
                        }
                        $smoke = $carinven->smoke;
                        if($smoke == '1') {
                            $portal_info['smoke'] = '喫煙';
                        }
                        if($smoke == '0') {
                            $portal_info['smoke'] = '禁煙';
                        }
                        $book['model_id'] = $carinven->model_id;
                        $book['inventory_id'] = $carinven->id;
                        $inventory_id =  $carinven->id;
                    }



                    $basic_price = $vals[16];   //Q:17."basic price"
                    $basic_price = $this->getInt($basic_price);
                    $portal_info['basic_price'] = $basic_price;
                    $book['basic_price']        = $basic_price;

                    $insurance = $vals[17];   //R:18."insurance"
                    if($insurance == '免責補償') {
                        $ins    = CarInsurance::where('name', $insurance)->first();
                        $insurance_id = $ins->id;
                        $ins_price = \DB::table('car_class_insurance')
                                        ->where('class_id', $class_id)
                                        ->where('insurance_id', $insurance_id)
                                        ->first();
                        //$portal_info['insurance1'] = $ins_price->price;
                        $book['insurance1'] = $ins_price->price*$day;
                        //$portal_info['insurance2'] = 0;
                        $book['insurance2'] = 0;
                    }
                    if($insurance == 'ワイド免責補償') {
                        $ins    = CarInsurance::where('id','!=', '0')->get();
                        foreach($ins as $in) {
                            if($in->search_condition == '1'&& $in->name == '免責補償') {
                                $insurance_id = $in->id;
                                $ins_price = \DB::table('car_class_insurance')
                                    ->where('class_id', $class_id)
                                    ->where('insurance_id', $insurance_id)
                                    ->first();
                                //$portal_info['insurance1'] = $ins_price->price;
                                $book['insurance1'] = $ins_price->price*$day;
                            }
                            if($in->search_condition == '2'&& $in->name == 'ワイド免責補償') {
                                $insurance_id = $in->id;
                                $ins_price = \DB::table('car_class_insurance')
                                    ->where('class_id', $class_id)
                                    ->where('insurance_id', $insurance_id)
                                    ->first();
                                //$portal_info['insurance2'] = $ins_price->price;
                                $book['insurance2'] = $ins_price->price*$day;
                            }
                        }
                    }
                    if($insurance == '無し') {
                        //$portal_info['insurance1'] = '0';
                        $book['insurance1'] = '0';
                        //$portal_info['insurance2'] = '0';
                        $book['insurance2'] = '0';
                    }

                    $option_all_price = 0;
                    $child_seat_number = $vals[18];   //S:19."child seats":

                    $paid_options = "";
                    $paid_options_price = "";
                    $paid_options_number = "";
                    $car_options = "";
                    if ($child_seat_number != 0) {
                        $option_id      = 0;
                        $option_price   = 0;
                        $option_name    = 'child seat';
                        $column_number  = '22';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price*$child_seat_number;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $child_seat_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $car_options .= $option_id."_".$option_name . "_" . $paid_options_number . "_".$option_price."|";
                        $option_all_price += $option_price;
                    }

                    $baby_seat_number = $vals[19];   //T:20."babyseats"

                    if ($baby_seat_number != 0) {
                        $option_id  = 0;
                        $option_price = 0;
                        $option_name = 'baby seat';
                        $column_number  = '23';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price*$baby_seat_number;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $baby_seat_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $car_options .= $option_id."_".$option_name . "_" . $paid_options_number . "_".$option_price."|";
                        $option_all_price += $option_price;
                    }

                    $junior_seat_number = $vals[20];   //U:21."juniorseats":""

                    if ($junior_seat_number != 0) {
                        $option_id   = 0;
                        $option_price = 0;
                        $option_name = 'junior seat';
                        $column_number  = '24';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price*$junior_seat_number;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $junior_seat_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $car_options .= $option_id."_".$option_name . "_" . $paid_options_number . "_".$option_price."|";
                        $option_all_price += $option_price;
                    }

                    $etocards_text = $vals[21];   //V:22."etccards":""
                    $etocards_number = 0;
                    if($etocards_text == 'はい')
                        $etocards_number = 1;
                    if ($etocards_number != 0) {
                        $option_id   = 0 ;
                        $option_price = 0;
                        $option_name = 'etocards';
                        $column_number  = '25';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $etocards_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $car_options .= $option_id."_".$option_name . "_" . $paid_options_number . "_".$option_price."|";
                        $option_all_price += $option_price;
                    }

                    $snowtire_text      = $vals[22];   //W:23."snowtire"
                    $snowtire_number    = 0;
                    if($snowtire_text == 'はい')
                        $snowtire_number = 1;
                    if ($snowtire_number != 0) {
                        $option_id   = 0;
                        $option_price = 0;
                        $option_name = 'snowtire';
                        $column_number  = '26';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price*$day;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $snowtire_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $car_options .= $option_id."_".$option_name . "_" . $paid_options_number . "_".$option_price."|";
                        $option_all_price += $option_price;
                    }

                    $smart_text      = $vals[23];   //X:24."smart pickup= smart option drive"
                    $smart_number    = 0;
                    if($smart_text == 'スマート乗り出し') {
                        $option_id =0;
                        $option_price = 0;
                        $smart_number = 1;
                        $option_name = 'smart drive';
                        $column_number  = '38';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $smart_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $car_options .= $option_id."_".$option_name . "_" . $paid_options_number . "_".$option_price."|";
                        $option_all_price += $option_price;
                        $book['wait_status'] = '3';
                    }

                    if($smart_text == 'スマート返却') {
                        $option_id    = 0;
                        $option_price = 0;
                        $smart_number = 1;
                        $option_name = 'smart drive';
                        $column_number  = '106';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $smart_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $car_options .= $option_id."_".$option_name . "_" . $paid_options_number . "_".$option_price."|";
                        $option_all_price += $option_price;
                        $book['wait_status'] = '3';
                    }
                    if($smart_text == 'スマート乗出&返却') {
                        $smart_number = 1;
                        //first
                            $option_id  = 0;
                            $option_price = 0;
                            $option_name = 'smart drive';
                            $column_number  = '38';
                            $option = CarOption::where('google_column_number', $column_number)->first();
                            if (!empty($option)) {
                                $option_id              = $option->id;
                                $option_price           = $option->price;
                                $paid_options           .= $option_id . ",";
                                $paid_options_price     .= $option_price . ",";
                                $paid_options_number    .= $smart_number.",";
                                $option_name = $option->abbriviation;
                            }
                            $car_options .= $option_id."_".$option_name . "_" . $paid_options_number . "_".$option_price."|";
                        $option_all_price += $option_price;
                        //second
                            $option_id  = 0;
                            $option_price = 0;
                            $option_name = 'smart drive';
                            $column_number  = '106';
                            $option = CarOption::where('google_column_number', $column_number)->first();
                            if (!empty($option)) {
                                $option_id              = $option->id;
                                $option_price           = $option->price;
                                $paid_options           .= $option_id . ",";
                                $paid_options_price     .= $option_price . ",";
                                $paid_options_number    .= $smart_number.",";
                                $option_name = $option->abbriviation;
                            }
                            $car_options .= $option_id."_".$option_name . "_" . $paid_options_number . "_".$option_price."|";
                        $option_all_price += $option_price;
                        $book['wait_status'] = '3';
                    }

                    if ($car_options != "") $car_options = rtrim($car_options, "|");

//                    if (!empty($paid_options)) {
//                        $paid_options = rtrim($paid_options, ",");
//                        $paid_options_price = rtrim($paid_options_price, ",");
//                        $book['paid_options'] = $paid_options;
//                        $book['paid_options_price'] = $paid_options_price;
//                        $book['paid_option_numbers'] = $paid_options_number;
//                    }
//                    //$portal_info['car_option'] = $car_options;
//                    $book['option_price'] = $option_all_price;


                    $free_text = $vals[24];   //Y:25."free option"
                    if($free_text == '不要') { //no need
                        $book['free_options_category'] = '0';
                    }
                    if($free_text == '国内空港送迎') { //pickup at domestic airport
                        $option_name = '';
                        $column_number  = '101';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        $option_id = 0;
                        if (!empty($option)) {
                            $option_id   = $option->id;
                            $option_name = $free_text;
                        }
                        $free_options = $option_id."_".$option_name;
                        $portal_info['free_option_name'] = $option_name;
                        $book['free_options'] = $option_id;
                        $book['wait_status'] = '1';
                        $book['free_options_category'] = '1';
                    }elseif($free_text == '国際空港送迎'){ //pickup at international airport
                        $option_name = '';
                        $column_number  = '102';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        $option_id = 0;
                        if (!empty($option)) {
                            $option_id   = $option->id;
                            $option_name = $free_text;
                        }
                        $free_options = $option_id."_".$option_name;
                        $portal_info['free_option_name'] = $option_name;
                        $book['free_options'] = $option_id;
                        $book['wait_status'] = '1';
                        $book['free_options_category'] = '2';
                    }elseif($free_text == 'コインパーキング') {
                        $option_name = '';
                        $column_number  = '101';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        $option_id = 0;
                        if (!empty($option)) {
                            $option_id   = $option->id;
                            $option_name = $free_text;
                        }
                        $free_options = $option_id."_".$option_name;
                        $portal_info['free_option_name'] = $option_name;
                        $book['free_options'] = $option_id;
                        $book['wait_status'] = '1';
                        $book['free_options_category'] = '3';
                    }else {
                        $portal_info['free_option_name'] = '';
                    }


                    $given_points = $vals[25];    //Z:26."given points"
                    $given_points = $this->getInt($given_points);
                    $book['given_points'] = $given_points;

                    $adjustment_price = $vals[26];    //AA:27."adjustment price"
                    $adjustment_price = $this->getInt($adjustment_price);
                    $book['discount'] = $adjustment_price;
                    $web_payment = $vals[27];    //AB:28."web payment"
                    $web_payment = $this->getInt($web_payment);
                    if($web_payment > 0 ) {
                        $book['pay_method'] = '4';
                        $book['pay_status'] = '1';
                        $book['paid_date'] = $submited_date;
                    }//1=cash, 2= credit, 3 =web, 4= portal
                    $book['web_payment'] = $web_payment;

                    $total_mount = $vals[28];    //AC:29."totalamount":"\u00a518,660"
                    $total_mount = $this->getInt($total_mount);
                    if($web_payment > 0 ) $total_mount = $web_payment;
                    $book['payment'] = $total_mount;
                    $flight_company = $vals[29];    //AD:30."flight company":""
                    $flight = \DB::table("flight_lines")->where('title', $flight_company)->first();
                    if (empty($flight)) {
                        $flight = \DB::table('flight_lines')->insert(
                            [
                                'title' => $flight_company,
                                'order' => 1,
                            ]
                        );
                        $flight = \DB::table("flight_lines")->where('title', $flight_company)->first();
                    }

                    $flight_line = $flight->id;
                    $book['flight_line'] = $flight_line;


                    $flight_number = $vals[30];    //AE:31."flight number"
                    $book['flight_number'] = $flight_number;

                    $returning_point = $vals[31];    //AF:32."returning point" = only need for poratal
                    $portal_info['returning_point'] = $returning_point;
                    //add to admin memo below AK(admin_memo)


                    $passengers = $vals[32];    //AG:33."passenger":"5".
                    $passengers = $this->getPassenger($passengers);
                    $book['passengers'] = $passengers;

                    $phone = $vals[33];    //AH:34."phone":"090-5682-2222"
                    $portal_info['phone'] = $phone;

                    $emergency_call = $vals[34];    //AI:35."emergencycall":""
                    $book['emergency_phone']= $emergency_call;

                    $email = $vals[35];    //AJ:36."email"
                    $portal_info['email'] = $email;

                    $staff = $vals[36];    //AK:37."staff":""
                    $staff = trim($staff);
                    if($staff == '') {
                        $book['admin_id'] = 0;
                    }else {
                        $user = \DB::table('users as u')
                            ->leftjoin('role_user as ru', 'ru.user_id', '=', 'u.id')
                            ->leftjoin('roles as r', 'r.id', "=", 'ru.role_id')
                            ->whereRaw("(r.level = 5 or r.level = 4) and (u.last_name ='" . $staff . "' or u.first_name = '" . $staff . "')")
                            ->first();
                        if (!empty($user)) {
                            $book['admin_id'] = $user->id;
                        } else {
                            $failed[] = $this->ChangeInvnetoryFromGoogle($row, '担当者確認');//wrong admin staff
                            continue;
                        }
                    }
                    //$portal_info['staff'] = $staff;

                    $admin_memo = $vals[37];    //AL:38."memo":""
                    $book['admin_memo'] = $returning_point.' '.$admin_memo;

                    $hotel = $vals[38];    //AM:39." return name":""
                    if($hotel == 'ホテ乗A') {
                        $option_id =0;
                        $option_price = 0;
                        $option_number = 1;
                        $option_name = 'ホテ乗A';
                        $column_number  = '251';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $option_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $option_all_price += $option_price;
                    }

                    if($hotel == 'ホテA返') {
                        $option_id =0;
                        $option_price = 0;
                        $option_number = 1;
                        $option_name = 'ホテA返';
                        $column_number  = '252';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $option_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $option_all_price += $option_price;
                    }

                    if($hotel == 'ホテB乗') {
                        $option_id =0;
                        $option_price = 0;
                        $option_number = 1;
                        $option_name = 'ホテB乗';
                        $column_number  = '253';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $option_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $option_all_price += $option_price;
                    }

                    if($hotel == 'ホテB返') {
                        $option_id =0;
                        $option_price = 0;
                        $option_number = 1;
                        $option_name = 'ホテB返';
                        $column_number  = '254';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $option_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $option_all_price += $option_price;
                    }

                    if($hotel == 'ホテルA乗&返') {
                        $option_id =0;
                        $option_price = 0;
                        $option_number = 1;
                        $option_name = 'ホテルA乗&返';
                        $column_number  = '255';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $option_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $option_all_price += $option_price;
                    }
                    if($hotel == 'ホテルB乗&返') {
                        $option_id =0;
                        $option_price = 0;
                        $option_number = 1;
                        $option_name = 'ホテルB乗&返';
                        $column_number  = '250';
                        $option = CarOption::where('google_column_number', $column_number)->first();
                        if (!empty($option)) {
                            $option_id              = $option->id;
                            $option_price           = $option->price;
                            $paid_options           .= $option_id . ",";
                            $paid_options_price     .= $option_price . ",";
                            $paid_options_number    .= $option_number.",";
                            $option_name = $option->abbriviation;
                        }
                        $option_all_price += $option_price;
                    }


                    if (!empty($paid_options)) {
                        $paid_options = rtrim($paid_options, ",");
                        $paid_options_price = rtrim($paid_options_price, ",");
                        $book['paid_options'] = $paid_options;
                        $book['paid_options_price'] = $paid_options_price;
                        $book['paid_option_numbers'] = $paid_options_number;
                    }
                    $book['option_price'] = $option_all_price;
                    if(empty($book['insurance1']))  $book['insurance1'] = 0;
                    if(empty($book['insurance2']))  $book['insurance2'] = 0;
                    $virtual_payment = $total_mount - ($basic_price + $book['insurance1'] + $book['insurance2'] + $option_all_price + $adjustment_price);
                    $book['virtual_payment'] = $virtual_payment;

                    //$portal_bad_flag = $vals[38];    //AL:38."price bad flag":"1=bad, else  good"
                    //$portal_info['bad_flag'] = $portal_bad_flag;

                    $book['portal_info'] = json_encode($portal_info);

                    $book['booking_id'] = $this->getbookingIdFromsubmite($submited_date);
                    //check booking inform
                    $check_book = ServerPath::getconfirmBooking($book['inventory_id'], $book['departing'], $book['returning'], '');
                    $check_inspection = ServerPath::getConfirmInspection($inventory_id, $departing_date , $returning_date, '', '');

                    //echo "<br>"."class_id=".$book['class_id'].":model_id".$book['model_id'].":inventory_id=".$book['inventory_id'].":departing=".$book['departing'].":return=".$book['returning']."<br>";
                    if($check_book == true && $check_inspection == true) {
                        //insert database booking
                        $insertbook = DB::table('bookings')->insert($book);
                        $passed[] = $row;
                    }else {
                        $unmached = $this->ChangeInvnetoryFromGoogle($row,'満車'); //Full error
                        $failed[] = $unmached;
                    }
            }
            $ret['passed'] = $passed;
            $ret['failed']  = $failed;
            return $ret;
        }else{
            $ret['passed'] = $passed;
            $ret['failed']  = $failed;
            return $ret;
        }
    }
    //delete content portal to test
    public function deletegoogleportal()
    {
        $deletebook = DB::table('bookings')->where('portal_flag', '1')->delete();
    }
    //
    public function getgooglesheet_new(Request $request){
        //$public_path  = "http://motocle7.sakura.ne.jp/projects/rentcar/hakoren/public";
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domain = $_SERVER['HTTP_HOST'];
        $public_path = "";
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $public_path = $protocol.$domain;
        } else {
            //$public_path = $protocol.$domain."/projects/rentcar/hakoren/public";
            $public_path = $protocol.$domain;
        }

        $token_run  = $public_path."/googleprocess.php";
        $response   = Curl::to($token_run)
            ->enableDebug(public_path().'/curllog.txt')
            //->withHeaders( array( 'username: motocle', 'password: m123' ) )
            ->withOption('SSL_VERIFYHOST', false)
            ->withData( array( 'page' => 'load' ) )
            ->returnResponseObject()
            ->post();//help::https://github.com/ixudra/curl
        $val =  $response->content;
        $insert = $this->savegooglesheet($val);

        if(!empty($insert['passed'])) {
            $response   = Curl::to($token_run)
                ->enableDebug(public_path().'/curllog.txt')
                ->withOption('SSL_VERIFYHOST', false)
                ->withData( array( 'page' => 'move_pass','passed'=>json_encode($insert['passed'])))
                ->post();//help::https://github.com/ixudra/curl
        }
        if(!empty($insert['failed']) ) {
            $response   = Curl::to($token_run)
                ->enableDebug(public_path().'/curllog.txt')
                ->withOption('SSL_VERIFYHOST', false)
                ->withData( array( 'page' => 'move_fail','failed'=>json_encode($insert['failed'])))
                ->post();//help::https://github.com/ixudra/curl
        }

        if(!empty($insert['passed']) || !empty($insert['failed']) ) {
            $response   = Curl::to($token_run)
                ->enableDebug(public_path().'/curllog.txt')
                ->withOption('SSL_VERIFYHOST', false)
                ->withData( array( 'page' => 'delete') )
                ->post();//help::https://github.com/ixudra/curl
        }

        $ret = array();
        $ret['passed'] = count($insert['passed']);
        $ret['failed'] = count($insert['failed']);
        return Response::json($ret);
    }

    public function ChangeInvnetoryFromGoogle($val, $cond) {
        $ret = array();
        $ret[0] = $cond;
        $count = 1 ;
        foreach($val as $v) {
            $ret[$count] = $v;
            $count++;
        }
        return $ret;
    }

    //get possible car inventory
    public function getInventory($invens, $depart, $return, $cond ){
        $ret = array();
        foreach($invens as $in){
            $check_book = ServerPath::getconfirmBooking($in->id, $depart, $return , '');
            $check_inspection = ServerPath::getConfirmInspection($in->id, $depart , $return, '', '');
            if($check_book == true && $check_inspection == true) {
                return $in;
            }
        }
        return $ret;
    }


}
