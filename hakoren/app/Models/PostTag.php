<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post_tags';

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
        'created_at',
        'updated_at'
    ];
      
    public function blogpost(){
        return $this->hasMany('App\Models\BlogPost','blog_post_id','id');
    }
		  
}
