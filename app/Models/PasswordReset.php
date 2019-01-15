<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 15 Jan 2019 07:03:19 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PasswordReset
 * 
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Carbon\Carbon $created_at
 *
 * @package App\Models
 */
class PasswordReset extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'email',
		'token'
	];
}
