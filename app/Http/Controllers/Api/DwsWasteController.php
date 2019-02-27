<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\DwsWasteCategoryData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class DwsWasteController extends Controller
{
    public function getData(){
        try{
            $dwsWastes = DwsWasteCategoryData::all();
            $baseUri = URL::to('/');
            foreach ($dwsWastes as $dwsWaste){
                $dwsWaste->img_path = $baseUri . '/storage/admin/dwscategory/' . $dwsWaste->img_path;
            }

            return Response::json($dwsWastes);
        }
        catch (\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
