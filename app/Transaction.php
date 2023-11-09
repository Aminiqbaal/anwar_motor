<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'transactions';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'id');
    }

    public function workshop()
    {
        return $this->belongsTo('App\Workshop', 'workshop_id', 'id');
    }

    public function spareparts()
    {
        return $this->hasMany('App\SparepartTransactionItem', 'transaction_id', 'id');
    }

    public function services()
    {
        return $this->hasMany('App\ServiceTransactionItem', 'transaction_id', 'id');
    }

    public function mechanic()
    {
        return $this->belongsTo('App\User', 'mechanic_id', 'id');
    }
}
