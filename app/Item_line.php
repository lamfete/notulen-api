<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_line extends Model
{
	protected $fillable = ['item_name'];

    public function item_head()
    {
    	return $this->belongsTo('App\Item_head');
    }
}
