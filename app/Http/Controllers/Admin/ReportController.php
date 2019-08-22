<?php


namespace App\Http\Controllers\Admin;


use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
        $filenameExcel = 'TRANSACTION_REPORT_' . $nowExcel->toDateTimeString(). '.xlsx';

        $adminUser = Auth::guard('admin')->user();
        $wasteBankId = 0;
        if($adminUser->is_super_admin !== 1 && !empty($adminUser->waste_bank_id)){
            $wasteBankId = $adminUser->waste_bank_id;
        }

        return (new TransactionExport(
            $start->toDateTimeString(),
            $end->toDateTimeString(),
            (int) $request->input('transaction_type'),
            (int) $request->input('waste_category'),
            $wasteBankId
        ))->download($filenameExcel);
    }
}
