<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'email' => "required|email",
            'password' => "required",
        ]);

        if ($valid->passes()) {
            $user = User::where('email', $request->email)->get()->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $request->session()->put('name', $user->name);
                    $request->session()->put('email', $user->email);
                    return \response()->json([
                        'status' => true,
                        'url' => url('/dashboard'),
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'msg' => "Enter Valid Credential",
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => "Enter Valid Credential",
                ]);
            }
        } else {
            return \response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }
    public function showlogin(Request $request)
    {
        if ($request->session()->get('email') && $request->session()->get('name')) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('name');
        $request->session()->forget('email');
        return redirect('/');
    }
}
