<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 26 Dec 2018 04:18:30 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MasaroWasteCategoryData
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $img_path
 * @property float $price
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * 
 * @property \App\Models\AdminUser $admin_user
 *
 * @package App\Models
 */
class MasaroWasteCategoryData extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'price' => 'float',
		'created_by' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'img_path',
		'price',
		'created_by'
	];

	public function admin_user()
	{
		return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
	}
}
