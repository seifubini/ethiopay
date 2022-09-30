<?php

namespace App\Models;

use App\library\CommonFunction;
use App\Observers\UserObserver;
use Illuminate\Notifications\Notifiable;
use App\Notifications\UserResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use Notifiable;

use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'profile_picture', 'country_code', 'phone_code', 'phone_number', 'ethiopia_phone_code', 'ethiopia_phone_number', 'federal_tax_id'
    ];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'stripe_customer_id'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'fullname',
        'profile_picture_original', 'profile_picture_small', 'profile_picture_medium'
    ];

    //Register Observer
    public static function boot() {
        parent::boot();
        static::observe(UserObserver::class);
    }

    /**
     * Get the user's federal_tax_id.
     *
     * @param  string  $value
     * @return string
     */
    public function getFederalTaxIdAttribute($value) {
        return CommonFunction::decodeForFederalID($value);
    }

    /**
     * Set the user's federal_tax_id.
     *
     * @param  string  $value
     * @return void
     */
    public function setFederalTaxIdAttribute($value) {
        $this->attributes['federal_tax_id'] = CommonFunction::encodeForFederalID($value);
    }

    /**
     * Get the fullname.
     *
     * @return bool
     */
    public function getFullnameAttribute() {
        if (isset($this->attributes['firstname']) && !empty($this->attributes['firstname']) && isset($this->attributes['lastname']) && !empty($this->attributes['lastname'])) {
            return ucwords($this->attributes['firstname']) . ' ' . ucfirst($this->attributes['lastname']);
        }
        return '';
    }

    /**
     * Get original profile picture url.
     *
     * @return string
     */
    public function getProfilePictureOriginalAttribute() {
        if (isset($this->attributes['profile_picture']) && !empty($this->attributes['profile_picture'])) {
            if (file_exists(config('ethiopay.DOC_PATH.USER_PROFILE_ORIGINAL_DOC_PATH') . $this->attributes['profile_picture'])) {
                return config('ethiopay.WEB_PATH.USER_PROFILE_ORIGINAL_URL') . $this->attributes['profile_picture'];
            }
            return config('ethiopay.WEB_PATH.USER_DEFAULT_PROFILE_PICTURE_URL');
        }
        return config('ethiopay.WEB_PATH.USER_DEFAULT_PROFILE_PICTURE_URL');
    }

    /**
     * Get small profile picture url.
     *
     * @return string
     */
    public function getProfilePictureSmallAttribute() {
        if (isset($this->attributes['profile_picture']) && !empty($this->attributes['profile_picture'])) {
            if (file_exists(config('ethiopay.DOC_PATH.USER_PROFILE_SMALL_DOC_PATH') . $this->attributes['profile_picture'])) {
                return config('ethiopay.WEB_PATH.USER_PROFILE_SMALL_URL') . $this->attributes['profile_picture'];
            }
            return config('ethiopay.WEB_PATH.USER_DEFAULT_PROFILE_PICTURE_URL');
        }
        return config('ethiopay.WEB_PATH.USER_DEFAULT_PROFILE_PICTURE_URL');
    }

    /**
     * Get medium profile picture url.
     *
     * @return string
     */
    public function getProfilePictureMediumAttribute() {
        if (isset($this->attributes['profile_picture']) && !empty($this->attributes['profile_picture'])) {
            if (file_exists(config('ethiopay.DOC_PATH.USER_PROFILE_MEDIUM_DOC_PATH') . $this->attributes['profile_picture'])) {
                return config('ethiopay.WEB_PATH.USER_PROFILE_MEDIUM_URL') . $this->attributes['profile_picture'];
            }
            return config('ethiopay.WEB_PATH.USER_DEFAULT_PROFILE_PICTURE_URL');
        }
        return config('ethiopay.WEB_PATH.USER_DEFAULT_PROFILE_PICTURE_URL');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function addressData() {
        return $this->hasOne('App\Models\Address');
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new UserResetPassword($token, $this->attributes['email'], $this->attributes['firstname'], $this->attributes['id']));
    }

}
