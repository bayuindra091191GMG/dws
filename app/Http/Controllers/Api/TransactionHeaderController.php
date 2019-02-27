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
     * @param Request $request
     * @return UserResource
     */
    public function getTransactions(Request $request)
    {
        $user = User::where('email', $request->input('email'));
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
            'email'             => 'required',
            'total_weight'      => 'required',
            'total_price'       => 'required',
            'details'           => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = User::where('email', $data['email'])->first();

        // Generate transaction codes
        $today = Carbon::today()->format("Ym");
        $categoryType = $user->company->waste_category_id;
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
            'updated_at'            => Carbon::now('Asia/Jakarta')
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
            'driver_id'         => 'required',
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
                "type_id" => "DWS - ".$title,
                "message" => $body,
            ]
        );
        $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);

        return Response::json([
            'message' => "Success Confirming Transaction!",
        ], 200);
    }

    /**
     * Used for Antar Sendiri Transaction when Admin Scan the User QR Code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setTransactionToUser(Request $request)
    {
        $rules = array(
            'transaction_no'    => 'required',
            'email'             => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = User::where('email', $data['email'])->first();
        $header = TransactionHeader::where('transaction_no', $data['transaction_no'])->first();
        $header->user_id = $user->id;
        $header->save();

        //send notification
        $title = "Digital Waste Solution";
        $body = "Admin Scan the User QR Code";
        $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body);

        return Response::json([
            'message' => "Success Set " . $user->email . " to " . $header->transaction_no . "!",
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
        FCMNotification::SendNotification($header->waste_collector_id, 'app', $title, $body, $data);

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
        FCMNotification::SendNotification($header->waste_collector_id, 'app', $title, $body, $data);
        //Push Notification to Admin.

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
