<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 01/04/2019
 * Time: 15:25
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\TransactionHeader;
use App\Models\WasteCollectorPickupHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionHeaderRoutineController extends Controller
{
    // Get transaction routine list for customer
    public function getTransactionRoutineForCustomer(Request $request)
    {
        try{
            $customerUser = auth('api')->user();
            $skip = intval($request->input('skip'));

            $transactions = TransactionHeader::with(['transaction_details'])->where('transaction_type', 1)
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
                    'waste_bank'        => $header->waste_bank ?? null,
                    'waste_collector'   => $header->waste_collector ?? null,
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
                            'dws_category_id'   => $detail->dws_category_id,
                            'masaro_category_id'=> 0,
                            'waste_name'        => $detail->dws_waste_category_data->name,
                            'price'             => $detail->price,
                            'weight_double'     => $detail->weight / 1000,
                            'weight_str'        => $detail->weight_kg_string
                        ]);
                        $detailResponses->push($newDetailResponse);
                    }
                    elseif(empty($detail->dws_category_id) && !empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'dws_category_id'   => 0,
                            'waste_id'          => $detail->masaro_category_id,
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
        catch(Exception $ex){
            Log::error("TransactionHeaderRoutineController - getTransactionRoutineForCustomer Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }

    // Get transaction routine List for waste collector
    public function getTransactionRoutineForWasteCollector(Request $request)
    {
        try{
            //$data = $request->json()->all();
            $wasteCollector = auth('waste_collector')->user();

            // 0 = get all
            // 1 = today
            // 2 = 1 week
            // 3 = 1 month
            $filterIntervalId = $request->input('filter_interval_id');

            $pickupHistories = WasteCollectorPickupHistory::with(['transaction_header'])->where('waste_collector_user_id', $wasteCollector->id);

            if ($filterIntervalId == 1) {
                $today = Carbon::today('Asia/Jakarta');
                $pickupHistories = $pickupHistories->where('created_at', '>=', $today->toDateTimeString());
            } elseif ($filterIntervalId == 2) {
                $weekAgo = Carbon::today('Asia/Jakarta')->subDays(7);
                $pickupHistories = $pickupHistories->where('created_at', '>=', $weekAgo->toDateTimeString());
            } elseif ($filterIntervalId == 3) {
                $weekAgo = Carbon::today('Asia/Jakarta')->subMonths(1);
                $pickupHistories = $pickupHistories->where('created_at', '>=', $weekAgo->toDateTimeString());
            }

            $pickupHistories = $pickupHistories->orderBy('created_at', 'desc')->get();

            if($pickupHistories->count() == 0){
                return Response::json([
                    'message' => "No transaction history found!",
                ], 482);
            }

            $headerResponses = collect();
            foreach ($pickupHistories as $history){
                if(!empty($history->transaction_header_id)){
                    $header = $history->transaction_header;
                    $newHeaderResponse = collect([
                        'transaction_no'    => $header->transaction_no,
                        'customer_name'     => $header->user->first_name. " ". $header->user->last_name,
                        'customer_image'    => $header->user->image_path,
                        'latitude'          => $header->latitude,
                        'longitude'         => $header->longitude,
                        'total_weight'      => $header->total_weight / 1000,
                        'status'            => $header->status_id,
                        'created_at'        => Carbon::parse($header->created_at)->format('d M Y')
                    ]);

                    // Checking customer address
                    $customerAddress = null;
                    if($header->user->address->count() > 0){
                        $addressObj = $header->user->address->first();
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

                    $detailResponses = collect();
                    foreach ($trxDetails as $detail){

                        if(!empty($detail->dws_category_id) && empty($detail->masaro_category_id)){
                            $newDetailResponse = collect([
                                'dws_category_id'   => $detail->dws_category_id,
                                'masaro_category_id'=> 0,
                                'waste_name'        => $detail->dws_waste_category_data->name,
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
                                'weight_double'     => $detail->weight / 1000,
                                'weight_str'        => $detail->weight_kg_string
                            ]);
                            $detailResponses->push($newDetailResponse);
                        }
                    }

                    $newHeaderResponse->put('transaction_details', $detailResponses);

                    $headerResponses->push($newHeaderResponse);
                }
                else{
                    $newHeaderResponse = collect([
                        'customer_name'         => $history->user->first_name. " ". $history->user->last_name,
                        'customer_image'        => $history->user->image_path,
                        'latitude'              => null,
                        'longitude'             => null,
                        'total_weight'          => null,
                        'status'                => $history->status_id,
                        'customer_address'      => null,
                        'point'                 => null,
                        'transaction_details'   => null
                    ]);

                    $headerResponses->push($newHeaderResponse);
                }
            }

            return $headerResponses;
        }
        catch(Exception $ex){
            Log::error("TransactionHeaderRoutineController - getTransactionRoutineForWasteCollector Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}