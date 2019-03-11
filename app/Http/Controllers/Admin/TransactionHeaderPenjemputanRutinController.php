<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 06/02/2019
 * Time: 15:49
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Configuration;
use App\Models\PointHistory;
use App\Models\PointWastecollectorHistory;
use App\Models\TransactionHeader;
use App\Models\User;
use App\Models\WasteCollector;
use App\Models\WasteCollectorUser;
use App\Transformer\UserPenjemputanRutinTransformer;
use App\Transformer\UserWasteBankTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class TransactionHeaderPenjemputanRutinController extends Controller
{
    public function indexSuscribedUsers(){
//        $user = Auth::guard('admin')->user();
//        $adminWasteBankId = $user->waste_bank_id;
//
//        $subscribedUsers = User::where('status_id', 1)
//            ->whereHas('waste_banks', function($query) use ($adminWasteBankId){
//                $query->where('waste_bank_id', $adminWasteBankId);
//            })->get();
//
//        dd($subscribedUsers);

        return view('admin.transaction.rutin.index_subscribed_users');
    }

    public function getIndex(Request $request){
        $transations = TransactionHeader::where('transaction_type_id', 2)->get();
        return DataTables::of($transations)
            ->setTransformer(new TransactionTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    public function getIndexSubscribedUsers(Request $request){
        try{
            $userAdmin = Auth::guard('admin')->user();
            $adminWasteBankId = $userAdmin->waste_bank_id;
            error_log("wastebank id = ".$adminWasteBankId);

            $subscribedUsers = User::where('routine_pickup', 1)
                ->whereIn('status_id', [1, 14, 19])
                ->whereHas('waste_banks', function($query) use ($adminWasteBankId){
                    $query->where('waste_bank_id', $adminWasteBankId);
                })->get();
            error_log("count = ".$subscribedUsers->count());

            return DataTables::of($subscribedUsers)
                ->setTransformer(new UserPenjemputanRutinTransformer())
                ->addIndexColumn()
                ->make(true);
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }

    /**
     * Form to assign wastecollector to User rutin pickup
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setUserWastecollector($id){
        $user = User::find($id);
        $address = Address::where('user_id', $id)->first();

        return view('admin.transaction.rutin.set_user_wastecollector', compact('user', 'address'));
    }

    /**
     * Update wastecollector to User rutin pickup
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateUserWastecollector(Request $request){

        $user = Auth::guard('admin')->user();

        $id = $request->input('user_id');
        $wastecollectorId = $request->input('wastecollector');

        //check if exist on DB, if exist edit assigned wastecollector, else create new one
        $wasteCollectorUserDB = WasteCollectorUser::where('user_id', $id)->first();
        if(empty($wasteCollectorUserDB)){
            $saveToDb = WasteCollectorUser::create([
                'user_id'  => $id,
                'waste_collector_id'   => $wastecollectorId,
                'created_by'    => $user->id,
                'created_at'    => Carbon::now('Asia/Jakarta'),
            ]);
        }
        else{
            $wasteCollectorUserDB->waste_collector_id = $wastecollectorId;
            $wasteCollectorUserDB->save();
        }

        Session::flash('message', 'Berhasil menugaskan waastecollector kepada user!');

        return redirect()->route('admin.user.penjemputan_rutin.index');
    }

    /**
     * Confirm Transaction Penjemputan rutin by wastebank admin
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirm(Request $request){
        $trxId = $request->input('confirmed_header_id');
        $header = TransactionHeader::find($trxId);
        if($header->status_id !== 8){
            Session::flash('error', 'Customer harus konfirmasi transaksi terlebih dahulu!');
            return redirect()->back();
        }

        $user = Auth::guard('admin')->user();
        $now = Carbon::now();

        $header->status_id = 18;
        $header->updated_at = $now->toDateTimeString();
        $header->updated_by_admin = $user->id;
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

        //add point to wastecollector
        $configuration = Configuration::where('configuration_key', 'point_amount_wastecollector')->first();
        $amount = $configuration->configuration_value;
        $userDB = $header->waste_collector;
        $newSaldo = $userDB->point + $amount;
        $userDB->point = $newSaldo;
        $userDB->save();

        $point = PointWastecollectorHistory::create([
            'wastecollector_id'  => $header->waste_collector_id,
            'type'   => $header->transaction_type_id,
            'transaction_id'    => $header->id,
            'type_transaction'   => "Kredit",
            'amount'    => $amount,
            'saldo'    => $newSaldo,
            'description'    => "Point dari transaksi nomor ".$header->transaction_no,
            'created_at'    => Carbon::now('Asia/Jakarta'),
        ]);

        Session::flash('message', 'Berhasil konfirmasi transaksi On Demand!');

        return redirect()->route('admin.transactions.on_demand.show', ['id' => $trxId]);
    }

}