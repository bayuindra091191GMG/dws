<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\PointHistory;
use App\Models\TransactionHeader;
use App\Models\User;
use Carbon\Carbon;
use Intervention\Image\Point;
use Illuminate\Support\Facades\Log;
use League\Fractal\TransformerAbstract;

class PointTransformer extends TransformerAbstract
{
    public function transform(PointHistory $point){

        try{
//            $date = Carbon::parse($point->date)->toIso8601String();
            $createdDate = Carbon::parse($point->created_at)->toIso8601String();
            $action = "<a class='btn btn-xs btn-info' href='points/show/".$point->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-info'></i></a>";

            $amount = $point->amount;
            if($point->type_transaction == 'debet'){
                $amount = "(".$point->amount.")";
            }

            $name = "BELUM ASSIGN";
            if(!empty($point->user_id)){
                $name = $point->user->first_name. " ". $point->user->last_name;
            }

            return[
                'date'              => $createdDate,
                'transaction_no'    => $point->transaction_header->transaction_no,
                'name'              => $name,
                'type'              => $point->type_transaction,
                'amount'            => $amount,
                'saldo'             => $point->saldo,
                'description'       => $point->description,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            Log::error("PointTransformer error: ". $exception);
        }
    }
}