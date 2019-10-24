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
            $dwsWasteDBs = DwsWasteCategoryData::all();
//            $baseUri = URL::to('/');
//            foreach ($dwsWastes as $dwsWaste){
//                $dwsWaste->img_path = $baseUri . '/public/storage/admin/dwscategory/' . $dwsWaste->img_path;
//            }

            $dwsWasteData = collect();
            foreach ($dwsWasteDBs as $dwsWasteDB){
                $exampleImgDBs =  $dwsWasteDB->dws_waste_category_images;
                $exampleImgPaths = collect();
                foreach ($exampleImgDBs as $exampleImgDB){
                    $exampleImgPaths->push(asset('storage/admin/dwscategory/'. $exampleImgDB->img_path));
                }

                $masaroWaste = ([
                    'id'=> $dwsWasteDB->id,
                    'code'=> $dwsWasteDB->code,
                    'name'=> $dwsWasteDB->name,
                    'examples'=> $dwsWasteDB->examples,
                    'description'=> $dwsWasteDB->description,
                    'img_path'=> $dwsWasteDB->img_path,
                    'price' => $dwsWasteDB->price,
                    'examples_img' => $exampleImgPaths
                ]);

                $dwsWasteData->push($masaroWaste);
            }

            return Response::json($dwsWasteData);
        }
        catch (\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
