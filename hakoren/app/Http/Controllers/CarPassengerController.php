<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CarPassengerTags;
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

class CarPassengerController extends Controller
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
        $passengers = CarPassengerTags::all();

        return View('pages.admin.carbasic.carpassenger-index', compact('passengers','route','subroute'));

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

        return view('pages.admin.carbasic.carpassenger-create')->with($data);
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
                'name.required'    => trans('carbasic.carpassengerrequired')
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {

            $carinsurance = CarInsurance::create([
                'name' => $request->input('name'),
                'name_en' => $request->input('name_en'),
                'max_passenger' => $request->input('max_passenger'),
                'min_passenger' => $request->input('min_passenger'),
                'show_order' => $request->input('show_order')
            ]);
            $carinsurance->save();
            return redirect('/carbasic/carpassenger')->with('success', trans('carbasic.carpassengercreateSuccess'));
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

        $carpassenger   = CarPassengerTags::find($id);
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();

        return view('pages.admin.carbasic.carpassenger-show', compact('carpassenger', 'route', 'subroute'));
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
        $carpassenger = CarPassengerTags::findOrFail($id);

        $data = [
            'carpassenger'  => $carpassenger,
            'route'         => $route,
            'subroute'      => $subroute
        ];
        return view('pages.admin.carbasic.carpassenger-edit')->with($data);
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

        $passenger    = CarPassengerTags::find($id);
        $passenger->name = $request->input('name');
        $passenger->name_en = $request->input('name_en');
        $passenger->max_passenger = $request->input('max_passenger');
        $passenger->min_passenger = $request->input('min_passenger');
        $passenger->save();
        return back()->with('success', trans('carbasic.updateCarpassenger'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $passenger        = CarPassengerTags::findOrFail($id);
        $passenger->delete();
        return redirect('/carbasic/carinsurance/')->with('success', trans('carbasic.deleteCarpassenger'));


    }
}
