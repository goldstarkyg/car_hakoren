<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarModel extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_model';

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
        'thumb_path',
        'category_id',
        'type_id',
        'vendor_id',
//        'priority',
//        'passengers',
        'luggages',
        'doors',
        'transmission',
        'smoking',
        'selected_by_class'
    ];

    //get type
    public function type()
    {
        return $this->belongsTo('App\Models\CarType');
    }

    //get vendor
    public function vendor()
    {
        return $this->belongsTo('App\Models\CarVendor');
    }

    //get vendor
    public function category()
    {
        return $this->belongsTo('App\Models\CartypeCategory');
    }

    public function carinventory(){
        return $this->hasOne('App\Model\CarInventory');
    }
}
