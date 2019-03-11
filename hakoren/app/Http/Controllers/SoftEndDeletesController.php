<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Profile;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Auth;
use Illuminate\Http\Request;
use jeremykenedy\LaravelRoles\Models\Role;

class SoftEndDeletesController extends Controller
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
     * Get Soft Deleted User.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedUser($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->get();
        if (count($user) != 1) {
            return redirect('/endusers/deleted/')->with('error', trans('usersmanagement.errorUserNotFound'));
        }
        return $user[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $users = User::onlyTrashed()->where('delflg', 0)->orderby('deleted_at', 'desc')->get();
        $users2 = User::onlyTrashed()->where('delflg', 1)->orderby('deleted_at', 'desc')->get();
        $users3 = User::onlyTrashed()->get();
        $roles = Role::all();
        $tab = $request->get('tab', 1);
        return View('usersmanagement.show-deleted-endusers', compact('users', 'users2', 'users3', 'tab', 'roles'));
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

        //$user = User::find($id);
        $user = self::getDeletedUser($id);
        $loginmonths = array();
        for($i = 1; $i <= 12; $i++){
            $month = $i;
            if($i < 10) $month = '0'.$i;
            $year = date('Y');
            $date = $year.'-'.$month;
            $count = \DB::table('userlogs')->where('user_id', $user->id)->whereIn('logcat_id', [1,2])->where('created_at', 'like', '%'.$date.'%')->count();

            $loginmonths[] = array('key'=>$i, 'count'=>$count);
        }
        $totalcount = \DB::table('userlogs')->where('user_id', $user->id)->whereIn('logcat_id', [1,2])->count();

        $month = $request->get('month', '');
        if($month != ''){
            $month = date('Y')."-".$month;
        }
        $categories = \DB::table('category')->orderby('id', 'asc')->get();
        $tab = $request->get('tab', 1);


        return view('usersmanagement.show-deleted-enduser', compact('loginmonths', 'tab', 'totalcount', 'month', 'categories', 'cat_id', 'dateinterval'))->withUser($user);
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
        $user = self::getDeletedUser($id);
        $user->delflg = 0;
        $user->save();
        $user->restore();
        //\DB::table('userlogs')->where('user_id', $id)->delete();
        //\DB::table('scores')->where('user_id', $id)->delete();
        return redirect('/endusers/')->with('success', trans('usersmanagement.successRestore'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = self::getDeletedUser($id);
        $user->activated = 0;
        $user->deleted_at = date('Y-m-d H:i:s');
        $user->save();
//        $user->forceDelete();
        return redirect('/endusers/deleted/')->with('success', trans('usersmanagement.successDestroy'));
    }

}
