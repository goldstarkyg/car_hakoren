<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Response;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Http\DataUtil\ServerPath;
use DB;
use Auth;
use Validator;

class BlogTagsController extends Controller
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
        $tagdata      = BlogTag::all();
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'tagdata'     => $tagdata
        ];
        return View('pages.admin.blogtag.index')->with($data);
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
        return view('pages.admin.blogtag.create')->with($data);
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
				'slug'              => 'required|alpha_dash|unique:blog_tags,slug'
            ] 
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
				
            $blogtag = BlogTag::create($input);
            $blogtag->save();
            return redirect('/adminblog/blogtags')->with('success');
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
        $blogtag	= BlogTag::findOrFail($id);
        $data = [
            'blogtag'  => $blogtag,
            'route'         => $route,
            'subroute'      => $subroute            
        ];		
        return view('pages.admin.blogtag.show')->with($data);
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
        $blogtag	= BlogTag::findOrFail($id);
        $data = [
            'blogtag'  => $blogtag,
            'route'         => $route,
            'subroute'      => $subroute            
        ];		
        return view('pages.admin.blogtag.edit')->with($data);
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
				'slug'              => 'required|alpha_dash|unique:blog_tags,slug'.','.$id
            ] 
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {		
		
		$blogtag 		 = BlogTag::find($id);
		$blogtag->title = $input['title'];
		$blogtag->slug  = $input['slug'];	
		$blogtag->is_popular_tag  = array_key_exists("is_popular_tag",$input)?1:0;		
        $blogtag->save();	
		
        return redirect('/adminblog/blogtags')->with('success');
		
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
        $shop       = BlogTag::findOrFail($id);
 
        //delete featured thumbnail
        $shop->delete();
        return redirect('/adminblog/blogtags')->with('success');
    }
}
