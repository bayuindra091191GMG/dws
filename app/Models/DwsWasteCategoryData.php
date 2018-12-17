<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Dec 2018 08:09:49 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DwsWasteCategoryData
 * 
 * @property int $id
 * @property string $name
 * @property string $title
 * @property float $price
 * @property string $sub_title
 * @property string $img_path
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 *
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 *
 * @package App\Models
 */
class DwsWasteCategoryData extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'price' => 'float',
		'created_by' => 'int'
	];

	protected $fillable = [
		'name',
		'title',
		'price',
		'sub_title',
		'img_path',
		'description',
		'created_by'
	];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by');
    }
}
