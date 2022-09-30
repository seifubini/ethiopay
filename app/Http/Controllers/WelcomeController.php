<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\library\CommonFunction;
use Illuminate\Support\Facades\Mail;
use App\library\TwilioLibrary;
use Response; 

class WelcomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function index() {
        return view('welcome.index');
    }
    
    public function termsAndConditions() {
        return view('termsAndConditions');
    }

    public function contactUs() {
        $phone_codes = Country::orderBy('name')->orderBy('phone_code')->get();
        $phone_code_united = $phone_codes->where('id', 231)->first();
        $countries = Country::orderBy('name')->get();

        $viewData = [
            'countries' => $countries,
            'phone_code_united' => $phone_code_united,
            'phone_codes' => $phone_codes
        ];
        return view('contactUs', $viewData);
    }

    public function mailContact(Request $request){
        $input = $request->except('_token');
        // $input = $request->all();

        $rules = config('input_validation.rules.contact');
        $messages = config('input_validation.messages.contact');

        $apiValidator = CommonFunction::inputValidator($input, $rules, $messages);
        if ($apiValidator)
            return $apiValidator;
        
        // return new \App\Mail\ContactMail($input);
            
        $emailQueueMessage = (new ContactMail($input))->onQueue('emails');
        Mail::to(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->queue($emailQueueMessage);

        $data = ['status' => true, 'message' => 'Mail send Successfully'];
        return Response::json($data);
    }

    public function validateContactUsPhoneNumber(Request $req) {
        // $phone_code, $phone_number
        $phone = $req->get('phone_code') . $req->get('phone_number');
        $isPhoneNoIsValid = TwilioLibrary::isPhoneNoIsValid($phone);
        if ($isPhoneNoIsValid) {
            $checkPhoneUnique['status'] = 'true';
            $checkPhoneUnique['message'] = '';
        } else {
            $checkPhoneUnique['status'] = 'false';
            $checkPhoneUnique['message'] = 'Please enter valid phone no.';
        }
        return $checkPhoneUnique;
    }
    
    public function moreAboutUs() {
        return view('moreAboutUs');
    }
}
