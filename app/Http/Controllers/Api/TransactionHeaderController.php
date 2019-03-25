<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\libs\Utilities;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\User;
use App\Notifications\FCMNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionHeaderController extends Controller
{
    /**
     * Function to get the Transaction Data Details.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function createTransaction(Request $request)
    {
        $rules = array(
            'total_weight'      => 'required',
            'total_price'       => 'required',
            'details'           => 'required',
            'latitude'          => 'required',
            'longitude'         => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = auth('api')->user();

        // Generate transaction codes
        $today = Carbon::today()->format("Ym");
        $categoryType = $user->company->waste_category_id;

        //Check for Nearest Wastebank
        $wasteBankId = '';
        $wasteBankTemp = DB::table("waste_banks")
            ->select("*"
                ,DB::raw("6371 * acos(cos(radians(" . $request->input('latitude') . ")) 
                    * cos(radians(waste_banks.latitude)) 
                    * cos(radians(waste_banks.longitude) - radians(" . $request->input('longitude') . ")) 
                    + sin(radians(" .$request->input('latitude'). ")) 
                    * sin(radians(waste_banks.latitude))) AS distance"))
            ->get();
        $now = Carbon::now('Asia/Jakarta');

        $wasteBanks = $wasteBankTemp->where('distance', '<=', 20)
            ->where('waste_category_id', $categoryType)
            ->where('open_hours', '<', $now->toTimeString())
            ->where('closed_hours', '>', $now->toTimeString());

        if($wasteBanks == null || $wasteBanks->count() == 0){
            return response()->json("No Nearest Wastebank Detected!", 400);
        }
        else{
            $tmp = $wasteBanks->first();
            $wasteBankId = $tmp->id;
        }

        if($categoryType == "1"){
            $prepend = "TRANS/DWS/". $today;
        }
        else{
            $prepend = "TRANS/MASARO/". $today;
        }

        $nextNo = Utilities::GetNextTransactionNumber($prepend);
        $code = Utilities::GenerateTransactionNumber($prepend, $nextNo);

        //Awaiting Bayu Create Transaction Number
        $header = TransactionHeader::create([
            'transaction_no'        => $code,
            'total_weight'          => $data["total_weight"],
            'total_price'           => $data["total_price"],
            'status_id'             => 6,
            'user_id'               => $user->id,
            'transaction_type_id'   => 3,
            'waste_category_id'     => $user->company->waste_category_id,
            'created_at'            => Carbon::now('Asia/Jakarta'),
            'updated_at'            => Carbon::now('Asia/Jakarta'),
            'waste_bank_id'         => $wasteBankId
        ]);

        //do detail
        foreach ($data['details'] as $item){
            if($user->company->waste_category_id == 1) {
                TransactionDetail::create([
                    'transaction_header_id' => $header->id,
                    'dws_category_id'       => $item['dws_category_id'],
                    'weight'                => $item['weight'],
                    'price'                 => $item['price']
                ]);
            }
            else if($user->company->waste_category_id == 2){
                TransactionDetail::create([
                    'transaction_header_id' => $header->id,
                    'masaro_category_id'    => $item['masaro_category_id'],
                    'weight'                => $item['weight'],
                    'price'                 => $item['price']
                ]);
            }
        }

        //Send notification to
        //Driver, Admin Wastebank
        $title = "Digital Waste Solution";
        $body = "User Membuat Transaksi On Demand";
        $data = array(
            "data" => [
                "type_id" => "3",
                "transaction_id" => $header->id,
                "transaction_date" => Carbon::parse($header->date)->format('j-F-Y H:i:s'),
                "transaction_no" => $header->transaction_no,
                "name" => $user->first_name." ".$user->last_name,
                "waste_category_name" => $body,
                "total_weight" => $header->total_weight,
                "total_price" => $header->total_price,
                "waste_bank" => "-",
                "waste_collector" => "-",
                "status" => $header->status->description,
            ]
        );
        $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);

        return Response::json([
            'message' => "Success creating On demand Transaction!",
        ], 200);
    }

    /**
     * Used for On demand transaction when Driver Scan user QR Code then Confirm the Transaction's Details.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
            $header->total_weight = $data['total_weight'];
            $header->total_price = $data['total_price'];
            $header->status_id = 7;
            $header->save();

            foreach ($header->transaction_details as $detail)
            {
                foreach ($data['details'] as $item) {
                    if($detail->id == $item['id']) {
                        if ($header->user->company->waste_category_id == 1) {
                            $detail->dws_category_id = $item['dws_category_id'];
                            $detail->weight = $item['weight'];
                            $detail->price = $item['price'];
                            $detail->save();
                        } else if ($header->user->company->waste_category_id == 2) {
                            $detail->masaro_category_id = $item['masaro_category_id'];
                            $detail->weight = $item['weight'];
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
     * @return \Illuminate\Http\JsonResponse
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

        $header = TransactionHeader::find($data['transaction_id']);
        $header->status_id = 8;
        $header->save();

        //Send notification to
        //Driver, Admin Wastebank
        $title = "Digital Waste Solution";
        $body = "User Mengkonfirmasi Transaksi On Demand";
        $data = array(
            "data" => [
                "type_id" => "3",
                "message" => $body,
            ]
        );
        //Push Notification to Collector App.
        FCMNotification::SendNotification($header->waste_collector_id, 'collector', $title, $body, $data);
        //Push Notification to Admin.
        $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);

        return Response::json([
            'message' => "Success Confirming Transaction!",
        ], 200);
    }

    /**
     * Used for Antar Sendiri Transaction when User Confirm The Transaction inputed by Admin.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmTransactionByUserAntarSendiri(Request $request)
    {
        $rules = array(
            'transaction_no' => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $header = TransactionHeader::where('transaction_no', $data['transaction_no'])->first();
        $header->status_id = 10;
        $header->save();

        //Send notification to
        //Admin Wastebank
        //send notification
        $userName = $header->user->first_name." ".$header->user->last_name;
        $title = "Digital Waste Solution";
        $body = "User Mengkonfirmasi Transaksi Antar Sendiri";
        $data = array(
            'type_id' => '2',
            'transaction_no' => $data['transaction_no'],
            'name' => $userName
        );
        $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);

        return Response::json([
            'message' => "Success Confirming Transaction!",
        ], 200);
    }

    /**
     * Used for Antar Sendiri Transaction when User Cancelled the Transaction.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
        $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);

        return Response::json([
            'message' => "Success Cancelling Transaction!",
        ], 200);
    }

    /**
     * Used for Routine pickup Transaction when user Confirm the Transaction Confirmed by User.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
        $header->status_id = 16;
        $header->save();

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

        return Response::json([
            'message' => "Success Confirming Transaction!",
        ], 200);
    }

    /**
     * Used for Routine Pickup Transaction when user Cancel the Transaction Confirmed By User.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
}
