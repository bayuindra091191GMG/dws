<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\AdminUser;
use App\Models\WasteBank;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class WasteBankTransformer extends TransformerAbstract
{
    public function transform(WasteBank $wasteBank){

        try{
            $createdDate = Carbon::parse($wasteBank->created_at)->format('d M Y');

            $action = "<a class='btn btn-xs btn-info' href='waste-banks/edit/".$wasteBank->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $wasteBank->id ."' ><i class='fas fa-trash-alt'></i></a>";
            $days = str_replace('#', ',', $wasteBank->open_days);

            return[
                'name'              => $wasteBank->name,
                'address'           => $wasteBank->address,
                'open_days'         => $days,
                'open_hours'        => $wasteBank->open_hours . ' - ' . $wasteBank->closed_hours,
                'latitude'          => $wasteBank->latitude,
                'longitude'         => $wasteBank->longitude,
                'pic'               => $wasteBank->admin_user->first_name . ' ' . $wasteBank->admin_user->last_name,
                'city'              => $wasteBank->city->name,
                'phone'             => $wasteBank->phone,
                'created_at'        => $createdDate,
                'created_by'        => $wasteBank->createdBy->first_name . ' ' . $wasteBank->createdBy->last_name,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}