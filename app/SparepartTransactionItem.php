<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SparepartTransactionItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'sparepart_transaction_items';

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

    public function transaction()
    {
        return $this->belongsTo('App\Transaction', 'transaction_id', 'id');
    }
}
