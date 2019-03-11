<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/
//Route::get('/', 'WelcomeController@welcome')->name('welcome');
//Route::any('/google/menu', 'GoogleSpreadController@getMenuData');

// Authentication Routes
Auth::routes();
Route::get('lang/{lang}', function($lang) {
    \Session::put('lang', $lang);
    return \Redirect::back();
})->middleware('web')->name('change_lang');

Route::get('en', function() {
    \Session::put('lang', 'en');
    return redirect('/');
});

$lang = Request::segment(1) == 'en'? 'en' : '';

Route::any('/notifitest1', 'DashBoardController@PriceMountYesterday');
Route::any('/sale_test', 'TestController@test');
// Public Routes
Route::group(['prefix'=>$lang, 'middleware' => 'web'], function() {
    Route::get('/{shop}/search-car', 'Front\SearchController@search');
    Route::any('/', 'Front\SearchController@showToppage');
    Route::any('/search-confirm/', 'Front\SearchController@search_confirm');
    Route::get('getfbpost', 'Front\SearchController@getFBPost');
    // Get Optinos
    Route::post('getOptionsByShopid', 'Front\SearchController@getOptionsByShopid');
//    Route::any('/shop-detail/{shortname}', 'Front\SearchController@showShopdetail');
    Route::any('/shop/{shortname}', 'Front\SearchController@showShopdetail');
    Route::get('/campaign/{shortname}/{start}', 'Front\SearchController@showCampaigndetail');
//    Route::post('/campaign/{shortname}/{start}', 'Front\SearchController@showCampaigndetail');


	Route::get('/agreement', function () {
		Return view ('pages.frontend.agreement');
	});
	
	Route::get('/fukuoka-rentacar-trip-information', function () {
		Return view ('pages.frontend.fukuoka-rentacar-trip');
	});
	
	Route::get('/okinawa-rentacar-trip-information', function () {
		Return view ('pages.frontend.okinawa-rentacar-trip');
	});
	
	Route::post('/savebag_choose/', 'Front\SearchController@savebag_choose');
	
    Route::get('/quickstart-01/', 'Front\SearchController@quickstart_01');
    Route::post('/savequickstart-01/', 'Front\SearchController@savequickstart_01');

    Route::any('/quickstart-02/', 'Front\SearchController@quickstart_02');
    Route::post('/savequickstart-02/', 'Front\SearchController@savequickstart_02');

    Route::any('/quickstart-03/', 'Front\SearchController@quickstart_03');
    Route::any('/savequickstart-03/', 'Front\SearchController@savequickstart_03');
	Route::get('/pay-locally/', 'Front\SearchController@pay_offline');
	Route::get('/campaign/summer_campaign2018/', 'Front\SearchController@showCampaign');
	
    Route::any('/faq/{title}', 'Front\SearchController@showFaq');
    Route::any('/contact', 'Front\SearchController@showContact');
    Route::any('/business_contact', 'Front\SearchController@showBusinessContact');
    Route::any('/insurance', 'Front\SearchController@showInsurance');
    Route::any('/transactions', 'Front\SearchController@showTransactions');
    Route::any('/first', 'Front\SearchController@showFirst');
    Route::any('/agreement', 'Front\SearchController@showAgreement');
    Route::any('/rules', 'Front\SearchController@showRules');
    Route::any('/policy', 'Front\SearchController@showPolicy');
    Route::any('/booking', 'Front\SearchController@showBooking');
    Route::any('/passing', 'Front\SearchController@showPassing');
    Route::any('/search-car', 'Front\SearchController@search');
    Route::any('/search-save', 'Front\SearchController@search_save');
    Route::any('/book_campaign', 'Front\SearchController@book_campaign');
    Route::any('/email-check', 'Front\SearchController@email_check');
    Route::any('search/getoption', 'Front\SearchController@getOptions');
    Route::any('search/getshopoption', 'Front\SearchController@getShopOptions');
    Route::any('search/getclasses', 'Front\SearchController@search_classes');
    Route::any('/class-search', 'Front\SearchController@class_search');
    Route::any('/thankyou', 'Front\SearchController@search_thankyou');
    Route::any('/news-listing', ['as' => 'newslist','uses' => 'Front\BlogController@newslist']);
    Route::any('/info', ['as' => 'viewallpost','uses' => 'Front\BlogController@index']);
    Route::any('/info/{category}', ['as' => 'viewcategorypost','uses' => 'Front\BlogController@categoryindex']);
    Route::any('/info/{category}/{articletag}', ['as' => 'viewarticletagpost','uses' => 'Front\BlogController@articletagindex']);
    Route::any('/blog-tags/{tag}', ['as' => 'viewtagpost','uses' => 'Front\BlogController@tagindex']);
    Route::any('/view-post/{slug}', ['as' => 'viewpost','uses' => 'Front\BlogController@view']);
 	 
	
    //change language
    Route::get('lang/{locale}', function ($locale) {
        Session::put('locale', $locale);
        return redirect()->route('search');
    });
    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'Auth\ActivateController@exceeded']);
 	
    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'Auth\SocialController@getSocialHandle']);
    Route::any('/carclass-detail/{class_id}', 'Front\SearchController@carclass_detail');
    Route::any('/getpassengerlist', 'Front\SearchController@get_class_passengers');
    Route::any('/carclasslist', 'Front\SearchController@carclassList');
    Route::any('/carclasslist/{shop_slug}', 'Front\SearchController@carclassList');
	
	Route::any('/showbubbletext', 'Front\SearchController@showbubbletext');
	Route::any('/showbubblestep', 'Front\SearchController@showbubblestep');


    Route::any('/forgotpassword', 'Front\SearchController@forgotpassword');
    Route::any('/resetpassword', 'Front\SearchController@resetpassword');
	Route::get('/page/{slug}','Front\PageController@showDynamicPage');

});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated']], function() {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'Auth\ActivateController@activationRequired'])->name('activation-required');
    //Route::get('/logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');
    Route::get('/logout', ['uses' => 'Auth\LoginController@logout']);

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'UserController@index']);

    /* user mypage */
    Route::any('/mypage/top', 'UserController@showMypageTop');
	Route::any('/mypage/movetoqs/{booking}', 'UserController@redirectQuickstart');
    Route::any('/mypage/profile', 'UserController@showMypageProfile');	 
    Route::post('/save_mypage_profile', ['as' => 'mypage.profile', 'uses' =>'UserController@updateMypageProfile']);	
	
    Route::any('/mypage/changepassword', 'UserController@changepassword');	 	
	Route::post('/update_mypage_password', ['as' => 'mypage.updatepassword', 'uses' =>'UserController@updatePassword']);	
	
    Route::any('/mypage/log', 'UserController@showMypageLog');
    Route::any('/mypage/faq', 'UserController@showMypageFaq');
    Route::any('/mypage/bookingupdate', 'UserController@updateBooking');
    /***************/

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'        => '{username}',
        'uses'      => 'ProfilesController@show'
    ]);

});

// Registered, activated, and is current user routes.
Route::group(['middleware'=> ['auth', 'activated', 'currentUser']], function () {

    Route::get('profile/{username}/setpassword', [
        'as'        => '{username}',
        'uses'      => 'ProfilesController@setpassword'
    ]);

    Route::resource(
        'profile',
        'ProfilesController', [
            'only'  => [
                'show',
                'edit',
                'update',
                'create'
            ]
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'        => '{username}',
        'uses'      => 'ProfilesController@updateUserAccount'
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'        => '{username}',
        'uses'      => 'ProfilesController@updateUserPassword'
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'        => '{username}',
        'uses'      => 'ProfilesController@deleteUserAccount'
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses'      => 'ProfilesController@userProfileAvatar'
    ]);
	
    Route::get('images/profile/{id}/licenseback/{image}', [
        'uses'      => 'ProfilesController@userLicenseBack'
    ]);
    Route::get('images/profile/{id}/licensesurface/{image}', [
        'uses'      => 'ProfilesController@userLicenseSurface'
    ]);		

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'ProfilesController@upload']);

});

// Registered, activated, and is admin routes.
Route::group(['middleware'=> ['auth', 'activated', 'role:admin|subadmin']], function () {

    Route::resource('users', 'UsersManagementController', [
        'names' => [
            'index' => 'users',
            'destroy' => 'user.destroy'
        ]
    ]);

//    Route::resource('themes', 'ThemesManagementController', [
//        'names' => [
//            'index' => 'themes',
//            'destroy' => 'themes.destroy'
//        ]
//    ]);

    //Route::get('/admintop', ['as' => 'public.admintop',   'uses' => 'UserController@adminindex'])->name('admintop');
    Route::any('/admintop', ['as' => 'public.admintop1',   'uses' => 'DashBoardController@admintop'])->name('admintop1');
    
    
    
	Route::get('/download_files', function () {
		return view('pages.admin.downloadfiles');
	});
    //simpleform management
    Route::get('/simpleform/export', 'SimpleFormController@getExportData');
    Route::resource('simpleform', 'SimpleFormController', [
        'names' => [
            'index' => 'simpleform',
            'destroy' => 'simpleform.destroy'
        ],
        'except' => [
            'deleted'
        ]
    ]);

    //member managemant
    Route::get('/member/export', 'MemberManagementController@getExportData');
//    Route::resource('/member/deleted', 'SoftEndDeletesController', [
//        'only' => [
//            'index', 'show', 'update', 'destroy',
//        ]
//    ]);
//    Route::post('/member/{user_id}', 'MemberManagementController@show');
    Route::resource('members', 'MemberManagementController', [
        'names' => [
            'index' => 'member',
            'destroy' => 'member.destroy'
        ],
        'except' => [
            'deleted'
        ]
    ]);
    Route::resource('tag', 'MemberTagController');
    Route::any('directmessage', 'MemberManagementController@directMessage');
    Route::any('directmessage/sendmessage', 'MemberManagementController@sendMessage');


    //simpleform usermanagement
    Route::get('settings/endusers/logs/{id}', 'EndUserLogController@logs');
    Route::get('settings/endusers/export', 'EndUsersManagementController@getExportData');
    Route::resource('settings/endusers/deleted', 'SoftEndDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ]
    ]);
    Route::post('settings/endusers/{user_id}', 'EndUsersManagementController@show');
    Route::resource('settings/endusers', 'EndUsersManagementController', [
        'names' => [
            'index' => 'endusers',
            'destroy' => 'enduser.destroy'
        ],
        'except' => [
            'deleted'
        ]
    ]);
    //car basic module
    Route::resource('settings/usergroup', 'UserGroupController');
    Route::any('carbasic/carclassa/getcartypemodel', 'CarClassController@getcartypemodel');
    Route::post('carbasic/carclass/updatenormal', 'CarClassController@updatenormal');
    Route::post('carbasic/carclass/updatecustom', 'CarClassController@updatecustom');
    Route::post('carbasic/carclass/updateoption', 'CarClassController@updateoption');
    Route::post('carbasic/carclass/updateinsurance', 'CarClassController@updateinsurance');
    Route::post('carbasic/carclass/updateequipmant', 'CarClassController@updateequipment');
    Route::post('carbasic/carclass/update_model_order/{id}', 'CarClassController@update_model_order');

    Route::get('carbasic/carclass/editpricecustom/{id}', 'CarClassController@editpricecustom');
    Route::get('carbasic/carclass/deletepricecustom/{id}', 'CarClassController@deletepricecustom');
    Route::resource('carbasic/carclass', 'CarClassController');
	
	Route::any('fetchCarClassCustomData', 'CarClassController@fetchCarClassCustomData');	
	
    Route::any('carbasic/carclasspost', 'CarClassController@index');
    Route::resource('carbasic/cartype', 'CarTypeController');
    Route::resource('carbasic/caroption', 'CarOptionController');
    Route::any('carbasic/carmodela/getcartype', 'CarModelController@getcartype');
    Route::resource('carbasic/carmodel', 'CarModelController');
    Route::resource('carbasic/carequip', 'CarEquipController');
    Route::resource('carbasic/carinsurance', 'CarInsuranceController');
    Route::resource('carbasic/carpassenger', 'CarPassengerController');

    //shop basic module
    Route::post('shopbasic/updatecomment/{id}', 'ShopController@updatecomment');
    Route::post('shopbasic/updatebusiness', 'ShopController@updatebusiness');
    Route::post('shopbasic/updatebusinesscustom', 'ShopController@updatebusinesscustom');
    Route::get('shopbasic/editbusinesscustom/{id}', 'ShopController@editbusinesscustom');
    Route::get('shopbasic/deletebusinesscustom/{id}', 'ShopController@deletebusinesscustom');
    Route::any('shopbasic/updateshoppickup/{id}', 'ShopController@updatePickup');
    Route::any('shopbasic/deleteshoppickup/{id}', 'ShopController@deletePickup');
    Route::resource('shopbasic/shop', 'ShopController');
    Route::resource('carinventory/inventory', 'CarInventoryController');
    Route::any('carinventory/inventory', 'CarInventoryController@index');
    Route::any('carinventory/calendar/{id}', 'CarCalendarController@index');
    Route::any('carinventory/priority', 'CarInventoryController@priority');
    Route::any('carinventory/storepriority', 'CarInventoryController@storepriority');
    Route::any('carinventory/reallocate/{id}', 'CarInventoryController@reallocate');
    Route::any('/saveinventory', 'CarInventoryController@store');

    Route::resource('carrepair', 'CarRepairController');
    Route::any('carrepair/getinventory', 'CarRepairController@get_inventory');
    Route::any('carrepair/checkdaterange', 'CarRepairController@checkDateRange');
    Route::any('carrepair/getnumofbookinginspection', 'CarRepairController@getNumberOfBookingInspection');
    Route::any('carrepair/inspectionlist', 'CarRepairController@inspectionList');

    // Booking Management
    Route::any('booking/all', 'BookingManagementController@index');   // all view
//    Route::get('booking/today', 'BookingManagementController@today');   // all view
//    Route::get('booking/tomorrow', 'BookingManagementController@tomorrow');   // all view
    Route::get('booking/detail/{book_id}', 'BookingManagementController@detail');   //  view of booking detail
    Route::get('booking/edit/{book_id}', 'BookingManagementController@edit');   // view edit page of booking
    Route::post('booking/update', 'BookingManagementController@update');   // update of booking
    Route::post('booking/sendnotifi_cancel', 'BookingManagementController@sendnotifi_cancel');   // send notification for cancellation fee
    Route::get('booking/new/{user_id}', 'BookingManagementController@add');   // view new booking page
    Route::post('booking/create', 'BookingManagementController@create');   // create of new booking
    Route::any('booking/delete/{book_id}', 'BookingManagementController@delete');   // view new booking page
    Route::any('booking/cancel/{book_id}', 'BookingManagementController@cancel');   // view new booking page
    Route::any('booking/getoptionsfrommodel', 'BookingManagementController@getOptionsfromModel');
    Route::any('booking/getprice', 'BookingManagementController@getPrice');
    Route::any('bookingtask/changereturn', 'BookingManagementController@changereturn');
    Route::any('bookingtask/changedepart', 'BookingManagementController@changedepart');
    Route::any('bookingtask/updatepricestatus', 'BookingManagementController@updatepricestatus');
    Route::any('booking/getinsuranceprice', 'BookingManagementController@getInsurancePrice');
    Route::any('booking/search-plans', 'BookingManagementController@searchPlans');
    Route::any('booking/search-class', 'BookingManagementController@searchClasses');
    Route::any('booking/search-user', 'BookingManagementController@searchUser');
    Route::any('booking/getuserlist', 'BookingManagementController@getUserList');
    Route::any('booking/search-save', 'BookingManagementController@searchSave');
    Route::any('booking/getpaidprice', 'BookingManagementController@getpaidprice');
   // Route::any('booking/task', 'BookingManagementController@task');
    Route::any('booking/task', 'BookingManagementController@task1');
    Route::any('booking/savestatus', 'BookingManagementController@savestatus');
    Route::any('booking/changeoption', 'BookingManagementController@changeoption');
    Route::any('booking/changeRentInsuranceETC', 'BookingManagementController@changeRentInsuranceETC');
    Route::any('booking/completestatus', 'BookingManagementController@completeStatus');
    Route::any('booking/pdf', 'BookingManagementController@pdfview')->name('generate-pdf');
    Route::any('booking/savememo', 'BookingManagementController@savememo');
    Route::any('/booking/extendgetprice', 'BookingManagementController@extendgetprice');
    //Route::any('/booking/extendsaveprice', 'BookingManagementController@extendsaveprice');
    //Route::any('/booking/extendchangepay', 'BookingManagementController@extendchangepay');
    Route::any('/booking/saveAdditionalPrice', 'BookingManagementController@saveAdditionalPrice');
    Route::any('/booking/unpaidpay', 'BookingManagementController@unpaidpay');
    Route::any('/booking/upload-lic', 'BookingManagementController@uploadLicense');
    Route::any('/booking/delete-lic', 'BookingManagementController@deleteLicense');
    Route::any('booking/update-lic', 'BookingManagementController@updateLicense');

	Route::any('booking/printtask/{bookingid}', 'BookingManagementController@printtask');
	Route::any('booking/printalltask/{id}', 'BookingManagementController@printalltask');
	Route::any('booking/printalltaskempty/{id}', 'BookingManagementController@printalltaskempty');

    Route::any('sales/salesmanagement', 'SalesManagementController@sales');
    Route::any('sales/authmanagement', 'SalesManagementController@auth');

	
    //save booking data from googlesheet
    //Route::any('/google', 'GoogleSpreadController@saveData');// run php with command
    //Route::any('/googlesheet', 'GoogleSpreadController@getgooglesheet');//google sheet api version 3
    Route::any('/googlesheet', 'GoogleSpreadController@getgooglesheet_new'); // google sheet api version 4
    Route::any('/deletegoogleportal', 'GoogleSpreadController@deletegoogleportal'); // google sheet api version 4

	Route::resource('adminblog/posttags', 'PostTagsController');
	Route::resource('adminblog/blogtags', 'BlogTagsController');
	Route::resource('adminblog/blogposts','BlogPostsController');
	Route::resource('adminpage/webpages','Front\PageController');
    //mailtemplate
    Route::any('/mailtemplate', 'MailTemplateController@index');
    Route::any('/mailtemplate/{tempid}/edit', 'MailTemplateController@edit');
    Route::any('/mailtemplate/update', 'MailTemplateController@update');
    Route::any('/mailtemplate/new', 'MailTemplateController@create');
    Route::any('/mailtemplate/save', 'MailTemplateController@store');

    Route::get('/file/{filename}', 'FileController@getFile')->where('filename', '^[^/]+$');

});