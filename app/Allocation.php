<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    public function user()
	{
	    return $this->belongsTo('App\User');
	}

	public function stock()
	{
	    return $this->belongsTo('App\Stock');
	}

	public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
