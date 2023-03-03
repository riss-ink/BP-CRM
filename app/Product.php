<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =['product_category_id', 'name', 'spend', 'details'];
    protected $casts = ['spend' => 'boolean'];
    public function product_category()
    {
        return $this->belongsTo('App\ProductCategory');
    }

    public function client_product()
    {
        return $this->hasMany('App\ClientProduct');
    }
    public function prices()
    {
        return $this->morphMany('App\Price', 'priceable');
    }

    public function addons()
    {
        return $this->hasMany('App\Addon');
    }
}
