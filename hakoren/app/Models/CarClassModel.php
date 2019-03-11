<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CarClassModel extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_class_model';

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
        'model_id',
        'priority'
    ];
    /**
     * car option model
     */

    public function carclass()
    {
        return $this->belongsTo('App\Models\CarClass');
    }
}
