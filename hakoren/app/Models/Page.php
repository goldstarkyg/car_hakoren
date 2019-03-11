<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';

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
        'page_content',
        'meta_description',
        'meta_description_en',
        'og_tags',
        'meta_keywords',
        'meta_keywords_en',
        'meta_only',
        'featured_image',
        'featured_image_en'
    ];

}