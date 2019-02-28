<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\PointHistory;
use App\Models\PointWastecollectorHistory;
use App\Models\TransactionHeader;
use App\Models\User;
use Carbon\Carbon;
use Intervention\Image\Point;
use League\Fractal\TransformerAbstract;

class PointWastecollectorTransformer extends TransformerAbstract
{
    public function transform(PointWastecollectorHistory $point){

        try{
//            $date = Carbon::parse($point->date)->toIso8601String();
            $createdDate = Carbon::parse($point->created_at)->format('d M Y');
            $action = "<a class='btn btn-xs btn-info' href='points/show/".$point->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-info'></i></a>";

            if($point->type_transaction == 'debet'){
                $amount = "(".$point->amount.")";
            }
            else{
                $amount = $point->amount;
            }

            if(!empty($point->wastecollector_id)){
                $name = $point->waste_collector->first_name. " ". $point->waste_collector->last_name;
            }
            else{
                $name = "BELUM ASSIGN";
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
            error_log($exception);
        }
    }
}