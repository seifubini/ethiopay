<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $hidden = [
        'stripe_transaction_id', 'stripe_transaction_response',
        'paypal_transaction_id', 'paypal_transaction_response'
    ];
    protected $appends = [];

    public function serviceProviderData() {
        return $this->belongsTo('App\Models\ServiceProvider', 'service_provider_id', 'id');
    }

    public function serviceTypeData() {
        return $this->belongsTo('App\Models\ServiceType', 'service_type_id', 'id');
    }

}
