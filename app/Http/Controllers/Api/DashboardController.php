<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\DwsWasteCategoryData;
use App\Models\User;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    public function getData(Request $request){
        //Saldo dan Point
        //Data User
        try {
            $city = City::whereRaw('LOWER(`name`) LIKE ? ',[trim(strtolower($request->input('city'))).'%']);
            $userData = User::find($request->input('user_id'));
            $wasteBank = WasteBank::where('city_id', $city->id)->get();
            $wasteCategory = DwsWasteCategoryData::all();

            return Response::json([
                'user_data' => $userData,
                'waste_bank' => $wasteBank,
                'waste_category' => $wasteCategory
            ], 200);
        }
        catch (\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
