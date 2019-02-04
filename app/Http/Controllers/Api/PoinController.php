<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PointHistory;
use App\Models\TransactionHeader;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PoinController extends Controller
{
    public function redeem(Request $request)
    {
        try{
            //Check the Voucher
            $voucher = Voucher::where('code', $request->input('voucher_code'));
            $user = User::where('email', $request->input('email'));

            $voucherRedeemed = UserVoucher::where('user_id', $user->id)->where('voucher_id', $voucher->id)->get();
            if($voucherRedeemed != null && $voucherRedeemed->count() >= $voucher->redeemable){
                return Response::json([
                    'message' => "You Already Redeem this Voucher!",
                ], 400);
            }
            else{
                //Create User Voucher
                UserVoucher::create([
                    'user_id'       => $request->input('user_id'),
                    'voucher_id'    => $voucher->id,
                    'is_used'       => 0,
                    'redeem_at'     => Carbon::now('Asia/Jakarta')
                ]);

                $userPoint = $user->point - $voucher->required_poin;
                $transaction = TransactionHeader::find($request->input('transaction_id'));
                PointHistory::create([
                    'user_id' => $request->input('user_id'),
                    'type' => $transaction->transaction_type_id,
                    'transaction_id' => $request->input('transaction_id'),
                    'type_transaction' => "debet",
                    'amount' => $voucher->required_poin,
                    'saldo' => $userPoint,
                    'description' => "Pemakaian voucher code ".$voucher->code,
                    'created_at'     => Carbon::now('Asia/Jakarta')
                ]);

                $user->point = $userPoint;
                $user->save();

                return Response::json([
                    'message' => "Success Redeem this Voucher!",
                ], 200);
            }
        }
        catch(\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
