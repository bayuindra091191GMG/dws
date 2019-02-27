<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionHeader;
use App\Notifications\FCMNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    /**
     * Function to confirm transaction by Admin Wastebank.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmTransactionAntarSendiri(Request $request)
    {
        try{
            $header = TransactionHeader::where('transaction_no', $request->input('transaction_no'))->first();
            $header->status_id = 11;
            $header->save();

            $userName = $header->user->first_name." ".$header->user->last_name;
            $title = "Digital Waste Solution";
            $body = "Wastebank Mengkonfirmasi Transaksi";
            $data = array(
                'type_id' => '2',
                'transaction_no' => $header->transaction_no,
                'name' => $userName
            );
            $isSuccess = FCMNotification::SendNotification($header->created_by_admin, 'app', $title, $body, $data);

            return Response::json("Transaction Confirmed!", 200);
        }
        catch (\Exception $ex){
            return Response::json("Sorry Something went Wrong!", 500);
        }
    }
}
