<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CarEquip;
use App\Models\CarModel;
use App\Models\CarType;
use App\Models\CarVendor;
use App\Models\Score;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
    use Validator;
use Illuminate\Support\Facades\Route;

use DB;
use App\Http\DataUtil\ServerPath;
use Response;

class CarModelController extends Controller
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
        $models     = CarModel::all();

        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'models'    => $models
        ];

        return View('pages.admin.carbasic.carmodel-index')->with($data);

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
        $types      = CarType::all();
        $vendors    = CarVendor::all();
        $equips     = CarEquip::all();
        $category   = \DB::table('car_type_category')->get();
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'types'     => $types,
            'vendors'   => $vendors,
            'equips'    => $equips,
            'category'  => $category,
        ];

        return view('pages.admin.carbasic.carmodel-create')->with($data);
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
                'name.required'    => trans('carbasic.carmodelrequired')
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
            $input = Input::except('_token','thumb_path','file');
            $carmodel = CarModel::create($input);
            $carmodel->save();
            $id = $carmodel->id;
            if($thumbfile = $request->file('thumb_path')) {
                $fileName = $thumbfile->getClientOriginalName();
                $extension = $thumbfile->getClientOriginalExtension();
                $folderName = '/images/carmodel/';
                $destinationPath = public_path() . $folderName;
                $safeName = strtotime("now").rand(1,20).'.' . $extension;
                $thumbfile->move($destinationPath, $safeName);
                $thumb_path = $folderName.$safeName;
                $carupdate = DB::table('car_model')
                    ->where('id', $id)
                    ->update(['thumb_path' => $thumb_path]);
            }

            $model_thumbnails = array();
            if($files = $request->file('file')) {
                for($i=0; $i < count($files); $i++) {
                    $fileName = $files[$i]->getClientOriginalName();
                    $extension = $files[$i]->getClientOriginalExtension();
                    $folderName = '/images/carmodel/';
                    $destinationPath = public_path() . $folderName;
                    $safeName = strtotime("now").rand(1,20).'.' . $extension;
                    $files[$i]->move($destinationPath, $safeName);
                    $thumb_path = $folderName.$safeName;
                    $thumb = array("model_id" => $id, "thumb_path" => $thumb_path);
                    array_push($model_thumbnails, $thumb);
                }
                $carmodelthubnails = \DB::table('car_model_thumb')->insert($model_thumbnails);
            }

            return redirect('/carbasic/carmodel')->with('success', trans('carbasic.carmodelcreateSuccess'));
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

        $carmodel = CarModel::find($id);
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $thumbnails = \DB::table('car_model_thumb')->where('model_id',$id)->get();

        return view('pages.admin.carbasic.carmodel-show', compact('carmodel', 'route', 'subroute','thumbnails'))->withUser($carmodel);
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
        $carmodel   = CarModel::findOrFail($id);
        $types      = CarType::all();
        $vendors    = CarVendor::all();
        $equips     = CarEquip::all();
        $thumbnails = \DB::table('car_model_thumb')->where('model_id',$id)->get();
        $category   = \DB::table('car_type_category')->get();
        $data = [
            'carmodel'     => $carmodel,
            'route'         => $route,
            'subroute'      => $subroute,
            'types'         => $types,
            'vendors'       => $vendors,
            'thumbnails'    => $thumbnails,
            'equips'        => $equips,
            'category'      => $category
        ];
        return view('pages.admin.carbasic.carmodel-edit')->with($data);
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
        $input = Input::except('_token','thumb_path','_method','file','deletethumbs');
        if($thumbfile = $request->file('thumb_path')) {
            $fileName = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/images/carmodel/';
            $destinationPath = public_path() . $folderName;
            $safeName = strtotime("now").rand(1,20).'.' . $extension;
            $thumbfile->move($destinationPath, $safeName);
            $thumb_path = $folderName.$safeName;
            $input['thumb_path'] = $thumb_path;
        }

        $carmodel = CarModel::where('id',$id)->update($input);
        $model_thumbnails = array();

        //delete from deletethumbs
        if($ids = $request->get('deletethumbs')) {
            $thumbids = explode(',',$ids);
            for($i =0; $i < count($thumbids) ; $i++) {
                $deletethumb = \DB::table('car_model_thumb')->where('id', $thumbids[$i])->delete();
            }
        }
        if($files = $request->file('file')) {
            //delete file
            $thumbs = \DB::table('car_model_thumb')->where('model_id',$id)->get();
            for($i = 0; $i < count($thumbs); $i++) {
                if($thumbs[$i]->thumb_path != null) {
                    $thumb_path = public_path() . $thumbs[$i]->thumb_path;
                    if(file_exists($thumb_path))
                        unlink($thumb_path);
                }
            }
            $carmodeldeletethumbnails = \DB::table('car_model_thumb')->where('model_id',$id)->delete();

            for($i=0; $i < count($files); $i++) {
                $fileName = $files[$i]->getClientOriginalName();
                $extension = $files[$i]->getClientOriginalExtension();
                $folderName = '/images/carmodel/';
                $destinationPath = public_path() . $folderName;
                $safeName = strtotime("now").rand(1,20).'.' . $extension;
                $files[$i]->move($destinationPath, $safeName);
                $thumb_path = $folderName.$safeName;
                $thumb = array("model_id" => $id, "thumb_path" => $thumb_path);
                array_push($model_thumbnails, $thumb);
            }
            $carmodelthumbnails = \DB::table('car_model_thumb')->insert($model_thumbnails);
        }
        return redirect('carbasic/carmodel/'.$id)->with('success', trans('carbasic.updateCarModel'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $CarModel       = CarModel::findOrFail($id);
        $thumb_path     = $CarModel->thumb_path;
        //delete featured thumbnail
        $CarModel->delete();
        if($thumb_path != null) {
            $path = public_path() . $thumb_path;
            unlink($path);
        }
        //delete related thumbnails
        $thumbs = \DB::table('car_model_thumb')->where('model_id',$id)->get();
        for($i = 0; $i < count($thumbs); $i++) {
            if($thumbs[$i]->thumb_path != null) {
                $thumb_path = public_path() . $thumbs[$i]->thumb_path;
                unlink($thumb_path);
            }
        }
        $carmodelthumbnails = \DB::table('car_model_thumb')->where('model_id', $id)->delete();

        return redirect('/carbasic/carmodel/')->with('success', trans('carbasic.deleteCarMoeel'));


    }

    //get equip name
    public function equipname($equip_id) {
        $equip = \DB::table('car_equip')->where('id',$equip_id)->first();
        if($equip) {
            return $equip->name;
        }else {
            return '';
        }

    }

    //get number of smoking and non smoking from car inventory
    public function getnumberSmoking($model_id, $cond) {
        //$cond == 1 -> smoke  $cond == 0 -> non smoke
        $smokenum = \DB::table('car_inventory')
            ->where('model_id', $model_id)
            ->where('smoke', $cond)
            ->where('delete_flag', 0)
            ->count();
        return $smokenum;
    }

    //get car type list like cateogty_id
    public function getcartype(Request $request) {
        $category_id = $request->get('category_id');
        $type = \DB::table('car_type')->where('category_id', $category_id)->get();
        return Response::json($type);
    }
}
