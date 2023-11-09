<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'spareparts';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function stocks()
    {
        return $this->hasMany('App\Stock', 'sparepart_id', 'id');
    }

    public function stock()
    {
        return $this->hasOne('App\Stock', 'sparepart_id', 'id')->where('workshop_id', \Auth::user()->workshop_id);
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id', 'id');
    }
}
