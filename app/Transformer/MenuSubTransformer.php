<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\Menu;
use App\Models\MenuSub;
use League\Fractal\TransformerAbstract;

class MenuSubTransformer extends TransformerAbstract
{
    public function transform(MenuSub $menu){
        try{
            $action = "<a class='btn btn-xs btn-info' href='menu-subs/edit/".$menu->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $menu->id ."' ><i class='fas fa-trash-alt'></i></a>";

            return[
                'name'              => $menu->name,
                'route'             => $menu->route,
                'menu_header'       => $menu->menu->name,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}