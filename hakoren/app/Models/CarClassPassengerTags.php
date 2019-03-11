<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarClassPassengerTags extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_class_passenger';

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
        'class_id',
        'passenger_tag'
    ];
    /**
     * car option model
     */

    public function passengertags()
    {
        return $this->belongsTo('App\Models\CarClass');
    }
}
