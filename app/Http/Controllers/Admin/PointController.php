<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\libs\Utilities;
use App\Models\DwsWasteCategoryData;
use App\Models\MasaroWasteCategoryData;
use App\Models\PointHistory;
use App\Models\PointWastecollectorHistory;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Transformer\PointTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PointController extends Controller
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
        $points = PointHistory::query();
        return DataTables::of($points)
            ->setTransformer(new PointTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    public function getIndexWastecollectors(Request $request){
        $points = PointWastecollectorHistory::query();
        return DataTables::of($points)
            ->setTransformer(new PointTransformer())
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
        return view('admin.point.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWastecollectors()
    {
        return view('admin.point.index-wastecollector');
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

        return view('admin.point.show')->with($data);
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
