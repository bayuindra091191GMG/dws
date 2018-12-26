<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\MasaroWasteCategoryData;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MasaroWasteCategoryTransformer extends TransformerAbstract
{
    public function transform(MasaroWasteCategoryData $masaroWaste){

        try{
            $createdDate = Carbon::parse($masaroWaste->created_at)->format('d M Y');

            $action = "<a class='btn btn-xs btn-info' href='masaro-wastes/edit/".$masaroWaste->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $masaroWaste->id ."' ><i class='fas fa-trash-alt'></i></a>";

            return[
                'name'              => $masaroWaste->name,
                'price'             => $masaroWaste->price,
                'description'       => $masaroWaste->description,
                'image'             => $masaroWaste->img_path,
                'created_at'        => $createdDate,
                'created_by'        => $masaroWaste->createdBy->first_name . ' ' . $masaroWaste->createdBy->last_name,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}