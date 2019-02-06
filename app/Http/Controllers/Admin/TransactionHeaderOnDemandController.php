<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 06/02/2019
 * Time: 14:41
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\TransactionHeader;
use App\Transformer\TransactionTransformer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionHeaderOnDemandController extends Controller
{
    public function index()
    {
        return view('admin.transaction.on_demand.index');
    }

    public function getIndex(Request $request){
        $transations = TransactionHeader::where('transaction_type_id', 3)->get();
        return DataTables::of($transations)
            ->setTransformer(new TransactionTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}