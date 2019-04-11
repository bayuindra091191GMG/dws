<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Address;
use App\Models\Configuration;
use App\Models\User;
use App\Models\UserWasteBank;
use App\Notifications\FCMNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return User[]|\Exception|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        error_log("exception");
        try{

            $users = User::all();

            return $users;
        }
        catch(\Exception $ex){
            error_log($ex);
            return $ex;
        }
    }

    /**
     * Function to change the status of Routine Pickup.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeRoutinePickup(Request $request)
    {
        try{
            $userId = auth('api')->user();
            $user = User::where('email', $userId->email)->first();

            $userWasteBank = UserWasteBank::where('user_id', $user->id)->where('waste_bank_id', $request->input('waste_bank_id'))->first();

            if(empty($userWasteBank)){
                $wasteBank = DB::table("waste_banks")
                    ->select("*"
                        ,DB::raw("6371 * acos(cos(radians(" . $request->input('latitude') . ")) 
                    * cos(radians(waste_banks.latitude)) 
                    * cos(radians(waste_banks.longitude) - radians(" . $request->input('longitude') . ")) 
                    + sin(radians(" .$request->input('latitude'). ")) 
                    * sin(radians(waste_banks.latitude))) AS distance"))
                    ->get();
                $config = Configuration::where('configuration_key', 'wastebank_radius')->first();
                $temp = $wasteBank->where('distance', '<=', $config->configuration_value);

                if(count($temp) == 0){
                    return Response::json([
                        'message' => "No Near Wastebank Found!!",
                    ], 482);
                }

                UserWasteBank::create([
                    'user_id'       => $user->id,
                    'waste_bank_id' => $wasteBank[0]->id
                ]);
            }

            $user->routine_pickup = $request->input('routine_pickup');
            $user->save();

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

    /**
     * Function to save user token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveUserToken(Request $request)
    {
        try{
            $data = $request->json()->all();
            $user = auth('api')->user();

            //Save user deviceID
            FCMNotification::SaveToken($user->id, $data['device_id'], "app");

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        error_log("exception");
        try{
            $user = auth('api')->user();
            $users = User::where('email', $user->email)->with('company', 'addresses')->first();

            return Response::json($users, 200);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddress()
    {
        try{
            $userId = auth('api')->user();
            $user = User::where('email', $userId->email)->first();

            return Response::json($user->addresses->first(),200);
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
            $rules = array(
                'description'    => 'required',
                'latitude'       => 'required',
                'longitude'      => 'required',
                'city'           => 'required',
                'province'       => 'required',
                'postal_code'    => 'required'
            );

            $data = $request->json()->all();

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $user = auth('api')->user();
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

    public function testingAuthToken(){
        $user = auth('waste_collector')->user();
        return $user;
    }

    // Update customer profile
    public function updateProfile(Request $request)
    {
        try{
            $rules = array(
                'first_name'    => 'required',
                'last_name'     => 'required',
                'phone'         => 'required'
            );

            Log::info("UserController - updateProfile Content: ". $request->getContent());

            $data = $request->json()->all();
            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $user = auth('api')->user();
            $profile = User::with('addresses')->where('id', $user->id)->first();
            $profile->first_name = $data['first_name'];
            $profile->last_name = $data['last_name'];
            $profile->phone = $data['phone'];
            $profile->save();

            return Response::json([
                'user'   => $profile,
            ], 200);
        }
        catch (\Exception $ex){
            Log::error("UserController - updateProfile Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'error'   => $ex,
            ], 500);
        }
    }
}
