<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CarEquip;
use App\Models\CarModel;
use App\Models\CarClass;
use App\Models\CarOption;
use App\Models\Shop;
use App\Models\CarInventory;
//use App\Models\Score;
//use App\Models\Repairshops;
use App\Models\CarClassModel;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\DataUtil\ServerPath;

use DB;
use DateTime;
use Response;

class CarRepairController extends Controller
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
        $current_user = Auth::user();
        $related_shops = \DB::table('admin_shop')->where('admin_id', $current_user->id)->get();
        $admin_shop_ids = [];
        $admin_shop_id = '';

        foreach ($related_shops as $key=>$shop) {
            $admin_shop_ids[] = $shop->shop_id;
            if($key == 0) {
                $admin_shop_id = $shop->shop_id;
            }
        }

        if(!empty($request->input('search_shop'))) {
            $search_shop = $request->input('search_shop');
        }else if(!empty($admin_shop_id)){
            $search_shop = $admin_shop_id;
        }else{
            $search_shop = 'all';
        }

        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $shops          = Shop::all();
        $shop_id        = $search_shop;

        $repairs    = \DB::table('car_inspections as ins')
            ->leftJoin('car_inventory as inv', 'ins.inventory_id','=','inv.id')
            ->leftjoin('car_model as mo', 'mo.id', '=', 'inv.model_id')
            ->leftjoin('car_shop as sh', 'sh.id', '=', 'inv.shop_id')
            ->select(['ins.*','inv.shortname','mo.name as model_name','sh.name as shop_name']);

        if($shop_id != 'all'){
            $repairs = $repairs
            ->where('inv.shop_id',$shop_id);
        }
        $repairs = $repairs->where('ins.delete_flag', 0)
            ->orderby('ins.id', 'desc')->get();
        return View('pages.admin.carrepair.index')
            ->with([
                'repairs'   => $repairs,
                'route'     => $route,
                'subroute'  => $subroute,
                'shops'     => $shops,
                'shop_id'   => $shop_id,
                'user'      => $current_user,
                'admin_shop_ids' => $admin_shop_ids
            ]);

    }

    //get class name from model_id
    public function getClassNameFromModelId($model_id){
        $class = \DB::table('car_class as cl')
            ->join('car_class_model as clm','clm.class_id','=', 'cl.id')
            ->where('clm.model_id',$model_id)
            ->select(['cl.*'])->first();
        if(!empty($class))
            return $class_name = $class->name;
        else
            return $class_name = "";
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
        $classes    = CarClass::orderby('name')->get();
        $models     = CarModel::all();
        $shops      = Shop::all();

        $current_user = Auth::user();
        $related_shops = \DB::table('admin_shop')->where('admin_id', $current_user->id)->get();
        $admin_shop_ids = [];
        $admin_shop_id = '';

        foreach ($related_shops as $key=>$shop) {
            $admin_shop_ids[] = $shop->shop_id;
            if($key == 0) {
                $admin_shop_id = $shop->shop_id;
            }
        }

        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'classes'   => $classes,
            'models'    => $models,
            'shops'     => $shops,
            'shop_id'   => $admin_shop_id,
//            'cars'      => $cars
        ];

        return view('pages.admin.carrepair.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::except('_token','method');
//        $ids = explode(",", $request->input('dropoff_ids'));

        $validator = Validator::make($request->all(),
            [
                'car_inventory' => 'required',
                'kind'          => 'required',
                'begin_end'     => 'required',
                'status'        => 'required',
            ],
            [
                'car_inventory.required'    => 'vehicle not selected',
                'kind.required'             => 'kind not selected',
                'begin_end.required'        => 'duration not selected',
                'status.required'           => 'status not selected',
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
            $date_part = date('ymd');
            $v = explode(' - ', $input['begin_end']);
            $new_begin = date('Y-m-d', strtotime($v[0]));
            $new_end = date('Y-m-d', strtotime($v[1]));

            // check duplicating
            $inspections = \DB::table('car_inspections')
                ->whereRaw('delete_flag=0 AND inventory_id=? AND ((`begin_date`>=? AND `begin_date`<=?) OR (`end_date`>=? AND `end_date`<=?) OR (`begin_date`<=? AND `end_date`>=?))', [$input['car_inventory'], $new_begin, $new_end, $new_begin, $new_end, $new_begin, $new_end])
                ->orderBy('id')
                ->count();

            $duplicating = $inspections > 0;

            if($duplicating) {
                return back()->withErrors(['error', '重複した検査があります。'])
                    ->withInput();
            }

            $last_id = \DB::table('car_inspections')
                ->where('inspection_id', 'like', $date_part.'%')
                ->orderBy('id','desc')
                ->first();
            if(is_null($last_id)) {
                $inspection_id = $date_part.'-0001';
            } else {
                $v = explode('-', $last_id->inspection_id);
                $num = intval($v[1]);
                $num_part = str_pad(($num + 1), 4, '0', STR_PAD_LEFT);
                $inspection_id = $date_part.'-'.$num_part;
            }
            $admin = Auth::user();
            $data = [
                'inspection_id' => $inspection_id,
                'admin_id'      => $admin->id,
                'inventory_id'  => $input['car_inventory'],
                'shop_id'       => $input['shop'],
                'class_id'      => $input['car_class'],
                'begin_date'    => $new_begin,
                'end_date'      => $new_end,
                'status'        => $input['status'],
                'kind'          => $input['kind'],
                'price'         => $input['price'],
                'mileage'       => $input['mileage'],
                'delete_flag'   => 0,
                'memo'          => $input['memo']
            ];
            DB::beginTransaction();
            $inspection = \DB::table('car_inspections')->insert($data);

            if(!$inspection){
                DB::rollBack();
            }
            DB::commit();
            return redirect('/carrepair')->with('success', '検査データが正常に保存されました。');
        }
    }

    public function getNumberOfBookingInspection(Request $request) {
        $id = $request->input('id');
        $begin  = $request->input('begin');
        $end = $request->input('end');

        $bookings = \DB::table('bookings as b')
            ->leftJoin('users as u', 'b.client_id', '=', 'u.id')
            ->leftJoin('profiles as p', 'b.client_id', '=', 'p.user_id')
            ->select('b.*', 'u.email', 'u.first_name', 'u.last_name', 'p.fur_first_name', 'p.fur_last_name')
            ->where('b.inventory_id','=',$id)
            ->whereNotIn('b.status', [8, 9]);
        if(is_null($end)) {
            $bookings = $bookings->where(function($q) use ($begin){
                $q->whereDate('b.departing', '>=', $begin)
                    ->orWhere(function($query) use ($begin) {
                        $query->whereDate('b.departing', '<=', $begin)
                            ->where(function($sub) use ($begin) {
                                $sub->whereDate('b.returning', '>=', $begin)
                                    ->orWhereDate('b.returning_updated', '>=', $begin);
                            });
                    });

            });
        } else {
            $bookings = $bookings->where(function($query) use ($begin, $end) {
                $query->where(function ($q1) use ($begin, $end) {
                        $q1->whereDate('b.departing', '>=', $begin)->whereDate('b.departing', '<=', $end);})
                    ->orWhere(function ($q2) use ($begin, $end) {
                        $q2->whereDate('b.returning', '>=', $begin)->whereDate('b.returning', '<=', $end);})
                    ->orWhere(function ($q3) use ($begin, $end) {
                        $q3->whereDate('b.returning_updated', '>=', $begin)->whereDate('b.returning_updated', '<=', $end);})
                    ->orWhere(function ($q4) use ($begin, $end) {
                        $q4->whereDate('b.departing', '<=', $begin)
                            ->where(function ($sub) use ($end) {
                                $sub->whereDate('b.returning', '>=', $end)
                                    ->orWhereDate('b.returning_updated', '>=', $end);
                            });
                    });
            });
        }
        $bookings = $bookings->orderBy('b.departing', 'desc')->get();

        $inspections = \DB::table('car_inspections')
            ->where('inventory_id', $id);
        if(is_null($end)) {
            $inspections = $inspections->where(function($q) use ($begin){
                $q->whereDate('begin_date', '>=', $begin)
                    ->orWhere(function($query) use ($begin) {
                        $query->whereDate('begin_date', '<=', $begin)
                            ->whereDate('end_date', '>=', $begin);
                    });
            });
        } else {
            $inspections = $inspections->where(function($qq) use ($begin, $end) {
                $qq->where(function ($q) use ($begin, $end) {
                        $q->whereDate('begin_date', '>=', $begin)->whereDate('begin_date', '<=', $end);})
                    ->orWhere(function ($q) use ($begin, $end) {
                        $q->whereDate('end_date', '>=', $begin)->whereDate('end_date', '>=', $end);})
                    ->orWhere(function ($q) use ($begin, $end) {
                        $q->whereDate('begin_date', '<=', $begin)->whereDate('end_date', '>=', $end);});
            });
        }
        $inspections = $inspections->where('delete_flag', 0)
            ->where('kind', 1)
            ->count();
        $substitutions = \DB::table('car_inspections')
            ->where('inventory_id', $id);
        if(is_null($end)) {
            $substitutions = $substitutions->where(function($q) use ($begin) {
                $q->whereDate('begin_date', '>=', $begin)
                    ->orWhere(function ($query) use ($begin) {
                        $query->whereDate('begin_date', '<=', $begin)
                            ->whereDate('end_date', '>=', $begin);
                    });
            });
        } else {
            $substitutions = $substitutions->where(function($qq) use ($begin, $end){
                $qq->where(function($q) use ($begin, $end) {
                        $q->whereDate('begin_date','>=',$begin)->whereDate('begin_date','>=',$end); })
                    ->orWhere(function($q) use ($begin, $end) {
                        $q->whereDate('end_date','>=',$begin)->whereDate('end_date','>=',$end); })
                    ->orWhere( function($query) use ($begin, $end){
                        $query->whereDate('begin_date','<=', $begin)
                            ->whereDate('end_date', '>=', $end);
                    } );
            });
        }
        $substitutions = $substitutions->where('delete_flag', 0)
            ->where('kind', '>', 1)
            ->count();

        return [
            'booking'       => count($bookings),
            'booking_data'  => $bookings,
            'inspections'   => $inspections,
            'substitutions' => $substitutions
            ];
    }

    public function inspectionList(Request $request) {
        $id = $request->input('id');
        $begin  = $request->input('begin');
        $end = $request->input('end');
        $inventory = CarInventory::find($id);

        $inspections = \DB::table('car_inspections')
            ->where('inventory_id', $id)
            ->whereDate('begin_date', '>=', $begin)
            ->whereDate('end_date', '<=', $end)
            ->where('delete_flag', 0)
            ->where('kind', 1)
            ->get();

        return view('pages.admin.carrepair.inspection-list')
            ->with([
                'repairs'   => $inspections,
                'inventory' => $inventory,
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $models     = CarModel::all();
        $shops      = Shop::all();

        $repair    = \DB::table('car_inspections as ins')
            ->leftJoin('car_inventory as inv', 'ins.inventory_id','=','inv.id')
//            ->leftJoin('repairshops as rs', 'ins.repairshop','=','rs.id')
            ->leftjoin('car_model as mo', 'mo.id', '=', 'inv.model_id')
            ->leftjoin('car_shop as sh', 'sh.id', '=', 'inv.shop_id')
            ->select(['ins.*','inv.shortname','mo.name as model_name','sh.name as shop_name'])
            ->where('ins.delete_flag', 0)
            ->where('ins.id', $id)
            ->orderby('ins.id', 'desc')->first();
        if(!is_null($repair))
            $inventory = CarInventory::findOrFail($repair->inventory_id);
        else
            $inventory = null;
        $data = [
            'repair'        => $repair,
            'inventory'     => $inventory,
            'route'         => $route,
            'subroute'      => $subroute,
            'shops'         => $shops,
            'models'        => $models,
        ];

        return view('pages.admin.carrepair.show')->with($data);
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
        $models     = CarModel::all();
        $shops      = Shop::all();

        $repair    = \DB::table('car_inspections as ins')
            ->leftJoin('car_inventory as inv', 'ins.inventory_id','=','inv.id')
//            ->leftJoin('repairshops as rs', 'ins.repairshop','=','rs.id')
            ->leftjoin('car_model as mo', 'mo.id', '=', 'inv.model_id')
            ->leftjoin('car_shop as sh', 'sh.id', '=', 'inv.shop_id')
            ->select(['ins.*','inv.shortname','mo.name as model_name','sh.name as shop_name'])
            ->where('ins.delete_flag', 0)
            ->where('ins.id', $id)
            ->orderby('ins.id', 'desc')->first();
        $inventory = CarInventory::find($repair->inventory_id);
//        $repair_shops = \DB::table('repairshops')->orderBy('id')->get();
        $data = [
            'repair'        => $repair,
            'inventory'     => $inventory,
            'route'         => $route,
            'subroute'      => $subroute,
            'shops'         => $shops,
            'models'        => $models,
//            'repair_shops'  => $repair_shops,
        ];
        return view('pages.admin.carrepair.edit')->with($data);
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
        $input = Input::except('_token','method','_method');

        $begin_end = $input['begin_end'];
        $tmp = explode(' - ', $begin_end);
        $begin = $tmp[0];
        $end = $tmp[1];
        $data = [
            'kind'          => $input['kind'],
//            'repairshop'    => $input['repairshop'],
            'begin_date'    => $begin,
            'end_date'      => $end,
            'price'         => $input['price'],
            'memo'          => $input['memo'],
            'status'        => $input['status'],
            'mileage'       => $input['mileage']
        ];

        $result = \DB::table('car_inspections')->where('id',$id)->update($data);
        $result1 = \DB::table('car_inventory')
            ->where('id', $input['inventory_id'])
            ->update(['current_mileage' => $input['mileage']]);

        return back()->with('success', 'Inspection data has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $carinventory = \DB::table('car_inspections')->where('id',$id)->update(['delete_flag' => 1]);
//        $carinventory        = CarInventory::findOrFail($id);
//        \DB::transaction(function() use($carinventory, $id) {
//            $carinventory->delete();
//            $cardropoff = \DB::table('car_inventory_dropoff')->where('inventory_id', $id)->delete();
//        });
        return redirect('/carrepair')->with('success', 'inspection has been deleted');
    }

    function get_inventory(Request $request) {
        $shop_id = $request->get('shop_id');
        $class_id = $request->get('class_id');
//        return ['class'=>$class_id, 'shop'=>$shop_id];

        $cars = DB::table('car_class_model as cm')
            ->leftJoin('car_model as m', 'm.id', '=', 'cm.model_id' )
            ->leftJoin('car_inventory as ci', 'cm.model_id', '=', 'ci.model_id')
            ->where('cm.class_id', '=', $class_id)
            ->where('ci.shop_id',$shop_id)
            ->where('ci.delete_flag', 0)
            ->orderBy('ci.shortname')
            ->select(['ci.*'])->get();

        return ['cars' => $cars];
    }

    function checkDateRange(Request $request) {
        $car_id = $request->get('cid');
        $begin  = $request->get('begin');
        $end    = $request->get('end');
        $ins_id = $request->get('ins_id', '');

        $enable = ServerPath::getConfirmInspection($car_id, $begin, $end, $ins_id, '');
        $enable = $enable && ServerPath::getconfirmBooking($car_id, $begin, $end, '');

        return ['enable' => $enable];
    }
}
