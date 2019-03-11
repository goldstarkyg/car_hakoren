<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CarInsurance;
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

class CarInsuranceController extends Controller
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
        $insurances = \DB::table('car_insurance as insurance')
            ->select(['insurance.*']);

        $insurances = $insurances
            ->orderby('insurance.id', 'desc')->get();

        return View('pages.admin.carbasic.carinsurance-index', compact('insurances','route','subroute'));

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

        return view('pages.admin.carbasic.carinsurance-create')->with($data);
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
                'abbriviation.required'   => trans('carbasic.carinsuranceabbriviationrequired'),
                'name.required'    => trans('carbasic.carinsurancerequired')
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {

            $carinsurance = CarInsurance::create([
                'abbriviation' => $request->input('abbriviation'),
                'name' => $request->input('name')
            ]);
            $carinsurance->save();
            return redirect('/carbasic/carinsurance')->with('success', trans('carbasic.carinsurancecreateSuccess'));
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

        $carinsurance   = CarInsurance::find($id);
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();

        return view('pages.admin.carbasic.carinsurance-show', compact('carinsurance', 'route', 'subroute'))->withUser($carinsurance);
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
        $carinsurance = CarInsurance::findOrFail($id);

        $data = [
            'carinsurance'  => $carinsurance,
            'route'         => $route,
            'subroute'      => $subroute
        ];
        return view('pages.admin.carbasic.carinsurance-edit')->with($data);
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

        $carinsurance    = CarInsurance::find($id);

        $carinsurance->abbriviation = $request->input('abbriviation');
        $carinsurance->name = $request->input('name');
        $carinsurance->search_condition = $request->input('search_condition');
        $carinsurance->save();
        return back()->with('success', trans('carbasic.updateCarinsurance'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $carinsurance        = CarInsurance::findOrFail($id);
        $carinsurance->delete();

        return redirect('/carbasic/carinsurance/')->with('success', trans('carbasic.deleteCarInsurance'));


    }
}
