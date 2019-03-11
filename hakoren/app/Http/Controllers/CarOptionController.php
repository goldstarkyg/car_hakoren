<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CarOption;
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
use App\Http\DataUtil\ServerPath;

use DB;

class CarOptionController extends Controller
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
        $options    = CarOption::all();
        $classes    = \DB::table('car_class')->get();
        $shops      = \DB::table('car_shop')->get();
        $data = [
            'options'   => $options,
            'route'     => $route,
            'subroute'  => $subroute,
            'shops'     => $shops,
            'classes'    => $classes
        ];
        return View('pages.admin.carbasic.caroption-index')->with($data);

    }

    /**
     * the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route = ServerPath::getRoute();
        $subroute = ServerPath::getSubRoute();
        $shops = \DB::table('car_shop')->get();
        $classes = \DB::table('car_class')->get();
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'shops'     => $shops,
            'classes'   => $classes
        ];

        return view('pages.admin.carbasic.caroption-create')->with($data);
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
                'abbriviation'     => 'required',
                'name'             => 'required'
            ],
            [
                'abbriviation.required'   => trans('carbasic.caroptionabbriviationrequired'),
                'name.required'    => trans('carbasic.caroptionrequired')
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
            DB::beginTransaction();

            $last_order = CarOption::orderBy('order','desc')->first();
            $order = is_null($last_order)? 1 : $last_order->order + 1;

                $caroption = CarOption::create([
                    'abbriviation'  => $request->input('abbriviation'),
                    'name'          => $request->input('name'),
                    'name_en'          => $request->input('name_en'),
                    'price'         => $request->input('price'),
                    'charge_system' => $request->input('charge_system'),
                    'max_number'    => $request->input('max_number'),
                    'google_column_number' => $request->input('google_column_number'),
                    'type' => $request->input('type'),
                    'order' => $order,
                ]);
                $caroption->save();
            if(!$caroption){
                DB::rollBack();
            }
            //save icon
            //save realted thumbnail
            $id = $caroption->id;
            $option_thumbnails = array();

            if($files = $request->file('file')) {
                for($i=0; $i < count($files); $i++) {
                    $fileName = $files[$i]->getClientOriginalName();
                    $extension = $files[$i]->getClientOriginalExtension();
                    $folderName = '/images/caroption/';
                    $destinationPath = public_path() . $folderName;
                    //$safeName = 'car_option_'.$id.'_'.$i.'.' . $extension;
                    $safeName = strtotime("now").rand(1,20).'.' . $extension;
                    $files[$i]->move($destinationPath, $safeName);
                    $thumb_path = $folderName.$safeName;
                    $thumb = array("option_id" => $id, "thumb_path" => $thumb_path);
                    array_push($option_thumbnails, $thumb);
                }
                $caroptionthumbnails = \DB::table('car_option_thumb')->insert($option_thumbnails);
                if(!$caroptionthumbnails) {
                    DB::rollBack();
                }
            }
            //save car shop and option
            $shop_ids    = $request->input('shop_id');
            $shop_counts = $request->input('shop_count');
            $car_options = array();
            for ($i = 0; $i < count($shop_ids); $i++) {
                $option_id  = $caroption->id;
                $shop_id    = $shop_ids[$i];
                $option_count=$shop_counts[$i];
                $price      = round($request->input('price') * $option_count, 2);
                $shop = array("option_id" => $option_id, "shop_id" => $shop_id, "option_count" => $option_count, "price"  => $price);
                if($option_count > 0) array_push($car_options, $shop);
            }
            $caroptionshopnsert = \DB::table('car_option_shop')->insert($car_options);
            if(!$caroptionshopnsert) {
                DB::rollBack();
            }
                //save car shop and models
                $classes = explode(",", $request->input('car_classes'));
                if($classes[0] != "") {
                    $car_classes = array();
                    for ($i = 0; $i < count($classes); $i++) {
                        $class = array("option_id" => $caroption->id, "class_id" => $classes[$i]);
                        array_push($car_classes, $class);
                    }
                    $caroptionclassinsert = \DB::table('car_option_class')->insert($car_classes);
                    if (!$caroptionclassinsert) {
                        DB::rollBack();
                    }
                }
            DB::commit();


            return redirect('/carbasic/caroption')->with('success', trans('carbasic.caroptoncreateSuccess'));
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

        $caroption  = CarOption::find($id);
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $shops      = \DB::table('car_shop')->get();
        $classes    = \DB::table('car_class')->get();
        $icons = \DB::table('car_option_thumb')->where('option_id',$id)->get();

        $data = [
            'caroption' => $caroption,
            'route'     => $route,
            'subroute'  => $subroute,
            'shops'     => $shops,
            'classes'   => $classes,
            'icons'     => $icons
        ];
        return view('pages.admin.carbasic.caroption-show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $route = ServerPath::getRoute();
        $subroute = ServerPath::getSubRoute();
        $caroption = CarOption::findOrFail($id);
        $shops = \DB::table('car_shop')->get();
        $classes = \DB::table('car_class')->get();
        $icons = \DB::table('car_option_thumb')->where('option_id',$id)->get();
        $data = [
            'caroption'     => $caroption,
            'route'         => $route,
            'subroute'      => $subroute,
            'shops'         => $shops,
            'classes'       => $classes,
            'thumbnails'    => $icons,
        ];
        return view('pages.admin.carbasic.caroption-edit')->with($data);
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

        DB::beginTransaction();
            $caroption              = CarOption::find($id);
            $caroption->abbriviation= $request->input('abbriviation');
            $caroption->name        = $request->input('name');
            $caroption->name_en     = $request->input('name_en');
            $caroption->price       = $request->input('price');
            $caroption->charge_system = $request->input('charge_system');
            $caroption->max_number  = $request->input('max_number');
            $caroption->google_column_number = $request->input('google_column_number');
            $caroption->type        = $request->input('type');
            $caroption->save();
        if(!$caroption){
            DB::rollBack();
        }
        //save icons
        $icon_thumbnails = array();

        if($files = $request->file('file')) {
            //delete file
            $thumbs = \DB::table('car_option_thumb')->where('option_id',$id)->get();
            for($i = 0; $i < count($thumbs); $i++) {
                if($thumbs[$i]->thumb_path != null) {
                    $thumb_path = public_path() . $thumbs[$i]->thumb_path;
                    if(file_exists($thumb_path))
                        unlink($thumb_path);
                }
            }
            $carorderdeletethumbnails = \DB::table('car_option_thumb')->where('option_id',$id)->delete();

            for($i=0; $i < count($files); $i++) {
                $fileName = $files[$i]->getClientOriginalName();
                $extension = $files[$i]->getClientOriginalExtension();
                $folderName = '/images/caroption/';
                $destinationPath = public_path() . $folderName;
                //$safeName = 'car_option_'.$id.'_'.$i.'.' . $extension;
                $safeName = strtotime("now").rand(1,20).'.' . $extension;
                $files[$i]->move($destinationPath, $safeName);
                $thumb_path = $folderName.$safeName;
                $thumb = array("option_id" => $id, "thumb_path" => $thumb_path);
                array_push($icon_thumbnails, $thumb);
            }
            $carorderthumbnails = \DB::table('car_option_thumb')->insert($icon_thumbnails);
            if(!$carorderthumbnails) DB::rollBack();
        }
        //update car shop and option
        $shop_ids    = $request->input('shop_id');
        $shop_counts = $request->input('shop_count');
        $car_options = array();
        for ($i = 0; $i < count($shop_ids); $i++) {
            $option_id  = $caroption->id;
            $shop_id    = $shop_ids[$i];
            $option_count=$shop_counts[$i];
            if(!empty($option_count)) {
                $price = round($request->input('price') * $option_count, 2);
                $shop = array("option_id" => $option_id, "shop_id" => $shop_id, "option_count" => $option_count, "price" => $price);
                if ($option_count > 0) array_push($car_options, $shop);
            }
        }
        
        if(count($car_options) > 0) {
            $carotionshopdelte = \DB::table('car_option_shop')
                ->where('option_id', $id)->delete();
            if (!$carotionshopdelte) {
                DB::rollBack();
            }

            $caroptionshopinsert = \DB::table('car_option_shop')->insert($car_options);
            if (!$caroptionshopinsert) {
                DB::rollBack();
            }
        }

        //update car model and option
        if(!empty($request->input('car_classes'))) {
            $classes = explode(",", $request->input('car_classes'));

            $car_classes = array();
            for ($i = 0; $i < count($classes); $i++) {
                $class = array("option_id" => $id, "class_id" => $classes[$i]);
                array_push($car_classes, $class);
            }
            $carotionclassdelete = \DB::table('car_option_class')
                ->where('option_id', $id)->delete();
            if (!$carotionclassdelete) {
                DB::rollBack();
            }

            $caroptionclassinsert = \DB::table('car_option_class')->insert($car_classes);
            if (!$caroptionclassinsert) {
                DB::rollBack();
            }
        }else{
            $carotionclassdelete = \DB::table('car_option_class')
                ->where('option_id', $id)->delete();
            if (!$carotionclassdelete) {
                DB::rollBack();
            }
        }

        DB::commit();

        return back()->with('success', trans('carbasic.updateCarOption'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $caroption        = CarOption::findOrFail($id);
        \DB::transaction(function() use($caroption,$id) {
            //delete related thumbnails
            $thumbs = \DB::table('car_option_thumb')->where('option_id',$id)->get();
            for($i = 0; $i < count($thumbs); $i++) {
                if($thumbs[$i]->thumb_path != null) {
                    $thumb_path = public_path() . $thumbs[$i]->thumb_path;
                    if(file_exists($thumb_path))
                        unlink($thumb_path);
                }
            }
            $carorderhumbnails = \DB::table('car_option_thumb')->where('option_id', $id)->delete();

            $caroption->delete();
            $caroptionmodel = \DB::table('car_option_class')->where('option_id', $id)->delete();
            $caroptionshopl = \DB::table('car_option_shop')->where('option_id', $id)->delete();
        });

        return redirect('/carbasic/caroption/')->with('success', trans('carbasic.deleteCarOption'));


    }

    // get car shop name
    public function getCarShopName($id) {
        $shop     = \DB::table('car_shop')->where('id',$id)->first();
        if($shop)
            return $shop->name;
        else
            return '';
    }

    //get car model name
    public function getCarClassName($id) {
        $class     = \DB::table('car_class')->where('id',$id)->first();
        if($class)
            return $class->name;
        else
            return '';
    }

    //get price like option id and shop from car_option_shop table
    public function getCarOptionCount($option_id, $shop_id) {
        $shop = \DB::table('car_option_shop')
                            ->where('option_id',$option_id)
                            ->where('shop_id',$shop_id)
                            ->first();
        if($shop)
            return $shop->option_count;
        else
            return '';
    }
}
