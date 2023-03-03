<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldGroup extends Model
{
    protected $fillable = ['name'];
    public function definitions()
    {
        return $this->hasMany('App\Definition');
    }
}
