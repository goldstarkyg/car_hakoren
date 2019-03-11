<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CartypeCategory extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_type_category';

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
        'alias'
    ];

    //get car model
    public function carmodel() {
        return $this->hasOne('App\Models\CarModel');
    }
    //get car type
    public function cartype() {
        return $this->hasOne('App\Models\CarType');
    }
}
