<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Address;
use App\Models\Configuration;
use App\Models\User;
use App\Models\UserWasteBank;
use App\Models\WasteCollectorUser;
use App\Notifications\FCMNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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
     * @return JsonResponse
     */
    public function changeRoutinePickup(Request $request)
    {
        try{
            $userId = auth('api')->user();
            $user = User::where('email', $userId->email)->first();

            $user->routine_pickup = $request->input('routine_pickup');
            $user->save();

            //$userWasteBank = UserWasteBank::where('user_id', $user->id)->where('waste_bank_id', $request->input('waste_bank_id'))->first();

            if($user->routine_pickup === 1){
                $wasteBankRaws = DB::table("waste_banks")
                    ->where('waste_category_id', ($user->company_id + 1))
                    ->select("*"
                        ,DB::raw("6371 * acos(cos(radians(" . $request->input('latitude') . ")) 
                    * cos(radians(waste_banks.latitude)) 
                    * cos(radians(waste_banks.longitude) - radians(" . $request->input('longitude') . ")) 
                    + sin(radians(" .$request->input('latitude'). ")) 
                    * sin(radians(waste_banks.latitude))) AS distance"))
                    ->orderBy("distance")
                    ->get();

                foreach ($wasteBankRaws as $wasteBankRaw){
                    Log::info($wasteBankRaw->id. " distance: ". $wasteBankRaw->distance);
                }

                $config = Configuration::where('configuration_key', 'wastebank_radius')->first();
                $temp = $wasteBankRaws->where('distance', '<=', $config->configuration_value);


                if(count($temp) == 0){
                    // If calculated waste bank not found
                    $userWasteBanks = UserWasteBank::where('user_id', $user->id)->get();
                    foreach($userWasteBanks as $userWasteBank){
                        $userWasteBank->status_id = 2;
                        $userWasteBank->save();
                    }

                    // tidak ketemu wastebank, routine pickup status tetap 0
                    $user->routine_pickup = 0;
                    $user->save();

                    return Response::json([
                        'message' => "There isn't any Waste Bank near your household address.",
                    ], 482);
                }
                else{
                    $userWasteBank = UserWasteBank::where('user_id', $user->id)->where('waste_bank_id', $wasteBankRaws[0]->id)->first();

                    if(empty($userWasteBank)){
                        UserWasteBank::create([
                            'user_id'       => $user->id,
                            'waste_bank_id' => $wasteBankRaws[0]->id,
                            'status_id'     => 1
                        ]);
                    }
                    else{
                        $userWasteBank->status_id = 1;
                        $userWasteBank->save();
                    }
                    // ketemu wastebank, routine pickup status jadi 1
                    $user->routine_pickup = 1;
                    $user->save();

//                    $wasteCollectorUser = WasteCollectorUser::where('user_id', $user->id)->first();
//                    if(!empty($wasteCollectorUser)){
//                        $wasteCollectorUser->status_id = 1;
//                        $wasteCollectorUser->save();
//                    }

                    $responseJson = User::where('id', $user->id)->with('company', 'addresses')->first();
                    return Response::json($responseJson, 200);
                }
            }
            else{
                $userWasteBanks = UserWasteBank::where('user_id', $user->id)->get();
                if($userWasteBanks->count() > 0){
                    foreach($userWasteBanks as $userWasteBank){
                        $userWasteBank->status_id = 2;
                        $userWasteBank->save();
                    }
                }

                $userWasteCollectors = WasteCollectorUser::where('user_id', $user->id)->get();
                if($userWasteCollectors->count() > 0){
                    foreach ($userWasteCollectors as $userWasteCollector){
                        $userWasteCollector->status_id = 2;
                        $userWasteCollector->save();
                    }
                }

                $responseJson = User::where('id', $user->id)->with('company', 'addresses')->first();
                return Response::json($responseJson, 200);
            }
        }
        catch(\Exception $ex){
            Log::error("Api/UserController - changeRoutinePickup error: ". $ex);
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
     * @return JsonResponse
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
     * @return JsonResponse
     */
    public function show()
    {
        //error_log("exception");
        try{
            $user = auth('api')->user();
            $users = User::where('email', $user->email)->with('company', 'addresses')->first();

            return Response::json($users, 200);
        }
        catch(\Exception $ex){
            Log::error('Api/UserController - show error EX: '. $ex);
            return Response::json([
                'error'   => $ex,
            ], 500);
        }
    }

    /**
     * Function to get user Address with Email Posted.
     *
     * @return JsonResponse
     */
    public function getAddress()
    {
        try{
            $user = auth('api')->user();
            $user = User::where('email', $user->email)->first();

            $address = Address::where('user_id', $user->id)
                ->where('primary', 1)
                ->first();

            if(empty($address)){
                return Response::json([
                    'message' => "Anda belum punya alamat.",
                ], 482);
            }

            return Response::json($address,200);
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
     * @return JsonResponse
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

            Log::info("UserController - setAddress Content: ". $request);

            $data = $request->json()->all();

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $user = auth('api')->user();

            // Disable routine pickup
            if($user->routine_pickup === 1){
                $userDb = User::find($user->id);
                $userDb->routine_pickup = 0;
                $userDb->save();

                $userWasteBanks = UserWasteBank::where('user_id', $user->id)->get();
                if($userWasteBanks->count() > 0){
                    foreach($userWasteBanks as $userWasteBank){
                        $userWasteBank->status_id = 2;
                        $userWasteBank->save();
                    }
                }

                $userWasteCollectors = WasteCollectorUser::where('user_id', $user->id)->get();
                if($userWasteCollectors->count() > 0){
                    foreach ($userWasteCollectors as $userWasteCollector){
                        $userWasteCollector->status_id = 2;
                        $userWasteCollector->save();
                    }
                }
            }

            $addresses = Address::where('user_id', $user->id)->get();
            if($addresses->count() === 0){
                // Create new address
                $nAddress = Address::create([
                    'user_id'       => $user->id,
                    'primary'       => 1,
                    'description'   => $data['description'],
                    'latitude'      => $data['latitude'],
                    'longitude'     => $data['longitude'],
                    'city'          => (int)$data['city'],
                    'province'      => (int)$data['province'],
                    'postal_code'   => $data['postal_code'],
                    'notes'         => $data['notes'] ?? null,
                    'created_at'    => Carbon::now('Asia/Jakarta')
                ]);

                return Response::json($nAddress, 200);
            }
            else{
                // Assume edited address is always primary
                $address = Address::where('user_id', $user->id)
                    ->first();

                $address->description = $data['description'];
                $address->latitude = $data['latitude'];
                $address->longitude = $data['longitude'];
                $address->city = (int)$data['city'];
                $address->province = (int)$data['province'];
                $address->postal_code = $data['postal_code'];
                $address->notes = $data['notes'] ?? null;
                $address->save();

                return Response::json($address, 200);
            }



        }
        catch (\Exception $ex){
            Log::error("Api/UserController - setAddress error: ". $ex);
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try{
            $rules = array(
                'first_name'    => 'required',
                'last_name'     => 'required',
                'phone'         => 'required'
            );

            Log::info("UserController - updateProfile Content: ". $request);
            $data = json_decode($request->input('json_string'));
            //$jsonData = $request->input('apiEditProfileModel');

            //Log::info("First Name: ". $data->json_string->first_name);

            //$data = $request->json()->all();
            //$validator = Validator::make($data, $rules);

//            if ($validator->fails()) {
//                return response()->json($validator->messages(), 400);
//            }

            $user = auth('api')->user();
            $profile = User::with(['addresses', 'company'])->where('id', $user->id)->first();
            $profile->first_name = $data->first_name;
            $profile->last_name = $data->last_name;
            $profile->phone = $data->phone;
            $profile->save();

            // Update avatar
            if($request->hasFile('avatar')){
                if(!empty($profile->image_path)){
                    $tempImg = public_path('storage/avatars/'. $profile->image_path);
                    if(file_exists($tempImg)){
                        unlink($tempImg);
                    }
                }

                $avatar = Image::make($request->file('avatar'));
//                $filename = $profile->id. "_". Carbon::now('Asia/Jakarta')->format('Ymdhms') . '.' . $avatar->mime();
                $extension = $request->file('avatar')->extension();
                $filename = $profile->id. "_". Carbon::now('Asia/Jakarta')->format('Ymdhms') . '.' . $extension;
                $avatar->save(public_path('storage/avatars/'. $filename));
                $profile->image_path = $filename;
                $profile->save();
            }

            return Response::json($profile, 200);
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
