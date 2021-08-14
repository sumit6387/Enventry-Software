<?php

namespace App\Http\Controllers;

use App\Functions\AllFunction;
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
                    $request->session()->put('role', $user->role);
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
        if ($request->session()->get('email') && $request->session()->get('name') && $request->session()->get('role')) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('name');
        $request->session()->forget('email');
        $request->session()->forget('role');
        return redirect('/');
    }

    public function sendForgotEmail(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'email' => "required",
        ]);

        if ($valid->passes()) {
            $user = User::where('email', $request->email)->get()->first();
            if ($user) {
                $sendmail = new AllFunction();
                $sendmail->sendForgotPassword($request->email);
                return response()->json([
                    'status' => true,
                    'msg' => "Email Send Succesfully!!",
                    'url' => url('/'),
                ]);

            } else {
                return response()->json([
                    'status' => false,
                    'msg' => "Enter Registered Email!!",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function changePassword($email)
    {
        $users = User::all();
        $x = 0;
        foreach ($users as $key => $value) {
            if (md5($value->email) == $email) {
                $x = 1;
                return view('change-password', ['email' => $value->email]);
            }
        }
        return redirect('/');
    }

    public function ChangePasswordPro(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'new_password' => "required",
            'cnf_password' => "required",
        ]);

        if ($valid->passes()) {
            if ($request->new_password != $request->cnf_password) {
                return response()->json([
                    'status' => false,
                    'msg' => "Enter Both Password Same!!",
                ]);
            }
            $user = User::where('email', $request->email)->get()->first();
            if ($user) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return response()->json([
                    'status' => true,
                    'msg' => "Password Changed Successfully!!",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => "Something Went Wrong!!",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }
}
