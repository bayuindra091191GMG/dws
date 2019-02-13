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
use App\Models\VoucherCategory;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class VoucherCategoryTransformer extends TransformerAbstract
{
    public function transform(VoucherCategory $data){

        try{
            $createdDate = Carbon::parse($data->created_at)->format('d M Y');
            $updatedDate = Carbon::parse($data->updated_at)->format('d M Y');

            $action = "<a class='btn btn-xs btn-info' href='voucher-categories/edit/".$data->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $data->id ."' ><i class='fas fa-trash-alt'></i></a>";

            return[
                'name'              => $data->name,
                'img_path'          => $data->img_path,
                'created_at'        => $createdDate,
                'created_by'        => $data->createdBy->first_name . ' ' . $data->createdBy->last_name,
                'updated_at'        => $updatedDate,
                'updated_by'        => $data->updatedBy->first_name . ' ' . $data->updatedBy->last_name,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}