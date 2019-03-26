<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 06/02/2019
 * Time: 14:41
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\PointHistory;
use App\Models\PointWastecollectorHistory;
use App\Models\TransactionHeader;
use App\Models\WasteCollector;
use App\Transformer\TransactionTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class TransactionHeaderOnDemandController extends Controller
{
    public function index()
    {
        return view('admin.transaction.on_demand.index');
    }
    public function list()
    {
        $transactions = TransactionHeader::where('transaction_type_id', 3)->orderByDesc('created_at')->get();

        return view('admin.transaction.on_demand.list', compact('transactions'));
    }

    public function getIndex(Request $request){

        $user = Auth::guard('admin')->user();
        if($user->is_super_admin === 1){
            $transations = TransactionHeader::where('transaction_type_id', 3);
        }
        else{
            $adminWasteBankId = $user->waste_bank_id;
            $transations = TransactionHeader::where('transaction_type_id', 3)
                            ->where('waste_bank_id', $adminWasteBankId);
        }

        return DataTables::of($transations)
            ->setTransformer(new TransactionTransformer())
            ->make(true);
    }

    /**
     * Assign a waste collector to selected on demand transaction
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignWasteCollector(Request $request, $id){
        $collectorId = $request->input('waste_collector_id');

        $transaction = TransactionHeader::find($id);
        $transaction->waste_collector_id = $collectorId;
        $transaction->save();

        $collector = WasteCollector::find($collectorId);

        Session::flash('message', 'Berhasil assign Waste Collector '. $collector->first_name. ' '. $collector->last_name.  ' ke transaksi '. $transaction->transaction_no);

        return redirect()->route('admin.transactions.on_demand.show', ['id' => $id]);
    }

    /**
     * Display on demand transaction detail
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::guard('admin')->user();
        $adminWasteBankId = $user->waste_bank_id;

        $header = TransactionHeader::find($id);
        $date = Carbon::parse($header->date)->format("d M Y");
        $wasteCollectors = WasteCollector::where('status_id', 1)
            ->whereHas('waste_banks', function($query) use ($adminWasteBankId){
                $query->where('waste_bank_id', $adminWasteBankId);
            })->get();

        $data = [
            'header'            => $header,
            'date'              => $date,
            'name'              => $header->user->first_name. " ". $header->user->last_name,
            'wasteCollectors'   => $wasteCollectors
        ];

        return view('admin.transaction.on_demand.show')->with($data);
    }

    /**
     * Confirm Transaction on demand
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirm(Request $request){
        $trxId = $request->input('confirmed_header_id');
        $header = TransactionHeader::find($trxId);
        if($header->status_id !== 16){
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