<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\CaptureIpTrait;
use App\Models\User;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

// Add LOG HERE
        if ($exception instanceof \jeremykenedy\LaravelRoles\Exceptions\LevelDeniedException) {

// ADD FLASH AND REDIRECT HERE

            return redirect()->back();

        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
//            $user = \Auth::user();
//            if($user && $user->role()->level < 4) {
                return redirect()->route( 'login' );
//            } else {
//                return redirect()->route( 'mtclsecuredlogin' );
//            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // session aaaaaaa
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        $ipAddress  = new CaptureIpTrait;
        $ipaddress  = $ipAddress->getClientIp();
        $user = User::where('signin_ip', $ipaddress)->first();
        $admin = 'false';
        if(!empty($user)){
            $user_id = $user->id;
            $role = \DB::table('roles as r')
                ->leftjoin('role_user as ru', 'ru.role_id','=','r.id')
                ->where('ru.user_id',$user_id)->select('r.*')->first();
            if(!empty($role)) {
                if($role->level == '5' || $role->level == '4' ) {
                    $admin = 'true';
                }
            }
        }
        if($admin == 'true')
            return redirect()->guest(route('mtclsecuredlogin'));
        else
            return redirect()->guest(route('login'));
    }
}
