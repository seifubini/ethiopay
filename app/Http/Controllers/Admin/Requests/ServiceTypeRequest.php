<?php

namespace App\Http\Controllers\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceTypeRequest extends FormRequest {

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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'service_name' => 'required|unique:service_types,service_name,NULL,NULL,deleted_at,NULL',
                        'payment_fee_in_percentage' => 'required|numeric|min:0|max:100'
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'service_name' => 'required|unique:service_types,service_name,' . $this->segment(3) . ',id,deleted_at,NULL',
                        'payment_fee_in_percentage' => 'required|numeric|min:0|max:100'
                    ];
                }
            default:break;
        }
    }

}
