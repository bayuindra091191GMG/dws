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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionHeaderRoutineController extends Controller
{
    // Get transaction routine List for waste collector
    public function getTransactionRoutineForWasteCollector(Request $request)
    {
        try{
            $data = $request->json()->all();
            $wasteCollector = auth('waste_collector')->user();

            // 0 = get all
            // 1 = today
            // 2 = 1 week
            // 3 = 1 month
            $filterIntervalId = $data['filter_interval_id'];

            $transactions = TransactionHeader::where('transaction_type_id', 1)
                ->where('waste_collector_id', $wasteCollector->id);

            if ($filterIntervalId == 1) {
                $today = Carbon::today('Asia/Jakarta');
                $transactions->where('created_at', '>=', $today->toDateTimeString());
            } elseif ($filterIntervalId == 2) {
                $weekAgo = Carbon::today('Asia/Jakarta')->subDays(7);
                $transactions->where('created_at', '>=', $weekAgo->toDateTimeString());
            } elseif ($filterIntervalId == 3) {
                $weekAgo = Carbon::today('Asia/Jakarta')->subMonths(1);
                $transactions->where('created_at', '>=', $weekAgo->toDateTimeString());
            }

            $transactions->orderBy('created_at', 'desc')->get();

            $headerResponses = collect();
            foreach ($transactions as $header){
                $newHeaderResponse = collect([
                    'customer_name'     => $header->user->first_name. " ". $header->user->last_name,
                    'customer_image'    => $header->user->image_path,
                    'latitude'          => $header->latitude,
                    'longitude'         => $header->longitude,
                    'total_weight'      => $header->total_weight / 1000,
                    'status'            => 'Berhasil'
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
                            'waste_name'        => $detail->dws_waste_category_data->name,
                            'weight_double'     => $detail->weight,
                            'weight_str'        => $detail->weight_string
                        ]);
                        $detailResponses->push($newDetailResponse);
                    }
                    elseif(!empty($detail->dws_category_id) && empty($detail->masaro_category_id)){
                        $newDetailResponse = collect([
                            'waste_name'        => $detail->masaro_waste_category_data->name,
                            'weight_double'     => $detail->weight,
                            'weight_str'        => $detail->weight_string
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
            Log::error("TransactionHeaderRoutineController - getTransactionRoutineForWasteCollector Error: ". $ex);
            return Response::json([
                'message' => "Sorry Something went Wrong!",
                'ex' => $ex,
            ], 500);
        }
    }
}