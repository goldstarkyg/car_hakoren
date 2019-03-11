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
use App\Models\UserLog;

class EndUserLogController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logs($id)
    {
        $logs = \DB::table('userlogs as ul')
            ->leftJoin('log_category as uc', 'uc.id', '=', 'ul.logcat_id')
            ->leftJoin('category as c', 'c.id', '=', 'ul.arcat_id')
            ->leftJoin('articles as a', 'a.id', '=', 'ul.article_id')
            ->select('ul.*', 'uc.name as logcatname', 'c.name as catname', 'a.title as articletitle')->where('user_id', $id)->orderby('id', 'desc')->get();
        $user = \DB::table('users')->where('id', $id)->select('first_name', 'last_name')->first();
        return View('usersmanagement.show-log-endusers', compact('logs', 'user'));
    }


}
