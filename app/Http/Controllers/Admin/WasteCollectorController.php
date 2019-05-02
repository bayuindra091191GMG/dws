<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionHeader;
use App\Models\WasteBank;
use App\Models\WasteCollector;
use App\Models\WasteCollectorWasteBank;
use App\Transformer\WasteCollectorTransactionTransformer;
use App\Transformer\WasteCollectorTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;

class WasteCollectorController extends Controller
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
        $users = WasteCollector::query();
        return DataTables::of($users)
            ->setTransformer(new WasteCollectorTransformer)
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
        return view('admin.wastecollector.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.wastecollector.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email|max:100|unique:waste_collectors',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'password'              => 'required|min:6|max:20|same:password',
            'password_confirmation' => 'required|same:password',
            'phone'                 => 'required',
            'address'               => 'required',
            'identity_number'       => 'required'
        ]);

        $image = $request->file('img_path');

        if($image == null){
            return back()->withErrors("Image required")->withInput($request->all());
        }

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = Auth::guard('admin')->user();
        $wasteCollector = WasteCollector::create([
            'email'             => $request->input('email'),
            'password'          => Hash::make($request->input('password')),
            'first_name'        => $request->input('first_name'),
            'last_name'         => $request->input('last_name'),
            'identity_number'   => $request->input('identity_number'),
            'phone'             => $request->input('phone'),
            'address'           => $request->input('address'),
            'point'             => 0,
            'created_by'        => $user->id,
            'created_at'        => Carbon::now('Asia/Jakarta'),
            'updated_by'        => $user->id,
            'updated_at'        => Carbon::now('Asia/Jakarta'),
            'status_id'         => $request->input('status')
        ]);

        //Save Image
        $img = Image::make($image);
        $extStr = $img->mime();
        $ext = explode('/', $extStr, 2);

        $filename = $wasteCollector->id.'_main_'.$wasteCollector->first_name . '-' . $wasteCollector->last_name.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

        //$img->save('../public_html/storage/admin/masarocategory/'. $filename, 75);
        $img->save(public_path('storage/admin/wastecollector/'. $filename), 75);

        $wasteCollector->img_path = $filename;
        $wasteCollector->save();

        //save wastecollector wastebank
        $wasteCollectorWastebank = WasteCollectorWasteBank::create([
            'waste_bank_id'             => $request->input('wastebank'),
            'waste_collector_id'          => $wasteCollector->id,
        ]);

        Session::flash('success', 'Sukses Membuat data Waste Collector!');
        return redirect()->route('admin.wastecollectors.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $collector = WasteCollector::find($id);
        $name = $collector->first_name. " ". $collector->last_name;
        $wasteBank = $collector->waste_banks->first()->name ?? "Belum Pilih Waste Bank";

        $data = [
            'collector'     => $collector,
            'name'          => $name,
            'wasteBank'     => $wasteBank
        ];
        return view('admin.wastecollector.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $wasteCollector = WasteCollector::find($id);
        $wasteBanks = WasteBank::orderBy('name')->get();
        $wasteBankId = $wasteCollector->waste_banks->first()->id ?? -1;

        $data = [
            'wasteCollector'    => $wasteCollector,
            'wasteBanks'        => $wasteBanks,
            'wasteBankId'       => $wasteBankId
        ];

        return view('admin.wastecollector.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email|max:100',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'phone'                 => 'required',
            'address'               => 'required',
            'identity_number'       => 'required'
        ]);

        $image = $request->file('img_path');

        if($image == null){
            return back()->withErrors("Image required")->withInput($request->all());
        }

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = Auth::guard('admin')->user();
        $wasteCollector = WasteCollector::find($request->input('id'));
        $wasteCollector->email = $request->input('email');
        $wasteCollector->first_name = $request->input('first_name');
        $wasteCollector->last_name = $request->input('last_name');
        $wasteCollector->identity_number = $request->input('identity_number');
        $wasteCollector->phone = $request->input('phone');
        $wasteCollector->address = $request->input('address');
        $wasteCollector->updated_by = $user->id;
        $wasteCollector->updated_at = Carbon::now('Asia/Jakarta');
        $wasteCollector->save();

        if($image != null) {
            $img = Image::make($image);
            $filename = $wasteCollector->img_path;
            //$img->save('../public_html/storage/admin/masarocategory/'. $filename, 75);
            $img->save(public_path('storage/admin/wastecollector/'. $filename), 75);
        }

        //update or save wastecollector wastebank
        $wasteCollectorWastebankDB = WasteCollectorWasteBank::where('waste_collector_id', $request->input('id'))->first();
        if(!empty($wasteCollectorWastebankDB)){
            $wasteCollectorWastebankDB->waste_bank_id = $request->input('wastebank');
            $wasteCollectorWastebankDB->save();
        }
        else{
            $wasteCollectorWastebank = WasteCollectorWasteBank::create([
                'waste_bank_id'             => $request->input('wastebank'),
                'waste_collector_id'          => $wasteCollector->id,
            ]);

        }

        Session::flash('success', 'Sukses Menggubah data Waste Collector!');
        return redirect()->route('admin.wastecollectors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            //Belum melakukan pengecekan hubungan antar Table
            $wasteCollectorId = $request->input('id');
            $wasteCollector = WasteCollector::find($wasteCollectorId);
            $wasteCollector->delete();

            //$image_path = "../public_html/storage/admin/masarocategory/" . $masaroWaste->img_path;  // Value is not URL but directory file path
            $image_path = public_path("storage/admin/wastecollector/" . $wasteCollector->img_path);  // Value is not URL but directory file path
            if(file_exists($image_path)) {
                unlink($image_path);
            }

            Session::flash('success', 'Sukses Menghapus Data Waste Collector ' . $wasteCollector->first_name . ' ' . $wasteCollector->last_name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function indexTransaction($id)
    {
        $collector = WasteCollector::find($id);
        $name = $collector->first_name. " ". $collector->last_name;

        $data = [
            'collector'     => $collector,
            'name'          => $name
        ];

        return view('admin.wastecollector.transactions')->with($data);
    }

    public function getTransactions(Request $request){
        $transations = TransactionHeader::where('waste_collector_id', $request->input('waste_collector_id'));
        return DataTables::of($transations)
            ->setTransformer(new WasteCollectorTransactionTransformer())
            ->addIndexColumn()
            ->make(true);
    }



    public function getWastecollectors(Request $request){
        $term = trim($request->q);
        $roles = WasteCollector::where(function ($q) use ($term) {
            $q->where('first_name', 'LIKE', '%' . $term . '%')
            ->orwhere('last_name', 'LIKE', '%' . $term . '%');
        })
            ->get();

        $formatted_tags = [];

        foreach ($roles as $role) {
            $formatted_tags[] = ['id' => $role->id, 'text' => $role->first_name." ".$role->last_name];
        }

        return \Response::json($formatted_tags);
    }
}
