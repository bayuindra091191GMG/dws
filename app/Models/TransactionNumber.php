<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 01 Feb 2019 09:27:19 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionNumber
 * 
 * @property string $id
 * @property int $next_no
 *
 * @package App\Models
 */
class TransactionNumber extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'next_no' => 'int'
	];

	protected $fillable = [
	    'id',
		'next_no'
	];
}
