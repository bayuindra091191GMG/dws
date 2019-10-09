<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Product;
use App\Models\UserVoucher;
use App\Models\Voucher;
use App\Transformer\VoucherTransformer;
use App\Transformer\VoucherUserTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;

class VoucherController extends Controller
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
        $vouchers = Voucher::all();
        return DataTables::of($vouchers)
            ->setTransformer(new VoucherTransformer)
            ->make(true);
    }

    public function getIndexUserVoucher(Request $request){
        $vouchers = UserVoucher::where('is_used', 1);
        return DataTables::of($vouchers)
            ->setTransformer(new VoucherUserTransformer)
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.voucher.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUsers()
    {
        return view('admin.voucher.index-users');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.voucher.create');
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
            'code'          => 'required|max:100|unique:vouchers',
            'description'   => 'required|max:100',
            'start_date'    => 'required',
            'finish_date'   => 'required',
            'category'      => 'required',
            'affiliate'     => 'required',
            'required_point'=> 'required',
            'company'       => 'required'
        ]);

        $image = $request->file('img_path');

        if($image == null) return redirect()->back()->withErrors('Harus Upload Image!', 'default')->withInput($request->all());;
        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        //Sort out Data
        $startDate = Carbon::parse($request->input('start_date'));
        $finishDate = Carbon::parse($request->input('finish_date'));

        //Check DateTime
        if(!$finishDate->greaterThan($startDate)){
            Session::flash('error', 'Finish Date cannot be less than Start Date!');
            return redirect()->back()->withErrors('Finish Date cannot be less than Start Date!', 'default')->withInput($request->all());;
        }

        $user = Auth::guard('admin')->user();
        $voucher = Voucher::create([
            'code'          => $request->input('code'),
            'description'   => $request->input('description'),
            'start_date'    => $startDate,
            'finish_date'   => $finishDate,
            'category_id'   => $request->input('category'),
            'affiliate_id'  => $request->input('affiliate'),
            'company_id'    => $request->input('company'),
            'required_point'=> $request->input('required_point'),
            'quantity'      => $request->input('qty'),
            'created_at'    => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            'created_by'    => $user->id,
            'updated_at'    => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            'updated_by'    => $user->id,
            'status_id'     => $request->input('status')
        ]);

        //Save Image
        $img = Image::make($image);
        $extStr = $img->mime();
        $ext = explode('/', $extStr, 2);

        $filename = $voucher->id.'_main_'.$voucher->code.'_'.Carbon::now('Asia/Jakarta')->format('Ymdhms'). '.'. $ext[1];
        $img->save(public_path('storage/admin/vouchers/'. $filename), 75);

        $voucher->img_path = $filename;
        $voucher->save();

        Session::flash('success', 'Success Creating new Voucher!');
        return redirect()->route('admin.vouchers.index');
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
        $voucher = Voucher::find($id);

        $startDate = $date = Carbon::parse($voucher->start_date)->format("d M Y");
        $finishDate = $date = Carbon::parse($voucher->finish_date)->format("d M Y");

        $data = [
            'voucher'       => $voucher,
            'startDate'     => $startDate,
            'finishDate'    => $finishDate
        ];

        return view('admin.voucher.edit')->with($data);
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
            'code'          => 'required|max:100',
            'description'   => 'required|max:100',
            'start_date'    => 'required',
            'finish_date'   => 'required'
        ]);

        $image = $request->file('img_path');

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        //Sort out Data
        $startDate = Carbon::parse($request->input('start_date'));
        $finishDate = Carbon::parse($request->input('finish_date'));

        //Check DateTime
        if(!$finishDate->greaterThan($startDate)){
            Session::flash('error', 'Finish Date cannot be less than Start Date!');
            return redirect()->back()->withErrors('Finish Date cannot be less than Start Date!', 'default')->withInput($request->all());;
        }

        $user = Auth::guard('admin')->user();
        $voucher = Voucher::find($request->input('id'));

        $voucher->code = $request->input('code');
        $voucher->description = $request->input('description');
        $voucher->start_date = $startDate;
        $voucher->finish_date = $finishDate;
        $voucher->status_id = $request->input('status');
        $voucher->category_id = $request->input('category');
        $voucher->affiliate_id = $request->input('affiliate');
        $voucher->quantity = $request->input('qty');
        $voucher->required_point = $request->input('required_point');
        $voucher->updated_at = Carbon::now('Asia/Jakarta')->toDateTimeString();
        $voucher->updated_by = $user->id;
        $voucher->save();

        //Save Image
        if($image != null) {
            $img = Image::make($image);
            $filename = $voucher->img_path;
            $img->save(public_path('storage/admin/vouchers/'. $filename), 75);
            $voucher->img_path = $filename;
            $voucher->save();
        }

        Session::flash('success', 'Success Updating Voucher ' . $voucher->code . '!');
        return redirect()->route('admin.vouchers.index');
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
            $voucherId = $request->input('id');

            $check = UserVoucher::where('voucher_id', $voucherId)->first();
            if($check != null){
                Session::flash('error', 'Voucher sudah digunakan!');
                return Response::json(array('errors' => 'INVALID'));
            }

            $voucher = Voucher::find($voucherId);
            $voucher->delete();

            $image_path = public_path("storage/admin/vouchers/" . $voucher->img_path);  // Value is not URL but directory file path
            if(file_exists($image_path)) {
                unlink($image_path);
            }

            Session::flash('success', 'Success Deleting Voucher ' . $voucher->code);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
