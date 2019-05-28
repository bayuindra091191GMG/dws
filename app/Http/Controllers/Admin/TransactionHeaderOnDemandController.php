<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 06/02/2019
 * Time: 14:41
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\libs\Utilities;
use App\Models\Configuration;
use App\Models\DwsWasteCategoryData;
use App\Models\MasaroWasteCategoryData;
use App\Models\PointHistory;
use App\Models\PointWastecollectorHistory;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\WasteCollector;
use App\Notifications\FCMNotification;
use App\Transformer\TransactionTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TransactionHeaderOnDemandController extends Controller
{
    public function index()
    {
        return view('admin.transaction.on_demand.index');
    }

    public function list()
    {
        $admin = Auth::guard('admin')->user();
        $transactions = TransactionHeader::where('transaction_type_id', 3);
        if($admin->is_super_admin === 0){
            $adminWasteBankId = $admin->waste_bank_id;
            $transactions = $transactions->where('waste_bank_id', $adminWasteBankId);
        }
        $transactions = $transactions->get();

        return view('admin.transaction.on_demand.list', compact('transactions'));
    }

    public function getIndex(Request $request){

        $admin = Auth::guard('admin')->user();
        if($admin->is_super_admin === 1){
            $transations = TransactionHeader::where('transaction_type_id', 3);
        }
        else{
            $adminWasteBankId = $admin->waste_bank_id;
            $transations = TransactionHeader::where('transaction_type_id', 3)
                            ->where('waste_bank_id', $adminWasteBankId);
        }

        //error_log('count: '. $transations->get()->count());

        return DataTables::of($transations)
            ->setTransformer(new TransactionTransformer())
            ->make(true);
    }

    /**
     * Edit dws category type transaction
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editDws($id)
    {
        $header = TransactionHeader::find($id);
        $date = Carbon::parse($header->date)->format("d M Y");
        $wasteCategories = DwsWasteCategoryData::orderBy('name')->get();

        $data = [
            'header'            => $header,
            'date'              => $date,
            'wasteCategories'   => $wasteCategories
        ];

        return view('admin.transaction.on_demand.edit_dws')->with($data);
    }

    /**
     * Edit Masaro category type transaction
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editMasaro($id)
    {
        $header = TransactionHeader::find($id);
        $date = Carbon::parse($header->date)->format("d M Y");
        $wasteCategories = MasaroWasteCategoryData::orderBy('name')->get();

        $data = [
            'header'            => $header,
            'date'              => $date,
            'wasteCategories'   => $wasteCategories
        ];

        return view('admin.transaction.on_demand.edit_masaro')->with($data);
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'date'          => 'required',
            'notes'         => 'max:199'
        ],[
            'date.required'         => 'Tanggal wajib diisi!',
            'notes.max'             => 'Catatan tidak boleh lebih dari 200 karakter!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $valid = true;
        $categories = $request->input('categories');
        $prices = $request->input('prices');
        $weights = $request->input('weights');

        if(empty($categories || empty($prices || empty($weights)))){
            return redirect()->back()->withErrors('Detil kategori, berat dan harga wajib diisi!', 'default')->withInput($request->all());
        }

        $idx = 0;
        foreach ($categories as $category){
            if(empty($category) || $category == "-1") $valid = false;
            if(empty($prices[$idx]) || $prices[$idx] === '0') $valid = false;
            if(empty($weights[$idx]) || $weights[$idx] === '0') $valid = false;
            $idx++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detil kategori, berat dan harga wajib diisi!', 'default')->withInput($request->all());
        }

        // Check duplicate categories
        $validUnique = Utilities::arrayIsUnique($categories);
        if(!$validUnique){
            return redirect()->back()->withErrors('Detil kategori tidak boleh kembar!', 'default')->withInput($request->all());
        }

        // Count total weight
        $totalWeight = 0;
        $totalPrice = 0;
        $idx = 0;
        foreach ($categories as $category){
            $floatWeight = Utilities::toFloat($weights[$idx]);
            $floatPrice = Utilities::toFloat($prices[$idx]);
            $totalWeight += (double) $floatWeight;
            $totalPrice += (double) $floatPrice;
            $idx++;
        }

        $user = Auth::guard('admin')->user();
        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $now = Carbon::now();
        $categoryType = $request->input('category_type');

        $trxHeader = TransactionHeader::find($id);
        $trxHeader->date = $date;
        $trxHeader->total_weight = $totalWeight * 1000;
        $trxHeader->total_price = $totalPrice;
        $trxHeader->notes = $request->input('notes');
        $trxHeader->updated_at = $now->toDateTimeString();
        $trxHeader->updated_by_admin = $user->id;
        $trxHeader->point_user = $totalPrice;
        $trxHeader->save();

        // Check deleted details
        foreach ($trxHeader->transaction_details as $detail){
            $isFound = false;
            foreach ($categories as $category){
                if($categoryType == "1" && $detail->dws_category_id == $category){
                    $isFound = true;
                }
                elseif($categoryType == "2" && $detail->masaro_category_id == $category){
                    $isFound = true;
                }
            }

            if(!$isFound){
                $detail->delete();
            }
        }

        $idx = 0;
        foreach ($categories as $category){
            $floatWeight = Utilities::toFloat($weights[$idx]);
            $floatPrice = Utilities::toFloat($prices[$idx]);

            if($categoryType == "1"){
                $trxDetail = $trxHeader->transaction_details->where('dws_category_id', $category)->first();
                if(!empty($trxDetail)){
                    $trxDetail->weight = $floatWeight * 1000;
                    $trxDetail->price = $floatPrice;
                    $trxDetail->save();
                }
                else{
                    $trxDetail = TransactionDetail::create([
                        'transaction_header_id'     => $trxHeader->id,
                        'dws_category_id'           => $category,
                        'weight'                    => $floatWeight * 1000,
                        'price'                     => $floatPrice
                    ]);
                }
            }
            else{
                $trxDetail = $trxHeader->transaction_details->where('masaro_category_id', $category)->first();
                if(!empty($trxDetail)){
                    $trxDetail->weight = $floatWeight * 1000;
                    $trxDetail->price = $floatPrice;
                    $trxDetail->save();
                }
                else{
                    $trxDetail = TransactionDetail::create([
                        'transaction_header_id'     => $trxHeader->id,
                        'masaro_category_id'        => $category,
                        'weight'                    => $floatWeight * 1000,
                        'price'                     => $floatPrice
                    ]);
                }
            }
            $idx++;
        }

        if($categoryType == "1"){
            Session::flash('message', 'Berhasil ubah transaksi On Demand kategori DWS!');
        }
        else{
            Session::flash('message', 'Berhasil ubah transaksi On Demand kategori Masaro!');
        }

        return redirect()->route('admin.transactions.on_demand.show', ['id' => $trxHeader->id]);
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

        //Send notification to
        //Driver, waste source
        $transactionDB = TransactionHeader::where('id', $id)->with('status', 'user', 'transaction_details')->first();
        $title = "Digital Waste Solution";
        $body = "Admin assign Driver On Demand Pickup";
        $data = array(
            "type_id" => "31",
            "model" => $transactionDB,
        );

        // MURPHIE BUTUH CEK INI
        $isSuccess = FCMNotification::SendNotification($collectorId, 'collector', $title, $body, $data);

        // TAMBAH NOTIF WASTE COLLECTOR TELAH DITEMUKAN KE WASTE SOURCE
//        $title = "Digital Waste Solution";
//        $body = "Anda Telah Mendapatkan Driver";
//        $data = array(
//            "data" => [
//                'type_id' => '32',
//                "model" => $transactionDB,
//            ]
//        );
//        //Push Notification to user/ wastesource App.
//        FCMNotification::SendNotification($transactionDB->user_id, 'app', $title, $body, $data);

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
        if($header->status_id !== 8){
            Session::flash('error', 'Customer harus konfirmasi transaksi terlebih dahulu!');
            return redirect()->back();
        }

        $user = Auth::guard('admin')->user();
        $now = Carbon::now();

        $header->status_id = 9;
        $header->updated_at = $now->toDateTimeString();
        $header->updated_by_admin = $user->id;
        $header->save();

        //add point to user
        //$configuration = Configuration::where('configuration_key', 'point_amount_user')->first();
        //$amount = $configuration->configuration_value;
        $userDB = $header->user;
        $newSaldo = $userDB->point + $header->total_price;
        $userDB->point = $newSaldo;
        $userDB->save();

        $point = PointHistory::create([
            'user_id'  => $header->user_id,
            'type'   => $header->transaction_type_id,
            'transaction_id'    => $header->id,
            'type_transaction'   => "Kredit",
            'amount'    => $header->total_price,
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