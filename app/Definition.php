<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Definition extends Model
{
    protected $fillable =['field_group_id', 'name', 'field_type', 'type'];
    public function clients()
    {
        return $this->morphedByMany('App\Client', 'definable');
    }

    public function product_categories()
    {
        return $this->morphedByMany('App\ProductCategory', 'definable');
    }

    public function field_group()
    {
        return $this->belongsTo('App\FieldGroup');
    }

    public function custom_fields()
    {
        $this->hasMany('App\CustomField');
    }
}
