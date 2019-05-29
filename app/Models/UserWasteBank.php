<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 07 Feb 2019 04:37:12 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class UserWasteBank
 * 
 * @property int $id
 * @property int $user_id
 * @property int $waste_bank_id
 * @property int $status_id
 * 
 * @property \App\Models\User $user
 * @property \App\Models\WasteBank $waste_bank
 * @property \App\Models\Status $status
 *
 * @package App\Models
 */
class UserWasteBank extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'waste_bank_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'waste_bank_id',
        'status_id'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function waste_bank()
	{
		return $this->belongsTo(\App\Models\WasteBank::class);
	}

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class);
    }
}
