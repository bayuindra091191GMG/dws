<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\libs\Utilities;
use App\Models\Configuration;
use App\Models\PointHistory;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\User;
use App\Models\WasteCollectorUser;
use App\Models\WasteCollectorUserStatus;
use App\Notifications\FCMNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class TransactionHeaderController extends Controller
{
    /**
     * Function to get the Transaction Data Details.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTransactionData(Request $request)
    {
        $transactionHeader = TransactionHeader::where('transaction_no', $request->input('transaction_no'))->with('transaction_details')->first();



        return $transactionHeader;
    }

    /**
     * Function to get all the Transactions.
     *
     * @return UserResource
     */
    public function getTransactions()
    {
        $user = auth('api')->user();
        $transactions = TransactionHeader::where('user_id', $user->id)->get();

        return new UserResource($transactions);
    }

    /**
     * Create a new Transaction when on Demand.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function createTransaction(Request $request)
    {
        try{
            $rules = array(
                'total_weight'      => 'required',
                'total_price'       => 'required',
                'details'           => 'required',
                'latitude'          => 'required',
                'longitude'         => 'required'
            );

            $data = $request->json()->all();

            //Log::info("TransactionHeaderController - createTransaction: ". $request->getContent());

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return Response::json([
                    'message' => $validator->messages(),
                ], 400);
            }

            $user = auth('api')->user();

            // Generate transaction codes
            $today = Carbon::today()->format("Ym");
            $categoryType = $user->company->waste_category_id;

            //Check for Nearest Wastebank
            $wasteBankId = '';
            $wasteBankPIC = 1;
            $wasteBankTemp = DB::table("waste_banks")
                ->select("*"
                    ,DB::raw("6371 * acos(cos(radians(" . $data['latitude'] . "))
                    * cos(radians(waste_banks.latitude))
                    * cos(radians(waste_banks.longitude) - radians(" . $data['longitude'] . "))
                    + sin(radians(" .$data['latitude']. "))
                    * sin(radians(waste_banks.latitude))) AS distance"))
                ->orderBy("distance")
                ->get();

            $now = Carbon::now('Asia/Jakarta');
            $radiusDB = Configuration::find(18);

            //return $wasteBankTemp;
            $wasteBanks = $wasteBankTemp->where('distance', '<=', $radiusDB->configuration_value)
                ->where('waste_category_id', $categoryType)
                ->where('open_hours', '<', $now->toTimeString())
                ->where('closed_hours', '>', $now->toTimeString());

            //return $wasteBanks;
            if($wasteBanks == null || $wasteBanks->count() == 0){
                return Response::json([
                    'message' => "No Nearest Wastebank Detected!",
                ], 400);
            }
            else{
                $tmp = $wasteBanks->first();
                $wasteBankId = $tmp->id;
                $wasteBankPIC = $tmp->pic_id;
            }

            if($categoryType == "1"){
                $prepend = "TRANS/DWS/". $today;
            }
            else{
                $prepend = "TRANS/MASARO/". $today;
            }

            $nextNo = Utilities::GetNextTransactionNumber($prepend);
            $code = Utilities::GenerateTransactionNumber($prepend, $nextNo);

            // Convert total weight to kilogram
            $totalWeight = floatval($data["total_weight"]) * 1000;

            // Create on demand transaction
            $header = TransactionHeader::create([
                'transaction_no'        => $code,
                'total_weight'          => $totalWeight,
                'total_price'           => $data["total_price"],
                'date'                  => Carbon::now('Asia/Jakarta'),
                'status_id'             => 6,
                'user_id'               => $user->id,
                'transaction_type_id'   => 3,
                'waste_category_id'     => $user->company->waste_category_id,
                'latitude'              => $data['latitude'],
                'longitude'             => $data['longitude'],
                'created_at'            => Carbon::now('Asia/Jakarta'),
                'updated_at'            => Carbon::now('Asia/Jakarta'),
                'waste_bank_id'         => $wasteBankId,
                'point_user'            => $data["total_price"]
            ]);

            //do detail
            foreach ($data['details'] as $item){
                $detailWeight = floatval($item["weight"]) * 1000;
                if($user->company->waste_category_id == 1) {
                    TransactionDetail::create([
                        'transaction_header_id' => $header->id,
                        'dws_category_id'       => $item['dws_category_id'],
                        'weight'                => $detailWeight,
                        'price'                 => $item['price']
                    ]);
                }
                else if($user->company->waste_category_id == 2){
                    TransactionDetail::create([
                        'transaction_header_id' => $header->id,
                        'masaro_category_id'    => $item['masaro_category_id'],
                        'weight'                => $detailWeight,
                        'price'                 => $item['price']
                    ]);
                }
            }
            Utilities::UpdateTransactionNumber($prepend);

            //Send notification to
            //Driver, Admin Wastebank
            $title = "Digital Waste Solution";
            $body = "User Membuat Transaksi On Demand";
            $data = array(
                "type_id" => "3-1",
                "transaction_id" => $header->id,
                "transaction_date" => Carbon::parse($header->created_at)->format('j-F-Y H:i:s'),
                "transaction_no" => $header->transaction_no,
                "name" => $user->first_name." ".$user->last_name,
//                    "waste_category_name" => $body,
                "total_weight" => $header->total_weight_kg,
                "total_price" => $header->total_price,
                "transaction_details" => $header->transaction_details
//                    "waste_bank" => $wasteBankId,
//                    "waste_collector" => "-",
//                    "status" => $header->status->description,
            );

            $isSuccess = FCMNotification::SendNotification($wasteBankPIC, 'browser', $title, $body, $data);

            return Response::json([
                'message' => "Success creating On demand Transaction!",
            ], 200);
        }
        catch (\Exception $ex){
            Log::error("Api/TransactionHeaderController - createTransaction Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    public function createTransactionDev(Request $request)
    {
        try{
            $rules = array(
                'total_weight'      => 'required',
                'total_price'       => 'required',
                'details'           => 'required',
                'latitude'          => 'required',
                'longitude'         => 'required'
            );

            //$data = $request->json()->all();

            //$validator = Validator::make($data, $rules);

            Log::info("Api/TransactionHeaderController - createTransactionDev Content: ". $request);
            //Log::info("Api/TransactionHeaderController - createTransactionDev Content: ". $request->getContent());
            //Log::info("Api/TransactionHeaderController - createTransactionDev Content: ". json_encode($request->all()));
            $data = json_decode($request->input('json_string'));

//            if ($validator->fails()) {
//                return Response::json([
//                    'message' => $validator->messages(),
//                ], 400);
//            }

            $user = auth('api')->user();

            // Generate transaction codes
            $today = Carbon::today('Asia/Jakarta')->format("Ym");
            $categoryType = $user->company->waste_category_id;

            Log::info("Latitude: ". $data->latitude. " Longitude: ". $data->longitude);

            //Check for Nearest Wastebank
            $wasteBankId = '';
            $wasteBankPIC = 1;
            $wasteBankTemp = DB::table("waste_banks")
                ->select("*"
                    ,DB::raw("6371 * acos(cos(radians(" . $data->latitude . "))
                    * cos(radians(waste_banks.latitude))
                    * cos(radians(waste_banks.longitude) - radians(" . $data->longitude . "))
                    + sin(radians(" .$data->latitude. "))
                    * sin(radians(waste_banks.latitude))) AS distance"))
                ->orderBy("distance")
                ->get();

            $now = Carbon::now('Asia/Jakarta');
            $radiusDB = Configuration::find(18);

            $wasteBanks = $wasteBankTemp->where('distance', '<=', $radiusDB->configuration_value)
                ->where('waste_category_id', $categoryType);

            if($wasteBanks == null || $wasteBanks->count() == 0){
                return Response::json([
                    'message' => "Tidak Ada Wastebank disekitar Anda!",
                ], 400);
            }
            else{
                $tmp = $wasteBanks->first();
                $wasteBankId = $tmp->id;
                $wasteBankPIC = $tmp->pic_id;
            }

            if($categoryType == "1"){
                $prepend = "TRANS/DWS/". $today;
            }
            else{
                $prepend = "TRANS/MASARO/". $today;
            }

            $nextNo = Utilities::GetNextTransactionNumber($prepend);
            $code = Utilities::GenerateTransactionNumber($prepend, $nextNo);

            // Convert total weight to kilogram
            $totalWeight = floatval($data->total_weight) * 1000;

            // Create on demand transaction
            $header = TransactionHeader::create([
                'transaction_no'        => $code,
                'total_weight'          => $totalWeight,
                'total_price'           => $data->total_price,
                'date'                  => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'status_id'             => 6,
                'user_id'               => $user->id,
                'transaction_type_id'   => 3,
                'waste_category_id'     => $user->company->waste_category_id,
                'latitude'              => $data->latitude,
                'longitude'             => $data->longitude,
                'created_at'            => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'updated_at'            => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'waste_bank_id'         => $wasteBankId,
                'point_user'            => $data->total_price
            ]);

            $arrDetailIds = [];

            //do detail
            foreach ($data->details as $item){
                $detailWeight = floatval($item->weight) * 1000;
                if($user->company->waste_category_id == 1) {
                    $newDetail = TransactionDetail::create([
                        'transaction_header_id' => $header->id,
                        'dws_category_id'       => $item->dws_category_id,
                        'weight'                => $detailWeight,
                        'price'                 => $item->price,
                        'note'                  => $item->note ?? ""
                    ]);

                    array_push($arrDetailIds, $newDetail->id);
                }
                else if($user->company->waste_category_id == 2){
                    $newDetail = TransactionDetail::create([
                        'transaction_header_id' => $header->id,
                        'masaro_category_id'    => $item->masaro_category_id,
                        'weight'                => $detailWeight,
                        'price'                 => $item->price,
                        'note'                  => $item->note ?? ""
                    ]);

                    array_push($arrDetailIds, $newDetail->id);
                }
            }

            // Update transaction auto number
            Utilities::UpdateTransactionNumber($prepend);

            // Save uploaded photo
            if($request->hasFile('image')){
                //Log::info("check 1: ");

                $images = $request->file('image');

                $arrayIdx = 0;
                foreach($images as $image){

                    //Log::info("check 2: ");
                    $detailId = $arrDetailIds[$arrayIdx];

                    $avatar = Image::make($image);
                    $extension = $image->extension();
                    $filename = $header->id. '_ondemand_'. $detailId. '_'. Carbon::now('Asia/Jakarta')->format('Ymdhms') . '.' . $extension;
                    $avatar->save(public_path('storage/transactions/ondemand/'. $filename));
//                    $header->image_path = $filename;
//                    $header->save();

                    $transactionDetail = TransactionDetail::find($detailId);
                    $transactionDetail->image_path = $filename;
                    $transactionDetail->save();

                    $arrayIdx++;
                }
            }

            // Send notification to Driver & Admin Waste Processor
            $title = "Digital Waste Solution";
            $body = "User Membuat Transaksi On Demand";
            $data = array(
                "type_id" => "3-1",
                "transaction_id" => $header->id,
                "transaction_date" => Carbon::parse($header->created_at)->format('j-F-Y H:i:s'),
                "transaction_no" => $header->transaction_no,
                "name" => $user->first_name." ".$user->last_name,
//                    "waste_category_name" => $body,
                "total_weight" => $header->total_weight_kg,
                "total_price" => $header->total_price,
                "transaction_details" => $header->transaction_details
//                    "waste_bank" => $wasteBankId,
//                    "waste_collector" => "-",
//                    "status" => $header->status->description,
            );

            $isSuccess = FCMNotification::SendNotification($wasteBankPIC, 'browser', $title, $body, $data);

            return Response::json([
                'message' => "Success creating On demand Transaction!",
            ], 200);
        }
        catch (\Exception $ex){
            Log::error("TransactionHeaderController - createTransactionDev Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    /**
     * Used for On demand transaction when Driver Scan user QR Code then Confirm the Transaction's Details.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmTransactionByDriver(Request $request)
    {
        $rules = array(
            'transaction_id'    => 'required',
            'flag'              => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        // if 0 skip Edit
        // if 1 Edit
        $header = TransactionHeader::find($data['transaction_id']);

        if($data['flag'] == 1)
        {
            $totalWeight = floatval($data["total_weight"]) * 1000;
            $header->total_weight = $totalWeight;
            $header->total_price = $data['total_price'];
            $header->status_id = 7;
            $header->save();

            foreach ($header->transaction_details as $detail)
            {
                foreach ($data['details'] as $item) {
                    $detailWeight = floatval($item['weight']) * 1000;
                    if($detail->id == $item['id']) {
                        if ($header->user->company->waste_category_id == 1) {
                            $detail->dws_category_id = $item['dws_category_id'];
                            $detail->weight = $detailWeight;
                            $detail->price = $item['price'];
                            $detail->save();
                        } else if ($header->user->company->waste_category_id == 2) {
                            $detail->masaro_category_id = $item['masaro_category_id'];
                            $detail->weight = $detailWeight;
                            $detail->price = $item['price'];
                            $detail->save();
                        }
                    }
                }
            }
        }
        else{
            $header->status_id = 7;
            $header->save();
        }

        return Response::json([
            'message' => "Success Confirming Transaction!",
        ], 200);
    }

    /**
     * Used for On Demand Transactions
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmTransactionByUser(Request $request)
    {
        $rules = array(
            'transaction_id'    => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $header = TransactionHeader::where('transaction_no', $data['transaction_no'])->first();
        $header->status_id = 8;
        $header->save();

        //Send notification to
        //Driver, Admin Wastebank
        $transactionDB = TransactionHeader::where('transaction_no', $data['transaction_no'])->with('status', 'user', 'transaction_details')->first();
        $title = "Digital Waste Solution";
        $body = "User Mengkonfirmasi Transaksi On Demand";
        $data = array(
            "type_id" => "3",
            "message" => $body,
            'model' => $transactionDB
        );
        //Push Notification to Collector App.
        FCMNotification::SendNotification($header->waste_collector_id, 'collector', $title, $body, $data);
        //Push Notification to Admin.
        $wastebankDB = $header->waste_bank;
        FCMNotification::SendNotification($wastebankDB->pic_id, 'browser', $title, $body, $data);

        return Response::json([
            'message' => "Success Confirming Transaction!",
        ], 200);
    }

    /**
     * Used for Antar Sendiri Transaction when User Confirm The Transaction inputed by Admin.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmTransactionByUserAntarSendiri(Request $request)
    {
        try{
            Log::info('User Confirm Transaction Antar Sendiri!');

            $rules = array(
                'transaction_no' => 'required'
            );

            $data = $request->json()->all();

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }

            $header = TransactionHeader::where('transaction_no', $data['transaction_no'])->first();

            if($header->status_id === 13){
                $header->status_id = 10;
                $header->save();

                //Send notification to
                //Admin Wastebank
                //send notification
                $userName = $header->user->first_name." ".$header->user->last_name;
                $title = "Digital Waste Solution";
                $body = "User Mengkonfirmasi Transaksi Antar Sendiri";
                $data = array(
                    'type_id'           => '2',
                    'is_confirm'        => '1',
                    'transaction_no'    => $data['transaction_no'],
                    'name'              => $userName
                );

                // Tambah poin ke waste source
                $newPoint = intval($header->total_price);

                $user = $header->user;
                $newSaldo = $user->point + $newPoint;
                $user->point = $newSaldo;
                $user->save();

                PointHistory::create([
                    'user_id'           => $header->user_id,
                    'type'              => $header->transaction_type_id,
                    'transaction_id'    => $header->id,
                    'type_transaction'  => "Kredit",
                    'amount'            => $newPoint,
                    'saldo'             => $newSaldo,
                    'description'       => "Point dari transaksi nomor ".$header->transaction_no,
                    'created_at'        => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                ]);

                $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);
            }

            return Response::json([
                'message' => "Success Confirming Transaction!",
            ], 200);
        }
        catch (\Exception $ex){
            Log::error("Api/TransactionHeaderController - confirmTransactionByUserAntarSendiri error: ". $ex);
            return Response::json([
                'ex' => "ex " . $ex
            ], 500);
        }
    }

    /**
     * Used for on demand Transaction when User Cancelled the Transaction.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelTransactionByUserOnDemand(Request $request)
    {
        $rules = array(
            'transaction_no'    => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $header = TransactionHeader::where('transaction_no', $data['transaction_no'])->first();
        $header->status_id = 23;
        $header->save();

        //Send notification to
        //Driver
        //send notification
        $transactionDB = TransactionHeader::where('transaction_no', $data['transaction_no'])->with('status', 'user', 'transaction_details')->first();
        $userName = $header->user->first_name." ".$header->user->last_name;
        $title = "Digital Waste Solution";
        $body = "User Membatalkan Transaksi On Demand";
        $data = array(
            'type_id' => '3',
            'model' => $transactionDB
        );
        $isSuccess = FCMNotification::SendNotification($transactionDB->waste_collector_id, 'collector', $title, $body, $data);

        return Response::json([
            'message' => "Success Cancelling Transaction!",
        ], 200);
    }

    /**
     * Used for Antar Sendiri Transaction when User Cancelled the Transaction.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelTransactionByUserAntarSendiri(Request $request)
    {
        $rules = array(
            'transaction_no'    => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $header = TransactionHeader::where('transaction_no', $data['transaction_no'])->first();

        if($header->status_id === 13){
            $header->status_id = 12;
            $header->save();

            //Send notification to
            //Admin Wastebank
            //send notification
            $userName = $header->user->first_name." ".$header->user->last_name;
            $title = "Digital Waste Solution";
            $body = "User Membatalkan Transaksi Antar Sendiri";
            $data = array(
                'type_id' => '2',
                'is_confirm' => '0',
                'transaction_no' => $data['transaction_no'],
                'name' => $userName
            );
            $isSuccess = FCMNotification::SendNotification2($header->created_by_admin, 'browser', $title, $body, $data);
        }

        return Response::json([
            'message' => "Success Cancelling Transaction!",
        ], 200);
    }

    /**
     * Used for Routine pickup Transaction when user Confirm the Transaction Confirmed by User.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmTransactionByUserRoutinePickup(Request $request)
    {
        $rules = array(
            'transaction_no'    => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $header = TransactionHeader::where('transaction_no', $data['transaction_no'])->first();

        if($header->status_id === 15){
            $header->status_id = 16;
            $header->save();

            // Update pickup status
            $wasteCollectorUser = WasteCollectorUser::where('user_id', $header->user_id)
                ->where('waste_collector_id', $header->waste_collector_id)
                ->first();

            $wasteCollectorUserStatus = WasteCollectorUserStatus::where('waste_collector_user_id', $wasteCollectorUser->id)
                ->first();
            $wasteCollectorUserStatus->status_id = 16;
            $wasteCollectorUserStatus->save();

            //Send notification to
            //Driver, Admin Wastebank
            $title = "Digital Waste Solution";
            $body = "User Mengkonfirmasi Transaksi Rutin Pickup";
            $data = array(
                "data" => [
                    'type_id' => '1',
                    'is_confirm' => '1',
                    'transaction_no' => $data['transaction_no']
                ]
            );
            //Push Notification to Collector App.
            FCMNotification::SendNotification($header->waste_collector_id, 'collector', $title, $body, $data);
            //Push Notification to Admin.
            FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);
        }

        return Response::json([
            'message' => "Success Confirming Transaction!",
        ], 200);
    }

    /**
     * Used for Routine Pickup Transaction when user Cancel the Transaction Confirmed By User.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelTransactionByUserRoutinePickup(Request $request)
    {
        $rules = array(
            'transaction_no'    => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $header = TransactionHeader::where('transaction_no', $data['transaction_no'])->first();
        $header->status_id = 17;
        $header->save();

        //Send notification to
        //Admin Wastebank
        //send notification
        $userName = $header->user->first_name." ".$header->user->last_name;
        $title = "Digital Waste Solution";
        $body = "User Membatalkan Transaksi Rutin Pickup";
        $data = array(
            "data" => [
                'type_id' => '1',
                'is_confirm' => '0',
                'transaction_no' => $data['transaction_no']
            ]
        );
        //Push Notification to Admin.
        FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);

        return Response::json([
            'message' => "Success Cancelling Transaction!",
        ], 200);
    }

    public function test(Request $request){
        $data = $request->json()->all();

        $test = '';
        foreach ($data["details"] as $item){
            $test .= $item["masaro_category_id"] . ' ';
        }
        return $test;
    }

    // Get list of transaction antar sendiri for customer app
    public function getTransactionAntarSendiriForCustomer(Request $request)
    {
        try{
            $customerUser = auth('api')->user();
            $skip = intval($request->input('skip'));

            $transactions = TransactionHeader::with(['transaction_details'])
                ->where('transaction_type_id', 2)
                ->where('user_id', $customerUser->id)
                ->orderBy('created_at', 'desc')
                ->skip($skip)
                ->limit(10)
                ->get();

            if($transactions->count() == 0 && $skip === 0){
                return Response::json([
                    'message' => "No transaction found!",
                ], 482);
            }

            $headerResponses = collect();
            foreach ($transactions as $header){
                $newHeaderResponse = collect([
                    'id'                => $header->id,
                    'transaction_no'    => $header->transaction_no,
                    'waste_bank'        => $header->waste_bank ?? null,
                    'waste_collector'   => $header->waste_collector ?? null,
                    'waste_source'      => !empty($header->user_id) ? $header->user->first_name. ' '. $header->user->last_name : '',
                    'total_weight'      => $header->total_weight / 1000,
                    'total_point'       => intval($header->total_price),
                    'status'            => $header->status_id,
                    'created_at'        => Carbon::parse($header->created_at)->format('d M Y')
                ]);

                // Get transaction credit point amount
//                $point = 0;
//                $customerPointHistory = DB::table('point_histories')
//                    ->select('amount')
//                    ->where('transaction_id', $header->id)
//                    ->where('user_id', $customerUser->id)
//                    ->where('type_transaction', 'credit')
//                    ->first();
//
//                if(!empty($customerPointHistory)){
//                    $point = $customerPointHistory->amount;
//                }
//
//                $newHeaderResponse->put('point', $point);

                // Get waste details
                $trxDetails = $header->transaction_details;

                $detailResponses = collect();
                foreach ($trxDetails as $detail){

                    if(!empty($detail->dws_category_id) && empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'dws_category_id'   => $detail->dws_category_id,
                            'masaro_category_id'=> 0,
                            'waste_name'        => $detail->dws_waste_category_data->name,
                            'point'             => $detail->price,
                            'weight_double'     => $detail->weight / 1000,
                            'weight_str'        => $detail->weight_kg_string
                        ]);
                        $detailResponses->push($newDetailResponse);
                    }
                    elseif(empty($detail->dws_category_id) && !empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'masaro_category_id'=> $detail->masaro_category_id,
                            'dws_category_id'   => 0,
                            'waste_name'        => $detail->masaro_waste_category_data->name,
                            'point'             => $detail->price,
                            'weight_double'     => $detail->weight / 1000,
                            'weight_str'        => $detail->weight_kg_string
                        ]);
                        $detailResponses->push($newDetailResponse);
                    }
                }

                $newHeaderResponse->put('transaction_details', $detailResponses);

                $headerResponses->push($newHeaderResponse);
            }

            return $headerResponses;
        }
        catch(\Exception $ex){
            Log::error("TransactionHeaderController - getTransactionAntarSendiriForCustomer Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    // Get list of transaction antar sendiri for admin app
    public function getTransactionAntarSendiriForAdmin()
    {
        try{
            $adminUser = auth('admin_wastebank')->user();

            $transactions = TransactionHeader::with(['transaction_details'])
                ->where('transaction_type_id', 2)
                ->where('waste_bank_id', $adminUser->waste_bank_id)
//                ->whereNull('user_id')
                ->where('status_id', '!=', 10)
                ->where('status_id', '!=', 12)
                ->orderBy('created_at', 'desc')
                ->get();

            if($transactions->count() == 0){
                return Response::json([
                    'message' => "No transaction found!",
                ], 482);
            }

            $headerResponses = collect();
            foreach ($transactions as $header){
                //Log::info("first name: ". $header->user->first_name);
                $newHeaderResponse = collect([
                    'id'                => $header->id,
                    'transaction_no'    => $header->transaction_no,
                    'total_weight'      => $header->total_weight / 1000,
                    'total_point'       => $header->total_price,
                    'status'            => $header->status_id,
                    'created_at'        => Carbon::parse($header->created_at)->format('d M Y')
                ]);

                // Get waste details
                $trxDetails = $header->transaction_details;

                $detailResponses = collect();
                foreach ($trxDetails as $detail){

                    if(!empty($detail->dws_category_id) && empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'dws_category_id'   => $detail->dws_category_id,
                            'masaro_category_id'=> 0,
                            'waste_name'        => $detail->dws_waste_category_data->name,
                            'point'             => $detail->price,
                            'weight_double'     => $detail->weight / 1000,
                            'weight_str'        => $detail->weight_kg_string
                        ]);
                        $detailResponses->push($newDetailResponse);
                    }
                    elseif(empty($detail->dws_category_id) && !empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'masaro_category_id'=> $detail->masaro_category_id,
                            'dws_category_id'   => 0,
                            'waste_name'        => $detail->masaro_waste_category_data->name,
                            'point'             => $detail->price,
                            'weight_double'     => $detail->weight / 1000,
                            'weight_str'        => $detail->weight_kg_string
                        ]);
                        $detailResponses->push($newDetailResponse);
                    }
                }

                $newHeaderResponse->put('transaction_details', $detailResponses);

                $headerResponses->push($newHeaderResponse);
            }

            return $headerResponses;
        }
        catch(\Exception $ex){
            Log::error("TransactionHeaderController - getTransactionAntarSendiriForAdmin Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}
