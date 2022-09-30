<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model {

    use SoftDeletes;

    protected $table = 'payment_methods';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $hidden = ['stripe_card_id', 'stripe_card_fingerprint'];
    protected $dates = ['deleted_at'];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'payment_method_icon'
    ];

    /**
     * Get original profile picture url.
     *
     * @return string
     */
    public function getPaymentMethodIconAttribute() {
        if (isset($this->attributes['method_type']) && $this->attributes['method_type'] == 'card' && isset($this->attributes['card_type']) && !empty($this->attributes['card_type'])) {
            if ($this->attributes['card_type'] == 'American Express') {
                return config('ethiopay.WEB_PATH.ABSOLUTE_URL') . 'img/credit-card/American-Express.png';
            } elseif ($this->attributes['card_type'] == 'Diners Club') {
                return config('ethiopay.WEB_PATH.ABSOLUTE_URL') . 'img/credit-card/Diners-Club.png';
            } elseif ($this->attributes['card_type'] == 'Discover') {
                return config('ethiopay.WEB_PATH.ABSOLUTE_URL') . 'img/credit-card/Discover.png';
            } elseif ($this->attributes['card_type'] == 'JCB') {
                return config('ethiopay.WEB_PATH.ABSOLUTE_URL') . 'img/credit-card/JCB.png';
            } elseif ($this->attributes['card_type'] == 'MasterCard') {
                return config('ethiopay.WEB_PATH.ABSOLUTE_URL') . 'img/credit-card/MasterCard.png';
            } elseif ($this->attributes['card_type'] == 'UnionPay') {
                return config('ethiopay.WEB_PATH.ABSOLUTE_URL') . 'img/credit-card/UnionPay.png';
            } elseif ($this->attributes['card_type'] == 'Visa') {
                return config('ethiopay.WEB_PATH.ABSOLUTE_URL') . 'img/credit-card/Visa.png';
            } elseif ($this->attributes['card_type'] == 'Unknown') {
                return config('ethiopay.WEB_PATH.ABSOLUTE_URL') . 'img/credit-card/Unknown.png';
            } else {
                return '';
            }
        } elseif (isset($this->attributes['method_type']) && $this->attributes['method_type'] == 'paypal') {
            return config('ethiopay.WEB_PATH.ABSOLUTE_URL') . 'img/paypal.png';
        }
        return '';
    }

}
