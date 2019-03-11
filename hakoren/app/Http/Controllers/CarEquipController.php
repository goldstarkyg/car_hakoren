<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CarEquip;
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

class CarEquipController extends Controller
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
        $equips = \DB::table('car_equip as equip')
            ->select(['equip.*']);

        $equips = $equips
            ->orderby('equip.id', 'desc')->get();

        return View('pages.admin.carbasic.carequip-index', compact('equips','route','subroute'));

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
        $data = [
            'route' => $route,
            'subroute' => $subroute
        ];

        return view('pages.admin.carbasic.carequip-create')->with($data);
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
                'abbriviation.required'   => trans('carbasic.carequipabbriviationrequired'),
                'name.required'    => trans('carbasic.carequiprequired')
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
            DB::beginTransaction();
            $input = Input::except('_token','thumbnail','name', 'abbriviation');

            $carequip = CarEquip::create([
                'abbriviation' => $request->input('abbriviation'),
                'name' => $request->input('name')
            ]);
//            $carequip->save();

            if(!$carequip) { DB::rollBack(); }

            $id = $carequip->id;
            if($thumbfile = $request->file('thumbnail')) {
                $fileName = $thumbfile->getClientOriginalName();
                $extension = $thumbfile->getClientOriginalExtension();
                $folderName = '/images/carequipment/';
                $destinationPath = public_path() . $folderName;
                $safeName = strtotime("now").rand(1,20).'.' . $extension;
                $thumbfile->move($destinationPath, $safeName);
                $thumb_path = $folderName.$safeName;
                $carupdate = DB::table('car_equip')
                    ->where('id', $id)
                    ->update(['thumbnail' => $thumb_path]);
                if(!$carupdate){
                    DB::rollBack();
                }
            }
            DB::commit();
            return redirect('/carbasic/carequip')->with('success', trans('carbasic.carequipcreateSuccess'));
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

        $carequip    = CarEquip::find($id);
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();

        return view('pages.admin.carbasic.carequip-show', compact('carequip', 'route', 'subroute'))->withUser($carequip);
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
        $carequip = CarEquip::findOrFail($id);

        $data = [
            'carequip'      => $carequip,
            'route'         => $route,
            'subroute'      => $subroute
        ];
        return view('pages.admin.carbasic.carequip-edit')->with($data);
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

        $carequip    = CarEquip::find($id);

        $carequip->abbriviation = $request->input('abbriviation');
        $carequip->name = $request->input('name');

        if($thumbfile = $request->file('thumbnail')) {
            $fileName = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/images/carequipment/';
            $destinationPath = public_path() . $folderName;
            $safeName = strtotime("now").rand(1,20).'.' . $extension;
            $thumbfile->move($destinationPath, $safeName);
            $thumb_path = $folderName.$safeName;
            $carequip->thumbnail = $thumb_path;
        }
        $carequip->save();
        return back()->with('success', trans('carbasic.updateCarequip'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $carequip        = CarEquip::findOrFail($id);
        $carequip->delete();

        return redirect('/carbasic/carequip/')->with('success', trans('carbasic.deleteCarEquip'));


    }
}
