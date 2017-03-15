<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'balance',
        'bonus',
        'currency'
    ];

    public function customer()
    {
        return $this->belongsToMany('App\Customer');
    }
}
