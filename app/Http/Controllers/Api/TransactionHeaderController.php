<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TransactionHeaderController extends Controller
{
    public function getTransactions(Request $request)
    {
        $user = User::where('email', $request->input('email'));
        $transactions = TransactionHeader::where('user_id', $user->id)->get();

        return new UserResource($transactions);
    }

    public function getTransactionDetails(Request $request)
    {
        $detailTransactions = TransactionDetail::where('transaction_header_id', $request->input('transaction_header_id'))->get();

        return new UserResource($detailTransactions);
    }

    /**
     * Create a new Transaction when on Demand
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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

        //Awaiting Bayu Create Transaction Number
        $header = TransactionHeader::create([
            'transaction_no'        => 'asdf',
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
            'transaction_id'    => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $header = TransactionHeader::find($data['transaction_id']);
        $header->status_id = 10;
        $header->save();

        //Send notification to
        //Admin Wastebank

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
            'transaction_id'    => 'required'
        );

        $data = $request->json()->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $header = TransactionHeader::find($data['transaction_id']);
        $header->status_id = 12;
        $header->save();

        //Send notification to
        //Admin Wastebank

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
