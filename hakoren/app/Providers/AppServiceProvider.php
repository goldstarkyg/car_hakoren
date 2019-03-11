<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use URL;
use Validator;
use Auth;
use Hash;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //URL::forceScheme('https');
        //
        Schema::defaultStringLength(191);
		Validator::extend('current_password', function ($attribute, $value, $parameters) {
			$user	= User::find(Auth::user()->id);			
			return $user && Hash::check($value, $user->password);
		});		
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
