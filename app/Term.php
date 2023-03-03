<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $fillable = ['type', 'length'];
    public function prices()
    {
        return $this->hasMany('App\Price');
    }
}
