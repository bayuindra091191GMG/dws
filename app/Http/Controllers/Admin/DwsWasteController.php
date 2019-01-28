<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\DwsWasteCategoryData;
use App\Transformer\DwsWasteCategoryTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Intervention\Image\File;
use Yajra\DataTables\DataTables;

class DwsWasteController extends Controller
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
        $users = DwsWasteCategoryData::query();
        return DataTables::of($users)
            ->setTransformer(new DwsWasteCategoryTransformer)
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
        return view('admin.dwswaste.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dwswaste.create');
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
            'golongan'      => 'required',
            'price'         => 'required',
            'description'   => 'required',
        ]);
        $image = $request->file('img_path');

        if($image == null){
            return back()->withErrors("Image required")->withInput($request->all());
        }

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = Auth::guard('admin')->user();

        $dwsWaste = DwsWasteCategoryData::create([
            'name'          => $request->input('name'),
            'golongan'      => $request->input('golongan'),
            'price'         => $request->input('price'),
            'description'   => $request->input('description'),
            'created_at'    => Carbon::now('Asia/Jakarta'),
            'created_by'    => $user->id
        ]);

        //Save Image
        $img = Image::make($image);
        $extStr = $img->mime();
        $ext = explode('/', $extStr, 2);

        $filename = $dwsWaste->id.'_main_'.$dwsWaste->name.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

        $img->save('../public_html/storage/admin/dwscategory/'. $filename, 75);

        $dwsWaste->img_path = $filename;
        $dwsWaste->save();

        Session::flash('success', 'Success Creating new Dws Waste Category!');
        return redirect()->route('admin.dws-wastes.index');
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
        $dwsWaste = DwsWasteCategoryData::find($id);
        return view('admin.dwswaste.edit', compact('dwsWaste'));
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
            'golongan'      => 'required',
            'price'         => 'required',
            'description'   => 'required',
        ]);
        $image = $request->file('img_path');

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = Auth::guard('admin')->user();

        $dwsWaste = DwsWasteCategoryData::find($request->input('id'));
        $dwsWaste->name = $request->input('name');
        $dwsWaste->golongan = $request->input('golongan');
        $dwsWaste->price = $request->input('price');
        $dwsWaste->description = $request->input('description');
        $dwsWaste->updated_at = Carbon::now('Asia/Jakarta');
        $dwsWaste->updated_by = $user->id;
        $dwsWaste->save();

        //Save Image
        if($image != null) {
            $img = Image::make($image);
            $filename = $dwsWaste->img_path;
            $img->save('../public_html/storage/admin/dwscategory/' . $filename, 75);
        }

        Session::flash('success', 'Success Updating new Dws Waste Category!');
        return redirect()->route('admin.dws-wastes.index');
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
            $dwsWasteId = $request->input('id');
            $dwsWaste = DwsWasteCategoryData::find($dwsWasteId);
            $dwsWaste->delete();

            $image_path = "../public_html/storage/admin/dwscategory/" . $dwsWaste->img_path;  // Value is not URL but directory file path
            if(file_exists($image_path)) {
                unlink($image_path);
            }

            Session::flash('success', 'Success Deleting Dws Waste Category ' . $dwsWaste->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
