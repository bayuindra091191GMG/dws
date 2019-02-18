<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\libs\Utilities;
use App\Models\DwsWasteCategoryData;
use App\Models\MasaroWasteCategoryData;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Notifications\FCMNotification;
use App\Transformer\TransactionTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TransactionHeaderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function getIndex(Request $request){
        $transations = TransactionHeader::where('transaction_type_id', 2)->get();
        return DataTables::of($transations)
            ->setTransformer(new TransactionTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.transaction.antar_sendiri.index');
    }

    /**
     * Create transaction for DWS category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function createDws()
    {
        $dateToday = Carbon::today()->format("d M Y");
        $wasteCategories = DwsWasteCategoryData::orderBy('name')->get();

        // Generate transaction codes
        $today = Carbon::today()->format("Ym");
        $prepend = "TRANS/DWS/". $today;
        $nextNo = Utilities::GetNextTransactionNumber($prepend);
        $code = Utilities::GenerateTransactionNumber($prepend, $nextNo);

        $data = [
            'code'              => $code,
            'dateToday'         => $dateToday,
            'wasteCategories'   => $wasteCategories
        ];

        return view('admin.transaction.antar_sendiri.create_dws')->with($data);
    }

    /**
     * Create transaction for Masaro category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function createMasaro()
    {
        $dateToday = Carbon::today()->format("d M Y");
        $wasteCategories = MasaroWasteCategoryData::orderBy('name')->get();

        // Generate transaction codes
        $today = Carbon::today()->format("Ym");
        $prepend = "TRANS/MASARO/". $today;
        //dd($prepend);
        $nextNo = Utilities::GetNextTransactionNumber($prepend);
        //dd($nextNo);
        $code = Utilities::GenerateTransactionNumber($prepend, $nextNo);

        $data = [
            'code'              => $code,
            'dateToday'         => $dateToday,
            'wasteCategories'   => $wasteCategories
        ];

        return view('admin.transaction.antar_sendiri.create_masaro')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
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

        // Generate transaction codes
        $today = Carbon::today()->format("Ym");

        $categoryType = $request->input('category_type');
        if($categoryType == "1"){
            $prepend = "TRANS/DWS/". $today;
        }
        else{
            $prepend = "TRANS/MASARO/". $today;
        }

        $nextNo = Utilities::GetNextTransactionNumber($prepend);
        $code = Utilities::GenerateTransactionNumber($prepend, $nextNo);

        $user = Auth::guard('admin')->user();
        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $now = Carbon::now();

        $trxHeader = TransactionHeader::create([
            'transaction_no'        => $code,
            'date'                  => $date->toDateTimeString(),
            'transaction_type_id'   => 2,
            'total_weight'          => $totalWeight,
            'total_price'           => $totalPrice,
            'waste_category_id'     => $categoryType,
            'status_id'             => 13,
            'notes'                 => $request->input('notes'),
            'created_at'            => $now->toDateTimeString(),
            'updated_at'            => $now->toDateTimeString(),
            'created_by_admin'      => $user->id,
            'updated_by_admin'      => $user->id
        ]);

        $idx = 0;
        foreach ($categories as $category){
            $floatWeight = Utilities::toFloat($weights[$idx]);
            $floatPrice = Utilities::toFloat($prices[$idx]);

            if($categoryType == "1"){
                $trxDetail = TransactionDetail::create([
                    'transaction_header_id'     => $trxHeader->id,
                    'dws_category_id'           => $category,
                    'weight'                    => $floatWeight,
                    'price'                     => $floatPrice
                ]);
            }
            else{
                $trxDetail = TransactionDetail::create([
                    'transaction_header_id'     => $trxHeader->id,
                    'masaro_category_id'        => $category,
                    'weight'                    => $floatWeight,
                    'price'                     => $floatPrice
                ]);
            }
            $idx++;
        }

        // Update transaction auto number
        Utilities::UpdateTransactionNumber($prepend);

        $title = "Digital Waste Solution";
        if($categoryType == "1"){
            Session::flash('message', 'Berhasil membuat transaksi kategori DWS!');
            $body = "Berhasil membuat transaksi kategori DWS - Nomor Transaksi ".$code;
        }
        else{
            Session::flash('message', 'Berhasil membuat transaksi kategori Masaro!');
            $body = "Berhasil membuat transaksi kategori Masaro - Nomor Transaksi ".$code;
        }
        $data = array(
            'type_id' => '1',
            'transaction_no' => $code,
            'message' => $body,
        );
//        dd($data);
        $isSuccess = FCMNotification::SendNotification(8, 'apps', $title, $body, $data);


        return redirect()->route('admin.transactions.antar_sendiri.show', ['id' => $trxHeader->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $header = TransactionHeader::find($id);
        $date = Carbon::parse($header->date)->format("d M Y");

        if(!empty($header->first_name)){
            $name = $header->first_name. " ". $header->last_name;
        }
        else{
            $name = "BELUM ASSIGN";
        }

        $data = [
            'header'        => $header,
            'date'          => $date,
            'name'          => $name
        ];

        return view('admin.transaction.antar_sendiri.show')->with($data);
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

        return view('admin.transaction.antar_sendiri.edit_dws')->with($data);
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

        return view('admin.transaction.antar_sendiri.edit_masaro')->with($data);
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
        $trxHeader->total_weight = $totalWeight;
        $trxHeader->total_price = $totalPrice;
        $trxHeader->notes = $request->input('notes');
        $trxHeader->updated_at = $now->toDateTimeString();
        $trxHeader->updated_by_admin = $user->id;
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
                    $trxDetail->weight = $floatWeight;
                    $trxDetail->price = $floatPrice;
                    $trxDetail->save();
                }
                else{
                    error_log("check!");
                    $trxDetail = TransactionDetail::create([
                        'transaction_header_id'     => $trxHeader->id,
                        'dws_category_id'           => $category,
                        'weight'                    => $floatWeight,
                        'price'                     => $floatPrice
                    ]);
                }
            }
            else{
                $trxDetail = $trxHeader->transaction_details->where('masaro_category_id', $category)->first();
                if(!empty($trxDetail)){
                    $trxDetail->weight = $floatWeight;
                    $trxDetail->price = $floatPrice;
                    $trxDetail->save();
                }
                else{
                    $trxDetail = TransactionDetail::create([
                        'transaction_header_id'     => $trxHeader->id,
                        'masaro_category_id'        => $category,
                        'weight'                    => $floatWeight,
                        'price'                     => $floatPrice
                    ]);
                }
            }
            $idx++;
        }

        if($categoryType == "1"){
            Session::flash('message', 'Berhasil ubah transaksi kategori DWS!');
        }
        else{
            Session::flash('message', 'Berhasil ubah transaksi kategori Masaro!');
        }

        return redirect()->route('admin.transactions.antar_sendiri.show', ['id' => $trxHeader->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
