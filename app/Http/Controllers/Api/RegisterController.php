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
        if($data['referral'] != null && $data['referral'] != ''){
            $categoryId = 2;
        }
        else{
            $categoryId = 1;
        }

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'email_token' => base64_encode($data['email']),
            'status_id' => 4,
            'waste_category_id' => $categoryId
        ]);
    }

    public function register(Request $request){
        $rules = array(
            'email'                 => 'required|email|max:100|unique:users',
            'first_name'            => 'required|max:100',
            'last_name'             => 'required|max:100',
            'phone'                 => 'required|unique:users',
            'password'              => 'required|min:6|max:20',
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

//            return Response::json([
//                'message' => $user,
//                'Request'   => $request->all()
//            ], 200);

            //Save Address
            $address = Address::create([
                'user_id'       => $user->id,
                'description'   => $request->input('description'),
                'latitude'      => $request->input('latitude'),
                'longitude'     => $request->input('longitude'),
                'city'          => (int)$request->input('city'),
                'province'      => (int)$request->input('province'),
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
                'message' => "Something went Wrong!",
                'exception' => $exception
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
