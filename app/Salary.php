<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'salaries';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function transaction()
    {
        return $this->belongsTo('App\Transaction', 'transaction_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
