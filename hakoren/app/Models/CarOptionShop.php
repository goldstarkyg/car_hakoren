<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarOptionShop extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_option_shop';

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
        'option_id',
        'shop_id',
        'option_count',
        'price'
    ];
    /**
     * car option model
     */
    public function caroption()
    {
        return $this->belongsTo('App\Models\CarOption');
    }
}
