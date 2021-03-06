<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;
use App\Models\Profile;
use App\Models\Theme;
use App\Models\User;
use Validator;
use View;
use Helper;


use Image;
use File;


use App\Notifications\SendGoodbyeEmail;
use App\Traits\CaptureIpTrait;



use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;


class ProfilesController extends Controller
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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function profile_validator(array $data)
    {
        return Validator::make($data, [
            'theme_id'          => '',
            'location'          => '',
            'bio'               => 'max:500',
            'twitter_username'  => 'max:50',
            'github_username'   => 'max:50',
            'avatar'            => '',
            'avatar_status'     => '',
        ]);
    }

    /**
     * Fetch user
     * (You can extract this to repository method)
     *
     * @param $username
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        return User::with('profile')->wherename($username)->firstOrFail();
    }

    /**
     * Display the specified resource.
     *
     * @param string $username
     * @return Response
     */
    public function show($username)
    {
        try {

            $user = $this->getUserByUsername($username);

        } catch (ModelNotFoundException $exception) {

            abort(404);

        }

        $currentTheme = Theme::find($user->profile->theme_id);

        $data = [
            'user' => $user,
            'currentTheme' => $currentTheme
        ];

        return view('profiles.show')->with($data);

    }

    /**
     * /profiles/username/edit
     *
     * @param $username
     * @return mixed
     */
    public function edit($username)
    {
        try {

            $user = $this->getUserByUsername($username);

        } catch (ModelNotFoundException $exception) {
            return view('pages.status')
                ->with('error', trans('profile.notYourProfile'))
                ->with('error_title', trans('profile.notYourProfileTitle'));
        }

        $themes = Theme::where('status', 1)
                       ->orderBy('name', 'asc')
                       ->get();

        $currentTheme = Theme::find($user->profile->theme_id);

        $data = [
            'user'          => $user,
            'themes'        => $themes,
            'currentTheme'  => $currentTheme

        ];

        return view('profiles.edit')->with($data);

    }

    /**
     * Update a user's profile
     *
     * @param $username
     * @return mixed
     * @throws Laracasts\Validation\FormValidationException
     */
    public function update($username, Request $request)
    {
        $user = $this->getUserByUsername($username);

        $input = Input::only('theme_id', 'location', 'bio', 'twitter_username', 'github_username', 'avatar_status');

        $profile_validator = $this->profile_validator($request->all());

        if ($profile_validator->fails()) {

            $this->throwValidationException(
                $request, $profile_validator
            );

            return redirect('profile/'.$user->name.'/edit')->withErrors($validator)->withInput();
        }

        if ($user->profile == null) {

            $profile = new Profile;
            $profile->fill($input);
            $user->profile()->save($profile);

        } else {

            $user->profile->fill($input)->save();

        }

        $user->save();

        return redirect('profile/'.$user->name.'/edit')->with('success', trans('profile.updateSuccess'));

    }

    /**
     * Upload and Update user avatar
     *
     * @param $file
     * @return mixed
     */
    public function upload() {
        if(Input::hasFile('file')) {

          $avatar       = Input::file('file');
          $filename     = 'avatar.' . $avatar->getClientOriginalExtension();
          $save_path    = storage_path() . '/users/id/' . \Auth::user()->id . '/uploads/images/avatar/';
          $path         = $save_path . $filename;

          // Make the user a folder and set permissions
          File::makeDirectory($save_path, $mode = 0755, true, true);

          // Save the file to the server
          Image::make($avatar)->resize(300, 300)->save($save_path . $filename);

          return response()->json(array('path'=> $path), 200);

        } else {

          return response()->json(false, 200);

        }
    }

    /**
     * Show user avatar
     *
     * @param $id
     * @param $image
     * @return string
     */
    public function userProfileAvatar($id, $image)
    {
        return Image::make(storage_path() . '/users/id/' . $id . '/uploads/images/avatar/' . $image)->response();
    }
	
	
    public function userLicenseSurface($id, $image)
    {
        return Image::make(storage_path() . '/users/id/' . $id . '/uploads/images/license/' . $image)->response();
    }
	
    public function userLicenseBack($id, $image)
    {
        return Image::make(storage_path() . '/users/id/' . $id . '/uploads/images/license/' . $image)->response();
    }		

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserAccount(Request $request, $id)
    {

        $currentUser = \Auth::user();
        $user        = User::findOrFail($id);
        $emailCheck  = ($request->input('email') != '') && ($request->input('email') != $user->email);
        $ipAddress   = new CaptureIpTrait;

        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:255',
            'depart_id'      => 'required',
        ]);

        $rules = [];

        if ($emailCheck) {
            $rules = [
                'email'     => 'email|max:255|unique:users'
            ];
        }

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $user->name         = $request->input('name');
        $user->first_name   = $user->first_name;
        $user->last_name    = $user->last_name;
        $user->depart_id    = $request->input('depart_id');

        if ($emailCheck) {
            $user->email = $request->input('email');
        }

        $user->updated_ip_address = $ipAddress->getClientIp();

        $user->save();

        $profile = Profile::where('user_id', $user->id)->first();
        $profile->first_name = $user->first_name;
        $profile->last_name = $user->last_name;
        $profile->save();
        return redirect('profile/'.$user->name.'/edit')->with('success', trans('profile.updateAccountSuccess'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserPassword(Request $request, $id)
    {

        $currentUser = \Auth::user();
        $user        = User::findOrFail($id);
        $ipAddress   = new CaptureIpTrait;

        $validator = Validator::make($request->all(),
            [
                'password'              => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'password.required'     => trans('auth.passwordRequired'),
                'password.min'          => trans('auth.PasswordMin'),
                'password.max'          => trans('auth.PasswordMax'),
            ]
        );

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        if ($request->input('password') != null) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->updated_ip_address = $ipAddress->getClientIp();

        $user->save();

        return redirect('profile/'.$user->name.'/edit')->with('success', trans('profile.updatePWSuccess'));

    }

    public function setpassword($username){
        try {

            $user = $this->getUserByUsername($username);

        } catch (ModelNotFoundException $exception) {
            return view('pages.status')
                ->with('error', trans('profile.notYourProfile'))
                ->with('error_title', trans('profile.notYourProfileTitle'));
        }

        $themes = Theme::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        //$currentTheme = Theme::find($user->profile->theme_id);

        $data = [
            'user'          => $user,
            //'themes'        => $themes,
            //'currentTheme'  => $currentTheme

        ];

        return view('profiles.setpassword')->with($data);
    }

}
