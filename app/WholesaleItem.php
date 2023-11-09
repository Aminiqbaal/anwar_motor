<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WholesaleItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'wholesale_items';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function wholesale()
    {
        return $this->belongsTo('App\Wholesale', 'wholesale_id', 'id');
    }
}
