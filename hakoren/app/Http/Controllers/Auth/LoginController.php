<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\User;
use App\Traits\CaptureIpTrait;
use App\Models\UserLog;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectAfterLogout = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    /**
     * Logout, Clear Session, and Return.
     *
     * @return void
     */
    public function logout(Request $request)
    {
        $request->session()->flash('adminlogin', '');
        $user = Auth::user();
        Log::info('User Logged Out. ', [$user]);

        $rolemark = 0;
        foreach (Auth::user()->roles as $user_role){
            if($user_role->name == 'User'){
                $rolemark = 1;
            }
        }
        if($rolemark == 1) {
            $ipAddress = new CaptureIpTrait;
            if ($ipAddress->getClientIp() != null && $ipAddress->getClientIp() != '0.0.0.0') {
                $details = json_decode(file_get_contents("http://ip-api.com/json/" . $ipAddress->getClientIp()));
                $log = new UserLog();
                $log->user_id = $user->id;
                $log->logcat_id = 3;
                $log->ipaddress = $ipAddress->getClientIp();
                $city       = '';
                $regionName = '';
                $country    = '';
                $zip        = '';
                if(!empty($details->city)) $city = $details->city;
                if(!empty($details->regionName)) $regionName = $details->regionName;
                if(!empty($details->country)) $country = $details->country;
                if(!empty($details->zip)) $zip = $details->zip;
                $log->location = $city . ', ' . $regionName . ', ' . $country . ', ' . $zip;
                $log->prefecture = $regionName;
                $log->logcat_id = 3;
                $log->content = 'ユーザーのログアウト';
                $log->waitingtime = 0;
                $log->save();
            } else {
                $log = new UserLog();
                $log->user_id = $user->id;
                $log->logcat_id = 3;
                $log->prefecture = '';
                $log->location = '';
                $log->content = 'ユーザーのログアウト';
                $log->waitingtime = 0;
                $log->save();
            }
        }
        Auth::logout();
        Session::flush();
        if($rolemark == 1)
            return redirect()->to('/login');
        else
            return redirect()->to('/mtclsecuredlogin');
        //return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

}
