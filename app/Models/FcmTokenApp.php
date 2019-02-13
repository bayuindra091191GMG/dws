<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 13 Feb 2019 12:44:54 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class FcmTokenApp
 * 
 * @property int $id
 * @property int $user_id
 * @property string $token
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class FcmTokenApp extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'user_id',
		'token'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
