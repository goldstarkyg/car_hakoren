<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CartypeCategory;
use Illuminate\Http\Request;
use App\Models\CarClassEquipment;
use App\Models\CarEquip;
use App\Models\CarModel;
use App\Models\CarOption;
use App\Models\CarInsurance;
use App\Models\CarPassengerTags;
use App\Models\CarType;
use App\Models\CarClass;
use App\Models\CarVendor;
use App\Models\Score;
use App\Models\Shop;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\Route;

use DB;
use App\Http\DataUtil\ServerPath;
use Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use View;

class CarClassController extends Controller
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
        $shops      = Shop::all();

        if($request->has('car_shop')){
            $shop_id = $request->get('car_shop');
        } else {
            $admin      = Auth::user();
            $related_shop = \DB::table('admin_shop')->where('admin_id', $admin->id)->first();
            if(is_null($related_shop)) {
                $related_shop = Shop::orderBy('id')->first();
            }
            $shop_id = $related_shop->shop_id;
        }

        $classes    = CarClass::where('car_shop_name', $shop_id)
                        ->orderBy('car_class_priority')->get();

        foreach ($classes as $key => $cls) {
            $carClassModel = \DB::table('car_class_model')
                ->where('class_id', $cls->id)
                ->orderBy('priority')->get();
            $classes[$key]->carClassModel = $carClassModel;
        }

        $car_category = \DB::table('car_type_category')->get();
        $data = [
            'route'         => $route,
            'subroute'      => $subroute,
            'classes'       => $classes,
            'shops'         => $shops,
            'car_shop'      => $shop_id,
            'car_type_category' => $car_category,
        ];

        return View('pages.admin.carbasic.carclass-index')->with($data);

    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        //$models      = CarModel::where('selected_by_class','0')->get(); changed logic from this
        $models      = CarModel::all();
        $passengertags      = CarPassengerTags::all();
		$shop_list   = \DB::table('car_shop')->select('id','name','slug')->get();
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'models'    => $models,
            'psgtags'   => $passengertags,
			'car_shop_list' => $shop_list
        ];

        return view('pages.admin.carbasic.carclass-create')->with($data);
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
                'name'             => 'required',
				'car_shop_name'	   => 'required'
            ],
            [
                'name.required'             => trans('carbasic.carclassrequired'),
				'car_shop_name.required'    => trans('carbasic.carshoprequired')
            ]
        );

		/*
        $carclassname = $request->get('car_shop_name');
        $carclass = \DB::table('car_class')->insert(array('car_shop_name' => $carclassname));
		*/


        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
            DB::beginTransaction();
                $input = Input::except('_token','thumb_path','car_models', 'car_psgtags');
                $carclass = CarClass::create($input);
//                $carclass->save();
                if(!$carclass) {
                    DB::rollBack();
                }
                $id = $carclass->id;
                if($thumbfile = $request->file('thumb_path')) {
                    $fileName = $thumbfile->getClientOriginalName();
                    $extension = $thumbfile->getClientOriginalExtension();
                    $folderName = '/images/carclass/';
                    $destinationPath = public_path() . $folderName;
                    $safeName = strtotime("now").rand(1,20).'.' . $extension;
                    $thumbfile->move($destinationPath, $safeName);
                    $thumb_path = $folderName.$safeName;
                    $carupdate = DB::table('car_class')
                        ->where('id', $id)
                        ->update(['thumb_path' => $thumb_path]);
                    if(!$carupdate){
                        DB::rollBack();
                    }
                }
                //save realted thumbnail
                $class_thumbnails = array();
                if($files = $request->file('file')) {
                    for($i=0; $i < count($files); $i++) {
                        $fileName = $files[$i]->getClientOriginalName();
                        $extension = $files[$i]->getClientOriginalExtension();
                        $folderName = '/images/carclass/';
                        $destinationPath = public_path() . $folderName;
                        $safeName = strtotime("now").rand(1,20).'.' . $extension;
                        $files[$i]->move($destinationPath, $safeName);
                        $thumb_path = $folderName.$safeName;
                        $thumb = array("class_id" => $id, "thumb_path" => $thumb_path);
                        array_push($class_thumbnails, $thumb);
                    }
                    $carclassthumbnails = \DB::table('car_class_thumb')->insert($class_thumbnails);
                    if(!$carclassthumbnails) {
                        DB::rollBack();
                    }
                }
                //save models
                $models = explode(",", $request->input('car_models'));

                if(!empty($models)) {
                    $car_classes = array();
                    for ($i = 0; $i < count($models); $i++) {
                        if($models[$i] != "") {
                            $model = array("class_id" => $carclass->id, "model_id" => $models[$i]);
                            /*$carmodel = CarModel::where('id', $models[$i])->update(['selected_by_class' => 1]);
                            if (!$carmodel) {
                                DB::rollBack();
                            }*/
                            array_push($car_classes, $model);
                        }
                    }
                    $carclassmodelnsert = \DB::table('car_class_model')->insert($car_classes);
                    if (!$carclassmodelnsert) {
                        DB::rollBack();
                    }
                }
                //save passenger tags
                $tags = explode(",", $request->input('car_psgtags'));

                if(!empty($tags)) {
                    $class_tags = array();
                    for ($i = 0; $i < count($tags); $i++) {
                        if($tags[$i] != "") {
                            $class_tag = array("class_id" => $carclass->id, "passenger_tag" => $tags[$i]);
                            array_push($class_tags, $class_tag);
                        }
                    }
                    $classTagInsert = \DB::table('car_class_passenger')->insert($class_tags);
                    if (!$classTagInsert) {
                        DB::rollBack();
                    }
                }

           DB::commit();

            return redirect('/carbasic/carclass')->with('success', trans('carbasic.carclasscreateSuccess'));
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
        $carclass = CarClass::find($id);
        $thumbnails = \DB::table('car_class_thumb')->where('class_id',$id)->get();
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $models = \DB::table('car_model')->get();
        $suggests =  \DB::table('car_class_suggests as s')
            ->leftJoin('car_class as c', 's.suggest_class_id', '=', 'c.id')
            ->where('s.class_id', $id)
            ->select('c.*')->get();

        return view('pages.admin.carbasic.carclass-show', compact('carclass', 'route', 'subroute','models','thumbnails','suggests'))->withUser($carclass);
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
        $carclass   = CarClass::findOrFail($id);
        $models     = CarModel::all();
        $options    = CarOption::all();
        $inses      = CarInsurance::all();
        $equipments = CarEquip::all();
        $passengertags  = CarPassengerTags::all();
        $normal     =  DB::table('car_class_price')->where('class_id',$id)->first();
        $thumbnails = \DB::table('car_class_thumb')->where('class_id',$id)->get();
        $classes = CarClass::all();

        foreach ($classes as $key=>$cls) {
            if($cls->id == $id) unset($classes[$key]);
        }

        $suggest_classes = DB::table('car_class_suggests')->where('class_id',$id)->get();

        $insurances = array();
        foreach($inses as $in){
            $price = 0;
            $obj = clone $in;
            $carins     = DB::table('car_class_insurance')
                ->where('class_id',$id)->where('insurance_id',$in->id)->first();
            if(!empty($carins)){
                $price = $carins->price;
            }else{
                $price = 0;
            }
            $obj->price = $price;
            array_push($insurances,$obj);
        }
        if(empty($normal) ) {
            $normal['id'] = 0;
            $normal['class_id']     = $id;
            $normal['d1_day1']      = '0';
            $normal['d1_total']     = '0';
            $normal['n1d2_day1']    = '0';
            $normal['n1d2_day2']    = '0';
            $normal['n1d2_total']   = '0';
            $normal['n2d3_day1']    = '0';
            $normal['n2d3_day2']    = '0';
            $normal['n2d3_day3']    = '0';
            $normal['n2d3_total']   = '0';
            $normal['n3d4_day1']    = '0';
            $normal['n3d4_day2']    = '0';
            $normal['n3d4_day3']    = '0';
            $normal['n3d4_day4']    = '0';
            $normal['n3d4_total']   = '0';
            $normal['additional_day']   = '0';
            $normal['additional_total'] = '0';
        }

        $customs    =  DB::table('car_class_price_custom')
            ->where('class_id',$id)->get();

        $pOrders = \DB::table('car_class_model as c')
            ->leftJoin('car_model as m', 'c.model_id','=','m.id')
            ->where('c.class_id', $id)
            ->select('c.class_id', 'c.model_id', 'm.name', 'c.priority')
            ->orderBy('c.priority')
            ->get();

        $shop_list = \DB::table('car_shop')->select('id','name','slug')->get();
    
       
        $data = [
            'carclass'              => $carclass,
            'thumbnails'            => $thumbnails,
            'route'                 => $route,
            'subroute'              => $subroute,
            'models'                => $models,
            'normal'                => (object)$normal,
            'customs'               => (object)$customs,
            'options'               => $options,
            'insurances'            => $insurances,
            'equipments'            => $equipments,
            'priorities'            => $pOrders,
            'psgtags'               => $passengertags,
            'classes'               => $classes,
            'suggests'              => $suggest_classes,
            'car_shop_list'         => $shop_list,
       ];

        return view('pages.admin.carbasic.carclass-edit')->with($data);
    }

	/**	
	* Function will be called by AJAX request
	*/
	public function fetchCarClassCustomData(Request $request){
		 
		 if($request->ajax()){
			
			$perpage	= 20;			
        	$customs    =  DB::table('car_class_price_custom')
            				 ->where('class_id',$request->get('car_class_id'))->get();			
			 
			//Get current page form url e.g. &page=6
			$currentPage = LengthAwarePaginator::resolveCurrentPage();

			//Create a new Laravel collection from the array data
			$collection  = new Collection($customs);
			
			//Slice the collection to get the items to display in current page
			$currentPageResults = $collection->slice(($currentPage-1) * $perpage, $perpage)->all();			
			
			//Create our paginator and pass it to the view
			$customs   = new LengthAwarePaginator($currentPageResults, count($collection), $perpage);			
					 			 
			return Response::json(View::make('pages.admin.carbasic.carclass-custom-data', ['customs'=> $customs])->render());		 
			 
		 }
			
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
        $input = Input::except('_token','thumb_path','thumb_path_en','_method','car_models','car_model', 'suggest_list', 'car_suggest_classes', 'car_psgtags', 'car_psgtag','file' ,'deletethumbs');

        DB::beginTransaction();

        //  Add car class
    

        $carclassname = $request->get('car_shop_name');
        $carclass = \DB::table('car_class')->where('id',$id)->update(array('car_shop_name' => $carclassname));


        if($thumbfile = $request->file('thumb_path')) {
            $fileName = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/images/carclass/';
            $destinationPath = public_path() . $folderName;
            //$safeName = 'car_class_'.$id.'.' . $extension;
            $safeName = strtotime("now").rand(1,20).'.' . $extension;
            if(\File::exists($destinationPath.$safeName)) \File::delete($destinationPath.$safeName);
            $thumbfile->move($destinationPath, $safeName);
            $thumb_path = $folderName.$safeName;
            $input['thumb_path'] = $thumb_path;
        }
        if($thumbfile_en = $request->file('thumb_path_en')) {
            $fileName = $thumbfile_en->getClientOriginalName();
            $extension_en = $thumbfile_en->getClientOriginalExtension();
            $folderName_en = '/images/carclass/';
            $destinationPath_en = public_path() . $folderName_en;
            //$safeName = 'car_class_'.$id.'.' . $extension;
            $safeName_en = strtotime("now").rand(1,20).'.' . $extension_en;
            if(\File::exists($destinationPath_en.$safeName_en)) \File::delete($destinationPath_en.$safeName_en);
            $thumbfile_en->move($destinationPath_en, $safeName_en);
            $thumb_path_en = $folderName_en.$safeName_en;
            $input['thumb_path_en'] = $thumb_path_en;
        }

        $carclass = CarClass::where('id',$id)->update($input);
        if (!$carclass)
        {
            DB::rollBack();
        }

        $class_thumbnails = array();
        //delete from deletethumbs
        if($ids = $request->get('deletethumbs')) {
            $thumbids = explode(',',$ids);
            for($i =0; $i < count($thumbids) ; $i++) {
                $deletethumb = \DB::table('car_class_thumb')->where('id', $thumbids[$i])->delete();
                if(!$deletethumb) DB::rollBack();
            }
        }
        if($files = $request->file('file')) {
            //delete file
//            $thumbs = \DB::table('car_class_thumb')->where('class_id',$id)->get();
//            for($i = 0; $i < count($thumbs); $i++) {
//                if($thumbs[$i]->thumb_path != null) {
//                    $thumb_path = public_path() . $thumbs[$i]->thumb_path;
//                    if(file_exists($thumb_path))
//                        unlink($thumb_path);
//                }
//            }
//            $carclassdeletethumbnails = \DB::table('car_class_thumb')->where('class_id',$id)->delete();

            for($i=0; $i < count($files); $i++) {
                $fileName = $files[$i]->getClientOriginalName();
                $extension = $files[$i]->getClientOriginalExtension();
                $folderName = '/images/carclass/';
                $destinationPath = public_path() . $folderName;
                //$safeName = 'car_class_'.$id.'_'.$i.'.' . $extension;
                $safeName = strtotime("now").rand(1,20).'.' . $extension;
                $files[$i]->move($destinationPath, $safeName);
                $thumb_path = $folderName.$safeName;
                $thumb = array("class_id" => $id, "thumb_path" => $thumb_path);
                array_push($class_thumbnails, $thumb);
            }

            $carclassthumbnails = \DB::table('car_class_thumb')->insert($class_thumbnails);
            if(!$carclassthumbnails) DB::rollBack();
        }

        //update car model and option
        $models = explode(",", $request->input('car_models'));

        $car_models = array();
        for ($i = 0; $i < count($models); $i++) {
            $model = array("class_id" => $id, "model_id" => $models[$i]);
            if($models[$i] != "") {
                array_push($car_models, $model);
            }
        }

        //update selected_by_class status of models with default value(0).
        //This mean that not be selected by class.
        $caroriginalmodel   = \DB::table('car_class_model')
            ->where('class_id', $id)->get();

        foreach($caroriginalmodel as $model) {
            $firstmodel = CarModel::where('id',$model->model_id)->first();
            if($firstmodel) {
                $carmodel = CarModel::where('id', $model->model_id)->update(['selected_by_class' => 0]);
                if (!$carmodel) {
                    DB::rollBack();
                }
            }
        }

        $carotionmodeldelete   = \DB::table('car_class_model')
            ->where('class_id', $id)->delete();

        if (!$carotionmodeldelete)
        {
            DB::rollBack();
        }

        if(count($car_models) > 0) {
            $carclassmodelnsert = \DB::table('car_class_model')->insert($car_models);
            if (!$carclassmodelnsert)
            {
                DB::rollBack();
            }

            //update selected_by_class in car model table.
            for ($i = 0; $i < count($car_models); $i++) {
                $firstmodel = CarModel::where('id',$car_models[$i]['model_id'])->first();
                if($firstmodel) {
                    $carmodelup = CarModel::where('id', $car_models[$i]['model_id'])->update(['selected_by_class' => 1]);
                    if (!$carmodelup) {
                        DB::rollBack();
                    }
                }
            }
        }

        // update suggest classes of class
        $suggests = explode(',', $request->input('suggest_list'));
        $class_suggests = array();
        for ($i = 0; $i < count($suggests); $i++) {
            $suggest = array("class_id" => $id, "suggest_class_id" => $suggests[$i]);
            if($suggests[$i] != "") {
                array_push($class_suggests, $suggest);
            }
        }
        if(count($suggests) > 0) {
            \DB::table('car_class_suggests')->where('class_id', $id)->delete();

            $delete = \DB::table('car_class_suggests')->where('class_id', $id)->delete();
            $insert = \DB::table('car_class_suggests')->insert($class_suggests);
            if (!$insert)
            {
                DB::rollBack();
            }
        }

        // update class passenger tags
        $newtags = explode(",", $request->input('car_psgtags'));

        $psg_tags = array();
        for ($i = 0; $i < count($newtags); $i++) {
            $tag = array("class_id" => $id, "passenger_tag" => $newtags[$i]);
            if($newtags[$i] != "") {
                array_push($psg_tags, $tag);
            }
        }

        // delete old tags
        \DB::table('car_class_passenger')->where('class_id', $id)->delete();

        // insert new tags
        if(count($psg_tags) > 0) {
            if (!\DB::table('car_class_passenger')->insert($psg_tags))
            {
                DB::rollBack();
            }
        }

        DB::commit();
        return redirect('carbasic/carclass/'.$id)->with('success', trans('carbasic.updateCarclass'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $CarClass       = CarClass::findOrFail($id);
        $thumb_path     = $CarClass->thumb_path;
        \DB::transaction(function() use($CarClass, $thumb_path, $id) {
            $CarClass->delete();
            if ($thumb_path != null) {
                $path = public_path() . $thumb_path;
                unlink($path);
            }
            //delete related thumbnails
            $thumbs = \DB::table('car_class_thumb')->where('class_id',$id)->get();
            for($i = 0; $i < count($thumbs); $i++) {
                if($thumbs[$i]->thumb_path != null) {
                    $thumb_path = public_path() . $thumbs[$i]->thumb_path;
                    unlink($thumb_path);
                }
            }
            $carclassthumbnails = \DB::table('car_class_thumb')->where('class_id', $id)->delete();

            //change model selected_by_class value to default
            $caroriginalmodel   = \DB::table('car_class_model')
                ->where('class_id', $id)->get();
            foreach($caroriginalmodel as $model) {
                $carmodel = CarModel::where('id',$model->model_id)->update(['selected_by_class' => 0]);
            }

            $carclassmodel = \DB::table('car_class_model')->where('class_id', $id)->delete();
        });

        return redirect('/carbasic/carclass/')->with('success', trans('carbasic.deleteCarClass'));

    }

    //get car model name
    public function getCarModelName($id) {
        $model     = \DB::table('car_model')->where('id',$id)->first();
        if($model)
            return $model->name;
        else
            return '';
    }

    //get car model object
    public function getCarModelInform($model_id) {
        /* car model name
        - car model thumbnail
        - car type
        - car vendor
        - car number (active: 0 cars / inactive 0 cars)
        - relating shops
        eg: Shop A ( active 10 cars / inactive 2 cars )  from car inventory
        */
        $model     = \DB::table('car_model as cm')
                        ->join('car_type as ct', 'ct.id', '=', 'cm.type_id')
                        ->join('car_vendor as cv', 'cv.id', '=', 'cm.vendor_id')
                        ->select(['cm.*' ,'ct.name as type_name','cv.name as vendor_name'])
                        ->where('cm.id' , $model_id)
                        ->first();

        $shops  = Shop::all();
        $car    = array();
        foreach($shops as $shop) {
            $carinvne_active = \DB::table('car_inventory as ci')
                            ->where('shop_id', $shop->id)
                            ->where('status', '1')
                            ->count();
            $carinvne_inactive = \DB::table('car_inventory as ci')
                ->where('shop_id', $shop->id)
                ->where('status', '0')
                ->count();
            $car[] = $shop->name.' ( active '. $carinvne_active.' cars / inactive '.$carinvne_inactive.' cars )';
        }
        if(!empty($model))
            $model->cars = $car;
        return $model;

    }

    //getcartype from parent category
    public function getcartypemodel(Request $request) {

        $category_id = $request->get('category_id');
        $type = \DB::table('car_type')->where('category_id', $category_id)->get();
        $model = \DB::table('car_model')->where('category_id', $category_id)->get();
        $ret = array();
        $ret['type']    = $type;
        $ret['model']   = $model;
        return Response::json($ret);
    }
    //getcartype from parent category in this class method
    public function getcartypemodelfromthis($category_id) {

        $type = \DB::table('car_type')->where('category_id', $category_id)->get();
        $model = \DB::table('car_model')->where('category_id', $category_id)->get();
        $ret = array();
        $ret['type']    = $type;
        $ret['model']   = $model;
        return $ret;
    }

    /**
     * updatenormal
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatenormal(Request $request)
    {

        $input      = Input::except('_token');
        $del_method = Input::get('method');
        $class_id    = $input['class_id'];

        //delete normal price hour 1
        if($del_method == 'DELETE') {
            $delenormal = \DB::table('car_class_price')->where('class_id', $class_id)->delete();
            if($delenormal)
                return back()->with('success', trans('carbasic.deletenormal'));
            else
                return back()->with('error', trans('carbasic.delnormalerror'));
        }

        //select normal price
        $normal = \DB::table('car_class_price')->where('class_id', $class_id)->first();

        // update and insert
        if(!empty($normal)) {
            $update = \DB::table('car_class_price')->where('class_id', $class_id)->update($input);
        }else{
            $create = \DB::table('car_class_price')->insert($input);
            if(!$create)
                return back()->with('error', trans('carbasic.errornormalprice'));
        }
        return back()->with('success', trans('carbasic.updateCarclass'))->with('tag','normal');
    }

    /**
     * updatecustom
     * @return \Illuminate\Http\Response
     */
    public function updatecustom(Request $request)
    {
        $input      = Input::except('_token','method');
        $method = Input::get('method');
        $class_id    = $input['class_id'];
        $id         = $input['id'];
        $input['d1_day1'] = str_replace(',', '' , $input['d1_day1']);
        $input['d1_total'] = str_replace(',', '' , $input['d1_total']);
        $input['n1d2_day1'] = str_replace(',', '' , $input['n1d2_day1']);
        $input['n1d2_day2'] = str_replace(',', '' , $input['n1d2_day2']);
        $input['n1d2_total'] = str_replace(',', '' , $input['n1d2_total']);
        $input['n2d3_day1'] = str_replace(',', '' , $input['n2d3_day1']);
        $input['n2d3_day2'] = str_replace(',', '' , $input['n2d3_day2']);
        $input['n2d3_day3'] = str_replace(',', '' , $input['n2d3_day3']);
        $input['n2d3_total'] = str_replace(',', '' , $input['n2d3_total']);
        $input['n3d4_day1'] = str_replace(',', '' , $input['n3d4_day1']);
        $input['n3d4_day2'] = str_replace(',', '' , $input['n3d4_day2']);
        $input['n3d4_day3'] = str_replace(',', '' , $input['n3d4_day3']);
        $input['n3d4_day4'] = str_replace(',', '' , $input['n3d4_day4']);
        $input['n3d4_total'] = str_replace(',', '' , $input['n3d4_total']);
        $input['additional_day'] = str_replace(',', '' , $input['additional_day']);
        $input['additional_total'] = str_replace(',', '' , $input['additional_total']);


        $validator = Validator::make($request->all(),
            [
                'title'             => 'required',
                'startdate'         => 'required',
                'enddate'           => 'required',
                'percent'           => 'required|numeric|min:0|max:400',
            ],
            [
                'startdate.required'  => trans('carbasic.custompricestartdaterquired'),
                'enddate.required'    => trans('carbasic.custompriceenddaterquired'),
                'title.required'    => trans('carbasic.custompricetitlerquired'),
                'percent.required'  => trans('carbasic.custompricepercentrquired'),
                'percent.min'       => trans('carbasic.custompricepercentmin'),
                'percent.max'       => trans('carbasic.custompricepercentmax'),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->with('tag','custom');
//            $this->throwValidationException(
//                $request, $validator
//            );
        }

        $getdata = \DB::table('car_class_price_custom')->where('id', $id)->get();

        if(count($getdata) != 0) {
            $update = \DB::table('car_class_price_custom')->where('id', $id)->update($input);
        }else {
            $create = \DB::table('car_class_price_custom')->insert($input);
        }

        //select price custome
        $custom = \DB::table('car_class_price_custom')->where('class_id', $class_id)->get();

        return back()->with('success', trans('carbasic.updatepricecustom'))->with('tag','custom');
    }

    //update car class option
    public function updateoption(Request $request){

        $input      = Input::except('_token','method');

        $options = explode(",", $request->input('car_options'));
        $class_id    = $input['class_id'];
        DB::beginTransaction();
        if($options[0] != "") {
            $car_options = array();
            for ($i = 0; $i < count($options); $i++) {
                    $option = array("option_id" => $options[$i], "class_id" => $class_id);
                    array_push($car_options, $option);
            }
            $carotionclassdelete = \DB::table('car_option_class')
                                ->where('class_id', $class_id)->delete();
            if (!$carotionclassdelete) {
                DB::rollBack();
            }

            $caroptionclassinsert = \DB::table('car_option_class')->insert($car_options);
            if (!$caroptionclassinsert) {
                DB::rollBack();
            }
        }else {
            $carotionclassdelete = \DB::table('car_option_class')
                ->where('class_id', $class_id)->delete();
            if (!$carotionclassdelete) {
                DB::rollBack();
            }
        }
        DB::commit();
        $carClass       = CarClass::findOrFail($class_id);
        //select normal price
        $options = \DB::table('car_option as co')
                        ->leftjoin('car_option_class as coc','co.id','=','coc.class_id')
                        ->select(['co.*'])
                        ->where('coc.class_id', $class_id)->get();
        $data = [
          'carclass'    => $carClass,
          'options'      => $options
        ];

        return back()->with('success', trans('carbasic.updateclassoption'))->with($data)->with('tag','option');
    }

    //update insurance
    public function updateinsurance(Request $request){

        $input          = Input::except('_token','method');
        $first          = explode("_",$request->input('first_val'));
        $first_price    = $first[0];
        $first_ins_id   = $first[1];
        $second         = explode("_",$request->input('second_val'));
        $second_price   = $second[0];
        $second_ins_id  = $second[1];
        $class_id       = $input['class_id'];
        DB::beginTransaction();
        $firstins = \DB::table('car_class_insurance')
                ->where('class_id', $class_id)
                ->where('insurance_id', $first_ins_id)->first();
        if(empty($firstins)) {
            $ins    = array("insurance_id" => $first_ins_id, "class_id" => $class_id,"price" =>$first_price);
            $first_insert = \DB::table('car_class_insurance')->insert($ins);
            if (!$first_insert) {
                DB::rollBack();
            }
        }else {
            $ins    = array("price" =>$first_price);
            $first_update = \DB::table('car_class_insurance')
                ->where("insurance_id", $first_ins_id)
                ->where("class_id", $class_id)
                ->update($ins);
            if (!$first_update) {
                DB::rollBack();
            }
        }

        $secondins = \DB::table('car_class_insurance')
            ->where('class_id', $class_id)
            ->where('insurance_id', $second_ins_id)->first();
        if(empty($secondins)) {
            $ins_sec    = array("insurance_id" => $second_ins_id, "class_id" => $class_id,"price" =>$second_price);
            $second_insert = \DB::table('car_class_insurance')->insert($ins_sec);
            if (!$second_insert) {
                DB::rollBack();
            }
        }else {
            $ins_sec    = array("price" =>$second_price);
            $second_update = \DB::table('car_class_insurance')
                ->where("insurance_id", $second_ins_id)
                ->where("class_id", $class_id)
                ->update($ins_sec);
            if (!$second_update) {
                DB::rollBack();
            }
        }


        DB::commit();
        $carClass       = CarClass::findOrFail($class_id);
        //select normal price

        $inses      = CarInsurance::all();
        $insurances = array();
        foreach($inses as $in){
            $price = 0;
            $obj = clone $in;
            $carins     = DB::table('car_class_insurance')
                ->where('class_id',$class_id)->where('insurance_id',$in->id)->first();
            if(!empty($carins)){
                $price = $carins->price;
            }else{
                $price = 0;
            }
            $obj->price = $price;
            array_push($insurances,$obj);
        }

        $data = [
            'carclass'    => $carClass,
            'options'      => $insurances
        ];

        return back()->with('success', trans('carbasic.updateclassinsurance'))->with($data)->with('tag','insurance');
    }
    //update equipment
    public function updateequipment(Request $request){

        $input      = Input::except('_token','method');

        $equipments = explode(",", $request->input('car_equipments'));
        $class_id    = $input['class_id'];
        DB::beginTransaction();
        if($equipments[0] != "") {
            $car_equipments = array();
            for ($i = 0; $i < count($equipments); $i++) {
                $equipment = array("equipment_id" => $equipments[$i], "class_id" => $class_id);
                array_push($car_equipments, $equipment);
            }
            $carequipmentclassdelete = \DB::table('car_class_equipment')
                ->where('class_id', $class_id)->delete();
            if (!$carequipmentclassdelete) {
                DB::rollBack();
            }

            $carequipmentclassinsert = \DB::table('car_class_equipment')->insert($car_equipments);
            if (!$carequipmentclassinsert) {
                DB::rollBack();
            }
        }else {
            $carequipmentclassdelete = \DB::table('car_class_equipment')
                ->where('class_id', $class_id)->delete();
            if (!$carequipmentclassdelete) {
                DB::rollBack();
            }
        }
        DB::commit();
        $carClass       = CarClass::findOrFail($class_id);
        //select normal price
        $equip = \DB::table('car_equip as ce')
            ->leftjoin('car_class_equipment as cce','ce.id','=','cce.class_id')
            ->select(['ce.*'])
            ->where('cce.class_id', $class_id)->get();
        $data = [
            'carclass'    => $carClass,
            'equip'  => $equip,
        ];

        return back()->with('success', trans('carbasic.updateclassequipment'))->with($data)->with('tag','equip');
    }


    //edit custom price
    public function editpricecustom(Request $request,$id)
    {
        //select business hour2
        $custom = \DB::table('car_class_price_custom')->where('id', $id)->first();
        return back()->with('tag','custom')
            ->with('custom', $custom);
    }
    //delete custom price
    public function deletepricecustom(Request $request,$id)
    {
        $input      = Input::except('_token','method');
        $deletehour = \DB::table('car_class_price_custom')->where('id', $id)->delete();
        return back()->with('success', trans('carbasic.deletepricecustom'))->with('tag','custom');

    }

    public function update_model_order(Request $request, $class_id)
    {
//        echo $class_id.'<br>';
        $order_string = $request->input('model_orders');
        if( $order_string == '' )
            return back()->withErrors(['model_priority' => 'No model order information']);

        $model_ids = explode(',', $order_string);
        $k = 0;
        foreach( $model_ids as $mid ) {
            \DB::table('car_class_model')
                ->where('class_id', $class_id)
                ->where('model_id', $mid)
                ->update(['priority'=>$k] );
            $k++;
        }

        return back()->with('success', 'クラスのモデル優先度が更新されました。');
    }
}
