<?php

namespace App\Http\Controllers\Admin\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {

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
    public function rules(Request $request) {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'firstname' => 'required',
                        'lastname' => 'required',
                        'email' => 'required|unique:users,email,NULL,NULL,deleted_at,NULL',
                        'profile_picture' => 'required',
                        'password' => 'required|min:8|regex:/^(?=.*[0-9])(?=.*[A-Z]).+$/',
                        'phone_code' => 'required',
                        'phone_number' => 'required',
                        'ethiopia_phone_code' => 'required',
                        'ethiopia_phone_number' => 'required',
                        'federal_tax_id' => 'required',
                        'address_line_1' => 'required',
                        'city_id' => 'required',
                        'state_id' => 'required',
                        'country_id' => 'required',
                        'zipcode' => 'required'
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    $input = $request->all();
        
                    $result = [
                        'firstname' => 'required',
                        'lastname' => 'required',
                        'email' => 'required|unique:users,email,' . $this->segment(3) . ',id,deleted_at,NULL',
                       // 'profile_picture' => 'mimes:jpeg,jpg,png',
                        'password' => '',
                        'phone_code' => 'required',
                        'phone_number' => 'required',
                        'ethiopia_phone_code' => 'required',
                        'ethiopia_phone_number' => 'required',
                        'federal_tax_id' => 'required',
                        'address_line_1' => 'required',
                        'city_id' => 'required',
                        'state_id' => 'required',
                        'country_id' => 'required',
                        'zipcode' => 'required'
                    ];

                    if($input['password'] != '') {
                        $result['password'] = 'min:8|regex:/^(?=.*[0-9])(?=.*[A-Z]).+$/';
                    }

                    return $result;
                }
            default:break;
        }
    }

}
