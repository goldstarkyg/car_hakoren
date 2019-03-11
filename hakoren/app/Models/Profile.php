<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    	'id'
    ];

	/**
	 * Fillable fields for a Profile
	 *
	 * @var array
	 */
	protected $fillable = [
		'theme_id',
        'location',
		'bio',
		'twitter_username',
		'github_username',
        'user_profile_bg',
        'avatar',
        'avatar_status',
		'fur_first_name',
		'fur_last_name',
		'sex',
		'birth',
		'category_id',
        'phone',
		'postal_code',
		'prefecture',
		'city',
		'address1',
		'address2',
        'emergency_phone',
		'state',
		'country',
		'zip_code',
		'company_name',
		'company_psotal_code',
		'company_prefecture',
		'company_address1',
		'company_city',
		'company_address2',
		'credit_card_type',
		'credit_card_number',
		'credit_card_expiration',
		'credit_card_code',
		'license_surface',
		'license_back'		
	];

    protected $casts = [
        'theme_id' => 'integer',
    ];

	/**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function user()
	{
		return $this->belongsTo('App\Models\User','user_id');
	}

    /**
     * Profile Theme Relationships
     *
     * @var array
     */
    public function theme()
    {
        return $this->hasOne('App\Models\Theme');
    }

	public function usercategory()
	{
		return $this->belongsTo('App\Models\UserCategory','category_id');
	}
}