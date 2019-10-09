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
use App\Models\Voucher;
use App\Models\VoucherCategory;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class VoucherTransformer extends TransformerAbstract
{
    public function transform(Voucher $data){

        try{
            $createdDate = Carbon::parse($data->created_at)->format('d M Y');
            $startDate = Carbon::parse($data->start_date)->format('d M Y');
            $finishDate = Carbon::parse($data->finish_date)->format('d M Y');

            $action = "<a class='btn btn-xs btn-info' href='vouchers/edit/".$data->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $data->id ."' ><i class='fas fa-trash-alt'></i></a>";

//            $imgPath = "<img src='". public_path('storage/admin/vouchers'.$data->img_path) . "' width='50'/>";

            if($data->category_id != null || $data->category_id != 0){
                $tmpCategory = VoucherCategory::find($data->category_id);
                $category = $tmpCategory->name;
            }
            else{
                $category = '-';
            }

            return[
                'code'              => $data->code,
                'description'       => $data->description,
                'category'          => $category,
//                'product'           => $product,
                'quantity'          => $data->quantity,
                'required_point'    => $data->required_point,
//                'img_path'          => $imgPath,
                'created_at'        => $createdDate,
                'created_by'        => $data->createdBy->first_name . ' ' . $data->createdBy->last_name,
                'start_date'        => $startDate,
                'finish_date'       => $finishDate,
                'status'            => $data->status->description,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}
