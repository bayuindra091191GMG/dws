<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\PointHistory;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use App\Models\VoucherCategory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class VoucherController extends Controller
{
    /**
     * Function to get All the Voucher Categories.
    */
    public function getCategories()
    {
        try{
            $voucherCategories = VoucherCategory::all();

            if($voucherCategories->count() === 0){
                return Response::json([
                    'message' => "No voucher categories found!",
                ], 482);
            }

            return $voucherCategories;
        }
        catch(\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    /**
 * Function to show all the vouchers based on category and  company.
 *
 * @param Request $request
 * @return UserResource|JsonResponse
 */
    public function get(Request $request)
    {
        try{
            $vouchers = Voucher::where('category_id', $request->input('category_id'))
                ->where('company_id', $request->input('company_id'))
                ->get();

            if($vouchers->count() === 0){
                return Response::json([
                    'message' => "No vouchers found!",
                ], 482);
            }

            return $vouchers;
        }
        catch(\Exception $ex){
            Log::error("Api/VoucherController - get Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    /**
     * Function to show all the vouchers based on company.
     *
     * @param Request $request
     * @return UserResource|JsonResponse
     */
    public function getAll(Request $request)
    {
        try{
            $vouchers = Voucher::where('company_id', $request->input('company_id'))
                ->get();

            if($vouchers->count() === 0){
                return Response::json([
                    'message' => "No vouchers found!",
                ], 482);
            }

            return $vouchers;
        }
        catch(\Exception $ex){
            Log::error("Api/VoucherController - getAll Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    /**
     * Function to Buy Voucher.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function buy(Request $request){
        try{
            $user = auth('api')->user();
            $voucher = Voucher::where('code', $request->input('voucher_code'))->first();

            //Pembatasan pembelian hanya 1 voucher
            $userVoucher = UserVoucher::where('voucher_id', $voucher->id)
                ->where('user_id', $user->id)->first();
            if($userVoucher != null){
                return Response::json('User Already Buy this Voucher!', 400);
            }

            if($user->point < $voucher->required_point){
                return Response::json('Not Enough Point', 400);
            }

            $letters='ABCDEFGHIJKLMNOPQRSTUVWXYZ';  // selection of a-z
            $redeemCode='';  // declare empty string
            for($x=0; $x<3; ++$x){  // loop three times
                $redeemCode.=$letters[rand(0,25)].rand(0,9);  // concatenate one letter then one number
            }

            UserVoucher::create([
                'user_id'       => $user->id,
                'voucher_id'    => $voucher->id,
                'is_used'       => 0,
                'created_at'    => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'redeem_code'   => $redeemCode
            ]);
            $voucher->quantity--;
            $voucher->save();

            //Create Point History
            PointHistory::create([
                'user_id'           => $user->id,
                'type_transaction'  => 'Debit',
                'amount'            => $voucher->required_point,
                'saldo'             => $user->point - $voucher->required_point,
                'description'       => 'Pembelian Voucher ' . $voucher->code,
                'created_at'        => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ]);

            $user->point -= $voucher->required_point;
            $user->save();

            return Response::json('Success Buy Voucher ' . $voucher->code, 200);
        }catch(\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    /**
     * Function to Redeem the Voucher.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function redeem(Request $request)
    {
        try{
            $user = auth('api')->user();
            $voucher = Voucher::where('code', $request->input('voucher_code'))->first();

            $userVoucher = UserVoucher::where('user_id', $user->id)->where('voucher_id', $voucher->id)->first();
            $userVoucher->redeem_at = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $userVoucher->is_used = 1;
            $userVoucher->used_at = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $userVoucher->save();

            return Response::json("Success Redeeming Voucher " . $voucher->code, 200);
        }catch(\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    /**
     * Function to Get all the user remaining Vouchers.
     *
    */
    public function getAllUserVoucher(){
        try{
            $user = auth('api')->user();
            $userVouchers = UserVoucher::where('user_id', $user->id)->where('is_used', 0)->with('voucher')->get();

            return $userVouchers;
        }
        catch(\Exception $ex){
            Log::error("Api/VoucherController - getAllUserVoucher Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
