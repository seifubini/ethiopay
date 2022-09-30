<?php

namespace App\Http\Controllers\Admin\Requests;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class SmsEmailMessageRequest extends FormRequest {

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

        $input = $request->all();
        
        $result = [
            'message_type' => 'required',
            'users_selection_type' => 'required',
            'users_id' => '',
            'title' => 'required',
            'description' => 'required',
            'sent_type' => 'required',
            'sentdate' => '',
            'senttime' => '',
            'sentdatetime' => '',
        ];

        if($input['message_type'] == 'email' && $input['users_selection_type'] == 'selected') {
            $result['users_id'] = 'required';
        }
        if($input['message_type'] == 'sms' && $input['users_selection_type'] == 'selected') {
            $result['users_id'] = 'required_without:debtors_phone';
        }
        if($input['message_type'] == 'sms') {
            $result['description'] = 'required|max:140';
        }

        if ($input['sent_type'] == 'schedule') {
            $result['sentdate'] = 'required';
            $result['senttime'] = 'required';
            $result['sentdatetime'] = 'date_format:Y-m-d H:i:s|after_or_equal:' . Carbon::now('UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y-m-d H:i:s');
        }
        
        return $result;
    }
}
