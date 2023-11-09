<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceTransactionItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'service_transaction_items';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction', 'transaction_id', 'id');
    }
}
