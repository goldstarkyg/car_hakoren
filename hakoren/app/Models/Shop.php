<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App;

class Shop extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_shop';

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
        'name',
        'name_en',
        'abbriviation',
        'slug',
        'thumb_path',
        'thumb_path_en',
        'phone',
        'postal',
        'postal_en',
        'Prefecture',
        'Prefecture_en',
        'city',
        'city_en',
        'address1',
        'address1_en',
        'address2',
        'address2_en',
        'member_id',
        'shop_number'
    ];
    //car invnetory shop
    public function carinventory(){
        return $this->hasOne('App\Model\CarInventory');
    }
    //car invnetory drop shop
    public function carinventorydrop(){
        return $this->hasMany('App\Model\CarInventoryDrop');
    }

    public function blogpost(){
        return $this->hasMany('App\Models\BlogPost','shop_id','id');
    }
//    public function shoptr(){
//        $lang = App::getLocale();
//        return $this->hasMany('App\Models\ShopTr','shop_id')->where('lang',$lang);
//    }
}
