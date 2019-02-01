<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\libs\Utilities;
use App\Models\DwsWasteCategoryData;
use App\Models\TransactionHeader;
use App\Transformer\TransactionTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $transations = TransactionHeader::query();
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
        return view('admin.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function create()
    {
        $dateToday = Carbon::today()->format("d M Y");
        $wasteCategories = DwsWasteCategoryData::orderBy('name')->get();

        // Generate transaction codes
        $today = Carbon::today()->format("Ymd");
        $prepend = "TRANS/". $today;
        $nextNo = Utilities::GetNextTransactionNumber($prepend);
        $code = Utilities::GenerateTransactionNumber($prepend, $nextNo);

        $data = [
            'code'              => $code,
            'dateToday'         => $dateToday,
            'wasteCategories'   => $wasteCategories
        ];

        return view('admin.transaction.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        $idx = 0;
        foreach ($categories as $category){
            if(empty($category)) $valid = false;
            if(empty($prices[$idx]) || $prices[$idx] === '0') $valid = false;
            if(empty($weights[$idx]) || $weights[$idx] === '0') $valid = false;
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
        foreach ($categories as $category){
            $totalWeight += (double) $weights[$idx];
            $totalPrice += (double) $prices[$idx];
        }

        $user = Auth::guard('admin')->user();
        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $now = Carbon::now();

        $trxHeader = TransactionHeader::create([
            'transaction_no'        => $request->input('code'),
            'date'                  => $date->toDateTimeString(),
            'transaction_type_id'   => 2,
            'total_weight'          => $totalWeight,
            'total_price'           => $totalPrice,
            'waste_category_id'     => 1,
            'status_id'             => 13,
            'notes'                 => $request->input('notes'),
            'created_at'            => $now->toDateTimeString(),
            'updated_at'            => $now->toDateTimeString()
        ]);
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

        return view('admin.transaction.show', compact('header'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
