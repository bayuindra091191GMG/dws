<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 06/02/2019
 * Time: 14:41
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
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

    public function getIndex(Request $request){
        $transations = TransactionHeader::where('transaction_type_id', 3)->get();
        return DataTables::of($transations)
            ->setTransformer(new TransactionTransformer())
            ->addIndexColumn()
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
}