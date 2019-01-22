<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\AdminUser;
use App\Models\DwsWaste;
use App\Models\DwsWasteCategoryData;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class DwsWasteTransformer extends TransformerAbstract
{
    public function transform(DwsWaste $dwsWaste){

        try{
            $createdDate = Carbon::parse($dwsWaste->created_at)->format('d M Y');

            $action = "<a class='btn btn-xs btn-info' href='dws-waste-items/edit/".$dwsWaste->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $dwsWaste->id ."' ><i class='fas fa-trash-alt'></i></a>";

            return[
                'name'              => $dwsWaste->name,
                'description1'      => $dwsWaste->description,
                'description2'      => $dwsWaste->other_description,
                'image'             => $dwsWaste->img_path,
                'category'          => $dwsWaste->dws_waste_category_data->description,
                'created_at'        => $createdDate,
                'created_by'        => $dwsWaste->createdBy->first_name . ' ' . $dwsWaste->createdBy->last_name,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}