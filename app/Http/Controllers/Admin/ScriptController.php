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
}
