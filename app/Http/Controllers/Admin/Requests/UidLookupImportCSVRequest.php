<?php

namespace App\Http\Controllers\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UidLookupImportCSVRequest extends FormRequest {

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
//            'uidLookup' => 'required|file|mimes:CSV,csv',
            'uidLookup' => 'required|mimes:csv,txt'
        ];
    }

}
