<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Response;
use App\Models\CarModel;
use App\Models\Shop;
use App\Models\CarVendor;
use App\Models\Score;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\Route;

use DB;
use App\Http\DataUtil\ServerPath;

class ShopController extends Controller
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
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $shops     = Shop::all();
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'shops'    => $shops
        ];

        return View('pages.admin.shopbasic.shop-index')->with($data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $shops      = Shop::all();
        $members    =  DB::table('users as u')
                        ->join('role_user as ru', 'ru.user_id', '=', 'u.id')
                        ->join('roles as ro', 'ro.id', '=', 'ru.role_id')
                        ->select(['u.*'])
                        ->where('ro.level','4')->get(); //sub admin
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'shops'     => $shops,
            'members'   => $members
        ];

        return view('pages.admin.shopbasic.shop-create')->with($data);
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
                'name'             => 'required'
            ],
            [
                'name.required'    => trans('shopbasic.shoprequired')
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
            $input = Input::except('_token','thumb_path');
            $shop = Shop::create($input);
            $shop->save();
            $id = $shop->id;
            /////////////////
            if($thumbfile = $request->file('thumb_path')) {
                $fileName = $thumbfile->getClientOriginalName();
                $extension = $thumbfile->getClientOriginalExtension();
                $folderName = '/images/shop/';
                $destinationPath = public_path() . $folderName;
                $safeName = 'shop_'.$id.'.' . $extension;
                $thumbfile->move($destinationPath, $safeName);
                $thumb_path = $folderName.$safeName;
                $shopupdate = DB::table('car_shop')
                    ->where('id', $id)
                    ->update(['thumb_path' => $thumb_path]);
            }
            if($thumbfile_en = $request->file('thumb_path_en')) {
                $fileName = $thumbfile_en->getClientOriginalName();
                $extension = $thumbfile_en->getClientOriginalExtension();
                $folderName = '/images/shop/';
                $destinationPath = public_path() . $folderName;
                $safeName = 'shop_'.$id.'_en.' . $extension;
                $thumbfile_en->move($destinationPath, $safeName);
                $thumb_path_en = $folderName.$safeName;
                $shopupdate = DB::table('car_shop')
                    ->where('id', $id)
                    ->update(['thumb_path_en' => $thumb_path_en]);
            }
            return redirect('/shopbasic/shop')->with('success', trans('shopbasic.shopcreateSuccess'));
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        $shop = Shop::find($id);
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $shops      = Shop::all();
        $members    =  DB::table('users as u')
            ->join('role_user as ru', 'ru.user_id', '=', 'u.id')
            ->join('roles as ro', 'ro.id', '=', 'ru.role_id')
            ->select(['u.*'])
            ->where('ro.level','4')->get(); //sub admin+

        return view('pages.admin.shopbasic.shop-show', compact('shop', 'route', 'subroute','shops','members'))->withUser($shop);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $shop   = Shop::findOrFail($id);
        $shops      = Shop::all();
        $members    =  DB::table('users as u')
            ->join('role_user as ru', 'ru.user_id', '=', 'u.id')
            ->join('roles as ro', 'ro.id', '=', 'ru.role_id')
            ->select(['u.*'])
            ->where('ro.level','4')->get(); //sub admin+

        //business hour1
        $hours    =  DB::table('shop_working_times')
                            ->where('shop_id',$id)->first();

        if(empty($hours) ) {
            $hours['id'] = 0;
            $hours['shop_id'] = $id;
            $hours['monday_from'] = '00:00';
            $hours['monday_to'] = '00:00';
            $hours['monday_dayoff'] = '1';
            $hours['tuesday_from'] = '00:00';
            $hours['tuesday_to'] = '00:00';
            $hours['tuesday_dayoff'] = '1';
            $hours['wednesday_from'] = '00:00';
            $hours['wednesday_to'] = '00:00';
            $hours['wednesday_dayoff'] = '1';
            $hours['thursday_from'] = '00:00';
            $hours['thursday_to'] = '00:00';
            $hours['thursday_dayoff'] = '1';
            $hours['friday_from'] = '00:00';
            $hours['friday_to'] = '00:00';
            $hours['friday_dayoff'] = '1';
            $hours['saturday_from'] = '00:00';
            $hours['saturday_to'] = '00:00';
            $hours['saturday_dayoff'] = '1';
            $hours['sunday_from'] = '00:00';
            $hours['sunday_to'] = '00:00';
            $hours['sunday_dayoff'] = '1';
        }

        $time_1 = array();
        foreach($hours as  $key => $value ) {
            if (strpos($key, '_from') !== false || strpos($key, '_to') !== false ) {
                    $h = date("g:i A", strtotime($value)); // convert from 24 hours to AM/PM
                    $time_1[$key] = $h;
            }else {
                $time_1[$key] = $value;
            }
        }

        //business hour 2
        $time_2    =  DB::table('shop_working_times_spec')
            ->where('shop_id',$id)->get();
        /*
        if(empty($hours_2)) {
            $hours_2['id'] = 0;
            $hours_2['shop_id'] = $id;
            $hours_2['date'] = date("Y-m-d");
            $hours_2['start_time'] = '00:00';
            $hours_2['end_time']   = '00:00';
            $hours_2['is_dayoff']  = '1';
        }

        $time_2 = array();
        foreach($hours_2 as $key => $value ) {
            if (strpos($key, '_time') !== false ) {
                $h = date("g:i A", strtotime($value)); // convert from 24 hours to AM/PM
                $time_2[$key] = $h;
            }else {
                $time_2[$key] = $value;
            }
        }*/

        // pickup
        $pickup =  DB::table('car_pickup')->where('shop_id',$id)->first();
        if(is_null($pickup)){
            // insert
            DB::table('car_pickup')->insert(['shop_id'=>$id]);
            $pickup = (Object) array('shop_id' => $id, 'thumb_path' => '', 'title1' => '', 'content1' => '', 'title2' => '', 'content2' => '','content1_en'=>'');
        }

        $data = [
            'shop'          => $shop,
            'route'         => $route,
            'subroute'      => $subroute,
            'shops'         => $shops,
            'members'       => $members,
            'time_1'        => (object)$time_1,
            'time_2'        => $time_2,
            'pickup'        => $pickup
        ];

        return view('pages.admin.shopbasic.shop-edit')->with($data);
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
        $input = Input::except('_token','thumb_path','_method');
//		dd($input);
        if($thumbfile = $request->file('thumb_path')) {
            $fileName = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/images/shop/';
            $destinationPath = public_path() . $folderName;
            $safeName = 'shop_'.$id.'.' . $extension;
            $thumbfile->move($destinationPath, $safeName);
            $thumb_path = $folderName.$safeName;
            $input['thumb_path'] = $thumb_path;
        }
        if($thumbfile_en = $request->file('thumb_path_en')) {
            $fileName = $thumbfile_en->getClientOriginalName();
            $extension = $thumbfile_en->getClientOriginalExtension();
            $folderName = '/images/shop/';
            $destinationPath = public_path() . $folderName;
            $safeName = 'shop_'.$id.'_en.' . $extension;
            $thumbfile_en->move($destinationPath, $safeName);
            $thumb_path_en = $folderName.$safeName;
            $input['thumb_path_en'] = $thumb_path_en;
        }
        $carmodel = Shop::where('id',$id)->update($input);

        return back()->with('success', trans('shopbasic.updateshop'));//->with('tag','address');
    }
	
	

    /**
     * updatebusiness the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatebusiness(Request $request)
    {
        $input      = Input::except('_token');
        $del_method = Input::get('method');
        $shop_id    = $input['shop_id'];

        //delete business hour 1
        if($del_method == 'DELETE') {
            $delehour = \DB::table('shop_working_times')->where('shop_id', $shop_id)->delete();
            if($delehour)
                return back()->with('success', trans('shopbasic.deletebusinesshour1'));
            else
                return back()->with('error', trans('shopbasic.delhour1error'));
        }

        //make day_off to 0 if there is no day_off from request form.
        $days_off = array('monday_dayoff','tuesday_dayoff','wednesday_dayoff','thursday_dayoff','friday_dayoff','saturday_dayoff','sunday_dayoff');
        foreach($days_off as $day) {
            if(empty($input[$day])) $input[$day] = 0;
        }

        //change from AM/PM  to 24 hours
        foreach($input as  $key => $value ) {
            if (strpos($key, '_from') !== false || strpos($key, '_to') !== false ) {
                if($input[$key])
                    $input[$key] = date("H:i", strtotime($input[$key])); // convert from AM/PM to 24 hours
            }
        }

        //select shop
        $hour_1 = \DB::table('shop_working_times')->where('shop_id', $shop_id)->first();

        // update and insert
        if(!empty($hour_1)) {
            $update = \DB::table('shop_working_times')->where('shop_id', $shop_id)->update($input);
//            if(!$update)
//                return back()->with('error', trans('shopbasic.errorbusinesshour'));
        }else{
            $create = \DB::table('shop_working_times')->insert($input);
            if(!$create)
                return back()->with('error', trans('shopbasic.errorbusinesshour'));
        }

        return back()->with('success', trans('shopbasic.updateshop'))->with('tag','hour1');
    }


    /**
     * updatebusinesscustom the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatebusinesscustom(Request $request)
    {

        $input      = Input::except('_token','method');
        $method = Input::get('method');
        $shop_id    = $input['shop_id'];
        $id         = $input['id'];
        if(!$request->get('is_dayoff')) $input['is_dayoff'] = 0;

        $input['start_time'] = date("H:i", strtotime($input['start_time']));
        $input['end_time'] = date("H:i", strtotime($input['end_time']));

        $getdata = \DB::table('shop_working_times_spec')->where('id', $id)->get();

        if(count($getdata) != 0) {
            $updatehour = \DB::table('shop_working_times_spec')->where('id', $id)->update($input);
        }else {
            $createhour = \DB::table('shop_working_times_spec')->insert($input);
        }

        //select business hour2
        $hour_2 = \DB::table('shop_working_times_spec')->where('shop_id', $shop_id)->get();

        return back()->with('success', trans('shopbasic.updatebusinesshour2'))->with('tag','hour2');
        //return Response::json($hour_2);
    }

    //delete business custom time
    public function deletebusinesscustom(Request $request,$id)
    {
        $input      = Input::except('_token','method');
        $deletehour = \DB::table('shop_working_times_spec')->where('id', $id)->delete();
        return back()->with('success', trans('shopbasic.deletebusinesshour2'))->with('tag','hour2');

    }

    //edit business hour2
    public function editbusinesscustom(Request $request,$id)
    {
        //select business hour2
        $hour_2 = \DB::table('shop_working_times_spec')->where('id', $id)->first();
        $hour_2->start_time = date("g:i A", strtotime($hour_2->start_time));
        $hour_2->end_time = date("g:i A", strtotime($hour_2->end_time));
        return back()->with('tag','hour2')
                     ->with('customhour', $hour_2);
    }

	public function updatePickup(Request $request, $id)
    {
        $input = Input::except('_token','_method');

        if($thumbfile = $request->file('thumb_path')) {
            $fileName = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/images/shop_pickup/';
            $destinationPath = public_path() . $folderName;
            $safeName = 'shoppickup_'.$id.'.' . $extension;
            $thumbfile->move($destinationPath, $safeName);
            $thumb_path = $folderName.$safeName;
            $input['thumb_path'] = $thumb_path;
        }

        $pickup = \DB::table('car_pickup')->where('shop_id',$id)->first();
        if(is_null($pickup)){
            $input['shop_id'] = $id;
            \DB::table('car_pickup')->insert($input);
        } else {
            \DB::table('car_pickup')->where('shop_id',$id)->update($input);
        }

        return back()->with('success', 'Pickup saved');//->with('tag','address');
    }

	public function deletePickup(Request $request, $id)
    {
        \DB::table('car_pickup')->where('shop_id',$id)->delete();

        return back()->with('success', 'Pickup deleted');//->with('tag','address');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $shop       = Shop::findOrFail($id);
        $thumb_path     = $shop->thumb_path;
        //delete featured thumbnail
        $shop->delete();
        if($thumb_path != null) {
            $path = public_path() . $thumb_path;
            unlink($path);
        }
        return redirect('/shopbasic/shop/')->with('success', trans('shopbasic.deleteShop'));


    }
    public function updatecomment(Request $request, $id)
    {
        $input      = Input::except('_token','method');
        $method     = Input::get('method');
        $id         = $id;
        if(!empty($input['comment']))
        {
            DB::table('car_shop')
                ->where('id', $id)
                ->update(['comment' => $input['comment'],'comment_en' => $input['comment_en']]);
        }

        return redirect('/shopbasic/shop/')->with('success', trans('shopbasic.updatebusinesshour2'));
    }
}
