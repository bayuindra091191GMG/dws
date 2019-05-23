<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\AdminUser;
use App\Models\Affiliate;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AffiliateTransformer extends TransformerAbstract
{
    public function transform(Affiliate $affiliate){

        try{
            $createdDate = Carbon::parse($affiliate->created_at)->format('d M Y');

            $action = "<a class='btn btn-xs btn-info' href='affiliates/edit/".$affiliate->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $affiliate->id ."' ><i class='fas fa-trash-alt'></i></a>";

            return[
                'name'             => $affiliate->name,
                'img_path'         => $affiliate->img_path,
                'created_at'       => $createdDate,
                'action'           => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}