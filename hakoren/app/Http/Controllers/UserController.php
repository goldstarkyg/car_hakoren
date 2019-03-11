<?php

namespace App\Http\Controllers;

use App\Http\DataUtil\ServerPath;
use App\Models\Shop;
use App\Models\CarOption;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Validator;
use File;
use Image;

class UserController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $route = Route::getFacadeRoot()->current()->uri();
        $user = Auth::user();

        if ($user && $user->activated != 1) {
            return view('pages.user.unactive');
        }else {
            $adminlogin = $request->session()->get('adminlogin');
            $role = \DB::table('role_user as ru')->where('ru.user_id', $user->id)
                        ->leftJoin('roles AS r', 'r.id','=','ru.role_id')
                        ->select(['ru.*', 'r.level'])
                        ->first();
            if (!empty($role) && $role->level >= 4) {
                $request->session()->flash('adminlogin', '');
                return Redirect::to('/admintop');
            } else {
                return Redirect::to('/mypage/top');
//                return view('pages.user.mypage-top');
            }
        }
    }

    private function getShopName($shop_id) {
        $shops = Shop::all();
        $shop_name = '';
        foreach ( $shops as $shop ) {
            if( $shop->id == $shop_id) {
                $shop_name = $shop->name;
                break;
            }
        }
        return $shop_name;
    }

    private function getOptionName($option_id) {
        $options = CarOption::all();
        $option_name = '';
        foreach ( $options as $option ) {
            if( $option->id == $option_id) {
                $option_name = $option->name;
                break;
            }
        }
        return $option_name;
    }

    private function getShortDay($day) {
//        日曜日, 月曜, 火曜日, 水曜日, 木曜日, 金曜日, 土曜日
        $lang = ServerPath::lang();
        if($lang == 'ja') {
            $shortDays = ['日', '月', '火', '水', '木', '金', '土'];
        }
        if($lang == 'en') {
            $shortDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        }

        return $shortDays[$day];
    }

    public function showMypageTop(Request $request)
    {
		//dd($title);
        $route = Route::getFacadeRoot()->current()->uri();
        $user = Auth::user();

		if(!$user->password_updated){
            $data = ['user'  => $user];
			return redirect('/mypage/changepassword')
				->withFlashMessage('パスワードを変更してください。')
				->withFlashType('danger')
				->with($data);
		}

        if ($user && $user->activated != 1) {
            return view('pages.user.unactive');
        }else {

            $adminlogin = $request->session()->get('adminlogin');
            $role = \DB::table('role_user')->where('user_id', $user->id)->first();

            if ((!empty($role) && $role->role_id == 2) ) {
                $shops = Shop::all();
                $books = \DB::table('bookings as b')
                    ->leftJoin('car_class as c', 'b.class_id', '=', 'c.id')
                    ->leftJoin('car_inventory as i', 'b.inventory_id', '=', 'i.id')
                    ->leftJoin('car_shop as s', 'b.pickup_id', '=', 's.id')
                    ->select(['b.*', 'c.name as class_name', 'i.smoke', 's.name as depart_shop','s.name_en as depart_shop_en', 'i.shortname','i.max_passenger'])
                    ->where('b.client_id','=',$user->id)
                    ->whereNotIn('b.status', [8, 9])
                    ->whereDate('b.returning', '>=', date('Y-m-d'))
                    ->orderby('b.departing', 'desc')
                    ->get();
                $book_count = 0;
                foreach($books as $book) {
                    if(ServerPath::lang() == 'ja') {
                        $book->smoke = ($book->smoke == 1) ? '喫煙' : '禁煙';
                    }
                    if(ServerPath::lang() == 'en') {
                        $book->smoke = ($book->smoke == 1) ? 'Smoking' : 'Non Smoking';
                        $book->depart_shop = $book->depart_shop_en;
                    }
                    $depart_date = date('Y/n/j', strtotime($book->departing));
                    $return_date = date('Y/n/j', strtotime($book->returning));
                    $depart_day = date('w', strtotime($book->departing));
                    $return_day = date('w', strtotime($book->returning));
                    $book->depart_date = $depart_date.'('.$this->getShortDay($depart_day).')';
                    $book->return_date = $return_date.'('.$this->getShortDay($return_day).')';
					$book->depart_time = date('H:i', strtotime($book->departing));
					$book->return_time = date('H:i', strtotime($book->returning));
					$book->booking_active = (time() < strtotime($book->departing))?1:0;
					$book->web_status  =  $book->web_status;

                    $option_ids = explode(',', $book->paid_options);
                    $option_numbers = explode(',', $book->paid_option_numbers);
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
                                if(ServerPath::lang() == 'ja') {
                                    array_push($option_names, $option->name . '(' . $opt_num . ')');  // チャイルドシート(1)
                                    array_push($option_prices, $option->name . ' ' . $option->price . '円' . ' x ' . $opt_num . '個'); // childseat 540円 x 1個
                                }
                                if(ServerPath::lang() == 'en') {
                                    array_push($option_names, $option->name_en . '(' . $opt_num . ')');  // チャイルドシート(1)
                                    array_push($option_prices, $option->name_en . ' JPY ' . $option->price .  ' x ' . $opt_num . ' '); // childseat 540円 x 1個
                                }
                            }
                        }
                    }

                    $free_options = $book->free_options;
                    $pickup_yes = !is_null($free_options) && ($free_options != '');
                    $foptions = [];
//                    if($pickup_yes == false) {
//                        $foptions = explode(',', $free_options);
//                        $pickup_yes = in_array('17', $foptions);
//                    }

                    if( $pickup_yes ) {
                        $foptions = explode(',', $free_options);
                        foreach($foptions as $fop) {
                            $fop = \DB::table('car_option')->where('id', $fop)->first();
                            if(!is_null($fop)) {
                                if(ServerPath::lang() == 'ja') {
                                    array_push($option_names, '無料' . $fop->name);
                                    array_push($option_prices, '無料' . $fop->name . ' 0円');
                                }
                                if(ServerPath::lang() == 'en') {
                                    array_push($option_names, 'Free ' . $fop->name_en);
                                    array_push($option_prices, 'Free ' . $fop->name_en . ' JPY 0');
                                }
                            }
                        }
                    }

                    $book->option_names = implode(',', $option_names);
                    $book->option_prices = implode(',', $option_prices);
                    //get max passenger
                    $psgRanges   = \DB::table('car_class_passenger AS p')
                        ->leftJoin('car_passenger_tags AS t', 'p.passenger_tag', '=','t.id')
                        ->where('p.class_id','=',$book->class_id)
                        ->orderBy('t.max_passenger', 'desc')
                        ->first();
                    $maxPerson = range(0,$psgRanges->max_passenger);
                    $book->maxperson = $maxPerson;
                    $books[$book_count] = $book;
                    $book_count++;
                }

                // get new and notification from blog posts
                $posts = DB::select("SELECT p.* FROM blog_posts AS p LEFT JOIN post_tags AS t ON p.`post_tag_id`=t.id WHERE (t.slug='news' OR t.slug='notification') AND  p.`publish_date`<=DATE(NOW())");

                $request->session()->flash('register_user_id', $user->id);

                $flight_lines = \DB::table('flight_lines')->orderby('order')->get();
                $data = [
                    'user'  => $user,
                    'books' => $books,
                    'posts' => $posts,
                    'flight_lines' => $flight_lines
                ];

                $request->session()->flash('adminlogin', '');
                return view('pages.user.mypage-top')->with($data);
            } else if($adminlogin){
                return Redirect::to('/admintop');
            } else {
                return Redirect::to('/login');
            }
        }
    }

    public function updateBooking(Request $request)
    {
        $book_id = $request->input('book_id');
        $data_content = [];

        $data_content['flight_inform'] = $request->input('flight_inform');
        $data_content['passengers'] = $request->input('passenger');
        $data_content['client_message'] = $request->input('comment');

        if(!empty($data_content)) {
            $find = \DB::table('bookings')->where('id', $book_id)->first();

            $shop = \DB::table('car_shop')->where('id', $find->pickup_id)->first();
            $shop_name  = $shop->name;

            $customer = \DB::table('users')->where('id', $find->client_id)->first();
            $user_name = $customer->last_name.' '.$customer->first_name;
            $booking_id = $find->booking_id;
            $departing  = $find->departing;
            $returning  = $find->returning;

            $changed = false;
            if( $data_content['flight_inform'] != $find->flight_inform){
                $changed = true;
            }
            if( $data_content['passengers'] != $find->passengers){
                $changed = true;
            }
            if( $data_content['client_message'] != $find->client_message){
                $changed = true;
            }
            //send message to hakoren
            if($changed == true) {
                $subject    = '【ハコレン】お客様のマイページが更新されました。'."<".$user_name.">";
                $sender     ='ハコレンシステム';


                $template   = '※自動送信<br/>';
                $template   .= 'お客様のマイページから更新がありました。<br/>';
                $template   .= '下記の内容を確認してお客様の対応をお願いします。<br/><br/>';

                $template   .= 'ご利用店：'.$shop_name.'<br/>';
                $template   .= 'お名前：'.$user_name.'<br/><br/>';

                $template   .= '予約ID：'.$booking_id.'<br/>';
                $template   .= 'ご利用店：'.$shop_name.'<br/>';
                $template   .= '出発日：'.$departing.'<br/>';
                $template   .= '返却日：'.$returning.'<br/><br/>';

                $template   .= '更新された内容：<br/>';
                $template   .= 'フライト便番：'.$data_content['flight_inform'].'<br/>';
                $template   .= '乗車人数：'. $data_content['passengers'].'<br/>';
                $template   .= 'お客様のコメント：'. $data_content['client_message'].'<br/><br/>';

                $template   .= '---------------------------------------------------------<br/>';
                $template   .= 'このメールは【ハコレンタカー】のマイページから送信されました。</br/>';

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
            }
            $result = \DB::table('bookings')->where('id', $book_id)->update($data_content);
        }
        return back()->with('success', 'ありがとうございました。変更内容を受け付けました。');

    }

    public function showMypageLog(Request $request)
    {
//dd($title);
        $route = Route::getFacadeRoot()->current()->uri();
        $user = Auth::user();
        if ($user && $user->activated != 1) {
            return view('pages.user.unactive');
        }else {

            $adminlogin = $request->session()->get('adminlogin');
            $role = \DB::table('role_user')->where('user_id', $user->id)->first();

            if ((!empty($role) && $role->role_id == 2) ) {

				if(!$user->password_updated){
					$data = ['user'  => $user];
					return redirect('/mypage/changepassword')
						->withFlashMessage('パスワードを変更してください。')
						->withFlashType('danger')
						->with($data);
				}

                $books = \DB::table('bookings as b')
                    ->leftJoin('car_class as c', 'b.class_id', '=', 'c.id')
                    ->leftJoin('car_inventory as i', 'b.inventory_id', '=', 'i.id')
                    ->leftJoin('car_shop as s', 'b.pickup_id', '=', 's.id')
                    ->select(['b.*', 'c.name as class_name', 'i.smoke', 's.name as depart_shop'])
                    ->where('b.client_id','=',$user->id)
                    ->whereDate('b.returning', date('Y-m-d'))
                    ->orderBy('b.id', 'desc')
                    ->simplePaginate(5);

                foreach ( $books as $book) {
                    $book->smoke = ($book->smoke == 1)? '喫煙車' : '禁煙車';
                    $depart_date = date('Y/n/j', strtotime($book->departing));
                    $return_date = date('Y/n/j', strtotime($book->returning));
                    $depart_day = date('w', strtotime($book->departing));
                    $return_day = date('w', strtotime($book->returning));
                    $book->depart_date = $depart_date.'('.$this->getShortDay($depart_day).')';
                    $book->return_date = $return_date.'('.$this->getShortDay($return_day).')';
                    $book->depart_datetime = $book->depart_date.' '.date('G時i分', strtotime($book->departing));
                    $book->return_datetime = $book->return_date.' '.date('G時i分', strtotime($book->returning));
                    $dn = explode('_', $book->rent_days);
                    if(ServerPath::lang() == 'ja') {
                        $book->rentdate_str = (count($dn) > 1) ? $dn[0] . '泊' . $dn[1] . '日' : ' 泊 日';
                    }
                    if(ServerPath::lang() == 'en') {
                        $book->rentdate_str = (count($dn) > 1) ? $dn[0] . 'N' . $dn[1] . 'D' : ' N D';
                    }
                    $book->depart_shop = $this->getShopName($book->pickup_id);
                    $book->return_shop = $this->getShopName($book->dropoff_id);
                    $free_options = explode(',', $book->free_options);
                    $paid_options = explode(',', $book->paid_options);
                    $options = [];
                    foreach ($free_options as $option ) {
                        array_push($options, $this->getOptionName($option));
                    }
					$paid_option_names = array();
                    foreach ($paid_options as $option ) {
                        array_push($options, $this->getOptionName($option));
					    array_push($paid_option_names, $this->getOptionName($option));
                    }
                    $book->option_names = implode(',', $options);
					$book->paid_option_names = $paid_option_names;
                    $models = DB::table('car_class_model as cm')
                        ->leftJoin('car_model as m', 'cm.model_id', '=', 'm.id')
                        ->where('cm.class_id','=',$book->class_id)
                        ->select('m.name')
                        ->get();
                    $model_names = [];
                    foreach ($models as $model) {
                        array_push($model_names, $model->name);
                    }
                    $book->model_names = implode(' / ', $model_names);
                }
                $books->total = DB::table('bookings')->where('client_id','=',$user->id)->count();
                $current_page = $books->currentPage();
                $per_page = $books->perPage();
                $count = $books->count();
                $books->start = $per_page * ($current_page - 1) + 1;
                $books->end = $per_page * ($current_page - 1) + $count;

                $request->session()->flash('register_user_id', $user->id);
                $data = [
                    'user'  => $user,
                    'books' => $books
                ];


                $request->session()->flash('adminlogin', '');
                return view('pages.user.mypage-log')->with($data);
            } else if($adminlogin){
                return Redirect::to('/admintop');
            } else {
                return Redirect::to('/login');
            }
        }
    }

    public function showMypageProfile(Request $request)
    {
//dd($title);
        $route = Route::getFacadeRoot()->current()->uri();
        $user  = Auth::user();
        if ($user && $user->activated != 1) {
            return view('pages.user.unactive');
        }else {

            $adminlogin = $request->session()->get('adminlogin');
            $role = \DB::table('role_user')->where('user_id', $user->id)->first();

            if ((!empty($role) && $role->role_id == 2) ) {
                $data = [
                    'user'  => $user,
//                    'books' => $books
                ];

				if(!$user->password_updated){
					return redirect('/mypage/changepassword')
						->withFlashMessage('パスワードを変更してください。')
						->withFlashType('danger')
						->with($data);
				}
                $request->session()->flash('adminlogin', '');
                return view('pages.user.mypage-profile')->with($data);
            } else if($adminlogin){
                return Redirect::to('/admintop');
            } else {
                return Redirect::to('/login');
            }
        }
    }

    public function showMypageFaq(Request $request)
    {
//dd($title);
        $route = Route::getFacadeRoot()->current()->uri();
        $user  = Auth::user();
        if ($user && $user->activated != 1) {
            return view('pages.user.unactive');
        }else {
            return view('pages.user.mypage-faq');
        }
    }


	public function updateMypageProfile(Request $request)
    {
		$user  = User::find(Auth::user()->id);
		$input = $request->all();
		$messages = [
    'required' => 'この項目は必須です。',
    'regex' => '正しい電話番号を入力して下さい。',
    'email' => '正しいメールアドレスを入力して下さい。',
    'min' => '10文字以上の数字を入力して下さい。',
];
        $validator = Validator::make($input,
            [
				'last_name'         => 'required',
				'first_name'        => 'required',
				'furi_last_name'    => 'required',
				'furi_first_name'   => 'required',
				'email'             => 'required|email|unique:users,email,'.$user->id,
				'phone'             => 'required|regex:/^[0-9]{2,4}[0-9]{2,4}[0-9]{3,4}$/|min:10',
				'postal_code'       => 'required',
				'address'           => 'required'
				//'license_surface'         => 'image|mimes:jpeg,jpg,bmp,png|max:2048',
				//'license_back'            => 'image|mimes:jpeg,jpg,bmp,png|max:2048'
            ] ,$messages
        );


        if ($validator->fails()) {
//dd($validator);
return redirect()->back()->withErrors($validator->errors())->withInput();
            $this->throwValidationException(
                $request, $validator
            );

        } else {

			$user->update($request->all());

			$user->profile->update([
								   'fur_last_name'=>$input['furi_last_name'],
								   'fur_first_name'=>$input['furi_first_name'],
								   'phone'=>$input['phone'],
								   'postal_code'=>$input['postal_code'],
								   'address1'=>$input['address']
								  ]);

			$destinationPath = storage_path() . '/users/id/' . $user->id . '/uploads/images/license/';
            // Make the user a folder and set permissions
            File::makeDirectory($destinationPath, $mode = 0755, true, true);

			if(!empty($request->file('license_surface'))){
				$surfacefile = $request->file('license_surface');
				$fileName  = $surfacefile->getClientOriginalName();
				$extension = $surfacefile->getClientOriginalExtension();
				$safeName = 'surface_' . $user->id . '.' . $extension;
				$surfacefile->move($destinationPath, $safeName);
				$user->profile->update(['license_surface' => $safeName]);
			}

			if(!empty($request->file('license_back'))){
				$backfile = $request->file('license_back');
				$fileName = $backfile->getClientOriginalName();
				$extension = $backfile->getClientOriginalExtension();
				$safeName = 'back_' . $user->id . '.' . $extension;
				$backfile->move($destinationPath, $safeName);
				$user->profile->update(['license_back' => $safeName]);
			}

			$data = ['user'  => $user];
			return redirect('/mypage/profile')
				->withFlashMessage('変更が保存されました。')
				->withFlashType('success')
				->with($data);
		}
    }


	public function changepassword(Request $request){

        $route = Route::getFacadeRoot()->current()->uri();
        $user  = Auth::user();

        if ($user && $user->activated != 1) {
            return view('pages.user.unactive');
        }else {

            $adminlogin = $request->session()->get('adminlogin');
            $role = \DB::table('role_user')->where('user_id', $user->id)->first();

            if ((!empty($role) && $role->role_id == 2) ) {
                $data = [
                    'user'  => $user,
//                    'books' => $books
                ];

                $request->session()->flash('adminlogin', '');
                return view('pages.user.mypage-password')->with($data);
            } else if($adminlogin){
                return Redirect::to('/admintop');
            } else {
                return Redirect::to('/login');
            }
        }
	}


	public function updatePassword(Request $request)
    {
		$user  = User::find(Auth::user()->id);
		$input = $request->all();
        $validator = Validator::make($input,
            [
				/*'current_password'         => 'required|current_password',*/
				'password' 				   => 'required|confirmed|min:6|max:25',
				'password_confirmation'    => 'required|min:6'
            ]
        );
        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
			$user->password_updated = 1;
			$user->password = bcrypt($input['password']);
			$user->save();
			$data = [
				'user'  => $user,
			];
			return redirect('/mypage/changepassword')
				->withFlashMessage('パスワードが変更されました！')
				->withFlashType('success')
				->with($data);
		}
    }

	public function redirectQuickstart($booking){
		session()->forget('booking_id');
		session()->forget('register_user_id');

		session()->put('register_user_id', Auth::user()->id);
		session()->put('booking_id', $booking);

 		if(session()->has('register_user_id') and session()->get('register_user_id') and
		   session()->has('booking_id') and session()->get('booking_id')){
			return Redirect::to('/quickstart-01');
        }else{
			return Redirect::to('/mypage/top');
		}

	}

    public function adminindex(Request $request)
    {

        $user = Auth::user();
        if ($user && $user->activated != 1) {

            return view('pages.user.unactive');

        }else{

            return view('pages.admin.home');

        }
    }
}
