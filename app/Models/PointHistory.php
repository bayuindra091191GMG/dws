<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 04 Feb 2019 05:32:35 +0000.
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
 * @property string $type_transaction
 * @property float $amount
 * @property float $saldo
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\TransactionHeader $transaction_header
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
		'type_transaction',
		'amount',
		'saldo',
		'description'
	];

    protected $appends = [
        'amount_string',
        'saldo_string'
    ];

    public function getAmountStringAttribute(){
        return number_format($this->attributes['amount'], 0, ",", ".");
    }

    public function getSaldoStringAttribute(){
        return number_format($this->attributes['saldo'], 0, ",", ".");
    }

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function transaction_header()
	{
		return $this->belongsTo(\App\Models\TransactionHeader::class, 'transaction_id');
	}
}
