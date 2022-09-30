<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function sessionExpired(Request $request) {
        $auth = Auth::guard('admin');
        $auth->logout();

        $request->session()->flash('error', "Your session has been expired!");
        
        $data = array(
            'status' => true,
            'message' => 'Your session has been expired!',
        );
        return response()->json($data);
        // return redirect('admin/login')->with('error', 'Your session has been expired!');
    }
}
