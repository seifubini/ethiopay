<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use App\library\TwilioLibrary;
use App\library\CommonFunction;
use Intervention\Image\Facades\Image;

class UserRepository extends BaseRepository {

    protected $model;

    public function __construct(User $model) {
        $this->model = $model;
        parent::__construct($this->model);
    }

    public function checkEmailUnique($data) {
        $data['email'] = isset($data['email']) ? $data['email'] : '';
        $user = User::select(['id'])->where('email', '=', $data['email']);

        if (isset($data['user_id']) && $data['user_id'] > 0)
            $user = $user->where('id', '!=', $data['user_id']);
        $user = $user->first();

        if ($user) {
            return Response::json(['status' => "false", "message" => "Email already taken by other user"]);
        } else {
            return Response::json(['status' => "true", "message" => ""]);
        }
    }

    public function checkPhoneNumberUnique($data) {
        $checkPhoneUnique = array();

        $phone_code = isset($data['phone_code']) ? $data['phone_code'] : '';
        $phone_number = isset($data['phone_number']) ? $data['phone_number'] : '';

        $phone = $phone_code . $phone_number;
        $user = User::select(['id'])
                ->where('phone_code', $phone_code)
                ->where('phone_number', $phone_number);

        if (isset($data['user_id']) && $data['user_id'] > 0)
            $user = $user->where('id', '!=', $data['user_id']);

        $user = $user->first();

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
//        return Response::json($checkPhoneUnique);
    }

    public function checkEthiopiaPhoneNumberUnique($data) {
        $checkPhoneUnique = array();
        $ethiopia_phone_code = isset($data['ethiopia_phone_code']) ? $data['ethiopia_phone_code'] : '';
        $ethiopia_phone_number = isset($data['ethiopia_phone_number']) ? $data['ethiopia_phone_number'] : '';

        $ethiopia_phone = $ethiopia_phone_code . $ethiopia_phone_number;
        $user = User::select(['id'])
                ->where('ethiopia_phone_code', $ethiopia_phone_code)
                ->where('ethiopia_phone_number', $ethiopia_phone_number);

        if (isset($data['user_id']) && $data['user_id'] > 0)
            $user = $user->where('id', '!=', $data['user_id']);

        $user = $user->first();

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
//        return response()->json($checkPhoneUnique);
    }

    public function uploadImage($profilePictureFile) {
        $profilePictureName = CommonFunction::generateRandomFileName() . "." . $profilePictureFile->getClientOriginalExtension();

        $USER_PROFILE_ORIGINAL_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_ORIGINAL_DOC_PATH');
        $USER_PROFILE_SMALL_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_SMALL_DOC_PATH');
        $USER_PROFILE_MEDIUM_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_MEDIUM_DOC_PATH');
        $USER_PROFILE_LARGE_DOC_PATH = config('ethiopay.DOC_PATH.USER_PROFILE_LARGE_DOC_PATH');

        Image::make($profilePictureFile->getRealPath())->resize(config('ethiopay.UPLOAD_PROFILE_SMALL_WIDTH'), config('ethiopay.UPLOAD_PROFILE_SMALL_HEIGHT'), function ($constraint) {
            $constraint->aspectRatio();
        })->save($USER_PROFILE_SMALL_DOC_PATH . $profilePictureName);
        Image::make($profilePictureFile->getRealPath())->resize(config('ethiopay.UPLOAD_PROFILE_MEDIUM_WIDTH'), config('ethiopay.UPLOAD_PROFILE_MEDIUM_HEIGHT'), function ($constraint) {
            $constraint->aspectRatio();
        })->save($USER_PROFILE_MEDIUM_DOC_PATH . $profilePictureName);

        $profilePictureFile->move($USER_PROFILE_ORIGINAL_DOC_PATH, $profilePictureName);
        return $profilePictureName;
    }

}
