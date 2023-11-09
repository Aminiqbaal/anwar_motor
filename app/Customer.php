<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'customers';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function transactions()
    {
        return $this->hasMany('App\Transaction', 'customer_id', 'id');
    }
}
