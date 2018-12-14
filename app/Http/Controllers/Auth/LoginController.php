<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        try{
            $rules = array(
                'email'                 => 'required|email',
                'password'              => 'required',
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return View("auth.login")->withErrors(['msg' => ['Wrong Username or Password!']]);
            }

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->back()->withErrors('Wrong Email or Password!!', 'default')->withInput($request->only('email'));
            }

            $credentials = $this->credentials($request);
            $userData = User::where('email', $request->email)->first();

            if($userData->status_id == 4){
                $email = $userData->email;
                return View('auth.send-email', compact('email'));
            }

            if ($this->guard()->attempt($credentials, $request->has('remember'))) {
                return $this->sendLoginResponse($request);
            }

            return Redirect::route('index');
        }
        catch (\Exception $ex){
            dd($ex);
        }
    }
}
