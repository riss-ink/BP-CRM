<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Client extends Model
{
    use Searchable;
    use SoftDeletes;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = ['keywords' => 'array', 'competitors' => 'array', 'geo_targeting' => 'array', 'business_hours' => 'array', 'contacts' => 'array'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at', 'canceled_at'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function client_products()
    {
        return $this->hasMany('App\ClientProduct');
    }

    public function custom_fields()
    {
        return $this->morphToMany('App\CustomField', 'customizable');

    }

    public function logins()
    {
        return $this->hasMany('App\ClientLogin');
    }

    public function team()
    {
        return $this->belongsTo('App\Team');
    }

    public function getCustomFieldByName($name)
    {
        $clinet = $this->with('client_products.custom_fields')->where('id', $this->id)->get();
        $cfs = $clinet->pluck('client_products.*.custom_fields');
        $definition = Definition::where('name', $name)->first();
        if(!$definition){
            return false;
        }
        $cf = $cfs->flatten()->where('definition_id', $definition->id);
        if (!$cf) {
            return false;
        }
        return $cf->first()->value;
    }


}
