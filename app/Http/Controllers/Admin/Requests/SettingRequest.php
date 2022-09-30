<?php

namespace App\Http\Controllers\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest {

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
                        'key' => 'required|unique:settings,key',
                        'value' => 'required',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'key' => 'required|unique:settings,key,' . $this->segment(3) . ',id',
                        'value' => 'required'
                    ];
                }
            default:break;
        }
    }

}
