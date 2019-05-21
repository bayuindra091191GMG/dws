<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Affiliate;
use App\Transformer\AffiliateTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;

class AffiliateController extends Controller
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
        $users = Affiliate::query();
        return DataTables::of($users)
            ->setTransformer(new AffiliateTransformer)
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
        return view('admin.affiliate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.affiliate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Get Data from View
        $validator = Validator::make($request->all(), [
            'name'  => 'required|max:100',
        ]);
        $image = $request->file('img_path');

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = Auth::guard('admin')->user();
        $affiliate = Affiliate::create([
            'name'          => $request->input('name'),
            'created_at'    => Carbon::now('Asia/Jakarta'),
            'created_by'    => $user->id
        ]);

        //Save Image
        if($image != null) {
            $img = Image::make($image);
            $extStr = $img->mime();
            $ext = explode('/', $extStr, 2);

            $filename = $affiliate->id . '_main_' . $affiliate->name . '_' . Carbon::now('Asia/Jakarta')->format('Ymdhms') . '.' . $ext[1];

            $img->save(public_path('storage/admin/affiliates/' . $filename), 75);

            $affiliate->img_path = $filename;
            $affiliate->save();
        }

        Session::flash('success', 'Berhasil Membuat Affiliate baru!');
        return redirect()->route('admin.affiliate.index');
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
        $data = Affiliate::find($id);

        return view('admin.affiliate.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Get Data from View
        $validator = Validator::make($request->all(), [
            'name'  => 'required|max:100'
        ]);

        $image = $request->file('img_path');

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $affiliate = Affiliate::find($request->input('id'));

        $affiliate->name = $request->input('name');
        $affiliate->save();

        //Save Image
        if($image != null) {
            $img = Image::make($image);
            $filename = $affiliate->img_path;
            $img->save(public_path('storage/admin/affiliates/'. $filename), 75);
        }

        Session::flash('success', 'Berhasil Mengubah Affiliate ' . $affiliate->name . '!');
        return redirect()->route('admin.affiliate.index');
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
            $affiliateId = $request->input('id');
            $affiliate = Affiliate::find($affiliateId);
            $affiliate->delete();

            $image_path = public_path("storage/admin/affiliates/" . $affiliate->img_path);  // Value is not URL but directory file path
            if(file_exists($image_path)) {
                unlink($image_path);
            }

            Session::flash('success', 'Berhasil Menghapus Afiliate ' . $affiliate->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    /**
     * Function to select2 Affiliates
     *
     * @param Request $request
     * @return
     */
    public function getAffiliates(Request $request){
        $term = trim($request->q);
        $formatted_tags = [];

        $modelDB = Affiliate::where(function ($q) use ($term) {
            $q->where('name', 'LIKE', '%' . $term . '%');
        })->get();

        foreach ($modelDB as $model) {
            $formatted_tags[] = ['id' => $model->id, 'text' => $model->name];
        }

        return \Response::json($formatted_tags);
    }
}
