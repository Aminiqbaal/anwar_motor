<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'reports';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function sparepart()
    {
        return $this->belongsTo('App\Sparepart', 'sparepart_id', 'id');
    }

    public function workshop()
    {
        return $this->belongsTo('App\Workshop', 'workshop_id', 'id');
    }
}
