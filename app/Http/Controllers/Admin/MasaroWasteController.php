<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasaroWasteCategoryData;
use App\Transformer\MasaroWasteCategoryTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;
use Intervention\Image\File;


class MasaroWasteController extends Controller
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
        $users = MasaroWasteCategoryData::query();
        return DataTables::of($users)
            ->setTransformer(new MasaroWasteCategoryTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    public function getMasaroCategories(Request $request){
        $term = trim($request->q);
        $masaroCategories = MasaroWasteCategoryData::where(function ($q) use ($term) {
            $q->where('code', 'LIKE', '%' . $term . '%')
                ->orWhere('name', 'LIKE', '%' . $term . '%');
        })->get();

        $formatted_tags = [];

        foreach ($masaroCategories as $masaroCategory) {
            $formatted_tags[] = ['id' => $masaroCategory->id, 'text' => $masaroCategory->code . ' - ' . $masaroCategory->name];
        }

        return Response::json($formatted_tags);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.masaro.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.masaro.create');
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
            'price'         => 'required',
            'description'   => 'required',
        ]);
        $image = $request->file('img_path');

        if($image == null){
            return back()->withErrors("Image required")->withInput($request->all());
        }

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = Auth::guard('admin')->user();

        $masaroWaste = MasaroWasteCategoryData::create([
            'name'          => $request->input('name'),
            'price'         => $request->input('price'),
            'description'   => $request->input('description'),
            'created_at'    => Carbon::now('Asia/Jakarta'),
            'created_by'    => $user->id
        ]);

        //Save Image
        $img = Image::make($image);
        $extStr = $img->mime();
        $ext = explode('/', $extStr, 2);

        $filename = $masaroWaste->id.'_main_'.$masaroWaste->name.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

        $img->save('../public_html/storage/admin/masarocategory/'. $filename, 75);

        $masaroWaste->img_path = $filename;
        $masaroWaste->save();

        Session::flash('success', 'Success Creating new Masaro Waste Category!');
        return redirect()->route('admin.masaro-wastes.index');
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
        $masaroWaste = MasaroWasteCategoryData::find($id);
        return view('admin.masaro.edit', compact('masaroWaste'));
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
            'price'         => 'required',
            'description'   => 'required',
        ]);
        $image = $request->file('img_path');

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = Auth::guard('admin')->user();

        $masaroWaste = MasaroWasteCategoryData::find($request->input('id'));
        $masaroWaste->name = $request->input('name');
        $masaroWaste->price = $request->input('price');
        $masaroWaste->description = $request->input('description');
        $masaroWaste->updated_at = Carbon::now('Asia/Jakarta');
        $masaroWaste->updated_by = $user->id;
        $masaroWaste->save();

        //Save Image
        if($image != null) {
            $img = Image::make($image);
            $filename = $masaroWaste->img_path;
            $img->save('../public_html/storage/admin/masarocategory/'. $filename, 75);
        }

        Session::flash('success', 'Success Updating new Masaro Waste Category!');
        return redirect()->route('admin.masaro-wastes.index');
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
            $masaroWasteid = $request->input('id');
            $masaroWaste = MasaroWasteCategoryData::find($masaroWasteid);
            $masaroWaste->delete();

            $image_path = "../public_html/storage/admin/masarocategory/" . $masaroWaste->img_path;  // Value is not URL but directory file path
            if(file_exists($image_path)) {
                unlink($image_path);
            }

            Session::flash('success', 'Success Deleting Masaro Waste Category ' . $masaroWaste->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
