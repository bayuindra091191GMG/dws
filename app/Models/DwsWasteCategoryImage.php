<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 23 Oct 2019 11:06:33 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DwsWasteCategoryImage
 * 
 * @property int $id
 * @property int $dws_waste_category_id
 * @property string $img_path
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\DwsWasteCategoryData $dws_waste_category_data
 *
 * @package App\Models
 */
class DwsWasteCategoryImage extends Eloquent
{
	protected $casts = [
		'dws_waste_category_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'dws_waste_category_id',
		'img_path',
		'created_by',
		'updated_by'
	];

	public function dws_waste_category_data()
	{
		return $this->belongsTo(\App\Models\DwsWasteCategoryData::class, 'dws_waste_category_id');
	}
}
