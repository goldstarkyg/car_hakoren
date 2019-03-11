<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarOption extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_option';

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
        'name_en',
        'price',
        'charge_system',
        'max_number',
        'google_column_number',
        'type',
        'order'
    ];
    //get model list about car option
    public function carOptionClass()
    {
        return $this->hasMany('App\Models\CarOptionClass','option_id');
    }
    //get getshop list about car option
    public function carOptionShop()
    {
        return $this->hasMany('App\Models\CarOptionShop','option_id');
    }


}
