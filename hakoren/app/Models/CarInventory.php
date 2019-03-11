<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarInventory extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_inventory';

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
        'model_id',
        'class_id',
        'numberplate1',
        'numberplate2',
        'numberplate3',
        'numberplate4',
        'shortname',
        'priority',
        'shop_id',
        'smoke',
        'current_mileage',
        'max_passenger',
        'dropoff_availability',
        'status'
    ];

    //get class
//    public function carclass()
//    {
//        return $this->belongsTo('App\Models\CarClass', 'class_id');
//    }

    //get model
    public function model()
    {
        return $this->belongsTo('App\Models\CarModel');
    }

    //get shop
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }

    //get drop
    public function dropshop()
    {
        return $this->belongsTo('App\Models\CarInventoryDrop');
    }

}
