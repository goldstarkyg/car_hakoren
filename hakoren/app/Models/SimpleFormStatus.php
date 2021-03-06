<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SimpleFormStatus extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'simpleform_status';

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

    public function simpleform()
    {
        return $this->belongsTo('App\Models\SimpleForm');
    }
}