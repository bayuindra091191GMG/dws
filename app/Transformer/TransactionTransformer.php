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
            $date = Carbon::parse($header->date)->toIso8601String();
            $createdDate = Carbon::parse($header->created_at)->format('d M Y');

            if($header->transaction_type_id == 1){
                $showUrl = route('admin.transactions.penjemputan_rutin.show', ['id' => $header->id]);
            }
            elseif($header->transaction_type_id == 2){
                $showUrl = route('admin.transactions.antar_sendiri.show', ['id' => $header->id]);
            }
            else{
                $showUrl = route('admin.transactions.on_demand.show', ['id' => $header->id]);
            }
            $action = "<a class='btn btn-xs btn-info' href='". $showUrl. "' data-toggle='tooltip' data-placement='top'><i class='fas fa-info'></i></a>";

            // Check customer
            if(!empty($header->user_id)){
                $name = $header->user->first_name. " ". $header->user->last_name;
            }
            else{
                $name = "BELUM ASSIGN";
            }

            // Check waste bank
            if(!empty($header->waste_bank_id)){
                $wasteBank = $header->waste_bank->name;
            }
            else{
                $wasteBank = "-";
            }

            // Check waste collector
            if(!empty($header->waste_collector_id)){
                $wasteCollector = $header->waste_collector->first_name. ' '. $header->waste_collector->last_name;
            }
            else{
                $wasteCollector = "-";
            }

            return[
                'date'              => $date,
                'transaction_no'    => $header->transaction_no,
                'name'              => $name,
                'type'              => $header->transaction_type->description,
                'category'          => $header->waste_category->name,
                'total_weight'      => $header->total_weight,
                'total_price'       => $header->total_price,
                'waste_bank'        => $wasteBank,
                'waste_collector'   => $wasteCollector,
                'status'            => $header->status->description,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}