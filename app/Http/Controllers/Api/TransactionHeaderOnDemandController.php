<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 02/04/2019
 * Time: 10:03
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\TransactionHeader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class TransactionHeaderOnDemandController extends Controller
{
    public function getTransactionOnDemandForCustomer(Request $request)
    {
        try{
            $customerUser = auth('api')->user();
            $skip = intval($request->input('skip'));

            $transactions = TransactionHeader::with(['transaction_details'])
                ->where('transaction_type_id', 3)
                ->where('user_id', $customerUser->id)
                ->orderBy('created_at', 'desc')
                ->skip($skip)
                ->limit(10)
                ->get();

            if($transactions->count() == 0){
                return Response::json([
                    'message' => "No transaction history found!",
                ], 482);
            }

            $headerResponses = collect();
            foreach ($transactions as $header){
                $newHeaderResponse = collect([
                    'id'                => $header->id,
                    'transaction_no'    => $header->transaction_no,
                    'total_weight'      => $header->total_weight / 1000,
                    'total_price'       => $header->total_price,
                    'status'            => $header->status_id,
                    'created_at'        => Carbon::parse($header->created_at)->format('d M Y')
                ]);

                // Get transaction credit point amount
                $point = 0;
                $customerPointHistory = DB::table('point_histories')
                    ->select('amount')
                    ->where('transaction_id', $header->id)
                    ->where('user_id', $customerUser->id)
                    ->where('type_transaction', 'credit')
                    ->first();

                if(!empty($customerPointHistory)){
                    $point = $customerPointHistory->amount;
                }

                $newHeaderResponse->put('point', $point);

                // Get waste details
                $trxDetails = $header->transaction_details;

                $detailResponses = collect();
                foreach ($trxDetails as $detail){

                    if(!empty($detail->dws_category_id) && empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'waste_name'        => $detail->dws_waste_category_data->name,
                            'price'             => $detail->price,
                            'weight_double'     => $detail->weight / 1000,
                            'weight_str'        => $detail->weight_kg_string
                        ]);
                        $detailResponses->push($newDetailResponse);
                    }
                    elseif(empty($detail->dws_category_id) && !empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'waste_name'        => $detail->masaro_waste_category_data->name,
                            'price'             => $detail->price,
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
            Log::error("TransactionHeaderOnDemandController - getTransactionOnDemandForCustomer Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    public function getTransactionOnDemandForWasteCollector(Request $request)
    {
        try{
            //$data = $request->json()->all();
            $wasteCollector = auth('waste_collector')->user();

            // 0 = get all
            // 1 = today
            // 2 = 1 week
            // 3 = 1 month
            $filterIntervalId = $request->input('filter_interval_id');

            $transactions = TransactionHeader::where('transaction_type_id', 3)
                ->where('waste_collector_id', $wasteCollector->id);

            if ($filterIntervalId == 1) {
                $today = Carbon::today('Asia/Jakarta');
                $transactions = $transactions->where('created_at', '>=', $today->toDateTimeString());
            } elseif ($filterIntervalId == 2) {
                $weekAgo = Carbon::today('Asia/Jakarta')->subDays(7);
                $transactions = $transactions->where('created_at', '>=', $weekAgo->toDateTimeString());
            } elseif ($filterIntervalId == 3) {
                $weekAgo = Carbon::today('Asia/Jakarta')->subMonths(1);
                $transactions = $transactions->where('created_at', '>=', $weekAgo->toDateTimeString());
            }

            $transactions = $transactions->orderBy('created_at', 'desc')->get();

            if($transactions->count() == 0){
                return Response::json([
                    'message' => "No transaction history found!",
                ], 482);
            }

            $headerResponses = collect();
            foreach ($transactions as $header){
                //Log::info("first name: ". $header->user->first_name);
                $newHeaderResponse = collect([
                    'customer_name'     => $header->user->first_name. " ". $header->user->last_name,
                    'customer_image'    => $header->user->image_path,
                    'latitude'          => $header->latitude,
                    'longitude'         => $header->longitude,
                    'total_weight'      => $header->total_weight / 1000,
                    'status'            => $header->status_id
                ]);

                //Log::info("customer name 1: ". $newHeaderResponse->customer_name);

                // Checking customer address
                $customerAddress = null;
                if($header->user->addresses->count() > 0){
                    $addressObj = $header->user->addresses->first();
                    if(!empty($addressObj->description) && !empty($addressObj->latitude) && !empty($addressObj->longitude)){
                        if($header->latitude == $addressObj->latitude && $header->longitude == $addressObj->longitude){
                            $customerAddress = $addressObj->description;

                        }
                    }
                }

                $newHeaderResponse->put('customer_address', $customerAddress);

                // Get transaction credit point amount
                $point = 0;
                $wasteCollectorPointHistory = DB::table('point_wastecollector_histories')
                    ->select('amount')
                    ->where('transaction_id', $header->id)
                    ->where('wastecollector_id', $wasteCollector->id)
                    ->where('type_transaction', 'credit')
                    ->first();

                if(!empty($wasteCollectorPointHistory)){
                    $point = $wasteCollector->amount;
                }

                $newHeaderResponse->put('point', $point);

                // Get waste details
                $trxDetails = $header->transaction_details;

                //Log::info("transaction details count: ". $trxDetails->count());

                $detailResponses = collect();
                foreach ($trxDetails as $detail){

                    if(!empty($detail->dws_category_id) && empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'waste_name'        => $detail->dws_waste_category_data->name,
                            'weight_double'     => $detail->weight / 1000,
                            'weight_str'        => $detail->weight_kg_string
                        ]);
                        $detailResponses->push($newDetailResponse);
                    }
                    elseif(empty($detail->dws_category_id) && !empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'waste_name'        => $detail->masaro_waste_category_data->name,
                            'weight_double'     => $detail->weight / 1000,
                            'weight_str'        => $detail->weight_kg_string
                        ]);
                        $detailResponses->push($newDetailResponse);
                    }
                }

                //Log::info("transaction response count: ". $detailResponses->count());

                $newHeaderResponse->put('transaction_details', $detailResponses);

                //Log::info("customer name 2: ". $newHeaderResponse->customer_name);

                $headerResponses->push($newHeaderResponse);
            }

            //Log::info("responses count: ". $headerResponses->count());

            return $headerResponses;
        }
        catch(\Exception $ex){
            Log::error("TransactionHeaderOnDemandController - getTransactionOnDemandForWasteCollector Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}