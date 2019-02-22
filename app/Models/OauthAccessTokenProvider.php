<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 22 Feb 2019 03:58:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class OauthAccessTokenProvider
 * 
 * @property string $oauth_access_token_id
 * @property string $provider
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\OauthAccessToken $oauth_access_token
 *
 * @package App\Models
 */
class OauthAccessTokenProvider extends Eloquent
{
	protected $primaryKey = 'oauth_access_token_id';
	public $incrementing = false;

	protected $fillable = [
		'provider'
	];

	public function oauth_access_token()
	{
		return $this->belongsTo(\App\Models\OauthAccessToken::class);
	}
}
