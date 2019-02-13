<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 13 Feb 2019 08:11:23 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MenuHeader
 * 
 * @property int $id
 * @property string $name
 * @property int $index
 * 
 * @property \Illuminate\Database\Eloquent\Collection $menus
 *
 * @package App\Models
 */
class MenuHeader extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'index' => 'int'
	];

	protected $fillable = [
		'name',
		'index'
	];

	public function menus()
	{
		return $this->hasMany(\App\Models\Menu::class);
	}
}
