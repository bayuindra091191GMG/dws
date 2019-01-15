<?php

namespace App\Http\Controllers\Auth;

use App\Mail\EmailVerification;
use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
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
            'name'            => 'required|max:100',
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
            return back()->withErrors($validator)->withInput();
        }

        $user = $this->create($request->all());

        $emailVerify = new EmailVerification($user, '');
        Mail::to($user->email)->send($emailVerify);

        return View('auth.send-email', compact('user'));
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

    public function RequestVerification($email){

        $userDB = User::where('email', $email)->first();

        $emailVerify = new EmailVerification($userDB, '');
        Mail::to($userDB->email)->send($emailVerify);

        $email = $userDB->email;
        return View('auth.send-email', compact('email'));
    }

    public function showRegistrationForm()
    {
        $countries = Country::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('auth.register', compact('countries', 'provinces', 'cities'));
    }
}
