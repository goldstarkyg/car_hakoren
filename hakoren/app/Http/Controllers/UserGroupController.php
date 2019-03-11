<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Profile;
use App\Models\RoleUser;
use App\Models\Score;
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

class UserGroupController extends Controller
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

        $usergroup = \DB::table('users_group as u')
            ->select(['u.*']);
        $usergroup = $usergroup
            ->orderby('u.id', 'desc')->get();
        return View('pages.admin.usergroup.index-usergroup', compact('usergroup','route'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route = Route::getFacadeRoot()->current()->uri();
        $data = [
            'route' => $route
        ];

        return view('pages.admin.usergroup.create-usergroup')->with($data);
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
                'name'            => 'required|unique:users_group',
                'alias'           => 'required'
            ],
            [
                'name.required'   => trans('auth.groupnameRequired'),
                'name.unique'     => trans('auth.groupnameUnique'),
                'alias.required'  => trans('auth.groupaliasRequired'),
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
            $name = $request->input('name');
            $alias = $request->input('alias');

            $usergroup= \DB::insert('insert into users_group (name, alias) values (?, ?)', [$name, $alias]);

            return redirect('/settings/usergroup')->with('success', trans('auth.createUserGroup'));

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
        $usergroup =  \DB::select('select * from users_group where id = :id', ['id' => $id]);

        $data = [
            'usergroup'     => $usergroup[0],
            'route'         => $route
        ];
        return view('pages.admin.usergroup.edit-usergroup')->with($data);
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
            $group_name     = $request->input('name');
            $group_alias    = $request->input('alias');
            $usergroup      = \DB::update("update users_group set name ='".$group_name."', alias='".$group_alias."'  where id =?", [$id]);

            return back()->with('success', trans('usersmanagement.updateSuccess'));

     }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $deleted = \DB::delete('delete from users_group where id='.$id);
        if ($deleted) {
            return redirect('/settings/usergroup')->with('success', 'Excluded this user group from the user groups list.');
        }
        return back()->with('error', trans('usersmanagement.deleteSelfError'));

    }
}
