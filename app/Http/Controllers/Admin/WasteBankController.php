<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\AdminUser;
use App\Models\City;
use App\Models\WasteBank;
use App\Transformer\WasteBankTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class WasteBankController extends Controller
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
        $users = WasteBank::query();
        return DataTables::of($users)
            ->setTransformer(new WasteBankTransformer)
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

        return view('admin.wastebank.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        return view('admin.wastebank.create', compact('cities'));
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
            'name'          => 'required',
            'address'       => 'required',
            'phone'         => 'required',
            'pic'           => 'required',
            'latitude'      => 'required',
            'longitude'     => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        //Create Wastebank
        $user = Auth::guard('admin')->user();
        $wastebank = WasteBank::create([
            'name'      => $request->input('name'),
            'address'   => $request->input('address'),
            'phone'     => $request->input('phone'),
            'pic_id'    => $request->input('pic'),
            'latitude'  => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'city_id'   => $request->input('city'),
            'created_at'=> Carbon::now('Asia/Jakarta'),
            'created_by'=> $user->id,
            'updated_at'=> Carbon::now('Asia/Jakarta'),
            'updated_by'=> $user->id
        ]);

        Session::flash('success', 'Success Creating new Waste Bank!');
        return redirect()->route('admin.waste-banks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $wasteBank = WasteBank::find($id);
        $cities = City::all();
        return view('admin.wastebank.edit', compact('wasteBank', 'cities'));
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
            'name'          => 'required',
            'address'       => 'required',
            'phone'         => 'required',
            'pic'           => 'required',
            'latitude'      => 'required',
            'longitude'     => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        //Create Wastebank
        $user = Auth::guard('admin')->user();
        $wastebank = WasteBank::find($request->input('id'));
        $wastebank->name = $request->input('name');
        $wastebank->address = $request->input('address');
        $wastebank->phone = $request->input('phone');
        $wastebank->pic_id = $request->input('pic') ;
        $wastebank->latitude = $request->input('latitude');
        $wastebank->longitude = $request->input('longitude');
        $wastebank->city_id = $request->input('city');
        $wastebank->updated_at = Carbon::now('Asia/Jakarta');
        $wastebank->updated_by = $user->id;
        $wastebank->save();

        Session::flash('success', 'Success Updating Waste Bank!');
        return redirect()->route('admin.waste-banks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        //
        try {
            //Belum melakukan pengecekan hubungan antar Table
            $wasteBankId = $request->input('id');
            $wasteBank = WasteBank::find($wasteBankId);
            $wasteBank->delete();

            Session::flash('success', 'Success Deleting WasteBank ' . $wasteBank->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
