<?php

namespace App\Http\Controllers\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UidLookupRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'service_type_id' => 'required',
            'uid' => 'required',
            'debtor_firstname' => 'required',
            'debtor_lastname' => 'required',
            'debtor_city' => 'required',
            'amount' => 'required|numeric|min:0',
            'cut_off_date' => 'required'
        ];
    }

}
