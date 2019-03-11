<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repairshops extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'repairshops';

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
        'shopname',
        'shortname',
        'shop_kind',
        'delete_flag',
        'created_at',
    ];
}
