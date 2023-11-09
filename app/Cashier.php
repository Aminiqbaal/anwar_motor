<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'cashiers';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne('App\User', 'user_id', 'id');
    }
}
