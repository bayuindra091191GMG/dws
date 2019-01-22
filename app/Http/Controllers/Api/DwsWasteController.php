<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\DwsWaste;
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

    public function getItems(Request $request)
    {
        $rules = array(
            'category_id'   => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        try{
            $items = DwsWaste::where('dws_waste_category_datas_id', $request->input('category_id'))->get();
            $baseUri = URL::to('/');
            foreach ($items as $item){
                $item->img_path = $baseUri . 'home/dwstesti/public_html/storage/admin/dwsitem/' . $item->img_path;
            }

            return Response::json($items);
        }
        catch (\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
