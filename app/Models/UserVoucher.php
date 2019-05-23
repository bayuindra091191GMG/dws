<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 23 May 2019 08:25:36 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class UserVoucher
 * 
 * @property int $id
 * @property int $user_id
 * @property int $voucher_id
 * @property \Carbon\Carbon $redeem_at
 * @property string $redeem_code
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
		'voucher_id' => 'int',
		'is_used' => 'int'
	];

	protected $dates = [
		'redeem_at',
		'used_at'
	];

	protected $fillable = [
		'user_id',
		'voucher_id',
		'redeem_at',
		'redeem_code',
		'is_used',
		'used_at'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function voucher()
	{
		return $this->belongsTo(\App\Models\Voucher::class);
	}
}
