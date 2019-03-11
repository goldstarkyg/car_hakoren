<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarClass extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_class';

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
        'abbriviation',
        'description',
        'staff_comment',
        'status',
        'price_1d',
        'price_1n_2d',
        'price_2n_3d',
        'price_3n_4d',
        'price_ad_d',
        'cu_price_1d',
        'cu_price_1n_2d',
        'cu_price_2n_3d',
        'cu_price_3n_4d',
        'cu_price_ad_d',
        'car_class_priority',
        'car_shop_name'
    ];

    //get models
    //get model list about car option
    public function carClassModel()
    {
        return $this->hasMany('App\Models\CarClassModel','class_id');
    }


    //get car option
    public function carClassOption()
    {
        return $this->hasMany('App\Models\CarOptionClass','class_id');
    }

    public function option()
    {
        return $this->hasManyThrough('App\Models\CarOption','App\Models\CarOptionClass','class_id','id');
    }

    //get car insurance
    public function carClassInsurance()
    {
        return $this->hasMany('App\Models\CarClassInsurance','class_id');
    }
    //get car insurance
    public function carClassEquipment()
    {
        return $this->hasMany('App\Models\CarClassEquipment','class_id');
    }
    //get car passenger tags
    public function carClassPassengerTags()
    {
        return $this->hasMany('App\Models\CarClassPassengerTags','class_id');
    }

	// get all the car class options associated with a car class
    public function classOptions()
    {
        return $this->belongsToMany('App\Models\CarOption','car_option_class', 'class_id', 'option_id');
    }

}
