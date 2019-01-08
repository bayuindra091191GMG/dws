<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Illuminate\Http\Request;

class TransactionHeaderController extends Controller
{
    public function getTransactions(Request $request)
    {
        $transactions = TransactionHeader::where('user_id', $request->input('user_id'))->get();

        return new UserResource($transactions);
    }

    public function getTransactionDetails(Request $request)
    {
        $detailTransactions = TransactionDetail::where('transaction_header_id', $request->input('transaction_header_id'))->get();

        return new UserResource($detailTransactions);
    }
}
