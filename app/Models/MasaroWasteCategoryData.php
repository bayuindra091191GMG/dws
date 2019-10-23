<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 23 Oct 2019 11:37:04 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MasaroWasteCategoryData
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $examples
 * @property string $description
 * @property string $img_path
 * @property float $price
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\AdminUser $admin_user
 * @property \Illuminate\Database\Eloquent\Collection $masaro_waste_category_images
 * @property \Illuminate\Database\Eloquent\Collection $masaro_wastes
 *
 * @package App\Models
 */
class MasaroWasteCategoryData extends Eloquent
{
	protected $casts = [
		'price' => 'float',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'examples',
		'description',
		'img_path',
		'price',
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

	public function masaro_waste_category_images()
	{
		return $this->hasMany(\App\Models\MasaroWasteCategoryImage::class, 'masaro_waste_category_id');
	}

	public function masaro_wastes()
	{
		return $this->hasMany(\App\Models\MasaroWaste::class, 'masaro_waste_category_datas_id');
	}
}
