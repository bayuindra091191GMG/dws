<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Dec 2018 08:41:12 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PointHistory
 * 
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property int $transaction_id
 * @property int $is_referral
 * @property float $amount
 * @property float $saldo
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class PointHistory extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'transaction_id' => 'int',
		'is_referral' => 'int',
		'amount' => 'float',
		'saldo' => 'float'
	];

	protected $fillable = [
		'user_id',
		'type',
		'transaction_id',
		'is_referral',
		'amount',
		'saldo',
		'description'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
