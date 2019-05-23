<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;

use App\Models\UserVoucher;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class VoucherUserTransformer extends TransformerAbstract
{
    public function transform(UserVoucher $data){

        try{
            $createdDate = Carbon::parse($data->created_at)->toIso8601String();
            $redeemDate = '';
            $usedDate = '';
            if($data->redeem_at != null){
                $redeemDate = Carbon::parse($data->redeem_at)->toIso8601String();
            }
            if($data->used_at != null){
                $usedDate = Carbon::parse($data->used_at)->toIso8601String();
            }
            $isUsed = $data->is_used == 0 ? "Belum Digunakan" : "Sudah Digunakan";


            $action = "<a class='btn btn-xs btn-info' href='vouchers/edit/".$data->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $data->id ."' ><i class='fas fa-trash-alt'></i></a>";

            return[
                'name'              => $data->user->first_name. ' ' . $data->user->last_name,
                'code'              => $data->voucher->code,
                'redeem_at'         => $redeemDate,
                'is_used'           => $isUsed,
                'created_at'        => $createdDate,
                'used_at'           => $usedDate,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}