<?php


namespace App\Http\Controllers\Admin;


use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\TransactionHeader;
use App\Models\WasteBank;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function transactionReport(){
        $dateStart = Carbon::today()->subMonths(1)->format('d M Y');
        $dateEnd = Carbon::today()->format('d M Y');

        $data = [
            'dateStart' => $dateStart,
            'dateEnd'   => $dateEnd
        ];

        return view('admin.transaction.report')->with($data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response|BinaryFileResponse
     */
    public function transactionReportSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'date_start'        => 'required',
            'date_end'          => 'required',
        ],[
            'start_date.required'   => 'Dari Tanggal wajib diisi!',
            'end_date.required'     => 'Sampai Tanggal wajib diisi!',

        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $start = Carbon::createFromFormat('d M Y', $request->input('date_start'), 'Asia/Jakarta');
        $end = Carbon::createFromFormat('d M Y', $request->input('date_end'), 'Asia/Jakarta');

        // Validate date
        if($start->gt($end)){
            return redirect()->back()->withErrors('Dari Tanggal tidak boleh lebih besar dari Sampai Tanggal!', 'default')->withInput($request->all());
        }

        $nowExcel = Carbon::now('Asia/Jakarta');
        $filenameExcel = 'LAPORAN_TRANSAKSI_' . $nowExcel->toDateTimeString(). '.xlsx';

        $adminUser = Auth::guard('admin')->user();

        $wasteBankId = 0;
        if($adminUser->is_super_admin !== 1 && !empty($adminUser->waste_bank_id)){
            $wasteBankId = $adminUser->waste_bank_id;
        }

        $transactions = DB::table('transaction_headers')
            ->whereBetween('date', array($start->toDateTimeString(), $end->toDateTimeString()));

//        $transactions = TransactionHeader::whereBetween('date', array($start->toDateTimeString(), $end->toDateTimeString()));

        $transactionTypeId = (int) $request->input('transaction_type');
        if($transactionTypeId != 0){
            $transactions = $transactions->where('transaction_type_id', $transactionTypeId);
        }

        $wasteCategoryId = (int) $request->input('waste_category');
        if($wasteCategoryId != 0){
            $transactions = $transactions->where('waste_category_id', $wasteCategoryId);
        }

        if($wasteBankId != 0){
            $transactions = $transactions->where('waste_bank_id', $wasteBankId);
        }

        if($transactions->doesntExist()){
            return redirect()->back()->withErrors('Transaksi tidak ditemukan!', 'default')->withInput($request->all());
        }

        return (new TransactionExport(
            $start->toDateTimeString(),
            $end->toDateTimeString(),
            $transactionTypeId,
            $wasteCategoryId,
            $wasteBankId
        ))->download($filenameExcel);
    }

    public function userWasteBankReport(){
        // Check superadmin & waste category type
        $admin = Auth::guard('admin')->user();
        $adminWasteBank = null;
        $adminCategoryType = 'all';
        if($admin->is_super_admin === 0){
            if(!empty($admin->waste_bank_id)){
                $adminWasteBank = WasteBank::find($admin->waste_bank_id);
            }

            $adminCategoryType = $admin->waste_bank->waste_category_id === 1 ? 'dws' : 'masaro';
        }

        $wasteBanks = WasteBank::orderBy('name')->get();

        $data = [
            'admin'             => $admin,
            'wasteBanks'        => $wasteBanks,
            'adminWasteBank'    => $adminWasteBank,
            'adminCategoryType' => $adminCategoryType
        ];
    }
}
