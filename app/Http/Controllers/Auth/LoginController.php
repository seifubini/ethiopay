<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    protected $guard = 'web';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm() {
        return view('login');
    }

    protected function credentials(Request $request) {
        return array_merge($request->only($this->username(), 'password'), ['is_email_verified' => '1']);
    }

    protected function sendFailedLoginResponse(Request $request) {
        $errors = [$this->username() => trans('auth.failed')];

        $user = User::where($this->username(), $request->{$this->username()})->first();
        $isUserCredentialsMatch = Auth::validate($request->only($this->username(), 'password'));
        
        if ($isUserCredentialsMatch && $user->is_email_verified == '0') {
            if ($request->expectsJson()) {
                return response()->json($errors, 422);
            }
            return redirect()->back()
                            ->withInput($request->only($this->username(), 'remember'))
                            ->withErrors(['email' => 'Please verify your email address.']);
        } else {
            if ($request->expectsJson()) {
                return response()->json($errors, 422);
            }
            return redirect()->back()
                            ->withInput($request->only($this->username(), 'remember'))
                            ->withErrors(['email' => 'These credentials do not match our records.']);
        }
    }

}
