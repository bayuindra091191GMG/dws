<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 21 May 2019 03:47:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Affiliate
 * 
 * @property int $id
 * @property string $name
 * @property string $img_path
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\AdminUser $admin_user
 * @property \Illuminate\Database\Eloquent\Collection $vouchers
 *
 * @package App\Models
 */
class Affiliate extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'created_by' => 'int'
	];

	protected $fillable = [
		'name',
		'img_path',
		'created_by'
	];

	public function admin_user()
	{
		return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
	}

	public function vouchers()
	{
		return $this->hasMany(\App\Models\Voucher::class);
	}
}
