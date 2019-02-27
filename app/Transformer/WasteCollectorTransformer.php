<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\Category;
use App\Models\Product;
use App\Models\WasteCollector;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class WasteCollectorTransformer extends TransformerAbstract
{
    public function transform(WasteCollector $data){

        try{
            $createdDate = Carbon::parse($data->created_at)->format('d M Y');

            //$action = "<a class='btn btn-xs btn-info' href='wastecollectors/edit/".$data->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";
            $action = "<a class='btn btn-xs btn-info' href='". route('admin.wastecollectors.show', ['id' => $data->id]). "'><i class='fas fa-info'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $data->id ."' ><i class='fas fa-trash-alt'></i></a>";

            return[
                'email'             => $data->email,
                'name'              => $data->first_name . ' ' . $data->last_name,
                'identity_number'   => $data->identity_number,
                'phone'             => $data->phone,
                'created_by'        => $data->createdBy->first_name . ' ' . $data->createdBy->last_name,
                'created_at'        => $createdDate,
                'status'            => $data->status->description,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}