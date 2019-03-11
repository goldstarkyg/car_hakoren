<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SimpleForm extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'simpleform';

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
        'firstname',
        'lastname',
        'email',
        'phone',
        'location',
        'message',
        'status_id',
        'username',
        'staff_id',
        'cartitle',
        'startdate',
        'enddate',
        'other_personal_info'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
     //   'status_id'
    ];


    public function simpleform_status()
    {
        return $this->hasOne('App\Models\SimpleFormStatus','id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User','id');
    }
}
