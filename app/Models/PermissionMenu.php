<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 13 Feb 2019 10:07:05 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PermissionMenu
 * 
 * @property int $id
 * @property int $role_id
 * @property int $menu_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 *
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 * @property \App\Models\Menu $menu
 * @property \App\Models\Role $role
 *
 * @package App\Models
 */
class PermissionMenu extends Eloquent
{
	protected $casts = [
		'role_id' => 'int',
		'menu_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'role_id',
		'menu_id',
		'created_by',
		'updated_by'
	];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by');
    }

	public function menu()
	{
		return $this->belongsTo(\App\Models\Menu::class);
	}

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class);
	}
}
