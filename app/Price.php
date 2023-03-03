<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['set_up_amount','recurring_amount', 'quote', 'term_id'];
    protected $casts = ['quote' => 'boolean'];
    public function priceable()
    {
        return $this->morphTo();
    }
    public function term(){
        return $this->belongsTo('App\Term');
    }

}
