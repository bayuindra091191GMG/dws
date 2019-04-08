<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserWasteBank;
use App\Models\WasteBank;
use App\Models\WasteBankSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use phpDocumentor\Reflection\Types\Array_;

class WasteBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {
        try{
            $wasteBank = WasteBank::all();

            return Response::json(
                $wasteBank
            , 200);
        }
        catch (\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    public function getClosestWasteBanks(Request $request)
    {
        try{
            $wasteBank = DB::table("waste_banks")
                ->select("*"
                    ,DB::raw("6371 * acos(cos(radians(" . $request->input('latitude') . ")) 
                    * cos(radians(waste_banks.latitude)) 
                    * cos(radians(waste_banks.longitude) - radians(" . $request->input('longitude') . ")) 
                    + sin(radians(" .$request->input('latitude'). ")) 
                    * sin(radians(waste_banks.latitude))) AS distance"))
                ->get();

            //$result = $wasteBank->where('distance', '<=', 5);

            return $wasteBank;
        }
        catch (\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    /**
     * Function to Get Waste Bank Schedules
    */
    public function getWasteBankSchedules()
    {
        try{
            $user = auth('api')->user();
            $userWastebank = UserWasteBank::where('user_id', $user->id)->first();
            if(empty($userWastebank)){
                return Response::json([
                    'message' => "User has not activated routine pickup!",
                ], 482);
            }
            else{
                $wasteBankSchedules = WasteBankSchedule::where('waste_bank_id', $userWastebank->waste_bank_id)->get();

                return $wasteBankSchedules;
            }
        }
        catch (\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
