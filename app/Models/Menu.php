<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 13 Feb 2019 08:11:08 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Menu
 * 
 * @property int $id
 * @property int $menu_header_id
 * @property string $name
 * @property string $route
 * @property int $index
 * @property string $icon
 * 
 * @property \App\Models\MenuHeader $menu_header
 * @property \Illuminate\Database\Eloquent\Collection $menu_subs
 *
 * @package App\Models
 */
class Menu extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'menu_header_id' => 'int',
		'index' => 'int'
	];

	protected $fillable = [
		'menu_header_id',
		'name',
		'route',
		'index',
		'icon'
	];

	public function menu_header()
	{
		return $this->belongsTo(\App\Models\MenuHeader::class);
	}

	public function menu_subs()
	{
		return $this->hasMany(\App\Models\MenuSub::class);
	}
}
