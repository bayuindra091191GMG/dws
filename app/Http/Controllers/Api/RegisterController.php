<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Mail\EmailVerification;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'email_token' => base64_encode($data['email']),
            'status_id' => 4
        ]);
    }

    public function register(Request $request){
        $rules = array(
            'email'                 => 'required|email|max:100|unique:users',
            'first_name'            => 'required|max:100',
            'last_name'             => 'required|max:100',
            'phone'                 => 'required|unique:users',
            'password'              => 'required|min:6|max:20|same:password',
            'password_confirmation' => 'required|same:password'
        );

        $messages = array(
            'not_contains'  => 'Email cannot contain these characters +',
            'phone.unique'  => 'Your phone number already registered!',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        try{
            $user = $this->create($request->all());

            //Save Address
            $address = Address::create([
                'user_id'  => $user->id,
                'description'   => $request->input('description'),
                'latitude'      => $request->input('latitude'),
                'longitude'     => $request->input('longitude'),
                'city'          => $request->input('city'),
                'province'      => $request->input('province'),
                'postal_code'   => $request->input('postal_code'),
                'created_at'    => Carbon::now('Asia/Jakarta')
            ]);

            $emailVerify = new EmailVerification($user);
            Mail::to($user->email)->send($emailVerify);

            return Response::json([
                'message' => "Success!"
            ], 200);
        }
        catch (\Exception $exception){
            return Response::json([
                'message' => "Something went Wrong!"
            ], 500);
        }
    }

    public function registrationData(){
        $provinces = Province::all();
        $cities = City::all();

        return Response::json([
            'provinces' => $provinces,
            'cities'    => $cities
        ], 200);
    }


    /**
     * Function to Send OTP to User Phone Number
     */
    public function sendOtp(){

    }

    /**
     * Function to Verify OTP That Sent to User
    */
    public function verifyOtp(){

    }
}
