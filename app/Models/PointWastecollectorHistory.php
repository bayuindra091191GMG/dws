<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 07:36:16 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PointWastecollectorHistory
 * 
 * @property int $id
 * @property int $wastecollector_id
 * @property string $type
 * @property int $transaction_id
 * @property int $is_referral
 * @property string $type_transaction
 * @property float $amount
 * @property float $saldo
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\TransactionHeader $transaction_header
 * @property \App\Models\WasteCollector $waste_collector
 *
 * @package App\Models
 */
class PointWastecollectorHistory extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'wastecollector_id' => 'int',
		'transaction_id' => 'int',
		'is_referral' => 'int',
		'amount' => 'float',
		'saldo' => 'float'
	];

	protected $fillable = [
		'wastecollector_id',
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


    public function transaction_header()
	{
		return $this->belongsTo(\App\Models\TransactionHeader::class, 'transaction_id');
	}

	public function waste_collector()
	{
		return $this->belongsTo(\App\Models\WasteCollector::class, 'wastecollector_id');
	}
}
