<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\PointHistory;
use App\Models\TransactionHeader;
use App\Notifications\FCMNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    /**
     * Function to confirm transaction Antar Sendiri by Admin Wastebank.
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

            //add point to user
            $configuration = Configuration::where('configuration_key', 'point_amount_user')->first();
            $amount = $configuration->configuration_value;
            $userDB = $header->user;
            $newSaldo = $userDB->point + $amount;
            $userDB->point = $newSaldo;
            $userDB->save();

            $point = PointHistory::create([
                'user_id'  => $header->user_id,
                'type'   => $header->transaction_type_id,
                'transaction_id'    => $header->id,
                'type_transaction'   => "Kredit",
                'amount'    => $amount,
                'saldo'    => $newSaldo,
                'description'    => "Point dari transaksi nomor ".$header->transaction_no,
                'created_at'    => Carbon::now('Asia/Jakarta'),
            ]);

            //send notification
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
