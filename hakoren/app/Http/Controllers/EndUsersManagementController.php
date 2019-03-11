<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Profile;
use App\Models\RoleUser;
use App\Models\Score;
use App\Models\Shop;
use App\Models\User;
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

class EndUsersManagementController extends Controller
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
            ->select(['u.*', 'r.role_id', 'ro.slug as role_slug']);

        if($dateinterval != ''){
            $dates = explode(' - ', $dateinterval);
            $startdate = date('Y-m-d', strtotime($dates[0]));
            $enddate = date('Y-m-d', strtotime($dates[1]));
            $users = $users->where('u.updated_at', '>=', $startdate)->where('u.updated_at', '<=', $enddate);
        }
        $users = $users
            ->whereNull('u.deleted_at')
//            ->where('u.id','!=','1')  //except superadmin
            ->whereIn('r.role_id', [4, 1])  //only sub admin
            ->orderby('r.role_id')
            ->orderby('u.created_at', 'desc')
            ->get();

        $roles = Role::all();
        return View('pages.admin.usermanagement.show-users', compact('users', 'roles', 'dateinterval','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route = Route::getFacadeRoot()->current()->uri();
        $roles = Role::where('level' ,'5')->orwhere('level','4')->get();
        $shops = Shop::all();
        $data = [
            'roles' => $roles,
            'route' => $route,
            'shops' => $shops
        ];

        return view('pages.admin.usermanagement.create-user')->with($data);
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
                //'name'                  => 'required|max:255|unique:users',
                'last_name'             => 'required',
                'email'                 => 'required|email|max:255|unique:users',
                'password'              => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
                'role'                  => 'required'
            ],
            [
                //'name.unique'           => trans('auth.userNameTaken'),
                //'name.required'         => trans('auth.userNameRequired'),
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

            $user =  User::create([
                'name'              => $request->input('name'),
                'first_name'        => $request->input('first_name'),
                'last_name'         => $request->input('last_name'),
                'email'             => $request->input('email'),
                'password'          => bcrypt($request->input('password')),
                'token'             => str_random(64),
                'admin_ip_address'  => $ipAddress->getClientIp(),
                'activated'         => 1
            ]);

            $user_id = $user->id;
            $user->profile()->save($profile);
            $user->attachRole($request->input('role'));
            $user->name = str_pad($user_id, 6, 0, STR_PAD_LEFT);
            $user->save();

            // save related shops
            if($request->has('shop_id')) {
                $data = [];
                $shop_id = $request->get('shop_id');
                $data[] = array('admin_id'=>$user_id, 'shop_id'=>$shop_id);
                if(!empty($data)) {
                    \DB::table('admin_shop')->where('admin_id', $user_id)->delete();
                    \DB::table('admin_shop')->insert($data);
                }
            }

            return redirect('/settings/endusers')->with('success', trans('usersmanagement.createSuccess'));
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

        $cat_id = $request->get('catid', '');
        $dateinterval = $request->get('dateinterval', '');

        $user = User::find($id);
        if(!empty($user)) {
            $check = \DB::table('role_user')->where('user_id', $user->id)->first();
            if (!empty($check) && $check->role_id != 4) {
                return Redirect::back();
            }
        }else{
            return Redirect::back();
        }

        $loginmonths = array();
        for($i = 11; $i >= 0; $i--){
            $mm = date('n', strtotime('-'.$i.' months'));
            $year = date('Y', strtotime('-'.$i.' months'));
            $month = $mm;
            if($mm < 10) $month = '0'.$i;
            $date = date('Y-m', strtotime('-'.$i.' months'));
//            $date = $year.'-'.$month;
            $count = count(\DB::table('userlogs')->where('user_id', $user->id)->where('logcat_id', 2)->where('created_at', 'like', '%'.$date.'%')->get());

            $loginmonths[] = array('key'=>$mm, 'count'=>$count);
        }
        $startdate = date('Y-m-d H:i:s', strtotime('-90 days'));
        $totalcount = count(\DB::table('userlogs')->where('user_id', $user->id)->where('logcat_id', 2)->where('created_at', '>=', $startdate)->get());

        $month = $request->get('month', '');
        if($month != ''){
            $month = date('Y')."-".$month;
        }
        //$categories = \DB::table('category')->orderby('id', 'asc')->get();

        return view('pages.admin.usermanagement.show-user', compact('loginmonths', 'totalcount', 'month',  'cat_id', 'dateinterval'))->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $route = Route::getFacadeRoot()->current()->uri();
        $user = User::findOrFail($id);
        $roles = Role::where('level','5')
            ->orwhere('level','4')->get();
        $shops = Shop::all();

        foreach ($user->roles as $user_role) {
            $currentRole = $user_role;
        }

        $user_shops = \DB::table('admin_shop')->where('admin_id', $id)->get();
        $tmp = [];
        foreach ($user_shops as $ushop) {
            $tmp[] = $ushop->shop_id;
        }

        $data = [
            'user'          => $user,
            'roles'         => $roles,
            'currentRole'   => $currentRole,
            'route'         => $route,
            'shops'         => $shops,
            'admin_shops'   => $tmp
        ];
        return view('pages.admin.usermanagement.edit-user')->with($data);
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
                'first_name'      => 'max:255',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'first_name'=> 'max:255',
                'email'     => 'email|max:255|unique:users',
            ]);
        }
//        if($request->input('password') == '' ) {
//            return back()->with('error', trans('auth.passwordnull'));
//        }
//        if($request->input('password')) {
//            $validator = Validator::make($request->all(), [
//                'password'  => 'nullable|confirmed|min:6'
//            ],
//            [
//                'passowrd.nullable'     => trans('auth.passwordnull'),
//                'password.confirmed'     => trans('auth.passwordConfirmed'),
//                'password.min'          => trans('auth.PasswordMin')
//            ]);
//        }

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
            //$user->activated = 1;

            $user->save();
            // save related shops
            if($request->has('shop_ids')) {
                $data = [];
                $shop_ids = explode(',', $request->get('shop_ids'));

                foreach ($shop_ids as $sid) {
                    $data[] = array('admin_id'=>$id, 'shop_id'=>$sid);
                }
                if(!empty($data)) {
                    \DB::table('admin_shop')->where('admin_id', $id)->delete();
                    \DB::table('admin_shop')->insert($data);
                }
            }
            return back()->with('success', trans('usersmanagement.updateSuccess'));
        }

//        $input = Input::only('name', 'first_name', 'last_name', 'email');
//        $input_role_id = Input::only('role_id');
//
//        $ipAddress = new CaptureIpTrait;
//        if($affectedRows = User::where('id',$id)->update($input)) {
//            if($affectrole = RoleUser::where('user_id',$id)->update($input_role_id)) {
//                return back()->with('success', trans('usersmanagement.updateSuccess'));
//            }
//        }else {
//            return back()->with('error', trans('usersmanagement.errorNotsave'));
//        }
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
//            $user->delete();
            $user->activated = 0;
            $user->deleted_at = date('Y-m-d H:i:s');
            $user->email = $user->email.'hakoren'.mt_rand(100, 999);
            $user->deleted_ip_address = $ipAddress->getClientIp();
            $user->save();

            // clear admin from admin_shop table
            \DB::table('admin_shop')->where('admin_id', $id)->delete();

            return redirect('/settings/endusers')->with('deletesuccess', 'このユーザーを会員リストから除外しました。');
        }
        return back()->with('error', trans('usersmanagement.deleteSelfError'));

    }
}
