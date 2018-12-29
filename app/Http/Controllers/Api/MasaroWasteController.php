<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\MasaroWasteCategoryData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MasaroWasteController extends Controller
{
    public function getData(){
        try{
            $masaroWaste = MasaroWasteCategoryData::all();

            return Response::json($masaroWaste);
        }
        catch (\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
