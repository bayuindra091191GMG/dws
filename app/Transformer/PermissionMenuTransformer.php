<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer;

use App\Models\PermissionMenu;
use App\Models\Role;
use League\Fractal\TransformerAbstract;

class PermissionMenuTransformer extends TransformerAbstract
{
    public function transform(Role $role){
        $permissionMenuRoute = route('admin.permission-menus.show', ['permission_menu' => $role->id]);
        $roleName =  "<a style='text-decoration: underline;' href='" . $permissionMenuRoute. "' target='_blank'>". $role->name . "</a>";
        $action =
            "<a class='btn btn-xs btn-info' href='permission-menus/edit/". $role->id."' data-toggle='tooltip' data-placement='top'><i class='fas fa-edit'></i></a>";

        $permission = PermissionMenu::where('role_id', $role->id)->count();

        return[
            'role'          => $roleName,
            'permission'    => $permission,
            'action'        => $action
        ];
    }
}