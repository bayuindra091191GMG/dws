<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Voucher;
use App\Models\VoucherCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VoucherController extends Controller
{
    public function getCategories()
    {
        try{
            $voucherCategories = VoucherCategory::all();

            return new UserResource($voucherCategories);
        }
        catch(\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    public function get(Request $request)
    {
        try{
            $vouchers = Voucher::where('category_id', $request->input('category_id'))
                ->where('company_id', $request->input('company_id'))->get();

            return new UserResource($vouchers);
        }
        catch(\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
