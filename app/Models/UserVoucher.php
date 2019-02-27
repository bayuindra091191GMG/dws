<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 27 Feb 2019 08:54:38 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class UserVoucher
 * 
 * @property int $id
 * @property int $user_id
 * @property int $vouchers_id
 * @property \Carbon\Carbon $redeem_at
 * @property int $is_used
 * @property \Carbon\Carbon $used_at
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Voucher $voucher
 *
 * @package App\Models
 */
class UserVoucher extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'vouchers_id' => 'int',
		'is_used' => 'int'
	];

	protected $dates = [
		'redeem_at',
		'used_at'
	];

	protected $fillable = [
		'user_id',
		'vouchers_id',
		'redeem_at',
		'is_used',
		'used_at'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function voucher()
	{
		return $this->belongsTo(\App\Models\Voucher::class, 'vouchers_id');
	}
}
