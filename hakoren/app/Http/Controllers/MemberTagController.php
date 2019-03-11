<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Tag;
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

class MemberTagController extends Controller
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
        $tag_list = \DB::table('tag_list')->orderby('id', 'desc')->get();

        $data = [
            'route' => $route,
            'subroute' => $subroute,
            'tag_list' => $tag_list
        ];
        return view('pages.admin.member.tag-index')->with($data);
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

        return view('pages.admin.member.tag-create')->with($data);
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
                'abbriviation.required'   => trans('tag.abbriviationrequired'),
                'name.required'    => trans('tag.namerequired')
            ]
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
            $tag = Tag::create([
                'abbriviation' => $request->input('abbriviation'),
                'name' => $request->input('name')
            ]);
            $tag->save();
            return redirect('/tag')->with('success', trans('tag.createSuccess'));
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

        $tag   = Tag::find($id);
        $route          = ServerPath::getRoute();
        $subroute       = ServerPath::getSubRoute();
        $data = [
            'route' => $route,
            'subroute' => $subroute,
            'tag'   => $tag
        ];
        return view('pages.admin.member.tag-create')->with($data);
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
        $tag        = Tag::findOrFail($id);

        $data = [
            'tag'       => $tag,
            'route'     => $route,
            'subroute'  => $subroute
        ];
        return view('pages.admin.member.tag-edit')->with($data);
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

        $tag    = Tag::find($id);
        $tag->abbriviation  = $request->input('abbriviation');
        $tag->name          = $request->input('name');
        $tag->status        = $request->input('status');
        $tag->save();
        return back()->with('success', trans('tag.updateTag'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $tag        = Tag::findOrFail($id);
        $tag->delete();
        return redirect('/tag')->with('success', trans('tag.deleteTag'));


    }
}
