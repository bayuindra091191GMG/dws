<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SubscribeController extends Controller
{
    public function save(Request $request)
    {
        try {
            Subscribe::create([
                'name'  => $request->input('name'),
                'phone' => $request->input('phone')
            ]);

            return Response::json([
                'Success'
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
