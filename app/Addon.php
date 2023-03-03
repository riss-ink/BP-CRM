<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = ['name'];
    public function prices()
    {
        return $this->morphMany('App\Price', 'priceable');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    public function product_categories()
    {
        return $this->belongsToMany('App\ProductCategory');
    }
}
