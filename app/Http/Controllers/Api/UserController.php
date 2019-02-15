<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Address;
use App\Models\User;
use App\Models\UserWasteBank;
use App\Notifications\FCMNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserResource
     */
    public function index()
    {
        error_log("exception");
        try{

            $users = User::all();

            return new UserResource($users);
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    public function changeRoutinePickup(Request $request)
    {
        try{
            $user = User::where('email', $request->input('email'))->first();

            $user->routine_pickup = $request->input('routine_pickup');
            $user->save();

            UserWasteBank::create([
                'user_id'       => $user->id,
                'waste_bank_id' => $request->input('waste_bank_id')
            ]);

            return Response::json([
                'message' => "Success Changing Routine Pickup Status!",
            ], 200);
        }
        catch(\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    public function saveUserToken(Request $request)
    {
        try{
            $user = User::where('email', $request->input('email'))->first();

            //Save user deviceID
            $saveToken = new FCMNotification();
            $saveToken->SaveToken($user->id, $request->input('device_id'), "apps");

            return Response::json([
                'message' => "Success Save User Token!",
            ], 200);
        }
        catch(\Exception $ex){
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        error_log("exception");
        try{
            $users = User::where('email', $request->input('email'))->with('company', 'addresses')->first();

            return Response::json([
                $users
            ], 200);
        }
        catch(\Exception $ex){
            return Response::json([
                'error'   => $ex,
            ], 500);
        }
    }

    /**
     * Function to get user Address with Email Posted.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddress(Request $request)
    {
        try{
            $user = User::where('email', $request->input('email'))->first();

            return Response::json([
                'address'   => $user->addresses->first(),
            ], 200);
        }
        catch (\Exception $ex){
            return Response::json([
                'error'   => $ex,
            ], 500);
        }
    }

    /**
     * Function to Set Address with Parameters like Register.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAddress(Request $request)
    {
        try{
            $user = User::where('email', $request->input('email'))->first();
            $address = Address::where('user_id', $user->id)->first();
            if($address == null){
                //Create new address
                $nAddress = Address::create([
                    'user_id'       => $user->id,
                    'description'   => $request->input('description'),
                    'latitude'      => $request->input('latitude'),
                    'longitude'     => $request->input('longitude'),
                    'city'          => (int)$request->input('city'),
                    'province'      => (int)$request->input('province'),
                    'postal_code'   => $request->input('postal_code'),
                    'created_at'    => Carbon::now('Asia/Jakarta')
                ]);

                return Response::json([
                    'address'   => $nAddress,
                ], 200);
            }
            else{
                $address->description = $request->input('description');
                $address->latitude = $request->input('latitude');
                $address->longitude = $request->input('longitude');
                $address->city = (int)$request->input('city');
                $address->province = (int)$request->input('province');
                $address->pistal_code = $request->input('postal_code');
                $address->save();

                return Response::json([
                    'address'   => $address,
                ], 200);
            }
        }
        catch (\Exception $ex){
            return Response::json([
                'error'   => $ex,
            ], 500);
        }
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
}
