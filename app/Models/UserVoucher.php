<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 01 Feb 2019 04:38:18 +0000.
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
		'vouchers_id' => 'int'
	];

	protected $dates = [
		'redeem_at'
	];

	protected $fillable = [
		'user_id',
		'vouchers_id',
		'redeem_at'
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
