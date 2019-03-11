<?php

namespace App\Http\Controllers;

use App\Models\SimpleForm;
use App\Models\SimpleFormStatus;
use App\Models\User;
use Faker\Provider\zh_TW\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests;
use File;
use Input;
use Excel;

class SimpleFormController extends Controller
{

    public $times = array("9:00","9:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","13:30",
                            "14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30");
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $route = Route::getFacadeRoot()->current()->uri();

        $status_id = $request->get('status_id', '1');
        $status_name = SimpleFormStatus::find($status_id);
        $location = $request->get('location', 'huku');
        $dateinterval = $request->get('dateinterval', '');
        $startdate = $request->get('startdate','');
        $enddate = $request->get('enddate','');

        $status_list = SimpleFormStatus::orderby('order', 'asc')->get();

        $simpleforms = \DB::table('simpleform as f')
            ->leftjoin('simpleform_status as s', 's.id', '=', 'f.status_id')
            ->leftjoin('users as u', 'u.id', '=', 'f.staff_id')
            ->select(['f.*', 's.name as status_name','s.alias as status_alias','u.name as staff_name']);

        if($status_id != '1'){
            if(!empty($status_name)) {
                if($status_name->alias == 'notstart') {
                    $simpleforms = $simpleforms->where(function($q) use ($status_id) {
                           $q->where('f.status_id', null)
                             ->orwhere('f.status_id',$status_id);
                    });
                }else {
                    $simpleforms = $simpleforms->where('f.status_id', $status_id);
                }
            }
        }
        if($location != ''){
            $simpleforms = $simpleforms->where('f.location', $location);
        }
        if($dateinterval != ''){
            $dates = explode(' - ', $dateinterval);
            $create_start = date('Y-m-d', strtotime($dates[0]));
            $create_end = date('Y-m-d', strtotime($dates[1]));
            $simpleforms = $simpleforms->where('f.created_at', '>=', $create_start)->where('f.created_at', '<=', $create_end);
        }
        if($startdate !='') {
            $startdate = date('Y-m-d', strtotime($startdate));
            $simpleforms = $simpleforms->where('f.startdate', '=', $startdate);
        }
        if($enddate !='') {
            $enddate = date('Y-m-d', strtotime($enddate));
            $simpleforms = $simpleforms->where('f.enddate', '=', $enddate);
        }

        $simpleforms = $simpleforms
            ->orderby('f.created_at', 'desc')->get();
        $data = [
            'route'         =>  $route  ,
            'status_list'   =>  $status_list ,
            'simpleforms'   =>  $simpleforms ,
            'dateinterval'  =>  $dateinterval ,
            'startdate'     =>  $startdate ,
            'enddate'       =>  $enddate ,
            'location'      =>  $location,
            'status_id'     =>  $status_id,
            'status_name'   =>  $status_name
        ];

        return View('pages.admin.simpleform.index')->with($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $route = Route::getFacadeRoot()->current()->uri();
        $status_id = $request->get('status_id', '1');
        $status_name = SimpleFormStatus::find($status_id);
        $location = $request->get('location', '');
        $dateinterval = $request->get('dateinterval', '');
        $startdate = $request->get('startdate','');
        $enddate = $request->get('enddate','');

        $status_list = \DB::table('simpleform_status')->orderby('order', 'asc')->get();
        $simpleforms = \DB::table('simpleform as f')
            ->leftjoin('simpleform_status as s', 's.id', '=', 'f.status_id')
            ->leftjoin('users as u', 'u.id', '=', 'f.staff_id')
            ->select(['f.*', 's.name as status_name','s.alias as status_alias','u.name as staff_name']);
        if($status_id != '1'){
            if(!empty($status_name)) {
                if($status_name->alias == 'notstart') {
                    $simpleforms = $simpleforms->where(function($q) use ($status_id) {
                        $q->where('f.status_id', null)
                            ->orwhere('f.status_id',$status_id);
                    });
                }else {
                    $simpleforms = $simpleforms->where('f.status_id', $status_id);
                }
            }
        }
        if($location != ''){
            $simpleforms = $simpleforms->where('f.location', $location);
        }
        if($dateinterval != ''){
            $dates = explode(' - ', $dateinterval);
            $create_start = date('Y-m-d', strtotime($dates[0]));
            $create_end = date('Y-m-d', strtotime($dates[1].' +1 day'));
            $simpleforms = $simpleforms->where('f.created_at', '>=', $create_start)->where('f.created_at', '<=', $create_end);
        }
        if($startdate !='') {
            $startdate = date('Y-m-d', strtotime($startdate));
            $simpleforms = $simpleforms->where('f.startdate', '=', $startdate);
        }
        if($enddate !='') {
            $enddate = date('Y-m-d', strtotime($enddate));
            $simpleforms = $simpleforms->where('f.enddate', '=', $enddate);
        }

        $simpleforms = $simpleforms
            ->orderby('f.created_at', 'desc')->get();
        $data = [
            'route'         =>  $route  ,
            'status_list'   =>  $status_list    ,
            'simpleforms'   =>  $simpleforms    ,
            'dateinterval'  =>  $dateinterval   ,
            'startdate'     =>  $startdate  ,
            'enddate'       =>  $enddate    ,
            'location'      =>  $location   ,
            'status_id'     =>  $status_id,
            'status_name'   =>  $status_name
        ];

        return View('pages.admin.simpleform.index')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $route = Route::getFacadeRoot()->current()->uri();
        $simpleform = SimpleForm::findOrFail($id);
        $status_list = SimpleFormStatus::orderby('order', 'asc')->get();
        $simpleform_status = SimpleFormStatus::orderBy('order', 'asc')->get();
        $staff = User::orderBy('id','asc')->get();
        $status = '';
        $user_name = '';
        if(!empty($simpleform->simpleform_status)) $status = $simpleform->simpleform_status->name;
        if(!empty($simpleform->user)) $user_name = $simpleform->user->name;
        $data = [
            'simpleform'    => $simpleform,
            'status_list'   => $status_list,
            'location'      => $simpleform->location,
            'status'        => $status,
            'simpleform_status' => $simpleform_status,
            'user_name'     => $user_name,
            'staffs'        => $staff,
            'route'         => $route,
            'times'         => $this->times
        ];

        return view('pages.admin.simpleform.edit')->with($data);
    }

    /*
     * update
     */
    public function update(Request $request)
    {
        $input = Input::except('_method','_token');
        $id = Input::only('id');
        if($affectedRows = SimpleForm::where('id',$id)->update($input)) {
            return back()->with('success', trans('simpleform.updateSuccess'));
        }else {
            return back()->with('error', trans('simpleform.errorNotsave'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $route = Route::getFacadeRoot()->current()->uri();
        $simpleform = SimpleForm::findOrFail($id);
        $status_list = SimpleFormStatus::orderby('order', 'asc')->get();
        $simpleform_status = SimpleFormStatus::orderBy('order', 'asc')->get();
        $staff = User::orderBy('id','asc')->get();
        $status = '';
        if(!empty($simpleform->simpleform_status)) $status = $simpleform->simpleform_status->name;
        $user_name = '';
        if(!empty($simpleform->user)) $user_name = $simpleform->user->name;
        $data = [
            'simpleform' => $simpleform,
            'status_list'   => $status_list,
            'location'   => $simpleform->location,
            'status'     => $status,
            'simpleform_status' => $simpleform_status,
            'user_name'  => $user_name,
            'staff'      => $staff,
            'route'      => $route
        ];

        return view('pages.admin.simpleform.detail')->with($data);
    }
   /**
    * export csv
    */
    public function getExportData(){
        $simpleforms = SimpleForm::orderBy('id','asc')->get();

        $data = array();
        foreach($simpleforms as $form){
            $location = ' 福岡空港店';
            if($form->location == 'huku'){
                $location = ' 福岡空港店';
            }elseif($form->location == 'okina'){
                $location = ' 那覇空港店';
            }
            $status_name = '';
            if(!empty( $form->simpleform_status)) $status_name = $form->simpleform_status->name ;
            $staff_name = '';
            if(!empty( $form->user)) $staff_name = $form->user->name ;
            $id         = str_pad($form->id, 6, '0', STR_PAD_LEFT);
            $created    = date_format(new \DateTime($form->created_at),'Y-m-d');
            $username   = $form->firstname." ".$form->lastname;
            $email      = $form->email;
            $phone      = $form->phone;
            $message    = $form->message;
            $staffname  = $staff_name;
            $carname   = $form->cartitle;
            $car_id     = $form->car_id;
            $startdate  = $form->startdate;
            $starttime  = $form->starttime;
            $enddate    = $form->enddate;
            $endtime    = $form->endtime;
            $date_str   = $startdate." ".$starttime."~".$enddate." ".$endtime;

            $updated_at = $form->updated_at;
            $other_personal_info = $form->other_personal_info;
            $show_memo  = $form->show_memo;
            $car_option_service = $form->car_option_service;
            $status     = $status_name;
            $data[] = array($id, $created, $username, $location ,$email, $phone,$message,  $carname, $car_id, $date_str, $status."/".$staffname,
                $other_personal_info,$show_memo,$car_option_service);
        }
        $headers = array('ID', '送信日', '氏名', '店舗','Email' ,'Phone', 'Message',  '車種', 'Car ID', '出発/返却','状態','Other','Memo','Service');
        $filename = date('YmdHi').'_simpleformlist';
        $data = array_merge(array($headers), $data);
        Excel::create($filename, function($excel) use($data) {
            $excel->sheet('userlist', function($sheet) use($data) {
                $sheet->fromArray($data);
            });
        })->export('xls');

        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $form = SimpleForm::findOrFail($id);

        if (!empty($form)) {
            $form->delete();
            return redirect('simpleform')->with('success', trans('simpleform.deleteSuccess'));
        }
        return back()->with('error', trans('simpleform.deleteSelfError'));
    }
}
