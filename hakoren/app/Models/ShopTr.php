<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ShopTr extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_shop_tr';

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
        'shop_id',
        'lang',
        'name',
        'Prefecture',
        'city',
        'address1',
        'address2',
        'comment',
    ];
    //car invnetory shop
    public function shop(){
        return $this->belongsTo('App\Models\Shop','shop_id');
    }

}
