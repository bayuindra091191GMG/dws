<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use App\Notifications\FCMNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class SubscribeController extends Controller
{
    public function save(Request $request)
    {
        try {
            $subscriber = Subscribe::where('name', $request->input('name'))->where('phone', $request->input('phone'))->get();
            if($subscriber != null){
                return Response::json([
                    'Already Exist!'
                ], 200);
            }

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
    public function demoSubmit(Request $request){
        try{
            $name = $request->input('name');
            $phone = $request->input('phone');
            $category = $request->input('category');
            $weight = $request->input('weight');

            //send notification
            $title = "Digital Waste Solution";
            $body = "Transaksi Baru dari kategori ".$category." seberat ".$weight." kilogram";
            $data = array(
                'category' => $category,
                'name' => $name,
                'weight' => $weight,
                'phone' => $phone,
            );
//        dd($data);
//        $isSuccess = FCMNotification::SendNotification(8, 'apps', $title, $body, $data);
            $isSuccess = FCMNotification::SendNotification(1, 'browser', $title, $body, $data);

            return Response::json([
                'message' => "Success submit data!",
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
