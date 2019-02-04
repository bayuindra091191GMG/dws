<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 01 Feb 2019 05:04:32 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class VoucherCategory
 * 
 * @property int $id
 * @property string $name
 * @property string $img_path
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 *
 * @property \Illuminate\Database\Eloquent\Collection $vouchers
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 *
 * @package App\Models
 */
class VoucherCategory extends Eloquent
{
	protected $casts = [
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'name',
		'img_path',
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

	public function vouchers()
	{
		return $this->hasMany(\App\Models\Voucher::class, 'category_id');
	}
}
