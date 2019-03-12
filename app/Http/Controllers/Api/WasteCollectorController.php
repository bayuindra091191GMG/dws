<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\User;
use App\Models\WasteBankSchedule;
use App\Models\WasteCollector;
use App\Models\WasteCollectorUser;
use App\Models\WasteCollectorWasteBank;
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
        try {
            $wasteCollector = WasteCollector::where('phone', $request->input('phone'))->first();

            return $wasteCollector;
        } catch (\Exception $ex) {
            return Response::json(
                $ex
                , 500);
        }
    }

    /**
     * Function to save WasteCollector Device ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveCollectorToken(Request $request)
    {
        try {
            $data = $request->json()->all();

            $collector = WasteCollector::where('phone', $data['phone'])->first();

            //Save user deviceID
            FCMNotification::SaveToken($collector->id, $data['device_id'], "collector");

            return Response::json([
                'message' => "Success Save Collector Token!",
            ], 200);
        } catch (\Exception $ex) {
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
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
        try {
            $wasteCollector = WasteCollector::where('phone', $request->input('phone'))->first();
            //$wasteCategoryId = $wasteCollector->company->waste_category_id;

//            $users = User::where('routine_pickup', 1)->whereHas('company', function($query) use ($wasteCategoryId){
//                $query->waste_category_id = $wasteCategoryId;
//            })->get();

            $collectorWasteBank = WasteCollectorWasteBank::where('waste_collector_id', $wasteCollector->id)->first();
            if (empty($collectorWasteBank)) {
                return Response::json([
                    'routine_pickup_list' => null,
                    'total_weight' => 0,
                    'total_point' => 0,
                    'total_household' => 0,
                    'total_household_done' => 0
                ], 482);
            }

            //get current day of week, and compare for wastebank schedule
            //Day of week number (between 1 (monday) and 7 (sunday))
            $currentday = Carbon::now()->dayOfWeekIso;
            $wasteBankSchedule = WasteBankSchedule::where('waste_bank_id', $collectorWasteBank->waste_bank_id)
                ->where('day', $currentday)->first();
            if (empty($wasteBankSchedule)) {
                return Response::json([
                    'routine_pickup_list' => null,
                    'total_weight' => 0,
                    'total_point' => 0,
                    'total_household' => 0,
                    'total_household_done' => 0
                ], 482);
            }

            //Get Users By Assign Table
            $data = WasteCollectorUser::where('waste_collector_id', $wasteCollector->id)->with('user')->get();

            //get Total household
            $totalHousehold = $data->count();

            //get total household done, total point, total weight
            //Should Compare List if the User Transaction has Done
            $totalHouseholdDone = 0;
            $totalWeight = 0;
            $totalPoint = 0;
            $pickUpModel = [];
            foreach ($data as $wasteCollectorUser) {
                $weight = 0;
                $point = 0;

                //summary total weight of transaction routine pickup and total household
                $transactionDBRoutine = TransactionHeader::where('user_id', $wasteCollectorUser->user_id)
                    ->where('status_id', 16)
                    ->first();
                if (!empty($transactionDBRoutine)) {
                    $totalHouseholdDone++;
                    $weight = $transactionDBRoutine->total_weight;
                    $totalWeight = $totalWeight + $transactionDBRoutine->total_weight;
                    $totalPoint = $transactionDBRoutine->waste_collector->point;
                }

                //summary total weight of transaction on demand
                $transactionDBOnDemand = TransactionHeader::where('user_id', $wasteCollectorUser->user_id)
                    ->where('status_id', 8)
                    ->first();
                if (!empty($transactionDBRoutine)) {
                    $weight = $transactionDBOnDemand->total_weight;
                    $totalWeight = $totalWeight + $transactionDBOnDemand->total_weight;
                    $totalPoint = $transactionDBRoutine->waste_collector->point;
                }
                $addressDb = Address::where('user_id', $wasteCollectorUser->user_id)
                    ->where('primary', 1)
                    ->first();
                $data = array(
                    "id" => $wasteCollectorUser->id,
                    "img_path" => $wasteCollectorUser->user->image_path,
                    "first_name" => $wasteCollectorUser->user->first_name,
                    "last_name" => $wasteCollectorUser->user->last_name,
                    "description" => $addressDb->description,
                    "latitude" => $addressDb->latitude,
                    "longitude" => $addressDb->longitude,
                    "weight" => $weight,
                    "point" => $point,
                );
                array_push($pickUpModel, $data);
            }

            return Response::json([
                'routine_pickup_list' => $pickUpModel,
                'total_weight' => $totalWeight,
                'total_point' => $totalPoint,
                'total_household' => $totalHousehold,
                'total_household_done' => $totalHouseholdDone
            ], 200);

        } catch (\Exception $ex) {
            return Response::json([
                'message' => $ex,
            ], 500);
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
            'user_email' => 'required',
            'waste_collector_phone' => 'required',
            'total_weight' => 'required',
            'total_price' => 'required',
            'details' => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $wasteCollector = WasteCollector::where('phone', $request->input('waste_collector_phone'))->first();
        $user = User::where('email', $data['email'])->first();

        // Generate transaction codes
        $today = Carbon::today()->format("Ym");
        $categoryType = $user->company->waste_category_id;
        if ($categoryType == "1") {
            $prepend = "TRANS/DWS/" . $today;
        } else {
            $prepend = "TRANS/MASARO/" . $today;
        }

        $nextNo = Utilities::GetNextTransactionNumber($prepend);
        $code = Utilities::GenerateTransactionNumber($prepend, $nextNo);

        //Awaiting Bayu Create Transaction Number
        $header = TransactionHeader::create([
            'transaction_no' => $code,
            'total_weight' => $data["total_weight"],
            'total_price' => $data["total_price"],
            'status_id' => 15,
            'user_id' => $user->id,
            'transaction_type_id' => 1,
            'waste_category_id' => $user->company->waste_category_id,
            'waste_collector_id' => $wasteCollector->id,
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta')
        ]);

        //do detail
        foreach ($data['details'] as $item) {
            if ($user->company->waste_category_id == 1) {
                TransactionDetail::create([
                    'transaction_header_id' => $header->id,
                    'dws_category_id' => $item['dws_category_id'],
                    'weight' => $item['weight'],
                    'price' => $item['price']
                ]);
            } else if ($user->company->waste_category_id == 2) {
                TransactionDetail::create([
                    'transaction_header_id' => $header->id,
                    'masaro_category_id' => $item['masaro_category_id'],
                    'weight' => $item['weight'],
                    'price' => $item['price']
                ]);
            }
        }

        //Send notification to
        //Driver, Admin Wastebank
        $title = "Digital Waste Solution";
        $body = "Driver Create Transaction Routine Pickup";
        $data = array(
            "data" => [
                "type_id" => "1",
                "transaction_id" => $header->id,
                "transaction_date" => Carbon::parse($header->date)->format('j-F-Y H:i:s'),
                "transaction_no" => $header->transaction_no,
                "name" => $user->first_name . " " . $user->last_name,
                "waste_category_name" => $body,
                "total_weight" => $header->total_weight,
                "total_price" => $header->total_price,
                "waste_bank" => "-",
                "waste_collector" => $wasteCollector->phone,
                "status" => $header->status->description,
            ]
        );
        $isSuccess = FCMNotification::SendNotification($user->id, 'collector', $title, $body, $data);
        //Push Notification to Admin.
//      $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);

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
        try {
            $wasteCollector = WasteCollector::where('phone', $request->input('phone'))->first();
            $transactions = TransactionHeader::with('status')->where('waste_collector_id', $wasteCollector->id)->get();

            return $transactions;
        } catch (\Exception $ex) {
            return Response::json("Sorry something went wrong!", 500);
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
            'email' => 'required',
            'total_weight' => 'required',
            'total_price' => 'required',
            'details' => 'required',
            'transaction_no' => 'required',
            'is_edit' => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        $user = User::where('email', $data['email'])->first();

        if ($request->input('is_edit')) {
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
            //User, Admin Wastebank
            $title = "Digital Waste Solution";
            $body = "Driver Mengkonfirmasi Transaksi On Demand!";
            $data = array(
                "data" => [
                    "type_id" => "3",
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
            //Push Notification to Admin.
//            $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);

            return Response::json([
                'message' => "Berhasil mengkonfirmasi dan mengubah Transaksi On demand!",
            ], 200);
        } else {
            $header = TransactionHeader::where('transaction_no', $request->input('transaction_no'))->first();
            $header->updated_at = Carbon::now('Asia/Jakarta');
            $header->status_id = 7;
            $header->save();

            //Send notification to
            //User, Admin Wastebank
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
            //Push Notification to Admin.
//            $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'browser', $title, $body, $data);

            return Response::json([
                'message' => "Berhasil mengkonfirmasi Transaksi On Demand!",
            ], 200);
        }
    }
}
