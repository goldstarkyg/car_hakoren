<?php

namespace App\Http\Controllers\Front;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Input;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		$pages = Page::all();
        return View('pages.admin.webpage.index')->with("pages",$pages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
				'slug'              => 'required|alpha_dash|unique:blog_posts,slug',
				'featured_image'    => 'required|image|mimes:jpeg,jpg,bmp,png|max:2048',
				'page_content'      => 'required',
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
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function showDynamicPage($slug,Page $page)
    {
		$noindex = 1;
		$slug = $slug;
		$page = $page->where('slug',$slug)->first();
		$meta_info = $page;
		//dd($page);
        return view("pages.frontend.dynamicPage")->with('page',$page)->with('noindex',$noindex)->with('meta_info',$meta_info);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Page $page)
    {
        //
		$page = Page::where("id",$id)->first();
		$meta_only = $page->meta_only;
        return view("pages.admin.webpage.edit")->with('page',$page)->with('meta_only',$meta_only);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 	 
		$input		     = $request->all();
		$page = Page::where('id',$id)->first();
		if($page->meta_only == 0){
			$input['slug']   = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $input['slug'])));	
		}
		$rules			 =	[
								'slug'              => 'alpha_dash|unique:blog_posts,slug'.','.$id,
								'page_content'      => '',		
							];
		 if($request->file('featured_image')) {
			 $rules['featured_image']  = 'image|mimes:jpeg,jpg,bmp,png|max:2048';
		 }
        if($request->file('featured_image_en')) {
            $rules['featured_image_en']  = 'image|mimes:jpeg,jpg,bmp,png|max:2048';
        }
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

        } else {		
		
		$page		 = Page::find($id);
        if($thumbfile = $request->file('featured_image')) {
            $fileName  = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/images/page/';
            $destinationPath = public_path() . $folderName;
            $safeName = 'page_'.$id.'.' . $extension;
            $thumbfile->move($destinationPath, $safeName);
            $thumb_path = $folderName.$safeName;
            $page->featured_image = $thumb_path;
        }
        if($thumbfile = $request->file('featured_image_en')) {
            $fileName  = $thumbfile->getClientOriginalName();
            $extension = $thumbfile->getClientOriginalExtension();
            $folderName = '/images/page/';
            $destinationPath = public_path() . $folderName;
            $safeName = 'page_en_'.$id.'.' . $extension;
            $thumbfile->move($destinationPath, $safeName);
            $thumb_path = $folderName.$safeName;
            $page->featured_image_en = $thumb_path;
        }
		if($page->meta_only == 0){
			$page->title = $input['title'];
            $page->title_en = $input['title_en'];
			$page->slug  = $input['slug'];	
			$page->page_content  = $input['page_content'];
		}	
		$page->meta_description     = $input['meta_description'];
		$page->meta_keywords        = $input['meta_keywords'];
        $page->meta_description_en  = $input['meta_description_en'];
        $page->meta_keywords_en     = $input['meta_keywords_en'];
			
        $page->save();	
		
        return redirect('/adminpage/webpages')->with('success');
		
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Page $page)
    {
        $page       = Page::findOrFail($id);
 		$thumb_path     = $page->featured_image;
        $thumb_path_en  = $page->featured_image_en;
        //delete featured thumbnail
        $page->delete();
        if($thumb_path != null) {
            $path = public_path() . $thumb_path;
            unlink($path);
            $path_en = public_path() . $thumb_path_en;
            unlink($path_en);
        }		
        return redirect('/adminpage/webpages')->with('success');
    }
}
