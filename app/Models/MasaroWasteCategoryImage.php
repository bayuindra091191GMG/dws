<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 23 Oct 2019 11:07:00 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MasaroWasteCategoryImage
 * 
 * @property int $id
 * @property int $masaro_waste_category_id
 * @property string $img_path
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\MasaroWasteCategoryData $masaro_waste_category_data
 *
 * @package App\Models
 */
class MasaroWasteCategoryImage extends Eloquent
{
	protected $casts = [
		'masaro_waste_category_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'masaro_waste_category_id',
		'img_path',
		'created_by',
		'updated_by'
	];

	public function masaro_waste_category_data()
	{
		return $this->belongsTo(\App\Models\MasaroWasteCategoryData::class, 'masaro_waste_category_id');
	}
}
