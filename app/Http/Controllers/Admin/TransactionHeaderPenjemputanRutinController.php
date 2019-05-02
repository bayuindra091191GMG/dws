<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 06/02/2019
 * Time: 15:49
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\libs\Utilities;
use App\Models\Address;
use App\Models\Configuration;
use App\Models\DwsWasteCategoryData;
use App\Models\MasaroWasteCategoryData;
use App\Models\PointHistory;
use App\Models\PointWastecollectorHistory;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\User;
use App\Models\WasteCollector;
use App\Models\WasteCollectorUser;
use App\Transformer\TransactionTransformer;
use App\Transformer\UserPenjemputanRutinTransformer;
use App\Transformer\UserWasteBankTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TransactionHeaderPenjemputanRutinController extends Controller
{
    public function index()
    {
        return view('admin.transaction.rutin.index');
    }

    public function getIndex(Request $request){
        $transactions = TransactionHeader::where('transaction_type_id', 1);
        return DataTables::of($transactions)
            ->setTransformer(new TransactionTransformer)
            ->addIndexColumn()
            ->make(true);
    }

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

        return view('admin.transaction.rutin.edit_dws')->with($data);
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

        return view('admin.transaction.rutin.edit_masaro')->with($data);
    }

    /**
     * Display on demand transaction detail
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $header = TransactionHeader::find($id);
        $date = Carbon::parse($header->date)->format("d M Y");

        $data = [
            'header'            => $header,
            'date'              => $date,
            'name'              => $header->user->first_name. " ". $header->user->last_name
        ];

        return view('admin.transaction.on_demand.show')->with($data);
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
            Session::flash('message', 'Berhasil ubah transaksi Penjemputan Rutin kategori DWS!');
        }
        else{
            Session::flash('message', 'Berhasil ubah transaksi Penjemputan Rutin kategori Masaro!');
        }

        return redirect()->route('admin.transactions.penjemputan_rutin.show', ['id' => $trxHeader->id]);
    }

    /**
     * Form to assign wastecollector to User rutin pickup
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setUserWastecollector($id){
        $adminUser = Auth::guard('admin')->user();
        $adminWasteBankId = $adminUser->waste_bank_id;
        $user = User::find($id);
        $address = Address::where('user_id', $id)->first();
        $wasteCollectors = WasteCollector::where('status_id', 1)
            ->whereHas('waste_banks', function($query) use ($adminWasteBankId){
                $query->where('waste_bank_id', $adminWasteBankId);
            })->get();

        $data = [
            'user'              => $user,
            'address'           => $address,
            'wasteCollectors'   => $wasteCollectors
        ];

        return view('admin.transaction.rutin.set_user_wastecollector')->with($data);
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