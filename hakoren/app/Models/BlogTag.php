<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'blog_tags';

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
        'slug',  
		'is_popular_tag',       
        'created_at',
        'updated_at'
    ];
      
 
		  
}
