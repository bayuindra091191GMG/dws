<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\PointHistory;
use App\Models\TransactionHeader;
use Carbon\Carbon;

class ScriptController extends Controller
{
    public function antarSendiriUserConfirm(int $trx_id){
        $header = TransactionHeader::find($trx_id);
        if($header->status_id === 10){
            return 'ALREADY SCRIPTED';
        }

        $header->status_id = 10;
        $header->save();

        // Tambah poin ke waste source
        $user = $header->user;
        $newSaldo = $user->point + $header->total_price;
        $user->point = $newSaldo;
        $user->save();

        PointHistory::create([
            'user_id'           => $header->user_id,
            'type'              => $header->transaction_type_id,
            'transaction_id'    => $header->id,
            'type_transaction'  => "Kredit",
            'amount'            => $header->total_price,
            'saldo'             => $newSaldo,
            'description'       => "Point dari transaksi nomor ".$header->transaction_no,
            'created_at'        => Carbon::now('Asia/Jakarta')->toDateTimeString(),
        ]);

        return 'SCRIPT SUCCESS!!';
    }

    public function refreshPointTransaction(){
        try{
            $headers = TransactionHeader::where('created_at', '>', '2019-08-07')
                ->where('waste_bank_id', 6)
                ->get();

            foreach ($headers as $header){
                $oldPrice = $header->total_price;
                $weight = round($header->total_weight);
                $newPoint = ($weight / 1000) * $oldPrice;

                // Edit history
                $history = PointHistory::where('transaction_id', $header->id)->first();
                if(!empty($history)){
                    $oldSaldo = $history->saldo;
                    $oldAmount = $history->amount;
                    $history->saldo = $oldSaldo - $oldAmount + $newPoint;
                    $history->amount = $newPoint;
                    $history->save();
                }

                $user = $header->user;
                if(!empty($user)){
                    $user->point -= $oldPrice;
                    $user->point += $newPoint;
                    $user->save();
                }

                $header->total_weight = round($header->total_weight);
                $header->total_price = round($newPoint, 4);
                $header->point_user = $newPoint;
                $header->save();
            }

            return 'SCRIPT SUCCESS!!';
        }
        catch (\Exception $ex){
            return $ex;
        }
    }
}
