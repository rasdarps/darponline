<?php

namespace App\Http\Controllers\ClientSide;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegistration;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ShopAuthController extends Controller
{
    //

    public function login_index(){
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:customers'],
            'password' => ['required', 'string'],
        ],
        [
            'email.exists' => "The email you entered is not recognized.",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::guard('customer')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $errors = [
                'password' => 'Invalid password.',
            ];
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'redirect' => route('shop.home')
        ]);
    }
   
    public function logout(Request $request)
    {   
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
            'redirect' => route('shop.login')
        ]);
    }

    public function register_index(){
        return view('client.auth.register');
    }

    public function register_post(CustomerRegistration $request)
    {

       

        try{
              // Use a transaction to ensure data integrity
              DB::transaction(function () use ($request) {
                    $user = Customer::create([
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'first_name' => $request->first_name ,
                        'last_name' => $request->last_name ,

                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'address' => $request->address,
                        'mobile_no' => $request->mobile_no,
                    ]);


                });

            

            
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful! Please login to continue.',
                // 'user' => [
                //     'id' => $user->id,
                //     'name' => $user->name,
                //     'email' => $user->email,
                // ],
                'redirect' => route('shop.login') // Optional: send redirect URL
            ], 201);

        } catch(\Throwable $e){ 

            Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
          }
    }
}
