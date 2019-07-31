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
use Illuminate\Support\Facades\Log;
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
            'status_id' => 1,
            'company_id' => $companyId
        ]);
    }

    public function register(Request $request){
        try{
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
                'phone.unique'  => 'Your phone number already registered!',
            );

            $data = $request->json()->all();

            $validator = Validator::make($data, $rules, $messages);

            if ($validator->fails()) {
                Log::error("Validator message: ". $validator->errors()->first());
                return response()->json($validator->errors()->first(), 400);
            }

            $user = $this->create($request->all());

            //Save Address
            $address = Address::create([
                'user_id'       => $user->id,
                'primary'       => 1,
                'description'   => $request->input('description'),
                'latitude'      => $request->input('latitude'),
                'longitude'     => $request->input('longitude'),
                'city'          => (int)$request->input('city'),
                'province'      => (int)$request->input('province'),
                'postal_code'   => $request->input('postal_code'),
                'created_at'    => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'notes'         => $request->input('notes')
            ]);

            //Save user deviceID
//            $saveToken = new FCMNotification();
//            $saveToken->SaveToken($user->id, )

//            $emailVerify = new EmailVerification($user, 'api');
//            Mail::to($user->email)->send($emailVerify);

            return Response::json([
                'message' => "Success!"
            ], 200);
        }
        catch (\Exception $ex){
            Log::error("RegisterController - register error: ". $ex);
            return Response::json([
                'message' => "Something went Wrong!",
                'exception' => $ex
            ], 500);
        }
    }

    public function externalAuth(Request $request){

        $data = $request->json()->all();

        $user = User::where('email', $request->input('email'))->with('company', 'addresses')->first();
        if(!empty($user)){
//            return new UserResource($user);
            return Response::json($user, 200);
        }
//        elseif($data['referral'] == '' ||  $data['phone'] == ''){
        elseif($request->input('referral') == '' ||  $request->input('phone') == ''){
            return Response::json([
                'email' => $request->input('email')
            ], 482);
        }
        else{
            $companyId = 1;
//            if($data['referral'] != null && $data['referral'] != ''){
            if($request->input('referral') != null && $request->input('referral') != ''){
                //Check if it is Company Code
                $companyData = Company::where('code', $request->input('referral'))->first();
                if($companyData != null){
                    $companyId = $companyData->id;
                }
            }

            // password default = {email}.{email_token}
            $emailToken =  base64_encode($request->input('email'));
            $passwordString = $request->input('email').$emailToken;

            //for status_id if user login using fb(ext_id=1) status 14, and gmail (ext_id=2) status 19
            $extId = $request->input('ext_id');
            $userDB = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($passwordString),
                'email_token' => $emailToken,
                'status_id' => $extId == "1" ? 14 : 19,
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

    public function isEmailExist(Request $request){
        $inputEmail = $request->input('email');
        $isEmailExists = User::where('email', $inputEmail)
            ->exists();

        if($isEmailExists){
            return Response::json(
                'true'
            , 200);
        }
        else{
            return Response::json(
                'false'
            , 200);
        }
    }

    public function isPhoneExist(Request $request){
        $inputPhone = $request->input('phone');
        $isPhoneExists = User::where('phone', $inputPhone)
            ->exists();

        if($isPhoneExists){
            return Response::json(
                'true'
            , 200);
        }
        else{
            return Response::json(
                'false'
            , 200);
        }
    }
}
