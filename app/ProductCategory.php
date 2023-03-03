<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    public function definitions()
    {
        return $this->morphToMany('App\Definition', 'definable');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function addons()
    {
        return $this->hasMany('App\Addons');
    }
}
