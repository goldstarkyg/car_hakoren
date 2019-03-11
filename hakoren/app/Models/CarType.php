<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarType extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_type';

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
        'abbriviation',
        'name',
        'category_id'
    ];

    //get car type
    public function cartype() {
        return $this->hasOne('App\Models\CarModel');
    }
}
