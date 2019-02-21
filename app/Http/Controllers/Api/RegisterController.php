<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\UserResource;
use App\Mail\EmailVerification;
use App\Models\Address;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\FcmTokenApp;
use App\Models\Province;
use App\Models\User;
use App\Notifications\FCMNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected function create(array $data)
    {
        $companyId = 0;
        if($data['referral'] != null && $data['referral'] != ''){
            //Check if it is Company Code
            $companyData = Company::where('code', $data['referral'])->first();
            if($companyData != null){
                $companyId = $companyData->id;
            }

            //Check for Referral
        }

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'email_token' => base64_encode($data['email']),
            'status_id' => 4,
            'company_id' => $companyId
        ]);
    }

    public function register(Request $request){
        $rules = array(
            'email'                 => 'required|email|max:100|unique:users',
            'first_name'            => 'required|max:100',
            'last_name'             => 'required|max:100',
            'phone'                 => 'required|unique:users',
            'password'              => 'required|min:6|max:20',
            'province'              => 'required',
            'city'                  => 'required',
            'postal_code'           => 'required',
            'description'           => 'required',
            'latitude'              => 'required',
            'longitude'             => 'required',
        );

        $messages = array(
            'not_contains'  => 'Email cannot contain these characters +',
            'phone.unique'  => 'Your phone number already registered!',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
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

            //Save user deviceID
//            $saveToken = new FCMNotification();
//            $saveToken->SaveToken($user->id, )

            $emailVerify = new EmailVerification($user, 'api');
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

    public function facebookAuth(Request $request){

        $data = $request->json()->all();

        $user = User::where('email', $request->input('email'))->with('company', 'addresses')->first();
        if(!empty($user)){
//            return new UserResource($user);
            return Response::json($user, 200);
        }
        elseif($data['referral'] == '' ||  $data['phone'] == ''){
            return Response::json([
                'email' => $data['email']
            ], 404);
        }
        else{
            $companyId = 1;
            if($data['referral'] != null && $data['referral'] != ''){
                //Check if it is Company Code
                $companyData = Company::where('code', $data['referral'])->first();
                if($companyData != null){
                    $companyId = $companyData->id;
                }
            }

            // password default = {email}.{email_token}
            $emailToken =  base64_encode($data['email']);
            $passwordString = $data['email'].$emailToken;

            $userDB = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($passwordString),
                'email_token' => $emailToken,
                'status_id' => 14,
                'company_id' => $companyId
            ]);
            //return new UserResource($userDB);
            //return Response::json(
            //    $userDB, 200);
            try{
                $user = User::where('email', $request->input('email'))->with('company', 'addresses')->first();

                return Response::json($user, 200);
            }
            catch(\Exception $ex){
                return Response::json([
                    'error'   => $ex,
                ], 500);
            }
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

    public function verify($token)
    {
        $user = User::where('email_token',$token)->first();
        $user->status_id = 1;
        $user->save();

        Session::put("user-data", $user);
        Session::flash('success', 'Your Email Have been Verified, Please Login');
        return Redirect::route('login');
    }
}
