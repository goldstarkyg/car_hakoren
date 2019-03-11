<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarClassEquipment extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_class_equipment';

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
        'equipment_id',
        'class_id'
    ];
    /**
     * car option model
     */

    public function equipment()
    {
        return $this->belongsTo('App\Models\CarClass');
    }
}
