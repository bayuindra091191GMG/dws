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

    public function createTransaction(Request $request)
    {
        $rules = array(
            'user_id'                 => 'required|email|max:100|unique:users',
            'transaction_type'            => 'required|max:100',
            'last_name'             => 'required|max:100',
            'phone'                 => 'required|unique:users',
            'password'              => 'required|min:6|max:20',
        );

        $messages = array(
            'not_contains'  => 'Email cannot contain these characters +',
            'phone.unique'  => 'Your phone number already registered!',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
    }
}
