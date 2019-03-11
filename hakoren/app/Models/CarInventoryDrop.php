<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarInventoryDrop extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_inventory_dropoff';

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
        'inventory_id'
    ];

    //get shop
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }
    //get inventory
    public function inventory()
    {
        return $this->belongsTo('App\Models\CarInventory');
    }
}
