<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2019
 * Time: 15:08
 */

namespace App\Transformer;


use App\Models\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserPenjemputanRutinTransformer extends TransformerAbstract
{
    public function transform(User $user){

        try{
            $createdDate = Carbon::parse($user->created_at)->format('d M Y');

            $editUrl = route('admin.user.penjemputan_rutin.edit', ['id' => $user->id]);
            $action = "<a class='btn btn-xs btn-info' href='". $editUrl. "' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";

            return[
                'email'             => $user->email,
                'name'              => $user->first_name . ' ' . $user->last_name,
                'phone'             => $user->phone,
                'status'            => $user->status->description,
                'created_at'        => $createdDate,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}