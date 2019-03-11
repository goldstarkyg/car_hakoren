<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'blog_posts';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
	 
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [		
		'title',
        'title_en',
		'slug',
		'publish_date',
		'featured_image',
		'post_content',
		'meta_description',
		 
		'shop_id',
		'post_tag_id',
        'created_at',
        'updated_at'
    ];
      
    // 
    public function shop(){
        return $this->belongsTo('App\Models\Shop','shop_id','id');
    }
	
    // 
    public function posttag(){
        return $this->belongsTo('App\Models\PostTag','post_tag_id','id');
    }
	
    // 
    public function blogtag(){
		return $this->belongsToMany('App\Models\BlogTag','post_tags_rel','post_id','blog_tag_id');
    }		
		  
}