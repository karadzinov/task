<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'country',
        'gender',
        'bonus',
        'email'
    ];

    public function transaction()
    {
        return $this->belongsToMany('App\Transaction');
    }
}
