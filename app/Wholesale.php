<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wholesale extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'wholesales';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function items()
    {
        return $this->hasMany('App\WholesaleItem', 'wholesale_id', 'id');
    }
}
