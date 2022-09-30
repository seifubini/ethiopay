<?php

namespace App\Http\Controllers\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceProviderRequest extends FormRequest {

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
                        'service_type_id' => 'required',
                        'service_id' => 'required',
                        'provider_name' => 'required|unique:service_providers,provider_name,NULL,NULL,deleted_at,NULL'
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'service_type_id' => 'required',
                        'service_id' => 'required',
                        'provider_name' => 'required|unique:service_providers,provider_name,' . $this->segment(3) . ',id,deleted_at,NULL'
                    ];
                }
            default:break;
        }
    }

}
