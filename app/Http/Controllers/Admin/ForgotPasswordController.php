<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function findBrowser($token){
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

            $data = [
                'email'     => $passwordReset->email,
                'token'     => $passwordReset->token
            ];

            return view('frontend.reset-password')->with($data);
        }
        catch (\Exception $ex){
            Log::error("Api/ForgotPasswordController - find - error EX: ". $ex);
            return response()->json(500);
        }
    }

    public function reset(Request $request){
        $validator = Validator::make($request->all(), [
            'password'              => 'required|max:100',
            'password_confirm'      => 'required|max:100'
        ],[
            'password.required'             => 'Kata Sandi Baru wajib diisi!',
            'password_confirm.required'     => 'Konfirmasi Kata Sandi wajib diisi!'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $passwordNew = $request->input('password');
        $passwordConfirm = $request->input('password_confirm');

        if($passwordNew !== $passwordConfirm){
            return back()->withErrors("Konfirmasi Kata Sandi harus sama dengan Kata Sandi Baru!")->withInput($request->all());
        }

        $passwordReset = PasswordReset::where([
            ['token', $request->input('token')],
            ['email', $request->input('email')]
        ])->first();

        if(empty($passwordReset)){
            return back()->withErrors("INVALID TOKEN!")->withInput($request->all());
        }

        $user = User::where('email', $request->input('email'))->first();
        $user->password = Hash::make($passwordNew);
        $user->save();

        // Delete Password Reset
        $passwordReset->delete();

        $data = [
            'email'     => $user->email
        ];

        return view('frontend.reset-password-success')->with($data);
    }
}
