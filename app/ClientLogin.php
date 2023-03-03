<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;

class ClientLogin extends Model
{
    protected $fillable = ['client_id', 'type', 'name', 'url', 'username', 'password','notes'];

    public function getPasswordAttribute($value)
    {
        if(!$value){
            return $value;
        }
        try {
            $decrypted = \Crypt::decrypt($value);
        } catch (DecryptException $e) {
        }
        return $decrypted;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Crypt::encrypt($value);
    }
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
}
