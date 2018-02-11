<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function allocation()
	{
	    return $this->belongsTo('App\Allocation');
	}
}
