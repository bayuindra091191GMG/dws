<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 13 Feb 2019 10:07:12 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PermissionMenuSub
 * 
 * @property int $id
 * @property int $role_id
 * @property int $menu_sub_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 * @property \App\Models\MenuSub $menu_sub
 * @property \App\Models\Role $role
 *
 * @package App\Models
 */
class PermissionMenuSub extends Eloquent
{
	protected $casts = [
		'role_id' => 'int',
		'menu_sub_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'role_id',
		'menu_sub_id',
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

	public function menu_sub()
	{
		return $this->belongsTo(\App\Models\MenuSub::class);
	}

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class);
	}
}
