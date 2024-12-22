<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // login 
    public function admin_login(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
            return response()->json([
                'status' => 200,
                'redirect' => '/dashboard',
                'user' => $user
            ]);
        }

        return response()->json([
            'status' => 401,
            'msg' => 'Invalid credentials'
        ]);
    }
    
    public function app_admin_logout(Request $request){
        Auth::guard('admin')->logout();
        return response()->json([
            'status' => 200,
            'message' => 'Logged out successfully',
            'redirect' => '/admin_login'
        ]);
    }
    public function customer_login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('customer')->attempt($credentials)) {
            $user = Auth::guard('customer')->user();
            return response()->json([
                'status' => 200,
                'redirect' => url('/'),
                'user' => $user,
            ]);
        }

        return response()->json([
            'status' => 401,
            'msg' => 'Invalid credentials',
        ]);
    }

    public function customer_register(Request $request)
    {
        $this->validate($request,[
            'email' => 'bail|required|email|unique:customers',
            'password' => 'bail|required|min:6',
            'repead_password' => 'bail|required|min:6',
        ]);
        if($request->password != $request->repead_password){
            return response()->json(['pass and repeas not the same'],422);
        }
        $password = bcrypt($request->password);
        $customer = Customer::create([
            'email' => $request->email,
            'password' => $password,
        ]);
        return redirect('customer_login');
    }

    public function customer_logout()
    {
        Auth::guard('customer')->logout();
        return response()->json([
            'status' => 200,
            'message' => 'Logged out successfully',
            'redirect' => '/customer_login'
        ]);
    }
}
