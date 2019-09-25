<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{

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
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function forgotPassword(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|string|email',
            ]);

            Log::info('LOG forgotPassword email: '. $request->email);

            $user = User::where('email', $request->email)->first();
            if (!$user)
                return response()->json([
                    'message' => "We can't find a user with that e-mail address."
                ], 404);
            $passwordReset = PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => str_random(60)
                ]
            );
            if ($user && $passwordReset)
                $user->notify(
                    new PasswordResetRequest($passwordReset->token)
                );
            return response()->json([
                'message' => 'We have e-mailed your password reset link!'
            ]);
        }
        catch (\Exception $ex){
            Log::error("Api/ForgotPasswordController - forgotPassword - error EX: ". $ex);
            return response()->json(500);
        }
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        try{
            $passwordReset = PasswordReset::where('token', $token)->first();

            Log::info('LOG find token: '. $token);

            if (!$passwordReset)
                return response()->json([
                    'message' => 'This password reset token is invalid.'
                ], 404);
            if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
                $passwordReset->delete();
                return response()->json([
                    'message' => 'This password reset token is invalid.'
                ], 404);
            }
            return response()->json($passwordReset);
        }
        catch (\Exception $ex){
            Log::error("Api/ForgotPasswordController - find - error EX: ". $ex);
            return response()->json(500);
        }
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|confirmed',
                'token' => 'required|string'
            ]);

            Log::info('LOG reset email: '. $request->input('email'));
            Log::info('LOG reset token: '. $request->input('token'));

            //return $request;
            $passwordReset = PasswordReset::where([
                ['token', $request->input('token')],
                ['email', $request->input('email')]
            ])->first();
            if (!$passwordReset)
                return response()->json([
                    'message' => 'This password reset token is invalid.'
                ], 404);
            $user = User::where('email', $passwordReset->email)->first();
            if (!$user)
                return response()->json([
                    'message' => "We can't find a user with that e-mail address."
                ], 404);

            $user->password = Hash::make($request->input('password'));
            $user->save();
            $passwordReset->delete();
            $user->notify(new PasswordResetSuccess());
            return response()->json($user);
        }
        catch (\Exception $ex){
            Log::error("Api/ForgotPasswordController - reset - error EX: ". $ex);
            return response()->json(500);
        }
    }
}
