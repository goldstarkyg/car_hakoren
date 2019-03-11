<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Response;
use App\Models\PostTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Http\DataUtil\ServerPath;
use DB;
use Auth;
use Validator;

class PostTagsController extends Controller
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
        $tagdata      = PostTag::all();
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'tagdata'     => $tagdata
        ];
        return View('pages.admin.posttag.index')->with($data);
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
            'route'     => $route,
            'subroute'  => $subroute,          
        ];
        return view('pages.admin.posttag.create')->with($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
		$input		     = $request->all();
		$input['slug']   = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $input['slug'])));
        $validator = Validator::make($input,
            [
                'title'             => 'required',
                'title_en'             => 'required',
				'slug'              => 'required|alpha_dash|unique:post_tags,slug'
            ] 
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
				
            $posttag = PostTag::create($input);
            $posttag->save();
            return redirect('/adminblog/posttags')->with('success');
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
		$route      = ServerPath::getRoute();
        $subroute   = ServerPath::getSubRoute();
        $posttag	= PostTag::findOrFail($id);
        $data = [
            'posttag'  => $posttag,
            'route'         => $route,
            'subroute'      => $subroute            
        ];		
        return view('pages.admin.posttag.show')->with($data);
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
        $posttag	= PostTag::findOrFail($id);
        $data = [
            'posttag'  => $posttag,
            'route'         => $route,
            'subroute'      => $subroute            
        ];		
        return view('pages.admin.posttag.edit')->with($data);
    }


    /**
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function update(Request $request, $id)
    { 	 
		$input		     = $request->all();
		$input['slug']   = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $input['slug'])));	
        $validator = Validator::make($input,
            [
                'title'             => 'required',
                'title_en'             => 'required',
				'slug'              => 'required|alpha_dash|unique:post_tags,slug'.','.$id
            ] 
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {		
		
		$posttag 		 = PostTag::find($id);
		$posttag->title = $input['title'];
        $posttag->title_en = $input['title_en'];
		$posttag->slug  = $input['slug'];		
        $posttag->save();	
		
        return redirect('/adminblog/posttags')->with('success');
		
		}
    }
 
       
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $shop       = PostTag::findOrFail($id);
 
        //delete featured thumbnail
        $shop->delete();
        return redirect('/adminblog/posttags')->with('success');
    }
}
