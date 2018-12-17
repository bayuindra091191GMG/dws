<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\DwsWasteCategoryData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DwsWasteController extends Controller
{
    public function getData(){
        try{
            $dwsWastes = DwsWasteCategoryData::all();

            return Response::json([
                'dws_wastes' => $dwsWastes,
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
