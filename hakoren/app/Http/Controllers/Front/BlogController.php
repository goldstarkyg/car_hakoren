<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Session;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\PostTag;
use App\Models\Shop;
use App\Http\DataUtil\ServerPath;
use DateTime;

class BlogController extends Controller
{

    // Page showing all the posts related to this category
    public function index(Request $request){
		$inputs	    		= array();	
		$perPage 			= 8;
		$input_array 	    = $request->all();
		$all_inputs 	    = array_map('trim', $input_array);
		
		$posts   	  = BlogPost::orderBy('updated_at','desc')->paginate($perPage);					  
		$recent_post  = BlogPost::orderBy('post_views','desc')->take(3)->get();
		$all_tags     = BlogTag::where('is_popular_tag', 1)->get(); 
		 		 		 
		return view('blog.postindex',compact('posts','all_inputs','recent_post','all_tags'));		
	}
	
    // Page showing all the posts related to this category
    public function categoryindex($category, Request $request){
		
		$inputs	    		= array();	
		$perPage 			= 8;
		$input_array 	    = $request->all();
		$all_inputs 	    = array_map('trim', $input_array);
		
		$posts   	  = BlogPost::whereHas('shop', function($query) use ($category) {
						 	$query->whereRaw(" slug = '".$category."' "); 
					    })->orderBy('updated_at','desc')->paginate($perPage);					  
		$recent_post  = BlogPost::orderBy('post_views','desc')->take(3)->get();
		$all_tags     = BlogTag::where('is_popular_tag', 1)->get(); 
		$shop = Shop::where('slug', $category)->first();				 
		return view('blog.categoryindex',compact('posts','all_inputs','recent_post','all_tags','shop'));
		
	}

    // Page showing all the posts related to this article tag
    public function articletagindex($category,$articletag, Request $request) {
		
		$inputs	    		= array();	
		$perPage 			= 8;
		$input_array 	    = $request->all();
		$all_inputs 	    = array_map('trim', $input_array);		 
		$posts   	  = BlogPost::whereHas('shop', function($query) use ($category) {
						 	$query->whereRaw(" slug = '".$category."' "); 
					    })->whereHas('posttag', function($query) use ($articletag) {
						 	$query->whereRaw(" slug = '".$articletag."' "); 
					    })->orderBy('updated_at','desc')->paginate($perPage);					  
		$recent_post  = BlogPost::orderBy('post_views','desc')->take(3)->get();
		$all_tags     = BlogTag::where('is_popular_tag', 1)->get(); 	
		$articletag   = PostTag::where('slug', $articletag)->first(); 
		$shop = Shop::where('slug', $category)->first();		 
		return view('blog.articletagindex',compact('posts','all_inputs','recent_post','all_tags','articletag','shop'));
		
	}

    // Page showing all the posts related to this tag
    public function tagindex($tag, Request $request) {
		$inputs	    		= array();	
		$perPage 			= 8;
		$input_array 	    = $request->all();
		$all_inputs 	    = array_map('trim', $input_array);
		
		$posts   	  = BlogPost::whereHas('blogtag', function($query) use ($tag) {
						 	$query->whereRaw(" slug = '".$tag."' "); 
					    })->orderBy('updated_at','desc')->paginate($perPage);
					  
		$recent_post  = BlogPost::orderBy('post_views','desc')->take(3)->get();
		$all_tags     = BlogTag::where('is_popular_tag', 1)->get(); 		  
					  
		$blogtag      = BlogTag::where('slug',$tag)->first();		 		 
		return view('blog.tagindex',compact('posts','blogtag','all_inputs','recent_post','all_tags'));		
	}
	
	// View the Blog post/article
	public function view($slug) {
		 		
		$postinfo 	= BlogPost::where('slug','=',$slug)->first();		
		$posts   	= BlogPost::where('shop_id', $postinfo->shop_id)
								->where('id','!=', $postinfo->id)
								->orderBy('updated_at','desc')
								->take(2)
								->get();
								
		$recent_post  = BlogPost::orderBy('post_views','desc')->take(3)->get();
		$all_tags     = BlogTag::where('is_popular_tag', 1)->get(); 
		
		
		// Updte count
		$post_views     	   = ($postinfo->post_views + 1);
		$postinfo->post_views  = $post_views;
        $postinfo->save();				
		
		return View('blog.post', compact('postinfo','posts','recent_post','all_tags'));	
	}


	// View all news
    public function newslist(Request $request){
		$inputs	    		= array();	
		$perPage  = 8;
		$input_array 	    = $request->all();
		$all_inputs 	    = array_map('trim', $input_array);
		
		// post_tag_id 12 == News
		$posts   	  = BlogPost::with('blogtag')
								->where('post_tag_id', 2)
								->orderBy('updated_at','desc')
								->paginate($perPage);		 		 		 
		return view('blog.newslist',compact('posts','all_inputs'));		
	}

}