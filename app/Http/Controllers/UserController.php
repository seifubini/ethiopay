<?php

namespace App\Http\Controllers;

use App\library\CommonFunction;
use App\library\TwilioLibrary;
use App\Mail\UserRegisterMail;
use App\Mail\UserWelcomeMail;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\ServiceType;
use App\Models\State;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Mail;

class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $user_id = auth()->guard('web')->user()->id;

        $serviceTypes = ServiceType::orderBy('service_name')->get();
        $transactions = Transaction::with(['serviceProviderData'])->where('user_id', $user_id)->orderBy('id', 'desc')->take(10)->get();
        $transactionsCount = Transaction::where('user_id', $user_id)->count();

        $viewData = [
            'serviceTypes' => $serviceTypes,
            'transactions' => $transactions,
            'transactionsCount' => $transactionsCount,
        ];
        return view('home.home', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $phone_codes = Country::orderBy('name')->orderBy('phone_code')->get();
        $phone_code_united = $phone_codes->where('id', 231)->first();
        $countries = Country::orderBy('name')->get();

        $viewData = [
            'countries' => $countries,
            'phone_code_united' => $phone_code_united,
            'phone_codes' => $phone_codes,
        ];
        return view('register', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $input = $request->all();

        $rules = config('input_validation.rules.user.create');
        $messages = config('input_validation.messages.user.create');

        if (isset($input['ethiopia_phone_number']) && !empty($input['ethiopia_phone_number'])) {
            $rules['ethiopia_phone_number'] = 'integer';
        }

        $apiValidator = CommonFunction::inputValidator($input, $rules, $messages);
        if ($apiValidator) {
            return $apiValidator;
        }

        $validateUserPhoneNumber = $this->validateUserPhoneNumber($input['phone_code'], $input['phone_number']);
        if ($validateUserPhoneNumber['status'] == 'false') {
            return response()->json($validateUserPhoneNumber);
        }

        if (isset($input['ethiopia_phone_number']) && !empty($input['ethiopia_phone_number'])) {
            $validateUserEthiopiaPhoneNumber = $this->validateUserEthiopiaPhoneNumber($input['ethiopia_phone_code'], $input['ethiopia_phone_number']);
            if ($validateUserEthiopiaPhoneNumber['status'] == 'false') {
                return response()->json($validateUserEthiopiaPhoneNumber);
            }
        }

        $user = new User();
        $user->firstname = $input['firstname'];
        $user->lastname = $input['lastname'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->phone_code = $input['phone_code'];
        $user->phone_number = $input['phone_number'];
        $user->ethiopia_phone_code = (isset($input['ethiopia_phone_code']) && !empty($input['ethiopia_phone_code'])) ? $input['ethiopia_phone_code'] : '';
        $user->ethiopia_phone_number = (isset($input['ethiopia_phone_number']) && !empty($input['ethiopia_phone_number'])) ? $input['ethiopia_phone_number'] : '';
        // $user->federal_tax_id = $input['federal_tax_id'];

        $profilePictureFile = Input::file('profile_picture');
        if ($profilePictureFile) {
            $profilePictureName = CommonFunction::generateRandomFileName() . "." . $profilePictureFile->getClientOriginalExtension();

            $USER_PROFILE_ORIGINAL_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_ORIGINAL_DOC_PATH');
            $USER_PROFILE_SMALL_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_SMALL_DOC_PATH');
            $USER_PROFILE_MEDIUM_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_MEDIUM_DOC_PATH');
            $USER_PROFILE_LARGE_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_LARGE_DOC_PATH');

            Image::make($profilePictureFile->getRealPath())->resize(config('ethiopay.UPLOAD_PROFILE_SMALL_WIDTH'), config('ethiopay.UPLOAD_PROFILE_SMALL_HEIGHT'), function ($constraint) {
                $constraint->aspectRatio();
                //            $constraint->upsize();
            })->save($USER_PROFILE_SMALL_DOC_PATH . $profilePictureName);
            Image::make($profilePictureFile->getRealPath())->resize(config('ethiopay.UPLOAD_PROFILE_MEDIUM_WIDTH'), config('ethiopay.UPLOAD_PROFILE_MEDIUM_HEIGHT'), function ($constraint) {
                $constraint->aspectRatio();
                //            $constraint->upsize();
            })->save($USER_PROFILE_MEDIUM_DOC_PATH . $profilePictureName);
            //        Image::make($profilePictureFile->getRealPath())->resize(config('ethiopay.UPLOAD_PROFILE_LARGE_WIDTH'), config('ethiopay.PATH.UPLOAD_PROFILE_LARGE_HEIGHT'), function ($constraint) {
            //            $constraint->aspectRatio();
            ////            $constraint->upsize();
            //        })->save($USER_PROFILE_LARGE_DOC_PATH . $profilePictureName);
            $profilePictureFile->move($USER_PROFILE_ORIGINAL_DOC_PATH, $profilePictureName);
            $user->profile_picture = $profilePictureName;
        }
        $user->save();

        $address = new Address();
        $address->user_id = $user->id;
        $address->country_id = $input['country_id'];
        $address->state_id = $input['state_id'];
        $address->city_id = $input['city_id'];
        $address->address_line_1 = $input['address_line_1'];
        $address->zipcode = $input['zipcode'];
        $address->save();

        //return new \App\Mail\UserRegisterMail($user);
        $emailQueueMessage = (new UserRegisterMail($user))->onQueue('emails');
        Mail::to($user->email, $user->fullname)->queue($emailQueueMessage);

        $request->session()->flash('successAlert', 'You have registered successfully. Please activate email to login.');
        $data = array('status' => true, 'message' => 'You have registered successfully. Please activate email to login.');
        return response()->json($data);
    }

    public function checkEmailUnique(Request $request) {
        $email = $request->get('email');
        $user = User::select(['id'])->where('email', '=', $email)->first();
        if ($user) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function checkPhoneNumberUnique(Request $request) {
        $checkPhoneUnique = array();

        $phone_code = $request->get('phone_code');
        $phone_number = $request->get('phone_number');

        $checkPhoneUnique = $this->validateUserPhoneNumber($phone_code, $phone_number);
        return response()->json($checkPhoneUnique);
    }

    public function validateUserPhoneNumber($phone_code, $phone_number) {
        $checkPhoneUnique = array();
        $phone = $phone_code . $phone_number;
        $user = User::select(['id'])
                ->where('phone_code', $phone_code)
                ->where('phone_number', $phone_number)
                ->first();

        if ($user) {
            $checkPhoneUnique['status'] = 'false';
            $checkPhoneUnique['message'] = 'Phone number is already registered';
        } else {
            $isPhoneNoIsValid = TwilioLibrary::isPhoneNoIsValid($phone);
            if ($isPhoneNoIsValid) {
                $checkPhoneUnique['status'] = 'true';
                $checkPhoneUnique['message'] = '';
            } else {
                $checkPhoneUnique['status'] = 'false';
                $checkPhoneUnique['message'] = 'Please enter valid phone no.';
            }
        }
        return $checkPhoneUnique;
    }

    public function checkEthiopiaPhoneNumberUnique(Request $request) {
        $checkPhoneUnique = array();
        $ethiopia_phone_code = $request->get('ethiopia_phone_code');
        $ethiopia_phone_number = $request->get('ethiopia_phone_number');
        $checkPhoneUnique = $this->validateUserEthiopiaPhoneNumber($ethiopia_phone_code, $ethiopia_phone_number);
        return response()->json($checkPhoneUnique);
    }

    public function validateUserEthiopiaPhoneNumber($ethiopia_phone_code, $ethiopia_phone_number) {
        $checkPhoneUnique = array();
        $ethiopia_phone = $ethiopia_phone_code . $ethiopia_phone_number;
        $user = User::select(['id'])
                ->where('ethiopia_phone_code', $ethiopia_phone_code)
                ->where('ethiopia_phone_number', $ethiopia_phone_number)
                ->first();

        if ($user) {
            $checkPhoneUnique['status'] = 'false';
            $checkPhoneUnique['message'] = 'Ethiopia Phone number is already registered';
        } else {
            $isPhoneNoIsValid = TwilioLibrary::isPhoneNoIsValid($ethiopia_phone);
            if ($isPhoneNoIsValid) {
                $checkPhoneUnique['status'] = 'true';
                $checkPhoneUnique['message'] = '';
            } else {
                $checkPhoneUnique['status'] = 'false';
                $checkPhoneUnique['message'] = 'Please enter valid phone no.';
            }
        }
        return $checkPhoneUnique;
    }

    public function checkDebtorPhoneNumber(Request $request) {
        $checkDebtorPhoneUnique = array();

        $debtor_phone_code = $request->get('debtor_phone_code');
        $debtor_phone_number = $request->get('debtor_phone_number');
        $debtor_phone = $debtor_phone_code . $debtor_phone_number;

        $isPhoneNoIsValid = TwilioLibrary::isPhoneNoIsValid($debtor_phone);
        if ($isPhoneNoIsValid) {
            $checkDebtorPhoneUniqueRes['status'] = 'true';
            $checkDebtorPhoneUniqueRes['message'] = '';
        } else {
            $checkDebtorPhoneUniqueRes['status'] = 'false';
            $checkDebtorPhoneUniqueRes['message'] = 'Please enter valid phone no.';
        }
        return response()->json($checkDebtorPhoneUniqueRes);
    }

    public function activateAccountByEmail(Request $request, $encoded_user_id) {
        $user_id = CommonFunction::decodeForID($encoded_user_id);
        $user = User::find($user_id);
        if ($user) {
            if ($user->is_email_verified == '1') {
                $request->session()->flash('errorAlert', 'You have already activated.');
                return redirect('login');
            } else {
                $user->is_email_verified = '1';
                $user->save();
                // return new \App\Mail\UserWelcomeMail($user);
                $emailQueueMessage = (new UserWelcomeMail($user))->onQueue('emails');
                Mail::to($user->email, $user->fullname)->queue($emailQueueMessage);
                $request->session()->flash('successAlert', 'Account activated successfully.');
                return redirect('login');
            }
        } else {
            abort(404, 'Page Not Found.');
        }
    }

    public function profile(Request $request) {
        $user_id = auth()->guard('web')->user()->id;
        $user = User::with(['addressData.countryData', 'addressData.stateData', 'addressData.cityData'])->find($user_id);
        $viewData = [
            'user' => $user,
        ];
        return view('profile.index', $viewData);
    }

    public function profileEdit(Request $request) {
        $user_id = auth()->guard('web')->user()->id;
        $user = User::with(['addressData'])->find($user_id);
        $phone_codes = Country::orderBy('name')->where('id', '!=', 231)->orderBy('phone_code')->get();
        $phone_code_united = Country::where('id', 231)->first();

        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->where('country_id', $user->addressData->country_id)->get();
        $cities = City::orderBy('name')->where('state_id', $user->addressData->state_id)->get();

        $viewData = [
            'user' => $user,
            'phone_codes' => $phone_codes,
            'phone_code_united' => $phone_code_united,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
        ];
        return view('profile.edit', $viewData);
    }

    public function checkPhoneNumberUniqueProfileEdit(Request $request) {
        $checkPhoneUnique = array();

        $phone_code = $request->get('phone_code');
        $phone_number = $request->get('phone_number');

        $checkPhoneUnique = $this->validateUserPhoneNumberProfileEdit($phone_code, $phone_number);
        return response()->json($checkPhoneUnique);
    }

    public function validateUserPhoneNumberProfileEdit($phone_code, $phone_number) {
        $user_id = auth()->guard('web')->user()->id;
        $checkPhoneUnique = array();
        $phone = $phone_code . $phone_number;
        $user = User::select(['id'])
                ->where('phone_code', $phone_code)
                ->where('phone_number', $phone_number)
                ->where('id', '!=', $user_id)
                ->first();

        if ($user) {
            $checkPhoneUnique['status'] = 'false';
            $checkPhoneUnique['message'] = 'Phone number is already registered';
        } else {
            $isPhoneNoIsValid = TwilioLibrary::isPhoneNoIsValid($phone);
            if ($isPhoneNoIsValid) {
                $checkPhoneUnique['status'] = 'true';
                $checkPhoneUnique['message'] = '';
            } else {
                $checkPhoneUnique['status'] = 'false';
                $checkPhoneUnique['message'] = 'Please enter valid phone no.';
            }
        }
        return $checkPhoneUnique;
    }

    public function checkEthiopiaPhoneNumberUniqueProfileEdit(Request $request) {
        $checkPhoneUnique = array();
        $ethiopia_phone_code = $request->get('ethiopia_phone_code');
        $ethiopia_phone_number = $request->get('ethiopia_phone_number');
        $checkPhoneUnique = $this->validateUserEthiopiaPhoneNumberProfileEdit($ethiopia_phone_code, $ethiopia_phone_number);
        return response()->json($checkPhoneUnique);
    }

    public function validateUserEthiopiaPhoneNumberProfileEdit($ethiopia_phone_code, $ethiopia_phone_number) {
        $user_id = auth()->guard('web')->user()->id;
        $checkPhoneUnique = array();
        $ethiopia_phone = $ethiopia_phone_code . $ethiopia_phone_number;
        $user = User::select(['id'])
                ->where('ethiopia_phone_code', $ethiopia_phone_code)
                ->where('ethiopia_phone_number', $ethiopia_phone_number)
                ->where('id', '!=', $user_id)
                ->first();

        if ($user) {
            $checkPhoneUnique['status'] = 'false';
            $checkPhoneUnique['message'] = 'Ethiopia Phone number is already registered';
        } else {
            $isPhoneNoIsValid = TwilioLibrary::isPhoneNoIsValid($ethiopia_phone);
            if ($isPhoneNoIsValid) {
                $checkPhoneUnique['status'] = 'true';
                $checkPhoneUnique['message'] = '';
            } else {
                $checkPhoneUnique['status'] = 'false';
                $checkPhoneUnique['message'] = 'Please enter valid phone no.';
            }
        }
        return $checkPhoneUnique;
    }

    public function profileUpdate(Request $request) {
        $user_id = auth()->guard('web')->user()->id;
        $input = $request->all();

        $rules = config('input_validation.rules.user.profileUpdate');
        $messages = config('input_validation.messages.user.profileUpdate');

        if (isset($input['password']) && !empty($input['password'])) {
            $rules['password'] = 'min:8';
        }
        if (isset($input['ethiopia_phone_number']) && !empty($input['ethiopia_phone_number'])) {
            $rules['ethiopia_phone_number'] = 'integer';
        }

        $apiValidator = CommonFunction::inputValidator($input, $rules, $messages);
        if ($apiValidator) {
            return $apiValidator;
        }

        $validateUserPhoneNumber = $this->validateUserPhoneNumberProfileEdit($input['phone_code'], $input['phone_number']);
        if ($validateUserPhoneNumber['status'] == 'false') {
            return response()->json($validateUserPhoneNumber);
        }

        if (isset($input['ethiopia_phone_number']) && !empty($input['ethiopia_phone_number'])) {
            $validateUserEthiopiaPhoneNumber = $this->validateUserEthiopiaPhoneNumberProfileEdit($input['ethiopia_phone_code'], $input['ethiopia_phone_number']);
            if ($validateUserEthiopiaPhoneNumber['status'] == 'false') {
                return response()->json($validateUserEthiopiaPhoneNumber);
            }
        }

        $user = User::find($user_id);
        $user->firstname = $input['firstname'];
        $user->lastname = $input['lastname'];
        $user->phone_code = $input['phone_code'];
        $user->phone_number = $input['phone_number'];
        $user->ethiopia_phone_code = $input['ethiopia_phone_code'];
        $user->ethiopia_phone_number = (isset($input['ethiopia_phone_number']) && !empty($input['ethiopia_phone_number'])) ? $input['ethiopia_phone_number'] : '';
        $user->federal_tax_id = $input['federal_tax_id'];
        if (isset($input['password']) && !empty($input['password'])) {
            $user->password = bcrypt($input['password']);
        }

        $profilePictureFile = Input::file('profile_picture');
        if ($profilePictureFile) {
            $profilePictureName = CommonFunction::generateRandomFileName() . "." . $profilePictureFile->getClientOriginalExtension();

            $USER_PROFILE_ORIGINAL_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_ORIGINAL_DOC_PATH');
            $USER_PROFILE_SMALL_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_SMALL_DOC_PATH');
            $USER_PROFILE_MEDIUM_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_MEDIUM_DOC_PATH');
//            $USER_PROFILE_LARGE_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_LARGE_DOC_PATH');

            Image::make($profilePictureFile->getRealPath())->resize(config('ethiopay.UPLOAD_PROFILE_SMALL_WIDTH'), config('ethiopay.UPLOAD_PROFILE_SMALL_HEIGHT'), function ($constraint) {
                $constraint->aspectRatio();
                //            $constraint->upsize();
            })->save($USER_PROFILE_SMALL_DOC_PATH . $profilePictureName);
            Image::make($profilePictureFile->getRealPath())->resize(config('ethiopay.UPLOAD_PROFILE_MEDIUM_WIDTH'), config('ethiopay.UPLOAD_PROFILE_MEDIUM_HEIGHT'), function ($constraint) {
                $constraint->aspectRatio();
                //            $constraint->upsize();
            })->save($USER_PROFILE_MEDIUM_DOC_PATH . $profilePictureName);
            //        Image::make($profilePictureFile->getRealPath())->resize(config('ethiopay.UPLOAD_PROFILE_LARGE_WIDTH'), config('ethiopay.PATH.UPLOAD_PROFILE_LARGE_HEIGHT'), function ($constraint) {
            //            $constraint->aspectRatio();
            ////            $constraint->upsize();
            //        })->save($USER_PROFILE_LARGE_DOC_PATH . $profilePictureName);
            $profilePictureFile->move($USER_PROFILE_ORIGINAL_DOC_PATH, $profilePictureName);

            CommonFunction::deleteFile($USER_PROFILE_ORIGINAL_DOC_PATH . $user->profile_picture);
            CommonFunction::deleteFile($USER_PROFILE_SMALL_DOC_PATH . $user->profile_picture);
            CommonFunction::deleteFile($USER_PROFILE_MEDIUM_DOC_PATH . $user->profile_picture);
            $user->profile_picture = $profilePictureName;
        }
        $user->save();

        $address = Address::where('user_id', $user->id)->first();
        $address->country_id = $input['country_id'];
        $address->state_id = $input['state_id'];
        $address->city_id = $input['city_id'];
        $address->address_line_1 = $input['address_line_1'];
        $address->zipcode = $input['zipcode'];
        $address->save();

        $request->session()->flash('successAlert', 'Profile updated successfully.');
        $data = array('status' => true, 'message' => 'Profile updated successfully.', 'user' => $user);
        return response()->json($data);
    }

    public function show($id, Request $request) {
        $user_id = auth()->guard('web')->user()->id;
        $transaction = Transaction::select("transactions.*", 'service_providers.provider_name', 'service_types.service_name')
                ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                ->join('service_types', 'service_providers.service_type_id', '=', 'service_types.id')
                ->where('transactions.id', $id)
                ->where('transactions.user_id', $user_id)
                ->first();
        if ($transaction) {
            $payment = PaymentMethod::where('id', $transaction->payment_method_id)->first();

            return view('home.transaction.show', compact('transaction', 'payment'));
        }

        $request->session()->flash('errorAlert', "Transaction Not Found.");
        return redirect('home.home');
    }

}
