<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function checkEmail(Request $request)
    {
        try{
            $email = User::where('email', $request->input('email'))->first();

            if($email != null) {
                return Response::json([
                    'message' => "Email Existed!"
                ], 200);
            }
            else{
                return Response::json([
                    'message' => "Email Not Found!"
                ], 404);
            }
        }
        catch (\Exception $exception){
            return Response::json([
                'message' => "Something went Wrong!",
                'exception' => $exception
            ], 500);
        }
    }

    public function setNewPassword(Request $request)
    {
        $rules = array(
            'email'                 => 'required',
            'password'              => 'required|min:6|max:20|same:password',
            'password_confirmation' => 'required|same:password'
        );

        $messages = array(
            'not_contains'  => 'Email cannot contain these characters +'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'message' => "Wrong Input",
                'validation' => $validator
            ], 400);
        }

        //Success Validation
        try{
            $user = User::where('email', $request->input('email'))->first();
            $user->password = Hash::make($request->input('password'));

            return Response::json([
                'message' => "Success Resetting Password!"
            ], 200);
        }
        catch (\Exception $exception){
            return Response::json([
                'message' => "Something went Wrong!",
                'exception' => $exception
            ], 500);
        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }
}
