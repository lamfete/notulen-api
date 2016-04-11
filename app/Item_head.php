<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_head extends Model
{
	protected $fillable = ['user_id'];

    public function item_lines()
    {
    	return $this->hasMany('App\Item_line');
    }
}
