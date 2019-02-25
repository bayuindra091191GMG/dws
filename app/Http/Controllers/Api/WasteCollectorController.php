<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\User;
use App\Models\WasteCollector;
use App\Notifications\FCMNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\libs\Utilities;

class WasteCollectorController extends Controller
{
    /**
     * Function to get WasteCollector Details.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        try{
            $wasteCollector = WasteCollector::where('email', $request->input('email'))->first();

            return $wasteCollector;
        }
        catch (\Exception $ex){
            return Response::json(
                $ex
                , 500);
        }
    }

    /**
     * Function to get the User List for Routine Pickup.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserListRoutinePickUp(Request $request)
    {
        //Get the Data based on Driver Data
        try{
            $wasteCollector = WasteCollector::where('email', $request->input('email'))->first();
            $wasteCategoryId = $wasteCollector->company->waste_category_id;

            $users = User::where('routine_pickup', 1)->whereHas('company', function($query) use ($wasteCategoryId){
                $query->waste_category_id = $wasteCategoryId;
            })->get();

            return $users;
        }
        catch (\Exception $ex){
            return Response::json(
                $ex
            , 500);
        }
    }

    /**
     * Create a new Transaction when on Routine Pickup.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function createTransactionRoutinePickup(Request $request)
    {
        $rules = array(
            'user_email'            => 'required',
            'waste_collector_email' => 'required',
            'total_weight'          => 'required',
            'total_price'           => 'required',
            'details'               => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $wasteCollector = WasteCollector::where('email', $request->input('waste_collector_email'))->first();
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
            'status_id'             => 15,
            'user_id'               => $user->id,
            'transaction_type_id'   => 1,
            'waste_category_id'     => $user->company->waste_category_id,
            'waste_collector_id'    => $wasteCollector->id,
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
        $body = "Driver Create Transaction Routine Pickup";
        $data = array(
            "data" => [
                "type_id" => "2",
                "transaction_id" => $header->id,
                "transaction_date" => Carbon::parse($header->date)->format('j-F-Y H:i:s'),
                "transaction_no" => $header->transaction_no,
                "name" => $user->first_name." ".$user->last_name,
                "waste_category_name" => $body,
                "total_weight" => $header->total_weight,
                "total_price" => $header->total_price,
                "waste_bank" => "-",
                "waste_collector" => $wasteCollector->email,
                "status" => $header->status->description,
            ]
        );
        $isSuccess = FCMNotification::SendNotification($user->id, 'app', $title, $body, $data);

        return Response::json([
            'message' => "Success creating Routine Pickup Transaction!",
        ], 200);
    }

    /**
     * Function to show all WasteCollector Transactions Related.
     *
     * @param Request $request
     * @return TransactionHeader[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function getAllTransactions(Request $request)
    {
        try{
            $wasteCollector = WasteCollector::where('email', $request->input('email'))->first();
            $transactions = TransactionHeader::with('status')->where('waste_collector_id', $wasteCollector->id)->get();

            return $transactions;
        }
        catch (\Exception $ex){
            return Response::json("Sorry something went wrong!",500);
        }
    }

    /**
     * On Demand.
     * Function for WasteCollector to edit on Demand Transaction.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmOnDemandTransaction(Request $request)
    {
        $rules = array(
            'email'             => 'required',
            'total_weight'      => 'required',
            'total_price'       => 'required',
            'details'           => 'required',
            'transaction_no'    => 'required',
            'is_edit'           => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        $user = User::where('email', $data['email'])->first();

        if($request->input('is_edit')) {
            $header = TransactionHeader::where('transaction_no', $request->input('transaction_no'))->first();
            $header->total_weight = $data['total_weight'];
            $header->total_price = $data['total_price'];
            $header->updated_at = Carbon::now('Asia/Jakarta');
            $header->status_id = 7;
            $header->save();

            //do detail
            $i = 0;
            foreach ($data['details'] as $item) {
                $detail = $header->transaction_details[$i];
                $detail->weight = $item['weight'];
                $detail->price = $item['price'];
                $detail->save();
                $i++;
            }

            //Send notification to
            //Driver, Admin Wastebank
            $title = "Digital Waste Solution";
            $body = "Driver Mengkonfirmasi Transaksi On Demand!";
            $data = array(
                "data" => [
                    "type_id" => "2",
                    "transaction_id" => $header->id,
                    "transaction_date" => Carbon::parse($header->date)->format('j-F-Y H:i:s'),
                    "transaction_no" => $header->transaction_no,
                    "name" => $user->first_name . " " . $user->last_name,
                    "waste_category_name" => $body,
                    "total_weight" => $header->total_weight,
                    "total_price" => $header->total_price,
                    "waste_bank" => "-",
                    "waste_collector" => "-",
                    "status" => $header->status->description,
                ]
            );
            $isSuccess = FCMNotification::SendNotification($user->id, 'app', $title, $body, $data);

            return Response::json([
                'message' => "Berhasil mengkonfirmasi dan mengubah Transaksi On demand!",
            ], 200);
        }
        else{
            $header = TransactionHeader::where('transaction_no', $request->input('transaction_no'))->first();
            $header->updated_at = Carbon::now('Asia/Jakarta');
            $header->status_id = 7;
            $header->save();

            //Send notification to
            //Driver, Admin Wastebank
            $title = "Digital Waste Solution";
            $body = "Driver Mengkonfirmasi Transaksi On Demand!";
            $data = array(
                "data" => [
                    "type_id" => "2",
                    "transaction_id" => $header->id,
                    "transaction_date" => Carbon::parse($header->date)->format('j-F-Y H:i:s'),
                    "transaction_no" => $header->transaction_no,
                    "name" => $user->first_name . " " . $user->last_name,
                    "waste_category_name" => $body,
                    "total_weight" => $header->total_weight,
                    "total_price" => $header->total_price,
                    "waste_bank" => "-",
                    "waste_collector" => "-",
                    "status" => $header->status->description,
                ]
            );
            $isSuccess = FCMNotification::SendNotification($user->id, 'app', $title, $body, $data);

            return Response::json([
                'message' => "Berhasil mengkonfirmasi Transaksi On Demand!",
            ], 200);
        }
    }
}
