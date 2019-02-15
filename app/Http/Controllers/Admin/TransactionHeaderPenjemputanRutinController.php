<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 06/02/2019
 * Time: 15:49
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Transformer\UserWasteBankTransformer;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TransactionHeaderPenjemputanRutinController extends Controller
{
    public function indexSuscribedUsers(){
//        $user = Auth::guard('admin')->user();
//        $adminWasteBankId = $user->waste_bank_id;
//
//        $subscribedUsers = User::where('status_id', 1)
//            ->whereHas('waste_banks', function($query) use ($adminWasteBankId){
//                $query->where('waste_bank_id', $adminWasteBankId);
//            })->get();
//
//        dd($subscribedUsers);

        return view('admin.transaction.rutin.index_subscribed_users');
    }

    public function getIndexSuscribedUsers(){
        $user = Auth::guard('admin')->user();
        $adminWasteBankId = $user->waste_bank_id;

        $subscribedUsers = User::where('status_id', 1)
            ->whereHas('waste_banks', function($query) use ($adminWasteBankId){
                $query->where('waste_bank_id', $adminWasteBankId);
            });

        return DataTables::of($subscribedUsers)
            ->setTransformer(new UserWasteBankTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}