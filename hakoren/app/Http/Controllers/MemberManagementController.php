<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Profile;
use App\Models\RoleUser;
use App\Models\Score;
use App\Models\User;
use App\Models\Shop;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
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

class MemberManagementController extends Controller
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

    public function index(Request $request)
    {
        $route = Route::getFacadeRoot()->current()->uri();
        $dateinterval = $request->get('dateinterval', '');

        $users = \DB::table('users as u')
            ->join('role_user as r', 'r.user_id', '=', 'u.id')
            ->join('profiles as p', 'p.user_id', '=', 'u.id')
            ->join('roles as ro', 'ro.id', '=', 'r.role_id')
            ->leftJoin('users_category as ca', 'p.category_id', '=', 'ca.id')
            ->leftJoin('credit_card as card', 'p.credit_card_type', '=', 'card.id')
            ->select(['u.*' ,'p.fur_first_name','p.fur_last_name','p.sex','p.birth','p.category_id','p.phone',
                'p.postal_code','p.prefecture','p.city','p.address1','p.address2','p.emergency_phone','p.foreign_address',
                'p.foreign_city','p.foreign_state','p.foreign_country','p.foreign_zip_code','p.company_name',
                'p.company_postal_code','p.company_prefecture','p.company_address1','p.company_address2','p.company_city',
                'p.company_address2','p.credit_card_type','p.credit_card_number','p.credit_card_expiration','p.credit_card_code',
                'ca.alias as user_category','r.role_id','card.name as card_name']);

        if($dateinterval != ''){
            $dates = explode(' - ', $dateinterval);
            $startdate = date('Y-m-d', strtotime($dates[0]));
            $enddate = date('Y-m-d', strtotime($dates[1]));
            $users = $users->where('u.updated_at', '>=', $startdate)->where('u.updated_at', '<=', $enddate);
        }

        $users = $users
            ->where('u.activated', 1)
            ->whereNotNull('u.email')
            ->where('ro.level','1')  //only user
            ->orderby('u.created_at', 'desc')->get();
        foreach ($users as $user) {
            $uid = $user->id;
            $shops = \DB::table('car_shop as shops')
                ->select('shops.name')
                ->rightJoin(DB::raw('(SELECT pickup_id FROM bookings where client_id='.$uid.' GROUP BY pickup_id) as pickups'),
                    function($join)
                    {
                        $join->on('shops.id', '=', 'pickups.pickup_id');
                    })
                ->get();
            $shoplist = [];
            foreach ($shops as $shop) {
                array_push($shoplist, $shop->name);
            }
            $user->store = implode('<br>', $shoplist);
            $last = \DB::table('bookings')
                ->where('client_id', '=', $uid)
                ->orderBy('created_at', 'desc')->first();
            $user->last_use = is_null($last)? '' : date('Y/m/d', strtotime($last->created_at));
            $user->visit_count = \DB::table('bookings')->where('client_id', '=', $uid)->count();
        }
        //echo json_encode($users); return;
        $roles = Role::all();
        $memberuser = \DB::table('users as u')
                                    ->join('role_user as r', 'r.user_id', '=', 'u.id')
                                    ->join('profiles as p', 'p.user_id', '=', 'u.id')
                                    ->join('roles as ro', 'ro.id', '=', 'r.role_id')
                                    ->whereNotNull('u.email')
                                    ->where('ro.level','1');
        $total_users = clone $memberuser;
        $total_users = $total_users->get();;
        //new user
        $current_month = date('Y-m');
        $new_users = clone $memberuser;
        $new_users = $new_users->where('u.created_at','like', '%'.$current_month.'%')->get();
        //before 365 day
        $day = date("Y-m-d",strtotime('-365 day'));
        $before_users = clone $memberuser;
        $before_users = $before_users->whereDate('u.created_at','>=', $day)->get();

        $before_users_morebooking = clone $memberuser;
//        $before_users_morebooking = $before_users_morebooking
//                                    ->join('bookings as bo' ,'bo.client_id','=','u.id')
//                                    ->groupBy('client_id')
//                                    ->having('client_id', '>', 1)
//                                    ->count();
//        $before_users_morebooking = $before_users_morebooking->get();
//        $cnt = 0;
//        foreach($before_users as $bu) {
//            $books = \DB::table('bookings')->where('client_id', '=', $bu->id)->count();
//            if($books > 1) $cnt++;
//        }
//        $percent = 0;
//        if(!empty($before_users)) {
////            $percent = round(100/count($before_users) * $before_users_morebooking);
//            $percent = $cnt * 100/count($before_users);
//        }
        $query = "SELECT COUNT(*) AS duplicate_count FROM ( ";
	    $query .=" SELECT client_id FROM bookings AS bo LEFT JOIN users AS u ON u.id= bo.client_id ";
	    $query .=" WHERE u.created_at >= '".$day."' GROUP BY client_id HAVING COUNT(client_id) > 1 ) AS t";
        $bus = \DB::select($query);
        foreach ($bus as $bu) {
            $before_users_morebooking = $bu->duplicate_count;
            break;
        }
        $percent = 0;
        if(!empty($before_users)) {
            $percent = round(100/count($before_users) * $before_users_morebooking);
        }

        $data = [
            'roles' => $roles,
            'route' => $route,
            'dateinterval' => $dateinterval,
            'users' => $users,
            'total_user' => count($total_users),
            'new_users'  => count($new_users),
            'before_users' => count($before_users),
            'before_users_morebooking' => $percent,
        ];
        return view('pages.admin.member.show-members')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = \DB::table('users_category')->get();
        $users_groups = \DB::table('users_group')->get();
        $credit_cards = \DB::table('credit_card')->get();
        $route = Route::getFacadeRoot()->current()->uri();
        $roles = Role::where('level' ,'1')->get();
        $data = [
            'roles' => $roles,
            'route' => $route,
            'categories' => $categories,
            'users_groups' => $users_groups,
            'credit_cards' => $credit_cards
        ];

        return view('pages.admin.member.create-member')->with($data);
        //return view('usersmanagement.create-user')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'first_name'            => 'required',
                'last_name'             => 'required',
                'email'                 => 'required|email|max:255|unique:users',
                'password'              => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
                'role'                  => 'required'
            ],
            [
                'first_name.required'   => trans('auth.fNameRequired'),
                'last_name.required'    => trans('auth.lNameRequired'),
                'email.required'        => trans('auth.emailRequired'),
                'email.email'           => trans('auth.emailInvalid'),
                'password.required'     => trans('auth.passwordRequired'),
                'password.min'          => trans('auth.PasswordMin'),
                'password.max'          => trans('auth.PasswordMax'),
                'role.required'         => trans('auth.roleRequired'),
                'password_confirmation.required' => trans('auth.PassordRequire'),
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {

            $ipAddress  = new CaptureIpTrait;
            $profile    = new Profile;

            //start transaction
            DB::beginTransaction();
                $user = User::create([
//                    'name' => $request->input('name'),
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'password' => bcrypt($request->input('password')),
                    'token' => str_random(64),
                    'admin_ip_address' => $ipAddress->getClientIp(),
                    'activated' => $request->input('activated')
                ]);
                $user_name = str_pad($user->id, 6, '0', STR_PAD_LEFT);
                $user->update(array('name'=> $user_name));

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
                if (!$user)
                {
                    DB::rollBack();
                }
                //save user group
                $groups = explode(",", $request->input('groups'));

                $user_group = array();
                for ($i = 0; $i < count($groups); $i++) {
                    if($groups[$i]) {
                        $group = array("user_id" => $user->id, "group_id" => $groups[$i]);
                        array_push($user_group, $group);
                    }
                }
                if(count($user_group) == 0 )
                    return back()->with('error',trans('auth.erroraddusergroup'));
                $groupinsert = \DB::table('users_group_tag')->insert($user_group);

                if (!$groupinsert)
                {
                    DB::rollBack();
                }
            DB::commit();
            return redirect('/members')->with('success', trans('usersmanagement.createSuccess'));

        }
    }

    public function getExportData(){
        $users = \DB::table('users as u')
            ->join('role_user as r', 'r.user_id', '=', 'u.id')
            ->join('profiles as p', 'p.user_id', '=', 'u.id')
            ->join('roles as ro', 'ro.id', '=', 'r.role_id')
            ->select(['u.*','ro.slug as user_slug'])
            ->where('u.deleted_at', null)
            ->where('ro.level','!=', '5')->get();

        $data = array();
        foreach($users as $user){
            $id = str_pad($user->id, 6, '0', STR_PAD_LEFT);
            $role = $user->user_slug;
            $name = $user->last_name." ".$user->first_name;
            $d1 = new \DateTime(date('Y-m-d'));
            $d2 = new \DateTime($user->created_at);
            $diffm = $d1->diff($d2)->m;
            $diffy = $d1->diff($d2)->y;
            if($diffy == 0){
                $diff = $diffm.'ヵ月';
            }else{
                $diff = $diffy.'年 '.$diffm.'ヵ月';
            }
            $created_at = date_format(new \DateTime($user->created_at),'Y-m-d');

            $data[] = array($id, $role, $name , $diff, $created_at);
        }
        $headers = array('会員ID', '役割', '氏名', '会員年数','登録日');
        $filename = date('YmdHi').'_userlist';
        $data = array_merge(array($headers), $data);
        Excel::create($filename, function($excel) use($data) {
            $excel->sheet('userlist', function($sheet) use($data) {
                $sheet->fromArray($data);
            });
        })->export('xls');

        return Redirect::back();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        $dateinterval = $request->get('dateinterval', '');

        $user = User::find($id);

        if(!empty($user)) {
            $check = \DB::table('role_user')->where('user_id', $user->id)->first();
            if (!empty($check) && $check->role_id != 2) {
                return Redirect::back();
            }
        }else{
            return Redirect::back();
        }

        $uid = $user->id;
        $shops = \DB::table('car_shop as shops')
            ->select('shops.name')
            ->rightJoin(DB::raw('(SELECT pickup_id FROM bookings where client_id='.$uid.' GROUP BY pickup_id) as pickups'),
                function($join)
                {
                    $join->on('shops.id', '=', 'pickups.pickup_id');
                })
            ->get();
        $shoplist = [];
        foreach ($shops as $shop) {
            array_push($shoplist, $shop->name);
        }
        $user->used_shops = implode(',', $shoplist);
        $last = \DB::table('bookings')
            ->where('client_id', '=', $uid)
            ->orderBy('created_at', 'desc')->first();
        $user->last_use = is_null($last)? '' : date('Y/m/d', strtotime($last->created_at));
		/*
        $bookings = \DB::table('bookings as b')
        ->leftJoin('car_inventory AS i', 'b.inventory_id','=','i.id')
        ->leftJoin('car_class_model AS cm', 'i.model_id','=','cm.model_id')
        ->leftJoin('car_class AS c','cm.class_id','=','c.id')
        ->leftJoin('reservations AS r','b.reservation_id','=','r.id')
        ->leftJoin('users AS u','b.admin_id','=','u.id')
        ->select(['b.id','b.booking_id','b.created_at','r.title AS reservation','c.name AS class_name','b.pickup_id','b.dropoff_id','u.first_name','u.last_name','b.departing','b.payment','b.admin_memo'])
            ->where('b.client_id','=',$user->id)
        ->orderBy('b.created_at', 'DESC')
        ->get();
		*/
		
		$bookings = \DB::table('bookings as b')
        ->leftJoin('car_inventory AS i', 'b.inventory_id','=','i.id')
        ->leftJoin('car_model AS cm', 'b.model_id','=','cm.id')
        ->leftJoin('car_class AS c','b.class_id','=','c.id')
        ->leftJoin('reservations AS r','b.reservation_id','=','r.id')
        ->leftJoin('users AS u','b.admin_id','=','u.id')
//        ->select(['b.id','b.booking_id','b.created_at','r.title AS reservation','c.name AS class_name','b.pickup_id','b.dropoff_id','u.first_name','u.last_name','b.departing','b.payment','b.admin_memo', 'i.shortname as car_name', 'i.delete_flag as car_deleted'])
        ->select(['b.*','r.title AS reservation','c.name AS class_name','u.first_name','u.last_name', 'i.shortname as car_name', 'i.delete_flag as car_deleted'])
            ->where('b.client_id','=',$user->id)
        ->orderBy('b.created_at', 'DESC')
        ->get();		
		
        $shops = Shop::all();
        $shopArr = [];
        foreach ($shops as $shop) {
            $shopArr[$shop->id] = $shop->name;
        }
        foreach ($bookings as $book){
            $book->depart_shop = $shopArr[$book->pickup_id];
            $book->return_shop = $shopArr[$book->dropoff_id];
        }
        return view('pages.admin.member.show-member')->with('bookings', $bookings)->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $categories = \DB::table('users_category')->get();
        $users_groups = \DB::table('users_group')->get();
        $credit_cards = \DB::table('credit_card')->get();
        $route = Route::getFacadeRoot()->current()->uri();
        $user = User::findOrFail($id);
        $roles = Role::where('level','1')->get();
        $groups_tag = \DB::table('users_group_tag')->where('user_id',$id)->get();

        $currentRole = null;
        foreach ($user->roles as $user_role) {
            $currentRole = $user_role;
        }
//        $profile = $user->profile;

        $data = [
            'user'          => $user,
            'roles'         => $roles,
            'currentRole'   => $currentRole,
            'route'         => $route,
            'categories'    => $categories,
            'users_groups'  => $users_groups,
            'groups_tag'    => $groups_tag,
            'credit_cards'  => $credit_cards,
//            'profile'       => $profile
        ];
        return view('pages.admin.member.edit-member')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
//                $profile->credit_card_type = $request->input('credit_card_type');
//                $profile->credit_card_number = $request->input('credit_card_number');
//                $profile->credit_card_expiration = date('Y-m-d', strtotime($request->input('credit_card_expiration')));
//                $profile->credit_card_code = $request->input('credit_card_code');

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
                if(!empty($user_group)) {
                    $groupdelete = \DB::table('users_group_tag')->where('user_id', $user->id)->delete();
                    $groupinsert = \DB::table('users_group_tag')->insert($user_group);
                }
            });
            return back()->with('success', trans('usersmanagement.updateSuccess'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $deltext = $request->get('deltext'.$id);
        $currentUser = Auth::user();
        $user        = User::findOrFail($id);
        $ipAddress   = new CaptureIpTrait;
        if ($user->id != $currentUser->id) {
//            $user->deleted_ip_address = $ipAddress->getClientIp();
//            $user->save();
            \DB::beginTransaction();
//                $user->delete();
                $user->deleted_ip_address = $ipAddress->getClientIp();
                $user->activated = 0;
                $user->email = $user->email.'hakoren'.mt_rand(100, 999);
                $user->deleted_at = date('Y-m-d H:i:s');
                $user->save();
//                $groupdelete = \DB::table('users_group_tag')->where('user_id', $id)->delete();
            \DB::commit();
            return redirect('/members')->with('deletesuccess', 'このユーザーを会員リストから除外しました。');
        }
        return back()->with('error', trans('usersmanagement.deleteSelfError'));

    }
    /**
     * direct message management
     */
    public function directMessage(Request $request) {
        $route = Route::getFacadeRoot()->current()->uri();
        $registerdate   = $request->get('registerdate', '');
        $start = date('Y/m/d');
        if($registerdate == '') $registerdate = $start.' - '.$start;
        $lastreturndate = $request->get('lastreturndate', '');
        $booking_numbers = $request->get('booking_numbers', '');
        $spent_amount   = $request->get('spent_amount','');
        $shop_id        = $request->get('shop_id' ,'');
        $prefecture     = $request->get('prefecture','');

        $group_id = $request->get('group_id', '');
        $class_id = $request->get('class_id', '');
        $groups = \DB::table('users_group')->get();
        $class  = \DB::table('car_class')->where('status','1')->where('delete_flag','0')->get();
        $shop   = \DB::table('car_shop')->get();

        $users = \DB::table('users as u')
            ->join('role_user as r', 'r.user_id', '=', 'u.id')
            ->join('profiles as p', 'p.user_id', '=', 'u.id')
            ->join('roles as ro', 'ro.id', '=', 'r.role_id')
            ->join('bookings as bo','bo.client_id','u.id')
            ->leftjoin('users_group_tag as ut','ut.user_id','u.id')
            ->select(['u.*' ,'p.fur_first_name','p.fur_last_name','bo.client_id','bo.pickup_id'])
            ->where('ro.level','1');
        if($registerdate != ''){
            $dates = explode(' - ', $registerdate);
            $register_start = date('Y-m-d', strtotime($dates[0]));
            $register_end = date('Y-m-d', strtotime($dates[1]));
            $users = $users->where('u.created_at', '>=', $register_start)->where('u.created_at', '<=', $register_end);
        }
        if($lastreturndate !=''){
            $dates = explode(' - ', $lastreturndate);
            $last_start = date('Y-m-d', strtotime($dates[0]));
            $last_end = date('Y-m-d', strtotime($dates[1]));
            $users = $users
                ->where('bo.returning_updated', '>=', $last_start)
                ->where('bo.returning_updated', '<=', $last_end);
        }
        if($group_id != '') {
            $users = $users->where('ut.group_id', $group_id);
        }
        if($class_id !='') {
            $users = $users->where('bo.class_id',  $class_id);
        }
        if($shop_id !='') {
            $users = $users->where('bo.pickup_id',  $shop_id);
        }
        if($prefecture !='') {
          $users = $users->where('p.prefecture','like','%'.$prefecture.'%');
        }
        $users = $users->get();
        $user_list = array();
        foreach ($users as $user) {
            $user_id = $user->id;
            if($booking_numbers != '') {
                $number = DB::table('bookings')
                    ->select(DB::raw('count(*) as user_number'))
                    ->where('client_id', $user_id)
                    ->groupBy('client_id')
                    ->first();
                $bo_number = 0;
                if(!empty($number)) $bo_number = $number->user_number;
                if($booking_numbers == '1') {
                    if($bo_number < 1 ) continue;
                }
                if($booking_numbers == '2') {
                    if($bo_number <2 || $bo_number > 5 ) continue;
                }
                if($booking_numbers == '6') {
                    if($bo_number <6 || $bo_number > 10 ) continue;
                }
                if($booking_numbers == '11') {
                    if($bo_number < 11 || $bo_number > 20 ) continue;
                }
                if($booking_numbers == '21') {
                    if($bo_number < 21  ) continue;
                }
            }
            if($spent_amount != '') {
                $price = 0 ;
                $payments = DB::table('bookings')
                    ->select('client_id',DB::raw('sum(payment) as sum_payment'))
                    ->where('pay_status','1')
                    ->where('client_id', $user_id)
                    ->groupBy('client_id')
                    ->first();
                if(!empty($payments)) $price += $payments->sum_payment;
                $cancel_fee = DB::table('bookings')
                    ->select('client_id', DB::raw('sum(cancel_fee) as cancel_fee'))
                    ->where('client_id', $user_id)
                    ->groupBy('client_id')
                    ->first();
                if(!empty($cancel_fee)) $price += $cancel_fee->cancel_fee;
                $adds = \DB::table('bookings_price as pr')
                            ->leftjoin('bookings as bo','bo.id','pr.book_id')
                            ->where('bo.client_id',$user_id)
                            ->where('pr.pay_status', '1')
                            ->select('client_id',DB::raw('sum(pr.total_price) as add_payment'))
                            ->groupBy('bo.client_id')
                            ->first();
               if(!empty($adds)) $price +=$adds->add_payment;
               if($spent_amount == '40000') {
                   if($price > 40000) continue;
               }
               if($spent_amount == '40001') {
                   if($price < 40001 && $price > 100000 ) continue;
               }
               if($spent_amount == '100001') {
                   if($price < 100001) continue;
               }
            }
            array_push($user_list, $user);
        }
        $data = [
            'route' => $route,
            'registerdate'      => $registerdate ,
            'lastreturndate'    => $lastreturndate,
            'booking_numbers'   => $booking_numbers,
            'spent_amount'      => $spent_amount,
            'members'   => $user_list,
            'groups'    => $groups,
            'class'     => $class,
            'group_id'  => $group_id,
            'class_id'  => $class_id,
            'shop'      => $shop,
            'shop_id'   => $shop_id,
            'prefecture' => $prefecture
        ];
        return view('pages.admin.member.direct-message')->with($data);
    }


    /*
     * send message to members groups     *
     */
    public function sendMessage(Request $request) {
        $route = Route::getFacadeRoot()->current()->uri();
        $subject   = $request->get('subject', '');
        $sender   = $request->get('sender', '');
        $content = $request->get('content', '');
        $member_list = $request->get('members', '');
        $members = explode(",",$member_list);
        if(!empty($members)) {
            foreach ( $members as $me) {
                $user = \DB::table('users')->where('id', $me)->first();
                $message = $content;
                if(!empty($user)) {
                    $message = str_replace('{user_name}', $user->last_name . " " . $user->first_name, $message);
                    $content = $message;
                    $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $sender, 'email' => $user->email);
                    $data[] = $data1;
                }
            }
        }


        if(!empty($members)) {
            $message = $content;
            $message = str_replace('{user_name}', ' ', $message);
            $content = $message;

            if(strpos($_SERVER['HTTP_HOST'], 'hakoren') === false){
                // for motocle8 test
                $mail_addresses = [
                    'future.syg1118@gmail.com',
                    'business@motocle.com',
                    'mailform@motocle.com'
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
                    'mailform@motocle.com',
                    'business@motocle.com'
                ];
            }

            foreach ($mail_addresses as $address){
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $sender, 'email' => $address);
                $data[] = $data1;
            }
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
}
