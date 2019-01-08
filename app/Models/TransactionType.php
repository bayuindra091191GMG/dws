<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 08 Jan 2019 07:18:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionType
 * 
 * @property int $id
 * @property string $description
 * 
 * @property \Illuminate\Database\Eloquent\Collection $transaction_headers
 *
 * @package App\Models
 */
class TransactionType extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'description'
	];

	public function transaction_headers()
	{
		return $this->hasMany(\App\Models\TransactionHeader::class);
	}
}
