<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\PointHistory;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\User;
use App\Models\UserWasteBank;
use App\Models\WasteBank;
use App\Models\WasteBankSchedule;
use App\Models\WasteCollectorWasteBank;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function deleteTransaction(int $trx_id){
        try{
            $header = TransactionHeader::find($trx_id);
            if(empty($header)){
                return 'ALREADY DELETED';
            }

            if($header->status_id === 10){
                // Decrease user point
                $user = $header->user;
                $newSaldo = $user->point - $header->total_price;
                $user->point = $newSaldo;
                $user->save();

                // Delete history
                $history = PointHistory::where('transaction_id', $header->id)->first();
                if(!empty($history)){
                    $history->delete();
                }
            }

            // Delete transaction details
            foreach ($header->transaction_details as $detail){
                $detail->delete();
            }

            // Delete header
            $header->delete();

            return 'DELETE SUCCESS!!';
        }
        catch (\Exception $ex){
            return $ex;
        }
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

    public function changeMasaroIdIndex(){
        try{
            $details = TransactionDetail::all();

            foreach ($details as $detail){
                if(!empty($detail->masaro_category_id)){
                    if($detail->masaro_category_id === 3)
                    {
                        $detail->masaro_category_id = 4;
                    }
                    elseif ($detail->masaro_category_id === 4){
                        $detail->masaro_category_id = 3;
                    }

                    $detail->save();
                }
            }

            return 'SCRIPT SUCCESS!!';
        }
        catch (\Exception $ex){
            return $ex;
        }
    }

    public function refreshSehatiPoint(){
        try{
            $headers = TransactionHeader::where('waste_bank_id', 6)
                ->where('status_id', 10)
                ->orderBy('created_at')
                ->get();

            $users = DB::table('transaction_headers')
                ->where('waste_bank_id', 6)
                ->where('status_id', 10)
                ->distinct()
                ->get(['user_id']);


            foreach ($users as $user){
                DB::table('users')
                    ->where('id', $user->user_id)
                    ->update(['point' => 0]);
            }

            foreach ($headers as $header){
                $user = $header->user;

                $point = intval($header->total_price);
                $header->point_user = $point;
                $header->save();

                // Edit history
                $history = PointHistory::where('transaction_id', $header->id)->first();
                if(!empty($history)){
                    $history->saldo = $user->point + $point;
                    $history->amount = $point;
                    $history->save();
                }

                if(!empty($user)){
                    $user->point += $point;
                    $user->save();
                }


            }

            return 'SCRIPT SUCCESS!!';
        }
        catch (\Exception $ex){
            return $ex;
        }
    }

    public function deleteCilegonMasaroImportData(){
        $transactions = TransactionHeader::where('waste_bank_id', 1)
            ->where('created_at', '<', '2019-06-01')
            ->where('total_price', 0)
            ->get();

        foreach ($transactions as $transaction){
            foreach ($transaction->transaction_details as $detail){
                $detail->delete();
            }
            $transaction->delete();
        }

        return 'SCRIPT SUCCESS!!';
    }

    public function deleteWasteBank(int $id){
        try{
            $wasteBank = WasteBank::find($id);
            if(empty($wasteBank)){
                return 'INVALID';
            }

            // Delete transactions
            $transactionHeaders = TransactionHeader::with('transaction_details')
                ->where('waste_bank_id', $id)
                ->get();

            foreach ($transactionHeaders as $transactionHeader){
                // Delete trx details
                foreach ($transactionHeader->transaction_details as $detail){
                    $detail->delete();
                }

                // Delete point histories
                foreach ($transactionHeader->point_histories as $point){
                    $point->delete();
                }

                $transactionHeader->delete();
            }

            // Delete user waste banks
            $userWasteBanks = UserWasteBank::where('waste_bank_id', $id)
                ->get();

            foreach ($userWasteBanks as $userWasteBank){
                $userWasteBank->delete();
            }

            // Delete waste bank schedules
            $wasteBankSchedules = WasteBankSchedule::where('waste_bank_id', $id)
                ->get();

            foreach ($wasteBankSchedules as $wasteBankSchedule){
                $wasteBankSchedule->delete();
            }

            // Delete waste collector waste banks
            $wasteCollectorWasteBanks = WasteCollectorWasteBank::where('waste_bank_id', $id)
                ->get();

            foreach ($wasteCollectorWasteBanks as $wasteCollectorWasteBank){
                $wasteCollectorWasteBank->delete();
            }

            // Check admin user
            $adminUser = AdminUser::where('waste_bank_id', $id)->first();
            if(!empty($adminUser)){
                $adminUser->waste_bank_id = null;
                $adminUser->save();
            }

            $wasteBank->delete();

            return 'SCRIPT SUCCESS!!';
        }
        catch (\Exception $ex){
            return $ex;
        }
    }

    public function deleteUserTransactions(int $id){
        try{
            // Delete transactions
            $transactionHeaders = TransactionHeader::with('transaction_details')
                ->where('user_id', $id)
                ->get();

            $count = 0;
            foreach ($transactionHeaders as $transactionHeader){
                DB::table('transaction_details')
                    ->where('transaction_header_id', $transactionHeader->id)
                    ->delete();

                $transactionHeader->delete();
                $count++;
            }

            return 'SCRIPT SUCCESS!! COUNT = '. $count;
        }
        catch (\Exception $ex){
            dd($ex);
        }
    }

    public function recountUserPoint(int $id){
        try{
            $user = User::find($id);
            if(empty($user)){
                return 'INVALID USER!';
            }

            // Delete point history
            DB::table('point_histories')
                ->where('user_id', $id)
                ->delete();

            $transactions = TransactionHeader::where('user_id', $id)->get();

            $totalPoint = 0;
            foreach ($transactions as $transaction){
                if($transaction->transaction_type_id === 2 && $transaction->status_id === 10){
                    $point = intval($transaction->total_price);
                    $transaction->point_user = $point;
                    $transaction->save();

                    $totalPoint += $point;

                    // Create history
                    PointHistory::create([
                        'user_id'           => $id,
                        'type'              => $transaction->type,
                        'transaction_id'    => $transaction->id,
                        'is_referral'       => 0,
                        'type_transaction'  => 'Kredit',
                        'amount'            => $point,
                        'saldo'             => $totalPoint,
                        'description'       => 'Point dari transaksi nomor '. $transaction->transaction_no,
                        'created_at'        => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ]);
                }
            }

            $user->point = $totalPoint;
            $user->save();

            return 'SCRIPT SUCCESS!';
        }
        catch (\Exception $ex){
            dd($ex);
        }
    }

    public function refreshAntarSendiriTransaction(){
        try{
            $transactions = TransactionHeader::where('transaction_type_id', 2)
                ->where('waste_category_id', 1)
                ->get();

            foreach ($transactions as $transaction){
                $transaction->total_price = ($transaction->total_weight / 1000);
                $point = intval($transaction->total_price);
                $transaction->point_user = $point;
                $transaction->save();
            }

            return 'SCRIPT SUCCESS!';
        }
        catch (\Exception $ex){
            dd($ex);
        }
    }
}
