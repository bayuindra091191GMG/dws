<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\TransactionHeader;
use App\Models\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    public function transform(TransactionHeader $header){

        try{
            $createdDate = Carbon::parse($header->created_at)->format('d M Y');
            $action = "<a class='btn btn-xs btn-info' href='transactions/show/".$header->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-info'></i></a>";

            return[
                'transaction_no'    => $header->transaction_no,
                'name'              => $header->user->first_name . ' ' . $header->user->last_name,
                'type'              => $header->transaction_type->description,
                'category'          => $header->waste_category->name,
                'total_weight'      => $header->total_weight . 'kg',
                'total_price'       => 'Rp'.$header->total_price,
                'status'            => $header->status->description,
                'created_at'        => $createdDate,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}