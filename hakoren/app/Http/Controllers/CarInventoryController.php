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
use App\Models\Score;
use App\Models\CarClassModel;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\DataUtil\ServerPath;

use DB;
use DateTime;
use Response;

class CarInventoryController extends Controller
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

        foreach ($related_shops as $key => $shop) {
            $admin_shop_ids[] = $shop->shop_id;
            if ($key == 0) {
                $admin_shop_id = $shop->shop_id;
            }
        }

        if (!empty($request->input('search_shop'))) {
            $search_shop = $request->input('search_shop');
        } else if (!empty($admin_shop_id)) {
            $search_shop = $admin_shop_id;
        } else {
            $search_shop = 'all';
        }

        $search_class = $request->input('search_class', 'all');
        $route = ServerPath::getRoute();
        $subroute = ServerPath::getSubRoute();
        $shops = Shop::all();
        $classes = \DB::table('car_class')
            ->where('delete_flag', 0)
            ->where('status', 1);
        if ($search_shop != 'all')
            $classes = $classes->where('car_shop_name', $search_shop);
        $classes = $classes->orderby('name')->get();
        $shop_id = $search_shop;

        if ($search_class == 'all') {
            $invens = \DB::table('car_inventory as i')
                ->join('car_model as m', 'i.model_id', '=', 'm.id')
                ->join('car_shop as s', 'i.shop_id', '=', 's.id')
                ->select(['i.*', 'm.name as model_name', 's.name as shop_name']);
            if ($shop_id != 'all') {
                $invens = $invens->where('i.shop_id', $shop_id);
            }
            $invens = $invens->where('i.status', 1)
                            ->where('i.delete_flag', 0)
                            ->orderby('i.shortname')->get();
        } else {
            $invens = \DB::table('car_class_model as mc')
                ->leftjoin('car_model as mo', 'mc.model_id', '=', 'mo.id')
                ->leftjoin('car_inventory as in', 'mc.model_id', '=', 'in.model_id')
                ->leftjoin('car_shop as sh', 'sh.id', '=', 'in.shop_id')
                ->select(['in.*', 'mc.class_id', 'mo.name as model_name', 'sh.name as shop_name']);
            if ($shop_id != 'all') {
                $invens = $invens->where('in.shop_id', $shop_id);
            }
            $invens = $invens->where('mc.class_id', $search_class)
                    ->where('in.status', 1)
                    ->where('in.delete_flag', 0)
                    ->orderby('in.shortname')->get();
        }

        return View('pages.admin.carinventory.index')
            ->with([
                'invens'    => $invens,
                'route'     => $route,
                'subroute'  => $subroute,
                'shops'     => $shops,
                'shop_id'   => $shop_id,
                'user'      => $current_user,
                'admin_shop_ids' => $admin_shop_ids,
                'class_id'  => $search_class,
                'classes'   => $classes
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

    public function create()
    {
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $classes    = CarClass::all();
        $models     = CarModel::all();
        $shops      = Shop::all();
        $shop_id    = 0;
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'classes'   => $classes,
            'models'    => $models,
            'shops'     => $shops,
            'shop_id'   => $shop_id,
        ];

        return view('pages.admin.carinventory.create')->with($data);
    }

    public function store(Request $request)
    {
        $input = Input::except('_token','method','dropoff_ids','dropoff_id');
//        $ids = explode(",", $request->input('dropoff_ids'));

        $validator = Validator::make($request->all(),
            [
                'numberplate1'     => 'required',
                'numberplate2'     => 'required',
                'numberplate3'     => 'required',
                'numberplate4'     => 'required',
                'priority'         => 'required',
                'current_mileage'  => 'required',
                'max_passenger'    => 'required'
            ],
            [
                'numberplate1.required'   => trans('carinventory.carinventorynumberplaterequired'),
                'numberplate2.required'   => trans('carinventory.carinventorynumberplaterequired'),
                'numberplate3.required'   => trans('carinventory.carinventorynumberplaterequired'),
                'numberplate4.required'   => trans('carinventory.carinventorynumberplaterequired'),
                'priority.required'       => trans('carinventory.carinventorypriorityrequired'),
                'current_mileage.required'    => trans('carinventory.carinvnetorymileageerequired'),
                'max_passenger.required'  => trans('carinventory.carinventorymaxpassengerrequired')
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
            DB::beginTransaction();
            $input['dropoff_availability'] = 0;
            $carinventory = CarInventory::create($input);

            if(!$carinventory){
                DB::rollBack();
            }
            //save drop shop
//            if($input['dropoff_availability'] == '1' && $request->input('dropoff_ids') !='' ) {
//                $ids = explode(",", $request->input('dropoff_ids'));
//
//                $car_iventorys = array();
//                for ($i = 0; $i < count($ids); $i++) {
//                    $inven = array("inventory_id" => $carinventory->id, "shop_id" => $ids[$i]);
//                    array_push($car_iventorys, $inven);
//                }
//                $carinventorylnsert = \DB::table('car_inventory_dropoff')->insert($car_iventorys);
//                if (!$carinventorylnsert) {
//                    DB::rollBack();
//                }
//            }
            //save car class model
           /* $model_id =  $request->input('model_id');
            $class_id =  $request->input('class_id');

            if(!empty($model_id)&&!empty($class_id)) {
                $class_model = array();
                $model = array("class_id" => $class_id, "model_id" => $model_id);
                array_push($class_model, $model);
                $confirm = CarClassModel::where('class_id',$class_id)->where('model_id',$model_id)->first();
                if(empty($confirm)) {
                    $carclassmodelnsert = \DB::table('car_class_model')->insert($class_model);
                    if (!$carclassmodelnsert) {
                        DB::rollBack();
                    }
                }
            }*/
            DB::commit();
            return redirect('/carinventory/inventory')->with('success', trans('carinvnetory.carinvnetorycreateSuccess'));
        }
    }

    public function show($id, Request $request)
    {
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $inven      = CarInventory::findOrFail($id);
        $repair    = \DB::table('car_inspections as ins')
            ->leftJoin('car_inventory as inv', 'ins.inventory_id','=','inv.id')
            ->leftjoin('car_model as mo', 'mo.id', '=', 'inv.model_id')
            ->leftjoin('car_shop as sh', 'sh.id', '=', 'inv.shop_id')
            ->select(['ins.*','inv.shortname','mo.name as model_name','sh.name as shop_name'])
            ->where('ins.delete_flag', 0)
            ->where('ins.inventory_id', $id)
            ->orderby('ins.id', 'desc')->first();
        $models     = CarModel::all();
        $shops      = Shop::all();
        $shop_id    = 0;
        $dropoffs   = \DB::table('car_inventory_dropoff as dr')
            ->join('car_shop as sh', 'sh.id', '=', 'dr.shop_id')
            ->select(['dr.*' ,'sh.name as shop_name'])
            ->where('dr.inventory_id', $id)->get();
        $bookings = \DB::table('bookings as b')
            ->leftJoin('users as u', 'b.client_id', '=', 'u.id')
            ->leftJoin('profiles as p', 'b.client_id', '=', 'p.user_id')
            ->select('b.*', 'u.email', 'u.first_name', 'u.last_name', 'p.fur_first_name', 'p.fur_last_name')
            ->where('b.inventory_id','=',$id)
            ->orderBy('b.returning', 'desc')
            ->orderBy('b.id', 'desc')
            ->get();

        $repairs = \DB::table('car_inspections')
            ->where('delete_flag', 0)
            ->where('inventory_id', $id)
            ->orderBy('end_date', 'desc')
            ->get();

        $data = [
            'inven'         => $inven,
            'route'         => $route,
            'subroute'      => $subroute,
            'shops'         => $shops,
            'models'        => $models,
            'dropoffs'      => $dropoffs,
            'shop_id'       => $shop_id,
            'bookings'      => $bookings,
            'repair'        => $repair,
            'repairs'       => $repairs,
        ];

        return view('pages.admin.carinventory.show')->with($data);
    }

    public function reallocate(Request $request, $id) {
        $start_date = $request->get('start_date', '');
        $end_date = $request->get('end_date', '');
        $action = $request->get('action', '');

        if($id == '') {
            return back()->withErrors(['error'=> 'select inventory']);
        }

        if($start_date == '') {
            return back()->withErrors(['error'=> 'select start date']);
        }

        if($action == '') {
            return back()->withErrors(['error'=> 'wrong action']);
        }

        \DB::beginTransaction();
        if( $action == 'all' || $action == 'booking' ) {
            // reallocate bookings
            // 1. get all bookings of this inventory
            $bookings  = \DB::table('bookings')
                ->where('inventory_id', $id)
                ->whereNotIn('status', [8, 9])
                ->whereDate('departing', '>=', $start_date);
            if($end_date != ''){
                $bookings = $bookings->whereDate('departing', '<=', $end_date);
            }
            $bookings = $bookings->get();

            $moved_booking_count = 0;
            // 2. reallocate bookings
            foreach($bookings as $book) {
                $car_id         = $book->inventory_id;
                $car            = CarInventory::find($car_id);
                $departing      = date('Y-m-d', strtotime($book->departing));
                $returning      = date('Y-m-d', strtotime($book->returning));
                if($book->returning_updated != '0000-01-01 00:00:00') {
                    $returning = date('Y-m-d', strtotime($book->returning_updated));
                }
                $depart_shop    = $book->pickup_id;
                $class_id       = $book->class_id;
                $model_id       = $book->model_id;
                $smoking        = $car->smoke;
                $max_passenger  = $car->max_passenger;

//                $category = \DB::table('car_type_category')->where('name', '乗用車')->first();
//                if(!is_null($category)) $car_category = $category->id;

                $invens = \DB::table('car_class_model as cm')
                    ->join('car_class as c', 'c.id', '=', 'cm.class_id')
                    ->leftJoin('car_model as m', 'cm.model_id', '=', 'm.id')
                    ->leftJoin('car_type as t', 't.id', '=', 'm.type_id')
                    ->leftJoin('car_inventory as i', 'm.id', '=', 'i.model_id')
                    ->select('i.*')
                    ->where('cm.class_id', $class_id)
                    ->where('cm.model_id', $model_id)
                    ->where('i.id', '!=', $car_id)
                    ->where('i.shop_id', $depart_shop)
                    ->where('i.smoke', $smoking)
                    ->where('i.max_passenger', '>=', $max_passenger)
                    ->where('i.delete_flag', 0)
                    ->where('i.status', 1)
                    ->orderBy('i.max_passenger','asc')
                    ->orderBy('i.priority','asc')
                    ->get();

                if(count($invens) == 0) {
                    \DB::rollBack();
                    return
                        [
                            'success'   => false,
                            'error'     => 'can not move all bookings'
                        ];
                }

                // check if car is available
                $moved = false;
                foreach ($invens as $inven) {
                    $checkBook = ServerPath::getconfirmBooking($inven->id, $departing, $returning, '');
                    $checkInspection = ServerPath::getConfirmInspection($inven->id, $departing, $returning,'', '');
                    if($checkBook && $checkInspection){
                        // reallocate booking
                        \DB::table('bookings')->where('id', $book->id)->update(['inventory_id'=> $inven->id]);
                        $moved = true;
                        break;
                    }
                }
//                echo $departing.' '.$returning; return;
                if($moved == true) {
                    $moved_booking_count++;
                } else {
                    \DB::rollBack();
                    return
                        [
                            'success'   => false,
                            'error'     => 'can not move all bookings'
                        ];
                }
            }
        }

        if( $action == 'all' || $action == 'substitution') {
            // reallocate substitutions

            // 1. get all substitutions of this inventory
            $substs  = \DB::table('car_inspections')
                ->where('inventory_id', $id)
                ->where('status', 1)
                ->where('kind', '>', 1)
                ->where('delete_flag', 0)
                ->where('delete_flag', 0)
                ->whereDate('begin_date', '>=', $start_date);
            if($end_date != '') {
                $substs = $substs->whereDate('begin_date', '<=', $end_date);
            }
            $substs = $substs->get();

            $moved_substs_count = 0;

            // 2. reallocate inspections
            foreach($substs as $subst) {
                $car_id         = $subst->inventory_id;
                $car            = CarInventory::find($car_id);
                $begin          = $subst->begin_date;
                $end            = $subst->end_date;
                $shop_id        = $subst->shop_id;
                $class_id       = $subst->class_id;
                $model_id       = $car->model_id;
                $smoking        = $car->smoke;
                $max_passenger  = $car->max_passenger;

                $invens = \DB::table('car_class_model as cm')
                    ->join('car_class as c', 'c.id', '=', 'cm.class_id')
                    ->leftJoin('car_model as m', 'cm.model_id', '=', 'm.id')
                    ->leftJoin('car_type as t', 't.id', '=', 'm.type_id')
                    ->leftJoin('car_inventory as i', 'm.id', '=', 'i.model_id')
                    ->select('i.*')
                    ->where('cm.class_id', $class_id)
                    ->where('cm.model_id', $model_id)
                    ->where('i.id', '!=', $car_id)
                    ->where('i.shop_id', $shop_id)
                    ->where('i.smoke', $smoking)
                    ->where('i.delete_flag', 0)
                    ->where('i.status', 1)
                    ->where('i.max_passenger', '>=', $max_passenger)
                    ->orderBy('i.max_passenger','asc')
                    ->orderBy('i.priority','asc')
                    ->get();

                if(count($invens) == 0) {
                    \DB::rollBack();
                    return
                        [
                            'success'   => false,
                            'error'     => 'can not move all substitutions'
                        ];
                }

                // check if car is available
                $moved = false;
                foreach ($invens as $inven) {
                    $checkBook = ServerPath::getconfirmBooking($inven->id, $begin, $end, '');
                    $checkInspection = ServerPath::getConfirmInspection($inven->id, $begin, $end,'', '');
                    if($checkBook && $checkInspection){
                        // reallocate booking
                        $moved = \DB::table('car_inspections')->where('id', $subst->id)->update(['inventory_id'=> $inven->id]);
                        break;
                    }
                }
                if($moved == true) {
                    $moved_substs_count++;
                } else {
                    \DB::rollBack();
                    return
                        [
                            'success'   => false,
                            'error'     => 'can not move all substitutions'
                        ];
                }
            }
        }

        if($action == 'all') {
            // remove inspections
            $result = \DB::table('car_inspections')
                ->where('inventory_id', $id)
                ->where('kind', 1)  // kind = 1 => inspection
                ->delete();

            if(!$result) {
                \DB::rollBack();
                return
                    [
                        'success'   => false,
                        'error'     => 'failure to remove inspections'
                    ];

            }

            // inactive this car
            \DB::table('car_inventory')->where('id', $id)->update(['status'=>0]);
        }

        \DB::commit();

        return
            [
                'success'   => true,
                'action'    => $action,
                'error'     => [],
            ];
    }

    public function edit($id)
    {
        $route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $inven      = CarInventory::findOrFail($id);
        $models     = CarModel::all();
        $classes    = CarClass::all();
        $shops      = Shop::all();
        $shop_id    = 0 ; //for left menu
        $dropoffs   = \DB::table('car_inventory_dropoff as dr')
                        ->join('car_shop as sh', 'sh.id', '=', 'dr.shop_id')
                        ->select(['dr.*' ,'sh.name as shop_name'])
                        ->where('dr.inventory_id', $id)->get();
        $future_bookings = \DB::table('bookings as b')
            ->leftJoin('users as u', 'b.client_id', '=', 'u.id')
            ->leftJoin('profiles as p', 'b.client_id', '=', 'p.user_id')
            ->select('b.*', 'u.email', 'u.first_name', 'u.last_name', 'p.fur_first_name', 'p.fur_last_name')
            ->where('b.inventory_id','=',$id)
            ->whereNotIn('b.status', [8, 9])
            ->where(function($query) {
                $query
                    ->whereDate('b.departing', '>=', date('Y-m-d'))
                    ->orWhere( function($query1) {
                        $query1
                            ->whereDate('b.departing', '<', date('Y-m-d'))
                            ->where( function ($query2) {
                                $query2
                                    ->whereDate('b.returning', '>=', date('Y-m-d'))
                                    ->orWhere('b.returning_updated', '>=', date('Y-m-d 00:00:00'));

                            });
                    });
            })
            ->orderBy('b.departing', 'desc')
            ->get();

        $count_bookings = count($future_bookings);

        $count_inspections = \DB::table('car_inspections')
            ->where('inventory_id','=',$id)
            ->whereDate('begin_date', '>=', date('Y-m-d'))
            ->where('status', 1)
            ->where('kind', 1)
            ->count();

        $count_substitutions = \DB::table('car_inspections')
            ->where('inventory_id','=',$id)
            ->whereDate('begin_date', '>=', date('Y-m-d'))
            ->where('status', 1)
            ->where('kind', '>', 1)
            ->count();

        $data = [
            'inven'         => $inven,
            'route'         => $route,
            'subroute'      => $subroute,
            'shops'         => $shops,
            'models'        => $models,
            'dropoffs'      => $dropoffs,
            'shop_id'       => $shop_id,
            'classes'       => $classes,
            'bookings'      => $future_bookings,
            'inactivable'   => $count_bookings > 0,
            'booking_number'        => $count_bookings,
            'inspection_number'     => $count_inspections,
            'substitution_number'   => $count_substitutions,
        ];
        return view('pages.admin.carinventory.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $input = Input::except('_token','method','dropoff_ids','dropoff_id','_method');
        DB::beginTransaction();

        $carinventory = CarInventory::where('id',$id)->update($input);
        if(!$carinventory){
            DB::rollBack();
        }

        DB::commit();

        return back()->with('success', trans('carinventory.updateCareinvnetory'));
    }

    public function destroy($id, Request $request)
    {
        $carinventory = CarInventory::where('id',$id)->update(['delete_flag' => 1]);
//        $carinventory        = CarInventory::findOrFail($id);
//        \DB::transaction(function() use($carinventory, $id) {
//            $carinventory->delete();
//            $cardropoff = \DB::table('car_inventory_dropoff')->where('inventory_id', $id)->delete();
//        });
        return redirect('/carinventory/inventory/')->with('success', trans('carinventory.deleteCarinvnetory'));
    }

    /*
    * manage inventory priority in model
    */
    function priority(Request $request) {
        $input = Input::all();
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $shops          = Shop::all();
        $shop_id    = '0';
        if(!empty($input['shop_id'])) {
            $shop_id   = $input['shop_id'];
        }else {
            $shop_id   = $shops[0]->id;
        }

        $shop_model = [];
        $smoke_car = [];
        $nonsmoke_car = [];
        foreach ($shops as $shop) {
            $sid = $shop->id;
            $shop_name = $shop->name;
//            SELECT DISTINCT model_id, m.name FROM car_inventory AS i LEFT JOIN car_model AS m ON i.model_id=m.id WHERE shop_id=4
            $temps = \DB::table('car_inventory as i')
                ->leftJoin('car_model as m', 'i.model_id', '=', 'm.id')
                ->select(DB::raw('DISTINCT model_id, m.name'))
                ->where('i.shop_id', $sid)
                ->where('i.model_id', '>', 0)
                ->get();
            // check deleted models
            $models = [];
            foreach ($temps as $mod) {
                if(!is_null($mod->name) && !is_null($mod->model_id)) {
                    array_push($models, $mod);
                }
            }
            $shop_model[$sid] = $models;

            foreach ($models as $model) {
                $model_id = $model->model_id;
                $cars = \DB::table('car_inventory')
                    ->where('shop_id', $sid)
                    ->where('model_id', $model_id)
                    ->where('smoke', 1)
                    ->where('delete_flag', 0)
                    ->orderBy('priority')
                    ->get();
                $smoke_car[$sid][$model_id] = $cars;
                $cars = \DB::table('car_inventory')
                    ->where('shop_id', $sid)
                    ->where('model_id', $model_id)
                    ->where('delete_flag', 0)
                    ->where('smoke', 0)
                    ->orderBy('priority')
                    ->get();
                $nonsmoke_car[$sid][$model_id] = $cars;
            }
        }

        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'shops'     => $shops,
            'j_shops'   => \GuzzleHttp\json_encode($shops),
            'shop_id'   => $shop_id,
            'models'    => json_encode($shop_model),
            'smokes'    => json_encode($smoke_car),
            'nonsmokes' => json_encode($nonsmoke_car)
        ];
        return View('pages.admin.carinventory.priority')->with($data);
    }

    /*
    * manage inventory priority in model
    */
    function storepriority(Request $request)
    {
//        var_dump($_POST); return;

        $error = '';
        if (empty($request->input('shop_id'))) {
            $error = 'none shop id';
        } else {
            $shop_id = $request->input('shop_id');
        }
        if (empty($request->input('model_id'))) {
            $error = 'none model id';
        } else {
            $model_id = $request->input('model_id');
        }
        $smokes = explode(',', $request->input('smoke_orders'));
        $nonsmokes = explode(',', $request->input('nonsmoke_orders'));

        if ($error == '') {
            // save smoke orders
            $k = 0;
            foreach ($smokes as $smoke) {
                DB::table('car_inventory')
                    ->where([['shop_id', '=', $shop_id], ['model_id', '=', $model_id], ['smoke', '=', 1], ['id', '=', $smoke]])
                    ->update(['priority' => $k]);
                $k++;
            }

            // save nonsmoke orders
            $k = 0;
            foreach ($nonsmokes as $nonsmoke) {
                DB::table('car_inventory')
                    ->where([['shop_id', '=', $shop_id], ['model_id', '=', $model_id], ['smoke', '=', 0], ['id', '=', $nonsmoke]])
                    ->update(['priority' => $k]);
                $k++;
            }
            $error = 'モデルの車両優先度が更新されました。';
        }
        return back()->with('success', $error);
    }
}
