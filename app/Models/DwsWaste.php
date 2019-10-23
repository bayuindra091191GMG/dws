<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 23 Oct 2019 11:38:55 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DwsWaste
 * 
 * @property int $id
 * @property int $dws_waste_category_datas_id
 * @property string $name
 * @property string $description
 * @property string $other_description
 * @property string $img_path
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\AdminUser $admin_user
 * @property \App\Models\DwsWasteCategoryData $dws_waste_category_data
 *
 * @package App\Models
 */
class DwsWaste extends Eloquent
{
	protected $casts = [
		'dws_waste_category_datas_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'dws_waste_category_datas_id',
		'name',
		'description',
		'other_description',
		'img_path',
		'created_by',
		'updated_by'
	];

	public function admin_user()
	{
		return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by');
	}

	public function dws_waste_category_data()
	{
		return $this->belongsTo(\App\Models\DwsWasteCategoryData::class, 'dws_waste_category_datas_id');
	}
}
