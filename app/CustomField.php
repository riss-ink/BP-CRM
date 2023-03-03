<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $fillable = ['definition_id', 'value'];
    public function clients()
    {
        return $this->morphedByMany('App\Client', 'customizable');
    }

    public function client_products()
    {
        return $this->morphedByMany('App\ClientProduct', 'customizable');
    }

    public function definition()
    {
        return $this->belongsTo('App\Definition');
    }

}
