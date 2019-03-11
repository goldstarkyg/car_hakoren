<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Response;

use App\Models\Shop;
use App\Models\BlogTag;
use App\Models\PostTag;
use App\Models\BlogPost;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Http\DataUtil\ServerPath;
use DB;
use Auth;
use Validator;

class BlogPostsController extends Controller
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
        $tagdata      = BlogPost::all();
        $data = [
            'route'     => $route,
            'subroute'  => $subroute,
            'tagdata'     => $tagdata
        ];
        return View('pages.admin.blogpost.index')->with($data);
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
        
		$shop    = Shop::orderBy('name','asc')
										->pluck('name','id')
										->all();
		$blogtagdata   	 = BlogTag::orderBy('title','asc')
										->pluck('title','id')
										->all();
		$posttagdata   	 = PostTag::orderBy('title','asc')
										->pluck('title','id')
										->all();			
		
		$data = [
            'route'     => $route,
            'subroute'  => $subroute,   
			'shop' => $shop,
			'blogtagdata'  => $blogtagdata,
			'posttagdata'  => $posttagdata       
        ];	
        return view('pages.admin.blogpost.create')->with($data);
		
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
				'slug'              => 'required|alpha_dash|unique:blog_posts,slug',
				'publish_date'      => 'required',
				'featured_image'    => 'required|image|mimes:jpeg,jpg,bmp,png|max:2048',
				 
				'meta_description'  => 'required',
				'post_content'      => 'required',
				'shop_id'  => 'required|exists:car_shop,id',
				'post_tag_id' 		=> 'required|exists:post_tags,id',
				'blog_tag_id'		=> 'required|exists:blog_tags,id',	
            ] 
        );

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {
 	
            $blogpost = BlogPost::create($input);
			$id = $blogpost->id;
            /////////////////
            if($thumbfile   = $request->file('featured_image')) {
                $fileName 	= $thumbfile->getClientOriginalName();
                $extension 	= $thumbfile->getClientOriginalExtension();
                $folderName = '/images/blog/';
                $destinationPath = public_path() . $folderName;
                $safeName = 'blog_'.$id.'.' . $extension;
                $thumbfile->move($destinationPath, $safeName);
                $thumb_path = $folderName.$safeName;
                $postupdate = DB::table('blog_posts')
                    ->where('id', $id)
                    ->update(['featured_image' => $thumb_path]);
            }
			$blogpost->blogtag()->sync($input['blog_tag_id']);
            return redirect('/adminblog/blogposts')->with('success');
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
        $blogpost	= BlogPost::with('shop')->where('id', $id)->first();
        $data = [
            'blogpost'      => $blogpost,
            'route'         => $route,
            'subroute'      => $subroute            
        ];		
        return view('pages.admin.blogpost.show')->with($data);
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $route       = ServerPath::getRoute();
        $subroute    = ServerPath::getSubRoute();
        $blogpost	 = BlogPost::findOrFail($id);
 		$blog_tag_id = array();
		foreach($blogpost->blogtag as $tag) {
			 $blog_tag_id[] = $tag['id'];
		}
		$blogpost->blog_tag_id = $blog_tag_id;		

		$shop    = Shop::orderBy('name','asc')
										->pluck('name','id')
										->all();
		$blogtagdata   	 = BlogTag::orderBy('title','asc')
										->pluck('title','id')
										->all();
		$posttagdata   	 = PostTag::orderBy('title','asc')
										->pluck('title','id')
										->all();
		
		$data = [
            'route'        => $route,
            'subroute'     => $subroute,  
			'blogpost'     => $blogpost, 
			'shop' => $shop,
			'blogtagdata'  => $blogtagdata,
			'posttagdata'  => $posttagdata      
        ];		
 		
        return view('pages.admin.blogpost.edit')->with($data);
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
		$rules			 =	[
								'title'             => 'required',
                                'title_en'          => 'required',
								'slug'              => 'required|alpha_dash|unique:blog_posts,slug'.','.$id,
								'publish_date'      => 'required',								   
								 
								'meta_description'  => 'required',
								'post_content'      => 'required',
								'shop_id'  => 'required|exists:car_shop,id',
								'post_tag_id' 		=> 'required|exists:post_tags,id',
								'blog_tag_id'		=> 'required|exists:blog_tags,id',				
							];
		 if($request->file('featured_image')) {
			 $rules['featured_image']  = 'image|mimes:jpeg,jpg,bmp,png|max:2048';
		 }
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {		
		
		$blogpost 		 = BlogPost::find($id);
        if($thumbfile = $request->file('featured_image')) {
            $fileName  = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/images/blog/';
            $destinationPath = public_path() . $folderName;
            $safeName = 'blog_'.$id.'.' . $extension;
            $thumbfile->move($destinationPath, $safeName);
            $thumb_path = $folderName.$safeName;
            $blogpost->featured_image = $thumb_path;
        }
		$blogpost->title = $input['title'];
        $blogpost->title_en = $input['title_en'];
		$blogpost->slug  = $input['slug'];	
		
		$blogpost->publish_date = $input['publish_date'];
		$blogpost->post_content  = $input['post_content'];	
		$blogpost->meta_description = $input['meta_description'];
		 	
		$blogpost->shop_id = $input['shop_id'];
		$blogpost->post_tag_id  = $input['post_tag_id'];					
			
        $blogpost->save();	
		$blogpost->blogtag()->sync($input['blog_tag_id']);
		
        return redirect('/adminblog/blogposts')->with('success');
		
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
        $shop       = BlogPost::findOrFail($id);
 		$thumb_path     = $shop->featured_image;
        //delete featured thumbnail
        $shop->delete();
        if($thumb_path != null) {
            $path = public_path() . $thumb_path;
            unlink($path);
        }		
        return redirect('/adminblog/blogposts')->with('success');
    }
}
