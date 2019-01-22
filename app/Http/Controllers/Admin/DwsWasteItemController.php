<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\DwsWaste;
use App\Models\DwsWasteCategoryData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;

class DwsWasteItemController extends Controller
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
        $users = DwsWaste::query();
        return DataTables::of($users)
            ->setTransformer(new DwsWaste)
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
        return view('admin.dwswasteitem.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = DwsWasteCategoryData::all();

        return view('admin.dwswasteitem.create', compact('categories'));
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
            'name'              => 'required',
            'other_description' => 'required',
            'category'          => 'required',
            'description'       => 'required',
        ]);

        $image = $request->file('img_path');

        if($image == null){
            return back()->withErrors("Image required")->withInput($request->all());
        }

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = Auth::guard('admin')->user();

        $dwsWaste = DwsWaste::create([
            'dws_waste_category_datas_id'   => $request->input('category'),
            'name'                          => $request->input('name'),
            'description'                   => $request->input('description'),
            'other_description'             => $request->input('other_description'),
            'created_by'                    => $user->id,
            'created_at'                    => Carbon::now('Asia/Jakarta'),
            'updated_by'                    => $user->id,
            'updated_at'                    => Carbon::now('Asia/Jakarta')
        ]);

        //Save Image
        $img = Image::make($image);
        $extStr = $img->mime();
        $ext = explode('/', $extStr, 2);

        $filename = $dwsWaste->id.'_main_'.$dwsWaste->name.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

        $img->save('home/dwstesti/public_html/storage/admin/dwsitem/'. $filename, 75);

        $dwsWaste->img_path = $filename;
        $dwsWaste->save();

        Session::flash('success', 'Success Creating new Dws Waste Item!');
        return redirect()->route('admin.dws-waste-items.index');
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
        $dwsWaste = DwsWaste::find($id);
        return view('admin.dwswasteitem.edit', compact('dwsWaste'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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

        $dwsWaste = DwsWaste::find($request->input('id'));
        $dwsWaste->name = $request->input('name');
        $dwsWaste->other_description = $request->input('other_description');
        $dwsWaste->description = $request->input('description');
        $dwsWaste->updated_at = Carbon::now('Asia/Jakarta');
        $dwsWaste->updated_by = $user->id;
        $dwsWaste->save();

        //Save Image
        if($image != null) {
            $img = Image::make($image);
            $filename = $dwsWaste->img_path;
            $img->save('home/dwstesti/public_html/storage/admin/dwsitem/' . $filename, 75);
        }

        Session::flash('success', 'Success Updating new Dws Waste Item!');
        return redirect()->route('admin.dws-waste-items.index');
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
            $dwsWaste = DwsWaste::find($dwsWasteId);
            $dwsWaste->delete();

            $image_path = "home/dwstesti/public_html/storage/admin/dwsitem/" . $dwsWaste->img_path;  // Value is not URL but directory file path
            if(file_exists($image_path)) {
                unlink($image_path);
            }

            Session::flash('success', 'Success Deleting Dws Waste Item ' . $dwsWaste->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
