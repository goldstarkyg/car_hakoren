<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CarType;
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

use DB;
use App\Http\DataUtil\ServerPath;

class CarTypeController extends Controller
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
        $types = \DB::table('car_type as type')
            ->leftjoin('car_type_category as ca','ca.id','=','type.category_id')
            ->select(['type.*','ca.name as category_name']);

        $types = $types
            ->orderby('type.id', 'desc')->get();

        return View('pages.admin.carbasic.cartype-index', compact('types','route','subroute'));

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
        $category   = \DB::table('car_type_category')->get();
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'category'  => $category,
        ];

        return view('pages.admin.carbasic.cartype-create')->with($data);
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
                'abbriviation.required'   => trans('carbasic.carabbriviationrequired'),
                'name.required'    => trans('carbasic.cartyperequired')
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {

            $cartype = CarType::create([
                'abbriviation'  => $request->input('abbriviation'),
                'name'          => $request->input('name'),
                'category_id'   => $request->input('category_id')
            ]);
            $cartype->save();
            return redirect('/carbasic/cartype')->with('success', trans('carbasic.cartypecreateSuccess'));
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

//        $cartype = CarType::find($id);
        $cartype =  \DB::table('car_type as ct')
                    ->leftjoin('car_type_category as ca','ca.id','=','ct.category_id')
                    ->where('ct.id',$id)
                    ->select(['ct.*','ca.name as category_name'])->first();

        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();

        return view('pages.admin.carbasic.cartype-show', compact('cartype', 'route', 'subroute'));
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
        $cartype = CarType::findOrFail($id);
        $category   = \DB::table('car_type_category')->get();

        $data = [
            'cartype'       => $cartype,
            'route'         => $route,
            'subroute'      => $subroute,
            'category'      => $category
        ];
        return view('pages.admin.carbasic.cartype-edit')->with($data);
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

        $cartype    = CarType::find($id);

        $cartype->abbriviation = $request->input('abbriviation');
        $cartype->name = $request->input('name');
        $cartype->category_id = $request->input('category_id');
        $cartype->save();
        return back()->with('success', trans('carbasic.updateCarType'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $cartype        = CarType::findOrFail($id);
        $cartype->delete();

        return redirect('/carbasic/cartype/')->with('success', trans('carbasic.deleteCarType'));


    }
}
