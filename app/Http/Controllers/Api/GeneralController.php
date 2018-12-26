<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GeneralController extends Controller
{
    public function checkCategory()
    {
        try {
            $isMasaro = Configuration::find(17);

            return Response::json([
                'is_masaro' => $isMasaro->configuration_value
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
