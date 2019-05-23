<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoucherCategory;
use App\Transformer\VoucherCategoryTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;

class VoucherCategoryController extends Controller
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
        $users = VoucherCategory::query();
        return DataTables::of($users)
            ->setTransformer(new VoucherCategoryTransformer)
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
        return view('admin.voucher-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.voucher-categories.create');
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
            'name'          => 'required|max:100|unique:voucher_categories'
        ]);
        $image = $request->file('img_path');

        if($image == null){
            return back()->withErrors("Image required")->withInput($request->all());
        }

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        $user = Auth::guard('admin')->user();

        //Save Data
        $vCategory = VoucherCategory::create([
            'name'  => $request->input('name'),
            'created_by'    => $user->id,
            'updated_by'    => $user->id,
            'created_at'    => Carbon::now('Asia/Jakarta'),
            'updated_at'    => Carbon::now('Asia/Jakarta')
        ]);

        //Save Image
        $img = Image::make($image);
        $extStr = $img->mime();
        $ext = explode('/', $extStr, 2);

        $filename = $vCategory->id.'_main_'.$vCategory->name.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];
        //$img->save('../public_html/storage/admin/vouchercategories/' . $filename, 75);
        $img->save(public_path('storage/admin/vouchercategories/'. $filename), 75);

        $vCategory->img_path = $filename;
        $vCategory->save();

        Session::flash('success', 'Success Creating new Voucher Category!');
        return redirect()->route('admin.voucher-categories.index');
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
        $voucherCategory = VoucherCategory::find($id);

        return view('admin.voucher-categories.edit', compact('voucherCategory'));
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
            'name'          => 'required|max:100|unique:voucher_categories'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        $user = Auth::guard('admin')->user();

        //Save Data
        $vCategory = VoucherCategory::find($request->input('id'));
        $vCategory->name = $request->input('name');
        $vCategory->updated_at = Carbon::now('Asia/Jakarta');
        $vCategory->updated_by = $user->id;
        $vCategory->save();

        //Save Image
        $image = $request->file('img_path');
        if($image != null) {
            $img = Image::make($image);
            $extStr = $img->mime();
            $ext = explode('/', $extStr, 2);

            $filename = $vCategory->id.'_main_'.$vCategory->name.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];

            //$img->save('../public_html/storage/admin/vouchercategories/' . $filename, 75);
            $img->save(public_path('storage/admin/vouchercategories/'. $filename), 75);
            $vCategory->img_path = $filename;
            $vCategory->save();
        }

        Session::flash('success', 'Success Updating Voucher Category!');
        return redirect()->route('admin.voucher-categories.index');
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
            $vCategoryId = $request->input('id');
            $vCategory = VoucherCategory::find($vCategoryId);
            if($vCategory->vouchers != null){
                Session::flash('success', 'Ketemu');
                return Response::json(array('success' => 'VALID'));
            }
            $vCategory->delete();

            $image_path = public_path("storage/admin/vouchercategories/" . $vCategory->img_path);  // Value is not URL but directory file path
            if(file_exists($image_path)) {
                unlink($image_path);
            }

            Session::flash('success', 'Success Deleting Voucher Category ' . $vCategory->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    /**
     * Function to select2 Voucher Categories
     *
     * @param Request $request
     * @return
     */
    public function getCategories(Request $request){
        $term = trim($request->q);
        $formatted_tags = [];

        $modelDB = VoucherCategory::where(function ($q) use ($term) {
            $q->where('name', 'LIKE', '%' . $term . '%');
        })->get();

        foreach ($modelDB as $model) {
            $formatted_tags[] = ['id' => $model->id, 'text' => $model->name];
        }

        return \Response::json($formatted_tags);
    }
}
