<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 07 Feb 2019 07:38:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionOnDemandTracking
 * 
 * @property int $id
 * @property int $transaction_header_id
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\TransactionHeader $transaction_header
 *
 * @package App\Models
 */
class TransactionOnDemandTracking extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'transaction_header_id' => 'int'
	];

	protected $fillable = [
		'transaction_header_id',
		'description'
	];

	public function transaction_header()
	{
		return $this->belongsTo(\App\Models\TransactionHeader::class);
	}
}
