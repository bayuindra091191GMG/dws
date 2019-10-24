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
            $masaroWasteDBs = MasaroWasteCategoryData::all();
            $masaroWasteData = collect();
            foreach ($masaroWasteDBs as $masaroWasteDB){
                $exampleImgDBs =  $masaroWasteDB->masaro_waste_category_images;
                $exampleImgPaths = collect();
                foreach ($exampleImgDBs as $exampleImgDB){
                    $exampleImgPaths->push(asset('storage/admin/masarocategory/'. $exampleImgDB->img_path));
                }

                $masaroWaste = ([
                    'id'=> $masaroWasteDB->id,
                    'code'=> $masaroWasteDB->code,
                    'name'=> $masaroWasteDB->name,
                    'examples'=> $masaroWasteDB->examples,
                    'description'=> $masaroWasteDB->description,
                    'img_path'=> $masaroWasteDB->img_path,
                    'price' => $masaroWasteDB->price,
                    'examples_img' => $exampleImgPaths
                ]);

                $masaroWasteData->push($masaroWaste);
            }

            return Response::json($masaroWasteData);
        }
        catch (\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
